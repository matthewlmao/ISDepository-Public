<?php 

  function smartSuggest() { 

    $user_id = $_SESSION['user_id'];
    $decayLambda = 0.1; 

    $sql = "
        SELECT 
            listings.*,
            conditions.condition_name,
            listing_images.image_path AS first_image,
            COALESCE(basic_score, 0) AS basic_score,
            COALESCE(cosine_score, 0) AS cosine_score,
            COALESCE(tfidf_score, 0) AS tfidf_score,
            COALESCE(temporal_score, 0) AS temporal_score,
            COALESCE(basic_score, 0) + 
            COALESCE(cosine_score, 0) + 
            COALESCE(tfidf_score, 0) + 
            COALESCE(temporal_score, 0) AS total_score
        FROM listings
        LEFT JOIN conditions ON listings.condition_id = conditions.condition_id
        LEFT JOIN (
            SELECT listing_id, MIN(img_id) AS min_image_id 
            FROM listing_images 
            GROUP BY listing_id
        ) AS first_images ON listings.listing_id = first_images.listing_id
        LEFT JOIN listing_images ON first_images.min_image_id = listing_images.img_id
        LEFT JOIN (
            -- Basic Dot Product Score
            SELECT lt.listing_id, SUM(ui.basic_weight) AS basic_score
            FROM listing_tags lt
            INNER JOIN (
                SELECT tag_id, COUNT(*) AS basic_weight
                FROM user_interests
                WHERE user_id = ?
                GROUP BY tag_id
            ) ui ON lt.tag_id = ui.tag_id
            GROUP BY lt.listing_id
        ) basic ON listings.listing_id = basic.listing_id
        LEFT JOIN (
            -- Cosine Similarity Score
            SELECT lt.listing_id,
                  SUM(ui.weight * lt.tag_presence) / 
                  (SQRT(SUM(POW(ui.weight, 2))) * SQRT(SUM(POW(lt.tag_presence, 2)))) AS cosine_score
            FROM listing_tags lt
            INNER JOIN (
                SELECT tag_id, COUNT(*) AS weight
                FROM user_interests
                WHERE user_id = ?
                GROUP BY tag_id
            ) ui ON lt.tag_id = ui.tag_id
            GROUP BY lt.listing_id
        ) cosine ON listings.listing_id = cosine.listing_id
        LEFT JOIN (
            -- TF-IDF Score
            SELECT lt.listing_id, SUM(ui.weight * tfidf.idf) AS tfidf_score
            FROM listing_tags lt
            INNER JOIN (
                SELECT tag_id, COUNT(*) AS weight
                FROM user_interests
                WHERE user_id = ?
                GROUP BY tag_id
            ) ui ON lt.tag_id = ui.tag_id
            INNER JOIN (
                SELECT tag_id, 
                      LOG((SELECT COUNT(*) FROM listings) / COUNT(DISTINCT listing_id)) AS idf
                FROM listing_tags
                GROUP BY tag_id
            ) tfidf ON lt.tag_id = tfidf.tag_id
            GROUP BY lt.listing_id
        ) tfidf ON listings.listing_id = tfidf.listing_id
        LEFT JOIN (
            -- Temporal Decay Score
            SELECT lt.listing_id, 
                  SUM(EXP(-$decayLambda * DATEDIFF(NOW(), ui.interaction_date))) AS temporal_score
            FROM listing_tags lt
            INNER JOIN (
                SELECT tag_id, interaction_date
                FROM user_interests
                WHERE user_id = ?
            ) ui ON lt.tag_id = ui.tag_id
            GROUP BY lt.listing_id
        ) temporal ON listings.listing_id = temporal.listing_id
        ORDER BY total_score DESC, listings.created_at DESC
    ";

    $stmt_relevance = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt_relevance, "ssss", $user_id, $user_id, $user_id, $user_id); 
    mysqli_stmt_execute($stmt_relevance);
    $result = mysqli_stmt_get_result($stmt_relevance);
  }
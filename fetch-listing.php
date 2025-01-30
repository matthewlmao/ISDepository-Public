<?php 
    require_once'config.php';

    // Fetching listings with relevant details 
    $sql = "
        SELECT l.listing_id, l.title, l.description, l.price, c.condition_name, li.image_url
        FROM listings l
        JOIN conditions c ON l.condition_id = c.condition_id
        LEFT JOIN listing_images li ON l.listing_id = li.listing_id
        WHERE l.is_active = 1
    "; 

    $result = mysqli_query($link, $sql);

    $listings = []; 
    if($result) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $listings[] = $row; 
        }
    }

    // Close connection... 
    mysqli_close($link);

    // Out put listings in form of json 
    header('Content-Type: application/json'); 
    echo json_encode($listings); 
?> 
<?php
  header('Content-Type: application/json');
  
  require_once '../../config.php'; 

  $query = "SELECT 
                l.listing_id,
                l.title,
                l.description,
                l.price,
                l.created_at,
                c.condition_name,
                GROUP_CONCAT(t.tag_name) AS tags
            FROM listings l
            JOIN conditions c ON l.condition_id = c.condition_id
            LEFT JOIN listing_tags lt ON l.listing_id = lt.listing_id
            LEFT JOIN tags t ON lt.tag_id = t.tag_id
            GROUP BY l.listing_id";

  $result = $pdo->query($query);
  $data = $result->fetchAll(PDO::FETCH_ASSOC);

  // Tags to array 
  // Search through text 
  foreach ($data as &$item) {
      $item['tags'] = $item['tags'] ? explode(',', $item['tags']) : [];
      $item['search_text'] = implode(' ', [
          $item['title'],
          $item['description'],
          $item['condition_name'],
          implode(' ', $item['tags'])
      ]);
  }

  echo json_encode($data);
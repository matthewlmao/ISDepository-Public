<?php

  require_once '../config.php'; 

    // Check that a session is set before showing the page 
    session_start(); 

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: /ISDepository/sign-in"); 
      exit; 
    } else {
      // Retrieve user id based on username (replace 'username' with your actual field name) 
      $sql = "SELECT `user_id` FROM users WHERE email = ?";
      $stmt = mysqli_prepare($connection, $sql);
      mysqli_stmt_bind_param($stmt, "s", $_SESSION["email"]); // Bind username to prevent injection
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
    
      // Use user id as seller id 
      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $seller_id = $row["user_id"]; // 
      } else {
          echo "Error: User details not found.";
        exit;
      }
    }
    
    // Initialising variables 
    $title = $img = $desc = $price = $condition = $createdAt = ""; 
    $title_err = $desc_err = $price_err = $condition_err = $createdAt_err = "";
    $tags = array(); 
    $tags_err = $img_err = array(); // Errors initialised as arrays because they receive multiple inputs in the form of an array 

    // For the conditions dropdown menu 
    $sql = "SELECT * FROM conditions ORDER BY `conditions`.`condition_id` ASC";
    $conditions_result = $connection->query($sql);

    // For the tags dropdown menu 
    $sql = "SELECT * FROM tags ORDER BY `tags`.`tag_id` ASC";
    $tags_result = $connection->query($sql); 
  
    if (isset($_POST["listing_id"]) && !empty($_POST["listing_id"])) { 

      // Validation checks 
      
      // Title 
      $input_title = trim($_POST["title"]); 
      if (empty($input_title)) { 
        $title_err = "Please enter a title for your listing!";
      } else {
          $title = $input_title;
      }

      // Images
/*       $uploaded_images = array();
      if (isset($_FILES["img"]) && !empty($_FILES["img"]['tmp_name'][0])) { // Check if the upload field is empty. This conditional check ensures that the array is treated as one even with inputs of one or none. 
        if (is_array($_FILES["img"]["tmp_name"])) {
          $num_of_imgs = count($_FILES["img"]["tmp_name"]);
        } else {
            $num_of_imgs = 1;
            $_FILES["img"]["name"] = [$_FILES["img"]["name"]];
            $_FILES["img"]["type"] = [$_FILES["img"]["type"]];
            $_FILES["img"]["tmp_name"] = [$_FILES["img"]["tmp_name"]];
            $_FILES["img"]["error"] = [$_FILES["img"]["error"]];
            $_FILES["img"]["size"] = [$_FILES["img"]["size"]];
        }

        // Allow for single and multiple image uploads
    
        for ($i = 0; $i < $num_of_imgs; $i++) {
          if (!empty($_FILES["img"]["tmp_name"][$i])) {
              $input_img = [
                  'name' => $_FILES["img"]["name"][$i],
                  'type' => $_FILES["img"]["type"][$i],
                  'tmp_name' => $_FILES["img"]["tmp_name"][$i],
                  'error' => $_FILES["img"]["error"][$i],
                  'size' => $_FILES["img"]["size"][$i]
              ];

              $allowed_img_types = ['image/jpeg', 'image/png']; 
              // Limit maximum file size to 5mb. Integrate compression algorithm in the future. 
              $max_img_size = 5 * 1024 * 1024; 
              // Get file extension to validate the uploaded image's type
              $file_extension = strtolower(pathinfo($input_img['name'], PATHINFO_EXTENSION));
              $allowed_extensions = ['jpeg', 'jpg', 'png'];
      
              // Validation checks
              if(!in_array($input_img['type'], $allowed_img_types)) { 
                  $img_err[$i] = "Only JPEG and PNG images are allowed! (Image " . ($i+1) . ")"; 
              } elseif(!in_array($file_extension, $allowed_extensions)) {
                  $img_err[$i] = "Invalid file extension! (Image " . ($i+1) . ")";
              } elseif($input_img['size'] > $max_img_size) { 
                // Compressing 
                    $image = null;
                    if($input_img['type'] == 'image/jpeg') {
                      $image = imagecreatefromjpeg($input_img['tmp_name']);
                    } elseif ($input_img['type'] == 'image/png'){
                      $image = imagecreatefrompng($input_img['tmp_name']);
                    } elseif ($input_img['type'] == 'img/jpg') { 
                    
                    if($image) {
                      $width = imagesx($image);
                      $height = imagesy($image);

                      // Calculate compression ratio
                      $compression_ratio = sqrt($max_img_size / $input_img['size']);
                      $new_width = $width * $compression_ratio;
                      $new_height = $height * $compression_ratio;

                      // Create a new temporary image
                      $temp_image = imagecreatetruecolor($new_width, $new_height);

                      // Preserve transparency for PNG
                      if($input_img['type'] == 'image/png') {
                          imagealphablending($temp_image, false);
                          imagesavealpha($temp_image, true);
                          $transparent = imagecolorallocatealpha($temp_image, 255, 255, 255, 127);
                          imagefilledrectangle($temp_image, 0, 0, $new_width, $new_height, $transparent);
                      }
                      
                      // Resize the image
                      imagecopyresampled($temp_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                      // Generate unique filename. This prevents files from being overwritten by new files uploaded of the same name. 
                      $unique_filename = uniqid() . '_' . basename($input_img['name']);

                      // Check if uploads directory exists, create if it doesn't
                      if(!is_dir('uploads/')) {
                          mkdir('uploads/', 0755, true);
                      }

                      $img_destination = 'uploads/' . $unique_filename; 
                      
                      // Save the image
                      if($img_type == 'image/jpeg') {
                          imagejpeg($temp_image, $img_destination, 80); // 80% quality
                      } elseif($img_type == 'image/png'){
                          imagepng($temp_image, $img_destination, 9); // Compression level for PNG
                      }

                      // Clean up the temporary image 
                      imagedestroy($image);
                      imagedestroy($temp_image);

                      
                      // Move uploaded file 
                      if(rename($input_img['tmp_name'], $img_destination)){
                        $uploaded_images[] = $unique_filename;
                      } else{
                        $img_err[$i] = "File upload failed! (Image " . ($i + 1) . ")";
                        // Skip to the next image if this one fails 
                      }
                    } else{
                        $img_err[$i] = "Failed to process image! (Image " . ($i+1) . ")";
                    }
                } else {
                    // Move uploaded file (for images within the size limit)
                    $unique_filename = uniqid() . '_' . basename($input_img['name']);
                    $img_destination = 'uploads/' . $unique_filename;

                    if(move_uploaded_file($input_img['tmp_name'], $img_destination)) {
                      $uploaded_images[] = $unique_filename; 
                    } else {
                        $img_err[$i] = "File upload failed! (Image " . ($i+1) . ")";
                    }
                }

            } else {
              if($i == 0){ // Requires at least 1 image to be uploaded
                $img_err[$i] = "Please upload at least one image for your listing! (Image " . ($i+1) . ")";
              }
            }
          }
        } */
      

      // Description 
      $input_desc = trim($_POST["desc"]); 
      if (empty($input_desc)) { 
        $desc_err = "Please enter a description for your listing!"; 
      } else { 
        $desc = $input_desc; 
      }

      // Price 
      $input_price = trim($_POST["price"]); 
      if (empty($input_price)) { 
        $price_err = "Please enter a price for your listing!"; 
      } elseif (!ctype_digit($input_price)){ 
          $price_err = "Please enter a positive integer for your price!"; 
      } else { 
          $price = $input_price; 
      } 

      // Condition 
      $input_condition = $_POST["condition"]; 
      if (empty($input_condition)) { 
        $condition_err = "Please enter a condition for your listing!"; 
      } else {  
          $condition = $input_condition; 
      }

      // Tags
      $input_tags = $_POST["tags"]; 
        if (empty($input_tags)) {
            $tags_err = "Please enter at least one tag for your listing!";
        } else {
            $tags = array_map('intval', $input_tags);  
            $tags = array_unique($tags); // Remove duplicate entries
        }


      // If there are no input errors, insert into database 
      if (empty($title_err) && empty($img_err) && empty($desc_err) && empty($price_err) && empty($condition_err)){ 

        $sql = "UPDATE listings SET seller_id = ?, title = ?, description = ?, price = ?, condition_id = ? WHERE listing_id = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) { 
          mysqli_stmt_bind_param($stmt, "sssii", $param_seller_id, $param_title, $param_desc, $param_price, $param_condition);

          $param_seller_id = $seller_id;
          $param_title = $title; 
          $param_desc = $desc; 
          $param_price = $price; 
          $param_condition = $condition; 

          if (mysqli_stmt_execute($stmt)) { 
            // Get UUID by selecting the single most recent listing. Unfortunately SELECT LAST_INSERT_ID() does not work with UUIDs :( 
            $listing_id_result = mysqli_query($connection, "SELECT listing_id FROM listings ORDER BY created_at DESC LIMIT 1"); 
            // Note for extensibility: Although highly unlikely to happen, there might be a chance that listings will be created in the same second. 
            $listing_id_row = mysqli_fetch_row($listing_id_result);
            $listing_id = $listing_id_row[0];

            /* foreach($uploaded_images as $image_filename) {
              $sql_img = "INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)";
              if($stmt_img = mysqli_prepare($connection, $sql_img)) {
                  mysqli_stmt_bind_param($stmt_img, "is", $listing_id, $image_filename);
                  if(!mysqli_stmt_execute($stmt_img)) {
                      echo "Oops! Something went wrong with image upload. Please try again later. (Error inserting image: " . $image_filename . ")";
                  }
                  mysqli_stmt_close($stmt_img);
              } else {
                echo "Error: Could not prepare image query.";
              }
            } */

            // Loop through selected tags and insert all of them into listing_tags
            foreach ($_POST["tags"] as $tag_id) {
              $sql_tag = "INSERT INTO listing_tags (listing_id, tag_id) VALUES (?, ?)";
              if ($stmt_tag = mysqli_prepare($connection, $sql_tag)) {
                mysqli_stmt_bind_param($stmt_tag, "si", $listing_id, $tag_id);
                if (!mysqli_stmt_execute($stmt_tag)) {
                  echo "Error inserting tag: " . $tag_id;
                }
                mysqli_stmt_close($stmt_tag);
              } else {
                echo "Error: Could not prepare tag query.";
              }
            

          // Redirect on success 
            header("location: /ISDepository/my-listings"); 
            exit(); 
          }
          } else { 
            echo "Oops! Something went wrong. Please try again later."; 
          }
        }

        mysqli_stmt_close($stmt); 
      }

      mysqli_close($connection); 

    } else {
      // Check existence of id parameter before processing further 
      if (isset($_GET["listing_id"]) && !empty(trim($_GET["listing_id"]))){ 
        // Get URL parameter 
        $listing_id =  trim($_GET["listing_id"]); 

        // Prepare a select statement 
        $sql = "SELECT 
                  listings.*,
                  GROUP_CONCAT(tags.tag_id) AS tag_ids 
                FROM 
                  listings
                LEFT JOIN 
                  listing_tags ON listings.listing_id = listing_tags.listing_id
                LEFT JOIN 
                  tags ON listing_tags.tag_id = tags.tag_id 
                WHERE 
                  listings.listing_id = ?
                GROUP BY 
                  listings.listing_id
                ";


        if ($stmt = mysqli_prepare($connection, $sql)){ 
           
          mysqli_stmt_bind_param($stmt, "s", $param_listing_id); 
           
          $param_listing_id = $listing_id; 
           
          if (mysqli_stmt_execute($stmt)) { 
            $result = mysqli_stmt_get_result($stmt); 

            if (mysqli_num_rows($result) == 1){ 
              $row = mysqli_fetch_array($result, MYSQLI_ASSOC); 
              $title = $row["title"]; 
              $desc = $row["description"]; 
              $price = $row["price"]; 
              $condition = $row["condition_id"]; 
              $tags = array_column($result->fetch_all(MYSQLI_ASSOC), 'tag_id');
            } else { 
              // URL doesn't contain valid id. Redirect to homepage with error message  
              $_SESSION['error'] = "Listing not found.";
              header("location: /ISDepository/"); 
              exit(); 
            } 
          } else {
            echo "Oops! Something went wrong. Please try again later."; 
          }
        }

        mysqli_stmt_close($stmt); 

      } else { 
        // URL doesn't contain id parameter. Redirect to error page 
        header("location: /ISDepository/error"); 
        exit(); 
      }
  }
  

?>
 
<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>ISDepository · Edit Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/ISDepository/assets/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <script src="/ISDepository/assets/dist/js/bootstrap.min.js"></script>
    <script src="/ISDepository/assets/dist/js/bootstrap.bundle.min.js" ></script>
    <script src="https://unpkg.com/@morbidick/bootstrap@latest/dist/elements.bundled.min.js"></script>
    <script src="/ISDepository/components/header-component/header-component.js"></script>
    <link rel="stylesheet" href="/ISDepository/style.css"> 
    <link rel="stylesheet" href="create-listing.css"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  </head> 

  <body>

    <header-component></header-component>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Edit Listing</h2>
                    <p style="font-style: italic">Please edit in the details below for your listing!</p>

                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST" enctype="multipart/form-data">

                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>" placeholder="Enter a title for your listing...">
                            <span class="invalid-feedback"><?php echo $title_err;?></span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Images</label>
                            <input type="file" name="img" id="img[]" accept=".jpg,.jpeg,.png" class="form-control <?php echo (!empty($img_err)) ? 'is-invalid' : ''; ?>" multiple> 
                            <?php if (!empty($img_err)) : ?>
                                <div class="invalid-feedback">
                                    <?php foreach ($img_err as $error) : ?>
                                        <p><?php echo $error; ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="desc" id="desc" class="form-control <?php echo (!empty($desc_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter a detailed description for your listing..."><?php echo $desc; ?></textarea>
                            <span class="invalid-feedback"><?php echo $desc_err;?></span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Price</label>
                            <input type="text" name="price" id="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>" placeholder="Enter a price...">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>
                        <!-- Script to prevent users from inputting anything other than numbers in the price field. This prevents users from having to re-enter valid data after attempting to submit invalid data --> 
                        <script>
                          const priceInput = document.getElementById('price');

                          priceInput.addEventListener('keydown', function(event) {
                            if (event.key !== 'Backspace' && event.key !== 'Delete' && !event.key.match(/[0-9]/)) {
                              event.preventDefault();
                            }
                          });
                        </script>

                        <div class="form-group">
                          <!-- Dynamic dropdown menu which allows users to select from a fixed list of item conditions. This will simplify both the uploading and moderating processes.--> 
                          <label class="form-label">Item Condition</label>
                          <select name="condition" id="condition" class="form-select">
                            <?php
                            if ($conditions_result->num_rows > 0) {
                                // Output data of each row
                                while($row = $conditions_result->fetch_assoc()) { 
                                  // Users choose from readable conditions, but the id of their chosen condition is what's posted to the processing algorithm. 
                                    echo "<option value='" . $row["condition_id"] . "'>" . $row["condition_name"] . "</option>"; 
                                }
                            } else {
                                echo "<option value=''>No conditions available</option>";
                            }
                            ?>
                          </select>
                        </div>

                        <div class="form-group"> 
                        <!-- Search form with AJAX that allows users to type an appropriate tag, then search from pre-determined tags that match their input. -->
                        <!-- This makes it easier for users to find appropriate tags, and is easier than scrolling through a long list. -->
                        <!-- However, the fact that they are choosing from a fixed list ensures that the tags are appropriate, valid, and corresponds to the recommendation algorithm. --> 
                        <!-- The tags are stored in a separate table, and the listing_tags table is used to associate tags with listings. --> 
                        <label class="form-label">Tags</label>
                        <select name="tags[]" multiple="multiple" id="tags" class="js-example-basic-multiple" style="width:100%"> 
                            <?php
                            if ($tags_result->num_rows > 0) {
                                // Output data of each row
                                while($row = $tags_result->fetch_assoc()) { 
                                    echo "<option value='" . $row["tag_id"] . "'>" . $row["tag_name"] . "</option>"; // Associate id with name to only show name
                                }
                            } else {
                                echo "<option value=''>No tags available</option>";
                            }

                            while($row = $tags_result->fetch_assoc()) { 
                              $selected = in_array($row['tag_id'], $tags) ? 'selected' : '';
                              echo "<option value='{$row['tag_id']}' $selected>{$row['tag_name']}</option>";
                            }
                            ?>
                          </select>
                          <span class="invalid-feedback"><?php echo $tags_err;?></span>
                        </div>

                        <input class="btn btn-primary" type="submit" value="Submit">
                        <?php
                        echo '<a href="/ISDepository/edit-listing/delete-listing.php?listing_id=' . $listing_id . '" class="btn btn-primary" data-toggle="tooltip">Delete</a>';
                        ?>

                        <a href="/ISDepository/index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>

  </body>
  <script>
    $(document).ready(function() {
      $('.js-example-basic-multiple').select2({
      placeholder: "Please enter relevant tags...",
      }); 
      })
  </script>
  
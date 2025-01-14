<?php
    require_once("db_connection.php");
    include("validateRequests.php");

    // Connection to db
    $pdo = connect();

    session_start();
    // Validate user is logged in (should always be true, but good to check)
    if(isset($_SESSION["active_user_id"])) {
        $active_user_id = $_SESSION["active_user_id"];
    } else {
        die("Error: User is not logged in");
    }

    // Validate contents from post request
    if(validatePostRequest($_POST,$_SERVER)) {
        $blog_title = $_POST["post_title"];
        $blog_contents = $_POST["blog_contents"];
        //TODO:  For now only sends one category_id val, allow more then one
        $catagories = $_POST["categories"];
        // Extract profile image file from user page
        if(!empty($_FILES)) {
        //Retrieve profile img contents
        $cover_img = $_FILES["cover_img"]["tmp_name"];
        $img_path = $_FILES["cover_img"]['name'];
        $imageFileType = strtolower(pathinfo($img_path,PATHINFO_EXTENSION));
        //Get image blob
        $image_blob = file_get_contents($cover_img);

        // Validate img contents
        if($_FILES["cover_img"]["size"] < 8000000 && ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "gif") ) {
            $uploadOk = 1;
     } else {
        die("Error: Image is too large or image is invalid type");
     }

    } else {
        // If image is not upload then set profile image to default
        $image_blob = null;
        $imageFileType = null;
    }

        $like_count = 0;
    } else {
        die();
    }

    // INSERT into Blogs
    $sql = "INSERT INTO Blogs (user_id,blog_title,blog_createdAt,blog_modifiedAt,blog_img,blog_img_type,blog_contents,like_count) VALUES (?,?,NOW(),NOW(),?,?,?,?)";

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute([$active_user_id,$blog_title,$image_blob,$imageFileType,$blog_contents,$like_count]);

    // //Get new blog ID
    $new_blog_id = $pdo->lastInsertId();

    // //TODO: Allow users to add category tags to a blog post

    foreach($catagories as $cat => $cat_id) {
        $sql = "INSERT INTO blogCategory VALUES (?,?)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$cat_id,$new_blog_id]);
    }



    //Redirect
   header("Location: ../index.php");
?>

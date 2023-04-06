<!doctype html>
<html class="no-js" lang="">
<?php
include('php/like_handler.php');
include('php/blogpost_handler.php');
?>
<head>
    <meta charset="utf-8">
    <title>VOCO Blog - Profile</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/profile.css">
    <script type="text/javascript" src="js/table_handler.js"></script>


</head>
<body>
<?php
include('php/header.php');
if(!(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true)){
    header("Location: register.html");
    exit();
}
?>
<!--TODO: Update 3 column layout to be prettier:
 Col 1: blog posts - view list of posted blogs, ability to view/edit/delete
 Col 2: Account Information - View account info, ability to edit account profile / delete account
 Col 3: Saved posts - Ability to access saved posts / remove saved posts
 -->

<div class="column">
    <div class="card">
        <h2>Your Posts</h2>
        <?php
        $blogs = get_user_posts($conn, $user_id);
        foreach ($blogs as $blog){
            echo "<div class='post-entry'><div class='post-preview'>";
            echo "<h3 style='float:left;'>".$blog['blog_title']."</h3><h3 style='float:right;'>Likes: ".$blog['like_count']."</h3>";
            echo "</div><div class='entry-buttons'>";
            echo "<a href='post.php?blog_id=".$blog['blog_id']."'><button>View</button></a>";
            echo "<a href='create.php?blog_id=".$blog['blog_id']."'><button>Edit</button></a>";
            // TODO: Add a "are you sure" confirmation?
            echo "<a href='php/delete_blog.php?blog_id=".$blog['blog_id']."'><button>Delete</button></a>";
            echo "</div></div>";
        }
        ?>
    </div>
    <div class="card" id="your-profile">
        <h2>Your Profile</h2>
        <?php
            $user = get_user($conn,$user_id);
        ?>
        <div class="profile-contents">

         <?php echo "<figure><img class=\"profile-pic\" src=\"data:image/".$user["profile_picture_type"].";base64,".base64_encode($user["profile_picture"])."\" height=\"100%\" width=\"100%\" /></figure>"; ?>;

            <p>First Name: <?php echo $user['first_name']?> <br> Last Name: <?php echo $user['last_name']?></p>
            <p>Email: <?php echo $user['email'] ?></p>

        </div>
        <div id="profile-buttons">
            <a href='php/delete_user.php?user_id=<?php echo $_SESSION['active_user_id']?>'><button>Delete Account</button></a>
            <!--TODO: implement this-->
            <button>Edit Profile</button>
        </div>

    </div>
    <div class="card">
        <div id="sidenav">
            <a id="likedposts">Liked Posts</a>
            <a id="usercomments">User Comments</a>
        </div>
    <div id="table"></div>
        <script>
            document.getElementById("likedposts").addEventListener("click", function () {

                userTableRequest("php/profile_handler.php","likedposts");

            })
            document.getElementById("usercomments").addEventListener("click", function() {

                userTableRequest("php/profile_handler.php","usercomments");
            });

        </script>
    </div>
</div>
<footer>

</footer>
</body>

</html>

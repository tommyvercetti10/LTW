<?php 
require_once(__DIR__ .'/itemPreview.php');
require_once(__DIR__ .'/../database/connection.db.php');
function wishlist() {?>
    <link rel = "stylesheet" type = "text/css" href = "/src/css/wishlist.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400&display=swap" rel="stylesheet">
    <section class="container">
        <h1>Wishlist</h1>
        <div class="itempreview">
            <?php
                $session = Session::getInstance();
                $user = $session->get("user");
                $userId = $user->id ?? "";
                $db = connectToDatabase();
                $posts = Post::getLikedPosts($db, $userId);
                if(count($posts) === 0) {?>
                    <h4>There are no items in your wishlist</h4> 
                <?php } 
                else {
                    foreach($posts as $post) {
                        itemPreview($post, true, false);
                    } 
                }       
            ?>
        </div>
    </section>
<?php }
?>

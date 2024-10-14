<?php
    require_once("../templates/editItem.php");
    require_once("../database/connection.db.php");
    require_once("../database/post.class.php");

    $post_id = $_GET['postId'];

    $db = connectToDatabase();
    $post = Post::getPost($db, $post_id);

    editItem($post);
?>
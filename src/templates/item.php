<?php 
require_once(__DIR__ . "/../database/post.class.php");
require_once(__DIR__ . "/../database/user.class.php");
require_once(__DIR__ . "/../database/comment.class.php");
require_once(__DIR__ . "/../database/connection.db.php");
require_once(__DIR__ . "/comment.php");
require_once(__DIR__ . "/../utils/session.php");

function item() { 
    $db = connectToDatabase();
    $postId = htmlspecialchars($_GET['postId']);
    $post = Post::getPost($db, $postId);
    $session = Session::getInstance();
    $currentUser = $session->get('user');
    $token = $session->getCsrfToken();
    if($post == null) {
        echo "Post not found";
        return;
    }

    $userId = $post->userId;
    $user = User::getUserById($db, $userId);

    // get all comments
    $comments = Comment::getCommentsFromPost($db, $postId);
    
    ?>
    
    <link rel="stylesheet" type="text/css" href="/src/css/itemPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="../javascript/carousel.js" defer></script>
    <script src="../javascript/comments.js" defer></script>
    <section class="item-section">
    <div class="image-container">
        <div class="user-info">
            <a href="../pages/profilePage.php?user_id=<?php echo $user->id; ?>" class="user">
                <img src="<?= $user->getPhoto() ?>" alt="user photo">
                <span><?php echo $user->name ?></span>
            </a>
        </div>
        <div class="carousel-container">
            <?php foreach($post->getPhotos() as $photo) {
                error_log($photo);
                if($photo != null && file_exists($photo)) { ?>
                    <img src="<?= $photo ?>" alt="item photo" class="carousel-image">
            <?php }
            } 
            
            if(count($post->getPhotos()) > 1) {?>
                <a onclick="previousImage()" class="left-arrow"><i class="fa fa-chevron-left"></i></a>
                <a onclick="nextImage()" class="right-arrow"><i class="fa fa-chevron-right"></i></a>
            <?php }?>
        </div>
        </div>
        <div class="item-info">
            <h1><?php echo $post->name ?></h1>
            <h3><?php echo $post->description ?></h3> 
            <h2 class="price"><?php echo $post->price ?> €</h2> 
            <?php if($currentUser !== null && $currentUser->id !== $userId) {?>
                <form action="/src/actions/action_post.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="hidden" name="postId" value="<?php echo $post->id ?>">
                    <button type="submit" name="cart" class="cart">Add to Cart</button>
                </form>
            <?php }?>
        </div>
    </section>
    <?php if($currentUser !== null) {?>
        <form method="post" class="add-comment-form" id="addCommentForm">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="post" value="<?= $post->id; ?>">
            <textarea name="text" class="comment-textarea" placeholder="Ask something to the buyer"></textarea>
            <input type="hidden" name="repliedTo" value="">
            <button type="submit" class="save-button">Send Comment</button>
        </form>
    <?php } ?>
    <section class="comments">
        <?php foreach ($comments as $comment) {
            comment($comment);
        } ?>
    </section>

    <?php
}
?>

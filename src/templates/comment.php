<?php 
require_once("../utils/date.php");
function comment(Comment $comment) {
    $db = connectToDatabase();
    $author = User::getUserById($db, $comment->by);
    $session = Session::getInstance();
    $currentUser = $session->get('user');
    $token = $session->getCsrfToken();
    // get comment replies
    $replies = Comment::getReplies($db, $comment->id);
    ?>

    <link rel="stylesheet" type="text/css" href="/src/css/comment.css">
    <script src="../javascript/comments.js" defer></script>

    <section class="comment-section" data-id="<?= $comment->id ?>">
        <div class="comment-info">
            <div class="user">
                <img src="<?= $author->getPhoto() ?>" alt="author photo">
                <h3> <?= $author->name ?> </h3>
            </div>
            
            <h5> <?= convertDateTimeToString($comment->timestamp) ?> </h5>
        </div>

        <h3> <?= $comment->text ?> </h3>

        <?php 
            if($currentUser !== null) { ?>
                 <button id="replyButton-<?= $comment->id ?>" class="reply-button" onclick="toggleReplyForm('<?= $comment->id ?>')">Reply</button>

                <form method="post" class="add-comment-form reply-form" id="replyForm-<?= $comment->id ?>" style="display: none;">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="hidden" name="post" value="<?= $comment->post; ?>">
                    <textarea name="text" class="comment-textarea"></textarea>
                    <input type="hidden" name="repliedTo" value="<?= $comment->id; ?>">
                    <button type="submit" class="save-button">Send Comment</button>
                </form>
        <?php } ?>
    
       

        <!-- build replies -->
        <div class="replies">
            <?php
            foreach ($replies as $reply) {
                comment($reply);
            }
            ?>
        </div>
    </section>
<?php } ?>

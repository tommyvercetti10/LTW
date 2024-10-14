<?php
require_once('../utils/session.php'); 
require_once('../database/connection.db.php');
require_once('../database/comment.class.php');
require_once('../database/user.class.php');

$session = Session::getInstance(); 
$db = connectToDatabase();

$user = $session->get('user');
$text = htmlspecialchars($_POST['text']);
$post = htmlspecialchars($_POST['post']);
$repliedTo = htmlspecialchars($_POST['repliedTo']);
$comment = new Comment($user->id, $post, $text, $repliedTo);

$csrfToken = $_POST['token'] ?? null;

if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
    $session->addMessage('error', 'Invalid CSRF token');
    echo json_encode(['error' => 'Invalid CSRF token']);
    exit();
}

if ($comment->postComment($db)) {
    $newCommentId = $db->lastInsertId(); 
    $author = User::getUserById($db, $comment->by);
    echo json_encode([
        'id' => $newCommentId,
        'authorPhoto' => $author->getPhoto(),
        'authorName' => $author->name,
        'timestamp' => $comment->timestamp,
        'repliedTo' => $comment->repliedTo
    ]);
    $session->addMessage('success', 'Comment posted successfully');
} else {
    $session->addMessage('error', "Couldn't post comment");
    echo json_encode(['error' => "Couldn't post comment"]);
}
exit();
?>

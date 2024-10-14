<?php
require_once('../database/connection.db.php');
require_once('../database/post.class.php');
require_once('../database/user.class.php');
require_once('../utils/session.php');

$session = Session::getInstance();
$db = connectToDatabase();

try {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : null;
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : null;
    $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : null;
    $postId = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;
    $csrfToken = $_POST['token'] ?? null;

    if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
        $session->addMessage('error', 'Invalid CSRF token');
        header('Location: ../../index.php'); 
        exit();
    }

    $post = Post::getPost($db, $postId);
    if (!$post) {
        throw new Exception('Post not found');
    }

    $post->name = $name;
    $post->description = $description;
    $post->price = $price;

    if ($post->updatePost($db)) {
        $session->addMessage('success', 'Updated item successfully');
        echo json_encode(['status' => 'success', 'message' => 'Updated item successfully']);
    } else {
        throw new Exception('Error while updating item');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

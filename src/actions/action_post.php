<?php
    require_once(__DIR__ . "/../utils/session.php");
    require_once(__DIR__ . "/../database/post.class.php");
    require_once(__DIR__ . "/../database/connection.db.php");
    require_once(__DIR__ . "/../database/user.class.php");
    
    $session = Session::getInstance();
    $db = connectToDatabase();
    $user = $session->get("user");
    $csrfToken = $_POST['token'] ?? null;

    if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
        error_log("HELLO");
        $session->addMessage('error', 'Invalid CSRF token');
        header('Location: ../../index.php'); 
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete'])) {
            if(Post::deletePostById($db, $_POST['postId'])) {
                $session->addMessage('success', 'Post deleted successfully');
            } else {
                $session->addMessage('error','Error deleting post');
            }
        
        } elseif (isset($_POST['remove_wishlist'])) {
            if(Post::deleteWishlistPostById($db, $_POST['postId'], $user->id)) { 
                $session->addMessage('success', 'Post deleted successfully');
            } else {
                $session->addMessage('error','Error deleting post');
            }
        
        } elseif (isset($_POST['remove_cart'])) {
            if(Post::deleteCartPostById($db, $_POST['postId'], $user->id)) { 
                $session->addMessage('success', 'Post deleted successfully');
            } else {
                $session->addMessage('error','Error deleting post');
            }
            
        } elseif (isset($_POST['watch'])) {
            if(Post::likePostById($db, $_POST['postId'], $_POST['watch'])) {
                $session->addMessage('success', 'Post liked successfully');
            };
        } elseif (isset($_POST['cart'])) {
            if(Post::shopCartById($db, $_POST['postId'], $user->id)) {
                $session->addMessage('success', 'Post liked successfully');
            };
        }
        if (!headers_sent() && ($session->get('last_page') !== null)) {
            header('Location: ' . $session->get('last_page'));
        }
        else {
            header('Location: /');
        }
        exit();
    }
    
    
    
?>
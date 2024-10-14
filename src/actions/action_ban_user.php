<?php
    require_once('../database/connection.db.php');
    require_once('../database/user.class.php');
    require_once('../utils/session.php');
    require_once('../database/post.class.php');

    $session = Session::getInstance();


    $db = connectToDatabase();
    $user = $session->get('user');
    
    $csrfToken = $_POST['token'] ?? null;

    if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
        $session->addMessage('error', 'Invalid CSRF token');
        header('Location: ../../index.php'); 
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_POST['user_id'];
        if(User::banUser($db, $user_id)) {
            $session->addMessage('success', 'User promoted successfully');
        } else {
            $session->addMessage('error','Error promoting user');
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
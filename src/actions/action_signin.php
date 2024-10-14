<?php
    require_once('../database/connection.db.php');
    require_once('../database/user.class.php');
    require_once('../utils/session.php');

    $session = Session::getInstance();

    $db = connectToDatabase();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['token'] ?? null;

    if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
        $session->addMessage('error', 'Invalid CSRF token');
        header('Location: ../../index.php'); 
        exit();
    }

    $user = User::getUser($db, $email, $password);
    
    if ($user != null) {
        $session->set('user', $user);
        $session->addMessage('sucess', 'Login successful');
        header('Location: ../../index.php');
        exit();
    } else {
        $session->addMessage('loginerror', 'Wrong email or password');
        header('Location: ../pages/signinPage.php');
    }
    
?>
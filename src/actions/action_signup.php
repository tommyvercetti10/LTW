<?php
    require_once('../database/connection.db.php');
    require_once('../database/user.class.php');
    require_once('../utils/session.php'); 

    $session = Session::getInstance(); 

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];
    $csrfToken = $_POST['token'] ?? null;

    if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
        $session->addMessage('error', 'Invalid CSRF token');
        header('Location: ../../index.php'); 
        exit();
    }

    if (!$email || !$name || empty($password)) {
        $session->addMessage('error', 'Please fill all required fields correctly.');
        exit(); 
    }

    $user = new User($email, $name, $password);
    $db = connectToDatabase();

    if ($user->saveUser($db)) {
        $session->set('user', $user);
        $session->addMessage('success', 'Sign up successful');
        header('Location: ../../index.php');
        exit();
        
    } else {
        $session->addMessage('signuperror', 'Insert a valid email and password.');
        header('Location: ../pages/signupPage.php');
        exit();
    }
    
    
?>
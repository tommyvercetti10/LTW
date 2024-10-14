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
        $category = trim($_POST['category']);
        $condition = trim($_POST['condition']);
        $category = strtolower($category);
        $condition = strtolower($condition);
        $success = true;
        if(!empty($category)) {
            try{
                Post::addCategory($db, $category);
            }
            catch(Exception $e){
                $success = false;
            }
            

        }
        
        if(!empty($condition)) {
            try{
                Post::addCondition($db, $condition);
            } catch(Exception $e){
                $success = false;
            }

            $success ? header('Location: ../pages/profile.php') : header('Location: ../pages/adminChangesPage.php');
        }
        
    }
?>
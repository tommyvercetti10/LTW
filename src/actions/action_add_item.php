<?php
require_once('../database/connection.db.php');
require_once('../database/post.class.php');
require_once('../database/user.class.php');
require_once('../utils/session.php'); 

$session = Session::getInstance(); 
$db = connectToDatabase();

$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);
$price = htmlspecialchars($_POST['price']);
$categories = isset($_POST['categories']) ? array_map('htmlspecialchars', $_POST['categories']) : [];
$condition = htmlspecialchars($_POST['condition']);
$uploadedPhotos = [];

$csrfToken = $_POST['token'] ?? null;

if (!$csrfToken || !$session->validateCsrfToken($csrfToken)) {
    $session->addMessage('error', 'Invalid CSRF token');
    header('Location: ../../index.php'); 
    exit();
}

if (!empty($_FILES['photos']['name'][0])) {
    $photos = $_FILES['photos'];
    $uploadDirectory = __DIR__ . '/../../uploads/items/';

    
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    for ($i = 0; $i < count($photos['name']); $i++) {
        $fileName = uniqid() . '-' . basename(str_replace(',', '', $photos['name'][$i]));
        $targetFilePath = $uploadDirectory . $fileName;

        if (move_uploaded_file($photos['tmp_name'][$i], $targetFilePath)) {
            $uploadedPhotos[] = 'uploads/items/' . $fileName; 
        } else {
            error_log('Failed to upload file');
        }
    }
} else {
    error_log('No photos uploaded.');
}

$post = new Post($session->get('user')->id, $name, $price, $description, $categories, $condition, null, null, $uploadedPhotos);
if ($post->savePost($db)) {
    $session->addMessage('success', 'Added item successfully');
    header('Location: ../pages/profilePage.php');
    exit();
} else {
    $session->addMessage('error', 'Error while adding item');
    header('Location: ../pages/addItemPage.php');
    exit();
}
?>

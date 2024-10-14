<?php
require_once('../database/connection.db.php');
require_once('../database/user.class.php');
require_once('../utils/session.php');

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
    $name = trim($_POST['name']);
    $bio = trim($_POST['biography']);
    $city = trim($_POST['city']);
    $email = trim($_POST['email']);
    $password1 = trim($_POST['password1']); // Current password
    $password2 = trim($_POST['password2']); // New password
    $password3 = trim($_POST['password3']); // Confirm new password
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    
    if (!empty($password2) || !empty($password3)) {
        if (empty($password1)) {
            $errors[] = "Current password is required to change password.";
        }
        if ($password2 !== $password3) {
            $errors[] = "New passwords do not match.";
        }
    }

    
    if (isset($_FILES['profile-photo']) && $_FILES['profile-photo']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile-photo']['tmp_name'];
        $fileName = $_FILES['profile-photo']['name'];
        $fileSize = $_FILES['profile-photo']['size'];
        $fileType = $_FILES['profile-photo']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = __DIR__ . '/../../uploads/users/';
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            if ($user->photo && file_exists($uploadFileDir . $user->photo)) {
                unlink($uploadFileDir . $user->photo);
            }

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $user->photo = $newFileName;
            } else {
                $errors[] = "There was an error moving the file to the upload directory.";
                error_log("Error moving file from $fileTmpPath to $dest_path");
            }
        } else {
            $errors[] = "Upload failed, file type not allowed" ;
        }
    } elseif (isset($_FILES['profile-photo']) && $_FILES['profile-photo']['error'] != UPLOAD_ERR_NO_FILE) {
        $errors[] = "There was an error uploading the file.";
        error_log("File upload error: " . $_FILES['profile-photo']['error']);
    }

    if (empty($errors)) {
        $user->name = htmlspecialchars($name);
        $user->biography = htmlspecialchars($bio);
        $user->city = htmlspecialchars($city);
        $user->email = htmlspecialchars($email);

        if (!empty($password2) && !empty($password3) && !empty($password1)) {
            if (password_verify($password1, $user->password)) {
                $user->updateUserPassword($db, $password2);
            } else {
                $errors[] = "Current password is incorrect.";
            }
        }

        if ($user->updateUser($db)) {
            $session->set("user", $user);
            header("Location: ../pages/profilePage.php");
            exit();
        } else {
            $errors[] = "An error occurred while updating the profile. Please try again.";
        }
    }

    if (!empty($errors)) {
        $errorMessage = implode("<br>", $errors);
    }
}
?>
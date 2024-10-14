<?php
require_once(__DIR__ . '/../utils/autoload.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../templates/header.php');
require_once(__DIR__ . '/../templates/footer.php');
require_once(__DIR__ . '/../templates/profile.php');
require_once(__DIR__ . '/../templates/login.php');

$session = Session::getInstance();
$db = connectToDatabase();
$session->set('last_page', $_SERVER['REQUEST_URI']);

$currentUser = $session->get('user');
if ($currentUser === null) {
    login();
    exit();
}

$user_id = isset($_GET['id']) ? $_GET['id'] : ($currentUser->id ?? "");
$user = User::getUserById($db, $user_id);

if ($user === null) {
    login();
    exit();
}

drawHeader();
profile($user);
footer();
?>

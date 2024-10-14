<?php
require_once(__DIR__ . '/../templates/header.php');
require_once(__DIR__ .'/../templates/footer.php');
require_once(__DIR__ .'/../templates/home.php');
require_once(__DIR__ .'/../utils/session.php');

$session = Session::getInstance();
$session->set('last_page', $_SERVER['REQUEST_URI']);

drawHeader();
home();
footer();
?>

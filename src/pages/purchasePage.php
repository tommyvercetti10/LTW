<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/post.class.php');
require_once(__DIR__ . '/../templates/purchase.php');

$session = Session::getInstance();
$session->set('last_page', $_SERVER['REQUEST_URI']);

purchase();
?>

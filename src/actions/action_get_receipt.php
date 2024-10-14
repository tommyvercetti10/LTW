<?php
require_once(__DIR__ . "/../utils/session.php");
require_once(__DIR__ . "/../database/post.class.php");
require_once(__DIR__ . "/../database/connection.db.php");
require_once(__DIR__ . "/../database/user.class.php");
require_once(__DIR__ . "/../templates/itemPreview.php");

$session = Session::getInstance();
$user = $session->get('user');

$json_str = file_get_contents('php://input');

$json_obj = json_decode($json_str, true);

$post_value = $json_obj['id'];

?>

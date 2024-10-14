<?php 
require_once(__DIR__ . "/../templates/footer.php");
require_once(__DIR__ . "/../templates/header.php");
require_once(__DIR__ . "/../templates/shopcart.php");
require_once(__DIR__ . "/../templates/login.php");
require_once(__DIR__ . "/../utils/session.php");

$session = Session::getInstance();

if($session->get('user') !== null) {
    drawHeader();
    shopcart();
    footer();
}
else {
    login();
}


?>
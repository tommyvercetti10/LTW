<?php
    require_once('../utils/session.php'); 

    $session = Session::getInstance(); 
    
    $session->logout();
    header('Location: ../../index.php');
    
?>
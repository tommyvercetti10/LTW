<?php 
    function connectToDatabase() {
        return new PDO('sqlite:' . __DIR__ . '/../database/database.db');
    }

?>
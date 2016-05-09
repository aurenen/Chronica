<?php

require_once 'config.php';

function db_connect() {
    $dsn = 'mysql:dbname=' . $db_name . ';host=' . $db_host;

    try {
        $link = new PDO($dsn, $db_user, $db_pass);
    }
    catch (PDOException $ex) {
        echo 'Connection failed: ' . $ex->getMessage();
    }
}
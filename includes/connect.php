<?php

function db_connect() {
    require_once 'config.php';

    $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name;

    try {
        $link = new PDO($dsn, $db_user, $db_pass);
        $link->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

        return $link;
    }
    catch (PDOException $ex) {
        echo 'Connection failed: ' . $ex->getMessage();
        echo $dsn;
    }
}
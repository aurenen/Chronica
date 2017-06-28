<?php
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  includes/connect.php
 *  
 *  Handles all database connections.
 * 
 ************************************************************/

/**
 * Connects to MySQL database through PDO
 * @return PDO connection link
 */
function db_connect() {
    require_once 'config.php';

    $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';

    try {
        $link = new PDO($dsn, $db_user, $db_pass);
        $link->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        //$link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

        return $link;
    }
    catch (PDOException $ex) {
        error_log(date('Y-m-d') . ' ERROR: Connection failed: ' . $ex->getMessage());
        exit('ERROR: failed to connect to database. Check error log.');
    }
}
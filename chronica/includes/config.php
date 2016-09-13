<?php
/************************************************************************
 * file: config.php
 *
 * modify the values below with your database information
 * keep the values within single quotes
 *
 * the hostname is usually 'localhost' only change if your host has a 
 * different setting
 ************************************************************************/

$db_user = 'username';
$db_pass = 'password';
$db_name = 'chronica';
$db_host = 'localhost';

date_default_timezone_set('America/Los_Angeles');

// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// Adds entropy into the randomization of the session ID, as PHP's random number
// generator has some known flaws
ini_set('session.entropy_file', '/dev/urandom');

// Uses a strong hash
ini_set('session.hash_function', 'whirlpool');
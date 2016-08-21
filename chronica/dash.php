<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  dash.php
 *  
 *  Dashboard for admin
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/util.php';
require_once 'includes/auth.php';
require_once 'includes/PasswordHash.php';

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

require_once 'includes/admin_header.php'; 
?>

        <h2>Dashboard</h2>
        <p>Logged in.</p>

<?php 
require_once 'includes/admin_footer.php'; 
?>
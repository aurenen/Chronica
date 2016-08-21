<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  login.php
 *  
 *  Handles login verification for admin
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/util.php';
require_once 'includes/auth.php';
require_once 'includes/PasswordHash.php';

session_start();

if (isset($_POST['login'])) {
    if (verifyUser($_POST['username'], $_POST['password'])) {
        $_SESSION['login'] = md5($_POST['username']);
        header('Location: dash.php');
        exit();
    }
}
require_once 'includes/admin_header.php'; 
?>

        <form action="login.php" method="post">
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username">
            </div>
            <div class="form-row">
                <label>Passcode</label>
                <input type="password" name="password">
            </div>
            <div class="form-row">
                <label>&nbsp;</label>
                <input type="submit" name="login" value="Login">
            </div>
        </form>

<?php 
require_once 'includes/admin_header.php'; 
?>
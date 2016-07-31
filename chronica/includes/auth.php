<?php
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  includes/auth.php
 *  
 *  User authentication functions.
 * 
 ************************************************************/

/**
 * [verifyUser description]
 * @param  [type] $user [description]
 * @param  [type] $pass [description]
 * @return [type]       [description]
 */
function verifyUser($user, $pass) {
    $db = db_connect();

    // check password length, bcrypt only uses the first 72 characters
    if (strlen($pass) > 72) { 
        header('Location: login.php?error');
        exit();
    }

    // hash password before checking with db
    $hasher = new PasswordHash(8, FALSE);

    // if match, return result[0][0] == 'username' and result[1][0] == 'password'
    $sql = "SELECT `set_key` FROM `settings` 
        WHERE (`set_key` = 'username' AND `set_value` = :user)
        UNION 
        SELECT `set_key` FROM `settings` 
        WHERE (`set_key` = 'password' AND `set_value` = :pass";

    $db = null;
}
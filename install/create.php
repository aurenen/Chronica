<?php
require_once '../includes/connect.php';

$db = db_connect();

/**
 * create tables
 */
if ( isset($_POST['create_tables']) ) { 
    $create = file_get_contents("create.sql");
    $stmts = explode("--", $create);

    foreach ($stmts as $s) {
        if (!$db->query($s)) {
            $table_msg = '<div class="warning">There was an error running the query [' . $s . ']</div>';
        }
    }

    $table_msg = '<div class="success">Tables successfully created.</div>';
}

/**
 * insert initial settings
 */
if ( isset($_POST['install']) ) { 

    // process post request, insert info, redirect to success page.

}

// kill connection
$db = null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Chronica Installation</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/normalize.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<h1 class="title center">Chronica Installation</h1>
<div id="wrap">
    <div id="nav">
        <h3>Navigation</h3>
        <a href="index.php">Start</a>
        <a href="create.php">&rarr; Install</a>
        <a href="upgrade.php">Upgrade</a>
    </div>
    <div id="content">
        <h2>New Install</h2>
        <p>
        Make sure you have filled out <strong>/includes/config.php</strong> with the correct database information before proceeding.
        </p>
        <p>
        When you install, click the submit button only once.
        </p>

        <form action="create.php" method="post">

        <?php if ( !isset($_POST['create_tables']) ): ?>

        <h3>Create Database Tables</h3>
        <p>
        Click the button below to create the required tables with the database connection, user, and password filled in your config file. Once it is successful, you may proceed to populating it with the initial data.
        </p>
        <div class="form-row">
            <input type="submit" name="create_tables" value="Create Tables &rarr;">
        </div>
            
        <?php else: echo $table_msg; ?>

        <h3>Admin Settings</h3>
        <p>
        Use the browser's back and forward nagivation with caution, and please avoid reloading after you submit any forms. 
        </p>
        <div class="form-row">
            <label>Username</label>
            <input type="text" name="username" value="" placeholder="Your login name">
        </div>
        <div class="form-row">
            <label>Password</label>
            <input type="password" name="password" value="" placeholder="Password">
        </div>
        <div class="form-row">
            <label>Email</label>
            <input type="text" name="email" value="" placeholder="Email address">
        </div>
        <div class="form-row">
            <label>Install full path</label>
            <input type="text" name="full_path" value="" placeholder="Full server path">
        </div>
        <div class="form-row">
            <label>Full URL</label>
            <input type="text" name="full_url" value="" placeholder="Full URL">
        </div>
        <div class="form-row">
            <label>Timezone offset</label>
            <input type="text" name="timezone" value="" placeholder="Offset number">
        </div>
        <div class="form-row">
            <label>Site name</label>
            <input type="text" name="site_name" value="" placeholder="Your blog/journal title">
        </div>
        <div class="form-row">
            <input type="submit" name="install" value="Install &rarr;">
        </div>

        <?php endif ?>

        </form>
    </div>
</div>
<div id="footer">
    <p>&copy; Chronica 2016.</p>
</div>
</body>
</html>
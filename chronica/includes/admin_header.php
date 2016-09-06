<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Chronica</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/normalize.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/flatpickr.min.css" rel="stylesheet">
</head>
<body>
<div id="wrap">
    <div id="nav">
        <h3>Navigation</h3>
        <?php if (isset($_SESSION['login'])): ?>
        <a href="dash.php"><?php is_current("dash"); ?>Dashboard</a>
        <a href="add.php"><?php is_current("add"); ?>Add Entry</a>
        <a href="edit.php"><?php is_current("edit"); ?>Edit Entry</a>
        <a href="category.php"><?php is_current("category"); ?>Categories</a>
        <a href="setting.php"><?php is_current("setting"); ?>Settings</a>
        <?php endif; ?>
        <a href="index.php"><?php is_current("index"); ?>View Published</a>

        <?php if (!isset($_SESSION['login'])): ?>
        <a href="login.php"><?php is_current("login"); ?>Login</a>
        <?php else: ?>
        <a href="logout.php"><?php is_current("logout"); ?>Logout</a>
        <?php endif; ?>
    </div>
    <div id="content">
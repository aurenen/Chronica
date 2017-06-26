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
    <link href="css/simplemde.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<div id="wrap">
    <div id="nav">
        <h3>Navigation</h3>
        <?php if (isset($_SESSION['login'])): ?>
        <a href="dash.php"><?php if (is_current("dash")) echo "&rarr; "; ?>Dashboard</a>
        <a href="add.php"><?php if (is_current("add")) echo "&rarr; "; ?>Add Entry</a>
        <a href="edit.php"><?php if (is_current("edit")) echo "&rarr; "; ?>Edit Entry</a>
        <a href="category.php"><?php if (is_current("category")) echo "&rarr; "; ?>Categories</a>
        <a href="settings.php"><?php if (is_current("settings")) echo "&rarr; "; ?>Settings</a>
        <?php endif; ?>
        <a href="index.php"><?php if (is_current("index")) echo "&rarr; "; ?>View Published</a>

        <?php if (!isset($_SESSION['login'])): ?>
        <a href="login.php"><?php if (is_current("login")) echo "&rarr; "; ?>Login</a>
        <?php else: ?>
        <a href="logout.php"><?php if (is_current("logout")) echo "&rarr; "; ?>Logout</a>
        <?php endif; ?>
    </div>
    <div id="content">
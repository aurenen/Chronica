<?php
/**
 * Edit this page as needed. Any images or css related to your layout should stay in 
 * the /template/ folder, and referenced here. Chronica will load from the main or
 * root folder, so consider the correct relative location, for example:
 *     <link href="template/style.css" rel="stylesheet">
 *
 * Please do not remove or rename header.php or footer.php
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Chronica Installation</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/normalize.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<h1 class="title center">Network Updates</h1>
<div id="wrap">
    <div id="nav">
        <h3>Navigation</h3>
        <a href="index.php"><?php if (is_current("index")) echo "&rarr; "; ?>Updates</a>
        <a href="dash.php"><?php if (is_current("dash")) echo "&rarr; "; ?>Admin Dash</a>
    </div>
    <div id="content">
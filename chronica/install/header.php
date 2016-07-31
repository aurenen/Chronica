<?php
function is_current($title) {
    $url = $_SERVER['REQUEST_URI'];

    if (strlen($_SERVER['QUERY_STRING']) > 0) 
        $page = (strpos($url, "=") > 0) ? (substr($url, strpos($url, "=") + 1)) : (substr($url, strpos($url, "?") + 1));
    
    else {
        preg_match('~\/(.*?)\.php~', $url, $output);
        $page = $output[1];
        while (strpos($page, "/") !== false)
            $page = substr($page, strpos($page, "/") + 1);
    }

    if ($page == null || strlen($page) == 0) {
        $page = "index";
    }

    if ($page === $title) {
        echo "&rarr; ";
    }
}
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
        <a href="index.php"><?php is_current("index"); ?>Start</a>
        <a href="create.php"><?php is_current("create"); ?>Install</a>
        <a href="../index.php"><?php is_current("success"); ?>Return to main</a>
    </div>
    <div id="content">
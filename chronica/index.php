<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  index.php
 *  
 *  Handles the display of entries. View may change if admin
 *  is logged in.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/util.php';
require_once 'includes/crud.php';

$cat = is_numeric($_GET['cat']) ? intval($_GET['cat']) : "all";
$entries = getEntriesForView($cat);

include_once 'template/header.php'; 

include 'view.php';

include_once 'template/footer.php'; 
?>
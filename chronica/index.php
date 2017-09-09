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
$page_num = isset($_GET['p']) ? $_GET['p'] : 1;
$page_offset = $page_num - 1;
$entries = getEntriesForView($cat, $page_offset, 10);

include_once 'template/header.php'; 

include 'view.php';

$pages = entry_pagination($cat);
echo '<div class="pagination">';
if (count($pages) > 0)
    echo '<strong>Pages: </strong>';
foreach ($pages as $p) {
    if ($page_num == $p)
        echo '<span>'. $p .'</span>';
    else 
        echo '<a href="index.php?cat='. $cat .'&p='. $p .'">'. $p .'</a>';
}
echo '</div>';

include_once 'template/footer.php'; 
?>
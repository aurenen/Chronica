<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  view.php
 *  
 *  Handles the display of entries. View may change if admin
 *  is logged in. Acts as the entry template.
 * 
 ************************************************************/

if ($entries == null)
    exit();

foreach ($entries as $e) {
    echo "<div class=\"entry-wrap\">\n"
        ."<h3>".$e['title']."</h3>\n"
        ."<div class=\"entry-body\">".$e['html']."</div>\n"
        ."<div class=\"entry-date\">Posted on: ".date('F jS, Y', strtotime($e['added']))."</div>\n"
        ."<div class=\"entry-cat\">Filed under: <a href=\"index.php?cat=".$e['cat_id']."\">".$e['name']."</a></div>\n"
        ."</div>\n\n";
}

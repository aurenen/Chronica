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
    $added_date = date('F jS, Y', strtotime($e['added']));
    echo <<<EOT
    <div class="entry-wrap">
        <h3>{$e['title']}</h3>
        <div class="entry-body">{$e['html']}</div>
        <div class="entry-date">Posted on: {$added_date}</div>
        <div class="entry-cat">Filed under: <a href="index.php?cat={$e['cat_id']}">{$e['name']}</a></div>
    </div>
EOT;
}

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

if ($entries == null) {
    echo "<p>No entries to display.</p>";
}

else {
    foreach ($entries as $e) {
        $entry_cats = getEntryCategoriesForView($e['ent_id']);
        $added_date = date('F jS, Y', strtotime($e['added']));
        echo <<<EOT

        <div class="entry-wrap">
            <h2 class="entry-title">{$e['title']}</h2>
            <div class="entry-body">{$e['html']}</div>
            <div class="entry-date">Posted on: {$added_date}</div>
            <div class="entry-cat">Filed under: 
EOT;
        $cat_count = count($entry_cats);
        for ($i=0;$i<$cat_count;$i++) {
            if ($i < $cat_count-1)
                echo '<a href="index.php?cat='.$entry_cats[$i]['cat_id'].'">'.$entry_cats[$i]['name'].'</a>, ';
            else
                echo '<a href="index.php?cat='.$entry_cats[$i]['cat_id'].'">'.$entry_cats[$i]['name'].'</a>';
        }
    echo <<<EOT

        </div> <!-- end .entry-cat -->
    </div> <!-- end .entry-wrap -->
EOT;

    }
}

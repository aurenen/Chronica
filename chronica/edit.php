<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  edit.php
 *  
 *  List current and edit entries.
 * 
 ************************************************************/

require_once 'includes/connect.php';
require_once 'includes/util.php';
require_once 'includes/crud.php';

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$action = $_GET['action'] == "edit" ? "edit" : "list";
$page_num = isset($_GET['page']) ? $_GET['page'] : 1;
$ent_id = is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$page_offset = $page_num - 1;
$entry_msg = '';

if ($action == "list") {
    $entries = getEntriesMeta($page_offset, 10);
}
elseif ($action == "edit" && $ent_id == 0) {
    $entry_msg .= '<div class="warning">Invalid entry id.</div>';
}
else {
    $edit_entry = getEntryForEdit($ent_id);
    $entry_cats = getEntryCategories($ent_id);
    $cats = getCategories();
    $current_date = date('Y-m-d H:i:s');
}

$edit = true;
$success = false;

if (isset($_POST['save_entry'])) {
    $p_id = $_POST['entry_id'];
    $p_title = strip_tags($_POST['title']);
    $p_date_added = $_POST['added'];
    $p_date_modified = $_POST['modifed'];

    if (preg_match("/[^\d\,]/", $_POST['category_array']) === 1) {
        $p_category = 0;
    }
    else {
        $p_category = explode(",", $_POST['category_array']);
    }
    
    $p_entry = strip_tags($_POST['entry']);

    if (strlen($p_title) > 100) {
        $entry_msg .= '<div class="warning">Entry title cannot be longer than 100 characters.</div>';
        $edit = false;
    }
    if (date_create_from_format('Y-m-d H:i:s', $p_date_added) === false || 
        date_create_from_format('Y-m-d H:i:s', $p_date_modified) === false) {
        $entry_msg .= '<div class="warning">Please use the drop down to select a valid date and time.</div>';
        $edit = false;
    }
    if ($p_category === 0) {
        $entry_msg .= '<div class="warning">Please select a category.</div>';
        $edit = false;
    }
    if ($action == "edit" && $ent_id === 0) {
        $entry_msg .= '<div class="warning">Invalid entry id.</div>';
        $edit = false;
    }

    // process post if no issues
    if ($edit && $p_id !== 0) {
        $success = editEntry($p_id, $p_title, substr($p_entry, 0, 200), $p_date_added, $p_date_modified, true, $p_category, $p_entry);
        if ($success) 
            header('Location: edit.php?action=edit&id='.$p_id.'&success=true');
        else 
            header('Location: edit.php?action=edit&id='.$p_id.'&success=false');
        exit();
    }
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Edit Entries</h2>

        <?php echo $entry_msg; 
            if ($_GET['success'] == 'true')
                echo '<div class="success">Entry successfully edited.</div>';
            if ($_GET['success'] == 'false')
                echo '<div class="warning">Failed to edit entry.</div>';
        ?>

        <?php if ($action == "edit"): /* edit view */ ?>
        <form action="edit.php" method="post">
            <div class="form-row">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $edit_entry['title']; ?>">
            </div>
            <div class="form-row">
                <label>Date Published</label>
                <input type="text" name="added" class="date" data-default-date="<?php echo $edit_entry['added']; ?>" data-enable-time="true" data-enable-seconds="true">
            </div>
            <div class="form-row">
                <label>Date Modififed</label>
                <input type="text" name="modifed" class="date" data-default-date="<?php echo $current_date; ?>" data-enable-time="true" data-enable-seconds="true">
            </div>
            <div class="form-row">
                <label>Category</label>
                <select name="category" id="category" multiple>
                    <?php foreach ($cats as $c) {
                        echo '<option value="'.$c['cat_id'].'"';
                        foreach ($entry_cats as $ec) {
                            if ($ec['cat_id'] == $c['cat_id']) 
                                echo ' selected';
                        }
                        echo '>'
                            .$c['name']
                            ."</option>\n";
                    } ?>
                </select>
                <input type="hidden" name="category_array" id="category_list">
            </div>
            <div class="form-row">
                <div id="editor-wrap">
                    <h4>Entry (<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">markdown syntax</a>)</h4>
                    <textarea id="md" name="entry"><?php echo $edit_entry['markdown']; ?></textarea>
                </div>
                <input type="hidden" name="entry_id" value="<?php echo $ent_id; ?>">
                <input type="submit" name="save_entry" value="Save Entry">
            </div>
        </form>
        <?php else: /* list view */?>

        <table>
            <tr>
                <th>Added</th>
                <th>Modified</th>
                <th>Title</th>
                <th>Entry</th>
                <th>Published</th>
                <th></th>
            </tr>
            <?php foreach ($entries as $e) {
                echo "<tr>\n"
                    ."<td>".date('Y-m-d H:i:s', strtotime($e['added']))."</td>\n"
                    ."<td>".date('Y-m-d H:i:s', strtotime($e['modified']))."</td>\n"
                    ."<td>".$e['title']."</td>\n"
                    ."<td>".$e['description']."</td>\n"
                    ."<td>".(($e['published'] == 1)?'Yes':'No')."</td>\n"
                    ."<td><a href=\"edit.php?action=edit&id=".$e['ent_id']."\">edit</a></td>\n"
                    ."</tr>\n";
            } ?>
        </table>
        
        <?php 
        $pages = entry_pagination();
        echo '<div class="pagination">';
        echo '<strong>Pages:</strong>';
        foreach ($pages as $p) {
            if ($page_num == $p)
                echo '<span>'. $p .'</span>';
            else 
                echo '<a href="edit.php?page='. $p .'">'. $p .'</a>';
        }
        echo '</div>';
        ?>

        <?php endif; ?>


<?php 
require_once 'includes/admin_footer.php'; 
?>
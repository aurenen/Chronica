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
require_once 'includes/PasswordHash.php';

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$action = $_GET['action'] == "edit" ? "edit" : "list";
$ent_id = is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$page_offset = 0;
$entry_msg = '';

if ($action == "list") {
    $entries = getEntriesMeta($page_offset, 10);
}
elseif ($action == "edit" && $ent_id == 0) {
    $entry_msg .= '<div class="warning">Invalid entry id.</div>';
}
else {
    $edit_entry = getEntryForEdit($ent_id);
    $cats = getCategories();
}

$edit = true;
$success = false;

if (isset($_POST['publish'])) {
    $p_title = strip_tags($_POST['title']);
    $p_date = $_POST['added'];
    $p_category = is_numeric($_POST['category']) ? intval($_POST['category']) : 0;
    $p_entry = strip_tags($_POST['entry']);

    $_SESSION['post_title'] = $p_title;
    $_SESSION['post_cat'] = $p_category;
    $_SESSION['post_entry'] = $p_entry;

    if (strlen($p_title) > 100) {
        $entry_msg .= '<div class="warning">Entry title cannot be longer than 100 characters.</div>';
        $edit = false;
    }
    if (date_create_from_format('Y-m-d H:i:s', $p_date) === false) {
        $entry_msg .= '<div class="warning">Please use the drop down to select a valid date and time.</div>';
        $edit = false;
    }
    if ($p_category === 0) {
        $entry_msg .= '<div class="warning">Please select a category.</div>';
        $edit = false;
    }

    // process post if no issues
    if ($edit) {
        $success = addEntry($p_title, substr($p_entry, 0, 200), $p_date, $p_date, true, $p_entry);
    }
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Edit Entries</h2>

        <?php echo $entry_msg; 
            if ($success)
                echo '<div class="success">Entry successfully added.</div>';
        ?>

        <?php if ($action == "edit"): ?>
        <form action="edit.php" method="post">
            <div class="form-row">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $edit_entry['title']; ?>">
            </div>
            <div class="form-row">
                <label>Date</label>
                <input type="text" name="added" class="date" data-default-date="<?php echo $edit_entry['added']; ?>" data-enable-time="true" data-enable-seconds="true">
            </div>
            <div class="form-row">
                <label>Category</label>
                <select name="category">
                    <option></option>
                    <?php foreach ($cats as $c) {
                        echo '<option value="'.$c['cat_id'].'"';
                        if ($edit_entry['cat_id'] == $c['cat_id']) 
                            echo ' selected';
                        echo '>'
                            .$c['name']
                            ."</option>\n";
                    } ?>
                </select>
            </div>
            <div id="editor-wrap">
                <h4>Entry (<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">markdown syntax</a>)</h4>
                <textarea id="md" name="entry"><?php echo $edit_entry['markdown']; ?></textarea>
            <div class="form-row">
                <input type="submit" name="publish" value="Publish">
                <input type="submit" name="draft" value="Save Draft">
            </div>
                <h4>HTML Output</h4>
                <textarea id="html"></textarea>
                <h4>Preview</h4>
                <div id="preview"></div>
            </div>
        </form>
        <?php else: ?>

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
                    ."<td>".date('Y-m-d H:i:s', strtotime($e['modifed']))."</td>\n"
                    ."<td>".$e['title']."</td>\n"
                    ."<td>".$e['description']."</td>\n"
                    ."<td>".(($e['published'] == 1)?'Yes':'No')."</td>\n"
                    ."<td><a href=\"edit.php?action=edit&id=".$e['ent_id']."\">edit</a></td>\n"
                    ."</tr>\n";
            } ?>
        </table>

        <?php endif; ?>


<?php 
require_once 'includes/admin_footer.php'; 
?>
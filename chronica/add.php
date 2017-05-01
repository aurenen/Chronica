<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  add.php
 *  
 *  Add new posts
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

$cats = getCategories();
$current_date = date('Y-m-d H:i:s');
$entry_msg = '';
$add = true;
$success = false;

if (isset($_POST['publish'])) {
    $p_title = strip_tags($_POST['title']);
    $p_date = $_POST['added'];

    if (preg_match("/[^\d\,]/", $_POST['category_array']) === 1) {
        $p_category = 0;
    }
    else {
        $p_category = explode(",", $_POST['category_array']);
    }
    
    $p_entry = strip_tags($_POST['entry']);

    $_SESSION['post_title'] = $p_title;
    $_SESSION['post_entry'] = $p_entry;

    if (strlen($p_title) > 100) {
        $entry_msg .= '<div class="warning">Entry title cannot be longer than 100 characters.</div>';
        $add = false;
    }
    if (date_create_from_format('Y-m-d H:i:s', $p_date) === false) {
        $entry_msg .= '<div class="warning">Please use the drop down to select a valid date and time.</div>';
        $add = false;
    }
    if ($p_category === 0) {
        $entry_msg .= '<div class="warning">Please select a category.</div>';
        $add = false;
    }

    // process post if no issues
    if ($add) {
        $success = addEntry($p_title, substr($p_entry, 0, 200), $p_date, $p_date, true, $p_category, $p_entry);
        $_SESSION['post_title'] = "";
        $_SESSION['post_entry'] = "";
    }
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Add Entry</h2>

        <?php echo $entry_msg; 
            if ($success)
                echo '<div class="success">Entry successfully added.</div>';
        ?>
        <form action="add.php" method="post">
            <div class="form-row">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $_SESSION['post_title']; ?>">
            </div>
            <div class="form-row">
                <label>Date</label>
                <input type="text" name="added" class="date" data-default-date="<?php echo $current_date; ?>" data-enable-time="true" data-enable-seconds="true">
            </div>
            <div class="form-row">
                <label>Category</label>
                <select name="category" id="category" multiple>
                    <?php foreach ($cats as $c) {
                        echo '<option value="'.$c['cat_id'].'"';
                        if ($_SESSION['post_cat'] == $c['cat_id']) 
                            echo ' selected';
                        echo '>'
                            .$c['name']
                            ."</option>\n";
                    } ?>
                </select>
                <input type="hidden" name="category_array" id="category_list">
            </div>
            <div id="editor-wrap">
                <h4>Entry (<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">markdown syntax</a>)</h4>
                <textarea id="md" name="entry"><?php echo $_SESSION['post_entry']; ?></textarea>
            <div class="form-row">
                <input type="submit" name="publish" value="Publish">
                <input type="submit" name="draft" value="Save Draft">
            </div>
            </div>
        </form>


<?php 
require_once 'includes/admin_footer.php'; 
?>
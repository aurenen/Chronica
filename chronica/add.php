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
require_once 'includes/PasswordHash.php';

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$cats = getCategories();
$current_date = date('Y-m-d H:i:s');

if (isset($_POST['publish'])) {
    $p_title = strip_tags($_POST['title']);
    $p_date = is_int($_POST['added']) ? intval($_POST['added']) : 0;
    $p_category = strip_tags($_POST['category']);
    $p_entry = strip_tags($_POST['entry']);

    $_SESSION['post_title'] = $p_title;
    $_SESSION['post_cat'] = $p_category;
    $_SESSION['post_entry'] = $p_entry;

    if (strlen($p_title) > 100) {
        $entry_msg = '<div class="warning">Entry title cannot be longer than 100 characters.</div>';
        exit();
    }

    // process post
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Add Entry</h2>

        <?php echo $entry_msg; ?>
        <form action="category.php" method="post">
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
                <select name="category">
                    <option></option>
                    <?php foreach ($cats as $c) {
                        echo '<option value="'.$c['cat_id'].'"';
                        if ($_SESSION['post_cat'] == $c['cat_id']) 
                            echo ' selected';
                        echo '>'
                            .$c['name']
                            ."</option>\n";
                    } ?>
                </select>
            </div>
            <div id="editor-wrap">
                <h4>Entry (<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">markdown syntax</a>)</h4>
                <textarea id="md" name="entry"><?php echo $_SESSION['post_entry']; ?></textarea>
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


<?php 
require_once 'includes/admin_footer.php'; 
?>
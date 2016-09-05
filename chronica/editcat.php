<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  category.php
 *  
 *  Manages categories
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

//$cats = getCategories();
// LOL DOESN'T EDIT, I NEED SLEEP, WILL FIX LATER

if (!is_numeric($_GET['id'])) {
    $edit_msg = '<div class="warning">Invalid category ID.</div>';
}
else {
    $current = getCategory($_GET['id']);
    $edit = true;
    if ( isset($_POST['edit_cat']) ) {
        $edit_msg = '<div class="warning">';
        if ( strlen($_POST['cat_name']) > 25 || preg_match('/[^A-Za-z0-9\.\&\!\s]+/', $_POST['cat_name']) === 1) {
            $edit_msg .= 'Category name must be less than 25 characters and contain no symbols. ';
            $edit = false;
        }
        if ( strlen($_POST['cat_perma']) > 25 || preg_match('/[^a-z0-9\-]+/', $_POST['cat_perma']) === 1) {
            $edit_msg .= 'Category permalink must be less than 25 characters, all lower case, and contain no symbols. ';
            $edit = false;
        }
        if ( strlen($_POST['cat_desc']) > 200) {
            $edit_msg .= 'Category description must be less than 200 characters. ';
            $edit = false;
        }
        $add_msg .= '</div>';

        if ($edit) {
            $edit_msg = editCategory($_GET['id'], $_POST['cat_name'], $_POST['cat_perma'], $_POST['cat_desc']);
        }
    }
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Edit Category</h2>

        <?php echo $edit_msg; ?>
        <p>Category name should avoid symbols and category permalink is the unique slug for your category, example: http://update.com/category/<strong>permalink</strong>, it is NOT the entire url, just the unique part you want to use to link.</p>
        <form action="editcat.php" method="post">
            <div class="form-row">
                <label>Name</label>
                <input type="text" name="cat_name" value="<?php echo $current['name']; ?>">
            </div>
            <div class="form-row">
                <label>URL Permalink</label>
                <input type="text" name="cat_perma" value="<?php echo $current['permalink']; ?>">
            </div>
            <div class="form-row">
                <label>Description</label>
                <input type="text" name="cat_desc" value="<?php echo $current['description']; ?>">
            </div>
            <div class="form-row">
                <input type="submit" name="Edit_cat" value="Edit">
            </div>
        </form>


<?php 
require_once 'includes/admin_footer.php'; 
?>
<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  editcat.php
 *  
 *  Edits category info.
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

$current = getCategory($_GET['id']);
$edit = true;
if ( isset($_POST['edit_cat']) ) {
    $edit_status = 'id='.$_POST['cat_id'];
    if ( strlen($_POST['cat_name']) > 25 || preg_match('/[^A-Za-z0-9\.\&\!\s]+/', $_POST['cat_name']) === 1 ) {
        $edit_status .= '&name=1';
        $edit = false;
    }
    if ( strlen($_POST['cat_perma']) > 25 || preg_match('/[^a-z0-9\-]+/', $_POST['cat_perma']) === 1 ) {
        $edit_status .= '&perma=1';
        $edit = false;
    }
    if ( strlen($_POST['cat_desc']) > 200 ) {
        $edit_status .= '&desc=1';
        $edit = false;
    }

    if ($edit) {
        $edit_status = editCategory($_POST['cat_id'], $_POST['cat_name'], $_POST['cat_perma'], $_POST['cat_desc']);
        header('Location: category.php');
    }
    else {
        header('Location: editcat.php?'.$edit_status);
    }
}


require_once 'includes/admin_header.php'; 
?>
        <h2>Edit Category</h2>

        <?php if (isset($_GET['name']) || isset($_GET['perma']) || isset($_GET['desc'])) {
            $edit_status = '<div class="warning">';
            if (isset($_GET['name']))
                $edit_status .= 'Category name must be less than 25 characters and contain no symbols. ';
            if (isset($_GET['perma']))
                $edit_status .= 'Category permalink must be less than 25 characters, all lower case, and contain no symbols. ';
            if (isset($_GET['desc']))
                $edit_status .= 'Category description must be less than 200 characters. ';
            $edit_status .= '</div>';
            echo $edit_status;
        }
        ?>
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
                <input type="hidden" name="cat_id" value="<?php echo $current['cat_id']; ?>" />
                <input type="submit" name="edit_cat" value="Edit">
            </div>
        </form>


<?php 
require_once 'includes/admin_footer.php'; 
?>
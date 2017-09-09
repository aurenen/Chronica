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

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
} 

$cats = getCategories();
$add_msg = '';
$add = true;
$added = false;

if ( isset($_POST['add_cat']) ) {
    $_SESSION['cat_name'] = htmlentities($_POST['cat_name']);
    $_SESSION['cat_perma'] = htmlentities($_POST['cat_perma']);
    $_SESSION['cat_desc'] = htmlentities($_POST['cat_desc']);

    if ( strlen($_POST['cat_name']) < 2 || strlen($_POST['cat_name']) > 25 || preg_match('/[^A-Za-z0-9\.\&\!\'\/\:\s]+/', $_POST['cat_name']) === 1) {
        $add_msg .= '<div class="warning">Category name must be greater than 1 and less than 25 characters and contain no symbols.</div>';
        $add = false;
    }
    if ( strlen($_POST['cat_perma']) < 2 || strlen($_POST['cat_perma']) > 25 || preg_match('/[^a-z0-9\-]+/', $_POST['cat_perma']) === 1) {
        $add_msg .= '<div class="warning">Category permalink must be greater than 1 and less than 25 characters, all lower case, and contain no symbols.</div>';
        $add = false;
    }
    if ( strlen($_POST['cat_desc']) > 200) {
        $add_msg .= '<div class="warning">Category description must be less than 200 characters.</div>';
        $add = false;
    }

    if ($add) {
        $_SESSION['cat_name'] = "";
        $_SESSION['cat_perma'] = "";
        $_SESSION['cat_desc'] = "";
        $added = addCategory($_POST['cat_name'], $_POST['cat_perma'], $_POST['cat_desc']);
    }
    if ($added) 
        header('Location: category.php?success=true');
    else 
        header('Location: category.php?success=false');
    exit();
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Categories</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>URL Permalink</th>
                <th>Description</th>
                <th></th>
            </tr>
            <?php foreach ($cats as $c) {
                echo "<tr>\n"
                    ."<td>".$c['cat_id']."</td>\n"
                    ."<td>".$c['name']."</td>\n"
                    ."<td>".$c['permalink']."</td>\n"
                    ."<td>".$c['description']."</td>\n"
                    ."<td><a href=\"editcat.php?id=".$c['cat_id']."\">edit</a></td>\n"
                    ."</tr>\n";
            } ?>
        </table>

        <h3>Add Category</h3>
        <?php echo $add_msg; 
            if ($_GET['success'] == 'true') {
                echo '<div class="success">Category successfully added.</div>';
            }
            if ($_GET['success'] == 'false') {
                echo '<div class="warning">ERROR: failed to add category.</div>';
            }
        ?>
        <p>Category name should avoid symbols and category permalink is the unique slug for your category, example: http://update.com/category/<strong>permalink</strong>, it is NOT the entire url, just the unique part you want to use to link.</p>
        <form action="category.php" method="post">
            <div class="form-row">
                <label>Name</label>
                <input type="text" name="cat_name" value="<?php echo $_SESSION['cat_name']; ?>">
            </div>
            <div class="form-row">
                <label>URL Permalink</label>
                <input type="text" name="cat_perma" value="<?php echo $_SESSION['cat_perma']; ?>">
            </div>
            <div class="form-row">
                <label>Description</label>
                <input type="text" name="cat_desc" value="<?php echo $_SESSION['cat_desc']; ?>">
            </div>
            <div class="form-row">
                <input type="submit" name="add_cat" value="Add">
            </div>
        </form>


<?php 
require_once 'includes/admin_footer.php'; 
?>
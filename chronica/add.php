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

require_once 'includes/admin_header.php'; 
?>
        <h2>Add Entry</h2>

        <div class="form-row">
            <label>Title</label>
            <input type="text" name="title">
        </div>
        <div class="form-row">
            <label>Date</label>
            <input type="text" name="added">
        </div>
        <div class="form-row">
            <label>Category</label>
            <select>
                <option></option>
                <?php foreach ($cats as $c) {
                    echo "<option value=\"".$c['cat_id']."\">"
                        .$c['name']
                        ."</option>\n";
                } ?>
            </select>
        </div>
        <div id="editor-wrap">
            <h4>Entry (<a href="https://daringfireball.net/projects/markdown/syntax" target="_blank">markdown syntax</a>)</h4>
            <textarea id="md"># header
test paragraph</textarea>
        <div class="form-row">
            <input type="submit" name="publish" value="Publish">
            <input type="submit" name="draft" value="Save Draft">
        </div>
            <h4>HTML Output</h4>
            <textarea id="html"></textarea>
            <h4>Preview</h4>
            <div id="preview"></div>
        </div>


<?php 
require_once 'includes/admin_footer.php'; 
?>
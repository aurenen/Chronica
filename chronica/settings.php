<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  settings.php
 *  
 *  Manages script settings.
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

// pull settings
$username = getSettings("username");
$email = getSettings("email");
$full_path = getSettings("full_path");
$full_url = getSettings("full_url");
$timezone = getSettings("timezone_offset");
$site_name = getSettings("site_name");
$entry_format = getSettings("entry_format");

// TODO: process settings
if (isset($_POST['save'])) {

    $setting = true;
    
    if ($_POST['password'] !== $_POST['password2']) {
        $entry_msg .= '<div class="warning">Passwords do not match.</div>';
        $setting = false;
    }
    if (strlen($_POST['password']) > 0 && strlen($_POST['password']) < 8) {
        $entry_msg .= '<div class="warning">Password must be at least 8 characters.</div>';
        $setting = false;
    }
    if (strpos($_POST['full_url'], 'http') !== 0) {
        $entry_msg .= '<div class="warning">Please make sure the full url begins with http:// or https://</div>';
        $setting = false;
    }

    $settings = array();
    if ($_POST['username'] !== $username)
        $settings['username'] = $_POST['username'];
    if ($_POST['email'] !== $email)
        $settings['email'] = $_POST['email'];
    if ($_POST['full_path'] !== $full_path)
        $settings['full_path'] = $_POST['full_path'];
    if ($_POST['full_url'] !== $full_url)
        $settings['full_url'] = $_POST['full_url'];
    if ($_POST['timezone'] !== $timezone)
        $settings['timezone_offset'] = $_POST['timezone'];
    if ($_POST['site_name'] !== $site_name)
        $settings['site_name'] = $_POST['site_name'];
    if ($_POST['entry_format'] !== $entry_format)
        $settings['entry_format'] = $_POST['entry_format'];

    if (strlen($_POST['password']) > 0) {
        // hash password before inserting into db
        $hasher = new PasswordHash(8, FALSE);
        $hash = $hasher->HashPassword( $_POST['password'] );
        if (strlen($hash) < 20)
            fail('Failed to hash new password');
        unset($hasher);
        $settings['password'] = $hash;
    }

    // process post if no issues
    if ($setting) {
        $success = editSettings($settings);
        if ($success)
            header('Location: settings.php?success=true');
    }
}

require_once 'includes/admin_header.php'; 
?>
        <h2>Settings</h2>
        <p>
            Enter anything you'd like to change. Leave password blank unless you'd like to change it. 
            Entry format is in <em>markdown</em> by default, only change if you absolutely need to. 
            Avoid editing old entries when you switch entry formats.
        </p>

        <?php echo $entry_msg; 
            if ($_GET['success'] == true)
                echo '<div class="success">Settings successfully updated.</div>';
        ?>
        <form action="settings.php" method="post">
            <div class="form-row">
                <label title="<?php echo $username['description']; ?>">Username</label>
                <input type="text" name="username" value="<?php echo $username['set_value']; ?>">
            </div>
            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" value="" placeholder="enter only if changing">
            </div>
            <div class="form-row">
                <label>Password again</label>
                <input type="password" name="password2" value="" placeholder="enter only if changing">
            </div>
            <div class="form-row">
                <label title="<?php echo $email['description']; ?>">Email</label>
                <input type="text" name="email" value="<?php echo $email['set_value']; ?>">
            </div>
            <div class="form-row">
                <label title="<?php echo $full_path['description']; ?>">Full Path</label>
                <input type="text" name="full_path" value="<?php echo $full_path['set_value']; ?>">
            </div>
            <div class="form-row">
                <label title="<?php echo $full_url['description']; ?>">Full URL</label>
                <input type="text" name="full_url" value="<?php echo $full_url['set_value']; ?>">
            </div>
            <div class="form-row">
                <label title="<?php echo $timezone['description']; ?>">Timezone Offset</label>
                <input type="text" name="timezone" value="<?php echo $timezone['set_value']; ?>">
            </div>
            <div class="form-row">
                <label title="<?php echo $site_name['description']; ?>">Site Name</label>
                <input type="text" name="site_name" value="<?php echo $site_name['set_value']; ?>">
            </div>
            <div class="form-row">
                <label title="<?php echo $entry_format['description']; ?>">Entry Format</label>
                <label class="radio"><input type="radio" name="entry_format" value="markdown"<?php if ($entry_format['set_value'] == "markdown") echo " checked"; ?>> Markdown</label>
                <label class="radio"><input type="radio" name="entry_format" value="html"<?php if ($entry_format['set_value'] == "html") echo " checked"; ?>> HTML</label>
            </div>
            <div class="form-row">
                <input type="submit" name="save" value="Save">
            </div>
        </form>


<?php 
require_once 'includes/admin_footer.php'; 
?>
<?php
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  install/create.php
 *  
 *  The initial set up for a fresh install of Chronica.
 *  Creates the MySQL tables and fills it with starter
 *  settings, and username and password for admin login.
 * 
 ************************************************************/

require_once '../includes/connect.php';
require_once '../includes/util.php';
require_once '../includes/PasswordHash.php';

$db = db_connect();
$done = true;
$page = $_GET['tables'];
$full_install_path = str_ireplace('install', '', realpath(__DIR__));
$full_install_url = str_ireplace('install/create.php', '', curPageURL());

// check if installer has already been used
$exist = $db->query('SELECT COUNT(*) FROM settings')->fetchColumn();

if (intval($exist) > 0) {
    $done = false;
    header('Location: ../index.php');
    exit();
}

// create tables
if ( isset($_POST['create_tables']) ) { 
    $create = file_get_contents("create.sql");
    $stmts = explode("--", $create);

    foreach ($stmts as $s) {
         try {
            $db->query($s);
         }
         catch (Exception $ex) {
            $table_msg = '<div class="warning">There was an error running the query [' . 
                        $s . '] with the exception:' . $ex->getMessage() . '</div>';
            $done = false;
            break;
        }
    }

    if ($done) {
        header('Location: create.php?tables=success');
        exit();
    }
}

// insert initial settings
if ( (isset($_GET['tables']) && $_GET['tables'] === 'success') || isset($_POST['install']) ) { 
    if (strlen( $_POST['password'] ) > 72) {
        $setting_msg = '<div class="warning">Password length must not exceed 72 characters.</div>';
        $done = false;
    }
    if (!is_numeric($_POST['timezone'])) {
        $setting_msg = '<div class="warning">Please use a valid timezone offset number.</div>';
        $done = false;
    }

    // no issues, continue
    if ($done && !isset($_GET['install'])) {

        // hash password before inserting into db
        $hasher = new PasswordHash(8, FALSE);
        $hash = $hasher->HashPassword( $_POST['password'] );
        if (strlen($hash) < 20)
            fail('Failed to hash new password');
        unset($hasher);

        $stmt = $db->prepare("INSERT INTO `settings` (`set_key`, `set_value`, `description`) VALUES 
            ('username', :username, 'Your login username.'),
            ('password', :password, 'Your login password.'),
            ('email', :email, 'Your email.'),
            ('full_path', :full_path, 'The full path in your server where this is installed.'),
            ('full_url', :full_url, 'The full url where this is located.'),
            ('timezone_offset', :timezone, 'Your UTF timezone offset.'),
            ('site_name', :site_name, 'The title of your blog.');");

        try {
            // process post request, insert info, redirect to success page.
            $stmt->bindParam(':username', trim($_POST['username']), PDO::PARAM_STR, 25);
            $stmt->bindParam(':password', $hash, PDO::PARAM_STR, 250);
            $stmt->bindParam(':email', trim($_POST['email']), PDO::PARAM_STR, 100);
            $stmt->bindParam(':full_path', trim($_POST['full_path']), PDO::PARAM_STR, 250);
            $stmt->bindParam(':full_url', trim($_POST['full_url']), PDO::PARAM_STR, 250);
            $stmt->bindParam(':timezone', trim($_POST['timezone']), PDO::PARAM_STR, 2);
            $stmt->bindParam(':site_name', trim($_POST['site_name']), PDO::PARAM_STR, 250);

            $stmt->execute();
        }
        catch (Exception $ex) {
            $setting_msg = '<div class="warning">ERROR: failed to insert settings. ' . 
                        $ex->getMessage() . '</div>';
            $done = false;
        }
        if ($done) {
            header('Location: success.php');
            exit();
        }

    } // end if $done
} // end settings

// toggle which messages to display
if (isset($_GET['tables']) && $_GET['tables'] === 'success') {
    $table_msg = '<div class="success">Tables successfully created.</div>';
}
if (isset($_GET['install']) && $_GET['install'] === 'exists') {
    $setting_msg = '<div class="warning">Settings already installed. Installation complete.</div>';
}

// kill connection
$db = null;
?>
<?php include_once 'header.php'; ?>
        <h2>New Install</h2>
        <p>
        Make sure you have filled out <strong>/includes/config.php</strong> with the correct database information before proceeding.
        </p>
        <p>
        When you install, click the submit button only once. 
        </p>

        <form action="create.php" method="post">

        <?php if ( !isset($_GET['tables']) && !isset($_POST['install']) ): ?>

        <h3>Create Database Tables</h3>
        <p>
        Click the button below to create the required tables with the database connection, user, and password filled in your config file. Once it is successful, you may proceed to populating it with the initial data.
        </p>
        <div class="form-row">
            <input type="submit" name="create_tables" value="Create Tables &rarr;">
        </div>
            
        <?php 
        //elseif ( !$done && isset($_POST['create_tables']) ): echo $table_msg;

        else: echo $table_msg; 
        ?>

        <h3>Admin Settings</h3>
        <p>
        Use the browser's back and forward nagivation with caution, and please avoid reloading after you submit any forms. 
        </p>

        <?php echo $setting_msg; ?>

        <div class="form-row">
            <label>Username</label>
            <input type="text" name="username" value="" placeholder="Your login name">
        </div>
        <div class="form-row">
            <label>Password</label>
            <input type="password" name="password" value="" placeholder="Password">
        </div>
        <div class="form-row">
            <label>Email</label>
            <input type="text" name="email" value="" placeholder="Email address">
        </div>
        <div class="form-row">
            <label>Install full path</label>
            <input type="text" name="full_path" value="<?php echo $full_install_path; ?>" placeholder="Full server path">
        </div>
        <div class="form-row">
            <label>Full URL</label>
            <input type="text" name="full_url" value="<?php echo $full_install_url; ?>" placeholder="Full URL">
        </div>
        <div class="form-row">
            <label>Timezone offset</label>
            <input type="text" name="timezone" value="0" placeholder="Offset number">
        </div>
        <div class="form-row">
            <label>Site name</label>
            <input type="text" name="site_name" value="" placeholder="Your blog/journal title">
        </div>
        <div class="form-row">
            <input type="submit" name="install" value="Install &rarr;">
        </div>

        <?php endif ?>

        </form>

<?php include_once 'footer.php'; ?>
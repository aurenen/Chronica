<?php 
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  dash.php
 *  
 *  Dashboard for admin
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

$username = getSettings("username");
$site_name = getSettings("site_name");
$last_update = getLastUpdate();

// TODO: pull dashboard stats

require_once 'includes/admin_header.php'; 
?>

        <h2>Dashboard</h2>
        <p>Welcome, <?php echo $username['set_value']; ?>!</p>

        <h3><?php echo $site_name['set_value']; ?> Stats</h3>
        <table class="stats">
            <tr>
                <td class="label">Version</td>
                <td><?php poweredBy('name'); ?> <?php getVersion(); ?></td>
            </tr>
            <tr>
                <td class="label">Last Updated</td>
                <td><?php echo date('F jS, Y', strtotime($last_update)); ?></td>
            </tr>
            <tr>
                <td class="label">Categories</td>
                <td><?php echo getCountStat('category'); ?></td>
            </tr>
            <tr>
                <td class="label">Entries</td>
                <td><?php echo getCountStat(); ?></td>
            </tr>
        </table>

        <h3>Usage Guide</h3>
        <p>Coming soon.</p>

<?php 
require_once 'includes/admin_footer.php'; 
?>
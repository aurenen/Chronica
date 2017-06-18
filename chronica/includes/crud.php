<?php
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  includes/crud.php
 *  
 *  Functions that handles writing to and reading from database.
 * 
 ************************************************************/

/**
 * adds a new category
 * @param string $name      category name
 * @param string $permalink category permalink slug
 * @param string $desc      category description
 */

$db = db_connect();
include_once 'Parsedown.php';

function addCategory($name, $permalink, $desc) {
    global $db;
    $query = "INSERT INTO `categories` (`name`, `permalink`, `description`) 
              VALUES (:name, :permalink, :description);";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':name', $name, PDO::PARAM_STR, 25);
        $stmt->bindParam(':permalink', $permalink, PDO::PARAM_STR, 25);
        $stmt->bindParam(':description', $desc, PDO::PARAM_STR, 200);

        $stmt->execute();

        $status = true;
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to add category. ' . $ex->getMessage());
        $status = false;
    }

    return $status;
}

function editCategory($id, $name, $permalink, $desc) {
    if (!is_numeric($id))
        return '<div class="warning">Invalid category ID.</div>';
    global $db;
    $query = "UPDATE `categories` 
              SET `name` = :name, `permalink` = :permalink, `description` = :description 
              WHERE `cat_id` = :id;";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':name', $name, PDO::PARAM_STR, 25);
        $stmt->bindParam(':permalink', $permalink, PDO::PARAM_STR, 25);
        $stmt->bindParam(':description', $desc, PDO::PARAM_STR, 200);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $status = true;
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to edit category. ' . $ex->getMessage());
        $status = false;
    }

    return $status;
}

function getCategories() {
    global $db;
    $query = "SELECT * FROM `categories`;";
    $stmt = $db->prepare($query);
    try {
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' div class="warning">ERROR: failed to load categories. ' . $ex->getMessage());
        $result = null;
    }

    return $result;
}

function getCategory($id) {
    if (!is_numeric($id))
        return null;
    global $db;
    $query = "SELECT * FROM `categories` WHERE `cat_id` = :id;";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' div class="warning">ERROR: failed to load category. ' . $ex->getMessage());
        $result = null;
    }

    return $result;
}

function addEntry($title, $desc, $added, $modified, $published, $category, $entry) {
    global $db;
    try {
        $query = "INSERT INTO `entry_meta` (`title`, `description`, `added`, `modified`, `published`)
                  VALUES (:title, :description, :added, :modified, :published);";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR, 100);
        $stmt->bindParam(':description', $desc, PDO::PARAM_STR, 200);
        $stmt->bindParam(':added', $added, PDO::PARAM_STR);
        $stmt->bindParam(':modified', $modified, PDO::PARAM_STR);
        $stmt->bindParam(':published', $published, PDO::PARAM_BOOL);

        $stmt->execute();

        $entry_id = $db->lastInsertId();

        $query2 = "INSERT INTO `entries` (`ent_id`, `markdown`, `html`) VALUES (:id, :entry, :html);";

        $stmt = $db->prepare($query2);
        $stmt->bindParam(':id', $entry_id, PDO::PARAM_INT);
        $stmt->bindParam(':entry', $entry, PDO::PARAM_STR);

        $Parsedown = new Parsedown();
        $html = $Parsedown->text($entry);

        $stmt->bindParam(':html', $html, PDO::PARAM_STR);
        $stmt->execute();

        $query3 = "INSERT INTO `category_has_entry` (`cat_id`, `ent_id`)
                   VALUES (:category, :id);";
        $stmt = $db->prepare($query3);
        $stmt->bindParam(':id', $entry_id, PDO::PARAM_INT);
        foreach ($category as $cat) {
            $stmt->bindParam(':category', $cat, PDO::PARAM_INT);
            $stmt->execute();
        }

        $status = true;
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to add entry. ' . $ex->getMessage());
        $status = false;
    }

    return $status;
}

function getEntriesMeta($offset, $count) {
    global $db;
    $offset *= 2;
    $query = "SELECT `ent_id`, `title`, `description`, `added`, `modified`, `published` FROM `entry_meta` LIMIT :offset, :count;";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':count', $count, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to get paginated entries. ' . $ex->getMessage());
        $result = null;
    }
    
    return $result;
}

function getEntryForEdit($id) {
    global $db;
    $query = "SELECT `entry_meta`.`ent_id`, `entry_meta`.`title`, `entry_meta`.`added`, 
                `entry_meta`.`modified`, `entry_meta`.`published`, `entries`.`markdown`,
                `categories`.`cat_id`, `categories`.`name` FROM `entry_meta`
              JOIN `entries` ON `entry_meta`.`ent_id` = `entries`.`ent_id`
              JOIN `category_has_entry` ON `category_has_entry`.`ent_id` = `entries`.`ent_id`
              JOIN `categories` ON `categories`.`cat_id` = `category_has_entry`.`cat_id`
              WHERE `entry_meta`.`ent_id` = :id";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to get entry for edit. ' . $ex->getMessage());
        $result = null;
    }
    
    return $result;
}

function getEntryCategories($id) {
    global $db;
    $query = "SELECT `cat_id` FROM `category_has_entry` WHERE `ent_id` = :id";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to get entry for edit. ' . $ex->getMessage());
        $result = null;
    }
    
    return $result;
}

function editEntry($id, $title, $desc, $added, $modified, $published, $category, $entry) {
    global $db;
    try {
        // remove old categories, then add new ones
        $remove_cats = "DELETE FROM `category_has_entry` WHERE `ent_id` = :id;";
        $rc_stmt = $db->prepare($remove_cats);
        $rc_stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $rc_stmt->execute();

        $insert_cats = "INSERT INTO `category_has_entry` (`cat_id`, `ent_id`)
                   VALUES (:category, :id);";
        $ic_stmt = $db->prepare($insert_cats);
        $ic_stmt->bindParam(':id', $id, PDO::PARAM_INT);
        foreach ($category as $cat) {
            $ic_stmt->bindParam(':category', $cat, PDO::PARAM_INT);
            $ic_stmt->execute();
        }

        $query = "UPDATE `entry_meta` SET `title` = :title, `description` = :description, `added` = :added, 
                    `modified` = :modified, `published` = :published 
                  WHERE `ent_id` = :id;
                  UPDATE `entries` SET `markdown` = :entry, `html` = :html
                  WHERE `ent_id` = :id;";
        $stmt = $db->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR, 100);
        $stmt->bindParam(':description', $desc, PDO::PARAM_STR, 200);
        $stmt->bindParam(':added', $added, PDO::PARAM_STR);
        $stmt->bindParam(':modified', $modified, PDO::PARAM_STR);
        $stmt->bindParam(':published', $published, PDO::PARAM_BOOL);
        $stmt->bindParam(':entry', $entry, PDO::PARAM_STR);
        $Parsedown = new Parsedown();
        $html = $Parsedown->text($entry);

        $stmt->bindParam(':html', $html, PDO::PARAM_STR);

        $stmt->execute();

        $status = true;
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to edit entry. ' . $ex->getMessage());
        $status = false;
    }

    return $status;
}

function getEntriesForView($cat = 'all') {
    global $db;
    $query = "SELECT `entry_meta`.`ent_id`, `entry_meta`.`title`, `entry_meta`.`added`, 
                `entry_meta`.`modified`, `entry_meta`.`published`, `entries`.`html`,
                `categories`.`cat_id`, `categories`.`name`, `categories`.`permalink` FROM `entry_meta`
              JOIN `entries` ON `entry_meta`.`ent_id` = `entries`.`ent_id`
              JOIN `category_has_entry` ON `category_has_entry`.`ent_id` = `entries`.`ent_id`
              JOIN `categories` ON `categories`.`cat_id` = `category_has_entry`.`cat_id` ";
    if ($cat !== 'all')
        $query .= "WHERE `category_has_entry`.`cat_id` = :category ";
    $query .= "ORDER BY `entry_meta`.`added` DESC";
    $stmt = $db->prepare($query);
    try {
        if ($cat !== 'all')
            $stmt->bindParam(':category', $cat, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    catch (Exception $ex) {
        error_log(date('Y-m-d') . ' ERROR: failed to get entry for edit. ' . $ex->getMessage());
        $result = null;
    }
    
    return $result;
}
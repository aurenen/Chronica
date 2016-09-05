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

        $msg = '<div class="success">Category successfully added.</div>';
    }
    catch (Exception $ex) {
        $msg = '<div class="warning">ERROR: failed to add category. ' . 
                       $ex->getMessage() . '</div>';
    }

    return $msg;
}

function editCategory($id, $name, $permalink, $desc) {
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

        $msg = '<div class="success">Category successfully updated.</div>';
    }
    catch (Exception $ex) {
        $msg = '<div class="warning">ERROR: failed to edit category. ' . 
                       $ex->getMessage() . '</div>';
    }

    return $msg;
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
        $result = '<div class="warning">ERROR: failed to load categories. ' . 
                       $ex->getMessage() . '</div>';
    }

    return $result;
}

function getCategory($id) {
    global $db;
    $query = "SELECT * FROM `categories` WHERE `cat_id` = :id;";
    $stmt = $db->prepare($query);
    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    catch (Exception $ex) {
        $result = '<div class="warning">ERROR: failed to load categories. ' . 
                       $ex->getMessage() . '</div>';
    }

    return $result;
}
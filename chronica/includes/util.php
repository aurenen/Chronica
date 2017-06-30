<?php
/************************************************************
 *
 *  (c) Chronica
 *  https://github.com/aurenen/chronica
 *  
 *  includes/util.php
 *  
 *  Useful general functions to keep things DRY.
 * 
 ************************************************************/

/**
 * Prints credit text/link.
 * @param  string $type values [url|full|link|name]
 * @return void
 */
function poweredBy($type = 'full') {
    $url = 'https://github.com/aurenen/chronica';
    if ($type == 'url')
        echo $url;
    else if ($type == 'full')
        echo 'Powered by <a href="'.$url.'">Chronica</a>';
    else if ($type == 'link')
        echo '<a href="'.$url.'">Chronica</a>';
    else if ($type == 'name')
        echo 'Chronica';
}

/**
 * Prints the version number.
 */
function getVersion() {
    echo 'v0.2.3';
}

/**
 * Check if page is current url for navigation
 * http://blog.aurenen.org/2015/11/php-dynamic-navigationpage-title/
 * 
 * @param  string $title : filename without extension
 * @return boolean 
 */
function is_current($title) {
    $url = $_SERVER['REQUEST_URI'];

    if (strlen($_SERVER['QUERY_STRING']) > 0) 
        $page = (strpos($url, "=") > 0) ? (substr($url, strpos($url, "=") + 1)) : (substr($url, strpos($url, "?") + 1));
    
    else {
        preg_match('~\/(.*?)\.php~', $url, $output);
        $page = $output[1];
        while (strpos($page, "/") !== false)
            $page = substr($page, strpos($page, "/") + 1);
    }

    if ($page == null || strlen($page) == 0) {
        $page = "index";
    }

    if ($page === $title)
        return true;
    else
        return false;
}

// http://webcheatsheet.com/php/get_current_page_url.php
function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
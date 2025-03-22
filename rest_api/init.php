<?
// INIT file loads resources needed by multiple PHP pages in a Web Application.

/******************************************************************************************
Database Connection
******************************************************************************************/
define('DB_SERVER','localhost');
define('DB_USERNAME','csci488_fall22');
define('DB_PASSWORD','db_fun_2022');
define('DB_DATABASE','csci488_fall22');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}

/******************************************************************************************
Database Tables
******************************************************************************************/
define('works_table','shakespeare_works');
define('characters_table','shakespeare_characters');
define('paragraph_table','shakespeare_paragraphs');
define('location_table','shakespeare_scene_locations');
define('PEOPLE_TABLE','tanweer_people');
define('API_TABLE','tanweer_api_log');


/******************************************************************************************
Classes
******************************************************************************************/
require_once 'class_data_operations.php'; // Parent Class for ORM/AR functionality
require_once 'class_lib.php';     // Wrapper for useful utility functions

// Table-specific classes to implement ORM/AR
require_once 'class_people_table.php';
require_once 'api_log_table.php';
// require_once 'class_states_table.php';




/******************************************************************************************
Consolidate and trim $_GET and $_POST super globals
******************************************************************************************/
$get_post    = array_merge($_GET,$_POST);

// No whitespace after the closing php tag below because that would generate script output
// which would be whitespace at the beginning of the HTML code returned to the browser.
?>
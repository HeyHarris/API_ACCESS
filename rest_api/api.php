<?
require 'init.php'; // database connection, etc
header('Access-Control-Allow-Origin: *');                 // Allow Access from any origin
header('Content-Type: application/json; charset=UTF-8');  // JSON payload (not HTML)
// $token_value = $_GET['token']; 
$echo_arr = array();
if(isset($_GET['token'])) {
    $api_token = $_GET['token'];
    $user = new people();
    $user -> load($_GET['token'], 'tanweer_api_key');
    if (trim($user->values['user_email']) == NULL ) {
        $echo_arr['Error Message'] = "You do not have a valid API token, please contact Harris or Professor Knuckles!";
        $json_string = json_encode($echo_arr);

        echo $json_string;
        exit;
      }
} 
else{
    $echo_arr['Error Message'] = "You do not have a API token, please go through the sign up process!";
    $json_string = json_encode($echo_arr);

    echo $json_string;
    exit;
}

$row_arr = [];


if( (isset($_GET['work']) && strlen(trim($_GET['work'])) > 0) && (isset($_GET['act']) && strlen(trim($_GET['act'])) > 0) && (isset($_GET['scene']) && strlen(trim($_GET['scene'])) > 0)) { 
    // when work, act, and scene are in the query string
    $sql = "SELECT * FROM " . paragraph_table . " JOIN shakespeare_scene_locations ON par_work_id=scene_work_id AND scene_act=par_act AND scene_scene = par_scene WHERE par_work_id= " . "'" . $_GET['work'] . "' AND par_act = " . $_GET['act'] . " AND par_scene = " . $_GET['scene'];

    $result = lib::db_query($sql);
    // var_dump($sql);
    while ( $row = $result->fetch_assoc() ) {
        $echo_arr['scene_location'] = $row['scene_location'];
        $echo_arr["paragraphs"][] = [$row["par_number"], $row["par_char_id"], $row["par_text"]];
        // $echo_arr[$row['par_id']]["paragraphs"][] = $row["par_char_id"];
        // $echo_arr[$row['par_id']]["paragraphs"][] = $row["par_text"];
        // do stuff with $row
        // array_push($echo_arr, $row_arr);
}
}
elseif( (isset($_GET['work']) && strlen(trim($_GET['work'])) > 0) ){
    // when query string specifies which work
    $sql = "SELECT * FROM " . location_table . " WHERE scene_work_id=" . "'" . $_GET['work'] . "'";
    $result = lib::db_query($sql);
    while ($row = $result->fetch_assoc()) {
        $row_arr["scene_id"] = $row["scene_id"];
        $row_arr["scene_work_id"] = $row["scene_work_id"];
        $row_arr["scene_act"] = $row["scene_act"];
        $row_arr["scene_scene"] = $row["scene_scene"];
        $row_arr["scene_location"] = $row["scene_location"];
        // do stuff with $row
        array_push($echo_arr, $row_arr);
}
}
else {
    // just work data
    $sql = "SELECT * FROM " . works_table;
    $result = lib::db_query($sql);
    while ($row = $result->fetch_assoc()) {
        $row_arr["work_id"] = $row["work_id"];
        $row_arr["work_title"] = $row["work_title"];
        $row_arr["work_long_title"] = $row["work_long_title"];
        $row_arr["work_year"] = $row["work_year"];
        $row_arr["work_genre"] = $row["work_genre"];
        // do stuff with $row
        array_push($echo_arr, $row_arr);
    }
}
// $echo_arr = $row_arr;
$json_string = json_encode($echo_arr);

// $queryString = $_SERVER['QUERY_STRING'];
// var_dump($queryString);
$current_user = new people();
$current_user -> load($_GET['token'], 'tanweer_api_key');
// var_dump($current_user);
$api_hit = new api_hit();
$api_hit -> values['api_log_user_id'] = $current_user -> get_id_value();
$api_hit -> values['api_log_timestamp'] = time();
$api_hit -> values['api_log_ip_address'] =  $_SERVER['REMOTE_ADDR'];
$api_hit -> values['api_log_query'] = $_SERVER['QUERY_STRING'];
$api_hit -> save();

echo $json_string;
exit;
?>
<!-- Thank You page -->
<? 
require 'init.php'; // database connection, etc

echo "Welcome to the Final Destination <br>"; 
$user = new people();
$user->load($_GET['user_id']);

// var_dump($user);


echo "Here is the data we store, not inculding password<br>"; 
echo "<br><hr>";
echo "User_Id: " . $_GET["user_id"] . "<br>";
echo "User_Name: " . $user->values["user_name"] . "<br>";
echo "User_Email: " . $user->values["user_email"] . "<br>";
echo "User_Timestamp: " . $user->values["user_timestamp"] . "<br>";
echo "User_IP_Address: " . $user->values["user_ipaddress"] . "<br>";
echo "User_IP_Address: " . $user->values["tanweer_api_key"] . "<br>";

// echo $_GET["user_id"];
?>
<br><br>
<a href="profile_edit.php?task=edit&user_id=<?=$_GET["user_id"]?>">Edit Profile</a>

<br><br>
<a href="page_form.php">Go To Signup Page</a>

<br><br>
<!-- <a href="https://csci.lakeforest.edu/~tanweerh/csci488/ajax_prac.html">Go To Shakespeare Api </a> -->

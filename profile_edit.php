<!-- Thank You page -->
<? 
require 'init.php'; // database connection, etc

echo "Welcome to the Final Destination <br>"; 
$user = new people();
$user->load($_GET['user_id']);
$form_name = $user->values["user_name"];
$form_email = $user->values["user_email"];
$form_password = $user->values["user_password"];

// var_dump($user);
// echo "<br><br><br>";

$task = $get_post['task'];
if($task == "save") {
    $user_update = new people();
    $user_update->load($get_post['user_id']);
    
    // echo "testttttt" . $get_post['user_id'] . "<br><br>";
    if($_POST['name_input']){
        $user_update -> values["user_name"] = $_POST['name_input'];
    }
    if($_POST['email_input']){
        $user_update -> values["user_email"] = $_POST['email_input'];
    }
    if($_POST['password_input']){
        $user_update -> values["user_password"] = hash('sha256', $_POST['password_input']);
    }
    // var_dump($user_update);
    $user_update->save();
    header ("Location:page_thank_you.php?user_id=" . $get_post['user_id']);
}
// var_dump($user);


echo "Here is the profile data for this specific user<br>"; 
echo "<br><br>";

?>
<form name="main_form" action="profile_edit.php" method="POST">
<input type="hidden" name="task" value="save">
<input type="hidden" name="user_id" value="<?= $_GET['user_id'] ?>">

Enter Your Username: 
<input type="text" name="name_input" value="<?= $form_name ?>">
<br><br>
Enter Your Email: 
<input type="email" name="email_input" value="<?= $form_email ?>">
<br><br>
Enter Your New Password:
<input type="password" name="password_input">

<br><br>
<!-- <a href="page_form.php?task=edit&user_id="<?=$_GET["user_id"]?>>Edit Profile</a> -->
<input type="submit" value=" Submit ">
<input type="reset" value=" Reset ">
<br><br>
<a href="page_thank_you.php">Go back to the Final Destination Page</a>

<br><br>
<!-- <a href="https://csci.lakeforest.edu/~tanweerh/csci488/ajax_prac.html">Go To Shakespeare Api </a> -->

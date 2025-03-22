<?
require 'init.php'; // database connection, etc
// session_start() -> already in init.php
// $_SESSION["name"] = ""blagh"


$task = $get_post['task'];
switch ($task) {
    case 'save':
        // Save the form data then transfer to the listing page

        $ppl = new people();
        $ppl->load_from_form_submit();
        $ppl -> values['user_timestamp'] = time();
        $ppl -> values['user_ipaddress'] = $_SERVER['REMOTE_ADDR'];
        $ppl -> values['tanweer_api_key'] = hash('md5', $ppl -> values['user_email'] . $ppl -> values['user_timestamp'] . 'wunderpuss');
      
      // var_dump($/ppl);
        // Load the incoming form data into an ORM object
        // The names of the object's fields MUST match the names of the form elements


        // Framework will automatically addslashes() (escape reserved SQL chars), 
        // so no need to manually do that here.

         // Name lenght validation
         //var_dump(strlen(trim($ppl->values['user_name'])));exit;
        if ( strlen(trim($ppl->values['user_name'])) < 2 ) {
          $message = "You must provide a longer name!";
          break; // fall back to form without saving
        }

        // Email lenght validation
         //var_dump(strlen(trim($ppl->values['user_name'])));exit;
         $ppl2 = new people();
         $ppl2->load(trim($ppl->values['user_email']) ,'user_email');
         $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
   
         if ( trim($ppl2->values['user_email']) ) {
            $message = "This email is already in use";
            break; // fall back to form without saving
         }
         if (!preg_match($pattern, $ppl->values['user_email'])) {
          $message = "You must provide a valid email!";
          break; // fall back to form without saving
        }



         // Password lenght validation
         //var_dump(strlen(trim($ppl->values['user_name'])));exit;
         if ( strlen(trim($ppl->values['user_password'])) < 5 ) {
            $message = "You must provide a longer password!";
            break; // fall back to form without saving
          }

         // Password verification validation
      //var_dump($_POST['verify_password']);exit;
        if ( $ppl -> values['user_password'] != $_POST['verify_password'] ) {
         $message = "Passwords are not matching!";
         break; // fall back to form without saving
       } else {
         $ppl -> values['user_password'] = hash('sha256', $_POST['user_password']);
       }

      //var_dump(load('user_email'));exit;




        // Save the contents of the object to the database.
      //  var_dump($ppl);
        $ppl->save();
        $id_value = $ppl->get_id_value();
        // echo $id_value;
        // $ppl->load();
        // var_dump($ppl);
        $logon_state = new logon_state();
        $login_token ="'" . hash('md5', $user -> values['user_email'] . time() . 'greenzebra') . "'";
        $logon_state -> set_id_value($login_token);
        // $logon_state -> values['login_token'] = hash('md5', $user -> values['user_email'] . $user -> values['user_timestamp'] . 'greenzebra');
        // echo $user -> values['user_id'] . "lololol"; 
        $logon_state -> values['login_id'] = $user -> get_id_value();
        $timestamp = time();
        $logon_state -> values['login_timestamp_start'] = $timestamp;
        $logon_state -> values['login_timestamp_mod'] = $timestamp;
        $logon_state -> values['login_ip_address'] = $_SERVER['REMOTE_ADDR'];
        
        $logon_state->save();
        // $id_value = $user -> values['user_id'];

        header ("Location: page_thank_you.php?user_id=" . $id_value);
        exit;
        break;

    ///////////////////////////////////////////////////////////////////
    case 'delete':

       if ( isset($get_post['ppl_id']) && $get_post['ppl_id'] > 0 ) {
          $ppl = new people();
          $ppl->delete($get_post['ppl_id']);
       }

       header ("Location: page_listing.php?deleted_message=yes");
       exit;
       break;

    ///////////////////////////////////////////////////////////////////
    case 'edit':
       // Edit an existing database record

       if ( ! isset($get_post['ppl_id']) ) {
          // No incoming ppl_id so just fall through to empty form
          break;
       }

       $entry_id = $get_post['entry_id'];

       $ppl = new people();
       $ppl->load($entry_id);  // $ppl now an Active Record object for one database record.


       $ppl->html_safe();  // runs htmlentities() on all object fields
                           // to escape characters that are reserved in HTML
                           // Have to do this after json_decode() or will break the json

       break;

    ///////////////////////////////////////////////////////////////////
    default:
      // No incoming task gives empty form
}

// Common Page Top for all Application Pages
require 'ssi_top.php';
?>

This application template uses an Object Relational Mapping (ORM) framework.
<br>
You will notice that there is no SQL in any of the PHP files that generate HTML pages.
<br>
All the SQL is in the "data operations class" or the "table-specific classes" that extend the data operations class.

<br><br>
<!-- <a href="page_listing.php">Go To Listing Page</a> -->
<br><br>

<? if ($message) { ?>
  <div style="color:red;"><?=$message?></div><br>
<? } ?>

<script language="javascript">
//https://martech.zone/javascript-password-strength/
// More characters – If the length is under 8 characters.
// Weak – If the length is less than 10 characters and doesn’t contain a combination of symbols, caps, text.
// Medium – If the length is 10 characters or more and has a combination of symbols, caps, text.
// Strong – If the length is 14 characters or more and has a combination of symbols, caps, text.
    function passwordChanged() {
        var strength = document.getElementById('strength');
        strength.innerHTML = '<span style="color:blue">Type Password</span>';
        var strongRegex = new RegExp("^(?=.{14,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        var mediumRegex = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
        var enoughRegex = new RegExp("(?=.{8,}).*", "g");
        var pwd = document.getElementById("user_password");
        if (pwd.value.length == 0) {
            strength.innerHTML = 'Type Password';
        } else if (false == enoughRegex.test(pwd.value)) {
            strength.innerHTML = 'More Characters';
            return false;
        } else if (strongRegex.test(pwd.value)) {
            strength.innerHTML = '<span style="color:green">Strong!</span>';
            return true;
        } else if (mediumRegex.test(pwd.value)) {
            strength.innerHTML = '<span style="color:orange">Medium!</span>';
            return true;
        } else {
            strength.innerHTML = '<span style="color:red">Weak!</span>';
            return false;
        }
    }
//     function ValidateEmail(input) {

// var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
// var validEmail = document.getElementById('valid_email');
// if (input.value.match(validRegex)) {
//   validEmail.innerHTML = '<span style="color:green">VALID EMAIL</span>';
//   document.form1.user_email.focus();
//   return true;

// } else {
//   alert("Error");
//   validEmail.innerHTML = '<span style="color:red">INVALID EMAIL!</span>';
//   document.form1.user_email.focus();

//   return false;

// }

// }
</script>

<!-- All the form element names (except task) match the DB table names  -->
<h4>So far I have added a more complex email validation and password tester</h2>
<form name="form1" action="page_form.php" method="POST">
   <input type="hidden" name="task" value="save">
   <input type="hidden" name="user_id" value="<?= $user_id ?>">

   Name: <input type="text" name="user_name" value="<?= $ppl->values['user_name'] ?>">
   <br>
   Email: <input type="text" name="user_email" value="<?= $ppl->values['user_email'] ?>">
   <span id="valid_email">&nbsp &nbsp Type Email</span>
   <br>
   Password: <input id="user_password" type="password" onkeyup="return passwordChanged();" name="user_password" value="<?= $ppl->values['user_password'] ?>">
   <span id="strength">&nbsp &nbsp Type Password</span>
   <br>
   Verify Password: <input type="password" name="verify_password">


    
   <br><br>
   <button type="submit"> Submit </button>
   <button type="reset"> Reset </button>
   <br>
   <a class="link" href="logon.php">Already have an account? Login</a>
</form>


<?
// Common Page Top for all Application Pages
require 'ssi_bottom.php';
?>
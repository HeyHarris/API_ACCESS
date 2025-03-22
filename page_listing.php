<?
require 'init.php'; // database connection, etc

// Static Method - don't need new people object.
$people_data = people::get_people_and_states();   


// Common Page Top for all Application Pages
require 'ssi_top.php';
?>

<br>
<a href="page_form.php">Go To New Person Form</a>
<br><br>

<? if ( isset($get_post['deleted_message']) ) { ?>
    <b>The DB record was sucessfuly deleted.</b>
    <br><br>
<? } ?>

<? if (count($people_data) ==0) { ?>
  <b>No records were found in the database.</b>
<? } else { ?>

  <b>Listing of Database Records:</b>

  <table width="" border="1" cellspacing="0" cellpadding="5">
  <tr  valign="top">
      <td>Name</td>
      <td>Age</td>
      <td>States Visited</td>
      <td>&nbsp;</td>
   </tr>
   <? foreach ( $people_data as $ppl_id => $person ) { ?>
      <tr  valign="top">
         <td><?= $person['ppl_name'] ?></td>
         <td><?= $person['ppl_age'] ?></td>
         <td><?= implode(', ',$person['states_visited']) ?></td>
         <td>
            <a href="page_form.php?task=edit&ppl_id=<?=$ppl_id?>">Edit</a>
            <br>
            <br>
            <a href="#null" onclick="confirm_delete(<?=$ppl_id?> , '<?=$person['ppl_name']?>')">Delete</a>
         </td>
      </tr>
   <? } // end while ?>
   </table>

<? } // end else ?>

<script type="text/javascript">

  function confirm_delete(ppl_id, ppl_name) {
    var choice = confirm("Are you sure you want to delete " + ppl_name + "?");

    if ( choice == true ) {
      window.location.href = "page_form.php?task=delete&ppl_id="+ppl_id;
    }
  }
</script>


<?
// Common Page Top for all Application Pages
require 'ssi_bottom.php';
?>
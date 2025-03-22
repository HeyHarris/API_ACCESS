<?php
// Table-specific class - extends the data_operations class to apply to a specific database table.

class logon_state extends data_operations {

  // Constructor - must have same name as class.
  function logon_state() {

    $table = LOGON_TABLE;                // Constant defined in init.php
    $id_field = 'login_token';                // Primary Key field
    $id_field_is_ai = false;              // Is Primary Key Auto Increment?
    $fields = array(                      // Array of all non-PK fields
      'login_id',
      'login_timestamp_start',
      'login_timestamp_mod',
      'login_ip_address'
    );

    // Parent class constructor
    // Sending it table-specific information enables this class to generate Active Record objects.
    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }

  /*
    A Table-specific class serves two main purposes:

    Active Record functionality:
      Inherits all methods in class_data_operations: load(), save(), load_from_form_submit(), etc
      that provide core data operations for 1 Active Record - an object that operates on a db record.

    Other Data Operations related to the specific table:
      More complex data operations (JOINS, etc) require custom methods such as below.
      Division of Labor: keep database/SQL logistics separate from the application logic in the PHP files that generate HTML.
  */

  ////////////////////////////////////////////////////////////////////////////////////
  // static function get_states_assoc_array() {
  //   // Returns an associative array: keys are state abbreviations,  values are state names.

  //   $query = "SELECT *
  //               FROM " . STATES_TABLE ."
  //               WHERE 1  ORDER BY st_name ASC";
  //   $result = lib::db_query($query);

  //   $states = array();
  //   while ( $row = $result->fetch_assoc() ) {
  //     $states[$row['st_abbr']] = $row['st_name'];
  //   }
  //   return $states;
  // }


} //end class

?>
<?php
// Table-specific class - extends the data_operations class to apply to a specific database table.

class api_hit extends data_operations {

  // Constructor - must have same name as class.
  function api_hit() {

    $table = API_TABLE;              // Constant defined in init.php
    $id_field = 'api_log_id';               // Primary Key field
    $id_field_is_ai = true;             // Is Primary Key Auto Increment?
    $fields = array(                    // Array of all non-PK fields
      'api_log_user_id',
      'api_log_timestamp',
      'api_log_ip_address',
      'api_log_query'
    );

    // Parent class constructor
    // Sending it table-specific information enables this class to generate Active Record objects.
    parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
  }

  /*
    This class inherits all methods in class_data_operations: load(), save(), load_from_form_submit(), etc
    That functionality is sufficient for operations on 1 Active Record.

    More complex data operations (JOINS, etc) require custom methods such as below.
    A table-specific ORM class such as this should contain all related data operations.
    This keeps the database logistics separate from the application logic in the PHP files that generate HTML.
  */




} //end class

?>

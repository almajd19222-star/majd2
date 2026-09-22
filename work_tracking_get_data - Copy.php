 
 
 <?php
 include_once "inc/session.php";
 include_once('inc/config_server.php' );
$table = 'work_tracking'; 
$primaryKey = "id";

/* $columns = array(
  array( 'db' => '`t1`.`id`',       'dt' => 'id', 'field' => 'id' ),
  array( 'db' => '`t1`.`id`',       'dt' => 'id', 'field' => 'id' ),
  array( 'db' => '`t1`.`status`',       'dt' => 'status', 'field' => 'status' ),
  array( 'db' => '`t1`.`study_for`',       'dt' => 'study_for', 'field' => 'study_for' ),
  array( 'db' => '`t1`.`requested_jeha`',       'dt' => 'requested_jeha', 'field' => 'requested_jeha' ),
  array( 'db' => '`t1`.`send_to`',       'dt' => 'send_to', 'field' => 'send_to' ),
  array( 'db' => '`t1`.`ketab_num_wared`',       'dt' => 'ketab_num_wared', 'field' => 'ketab_num_wared' ),
  array( 'db' => '`t1`.`handle_from_date`',       'dt' => 'handle_from_date', 'field' => 'handle_from_date' ),
  array( 'db' => '`t1`.`ketab_num_sader`',       'dt' => 'ketab_num_sader', 'field' => 'ketab_num_sader' ),
  array( 'db' => '`t1`.`takleef_date`',       'dt' => 'takleef_date', 'field' => 'takleef_date' ),
  array( 'db' => '`t1`.`takleef_name`',       'dt' => 'takleef_name', 'field' => 'takleef_name' ),
  array( 'db' => '`t1`.`ketab_num_reply`',       'dt' => 'ketab_num_reply', 'field' => 'ketab_num_reply' ),
  array( 'db' => '`t1`.`reply_date`',       'dt' => 'reply_date', 'field' => 'reply_date' ),
  array( 'db' => '`t1`.`ketab_num_to`',       'dt' => 'ketab_num_to', 'field' => 'ketab_num_to' ),
  array( 'db' => '`t1`.`handle_to_date`',       'dt' => 'handle_to_date', 'field' => 'handle_to_date' ),
  array( 'db' => '`t1`.`notes`',       'dt' => 'notes', 'field' => 'notes' )
); */

$columns = array(
	array( 'db' => 'id', 'dt' => 'id' ),
	array( 'db' => 'id',  'dt' => 'id' ),
	array( 'db' => 'status',   'dt' => 'status' ),
	array( 'db' => 'study_for',     'dt' => 'study_for' ),
  array( 'db' => 'requested_jeha',     'dt' => 'requested_jeha' ),
  array( 'db' => 'send_to',     'dt' => 'send_to' ),
  array( 'db' => 'ketab_num_wared',     'dt' => 'ketab_num_wared' ),
  array( 'db' => 'handle_from_date',     'dt' => 'handle_from_date' ),
  array( 'db' => 'ketab_num_sader',     'dt' => 'ketab_num_sader' ),
  array( 'db' => 'takleef_date',     'dt' => 'takleef_date' ),
  array( 'db' => 'takleef_name',     'dt' => 'takleef_name' ),
  array( 'db' => 'ketab_num_reply',     'dt' => 'ketab_num_reply' ),
  array( 'db' => 'reply_date',     'dt' => 'reply_date' ),
  array( 'db' => 'ketab_num_to',     'dt' => 'ketab_num_to' ),
  array( 'db' => 'handle_to_date',     'dt' => 'handle_to_date' ),
  array( 'db' => 'notes',     'dt' => 'notes' ),
  array( 'db' => 'id_num',     'dt' => 'id_num' )
	
);


/* $joinQuery = "FROM `$table` AS `t1`";
$extraWhere = "";
$groupBy = "";
$having = "";


require( 'ssp.customized.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
); */



require( 'ssp.class.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns )
);


?>

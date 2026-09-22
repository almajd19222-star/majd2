 <?php
 include_once "inc/session.php";
 include_once('inc/config_server.php' );
 //include_once('inc/footer_k_pub_server.php' );
 
$td_class = "show-read-more"; 

$table = 'studies_it_shops'; 
$primaryKey = "id";

$columns = array(
  
  array( 'db' => '`t1`.`id`',                     'dt' => 0, 'formatter' => function( $d, $row ) {
    return '<a href="delete.php?id='.$d.'&delete_studies=true"><i style="font-size: 2rem" class="zwicon-delete"></i></a>';},'field' => 'id' ),

  array( 'db' => '`t1`.`id`',                     'dt' => 1, 'formatter' => function( $d, $row ) {
    return '<a href="studies.php?id='.$d.'&edit=1&details_type='.$row['3'].'&type_code=ES&edit_process=0"><i style="font-size: 2rem" class="zwicon-edit-circle"></i></a>';},'field' => 'id' ),
  
  array( 'db' => 'id',                            'dt' => 2, 'formatter' => function( $d, $row ) {
    return '<a href="print-elements.php?id='.$d.'&type=ES"><i style="font-size: 2rem" class="zwicon-printer"></i></a>';},'field' => 'id' ),

  array( 'db' => '`t1`.`details_type`',           'dt' => 3, 'field' => 'details_type' ),
	array( 'db' => '`t1`.`jeha`',                   'dt' => 4, 'field' => 'jeha' ),
	array( 'db' => '`t1`.`ketab_num`',              'dt' => 5, 'field' => 'ketab_num'),
  array( 'db' => '`t1`.`ketab_date`',             'dt' => 6, 'field' => 'ketab_date', 'formatter' => function( $d, $row ) {
      return date( 'Y-m-d', strtotime($d));
  }),
	array( 'db' => '`t1`.`general_code`',           'dt' => 7, 'field' => 'general_code' ),
  array( 'db' => '`t1`.`result`',                 'dt' => 8, 'field' => 'result' ),
  array( 'db' => '`t1`.`suggestion`',             'dt' => 9, 'field' => 'suggestion' ),
  array( 'db' => '`t1`.`source`',                 'dt' => 10, 'field' => 'source' ),
	array( 'db' => '`t1`.`name`',                   'dt' => 11, 'field' => 'name' ),
  array( 'db' => '`t1`.`fname`',                  'dt' => 12, 'field' => 'fname' ),
  array( 'db' => '`t1`.`lname`',                  'dt' => 13, 'field' => 'lname' ),
	array( 'db' => '`t1`.`place_address`',           'dt' => 14, 'field' => 'place_address' ),
	array( 'db' => '`t1`.`longitude`',              'dt' => 15, 'field' => 'longitude' ),
	array( 'db' => '`t1`.`latitude`',               'dt' => 16, 'field' => 'latitude' ),
	array( 'db' => '`t1`.`partners_name`',          'dt' => 17, 'field' => 'partners_name' ),
  array( 'db' => '`t1`.`socialmedia`',            'dt' => 18, 'field' => 'socialmedia' ),
	array( 'db' => '`t1`.`capital`',                'dt' => 19, 'field' => 'capital' ),
  array( 'db' => '`t1`.`cameras`',                'dt' => 20, 'field' => 'cameras' ),
	array( 'db' => '`t1`.`job_type`',               'dt' => 21, 'field' => 'job_type' ),
    
  array( 'db' => '`t1`.`goods_type`',             'dt' => 22, 'field' => 'goods_type' ),
  array( 'db' => '`t1`.`license`',                'dt' => 23, 'field' => 'license' ),
  array( 'db' => '`t1`.`security_record`',        'dt' => 24, 'field' => 'security_record' ),
  array( 'db' => '`t1`.`other_branches`',         'dt' => 25, 'field' => 'other_branches' ),
  array( 'db' => '`t1`.`importation_place`',      'dt' => 26, 'field' => 'importation_place' ),
  array( 'db' => '`t1`.`cooperation`',            'dt' => 27, 'field' => 'cooperation' ),
  array( 'db' => '`t1`.`suspect_dealing`',        'dt' => 28, 'field' => 'suspect_dealing' ),
  array( 'db' => '`t1`.`circuit_sell`',           'dt' => 29, 'field' => 'circuit_sell' ),
  array( 'db' => '`t1`.`notable_customers`',      'dt' => 30, 'field' => 'notable_customers' ),
  array( 'db' => '`t1`.`most_sell_devices`',      'dt' => 31, 'field' => 'most_sell_devices' ),
  array( 'db' => '`t1`.`work_rank`',              'dt' => 32, 'field' => 'work_rank' ),
  array( 'db' => '`t1`.`suspicious_relations`',   'dt' => 33, 'field' => 'suspicious_relations' ),
  array( 'db' => '`t1`.`develop_ability`',        'dt' => 34, 'field' => 'develop_ability' ),
  array( 'db' => '`t1`.`criminal_record`',        'dt' => 35, 'field' => 'criminal_record' ),
  array( 'db' => '`t1`.`additional_information`', 'dt' => 36, 'field' => 'additional_information' ),

  array( 'db' => '`t1`.`added_by`',               'dt' => 37, 'field' => 'added_by' ),
  array( 'db' => '`t1`.`add_date`',               'dt' => 38, 'field' => 'add_date' )
);
 

$joinQuery = "FROM `$table` AS `t1` ";



/* $extraWhere = "IF(t1.jeha = '$jeha_profile' OR t1.origin_sendfrom = '$jeha_profile' , t1.isPrivate!='', t1.isPrivate='لا') 
AND IF($admin=6 ,t1.origin_sendfrom = '$jeha1' , t1.jeha = '$jeha1') 
AND IF('$gettype'='personal' , t1.ketab_type='personal', t1.ketab_type!='') 
AND IF('$gettype'='public' , t1.ketab_type='public', t1.ketab_type!='')                               
AND IF('$getview'='all' , t1.details_type !='' , t1.details_type = '$details_type')"; */
                               


$extraWhere = "";
$groupBy = "";
$having = "";


require( 'ssp.customized.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
);
?>

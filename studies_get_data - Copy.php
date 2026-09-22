 
 
 <?php
 include_once "inc/session.php";
 include_once('inc/config_server.php' );
 //include_once('inc/footer_k_pub_server.php' );
 
$sql_row_jehat = $_GET['sql_row_jehat'];
$jeha_profile = $_GET['jeha_profile'];
$admin = $_GET['admin'];
$jeha = $_GET['jeha'];
$details_type = $_GET['details_type'];

$table = 'studies_all'; 
$primaryKey = "id";

$columns = array(
  
  array( 'db' => '`t1`.`id`',                     'dt' => 0, 'formatter' => function( $d, $row ) {
    if($row['3']=='صادر'){
      return '';
    }else{
      $hasPerm_delete = $_GET['hasPerm_delete'];
      if($hasPerm_delete == '1'){
      return '<a href="delete.php?id='.$d.'&type='.$row['5'].'&general_code='.$row['6'].'&delete_studies=true" onclick="'."return confirm('متأكد من الحذف؟');".'"><i style="font-size: 2rem" class="zwicon-delete"></i></a>';
      }else{
        return '';
      }
    }
  },'field' => 'id' ),

  array( 'db' => '`t1`.`id`',                     'dt' => 1, 'formatter' => function( $d, $row ) {
    return '<a href="studies.php?id='.$d.'&edit=1&details_type='.$row['3'].'&type_code='.$row['5'].'&edit_process=0"><i style="font-size: 2rem" class="zwicon-edit-circle"></i></a>';},'field' => 'id' ),
  
  array( 'db' => 'id',                            'dt' => 2, 'formatter' => function( $d, $row ) {
    return '<a target="_blank" href="print-elements.php?id='.$d.'&jeha='.$row['4'].'&details_type='.$row['3'].'&type='.$row['5'].'&edit=1"><i style="font-size: 2rem" class="zwicon-printer"></i></a>';},'field' => 'id' ),

  array( 'db' => '`t1`.`details_type`',           'dt' => 3, 'field' => 'details_type' ),
	array( 'db' => '`t1`.`jeha`',                   'dt' => 4, 'field' => 'jeha' ),
  array( 'db' => '`t1`.`type_code`',           'dt' => 5, 'field' => 'type_code' ),
	array( 'db' => '`t1`.`general_code`',           'dt' => 6, 'field' => 'general_code' ),
	array( 'db' => '`t1`.`ketab_num`',              'dt' => 7, 'field' => 'ketab_num'),
  array( 'db' => '`t1`.`ketab_date`',             'dt' => 8, 'field' => 'ketab_date', 'formatter' => function( $d, $row ) {
      return date( 'Y-m-d', strtotime($d));
  }),
  
 
	array( 'db' => '`t1`.`name`',                   'dt' => 9, 'field' => 'name' ),
  array( 'db' => '`t1`.`fname`',                  'dt' => 10, 'field' => 'fname' ),
  array( 'db' => '`t1`.`lname`',                  'dt' => 11, 'field' => 'lname' ),	
  array( 'db' => '`t1`.`personal_code`',                  'dt' => 12, 'field' => 'personal_code' ),
  array( 'db' => '`t1`.`place_name`',                  'dt' => 13, 'field' => 'place_name' ),
  array( 'db' => '`t1`.`place_address`',                  'dt' => 14, 'field' => 'place_address' ),
  array( 'db' => '`t1`.`partners_name`',                  'dt' => 15, 'field' => 'partners_name' ),
	array( 'db' => '`t1`.`longitude`',              'dt' => 16, 'field' => 'longitude' ),
	array( 'db' => '`t1`.`latitude`',               'dt' => 17, 'field' => 'latitude' ),
  array( 'db' => '`t1`.`license`',                  'dt' => 18, 'field' => 'license' ),
  array( 'db' => '`t1`.`socialmedia`',                  'dt' => 19, 'field' => 'socialmedia' ),
  array( 'db' => '`t1`.`cooperation`',                  'dt' => 20, 'field' => 'cooperation' ),
  array( 'db' => '`t1`.`cameras`',                  'dt' => 21, 'field' => 'cameras' ),
  array( 'db' => '`t1`.`additional_information`', 'dt' => 22, 'field' => 'additional_information' ),
  array( 'db' => '`t1`.`result`',                 'dt' => 23, 'field' => 'result' ),
  array( 'db' => '`t1`.`suggestion`',             'dt' => 24, 'field' => 'suggestion' ),
  array( 'db' => '`t1`.`source`',                 'dt' => 25, 'field' => 'source' ),
  array( 'db' => '`t1`.`added_by`',               'dt' => 26, 'field' => 'added_by' ),
  array( 'db' => '`t1`.`add_date`',               'dt' => 27, 'field' => 'add_date' )
);
 

$joinQuery = "FROM `$table` AS `t1` ";

if (!empty($details_type)){
  $extraWhere = " t1.jeha = '$jeha' AND t1.details_type = '$details_type'";

 

}else{

  
  if (empty($details_type)){
    $extraWhere1 ="( t1.jeha = '$jeha_profile'  ";
  
     $extraWhere2= $sql_row_jehat.' )';
      
  
      $extraWhere=$extraWhere1.$extraWhere2;
    }

}

/* $extraWhere = "IF(t1.jeha = '$jeha_profile' OR t1.origin_sendfrom = '$jeha_profile' , t1.isPrivate!='', t1.isPrivate='لا') 
AND IF($admin=6 ,t1.origin_sendfrom = '$jeha1' , t1.jeha = '$jeha1') 
AND IF('$gettype'='personal' , t1.ketab_type='personal', t1.ketab_type!='') 
AND IF('$gettype'='public' , t1.ketab_type='public', t1.ketab_type!='')                               
AND IF('$getview'='all' , t1.details_type !='' , t1.details_type = '$details_type')"; */
                               


//$extraWhere = "";
$groupBy = "";
$having = "";


require( 'ssp.customized.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
);
?>

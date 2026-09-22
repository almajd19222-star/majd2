 

 <?php
 require( 'inc/session.php' );
 require( 'inc/config_server.php' );
 $sql_details = array(
    'host' => $servername,
    'user' => $username,
    'pass' => $password,
    'db'   => $dbname
  );
  
$jeha1=$_SESSION["jeha1"]; 
$details_type=$_SESSION["details_type"];

$table = 'study'; 
$primaryKey = "id";

$columns = array(
  


	array( 'db' => '`t1`.`id`', 'dt' => 0, 'formatter' => function( $d, $row ) {
    $jeha1=$_SESSION["jeha1"]; 
    $details_type=$_SESSION["details_type"];

    if($details_type !== 'صادر'){
      return '<a href="delete.php?id='.$details_type.'&delete_study=true" onclick="'."return confirm('متأكد من الحذف؟ سيتم حذف بيانات الدراسة');".'"><i style="font-size: 2.5rem" class="zwicon-delete"></i></a>';
    }else{
      return '';
    }
  },'field' => 'id' ),
  array( 'db' => '`t1`.`id`', 'dt' => 1, 'formatter' => function( $d, $row ) {
    if($details_type == 'وارد'){
      return '<a href="study_cp_to_processing.php?id='.$d.'" onclick="'."return confirm('متأكد من نسخ البيانات المحددة لقيد المعالجة؟');".'"><i style="font-size: 2.5rem" class="zwicon-copy"></i></a>';
    }else{
      return '';
    }
  },'field' => 'id' ),
  array( 'db' => '`t1`.`id`', 'dt' => 2, 'formatter' => function( $d, $row ) {
    if($details_type == 'وارد'){
      return '<a target="_blank" href="print-elements.php?id='.$d.'&type=study"><i style="font-size: 2.5rem" class="zwicon-printer"></i></a>';
    }else{
      return '';
    }
  },'field' => 'id' ),
  array( 'db' => '`t1`.`id`', 'dt' => 3, 'formatter' => function( $d, $row ) {
    return '<a href="s_edit.php?id='.$d.'"><i style="font-size: 2rem" class="zwicon-edit-circle"></i></a>';},
	'field' => 'id' ),

  array( 'db' => '`t1`.`jeha`', 'dt' => 4, 'formatter' => function( $d, $row ) {
    
      return $d;
    
  },'field' => 'jeha' ),
  
	array( 'db' => '`t1`.`sendfrom`',   'dt' => 5, 'field' => 'sendfrom' ),
	array( 'db' => '`t1`.`sendto`',   'dt' => 6, 'field' => 'sendto' ),

  array( 'db' => '`t1`.`id_num`', 'dt' => 7, 'formatter' => function( $d, $row ) {
    if (strpos($jeha_profile, 'داخلي') !== false || strpos($jeha_profile, 'هيئة الرقابة والتفتيش') !== false || $jeha_profile == 'الإدارة المركزية للمعلومات'){
      return $d;
    }else{
      return '';
    }
  },'field' => 'id' )
);
 

$joinQuery = "FROM `study` AS `t1`";

$extraWhere = "";


//$groupBy = "`t2`.`jeha`";
$having = "";


require( 'ssp.customized.php' );

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
);
?>
<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php include_once "columns_names.php"; ?>
<?php

$sql="SET FOREIGN_KEY_CHECKS = 0";
mysqli_query($conn, $sql);

 @$id = $_GET['id'];  
 @$eid = $_GET['e_id'];
 @$inserted_edbara_num = $_GET['edbara_num'];
 @$inserted_edbara_date = $_GET['edbara_date'];
 @$inserted_jeha = $_GET['jeha'];
 @$details_type = $_GET['details_type'];
 @$inserted_year = $_GET['e_year'];
 @$inserted_resala_type = $_GET['resala_type'];
 @$inserted_ketab_num = $_GET['ketab_num'];
 $details_type_new = 'صادر';
 $added_by_old = $_GET['added_by_old'];

 $sql_d_id = "SELECT d_id FROM dewan where d_id=(SELECT max(d_id) FROM dewan WHERE details_type='$details_type_new' AND jeha='$jeha_profile')";
$result_d_id = mysqli_query($conn, $sql_d_id);
$row_d_id = mysqli_fetch_assoc($result_d_id);

if ($row_d_id > 0) {   
    $d_id_new = $row_d_id['d_id']+1;  
}else { $d_id_new = 1;}

 //get last number of e_id in database
 $sql_e_id = "SELECT id FROM details where id=(SELECT max(id) FROM details WHERE details_type='$details_type_new' AND jeha='$jeha_profile')";
 $result_e_id = mysqli_query($conn, $sql_e_id);
 $row_e_id = mysqli_fetch_assoc($result_e_id);

 if($row_e_id > 0) {     
   $e_id_new = $row_e_id['id']+1;  
 }else { $e_id_new = 1;}

//get last number of edbara_num in database
$sql_edbara_num_max = "SELECT edbara_num  FROM details where edbara_num=(SELECT max(`edbara_num`) FROM details WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(edbara_date)=$year)";
$result_edbara_num_max = mysqli_query($conn, $sql_edbara_num_max);
$row_edbara_num_max = mysqli_fetch_assoc($result_edbara_num_max);

if($row_edbara_num_max > 0) {     
  $edbara_num_max = $row_edbara_num_max['edbara_num']+1;  
}else { $edbara_num_max = 1;}


//////////////////////////////
$sql_dewan_num_max = "SELECT dewan_num FROM dewan where dewan_num=(SELECT max(dewan_num) FROM dewan WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(dewan_date)=$year)";
$result_dewan_num_max = mysqli_query($conn, $sql_dewan_num_max);
$row_dewan_num_max = mysqli_fetch_assoc($result_dewan_num_max);

if($row_dewan_num_max > 0) { 
  $dewan_num_max = $row_dewan_num_max['dewan_num']+1;
}else { $dewan_num_max = 1;}



//////////////////////////////
$sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(ketab_date)=$year)";
$result_r_num_max = mysqli_query($conn, $sql_r_num_max);
$row_r_num_max = mysqli_fetch_assoc($result_r_num_max);

if($row_r_num_max > 0) { 
  $r_num_max = $row_r_num_max['ketab_num']+1;
}else { $r_num_max = 1;}

$sql_num_report_max = "SELECT ketab_num FROM feech_info where ketab_num=(SELECT max(ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(ketab_date)=$year)";
$result_num_report_max = mysqli_query($conn, $sql_num_report_max);
$row_num_report_max = mysqli_fetch_assoc($result_num_report_max);

if($row_num_report_max > 0) { 
  $num_report_max = $row_num_report_max['ketab_num']+1;
}else { $num_report_max = 1;}








/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
if(!empty($_GET['id']) && !empty($_GET['type']) && $_GET['type']=='archive' ) {
 
  $check_sader = "SELECT edbara_num_sader, edbara_date_sader FROM processing_to_sader where jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_num_pre = $inserted_edbara_num AND edbara_date_pre = '$inserted_edbara_date' ";
    $check_sader_result = mysqli_query($conn,$check_sader);
    $check_sader_row = mysqli_fetch_assoc($check_sader_result);

if($check_sader_row > 0){
      $edbara_num_sader=$check_sader_row['edbara_num_sader'];
      $edbara_date_sader=$check_sader_row['edbara_date_sader'];

      // UPDATE tbl_uploads
      $sql_uploads = "SELECT * FROM `tbl_uploads` WHERE `jeha`='$inserted_jeha' AND `details_type`='$details_type' AND `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND `upload_source`='الأضابير الشخصية' ORDER BY `id` ASC";
       
      $sql_uploads_result = mysqli_query($conn, $sql_uploads);
  
      
      $uploads_r_num_max=$r_num_max;
  
      while($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {   
        if($uploads_row["sader"]==0){
        $id_uploads=$uploads_row['id'];
        $name=$uploads_row['name'];
        $type=$uploads_row['type'];
        $size=$uploads_row['size'];
        $path=$uploads_row['path']; 
        $isPrivate=$uploads_row['isPrivate'];
        $added_by_old=$uploads_row['added_by'];
        $upload_source=$uploads_row['upload_source'];  

        $sql2="INSERT INTO `tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `isPrivate`, `upload_source`) VALUES (
          '$name', '$type', '$size', '$path', $edbara_num_sader, '$edbara_date_sader', '$jeha_profile', '$details_type_new',  '$added_by_old - $user', current_timestamp(), '$isPrivate','$upload_source')";       
          if(mysqli_query($conn, $sql2)){
            $sql6="UPDATE `tbl_uploads` SET
            sader=1 WHERE id=$id_uploads";
            mysqli_query($conn, $sql6);
          }else{
            echo "Error sql_uploads: " . "<br>" . mysqli_error($conn);
            exit;
          }
        }
      }

    $sql_reports = "SELECT * FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  AND `ketab_type`='personal' ORDER BY ketab_num ASC";
   
    $sql_reports_result = mysqli_query($conn, $sql_reports);

    $report_e_id_new=$e_id_new;
    $report_d_id_new=$d_id_new;
    $report_r_num_max=$r_num_max;

    while($report_row = mysqli_fetch_assoc($sql_reports_result)) {
      if($report_row["sader"]==0){
        $report_id = $report_row['id'];     
        $sendto=$report_row['sendto'];
        $r_title=$report_row['r_title'];
        $send_date=$report_row['send_date'];
        $report_notes=$report_row['report_notes'];
        $r_resala_type=$report_row['resala_type'];
        $e_id=$report_row['e_id'];
        $r_title=$report_row["r_title"];
        $r_address=$report_row["r_address"];
        $send_date=$report_row["send_date"];
        $r_follow_date=$report_row["r_follow_date"];
        $r_following_date=$report_row["r_following_date"];
        $r_handle_date=$report_row["r_handle_date"];
        $report_brief=$report_row["report_brief"];
        $ketab_brief=$report_row["ketab_brief"];
        $report_notes=$report_row["report_notes"];
        $speed_level=$report_row["speed_level"];
        $info_masdar=$report_row["info_masdar"];
        $info_level=$report_row["info_level"];
        $security_level=$report_row["security_level"];
        $r_important=$report_row["r_important"];
        $r_follow=$report_row["r_follow"];
        $balagh=$report_row["balagh"];
        $balagh_date=$report_row["balagh_date"];
        $balagh_type=$report_row["balagh_type"];
        $balagh_attach=$report_row["balagh_attach"];
        $balagh_attach_extension=$report_row["balagh_attach_extension"];
        $sendfrom=$report_row["sendfrom"];
        $origin_jeha=$report_row["jeha"];
        $r_attach=$report_row["r_attach"];
        $r_attach_extension=$report_row["r_attach_extension"];
        $ketab_type=$report_row["ketab_type"];
        $isReport=$report_row["isReport"];
        $isPrivate=$report_row["isPrivate"];
        $copyto = $report_row['copyto'];
        $id_num = $report_row['id_num'];
        //$added_by=$report_row["added_by"];

        

        $sql2 = "INSERT INTO `reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `e_id`, `jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `edbara_num`, `edbara_date`, `origin_sendfrom`,`isReport`, `isPrivate`, `copyto`,`id_num`)  
        VALUES(
        '$r_title', 
        '$r_address', $report_r_num_max, '$today', $report_r_num_max, '$today',  
        '$send_date', 
        '$r_follow_date', 
        '$r_follow', 
        '$r_following_date', 
        '$r_handle_date', 
        '$report_brief', 
        '$ketab_brief', 
        '$report_notes', 
        '$speed_level', 
        '$info_masdar', 
        '$info_level', 
        '$security_level', 
        '$r_important', 
        '$balagh', 
        '$balagh_date', 
        '$balagh_type', 
        '$balagh_attach', 
        '$balagh_attach_extension', 
        '$sendfrom',
        '$sendto', 
        '$r_attach', 
        '$r_attach_extension', $e_id_new, '$jeha_profile', '$details_type_new', 
        '$r_resala_type', 
        '$ketab_type', 1, current_timestamp(), $edbara_num_sader, '$edbara_date_sader', '$origin_jeha','$isReport', '$isPrivate', '$copyto', '$id_num' )";
        if(mysqli_query($conn, $sql2)){

          $sql6="UPDATE reports_info SET
          sader=1 WHERE id=$report_id";
          mysqli_query($conn, $sql6);
          
        }else{
          echo "Error sql_reports1: " . "<br>" . mysqli_error($conn);
          exit;
        }

        
        $report_r_num_max=$report_r_num_max+1;
      }
    }

    

      $sql_study = "SELECT * FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num ORDER BY ketab_num ASC";
   
      $sql_study_result = mysqli_query($conn, $sql_study);
  
     
      $study_r_num_max=$r_num_max;
  
      while($study_row = mysqli_fetch_assoc($sql_study_result)) {
        if($study_row["sader"]==0){

        $study_id = $study_row["id"];
        $study_num=$study_row["study_num"];
        $general_code=$study_row["general_code"];
        $study_num_date=$study_row["study_num_date"]; 
        $study_request_jeha=$study_row["study_request_jeha"]; 
        $study_reason=$study_row["study_reason"]; 
        $study_date=$study_row["study_date"]; 
        $nick_name=$study_row["nick_name"]; 
        $name=$study_row["name"]; 
        $fname=$study_row["fname"]; 
        $lname=$study_row["lname"]; 
        $mname=$study_row["mname"]; 
        $pbirth=$study_row["pbirth"]; 
        $dbirth=$study_row["dbirth"]; 
        $subname=$study_row["subname"]; 
        $sex=$study_row["sex"]; 
        $national=$study_row["national"]; 
        $awsaf=$study_row["awsaf"]; 
        $family_status=$study_row["family_status"]; 
        $child_num=$study_row["child_num"]; 
        $wife_name=$study_row["wife_name"]; 
        $wife_address=$study_row["wife_address"]; 
        $work_before=$study_row["work_before"]; 
        $work_after=$study_row["work_after"]; 
        $work_now=$study_row["work_now"]; 
        $study=$study_row["study"]; 
        $money_status=$study_row["money_status"]; 
        $dealing=$study_row["dealing"]; 
        $service=$study_row["service"]; 
        $special=$study_row["special"]; 
        $pre_address=$study_row["pre_address"]; 
        $address=$study_row["address"]; 
        $address_type=$study_row["address_type"]; 
        $fasael=$study_row["fasael"]; 
        $opinion=$study_row["opinion"]; 
        $travels=$study_row["travels"]; 
        $n_relatives=$study_row["n_relatives"];
        $d_relatives=$study_row["d_relatives"]; 
        $f_relatives=$study_row["f_relatives"]; 
        $s_relatives=$study_row["s_relatives"]; 
        $religon_status=$study_row["religon_status"]; 
        $mind=$study_row["religon_status"]; 
        $lead=$study_row["lead"]; 
        $personal=$study_row["personal"]; 
        $affected_others=$study_row["affected_others"]; 
        $affected_to=$study_row["affected_to"]; 
        $relation_others=$study_row["relation_others"]; 
        $speak=$study_row["speak"]; 
        $important_awsaf=$study_row["important_awsaf"]; 
        $sawabek=$study_row["sawabek"]; 
        $phone=$study_row["phone"]; 
        $details=$study_row["details"]; 
        $brief=$study_row["brief"]; 
        $foto1=$study_row["foto1"]; 
        $foto2=$study_row["foto1"]; 
        $foto3=$study_row["foto3"]; 
        $attach=$study_row["attach"];
        $attach_extension=$study_row["attach_extension"]; 
       
        
        $resala_type=$study_row["resala_type"]; 
       
        $sendfrom=$study_row["sendfrom"]; 
        $sendto=$study_row["sendto"];
        $study_jeha=$study_row["study_jeha"]; 
        $study_organizer=$study_row["study_organizer"];
        $study_masdar=$study_row["study_masdar"]; 
        $study_opinion=$study_row["study_opinion"]; 
        $study_result=$study_row["study_result"]; 
        $negative_reason=$study_row["negative_reason"];       
        $ketab_type=$study_row["ketab_type"]; 
       
        $origin_jeha=$study_row["jeha"];
        $id_num=$study_row["id_num"];
        $nafeer_date=$study_row["nafeer_date"];
        $entisab_date=$study_row["entisab_date"];
        $service_place=$study_row["service_place"];
        $hts_opinion=$study_row["hts_opinion"];
        $hts_work_details=$study_row["hts_work_details"];
        $estimara_num=$study_row["estimara_num"];
        $talab_num=$study_row["talab_num"];
        $wives_num=$study_row["wives_num"];
        $tasreeh_date=$study_row["tasreeh_date"];
        $isPrivate=$study_row["isPrivate"];

        $sql3 = "INSERT INTO `study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `details_type`, `resala_type`, `sendto`, sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate) VALUES(   
          '$general_code',     
        $study_num, 
        '$study_num_date', 
        '$study_request_jeha', 
       '$study_reason', 
        '$study_date', 
        '$nick_name', 
        '$name', 
       '$fname', 
        '$lname', 
        '$mname', 
        '$pbirth', 
        '$dbirth', 
        '$subname', 
        '$sex', 
        '$national', 
        '$awsaf', 
        '$family_status', 
        '$child_num', 
        '$wife_name', 
        '$wife_address', 
        '$work_before', 
        '$work_after', 
        '$work_now', 
        '$study', 
        '$money_status', 
        '$dealing', 
        '$service', 
        '$special', 
        '$pre_address', 
        '$address', 
        '$address_type', 
        '$fasael', 
        '$opinion', 
        '$travels', 
        '$n_relatives',
        '$d_relatives', 
        '$f_relatives', 
        '$s_relatives', 
        '$religon_status', 
        '$mind', 
        '$lead', 
        '$personal', 
        '$affected_others', 
        '$affected_to', 
        '$relation_others', 
        '$speak', 
        '$important_awsaf', 
        '$sawabek', 
        '$phone', 
        '$details', 
        '$brief', 
        '$foto1', 
        '$foto2', 
        '$foto3', 
        '$attach',
        '$attach_extension', 
        '$user', 
        current_timestamp(),  
        $edbara_num_sader, 
        '$edbara_date_sader', 
        '$jeha_profile', 
        1, 
        $e_id_new,  
        '$details_type_new', 
        '$resala_type', 
        '$sendto', 
        '$sendfrom', 
       '$study_jeha', 
       '$study_organizer',
        '$study_masdar', 
        '$study_opinion', 
        '$study_result', 
        '$negative_reason', 
        $study_r_num_max, 
        '$today', 
        $study_r_num_max,
        '$today',
        '$ketab_type', 
        0, 
        '$origin_jeha',
        '$id_num',
        '$nafeer_date', 
        '$entisab_date', 
        '$service_place', 
        '$hts_opinion', 
        '$hts_work_details',
        '$estimara_num',
        '$talab_num', 
        '$wives_num', 
        '$tasreeh_date',
        '$isPrivate')";

        if(mysqli_query($conn, $sql3)){
          $sql7="UPDATE study SET
          sader=1 WHERE id = $study_id";
          
          mysqli_query($conn, $sql7);  
        }else{
          echo "Error Study: " . "<br>" . mysqli_error($conn);
          exit;
        } 

        $study_r_num_max=$study_r_num_max+1;

      }
      }



      if($admin == 1 || $admin == 7){
    
        ///
          $sql_ketab_num_max = "SELECT ketab_num FROM feech_info where ketab_num=(SELECT max(ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(ketab_date)=$year)";
          $result_ketab_num_max = mysqli_query($conn, $sql_ketab_num_max);
          $row_ketab_num_max = mysqli_fetch_assoc($result_ketab_num_max);
        
          if($row_ketab_num_max > 0) { 
            $ketab_num_max = $row_ketab_num_max['ketab_num']+1;
          }else { $ketab_num_max = 1;}
        
          
        
        
          $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(d_balagh)=$year)";
          $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
          $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
        
          if($row_num_balagh_max > 0) { 
            $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
          }else { $num_balagh_max = 1;}
        
        
           //////////////////////////////////////////
          if($jeha_profile == 'الإدارة المركزية للمعلومات'){
           $sql_sho3ba_ketab_num_max = "SELECT sho3ba_ketab_num FROM feech_info where sho3ba_ketab_num=(SELECT max(sho3ba_ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(sho3ba_ketab_date)=$year)";
          $result_sho3ba_ketab_num_max = mysqli_query($conn, $sql_sho3ba_ketab_num_max);
          $row_sho3ba_ketab_num_max = mysqli_fetch_assoc($result_sho3ba_ketab_num_max);
        
          if($row_sho3ba_ketab_num_max > 0) { 
            $sho3ba_ketab_num_max = $row_sho3ba_ketab_num_max['sho3ba_ketab_num']+1;
          }else { $sho3ba_ketab_num_max = 1;}
        
        
        
        
          $sql_sho3ba_balagh_num_max = "SELECT sho3ba_balagh_num FROM feech_info where sho3ba_balagh_num=(SELECT max(sho3ba_balagh_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(sho3ba_balagh_date)=$year)";
          $result_sho3ba_balagh_num_max = mysqli_query($conn, $sql_sho3ba_balagh_num_max);
          $row_sho3ba_balagh_num_max = mysqli_fetch_assoc($result_sho3ba_balagh_num_max);
        
          if($row_sho3ba_balagh_num_max > 0) { 
            $sho3ba_balagh_num_max = $row_sho3ba_balagh_num_max['sho3ba_balagh_num']+1;
          }else { $sho3ba_balagh_num_max = 1;}
           
        
          }else{
            $sho3ba_ketab_num_max = 0;
            $sho3ba_balagh_num_max = 0;
          }
        ///
            $sql_feech_info = "SELECT * FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num ORDER BY ketab_num ASC";
           
            $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);
        
           
        
            while($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) { 
              if($study_row["sader"]==0){
              $id_feech_info = $feech_info_row['id'];
              $requested= $feech_info_row['requested'];
              $jorm= $feech_info_row['jorm'];
              $balagh_type= $feech_info_row['balagh_type'];
              $balagh_attach= $feech_info_row['balagh_attach'];
              $balagh_attach_extension= $feech_info_row['balagh_attach_extension'];
              $balagh= $feech_info_row['balagh'];
              $jeha_request_order= $feech_info_row['jeha_request_order'];
              $jeha_tohma= $feech_info_row['jeha_tohma'];         
              $sho3ba_balagh= $feech_info_row['sho3ba_balagh'];
              $sho3ba_balagh_type= $feech_info_row['sho3ba_balagh_type'];
              $sho3ba_balagh_attach= $feech_info_row['sho3ba_balagh_attach'];
              $sho3ba_balagh_attach_extension= $feech_info_row['sho3ba_balagh_attach_extension'];        
              $sho3ba_stop_reason= $feech_info_row['sho3ba_stop_reason'];
              $stop_request= $feech_info_row['stop_request'];
              $stop_reason= $feech_info_row['stop_reason'];
              $resala_type= $feech_info_row['resala_type'];            
              $feech_type= $feech_info_row['feech_type'];
              $origin_jeha= $feech_info_row['jeha'];
              $isPrivate= $feech_info_row['isPrivate'];
        
              $sql4 = "INSERT INTO `feech_info` (".$feech_info_cols.")  VALUES ('$requested','$jorm','$num_balagh_max','$today','$ketab_num_max','$balagh_type','$balagh_attach','$balagh_attach_extension','$balagh','$today','$jeha_request_order','$jeha_tohma','$sho3ba_balagh_num_max','$today','$sho3ba_balagh','$sho3ba_balagh_type','$sho3ba_balagh_attach','$sho3ba_balagh_attach_extension','$sho3ba_ketab_num_max','$today','$sho3ba_stop_reason','$stop_request','$stop_reason', '$details_type_new','$resala_type', '$jeha_profile', 1, $e_id_new, $edbara_num_sader,'$edbara_date_sader', $year,'$feech_type','$user', current_timestamp(),'$origin_jeha','$isPrivate')";
        
            if(mysqli_query($conn, $sql4)){
            }else{
              echo "Error feech_info: " . "<br>" . mysqli_error($conn);
              exit;
            }
              $ketab_num_max++;
              $num_balagh_max++;
        
            if($jeha_profile == 'الإدارة المركزية للمعلومات'){
              $sho3ba_ketab_num_max++;
              $sho3ba_balagh_num_max++;
            }
          }
          $sql8="UPDATE feech_info SET
          sader=1 WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num";
          mysqli_query($conn, $sql8); 
        }
      }


      //////////// UPDATE mawkoof tables ////////////
      /* $table_name[]='mawkoof_adjudication_mahdar';
      $table_name[]='mawkoof_arresting_report';
      $table_name[]='mawkoof_data';
      $table_name[]='mawkoof_deposits';
      $table_name[]='mawkoof_detained_receiving';
      $table_name[]='mawkoof_extend_investigation_period';
      $table_name[]='mawkoof_final_result';
      $table_name[]='mawkoof_id3aa_decision';
      $table_name[]='mawkoof_id3aa_private_right';
      $table_name[]='mawkoof_id3aa_public_right';
      $table_name[]='mawkoof_investigation_mahdar';
      $table_name[]='mawkoof_investigation_mahdar_attachment';
      $table_name[]='mawkoof_investigation_results';
      $table_name[]='mawkoof_judicial_judgment';
      $table_name[]='mawkoof_judicial_session';
      $table_name[]='mawkoof_medical_condition';
      $table_name[]='mawkoof_pressure_on_accused';
      $table_name[]='mawkoof_testimonies';
$table_name[]='mawkoof_120';

      $number = count($table_name);
  
        if ($number >= 1) {
            for ($i=0; $i<$number; $i++) {
              $sql_table_name= $table_name[$i];
              $sql = "UPDATE `$sql_table_name` SET
              details_type='$details_type_new',
              jeha='$jeha_profile',          
              edbara_num=$edbara_num_sader,
              edbara_date='$edbara_date_sader',   
              ketab_num=$ketab_num_sader,
              ketab_date='$ketab_date_sader',            
              added_by='$added_by_old',
              add_date = current_timestamp()
              where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND ketab_date='$inserted_ketab_date' AND details_type='$details_type'" ;              
              if (mysqli_query($conn, $sql)) {
              } else {
                  echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
                  exit;
              }
            }
        } */

    
   
    

    

    
    

    $sql_id_max = "SELECT id, e_id  FROM details where  details_type='$details_type_new' AND jeha='$jeha_profile' AND edbara_num=$edbara_num_sader AND edbara_date='$edbara_date_sader'";
    $result_id_max = mysqli_query($conn, $sql_id_max);
    $row_id_max = mysqli_fetch_assoc($result_id_max);
    $id_max=$row_id_max['id'];
    $e_id_new = $row_id_max['e_id'];

    $sql="SET FOREIGN_KEY_CHECKS = 1";
    mysqli_query($conn, $sql);
    header ("Location: j_edit.php?id=".$id_max."&edit=true");
    
    

}else{
  $sql1 = "INSERT INTO `details` (".$details_cols.")  SELECT nick_name, name, fname, lname, mname, pbirth, dbirth, address, sex, national, awsaf, jeha_name,$e_id_new, $year, $edbara_num_max, '$today', 0, '0000-00-00',  study, edbara_note, edbara_info, note, result, details_attach, details_attach_extension, foto1, foto2, foto3, data_type, '$jeha_profile', '$user', current_timestamp(), '$details_type_new', resala_type, 1, 0 , `jeha`, `id_num`, isPrivate FROM details  where id = $id AND jeha='$inserted_jeha' AND details_type='$details_type'";

  if(mysqli_query($conn, $sql1)){

    $sql_dup="INSERT INTO `dup_names_check` (`fullname`, `name`, `fname`, `lname`, `mname`, `dbirth`, `pbirth`, `jeha`, `details_type`, `e_id`, `edbara_num`, `edbara_date`) SELECT `fullname`, `name`, `fname`, `lname`, `mname`, `dbirth`, `pbirth`, '$jeha_profile', '$details_type_new', $e_id_new, $edbara_num_max, '$today' FROM `dup_names_check` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_num=$inserted_edbara_num ";
    if(mysqli_query($conn, $sql_dup)){
   }else{
     echo "Error sql_dup: " . "<br>" . mysqli_error($conn);
     exit;
   }

      // UPDATE tbl_uploads
      $sql_uploads = "SELECT * FROM `tbl_uploads` WHERE `jeha`='$inserted_jeha' AND `details_type`='$details_type' AND `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND upload_source='الأضابير الشخصية' ORDER BY num ASC";
       
      $sql_uploads_result = mysqli_query($conn, $sql_uploads);
  
      
      $uploads_r_num_max=$r_num_max;
  
      while($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {   
        $name=$uploads_row['name'];
        $type=$uploads_row['type'];
        $size=$uploads_row['size'];
        $path=$uploads_row['path'];
        $isPrivate=$uploads_row['isPrivate'];
        $added_by_old=$uploads_row['added_by'];  
        $upload_source=$uploads_row['upload_source'];


        $sql2="INSERT INTO `tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `isPrivate`,`upload_source`) VALUES (
          '$name', '$type', '$size', '$path', $edbara_num_max, '$today', '$jeha_profile', '$details_type_new',  '$added_by_old - $user', current_timestamp(), '$isPrivate', '$upload_source')";
       
  
          if(mysqli_query($conn, $sql2)){
            
          }else{
            echo "Error sql_uploads: " . "<br>" . mysqli_error($conn);
            exit;
          }
  
         
      }

      
    //insert to dewan from reports_info table
    $sql_reports = "SELECT * FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  AND `ketab_type`='personal' ORDER BY ketab_num ASC";
   
    $sql_reports_result = mysqli_query($conn, $sql_reports);

    $report_e_id_new=$e_id_new;
    $report_d_id_new=$d_id_new;
    $report_r_num_max=$r_num_max;

    while($report_row = mysqli_fetch_assoc($sql_reports_result)) {      
      $sendto=$report_row['sendto'];
      $r_title=$report_row['r_title'];
      $send_date=$report_row['send_date'];
      $report_notes=$report_row['report_notes'];
      $r_resala_type=$report_row['resala_type'];
      $e_id=$report_row['e_id'];
      $r_title=$report_row["r_title"];
      $r_address=$report_row["r_address"];
      $send_date=$report_row["send_date"];
      $r_follow_date=$report_row["r_follow_date"];
      $r_following_date=$report_row["r_following_date"];
      $r_handle_date=$report_row["r_handle_date"];
      $report_brief=$report_row["report_brief"];
      $ketab_brief=$report_row["ketab_brief"];
      $report_notes=$report_row["report_notes"];
      $speed_level=$report_row["speed_level"];
      $info_masdar=$report_row["info_masdar"];
      $info_level=$report_row["info_level"];
      $security_level=$report_row["security_level"];
      $r_important=$report_row["r_important"];
      $r_follow=$report_row["r_follow"];
      $balagh=$report_row["balagh"];
      $balagh_date=$report_row["balagh_date"];
      $balagh_type=$report_row["balagh_type"];
      $balagh_attach=$report_row["balagh_attach"];
      $balagh_attach_extension=$report_row["balagh_attach_extension"];
      $sendfrom=$report_row["sendfrom"];
      $origin_jeha=$report_row["jeha"];
      $r_attach=$report_row["r_attach"];
      $r_attach_extension=$report_row["r_attach_extension"];
      $ketab_type=$report_row["ketab_type"];
      $isReport=$report_row["isReport"];
      $isPrivate=$report_row["isPrivate"];
      $copyto = $report_row['copyto'];
      $id_num = $report_row['id_num'];
      //$added_by=$report_row["added_by"];

      

      $sql2 = "INSERT INTO `reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `e_id`,`jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `edbara_num`, `edbara_date`, `origin_sendfrom`, `isReport`, `isPrivate`, `copyto`,`id_num`)  
      VALUES(
      '$r_title', 
      '$r_address', $report_r_num_max, '$today', $report_r_num_max, '$today',  
      '$send_date', 
      '$r_follow_date', 
      '$r_follow', 
      '$r_following_date', 
      '$r_handle_date', 
      '$report_brief', 
      '$ketab_brief', 
      '$report_notes', 
      '$speed_level', 
      '$info_masdar', 
      '$info_level', 
      '$security_level', 
      '$r_important', 
      '$balagh', 
      '$balagh_date', 
      '$balagh_type', 
      '$balagh_attach', 
      '$balagh_attach_extension', 
      '$sendfrom', 
      '$sendto', 
      '$r_attach', 
      '$r_attach_extension', $e_id_new, '$jeha_profile', '$details_type_new', 
      '$r_resala_type', 
      '$ketab_type', 1, current_timestamp(), $edbara_num_max, '$today', '$origin_jeha','$isReport', '$isPrivate', '$copyto','$id_num' )";
      if(mysqli_query($conn, $sql2)){
          
      }else{
        echo "Error sql_reports2: " . "<br>" . mysqli_error($conn);
        exit;
      }

       
        $report_r_num_max=$report_r_num_max+1;
    }

    

      $sql_study = "SELECT * FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num ORDER BY ketab_num ASC";
   
      $sql_study_result = mysqli_query($conn, $sql_study);
  
     
      $study_r_num_max=$r_num_max;
  
      while($study_row = mysqli_fetch_assoc($sql_study_result)) {
       
        $study_num=$study_row["study_num"];
        $general_code=$study_row["general_code"];
        $study_num_date=$study_row["study_num_date"]; 
        $study_request_jeha=$study_row["study_request_jeha"]; 
        $study_reason=$study_row["study_reason"]; 
        $study_date=$study_row["study_date"]; 
        $nick_name=$study_row["nick_name"]; 
        $name=$study_row["name"]; 
        $fname=$study_row["fname"]; 
        $lname=$study_row["lname"]; 
        $mname=$study_row["mname"]; 
        $pbirth=$study_row["pbirth"]; 
        $dbirth=$study_row["dbirth"]; 
        $subname=$study_row["subname"]; 
        $sex=$study_row["sex"]; 
        $national=$study_row["national"]; 
        $awsaf=$study_row["awsaf"]; 
        $family_status=$study_row["family_status"]; 
        $child_num=$study_row["child_num"]; 
        $wife_name=$study_row["wife_name"]; 
        $wife_address=$study_row["wife_address"]; 
        $work_before=$study_row["work_before"]; 
        $work_after=$study_row["work_after"]; 
        $work_now=$study_row["work_now"]; 
        $study=$study_row["study"]; 
        $money_status=$study_row["money_status"]; 
        $dealing=$study_row["dealing"]; 
        $service=$study_row["service"]; 
        $special=$study_row["special"]; 
        $pre_address=$study_row["pre_address"]; 
        $address=$study_row["address"]; 
        $address_type=$study_row["address_type"]; 
        $fasael=$study_row["fasael"]; 
        $opinion=$study_row["opinion"]; 
        $travels=$study_row["travels"]; 
        $n_relatives=$study_row["n_relatives"];
        $d_relatives=$study_row["d_relatives"]; 
        $f_relatives=$study_row["f_relatives"]; 
        $s_relatives=$study_row["s_relatives"]; 
        $religon_status=$study_row["religon_status"]; 
        $mind=$study_row["religon_status"]; 
        $lead=$study_row["lead"]; 
        $personal=$study_row["personal"]; 
        $affected_others=$study_row["affected_others"]; 
        $affected_to=$study_row["affected_to"]; 
        $relation_others=$study_row["relation_others"]; 
        $speak=$study_row["speak"]; 
        $important_awsaf=$study_row["important_awsaf"]; 
        $sawabek=$study_row["sawabek"]; 
        $phone=$study_row["phone"]; 
        $details=$study_row["details"]; 
        $brief=$study_row["brief"]; 
        $foto1=$study_row["foto1"]; 
        $foto2=$study_row["foto1"]; 
        $foto3=$study_row["foto3"]; 
        $attach=$study_row["attach"];
        $attach_extension=$study_row["attach_extension"]; 
       
        
        $resala_type=$study_row["resala_type"]; 
       
        $sendto=$study_row["sendto"]; 
        $sendfrom=$study_row["sendfrom"];
        $study_jeha=$study_row["study_jeha"]; 
        $study_organizer=$study_row["study_organizer"];
        $study_masdar=$study_row["study_masdar"]; 
        $study_opinion=$study_row["study_opinion"]; 
        $study_result=$study_row["study_result"]; 
        $negative_reason=$study_row["negative_reason"];       
        $ketab_type=$study_row["ketab_type"]; 
       
        $origin_jeha=$study_row["jeha"];
        $id_num=$study_row["id_num"];
        $nafeer_date=$study_row["nafeer_date"];
        $entisab_date=$study_row["entisab_date"];
        $service_place=$study_row["service_place"];
        $hts_opinion=$study_row["hts_opinion"];
        $hts_work_details=$study_row["hts_work_details"];
        $estimara_num=$study_row["estimara_num"];
        $talab_num=$study_row["talab_num"];
        $wives_num=$study_row["wives_num"];
        $tasreeh_date=$study_row["tasreeh_date"];
        $isPrivate=$study_row["isPrivate"];

        $sql3 = "INSERT INTO `study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate) VALUES(        
          '$general_code',
        $study_num, 
        '$study_num_date', 
        '$study_request_jeha', 
       '$study_reason', 
        '$study_date', 
        '$nick_name', 
        '$name', 
       '$fname', 
        '$lname', 
        '$mname', 
        '$pbirth', 
        '$dbirth', 
        '$subname', 
        '$sex', 
        '$national', 
        '$awsaf', 
        '$family_status', 
        '$child_num', 
        '$wife_name', 
        '$wife_address', 
        '$work_before', 
        '$work_after', 
        '$work_now', 
        '$study', 
        '$money_status', 
        '$dealing', 
        '$service', 
        '$special', 
        '$pre_address', 
        '$address', 
        '$address_type', 
        '$fasael', 
        '$opinion', 
        '$travels', 
        '$n_relatives',
        '$d_relatives', 
        '$f_relatives', 
        '$s_relatives', 
        '$religon_status', 
        '$mind', 
        '$lead', 
        '$personal', 
        '$affected_others', 
        '$affected_to', 
        '$relation_others', 
        '$speak', 
        '$important_awsaf', 
        '$sawabek', 
        '$phone', 
        '$details', 
        '$brief', 
        '$foto1', 
        '$foto2', 
        '$foto3', 
        '$attach',
        '$attach_extension', 
        '$user', 
        current_timestamp(),  
        $edbara_num_max, 
        '$today',  
        '$jeha_profile',      
        1, 
        $e_id_new,         
        '$details_type_new', 
        '$resala_type', 
        '$sendto',  
        '$sendfrom', 
       '$study_jeha', 
       '$study_organizer',
        '$study_masdar', 
        '$study_opinion', 
        '$study_result', 
        '$negative_reason', 
        $study_r_num_max, 
        '$today', 
        $study_r_num_max,
        '$today',
        '$ketab_type', 
        0, 
        '$origin_jeha',
        '$id_num',
        '$nafeer_date', 
        '$entisab_date', 
        '$service_place', 
        '$hts_opinion', 
        '$hts_work_details',
        '$estimara_num',
        '$talab_num', 
        '$wives_num', 
        '$tasreeh_date',
        '$isPrivate')";

        if(mysqli_query($conn, $sql3)){
        }else{
          echo "Error Study edbara: " . "<br>" . mysqli_error($conn);
          exit;
        } 

        $study_r_num_max=$study_r_num_max+1;
      }



  if($admin == 1 || $admin == 7){
    
    ///
      $sql_ketab_num_max = "SELECT ketab_num FROM feech_info where ketab_num=(SELECT max(ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(ketab_date)=$year)";
      $result_ketab_num_max = mysqli_query($conn, $sql_ketab_num_max);
      $row_ketab_num_max = mysqli_fetch_assoc($result_ketab_num_max);
    
      if($row_ketab_num_max > 0) { 
        $ketab_num_max = $row_ketab_num_max['ketab_num']+1;
      }else { $ketab_num_max = 1;}
    
      
    
    
      $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(d_balagh)=$year)";
      $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
      $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
    
      if($row_num_balagh_max > 0) { 
        $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
      }else { $num_balagh_max = 1;}
    
    
       //////////////////////////////////////////
      if($jeha_profile == 'الإدارة المركزية للمعلومات'){
       $sql_sho3ba_ketab_num_max = "SELECT sho3ba_ketab_num FROM feech_info where sho3ba_ketab_num=(SELECT max(sho3ba_ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(sho3ba_ketab_date)=$year)";
      $result_sho3ba_ketab_num_max = mysqli_query($conn, $sql_sho3ba_ketab_num_max);
      $row_sho3ba_ketab_num_max = mysqli_fetch_assoc($result_sho3ba_ketab_num_max);
    
      if($row_sho3ba_ketab_num_max > 0) { 
        $sho3ba_ketab_num_max = $row_sho3ba_ketab_num_max['sho3ba_ketab_num']+1;
      }else { $sho3ba_ketab_num_max = 1;}
    
    
    
    
      $sql_sho3ba_balagh_num_max = "SELECT sho3ba_balagh_num FROM feech_info where sho3ba_balagh_num=(SELECT max(sho3ba_balagh_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(sho3ba_balagh_date)=$year)";
      $result_sho3ba_balagh_num_max = mysqli_query($conn, $sql_sho3ba_balagh_num_max);
      $row_sho3ba_balagh_num_max = mysqli_fetch_assoc($result_sho3ba_balagh_num_max);
    
      if($row_sho3ba_balagh_num_max > 0) { 
        $sho3ba_balagh_num_max = $row_sho3ba_balagh_num_max['sho3ba_balagh_num']+1;
      }else { $sho3ba_balagh_num_max = 1;}
       
    
      }else{
        $sho3ba_ketab_num_max = 0;
        $sho3ba_balagh_num_max = 0;
      }
    ///
        $sql_feech_info = "SELECT * FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num ORDER BY ketab_num ASC";
       
        $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);
    
       
    
        while($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) { 
          
          $id_feech_info = $feech_info_row['id'];
          $requested= $feech_info_row['requested'];
          $jorm= $feech_info_row['jorm'];
          $balagh_type= $feech_info_row['balagh_type'];
          $balagh_attach= $feech_info_row['balagh_attach'];
          $balagh_attach_extension= $feech_info_row['balagh_attach_extension'];
          $balagh= $feech_info_row['balagh'];
          $jeha_request_order= $feech_info_row['jeha_request_order'];
          $jeha_tohma= $feech_info_row['jeha_tohma'];         
          $sho3ba_balagh= $feech_info_row['sho3ba_balagh'];
          $sho3ba_balagh_type= $feech_info_row['sho3ba_balagh_type'];
          $sho3ba_balagh_attach= $feech_info_row['sho3ba_balagh_attach'];
          $sho3ba_balagh_attach_extension= $feech_info_row['sho3ba_balagh_attach_extension'];        
          $sho3ba_stop_reason= $feech_info_row['sho3ba_stop_reason'];
          $stop_request= $feech_info_row['stop_request'];
          $stop_reason= $feech_info_row['stop_reason'];
          $resala_type= $feech_info_row['resala_type'];            
          $feech_type= $feech_info_row['feech_type'];
          $origin_jeha= $feech_info_row['jeha'];
          $isPrivate= $feech_info_row['isPrivate'];
    
          $sql4 = "INSERT INTO `feech_info` (".$feech_info_cols.")  VALUES ('$requested','$jorm','$num_balagh_max','$today','$ketab_num_max','$balagh_type','$balagh_attach','$balagh_attach_extension','$balagh','$today','$jeha_request_order','$jeha_tohma','$sho3ba_balagh_num_max','$today','$sho3ba_balagh','$sho3ba_balagh_type','$sho3ba_balagh_attach','$sho3ba_balagh_attach_extension','$sho3ba_ketab_num_max','$today','$sho3ba_stop_reason','$stop_request','$stop_reason', '$details_type_new','$resala_type', '$jeha_profile', 1, $e_id_new, $edbara_num_max, '$today', $year,'$feech_type','$user', current_timestamp(),'$origin_jeha','$isPrivate')";
    
        if(mysqli_query($conn, $sql4)){
        }else{
          echo "Error feech_info: " . "<br>" . mysqli_error($conn);
          exit;
        }
          $ketab_num_max++;
          $num_balagh_max++;
    
        if($jeha_profile == 'الإدارة المركزية للمعلومات'){
          $sho3ba_ketab_num_max++;
          $sho3ba_balagh_num_max++;
        }
      }
      $sql8="UPDATE feech_info SET
      sader=1 WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num";
      mysqli_query($conn, $sql8); 
  }


  //////////// UPDATE mawkoof tables ////////////
  /* $table_name[]='mawkoof_adjudication_mahdar';
  $table_name[]='mawkoof_arresting_report';
  $table_name[]='mawkoof_data';
  $table_name[]='mawkoof_deposits';
  $table_name[]='mawkoof_detained_receiving';
  $table_name[]='mawkoof_extend_investigation_period';
  $table_name[]='mawkoof_final_result';
  $table_name[]='mawkoof_id3aa_decision';
  $table_name[]='mawkoof_id3aa_private_right';
  $table_name[]='mawkoof_id3aa_public_right';
  $table_name[]='mawkoof_investigation_mahdar';
  $table_name[]='mawkoof_investigation_mahdar_attachment';
  $table_name[]='mawkoof_investigation_results';
  $table_name[]='mawkoof_judicial_judgment';
  $table_name[]='mawkoof_judicial_session';
  $table_name[]='mawkoof_medical_condition';
  $table_name[]='mawkoof_pressure_on_accused';
  $table_name[]='mawkoof_testimonies';
$table_name[]='mawkoof_120';

  $number = count($table_name);

  if ($number >= 1) {
      for ($i=0; $i<$number; $i++) {
        $sql_table_name= $table_name[$i];
        $sql = "UPDATE `$sql_table_name` SET
        details_type='$details_type_new',
        jeha='$jeha_profile',          
        edbara_num=$edbara_num_sader,
        edbara_date='$edbara_date_sader',   
        ketab_num=$report_r_num_max,
        ketab_date='$today',            
        added_by='$added_by_old',
        add_date = current_timestamp()
        where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND ketab_date='$inserted_ketab_date' AND details_type='$details_type'" ;              
        if (mysqli_query($conn, $sql)) {
        } else {
            echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
            exit;
        }
      }
  } */

    $sql5="UPDATE details SET
    sader=1  WHERE id=$id";
    mysqli_query($conn, $sql5);

    $sql6="UPDATE reports_info SET
    sader=1 WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num";
    mysqli_query($conn, $sql6);

    $sql7="UPDATE study SET
    sader=1 WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num";
    
    mysqli_query($conn, $sql7);  

    $sql77="INSERT INTO processing_to_sader (edbara_num_pre, edbara_date_pre, edbara_num_sader, edbara_date_sader, jeha, details_type)
    VALUES ($inserted_edbara_num , '$inserted_edbara_date', $edbara_num_max, '$today', '$inserted_jeha', '$details_type')";
    
    mysqli_query($conn, $sql77);
    

    $sql_id_max = "SELECT id  FROM details where id=(SELECT max(`id`) FROM details WHERE details_type='$details_type_new' AND jeha='$jeha_profile')";
    $result_id_max = mysqli_query($conn, $sql_id_max);
    $row_id_max = mysqli_fetch_assoc($result_id_max);
    $id_max=$row_id_max['id'];

    $sql="SET FOREIGN_KEY_CHECKS = 1";
    mysqli_query($conn, $sql);
    header ("Location: j_edit.php?id=".$id_max."&edit=true");
    exit;
    
  }else{
    echo "Error details: " . "<br>" . mysqli_error($conn);
    exit;
  }
    
}
}






























if(!empty($_GET['id']) && !empty($_GET['type']) && $_GET['type']=='k_pub' ) {


  $sql2 = "INSERT INTO `reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `origin_sendfrom`,`isReport`, `isPrivate`, `copyto`,`id_num`)  
  
  SELECT `r_title`, `r_address`,  $r_num_max, '$today', $r_num_max, '$today',  `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, '$jeha_profile', '$details_type_new', `resala_type`, `ketab_type`, 1, current_timestamp(), `jeha`,`isReport`, `isPrivate`, `copyto`,`id_num` FROM `reports_info` WHERE  id=$id";



    if(mysqli_query($conn, $sql2)){

       // UPDATE tbl_uploads
       $sql_uploads = "SELECT * FROM `tbl_uploads` WHERE `jeha`='$inserted_jeha' AND `details_type`='$details_type' AND `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND upload_source='الكتب' ORDER BY `num` ASC";
       
       $sql_uploads_result = mysqli_query($conn, $sql_uploads);
   
       
       $uploads_r_num_max=$r_num_max;
   
       while($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {   
         $name=$uploads_row['name'];
         $type=$uploads_row['type'];
         $size=$uploads_row['size'];
         $path=$uploads_row['path'];
         $isPrivate=$uploads_row['isPrivate'];
         $upload_source=$uploads_row['upload_source'];


         $sql2="INSERT INTO `tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `isPrivate`, `upload_source`) VALUES (
           '$name', '$type', '$size', '$path', $uploads_r_num_max, '$today', '$jeha_profile', '$details_type_new',  '$added_by_old - $user', current_timestamp(), '$isPrivate', '$upload_source')";
           if(mysqli_query($conn, $sql2)){          
           }else{
             echo "Error sql_uploads: " . "<br>" . mysqli_error($conn);
             exit;
           }         
       }


       $sql9 = "INSERT INTO `dewan` (`dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `sendfrom`, `brief`, `following_date`, `result`, `sendto`, `sendto_date`, `added_by`, `add_date`, `jeha`, `sader`, `d_attach`, `d_attach_extension`, `note`, `details_type`, `resala_type`, `origin_sendfrom`, `isPrivate`, `isReport`,`id_num`)  SELECT $r_num_max, '$today', $r_num_max, '$today', `sendfrom`, `r_title`, `r_following_date`, '',`sendto`, `send_date`, '$user', current_timestamp(), '$jeha_profile', 1, '', '', '', '$details_type_new', `resala_type`, `jeha`, `isPrivate`, `isReport`,`id_num`  FROM reports_info WHERE  id=$id";

    
    if(mysqli_query($conn, $sql9)){

      $sql5="UPDATE reports_info SET
      sader=1 where id=$id";
      mysqli_query($conn, $sql5); 

      $sql3 = "INSERT INTO `study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `e_year`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, `isPrivate`)  
      
      SELECT `general_code`, `study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, '$user', current_timestamp(),  0, '0000-00-00', '$jeha_profile', 1, $e_id_new, $year, '$details_type_new', `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, $r_num_max, '$today', $r_num_max, '$today', ketab_type, 0 , `jeha`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate FROM study where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND ketab_date='$inserted_ketab_date' AND details_type='$details_type'";
    if(mysqli_query($conn, $sql3)){
      $sql7="UPDATE study SET
      sader=1 where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND ketab_date='$inserted_ketab_date' AND details_type='$details_type'";
      mysqli_query($conn, $sql7);
      }else{
        echo "Error Study: " . "<br>" . mysqli_error($conn);
        exit;
      }

     /*  $sql_details = "INSERT INTO `details` (`e_id`,`jeha`,`details_type`) VALUES ($e_id_new,'$jeha_profile','$details_type_new')";
      mysqli_query($conn, $sql_details); */

    

      $sql_id_new = "SELECT id FROM reports_info ORDER BY id DESC LIMIT 1";
      $result_id_new = mysqli_query($conn, $sql_id_new);
      $row_id_new = mysqli_fetch_assoc($result_id_new);
      $id_new = $row_id_new['id'];  

      $sql="SET FOREIGN_KEY_CHECKS = 1";
      mysqli_query($conn, $sql);
      header ("Location: k_pub_edit.php?id=".$id_new."&edit=true");
    }else{
      echo "Error dewan: " . "<br>" . mysqli_error($conn);
      exit;
    }
    
    }else{
      echo "Error: reports_info " . "<br>" . mysqli_error($conn);
      exit;
    }
  }
  
 

?>
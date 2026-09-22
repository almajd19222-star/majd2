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
 $details_type_new = 'قيد المعالجة';
 $added_by_old = $_GET['added_by_old'];

 

 //get last number of e_id in database
 $sql_e_id = "SELECT id FROM details where id=(SELECT max(id) FROM details WHERE details_type='$details_type_new' AND jeha='$inserted_jeha')";
 $result_e_id = mysqli_query($conn, $sql_e_id);
 $row_e_id = mysqli_fetch_assoc($result_e_id);

 if($row_e_id > 0) {     
   $e_id_new = $row_e_id['id']+1;  
 }else { $e_id_new = 1;}

//get last number of edbara_num in database
$sql_edbara_num_max = "SELECT edbara_num  FROM details where edbara_num=(SELECT max(`edbara_num`) FROM details WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(edbara_date)=$year)";
$result_edbara_num_max = mysqli_query($conn, $sql_edbara_num_max);
$row_edbara_num_max = mysqli_fetch_assoc($result_edbara_num_max);

if($row_edbara_num_max > 0) {     
  $edbara_num_max = $row_edbara_num_max['edbara_num']+1;  
}else { $edbara_num_max = 1;}





//////////////////////////////
$sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
$result_r_num_max = mysqli_query($conn, $sql_r_num_max);
$row_r_num_max = mysqli_fetch_assoc($result_r_num_max);

if($row_r_num_max > 0) { 
  $r_num_max = $row_r_num_max['ketab_num']+1;
}else { $r_num_max = 1;}

$sql_num_report_max = "SELECT ketab_num FROM feech_info where ketab_num=(SELECT max(ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
$result_num_report_max = mysqli_query($conn, $sql_num_report_max);
$row_num_report_max = mysqli_fetch_assoc($result_num_report_max);

if($row_num_report_max > 0) { 
  $num_report_max = $row_num_report_max['ketab_num']+1;
}else { $num_report_max = 1;}

/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
if (!empty($_GET['id']) && !empty($_GET['type']) && $_GET['type']=='archive') {
    $sql1 = "INSERT INTO temp_details (".$details_cols.")  SELECT nick_name, name, fname, lname, mname, pbirth, dbirth, address, sex, national, awsaf, jeha_name, e_id, e_year,edbara_num, edbara_date, 0, '0000-00-00', study, edbara_note, edbara_info, note, result, details_attach, details_attach_extension, foto1, foto2, foto3, data_type, jeha, added_by, add_date, '$details_type_new', resala_type, 0, 0, '', id_num, isPrivate FROM details where id = $id";

    if (mysqli_query($conn, $sql1)) {
        $delete_dup_details="DELETE FROM temp_details USING temp_details, details 
        WHERE /* temp_details.edbara_num=details.edbara_num 
        AND*/  temp_details.edbara_date=details.edbara_date   
        AND temp_details.jeha=details.jeha               
        AND temp_details.details_type=details.details_type
        AND temp_details.name = details.name 
        AND temp_details.fname = details.fname 
        AND temp_details.lname = details.lname   
        AND temp_details.mname = details.mname  
        AND temp_details.pbirth = details.pbirth";
        if (mysqli_query($conn, $delete_dup_details)) {
        } else {
            echo "Error delete dup details: "  . "<br>" . mysqli_error($conn);
            exit;
        }

        $sql1 = "INSERT INTO details (".$details_cols.")  SELECT nick_name, name, fname, lname, mname, pbirth, dbirth, address, sex, national, awsaf, jeha_name, e_id, e_year, `edbara_num`, `edbara_date`, 0, '0000-00-00', study, edbara_note, edbara_info, note, result, details_attach, details_attach_extension, foto1, foto2, foto3, data_type, jeha, added_by, add_date, '$details_type_new', resala_type, 0, 0, '', id_num, isPrivate FROM temp_details";
        if (mysqli_query($conn, $sql1)) {
        } else {
            echo "Error inser into details: "  . "<br>" . mysqli_error($conn);
            exit;
        }
    } else {
        echo "Error inser into details: "  . "<br>" . mysqli_error($conn);
        exit;
    }

    $sql_delete = "DELETE FROM temp_details";
    mysqli_query($conn, $sql_delete);
      
     
      
    //////////////

    ////////////// temp_reports_info //////////////


  

    $sql_reports = "SELECT * FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  AND `ketab_type`='personal' ORDER BY ketab_num ASC";
   
    $sql_reports_result = mysqli_query($conn, $sql_reports);

   

    while ($report_row = mysqli_fetch_assoc($sql_reports_result)) {
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
        $report_brief=@str_replace("'", "''", $report_row["report_brief"]);
        $ketab_brief=@str_replace("'", "''", $report_row["ketab_brief"]);
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
        $copyto=@str_replace("'", "''", $report_row["copyto"]);
        $inserted_jeha = $report_row['jeha'];
        //$added_by=$report_row["added_by"];

      
        $sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
        $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
        $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
        if ($row_r_num_max > 0) {
            $report_r_num_max = $row_r_num_max['ketab_num']+1;
        } else {
            $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date_pre)=$year)";
            $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
            $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
            if ($row_r_num_max > 0) {
                $report_r_num_max = $row_r_num_max['ketab_num_pre']+1;
            } else {
                $report_r_num_max = 1;
            }
        }


        $sql2 = "INSERT INTO `temp_reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `e_id`, `jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `edbara_num`, `edbara_date`, `origin_sendfrom`,`isReport`, `isPrivate`, `copyto`)  
      VALUES (
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
      '$r_attach_extension', $e_id_new, '$inserted_jeha', '$details_type_new', 
      '$r_resala_type', 
      '$ketab_type', 0, current_timestamp(), $inserted_edbara_num, '$inserted_edbara_date', '$origin_jeha','$isReport', '$isPrivate','$copyto' )";
        if (mysqli_query($conn, $sql2)) {
        } else {
            echo "Error sql_reports: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    $delete_dup_reports_info="DELETE FROM temp_reports_info USING temp_reports_info, reports_info 
        WHERE /* temp_reports_info.edbara_num=reports_info.edbara_num 
        AND temp_reports_info.edbara_date = reports_info.edbara_date 
        AND */ temp_reports_info.jeha = reports_info.jeha
        AND temp_reports_info.r_title=reports_info.r_title
        AND temp_reports_info.details_type = reports_info.details_type
        AND temp_reports_info.r_address = reports_info.r_address";
      
    if (mysqli_query($conn, $delete_dup_reports_info)) {
    } else {
        echo "Error delete dup reports_info: "  . "<br>" . mysqli_error($conn);
        exit;
    }

    $sql_toProcess = "INSERT INTO `reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `e_id`, `edbara_num`, `edbara_date`, `e_year`, `jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `added_by`, copyto, origin_sendfrom, isReport, isPrivate, origin_ketab_num, origin_ketab_date)  SELECT r_title, r_address, ketab_num, ketab_date, 0, '0000-00-00', send_date, r_follow_date, r_follow, r_following_date, r_handle_date, report_brief, ketab_brief, report_notes, speed_level, info_masdar, info_level, security_level, r_important, balagh, balagh_date, balagh_type, balagh_attach, balagh_attach_extension, sendfrom, sendto, r_attach, r_attach_extension, e_id, edbara_num, edbara_date, e_year, jeha, '$details_type_new', resala_type, ketab_type, 0, add_date, added_by, copyto, `jeha`, isReport, isPrivate, ketab_num, ketab_date FROM temp_reports_info";

    if (mysqli_query($conn, $sql_toProcess)) {
    } else {
        echo "Error INSERT INTO `reports_info` process status: "  . "<br>" . mysqli_error($conn);
        exit;
    }
  
    $sql_delete = "DELETE FROM temp_reports_info";
    mysqli_query($conn, $sql_delete);

    //////////////

    ////////////// temp_study //////////////

    $sql_study = "SELECT * FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num ORDER BY ketab_num ASC";
   
    $sql_study_result = mysqli_query($conn, $sql_study);
  
    while ($study_row = mysqli_fetch_assoc($sql_study_result)) {
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
        $details=@str_replace("'", "''", $study_row["details"]);
        $brief=@str_replace("'", "''", $study_row["brief"]);
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


        $sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
        $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
        $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
        if ($row_r_num_max > 0) {
            $report_r_num_max = $row_r_num_max['ketab_num']+1;
        } else {
            $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date_pre)=$year)";
            $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
            $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
            if ($row_r_num_max > 0) {
                $report_r_num_max = $row_r_num_max['ketab_num_pre']+1;
            } else {
                $report_r_num_max = 1;
            }
        }

        $sql3 = "INSERT INTO `temp_study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate) VALUES(   
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
        $inserted_edbara_num, 
        '$inserted_edbara_date', 
        '$inserted_jeha', 
        0, 
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
        $report_r_num_max, 
        '$today', 
        $report_r_num_max,
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

        if (mysqli_query($conn, $sql3)) {
        } else {
            echo "Error Study: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    $delete_dup_study="DELETE FROM temp_study USING temp_study, study 
  WHERE temp_study.study_date=study.study_date   
  /* AND temp_study.ketab_num=study.ketab_num
  AND temp_study.ketab_date=study.ketab_date */ 
  AND temp_study.jeha=study.jeha  
  AND temp_study.details_type=study.details_type
  AND temp_study.name = study.name 
  AND temp_study.fname = study.fname 
  AND temp_study.lname = study.lname   
  AND temp_study.mname = study.mname";
    if (mysqli_query($conn, $delete_dup_study)) {
    } else {
        echo "Error delete dup study: "  . "<br>" . mysqli_error($conn);
        exit;
    }
        
    $sql3 = "INSERT INTO `study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `e_year`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`,`study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate )  
    SELECT general_code, study_num, study_num_date, study_request_jeha, study_reason, study_date, nick_name, name, fname, lname, mname, pbirth, dbirth, subname, sex, national, awsaf, family_status, child_num, wife_name, wife_address, work_before, work_after, work_now, study, money_status, dealing, service, special, pre_address, address, address_type, fasael, opinion, travels, n_relatives, d_relatives, f_relatives, s_relatives, religon_status, mind, lead, personal, affected_others, affected_to, relation_others, speak, important_awsaf, sawabek, phone, details, brief, foto1, foto2, foto3, attach, attach_extension, added_by, add_date, edbara_num, edbara_date, jeha, 0, e_id, e_year, '$details_type_new', resala_type, sendto, sendfrom, study_jeha, study_organizer, study_masdar, study_opinion, study_result, negative_reason, 0, '0000-00-00', ketab_num, ketab_date, ketab_type, 0, jeha, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate FROM temp_study";
    if (mysqli_query($conn, $sql3)) {
    } else {
        echo "Error study: "  . "<br>" . mysqli_error($conn);
        exit;
    }

    $sql_delete = "DELETE FROM temp_study";
    mysqli_query($conn, $sql_delete);
    //////////////
    ////////////// feech_info //////////////

    $sql_feech_info = "SELECT * FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num ORDER BY ketab_num ASC";
    
    $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);

    while ($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) {
        $sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
        $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
        $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
        if ($row_r_num_max > 0) {
            $report_r_num_max = $row_r_num_max['ketab_num']+1;
        } else {
            $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date_pre)=$year)";
            $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
            $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
            if ($row_r_num_max > 0) {
                $report_r_num_max = $row_r_num_max['ketab_num_pre']+1;
            } else {
                $report_r_num_max = 1;
            }
        }
        
          
        
        
        $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(d_balagh)=$year)";
        $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
        $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
        
        if ($row_num_balagh_max > 0) {
            $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
        } else {
            $num_balagh_max = 1;
        }
        
        
        //////////////////////////////////////////
        if ($inserted_jeha == 'الإدارة المركزية للمعلومات') {
            $sql_sho3ba_ketab_num_max = "SELECT sho3ba_ketab_num FROM feech_info where sho3ba_ketab_num=(SELECT max(sho3ba_ketab_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(sho3ba_ketab_date)=$year)";
            $result_sho3ba_ketab_num_max = mysqli_query($conn, $sql_sho3ba_ketab_num_max);
            $row_sho3ba_ketab_num_max = mysqli_fetch_assoc($result_sho3ba_ketab_num_max);
        
            if ($row_sho3ba_ketab_num_max > 0) {
                $sho3ba_ketab_num_max = $row_sho3ba_ketab_num_max['sho3ba_ketab_num']+1;
            } else {
                $sho3ba_ketab_num_max = 1;
            }
        
        
        
        
            $sql_sho3ba_balagh_num_max = "SELECT sho3ba_balagh_num FROM feech_info where sho3ba_balagh_num=(SELECT max(sho3ba_balagh_num) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(sho3ba_balagh_date)=$year)";
            $result_sho3ba_balagh_num_max = mysqli_query($conn, $sql_sho3ba_balagh_num_max);
            $row_sho3ba_balagh_num_max = mysqli_fetch_assoc($result_sho3ba_balagh_num_max);
        
            if ($row_sho3ba_balagh_num_max > 0) {
                $sho3ba_balagh_num_max = $row_sho3ba_balagh_num_max['sho3ba_balagh_num']+1;
            } else {
                $sho3ba_balagh_num_max = 1;
            }
        } else {
            $sho3ba_ketab_num_max = 0;
            $sho3ba_balagh_num_max = 0;
        }


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


        $sql4 = "INSERT INTO `temp_feech_info` (".$feech_info_cols.")  VALUES ('$requested','$jorm','$num_balagh_max','$today','$report_r_num_max','$balagh_type','$balagh_attach','$balagh_attach_extension','$balagh','$today','$jeha_request_order','$jeha_tohma','$sho3ba_balagh_num_max','$today','$sho3ba_balagh','$sho3ba_balagh_type','$sho3ba_balagh_attach','$sho3ba_balagh_attach_extension','$sho3ba_ketab_num_max','$today','$sho3ba_stop_reason','$stop_request','$stop_reason', '$details_type_new','$resala_type', '$inserted_jeha', 1, $e_id_new, $inserted_edbara_num,'$inserted_edbara_date', $year,'$feech_type','$user', current_timestamp(),'$origin_jeha','$isPrivate')";

        if (mysqli_query($conn, $sql4)) {
        } else {
            echo "Error feech_info: " . "<br>" . mysqli_error($conn);
            exit;
        }
        $report_r_num_max++;
        $num_balagh_max++;

        if ($inserted_jeha == 'الإدارة المركزية للمعلومات') {
            $sho3ba_ketab_num_max++;
            $sho3ba_balagh_num_max++;
        }
    }


    $delete_dup_feech_info="DELETE FROM temp_feech_info USING temp_feech_info, feech_info 
    WHERE temp_feech_info.num_balagh=feech_info.num_balagh 
    AND temp_feech_info.d_balagh=feech_info.d_balagh
    AND /* temp_feech_info.edbara_num=feech_info.edbara_num 
    AND temp_feech_info.ketab_num=feech_info.ketab_num
    AND temp_feech_info.ketab_date=feech_info.ketab_date 
    AND*/ temp_feech_info.jeha=feech_info.jeha   
    AND temp_feech_info.details_type=feech_info.details_type";
    if (mysqli_query($conn, $delete_dup_feech_info)) {
    } else {
        echo "Error delete dup temp_feech_info: "  . "<br>" . mysqli_error($conn);
        exit;
    }

    $sql4 = "INSERT INTO `feech_info` (".$feech_info_cols.")  SELECT requested, jorm, num_balagh, d_balagh, ketab_num, balagh_type, balagh_attach, balagh_attach_extension, balagh, ketab_date, jeha_request_order, jeha_tohma, sho3ba_balagh_num, sho3ba_balagh_date, sho3ba_balagh, sho3ba_balagh_type, sho3ba_balagh_attach, sho3ba_balagh_attach_extension, sho3ba_ketab_num, sho3ba_ketab_date, sho3ba_stop_reason, stop_request, stop_reason, '$details_type_new', resala_type, jeha, 0, e_id, edbara_num, edbara_date, e_year, feech_type, added_by, add_date,'', isPrivate FROM temp_feech_info";
    if (mysqli_query($conn, $sql4)) {
    } else {
        echo "Error feech_info: "  . "<br>" . mysqli_error($conn);
        exit;
    }

    $sql_delete = "DELETE FROM temp_feech_info";
    mysqli_query($conn, $sql_delete);
  


    //////////////

    ////////////// feech_info //////////////



    // UPDATE tbl_uploads
    $sql_uploads = "SELECT * FROM `tbl_uploads` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' ORDER BY `num` ASC";

    $sql_uploads_result = mysqli_query($conn, $sql_uploads);


    $uploads_r_num_max=$r_num_max;

    while ($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {
        $name=$uploads_row['name'];
        $type=$uploads_row['type'];
        $size=$uploads_row['size'];
        $path=$uploads_row['path'];
        $upload_source=$uploads_row['upload_source'];
        $upload_type=$uploads_row['upload_type'];
        $isPrivate=$uploads_row['isPrivate'];
 

        /* $sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
        $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
        $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
        if ($row_r_num_max > 0) {
            $report_r_num_max = $row_r_num_max['ketab_num']+1;
        } else {
            $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date_pre)=$year)";
            $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
            $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
            if ($row_r_num_max > 0) {
                $report_r_num_max = $row_r_num_max['ketab_num_pre']+1;
            } else {
                $report_r_num_max = 1;
            }
        } */



        $sql2="INSERT INTO `temp_tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `isPrivate`, `upload_source`, `upload_type`) VALUES (
        '$name', '$type', '$size', '$path', $inserted_edbara_num, '$inserted_edbara_date', '$inserted_jeha', '$details_type_new',  '$user', current_timestamp(), '$isPrivate', '$upload_source','$upload_type')";
 

        if (mysqli_query($conn, $sql2)) {
        } else {
            echo "Error sql_uploads: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }



    $delete_dup_tbl_uploads="DELETE FROM temp_tbl_uploads USING temp_tbl_uploads, tbl_uploads 
  WHERE 
  temp_tbl_uploads.name=tbl_uploads.name 
  AND temp_tbl_uploads.type=tbl_uploads.type               
  AND temp_tbl_uploads.jeha=tbl_uploads.jeha
  AND temp_tbl_uploads.num=tbl_uploads.num
  AND temp_tbl_uploads.date=tbl_uploads.date
  AND temp_tbl_uploads.upload_source=tbl_uploads.upload_source
  AND temp_tbl_uploads.details_type=tbl_uploads.details_type";
    if (mysqli_query($conn, $delete_dup_tbl_uploads)) {
    } else {
        echo "Error delete dup tbl_uploads: "  . "<br>" . mysqli_error($conn);
        exit;
    }
  
     
    $sql4 = "INSERT INTO `tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `sader`, `isPrivate`, `upload_source`, `upload_type`)  SELECT `name`, `type`, `size`, `path`, `num`, `date`, `jeha`, '$details_type_new', `added_by`, `add_date` , 0, `isPrivate`, `upload_source`, `upload_type` FROM `temp_tbl_uploads`";
    if (mysqli_query($conn, $sql4)) {
    } else {
        echo "Error tbl_uploads_processing: "  . "<br>" . mysqli_error($conn);
        exit;
    }
    //$tb[]='المرفقات';
     

    $sql_delete = "DELETE FROM temp_tbl_uploads";
    mysqli_query($conn, $sql_delete);
  

    $sql_id_max = "SELECT id  FROM details where id=(SELECT max(`id`) FROM details WHERE details_type='$details_type_new' AND jeha='$inserted_jeha')";
    $result_id_max = mysqli_query($conn, $sql_id_max);
    $row_id_max = mysqli_fetch_assoc($result_id_max);
    $id_max=$row_id_max['id'];

    $sql="SET FOREIGN_KEY_CHECKS = 1";
    mysqli_query($conn, $sql);

    header("Location: j_edit.php?id=".$id_max."&edit=true");
    exit;
    //////////////
}

//////////////
//////////////
//////////////
////////////// public ketab //////////////
//////////////
//////////////
//////////////

if (!empty($_GET['id']) && !empty($_GET['type']) && $_GET['type']=='k_pub') {

  $sql_reports = "SELECT * FROM `reports_info` WHERE id = $id";   
  $sql_reports_result = mysqli_query($conn, $sql_reports);
  $report_row = mysqli_fetch_assoc($sql_reports_result);

    $report_id = $report_row['id']; 
    $ketab_num = $report_row['ketab_num']; 
    $ketab_date = $report_row['ketab_date'];
    $edbara_num = $report_row['edbara_num']; 
    $edbara_date = $report_row['edbara_date'];    
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
    if($report_row["report_brief"]==''){
        $report_brief='';
    }else{
        $report_brief=@str_replace("'", "''", $report_row["report_brief"]);
    }
    if($report_row["ketab_brief"]==''){
        $ketab_brief='';
    }else{
        $ketab_brief=@str_replace("'", "''", $report_row["ketab_brief"]);
    }
    if($report_row["report_notes"]==''){
        $report_notes='';
    }else{
        $report_notes=@str_replace("'", "''", $report_row["report_notes"]);
    }
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
    if($report_row["copyto"]==''){
        $copyto='';
    }else{
        $copyto=@str_replace("'", "''", $report_row["copyto"]);
    }
    $inserted_jeha = $report_row['jeha'];
    //$added_by=$report_row["added_by"];

    
    $sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date)=$year)";
    $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
    $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
    if($row_r_num_max > 0) { 
      $report_r_num_max = $row_r_num_max['ketab_num']+1;
    }else {        
      $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type_new' AND jeha='$inserted_jeha' AND YEAR(ketab_date_pre)=$year)";
      $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
      $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);  
      if($row_r_num_max > 0) { 
        $report_r_num_max = $row_r_num_max['ketab_num_pre']+1;
      }else { $report_r_num_max = 1;}
    } 

    

    $sql2 = "INSERT INTO `temp_reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `e_id`, `jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `edbara_num`, `edbara_date`, `origin_sendfrom`,`isReport`, `isPrivate`, `copyto`)  
    VALUES (
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
    '$r_attach_extension', $e_id_new, '$inserted_jeha', '$details_type_new', 
    '$r_resala_type', 
    '$ketab_type', 0, current_timestamp(), $edbara_num, '$edbara_date', '$origin_jeha','$isReport', '$isPrivate','$copyto')";
    if(mysqli_query($conn, $sql2)){

    }else{
      echo "Error sql_reports: " . "<br>" . mysqli_error($conn);
      exit;
    }

    
  
  

  $delete_dup_reports_info="DELETE FROM temp_reports_info USING temp_reports_info, reports_info 
      WHERE/*  temp_reports_info.edbara_num=reports_info.edbara_num 
      AND temp_reports_info.edbara_date = reports_info.edbara_date 
      AND */temp_reports_info.jeha = reports_info.jeha
      AND temp_reports_info.r_title=reports_info.r_title
      AND temp_reports_info.details_type = reports_info.details_type
      AND temp_reports_info.r_address = reports_info.r_address";
    
      if(mysqli_query($conn, $delete_dup_reports_info)){
      }else{
        echo "Error delete dup reports_info: "  . "<br>" . mysqli_error($conn);
        exit;
      }

  $sql_toProcess = "INSERT INTO `reports_info` (`r_title`, `r_address`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `send_date`, `r_follow_date`, `r_follow`, `r_following_date`, `r_handle_date`, `report_brief`, `ketab_brief`, `report_notes`, `speed_level`, `info_masdar`, `info_level`, `security_level`, `r_important`, `balagh`, `balagh_date`, `balagh_type`, `balagh_attach`, `balagh_attach_extension`, `sendfrom`, `sendto`, `r_attach`, `r_attach_extension`, `e_id`, `edbara_num`, `edbara_date`, `e_year`, `jeha`, `details_type`, `resala_type`, `ketab_type`, `sader`, `add_date`, `added_by`, copyto, origin_sendfrom, isReport, isPrivate, origin_ketab_num, origin_ketab_date)  SELECT r_title, r_address, ketab_num, ketab_date, `dewan_num`, `dewan_date`, send_date, r_follow_date, r_follow, r_following_date, r_handle_date, report_brief, ketab_brief, report_notes, speed_level, info_masdar, info_level, security_level, r_important, balagh, balagh_date, balagh_type, balagh_attach, balagh_attach_extension, sendfrom, sendto, r_attach, r_attach_extension, e_id, edbara_num, edbara_date, e_year, jeha, '$details_type_new', resala_type, ketab_type, 0, add_date, added_by, copyto, `jeha`, isReport, isPrivate, ketab_num, ketab_date FROM temp_reports_info";

  if (mysqli_query($conn, $sql_toProcess)) {
  } else {
      echo "Error INSERT INTO `reports_info` process status: "  . "<br>" . mysqli_error($conn);
      exit;
  }

  $sql_delete = "DELETE FROM temp_reports_info";
  mysqli_query($conn, $sql_delete);

//////////////

////////////// temp_study //////////////

$sql_study = "SELECT * FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND ketab_num=$ketab_num AND ketab_date='$ketab_date' ORDER BY ketab_num ASC";
 
    $sql_study_result = mysqli_query($conn, $sql_study);

    while ($study_row = mysqli_fetch_assoc($sql_study_result)) {
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
        if($study_row["details"]==''){
            $details='';
        }else{
            $details=@str_replace("'", "''", $study_row["details"]);
        }
        if($study_row["brief"]==''){
            $brief='';
        }else{
            $brief=@str_replace("'", "''", $study_row["brief"]);
        }
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


        

        $sql3 = "INSERT INTO `temp_study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate) VALUES(   
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
        $edbara_num, 
        '$edbara_date', 
        '$inserted_jeha', 
        0, 
        0,  
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
        $report_r_num_max, 
        '$today', 
        $report_r_num_max,
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

        if (mysqli_query($conn, $sql3)) {
        } else {
            echo "Error Study: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

$delete_dup_study="DELETE FROM temp_study USING temp_study, study 
WHERE temp_study.study_date=study.study_date 
/* AND temp_study.ketab_num=study.ketab_num
AND temp_study.ketab_date=study.ketab_date */
AND temp_study.jeha=study.jeha
AND temp_study.details_type=study.details_type
AND temp_study.name = study.name 
AND temp_study.fname = study.fname 
AND temp_study.lname = study.lname   
AND temp_study.mname = study.mname";
if(mysqli_query($conn, $delete_dup_study)){
}else{
  echo "Error delete dup study: "  . "<br>" . mysqli_error($conn);
  exit;
}
      
$sql3 = "INSERT INTO `study` (`general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `e_year`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`,`study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate )  
SELECT general_code, study_num, study_num_date, study_request_jeha, study_reason, study_date, nick_name, name, fname, lname, mname, pbirth, dbirth, subname, sex, national, awsaf, family_status, child_num, wife_name, wife_address, work_before, work_after, work_now, study, money_status, dealing, service, special, pre_address, address, address_type, fasael, opinion, travels, n_relatives, d_relatives, f_relatives, s_relatives, religon_status, mind, lead, personal, affected_others, affected_to, relation_others, speak, important_awsaf, sawabek, phone, details, brief, foto1, foto2, foto3, attach, attach_extension, added_by, add_date, edbara_num, edbara_date, jeha, 0, e_id, e_year, '$details_type_new', resala_type, sendto, sendfrom, study_jeha, study_organizer, study_masdar, study_opinion, study_result, negative_reason, 0, '0000-00-00', ketab_num, ketab_date, ketab_type, 0, jeha, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate FROM temp_study";
if(mysqli_query($conn, $sql3)){
}else{
echo "Error study: "  . "<br>" . mysqli_error($conn);
exit;
}

$sql_delete = "DELETE FROM temp_study";
mysqli_query($conn, $sql_delete);
//////////////
  
// UPDATE tbl_uploads
$sql_uploads = "SELECT * FROM `tbl_uploads` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND num=$ketab_num AND date='$ketab_date' ORDER BY num ASC";
       
$sql_uploads_result = mysqli_query($conn, $sql_uploads);


while($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {   
  $name=$uploads_row['name'];
  $type=$uploads_row['type'];
  $size=$uploads_row['size'];
  $path=$uploads_row['path'];
  $upload_source=$uploads_row['upload_source'];
  $upload_type=$uploads_row['upload_type'];
  $isPrivate=$uploads_row['isPrivate'];
 



  $sql2="INSERT INTO `temp_tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `isPrivate`, `upload_source`, `upload_type`) VALUES (
    '$name', '$type', '$size', '$path', $report_r_num_max, '$today', '$inserted_jeha', '$details_type_new',  '$user', current_timestamp(), '$isPrivate', '$upload_source', '$upload_type')";
 

    if(mysqli_query($conn, $sql2)){
      
    }else{
      echo "Error sql_uploads: " . "<br>" . mysqli_error($conn);
      exit;
    }

   
}



  $delete_dup_tbl_uploads="DELETE FROM temp_tbl_uploads USING temp_tbl_uploads, tbl_uploads 
  WHERE 
  temp_tbl_uploads.name=tbl_uploads.name 
  AND temp_tbl_uploads.type=tbl_uploads.type               
  AND temp_tbl_uploads.jeha=tbl_uploads.jeha
  AND temp_tbl_uploads.num=tbl_uploads.num
AND temp_tbl_uploads.date=tbl_uploads.date
AND temp_tbl_uploads.upload_source=tbl_uploads.upload_source
  AND temp_tbl_uploads.details_type=tbl_uploads.details_type";
  if(mysqli_query($conn, $delete_dup_tbl_uploads)){
  }else{
    echo "Error delete dup tbl_uploads: "  . "<br>" . mysqli_error($conn);
    exit;
  }
  
     
          $sql4 = "INSERT INTO `tbl_uploads` (`name`, `type`, `size`, `path`, `num`, `date`, `jeha`, `details_type`, `added_by`, `add_date`, `sader`, `isPrivate`,`upload_source`,`upload_type`)  SELECT `name`, `type`, `size`, `path`, `num`, `date`, `jeha`, '$details_type_new', `added_by`, `add_date` , 0, `isPrivate`,`upload_source`,`upload_type` FROM `temp_tbl_uploads`";
          if (mysqli_query($conn, $sql4)) {
          } else {
              echo "Error tbl_uploads_processing: "  . "<br>" . mysqli_error($conn);
              exit;
          }
          //$tb[]='المرفقات';
     

        $sql_delete = "DELETE FROM temp_tbl_uploads";
        mysqli_query($conn, $sql_delete);
    

        $sql_id_new = "SELECT id FROM reports_info ORDER BY id DESC LIMIT 1";
        $result_id_new = mysqli_query($conn, $sql_id_new);
        $row_id_new = mysqli_fetch_assoc($result_id_new);
        $id_new = $row_id_new['id'];

        $sql="SET FOREIGN_KEY_CHECKS = 1";
        mysqli_query($conn, $sql);
        
        header("Location: k_pub_edit.php?id=".$id_new."&edit=true");
        exit;
    
}
  
 

?>
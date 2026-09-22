<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php

$sql="SET FOREIGN_KEY_CHECKS = 0";
mysqli_query($conn, $sql);

$id=$_GET['id'];

$sql_toProcess = "INSERT INTO `study` (`general_code`, `study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `e_year`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`,`study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type`, `personal_code`, `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate )  

SELECT general_code, study_num, study_num_date, study_request_jeha, study_reason, study_date, nick_name, name, fname, lname, mname, pbirth, dbirth, subname, sex, national, awsaf, family_status, child_num, wife_name, wife_address, work_before, work_after, work_now, study, money_status, dealing, service, special, pre_address, address, address_type, fasael, opinion, travels, n_relatives, d_relatives, f_relatives, s_relatives, religon_status, mind, lead, personal, affected_others, affected_to, relation_others, speak, important_awsaf, sawabek, phone, details, brief, foto1, foto2, foto3, attach, attach_extension, added_by, current_timestamp(), 0, '0000-00-00', '$jeha_profile', 0, 0, e_year, 'قيد المعالجة', resala_type, sendto, sendfrom, study_jeha, study_organizer, study_masdar, study_opinion, study_result, negative_reason, 0, '0000-00-00', 0, '0000-00-00', '', 0, `jeha` , `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, isPrivate FROM study WHERE id =$id";

  if(mysqli_query($conn, $sql_toProcess)){

    $sql="SET FOREIGN_KEY_CHECKS = 1";
    mysqli_query($conn, $sql);

    header ("Location: s_view_server_qayd.php?jeha=$jeha_profile&details_type=قيد المعالجة");
    exit;
  }else{
    echo "Error insert into study process status: "  . "<br>" . mysqli_error($conn);
    exit;
  } 


?>
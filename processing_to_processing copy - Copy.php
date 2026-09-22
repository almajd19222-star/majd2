
<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php include_once "columns_names.php"; ?>
<?php


 @$id = $_GET['id'];  
 @$eid = $_GET['e_id'];
 @$inserted_edbara_num = $_GET['edbara_num'];
 @$inserted_edbara_date = $_GET['edbara_date'];
 @$inserted_jeha = $_GET['jeha'];
 @$details_type = $_GET['details_type'];
 @$inserted_year = $_GET['e_year'];
 @$inserted_resala_type = $_GET['resala_type'];
 @$inserted_ketab_num = $_GET['ketab_num'];
 @$inserted_ketab_date = $_GET['ketab_date'];
 $details_type_new = 'قيد المعالجة';
 $added_by_old = $_GET['added_by_old'];



 /* $sql_reports_first = "SELECT id,ketab_num,ketab_date FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
        $sql_reports_result_first = mysqli_query($conn, $sql_reports_first);
        $report_row_first = mysqli_fetch_assoc($sql_reports_result_first);

        $inserted_ketab_num_first = $report_row_first['ketab_num'];
        $inserted_ketab_date_first = $report_row_first['ketab_date'];

        $sql_reports_duplicates = "SELECT id,ketab_num,ketab_date FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND ketab_num=$inserted_ketab_num_first AND ketab_date='$inserted_ketab_date_first'";
        $sql_reports_result_duplicates = mysqli_query($conn, $sql_reports_duplicates);
       

        if (mysqli_num_rows($sql_reports_result_duplicates) > 1) {
          while( $report_row_duplicates = mysqli_fetch_assoc($sql_reports_result_duplicates)) {   
            echo $report_row_duplicates['ketab_num'].'<br>';

          }
          exit;
          include_once "processing_to_processing_multi_ketab.php?ketab_num=$inserted_ketab_num_first&ketab_date=$inserted_ketab_date_first";
          exit;
        }
 */

 $sql_d_id = "SELECT d_id FROM dewan where d_id=(SELECT max(d_id) FROM dewan WHERE details_type='$details_type_new' AND jeha='$jeha_profile' )";
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
$sql_edbara_num_max = "SELECT edbara_num  FROM details where edbara_num=(SELECT max(`edbara_num`) FROM details WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(edbara_date)='$year')";
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
//check_ketab_num_processing
if($details_type=='قيد المعالجة'){      
  $sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type' AND jeha='$jeha_profile' AND YEAR(ketab_date)=$year)";
  $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
  $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
  if($row_r_num_max > 0) { 
    $r_num_max = $row_r_num_max['ketab_num']+1;

    $sql_r_num_max1 = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre = $r_num_max AND details_type='$details_type' AND jeha='$jeha_profile' AND YEAR(ketab_date_pre)=$year";
    $result_r_num_max1 = mysqli_query($conn, $sql_r_num_max1);
    $row_r_num_max1 = mysqli_fetch_assoc($result_r_num_max1);  
    if($row_r_num_max1 > 0) { 
     
      $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type' AND jeha='$jeha_profile' AND YEAR(ketab_date_pre)=$year)";
      $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
      $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);
      $r_num_max = $row_r_num_max['ketab_num_pre']+1;

    }else {
      @$r_num_max = $row_r_num_max1['ketab_num_pre'];
      if($r_num_max > 0){
        $r_num_max = $row_r_num_max1['ketab_num_pre'];
      }else{
        $r_num_max = $row_r_num_max['ketab_num']+1;
      }
    }
  }else {        
    $sql_r_num_max = "SELECT ketab_num_pre FROM processing_to_sader where ketab_num_pre=(SELECT max(ketab_num_pre) FROM   processing_to_sader WHERE details_type='$details_type' AND jeha='$jeha_profile' AND YEAR(ketab_date_pre)=$year)";
    $result_r_num_max = mysqli_query($conn, $sql_r_num_max);
    $row_r_num_max = mysqli_fetch_assoc($result_r_num_max);  
    if($row_r_num_max > 0) { 
      $r_num_max = $row_r_num_max['ketab_num_pre']+1;
    }else { $r_num_max = 1;}
  } 
}else{
$sql_r_num_max = "SELECT ketab_num FROM reports_info where ketab_num=(SELECT max(ketab_num) FROM reports_info WHERE details_type='$details_type' AND jeha='$jeha_profile' AND YEAR(ketab_date)=$year)";
$result_r_num_max = mysqli_query($conn, $sql_r_num_max);
$row_r_num_max = mysqli_fetch_assoc($result_r_num_max);

if($row_r_num_max > 0) { 
  $r_num_max = $row_r_num_max['ketab_num']+1;
}else { $r_num_max = 1;}
}





/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////////////////////////////////////////////////
if(!empty($_GET['id']) && !empty($_GET['type']) && $_GET['type']=='archive' ) {
 

    $sql = "SELECT * FROM `details` WHERE id=$id";
    $sql_result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($sql_result);
    $id_details=$row['id'];
    $nick_name=$row['nick_name'];
    $name=$row['name'];
    $fname=$row['fname'];
    $lname=$row['lname'];
    $mname=$row['mname'];
    $pbirth=$row['pbirth'];
    $dbirth=$row["dbirth"];
    $fullname=$name.$fname.$lname.$mname;
    

    $dupfullname="SELECT id, fullname FROM dup_names_check WHERE jeha='$jeha_profile' AND details_type='$details_type_new' AND fullname = ".replace_letters($fullname)."" ;
    $resultdupfullname = mysqli_query($conn, $dupfullname);
    $rowd = mysqli_fetch_assoc($resultdupfullname);
    if ($rowd > 0) {      
        $dupname='&dupname=true';
        //exit;
   

        $check_sader = "SELECT edbara_num_sader, edbara_date_sader FROM processing_to_processing WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_num_pre = $inserted_edbara_num AND edbara_date_pre = '$inserted_edbara_date' ";
        $check_sader_result = mysqli_query($conn, $check_sader);
        $check_sader_row = mysqli_fetch_assoc($check_sader_result);

        if (mysqli_num_rows($check_sader_result) > 0) {
            $edbara_num_sader=$check_sader_row['edbara_num_sader'];
            $edbara_date_sader=$check_sader_row['edbara_date_sader'];
        }else{
          $sql = "SELECT edbara_num, edbara_date FROM `details` WHERE name='$name' AND fname='$fname' AND lname = '$lname' AND mname='$mname' AND details_type = '$details_type_new' AND jeha = '$jeha_profile'";
          $sql_result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($sql_result);
          $edbara_num_sader=$row['edbara_num'];
          $edbara_date_sader=$row['edbara_date'];
        }

            /////// UPDATE tbl_uploads table ///////
            $sql_uploads = "SELECT id FROM `tbl_uploads` WHERE `jeha`='$inserted_jeha' AND `details_type`='$details_type' AND `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND upload_source='الأضابير الشخصية' ORDER BY `id` ASC";
       
            $sql_uploads_result = mysqli_query($conn, $sql_uploads);
        
            while ($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {
                $id_uploads = $uploads_row['id'];
    
                $sql2 = "UPDATE `tbl_uploads` SET
                `details_type`='$details_type_new',
                `jeha`='$jeha_profile',               
                `num`=$edbara_num_sader,
                `date`='$edbara_date_sader',
                `add_date` = current_timestamp() where id=$id_uploads  ORDER BY id ASC";
    
                if (mysqli_query($conn, $sql2)) {
                } else {
                    echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                    exit;
                }
            }

            //insert to dewan FROM `reports_info` table


            /////// UPDATE reports_info table ///////
            $sql_reports = "SELECT id,ketab_num,ketab_date FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
       
            $sql_reports_result = mysqli_query($conn, $sql_reports);

            $report_r_num_max=$r_num_max;
            $report_dewan_num_max = $dewan_num_max;
    
            while ($report_row = mysqli_fetch_assoc($sql_reports_result)) {
                $id_report = $report_row['id'];
                $inserted_ketab_num = $report_row['ketab_num'];
                $inserted_ketab_date = $report_row['ketab_date'];
                $inserted_ketab_year = date('Y', strtotime($report_row['ketab_date']));

                $check_sader = "SELECT * FROM processing_to_processing WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND ketab_num_pre = $inserted_ketab_num AND ketab_date_pre = '$inserted_ketab_date' ";
                $check_sader_result = mysqli_query($conn, $check_sader);
                $check_sader_row = mysqli_fetch_assoc($check_sader_result);
      
                if (mysqli_num_rows($check_sader_result) > 0) {
                    $ketab_num_sader = $check_sader_row['ketab_num_sader'];
                    $ketab_date_sader = $check_sader_row['ketab_date_sader'];
           
                    $update_reports_info = "UPDATE `reports_info` SET             
                    `details_type`='$details_type_new',
                    `jeha`='$jeha_profile',
                    `e_id`=$e_id_new,    
                    `dewan_num`=$ketab_num_sader, 
                    `dewan_date`='$ketab_date_sader', 
                    `ketab_num`=$ketab_num_sader, 
                    `ketab_date`='$ketab_date_sader',         
                    `edbara_num`=$edbara_num_sader,
                    `edbara_date`='$edbara_date_sader',
                    `add_date` = current_timestamp() where id=$id_report ORDER BY ketab_num ASC";

                    if (mysqli_query($conn, $update_reports_info)) {
                      


                        $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                        `details_type`='$details_type_new',
                        `jeha`='$jeha_profile',               
                        `num`=$ketab_num_sader,               
                        `date`='$ketab_date_sader',
                        `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الكتب' ORDER BY `num` ASC";

                        if (mysqli_query($conn, $update_tbl_uploads)) {
                        } else {
                            echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
              

                        /////// UPDATE study table ///////
                        $sql_study = "SELECT id FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";

                        $sql_study_result = mysqli_query($conn, $sql_study);
                        if (mysqli_num_rows($sql_study_result) > 0) {

                            //$study_ketab_num_max=$ketab_num_sader;

                            while ($study_row = mysqli_fetch_assoc($sql_study_result)) {
                                $id_study = $study_row['id'];

                                $update_study = "UPDATE study SET
                                details_type='$details_type_new',
                                jeha='$jeha_profile',
                                e_id=$e_id_new,           
                                ketab_num=$ketab_num_sader,
                                ketab_date='$ketab_date_sader',
                                dewan_num=$ketab_num_sader,
                                dewan_date='$ketab_date_sader',
                                `edbara_num`=$edbara_num_sader,
                                `edbara_date`='$edbara_date_sader',
                                added_by='$added_by_old',
                                add_date = current_timestamp()
                                where id=$id_study ORDER BY ketab_num ASC" ;

                                if (mysqli_query($conn, $update_study)) {
                                    

                                $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                                `details_type`='$details_type_new',
                                `jeha`='$jeha_profile',               
                                `num`=$ketab_num_sader,               
                                `date`='$ketab_date_sader',
                                `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الدراسات الأمنية' ORDER BY `num` ASC";

                                if(mysqli_query($conn, $update_tbl_uploads)){                  
                                }else{
                                  echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                                  exit;
                                }
                                } else {
                                    echo "Error Study: " . "<br>" . mysqli_error($conn);
                                    exit;
                                }
                                //$study_ketab_num_max++;
                            }
                        }


                        /////// UPDATE feech_info table ///////
                        if ($admin == 1 || $admin == 7) {
                            $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(d_balagh)=$year)";
                            $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
                            $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
                
                            if ($row_num_balagh_max > 0) {
                                $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
                            } else {
                                $num_balagh_max = 1;
                            }
                
              
                            $sql_feech_info = "SELECT id, num_balagh, d_balagh, edbara_num, edbara_date FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
                  
                            $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);
                            if (mysqli_num_rows($sql_feech_info_result) > 0) {
                                $report_feech_ketab_num_max=$report_r_num_max;
                                while ($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) {
                                    $id_feech_info = $feech_info_row['id'];
                                    $inserted_balagh_num = $feech_info_row['num_balagh'];
                                    $inserted_balagh_year = date('Y', strtotime($feech_info_row['d_balagh']));
                                    $inserted_balagh_date = $feech_info_row['d_balagh'];

                                    $inserted_edbara_num = $feech_info_row['edbara_num'];
                                    $inserted_edbara_date = $feech_info_row['edbara_date'];

                                    $update_feech_info = "UPDATE feech_info SET 
                                    details_type='$details_type_new',
                                    jeha='$jeha_profile',
                                    e_id=$e_id_new,
                                    `edbara_num`=$edbara_num_sader,
                                    `edbara_date`='$edbara_date_sader',           
                                    num_balagh=$num_balagh_max, 
                                    d_balagh='$today',
                                    ketab_num=$ketab_num_sader, 
                                    ketab_date='$ketab_date_sader', 
                                    added_by='$added_by_old - $user',
                                    `add_date` = current_timestamp()            
                                    where id=$id_feech_info ORDER BY ketab_num ASC " ;
                                
                                    if (mysqli_query($conn, $update_feech_info)) {
                                        
                                       $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                                      `details_type`='$details_type_new',
                                      `jeha`='$jeha_profile',               
                                      `num`=$edbara_num_sader,               
                                      `date`='$edbara_date_sader',
                                      `add_date` = current_timestamp() WHERE `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND details_type='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الفيش' ORDER BY num ASC";
                                
                                      if(mysqli_query($conn, $update_tbl_uploads)){                  
                                      }else{
                                        echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                                        exit;
                                      }
                                    } else {
                                        echo "Error feech_info: " . "<br>" . mysqli_error($conn);
                                        exit;
                                    }
                                    $num_balagh_max++;
                                    //$report_feech_ketab_num_max++;
                                }
                            }
                        }

                        //////////// UPDATE mawkoof tables ////////////
                  $table_name[]='mawkoof_adjudication_mahdar';
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

                        $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                        $result_check = mysqli_query($conn, $sql_check);
                        if (mysqli_num_rows($result_check) > 0){

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
                      }

                      $sql = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$ketab_num_sader,               
                      `date`='$ketab_date_sader',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='أضابير الموقوفين'  ORDER BY `num` ASC";
                      if(mysqli_query($conn, $sql)){                  
                      }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
                  }



                //////////// UPDATE studies tables ////////////
                  $table_name[]='studies_association';
                  $table_name[]='studies_association_attachment';
                  $table_name[]='studies_association_projects';
                  $table_name[]='studies_car_shops';
                  $table_name[]='studies_computers_phones_shops';
                  $table_name[]='studies_estate_offices';
                  $table_name[]='studies_exchange_shops';
                  $table_name[]='studies_factions';
                  $table_name[]='studies_factions_attachment_1';
                  $table_name[]='studies_factions_attachment_2';
                  $table_name[]='studies_factions_attachment_3';
                  $table_name[]='studies_factions_attachment_4';
                  $table_name[]='studies_factions_attachment_5';
                  $table_name[]='studies_factions_attachment_6';
                  $table_name[]='studies_factions_attachment_7';
                  $table_name[]='studies_factions_attachment_8';
                  $table_name[]='studies_factions_attachment_9';
                  $table_name[]='studies_factions_attachment_10';
                  $table_name[]='studies_fertilizers_and_pesticides';
                  $table_name[]='studies_fertilizers_and_pesticides_attachment';
                  $table_name[]='studies_forgery_and_stamps_offices';
                  $table_name[]='studies_it_shops';
                  $table_name[]='studies_kiosks';
                  $table_name[]='studies_organizations';
                  $table_name[]='studies_organization_attachment';
                  $table_name[]='studies_organization_projects';
                  $table_name[]='studies_smugglers';
                  $table_name[]='studies_training_centre';
                  $table_name[]='studies_training_centre_attachment';
                  $table_name[]='studies_training_centre_projects';
                  $table_name[]='studies_universities';
                  $table_name[]='studies_unofficial_civil_activities';
                  $table_name[]='studies_weapon_shops';
                  $table_name[]='studies_weapon_shops_attachment';      
                  $table_name[]='studies_weapon_traders';
                //// 828 ////
                  $table_name[]='studies_828_checkpoint_study';
                  $table_name[]='studies_828_goal';
                  $table_name[]='studies_828_military_site_study';
                  $table_name[]='studies_828_military_site_study_attachment';
                  $table_name[]='studies_828_personal_security_study';
                  $table_name[]='studies_828_security_center_study';
                  $table_name[]='studies_828_security_center_study_attachment';
                  $table_name[]='studies_828_town';
                  $table_name[]='studies_828_town_bakeries';
                  $table_name[]='studies_828_town_council';
                  $table_name[]='studies_828_town_demographic_information';
                  $table_name[]='studies_828_town_education';
                  $table_name[]='studies_828_town_schools';
                  $table_name[]='studies_828_town_faculties';
                  $table_name[]='studies_828_town_famous_families';
                  $table_name[]='studies_828_town_famous_mosques';
                  $table_name[]='studies_828_town_important_military_people';
                  $table_name[]='studies_828_town_influencers';
                  $table_name[]='studies_828_town_made_by';
                  $table_name[]='studies_828_town_military_branches';
                  $table_name[]='studies_828_town_military_places';
                  $table_name[]='studies_828_town_mukhtar_name';
                  $table_name[]='studies_828_town_new_military_places';
                  $table_name[]='studies_828_town_organisation';
                  $table_name[]='studies_828_town_public_utilities';
                  $table_name[]='studies_828_town_rich_people';
                  $table_name[]='studies_828_town_russian_cultral_centers';
                  $table_name[]='studies_828_town_shari3a_institutes';
                  $table_name[]='studies_828_town_shi3a_centers';
                //// 2022 ////
                  $table_name[]='studies_2022';
                  $table_name[]='studies_2022_attachments';

                  $number = count($table_name);
              
                  if ($number >= 1) {
                      for ($i=0; $i<$number; $i++) {
                        $sql_table_name= $table_name[$i];

                        $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                        $result_check = mysqli_query($conn, $sql_check);
                        if (mysqli_num_rows($result_check) > 0){

                          $sql = "UPDATE `$sql_table_name` SET
                          details_type='$details_type_new',
                          jeha='$jeha_profile',          
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
                      }

                      $sql = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$ketab_num_sader,               
                      `date`='$ketab_date_sader',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='المسح الأمني'  ORDER BY `num` ASC";
                      if(mysqli_query($conn, $sql)){                  
                      }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
                  }

                    } else {
                        echo "Error sql_reports: " . "<br>" . mysqli_error($conn);
                        exit;
                    }


                } else {
                    $update_reports_info = "UPDATE `reports_info` SET             
                    `details_type`='$details_type_new',
                    `jeha`='$jeha_profile',
                    `e_id`=$e_id_new,    
                    `dewan_num`=$report_r_num_max, 
                    `dewan_date`='$today', 
                    `ketab_num`=$report_r_num_max, 
                    `ketab_date`='$today',         
                    `edbara_num`=$edbara_num_sader,
                    `edbara_date`='$edbara_date_sader',
                    `add_date` = current_timestamp() where id=$id_report ORDER BY ketab_num ASC";
                    if (mysqli_query($conn, $update_reports_info)) {
                        $sql77="INSERT INTO processing_to_processing (ketab_num_pre, ketab_date_pre, ketab_num_sader, ketab_date_sader,edbara_num_pre, edbara_date_pre, edbara_num_sader, edbara_date_sader, jeha, details_type)
                        VALUES ($inserted_ketab_num , '$inserted_ketab_date', $report_r_num_max, '$today', $inserted_edbara_num , '$inserted_edbara_date', $edbara_num_sader, '$edbara_date_sader', '$inserted_jeha', '$details_type')";
                        mysqli_query($conn, $sql77);



                   

                        $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                        `details_type`='$details_type_new',
                        `jeha`='$jeha_profile',               
                        `num`=$report_r_num_max,               
                        `date`='$today',
                        `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الكتب' ORDER BY num ASC";

                          if(mysqli_query($conn, $update_tbl_uploads)){                  
                          }else{
                            echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                            exit;
                          }
              

                        /////// UPDATE study table ///////
                        $sql_study = "SELECT id FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";

                        $sql_study_result = mysqli_query($conn, $sql_study);
                        if (mysqli_num_rows($sql_study_result) > 0) {
                            $study_ketab_num_max=$report_r_num_max;

                            while ($study_row = mysqli_fetch_assoc($sql_study_result)) {
                                $id_study = $study_row['id'];

                                $update_study = "UPDATE study SET
                                  details_type='$details_type_new',
                                  jeha='$jeha_profile',
                                  e_id=$e_id_new,           
                                  ketab_num=$study_ketab_num_max,
                                  ketab_date='$today',
                                  dewan_num=$study_ketab_num_max,
                                  dewan_date='$today',
                                  `edbara_num`=$edbara_num_sader,
                                  `edbara_date`='$edbara_date_sader',
                                  added_by='$added_by_old',
                                  add_date = current_timestamp()
                                  where id=$id_study ORDER BY ketab_num ASC" ;

                                if (mysqli_query($conn, $update_study)) {
                                  

                               $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                                `details_type`='$details_type_new',
                                `jeha`='$jeha_profile',               
                                `num`=$study_ketab_num_max,               
                                `date`='$today',
                                `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الدراسات الأمنية' ORDER BY num ASC";

                                  if(mysqli_query($conn, $update_tbl_uploads)){                  
                                  }else{
                                    echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                                    exit;
                                  }
                                } else {
                                    echo "Error Study: " . "<br>" . mysqli_error($conn);
                                    exit;
                                }
                                $study_ketab_num_max++;
                            }
                        }

                        

                        /////// UPDATE feech_info table ///////
                        if ($admin == 1 || $admin == 7) {
                            $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(d_balagh)=$year)";
                            $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
                            $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
                
                            if ($row_num_balagh_max > 0) {
                                $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
                            } else {
                                $num_balagh_max = 1;
                            }
                
              
                            $sql_feech_info = "SELECT id, num_balagh, d_balagh, edbara_num, edbara_date FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
                  
                            $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);
                            if (mysqli_num_rows($sql_feech_info_result) > 0) {
                                $report_feech_ketab_num_max=$report_r_num_max;
                                while ($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) {
                                    $id_feech_info = $feech_info_row['id'];
                                    $inserted_balagh_num = $feech_info_row['num_balagh'];
                                    $inserted_balagh_year = date('Y', strtotime($feech_info_row['d_balagh']));
                                    $inserted_balagh_date = $feech_info_row['d_balagh'];

                                    $update_feech_info = "UPDATE feech_info SET 
                                    details_type='$details_type_new',
                                    jeha='$jeha_profile',
                                    e_id=$e_id_new,
                                    `edbara_num`=$edbara_num_sader,
                                    `edbara_date`='$edbara_date_sader',           
                                    num_balagh=$num_balagh_max, 
                                    d_balagh='$today',
                                    ketab_num=$report_feech_ketab_num_max, 
                                    ketab_date='$today', 
                                    added_by='$added_by_old - $user',
                                    `add_date` = current_timestamp()            
                                    where id=$id_feech_info ORDER BY ketab_num ASC " ;
                  
                                    if (mysqli_query($conn, $update_feech_info)) {
                                     

                                        $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                                        `details_type`='$details_type_new',
                                        `jeha`='$jeha_profile',               
                                        `num`=$edbara_num_sader,               
                                        `date`='$edbara_date_sader',
                                        `add_date` = current_timestamp() where `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND details_type='$details_type' AND jeha='$inserted_jeha' AND upload_source='الفيش'  ORDER BY num ASC";
                                  
                                        if(mysqli_query($conn, $update_tbl_uploads)){                  
                                        }else{
                                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                                          exit;
                                        }
                                    } else {
                                        echo "Error feech_info: " . "<br>" . mysqli_error($conn);
                                        exit;
                                    }
                                    $num_balagh_max++;
                                    $report_feech_ketab_num_max++;
                                }
                            }
                        }
                    } else {
                        echo "Error sql_reports: " . "<br>" . mysqli_error($conn);
                        exit;
                    }
              

                     //////////// UPDATE mawkoof tables ////////////
            $table_name[]='mawkoof_adjudication_mahdar';
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

                  $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                  $result_check = mysqli_query($conn, $sql_check);
                  if (mysqli_num_rows($result_check) > 0){

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
                }

                $sql = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$report_r_num_max,               
                      `date`='$today',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='أضابير الموقوفين'  ORDER BY `num` ASC";
                      if(mysqli_query($conn, $sql)){                  
                      }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
            }



            //////////// UPDATE studies tables ////////////
            $table_name[]='studies_association';
            $table_name[]='studies_association_attachment';
            $table_name[]='studies_association_projects';
            $table_name[]='studies_car_shops';
            $table_name[]='studies_computers_phones_shops';
            $table_name[]='studies_estate_offices';
            $table_name[]='studies_exchange_shops';
            $table_name[]='studies_factions';
            $table_name[]='studies_factions_attachment_1';
            $table_name[]='studies_factions_attachment_2';
            $table_name[]='studies_factions_attachment_3';
            $table_name[]='studies_factions_attachment_4';
            $table_name[]='studies_factions_attachment_5';
            $table_name[]='studies_factions_attachment_6';
            $table_name[]='studies_factions_attachment_7';
            $table_name[]='studies_factions_attachment_8';
            $table_name[]='studies_factions_attachment_9';
            $table_name[]='studies_factions_attachment_10';
            $table_name[]='studies_fertilizers_and_pesticides';
            $table_name[]='studies_fertilizers_and_pesticides_attachment';
            $table_name[]='studies_forgery_and_stamps_offices';
            $table_name[]='studies_it_shops';
            $table_name[]='studies_kiosks';
            $table_name[]='studies_organizations';
            $table_name[]='studies_organization_attachment';
            $table_name[]='studies_organization_projects';
            $table_name[]='studies_smugglers';
            $table_name[]='studies_training_centre';
            $table_name[]='studies_training_centre_attachment';
            $table_name[]='studies_training_centre_projects';
            $table_name[]='studies_universities';
            $table_name[]='studies_unofficial_civil_activities';
            $table_name[]='studies_weapon_shops';
            $table_name[]='studies_weapon_shops_attachment';      
            $table_name[]='studies_weapon_traders';
          //// 828 ////
            $table_name[]='studies_828_checkpoint_study';
            $table_name[]='studies_828_goal';
            $table_name[]='studies_828_military_site_study';
            $table_name[]='studies_828_military_site_study_attachment';
            $table_name[]='studies_828_personal_security_study';
            $table_name[]='studies_828_security_center_study';
            $table_name[]='studies_828_security_center_study_attachment';
            $table_name[]='studies_828_town';
            $table_name[]='studies_828_town_bakeries';
            $table_name[]='studies_828_town_council';
            $table_name[]='studies_828_town_demographic_information';
            $table_name[]='studies_828_town_education';
            $table_name[]='studies_828_town_schools';
            $table_name[]='studies_828_town_faculties';
            $table_name[]='studies_828_town_famous_families';
            $table_name[]='studies_828_town_famous_mosques';
            $table_name[]='studies_828_town_important_military_people';
            $table_name[]='studies_828_town_influencers';
            $table_name[]='studies_828_town_made_by';
            $table_name[]='studies_828_town_military_branches';
            $table_name[]='studies_828_town_military_places';
            $table_name[]='studies_828_town_mukhtar_name';
            $table_name[]='studies_828_town_new_military_places';
            $table_name[]='studies_828_town_organisation';
            $table_name[]='studies_828_town_public_utilities';
            $table_name[]='studies_828_town_rich_people';
            $table_name[]='studies_828_town_russian_cultral_centers';
            $table_name[]='studies_828_town_shari3a_institutes';
            $table_name[]='studies_828_town_shi3a_centers';
          //// 2022 ////
            $table_name[]='studies_2022';
            $table_name[]='studies_2022_attachments';     


            $number = count($table_name);
        
            if ($number >= 1) {
                for ($i=0; $i<$number; $i++) {
                  $sql_table_name= $table_name[$i];

                  $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                  $result_check = mysqli_query($conn, $sql_check);
                  if (mysqli_num_rows($result_check) > 0){

                    $sql = "UPDATE `$sql_table_name` SET
                    details_type='$details_type_new',
                    jeha='$jeha_profile',          
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
                }

                $sql = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$report_r_num_max,               
                      `date`='$today',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='المسح الأمني'  ORDER BY `num` ASC";
                      if(mysqli_query($conn, $sql)){                  
                      }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
            }


                    $report_r_num_max++;
                    //$report_dewan_num_max++;
                }
            }
       
    
          
       
           


            $sql_id_max = "SELECT id  FROM details where  details_type='$details_type_new' AND jeha='$jeha_profile' AND edbara_num=$edbara_num_sader AND edbara_date='$edbara_date_sader'";
            $result_id_max = mysqli_query($conn, $sql_id_max);
            $row_id_max = mysqli_fetch_assoc($result_id_max);
            $id_max=$row_id_max['id'];
        
        
            header ("Location: j_view_server_qayd.php?resala_type=كتاب&jeha=$jeha_profile&details_type=قيد المعالجة");
               
            exit;
        
    }else{ // if $check_sader_result NOT TRUE
      $sql1 = "INSERT INTO `details` (".$details_cols.")  SELECT nick_name, name, fname, lname, mname, pbirth, dbirth, address, sex, national, awsaf, jeha_name, $e_id_new, $year, $edbara_num_max, '$today', 0, '0000-00-00',  study, edbara_note, edbara_info, note, result, details_attach, details_attach_extension, foto1, foto2, foto3, data_type, '$jeha_profile', '$user', current_timestamp(), '$details_type_new', resala_type, 0, 0 , `jeha`, `id_num`, isPrivate FROM details  where id = $id";
 



      if(mysqli_query($conn, $sql1)){


        $sql_dup="INSERT INTO `dup_names_check` (`fullname`, `name`, `fname`, `lname`, `mname`, `dbirth`, `pbirth`, `jeha`,   `details_type`, `e_id`, `edbara_num`, `edbara_date`) SELECT ".replace_letters($fullname)." , ".replace_letters($name).", ".replace_letters($fname).", ".replace_letters($lname).", ".replace_letters($mname).", `dbirth`,  ".replace_letters($pbirth).", '$jeha_profile', '$details_type_new', $e_id_new, $edbara_num_max, '$today' FROM `details` WHERE    jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_num=$inserted_edbara_num AND edbara_date='$inserted_edbara_date' ";
        if(mysqli_query($conn, $sql_dup)){
        }else{
          echo "Error sql_dup: " . "<br>" . mysqli_error($conn);
          exit;
        }

        /////// UPDATE tbl_uploads table ///////
        $sql_uploads = "SELECT id FROM `tbl_uploads` WHERE `jeha`='$inserted_jeha' AND `details_type`='$details_type' AND `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND upload_source='الأضابير الشخصية' ORDER BY `id` ASC";
       
        $sql_uploads_result = mysqli_query($conn, $sql_uploads);   
        
        while($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {   
          $id_uploads = $uploads_row['id'];
    
          $sql2 = "UPDATE `tbl_uploads` SET
          `details_type`='$details_type_new',
          `jeha`='$jeha_profile',               
          `num`=$edbara_num_max,
          `date`='$today',
          `add_date` = current_timestamp() where id=$id_uploads  ORDER BY id ASC";
    
            if(mysqli_query($conn, $sql2)){
              
            }else{
              echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
              exit;
            }
           
        }

        //insert to dewan FROM `reports_info` table


        /////// UPDATE reports_info table ///////

        $sql_reports = "SELECT id,ketab_num,ketab_date FROM `reports_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
       
        $sql_reports_result = mysqli_query($conn, $sql_reports); 
        
        $report_r_num_max=$r_num_max;
    
        while($report_row = mysqli_fetch_assoc($sql_reports_result)) {   

          $id_report = $report_row['id'];
          $inserted_ketab_num = $report_row['ketab_num'];
          $inserted_ketab_date = $report_row['ketab_date'];
          $inserted_ketab_year = date('Y',strtotime($report_row['ketab_date']));
          
          $check_sader = "SELECT * FROM processing_to_processing WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND ketab_num_pre = $inserted_ketab_num AND ketab_date_pre = '$inserted_ketab_date' ";
          $check_sader_result = mysqli_query($conn,$check_sader);
          $check_sader_row = mysqli_fetch_assoc($check_sader_result);
      
          if (mysqli_num_rows($check_sader_result) > 0) {
            $ketab_num_sader = $check_sader_row['ketab_num_sader'];
            $ketab_date_sader = $check_sader_row['ketab_date_sader'];
           
           
            $update_reports_info = "UPDATE `reports_info` SET             
            `details_type`='$details_type_new',
            `jeha`='$jeha_profile',
            `e_id`=$e_id_new,    
            `dewan_num`=$ketab_num_sader, 
            `dewan_date`='$ketab_date_sader', 
            `ketab_num`=$ketab_num_sader, 
            `ketab_date`='$ketab_date_sader',         
            `edbara_num`=$edbara_num_max,
            `edbara_date`='$today',
            `add_date` = current_timestamp() where id=$id_report ORDER BY ketab_num ASC";

            if(mysqli_query($conn, $update_reports_info)){             

             


              $update_tbl_uploads = "UPDATE `tbl_uploads` SET
              `details_type`='$details_type_new',
              `jeha`='$jeha_profile',               
              `num`=$ketab_num_sader,               
              `date`='$today',
              `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الكتب'  ORDER BY num ASC";

                if(mysqli_query($conn, $update_tbl_uploads)){                  
                }else{
                  echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                  exit;
                }
              

              /////// UPDATE study table ///////
              $sql_study = "SELECT id FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";

              $sql_study_result = mysqli_query($conn, $sql_study);
              if (mysqli_num_rows($sql_study_result) > 0) {

                //$study_ketab_num_max=$ketab_num_sader;

                while($study_row = mysqli_fetch_assoc($sql_study_result)) {   

                  $id_study = $study_row['id'];        

                  $update_study = "UPDATE study SET
                  details_type='$details_type_new',
                  jeha='$jeha_profile',
                  e_id=$e_id_new,           
                  ketab_num=$ketab_num_sader,
                  ketab_date='$ketab_date_sader',
                  dewan_num=$ketab_num_sader,
                  dewan_date='$ketab_date_sader',
                  `edbara_num`=$edbara_num_max,
                  `edbara_date`='$today',
                  added_by='$added_by_old',
                  add_date = current_timestamp()
                  where id=$id_study ORDER BY ketab_num ASC" ;

                  if(mysqli_query($conn, $update_study)){

                  

                    $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                        `details_type`='$details_type_new',
                        `jeha`='$jeha_profile',               
                        `num`=$ketab_num_sader,               
                        `date`='$ketab_date_sader',
                        `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الدراسات الأمنية' ORDER BY num ASC";

                          if(mysqli_query($conn, $update_tbl_uploads)){                  
                          }else{
                            echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                            exit;
                          }
                    }else{
                        echo "Error Study: " . "<br>" . mysqli_error($conn);
                        exit;
                    } 
                    //$study_ketab_num_max++;
                }
              }

            





              /////// UPDATE feech_info table ///////
                if($admin == 1 || $admin == 7){

                  $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(d_balagh)=$year)";
                  $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
                  $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
                
                  if($row_num_balagh_max > 0) { 
                    $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
                  }else { $num_balagh_max = 1;}
                
              
                  $sql_feech_info = "SELECT id, num_balagh, d_balagh, edbara_num, edbara_date FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
                  
                  $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);
                  if (mysqli_num_rows($sql_feech_info_result) > 0) {
                  
                    $report_feech_ketab_num_max=$report_r_num_max;
                    while($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) { 
                      $id_feech_info = $feech_info_row['id'];
                      $inserted_balagh_num = $feech_info_row['num_balagh'];
                      $inserted_balagh_year = date('Y',strtotime($feech_info_row['d_balagh']));
                      $inserted_balagh_date = $feech_info_row['d_balagh'];

                      $inserted_edbara_num = $feech_info_row['edbara_num'];
                      $inserted_edbara_date = $feech_info_row['edbara_date'];


                      $update_feech_info = "UPDATE feech_info SET 
                      details_type='$details_type_new',
                      jeha='$jeha_profile',
                      e_id=$e_id_new,
                      `edbara_num`=$edbara_num_max,
                      `edbara_date`='$today',           
                      num_balagh=$num_balagh_max, 
                      d_balagh='$today',
                      ketab_num=$ketab_num_sader, 
                      ketab_date='$ketab_date_sader', 
                      added_by='$added_by_old - $user',
                      `add_date` = current_timestamp()            
                      where id=$id_feech_info ORDER BY ketab_num ASC " ;
                  
                      if(mysqli_query($conn, $update_feech_info)){

                       
                        $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                        `details_type`='$details_type_new',
                        `jeha`='$jeha_profile',               
                        `num`=$edbara_num_max,               
                        `date`='$today',
                        `add_date` = current_timestamp() where `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الفيش'  ORDER BY `num` ASC";
                  
                        if(mysqli_query($conn, $update_tbl_uploads)){                  
                        }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                        }
                      }else{
                        echo "Error feech_info: " . "<br>" . mysqli_error($conn);
                        exit;
                      }
                      $num_balagh_max++;
                      //$report_feech_ketab_num_max++;
                    }
                  }
                
                }



                 //////////// UPDATE mawkoof tables ////////////
                 $table_name[]='mawkoof_adjudication_mahdar';
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

                       $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                        $result_check = mysqli_query($conn, $sql_check);
                        if (mysqli_num_rows($result_check) > 0){

                          $sql = "UPDATE `$sql_table_name` SET
                          details_type='$details_type_new',
                          jeha='$jeha_profile',          
                          edbara_num=$edbara_num_max,
                          edbara_date='$today',   
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
                      }

                     $sql = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$ketab_num_sader,               
                      `date`='$ketab_date_sader',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='أضابير الموقوفين'  ORDER BY `num` ASC";
                      if(mysqli_query($conn, $sql)){                  
                      }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
                 }
 
 
                 //////////// UPDATE studies tables ////////////
                   $table_name[]='studies_association';
                   $table_name[]='studies_association_attachment';
                   $table_name[]='studies_association_projects';
                   $table_name[]='studies_car_shops';
                   $table_name[]='studies_computers_phones_shops';
                   $table_name[]='studies_estate_offices';
                   $table_name[]='studies_exchange_shops';
                   $table_name[]='studies_factions';
                   $table_name[]='studies_factions_attachment_1';
                   $table_name[]='studies_factions_attachment_2';
                   $table_name[]='studies_factions_attachment_3';
                   $table_name[]='studies_factions_attachment_4';
                   $table_name[]='studies_factions_attachment_5';
                   $table_name[]='studies_factions_attachment_6';
                   $table_name[]='studies_factions_attachment_7';
                   $table_name[]='studies_factions_attachment_8';
                   $table_name[]='studies_factions_attachment_9';
                   $table_name[]='studies_factions_attachment_10';
                   $table_name[]='studies_fertilizers_and_pesticides';
                   $table_name[]='studies_fertilizers_and_pesticides_attachment';
                   $table_name[]='studies_forgery_and_stamps_offices';
                   $table_name[]='studies_it_shops';
                   $table_name[]='studies_kiosks';
                   $table_name[]='studies_organizations';
                   $table_name[]='studies_organization_attachment';
                   $table_name[]='studies_organization_projects';
                   $table_name[]='studies_smugglers';
                   $table_name[]='studies_training_centre';
                   $table_name[]='studies_training_centre_attachment';
                   $table_name[]='studies_training_centre_projects';
                   $table_name[]='studies_universities';
                   $table_name[]='studies_unofficial_civil_activities';
                   $table_name[]='studies_weapon_shops';
                   $table_name[]='studies_weapon_shops_attachment';      
                   $table_name[]='studies_weapon_traders';
                 //// 828 ////
                   $table_name[]='studies_828_checkpoint_study';
                   $table_name[]='studies_828_goal';
                   $table_name[]='studies_828_military_site_study';
                   $table_name[]='studies_828_military_site_study_attachment';
                   $table_name[]='studies_828_personal_security_study';
                   $table_name[]='studies_828_security_center_study';
                   $table_name[]='studies_828_security_center_study_attachment';
                   $table_name[]='studies_828_town';
                   $table_name[]='studies_828_town_bakeries';
                   $table_name[]='studies_828_town_council';
                   $table_name[]='studies_828_town_demographic_information';
                   $table_name[]='studies_828_town_education';
                   $table_name[]='studies_828_town_schools';
                   $table_name[]='studies_828_town_faculties';
                   $table_name[]='studies_828_town_famous_families';
                   $table_name[]='studies_828_town_famous_mosques';
                   $table_name[]='studies_828_town_important_military_people';
                   $table_name[]='studies_828_town_influencers';
                   $table_name[]='studies_828_town_made_by';
                   $table_name[]='studies_828_town_military_branches';
                   $table_name[]='studies_828_town_military_places';
                   $table_name[]='studies_828_town_mukhtar_name';
                   $table_name[]='studies_828_town_new_military_places';
                   $table_name[]='studies_828_town_organisation';
                   $table_name[]='studies_828_town_public_utilities';
                   $table_name[]='studies_828_town_rich_people';
                   $table_name[]='studies_828_town_russian_cultral_centers';
                   $table_name[]='studies_828_town_shari3a_institutes';
                   $table_name[]='studies_828_town_shi3a_centers';
                 //// 2022 ////
                   $table_name[]='studies_2022';
                   $table_name[]='studies_2022_attachments';
                                   
                   $number = count($table_name);
               
                   if ($number >= 1) {
                        for ($i=0; $i<$number; $i++) {
                         $sql_table_name= $table_name[$i];

                         $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                          $result_check = mysqli_query($conn, $sql_check);
                          if (mysqli_num_rows($result_check) > 0){

                            $sql = "UPDATE `$sql_table_name` SET
                            details_type='$details_type_new',
                            jeha='$jeha_profile',          
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
                        }
                       $sql = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$ketab_num_sader,               
                      `date`='$ketab_date_sader',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='المسح الأمني'  ORDER BY `num` ASC";
                      if(mysqli_query($conn, $sql)){                  
                      }else{
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
                   }

            }else{
              echo "Error sql_reports: " . "<br>" . mysqli_error($conn);
              exit;
            }
  



          }else{
              $update_reports_info = "UPDATE `reports_info` SET
              `details_type`='$details_type_new',
              `jeha`='$jeha_profile',
              `e_id`=$e_id_new,    
              `dewan_num`=$report_r_num_max, 
              `dewan_date`='$today', 
              `ketab_num`=$report_r_num_max, 
              `ketab_date`='$today',         
              `edbara_num`=$edbara_num_max,
              `edbara_date`='$today',
              `add_date` = current_timestamp() where id=$id_report ORDER BY ketab_num ASC";
    
              if (mysqli_query($conn, $update_reports_info)) {
                  $sql77="INSERT INTO processing_to_processing (ketab_num_pre, ketab_date_pre, ketab_num_sader, ketab_date_sader,edbara_num_pre, edbara_date_pre, edbara_num_sader, edbara_date_sader, jeha, details_type)
                  VALUES ($inserted_ketab_num , '$inserted_ketab_date', $report_r_num_max, '$today', $inserted_edbara_num , '$inserted_edbara_date', $edbara_num_max, '$today', '$inserted_jeha', '$details_type')";
                  mysqli_query($conn, $sql77);

                


                  $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                  `details_type`='$details_type_new',
                  `jeha`='$jeha_profile',               
                  `num`=$report_r_num_max,               
                  `date`='$today',
                  `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الكتب'  ORDER BY `num` ASC";
      
                  if (mysqli_query($conn, $update_tbl_uploads)) {
                  } else {
                      echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                      exit;
                  }
            

                  /////// UPDATE study table ///////
                  $sql_study = "SELECT id FROM `study` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
       
                  $sql_study_result = mysqli_query($conn, $sql_study);
                  if (mysqli_num_rows($sql_study_result) > 0) {
                      $study_ketab_num_max=$report_r_num_max;
          
                      while ($study_row = mysqli_fetch_assoc($sql_study_result)) {
                          $id_study = $study_row['id'];

                          $update_study = "UPDATE study SET
                            details_type='$details_type_new',
                            jeha='$jeha_profile',
                            e_id=$e_id_new,           
                            ketab_num=$study_ketab_num_max,
                            ketab_date='$today',
                            dewan_num=$study_ketab_num_max,
                            dewan_date='$today',
                            `edbara_num`=$edbara_num_max,
                            `edbara_date`='$today',
                            added_by='$added_by_old',
                            add_date = current_timestamp()
                            where id=$id_study ORDER BY ketab_num ASC" ;
        
                          if (mysqli_query($conn, $update_study)) {
                          

                            $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                            `details_type`='$details_type_new',
                            `jeha`='$jeha_profile',               
                            `num`=$study_ketab_num_max,               
                            `date`='$today',
                            `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الدراسات الأمنية' ORDER BY num ASC";
    
                              if(mysqli_query($conn, $update_tbl_uploads)){                  
                              }else{
                                echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                                exit;
                              }
                          } else {
                              echo "Error Study: " . "<br>" . mysqli_error($conn);
                              exit;
                          }
                          $study_ketab_num_max++;
                      }
                  }
    
           





                  /////// UPDATE feech_info table ///////
                  if ($admin == 1 || $admin == 7) {
                      $sql_num_balagh_max = "SELECT num_balagh FROM feech_info where num_balagh=(SELECT max(num_balagh) FROM feech_info WHERE details_type='$details_type_new' AND jeha='$jeha_profile' AND YEAR(d_balagh)=$year)";
                      $result_num_balagh_max = mysqli_query($conn, $sql_num_balagh_max);
                      $row_num_balagh_max = mysqli_fetch_assoc($result_num_balagh_max);
              
                      if ($row_num_balagh_max > 0) {
                          $num_balagh_max = $row_num_balagh_max['num_balagh']+1;
                      } else {
                          $num_balagh_max = 1;
                      }
              
            
                      $sql_feech_info = "SELECT id, num_balagh, d_balagh, edbara_num, edbara_date FROM `feech_info` WHERE jeha='$inserted_jeha' AND details_type='$details_type' AND edbara_date='$inserted_edbara_date' AND edbara_num=$inserted_edbara_num  ORDER BY ketab_num ASC";
                
                      $sql_feech_info_result = mysqli_query($conn, $sql_feech_info);
                      if (mysqli_num_rows($sql_feech_info_result) > 0) {
                          $report_feech_ketab_num_max=$report_r_num_max;
                          while ($feech_info_row = mysqli_fetch_assoc($sql_feech_info_result)) {
                              $id_feech_info = $feech_info_row['id'];
                              $inserted_balagh_num = $feech_info_row['num_balagh'];
                              $inserted_balagh_year = date('Y', strtotime($feech_info_row['d_balagh']));
                              $inserted_balagh_date = $feech_info_row['d_balagh'];

                              $inserted_edbara_num = $feech_info_row['edbara_num'];
                              $inserted_edbara_date = $feech_info_row['edbara_date'];

          
                              $update_feech_info = "UPDATE feech_info SET 
                              details_type='$details_type_new',
                              jeha='$jeha_profile',
                              e_id=$e_id_new,
                              `edbara_num`=$edbara_num_max,
                              `edbara_date`='$today',           
                              num_balagh=$num_balagh_max, 
                              d_balagh='$today',
                              ketab_num=$report_feech_ketab_num_max, 
                              ketab_date='$today', 
                              added_by='$added_by_old - $user',
                              `add_date` = current_timestamp()            
                              where id=$id_feech_info ORDER BY ketab_num ASC " ;
                
                              if (mysqli_query($conn, $update_feech_info)) {
                                 
                                $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                                `details_type`='$details_type_new',
                                `jeha`='$jeha_profile',               
                                `num`=$edbara_num_max,               
                                `date`='$today',
                                `add_date` = current_timestamp() where 
                                `num`=$inserted_edbara_num AND `date`='$inserted_edbara_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الفيش'  ORDER BY `num` ASC";
              
                                if (mysqli_query($conn, $update_tbl_uploads)) {
                                } else {
                                    echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                                    exit;
                                }
                              } else {
                                  echo "Error feech_info: " . "<br>" . mysqli_error($conn);
                                  exit;
                              }
                              $num_balagh_max++;
                              $report_feech_ketab_num_max++;
                          }
                      }
                  }
              } else {
                  echo "Error sql_reports: " . "<br>" . mysqli_error($conn);
                  exit;
              }
            



               //////////// UPDATE mawkoof tables ////////////
               $table_name[]='mawkoof_adjudication_mahdar';
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

                     $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                        $result_check = mysqli_query($conn, $sql_check);
                        if (mysqli_num_rows($result_check) > 0){

                        $sql = "UPDATE `$sql_table_name` SET
                        `details_type`='$details_type_new',
                        `jeha`='$jeha_profile',          
                        `edbara_num`=$edbara_num_max,
                        `edbara_date`='$today',   
                        `ketab_num`=$report_r_num_max,
                        `ketab_date`='$today',            
                        `added_by`='$added_by_old',
                        `add_date` = current_timestamp()
                        where `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'" ;              
                        if (mysqli_query($conn, $sql)) {
                        } else {
                            echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
                            exit;
                        }
                      }
                    }

                   $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                      `details_type`='$details_type_new',
                      `jeha`='$jeha_profile',               
                      `num`=$report_r_num_max,               
                      `date`='$today',
                      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='أضابير الموقوفين'  ORDER BY `num` ASC";
          
                      if (mysqli_query($conn, $update_tbl_uploads)) {
                      } else {
                          echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                          exit;
                      }
               }

                //////////// UPDATE studies tables ////////////
                 $table_name[]='studies_association';
                 $table_name[]='studies_association_attachment';
                 $table_name[]='studies_association_projects';
                 $table_name[]='studies_car_shops';
                 $table_name[]='studies_computers_phones_shops';
                 $table_name[]='studies_estate_offices';
                 $table_name[]='studies_exchange_shops';
                 $table_name[]='studies_factions';
                 $table_name[]='studies_factions_attachment_1';
                 $table_name[]='studies_factions_attachment_2';
                 $table_name[]='studies_factions_attachment_3';
                 $table_name[]='studies_factions_attachment_4';
                 $table_name[]='studies_factions_attachment_5';
                 $table_name[]='studies_factions_attachment_6';
                 $table_name[]='studies_factions_attachment_7';
                 $table_name[]='studies_factions_attachment_8';
                 $table_name[]='studies_factions_attachment_9';
                 $table_name[]='studies_factions_attachment_10';
                 $table_name[]='studies_fertilizers_and_pesticides';
                 $table_name[]='studies_fertilizers_and_pesticides_attachment';
                 $table_name[]='studies_forgery_and_stamps_offices';
                 $table_name[]='studies_it_shops';
                 $table_name[]='studies_kiosks';
                 $table_name[]='studies_organizations';
                 $table_name[]='studies_organization_attachment';
                 $table_name[]='studies_organization_projects';
                 $table_name[]='studies_smugglers';
                 $table_name[]='studies_training_centre';
                 $table_name[]='studies_training_centre_attachment';
                 $table_name[]='studies_training_centre_projects';
                 $table_name[]='studies_universities';
                 $table_name[]='studies_unofficial_civil_activities';
                 $table_name[]='studies_weapon_shops';
                 $table_name[]='studies_weapon_shops_attachment';      
                 $table_name[]='studies_weapon_traders';
               //// 828 ////
                 $table_name[]='studies_828_checkpoint_study';
                 $table_name[]='studies_828_goal';
                 $table_name[]='studies_828_military_site_study';
                 $table_name[]='studies_828_military_site_study_attachment';
                 $table_name[]='studies_828_personal_security_study';
                 $table_name[]='studies_828_security_center_study';
                 $table_name[]='studies_828_security_center_study_attachment';
                 $table_name[]='studies_828_town';
                 $table_name[]='studies_828_town_bakeries';
                 $table_name[]='studies_828_town_council';
                 $table_name[]='studies_828_town_demographic_information';
                 $table_name[]='studies_828_town_education';
                 $table_name[]='studies_828_town_schools';
                 $table_name[]='studies_828_town_faculties';
                 $table_name[]='studies_828_town_famous_families';
                 $table_name[]='studies_828_town_famous_mosques';
                 $table_name[]='studies_828_town_important_military_people';
                 $table_name[]='studies_828_town_influencers';
                 $table_name[]='studies_828_town_made_by';
                 $table_name[]='studies_828_town_military_branches';
                 $table_name[]='studies_828_town_military_places';
                 $table_name[]='studies_828_town_mukhtar_name';
                 $table_name[]='studies_828_town_new_military_places';
                 $table_name[]='studies_828_town_organisation';
                 $table_name[]='studies_828_town_public_utilities';
                 $table_name[]='studies_828_town_rich_people';
                 $table_name[]='studies_828_town_russian_cultral_centers';
                 $table_name[]='studies_828_town_shari3a_institutes';
                 $table_name[]='studies_828_town_shi3a_centers';
               //// 2022 ////
                 $table_name[]='studies_2022';
                 $table_name[]='studies_2022_attachments';
                                 
                 $number = count($table_name);
             
                 if ($number >= 1) {
                     for ($i=0; $i<$number; $i++) {
                       $sql_table_name= $table_name[$i];

                       $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
                        $result_check = mysqli_query($conn, $sql_check);
                        if (mysqli_num_rows($result_check) > 0){

                          $sql = "UPDATE `$sql_table_name` SET
                          `details_type`='$details_type_new',
                          `jeha`='$jeha_profile',          
                          `ketab_num`=$report_r_num_max,
                          `ketab_date`='$today',            
                          `added_by`='$added_by_old',
                          `add_date` = current_timestamp()
                          where `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'" ;              
                          if (mysqli_query($conn, $sql)) {
                          } else {
                              echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
                              exit;
                          }
                        }
                      }

                     $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                        `details_type`='$details_type_new',
                        `jeha`='$jeha_profile',               
                        `num`=$report_r_num_max,               
                        `date`='$today',
                        `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='المسح الأمني'  ORDER BY `num` ASC";
            
                        if (mysqli_query($conn, $update_tbl_uploads)) {
                        } else {
                            echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                 }

          
                $report_r_num_max++;
          }
          
        }
    
      
        /* $delete_dup_dewan="DELETE t1 FROM dewan t1 INNER JOIN dewan t2
        WHERE
        t1.dewan_num > t2.dewan_num AND
        t1.dewan_date = t2.dewan_date AND
        t1.brief = t2.brief AND
        t1.sendfrom = t2.sendfrom AND
        t1.sendto = t2.sendto AND
        t1.details_type = 'صادر' AND
        t1.jeha = '$jeha_profile'";
        if(mysqli_query($conn, $delete_dup_dewan)){ 
        }else{
          echo "Error delete_dup_dewan: " . "<br>" . mysqli_error($conn);
          exit;
        }  */


       
       

        $sql_id_max = "SELECT id  FROM details where id=(SELECT max(`id`) FROM details WHERE details_type='$details_type_new' AND jeha='$jeha_profile')";
        $result_id_max = mysqli_query($conn, $sql_id_max);
        $row_id_max = mysqli_fetch_assoc($result_id_max);
        $id_max=$row_id_max['id'];

       
        

        $sql77="INSERT INTO processing_to_processing (edbara_num_pre, edbara_date_pre, edbara_num_sader, edbara_date_sader, jeha, details_type)
        VALUES ($inserted_edbara_num , '$inserted_edbara_date', $edbara_num_max, '$today', '$inserted_jeha', '$details_type')";
        
        mysqli_query($conn, $sql77);

        header ("Location: j_view_server_qayd.php?resala_type=كتاب&jeha=$jeha_profile&details_type=قيد المعالجة");

        exit;
        
        
      }else{
        echo "Error details: " . "<br>" . mysqli_error($conn);
        exit;
      }
    
    }

}












if(!empty($_GET['id']) && !empty($_GET['type']) && $_GET['type']=='k_pub' ) {
 
$id=$_GET['id'];

  $sql2 = "UPDATE `reports_info` SET
      `details_type`='$details_type_new',
      `jeha`='$jeha_profile',           
      `dewan_num`=$r_num_max, 
      `dewan_date`='$today', 
      `ketab_num`=$r_num_max, 
      `ketab_date`='$today',               
      added_by='$added_by_old - $user',
      `add_date` = current_timestamp() WHERE  id=$id";

      

    if(mysqli_query($conn, $sql2)){

       // UPDATE tbl_uploads
       $sql_uploads = "SELECT id FROM `tbl_uploads` WHERE `jeha`='$inserted_jeha' AND `details_type`='$details_type' AND `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND upload_source='الكتب' ORDER BY num ASC";
       
       $sql_uploads_result = mysqli_query($conn, $sql_uploads);
   
       $uploads_r_num_max=$r_num_max;
       $uploads_dewan_num_max=$dewan_num_max;
   
       while($uploads_row = mysqli_fetch_assoc($sql_uploads_result)) {   
         $id_uploads = $uploads_row['id'];
   
         $sql2 = "UPDATE `tbl_uploads` SET
         `details_type`='$details_type_new',
         `jeha`='$jeha_profile',              
         `num`=$uploads_r_num_max,            
         `date`='$today',
         `add_date` = current_timestamp() where id=$id_uploads  ORDER BY num ASC";
   
           if(mysqli_query($conn, $sql2)){
             
           }else{
             echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
             exit;
           }
   
          
           $uploads_r_num_max++;
       }

   


      

      $sql3 = "UPDATE `study` SET
        details_type='$details_type_new',
        jeha='$jeha_profile',
        e_id=$e_id_new,      
        ketab_num=$r_num_max,
        ketab_date='$today',
        dewan_num=$r_num_max,
        dewan_date='$today',       
        added_by='$added_by_old',
        add_date = current_timestamp()
        where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND YEAR(ketab_date)=$inserted_year AND details_type='$details_type'" ;

      
      
    if(mysqli_query($conn, $sql3)){     
                
     
      }else{
        echo "Error study: " . "<br>" . mysqli_error($conn);
        exit;
      }

      $sql2 = "UPDATE `tbl_uploads` SET
      `details_type`='$details_type_new',
      `jeha`='$jeha_profile',               
      `num`=$r_num_max,               
      `date`='$today',
      `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='الدراسات الأمنية'  ORDER BY `num` ASC";

      if(mysqli_query($conn, $sql2)){                  
      }else{
        echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
        exit;
      }


         //////////// UPDATE mawkoof tables ////////////
         $table_name[]='mawkoof_adjudication_mahdar';
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

              $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
              $result_check = mysqli_query($conn, $sql_check);
              if (mysqli_num_rows($result_check) > 0){
                  
                $sql = "UPDATE `$sql_table_name` SET
                details_type='$details_type_new',
                jeha='$jeha_profile',         
                ketab_num=$r_num_max,
                ketab_date='$today',            
                added_by='$added_by_old',
                add_date = current_timestamp()
                where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND YEAR(ketab_date)=$inserted_year AND details_type='$details_type'" ;              
                if (mysqli_query($conn, $sql)) {
                } else {
                    echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
                    exit;
                }
              }
            }

             $update_tbl_uploads = "UPDATE `tbl_uploads` SET
                  `details_type`='$details_type_new',
                  `jeha`='$jeha_profile',               
                  `num`=$r_num_max,               
                  `date`='$today',
                  `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='أضابير الموقوفين'  ORDER BY `num` ASC";
      
                  if (mysqli_query($conn, $update_tbl_uploads)) {
                  } else {
                      echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                      exit;
                  }
         }

        //////////// UPDATE studies tables ////////////
          $table_name[]='studies_association';
          $table_name[]='studies_association_attachment';
          $table_name[]='studies_association_projects';
          $table_name[]='studies_car_shops';
          $table_name[]='studies_computers_phones_shops';
          $table_name[]='studies_estate_offices';
          $table_name[]='studies_exchange_shops';
          $table_name[]='studies_factions';
          $table_name[]='studies_factions_attachment_1';
          $table_name[]='studies_factions_attachment_2';
          $table_name[]='studies_factions_attachment_3';
          $table_name[]='studies_factions_attachment_4';
          $table_name[]='studies_factions_attachment_5';
          $table_name[]='studies_factions_attachment_6';
          $table_name[]='studies_factions_attachment_7';
          $table_name[]='studies_factions_attachment_8';
          $table_name[]='studies_factions_attachment_9';
          $table_name[]='studies_factions_attachment_10';
          $table_name[]='studies_fertilizers_and_pesticides';
          $table_name[]='studies_fertilizers_and_pesticides_attachment';
          $table_name[]='studies_forgery_and_stamps_offices';
          $table_name[]='studies_it_shops';
          $table_name[]='studies_kiosks';
          $table_name[]='studies_organizations';
          $table_name[]='studies_organization_attachment';
          $table_name[]='studies_organization_projects';
          $table_name[]='studies_smugglers';
          $table_name[]='studies_training_centre';
          $table_name[]='studies_training_centre_attachment';
          $table_name[]='studies_training_centre_projects';
          $table_name[]='studies_universities';
          $table_name[]='studies_unofficial_civil_activities';
          $table_name[]='studies_weapon_shops';
          $table_name[]='studies_weapon_shops_attachment';      
          $table_name[]='studies_weapon_traders';
      //// 828 ////
        $table_name[]='studies_828_checkpoint_study';
        $table_name[]='studies_828_goal';
        $table_name[]='studies_828_military_site_study';
        $table_name[]='studies_828_military_site_study_attachment';
        $table_name[]='studies_828_personal_security_study';
        $table_name[]='studies_828_security_center_study';
        $table_name[]='studies_828_security_center_study_attachment';
        $table_name[]='studies_828_town';
        $table_name[]='studies_828_town_bakeries';
        $table_name[]='studies_828_town_council';
        $table_name[]='studies_828_town_demographic_information';
        $table_name[]='studies_828_town_education';
        $table_name[]='studies_828_town_schools';
        $table_name[]='studies_828_town_faculties';
        $table_name[]='studies_828_town_famous_families';
        $table_name[]='studies_828_town_famous_mosques';
        $table_name[]='studies_828_town_important_military_people';
        $table_name[]='studies_828_town_influencers';
        $table_name[]='studies_828_town_made_by';
        $table_name[]='studies_828_town_military_branches';
        $table_name[]='studies_828_town_military_places';
        $table_name[]='studies_828_town_mukhtar_name';
        $table_name[]='studies_828_town_new_military_places';
        $table_name[]='studies_828_town_organisation';
        $table_name[]='studies_828_town_public_utilities';
        $table_name[]='studies_828_town_rich_people';
        $table_name[]='studies_828_town_russian_cultral_centers';
        $table_name[]='studies_828_town_shari3a_institutes';
        $table_name[]='studies_828_town_shi3a_centers';
      //// 2022 ////
        $table_name[]='studies_2022';
        $table_name[]='studies_2022_attachments';
                         
        $number = count($table_name);
    
        if ($number >= 1) {
            for ($i=0; $i<$number; $i++) {
              $sql_table_name= $table_name[$i];

              $sql_check="SELECT id from `$sql_table_name` WHERE `jeha` = '$inserted_jeha' AND `ketab_num` = $inserted_ketab_num AND `ketab_date`='$inserted_ketab_date' AND `details_type`='$details_type'";
              $result_check = mysqli_query($conn, $sql_check);
              if (mysqli_num_rows($result_check) > 0){
                
                $sql = "UPDATE `$sql_table_name` SET
                details_type='$details_type_new',
                jeha='$jeha_profile',          
                ketab_num=$r_num_max,
                ketab_date='$today',            
                added_by='$added_by_old',
                add_date = current_timestamp()
                where jeha = '$inserted_jeha' AND ketab_num = $inserted_ketab_num AND YEAR(ketab_date)=$inserted_year AND details_type='$details_type'" ;              
                if (mysqli_query($conn, $sql)) {
                } else {
                    echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
                    exit;
                }
              }
            }

            $sql = "UPDATE `tbl_uploads` SET
                `details_type`='$details_type_new',
                `jeha`='$jeha_profile',               
                `num`=$r_num_max,               
                `date`='$today',
                `add_date` = current_timestamp() where `num`=$inserted_ketab_num AND `date`='$inserted_ketab_date' AND `details_type`='$details_type' AND `jeha`='$inserted_jeha' AND upload_source='المسح الأمني'  ORDER BY `num` ASC";
                if(mysqli_query($conn, $sql)){                  
                }else{
                    echo "Error sql_uploadss: " . "<br>" . mysqli_error($conn);
                    exit;
                }
        }

        

      /* $sql_details = "INSERT INTO `details`(`e_id`,`jeha`,`details_type`) VALUES ($e_id_new,'$jeha_profile','$details_type_new')";
      mysqli_query($conn, $sql_details); */

  

     /*  $sql_id_new = "SELECT id FROM `reports_info` ORDER BY id ASC LIMIT 1";
      $result_id_new = mysqli_query($conn, $sql_id_new);
      $row_id_new = mysqli_fetch_assoc($result_id_new);
      $id_new = $row_id_new['id'];   */


      header ("Location: k_pub_view_server.php?resala_type=كتاب&jeha=$jeha_profile&details_type=قيد المعالجة&data_type=&type=all");
        exit;
    
    }else{
      echo "Error: " . "<br>" . mysqli_error($conn);
      exit;
    }
  }
  
 

?>
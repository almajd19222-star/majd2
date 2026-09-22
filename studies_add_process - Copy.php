<?php 
    include_once "inc/session.php"; 
    include_once "inc/config.php";
    include_once "inc/users_roles.php";
?>
<?php include_once "inc/errors.php"; ?>  
<?php 
$details_type=$_GET['details_type'];
$type_code = $_GET['type_code'];
$id=@$_GET['id'];
if (isset($_POST['submit_process'])) {
 

    include_once "studies_type_code.php";


    if (!empty($_POST['ketab_num'])) {
        $ketab_num = mysqli_real_escape_string($conn, $_POST['ketab_num']);
    } else {
        $ketab_num =mysqli_real_escape_string($conn, 0);
    }

    if (!empty($_POST['ketab_date'])) {
        $ketab_date = mysqli_real_escape_string($conn, $_POST['ketab_date']);
    } else {
        $ketab_date =mysqli_real_escape_string($conn, '0000-00-00');
    }

    $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
    $added_by_old = mysqli_real_escape_string($conn,  $_POST["added_by_old"]);

    if($_GET['edit'] == '0'){
        $table_name   = $_SESSION['table_name'];
        $general_code = $_SESSION['general_code'];
        $area_code    = $_SESSION['area_code'];
        $city_code    = $_SESSION['city_code'];
        $type_code    = $_SESSION['type_code'];
        $type_num     = $_SESSION['type_num'];
        
        
        $dupesql = "SELECT type_num FROM `$table_name` where details_type='$details_type' AND jeha='$jeha_profile' AND area_code = '$area_code' AND city_code = '$city_code' AND type_code = '$type_code' AND type_num = $type_num ";
        $duperaw = mysqli_query($conn,$dupesql);
        $row = mysqli_fetch_assoc($duperaw);
        if ($row > 0) {

            $sql_type_num_max = "SELECT type_num FROM `$table_name` where type_num=(SELECT max(type_num) FROM `$table_name` WHERE details_type='$details_type' AND jeha='$jeha_profile' AND area_code = '$area_code' AND city_code = '$city_code' AND type_code = '$type_code')";
            $result_type_num_max = mysqli_query($conn, $sql_type_num_max);
            $row_type_num_max = mysqli_fetch_assoc($result_type_num_max);
    
            if ($row_type_num_max > 0) {
                $type_num = $row_type_num_max['type_num'] + 1;
                $type_num = sprintf("%04d", $type_num);
              
            } else {
                $type_num = '0001';
            }

        }
        $general_code = $area_code.$city_code.$type_code.$type_num;
        
        $_SESSION['general_code'] = $general_code;
        
        $inserted_jeha=$jeha_profile;

        /////////////////// insert attachments ///////////////////

        if(!empty($_FILES["place_logo"]["name"])){           
            $fileUpload[] = 'place_logo';
        }else{
            $place_logo = '';
        }
        if(!empty($_FILES["studies_attach"]["name"])){            
            $fileUpload[] = 'studies_attach';
        }else{
            $studies_attach = '';
        }
          
         
          $num=$ketab_num;
          $date=$ketab_date;  
          
          $sql_s_id = "SELECT `id` FROM `$table_name` where `id`=(SELECT max(`id`) FROM `$table_name` )";
          $result_s_id = mysqli_query($conn, $sql_s_id);
          $row_s_id = mysqli_fetch_assoc($result_s_id);
  
          if($row_s_id > 0) { 
            $s_id = $row_s_id['id']+1;
          }else { $s_id = 1;}
          $id=$s_id;
          if(!empty($fileUpload)){
            include "inc/file_upload/file_upload_new.php";
          }

          /// start upload tinymce editor images to tbl_uplaods table
          include_once "inc/file_upload/editor_photos_upload.php";
          /// end uplaod to tbl_upload

    }else{
        
        $area_code = mysqli_real_escape_string($conn, $_POST['area_code']);
        $city_code = mysqli_real_escape_string($conn, $_POST['city_code']);
        $type_code = mysqli_real_escape_string($conn, $_POST['type_code']);
        $type_num = mysqli_real_escape_string($conn, $_POST['type_num']);
        $type_num = sprintf("%04d", $type_num);
        $general_code = $area_code.$city_code.$type_code.$type_num;

        $inserted_jeha = mysqli_real_escape_string($conn, $_POST['inserted_jeha']);
        $inserted_year = mysqli_real_escape_string($conn, $_POST['inserted_ketab_date']);
        $inserted_year = mysqli_real_escape_string($conn, date('Y',strtotime($inserted_year)));
        $e_year = mysqli_real_escape_string($conn, date('Y',strtotime($ketab_date)));
        $inserted_ketab_num = mysqli_real_escape_string($conn, $_POST['inserted_ketab_num']);
        $inserted_ketab_date = mysqli_real_escape_string($conn, $_POST['inserted_ketab_date']);


        /////////////////// insert attachments ///////////////////
        if(!empty($_FILES["place_logo"]["name"])){
            $fileUpload[] = 'place_logo';
        }
        if(!empty($_FILES["studies_attach"]["name"])){
            $fileUpload[] = 'studies_attach';
        } 
       
          
          $num=$ketab_num;
          $date=$ketab_date;
          if(!empty($fileUpload)){
            include "inc/file_upload/file_upload_new.php";
          }
          
          $inserted_num=$inserted_ketab_num;
          $inserted_date=$inserted_ketab_date;
          
          
          if(empty($_FILES["place_logo"]["name"])){
            $place_logo = mysqli_real_escape_string($conn, $_POST['inserted_place_logo']);
            include "inc/file_upload/file_upload_edit_new.php";
          }

          if(empty($_FILES["studies_attach"]["name"])){
            $studies_attach = mysqli_real_escape_string($conn, $_POST['inserted_attach']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
         

            /// start upload tinymce editor images to tbl_uplaods table
            include_once "inc/file_upload/editor_photos_upload.php";
            /// end uplaod to tbl_upload
    }


        include_once 'studies_coordinates.php';
   
    


   
    if ($type_code == 'SH') {
        $table_name= "studies_kiosks";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
              
       
       
       
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        
        
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

     
       

        $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);        
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $fixed_or_moveable = mysqli_real_escape_string($conn, $_POST['fixed_or_moveable']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);       
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $recruitment_possibility = mysqli_real_escape_string($conn, $_POST['recruitment_possibility']);
        $place_overlooking = mysqli_real_escape_string($conn, $_POST['place_overlooking']);
        $suspecious_activity = mysqli_real_escape_string($conn, $_POST['suspecious_activity']);

        $owner_suspecious_activity = mysqli_real_escape_string($conn, $_POST['owner_suspecious_activity']);
        $suitability = mysqli_real_escape_string($conn, $_POST['suitability']);
        $apparent_work = mysqli_real_escape_string($conn, $_POST['apparent_work']);

    
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `partners_name`, `socialmedia`, `place_address`, `longitude`,  `job_type`, `starting_work`, `license`, `cooperation`, `criminal_record`, `fixed_or_moveable`, `notable_customers`, `cameras`, `recruitment_possibility`, `place_overlooking`, `suspecious_activity`, `owner_suspecious_activity`, `suitability`, `apparent_work`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) 
            VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$partners_name', '$socialmedia',  '$place_address', '$longitude',  '$job_type', '$starting_work', '$license', '$cooperation', '$criminal_record', '$fixed_or_moveable', '$notable_customers', '$cameras', '$recruitment_possibility', '$place_overlooking', '$suspecious_activity', '$owner_suspecious_activity', '$suitability', '$apparent_work', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            partners_name='$partners_name',           
            socialmedia='$socialmedia', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            job_type='$job_type', 
            starting_work='$starting_work', 
            license='$license', 
            cooperation='$cooperation', 
            criminal_record='$criminal_record', 
            fixed_or_moveable='$fixed_or_moveable', 
            notable_customers='$notable_customers', 
            cameras='$cameras', 
            recruitment_possibility='$recruitment_possibility', 
            place_overlooking='$place_overlooking', 
            suspecious_activity='$suspecious_activity', 
            owner_suspecious_activity='$owner_suspecious_activity', 
            suitability='$suitability', 
            apparent_work='$apparent_work',  
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
        
    }

    if ($type_code == 'UV') {
        $table_name= "studies_universities";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);      
        $license = mysqli_real_escape_string($conn, $_POST['license']);

       
        
      
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

        $recognition = mysqli_real_escape_string($conn,  $_POST['recognition']);
        $teachers_names = mysqli_real_escape_string($conn,  $_POST['teachers_names']);
        $license_granter = mysqli_real_escape_string($conn,  $_POST['license_granter']);
        $teaching_manner = mysqli_real_escape_string($conn,  $_POST['teaching_manner']);
        $support_source = mysqli_real_escape_string($conn,  $_POST['support_source']);
        $inland_relations = mysqli_real_escape_string($conn,  $_POST['inland_relations']);
        $abroad_relations = mysqli_real_escape_string($conn,  $_POST['abroad_relations']);


        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }
      
        
           
       
        
        


        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `studies_attach`, `place_logo`, `place_address`, `longitude`,  `starting_work`, `recognition`, `teachers_names`, `socialmedia`, `license`, `license_granter`, `teaching_manner`, `support_source`, `inland_relations`, `abroad_relations`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$general_code', '$area_code', '$city_code', '$type_code', $type_num, '$name', '$fname', '$lname', '$personal_code', '$studies_attach', '$place_logo', '$place_address', '$longitude',  '$starting_work', '$recognition', '$teachers_names', '$socialmedia', '$license', '$license_granter', '$teaching_manner', '$support_source', '$inland_relations', '$abroad_relations', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date', 
            place_name='$place_name', 
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num=$type_num, 
            name='$name', 
            fname='$fname', 
            lname='$lname', 
            personal_code='$personal_code', 
            studies_attach='$studies_attach', 
            place_logo='$place_logo', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            starting_work='$starting_work', 
            recognition='$recognition', 
            teachers_names='$teachers_names', 
            socialmedia='$socialmedia', 
            license='$license', 
            license_granter='$license_granter', 
            teaching_manner='$teaching_manner', 
            support_source='$support_source', 
            inland_relations='$inland_relations', 
            abroad_relations='$abroad_relations', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type',            
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  

            if (!empty($_POST['faculty_name_new'])) {                    
                $number_new = count($_POST["faculty_name_new"]);
            } else {
                $number_new =mysqli_real_escape_string($conn, 0);
            }
         
            if ($number_new >= 1) {
                for ($i=0; $i<$number_new; $i++) {
                    $faculty_name = mysqli_real_escape_string($conn, $_POST['faculty_name_new'][$i]);
                    if (!empty($_POST['longitude_attach_new'][$i])) {
                        $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach_new'][$i]);

                        /// insert coordinates array to coordinates table
                        include "studies_coordinates_array_new.php";

                    } else {
                        $longitude =mysqli_real_escape_string($conn, 0);
                    }
                
                   
                    $address = mysqli_real_escape_string($conn, $_POST['address_new'][$i]);
                    $number_of_students = mysqli_real_escape_string($conn, $_POST['number_of_students_new'][$i]);
                    $yearly_installment = mysqli_real_escape_string($conn, $_POST['yearly_installment_new'][$i]);
                    
                    $sql_new="INSERT INTO `studies_universities_attachment`(`ketab_num`, `ketab_date`, `general_code`, `faculty_name`, `address`, `longitude`,  `number_of_students`, `yearly_installment`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$faculty_name', '$address', '$longitude',  '$number_of_students', '$yearly_installment', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                    if (mysqli_query($conn, $sql_new)){
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }
            
         
            
            if (!empty($_POST['faculty_name'])) {                    
                $number = count($_POST["faculty_name"]);
            } else {
                $number =mysqli_real_escape_string($conn, 0);
            }
            if ($number >= 1) {
                for ($i=0; $i<$number; $i++) {
                    $faculty_name = mysqli_real_escape_string($conn, $_POST['faculty_name'][$i]);
                    if (!empty($_POST['longitude_attach'][$i])) {
                        $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach'][$i]);

                        /// insert coordinates array to coordinates table
                        include "studies_coordinates_array_new.php";

                    } else {
                        $longitude =mysqli_real_escape_string($conn, 0);
                    }
                
                   
                    $address = mysqli_real_escape_string($conn, $_POST['address'][$i]);
                    $number_of_students = mysqli_real_escape_string($conn, $_POST['number_of_students'][$i]);
                    $yearly_installment = mysqli_real_escape_string($conn, $_POST['yearly_installment'][$i]);
                    $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                    $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

              


                    $sql= "UPDATE `studies_universities_attachment` SET 
                     general_code='$general_code',
                    ketab_num=$ketab_num, 
                    ketab_date='$ketab_date',
                    faculty_name='$faculty_name', 
                    address='$address', 
                    longitude='$longitude',                   
                    number_of_students='$number_of_students', 
                    yearly_installment='$yearly_installment',                    
                    added_by='$added_by_old - $added_by', 
                    add_date=current_timestamp()
                    WHERE id = $id_attach";
                
                    if (mysqli_query($conn, $sql)){
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }

            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'RF') {
        $table_name= "studies_factions";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);           
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $brief_description = mysqli_real_escape_string($conn, $_POST['brief_description']);
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
      
        if (!empty($_POST['faction_creation_date'])) {
            $faction_creation_date = mysqli_real_escape_string($conn, $_POST['faction_creation_date']);
        } else {
            $faction_creation_date =mysqli_real_escape_string($conn, '0000-00-00');
        }

               
       
        
        $faction_total_number = mysqli_real_escape_string($conn, $_POST['faction_total_number']);        
        $martyrs_number = mysqli_real_escape_string($conn, $_POST['martyrs_number']);
        $organizational_structure = mysqli_real_escape_string($conn, $_POST['organizational_structure']);
        $faction_important_stages = mysqli_real_escape_string($conn, $_POST['faction_important_stages']);   
        $faction_ideology = mysqli_real_escape_string($conn, $_POST['faction_ideology']);
        $fight_agianst_hts = mysqli_real_escape_string($conn, $_POST['fight_agianst_hts']); 
        $members_origin = mysqli_real_escape_string($conn, $_POST['members_origin']);
        $local_support_sources = mysqli_real_escape_string($conn, $_POST['local_support_sources']); 
        $outside_support_sources = mysqli_real_escape_string($conn, $_POST['outside_support_sources']);
        $faction_border_crossings = mysqli_real_escape_string($conn, $_POST['faction_border_crossings']); 
        $faction_security_service = mysqli_real_escape_string($conn, $_POST['faction_security_service']);
        $faction_media_office = mysqli_real_escape_string($conn, $_POST['faction_media_office']); 

        $faction_special_forces = mysqli_real_escape_string($conn, $_POST['faction_special_forces']);
        $notable_suporters = mysqli_real_escape_string($conn, $_POST['notable_suporters']); 
        $faction_areas_organizing = mysqli_real_escape_string($conn, $_POST['faction_areas_organizing']);
        $faction_popularity = mysqli_real_escape_string($conn, $_POST['faction_popularity']); 
        $faction_close_factions = mysqli_real_escape_string($conn, $_POST['faction_close_factions']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']); 

        $deads_with_factions = mysqli_real_escape_string($conn, $_POST['deads_with_factions']);
        $operations_with_coalition = mysqli_real_escape_string($conn, $_POST['operations_with_coalition']); 
        $outside_operations = mysqli_real_escape_string($conn, $_POST['outside_operations']);
        $faction_salaries = mysqli_real_escape_string($conn, $_POST['faction_salaries']); 
        $fighter_salary = mysqli_real_escape_string($conn, $_POST['fighter_salary']);
        $communication_towers = mysqli_real_escape_string($conn, $_POST['communication_towers']); 

        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
       


            
       

      
        
       

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `brief_description`, `socialmedia`, `faction_creation_date`, `faction_total_number`, `martyrs_number`, `organizational_structure`, `faction_important_stages`, `faction_ideology`, `fight_agianst_hts`, `members_origin`, `local_support_sources`, `outside_support_sources`, `faction_border_crossings`, `faction_security_service`, `faction_media_office`, `faction_special_forces`, `notable_suporters`, `faction_areas_organizing`, `faction_popularity`, `faction_close_factions`, `cooperation`, `deads_with_factions`, `operations_with_coalition`, `outside_operations`, `faction_salaries`, `fighter_salary`, `communication_towers`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code', '$area_code', '$city_code', '$type_code', $type_num, '$name', '$fname', '$lname', '$personal_code', '$brief_description', '$socialmedia', '$faction_creation_date', '$faction_total_number', '$martyrs_number', '$organizational_structure', '$faction_important_stages', '$faction_ideology', '$fight_agianst_hts', '$members_origin', '$local_support_sources', '$outside_support_sources', '$faction_border_crossings', '$faction_security_service', '$faction_media_office', '$faction_special_forces', '$notable_suporters', '$faction_areas_organizing', '$faction_popularity', '$faction_close_factions', '$cooperation', '$deads_with_factions', '$operations_with_coalition', '$outside_operations', '$faction_salaries', '$fighter_salary', '$communication_towers', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num = $ketab_num, 
            ketab_date = '$ketab_date', 
            place_name = '$place_name', 
            studies_attach = '$studies_attach', 
            general_code = '$general_code', 
            area_code = '$area_code', 
            city_code = '$city_code', 
            type_code = '$type_code', 
            type_num = $type_num, 
            name = '$name', 
            fname = '$fname',
            lname = '$lname',
            personal_code = '$personal_code', 
            brief_description = '$brief_description', 
            socialmedia= '$socialmedia', 
            faction_creation_date = '$faction_creation_date', 
            faction_total_number = '$faction_total_number', 
            martyrs_number = '$martyrs_number', 
            organizational_structure = '$organizational_structure', 
            faction_important_stages = '$faction_important_stages', 
            faction_ideology = '$faction_ideology', 
            fight_agianst_hts = '$fight_agianst_hts', 
            members_origin = '$members_origin', 
            local_support_sources = '$local_support_sources', 
            outside_support_sources = '$outside_support_sources', 
            faction_border_crossings = '$faction_border_crossings', 
            faction_security_service = '$faction_security_service', 
            faction_media_office = '$faction_media_office', 
            faction_special_forces = '$faction_special_forces', 
            notable_suporters = '$notable_suporters', 
            faction_areas_organizing = '$faction_areas_organizing', 
            faction_popularity = '$faction_popularity', 
            faction_close_factions = '$faction_close_factions', 
            cooperation = '$cooperation', 
            deads_with_factions = '$deads_with_factions', 
            operations_with_coalition = '$operations_with_coalition', 
            outside_operations = '$outside_operations', 
            faction_salaries = '$faction_salaries', 
            fighter_salary = '$fighter_salary', 
            communication_towers = '$communication_towers', 
            additional_information='$additional_information', 
            result = '$result', 
            suggestion = '$suggestion', 
            source = '$source',
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
            ///////////////// attachment_1 /////////////////
                if (!empty($_POST['name_and_nickname_new'])) {                    
                    $number = count($_POST["name_and_nickname_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $name_and_nickname = mysqli_real_escape_string($conn, $_POST['name_and_nickname_new'][$i]);
                        $intellectual_orientation = mysqli_real_escape_string($conn, $_POST['intellectual_orientation_new'][$i]);     
                        $attitude_towards_hts = mysqli_real_escape_string($conn, $_POST['attitude_towards_hts_new'][$i]);
                        $attitude_towards_gov = mysqli_real_escape_string($conn, $_POST['attitude_towards_gov_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_1`(`ketab_num`, `ketab_date`, `general_code`, `name_and_nickname`, `intellectual_orientation`, `attitude_towards_hts`, `attitude_towards_gov`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$name_and_nickname', '$intellectual_orientation', '$attitude_towards_hts', '$attitude_towards_gov', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 1: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['name_and_nickname'])) {                    
                    $number = count($_POST["name_and_nickname"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $name_and_nickname = mysqli_real_escape_string($conn, $_POST['name_and_nickname'][$i]);
                        $intellectual_orientation = mysqli_real_escape_string($conn, $_POST['intellectual_orientation'][$i]);     
                        $attitude_towards_hts = mysqli_real_escape_string($conn, $_POST['attitude_towards_hts'][$i]);
                        $attitude_towards_gov = mysqli_real_escape_string($conn, $_POST['attitude_towards_gov'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_1` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        name_and_nickname = '$name_and_nickname', 
                        intellectual_orientation = '$intellectual_orientation', 
                        attitude_towards_hts = '$attitude_towards_hts', 
                        attitude_towards_gov = '$attitude_towards_gov',                   
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 1 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            ///////////////// attachment_2 /////////////////
                if (!empty($_POST['name_and_nickname2_new'])) {                    
                    $number = count($_POST["name_and_nickname2_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $name_and_nickname = mysqli_real_escape_string($conn, $_POST['name_and_nickname2_new'][$i]);
                        $intellectual_orientation = mysqli_real_escape_string($conn, $_POST['intellectual_orientation2_new'][$i]); 
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job2_new'][$i]);     
                        $attitude_towards_hts = mysqli_real_escape_string($conn, $_POST['attitude_towards_hts2_new'][$i]);
                        $attitude_towards_gov = mysqli_real_escape_string($conn, $_POST['attitude_towards_gov2_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_2`(`ketab_num`, `ketab_date`, `general_code`, `name_and_nickname`, `current_job`, `intellectual_orientation`, `attitude_towards_hts`, `attitude_towards_gov`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$name_and_nickname', '$current_job', '$intellectual_orientation', '$attitude_towards_hts', '$attitude_towards_gov', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 2: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['name_and_nickname2'])) {                    
                    $number = count($_POST["name_and_nickname2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $name_and_nickname = mysqli_real_escape_string($conn, $_POST['name_and_nickname2'][$i]);
                        $intellectual_orientation = mysqli_real_escape_string($conn, $_POST['intellectual_orientation2'][$i]); 
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job2'][$i]);     
                        $attitude_towards_hts = mysqli_real_escape_string($conn, $_POST['attitude_towards_hts2'][$i]);
                        $attitude_towards_gov = mysqli_real_escape_string($conn, $_POST['attitude_towards_gov2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_2` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        name_and_nickname = '$name_and_nickname', 
                        intellectual_orientation = '$intellectual_orientation', 
                        attitude_towards_hts = '$attitude_towards_hts', 
                        attitude_towards_gov = '$attitude_towards_gov',                   
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 2 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            ///////////////// attachment_3 /////////////////
                if (!empty($_POST['name_and_nickname3_new'])) {                    
                    $number = count($_POST["name_and_nickname3_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $name_and_nickname = mysqli_real_escape_string($conn, $_POST['name_and_nickname3_new'][$i]);
                        $named = mysqli_real_escape_string($conn, $_POST['named_new'][$i]); 
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number_new'][$i]);     
                        $diffusion_area = mysqli_real_escape_string($conn, $_POST['diffusion_area_new'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_3`(`ketab_num`, `ketab_date`, `general_code`, `name_and_nickname`, `named`, `soldiers_number`, `diffusion_area`, `weapons`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$name_and_nickname', '$named', '$soldiers_number', '$diffusion_area', '$weapons', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 3: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['name_and_nickname3'])) {                    
                    $number = count($_POST["name_and_nickname3"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $name_and_nickname = mysqli_real_escape_string($conn, $_POST['name_and_nickname3'][$i]);
                        $named = mysqli_real_escape_string($conn, $_POST['named'][$i]); 
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number'][$i]);     
                        $diffusion_area = mysqli_real_escape_string($conn, $_POST['diffusion_area'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach3'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach3'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_3` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        name_and_nickname = '$name_and_nickname', 
                        named = '$named', 
                        soldiers_number = '$soldiers_number', 
                        diffusion_area = '$diffusion_area',  
                        weapons = '$weapons',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 3 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            
                ///////////////// attachment_4 /////////////////
                if (!empty($_POST['area_name4_new'])) {                    
                    $number = count($_POST["area_name4_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name4_new'][$i]);
                        $city_or_village_name = mysqli_real_escape_string($conn, $_POST['city_or_village_name_new'][$i]); 
                        $office_address = mysqli_real_escape_string($conn, $_POST['office_address_new'][$i]);     
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number4_new'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons4_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes4_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_4`(`ketab_num`, `ketab_date`, `general_code`, `area_name`, `city_or_village_name`, `office_address`, `soldiers_number`, `weapons`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$city_or_village_name', '$office_address', '$soldiers_number',  '$weapons', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 4: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name4'])) {                    
                    $number = count($_POST["area_name4"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name4'][$i]);
                        $city_or_village_name = mysqli_real_escape_string($conn, $_POST['city_or_village_name'][$i]); 
                        $office_address = mysqli_real_escape_string($conn, $_POST['office_address'][$i]);     
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number4'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons4'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes4'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach4'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach4'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_4` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        city_or_village_name = '$city_or_village_name', 
                        office_address = '$office_address', 
                        soldiers_number = '$soldiers_number',  
                        weapons = '$weapons', 
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 4 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            ///////////////// attachment_5 /////////////////
                if (!empty($_POST['area_name5_new'])) {                    
                    $number = count($_POST["area_name5_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name5_new'][$i]);
                        $point_name = mysqli_real_escape_string($conn, $_POST['point_name_new'][$i]); 
                        if (!empty($_POST['longitude_attach5_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach5_new'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number5_new'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons5_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes5_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_5`(`ketab_num`, `ketab_date`, `general_code`, `area_name`, `point_name`, `longitude`,  `soldiers_number`, `weapons`, `notes`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$point_name', '$longitude',  '$soldiers_number', '$weapons', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 5: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name5'])) {                    
                    $number = count($_POST["area_name5"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name5'][$i]);
                        $point_name = mysqli_real_escape_string($conn, $_POST['point_name'][$i]); 
                        if (!empty($_POST['longitude_attach5'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach5'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number5'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons5'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes5'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach5'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach5'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_5` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        point_name = '$point_name', 
                        longitude = '$longitude', 
                        soldiers_number = '$soldiers_number',  
                        weapons = '$weapons',  
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 5 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            ///////////////// attachment_6 /////////////////
                if (!empty($_POST['area_name6_new'])) {                    
                    $number = count($_POST["area_name6_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name6_new'][$i]);
                        $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name_new'][$i]); 
                        if (!empty($_POST['longitude_attach6_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach6_new'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        $warehouse_address = mysqli_real_escape_string($conn, $_POST['warehouse_address_new'][$i]);
                        $contents = mysqli_real_escape_string($conn, $_POST['contents_new'][$i]);                       
                        $notes = mysqli_real_escape_string($conn, $_POST['notes6_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_6`(`ketab_num`, `ketab_date`, `general_code`, `area_name`, `admin_name`, `warehouse_address`, `longitude`,  `contents`, `notes`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$admin_name', '$warehouse_address', '$longitude',  '$contents', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 6: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name6'])) {                    
                    $number = count($_POST["area_name6"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name6'][$i]);
                        $admin_name = mysqli_real_escape_string($conn, $_POST['admin_name'][$i]); 
                        if (!empty($_POST['longitude_attach6'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach6'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        $warehouse_address = mysqli_real_escape_string($conn, $_POST['warehouse_address'][$i]);
                        $contents = mysqli_real_escape_string($conn, $_POST['contents'][$i]);                       
                        $notes = mysqli_real_escape_string($conn, $_POST['notes6'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach6'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach6'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_6` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        admin_name = '$admin_name', 
                        longitude = '$longitude', 
                        warehouse_address = '$warehouse_address',  
                        contents = '$contents',  
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 6 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            ///////////////// attachment_7 /////////////////
                if (!empty($_POST['area_name7_new'])) {                    
                    $number = count($_POST["area_name7_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name7_new'][$i]);
                        $camp_leader = mysqli_real_escape_string($conn, $_POST['camp_leader_new'][$i]); 
                       
                        if (!empty($_POST['longitude_attach7_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach7_new'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        $camp_address = mysqli_real_escape_string($conn, $_POST['camp_address_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes7_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_7` (`ketab_num`, `ketab_date`, `general_code`, `area_name`, `camp_leader`, `camp_address`, `longitude`,  `notes`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$camp_leader', '$camp_address', '$longitude',  '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 7: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name7'])) {                    
                    $number = count($_POST["area_name7"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name7'][$i]);
                        $camp_leader = mysqli_real_escape_string($conn, $_POST['camp_leader'][$i]); 

                        if (!empty($_POST['longitude_attach7'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach7'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }   
                        $camp_address = mysqli_real_escape_string($conn, $_POST['camp_address'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes7'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach7'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach7'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_7` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        camp_leader = '$camp_leader', 
                        longitude = '$longitude', 
                        camp_address = '$camp_address',
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 7 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            
            ///////////////// attachment_8 /////////////////
                if (!empty($_POST['area_name8_new'])) {                    
                    $number = count($_POST["area_name8_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name8_new'][$i]);
                        $roadblock_leader = mysqli_real_escape_string($conn, $_POST['roadblock_leader_new'][$i]);
                        $roadblock_address = mysqli_real_escape_string($conn, $_POST['roadblock_address_new'][$i]);

                        if (!empty($_POST['longitude_attach8_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach8_new'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }   

                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number8_new'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons8_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes8_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_8` (`ketab_num`, `ketab_date`, `general_code`, `area_name`, `roadblock_leader`, `roadblock_address`, `longitude`,  `soldiers_number`, `weapons`, `notes`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$roadblock_leader', '$roadblock_address', '$longitude',  '$soldiers_number', '$weapons', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 8: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name8'])) {                    
                    $number = count($_POST["area_name8"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name8'][$i]);
                        $roadblock_leader = mysqli_real_escape_string($conn, $_POST['roadblock_leader'][$i]);
                        $roadblock_address = mysqli_real_escape_string($conn, $_POST['roadblock_address'][$i]); 

                        if (!empty($_POST['longitude_attach8'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach8'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }   
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number8'][$i]);
                        $weapons = mysqli_real_escape_string($conn, $_POST['weapons8'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes8'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach8'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach8'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_8` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        roadblock_leader = '$roadblock_leader', 
                        roadblock_address = '$roadblock_address', 
                        longitude = '$longitude', 
                        soldiers_number = '$soldiers_number',
                        weapons = '$weapons',
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 8 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            ///////////////// attachment_9 /////////////////
                if (!empty($_POST['area_name9_new'])) {                    
                    $number = count($_POST["area_name9_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name9_new'][$i]);
                        $prison_leader = mysqli_real_escape_string($conn, $_POST['prison_leader_new'][$i]);
                        $prison_address = mysqli_real_escape_string($conn, $_POST['prison_address_new'][$i]); 

                        if (!empty($_POST['longitude_attach9_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach9_new'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }
                        $notes = mysqli_real_escape_string($conn, $_POST['notes9_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_9` (`ketab_num`, `ketab_date`, `general_code`, `area_name`, `prison_leader`, `prison_address`, `longitude`,  `notes`,   `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$prison_leader', '$prison_address', '$longitude',  '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 9: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name9'])) {                    
                    $number = count($_POST["area_name9"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name9'][$i]);
                        $prison_leader = mysqli_real_escape_string($conn, $_POST['prison_leader'][$i]);
                        $prison_address = mysqli_real_escape_string($conn, $_POST['prison_address'][$i]); 

                        if (!empty($_POST['longitude_attach9'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach9'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }
                        $notes = mysqli_real_escape_string($conn, $_POST['notes9'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach9'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach9'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_9` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        prison_leader = '$prison_leader', 
                        prison_address = '$prison_address', 
                        longitude = '$longitude', 
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 9 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            ///////////////// attachment_10 /////////////////
                if (!empty($_POST['area_name10_new'])) {                    
                    $number = count($_POST["area_name10_new"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name10_new'][$i]);
                        $guard_leader = mysqli_real_escape_string($conn, $_POST['guard_leader_new'][$i]);
                        $group_name = mysqli_real_escape_string($conn, $_POST['group_name_new'][$i]); 
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number10_new'][$i]); 

                        if (!empty($_POST['longitude_attach10_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach10_new'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }
                        $notes = mysqli_real_escape_string($conn, $_POST['notes10_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_factions_attachment_10` (`ketab_num`, `ketab_date`, `general_code`, `area_name`, `guard_leader`, `group_name`, `soldiers_number`, `longitude`,  `notes`,   `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$area_name', '$guard_leader', '$group_name', '$soldiers_number', '$longitude',  '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error attach 10: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['area_name10'])) {                    
                    $number = count($_POST["area_name10"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $area_name = mysqli_real_escape_string($conn, $_POST['area_name10'][$i]);
                        $guard_leader = mysqli_real_escape_string($conn, $_POST['guard_leader'][$i]);
                        $group_name = mysqli_real_escape_string($conn, $_POST['group_name'][$i]); 
                        $soldiers_number = mysqli_real_escape_string($conn, $_POST['soldiers_number10'][$i]); 

                        if (!empty($_POST['longitude_attach10'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach10'][$i]);
    
                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
    
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }
                        $notes = mysqli_real_escape_string($conn, $_POST['notes10_new'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach10'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach10'][$i]);

                        $sql= "UPDATE `studies_factions_attachment_10` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        area_name = '$area_name', 
                        guard_leader = '$guard_leader', 
                        group_name = '$group_name',
                        soldiers_number = '$soldiers_number', 
                        longitude = '$longitude', 
                        notes = '$notes',                 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error attach 10 update: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'CE') {
        $table_name= "studies_unofficial_civil_activities";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
              
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        
        
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $cars_description = mysqli_real_escape_string($conn, $_POST['cars_description']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

     
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }

        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type']);
        $notable_organizers = mysqli_real_escape_string($conn, $_POST['notable_organizers']);
        
        $decision_making_mechanism = mysqli_real_escape_string($conn, $_POST['decision_making_mechanism']);
        $meetings_places = mysqli_real_escape_string($conn, $_POST['meetings_places']);       
        $activity_members_number = mysqli_real_escape_string($conn, $_POST['activity_members_number']);
        $society_interaction_level = mysqli_real_escape_string($conn, $_POST['society_interaction_level']);
        $activity_place = mysqli_real_escape_string($conn, $_POST['activity_place']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);

        $internal_links = mysqli_real_escape_string($conn, $_POST['internal_links']);
        $outside_links = mysqli_real_escape_string($conn, $_POST['outside_links']);
        $outside_activities = mysqli_real_escape_string($conn, $_POST['outside_activities']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['internal_links']);
        $internal_links = mysqli_real_escape_string($conn, $_POST['internal_links']);
     
       
        
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);


            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `starting_work`, `activity_type`, `notable_organizers`, `decision_making_mechanism`, `meetings_places`, `activity_members_number`, `society_interaction_level`, `activity_place`, `support_source`, `internal_links`, `outside_links`, `outside_activities`, `cooperation`, `license`, `socialmedia`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) 
            VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$starting_work', '$activity_type', '$notable_organizers', '$decision_making_mechanism', '$meetings_places', '$activity_members_number', '$society_interaction_level', '$activity_place', '$support_source', '$internal_links', '$outside_links', '$outside_activities', '$cooperation', '$license', '$socialmedia', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code', 
            starting_work='$starting_work', 
            activity_type='$activity_type', 
            notable_organizers='$notable_organizers', 
            decision_making_mechanism='$decision_making_mechanism', 
            meetings_places='$meetings_places', 
            activity_members_number='$activity_members_number', 
            society_interaction_level='$society_interaction_level', 
            activity_place='$activity_place', 
            support_source='$support_source', 
            internal_links='$internal_links', 
            outside_links='$outside_links', 
            outside_activities='$outside_activities', 
            cooperation='$cooperation', 
            license='$license', 
            socialmedia='$socialmedia',
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'ES') {
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);

       

        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $security_record = mysqli_real_escape_string($conn, $_POST['security_record']);
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $importation_place = mysqli_real_escape_string($conn, $_POST['importation_place']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);
        $suspect_dealing = mysqli_real_escape_string($conn, $_POST['suspect_dealing']);
        $circuit_sell = mysqli_real_escape_string($conn, $_POST['circuit_sell']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $most_sell_devices = mysqli_real_escape_string($conn, $_POST['most_sell_devices']);
        $work_rank = mysqli_real_escape_string($conn, $_POST['work_rank']);
        $suspicious_relations = mysqli_real_escape_string($conn, $_POST['suspicious_relations']);
        $develop_ability = mysqli_real_escape_string($conn, $_POST['develop_ability']);
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql= "INSERT INTO `studies_it_shops`(`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `place_address`, `longitude`,  `partners_name`, `socialmedia`, `capital`, `cameras`, `job_type`, `goods_type`, `license`, `security_record`, `other_branches`, `importation_place`, `cooperation`, `suspect_dealing`, `circuit_sell`, `notable_customers`, `most_sell_devices`, `work_rank`, `suspicious_relations`, `develop_ability`, `criminal_record`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date','$place_name','$studies_attach','$general_code','$area_code','$city_code','$type_code',$type_num,'$name','$fname','$lname', '$personal_code', '$place_address', '$longitude',  '$partners_name', '$socialmedia', '$capital','$cameras','$job_type','$goods_type','$license','$security_record', '$other_branches','$importation_place','$cooperation', '$suspect_dealing', '$circuit_sell', '$notable_customers','$most_sell_devices', '$work_rank', '$suspicious_relations', '$develop_ability', '$criminal_record', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);     

            $sql= "UPDATE `studies_it_shops` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            place_address='$place_address', 
            longitude='$longitude', 
            
            partners_name='$partners_name', 
            socialmedia='$socialmedia', 
            capital='$capital',
            cameras='$cameras',
            job_type='$job_type',
            goods_type='$goods_type',
            license='$license',
            security_record='$security_record', 
            other_branches='$other_branches',
            importation_place='$importation_place',
            cooperation='$cooperation', 
            suspect_dealing='$suspect_dealing', 
            circuit_sell='$circuit_sell', 
            notable_customers='$notable_customers',
            most_sell_devices='$most_sell_devices', 
            work_rank='$work_rank', 
            suspicious_relations='$suspicious_relations', 
            develop_ability='$develop_ability', 
            criminal_record='$criminal_record', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
        echo "Error: " . "<br>" . mysqli_error($conn);
        exit;
        }

    }
    
    if ($type_code == 'RE') {
        $table_name= "studies_estate_offices";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
              
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        
        
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

     
       

        $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $rent_percent_monthly = mysqli_real_escape_string($conn, $_POST['rent_percent_monthly']);       
        $compliance = mysqli_real_escape_string($conn, $_POST['compliance']);
        $security_record = mysqli_real_escape_string($conn, $_POST['security_record']);
        $realty_type = mysqli_real_escape_string($conn, $_POST['realty_type']);
        $regime_area_partners = mysqli_real_escape_string($conn, $_POST['regime_area_partners']);
           
       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `partners_name`, `socialmedia`, `place_address`, `longitude`,  `job_type`, `activity_area`, `license`, `starting_work`, `other_branches`, `rent_percent_monthly`, `criminal_record`, `cooperation`, `security_record`, `compliance`, `realty_type`, `regime_area_partners`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$partners_name', '$socialmedia',  '$place_address', '$longitude',  '$job_type', '$activity_area',  '$license', '$starting_work', '$other_branches', '$rent_percent_monthly', '$criminal_record', '$cooperation', '$security_record', '$compliance', '$realty_type', '$regime_area_partners', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            partners_name='$partners_name',           
            socialmedia='$socialmedia', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            job_type='$job_type', 
            activity_area='$activity_area',  
            license='$license', 
            starting_work='$starting_work', 
            other_branches='$other_branches', 
            rent_percent_monthly='$rent_percent_monthly', 
            criminal_record='$criminal_record', 
            cooperation='$cooperation',     
            security_record='$security_record',        
            compliance='$compliance', 
            realty_type='$realty_type', 
            regime_area_partners='$regime_area_partners',           
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'TR') {
        $table_name= "studies_smugglers";
            
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
       
          
       
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

     
       

        $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $former_cooperation = mysqli_real_escape_string($conn, $_POST['former_cooperation']);
          
        $compliance = mysqli_real_escape_string($conn, $_POST['compliance']);
        $weapon_owning = mysqli_real_escape_string($conn, $_POST['weapon_owning']);
        $smuggle_from = mysqli_real_escape_string($conn, $_POST['smuggle_from']);
       

        $guarantor = mysqli_real_escape_string($conn, $_POST['guarantor']);
        $smuggle_to = mysqli_real_escape_string($conn, $_POST['smuggle_to']);       
        $i_dont_know = mysqli_real_escape_string($conn, $_POST['i_dont_know']);
        $coordination = mysqli_real_escape_string($conn, $_POST['coordination']);
        $prohibited_coordination = mysqli_real_escape_string($conn, $_POST['prohibited_coordination']);
        $wanted_smuggling = mysqli_real_escape_string($conn, $_POST['wanted_smuggling']);
        $women_smuggling = mysqli_real_escape_string($conn, $_POST['women_smuggling']);
        $isis_smuggling = mysqli_real_escape_string($conn, $_POST['isis_smuggling']);
        $monitors_name = mysqli_real_escape_string($conn, $_POST['monitors_name']);       
        $commitment = mysqli_real_escape_string($conn, $_POST['commitment']);
        $abroad_partners = mysqli_real_escape_string($conn, $_POST['abroad_partners']);
        $former_smuggler = mysqli_real_escape_string($conn, $_POST['former_smuggler']);
        $i_dont_know2 = mysqli_real_escape_string($conn, $_POST['i_dont_know2']);
           
        $prices = mysqli_real_escape_string($conn, $_POST['prices']);
        $weapon_smuggling = mysqli_real_escape_string($conn, $_POST['monitors_name']);       
        $drivers_name = mysqli_real_escape_string($conn, $_POST['drivers_name']);
        $properties = mysqli_real_escape_string($conn, $_POST['properties']);
     
       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `studies_attach`, `personal_code`, `partners_name`, `former_cooperation`, `cooperation`, `compliance`, `starting_work`, `job_type`, `weapon_owning`, `activity_area`, `smuggle_from`, `guarantor`, `smuggle_to`, `i_dont_know`, `women_smuggling`, `coordination`, `prohibited_coordination`, `wanted_smuggling`, `isis_smuggling`, `monitors_name`, `commitment`, `abroad_partners`, `former_smuggler`, `i_dont_know2`, `prices`, `weapon_smuggling`, `drivers_name`, `properties`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname',  '$studies_attach', '$personal_code', '$partners_name', '$former_cooperation',  '$cooperation', '$compliance', '$starting_work', '$job_type', '$weapon_owning', '$activity_area', '$smuggle_from',            
            '$guarantor', '$smuggle_to', '$i_dont_know', '$women_smuggling', '$coordination', '$prohibited_coordination', '$wanted_smuggling', '$isis_smuggling', '$monitors_name', '$commitment', '$abroad_partners', '$former_smuggler', '$i_dont_know2', '$prices', '$weapon_smuggling', '$drivers_name', '$properties','$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            partners_name='$partners_name',           
            former_cooperation='$former_cooperation',  
            cooperation='$cooperation', 
            compliance='$compliance', 
            starting_work='$starting_work', 
            job_type='$job_type', 
            weapon_owning='$weapon_owning', 
            activity_area='$activity_area', 
            smuggle_from='$smuggle_from',            
            guarantor='$guarantor', 
            smuggle_to='$smuggle_to', 
            i_dont_know='$i_dont_know', 
            women_smuggling='$women_smuggling', 
            coordination='$coordination', 
            prohibited_coordination='$prohibited_coordination', 
            wanted_smuggling='$wanted_smuggling', 
            isis_smuggling='$isis_smuggling', 
            monitors_name='$monitors_name', 
            commitment='$commitment', 
            abroad_partners='$abroad_partners', 
            former_smuggler='$former_smuggler', 
            i_dont_know2='$i_dont_know2', 
            prices='$prices', 
            weapon_smuggling='$weapon_smuggling', 
            drivers_name='$drivers_name', 
            properties='$properties',
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'UD') {
        $table_name= "studies_weapon_traders";
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);        
        
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

        $weapon_type = mysqli_real_escape_string($conn, $_POST['weapon_type']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }

        $ammo_type = mysqli_real_escape_string($conn, $_POST['ammo_type']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);       
        $dependency = mysqli_real_escape_string($conn, $_POST['dependency']);
        $transact = mysqli_real_escape_string($conn, $_POST['transact']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $stolens_dealing = mysqli_real_escape_string($conn, $_POST['stolens_dealing']);
        $suspicion_tell = mysqli_real_escape_string($conn, $_POST['suspicion_tell']);
        $recipients = mysqli_real_escape_string($conn, $_POST['recipients']);
       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `studies_attach`, `personal_code`, `starting_work`, `partners_name`, `weapon_type`, `ammo_type`, `license`, `notable_customers`, `dependency`, `activity_area`, `transact`, `recipients`, `cooperation`, `capital`, `stolens_dealing`, `suspicion_tell`, `criminal_record`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$studies_attach', '$personal_code', '$starting_work', '$partners_name', '$weapon_type',  '$ammo_type', '$license', '$notable_customers', '$dependency', '$activity_area',  '$transact', '$recipients', '$cooperation', '$capital', '$stolens_dealing', '$suspicion_tell', '$criminal_record', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            starting_work='$starting_work',
            partners_name='$partners_name',           
            weapon_type='$weapon_type',  
            ammo_type='$ammo_type', 
            license='$license', 
            notable_customers='$notable_customers', 
            dependency='$dependency', 
            activity_area='$activity_area',  
            transact='$transact', 
            recipients='$recipients', 
            cooperation='$cooperation', 
            capital='$capital', 
            stolens_dealing='$stolens_dealing', 
            suspicion_tell='$suspicion_tell', 
            criminal_record='$criminal_record',                  
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'AS') {
        $table_name= "studies_association";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);   
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);   
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
       
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);        
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
      
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
       


        $employers_name = mysqli_real_escape_string($conn, $_POST['employers_name']);        
        $recruitment_possibility = mysqli_real_escape_string($conn, $_POST['recruitment_possibility']);
        $place_effectiveness = mysqli_real_escape_string($conn, $_POST['place_effectiveness']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $suspicious_activities = mysqli_real_escape_string($conn, $_POST['suspicious_activities']);
        $inland_offices = mysqli_real_escape_string($conn, $_POST['inland_offices']);
        $abroad_offices = mysqli_real_escape_string($conn, $_POST['abroad_offices']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $targeted_society_type = mysqli_real_escape_string($conn, $_POST['targeted_society_type']);
        $suporters = mysqli_real_escape_string($conn, $_POST['suporters']);
        $place_owners = mysqli_real_escape_string($conn, $_POST['place_owners']);
        $public_intention = mysqli_real_escape_string($conn, $_POST['public_intention']);
        $hidden_intention = mysqli_real_escape_string($conn, $_POST['hidden_intention']);
        $place_valuation = mysqli_real_escape_string($conn, $_POST['place_valuation']);
       
        if (!empty($_POST['inland'])) {
            $inland = mysqli_real_escape_string($conn, $_POST['inland']);
        } else {
            $inland =mysqli_real_escape_string($conn, '0000-00-00');
        }
        if (!empty($_POST['abroad'])) {
            $abroad = mysqli_real_escape_string($conn, $_POST['abroad']);
        } else {
            $abroad =mysqli_real_escape_string($conn, '0000-00-00');
        }

        if (!empty($_POST['establishment_date'])) {
            $establishment_date = mysqli_real_escape_string($conn, $_POST['establishment_date']);
        } else {
            $establishment_date =mysqli_real_escape_string($conn, '0000-00-00');
        }
       

      
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `place_logo`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `employers_name`, `place_address`, `longitude`,  `socialmedia`, `establishment_date`, `inland`, `abroad`, `license`, `cooperation`, `recruitment_possibility`, `cameras`, `place_effectiveness`, `partners_name`, `suspicious_activities`, `inland_offices`, `abroad_offices`, `activity_area`, `targeted_society_type`, `suporters`, `place_owners`, `public_intention`, `hidden_intention`, `place_valuation`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$place_logo', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$employers_name', '$place_address', '$longitude',  '$socialmedia', '$establishment_date', '$inland', '$abroad', '$license', '$cooperation', '$recruitment_possibility', '$cameras', '$place_effectiveness', '$partners_name', '$suspicious_activities', '$inland_offices', '$abroad_offices', '$activity_area', '$targeted_society_type', '$suporters', '$place_owners', '$public_intention', '$hidden_intention', '$place_valuation', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            place_logo='$place_logo',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code', 
            employers_name='$employers_name',
            place_address='$place_address', 
            longitude='$longitude', 
                      
            socialmedia='$socialmedia', 
            establishment_date='$establishment_date',
            inland='$inland', 
            abroad='$abroad', 
            license='$license', 
            cooperation='$cooperation', 
            recruitment_possibility='$recruitment_possibility', 
            cameras='$cameras', 
            place_effectiveness='$place_effectiveness', 
            partners_name='$partners_name', 
            suspicious_activities='$suspicious_activities',
            inland_offices='$inland_offices',
            abroad_offices='$abroad_offices',
            activity_area='$activity_area',
            targeted_society_type='$targeted_society_type', 
            suporters='$suporters', 
            place_owners='$place_owners', 
            public_intention='$public_intention', 
            hidden_intention='$hidden_intention', 
            place_valuation='$place_valuation', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['criteria_new'])) {                    
                    $number_new = count($_POST["criteria_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $criteria = mysqli_real_escape_string($conn, $_POST['criteria_new'][$i]);
                        $project_brief = mysqli_real_escape_string($conn, $_POST['project_brief_new'][$i]);
                      
                        
                        $sql_new="INSERT INTO `studies_association_projects`(`ketab_num`, `ketab_date`, `general_code`, `criteria`, `project_brief`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$criteria', '$project_brief', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['case_type_new'])) {                    
                    $number_new = count($_POST["case_type_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type_new'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview_new'][$i]);
                      
                        
                        $sql_new="INSERT INTO `studies_association_attachment`(`ketab_num`, `ketab_date`, `general_code`, `case_type`, `case_overview`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$case_type', '$case_overview', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                
              
             
                
                if (!empty($_POST['criteria'])) {                    
                    $number = count($_POST["criteria"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $criteria = mysqli_real_escape_string($conn, $_POST['criteria'][$i]);
                        $project_brief = mysqli_real_escape_string($conn, $_POST['project_brief'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_projects'][$i]);
                        $id_projects = mysqli_real_escape_string($conn, $_POST['id_projects'][$i]);

                        $sql= "UPDATE `studies_association_projects` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        criteria='$criteria',
                        project_brief='$project_brief',                       
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_projects";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['case_type'])) {                    
                    $number = count($_POST["case_type"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                        $sql= "UPDATE `studies_association_attachment` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        case_type='$case_type',
                        case_overview='$case_overview',                       
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }
    
    if ($type_code == 'MC') {
        $table_name= "studies_computers_phones_shops";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
       
        
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
       
        $license = mysqli_real_escape_string($conn, $_POST['license']);
     
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
       
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);
        $phones_unlock = mysqli_real_escape_string($conn, $_POST['phones_unlock']);
        $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
        $files_recovery = mysqli_real_escape_string($conn, $_POST['files_recovery']);
        $previous_cooperation = mysqli_real_escape_string($conn, $_POST['previous_cooperation']);
        $work_with_others = mysqli_real_escape_string($conn, $_POST['work_with_others']);
        $import_manner = mysqli_real_escape_string($conn, $_POST['import_manner']);
        $hacking_experiance = mysqli_real_escape_string($conn, $_POST['hacking_experiance']);
    

        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
     
       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql= "INSERT INTO `$table_name`(`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `place_address`, `longitude`,  `partners_name`, `socialmedia`, `capital`, `cameras`, `license`, `cooperation`, `other_branches`, `criminal_record`, `phones_unlock`, `job_type`, `files_recovery`, `previous_cooperation`, `work_with_others`, `import_manner`, `hacking_experiance`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date','$place_name','$studies_attach','$general_code','$area_code','$city_code','$type_code',$type_num,'$name','$fname','$lname','$personal_code', '$place_address', '$longitude',  '$partners_name', '$socialmedia', '$capital','$cameras', '$license', '$cooperation', '$other_branches', '$criminal_record', '$phones_unlock', '$job_type','$files_recovery','$previous_cooperation','$work_with_others', '$import_manner', '$hacking_experiance', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);     

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            partners_name='$partners_name', 
            socialmedia='$socialmedia', 
            capital='$capital',
            cameras='$cameras', 
            license='$license', 
            cooperation='$cooperation', 
            other_branches='$other_branches', 
            criminal_record='$criminal_record', 
            phones_unlock='$phones_unlock', 
            job_type='$job_type',
            files_recovery='$files_recovery',
            previous_cooperation='$previous_cooperation',
            work_with_others='$work_with_others', 
            import_manner='$import_manner', 
            hacking_experiance='$hacking_experiance',
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source',
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
        echo "Error: " . "<br>" . mysqli_error($conn);
        exit;
        }
    }

    if ($type_code == 'FP') {
        $table_name= "studies_fertilizers_and_pesticides";
       
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);


        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $import_from = mysqli_real_escape_string($conn, $_POST['import_from']);   
        $most_sell = mysqli_real_escape_string($conn, $_POST['most_sell']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $dealing_with_others = mysqli_real_escape_string($conn, $_POST['dealing_with_others']);
        $buying_from_traders = mysqli_real_escape_string($conn, $_POST['buying_from_traders']);
       


            
       

      
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `place_address`, `longitude`,  `partners_name`, `socialmedia`, `capital`, `cameras`, `goods_type`, `import_from`, `most_sell`, `notable_customers`, `license`, `criminal_record`, `dealing_with_others`, `cooperation`, `buying_from_traders`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code',  '$place_address', '$longitude',  '$partners_name', '$socialmedia', '$capital', '$cameras', '$goods_type', '$import_from', '$most_sell', '$notable_customers', '$license', '$criminal_record', '$dealing_with_others', '$cooperation', '$buying_from_traders', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            place_address='$place_address', 
            longitude='$longitude', 
            
            partners_name='$partners_name', 
            socialmedia='$socialmedia', 
            capital='$capital', 
            cameras='$cameras', 
            goods_type='$goods_type', 
            import_from='$import_from', 
            most_sell='$most_sell', 
            notable_customers='$notable_customers', 
            license='$license', 
            criminal_record='$criminal_record', 
            dealing_with_others='$dealing_with_others', 
            cooperation='$cooperation', 
            buying_from_traders='$buying_from_traders',
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
           

                if (!empty($_POST['place_address_attach_new'])) {                    
                    $number_new = count($_POST["place_address_attach_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
             
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $place_address = mysqli_real_escape_string($conn, $_POST['place_address_attach_new'][$i]);
                     
                               
                        if (!empty($_POST['longitude_attach_new'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach_new'][$i]);

                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";
                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        
                    
                        
        
                        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type_attach_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes_attach_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_fertilizers_and_pesticides_attachment`(`ketab_num`, `ketab_date`, `general_code`, `place_address`, `longitude`,  `goods_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$place_address', '$longitude',  '$goods_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                
             
                
                if (!empty($_POST['place_address_attach'])) {                    
                    $number = count($_POST["place_address_attach"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $place_address = mysqli_real_escape_string($conn, $_POST['place_address_attach'][$i]);
                        if (!empty($_POST['longitude_attach'][$i])) {
                            $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach'][$i]);

                            /// insert coordinates array to coordinates table
                            include "studies_coordinates_array_new.php";

                        } else {
                            $longitude =mysqli_real_escape_string($conn, 0);
                        }

                        
                        
                        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type_attach'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes_attach'][$i]);
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                        $sql= "UPDATE `studies_fertilizers_and_pesticides_attachment` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        place_address='$place_address',
                        longitude='$longitude',
                        goods_type='$goods_type',
                        notes='$notes',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }

    }

    if ($type_code == 'WA') {
        $table_name= "studies_weapon_shops";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        
        
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

     
       

        $weapons_type = mysqli_real_escape_string($conn, $_POST['weapons_type']);
        $explosive_materials = mysqli_real_escape_string($conn, $_POST['explosive_materials']);
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $explosive_materials_type = mysqli_real_escape_string($conn, $_POST['explosive_materials_type']);
        $circuit_dealing = mysqli_real_escape_string($conn, $_POST['circuit_dealing']);       
        $proofs_from_customers = mysqli_real_escape_string($conn, $_POST['proofs_from_customers']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        
           
       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `place_address`, `longitude`,  `partners_name`, `socialmedia`, `weapons_type`, `capital`, `cameras`, `starting_work`, `explosive_materials`, `explosive_materials_type`, `circuit_dealing`, `license`, `cooperation`, `criminal_record`, `proofs_from_customers`, `notable_customers`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$place_address', '$longitude',  '$partners_name', '$socialmedia', '$weapons_type', '$capital', '$cameras', '$starting_work', '$explosive_materials', '$explosive_materials_type', '$circuit_dealing', '$license', '$cooperation', '$criminal_record', '$proofs_from_customers', '$notable_customers', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
        }

     
        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            partners_name='$partners_name',           
            socialmedia='$socialmedia', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            weapons_type='$weapons_type', 
            capital='$capital',  
            license='$license', 
            starting_work='$starting_work', 
            explosive_materials='$explosive_materials', 
            explosive_materials_type='$explosive_materials_type', 
            criminal_record='$criminal_record', 
            cooperation='$cooperation',     
            circuit_dealing='$circuit_dealing',        
            notable_customers='$notable_customers', 
            proofs_from_customers='$proofs_from_customers',           
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  

            if (!empty($_POST['place_address_attach_new'])) {                    
                $number_new = count($_POST["place_address_attach_new"]);
            } else {
                $number_new =mysqli_real_escape_string($conn, 0);
            }
         
            if ($number_new >= 1) {
                for ($i=0; $i<$number_new; $i++) {
                    $place_address = mysqli_real_escape_string($conn, $_POST['place_address_attach_new'][$i]);
                    if (!empty($_POST['longitude_attach_new'][$i])) {
                        $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach_new'][$i]);

                        /// insert coordinates array to coordinates table
                        include "studies_coordinates_array_new.php";

                    } else {
                        $longitude =mysqli_real_escape_string($conn, 0);
                    }
                
                    
                    $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type_attach_new'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes_attach_new'][$i]);
                    
                    $sql_new="INSERT INTO `studies_weapon_shops_attachment`(`ketab_num`, `ketab_date`, `general_code`, `place_address`, `longitude`,  `goods_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$place_address', '$longitude',  '$goods_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                    if (mysqli_query($conn, $sql_new)){
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }
            
         
            
            if (!empty($_POST['place_address_attach'])) {                    
                $number = count($_POST["place_address_attach"]);
            } else {
                $number =mysqli_real_escape_string($conn, 0);
            }
            if ($number >= 1) {
                for ($i=0; $i<$number; $i++) {
                    $place_address = mysqli_real_escape_string($conn, $_POST['place_address_attach'][$i]);
                    if (!empty($_POST['longitude_attach'][$i])) {
                        $longitude = mysqli_real_escape_string($conn, $_POST['longitude_attach'][$i]);

                        /// insert coordinates array to coordinates table
                        include "studies_coordinates_array_new.php";

                    } else {
                        $longitude =mysqli_real_escape_string($conn, 0);
                    }
                
                  
                    $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type_attach'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes_attach'][$i]);
                    $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                    $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                    $sql= "UPDATE `studies_weapon_shops_attachment` SET 
                     general_code='$general_code',
                    ketab_num=$ketab_num, 
                    ketab_date='$ketab_date',
                    place_address='$place_address',
                    longitude='$longitude',
                    goods_type='$goods_type',
                    notes='$notes',
                    added_by='$added_by_old - $added_by', 
                    add_date=current_timestamp()
                    WHERE id = $id_attach";
                
                    if (mysqli_query($conn, $sql)){
                    }else{
                        echo "Error: " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }

            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code =='CH') {
       
        $table_name= "studies_exchange_shops";
       
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);


        $trader_card = mysqli_real_escape_string($conn, $_POST['trader_card']);
      
        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $other_jobs = mysqli_real_escape_string($conn, $_POST['other_jobs']);
        $carrying_weapon = mysqli_real_escape_string($conn, $_POST['carrying_weapon']);
        $currencies = mysqli_real_escape_string($conn, $_POST['currencies']);
        $btc_dealing = mysqli_real_escape_string($conn, $_POST['btc_dealing']);
        $monuments_dealing = mysqli_real_escape_string($conn, $_POST['monuments_dealing']);
        $prices_screen = mysqli_real_escape_string($conn, $_POST['prices_screen']);
        $partners_abroad = mysqli_real_escape_string($conn, $_POST['partners_abroad']);
        $librated_area_partners = mysqli_real_escape_string($conn, $_POST['librated_area_partners']);
        $regime_area_partners = mysqli_real_escape_string($conn, $_POST['regime_area_partners']);
        $customers_type = mysqli_real_escape_string($conn, $_POST['customers_type']);    
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);      
       

      
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `studies_exchange_shops` (`ketab_num`, `ketab_date`, `place_name`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `studies_attach`, `place_address`, `longitude`,  `partners_name`, `socialmedia`, `trader_card`, `starting_work`, `other_jobs`, `carrying_weapon`, `capital`, `currencies`, `btc_dealing`, `cameras`, `monuments_dealing`, `other_branches`, `prices_screen`, `license`, `partners_abroad`, `librated_area_partners`, `regime_area_partners`, `customers_type`, `cooperation`, `criminal_record`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`)
            VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$studies_attach', '$place_address', '$longitude',  '$partners_name', '$socialmedia', '$trader_card', '$starting_work', '$other_jobs', '$carrying_weapon', '$capital', '$currencies', '$btc_dealing', '$cameras', '$monuments_dealing', '$other_branches', '$prices_screen', '$license', '$partners_abroad', '$librated_area_partners', '$regime_area_partners', '$customers_type', '$cooperation', '$criminal_record', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            

            $sql= "UPDATE `studies_exchange_shops` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            place_address='$place_address', 
            longitude='$longitude', 
            
            partners_name='$partners_name', 
            socialmedia='$socialmedia', 
            trader_card='$trader_card', 
            starting_work='$starting_work', 
            other_jobs='$other_jobs', 
            carrying_weapon='$carrying_weapon', 
            capital='$capital',
            currencies='$currencies',
            btc_dealing='$btc_dealing',
            cameras='$cameras',
            monuments_dealing='$monuments_dealing',
            other_branches='$other_branches',
            prices_screen='$prices_screen',
            license='$license',
            partners_abroad='$partners_abroad',
            librated_area_partners='$librated_area_partners', 
            regime_area_partners='$regime_area_partners',
            customers_type='$customers_type', 
            cooperation='$cooperation',
            criminal_record='$criminal_record', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }


    }

    if ($type_code =='TC') {
        $table_name= "studies_training_centre";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);   
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);   
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
       
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);        
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
       
      
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
       


        $employers_name = mysqli_real_escape_string($conn, $_POST['employers_name']);        
        $recruitment_possibility = mysqli_real_escape_string($conn, $_POST['recruitment_possibility']);
        $place_effectiveness = mysqli_real_escape_string($conn, $_POST['place_effectiveness']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $suspicious_activities = mysqli_real_escape_string($conn, $_POST['suspicious_activities']);
        $inland_offices = mysqli_real_escape_string($conn, $_POST['inland_offices']);
        $abroad_offices = mysqli_real_escape_string($conn, $_POST['abroad_offices']);
        $targeted_areas = mysqli_real_escape_string($conn, $_POST['targeted_areas']);
        $targeted_society_type = mysqli_real_escape_string($conn, $_POST['targeted_society_type']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);
        $place_ownership = mysqli_real_escape_string($conn, $_POST['place_ownership']);
        $public_intention = mysqli_real_escape_string($conn, $_POST['public_intention']);
        $hidden_intention = mysqli_real_escape_string($conn, $_POST['hidden_intention']);
        $place_valuation = mysqli_real_escape_string($conn, $_POST['place_valuation']);
       
     

        if (!empty($_POST['starting_work'])) {
            $starting_work = mysqli_real_escape_string($conn, $_POST['starting_work']);
        } else {
            $starting_work =mysqli_real_escape_string($conn, '0000-00-00');
        }

       

            
       

      
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `employers_name`, `place_address`, `longitude`,  `socialmedia`, `starting_work`, `license`, `recruitment_possibility`, `cameras`, `place_effectiveness`, `notable_customers`, `suspicious_activities`, `inland_offices`, `abroad_offices`, `targeted_areas`, `targeted_society_type`, `support_source`, `place_ownership`, `public_intention`, `hidden_intention`, `place_valuation`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code', '$area_code', '$city_code', '$type_code', $type_num, '$name', '$fname', '$lname', '$personal_code', '$employers_name', '$place_address', '$longitude',  '$socialmedia', '$starting_work', '$license', '$recruitment_possibility', '$cameras', '$place_effectiveness', '$notable_customers', '$suspicious_activities', '$inland_offices', '$abroad_offices', '$targeted_areas', '$targeted_society_type', '$support_source', '$place_ownership', '$public_intention', '$hidden_intention', '$place_valuation', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code', 
            employers_name='$employers_name',
            place_address='$place_address', 
            longitude='$longitude', 
                      
            socialmedia='$socialmedia', 
            starting_work='$starting_work', 
            license='$license', 
            recruitment_possibility='$recruitment_possibility', 
            cameras='$cameras', 
            place_effectiveness='$place_effectiveness', 
            notable_customers='$notable_customers', 
            suspicious_activities='$suspicious_activities', 
            inland_offices='$inland_offices', 
            abroad_offices='$abroad_offices', 
            targeted_areas='$targeted_areas', 
            targeted_society_type='$targeted_society_type', 
            support_source='$support_source', 
            place_ownership='$place_ownership', 
            public_intention='$public_intention', 
            hidden_intention='$hidden_intention', 
            place_valuation='$place_valuation',            
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['criteria_new'])) {                    
                    $number_new = count($_POST["criteria_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $criteria = mysqli_real_escape_string($conn, $_POST['criteria_new'][$i]);
                        $project_brief = mysqli_real_escape_string($conn, $_POST['project_brief_new'][$i]);
                      
                        
                        $sql_new="INSERT INTO `studies_training_centre_projects`(`ketab_num`, `ketab_date`, `general_code`, `criteria`, `project_brief`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$criteria', '$project_brief', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['case_type_new'])) {                    
                    $number_new = count($_POST["case_type_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type_new'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview_new'][$i]);
                      
                        
                        $sql_new="INSERT INTO `studies_training_centre_attachment` (`ketab_num`, `ketab_date`, `general_code`, `case_type`, `case_overview`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$case_type', '$case_overview', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                
              
             
                
                if (!empty($_POST['criteria'])) {                    
                    $number = count($_POST["criteria"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $criteria = mysqli_real_escape_string($conn, $_POST['criteria'][$i]);
                        $project_brief = mysqli_real_escape_string($conn, $_POST['project_brief'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_projects'][$i]);
                        $id_projects = mysqli_real_escape_string($conn, $_POST['id_projects'][$i]);

                        $sql= "UPDATE `studies_training_centre_projects` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        criteria='$criteria',
                        project_brief='$project_brief',                       
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_projects";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['case_type'])) {                    
                    $number = count($_POST["case_type"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                        $sql= "UPDATE `studies_training_centre_attachment` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        case_type='$case_type',
                        case_overview='$case_overview',                       
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            
                

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'FS') {
        $table_name= "studies_forgery_and_stamps_offices";
       
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

       

        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $fake_doc_type = mysqli_real_escape_string($conn, $_POST['fake_doc_type']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $matching_level = mysqli_real_escape_string($conn, $_POST['matching_level']);
        $manufacturing_manner = mysqli_real_escape_string($conn, $_POST['manufacturing_manner']);
           
       
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `partners_name`, `socialmedia`, `place_address`, `longitude`,  `license`, `cameras`, `cooperation`, `criminal_record`, `notable_customers`, `fake_doc_type`, `work_type`, `matching_level`, `manufacturing_manner`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name','$studies_attach', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$partners_name', '$socialmedia',  '$place_address', '$longitude',  '$license', '$cameras',  '$cooperation', '$criminal_record', '$notable_customers', '$fake_doc_type', '$work_type', '$matching_level', '$manufacturing_manner',  '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code',
            partners_name='$partners_name',           
            socialmedia='$socialmedia', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            license='$license',
            cameras='$cameras',          
            cooperation='$cooperation',
            criminal_record='$criminal_record', 
            notable_customers='$notable_customers', 
            fake_doc_type='$fake_doc_type', 
            work_type='$work_type', 
            matching_level='$matching_level', 
            manufacturing_manner='$manufacturing_manner',
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'CO') {
        $table_name= "studies_car_shops";
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);      
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
              
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        
        
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
        $cars_description = mysqli_real_escape_string($conn, $_POST['cars_description']);
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);

     
       

        $cars_type = mysqli_real_escape_string($conn, $_POST['cars_type']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        
        $cars_amount = mysqli_real_escape_string($conn, $_POST['cars_amount']);
        $import_source = mysqli_real_escape_string($conn, $_POST['import_source']);       
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $stolens_dealing = mysqli_real_escape_string($conn, $_POST['stolens_dealing']);
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $contract_installing = mysqli_real_escape_string($conn, $_POST['contract_installing']);

        $renting_cars = mysqli_real_escape_string($conn, $_POST['renting_cars']);
     

        
        
       

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `partners_name`, `socialmedia`, `place_address`, `longitude`,  `cars_description`, `cars_type`, `capital`, `cars_amount`, `license`, `import_source`, `stolens_dealing`, `cameras`, `other_branches`, `cooperation`, `contract_installing`, `renting_cars`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) 
            VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code', '$area_code', '$city_code', '$type_code', $type_num, '$name', '$fname', '$lname', '$personal_code', '$partners_name', '$socialmedia', '$place_address', '$longitude',  '$cars_description', '$cars_type', '$capital', '$cars_amount', '$license', '$import_source', '$stolens_dealing', '$cameras', '$other_branches', '$cooperation', '$contract_installing', '$renting_cars', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code', 
            partners_name='$partners_name', 
            socialmedia='$socialmedia', 
            place_address='$place_address', 
            longitude='$longitude', 
            
            cars_description='$cars_description', 
            cars_type='$cars_type', 
            capital='$capital', 
            cars_amount='$cars_amount', 
            license='$license', 
            import_source='$import_source', 
            stolens_dealing='$stolens_dealing', 
            cameras='$cameras', 
            other_branches='$other_branches', 
            cooperation='$cooperation', 
            contract_installing='$contract_installing', 
            renting_cars='$renting_cars',
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'OR') {
        $table_name= "studies_organizations";
       
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);   
        $place_address = mysqli_real_escape_string($conn, $_POST['place_address']);   
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
       
               
        
        
        $socialmedia = mysqli_real_escape_string($conn, $_POST['socialmedia']);        
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $license = mysqli_real_escape_string($conn, $_POST['license']);
        $cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);   
      
        $additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
       


        $employers_name = mysqli_real_escape_string($conn, $_POST['employers_name']);        
        $recruitment_possibility = mysqli_real_escape_string($conn, $_POST['recruitment_possibility']);
        $org_effectiveness = mysqli_real_escape_string($conn, $_POST['org_effectiveness']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $suspicious_activities = mysqli_real_escape_string($conn, $_POST['suspicious_activities']);
        $inland_offices = mysqli_real_escape_string($conn, $_POST['inland_offices']);
        $abroad_offices = mysqli_real_escape_string($conn, $_POST['abroad_offices']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $targeted_society_type = mysqli_real_escape_string($conn, $_POST['targeted_society_type']);
        $suporters = mysqli_real_escape_string($conn, $_POST['suporters']);
        $org_owners = mysqli_real_escape_string($conn, $_POST['org_owners']);
        $public_intention = mysqli_real_escape_string($conn, $_POST['public_intention']);
        $hidden_intention = mysqli_real_escape_string($conn, $_POST['hidden_intention']);
        $org_valuation = mysqli_real_escape_string($conn, $_POST['org_valuation']);
       
        if (!empty($_POST['inland'])) {
            $inland = mysqli_real_escape_string($conn, $_POST['inland']);
        } else {
            $inland =mysqli_real_escape_string($conn, '0000-00-00');
        }
        if (!empty($_POST['abroad'])) {
            $abroad = mysqli_real_escape_string($conn, $_POST['abroad']);
        } else {
            $abroad =mysqli_real_escape_string($conn, '0000-00-00');
        }

            
       

      
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `place_name`, `studies_attach`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `fname`, `lname`, `personal_code`, `employers_name`, `place_address`, `longitude`,  `socialmedia`, `inland`, `abroad`, `license`, `cooperation`, `recruitment_possibility`, `cameras`, `org_effectiveness`, `partners_name`, `suspicious_activities`, `inland_offices`, `abroad_offices`, `activity_area`, `targeted_society_type`, `suporters`, `org_owners`, `public_intention`, `hidden_intention`, `org_valuation`, `additional_information`, `result`, `suggestion`, `source`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$place_name', '$studies_attach', '$general_code','$area_code', '$city_code', '$type_code', '$type_num', '$name', '$fname', '$lname', '$personal_code', '$employers_name', '$place_address', '$longitude',  '$socialmedia', '$inland', '$abroad', '$license', '$cooperation', '$recruitment_possibility', '$cameras', '$org_effectiveness', '$partners_name', '$suspicious_activities', '$inland_offices', '$abroad_offices', '$activity_area', '$targeted_society_type', '$suporters', '$org_owners', '$public_intention', '$hidden_intention', '$org_valuation', '$additional_information', '$result', '$suggestion', '$source', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',
            place_name='$place_name',
            studies_attach='$studies_attach',
            general_code='$general_code',
            area_code='$area_code',
            city_code='$city_code',
            type_code='$type_code',
            type_num=$type_num,
            name='$name',
            fname='$fname',
            lname='$lname',
            personal_code='$personal_code', 
            employers_name='$employers_name',
            place_address='$place_address', 
            longitude='$longitude', 
                      
            socialmedia='$socialmedia', 
            inland='$inland', 
            abroad='$abroad', 
            license='$license', 
            cooperation='$cooperation', 
            recruitment_possibility='$recruitment_possibility', 
            cameras='$cameras', 
            org_effectiveness='$org_effectiveness', 
            partners_name='$partners_name', 
            suspicious_activities='$suspicious_activities',
            inland_offices='$inland_offices',
            abroad_offices='$abroad_offices',
            activity_area='$activity_area',
            targeted_society_type='$targeted_society_type', 
            suporters='$suporters', 
            org_owners='$org_owners', 
            public_intention='$public_intention', 
            hidden_intention='$hidden_intention', 
            org_valuation='$org_valuation', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['criteria_new'])) {                    
                    $number_new = count($_POST["criteria_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $criteria = mysqli_real_escape_string($conn, $_POST['criteria_new'][$i]);
                        $project_brief = mysqli_real_escape_string($conn, $_POST['project_brief_new'][$i]);
                      
                        
                        $sql_new="INSERT INTO `studies_organization_projects`(`ketab_num`, `ketab_date`, `general_code`, `criteria`, `project_brief`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$criteria', '$project_brief', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['case_type_new'])) {                    
                    $number_new = count($_POST["case_type_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type_new'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview_new'][$i]);
                      
                        
                        $sql_new="INSERT INTO `studies_organization_attachment`(`ketab_num`, `ketab_date`, `general_code`, `case_type`, `case_overview`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$case_type', '$case_overview', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                
              
             
                
                if (!empty($_POST['criteria'])) {                    
                    $number = count($_POST["criteria"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $criteria = mysqli_real_escape_string($conn, $_POST['criteria'][$i]);
                        $project_brief = mysqli_real_escape_string($conn, $_POST['project_brief'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_projects'][$i]);
                        $id_projects = mysqli_real_escape_string($conn, $_POST['id_projects'][$i]);

                        $sql= "UPDATE `studies_organization_projects` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        criteria='$criteria',
                        project_brief='$project_brief',                       
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_projects";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

                if (!empty($_POST['case_type'])) {                    
                    $number = count($_POST["case_type"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                        $sql= "UPDATE `studies_organization_attachment` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        case_type='$case_type',
                        case_overview='$case_overview',                       
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }
 

    if ($_GET['edit']=='0') {
        $type_num++;
        $type_num = sprintf("%04d", $type_num);
        $general_code = $area_code.$city_code.$type_code.$type_num;       
        $_SESSION['general_code'] = $general_code;

        header("Location: studies.php?details_type=".$details_type."&type_code=".$type_code."&edit=0&add=true");
    }
    if ($_GET['edit']=='1') {
        header("Location: studies.php?details_type=".$details_type."&type_code=".$type_code."&id=".$id."&edit=1&edit_process=true");
    } 
    
    
}
?>

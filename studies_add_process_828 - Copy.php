<?php 

include_once "inc/session.php"; 

include_once "inc/config.php";
include_once "inc/users_roles.php";
?>
<?php include_once "inc/errors.php"; ?>  
<?php 
$details_type=$_GET['details_type'];
$type_code = $_GET['type_code'];
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
       
      /*   $sql_type_num_max = "SELECT type_num FROM `$table_name` where type_num=(SELECT max(type_num) FROM `$table_name` WHERE  jeha='$jeha_profile' AND type_code = '$type_code')";
        $result_type_num_max = mysqli_query($conn, $sql_type_num_max);
        $row_type_num_max = mysqli_fetch_assoc($result_type_num_max);

        if ($row_type_num_max > 0) {
            $type_num = $row_type_num_max['type_num'] + 1;
            $type_num = sprintf("%05d", $type_num);
            
        } else {
            $type_num = '00001';
        } */


        //$general_code = $_SESSION['general_code'];
        if(!empty($_SESSION['area_code'])){
            $area_code    = $_SESSION['area_code'];
        }else{
            $area_code    = '';
        }

        if(!empty($_SESSION['city_code'])){
            $city_code    = $_SESSION['city_code'];
        }else{
            $city_code    = '';
        }

        if(!empty($_SESSION['type_code'])){
            $type_code    = $_SESSION['type_code'];
        }else{
            $type_code    = '';
        }

        

        if(!empty($_SESSION['type_num'])){
            $type_num    = $_SESSION['type_num'];
        }else{
            $type_num    = 0;
        }
       
        
        
        /* $city_code    = $_SESSION['city_code'];
        $type_code    = $_SESSION['type_code'];
        $type_num     = $_SESSION['type_num']; */
        //$general_code = $type_code.'_'.$type_num;
        
        $dupesql = "SELECT type_num FROM `$table_name` where details_type='$details_type' AND jeha='$jeha_profile' AND area_code = '$area_code' AND city_code = '$city_code' AND type_code = '$type_code' AND type_num = $type_num ";
        $duperaw = mysqli_query($conn,$dupesql);
        $row = mysqli_fetch_assoc($duperaw);
        if ($row > 0) {

            
            $sql_type_num_max = "SELECT type_num FROM `$table_name` where type_num=(SELECT max(type_num) FROM `$table_name` WHERE  details_type='$details_type' AND jeha='$jeha_profile' AND area_code = '$area_code' AND city_code = '$city_code' AND type_code = '$type_code')";
            $result_type_num_max = mysqli_query($conn, $sql_type_num_max);
            $row_type_num_max = mysqli_fetch_assoc($result_type_num_max);

            if ($row_type_num_max > 0) {
                $type_num = $row_type_num_max['type_num'] + 1;
                $type_num = sprintf("%05d", $type_num);
                
            } else {
                $type_num = '00001';
            }

        }
        if($type_code=='SPE'){
            $general_code = $type_code.'_'.$type_num;
        }else{
            $general_code = $area_code.'_'.$city_code.'_'.$type_code.'_'.$type_num;
        }
        

        
        
        $_SESSION['general_code'] = $general_code;
        
        
        $inserted_jeha=$jeha_profile;


         /////////////////// insert attachments ///////////////////
        if(!empty($_FILES["personal_image"]["name"])){
            $fileUpload[] = 'personal_image';
        }else{
            $personal_image = '';
        }
        if(!empty($_FILES["studies_attach"]["name"])){
            $fileUpload[] = 'studies_attach';
        }else{
            $studies_attach = '';
        } 
        if(!empty($_FILES["site_map"]["name"])){
            $fileUpload[] = 'site_map';
        }else{
            $site_map = '';
        }
        if(!empty($_FILES["site_photos"]["name"])){
            $fileUpload[] = 'site_photos';
        }else{
            $site_photos = '';
        }
        if(!empty($_FILES["center_map"]["name"])){
            $fileUpload[] = 'center_map';
        }else{
            $center_map = '';
        }
        if(!empty($_FILES["center_photos"]["name"])){
            $fileUpload[] = 'center_photos';
        }else{
            $center_photos = '';
        }
        if(!empty($_FILES["checkpoint_photos"]["name"])){
            $fileUpload[] = 'checkpoint_photos';
        }else{
            $checkpoint_photos = '';
        }
        if(!empty($_FILES["checkpoint_map"]["name"])){
            $fileUpload[] = 'checkpoint_map';
        }else{
            $checkpoint_map = '';
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
        $id=$_GET['id'];
        $area_code = mysqli_real_escape_string($conn, $_POST['area_code']);
        $city_code = mysqli_real_escape_string($conn, $_POST['city_code']);
        $type_code = mysqli_real_escape_string($conn, $_GET['type_code']);
        $type_num = mysqli_real_escape_string($conn, $_POST['type_num']);
        $type_num = sprintf("%05d", $type_num);
        $general_code = $area_code.'_'.$city_code.'_'.$type_code.'_'.$type_num;

        $inserted_jeha = mysqli_real_escape_string($conn,  $_POST["inserted_jeha"]);

        $inserted_jeha = mysqli_real_escape_string($conn, $_POST['inserted_jeha']);
        $inserted_year = mysqli_real_escape_string($conn, $_POST['inserted_ketab_date']);
        $inserted_year = mysqli_real_escape_string($conn, date('Y',strtotime($inserted_year)));
        $inserted_ketab_num = mysqli_real_escape_string($conn, $_POST['inserted_ketab_num']);
        $inserted_ketab_date = mysqli_real_escape_string($conn, $_POST['inserted_ketab_date']);
        
        $e_year = mysqli_real_escape_string($conn, date('Y',strtotime($ketab_date)));
        
        if(!empty($_FILES["personal_image"]["name"])){
            $fileUpload[] = 'personal_image';
        }
        if(!empty($_FILES["studies_attach"]["name"])){
            $fileUpload[] = 'studies_attach';
        } 
        if(!empty($_FILES["site_map"]["name"])){
            $fileUpload[] = 'site_map';
        }
        if(!empty($_FILES["site_photos"]["name"])){
            $fileUpload[] = 'site_photos';
        }
        if(!empty($_FILES["center_map"]["name"])){
            $fileUpload[] = 'center_map';
        }
        if(!empty($_FILES["center_photos"]["name"])){
            $fileUpload[] = 'center_photos';
        }
        if(!empty($_FILES["checkpoint_photos"]["name"])){
            $fileUpload[] = 'checkpoint_photos';
        }
        if(!empty($_FILES["checkpoint_map"]["name"])){
            $fileUpload[] = 'checkpoint_map';
        }
          
          $num=$ketab_num;
          $date=$ketab_date;
          if(!empty($fileUpload)){
            include "inc/file_upload/file_upload_new.php";
          }
          
          $inserted_num=$inserted_ketab_num;
          $inserted_date=$inserted_ketab_date;
          
          
          if(empty($_FILES["personal_image"]["name"])){
            @$personal_image = mysqli_real_escape_string($conn, $_POST['inserted_personal_image']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["studies_attach"]["name"])){
            @$studies_attach = mysqli_real_escape_string($conn, $_POST['inserted_attach']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["site_map"]["name"])){
            @$site_map = mysqli_real_escape_string($conn, $_POST['inserted_site_map']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["site_photos"]["name"])){
            @$site_photos = mysqli_real_escape_string($conn, $_POST['inserted_site_photos']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["center_map"]["name"])){
            @$center_map = mysqli_real_escape_string($conn, $_POST['inserted_center_map']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["center_photos"]["name"])){
            @$center_photos = mysqli_real_escape_string($conn, $_POST['inserted_center_photos']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["checkpoint_photos"]["name"])){
            @$checkpoint_photos = mysqli_real_escape_string($conn, $_POST['inserted_checkpoint_photos']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          if(empty($_FILES["checkpoint_map"]["name"])){
            @$checkpoint_map = mysqli_real_escape_string($conn, $_POST['inserted_checkpoint_map']);
            include "inc/file_upload/file_upload_edit_new.php";
          }
          

            /// start upload tinymce editor images to tbl_uplaods table
            include_once "inc/file_upload/editor_photos_upload.php";
            /// end uplaod to tbl_upload

    }
    

         
        include_once 'studies_coordinates.php';
    
   
   


    if ($type_code == 'T') {
        
      
       
       
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);   
        $related_to_area = mysqli_real_escape_string($conn, $_POST['related_to_area']);   
        $related_to_governorate = mysqli_real_escape_string($conn, $_POST['related_to_governorate']);
        $place_space = mysqli_real_escape_string($conn, $_POST['place_space']);
        $geographical_nature = mysqli_real_escape_string($conn, $_POST['geographical_nature']);
       
               
        
       
       
        
        $its_location_details = mysqli_real_escape_string($conn, $_POST['its_location_details']);        
        $strategic_importance = mysqli_real_escape_string($conn, $_POST['strategic_importance']);
        $important_facilities = mysqli_real_escape_string($conn, $_POST['important_facilities']);
        $people_attitude = mysqli_real_escape_string($conn, $_POST['people_attitude']);   
      
        $important_roads = mysqli_real_escape_string($conn, $_POST['important_roads']);
        $secured_entrances = mysqli_real_escape_string($conn, $_POST['secured_entrances']);
        $sub_entrances = mysqli_real_escape_string($conn, $_POST['sub_entrances']);
        $general_situation = mysqli_real_escape_string($conn, $_POST['general_situation']);

        $suggestions = mysqli_real_escape_string($conn, $_POST['suggestions']);

        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
       


        $taftesh_type = mysqli_real_escape_string($conn, $_POST['taftesh_type']);        
      
            
       

      
        
       /*  if(!empty($_FILES['studies_attach']['name'])) { 
            include_once "inc/file_upload.php";
            $studies_attach = mysqli_real_escape_string($conn, $target_file);         
        }elseif($_GET['edit']=='1' && empty($_FILES['studies_attach']['name'])){
            $inserted_ketab_num = mysqli_real_escape_string($conn, $_POST['inserted_ketab_num']);
            $inserted_ketab_date = mysqli_real_escape_string($conn, $_POST['inserted_ketab_date']);
            $inserted_jeha = mysqli_real_escape_string($conn, $_POST['inserted_jeha']);
            $attach_fileUpload='1';
            include_once "inc/file_upload_edit.php";
            $studies_attach = mysqli_real_escape_string($conn, $_POST['inserted_attach']);
        }else{
            $studies_attach = mysqli_real_escape_string($conn, '');
        } */

        if ($_GET['edit']=='0') {
  

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `related_to_area`, `related_to_governorate`, `place_space`, `longitude`, `geographical_nature`, `its_location_details`, `strategic_importance`, `important_facilities`, `people_attitude`, `important_roads`, `secured_entrances`, `sub_entrances`, `general_situation`, `taftesh_type`, `suggestions`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$related_to_area', '$related_to_governorate', '$place_space', '$longitude', '$geographical_nature', '$its_location_details', '$strategic_importance', '$important_facilities', '$people_attitude', '$important_roads', '$secured_entrances', '$sub_entrances', '$general_situation', '$taftesh_type', '$suggestions', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }

        if ($_GET['edit']=='1') {

            
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);

            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num=$type_num, 
            place_name='$place_name', 
            related_to_area='$related_to_area', 
            related_to_governorate='$related_to_governorate', 
            place_space='$place_space', 
            longitude='$longitude',            
            geographical_nature='$geographical_nature', 
            its_location_details='$its_location_details', 
            strategic_importance='$strategic_importance', 
            important_facilities='$important_facilities', 
            people_attitude='$people_attitude', 
            important_roads='$important_roads', 
            secured_entrances='$secured_entrances', 
            sub_entrances='$sub_entrances', 
            general_situation='$general_situation', 
            taftesh_type='$taftesh_type', 
            suggestions='$suggestions',           
             jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            /// studies_828_town_important_military_people ///
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);

                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name1_new'][$i]);
                        $the_family = mysqli_real_escape_string($conn, $_POST['the_family1_new'][$i]);
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job1_new'][$i]);
                        $tawajoh_3am= mysqli_real_escape_string($conn, $_POST['tawajoh_3am1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_828_town_important_military_people`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `place_of_birth`, `date_of_birth`, `nick_name`, `the_family`, `current_job`, `tawajoh_3am`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$place_of_birth', '$date_of_birth', '$nick_name', '$the_family', '$current_job', '$tawajoh_3am', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_important_military_people" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          
                
                if (!empty($_POST['name1'])) {                    
                    $number = count($_POST["name1"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);

                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name1'][$i]);
                        $the_family = mysqli_real_escape_string($conn, $_POST['the_family1'][$i]);
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job1'][$i]);
                        $tawajoh_3am= mysqli_real_escape_string($conn, $_POST['tawajoh_3am1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `studies_828_town_important_military_people` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        nick_name='$nick_name', 
                        the_family='$the_family', 
                        current_job='$current_job', 
                        tawajoh_3am='$tawajoh_3am', 
                        notes='$notes',                   
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_important_military_people" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            
                /// studies_828_town_influencers ///  
                if (!empty($_POST['name2_new'])) {                    
                    $number_new = count($_POST["name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);

                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name2_new'][$i]);
                        $the_family = mysqli_real_escape_string($conn, $_POST['the_family2_new'][$i]);
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job2_new'][$i]);
                        $tawajoh_3am= mysqli_real_escape_string($conn, $_POST['tawajoh_3am2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_828_town_influencers`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `place_of_birth`, `date_of_birth`, `nick_name`, `the_family`, `current_job`, `tawajoh_3am`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$place_of_birth', '$date_of_birth', '$nick_name', '$the_family', '$current_job', '$tawajoh_3am', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_influencers" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          
                
                if (!empty($_POST['name2'])) {                    
                    $number = count($_POST["name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

            
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);

                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name2'][$i]);
                        $the_family = mysqli_real_escape_string($conn, $_POST['the_family2'][$i]);
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job2'][$i]);
                        $tawajoh_3am= mysqli_real_escape_string($conn, $_POST['tawajoh_3am2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `studies_828_town_influencers` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        nick_name='$nick_name', 
                        the_family='$the_family', 
                        current_job='$current_job', 
                        tawajoh_3am='$tawajoh_3am', 
                        notes='$notes',                   
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_influencers" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            
                /// studies_828_town_rich_people ///  
                if (!empty($_POST['name3_new'])) {                    
                    $number_new = count($_POST["name3_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name3_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth3_new'][$i]);

                        if (!empty($_POST['date_of_birth3_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth3_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name3_new'][$i]);
                        $the_family = mysqli_real_escape_string($conn, $_POST['the_family3_new'][$i]);
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job3_new'][$i]);
                        $tawajoh_3am= mysqli_real_escape_string($conn, $_POST['tawajoh_3am3_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes3_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_828_town_rich_people`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `place_of_birth`, `date_of_birth`, `nick_name`, `the_family`, `current_job`, `tawajoh_3am`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$place_of_birth', '$date_of_birth', '$nick_name', '$the_family', '$current_job', '$tawajoh_3am', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_rich_people" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          
                
                if (!empty($_POST['name3'])) {                    
                    $number = count($_POST["name3"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

            
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name3'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth3'][$i]);

                        if (!empty($_POST['date_of_birth3'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth3'][$i]);
                        } else {
                            $date_of_birth = mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name3'][$i]);
                        $the_family = mysqli_real_escape_string($conn, $_POST['the_family3'][$i]);
                        $current_job = mysqli_real_escape_string($conn, $_POST['current_job3'][$i]);
                        $tawajoh_3am= mysqli_real_escape_string($conn, $_POST['tawajoh_3am3'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes3'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach3'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach3'][$i]);

                        $sql= "UPDATE `studies_828_town_rich_people` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        nick_name='$nick_name', 
                        the_family='$the_family', 
                        current_job='$current_job', 
                        tawajoh_3am='$tawajoh_3am', 
                        notes='$notes',                   
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_rich_people" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            
                /// studies_828_town_council ///  
                if (!empty($_POST['name4_new'])) {                    
                    $number_new = count($_POST["name4_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num4_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name4_new'][$i]);
                                           
                        $degree = mysqli_real_escape_string($conn, $_POST['degree4_new'][$i]);
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to4_new'][$i]);
                        $intellectual_orientation = mysqli_real_escape_string($conn, $_POST['intellectual_orientation4_new'][$i]);                    
                        $notes = mysqli_real_escape_string($conn, $_POST['notes4_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_828_town_council`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `degree`, `related_to`, `intellectual_orientation`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$degree', '$related_to', '$intellectual_orientation', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_council" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          
                
                if (!empty($_POST['name4'])) {                    
                    $number = count($_POST["name4"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

            
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num4'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name4'][$i]);
                        $degree = mysqli_real_escape_string($conn, $_POST['degree4'][$i]);
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to4'][$i]);
                        $intellectual_orientation = mysqli_real_escape_string($conn, $_POST['intellectual_orientation4'][$i]);                    
                        $notes = mysqli_real_escape_string($conn, $_POST['notes4'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach4'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach4'][$i]);

                        $sql= "UPDATE `studies_828_town_council` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        degree='$degree', 
                        related_to='$related_to', 
                        intellectual_orientation='$intellectual_orientation',                        
                        notes='$notes',                   
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_council" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }



               
            
            

            
                /// studies_828_town_demographic_information ///  
                        
                $original_people_num = mysqli_real_escape_string($conn, $_POST['original_people_num']);
                $displaced_num = mysqli_real_escape_string($conn, $_POST['displaced_num']);
                $meleshiat_num = mysqli_real_escape_string($conn, $_POST['meleshiat_num']);                      
                $total = mysqli_real_escape_string($conn, $_POST['total']);
                $displaced_return_to = mysqli_real_escape_string($conn, $_POST['displaced_return_to']);
                $mustawtin_nationality = mysqli_real_escape_string($conn, $_POST['mustawtin_nationality']);                    
                $people_type = mysqli_real_escape_string($conn, $_POST['people_type']);
                $famous_displaced = mysqli_real_escape_string($conn, $_POST['famous_displaced']);
                $famous_people = mysqli_real_escape_string($conn, $_POST['famous_people']);
                

                if($_GET['edit']=='0'){ 
                    $sql_new="INSERT INTO `studies_828_town_demographic_information`(`ketab_num`, `ketab_date`, `general_code`, `original_people_num`, `displaced_num`, `meleshiat_num`, `total`, `displaced_return_to`, `mustawtin_nationality`, `people_type`, `famous_displaced`, `famous_people`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$original_people_num', '$displaced_num', '$meleshiat_num', '$total', '$displaced_return_to', '$mustawtin_nationality', '$people_type', '$famous_displaced', '$famous_people', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                    if (mysqli_query($conn, $sql_new)){
                    }else{
                        echo "Error: studies_828_town_demographic_information" . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }

                if($_GET['edit']=='1'){ 
                    $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_demographic']);
                    $id_demographic = mysqli_real_escape_string($conn, $_POST['id_demographic']);
                    if(!empty($id_demographic)){
                        $sql= "UPDATE `studies_828_town_demographic_information` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        original_people_num='$original_people_num', 
                        displaced_num='$displaced_num', 
                        meleshiat_num='$meleshiat_num', 
                        total='$total', 
                        displaced_return_to='$displaced_return_to', 
                        mustawtin_nationality='$mustawtin_nationality', 
                        people_type='$people_type', 
                        famous_displaced='$famous_displaced', 
                        famous_people='$famous_people',              
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_demographic";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_demographic_information" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }else{
                        $sql_new="INSERT INTO `studies_828_town_demographic_information`(`ketab_num`, `ketab_date`, `general_code`, `original_people_num`, `displaced_num`, `meleshiat_num`, `total`, `displaced_return_to`, `mustawtin_nationality`, `people_type`, `famous_displaced`, `famous_people`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$original_people_num', '$displaced_num', '$meleshiat_num', '$total', '$displaced_return_to', '$mustawtin_nationality', '$people_type', '$famous_displaced', '$famous_people', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_demographic_information" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
               



              
        
            ///////


            /// studies_828_town_famous_families ///  
                if (!empty($_POST['family_name5_new'])) {                    
                    $number_new = count($_POST["family_name5_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num5_new'][$i]);
                        $family_name = mysqli_real_escape_string($conn, $_POST['family_name5_new'][$i]);
                        $estimate_num = mysqli_real_escape_string($conn, $_POST['estimate_num5_new'][$i]);                      
                        $percent = mysqli_real_escape_string($conn, $_POST['percent5_new'][$i]);
                        $head_of_family = mysqli_real_escape_string($conn, $_POST['head_of_family5_new'][$i]);
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am5_new'][$i]);                    
                        $notes = mysqli_real_escape_string($conn, $_POST['notes5_new'][$i]);
                        
                        $sql_new="INSERT INTO `studies_828_town_famous_families`(`ketab_num`, `ketab_date`, `general_code`, `num`, `family_name`, `estimate_num`, `percent`, `head_of_family`, `tawajoh_3am`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$family_name', '$estimate_num', '$percent', '$head_of_family', '$tawajoh_3am', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_famous_families" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          
                
                if (!empty($_POST['family_name5'])) {                    
                    $number = count($_POST["family_name5"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

            
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num5'][$i]);
                        $family_name = mysqli_real_escape_string($conn, $_POST['family_name5'][$i]);
                        $estimate_num = mysqli_real_escape_string($conn, $_POST['estimate_num5'][$i]);                      
                        $percent = mysqli_real_escape_string($conn, $_POST['percent5'][$i]);
                        $head_of_family = mysqli_real_escape_string($conn, $_POST['head_of_family5'][$i]);
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am5'][$i]);                    
                        $notes = mysqli_real_escape_string($conn, $_POST['notes5'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach5'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach5'][$i]);

                        $sql= "UPDATE `studies_828_town_famous_families` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        family_name='$family_name', 
                        estimate_num='$estimate_num', 
                        percent='$percent', 
                        head_of_family='$head_of_family', 
                        tawajoh_3am='$tawajoh_3am', 
                        notes='$notes',                
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_famous_families" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_mukhtar_name ///  

                $mustawa_ma3eshi = mysqli_real_escape_string($conn, $_POST['mustawa_ma3eshi']);
                $people_works = mysqli_real_escape_string($conn, $_POST['people_works']); 
                
                if (!empty($_POST['mukhtar_name6_new'])) {                    
                    $number_new = count($_POST["mukhtar_name6_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num6_new'][$i]);
                        $mukhtar_name = mysqli_real_escape_string($conn, $_POST['mukhtar_name6_new'][$i]);
                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name6_new'][$i]);                      
                        $neighborhood = mysqli_real_escape_string($conn, $_POST['neighborhood6_new'][$i]);
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am6_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes6_new'][$i]);     

                        
                        
                        $sql_new="INSERT INTO `studies_828_town_mukhtar_name` (`ketab_num`, `ketab_date`, `general_code`, `num`, `mukhtar_name`, `nick_name`, `neighborhood`, `tawajoh_3am`, `notes`, `mustawa_ma3eshi`, `people_works`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$mukhtar_name', '$nick_name', '$neighborhood', '$tawajoh_3am', '$notes', '$mustawa_ma3eshi', '$people_works', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_mukhtar_name" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['mukhtar_name6'])) {                    
                    $number = count($_POST["mukhtar_name6"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num6'][$i]);
                        $mukhtar_name = mysqli_real_escape_string($conn, $_POST['mukhtar_name6'][$i]);
                        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name6'][$i]);                      
                        $neighborhood = mysqli_real_escape_string($conn, $_POST['neighborhood6'][$i]);
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am6'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes6'][$i]);     

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach6'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach6'][$i]);

                        $sql= "UPDATE `studies_828_town_mukhtar_name` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        mukhtar_name='$mukhtar_name', 
                        nick_name='$nick_name', 
                        neighborhood='$neighborhood', 
                        tawajoh_3am='$tawajoh_3am', 
                        notes='$notes', 
                        mustawa_ma3eshi='$mustawa_ma3eshi', 
                        people_works='$people_works',         
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_mukhtar_name" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_famous_mosques ///  

                $mosques_num = mysqli_real_escape_string($conn, $_POST['mosques_num']);

                if (!empty($_POST['mosques_name7_new'])) {                    
                    $number_new = count($_POST["mosques_name7_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                      
                        $num = mysqli_real_escape_string($conn, $_POST['num7_new'][$i]);
                        $mosques_name = mysqli_real_escape_string($conn, $_POST['mosques_name7_new'][$i]);
                        $khateed_aljum3a_name = mysqli_real_escape_string($conn, $_POST['khateed_aljum3a_name7_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth7_new'][$i]);                      
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth7_new'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am7_new'][$i]);
                        $imam_ratib_name = mysqli_real_escape_string($conn, $_POST['imam_ratib_name7_new'][$i]);     

                        $ratib_place_of_birth = mysqli_real_escape_string($conn, $_POST['ratib_place_of_birth7_new'][$i]);
                        if (!empty($_POST['ratib_date_of_birth7_new'][$i])) {
                            $ratib_date_of_birth = mysqli_real_escape_string($conn, $_POST['ratib_date_of_birth7_new'][$i]);
                        } else {
                            $ratib_date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $ratib_tawajoh_3am = mysqli_real_escape_string($conn, $_POST['ratib_tawajoh_3am7_new'][$i]); 
                        
                     
                        
                        $sql_new="INSERT INTO `studies_828_town_famous_mosques`(`ketab_num`, `ketab_date`, `general_code`, `mosques_num`, `num`, `mosques_name`, `khateed_aljum3a_name`, `place_of_birth`, `date_of_birth`, `tawajoh_3am`, `imam_ratib_name`, `ratib_place_of_birth`, `ratib_date_of_birth`, `ratib_tawajoh_3am`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$mosques_num', '$num', '$mosques_name', '$khateed_aljum3a_name', '$place_of_birth', '$date_of_birth', '$tawajoh_3am', '$imam_ratib_name', '$ratib_place_of_birth', '$ratib_date_of_birth', '$ratib_tawajoh_3am', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_famous_mosques" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['mosques_name7'])) {                    
                    $number = count($_POST["mosques_name7"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num7'][$i]);
                        $mosques_name = mysqli_real_escape_string($conn, $_POST['mosques_name7'][$i]);
                        $khateed_aljum3a_name = mysqli_real_escape_string($conn, $_POST['khateed_aljum3a_name7'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth7'][$i]);                      
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth7'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am7'][$i]);
                        $imam_ratib_name = mysqli_real_escape_string($conn, $_POST['imam_ratib_name7'][$i]);     

                        $ratib_place_of_birth = mysqli_real_escape_string($conn, $_POST['ratib_place_of_birth7'][$i]);
                        if (!empty($_POST['ratib_date_of_birth7'][$i])) {
                            $ratib_date_of_birth = mysqli_real_escape_string($conn, $_POST['ratib_date_of_birth7'][$i]);
                        } else {
                            $ratib_date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $ratib_tawajoh_3am = mysqli_real_escape_string($conn, $_POST['ratib_tawajoh_3am7'][$i]); 
                        
                      

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach7'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach7'][$i]);

                        $sql= "UPDATE `studies_828_town_famous_mosques` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        mosques_name='$mosques_name', 
                        khateed_aljum3a_name='$khateed_aljum3a_name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        tawajoh_3am='$tawajoh_3am', 
                        imam_ratib_name='$imam_ratib_name', 
                        ratib_place_of_birth='$ratib_place_of_birth', 
                        ratib_date_of_birth='$ratib_date_of_birth', 
                        ratib_tawajoh_3am='$ratib_tawajoh_3am', 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_famous_mosques" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////


            /// studies_828_town_shari3a_institutes ///  

                $institution_num = mysqli_real_escape_string($conn, $_POST['institution_num']);
                $male_student = mysqli_real_escape_string($conn, $_POST['male_student']);
                $female_student = mysqli_real_escape_string($conn, $_POST['female_student']);
               


                if (!empty($_POST['institution_name8_new'])) {                    
                    $number_new = count($_POST["institution_name8_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num8_new'][$i]);
                        $institution_name = mysqli_real_escape_string($conn, $_POST['institution_name8_new'][$i]);
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names8_new'][$i]);
                        $tawajoh_fekre = mysqli_real_escape_string($conn, $_POST['tawajoh_fekre8_new'][$i]);                      
                        $male_student_num = mysqli_real_escape_string($conn, $_POST['male_student_num8_new'][$i]);
                        $female_student_num = mysqli_real_escape_string($conn, $_POST['female_student_num8_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes8_new'][$i]);     

                      
                        
                        $sql_new="INSERT INTO `studies_828_town_shari3a_institutes`(`ketab_num`, `ketab_date`, `general_code`, `institution_num`, `male_student`, `female_student`, `num`, `institution_name`, `workers_names`, `tawajoh_fekre`, `male_student_num`, `female_student_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$institution_num', '$male_student', '$female_student', '$num', '$institution_name', '$workers_names', '$tawajoh_fekre', '$male_student_num', '$female_student_num', '$notes','$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_shari3a_institutes" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['institution_name8'])) {                    
                    $number = count($_POST["institution_name8"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num8'][$i]);
                        $institution_name = mysqli_real_escape_string($conn, $_POST['institution_name8'][$i]);
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names8'][$i]);
                        $tawajoh_fekre = mysqli_real_escape_string($conn, $_POST['tawajoh_fekre8'][$i]);                      
                        $male_student_num = mysqli_real_escape_string($conn, $_POST['male_student_num8'][$i]);
                        $female_student_num = mysqli_real_escape_string($conn, $_POST['female_student_num8'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes8'][$i]); 

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach8'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach8'][$i]);

                        $sql= "UPDATE `studies_828_town_shari3a_institutes` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        institution_num='$institution_num', 
                        male_student='$male_student', 
                        female_student='$female_student', 
                        num='$num', 
                        institution_name='$institution_name', 
                        workers_names='$workers_names', 
                        tawajoh_fekre='$tawajoh_fekre', 
                        male_student_num='$male_student_num', 
                        female_student_num='$female_student_num', 
                        notes='$notes',        
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_shari3a_institutes" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_shi3a_centers ///  

        


                if (!empty($_POST['center_name9_new'])) {                    
                    $number_new = count($_POST["center_name9_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num9_new'][$i]);
                        $center_name = mysqli_real_escape_string($conn, $_POST['center_name9_new'][$i]);
                        $center_manager_name = mysqli_real_escape_string($conn, $_POST['center_manager_name9_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth9_new'][$i]);  

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth9_new'][$i]);
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names9_new'][$i]);
                        $targeted_groups = mysqli_real_escape_string($conn, $_POST['targeted_groups9_new'][$i]);     

                        $male_student_num = mysqli_real_escape_string($conn, $_POST['male_student_num9_new'][$i]);
                        $female_student_num = mysqli_real_escape_string($conn, $_POST['female_student_num9_new'][$i]);
                        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type9_new'][$i]);
                       
                        
                        $sql_new="INSERT INTO `studies_828_town_shi3a_centers`(`ketab_num`, `ketab_date`, `general_code`, `num`, `center_name`, `center_manager_name`, `place_of_birth`, `date_of_birth`, `workers_names`, `targeted_groups`, `male_student_num`, `female_student_num`, `activity_type`,`jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$center_name', '$center_manager_name', '$place_of_birth', '$date_of_birth', '$workers_names', '$targeted_groups', '$male_student_num', '$female_student_num', '$activity_type', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_shi3a_centers_add" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['center_name9'])) {                    
                    $number = count($_POST["center_name9"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num9'][$i]);
                        $center_name = mysqli_real_escape_string($conn, $_POST['center_name9'][$i]);
                        $center_manager_name = mysqli_real_escape_string($conn, $_POST['center_manager_name9'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth9'][$i]);                      
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth9'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names9'][$i]);
                        $targeted_groups = mysqli_real_escape_string($conn, $_POST['targeted_groups9'][$i]);     

                        $male_student_num = mysqli_real_escape_string($conn, $_POST['male_student_num9'][$i]);
                        $female_student_num = mysqli_real_escape_string($conn, $_POST['female_student_num9'][$i]);
                        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type9'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach9'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach9'][$i]);

                        $sql= "UPDATE `studies_828_town_shi3a_centers` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        center_name='$center_name', 
                        center_manager_name='$center_manager_name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        workers_names='$workers_names', 
                        targeted_groups='$targeted_groups', 
                        male_student_num='$male_student_num', 
                        female_student_num='$female_student_num', 
                        activity_type='$activity_type',      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_shi3a_centers_edit" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_education ///  

                    $education_level = mysqli_real_escape_string($conn, $_POST['education_level']);
                    $educational_num = mysqli_real_escape_string($conn, $_POST['educational_num']);
                    $school_num = mysqli_real_escape_string($conn, $_POST['school_num']);

                    if (!empty($_POST['school_name10_new'])) {                    
                        $number_new = count($_POST["school_name10_new"]);
                    } else {
                        $number_new =mysqli_real_escape_string($conn, 0);
                    }

                    if ($number_new >= 1) {
                        for ($i=0; $i<$number_new; $i++) {
                        
                            $num = mysqli_real_escape_string($conn, $_POST['num10_new'][$i]);
                            $school_name = mysqli_real_escape_string($conn, $_POST['school_name10_new'][$i]);
                            $stage = mysqli_real_escape_string($conn, $_POST['stage10_new'][$i]);
                            $general_name = mysqli_real_escape_string($conn, $_POST['general_name10_new'][$i]);                      
                            $famous_teachers = mysqli_real_escape_string($conn, $_POST['famous_teachers10_new'][$i]);
                            $notes = mysqli_real_escape_string($conn, $_POST['notes10_new'][$i]);
                            
                        
                            
                            $sql_new="INSERT INTO `studies_828_town_education`(`ketab_num`, `ketab_date`, `general_code`,  `education_level`, `educational_num`,`school_num`, `num`, `school_name`, `stage`, `general_name`, `famous_teachers`, `notes`,`jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$education_level', '$educational_num', '$school_num', '$num', '$school_name', '$stage', '$general_name', '$famous_teachers', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                            if (mysqli_query($conn, $sql_new)){
                            }else{
                                echo "Error: studies_828_town_education" . "<br>" . mysqli_error($conn);
                                exit;
                            }
                        }
                    }                          

                    if (!empty($_POST['school_name10'])) {                    
                        $number= count($_POST["school_name10"]);
                    } else {
                        $number =mysqli_real_escape_string($conn, 0);
                    }


                    if ($number >= 1) {
                        for ($i=0; $i<$number; $i++) {
                            $num = mysqli_real_escape_string($conn, $_POST['num10'][$i]);
                            $school_name = mysqli_real_escape_string($conn, $_POST['school_name10'][$i]);
                            $stage = mysqli_real_escape_string($conn, $_POST['stage10'][$i]);
                            $general_name = mysqli_real_escape_string($conn, $_POST['general_name10'][$i]);                      
                            $famous_teachers = mysqli_real_escape_string($conn, $_POST['famous_teachers10'][$i]);
                            $notes = mysqli_real_escape_string($conn, $_POST['notes10'][$i]);

                            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach10'][$i]);
                            $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach10'][$i]);

                            $sql= "UPDATE `studies_828_town_education` SET 
                            general_code='$general_code',
                            ketab_num=$ketab_num, 
                            ketab_date='$ketab_date',
                            education_level='$education_level', 
                            educational_num='$educational_num',
                            school_num='$school_num',
                            num='$num', 
                            school_name='$school_name', 
                            stage='$stage', 
                            general_name='$general_name', 
                            famous_teachers='$famous_teachers', 
                            notes='$notes',
                            added_by='$added_by_old - $added_by', 
                            add_date=current_timestamp()
                            WHERE id = $id_attach";
                        
                            if (mysqli_query($conn, $sql)){
                            }else{
                                echo "Error: studies_828_town_education" . "<br>" . mysqli_error($conn);
                                exit;
                            }
                        }
                    }
            //////

            /// studies_828_town_schools ///  

                  
                    if (!empty($_POST['school_level20_new'])) {                    
                        $number_new = count($_POST["school_level20_new"]);
                    } else {
                        $number_new =mysqli_real_escape_string($conn, 0);
                    }

                    if ($number_new >= 1) {
                        for ($i=0; $i<$number_new; $i++) {
                        
                            $school_level = mysqli_real_escape_string($conn, $_POST['school_level20_new'][$i]);
                            $schools_num = mysqli_real_escape_string($conn, $_POST['schools_num20_new'][$i]);
                            $schools_students_num = mysqli_real_escape_string($conn, $_POST['schools_students_num20_new'][$i]);
                            $notes = mysqli_real_escape_string($conn, $_POST['notes20_new'][$i]);
                            
                        
                            
                            $sql_new="INSERT INTO `studies_828_town_schools`( `ketab_num`, `ketab_date`, `general_code`, `school_level`, `schools_num`, `schools_students_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$school_level', '$schools_num', '$schools_students_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                            if (mysqli_query($conn, $sql_new)){
                            }else{
                                echo "Error: studies_828_town_schools" . "<br>" . mysqli_error($conn);
                                exit;
                            }
                        }
                    }                          

                    if (!empty($_POST['school_level20'])) {                    
                        $number= count($_POST["school_level20"]);
                    } else {
                        $number =mysqli_real_escape_string($conn, 0);
                    }


                    if ($number >= 1) {
                        for ($i=0; $i<$number; $i++) {
                            $school_level = mysqli_real_escape_string($conn, $_POST['school_level20'][$i]);
                            $schools_num = mysqli_real_escape_string($conn, $_POST['schools_num20'][$i]);
                            $schools_students_num = mysqli_real_escape_string($conn, $_POST['schools_students_num20'][$i]);
                            $notes = mysqli_real_escape_string($conn, $_POST['notes20'][$i]);

                            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach20'][$i]);
                            $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach20'][$i]);

                            $sql= "UPDATE `studies_828_town_schools` SET 
                            general_code='$general_code',
                            ketab_num=$ketab_num, 
                            ketab_date='$ketab_date',
                            school_level='$school_level', 
                            schools_num='$schools_num',
                            schools_students_num='$schools_students_num', 
                            notes='$notes',
                            added_by='$added_by_old - $added_by', 
                            add_date=current_timestamp()
                            WHERE id = $id_attach";
                        
                            if (mysqli_query($conn, $sql)){
                            }else{
                                echo "Error: studies_828_town_schools" . "<br>" . mysqli_error($conn);
                                exit;
                            }
                        }
                    }
            //////
           

            /// studies_828_town_faculties ///  

                if (!empty($_POST['faculty_name11_new'])) {                    
                    $number_new = count($_POST["faculty_name11_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num11_new'][$i]);
                        $faculty_name = mysqli_real_escape_string($conn, $_POST['faculty_name11_new'][$i]);
                        $dean_name = mysqli_real_escape_string($conn, $_POST['dean_name11_new'][$i]);
                        $students_num = mysqli_real_escape_string($conn, $_POST['students_num11_new'][$i]);                      
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to11_new'][$i]);
                        $university_president = mysqli_real_escape_string($conn, $_POST['university_president11_new'][$i]);
                        $university_related_to = mysqli_real_escape_string($conn, $_POST['university_related_to11_new'][$i]);
                        $university_funding = mysqli_real_escape_string($conn, $_POST['university_funding11_new'][$i]);
                        
                        $notes = mysqli_real_escape_string($conn, $_POST['notes11_new'][$i]);
                    
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_faculties`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `faculty_name`, `dean_name`, `students_num`, `related_to`, `university_president`, `university_related_to`, `university_funding`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$faculty_name', '$dean_name', '$students_num', '$related_to', '$university_president', '$university_related_to', '$university_funding', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_faculties" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['faculty_name11'])) {                    
                    $number = count($_POST["faculty_name11"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num11'][$i]);
                        $faculty_name = mysqli_real_escape_string($conn, $_POST['faculty_name11'][$i]);
                        $dean_name = mysqli_real_escape_string($conn, $_POST['dean_name11'][$i]);
                        $students_num = mysqli_real_escape_string($conn, $_POST['students_num11'][$i]);                      
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to11'][$i]);
                        $university_president = mysqli_real_escape_string($conn, $_POST['university_president11'][$i]);
                        $university_related_to = mysqli_real_escape_string($conn, $_POST['university_related_to11'][$i]);
                        $university_funding = mysqli_real_escape_string($conn, $_POST['university_funding11'][$i]);
                        
                        $notes = mysqli_real_escape_string($conn, $_POST['notes11'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach11'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach11'][$i]);

                        $sql= "UPDATE `studies_828_town_faculties` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        faculty_name='$faculty_name', 
                        dean_name='$dean_name', 
                        students_num='$students_num', 
                        related_to='$related_to', 
                        university_president='$university_president', 
                        university_related_to='$university_related_to', 
                        university_funding='$university_funding',
                        notes='$notes',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_faculties edit" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_russian_cultral_centers ///  

                if (!empty($_POST['center_name12_new'])) {                    
                    $number_new = count($_POST["center_name12_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num12_new'][$i]);
                        $center_name = mysqli_real_escape_string($conn, $_POST['center_name12_new'][$i]);
                        $center_manager_name = mysqli_real_escape_string($conn, $_POST['center_manager_name12_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth12_new'][$i]);                      
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth12_new'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names12_new'][$i]);
                        $targeted_groups = mysqli_real_escape_string($conn, $_POST['targeted_groups12_new'][$i]);
                        $male_student_num = mysqli_real_escape_string($conn, $_POST['male_student_num12_new'][$i]);                        
                        $female_student_num = mysqli_real_escape_string($conn, $_POST['female_student_num12_new'][$i]);
                        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type12_new'][$i]);
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_russian_cultral_centers`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `center_name`, `center_manager_name`, `place_of_birth`, `date_of_birth`, `workers_names`, `targeted_groups`, `male_student_num`, `female_student_num`, `activity_type`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$center_name', '$center_manager_name', '$place_of_birth', '$date_of_birth', '$workers_names', '$targeted_groups', '$male_student_num', '$female_student_num', '$activity_type', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_russian_cultral_centers" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['center_name12'])) {                    
                    $number = count($_POST["center_name12"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num12'][$i]);
                        $center_name = mysqli_real_escape_string($conn, $_POST['center_name12'][$i]);
                        $center_manager_name = mysqli_real_escape_string($conn, $_POST['center_manager_name12'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth12'][$i]);         

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth12'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names12'][$i]);
                        $targeted_groups = mysqli_real_escape_string($conn, $_POST['targeted_groups12'][$i]);
                        $male_student_num = mysqli_real_escape_string($conn, $_POST['male_student_num12'][$i]);                        
                        $female_student_num = mysqli_real_escape_string($conn, $_POST['female_student_num12'][$i]);
                        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type12'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach12'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach12'][$i]);

                        $sql= "UPDATE `studies_828_town_russian_cultral_centers` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        center_name='$center_name', 
                        center_manager_name='$center_manager_name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        workers_names='$workers_names', 
                        targeted_groups='$targeted_groups', 
                        male_student_num = '$male_student_num', 
                        female_student_num = '$female_student_num', 
                        activity_type = '$activity_type',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_russian_cultral_centers" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_public_utilities ///  

                if (!empty($_POST['service_type13_new'])) {                    
                    $number_new = count($_POST["service_type13_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num13_new'][$i]);
                        $service_type = mysqli_real_escape_string($conn, $_POST['service_type13_new'][$i]);
                        $center_name = mysqli_real_escape_string($conn, $_POST['center_name13_new'][$i]);
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names13_new'][$i]);                      
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth13_new'][$i]);
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth13_new'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $degree = mysqli_real_escape_string($conn, $_POST['degree13_new'][$i]);
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am13_new'][$i]);                        
                        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type13_new'][$i]);
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_public_utilities`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `service_type`, `center_name`, `workers_names`, `place_of_birth`, `date_of_birth`, `degree`, `tawajoh_3am`, `activity_type`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$service_type', '$center_name', '$workers_names', '$place_of_birth', '$date_of_birth', '$degree', '$tawajoh_3am', '$activity_type',  '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_public_utilities" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['service_type13'])) {                    
                    $number = count($_POST["service_type13"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num13'][$i]);
                        $service_type = mysqli_real_escape_string($conn, $_POST['service_type13'][$i]);
                        $center_name = mysqli_real_escape_string($conn, $_POST['center_name13'][$i]);
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names13'][$i]);                      
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth13'][$i]);
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth13'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $degree = mysqli_real_escape_string($conn, $_POST['degree13'][$i]);
                        $tawajoh_3am = mysqli_real_escape_string($conn, $_POST['tawajoh_3am13'][$i]);                        
                        $activity_type = mysqli_real_escape_string($conn, $_POST['activity_type13'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach13'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach13'][$i]);

                        $sql= "UPDATE `studies_828_town_public_utilities` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        service_type='$service_type', 
                        center_name='$center_name', 
                        workers_names='$workers_names', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        degree='$degree', 
                        tawajoh_3am='$tawajoh_3am', 
                        activity_type='$activity_type',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_public_utilities" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////


            /// studies_828_town_bakeries ///  
                $afran_num = mysqli_real_escape_string($conn, $_POST['afran_num']);
                $enough = mysqli_real_escape_string($conn, $_POST['enough']);

                if (!empty($_POST['frn_name14_new'])) {                    
                    $number_new = count($_POST["frn_name14_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num14_new'][$i]);
                        $frn_name = mysqli_real_escape_string($conn, $_POST['frn_name14_new'][$i]);
                        $frn_type = mysqli_real_escape_string($conn, $_POST['frn_type14_new'][$i]);
                        $taheen_amount = mysqli_real_escape_string($conn, $_POST['taheen_amount14_new'][$i]);                      
                        $frn_support = mysqli_real_escape_string($conn, $_POST['frn_support14_new'][$i]);
                        $frn_related_to = mysqli_real_escape_string($conn, $_POST['frn_related_to14_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes14_new'][$i]);
                        
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_bakeries`(`ketab_num`, `ketab_date`, `general_code`,  `afran_num`, `enough`, `num`, `frn_name`, `frn_type`, `taheen_amount`, `frn_support`, `frn_related_to`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$afran_num', '$enough', '$num', '$frn_name', '$frn_type', '$taheen_amount', '$frn_support', '$frn_related_to', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_bakeries" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['frn_name14'])) {                    
                    $number = count($_POST["frn_name14"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num14'][$i]);
                        $frn_name = mysqli_real_escape_string($conn, $_POST['frn_name14'][$i]);
                        $frn_type = mysqli_real_escape_string($conn, $_POST['frn_type14'][$i]);
                        $taheen_amount = mysqli_real_escape_string($conn, $_POST['taheen_amount14'][$i]);                      
                        $frn_support = mysqli_real_escape_string($conn, $_POST['frn_support14'][$i]);
                        $frn_related_to = mysqli_real_escape_string($conn, $_POST['frn_related_to14'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes14'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach14'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach14'][$i]);

                        $sql= "UPDATE `studies_828_town_bakeries` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        afran_num='$afran_num', 
                        enough='$enough', 
                        num='$num', 
                        frn_name='$frn_name', 
                        frn_type='$frn_type', 
                        taheen_amount='$taheen_amount', 
                        taheen_amount='$frn_support', 
                        frn_related_to='$frn_related_to', 
                        notes='$notes',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_bakeries" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /*  /// studies_828_town_bakeries ///  

                    $afran_num = mysqli_real_escape_string($conn, $_POST['afran_num']);
                    $enough = mysqli_real_escape_string($conn, $_POST['enough']);

                    if (!empty($_POST['frn_name14_new'])) {                    
                        $number_new = count($_POST["frn_name14_new"]);
                    } else {
                        $number_new =mysqli_real_escape_string($conn, 0);
                    }

                    if ($number_new >= 1) {
                        for ($i=0; $i<$number_new; $i++) {
                        
                            $num = mysqli_real_escape_string($conn, $_POST['num14_new'][$i]);
                            $frn_name = mysqli_real_escape_string($conn, $_POST['frn_name14_new'][$i]);
                            $frn_type = mysqli_real_escape_string($conn, $_POST['frn_type14_new'][$i]);
                            $taheen_amount = mysqli_real_escape_string($conn, $_POST['taheen_amount14_new'][$i]);                      
                            $frn_support = mysqli_real_escape_string($conn, $_POST['frn_support14_new'][$i]);
                            $frn_related_to = mysqli_real_escape_string($conn, $_POST['frn_related_to14_new'][$i]);
                            $notes = mysqli_real_escape_string($conn, $_POST['notes14_new'][$i]);
                            
                        
                            
                            $sql_new="INSERT INTO `studies_828_town_bakeries`(`ketab_num`, `ketab_date`, `general_code`,   `afran_num`, `enough`, `num`, `frn_name`, `frn_type`, `taheen_amount`, `frn_support`, `frn_related_to`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code',  '$afran_num', '$enough', '$num', '$frn_name', '$frn_type', '$taheen_amount', '$frn_support', '$frn_related_to', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                            if (mysqli_query($conn, $sql_new)){
                            }else{
                                echo "Error: " . "<br>" . mysqli_error($conn);
                                exit;
                            }
                        }
                    }                          

                    if (!empty($_POST['activity_name15'])) {                    
                        $number = count($_POST["activity_name15"]);
                    } else {
                        $number =mysqli_real_escape_string($conn, 0);
                    }


                    if ($number >= 1) {
                        for ($i=0; $i<$number; $i++) {
                            $num = mysqli_real_escape_string($conn, $_POST['num14'][$i]);
                            $frn_name = mysqli_real_escape_string($conn, $_POST['frn_name14'][$i]);
                            $frn_type = mysqli_real_escape_string($conn, $_POST['frn_type14'][$i]);
                            $taheen_amount = mysqli_real_escape_string($conn, $_POST['taheen_amount14'][$i]);                      
                            $frn_support = mysqli_real_escape_string($conn, $_POST['frn_support14'][$i]);
                            $frn_related_to = mysqli_real_escape_string($conn, $_POST['frn_related_to14'][$i]);
                            $notes = mysqli_real_escape_string($conn, $_POST['notes14'][$i]);

                            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach14'][$i]);
                            $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach14'][$i]);

                            $sql= "UPDATE `studies_828_town_bakeries` SET 
                            general_code='$general_code',
                            ketab_num=$ketab_num, 
                            ketab_date='$ketab_date',
                            afran_num='$afran_num', 
                            enough='$enough', 
                            num='$num', 
                            frn_name='$frn_name', 
                            frn_type='$frn_type', 
                            taheen_amount='$taheen_amount', 
                            frn_support='$frn_support', 
                            frn_related_to='$frn_related_to', 
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
            ////// */

            /// studies_828_town_organisation ///  

               

                if (!empty($_POST['activity_name15_new'])) {                    
                    $number_new = count($_POST["activity_name15_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num15_new'][$i]);
                        $activity_name = mysqli_real_escape_string($conn, $_POST['activity_name15_new'][$i]);
                        $type = mysqli_real_escape_string($conn, $_POST['type15_new'][$i]);
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names15_new'][$i]);                      
                        $activities = mysqli_real_escape_string($conn, $_POST['activities15_new'][$i]);
                        $support = mysqli_real_escape_string($conn, $_POST['support15_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes15_new'][$i]);
                        
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_organisation`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `activity_name`, `type`, `workers_names`, `activities`, `support`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$activity_name', '$type', '$workers_names', '$activities', '$support', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_organisation" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['activity_name15'])) {                    
                    $number = count($_POST["activity_name15"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num15'][$i]);
                        $activity_name = mysqli_real_escape_string($conn, $_POST['activity_name15'][$i]);
                        $type = mysqli_real_escape_string($conn, $_POST['type15'][$i]);
                        $workers_names = mysqli_real_escape_string($conn, $_POST['workers_names15'][$i]);                      
                        $activities = mysqli_real_escape_string($conn, $_POST['activities15'][$i]);
                        $support = mysqli_real_escape_string($conn, $_POST['support15'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes15'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach15'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach15'][$i]);

                        $sql= "UPDATE `studies_828_town_organisation` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        activity_name='$activity_name', 
                        type='$type', 
                        workers_names='$workers_names', 
                        activities='$activities', 
                        support='$support', 
                        notes='$notes',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_organisation" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            

            /// studies_828_town_military_branches ///  

                if (!empty($_POST['branch_name16_new'])) {                    
                    $number_new = count($_POST["branch_name16_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num16_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name16_new'][$i]);
                        $leader_name = mysqli_real_escape_string($conn, $_POST['leader_name16_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth16_new'][$i]);     

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth16_new'][$i]);

                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $type = mysqli_real_escape_string($conn, $_POST['type16_new'][$i]);
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to16_new'][$i]);
                        $estimate_num = mysqli_real_escape_string($conn, $_POST['estimate_num16_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes16_new'][$i]);
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_military_branches`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `branch_name`, `leader_name`, `place_of_birth`, `date_of_birth`, `type`, `related_to`, `estimate_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$leader_name', '$place_of_birth', '$date_of_birth', '$type', '$related_to', '$estimate_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_military_branches" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['branch_name16'])) {                    
                    $number = count($_POST["branch_name16"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num16'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name16'][$i]);
                        $leader_name = mysqli_real_escape_string($conn, $_POST['leader_name16'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth16'][$i]);   

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth16'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $type = mysqli_real_escape_string($conn, $_POST['type16'][$i]);
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to16'][$i]);
                        $estimate_num = mysqli_real_escape_string($conn, $_POST['estimate_num16'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes16'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach16'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach16'][$i]);

                        $sql= "UPDATE `studies_828_town_military_branches` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        leader_name='$leader_name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth',                        
                        type='$type', 
                        related_to='$related_to', 
                        estimate_num='$estimate_num',
                        notes='$notes',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_military_branches" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////


            /// studies_828_town_military_places ///  

                if (!empty($_POST['branch_name17_new'])) {                    
                    $number_new = count($_POST["branch_name17_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num17_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name17_new'][$i]);
                        $leader_name = mysqli_real_escape_string($conn, $_POST['leader_name17_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth17_new'][$i]);   

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth17_new'][$i]);

                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $leader_rank = mysqli_real_escape_string($conn, $_POST['leader_rank17_new'][$i]);
                        $type = mysqli_real_escape_string($conn, $_POST['type17_new'][$i]);
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to17_new'][$i]);
                        $estimate_num = mysqli_real_escape_string($conn, $_POST['estimate_num17_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes17_new'][$i]);
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_military_places`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `branch_name`, `leader_name`, `place_of_birth`, `date_of_birth`, `leader_rank`, `type`, `related_to`, `estimate_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$leader_name', '$place_of_birth', '$date_of_birth', '$leader_rank', '$type', '$related_to', '$estimate_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_military_places" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['branch_name17'])) {                    
                    $number = count($_POST["branch_name17"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num17'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name17'][$i]);
                        $leader_name = mysqli_real_escape_string($conn, $_POST['leader_name17'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth17'][$i]);                     

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth17'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }

                        $leader_rank = mysqli_real_escape_string($conn, $_POST['leader_rank17'][$i]);
                        $type = mysqli_real_escape_string($conn, $_POST['type17'][$i]);
                        $related_to = mysqli_real_escape_string($conn, $_POST['related_to17'][$i]);
                        $estimate_num = mysqli_real_escape_string($conn, $_POST['estimate_num17'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes17'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach17'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach17'][$i]);

                        $sql= "UPDATE `studies_828_town_military_places` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        leader_name='$leader_name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        leader_rank='$leader_rank', 
                        type='$type', 
                        related_to='$related_to', 
                        estimate_num='$estimate_num',
                        notes='$notes',
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_military_places" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////

            /// studies_828_town_new_military_places ///  

                if (!empty($_POST['branch_name18_new'])) {                    
                    $number_new = count($_POST["branch_name18_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }

                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                    
                        $num = mysqli_real_escape_string($conn, $_POST['num18_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name18_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address18_new'][$i]);
                        $leader_name = mysqli_real_escape_string($conn, $_POST['leader_name18_new'][$i]);                      
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth18_new'][$i]);

                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth18_new'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $type = mysqli_real_escape_string($conn, $_POST['type18_new'][$i]);
                        $who_established = mysqli_real_escape_string($conn, $_POST['who_established18_new'][$i]);
                        $goal_of_establishing = mysqli_real_escape_string($conn, $_POST['goal_of_establishing18_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes18_new'][$i]);
                    
                        
                        $sql_new="INSERT INTO `studies_828_town_new_military_places`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `branch_name`, `address`, `leader_name`, `place_of_birth`, `date_of_birth`, `type`, `who_established`, `goal_of_establishing`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$leader_name', '$place_of_birth', '$date_of_birth', '$type', '$who_established', '$goal_of_establishing', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: studies_828_town_new_military_places" . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }                          

                if (!empty($_POST['branch_name18'])) {                    
                    $number = count($_POST["branch_name18"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }


                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num18'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name18'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address18'][$i]);
                        $leader_name = mysqli_real_escape_string($conn, $_POST['leader_name18'][$i]);                      
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth18'][$i]);
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth18'][$i]);
                        
                        if (!empty($date_of_birth)) {
                            $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $type = mysqli_real_escape_string($conn, $_POST['type18'][$i]);
                        $who_established = mysqli_real_escape_string($conn, $_POST['who_established18'][$i]);
                        $goal_of_establishing = mysqli_real_escape_string($conn, $_POST['goal_of_establishing18'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes18'][$i]);

                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach18'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach18'][$i]);

                        $sql= "UPDATE `studies_828_town_new_military_places` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        leader_name='$leader_name', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        type='$type', 
                        who_established='$who_established', 
                        goal_of_establishing='$goal_of_establishing', 
                        notes='$notes', 
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: studies_828_town_new_military_places " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
            //////
            
            /// studies_828_town_made_by ///  

                if (!empty($_POST['brother_name19_new'])) {                    
                $number_new = count($_POST["brother_name19_new"]);
            } else {
                $number_new =mysqli_real_escape_string($conn, 0);
            }

            if ($number_new >= 1) {
                for ($i=0; $i<$number_new; $i++) {
                
                    $num = mysqli_real_escape_string($conn, $_POST['num19_new'][$i]);
                    $brother_name = mysqli_real_escape_string($conn, $_POST['brother_name19_new'][$i]);
                    $rank = mysqli_real_escape_string($conn, $_POST['rank19_new'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes19_new'][$i]);                      
                   
                
                    
                    $sql_new="INSERT INTO `studies_828_town_made_by`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `brother_name`, `rank`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$brother_name', '$rank', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                    if (mysqli_query($conn, $sql_new)){
                    }else{
                        echo "Error: studies_828_town_made_by" . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }                          

            if (!empty($_POST['brother_name19'])) {                    
                $number = count($_POST["brother_name19"]);
            } else {
                $number =mysqli_real_escape_string($conn, 0);
            }


            if ($number >= 1) {
                for ($i=0; $i<$number; $i++) {
                    $num = mysqli_real_escape_string($conn, $_POST['num19'][$i]);
                    $brother_name = mysqli_real_escape_string($conn, $_POST['brother_name19'][$i]);
                    $rank = mysqli_real_escape_string($conn, $_POST['rank19'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes19'][$i]); 

                    $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach19'][$i]);
                    $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach19'][$i]);

                    $sql= "UPDATE `studies_828_town_made_by` SET 
                    general_code='$general_code',
                    ketab_num=$ketab_num, 
                    ketab_date='$ketab_date',
                    num='$num', 
                    brother_name='$brother_name', 
                    rank='$rank', 
                    notes='$notes',
                    added_by='$added_by_old - $added_by', 
                    add_date=current_timestamp()
                    WHERE id = $id_attach";
                
                    if (mysqli_query($conn, $sql)){
                    }else{
                        echo "Error: studies_828_town_new_military_places " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }
            //////


           


            } else {
            echo "Error: sql_town " . "<br>" . mysqli_error($conn);
            exit;
        }
    }


    if ($type_code == 'G') { 

        $name= mysqli_real_escape_string($conn, $_POST['name']); 
        $lname= mysqli_real_escape_string($conn, $_POST['lname']); 
        $fname= mysqli_real_escape_string($conn, $_POST['fname']); 
        $mname= mysqli_real_escape_string($conn, $_POST['mname']); 
  

        $place_of_birth= mysqli_real_escape_string($conn, $_POST['place_of_birth']); 

        if (!empty($_POST['date_of_birth'])) {
            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
        } else {
            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
        }

        $nick_name= mysqli_real_escape_string($conn, $_POST['nick_name']); 
        $characteristics= mysqli_real_escape_string($conn, $_POST['characteristics']); 
        $marital_status= mysqli_real_escape_string($conn, $_POST['marital_status']); 
        $children_number= mysqli_real_escape_string($conn, $_POST['children_number']); 
        $wife_full_name= mysqli_real_escape_string($conn, $_POST['wife_full_name']); 
        $wife_place_of_birth= mysqli_real_escape_string($conn, $_POST['wife_place_of_birth']); 
        $job_before_revolution= mysqli_real_escape_string($conn, $_POST['job_before_revolution']); 
        $current_job= mysqli_real_escape_string($conn, $_POST['current_job']); 
        $academic_qualification= mysqli_real_escape_string($conn, $_POST['academic_qualification']); 
        $financial_status= mysqli_real_escape_string($conn, $_POST['financial_status']); 
        $reputation= mysqli_real_escape_string($conn, $_POST['reputation']); 
        $family_reputation= mysqli_real_escape_string($conn, $_POST['family_reputation']); 
        $khedma_elzamia= mysqli_real_escape_string($conn, $_POST['khedma_elzamia']); 
        $specialization= mysqli_real_escape_string($conn, $_POST['specialization']); 
        $previous_residence= mysqli_real_escape_string($conn, $_POST['previous_residence']); 
        $current_residence= mysqli_real_escape_string($conn, $_POST['current_residence']); 
        $mlk_or_agar= mysqli_real_escape_string($conn, $_POST['mlk_or_agar']); 
        $criminal_record= mysqli_real_escape_string($conn, $_POST['criminal_record']); 
        $eltzam_dene= mysqli_real_escape_string($conn, $_POST['eltzam_dene']); 
        $tawajoh_fekre= mysqli_real_escape_string($conn, $_POST['tawajoh_fekre']); 
        $aham_sefat_shakhsia= mysqli_real_escape_string($conn, $_POST['aham_sefat_shakhsia']); 
        $waselat_tawasol= mysqli_real_escape_string($conn, $_POST['waselat_tawasol']); 
        $faecbook_account= mysqli_real_escape_string($conn, $_POST['faecbook_account']); 
        $mawkef_mn_althawra= mysqli_real_escape_string($conn, $_POST['mawkef_mn_althawra']); 
        $work_with_factions= mysqli_real_escape_string($conn, $_POST['work_with_factions']); 
        $traveling_abroad= mysqli_real_escape_string($conn, $_POST['traveling_abroad']); 
        $relatives_with_regime= mysqli_real_escape_string($conn, $_POST['relatives_with_regime']); 
        $relatives_with_isis= mysqli_real_escape_string($conn, $_POST['relatives_with_isis']); 
        $relatives_with_factions= mysqli_real_escape_string($conn, $_POST['relatives_with_factions']); 
        $relatives_hts_arrested= mysqli_real_escape_string($conn, $_POST['relatives_hts_arrested']); 
        $friends_with_regime= mysqli_real_escape_string($conn, $_POST['friends_with_regime']); 
        
        $relatives_with_free_area= mysqli_real_escape_string($conn, $_POST['relatives_with_free_area']); 
        $mawkef_from_russia= mysqli_real_escape_string($conn, $_POST['mawkef_from_russia']); 
        $mawkef_from_iran= mysqli_real_escape_string($conn, $_POST['mawkef_from_iran']); 
        $recruitment_possibility= mysqli_real_escape_string($conn, $_POST['recruitment_possibility']); 
        $attempted_to_kill= mysqli_real_escape_string($conn, $_POST['attempted_to_kill']); 
        $target_vehicle= mysqli_real_escape_string($conn, $_POST['target_vehicle']); 
        $daily_routine= mysqli_real_escape_string($conn, $_POST['daily_routine']); 
        $weekly_routine= mysqli_real_escape_string($conn, $_POST['weekly_routine']); 
        $home_address= mysqli_real_escape_string($conn, $_POST['home_address']); 
        $close_support= mysqli_real_escape_string($conn, $_POST['close_support']); 
        $protected_home= mysqli_real_escape_string($conn, $_POST['protected_home']); 
        $cameras= mysqli_real_escape_string($conn, $_POST['cameras']); 
        $population= mysqli_real_escape_string($conn, $_POST['population']); 
        $how_can_be_reached= mysqli_real_escape_string($conn, $_POST['how_can_be_reached']); 
        $qabda= mysqli_real_escape_string($conn, $_POST['qabda']); 
        $bodyguards= mysqli_real_escape_string($conn, $_POST['bodyguards']); 
        $armed= mysqli_real_escape_string($conn, $_POST['armed']); 
        $killing_benefits= mysqli_real_escape_string($conn, $_POST['killing_benefits']); 
        $additional_information= mysqli_real_escape_string($conn, $_POST['additional_information']);  
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
       
    
    
      
            
       
    
      
        
       /*  if(!empty($_FILES['studies_attach']['name'])) { 
            include_once "inc/file_upload.php";
            $studies_attach = mysqli_real_escape_string($conn, $target_file);         
        }elseif($_GET['edit']=='1' && empty($_FILES['studies_attach']['name'])){
            $inserted_ketab_num = mysqli_real_escape_string($conn, $_POST['inserted_ketab_num']);
            $inserted_ketab_date = mysqli_real_escape_string($conn, $_POST['inserted_ketab_date']);
            $attach_fileUpload='1';
            include_once "inc/file_upload_edit.php";
            $studies_attach = mysqli_real_escape_string($conn, $_POST['inserted_attach']);
        }else{
            $studies_attach = mysqli_real_escape_string($conn, '');
        } */
    
        if ($_GET['edit']=='0') {
    
    
            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `lname`, `fname`, `mname`, `personal_image`, `place_of_birth`, `date_of_birth`, `nick_name`, `characteristics`, `marital_status`, `children_number`, `wife_full_name`, `wife_place_of_birth`, `job_before_revolution`, `current_job`, `academic_qualification`, `financial_status`, `reputation`, `family_reputation`, `khedma_elzamia`, `specialization`, `previous_residence`, `current_residence`, `mlk_or_agar`, `criminal_record`, `eltzam_dene`, `tawajoh_fekre`, `aham_sefat_shakhsia`, `waselat_tawasol`, `faecbook_account`, `mawkef_mn_althawra`, `work_with_factions`, `traveling_abroad`, `relatives_with_regime`, `relatives_with_isis`, `relatives_with_factions`, `relatives_hts_arrested`, `friends_with_regime`, `mawkef_from_russia`, `mawkef_from_iran`, `recruitment_possibility`, `attempted_to_kill`, `target_vehicle`, `daily_routine`, `weekly_routine`, `home_address`, `close_support`, `protected_home`, `cameras`, `population`, `how_can_be_reached`, `qabda`, `bodyguards`, `armed`, `killing_benefits`, `additional_information`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ('$ketab_num', '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$name', '$lname', '$fname', '$mname', '$personal_image', '$place_of_birth', '$date_of_birth', '$nick_name', '$characteristics', '$marital_status', '$children_number', '$wife_full_name', '$wife_place_of_birth', '$job_before_revolution', '$current_job', '$academic_qualification', '$financial_status', '$reputation', '$family_reputation', '$khedma_elzamia', '$specialization', '$previous_residence', '$current_residence', '$mlk_or_agar', '$criminal_record', '$eltzam_dene', '$tawajoh_fekre', '$aham_sefat_shakhsia', '$waselat_tawasol', '$faecbook_account', '$mawkef_mn_althawra', '$work_with_factions', '$traveling_abroad', '$relatives_with_regime', '$relatives_with_isis', '$relatives_with_factions', '$relatives_hts_arrested', '$friends_with_regime', '$mawkef_from_russia', '$mawkef_from_iran', '$recruitment_possibility', '$attempted_to_kill', '$target_vehicle', '$daily_routine', '$weekly_routine', '$home_address', '$close_support', '$protected_home', '$cameras', '$population', '$how_can_be_reached', '$qabda', '$bodyguards', '$armed', '$killing_benefits', '$additional_information', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }
    
        if ($_GET['edit']=='1') {
    
            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);
    
            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num=$type_num, 
            name='$name',  
            lname='$lname', 
            fname='$fname', 
            mname='$mname', 
            personal_image='$personal_image', 
            place_of_birth='$place_of_birth', 
            date_of_birth='$date_of_birth', 
            nick_name='$nick_name', 
            characteristics='$characteristics', 
            marital_status='$marital_status', 
            children_number='$children_number', 
            wife_full_name='$wife_full_name', 
            wife_place_of_birth='$wife_place_of_birth', 
            job_before_revolution='$job_before_revolution', 
            current_job='$current_job', 
            academic_qualification='$academic_qualification', 
            financial_status='$financial_status', 
            reputation='$reputation', 
            family_reputation='$family_reputation', 
            khedma_elzamia='$khedma_elzamia', 
            specialization='$specialization', 
            previous_residence='$previous_residence', 
            current_residence='$current_residence', 
            mlk_or_agar='$mlk_or_agar', 
            criminal_record='$criminal_record', 
            eltzam_dene='$eltzam_dene', 
            tawajoh_fekre='$tawajoh_fekre', 
            aham_sefat_shakhsia='$aham_sefat_shakhsia', 
            waselat_tawasol='$waselat_tawasol', 
            faecbook_account='$faecbook_account', 
            mawkef_mn_althawra='$mawkef_mn_althawra', 
            work_with_factions='$work_with_factions', 
            traveling_abroad='$traveling_abroad', 
            relatives_with_regime='$relatives_with_regime', 
            relatives_with_isis='$relatives_with_isis', 
            relatives_with_factions='$relatives_with_factions', 
            relatives_hts_arrested='$relatives_hts_arrested', 
            friends_with_regime='$friends_with_regime', 
            mawkef_from_russia='$mawkef_from_russia', 
            mawkef_from_iran='$mawkef_from_iran', 
            recruitment_possibility='$recruitment_possibility', 
            attempted_to_kill='$attempted_to_kill', 
            target_vehicle='$target_vehicle', 
            daily_routine='$daily_routine', 
            weekly_routine='$weekly_routine', 
            home_address='$home_address', 
            close_support='$close_support', 
            protected_home='$protected_home', 
            cameras='$cameras', 
            population='$population', 
            how_can_be_reached='$how_can_be_reached', 
            qabda='$qabda', 
            bodyguards='$bodyguards', 
            armed='$armed', 
            killing_benefits='$killing_benefits', 
            additional_information='$additional_information',
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }
    
    
        if (mysqli_query($conn, $sql)) {  
        }else{
            echo "Error: studies_828_goal" . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'S') {

       
       
       

        $site_related_to = mysqli_real_escape_string($conn, $_POST['site_related_to']);
        if(!empty($site_related_to) && $site_related_to!=='غير ذلك'){
            $site_related_to = mysqli_real_escape_string($conn, $_POST['site_related_to']);
        }else{
            $site_related_to = mysqli_real_escape_string($conn, $_POST['site_related_to_other']);
        }

        $site_type = mysqli_real_escape_string($conn, $_POST['site_type']);
        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);
        $site_address = mysqli_real_escape_string($conn, $_POST['site_address']);

        
        $roads_to_center = mysqli_real_escape_string($conn, $_POST['roads_to_center']);
        $related_to = mysqli_real_escape_string($conn, $_POST['related_to']);
        $site_missions = mysqli_real_escape_string($conn, $_POST['site_missions']);
        $leader_rank_and_name = mysqli_real_escape_string($conn, $_POST['leader_rank_and_name']);
        $security_level = mysqli_real_escape_string($conn, $_POST['security_level']);
        $prison = mysqli_real_escape_string($conn, $_POST['prison']);
        $ports_number = mysqli_real_escape_string($conn, $_POST['ports_number']);
        $mahares_details = mysqli_real_escape_string($conn, $_POST['mahares_details']);
        $close_military_sites = mysqli_real_escape_string($conn, $_POST['close_military_sites']);
        $support_found = mysqli_real_escape_string($conn, $_POST['support_found']);
        $support_time_to_arrive = mysqli_real_escape_string($conn, $_POST['support_time_to_arrive']);
        $protection = mysqli_real_escape_string($conn, $_POST['protection']);
        $taftesh_type = mysqli_real_escape_string($conn, $_POST['taftesh_type']);
        $how_to_enter = mysqli_real_escape_string($conn, $_POST['how_to_enter']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $alarm_system = mysqli_real_escape_string($conn, $_POST['alarm_system']);
        $vehicles_that_enter = mysqli_real_escape_string($conn, $_POST['vehicles_that_enter']);
        $population_around_information = mysqli_real_escape_string($conn, $_POST['population_around_information']);
        $distance_to_people = mysqli_real_escape_string($conn, $_POST['distance_to_people']);
        $food_source = mysqli_real_escape_string($conn, $_POST['food_source']);
        $additional_details = mysqli_real_escape_string($conn, $_POST['additional_details']);



       


       

       
        
       
       

       

        
       

    
        if ($_GET['edit']=='0') {
    
    
            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `site_related_to`, `site_type`, `site_name`, `site_address`, `longitude`, `roads_to_center`, `related_to`, `site_missions`, `leader_rank_and_name`, `security_level`, `prison`, `ports_number`, `mahares_details`, `close_military_sites`, `support_found`, `support_time_to_arrive`, `protection`, `taftesh_type`, `how_to_enter`, `cameras`, `alarm_system`, `vehicles_that_enter`, `population_around_information`, `distance_to_people`, `food_source`, `additional_details`, `site_map`, `site_photos`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ('$ketab_num', '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$site_related_to', '$site_type','$place_name', '$site_address', '$longitude', '$roads_to_center', '$related_to', '$site_missions', '$leader_rank_and_name', '$security_level', '$prison', '$ports_number', '$mahares_details', '$close_military_sites', '$support_found', '$support_time_to_arrive', '$protection', '$taftesh_type', '$how_to_enter', '$cameras', '$alarm_system', '$vehicles_that_enter', '$population_around_information', '$distance_to_people', '$food_source', '$additional_details', '$site_map', '$site_photos', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }
    
        if ($_GET['edit']=='1') {
    
            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);
    
            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num = $type_num, 
            site_type='$site_type',
            `site_related_to`='$site_related_to',
            site_name='$place_name',
            site_address='$site_address',
            longitude='$longitude',
            roads_to_center='$roads_to_center',
            related_to='$related_to',
            site_missions='$site_missions',
            leader_rank_and_name='$leader_rank_and_name',
            security_level='$security_level',
            prison='$prison',
            ports_number='$ports_number',
            mahares_details='$mahares_details',
            close_military_sites='$close_military_sites',
            support_found='$support_found',
            support_time_to_arrive='$support_time_to_arrive',
            protection='$protection',
            taftesh_type='$taftesh_type',
            how_to_enter='$how_to_enter',
            cameras='$cameras',
            alarm_system='$alarm_system',
            vehicles_that_enter='$vehicles_that_enter',
            population_around_information='$population_around_information',
            distance_to_people='$distance_to_people',
            food_source='$food_source',
            additional_details='$additional_details',
            site_map='$site_map',
            site_photos='$site_photos',
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }
    
    
        if (mysqli_query($conn, $sql)) {  

            if (!empty($_POST['name_new'])) {                    
                $number_new = count($_POST["name_new"]);
            } else {
                $number_new =mysqli_real_escape_string($conn, 0);
            }

            if ($number_new >= 1) {
                for ($i=0; $i<$number_new; $i++) {
                
                    $num = mysqli_real_escape_string($conn, $_POST['num_new'][$i]);
                    $name = mysqli_real_escape_string($conn, $_POST['name_new'][$i]);
                    $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth_new'][$i]);
                    if (!empty($_POST['date_of_birth'][$i])) {
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth'][$i]);
                    } else {
                        $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                    }
                    $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name_new'][$i]);
                    $rank = mysqli_real_escape_string($conn, $_POST['rank_new'][$i]);
                    $current_residence = mysqli_real_escape_string($conn, $_POST['current_residence_new'][$i]);
                    $current_job = mysqli_real_escape_string($conn, $_POST['current_job_new'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes_new'][$i]);                      
                   
                
                    
                    $sql_new="INSERT INTO `studies_828_military_site_study_attachment`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `name`, `place_of_birth`, `date_of_birth`, `nick_name`, `rank`, `current_residence`, `current_job`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$place_of_birth', '$date_of_birth', '$nick_name', '$rank', '$current_residence', '$current_job', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                    if (mysqli_query($conn, $sql_new)){
                    }else{
                        echo "Error: studies_828_military_site_study_attachment" . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }                          

            if (!empty($_POST['name'])) {                    
                $number = count($_POST["name"]);
            } else {
                $number =mysqli_real_escape_string($conn, 0);
            }


            if ($number >= 1) {
                for ($i=0; $i<$number; $i++) {
                    $num = mysqli_real_escape_string($conn, $_POST['num'][$i]);
                    $name = mysqli_real_escape_string($conn, $_POST['name'][$i]);
                    $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth'][$i]);
                    if (!empty($_POST['date_of_birth'][$i])) {
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth'][$i]);
                    } else {
                        $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                    }
                    $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name'][$i]);
                    $rank = mysqli_real_escape_string($conn, $_POST['rank'][$i]);
                    $current_residence = mysqli_real_escape_string($conn, $_POST['current_residence'][$i]);
                    $current_job = mysqli_real_escape_string($conn, $_POST['current_job'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes'][$i]);   

                    $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                    $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                    $sql= "UPDATE `studies_828_military_site_study_attachment` SET 
                    general_code='$general_code',
                    ketab_num=$ketab_num, 
                    ketab_date='$ketab_date',
                    name='$name', 
                    place_of_birth='$place_of_birth', 
                    date_of_birth='$date_of_birth', 
                    nick_name='$nick_name', 
                    rank='$rank', 
                    current_residence='$current_residence', 
                    current_job='$current_job',
                    notes='$notes',
                    added_by='$added_by_old - $added_by', 
                    add_date=current_timestamp()
                    WHERE id = $id_attach";
                
                    if (mysqli_query($conn, $sql)){
                    }else{
                        echo "Error: studies_828_military_site_study_attachment " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }
            //////

        }else{
            echo "Error: studies_828_military_site_study" . "<br>" . mysqli_error($conn);
            exit;
        }
    }
    
    if ($type_code == 'SCE') {

        
       
       

        $center_name = mysqli_real_escape_string($conn, $_POST['center_name']);
        $center_address = mysqli_real_escape_string($conn, $_POST['center_address']);
  
        $roads_to_cente = mysqli_real_escape_string($conn, $_POST['roads_to_cente']);
        $related_to = mysqli_real_escape_string($conn, $_POST['related_to']);
        $center_missions = mysqli_real_escape_string($conn, $_POST['center_missions']);
        $leader_rank_and_name = mysqli_real_escape_string($conn, $_POST['leader_rank_and_name']);
        $security_level = mysqli_real_escape_string($conn, $_POST['security_level']);
        $prison = mysqli_real_escape_string($conn, $_POST['prison']);
        $ports_number = mysqli_real_escape_string($conn, $_POST['ports_number']);
        $mahares_details = mysqli_real_escape_string($conn, $_POST['mahares_details']);
        $close_military_centers = mysqli_real_escape_string($conn, $_POST['close_military_centers']);
        $support_found = mysqli_real_escape_string($conn, $_POST['support_found']);
        $support_time_to_arrive = mysqli_real_escape_string($conn, $_POST['support_time_to_arrive']);
        $protection = mysqli_real_escape_string($conn, $_POST['protection']);
        $taftesh_type = mysqli_real_escape_string($conn, $_POST['taftesh_type']);
        $how_to_enter = mysqli_real_escape_string($conn, $_POST['how_to_enter']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $alarm_system = mysqli_real_escape_string($conn, $_POST['alarm_system']);
        $vehicles_that_enter = mysqli_real_escape_string($conn, $_POST['vehicles_that_enter']);
        $population_around_information = mysqli_real_escape_string($conn, $_POST['population_around_information']);
        $distance_to_people = mysqli_real_escape_string($conn, $_POST['distance_to_people']);
        $food_source = mysqli_real_escape_string($conn, $_POST['food_source']);
        $additional_details = mysqli_real_escape_string($conn, $_POST['additional_details']);
       
       
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
        $inserted_jeha = mysqli_real_escape_string($conn, $_POST['inserted_jeha']);
    
    
      
       

       

        
       

    
        if ($_GET['edit']=='0') {
    
    
            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `center_name`, `center_address`, `longitude`, `roads_to_cente`, `related_to`, `center_missions`, `leader_rank_and_name`, `security_level`, `prison`, `ports_number`, `mahares_details`, `close_military_centers`, `support_found`, `support_time_to_arrive`, `protection`, `taftesh_type`, `how_to_enter`, `cameras`, `alarm_system`, `vehicles_that_enter`, `population_around_information`, `distance_to_people`, `food_source`, `additional_details`, `center_map`, `center_photos`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ('$ketab_num', '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$center_name', '$center_address', '$longitude', '$roads_to_cente', '$related_to', '$center_missions', '$leader_rank_and_name', '$security_level', '$prison', '$ports_number', '$mahares_details', '$close_military_centers', '$support_found', '$support_time_to_arrive', '$protection', '$taftesh_type', '$how_to_enter', '$cameras', '$alarm_system', '$vehicles_that_enter', '$population_around_information', '$distance_to_people', '$food_source', '$additional_details', '$center_map', '$center_photos', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }
    
        if ($_GET['edit']=='1') {
    
            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);
    
            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num=$type_num, 
            center_name='$center_name', 
            center_address='$center_address', 
            longitude='$longitude',             
            roads_to_cente='$roads_to_cente', 
            related_to='$related_to', 
            center_missions='$center_missions', 
            leader_rank_and_name='$leader_rank_and_name', 
            security_level='$security_level',
            prison='$prison',
            ports_number='$ports_number',
            mahares_details='$mahares_details',
            close_military_centers='$close_military_centers',
            support_found='$support_found',
            support_time_to_arrive='$support_time_to_arrive',
            protection='$protection',
            taftesh_type='$taftesh_type',
            how_to_enter='$how_to_enter',
            cameras='$cameras',
            alarm_system='$alarm_system',
            vehicles_that_enter='$vehicles_that_enter',
            population_around_information='$population_around_information',
            distance_to_people='$distance_to_people',
            food_source='$food_source',
            additional_details='$additional_details',
            center_map='$center_map',
            center_photos='$center_photos',
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }
    
    
        if (mysqli_query($conn, $sql)) {  

            if (!empty($_POST['name_new'])) {                    
                $number_new = count($_POST["name_new"]);
            } else {
                $number_new =mysqli_real_escape_string($conn, 0);
            }

            if ($number_new >= 1) {
                for ($i=0; $i<$number_new; $i++) {
                
                    $num = mysqli_real_escape_string($conn, $_POST['num_new'][$i]);
                    $name = mysqli_real_escape_string($conn, $_POST['name_new'][$i]);
                    $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth_new'][$i]);
                    if (!empty($_POST['date_of_birth'][$i])) {
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth'][$i]);
                    } else {
                        $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                    }
                    $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name_new'][$i]);
                    $rank = mysqli_real_escape_string($conn, $_POST['rank_new'][$i]);
                    $current_residence = mysqli_real_escape_string($conn, $_POST['current_residence_new'][$i]);
                    $current_job = mysqli_real_escape_string($conn, $_POST['current_job_new'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes_new'][$i]);                      
                   
                
                    
                    $sql_new="INSERT INTO `studies_828_security_center_study_attachment`(`ketab_num`, `ketab_date`, `general_code`,  `num`, `name`, `place_of_birth`, `date_of_birth`, `nick_name`, `rank`, `current_residence`, `current_job`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$place_of_birth', '$date_of_birth', '$nick_name', '$rank', '$current_residence', '$current_job', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                    if (mysqli_query($conn, $sql_new)){
                    }else{
                        echo "Error: studies_828_security_center_study_attachment" . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }                          

            if (!empty($_POST['name'])) {                    
                $number = count($_POST["name"]);
            } else {
                $number =mysqli_real_escape_string($conn, 0);
            }


            if ($number >= 1) {
                for ($i=0; $i<$number; $i++) {
                    $num = mysqli_real_escape_string($conn, $_POST['num'][$i]);
                    $name = mysqli_real_escape_string($conn, $_POST['name'][$i]);
                    $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth'][$i]);
                    if (!empty($_POST['date_of_birth'][$i])) {
                        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth'][$i]);
                    } else {
                        $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                    }
                    $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name'][$i]);
                    $rank = mysqli_real_escape_string($conn, $_POST['rank'][$i]);
                    $current_residence = mysqli_real_escape_string($conn, $_POST['current_residence'][$i]);
                    $current_job = mysqli_real_escape_string($conn, $_POST['current_job'][$i]);
                    $notes = mysqli_real_escape_string($conn, $_POST['notes'][$i]);   

                    $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach'][$i]);
                    $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach'][$i]);

                    $sql= "UPDATE `studies_828_security_center_study_attachment` SET 
                    general_code='$general_code',
                    ketab_num=$ketab_num, 
                    ketab_date='$ketab_date',
                    name='$name', 
                    place_of_birth='$place_of_birth', 
                    date_of_birth='$date_of_birth', 
                    nick_name='$nick_name', 
                    rank='$rank', 
                    current_residence='$current_residence', 
                    current_job='$current_job',
                    notes='$notes',
                    added_by='$added_by_old - $added_by', 
                    add_date=current_timestamp()
                    WHERE id = $id_attach";
                
                    if (mysqli_query($conn, $sql)){
                    }else{
                        echo "Error: studies_828_security_center_study_attachment " . "<br>" . mysqli_error($conn);
                        exit;
                    }
                }
            }
            //////

        }else{
            echo "Error: studies_828_security_center_study" . "<br>" . mysqli_error($conn);
            exit;
        }
    }


    if ($type_code == 'CP') {

        
       
       

        $place_name = mysqli_real_escape_string($conn, $_POST['place_name']);
        $checkpoint_address = mysqli_real_escape_string($conn, $_POST['checkpoint_address']);
       
        $roads_to_checkpoint = mysqli_real_escape_string($conn, $_POST['roads_to_checkpoint']);
        $related_to = mysqli_real_escape_string($conn, $_POST['related_to']);
        $checkpoint_type = mysqli_real_escape_string($conn, $_POST['checkpoint_type']);
        $checkpoint_mission = mysqli_real_escape_string($conn, $_POST['checkpoint_mission']);
        $leader_rank_and_name = mysqli_real_escape_string($conn, $_POST['leader_rank_and_name']);
        $checkpoint_soldier_number = mysqli_real_escape_string($conn, $_POST['checkpoint_soldier_number']);
        $taftesh_soldier_number = mysqli_real_escape_string($conn, $_POST['taftesh_soldier_number']);
        $mudat_almunawbah = mysqli_real_escape_string($conn, $_POST['mudat_almunawbah']);
        $dealing_manner = mysqli_real_escape_string($conn, $_POST['dealing_manner']);
        $military_line = mysqli_real_escape_string($conn, $_POST['military_line']);
        $awqat_altaftesh = mysqli_real_escape_string($conn, $_POST['awqat_altaftesh']);
        $taftesh_type = mysqli_real_escape_string($conn, $_POST['taftesh_type']);
        $cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        $soldiers_mabeet = mysqli_real_escape_string($conn, $_POST['soldiers_mabeet']);
        $checkpoint_weapons = mysqli_real_escape_string($conn, $_POST['checkpoint_weapons']);
        $checkpoint_support = mysqli_real_escape_string($conn, $_POST['checkpoint_support']);
        $support_time_to_arrive = mysqli_real_escape_string($conn, $_POST['support_time_to_arrive']);
        $checkpoint_at_night = mysqli_real_escape_string($conn, $_POST['checkpoint_at_night']);
        $distance_to_people = mysqli_real_escape_string($conn, $_POST['distance_to_people']);
        $food_source = mysqli_real_escape_string($conn, $_POST['food_source']);
        $recruitment_possibility = mysqli_real_escape_string($conn, $_POST['recruitment_possibility']);
        $vehicles_types = mysqli_real_escape_string($conn, $_POST['vehicles_types']);       
        $communication_manner = mysqli_real_escape_string($conn, $_POST['communication_manner']);
        $prison = mysqli_real_escape_string($conn, $_POST['prison']);
        $additional_details = mysqli_real_escape_string($conn, $_POST['additional_details']);
      

       
       
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
        $inserted_jeha = mysqli_real_escape_string($conn, $_POST['inserted_jeha']);
    
    
      
        

        


        if ($_GET['edit']=='0') {
    
    
            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `checkpoint_name`, `checkpoint_address`, `longitude`, `roads_to_checkpoint`, `related_to`, `checkpoint_type`, `checkpoint_mission`, `leader_rank_and_name`, `checkpoint_soldier_number`, `taftesh_soldier_number`, `mudat_almunawbah`, `dealing_manner`, `military_line`, `awqat_altaftesh`, `taftesh_type`, `cameras`, `soldiers_mabeet`, `checkpoint_weapons`, `checkpoint_support`, `support_time_to_arrive`, `checkpoint_at_night`, `distance_to_people`, `food_source`, `recruitment_possibility`, `vehicles_types`, `communication_manner`, `prison`, `additional_details`, `checkpoint_map`, `checkpoint_photos`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$checkpoint_address', '$longitude', '$roads_to_checkpoint', '$related_to', '$checkpoint_type', '$checkpoint_mission', '$leader_rank_and_name', '$checkpoint_soldier_number', '$taftesh_soldier_number', '$mudat_almunawbah', '$dealing_manner', '$military_line', '$awqat_altaftesh', '$taftesh_type', '$cameras', '$soldiers_mabeet', '$checkpoint_weapons', '$checkpoint_support', '$support_time_to_arrive', '$checkpoint_at_night', '$distance_to_people', '$food_source', '$recruitment_possibility', '$vehicles_types', '$communication_manner', '$prison', '$additional_details', '$checkpoint_map', '$checkpoint_photos', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
        }
    
        if ($_GET['edit']=='1') {
    
            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);
    
            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num=$type_num, 
            checkpoint_name='$place_name',
            checkpoint_address='$checkpoint_address',
            longitude='$longitude',           
            roads_to_checkpoint='$roads_to_checkpoint',
            related_to='$related_to',
            checkpoint_type='$checkpoint_type',
            checkpoint_mission='$checkpoint_mission',
            leader_rank_and_name='$leader_rank_and_name',
            checkpoint_soldier_number='$checkpoint_soldier_number',
            taftesh_soldier_number='$taftesh_soldier_number',
            mudat_almunawbah='$mudat_almunawbah',
            dealing_manner='$dealing_manner',
            military_line='$military_line',
            awqat_altaftesh='$awqat_altaftesh',
            taftesh_type='$taftesh_type',
            cameras='$cameras',
            soldiers_mabeet='$soldiers_mabeet',
            checkpoint_weapons='$checkpoint_weapons',
            checkpoint_support='$checkpoint_support',
            support_time_to_arrive='$support_time_to_arrive',
            checkpoint_at_night='$checkpoint_at_night',
            distance_to_people='$distance_to_people',
            food_source='$food_source',
            recruitment_possibility='$recruitment_possibility',
            vehicles_types='$vehicles_types',
            communication_manner='$communication_manner',
            prison='$prison',
            additional_details='$additional_details',
            checkpoint_map='$checkpoint_map',
            checkpoint_photos='$checkpoint_photos',
            jeha='$inserted_jeha', 
            details_type='$details_type', 
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }
    
    
        if (mysqli_query($conn, $sql)) {  
        }else{
            echo "Error: studies_828_checkpoint_study" . "<br>" . mysqli_error($conn);
            exit;
        }
    }


    if ($type_code == 'SPE') {

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $mname = mysqli_real_escape_string($conn, $_POST['mname']);
        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth']);
        if (!empty($_POST['date_of_birth'])) {
            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
        } else {
            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
        }

        $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name']);
        $characteristics = mysqli_real_escape_string($conn, $_POST['characteristics']);
        $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
        $children_number = mysqli_real_escape_string($conn, $_POST['children_number']);
        $wife_full_name = mysqli_real_escape_string($conn, $_POST['wife_full_name']);
        $wife_place_of_birth = mysqli_real_escape_string($conn, $_POST['wife_place_of_birth']);
        $job_before_revolution = mysqli_real_escape_string($conn, $_POST['job_before_revolution']);
        $current_job = mysqli_real_escape_string($conn, $_POST['current_job']);
        $academic_qualification = mysqli_real_escape_string($conn, $_POST['academic_qualification']);
        $financial_status = mysqli_real_escape_string($conn, $_POST['financial_status']);
        $reputation = mysqli_real_escape_string($conn, $_POST['reputation']);
        $family_reputation = mysqli_real_escape_string($conn, $_POST['family_reputation']);
        $khedma_elzamia = mysqli_real_escape_string($conn, $_POST['khedma_elzamia']);
        $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
        $previous_residence = mysqli_real_escape_string($conn, $_POST['previous_residence']);
        $current_residence = mysqli_real_escape_string($conn, $_POST['current_residence']);
        $mlk_or_agar = mysqli_real_escape_string($conn, $_POST['mlk_or_agar']);
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        $eltzam_dene = mysqli_real_escape_string($conn, $_POST['eltzam_dene']);
        $tawajoh_fekre = mysqli_real_escape_string($conn, $_POST['tawajoh_fekre']);
        $aham_sefat_shakhsia = mysqli_real_escape_string($conn, $_POST['aham_sefat_shakhsia']);
        $waselat_tawasol = mysqli_real_escape_string($conn, $_POST['waselat_tawasol']);
        $mawkef_mn_althawra = mysqli_real_escape_string($conn, $_POST['mawkef_mn_althawra']);
        $work_with_factions = mysqli_real_escape_string($conn, $_POST['work_with_factions']);
        $traveling_abroad = mysqli_real_escape_string($conn, $_POST['traveling_abroad']);
        $relatives_with_regime = mysqli_real_escape_string($conn, $_POST['relatives_with_regime']);
        
        $relatives_with_isis = mysqli_real_escape_string($conn, '');
        $relatives_with_factions = mysqli_real_escape_string($conn, $_POST['relatives_with_factions']);
        $relatives_hts_arrested = mysqli_real_escape_string($conn, $_POST['relatives_hts_arrested']);
        $close_friends = mysqli_real_escape_string($conn, $_POST['close_friends']);
        $mogaz_an_haeat = mysqli_real_escape_string($conn, $_POST['mogaz_an_haeat']);
        $additional_details = mysqli_real_escape_string($conn, $_POST['additional_details']);

        $friends_with_regime = mysqli_real_escape_string($conn, $_POST['friends_with_regime']);
        $relatives_with_free_area = mysqli_real_escape_string($conn, $_POST['relatives_with_free_area']);
        $free_area_owns = mysqli_real_escape_string($conn, $_POST['free_area_owns']);
        $study_request_jeha = mysqli_real_escape_string($conn, $_POST['study_request_jeha']);
        $study_reason = mysqli_real_escape_string($conn, $_POST['study_reason']);
        $study_masdar = mysqli_real_escape_string($conn, $_POST['study_masdar']);
        if (!empty($_POST['study_masdar_num'])) {
            $study_masdar_num = mysqli_real_escape_string($conn, $_POST['study_masdar_num']);
        } else {
            $study_masdar_num =mysqli_real_escape_string($conn, 0);
        }
        $study_organizer = mysqli_real_escape_string($conn, $_POST['study_organizer']);
        
        $mawkef_from_iran = mysqli_real_escape_string($conn, $_POST['mawkef_from_iran']);
        $mawkef_from_russia = mysqli_real_escape_string($conn, $_POST['mawkef_from_russia']);
        $recruitment_possibility= mysqli_real_escape_string($conn, $_POST['recruitment_possibility']); 

        $study_result = mysqli_real_escape_string($conn, $_POST['study_result']);
       if(!empty($_POST['negative_reason'])){
              @$negative_reason = mysqli_real_escape_string($conn, $_POST['negative_reason']);
            }else{
              @$negative_reason = mysqli_real_escape_string($conn, $_POST['negative_reason1']);
            }
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
        $inserted_jeha = mysqli_real_escape_string($conn, $_POST['inserted_jeha']);

        if ($_GET['edit']=='0') {   

            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `name`, `lname`, `fname`, `mname`, `personal_image`, `place_of_birth`, `date_of_birth`, `nick_name`, `characteristics`, `marital_status`, `children_number`, `wife_full_name`, `wife_place_of_birth`, `job_before_revolution`, `current_job`, `academic_qualification`, `financial_status`, `reputation`, `family_reputation`, `khedma_elzamia`, `specialization`, `previous_residence`, `current_residence`, `mlk_or_agar`, `criminal_record`, `eltzam_dene`, `tawajoh_fekre`, `aham_sefat_shakhsia`, `waselat_tawasol`, `mawkef_mn_althawra`, `work_with_factions`, `traveling_abroad`, `relatives_with_regime`, `relatives_with_isis`, `relatives_with_factions`, `relatives_hts_arrested`, `close_friends`, `mogaz_an_haeat`, `additional_details`, `jeha`, `details_type`, `added_by`, `add_date`,`friends_with_regime`, `relatives_with_free_area`, `free_area_owns`,            `study_request_jeha`, `study_reason`, `study_masdar`, `study_masdar_num`, `study_organizer`,          `study_result`, `negative_reason`,`mawkef_from_iran`,`mawkef_from_russia`,`recruitment_possibility`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$name', '$lname', '$fname', '$mname', '$personal_image', '$place_of_birth', '$date_of_birth', '$nick_name', '$characteristics', '$marital_status', '$children_number', '$wife_full_name', '$wife_place_of_birth', '$job_before_revolution', '$current_job', '$academic_qualification', '$financial_status', '$reputation', '$family_reputation', '$khedma_elzamia', '$specialization', '$previous_residence', '$current_residence', '$mlk_or_agar', '$criminal_record', '$eltzam_dene', '$tawajoh_fekre', '$aham_sefat_shakhsia', '$waselat_tawasol', '$mawkef_mn_althawra', '$work_with_factions', '$traveling_abroad', '$relatives_with_regime', '$relatives_with_isis', '$relatives_with_factions', '$relatives_hts_arrested', '$close_friends', '$mogaz_an_haeat', '$additional_details', '$jeha_profile', '$details_type', '$added_by', current_timestamp(),'$friends_with_regime', '$relatives_with_free_area', '$free_area_owns', '$study_request_jeha','$study_reason', '$study_masdar', '$study_masdar_num', '$study_organizer', '$study_result', '$negative_reason', '$mawkef_from_iran','$mawkef_from_russia','$recruitment_possibility' )";
        }
    
        if ($_GET['edit']=='1') {
    
            $id=$_GET['id'];
           
            $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old']);
    
            $sql= "UPDATE `$table_name` SET 
            ketab_num=$ketab_num, 
            ketab_date='$ketab_date',           
            general_code='$general_code', 
            area_code='$area_code', 
            city_code='$city_code', 
            type_code='$type_code', 
            type_num=$type_num, 
            name = '$name', 
            lname = '$lname',
            fname = '$fname',
            mname = '$mname',
            personal_image= '$personal_image',
            place_of_birth = '$place_of_birth',
            date_of_birth= '$date_of_birth',
            nick_name= '$nick_name',
            characteristics= '$characteristics',
            marital_status= '$marital_status',
            children_number = '$children_number',
            wife_full_name= '$wife_full_name',
            wife_place_of_birth = '$wife_place_of_birth',
            job_before_revolution= '$job_before_revolution',
            current_job= '$current_job',
            academic_qualification = '$academic_qualification',
            financial_status = '$financial_status',
            reputation= '$reputation',
            family_reputation = '$family_reputation',
            khedma_elzamia = '$khedma_elzamia',
            specialization= '$specialization',
            previous_residence  = '$previous_residence',
            current_residence = '$current_residence',
            mlk_or_agar = '$mlk_or_agar',
            criminal_record = '$criminal_record',
            eltzam_dene = '$eltzam_dene',
            tawajoh_fekre = '$tawajoh_fekre',
            aham_sefat_shakhsia = '$aham_sefat_shakhsia',
            waselat_tawasol = '$waselat_tawasol',
            mawkef_mn_althawra  = '$mawkef_mn_althawra',
            work_with_factions= '$work_with_factions',
            traveling_abroad  = '$traveling_abroad',
            relatives_with_regime = '$relatives_with_regime',
            relatives_with_free_area='$relatives_with_free_area',
            relatives_with_isis = '$relatives_with_isis',
            relatives_with_factions = '$relatives_with_factions',
            relatives_hts_arrested = '$relatives_hts_arrested',
            close_friends  = '$close_friends',
            mogaz_an_haeat = '$mogaz_an_haeat',
            additional_details = '$additional_details',
            jeha = '$inserted_jeha', 
            details_type = '$details_type', 
            added_by = '$added_by_old - $added_by', 
            add_date = current_timestamp(),
            friends_with_regime = '$friends_with_regime', 
            relatives_with_free_area = '$relatives_with_free_area',
            free_area_owns = '$free_area_owns',
            study_request_jeha = '$study_request_jeha',
            study_reason = '$study_reason',
            study_masdar = '$study_masdar',
            study_masdar_num = '$study_masdar_num',
            study_organizer = '$study_organizer',
            study_result = '$study_result',
            negative_reason = '$negative_reason',
            mawkef_from_iran='$mawkef_from_iran',
            mawkef_from_russia='$mawkef_from_russia',
            recruitment_possibility='$recruitment_possibility'
            WHERE id = $id";
        }
    
    
        if (mysqli_query($conn, $sql)) {  
        }else{
            echo "Error: studies_828_personal_security_study" . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($_GET['edit']=='0') {
        $type_num++;
        $type_num = sprintf("%05d", $type_num);
        $general_code = $area_code.'_'.$city_code.'_'.$type_code.'_'.$type_num;        
        $_SESSION['general_code'] = $general_code;
        
        header("Location: studies_828.php?details_type=".$details_type."&type_code=".$type_code."&type_num=".$type_num."&edit=0&add=true");
        exit;
    }
    if ($_GET['edit']=='1') {
        //header("Location: studies_828.php?details_type=".$details_type."&type_code=".$type_code."&id=".$id."&type_num=".$type_num."&edit=1&edit_process=true");
        echo '<script> alert ("تم التعديل بنجاح");</script>';
        echo '<script>history.go(-1);</script>';
        exit;
    }

///////



}
?>

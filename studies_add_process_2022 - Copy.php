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

    //include_once "studies_type_code.php";

    $table_name= "studies_2022";
    $studies_2022_attachments= "studies_2022_attachments";

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

    
        @$place_name = mysqli_real_escape_string($conn, $_POST['place_name']);
        @$place_address = mysqli_real_escape_string($conn, $_POST['place_address']);
        @$name = mysqli_real_escape_string($conn, $_POST['name']);
        @$fname = mysqli_real_escape_string($conn, $_POST['fname']);
        @$lname = mysqli_real_escape_string($conn, $_POST['lname']);
        @$mname = mysqli_real_escape_string($conn, $_POST['mname']);
        @$personal_code = mysqli_real_escape_string($conn, $_POST['personal_code']);
        @$socialmedia  = mysqli_real_escape_string($conn, $_POST['socialmedia']);
        if (!empty($_POST['start_working'])) {
            @$start_working = mysqli_real_escape_string($conn, $_POST['start_working']);
        }else {
            @$start_working =mysqli_real_escape_string($conn, '0000-00-00');
        }
        @$cameras = mysqli_real_escape_string($conn, $_POST['cameras']);
        @$license = mysqli_real_escape_string($conn, $_POST['license']);
        @$cooperation = mysqli_real_escape_string($conn, $_POST['cooperation']);
        @$criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);
        @$cooperation_with_hts = mysqli_real_escape_string($conn, $_POST['cooperation_with_hts']);
        @$cooperation_before = mysqli_real_escape_string($conn, $_POST['cooperation_before']);
        @$suspicious_activity = mysqli_real_escape_string($conn, $_POST['suspicious_activity']);
        @$additional_information = mysqli_real_escape_string($conn, $_POST['additional_information']);
        $result = mysqli_real_escape_string($conn, $_POST['result']);
        $suggestion = mysqli_real_escape_string($conn, $_POST['suggestion']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $study_preparer = mysqli_real_escape_string($conn, $_POST['study_preparer']);

        @$apparent_work = mysqli_real_escape_string($conn, $_POST['apparent_work']);

        @$ownership = mysqli_real_escape_string($conn, $_POST['ownership']);
        @$capital= mysqli_real_escape_string($conn, $_POST['capital']);
     
        $added_by = mysqli_real_escape_string($conn,  $_SESSION["user"]);
        $added_by_old = mysqli_real_escape_string($conn,  $_POST["added_by_old"]);
        

        



    if($_GET['edit'] == '0'){
        //$table_name   = $_SESSION['table_name'];
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

        if(!empty($_FILES["studies_attach1"]["name"])){           
            $fileUpload[] = 'studies_attach1';
        }else{
            $studies_attach1 = '';
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
            $id = $row_s_id['id']+1;
          }else { $id = 1;}
          
         
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
        if(!empty($_FILES["studies_attach1"]["name"])){
            $fileUpload[] = 'studies_attach1';
        }
        if(!empty($_FILES["studies_attach"]["name"])){
            $fileUpload[] = 'studies_attach';
        } 
       
          
          $num=$ketab_num;
          $date=$ketab_date;

          $num=$ketab_num;
          $date=$ketab_date;  
          
          $sql_s_id = "SELECT `id` FROM `$table_name` where `id`=(SELECT max(`id`) FROM `$table_name` )";
          $result_s_id = mysqli_query($conn, $sql_s_id);
          $row_s_id = mysqli_fetch_assoc($result_s_id);
  
          if($row_s_id > 0) { 
            $id = $row_s_id['id']+1;
          }else { $id = 1;}
          
          if(!empty($fileUpload)){
            include "inc/file_upload/file_upload_new.php";
          }
          
          $inserted_num=$inserted_ketab_num;
          $inserted_date=$inserted_ketab_date;
          
          
          if(empty($_FILES["studies_attach1"]["name"])){
            $studies_attach1 = mysqli_real_escape_string($conn, $_POST['inserted_studies_attach1']);
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
 

    if ($type_code == 'QU') {
       
        
         
        $makla3_type = mysqli_real_escape_string($conn, $_POST['makla3_type']);  
        $extraction_manner = mysqli_real_escape_string($conn, $_POST['extraction_manner']);  
        $explosion_num = mysqli_real_escape_string($conn, $_POST['explosion_num']);  
        $explosion_type = mysqli_real_escape_string($conn, $_POST['explosion_type']);  
        $explosion_materials_amount = mysqli_real_escape_string($conn, $_POST['explosion_materials_amount']);  
        $explosion_materials_source = mysqli_real_escape_string($conn, $_POST['explosion_materials_source']);  
        $explosion_expert = mysqli_real_escape_string($conn, $_POST['explosion_expert']);  
        $explosion_materials_warehouse = mysqli_real_escape_string($conn, $_POST['explosion_materials_warehouse']);  
        $makla3_office = mysqli_real_escape_string($conn, $_POST['makla3_office']);  
        $makla3_vehicles = mysqli_real_escape_string($conn, $_POST['makla3_vehicles']);  
        $where_sell = mysqli_real_escape_string($conn, $_POST['where_sell']);  
        $deal_with = mysqli_real_escape_string($conn, $_POST['deal_with']);  
        $trader_card = mysqli_real_escape_string($conn, $_POST['trader_card']);  
        $personal_weapon = mysqli_real_escape_string($conn, $_POST['personal_weapon']);  
       

               
        
        
        
        
       


    
            
       

      
        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `makla3_type`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `extraction_manner`, `explosion_num`, `explosion_type`, `explosion_materials_amount`, `explosion_materials_source`, `explosion_expert`, `explosion_materials_warehouse`, `makla3_office`, `makla3_vehicles`, `where_sell`, `deal_with`, `cameras`, `trader_card`, `personal_weapon`, `license`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$makla3_type', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$extraction_manner', '$explosion_num', '$explosion_type', '$explosion_materials_amount', '$explosion_materials_source', '$explosion_expert', '$explosion_materials_warehouse', '$makla3_office', '$makla3_vehicles', '$where_sell', '$deal_with', '$cameras', '$trader_card', '$personal_weapon', '$license', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            makla3_type='$makla3_type',
            name='$name',
            fname='$fname',
            mname='$mname',
            personal_code='$personal_code',
            studies_attach='$studies_attach',
            place_address='$place_address',
            socialmedia='$socialmedia',
            start_working='$start_working',
            longitude='$longitude',
            
            extraction_manner='$extraction_manner',
            explosion_num='$explosion_num',
            explosion_type='$explosion_type',
            explosion_materials_amount='$explosion_materials_amount',
            explosion_materials_source='$explosion_materials_source',
            explosion_expert='$explosion_expert',
            explosion_materials_warehouse='$explosion_materials_warehouse',
            makla3_office='$makla3_office',
            makla3_vehicles='$makla3_vehicles',
            where_sell='$where_sell',
            deal_with='$deal_with',
            cameras='$cameras',
            trader_card='$trader_card',
            personal_weapon='$personal_weapon',
            license='$license',
            cooperation='$cooperation',
            criminal_record='$criminal_record',
            cooperation_with_hts='$cooperation_with_hts',
            cooperation_before='$cooperation_before',
            suspicious_activity='$suspicious_activity',
            additional_information='$additional_information',
            result='$result',
            suggestion='$suggestion',
            source='$source',
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
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


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);
                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `address`, `work_type`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$work_type', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);
                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        work_type='$work_type', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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



                if (!empty($_POST['store_name3_new'])) {                    
                    $number_new = count($_POST["store_name3_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3_new'][$i]);
                        $store_name = mysqli_real_escape_string($conn, $_POST['store_name3_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address3_new'][$i]);
                        $stored_goods = mysqli_real_escape_string($conn, $_POST['stored_goods3_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name3_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth3_new'][$i]);
                        if (!empty($_POST['date_of_birth3_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth3_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation3_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num3_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes3_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `store_name`, `address`, `stored_goods`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$store_name', '$address', '$stored_goods', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['store_name3'])) {                    
                    $number = count($_POST["store_name3"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3'][$i]);
                        $store_name = mysqli_real_escape_string($conn, $_POST['store_name3'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address3'][$i]);
                        $stored_goods = mysqli_real_escape_string($conn, $_POST['stored_goods3'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name3'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth3'][$i]);
                        if (!empty($_POST['date_of_birth3'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth3'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation3'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num3'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes3'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach3'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach3'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        store_name='$store_name', 
                        address='$address', 
                        stored_goods='$stored_goods', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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

    if ($type_code == 'CA') {
       
        
     
        $trade_type= mysqli_real_escape_string($conn, $_POST['trade_type']); 
        $capital= mysqli_real_escape_string($conn, $_POST['capital']); 
         
         
         
        
         
        $trade_log_num= mysqli_real_escape_string($conn, $_POST['trade_log_num']); 
        if (!empty($_POST['trade_log_date'])) {
            $trade_log_date = mysqli_real_escape_string($conn, $_POST['trade_log_date']);
        } else {
            $trade_log_date =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $trade_activity= mysqli_real_escape_string($conn, $_POST['trade_activity']); 
        $log_work_period= mysqli_real_escape_string($conn, $_POST['log_work_period']); 
        $mowakel_name= mysqli_real_escape_string($conn, $_POST['mowakel_name']); 
        $mowakel_fname= mysqli_real_escape_string($conn, $_POST['mowakel_fname']); 
        $mowakel_mname= mysqli_real_escape_string($conn, $_POST['mowakel_mname']); 
        $mowakel_nationality= mysqli_real_escape_string($conn, $_POST['mowakel_nationality']); 
        $wkala_type= mysqli_real_escape_string($conn, $_POST['wkala_type']); 
        $wkala_duration= mysqli_real_escape_string($conn, $_POST['wkala_duration']); 
        $wkala_subject= mysqli_real_escape_string($conn, $_POST['wkala_subject']); 
        $trade_name= mysqli_real_escape_string($conn, $_POST['trade_name']); 
        $georaphical= mysqli_real_escape_string($conn, $_POST['georaphical']); 
        //$main_company_address= mysqli_real_escape_string($conn, $_POST['main_company_address']); 
        $wkala_company_address= mysqli_real_escape_string($conn, $_POST['wkala_company_address']); 
        

        
   
        $trader_card= mysqli_real_escape_string($conn, $_POST['trader_card']); 
        $personal_weapon= mysqli_real_escape_string($conn, $_POST['personal_weapon']); 
        


        



               
        
       
        
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `trade_type`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `capital`, `trade_log_num`, `trade_log_date`, `trade_activity`, `log_work_period`, `mowakel_name`, `mowakel_fname`, `mowakel_mname`, `mowakel_nationality`, `wkala_type`, `wkala_duration`, `wkala_subject`, `trade_name`, `georaphical`, `place_address`, `wkala_company_address`, `socialmedia`, `start_working`, `longitude`,  `trader_card`, `personal_weapon`, `apparent_work`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$trade_type', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$capital', '$trade_log_num', '$trade_log_date', '$trade_activity', '$log_work_period', '$mowakel_name', '$mowakel_fname', '$mowakel_mname', '$mowakel_nationality', '$wkala_type', '$wkala_duration', '$wkala_subject', '$trade_name', '$georaphical', '$place_address', '$wkala_company_address', '$socialmedia', '$start_working', '$longitude',  '$trader_card', '$personal_weapon', '$apparent_work', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name', 
            trade_type='$trade_type', 
            name='$name', 
            fname='$fname', 
            mname='$mname', 
            personal_code='$personal_code', 
            studies_attach='$studies_attach', 
            place_address='$place_address', 
            trade_log_num='$trade_log_num', 
            trade_log_date='$trade_log_date', 
            trade_activity='$trade_activity', 
            log_work_period='$log_work_period', 
            mowakel_name='$mowakel_name', 
            mowakel_fname='$mowakel_fname', 
            mowakel_mname='$mowakel_mname', 
            mowakel_nationality='$mowakel_nationality', 
            wkala_type='$wkala_type', 
            wkala_duration='$wkala_duration', 
            wkala_subject='$wkala_subject', 
            trade_name='$trade_name', 
            georaphical='$georaphical', 
            capital='$capital', 
            wkala_company_address='$wkala_company_address', 
            socialmedia='$socialmedia', 
            start_working='$start_working', 
            longitude='$longitude', 
             
            trader_card='$trader_card', 
            personal_weapon='$personal_weapon', 
            apparent_work='$apparent_work', 
            cameras='$cameras', 
            license='$license', 
            cooperation='$cooperation', 
            criminal_record='$criminal_record', 
            cooperation_with_hts='$cooperation_with_hts', 
            cooperation_before='$cooperation_before', 
            suspicious_activity='$suspicious_activity', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
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


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);
                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `address`, `work_type`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$work_type', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);
                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        work_type='$work_type', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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

    if ($type_code == 'JS') {
       
        
     
       
        
        
        
        
        
        
       
       
        $capital= mysqli_real_escape_string($conn, $_POST['capital']);
        $currencies= mysqli_real_escape_string($conn, $_POST['currencies']);
        $gold_amount= mysqli_real_escape_string($conn, $_POST['gold_amount']);
        $btc_dealing= mysqli_real_escape_string($conn, $_POST['btc_dealing']);
        $export= mysqli_real_escape_string($conn, $_POST['export']);
        $gold_make= mysqli_real_escape_string($conn, $_POST['gold_make']);
        $trader_card= mysqli_real_escape_string($conn, $_POST['trader_card']);
        $personal_weapon= mysqli_real_escape_string($conn, $_POST['personal_weapon']);
        



        
   
        

        



               
        
       
        
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `capital`, `currencies`, `gold_amount`, `btc_dealing`, `export`, `gold_make`, `cameras`, `trader_card`, `personal_weapon`, `license`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$capital', '$currencies', '$gold_amount', '$btc_dealing', '$export', '$gold_make', '$cameras', '$trader_card', '$personal_weapon', '$license', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname = '$mname',
            personal_code = '$personal_code',
            studies_attach = '$studies_attach',
            place_address   = '$place_address',
            socialmedia= '$socialmedia',
            start_working = '$start_working',
          
            longitude= '$longitude',
             
            capital= '$capital',
            currencies= '$currencies',
            gold_amount= '$gold_amount',
            btc_dealing= '$btc_dealing',
            export= '$export',
            gold_make= '$gold_make',
            cameras= '$cameras',
            trader_card= '$trader_card',
            personal_weapon = '$personal_weapon',
            license  = '$license',
            cooperation  = '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            suspicious_activity = '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source = '$source',
            study_preparer = '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
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


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);
                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `address`, `work_type`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$work_type', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);
                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        work_type='$work_type', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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

    if ($type_code == 'TM') {
              
        
        $trade_type= mysqli_real_escape_string($conn, $_POST['trade_type']);
        $capital= mysqli_real_escape_string($conn, $_POST['capital']); 
        
        
        
        
        $industrial_log_num= mysqli_real_escape_string($conn, $_POST['industrial_log_num']);
        if (!empty($_POST['industrial_log_date'])) {
            $industrial_log_date = mysqli_real_escape_string($conn, $_POST['industrial_log_date']);
        } else {
            $industrial_log_date =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $log_period= mysqli_real_escape_string($conn, $_POST['log_period']);

        if (!empty($_POST['commercial_log_date'])) {
            $commercial_log_date = mysqli_real_escape_string($conn, $_POST['commercial_log_date']);
        } else {
            $commercial_log_date =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $commercial_activity= mysqli_real_escape_string($conn, $_POST['commercial_activity']);
        $commercial_log_period= mysqli_real_escape_string($conn, $_POST['commercial_log_period']);
        
       


        $trader_card= mysqli_real_escape_string($conn, $_POST['trader_card']);
        $personal_weapon= mysqli_real_escape_string($conn, $_POST['personal_weapon']);
        





        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `trade_type`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `industrial_log_num`, `industrial_log_date`, `log_period`, `commercial_log_date`, `commercial_activity`, `commercial_log_period`, `capital`, `socialmedia`, `start_working`, `longitude`,  `trader_card`, `personal_weapon`, `apparent_work`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$trade_type', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$industrial_log_num', '$industrial_log_date', '$log_period', '$commercial_log_date', '$commercial_activity', '$commercial_log_period', '$capital', '$socialmedia', '$start_working', '$longitude',  '$trader_card', '$personal_weapon', '$apparent_work', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            trade_type= '$trade_type',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            industrial_log_num= '$industrial_log_num',
            industrial_log_date= '$industrial_log_date',
            log_period= '$log_period',
            commercial_log_date= '$commercial_log_date',
            commercial_activity= '$commercial_activity',
            commercial_log_period= '$commercial_log_period',
            capital= '$capital',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            trader_card= '$trader_card',
            personal_weapon= '$personal_weapon',
            apparent_work= '$apparent_work',
            cameras= '$cameras',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer = '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);
                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `address`, `work_type`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$work_type', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error:$studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);
                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        work_type='$work_type', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SC') {
              
        
        

        if (!empty($_POST['company_establishing_date'])) {
            $company_establishing_date = mysqli_real_escape_string($conn, $_POST['company_establishing_date']);
        } else {
            $company_establishing_date =mysqli_real_escape_string($conn, '0000-00-00');
        }

        
        
        
        
        
       
     
        

        $goods_type= mysqli_real_escape_string($conn, $_POST['goods_type']);
        $which_countries_can_reach= mysqli_real_escape_string($conn, $_POST['which_countries_can_reach']);
        $border_crossings= mysqli_real_escape_string($conn, $_POST['border_crossings']);
        $can_import_from= mysqli_real_escape_string($conn, $_POST['can_import_from']);
        $vehicles_num= mysqli_real_escape_string($conn, $_POST['vehicles_num']);
        $other_branches= mysqli_real_escape_string($conn, $_POST['other_branches']);
        

       




       
   
        

        



               
        
       
        
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `goods_type`, `which_countries_can_reach`, `border_crossings`, `can_import_from`, `vehicles_num`, `cameras`, `license`, `cooperation`, `other_branches`, `cooperation_with_hts`, `criminal_record`, `cooperation_before`, `suspicious_activity`, `apparent_work`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$goods_type', '$which_countries_can_reach', '$border_crossings', '$can_import_from', '$vehicles_num', '$cameras', '$license', '$cooperation', '$other_branches', '$cooperation_with_hts', '$criminal_record', '$cooperation_before', '$suspicious_activity', '$apparent_work', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name='$name',
            fname='$fname',
            mname='$mname',
            personal_code='$personal_code',
            studies_attach='$studies_attach',
            place_address ='$place_address',
            socialmedia='$socialmedia',
            longitude='$longitude',
            
            start_working='$start_working',
            goods_type='$goods_type',
            which_countries_can_reach='$which_countries_can_reach',
            border_crossings='$border_crossings',
            can_import_from='$can_import_from',
            vehicles_num='$vehicles_num',
            cameras='$cameras',
            license='$license',
            cooperation='$cooperation',
            other_branches='$other_branches',
            cooperation_with_hts='$cooperation_with_hts',
            criminal_record='$criminal_record',
            cooperation_before='$cooperation_before',
            suspicious_activity='$suspicious_activity',
            apparent_work='$apparent_work',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer = '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'CI') {
              
       
        

       
              
       
        

        $get_internet_from = mysqli_real_escape_string($conn, $_POST['get_internet_from']);
        $network_range = mysqli_real_escape_string($conn, $_POST['network_range']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $network_type = mysqli_real_escape_string($conn, $_POST['network_type']);
        $radious_type = mysqli_real_escape_string($conn, $_POST['radious_type']);
        $network_depends_on_what = mysqli_real_escape_string($conn, $_POST['network_depends_on_what']);
        $network_speed = mysqli_real_escape_string($conn, $_POST['network_speed']);
        $line_size = mysqli_real_escape_string($conn, $_POST['line_size']);
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $towers_locations = mysqli_real_escape_string($conn, $_POST['towers_locations']);
        $maintenance_person = mysqli_real_escape_string($conn, $_POST['maintenance_person']);
        $subscription_manner = mysqli_real_escape_string($conn, $_POST['subscription_manner']);
        $dealing_with_others = mysqli_real_escape_string($conn, $_POST['dealing_with_others']);
        $can_see_customers_privacy = mysqli_real_escape_string($conn, $_POST['can_see_customers_privacy']);
        


               
        
       
        
        
        
        
       


    
            
       

        
        

        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `studies_attach1`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `get_internet_from`, `network_range`, `capital`, `network_type`, `radious_type`, `network_depends_on_what`, `network_speed`, `line_size`, `other_branches`, `towers_locations`, `maintenance_person`, `subscription_manner`, `dealing_with_others`, `can_see_customers_privacy`, `license`, `cameras`, `criminal_record`, `cooperation`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$studies_attach1', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$get_internet_from', '$network_range', '$capital', '$network_type', '$radious_type', '$network_depends_on_what', '$network_speed', '$line_size', '$other_branches', '$towers_locations', '$maintenance_person', '$subscription_manner', '$dealing_with_others', '$can_see_customers_privacy', '$license', '$cameras', '$criminal_record', '$cooperation', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name='$name',
            fname='$fname',
            mname='$mname',
            personal_code='$personal_code',
            studies_attach='$studies_attach',
            studies_attach1='$studies_attach1',
            place_address='$place_address',
            socialmedia='$socialmedia',
            start_working='$start_working',
            longitude='$longitude',
            
            get_internet_from='$get_internet_from',
            network_range='$network_range',
            capital='$capital',
            network_type='$network_type',
            radious_type='$radious_type',
            network_depends_on_what='$network_depends_on_what',
            network_speed='$network_speed',
            line_size='$line_size',
            other_branches='$other_branches',
            towers_locations='$towers_locations',
            maintenance_person='$maintenance_person',
            subscription_manner='$subscription_manner',
            dealing_with_others='$dealing_with_others',
            can_see_customers_privacy='$can_see_customers_privacy',
            license='$license',
            cameras='$cameras',
            criminal_record='$criminal_record',
            cooperation='$cooperation',
            cooperation_with_hts='$cooperation_with_hts',
            suspicious_activity='$suspicious_activity',
            additional_information='$additional_information',
            result='$result',
            suggestion='$suggestion',
            source='$source',
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


                



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'TX') {
              
        
        

        if (!empty($_POST['company_establishing_date'])) {
            $company_establishing_date = mysqli_real_escape_string($conn, $_POST['company_establishing_date']);
        } else {
            $company_establishing_date =mysqli_real_escape_string($conn, '0000-00-00');
        }


        
        $taxi_parking = mysqli_real_escape_string($conn, $_POST['taxi_parking']);
        $taxi_distinctive_signs = mysqli_real_escape_string($conn, $_POST['taxi_distinctive_signs']);
        $taxi_id = mysqli_real_escape_string($conn, $_POST['taxi_id']);
        $taxi_ownenership = mysqli_real_escape_string($conn, $_POST['taxi_ownenership']);
        $working_with_organizations = mysqli_real_escape_string($conn, $_POST['working_with_organizations']);
        $travel_to_north = mysqli_real_escape_string($conn, $_POST['travel_to_north']);
        $taxi_park_overlooking = mysqli_real_escape_string($conn, $_POST['taxi_park_overlooking']);
        $ethical_problems = mysqli_real_escape_string($conn, $_POST['ethical_problems']);
        $taxi_specifications = mysqli_real_escape_string($conn, $_POST['taxi_specifications']);

       
   
        

        



               
        /* 
       
         */
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `socialmedia`, `taxi_parking`, `start_working`, `taxi_distinctive_signs`, `taxi_id`, `taxi_ownenership`, `taxi_specifications`,`working_with_organizations`, `travel_to_north`, `taxi_park_overlooking`, `criminal_record`, `ethical_problems`, `cooperation`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$socialmedia', '$taxi_parking', '$start_working', '$taxi_distinctive_signs', '$taxi_id', '$taxi_ownenership', '$taxi_specifications', '$working_with_organizations', '$travel_to_north', '$taxi_park_overlooking', '$criminal_record', '$ethical_problems', '$cooperation', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name='$name',
            fname='$fname',
            mname='$mname',
            personal_code='$personal_code',
            studies_attach='$studies_attach',
            socialmedia='$socialmedia',
            taxi_parking='$taxi_parking',
            start_working='$start_working',
            taxi_distinctive_signs='$taxi_distinctive_signs',
            taxi_id='$taxi_id',
            taxi_ownenership='$taxi_ownenership',
            taxi_specifications='$taxi_specifications',
            working_with_organizations='$working_with_organizations',
            travel_to_north='$travel_to_north',
            taxi_park_overlooking='$taxi_park_overlooking',
            criminal_record='$criminal_record',
            ethical_problems='$ethical_problems',
            cooperation='$cooperation',
            cooperation_with_hts='$cooperation_with_hts',
            suspicious_activity='$suspicious_activity',
            additional_information='$additional_information',
            result='$result',
            suggestion='$suggestion',
            source='$source',
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'LW') {
              
        
        
        
        
        
       
     
        

       

      
        
        
        $ownership= mysqli_real_escape_string($conn, $_POST['ownership']);
        $warsha_equipment_ownership= mysqli_real_escape_string($conn, $_POST['warsha_equipment_ownership']);
        $iron_source= mysqli_real_escape_string($conn, $_POST['iron_source']);
        $military_equipment_make= mysqli_real_escape_string($conn, $_POST['military_equipment_make']);
        $contracts= mysqli_real_escape_string($conn, $_POST['contracts']);
        $warsha_work_evaluation= mysqli_real_escape_string($conn, $_POST['warsha_work_evaluation']);
        $working_days= mysqli_real_escape_string($conn, $_POST['working_days']);
       
   
        

        



               
        
       
        
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `ownership`, `warsha_equipment_ownership`, `iron_source`, `military_equipment_make`, `contracts`, `warsha_work_evaluation`, `working_days`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `apparent_work`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$ownership', '$warsha_equipment_ownership', '$iron_source', '$military_equipment_make', '$contracts', '$warsha_work_evaluation', '$working_days', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$apparent_work', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            ownership= '$ownership',
            warsha_equipment_ownership= '$warsha_equipment_ownership',
            iron_source= '$iron_source',
            military_equipment_make= '$military_equipment_make',
            contracts= '$contracts',
            warsha_work_evaluation= '$warsha_work_evaluation',
            working_days= '$working_days',
            cameras= '$cameras',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            apparent_work= '$apparent_work',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'BS') {
              
        
        
        
        
        
       
     
        

        

      
         
        
        $ownership = mysqli_real_escape_string($conn, $_POST['ownership']); 
       /*  $warsha_equipment_ownership = mysqli_real_escape_string($conn, $_POST['warsha_equipment_ownership']); 
        $iron_source = mysqli_real_escape_string($conn, $_POST['iron_source']); 
        $military_equipment_make = mysqli_real_escape_string($conn, $_POST['military_equipment_make']); 
        $contracts = mysqli_real_escape_string($conn, $_POST['contracts']); 
        $warsha_work_evaluation = mysqli_real_escape_string($conn, $_POST['warsha_work_evaluation']); */ 
        $working_days = mysqli_real_escape_string($conn, $_POST['working_days']); 
        
       
   
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $military_cars_repair = mysqli_real_escape_string($conn, $_POST['military_cars_repair']);
        $contracts_with_orgs = mysqli_real_escape_string($conn, $_POST['contracts_with_orgs']);
        $workplace = mysqli_real_escape_string($conn, $_POST['workplace']);
        $equipment_size = mysqli_real_escape_string($conn, $_POST['equipment_size']);


        



               
        
       
        
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `ownership`, `other_branches`, `work_type`, `military_cars_repair`, `contracts_with_orgs`, `warsha_work_evaluation`, `workplace`, `equipment_size`, `working_days`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `apparent_work`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$ownership', '$other_branches', '$work_type', '$military_cars_repair', '$contracts_with_orgs', '$warsha_work_evaluation', '$workplace', '$equipment_size', '$working_days', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$apparent_work', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            ownership= '$ownership',
            other_branches= '$other_branches',
            work_type= '$work_type',
            military_cars_repair= '$military_cars_repair',
            contracts_with_orgs= '$contracts_with_orgs',
            warsha_work_evaluation= '$warsha_work_evaluation',
            workplace= '$workplace',
            equipment_size= '$equipment_size',
            working_days= '$working_days',
            cameras= '$cameras',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            apparent_work= '$apparent_work',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SI') {
              
    
        
        $grade_type = mysqli_real_escape_string($conn, $_POST['grade_type']);
        $books_type = mysqli_real_escape_string($conn, $_POST['books_type']);
        $ideas_outside_books = mysqli_real_escape_string($conn, $_POST['ideas_outside_books']);
        $students_num = mysqli_real_escape_string($conn, $_POST['students_num']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $isolation_between_males_females = mysqli_real_escape_string($conn, $_POST['isolation_between_males_females']);
        $work_time = mysqli_real_escape_string($conn, $_POST['work_time']);
        $work_duration = mysqli_real_escape_string($conn, $_POST['work_duration']);
        

        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `grade_type`, `books_type`, `ideas_outside_books`, `students_num`, `location_compatibility`, `isolation_between_males_females`, `work_time`, `work_duration`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `cameras`, `suspicious_activity`, `apparent_work`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$grade_type', '$books_type', '$ideas_outside_books', '$students_num', '$location_compatibility', '$isolation_between_males_females', '$work_time', '$work_duration', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$cameras', '$suspicious_activity', '$apparent_work', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            longitude= '$longitude',
             
            start_working= '$start_working',
            grade_type= '$grade_type',
            books_type= '$books_type',
            ideas_outside_books= '$ideas_outside_books',
            students_num= '$students_num',
            location_compatibility= '$location_compatibility',
            isolation_between_males_females= '$isolation_between_males_females',
            work_time= '$work_time',
            work_duration= '$work_duration',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            apparent_work= '$apparent_work',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'HO') {
              
    
        
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $resident_nationalities = mysqli_real_escape_string($conn, $_POST['resident_nationalities']);

        $bad_behaviour = mysqli_real_escape_string($conn, $_POST['bad_behaviour']);
        $constant_visitors = mysqli_real_escape_string($conn, $_POST['constant_visitors']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $hotel_location_importance = mysqli_real_escape_string($conn, $_POST['hotel_location_importance']);
        
    
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `work_type`, `resident_nationalities`, `license`, `cooperation`, `bad_behaviour`, `criminal_record`, `constant_visitors`, `cameras`, `location_compatibility`, `hotel_location_importance`, `cooperation_with_hts`, `apparent_work`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$work_type', '$resident_nationalities', '$license', '$cooperation', '$bad_behaviour', '$criminal_record', '$constant_visitors', '$cameras', '$location_compatibility', '$hotel_location_importance', '$cooperation_with_hts', '$apparent_work', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            work_type= '$work_type',
            resident_nationalities= '$resident_nationalities',
            license= '$license',
            cooperation= '$cooperation',
            bad_behaviour= '$bad_behaviour',
            criminal_record= '$criminal_record',
            constant_visitors= '$constant_visitors',
            cameras= '$cameras',
            location_compatibility= '$location_compatibility',
            hotel_location_importance= '$hotel_location_importance',
            cooperation_with_hts= '$cooperation_with_hts',
            apparent_work= '$apparent_work',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SP') {
              
        
        
        
        
        
       
     


        

        
        
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $prohibitions = mysqli_real_escape_string($conn, $_POST['prohibitions']);
        $teach_swimming = mysqli_real_escape_string($conn, $_POST['teach_swimming']);
        $notable_visitors = mysqli_real_escape_string($conn, $_POST['notable_visitors']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $parties_meetings = mysqli_real_escape_string($conn, $_POST['parties_meetings']);

               
        
       
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `work_type`, `prohibitions`, `license`, `cooperation`, `teach_swimming`, `criminal_record`, `notable_visitors`, `cameras`, `location_compatibility`, `parties_meetings`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$work_type', '$prohibitions', '$license', '$cooperation', '$teach_swimming', '$criminal_record', '$notable_visitors', '$cameras', '$location_compatibility', '$parties_meetings', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia='$socialmedia',
            longitude='$longitude',
             
            start_working= '$start_working', 
            work_type= '$work_type', 
            prohibitions=   '$prohibitions', 
            license= '$license', 
            cooperation= '$cooperation', 
            teach_swimming= '$teach_swimming', 
            criminal_record=  '$criminal_record', 
            notable_visitors=   '$notable_visitors', 
            cameras=  '$cameras', 
            location_compatibility= '$location_compatibility', 
            parties_meetings=   '$parties_meetings',
            cooperation_with_hts= '$cooperation_with_hts',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'CK') {
              
        
       
        $services  = mysqli_real_escape_string($conn, $_POST['services']);
        $keys_copying  = mysqli_real_escape_string($conn, $_POST['keys_copying']);
        $imprint_programming  = mysqli_real_escape_string($conn, $_POST['imprint_programming']);
        $breaking_locks  = mysqli_real_escape_string($conn, $_POST['breaking_locks']);
        $require_identity  = mysqli_real_escape_string($conn, $_POST['require_identity']);
        $location_compatibility  = mysqli_real_escape_string($conn, $_POST['location_compatibility']); 

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `services`, `keys_copying`, `imprint_programming`, `breaking_locks`, `license`, `cooperation`, `require_identity`, `criminal_record`, `cameras`, `location_compatibility`, `apparent_work`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$services', '$keys_copying', '$imprint_programming', '$breaking_locks', '$license', '$cooperation', '$require_identity', '$criminal_record', '$cameras', '$location_compatibility', '$apparent_work', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$type_num',
            name='$name',
            fname='$fname',
            mname='$mname',
            personal_code='$personal_code',
            studies_attach='$studies_attach',
            place_address='$place_address',
            socialmedia='$socialmedia',
            longitude='$longitude',
            
            start_working='$start_working',
            services='$services',
            keys_copying='$keys_copying',
            imprint_programming='$imprint_programming',
            breaking_locks='$breaking_locks',
            license='$license',
            cooperation='$cooperation',
            require_identity='$require_identity',
            criminal_record='$criminal_record',
            cameras='$cameras',
            location_compatibility='$location_compatibility',
            apparent_work='$apparent_work',
            cooperation_with_hts= '$cooperation_with_hts',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'CD') {
              
        
        
        
        
        
       
     


        

        
        
        $center_nature = mysqli_real_escape_string($conn, $_POST['center_nature']);
        $area_cover = mysqli_real_escape_string($conn, $_POST['area_cover']);
        $response_level = mysqli_real_escape_string($conn, $_POST['response_level']);
        $corona_response = mysqli_real_escape_string($conn, $_POST['corona_response']);
        $center_vehicles = mysqli_real_escape_string($conn, $_POST['center_vehicles']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $working_days = mysqli_real_escape_string($conn, $_POST['working_days']);



               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `center_nature`, `area_cover`, `response_level`, `corona_response`, `center_vehicles`, `location_compatibility`, `working_days`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `additional_information`, `result`, `suggestion`, `source`, `suspicious_activity`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$center_nature', '$area_cover', '$response_level', '$corona_response', '$center_vehicles', '$location_compatibility', '$working_days', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$additional_information', '$result', '$suggestion', '$source', '$suspicious_activity', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            longitude= '$longitude',
             
            start_working= '$start_working',
            center_nature= '$center_nature',
            area_cover= '$area_cover',
            response_level= '$response_level',
            corona_response= '$corona_response',
            center_vehicles= '$center_vehicles',
            location_compatibility= '$location_compatibility',
            working_days= '$working_days',
            cameras= '$cameras',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            additional_information= '$additional_information',
            result = '$result',
            suggestion = '$suggestion',
            source = '$source',
            suspicious_activity = '$suspicious_activity',
            study_preparer = '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }

            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'AP') {
              
        
        
        
        
        
       
     


        

        
        
        $games_type = mysqli_real_escape_string($conn, $_POST['games_type']);
        $forbidden_things = mysqli_real_escape_string($conn, $_POST['forbidden_things']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $parties_or_meetings = mysqli_real_escape_string($conn, $_POST['parties_or_meetings']);
        




               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `games_type`, `forbidden_things`, `license`, `cooperation`, `capital`, `criminal_record`, `location_compatibility`, `cameras`, `parties_or_meetings`, `apparent_work`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$games_type', '$forbidden_things', '$license', '$cooperation', '$capital', '$criminal_record', '$location_compatibility', '$cameras', '$parties_or_meetings', '$apparent_work', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type',  '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            longitude= '$longitude',
             
            start_working= '$start_working',
            games_type= '$games_type',
            forbidden_things= '$forbidden_things',
            license= '$license',
            cooperation= '$cooperation',
            capital= '$capital',
            criminal_record= '$criminal_record',
            location_compatibility= '$location_compatibility',
            cameras= '$cameras',
            parties_or_meetings= '$parties_or_meetings',
            apparent_work= '$apparent_work', 
            cooperation_with_hts= '$cooperation_with_hts',
            additional_information= '$additional_information',
            result = '$result',
            suggestion = '$suggestion',
            source = '$source',
            suspicious_activity = '$suspicious_activity',
            study_preparer = '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'FA') {
              
        
        
        
        
        
       
     


        

        
        
        $overlooking = mysqli_real_escape_string($conn, $_POST['overlooking']);
        $used_military_residence = mysqli_real_escape_string($conn, $_POST['used_military_residence']);
        $residing_person_name = mysqli_real_escape_string($conn, $_POST['residing_person_name']);
        $hiring = mysqli_real_escape_string($conn, $_POST['hiring']);
        $legal_hiring = mysqli_real_escape_string($conn, $_POST['legal_hiring']);
        $hiring_price = mysqli_real_escape_string($conn, $_POST['hiring_price']);


               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `longitude`,  `overlooking`, `used_military_residence`, `residing_person_name`, `hiring`, `legal_hiring`, `hiring_price`, `criminal_record`, `cameras`, `cooperation`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$longitude',  '$overlooking', '$used_military_residence', '$residing_person_name', '$hiring', '$legal_hiring', '$hiring_price', '$criminal_record', '$cameras', '$cooperation', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile','$details_type',  '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            longitude= '$longitude',
             
            overlooking= '$overlooking',
            used_military_residence= '$used_military_residence',
            residing_person_name= '$residing_person_name',
            hiring= '$hiring',
            legal_hiring= '$legal_hiring',
            hiring_price= '$hiring_price',
            criminal_record= '$criminal_record',
            cameras= '$cameras',
            cooperation= '$cooperation',
            cooperation_with_hts= '$cooperation_with_hts',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'PH') {
              
        
        
        
        
        
       
     


        

        
        //$start_working = mysqli_real_escape_string($conn, $_POST['start_working']);
        
    
        $cure_type = mysqli_real_escape_string($conn, $_POST['cure_type']);
        $capital = mysqli_real_escape_string($conn, $_POST['capital']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $coverd_areas = mysqli_real_escape_string($conn, $_POST['coverd_areas']);
        



               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`,`place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `start_working`, `socialmedia`, `place_address`, `longitude`,  `cure_type`, `license`, `cooperation`, `capital`, `criminal_record`, `cooperation_before`, `cameras`, `location_compatibility`, `coverd_areas`, `cooperation_with_hts`, `apparent_work`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$start_working', '$socialmedia', '$place_address', '$longitude',  '$cure_type', '$license', '$cooperation', '$capital', '$criminal_record', '$cooperation_before', '$cameras', '$location_compatibility', '$coverd_areas', '$cooperation_with_hts', '$apparent_work', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            start_working= '$start_working',
            socialmedia= '$socialmedia',
            place_address= '$place_address',
            longitude= '$longitude',
             
            cure_type= '$cure_type',
            license= '$license',
            cooperation= '$cooperation',
            capital= '$capital',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            location_compatibility= '$location_compatibility',
            coverd_areas= '$coverd_areas',
            cooperation_with_hts= '$cooperation_with_hts',
            apparent_work= '$apparent_work',
            cooperation_with_hts= '$cooperation_with_hts',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'PP') {
              
        
        
        
        
        
       
     


        

        
        
        $work_type= mysqli_real_escape_string($conn, $_POST['work_type']);
        $work_rank= mysqli_real_escape_string($conn, $_POST['work_rank']);
        $advertising_campaign= mysqli_real_escape_string($conn, $_POST['advertising_campaign']);
        $other_branches= mysqli_real_escape_string($conn, $_POST['other_branches']);
        $marketing_manner= mysqli_real_escape_string($conn, $_POST['marketing_manner']);
        $contracts_with_orgs= mysqli_real_escape_string($conn, $_POST['contracts_with_orgs']);
 



               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`,`place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `work_type`, `work_rank`, `advertising_campaign`, `other_branches`, `marketing_manner`, `cameras`, `contracts_with_orgs`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$work_type', '$work_rank', '$advertising_campaign', '$other_branches', '$marketing_manner', '$cameras', '$contracts_with_orgs', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            work_type= '$work_type',
            work_rank= '$work_rank',
            advertising_campaign= '$advertising_campaign',
            other_branches= '$other_branches',
            marketing_manner= '$marketing_manner',
            cameras= '$cameras',
            contracts_with_orgs= '$contracts_with_orgs',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',

            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'MP') {
              
        
        
        
        
        
       
     


        

        
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $work_rank = mysqli_real_escape_string($conn, $_POST['work_rank']);
        $most_participation = mysqli_real_escape_string($conn, $_POST['most_participation']);
        



               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`,`place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `socialmedia`, `license`, `start_working`, `work_type`, `work_rank`, `most_participation`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `apparent_work`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$socialmedia', '$license', '$start_working', '$work_type', '$work_rank', '$most_participation', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$apparent_work', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            socialmedia= '$socialmedia',
            license= '$license',
            start_working= '$start_working',
            work_type= '$work_type',
            work_rank= '$work_rank',
            most_participation= '$most_participation',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            apparent_work= '$apparent_work',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'FG') {
              
        
        
        
        
        
       
     


        

        
        
        //$start_working = mysqli_real_escape_string($conn, $_POST['start_working']);
        $max_trainees_num = mysqli_real_escape_string($conn, $_POST['max_trainees_num']);
        $current_trainees_num = mysqli_real_escape_string($conn, $_POST['current_trainees_num']);
        $gym_courses_type = mysqli_real_escape_string($conn, $_POST['gym_courses_type']);
        $gym_sport_machine = mysqli_real_escape_string($conn, $_POST['gym_sport_machine']);
        $gym_daily_routine = mysqli_real_escape_string($conn, $_POST['gym_daily_routine']);
        $males_or_females = mysqli_real_escape_string($conn, $_POST['males_or_females']);
        $ask_for_id = mysqli_real_escape_string($conn, $_POST['ask_for_id']);
        $most_trainees_type = mysqli_real_escape_string($conn, $_POST['most_trainees_type']);
        $non_syrian_trainees = mysqli_real_escape_string($conn, $_POST['non_syrian_trainees']);
        $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);

 



               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `max_trainees_num`, `current_trainees_num`, `gym_courses_type`, `gym_sport_machine`, `gym_daily_routine`, `males_or_females`, `ask_for_id`, `most_trainees_type`, `non_syrian_trainees`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$max_trainees_num', '$current_trainees_num', '$gym_courses_type', '$gym_sport_machine', '$gym_daily_routine', '$males_or_females', '$ask_for_id', '$most_trainees_type', '$non_syrian_trainees', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',
             
            start_working= '$start_working',
            max_trainees_num= '$max_trainees_num',
            current_trainees_num= '$current_trainees_num',
            gym_courses_type= '$gym_courses_type',
            gym_sport_machine= '$gym_sport_machine',
            gym_daily_routine= '$gym_daily_routine',
            males_or_females= '$males_or_females',
            ask_for_id= '$ask_for_id',
            most_trainees_type= '$most_trainees_type',
            non_syrian_trainees= '$non_syrian_trainees',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'BB') {
              
        
        //$start_working = mysqli_real_escape_string($conn, $_POST['start_working']);
        $max_trainees_num = mysqli_real_escape_string($conn, $_POST['max_trainees_num']);
        $current_trainees_num = mysqli_real_escape_string($conn, $_POST['current_trainees_num']);
        $gym_courses_type = mysqli_real_escape_string($conn, $_POST['gym_courses_type']);
        $gym_sport_machine = mysqli_real_escape_string($conn, $_POST['gym_sport_machine']);
        $gym_daily_routine = mysqli_real_escape_string($conn, $_POST['gym_daily_routine']);
        $males_or_females = mysqli_real_escape_string($conn, $_POST['males_or_females']);
        $ask_for_id = mysqli_real_escape_string($conn, $_POST['ask_for_id']);
        $most_trainees_type = mysqli_real_escape_string($conn, $_POST['most_trainees_type']);
        $non_syrian_trainees = mysqli_real_escape_string($conn, $_POST['non_syrian_trainees']);
        $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);

        $services = mysqli_real_escape_string($conn, $_POST['services']);
        $monthly_fee = mysqli_real_escape_string($conn, $_POST['monthly_fee']);

        
        
               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `max_trainees_num`, `current_trainees_num`, `services`, `gym_courses_type`, `gym_sport_machine`, `gym_daily_routine`, `males_or_females`, `monthly_fee`, `ask_for_id`, `most_trainees_type`, `non_syrian_trainees`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$max_trainees_num', '$current_trainees_num', '$services', '$gym_courses_type', '$gym_sport_machine', '$gym_daily_routine', '$males_or_females', '$monthly_fee', '$ask_for_id', '$most_trainees_type', '$non_syrian_trainees', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',
             
            start_working= '$start_working',
            max_trainees_num= '$max_trainees_num',
            current_trainees_num= '$current_trainees_num',
            gym_courses_type= '$gym_courses_type',
            gym_sport_machine= '$gym_sport_machine',
            gym_daily_routine= '$gym_daily_routine',
            males_or_females= '$males_or_females',
            ask_for_id= '$ask_for_id',
            services='$services',
            monthly_fee='$monthly_fee',
            most_trainees_type= '$most_trainees_type',
            non_syrian_trainees= '$non_syrian_trainees',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'MW') {
              
        
        
        
        
        
       
     


        

        
        
        
        $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);
        $warsha_equipment_ownership = mysqli_real_escape_string($conn, $_POST['warsha_equipment_ownership']);
        $iron_source = mysqli_real_escape_string($conn, $_POST['iron_source']);
        $manufacturing_manner = mysqli_real_escape_string($conn, $_POST['manufacturing_manner']);
        $contracts = mysqli_real_escape_string($conn, $_POST['contracts']);
        $warsha_work_evaluation = mysqli_real_escape_string($conn, $_POST['warsha_work_evaluation']);
        $working_days = mysqli_real_escape_string($conn, $_POST['working_days']);
        $criminal_record = mysqli_real_escape_string($conn, $_POST['criminal_record']);

 
        
               
        
       
        
        
        
        
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`,  `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `ownership`, `warsha_equipment_ownership`, `iron_source`, `manufacturing_manner`, `contracts`, `warsha_work_evaluation`, `working_days`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `apparent_work`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num',  '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$ownership', '$warsha_equipment_ownership', '$iron_source', '$manufacturing_manner', '$contracts', '$warsha_work_evaluation', '$working_days', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$apparent_work', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            ownership= '$ownership',
            warsha_equipment_ownership= '$warsha_equipment_ownership',
            iron_source= '$iron_source',
            manufacturing_manner= '$manufacturing_manner',
            contracts= '$contracts',
            warsha_work_evaluation= '$warsha_work_evaluation',
            working_days= '$working_days',
            apparent_work= '$apparent_work', 
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            license='$license',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               



                


            
                
            
            

            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'PS') {      
        $petrol_type = mysqli_real_escape_string($conn, $_POST['petrol_type']);
        $spare_tanks = mysqli_real_escape_string($conn, $_POST['spare_tanks']);
        $supplier_company_name = mysqli_real_escape_string($conn, $_POST['supplier_company_name']);
        $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);
        $vehicles_details = mysqli_real_escape_string($conn, $_POST['vehicles_details']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $trader_card = mysqli_real_escape_string($conn, $_POST['trader_card']);
        $accept_hts_cards = mysqli_real_escape_string($conn, $_POST['accept_hts_cards']);


        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`,  `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `petrol_type`, `spare_tanks`, `supplier_company_name`, `ownership`, `vehicles_details`, `location_compatibility`, `trader_card`, `accept_hts_cards`, `license`, `cooperation`, `criminal_record`, `cameras`, `cooperation_before`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num',  '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$petrol_type', '$spare_tanks', '$supplier_company_name', '$ownership', '$vehicles_details', '$location_compatibility', '$trader_card', '$accept_hts_cards', '$license', '$cooperation', '$criminal_record', '$cameras', '$cooperation_before', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            studies_attach='$studies_attach', 
            place_address='$place_address',
            petrol_type= '$petrol_type',
            spare_tanks= '$spare_tanks',
            supplier_company_name= '$supplier_company_name',
            ownership= '$ownership',
            vehicles_details= '$vehicles_details',
            location_compatibility= '$location_compatibility',
            trader_card= '$trader_card',
            accept_hts_cards= '$accept_hts_cards',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            socialmedia= '$socialmedia',
            start_working= '$start_working',
            longitude= '$longitude',
             
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }



        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }




    if ($type_code == 'IF') {
       
        
         
        $place_type = mysqli_real_escape_string($conn, $_POST['place_type']);  
        $place_cost = mysqli_real_escape_string($conn, $_POST['place_cost']);  
        $material_support = mysqli_real_escape_string($conn, $_POST['material_support']);  
        $place_sub_others = mysqli_real_escape_string($conn, $_POST['place_sub_others']);  
        $exported_type	 = mysqli_real_escape_string($conn, $_POST['exported_type']);  
        $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);  
        $power_payment = mysqli_real_escape_string($conn, $_POST['power_payment']);  
        $deal_with = mysqli_real_escape_string($conn, $_POST['deal_with']);  
        $land_owner = mysqli_real_escape_string($conn, $_POST['land_owner']);  
        $cars_num = mysqli_real_escape_string($conn, $_POST['cars_num']);  
        $personal_weapon = mysqli_real_escape_string($conn, $_POST['personal_weapon']);  
        $general_work = mysqli_real_escape_string($conn, $_POST['general_work']);  
        $guard_type = mysqli_real_escape_string($conn, $_POST['guard_type']);  
       


        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `place_type`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `start_working`, `longitude`,  `place_cost`, `material_support`, `place_sub_others`, `exported_type`, `payment_type`, `power_payment`, `deal_with`, `land_owner`, `cars_num`, `personal_weapon`, general_work,guard_type, `license`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$place_type', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$start_working', '$longitude',  '$place_cost', '$material_support', '$place_sub_others', '$exported_type', '$payment_type', '$power_payment', '$deal_with', '$land_owner', '$cars_num', '$personal_weapon', '$general_work', '$guard_type', '$license', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            place_type='$place_type', 
            name='$name', 
            fname='$fname', 
            mname='$mname', 
            personal_code='$personal_code', 
            studies_attach='$studies_attach', 
            place_address='$place_address', 
            socialmedia='$socialmedia', 
            start_working='$start_working', 
            longitude='$longitude',  
            place_cost='$place_cost', 
            material_support='$material_support', 
            place_sub_others='$place_sub_others', 
            exported_type='$exported_type', 
            payment_type = '$payment_type', 
            power_payment = '$power_payment', 
            deal_with = '$deal_with', 
            land_owner = '$land_owner', 
            cars_num='$cars_num', 
            personal_weapon='$personal_weapon', 
            general_work='$general_work', 
            guard_type='$guard_type',
            license='$license',
            cooperation='$cooperation',
            criminal_record='$criminal_record',
            cooperation_with_hts='$cooperation_with_hts',
            cooperation_before='$cooperation_before',
            suspicious_activity='$suspicious_activity',
            additional_information='$additional_information',
            result='$result',
            suggestion='$suggestion',
            source='$source',
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
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


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);
                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `address`, `work_type`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$work_type', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);
                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        work_type='$work_type', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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



                if (!empty($_POST['store_name3_new'])) {                    
                    $number_new = count($_POST["store_name3_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3_new'][$i]);
                        $store_name = mysqli_real_escape_string($conn, $_POST['store_name3_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address3_new'][$i]);
                        $stored_goods = mysqli_real_escape_string($conn, $_POST['stored_goods3_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name3_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth3_new'][$i]);
                        if (!empty($_POST['date_of_birth3_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth3_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation3_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num3_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes3_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `store_name`, `address`, `stored_goods`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$store_name', '$address', '$stored_goods', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['store_name3'])) {                    
                    $number = count($_POST["store_name3"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3'][$i]);
                        $store_name = mysqli_real_escape_string($conn, $_POST['store_name3'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address3'][$i]);
                        $stored_goods = mysqli_real_escape_string($conn, $_POST['stored_goods3'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name3'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth3'][$i]);
                        if (!empty($_POST['date_of_birth3'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth3'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation3'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num3'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes3'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach3'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach3'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        store_name='$store_name', 
                        address='$address', 
                        stored_goods='$stored_goods', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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


                if (!empty($_POST['name4_new'])) {                    
                    $number_new = count($_POST["store_name3_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num4_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name4_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname4_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname4_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth4_new'][$i]);
                        if (!empty($_POST['date_of_birth4_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth4_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $address = mysqli_real_escape_string($conn, $_POST['address4_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type4_new'][$i]);
                        $covered_area = mysqli_real_escape_string($conn, $_POST['covered_area4_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes4_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `address`, `work_type`, `covered_area`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$address', '$work_type', '$covered_area', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['store_name3'])) {                    
                    $number = count($_POST["store_name3"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num4'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name4'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname4'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname4'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth4'][$i]);
                        if (!empty($_POST['date_of_birth4'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth4'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $address = mysqli_real_escape_string($conn, $_POST['address4'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type4'][$i]);
                        $covered_area = mysqli_real_escape_string($conn, $_POST['covered_area4'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes4'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach3'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach4'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        address='$address', 
                        work_type='$work_type', 
                        covered_area='$covered_area', 
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


       

    if ($type_code == 'CL') {
       
        
         
        $geo_distribute = mysqli_real_escape_string($conn, $_POST['geo_distribute']);  
        $decision_making_mechanism = mysqli_real_escape_string($conn, $_POST['decision_making_mechanism']);  
        $meeting_place = mysqli_real_escape_string($conn, $_POST['meeting_place']);  
        $society_interaction_level	 = mysqli_real_escape_string($conn, $_POST['society_interaction_level']);  
        $acting = mysqli_real_escape_string($conn, $_POST['acting']);  
        $pre_fighting = mysqli_real_escape_string($conn, $_POST['pre_fighting']);  
        $internal_links = mysqli_real_escape_string($conn, $_POST['internal_links']);  
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);  
        $investments = mysqli_real_escape_string($conn, $_POST['investments']);  
        $current_fighting = mysqli_real_escape_string($conn, $_POST['current_fighting']);  
        $abroad_offices = mysqli_real_escape_string($conn, $_POST['abroad_offices']);  
        $suporters = mysqli_real_escape_string($conn, $_POST['suporters']);  

        $disagreements = mysqli_real_escape_string($conn, $_POST['disagreements']); 
        $financial_status = mysqli_real_escape_string($conn, $_POST['financial_status']); 

        $nafeer_response = mysqli_real_escape_string($conn, $_POST['nafeer_response']); 

        if(!empty($_POST['ages_to_15'])){
            $ages_to_15 = mysqli_real_escape_string($conn, $_POST['ages_to_15']);
        }else{
            $ages_to_15 = 0;
        }
        if(!empty($_POST['ages_15_30'])){
            $ages_15_30 = mysqli_real_escape_string($conn, $_POST['ages_15_30']);
        }else{
            $ages_15_30 = 0;
        }
        if(!empty($_POST['ages_31_45'])){
            $ages_31_45 = mysqli_real_escape_string($conn, $_POST['ages_31_45']);
        }else{
            $ages_31_45 = 0;
        }
        if(!empty($_POST['ages_over_40'])){
            $ages_over_40 = mysqli_real_escape_string($conn, $_POST['ages_over_40']);
        }else{
            $ages_over_40 = 0;
        }
        if(!empty($_POST['educational_num'])){
            $educational_num = mysqli_real_escape_string($conn, $_POST['educational_num']);
        }else{
            $educational_num = 0;
        }
   

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `studies_attach`, `name`, `fname`, `lname`, `personal_code`,`geo_distribute`, `place_address`,`decision_making_mechanism`,`meeting_place`, `society_interaction_level`,`acting`,`cooperation`,`pre_fighting`,`internal_links`,`related_branch`,`investments`,`current_fighting`,`abroad_offices`,`suporters`,`disagreements`,`financial_status`,`nafeer_response`,`ages_to_15`,`ages_15_30`,`ages_31_45`,`ages_over_40`,`educational_num`,`additional_information`,`result`,`suggestion`,`study_preparer`,`jeha`,`details_type`,`added_by`,`add_date`  ) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$studies_attach', '$name', '$fname', '$lname', '$personal_code','$geo_distribute', '$place_address','$decision_making_mechanism','$meeting_place', '$society_interaction_level','$acting','$cooperation','$pre_fighting','$internal_links','$related_branch','$investments','$current_fighting','$abroad_offices','$suporters','$disagreements','$financial_status','$nafeer_response','$ages_to_15','$ages_15_30','$ages_31_45','$ages_over_40','$educational_num','$additional_information','$result','$suggestion','$study_preparer', '$jeha_profile', '$details_type', '$added_by', current_timestamp() )";
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
            type_num='$type_num',
            place_name= '$place_name', 
            studies_attach= '$studies_attach',
            name= '$name',
            fname= '$fname',
            lname='$lname',
            personal_code= '$personal_code',
            geo_distribute='$geo_distribute',
            place_address= '$place_address',
            decision_making_mechanism='$decision_making_mechanism',
            meeting_place='$meeting_place',
            society_interaction_level= '$society_interaction_level',
            acting='$acting',
            cooperation='$cooperation',
            pre_fighting='$pre_fighting',
            internal_links='$internal_links',
            internal_links='$internal_links',
            related_branch='$related_branch',
            investments='$investments',
            current_fighting='$current_fighting',
            abroad_offices='$abroad_offices',
            suporters='$suporters',
            disagreements='$disagreements',
            financial_status='$financial_status',
            nafeer_response='$nafeer_response',
            ages_to_15='$ages_to_15',
            ages_15_30='$ages_15_30',
            ages_31_45='$ages_31_45',
            ages_over_40='$ages_over_40',
            educational_num='$educational_num',
            additional_information='$additional_information',
            result='$result',
            suggestion='$suggestion',
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
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


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $covered_area = mysqli_real_escape_string($conn, $_POST['covered_area2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `covered_area`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$covered_area', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $covered_area = mysqli_real_escape_string($conn, $_POST['covered_area2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        covered_area='$covered_area',                        
                        workers_num='$workers_num',
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



                if (!empty($_POST['faction_name3_new'])) {                    
                    $number_new = count($_POST["faction_name3_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3_new'][$i]);
                        $faction_name = mysqli_real_escape_string($conn, $_POST['faction_name3_new'][$i]);
                        $faction_status = mysqli_real_escape_string($conn, $_POST['faction_status3_new'][$i]);
                        if(!empty($_POST['faction_num3_new'][$i])){
                            $faction_num = mysqli_real_escape_string($conn, $_POST['faction_num3_new'][$i]);
                        }else{
                            $faction_num = 0;
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `faction_name`, `faction_status`, `faction_num`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$faction_name', '$faction_status', '$faction_num', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['faction_name3'])) {                    
                    $number = count($_POST["faction_name3"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num3'][$i]);
                        $faction_name = mysqli_real_escape_string($conn, $_POST['faction_name3'][$i]);
                        $faction_status = mysqli_real_escape_string($conn, $_POST['faction_status3'][$i]);
                        $faction_num = mysqli_real_escape_string($conn, $_POST['faction_num3'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach3'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach3'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        faction_name='$faction_name', 
                        faction_status='$faction_status', 
                        faction_num='$faction_num',                                             
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


                if (!empty($_POST['case_type_new'])) {                    
                    $number_new = count($_POST["case_type_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        
                        $case_type = mysqli_real_escape_string($conn, $_POST['case_type_new'][$i]);
                        $persons_in_case = mysqli_real_escape_string($conn, $_POST['persons_in_case_new'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview_new'][$i]);
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `case_type`, `persons_in_case`, `case_overview`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$case_type', '$persons_in_case', '$case_overview', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
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
                        $persons_in_case = mysqli_real_escape_string($conn, $_POST['persons_in_case'][$i]);
                        $case_overview = mysqli_real_escape_string($conn, $_POST['case_overview'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_case'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_case'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        case_type='$case_type', 
                        persons_in_case='$persons_in_case', 
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



    if ($type_code == 'AC') {
              
    
        
        $grade_type = mysqli_real_escape_string($conn, $_POST['grade_type']);
        $books_type = mysqli_real_escape_string($conn, $_POST['books_type']);
        $ideas_outside_books = mysqli_real_escape_string($conn, $_POST['ideas_outside_books']);
        $students_num = mysqli_real_escape_string($conn, $_POST['students_num']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $isolation_between_males_females = mysqli_real_escape_string($conn, $_POST['isolation_between_males_females']);
        $work_time = mysqli_real_escape_string($conn, $_POST['work_time']);
        $work_duration = mysqli_real_escape_string($conn, $_POST['work_duration']);
        

        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `longitude`,  `start_working`, `grade_type`, `books_type`, `ideas_outside_books`, `students_num`, `support_source`, `location_compatibility`, `isolation_between_males_females`, `work_time`, `work_duration`, `license`, `cooperation`, `criminal_record`, `cooperation_before`, `cooperation_with_hts`, `cameras`, `suspicious_activity`, `apparent_work`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`,  `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$longitude',  '$start_working', '$grade_type', '$books_type', '$ideas_outside_books', '$students_num', '$support_source', '$location_compatibility', '$isolation_between_males_females', '$work_time', '$work_duration', '$license', '$cooperation', '$criminal_record', '$cooperation_before', '$cooperation_with_hts', '$cameras', '$suspicious_activity', '$apparent_work', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            longitude= '$longitude',
             
            start_working= '$start_working',
            grade_type= '$grade_type',
            books_type= '$books_type',
            ideas_outside_books= '$ideas_outside_books',
            students_num= '$students_num',
            support_source='$support_source',
            location_compatibility= '$location_compatibility',
            isolation_between_males_females= '$isolation_between_males_females',
            work_time= '$work_time',
            work_duration= '$work_duration',
            license= '$license',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_before= '$cooperation_before',
            cooperation_with_hts= '$cooperation_with_hts',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            apparent_work= '$apparent_work',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer', 
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }


    if ($type_code == 'CU') {
        
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);

        $overlooking = mysqli_real_escape_string($conn, $_POST['overlooking']);
        $used_military_residence = mysqli_real_escape_string($conn, $_POST['used_military_residence']);
        $residing_person_name = mysqli_real_escape_string($conn, $_POST['residing_person_name']);
        $hiring = mysqli_real_escape_string($conn, $_POST['hiring']);
       
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `longitude`,  `overlooking`, `used_military_residence`, `residing_person_name`, `hiring`, `goods_type`, `other_branches`, `criminal_record`, `cameras`, `cooperation`, `cooperation_with_hts`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$longitude',  '$overlooking', '$used_military_residence', '$residing_person_name', '$hiring', '$goods_type', '$other_branches', '$criminal_record', '$cameras', '$cooperation', '$cooperation_with_hts', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile','$details_type',  '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            longitude= '$longitude',
             
            overlooking= '$overlooking',
            used_military_residence= '$used_military_residence',
            residing_person_name= '$residing_person_name',
            hiring= '$hiring',
            goods_type= '$goods_type',
            other_branches= '$other_branches',
            criminal_record= '$criminal_record',
            cameras= '$cameras',
            cooperation= '$cooperation',
            cooperation_with_hts= '$cooperation_with_hts',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


               
            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }


    if ($type_code == 'GE') {
              
        $games_type = mysqli_real_escape_string($conn, $_POST['games_type']);


        //$start_working = mysqli_real_escape_string($conn, $_POST['start_working']);
        $max_trainees_num = mysqli_real_escape_string($conn, $_POST['max_trainees_num']);
        $current_trainees_num = mysqli_real_escape_string($conn, $_POST['current_trainees_num']);
        $gym_courses_type = mysqli_real_escape_string($conn, $_POST['gym_courses_type']);
        $gym_sport_machine = mysqli_real_escape_string($conn, $_POST['gym_sport_machine']);
        $gym_daily_routine = mysqli_real_escape_string($conn, $_POST['gym_daily_routine']);
        $males_or_females = mysqli_real_escape_string($conn, $_POST['males_or_females']);
        $ask_for_id = mysqli_real_escape_string($conn, $_POST['ask_for_id']);
        $most_trainees_type = mysqli_real_escape_string($conn, $_POST['most_trainees_type']);
        $non_syrian_trainees = mysqli_real_escape_string($conn, $_POST['non_syrian_trainees']);
        $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);

        //$services = mysqli_real_escape_string($conn, $_POST['services']);
        $monthly_fee = mysqli_real_escape_string($conn, $_POST['monthly_fee']);

        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `max_trainees_num`, `current_trainees_num`, `games_type`, `gym_courses_type`, `gym_sport_machine`, `gym_daily_routine`, `males_or_females`, `monthly_fee`, `ask_for_id`, `most_trainees_type`, `non_syrian_trainees`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$max_trainees_num', '$current_trainees_num', '$games_type', '$gym_courses_type', '$gym_sport_machine', '$gym_daily_routine', '$males_or_females', '$monthly_fee', '$ask_for_id', '$most_trainees_type', '$non_syrian_trainees', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',
            games_type= '$games_type',
            start_working= '$start_working',
            max_trainees_num= '$max_trainees_num',
            current_trainees_num= '$current_trainees_num',
            gym_courses_type= '$gym_courses_type',
            gym_sport_machine= '$gym_sport_machine',
            gym_daily_routine= '$gym_daily_routine',
            males_or_females= '$males_or_females',
            ask_for_id= '$ask_for_id',
            monthly_fee='$monthly_fee',
            most_trainees_type= '$most_trainees_type',
            non_syrian_trainees= '$non_syrian_trainees',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }


    if ($type_code == 'MS') {
              
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $teach_swimming = mysqli_real_escape_string($conn, $_POST['teach_swimming']);
        $abroad_partners = mysqli_real_escape_string($conn, $_POST['abroad_partners']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `partners_name`, `related_branch`, `work_type`, `teach_swimming`, `abroad_partners`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$partners_name', '$related_branch', '$work_type', '$teach_swimming', '$abroad_partners', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',          
            start_working= '$start_working',
            partners_name = '$partners_name', 
            related_branch = '$related_branch',
            work_type = '$work_type', 
            teach_swimming = '$teach_swimming', 
            abroad_partners = '$abroad_partners',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'PD') {
              
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $contracts = mysqli_real_escape_string($conn, $_POST['contracts']);
        $prohibitions = mysqli_real_escape_string($conn, $_POST['prohibitions']);
       
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `partners_name`, `related_branch`, `contracts`, `prohibitions`,  `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$partners_name', '$related_branch', '$contracts', '$prohibitions', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',          
            start_working= '$start_working',
            partners_name = '$partners_name', 
            related_branch = '$related_branch',
            contracts = '$contracts', 
            prohibitions = '$prohibitions',            
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }
    
    if ($type_code == 'RC') {
              
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $apparent_work = mysqli_real_escape_string($conn, $_POST['apparent_work']);
        $forbidden_things = mysqli_real_escape_string($conn, $_POST['forbidden_things']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $parties_or_meetings = mysqli_real_escape_string($conn, $_POST['parties_or_meetings']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `partners_name`, `notable_customers`, `apparent_work`, `forbidden_things`, `location_compatibility`,`parties_or_meetings`, `work_type`,  `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$partners_name', '$notable_customers', '$apparent_work', '$forbidden_things', '$location_compatibility','$parties_or_meetings','$work_type', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',          
            start_working= '$start_working',
            partners_name = '$partners_name', 
            notable_customers = '$notable_customers', 
            apparent_work = '$apparent_work', 
            forbidden_things = '$forbidden_things', 
            location_compatibility = '$location_compatibility',
            parties_or_meetings = '$parties_or_meetings',
            work_type = '$work_type',           
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SE') {
              
        $gym_sport_machine = mysqli_real_escape_string($conn, $_POST['gym_sport_machine']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $services = mysqli_real_escape_string($conn, $_POST['services']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `partners_name`, `gym_sport_machine`, `services`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$partners_name', '$gym_sport_machine', '$services', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',          
            start_working= '$start_working',
            partners_name = '$partners_name', 
            gym_sport_machine = '$gym_sport_machine', 
            services = '$services',                     
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SK') {
              
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $stolens_dealing = mysqli_real_escape_string($conn, $_POST['stolens_dealing']);
        $suspicious_relations = mysqli_real_escape_string($conn, $_POST['suspicious_relations']);
        $contracts_with_orgs = mysqli_real_escape_string($conn, $_POST['contracts_with_orgs']);
        $buying_from_traders = mysqli_real_escape_string($conn, $_POST['buying_from_traders']);
        $workplace = mysqli_real_escape_string($conn, $_POST['workplace']);
        $warsha_work_evaluation = mysqli_real_escape_string($conn, $_POST['warsha_work_evaluation']);
        $equipment_size = mysqli_real_escape_string($conn, $_POST['equipment_size']);
        $apparent_work = mysqli_real_escape_string($conn, $_POST['apparent_work']);

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `notable_customers`,`partners_name`,`related_branch`, `goods_type`, `other_branches`,`stolens_dealing`, `suspicious_relations`, `contracts_with_orgs`, `buying_from_traders`, `workplace`, `warsha_work_evaluation`,`equipment_size`,`apparent_work`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$notable_customers','$partners_name','$related_branch', '$goods_type', '$other_branches','$stolens_dealing', '$suspicious_relations', '$contracts_with_orgs', '$buying_from_traders', '$workplace', '$warsha_work_evaluation','$equipment_size','$apparent_work', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',          
            start_working = '$start_working',
            notable_customers = '$notable_customers',
            partners_name = '$partners_name',
            related_branch = '$related_branch', 
            goods_type = '$goods_type', 
            other_branches = '$other_branches',
            stolens_dealing = '$stolens_dealing', 
            suspicious_relations = '$suspicious_relations', 
            contracts_with_orgs = '$contracts_with_orgs', 
            buying_from_traders = '$buying_from_traders', 
            workplace = '$workplace', 
            warsha_work_evaluation = '$warsha_work_evaluation',
            equipment_size = '$equipment_size',
            apparent_work = '$apparent_work',                    
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SM') {
              
        $taxi_specifications = mysqli_real_escape_string($conn, $_POST['taxi_specifications']);
        $taxi_parking = mysqli_real_escape_string($conn, $_POST['taxi_parking']);
        $taxi_id = mysqli_real_escape_string($conn, $_POST['taxi_id']);
        $working_with_organizations = mysqli_real_escape_string($conn, $_POST['working_with_organizations']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $taxi_park_overlooking = mysqli_real_escape_string($conn, $_POST['taxi_park_overlooking']);
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $ethical_problems = mysqli_real_escape_string($conn, $_POST['ethical_problems']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`,  `start_working`, `taxi_specifications`, `taxi_parking`, `taxi_id`, `working_with_organizations`, `goods_type`, `taxi_park_overlooking`, `location_compatibility`, `activity_area`, `ethical_problems`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license',  '$start_working', '$taxi_specifications', '$taxi_parking', '$taxi_id', '$working_with_organizations', '$goods_type', '$taxi_park_overlooking', '$location_compatibility', '$activity_area', '$ethical_problems', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
                   
            start_working= '$start_working',
            taxi_specifications='$taxi_specifications',              
            taxi_id='$taxi_id', 
            working_with_organizations='$working_with_organizations', 
            goods_type='$goods_type', 
            taxi_park_overlooking='$taxi_park_overlooking', 
            location_compatibility='$location_compatibility', 
            activity_area='$activity_area', 
            ethical_problems='$ethical_problems', 
            taxi_parking='$taxi_parking',                     
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'DF') {
              
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $taxi_parking = mysqli_real_escape_string($conn, $_POST['taxi_parking']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `other_branches`, `taxi_parking` ,`activity_area`,`related_branch`, `goods_type`, `support_source`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$other_branches', '$taxi_parking' ,'$activity_area','$related_branch', '$goods_type', '$support_source', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',
            longitude= '$longitude',      
            start_working= '$start_working',
            other_branches = '$other_branches', 
            taxi_parking = '$taxi_parking' ,
            activity_area = '$activity_area',
            related_branch = '$related_branch',     
            goods_type='$goods_type', 
            support_source= '$support_source',      
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'MO') {
              
        $location_compatibility = mysqli_real_escape_string($conn, $_POST['location_compatibility']);
        $working_days = mysqli_real_escape_string($conn, $_POST['working_days']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $compliance = mysqli_real_escape_string($conn, $_POST['compliance']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `location_compatibility`, `working_days` ,`work_type`,`goods_type`, `compliance`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude' ,  '$start_working', '$location_compatibility', '$working_days' ,'$work_type','$goods_type', '$compliance', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',   
            longitude='$longitude',                
            location_compatibility='$location_compatibility', 
            working_days='$working_days' ,
            work_type='$work_type',
            goods_type='$goods_type', 
            compliance='$compliance',        
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'OC') {
              
        $georaphical = mysqli_real_escape_string($conn, $_POST['georaphical']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $hacking_experiance = mysqli_real_escape_string($conn, $_POST['hacking_experiance']);
        $services = mysqli_real_escape_string($conn, $_POST['services']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`,`longitude` , `start_working`, `georaphical`, `goods_type` ,`hacking_experiance`,`services`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$georaphical', '$goods_type' ,'$hacking_experiance','$services', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',    
            longitude='$longitude',               
            georaphical='$georaphical', 
            goods_type='$goods_type' ,
            hacking_experiance='$hacking_experiance',
            services='$services',        
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'BA') {
              
        $overlooking = mysqli_real_escape_string($conn, $_POST['overlooking']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $prohibitions = mysqli_real_escape_string($conn, $_POST['prohibitions']);
        $parties_or_meetings = mysqli_real_escape_string($conn, $_POST['parties_or_meetings']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`,  `start_working`, `overlooking`, `notable_customers` ,`prohibitions`,`parties_or_meetings`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$overlooking', '$notable_customers' ,'$prohibitions','$parties_or_meetings', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license', 
            longitude='$longitude',                  
            overlooking='$overlooking', 
            notable_customers='$notable_customers' ,
            prohibitions='$prohibitions',
            parties_or_meetings='$parties_or_meetings',   
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'ME') {
              
        $public_intention = mysqli_real_escape_string($conn, $_POST['public_intention']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `public_intention`, `work_type` ,`related_branch`,`notable_customers`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$public_intention', '$work_type' ,'$related_branch','$notable_customers', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',        
            longitude='$longitude',           
            public_intention='$public_intention', 
            work_type='$work_type' ,
            related_branch='$related_branch',
            notable_customers='$notable_customers',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'BM') {
              
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);
        $explosion_type = mysqli_real_escape_string($conn, $_POST['explosion_type']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $services = mysqli_real_escape_string($conn, $_POST['services']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $other_branches = mysqli_real_escape_string($conn, $_POST['other_branches']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $deal_with = mysqli_real_escape_string($conn, $_POST['deal_with']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `support_source`, `explosion_type` ,`goods_type`,`services`, `related_branch`, `other_branches`, `activity_area`, `deal_with`, `notable_customers`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$support_source', '$explosion_type' ,'$goods_type','$services', '$related_branch', '$other_branches', '$activity_area', '$deal_with', '$notable_customers', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license', 
            longitude='$longitude',                  
            support_source='$support_source', 
            explosion_type='$explosion_type' ,
            goods_type='$goods_type',
            services='$services',
            related_branch='$related_branch', 
            other_branches='$other_branches', 
            activity_area='$activity_area', 
            deal_with='$deal_with', 
            notable_customers='$notable_customers',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SO') {
              
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `goods_type`,`related_branch`,`notable_customers`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$goods_type', '$related_branch','$notable_customers', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            goods_type='$goods_type' ,
            related_branch='$related_branch',
            notable_customers='$notable_customers',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'SW') {
              
        $taxi_specifications = mysqli_real_escape_string($conn, $_POST['taxi_specifications']);
        $working_with_organizations = mysqli_real_escape_string($conn, $_POST['working_with_organizations']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $taxi_parking = mysqli_real_escape_string($conn, $_POST['taxi_parking']);
        $taxi_park_overlooking = mysqli_real_escape_string($conn, $_POST['taxi_park_overlooking']);
        $partners_name = mysqli_real_escape_string($conn, $_POST['partners_name']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`,  `start_working`, `taxi_specifications`,`working_with_organizations`,`activity_area`, `taxi_parking`,`taxi_park_overlooking`,`partners_name`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license',  '$start_working', '$taxi_specifications','$working_with_organizations','$activity_area', '$taxi_parking','$taxi_park_overlooking','$partners_name', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',                   
            taxi_specifications='$taxi_specifications',
            working_with_organizations='$working_with_organizations',
            activity_area='$activity_area', 
            taxi_parking='$taxi_parking',
            taxi_park_overlooking='$taxi_park_overlooking',
            partners_name='$partners_name',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'TO') {
              
        $monthly_fee = mysqli_real_escape_string($conn, $_POST['monthly_fee']);
        $forbidden_things = mysqli_real_escape_string($conn, $_POST['forbidden_things']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $deal_with = mysqli_real_escape_string($conn, $_POST['deal_with']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `monthly_fee`,`forbidden_things`,`work_type`, `deal_with`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$monthly_fee', '$forbidden_things','$work_type', '$deal_with', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            monthly_fee='$monthly_fee', 
            forbidden_things='$forbidden_things',
            work_type='$work_type', 
            deal_with='$deal_with',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'TP') {
              
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);
        $source = mysqli_real_escape_string($conn, $_POST['source']);
        $notable_customers = mysqli_real_escape_string($conn, $_POST['notable_customers']);
        $activity_area = mysqli_real_escape_string($conn, $_POST['activity_area']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `goods_type`, `support_source`,`notable_customers`, `related_branch`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$goods_type', '$support_source', '$notable_customers', '$related_branch', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            goods_type='$goods_type' ,
            support_source='$support_source',           
            notable_customers='$notable_customers',
            related_branch='$related_branch',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'ET') {
              
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);
        $related_branch = mysqli_real_escape_string($conn, $_POST['related_branch']);
        $dealing_with_others = mysqli_real_escape_string($conn, $_POST['dealing_with_others']);
        $where_sell = mysqli_real_escape_string($conn, $_POST['where_sell']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `work_type`, `goods_type`, `support_source`, `related_branch`, `dealing_with_others`,`where_sell`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$work_type', '$goods_type', '$support_source', '$related_branch', '$dealing_with_others', '$where_sell', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            work_type='$work_type', 
            goods_type='$goods_type', 
            support_source='$support_source',
            source='$source', 
            related_branch='$related_branch', 
            dealing_with_others='$dealing_with_others', 
            where_sell='$where_sell',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'PA') {
              
        $abroad_relations = mysqli_real_escape_string($conn, $_POST['abroad_relations']);
        $public_intention = mysqli_real_escape_string($conn, $_POST['public_intention']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `abroad_relations`, `public_intention`, `work_type`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$abroad_relations', '$public_intention', '$work_type', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            abroad_relations= '$abroad_relations', 
            public_intention= '$public_intention', 
            work_type= '$work_type',
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'CR') {
              
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $warsha_work_evaluation = mysqli_real_escape_string($conn, $_POST['warsha_work_evaluation']);
        $workplace = mysqli_real_escape_string($conn, $_POST['workplace']);
        $equipment_size = mysqli_real_escape_string($conn, $_POST['equipment_size']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `work_type`, `warsha_work_evaluation`, `workplace`, `equipment_size`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$work_type', '$warsha_work_evaluation', '$workplace', '$equipment_size', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            work_type='$work_type', 
            warsha_work_evaluation='$warsha_work_evaluation', 
            workplace='$workplace', 
            equipment_size='$equipment_size',             
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'DC') {
              
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $apparent_work = mysqli_real_escape_string($conn, $_POST['apparent_work']);
        $teach_swimming = mysqli_real_escape_string($conn, $_POST['teach_swimming']);
        $investments = mysqli_real_escape_string($conn, $_POST['investments']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `goods_type`, `work_type`, `apparent_work`, `teach_swimming`, `investments`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$goods_type', '$work_type', '$apparent_work', '$teach_swimming', '$investments', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            goods_type='$goods_type', 
            work_type='$work_type', 
            apparent_work='$apparent_work', 
            teach_swimming='$teach_swimming', 
            investments='$investments',           
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }
    

    if ($type_code == 'MA') {
       
        
     
        $trade_type= mysqli_real_escape_string($conn, $_POST['trade_type']); 
        $capital= mysqli_real_escape_string($conn, $_POST['capital']); 
         
         
         
        
         
        $trade_log_num= mysqli_real_escape_string($conn, $_POST['trade_log_num']); 
        if (!empty($_POST['trade_log_date'])) {
            $trade_log_date = mysqli_real_escape_string($conn, $_POST['trade_log_date']);
        } else {
            $trade_log_date =mysqli_real_escape_string($conn, '0000-00-00');
        }
        $trade_activity= mysqli_real_escape_string($conn, $_POST['trade_activity']); 
        $log_work_period= mysqli_real_escape_string($conn, $_POST['log_work_period']); 
        $mowakel_name= mysqli_real_escape_string($conn, $_POST['mowakel_name']); 
        $mowakel_fname= mysqli_real_escape_string($conn, $_POST['mowakel_fname']); 
        $mowakel_mname= mysqli_real_escape_string($conn, $_POST['mowakel_mname']); 
        $mowakel_nationality= mysqli_real_escape_string($conn, $_POST['mowakel_nationality']); 
        $wkala_type= mysqli_real_escape_string($conn, $_POST['wkala_type']); 
        $wkala_duration= mysqli_real_escape_string($conn, $_POST['wkala_duration']); 
        $wkala_subject= mysqli_real_escape_string($conn, $_POST['wkala_subject']); 
        $trade_name= mysqli_real_escape_string($conn, $_POST['trade_name']); 
        $georaphical= mysqli_real_escape_string($conn, $_POST['georaphical']); 
        //$main_company_address= mysqli_real_escape_string($conn, $_POST['main_company_address']); 
        $wkala_company_address= mysqli_real_escape_string($conn, $_POST['wkala_company_address']); 
        

        
   
        $trader_card= mysqli_real_escape_string($conn, $_POST['trader_card']); 
        $personal_weapon= mysqli_real_escape_string($conn, $_POST['personal_weapon']); 
        


        



               
        
       
        
        
        
        
       


    
            
       

        
        

        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `trade_type`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `capital`, `trade_log_num`, `trade_log_date`, `trade_activity`, `log_work_period`, `mowakel_name`, `mowakel_fname`, `mowakel_mname`, `mowakel_nationality`, `wkala_type`, `wkala_duration`, `wkala_subject`, `trade_name`, `georaphical`, `place_address`, `wkala_company_address`, `socialmedia`, `start_working`, `longitude`,  `trader_card`, `personal_weapon`, `apparent_work`, `cameras`, `license`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$trade_type', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$capital', '$trade_log_num', '$trade_log_date', '$trade_activity', '$log_work_period', '$mowakel_name', '$mowakel_fname', '$mowakel_mname', '$mowakel_nationality', '$wkala_type', '$wkala_duration', '$wkala_subject', '$trade_name', '$georaphical', '$place_address', '$wkala_company_address', '$socialmedia', '$start_working', '$longitude',  '$trader_card', '$personal_weapon', '$apparent_work', '$cameras', '$license', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name', 
            trade_type='$trade_type', 
            name='$name', 
            fname='$fname', 
            mname='$mname', 
            personal_code='$personal_code', 
            studies_attach='$studies_attach', 
            place_address='$place_address', 
            trade_log_num='$trade_log_num', 
            trade_log_date='$trade_log_date', 
            trade_activity='$trade_activity', 
            log_work_period='$log_work_period', 
            mowakel_name='$mowakel_name', 
            mowakel_fname='$mowakel_fname', 
            mowakel_mname='$mowakel_mname', 
            mowakel_nationality='$mowakel_nationality', 
            wkala_type='$wkala_type', 
            wkala_duration='$wkala_duration', 
            wkala_subject='$wkala_subject', 
            trade_name='$trade_name', 
            georaphical='$georaphical', 
            capital='$capital', 
            wkala_company_address='$wkala_company_address', 
            socialmedia='$socialmedia', 
            start_working='$start_working', 
            longitude='$longitude', 
             
            trader_card='$trader_card', 
            personal_weapon='$personal_weapon', 
            apparent_work='$apparent_work', 
            cameras='$cameras', 
            license='$license', 
            cooperation='$cooperation', 
            criminal_record='$criminal_record', 
            cooperation_with_hts='$cooperation_with_hts', 
            cooperation_before='$cooperation_before', 
            suspicious_activity='$suspicious_activity', 
            additional_information='$additional_information', 
            result='$result', 
            suggestion='$suggestion', 
            source='$source', 
            study_preparer='$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
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


                if (!empty($_POST['branch_name2_new'])) {                    
                    $number_new = count($_POST["branch_name2_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2_new'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2_new'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2_new'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2_new'][$i]);
                        if (!empty($_POST['date_of_birth2_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2_new'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2_new'][$i]);
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `branch_name`, `address`, `work_type`, `manager_name`, `place_of_birth`, `date_of_birth`, `general_reputation`, `workers_num`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$branch_name', '$address', '$work_type', '$manager_name', '$place_of_birth', '$date_of_birth', '$general_reputation', '$workers_num', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }
                if (!empty($_POST['branch_name2'])) {                    
                    $number = count($_POST["branch_name2"]);
                } else {
                    $number =mysqli_real_escape_string($conn, 0);
                }

              
                if ($number >= 1) {
                    for ($i=0; $i<$number; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num2'][$i]);
                        $branch_name = mysqli_real_escape_string($conn, $_POST['branch_name2'][$i]);
                        $address = mysqli_real_escape_string($conn, $_POST['address2'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type2'][$i]);
                        $manager_name = mysqli_real_escape_string($conn, $_POST['manager_name2'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth2'][$i]);
                        if (!empty($_POST['date_of_birth2'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth2'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                        $general_reputation = mysqli_real_escape_string($conn, $_POST['general_reputation2'][$i]);
                        $workers_num = mysqli_real_escape_string($conn, $_POST['workers_num2'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes2'][$i]);
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach2'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach2'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                         general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        branch_name='$branch_name', 
                        address='$address', 
                        work_type='$work_type', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        manager_name='$manager_name', 
                        general_reputation='$general_reputation',
                        workers_num='$workers_num',
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

    if ($type_code == 'OH') {
              
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        if (!empty($_POST['ages_to_15'])) {
            $ages_to_15 = mysqli_real_escape_string($conn, $_POST['ages_to_15']);
        } else {
            $ages_to_15 =mysqli_real_escape_string($conn, 0);
        }
        $contracts = mysqli_real_escape_string($conn, $_POST['contracts']);
        $services = mysqli_real_escape_string($conn, $_POST['services']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `goods_type`, `work_type`, `ages_to_15`, `contracts`, `services`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$goods_type', '$work_type', $ages_to_15, '$contracts', '$services', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            goods_type='$goods_type', 
            work_type='$work_type', 
            ages_to_15=$ages_to_15, 
            contracts='$contracts', 
            services='$services',                       
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'OH') {
              
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        if (!empty($_POST['ages_to_15'])) {
            $ages_to_15 = mysqli_real_escape_string($conn, $_POST['ages_to_15']);
        } else {
            $ages_to_15 =mysqli_real_escape_string($conn, 0);
        }
        $contracts = mysqli_real_escape_string($conn, $_POST['contracts']);
        $services = mysqli_real_escape_string($conn, $_POST['services']);
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `goods_type`, `work_type`, `ages_to_15`, `contracts`, `services`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$goods_type', '$work_type', $ages_to_15, '$contracts', '$services', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            goods_type='$goods_type', 
            work_type='$work_type', 
            ages_to_15=$ages_to_15, 
            contracts='$contracts', 
            services='$services',                       
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'BO') {
              
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $support_source = mysqli_real_escape_string($conn, $_POST['support_source']);        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `work_type`, `support_source`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working','$work_type', '$support_source', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            work_type='$work_type',             
            support_source='$support_source',                       
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'DE') {
              
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        
        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `work_type`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working', '$work_type',  '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',        
            work_type='$work_type', 
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
                            exit;
                        }
                    }
                }


            

        } else {
            echo "Error: " . "<br>" . mysqli_error($conn);
            exit;
        }
    }

    if ($type_code == 'FC') {
              
        $goods_type = mysqli_real_escape_string($conn, $_POST['goods_type']);
        $work_type = mysqli_real_escape_string($conn, $_POST['work_type']);
        $apparent_work = mysqli_real_escape_string($conn, $_POST['apparent_work']);
        $teach_swimming = mysqli_real_escape_string($conn, $_POST['teach_swimming']);
        $investments = mysqli_real_escape_string($conn, $_POST['investments']);

        
        if ($_GET['edit']=='0') {
            $general_code = mysqli_real_escape_string($conn, $general_code);
            $area_code = mysqli_real_escape_string($conn, $area_code);
            $city_code = mysqli_real_escape_string($conn, $city_code);
            $type_code = mysqli_real_escape_string($conn, $type_code);
            $type_num = mysqli_real_escape_string($conn, $type_num);

            
            $sql="INSERT INTO `$table_name` (`ketab_num`, `ketab_date`, `general_code`, `area_code`, `city_code`, `type_code`, `type_num`, `place_name`, `name`, `fname`, `mname`, `personal_code`, `studies_attach`, `place_address`, `socialmedia`, `license`, `longitude`, `start_working`, `work_type`, `source`, `ownership`, `cooperation`, `criminal_record`, `cooperation_with_hts`, `cooperation_before`, `cameras`, `suspicious_activity`, `additional_information`, `result`, `suggestion`, `source`, `study_preparer`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES
            ($ketab_num, '$ketab_date', '$general_code', '$area_code', '$city_code', '$type_code', '$type_num', '$place_name', '$name', '$fname', '$mname', '$personal_code', '$studies_attach', '$place_address', '$socialmedia', '$license', '$longitude',  '$start_working','$work_type', '$source', '$ownership', '$cooperation', '$criminal_record', '$cooperation_with_hts', '$cooperation_before', '$cameras', '$suspicious_activity', '$additional_information', '$result', '$suggestion', '$source', '$study_preparer', '$jeha_profile', '$details_type', '$added_by',  current_timestamp() )";
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
            type_num='$type_num',
            place_name='$place_name',
            name= '$name',
            fname= '$fname',
            mname= '$mname',
            personal_code= '$personal_code',
            studies_attach= '$studies_attach',
            place_address= '$place_address',
            socialmedia= '$socialmedia',
            license= '$license',          
            longitude='$longitude',         
            work_type='$work_type',             
            source='$source',                       
            ownership= '$ownership',
            cooperation= '$cooperation',
            criminal_record= '$criminal_record',
            cooperation_with_hts= '$cooperation_with_hts',
            cooperation_before= '$cooperation_before',
            cameras= '$cameras',
            suspicious_activity= '$suspicious_activity',
            additional_information= '$additional_information',
            result= '$result',
            suggestion= '$suggestion',
            source= '$source',
            study_preparer= '$study_preparer',
            jeha='$inserted_jeha',
            details_type='$details_type',
            added_by='$added_by_old - $added_by', 
            add_date=current_timestamp() 
            WHERE id = $id";
        }


        if (mysqli_query($conn, $sql)) {  
            
                if (!empty($_POST['name1_new'])) {                    
                    $number_new = count($_POST["name1_new"]);
                } else {
                    $number_new =mysqli_real_escape_string($conn, 0);
                }
                
                if ($number_new >= 1) {
                    for ($i=0; $i<$number_new; $i++) {
                        $num = mysqli_real_escape_string($conn, $_POST['num1_new'][$i]);
                        $name = mysqli_real_escape_string($conn, $_POST['name1_new'][$i]);
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1_new'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1_new'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1_new'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1_new'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1_new'][$i]);
                        if (!empty($_POST['date_of_birth1_new'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1_new'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                       
                        
                        $sql_new="INSERT INTO `$studies_2022_attachments`(`ketab_num`, `ketab_date`, `general_code`, `num`, `name`, `fname`, `mname`, `place_of_birth`, `date_of_birth`, `work_type`, `notes`, `jeha`, `details_type`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$num', '$name', '$fname', '$mname', '$place_of_birth', '$date_of_birth', '$work_type', '$notes', '$jeha_profile', '$details_type', '$added_by', current_timestamp())";
                        if (mysqli_query($conn, $sql_new)){
                        }else{
                            echo "Error: $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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
                        $fname = mysqli_real_escape_string($conn, $_POST['fname1'][$i]);
                        $mname = mysqli_real_escape_string($conn, $_POST['mname1'][$i]);
                        $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth1'][$i]);
                        $work_type = mysqli_real_escape_string($conn, $_POST['work_type1'][$i]);
                        $notes = mysqli_real_escape_string($conn, $_POST['notes1'][$i]);
                        if (!empty($_POST['date_of_birth1'][$i])) {
                            $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth1'][$i]);
                        } else {
                            $date_of_birth =mysqli_real_escape_string($conn, '0000-00-00');
                        }
                      
                        $added_by_old = mysqli_real_escape_string($conn, $_POST['added_by_old_attach1'][$i]);
                        $id_attach = mysqli_real_escape_string($conn, $_POST['id_attach1'][$i]);

                        $sql= "UPDATE `$studies_2022_attachments` SET 
                        general_code='$general_code',
                        ketab_num=$ketab_num, 
                        ketab_date='$ketab_date',
                        num='$num', 
                        name='$name', 
                        fname='$fname', 
                        mname='$mname', 
                        place_of_birth='$place_of_birth', 
                        date_of_birth='$date_of_birth', 
                        work_type='$work_type', 
                        notes='$notes',                      
                        added_by='$added_by_old - $added_by', 
                        add_date=current_timestamp()
                        WHERE id = $id_attach";
                    
                        if (mysqli_query($conn, $sql)){
                        }else{
                            echo "Error: edit $studies_2022_attachments " . "<br>" . mysqli_error($conn);
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

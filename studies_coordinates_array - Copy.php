<?php

  
    $zone = mysqli_real_escape_string($conn, $_POST['zone_attach'][$i]);
    $x = mysqli_real_escape_string($conn, $_POST['easting_attach'][$i]);
    $y = mysqli_real_escape_string($conn, $_POST['northing_attach'][$i]);
    $longitude_attach= $zone.' '.$x.' '.$y;
    
    if(!empty($_POST['place_name'])){
        $placename=mysqli_real_escape_string($conn, $_POST['place_name']);
    }else{
        $placename='';
    }         

   /*  $sections = explode(" ", $longitude);
   

    $zone = $sections[0];    // "27S"
    $x = $sections[1];    // "XXXXXX"
    $y = $sections[2];    // "YYYYYYY" */

    if($zone == '36S'){
        $get_number='32636';
    }elseif($zone == '38S'){
        $get_number='32638';
    }elseif($zone == '37S'){
        $get_number='32637';
    }else{
        $get_number='32637';
    }

    
   
    /* echo '"'.$number.'"<br>';    // Output: "27S"
    echo '"'.$firstSection.'"<br>';    // Output: "XXXXXX"
    echo '"'.$secondSection.'"';    // Output: "YYYYYYY" */

    $sql="INSERT INTO `coordinates` (`ketab_num`, `ketab_date`, `general_code`, `name`, `jeha`, `details_type`, `zone`,`coordinate`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$placename', '$jeha_profile', '$details_type', '$zone', ST_GeomFromText('POINT($x $y)', $get_number), '$user', current_timestamp())";

    if(mysqli_query($conn, $sql)){ 
                         
    }else{
        echo "Error coordinates: " . "<br>" . mysqli_error($conn);
        exit;
    }


?>
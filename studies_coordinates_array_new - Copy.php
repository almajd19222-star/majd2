<?php   
    $sections = explode(" ", $longitude);
   
    $zone = $sections[0];    // "27S"
    $x = $sections[1];    // "XXXXXX"
    $y = $sections[2];    // "YYYYYYY"

    if($zone == '36S' || $zone == '36s'){
        $get_number='32636';
        $zone = '36S';
    }elseif($zone == '38S' || $zone == '38s'){
        $get_number='32638';
        $zone = '38S';
    }elseif($zone == '37S' || $zone == '37s'){
        $get_number='32637';
        $zone = '37S';
    }else{
        $get_number='32637';
    }

    $sql="INSERT INTO `coordinates` (`ketab_num`, `ketab_date`, `general_code`, `name`, `jeha`, `details_type`, `zone`,`coordinate`, `added_by`, `add_date`) VALUES ($ketab_num, '$ketab_date', '$general_code', '$placename', '$jeha_profile', '$details_type', '$zone', ST_GeomFromText('POINT($x $y)', $get_number), '$user', current_timestamp())";

    if(mysqli_query($conn, $sql)){ 
                         
    }else{
        echo "Error coordinates: " . "<br>" . mysqli_error($conn);
        exit;
    }
?>
<?php
    if ($type_code == 'SH') {
        $table_name= "studies_kiosks";
    }
    if ($type_code == 'UV') {
        $table_name= "studies_universities";
    }
    if ($type_code == 'RF') {
        $table_name= "studies_factions";
    }
    if ($type_code == 'CE') {
        $table_name= "studies_unofficial_civil_activities";
    }
    if ($type_code == 'ES') {
        $table_name= "studies_it_shops";
    }
    if ($type_code == 'RE') {
        $table_name= "studies_estate_offices";
    }
    if ($type_code == 'TR') {
        $table_name= "studies_smugglers";
    }
    if ($type_code == 'UD') {
        $table_name= "studies_weapon_traders";
    }
    if ($type_code == 'AS') {
        $table_name= "studies_association";
    }
    if ($type_code == 'MC') {
        $table_name= "studies_computers_phones_shops";
    }
    if ($type_code == 'FP') {
        $table_name= "studies_fertilizers_and_pesticides";
    }
    if ($type_code == 'WA') {
        $table_name= "studies_weapon_shops";
    }
    if ($type_code =='CH') {
        $table_name= "studies_exchange_shops";
    }
    if ($type_code =='TC') {
        $table_name= "studies_training_centre";
    }
    if ($type_code == 'FS') {
        $table_name= "studies_forgery_and_stamps_offices";
    }
    if ($type_code == 'CO') {
        $table_name= "studies_car_shops";
    }
    if ($type_code == 'OR') {
        $table_name= "studies_organizations";
    }

    if ($type_code == 'QU' || $type_code == 'CA' || $type_code == 'JS' || $type_code == 'TM' || $type_code == 'SC' || $type_code == 'CI' || $type_code == 'TX' || $type_code == 'LW' || $type_code == 'BS' || $type_code == 'SI' || $type_code == 'HO' || $type_code == 'SP' || $type_code == 'CK' || $type_code == 'CD' || $type_code == 'AP' || $type_code == 'FA' || $type_code == 'PH' || $type_code == 'PP' || $type_code == 'MP' || $type_code == 'FG' || $type_code == 'BB' || $type_code == 'MW' || $type_code == 'PS' || $type_code == 'IF' || $type_code == 'CL' || $type_code == 'RC' || $type_code == 'SM' || $type_code == 'PD' || $type_code == 'MS' || $type_code == 'SE' || $type_code == 'AC' || $type_code == 'CU' || $type_code == 'SK' || $type_code == 'GE' || $type_code == 'DF' || $type_code == 'MO' || $type_code == 'OC' || $type_code == 'BA' || $type_code == 'ME' || $type_code == 'BM' || $type_code == 'SO' || $type_code == 'SW' || $type_code == 'TO' || $type_code == 'TP' || $type_code == 'ET' || $type_code == 'PA' || $type_code == 'CR' || $type_code == 'DC' || $type_code == 'GA' || $type_code == 'MA' || $type_code == 'OH' || $type_code == 'BO' || $type_code == 'DE' || $type_code == 'FC') {
        $table_name= "studies_2022";
    }
    
    
    ////////// 828 //////////
        if($type_code == 'T'){
            $table_name = 'studies_828_town';
        }
        if($type_code == 'G'){
            $table_name = 'studies_828_goal';
        }
        if($type_code == 'S'){
            $table_name =  "studies_828_military_site_study";
        }

        if($type_code == 'SCE'){
            $table_name =  "studies_828_security_center_study";
        }

        if($type_code == 'CP'){
            $table_name =  "studies_828_checkpoint_study";
        }
        if($type_code == 'SPE'){
            $table_name =  "studies_828_personal_security_study";
        }
    ////////// 828 //////////

    ?>
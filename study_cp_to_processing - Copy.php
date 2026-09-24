<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php

$sql="SET FOREIGN_KEY_CHECKS = 0";
mysqli_query($conn, $sql);

$id=$_GET['id'];

// ============ البحث والتحديث في جدول المتابعة ============
$sql_study_get = "SELECT `name`, `ketab_num`, `ketab_date` FROM `study` WHERE `id` = " . (int)$id;
$result_study_get = mysqli_query($conn, $sql_study_get);
$study_data = mysqli_fetch_assoc($result_study_get);

if (!$study_data) {
    echo "خطأ: لم يتم العثور على الدراسة";
    exit;
}

$study_name = $study_data['name'];
$ketab_num = $study_data['ketab_num'];
$ketab_date = $study_data['ketab_date'];

$updated_records = [];
$not_updated_records = [];
$not_found_records = [];

$sql_search = "SELECT * FROM `work_tracking`
               WHERE `study_for` = '" . mysqli_real_escape_string($conn, $study_name) . "'
               AND `jeha` = '" . mysqli_real_escape_string($conn, $jeha_profile) . "'";

$result_search = mysqli_query($conn, $sql_search);

if (mysqli_num_rows($result_search) > 0) {
    $tracking_record = mysqli_fetch_assoc($result_search);
    $tracking_id = $tracking_record['id'];
    $current_status = $tracking_record['status'];

    if ($current_status === 'لم يتم الرد') {
        $sql_update = "UPDATE `work_tracking`
                       SET `status` = 'قيد العمل',
                           `ketab_num_reply` = " . (int)$ketab_num . ",
                           `reply_date` = '" . mysqli_real_escape_string($conn, $ketab_date) . "'
                       WHERE `id` = " . (int)$tracking_id;

        if (mysqli_query($conn, $sql_update)) {
            $updated_records[] = [
                'name' => $study_name,
                'new_status' => 'قيد العمل',
                'ketab_num' => $ketab_num,
                'ketab_date' => $ketab_date
            ];
        } else {
            echo "خطأ في تحديث جدول المتابعة: " . mysqli_error($conn);
            exit;
        }
    } else {
        $not_updated_records[] = [
            'name' => $study_name,
            'current_status' => $current_status,
            'reason' => 'لم يتم التحديث - الحالة ليست "لم يتم الرد"'
        ];
    }
} else {
    $not_found_records[] = [
        'name' => $study_name,
        'reason' => 'لم يتم العثور على سجل في جدول المتابعة'
    ];
}

// ============ النسخ الأصلي للدراسة ============
$sql_toProcess = "INSERT INTO `study` (`general_code`, `study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `isReport`, `e_id`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `ketab_num`, `ketab_date`, `dewan_num`, `dewan_date`, `ketab_type`, `isPrivate`, `origin_jeha`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`)

SELECT general_code, study_num, study_num_date, study_request_jeha, study_reason, study_date, nick_name, name, fname, lname, mname, pbirth, dbirth, subname, sex, national, awsaf, family_status, child_num, wife_name, wife_address, work_before, work_after, work_now, study, money_status, dealing, service, special, pre_address, address, address_type, fasael, opinion, travels, n_relatives, d_relatives, f_relatives, s_relatives, religon_status, mind, lead, personal, affected_others, affected_to, relation_others, speak, important_awsaf, sawabek, phone, details, brief, foto1, foto2, foto3, attach, attach_extension, '$user', current_timestamp(), 0, '0000-00-00', '$jeha_profile', 0, 0, 'قيد المعالجة', resala_type, sendto, sendfrom, study_jeha, study_organizer, study_masdar, study_opinion, study_result, negative_reason, ketab_num, ketab_date, dewan_num, dewan_date, ketab_type, isPrivate, origin_jeha, id_num, nafeer_date, entisab_date, service_place, hts_opinion, hts_work_details, estimara_num, talab_num, wives_num, tasreeh_date

FROM `study` WHERE `id` = " . (int)$id;

if(mysqli_query($conn, $sql_toProcess)){
    $sql="SET FOREIGN_KEY_CHECKS = 1";
    mysqli_query($conn, $sql);

    $_SESSION['tracking_updated'] = $updated_records;
    $_SESSION['tracking_not_updated'] = $not_updated_records;
    $_SESSION['tracking_not_found'] = $not_found_records;
    $_SESSION['show_tracking_result'] = true;

    header ("Location: studies_all_view_qayd.php?jeha=$jeha_profile&details_type=قيد المعالجة");
    exit;
}else{
    echo "Error insert into study process status: "  . "<br>" . mysqli_error($conn);
    exit;
}

?>

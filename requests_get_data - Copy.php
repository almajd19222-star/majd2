<?php
include_once "inc/session.php";
include_once('inc/config_server.php');
include_once "inc/ensure_imported_requests.php";

// مزوّد بيانات DataTables (معالجة من جهة الخادم) لجدول الطلبات المستوردة
$table      = 'imported_requests';
$primaryKey = 'id';

$columns = array(
    array('db' => 'id',                'dt' => 'id'),
    array('db' => 'category',          'dt' => 'category'),
    array('db' => 'request_num',       'dt' => 'request_num'),
    array('db' => 'name',              'dt' => 'name'),
    array('db' => 'father_name',       'dt' => 'father_name'),
    array('db' => 'surname',           'dt' => 'surname'),
    array('db' => 'mother_name',       'dt' => 'mother_name'),
    array('db' => 'national_id',       'dt' => 'national_id'),
    array('db' => 'birth_date',        'dt' => 'birth_date'),
    array('db' => 'birth_place',       'dt' => 'birth_place'),
    array('db' => 'status',            'dt' => 'status'),
    array('db' => 'reason',            'dt' => 'reason'),
    array('db' => 'requesting_entity', 'dt' => 'requesting_entity'),
    array('db' => 'entity',            'dt' => 'entity'),
    array('db' => 'source',            'dt' => 'source'),
    array('db' => 'reason2',           'dt' => 'reason2'),
    array('db' => 'entity2',           'dt' => 'entity2'),
    array('db' => 'contact_num',       'dt' => 'contact_num'),
    array('db' => 'transaction_num',   'dt' => 'transaction_num'),
    array('db' => 'submit_date',       'dt' => 'submit_date'),
    array('db' => 'review_date',       'dt' => 'review_date'),
    array('db' => 'result',            'dt' => 'result'),
    array('db' => 'add_date',          'dt' => 'add_date'),
);

require('ssp.class.php');

echo json_encode(
    SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns)
);

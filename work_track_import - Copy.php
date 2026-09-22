<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php

if ($hasPerm_work_tracking != 1 || !in_array($admin, [1, 7, 2, 5, 9])) {
    header("Location: index.php");
    exit;
}

$import_done = false;
$inserted_count = 0;
$failed_rows = [];
$fatal_error = '';

$header_to_col = [
    'الرقم الذاتي'                       => 'id_num',
    'تاريخ الاستلام'                     => 'handle_from_date',
    'رقم الكتاب الوارد'                  => 'ketab_num_wared',
    'الجهة الطالبة'                      => 'requested_jeha',
    'الجهة المكلفة'                      => 'send_to',
    'رقم الكتاب الصادر'                  => 'ketab_num_sader',
    'تاريخ الصدور'                       => 'takleef_date',
    'رقم كتاب رد الجهة المكلفة'           => 'ketab_num_reply',
    'تاريخ الورود من الجهة المكلفة'        => 'reply_date',
    'رقم كتاب الرد على الجهة الطالبة'      => 'ketab_num_to',
    'تاريخ تسليم الجهة الطالبة'           => 'handle_to_date',
    'الأخ المكلف'                        => 'takleef_name',
    'المطلوب للدراسة'                    => 'study_for',
    'ملاحظات'                           => 'notes',
    'الحالة'                            => 'status',
];

$number_cols = ['id_num', 'ketab_num_wared', 'ketab_num_sader', 'ketab_num_reply', 'ketab_num_to'];
$date_cols   = ['handle_from_date', 'takleef_date', 'reply_date', 'handle_to_date'];

function wt_norm($v) {
    if ($v === null) return '';
    $s = trim((string)$v);
    $s = str_replace(["\xC2\xA0", "\xE2\x80\x8E", "\xE2\x80\x8F"], '', $s); // NBSP, LRM, RLM
    return trim($s);
}

function wt_normalize_date($v) {
    $s = wt_norm($v);
    if ($s === '') return '0000-00-00';
    if (is_numeric($s)) {
        try {
            $d = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$s);
            return $d->format('Y-m-d');
        } catch (Throwable $e) {
            return '0000-00-00';
        }
    }
    $ts = strtotime($s);
    if ($ts === false) return '0000-00-00';
    return date('Y-m-d', $ts);
}

if (isset($_POST['submit']) && isset($_FILES['import_file'])) {
    $f = $_FILES['import_file'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $fatal_error = 'فشل في رفع الملف';
    } else {
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls'])) {
            $fatal_error = 'يجب أن يكون الملف من نوع xlsx أو xls';
        } else {
            require_once __DIR__ . '/sejil_mawared/vendor/autoload.php';
            try {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($f['tmp_name']);
                $reader->setReadDataOnly(true);
                $ss = $reader->load($f['tmp_name']);
                $sheet = $ss->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true);

                if (count($rows) < 2) {
                    $fatal_error = 'الملف لا يحتوي على بيانات';
                } else {
                    $header_row = array_shift($rows);
                    $col_map = [];
                    foreach ($header_row as $col_letter => $header_val) {
                        $h = wt_norm($header_val);
                        if (isset($header_to_col[$h])) {
                            $col_map[$col_letter] = $header_to_col[$h];
                        }
                    }

                    $sql_max = "SELECT MAX(num) AS m FROM work_tracking";
                    $r_max = mysqli_query($conn, $sql_max);
                    $row_max = mysqli_fetch_assoc($r_max);
                    $next_num = ((int)$row_max['m']) + 1;

                    $added_by_safe = mysqli_real_escape_string($conn, $_SESSION['user']);
                    $jeha_safe     = mysqli_real_escape_string($conn, $jeha_profile);

                    $line_no = 1;
                    foreach ($rows as $row) {
                        $line_no++;

                        $is_blank = true;
                        foreach ($row as $v) {
                            if (wt_norm($v) !== '') { $is_blank = false; break; }
                        }
                        if ($is_blank) continue;

                        $data = [
                            'id_num'           => 0,
                            'status'           => '',
                            'takleef_name'     => '',
                            'requested_jeha'   => '',
                            'handle_from_date' => '0000-00-00',
                            'ketab_num_wared'  => 0,
                            'takleef_date'     => '0000-00-00',
                            'ketab_num_sader'  => 0,
                            'send_to'          => '',
                            'reply_date'       => '0000-00-00',
                            'ketab_num_reply'  => 0,
                            'handle_to_date'   => '0000-00-00',
                            'ketab_num_to'     => 0,
                            'study_for'        => '',
                            'notes'            => '',
                        ];

                        foreach ($col_map as $letter => $db_col) {
                            $raw = isset($row[$letter]) ? $row[$letter] : '';
                            if (in_array($db_col, $date_cols, true)) {
                                $data[$db_col] = wt_normalize_date($raw);
                            } elseif (in_array($db_col, $number_cols, true)) {
                                $s = wt_norm($raw);
                                $data[$db_col] = ($s === '' || !is_numeric($s)) ? 0 : (int)$s;
                            } else {
                                $data[$db_col] = wt_norm($raw);
                            }
                        }

                        $e = function ($v) use ($conn) { return mysqli_real_escape_string($conn, $v); };

                        $sql = "INSERT INTO `work_tracking`
                            (`id_num`,`status`,`num`,`takleef_name`,`requested_jeha`,`handle_from_date`,`ketab_num_wared`,`takleef_date`,`ketab_num_sader`,`send_to`,`reply_date`,`ketab_num_reply`,`handle_to_date`,`ketab_num_to`,`study_for`,`notes`,`jeha`,`added_by`,`add_date`)
                            VALUES (
                                '" . $e($data['id_num']) . "',
                                '" . $e($data['status']) . "',
                                " . $next_num . ",
                                '" . $e($data['takleef_name']) . "',
                                '" . $e($data['requested_jeha']) . "',
                                '" . $e($data['handle_from_date']) . "',
                                " . (int)$data['ketab_num_wared'] . ",
                                '" . $e($data['takleef_date']) . "',
                                " . (int)$data['ketab_num_sader'] . ",
                                '" . $e($data['send_to']) . "',
                                '" . $e($data['reply_date']) . "',
                                " . (int)$data['ketab_num_reply'] . ",
                                '" . $e($data['handle_to_date']) . "',
                                '" . $e($data['ketab_num_to']) . "',
                                '" . $e($data['study_for']) . "',
                                '" . $e($data['notes']) . "',
                                '" . $jeha_safe . "',
                                '" . $added_by_safe . "',
                                current_timestamp()
                            )";

                        if (mysqli_query($conn, $sql)) {
                            $inserted_count++;
                            $next_num++;
                        } else {
                            $failed_rows[] = ['line' => $line_no, 'error' => mysqli_error($conn)];
                        }
                    }
                    $import_done = true;
                }
            } catch (Throwable $e) {
                $fatal_error = 'خطأ في قراءة الملف: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">

<?php include_once "inc/header.php"; ?>
<script src="resources/js/jquery.min2.2.0.js"></script>

<header class="header">
    <?php include_once "inc/nav.php"; ?>
    <?php include_once "inc/sidebar.php"; ?>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">استيراد متابعة من ملف إكسل</h4>
                <h6 class="card-subtitle">حمّل القالب، إملأه باللغة العربية، ثم ارفعه هنا لإدخال البيانات دفعة واحدة.</h6>

                <div style="margin: 15px 0;">
                    <a href="work_tracking_import_template.xlsx" class="btn btn-primary" download="work_tracking_import_template.xlsx">
                        <i class="zwicon-download"></i> تحميل القالب
                    </a>
                </div>

                <?php if ($fatal_error !== ''): ?>
                    <div style="color:red; margin: 10px 0;"><?php echo htmlspecialchars($fatal_error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if ($import_done): ?>
                    <div style="color:green; margin: 10px 0;">
                        تم إدخال <?php echo (int)$inserted_count; ?> سجل بنجاح.
                    </div>
                    <?php if (!empty($failed_rows)): ?>
                        <div style="color:red; margin: 10px 0;">
                            تعذر إدخال <?php echo count($failed_rows); ?> سطر:
                            <ul>
                                <?php foreach ($failed_rows as $fr): ?>
                                    <li>السطر <?php echo (int)$fr['line']; ?>: <?php echo htmlspecialchars($fr['error'], ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="work_track_import.php" method="POST" enctype="multipart/form-data">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="200"><label>ملف الإكسل</label></td>
                                <td>
                                    <input type="file" name="import_file" accept=".xlsx,.xls" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" name="submit" value="استيراد"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </section>

    <?php include_once "inc/footer.php"; ?>
</body>
</html>

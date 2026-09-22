<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php";
include_once "inc/ensure_imported_requests.php"; ?>
<?php
/*
 * استيراد طلبات العدل / المالية من ملف إكسل إلى جدول imported_requests
 * يقبل ملفات بصيغة file1.xlsx (طلبات العدل) و file2.xlsx (طلبات المالية).
 * يتم تحديد التصنيف تلقائياً من اسم ورقة العمل، أو يدوياً من القائمة.
 */

if (!in_array($admin, [1, 7, 2, 5, 9])) {
    header("Location: index.php");
    exit;
}

// رفع حدود التنفيذ للملفات الكبيرة (file2 ~ 50 ألف سطر)
@set_time_limit(0);
@ini_set('memory_limit', '3072M');

// تحميل مكتبة PhpSpreadsheet وتعريف فلتر القراءة على دفعات قبل استخدامه
require_once __DIR__ . '/sejil_mawared/vendor/autoload.php';
if (!class_exists('RequestsChunkFilter')) {
    class RequestsChunkFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
    {
        private $startRow = 0;
        private $endRow   = 0;
        public function __construct($startRow, $endRow)
        {
            $this->startRow = $startRow;
            $this->endRow   = $endRow;
        }
        public function readCell($columnAddress, $row, $worksheetName = ''): bool
        {
            return ($row >= $this->startRow && $row <= $this->endRow);
        }
    }
}

$import_done    = false;
$inserted_count = 0;
$skipped_count  = 0;
$deleted_count  = 0;
$fatal_error    = '';
$detected_cat   = '';

// خرائط الأعمدة حسب موضع العمود (1 = العمود A)
$map_adl = [
    1 => 'request_num', 2 => 'name', 3 => 'father_name', 4 => 'surname', 5 => 'mother_name',
    6 => 'national_id', 7 => 'birth_date', 8 => 'birth_place', 9 => 'status', 10 => 'reason',
    11 => 'requesting_entity', 12 => 'entity', 13 => 'source', 14 => 'reason2', 15 => 'entity2',
    16 => 'contact_num', 17 => 'transaction_num', 18 => 'submit_date', 19 => 'review_date', 20 => 'result',
];
$map_maliya = [
    1 => 'request_num', 2 => 'name', 3 => 'father_name', 4 => 'surname', 5 => 'mother_name',
    6 => 'national_id', 7 => 'birth_date', 8 => 'birth_place', 9 => 'status', 10 => 'reason',
    11 => 'requesting_entity', 12 => 'entity', 13 => 'contact_num', 14 => 'transaction_num',
    15 => 'submit_date', 16 => 'review_date', 17 => 'result',
];

$date_fields = ['birth_date', 'submit_date', 'review_date'];

function req_norm($v)
{
    if ($v === null) return '';
    $s = trim((string)$v);
    // إزالة المسافة غير الفاصلة وعلامات الاتجاه
    $s = str_replace(["\xC2\xA0", "\xE2\x80\x8E", "\xE2\x80\x8F"], '', $s);
    return trim($s);
}

// تحويل الأرقام التسلسلية للتواريخ في إكسل إلى نص مقروء، وإبقاء النص كما هو
function req_norm_date($v)
{
    $s = req_norm($v);
    if ($s === '') return '';
    if (is_numeric($s) && strpos($s, '/') === false && strpos($s, '-') === false) {
        try {
            $d = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$s);
            return $d->format('Y-m-d');
        } catch (Throwable $e) {
            return $s;
        }
    }
    return $s;
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
            try {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($f['tmp_name']);
                $reader->setReadDataOnly(true);

                // تحديد التصنيف
                $cat_choice = isset($_POST['category']) ? $_POST['category'] : 'auto';
                $sheet_names = $reader->listWorksheetNames($f['tmp_name']);
                $sheet_title = isset($sheet_names[0]) ? $sheet_names[0] : '';

                if ($cat_choice === 'عدل' || $cat_choice === 'مالية') {
                    $category = $cat_choice;
                } else {
                    // تلقائي: من اسم الورقة ثم عدد الأعمدة
                    if (mb_strpos($sheet_title, 'عدل') !== false) {
                        $category = 'عدل';
                    } elseif (mb_strpos($sheet_title, 'مالية') !== false) {
                        $category = 'مالية';
                    } else {
                        $category = 'مالية'; // سيُعاد ضبطه بعد قراءة عدد الأعمدة أدناه
                    }
                }
                $detected_cat = $category;

                // قراءة معلومات الورقة لمعرفة عدد الأسطر والأعمدة
                $info = $reader->listWorksheetInfo($f['tmp_name']);
                $total_rows  = isset($info[0]['totalRows']) ? (int)$info[0]['totalRows'] : 0;
                $total_cols  = isset($info[0]['totalColumns']) ? (int)$info[0]['totalColumns'] : 0;

                // ضبط التصنيف تلقائياً بعدد الأعمدة عند عدم وضوح اسم الورقة
                if ($cat_choice === 'auto'
                    && mb_strpos($sheet_title, 'عدل') === false
                    && mb_strpos($sheet_title, 'مالية') === false) {
                    $category = ($total_cols >= 18) ? 'عدل' : 'مالية';
                    $detected_cat = $category;
                }

                $col_map = ($category === 'عدل') ? $map_adl : $map_maliya;

                // حذف بيانات نفس التصنيف قبل الاستيراد عند الطلب
                if (isset($_POST['replace']) && $_POST['replace'] == '1') {
                    $cat_safe = mysqli_real_escape_string($conn, $category);
                    mysqli_query($conn, "DELETE FROM `imported_requests` WHERE `category` = '$cat_safe'");
                    $deleted_count = mysqli_affected_rows($conn);
                }

                $jeha_safe     = mysqli_real_escape_string($conn, $jeha_profile);
                $added_by_safe = mysqli_real_escape_string($conn, $_SESSION['user']);

                // جملة إدخال مُعدّة مسبقاً (24 عموداً، add_date تلقائي)
                $insert_sql = "INSERT INTO `imported_requests`
                    (`category`,`request_num`,`name`,`father_name`,`surname`,`mother_name`,`national_id`,
                     `birth_date`,`birth_place`,`status`,`reason`,`requesting_entity`,`entity`,`source`,
                     `reason2`,`entity2`,`contact_num`,`transaction_num`,`submit_date`,`review_date`,
                     `result`,`jeha`,`added_by`)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = mysqli_prepare($conn, $insert_sql);
                if (!$stmt) {
                    throw new Exception('تعذر تجهيز جملة الإدخال: ' . mysqli_error($conn));
                }

                // قراءة الملف على دفعات لتقليل استهلاك الذاكرة
                $chunkSize = 5000;
                mysqli_query($conn, "START TRANSACTION");

                for ($startRow = 2; $startRow <= $total_rows; $startRow += $chunkSize) {
                    $endRow = min($startRow + $chunkSize - 1, $total_rows);

                    $chunkReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($f['tmp_name']);
                    $chunkReader->setReadDataOnly(true);
                    $chunkReader->setReadFilter(new RequestsChunkFilter($startRow, $endRow));
                    $spreadsheet = $chunkReader->load($f['tmp_name']);
                    $ws = $spreadsheet->getActiveSheet();

                    for ($r = $startRow; $r <= $endRow; $r++) {
                        // تجميع قيم الأعمدة لهذا السطر
                        $data = array_fill_keys(array_values($col_map), '');
                        foreach ($col_map as $colIdx => $field) {
                            $cell = $ws->getCellByColumnAndRow($colIdx, $r);
                            $val  = $cell ? $cell->getValue() : '';
                            if (in_array($field, $date_fields, true)) {
                                $data[$field] = req_norm_date($val);
                            } else {
                                $data[$field] = req_norm($val);
                            }
                        }

                        // اعتبار السطر بياناً فعلياً فقط إذا احتوى على اسم أو رقم وطني
                        // (لتجاوز الأسطر الفارغة وسطر "تاريخ التصدير" في نهاية الملف)
                        if ($data['name'] === '' && $data['national_id'] === '') {
                            $skipped_count++;
                            continue;
                        }

                        $p_category          = $category;
                        $p_request_num       = $data['request_num'];
                        $p_name              = $data['name'];
                        $p_father_name       = $data['father_name'];
                        $p_surname           = $data['surname'];
                        $p_mother_name       = $data['mother_name'];
                        $p_national_id       = $data['national_id'];
                        $p_birth_date        = $data['birth_date'];
                        $p_birth_place       = $data['birth_place'];
                        $p_status            = $data['status'];
                        $p_reason            = $data['reason'];
                        $p_requesting_entity = $data['requesting_entity'];
                        $p_entity            = $data['entity'];
                        $p_source            = isset($data['source']) ? $data['source'] : '';
                        $p_reason2           = isset($data['reason2']) ? $data['reason2'] : '';
                        $p_entity2           = isset($data['entity2']) ? $data['entity2'] : '';
                        $p_contact_num       = $data['contact_num'];
                        $p_transaction_num   = $data['transaction_num'];
                        $p_submit_date       = $data['submit_date'];
                        $p_review_date       = $data['review_date'];
                        $p_result            = $data['result'];

                        mysqli_stmt_bind_param(
                            $stmt,
                            str_repeat('s', 23),
                            $p_category, $p_request_num, $p_name, $p_father_name, $p_surname,
                            $p_mother_name, $p_national_id, $p_birth_date, $p_birth_place, $p_status,
                            $p_reason, $p_requesting_entity, $p_entity, $p_source, $p_reason2,
                            $p_entity2, $p_contact_num, $p_transaction_num, $p_submit_date, $p_review_date,
                            $p_result, $jeha_safe, $added_by_safe
                        );
                        if (mysqli_stmt_execute($stmt)) {
                            $inserted_count++;
                        }
                    }

                    // تحرير ذاكرة الدفعة
                    $spreadsheet->disconnectWorksheets();
                    unset($spreadsheet, $ws, $chunkReader);
                    gc_collect_cycles();
                }

                mysqli_query($conn, "COMMIT");
                mysqli_stmt_close($stmt);
                $import_done = true;
            } catch (Throwable $e) {
                @mysqli_query($conn, "ROLLBACK");
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
                <h4 class="card-title">استيراد الطلبات من ملف إكسل (العدل / المالية)</h4>
                <h6 class="card-subtitle">
                    ارفع ملف الإكسل (مثل <code>file1.xlsx</code> لطلبات العدل أو <code>file2.xlsx</code> لطلبات المالية).
                    يتم التعرّف على نوع الطلبات تلقائياً، ويمكنك تحديده يدوياً.
                </h6>

                <?php if ($fatal_error !== ''): ?>
                    <div style="color:red; margin: 10px 0;"><?php echo htmlspecialchars($fatal_error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if ($import_done): ?>
                    <div style="color:green; margin: 10px 0;">
                        تم الاستيراد بنجاح بتصنيف: <b><?php echo htmlspecialchars($detected_cat, ENT_QUOTES, 'UTF-8'); ?></b><br>
                        عدد السجلات المُدخلة: <b><?php echo (int)$inserted_count; ?></b>
                        <?php if ($deleted_count > 0): ?>
                            — تم حذف <b><?php echo (int)$deleted_count; ?></b> سجل قديم من نفس التصنيف
                        <?php endif; ?>
                        <?php if ($skipped_count > 0): ?>
                            — تم تجاوز <b><?php echo (int)$skipped_count; ?></b> سطر فارغ
                        <?php endif; ?>
                    </div>
                    <div style="margin:10px 0;">
                        <a class="btn btn-primary" href="requests_view_server.php">عرض البيانات في الجدول</a>
                    </div>
                <?php endif; ?>

                <form action="requests_import.php" method="POST" enctype="multipart/form-data">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td width="200"><label>نوع الطلبات</label></td>
                                <td>
                                    <select name="category" class="form-control" style="max-width:300px;">
                                        <option value="auto">تلقائي (حسب الملف)</option>
                                        <option value="عدل">طلبات العدل</option>
                                        <option value="مالية">طلبات المالية</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="200"><label>ملف الإكسل</label></td>
                                <td>
                                    <input type="file" name="import_file" accept=".xlsx,.xls" required>
                                </td>
                            </tr>
                            <tr>
                                <td><label>استبدال البيانات؟</label></td>
                                <td>
                                    <label>
                                        <input type="checkbox" name="replace" value="1">
                                        حذف السجلات القديمة لنفس التصنيف قبل الاستيراد
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" class="btn btn-success" name="submit" value="استيراد" />
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

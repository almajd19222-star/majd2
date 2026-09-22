<?php include_once "inc/session.php"; ?>
<!DOCTYPE html>
<html lang="ar">
<?php
include_once "inc/config.php";
include_once "inc/users_roles.php";
include_once "inc/ensure_imported_requests.php";

if (!in_array($admin, [1, 7, 2, 5, 9])) {
    header("Location: index.php");
    exit;
}

include_once "inc/header.php";
?>

<header class="header ">

    <?php include_once "inc/nav.php"; ?>
    <?php include_once "inc/sidebar.php"; ?>

    <section class="content content--full">
        <h2 style="text-align: center; color: red;">
            <p>جدول الطلبات المستوردة (العدل / المالية)
                <?php if (in_array($admin, [1, 7])) { ?>
                    <a href="requests_import.php"><i style="font-size: 2.5rem" class="zwicon-upload"></i></a>
                <?php } ?>
            </p>
        </h2>

        <div class="card" style="overflow: auto; width:100%; left:0px;">
            <div class="card-body">
                <div class="table-responsive">

                    <table class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>التصنيف</th>
                                <th>رقم الطلب</th>
                                <th>الاسم</th>
                                <th>اسم الأب</th>
                                <th>الكنية</th>
                                <th>اسم الأم</th>
                                <th>الرقم الوطني</th>
                                <th>تاريخ التولد</th>
                                <th>مكان التولد</th>
                                <th>الحالة</th>
                                <th>السبب</th>
                                <th>الجهة الطالبة</th>
                                <th>الجهة</th>
                                <th>المصدر</th>
                                <th>السبب (2)</th>
                                <th>الجهة (2)</th>
                                <th>رقم التواصل</th>
                                <th>رقم المعاملة</th>
                                <th>تاريخ تقديم الطلب</th>
                                <th>تاريخ مراجعة الطلب</th>
                                <th>النتيجة</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    <?php include_once "inc/footer_server.php"; ?>

    <script>
        $(document).ready(function() {

            // إضافة حقل بحث أسفل كل عنوان عمود
            $('.example thead tr').clone(true).appendTo('.example thead');
            $('.example thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                if (title == '#') {
                    $(this).html('');
                } else {
                    $(this).html('<input type="text" class="form-control form-control-sm" placeholder="' + title + '" />');
                    $('input', this).on('keyup change clear', function() {
                        var input = this;
                        setTimeout(function() {
                            if (table.column(i).search() !== input.value) {
                                table.column(i).search(input.value).draw();
                            }
                        }, 700);
                    });
                }
            });

            var table = $('table.example').DataTable({
                "language": {
                    "url": "<?php echo $url_host; ?>/resources/DataTables/ar.json"
                },
                orderCellsTop: true,
                "dom": 'Bflrtip',
                autoWidth: true,
                select: true,
                colReorder: true,
                stateSave: true,
                fixedHeader: {
                    headerOffset: $('.top-nav').outerHeight(),
                    header: true,
                    footer: false,
                },
                "iDisplayLength": 25,
                "lengthMenu": [
                    [10, 25, 50, 100, 200, 1000, -1],
                    [10, 25, 50, 100, 200, 1000, "الكل"]
                ],
                "serverSide": true,
                "processing": true,
                "serverMethod": 'post',
                scrollX: true,
                ajax: {
                    url: "requests_get_data.php",
                    type: "POST"
                },
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        className: "dt-center",
                        targets: "_all"
                    },
                    {
                        // إخفاء عمود المعرّف
                        targets: [0],
                        visible: false,
                        searchable: false
                    },
                    {
                        targets: [11, 15, 21],
                        render: DataTable.render.ellipsis(40, true)
                    }
                ],
                buttons: [{
                        extend: 'colvis',
                        text: 'الأعمدة',
                        collectionLayout: 'two-column',
                    },
                    {
                        extend: 'print',
                        text: 'طباعة',
                        autoPrint: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'إكسل',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'all'
                            }
                        }
                    }
                ],
                columns: [{
                        data: "id",
                        orderable: true,
                        searchable: false
                    },
                    { data: "category" },
                    { data: "request_num" },
                    { data: "name" },
                    { data: "father_name" },
                    { data: "surname" },
                    { data: "mother_name" },
                    { data: "national_id" },
                    { data: "birth_date" },
                    { data: "birth_place" },
                    { data: "status" },
                    { data: "reason" },
                    { data: "requesting_entity" },
                    { data: "entity" },
                    { data: "source" },
                    { data: "reason2" },
                    { data: "entity2" },
                    { data: "contact_num" },
                    { data: "transaction_num" },
                    { data: "submit_date" },
                    { data: "review_date" },
                    { data: "result" }
                ],
            });

            $('table.example tbody').on('mouseenter', 'td', function() {
                var colIdx = table.cell(this).index().column;
                $(table.column(colIdx).nodes()).addClass('highlight');
            }).on('mouseleave', 'td', function() {
                var colIdx = table.cell(this).index().column;
                $(table.column(colIdx).nodes()).removeClass('highlight');
            });

        });
    </script>

</body>

</html>

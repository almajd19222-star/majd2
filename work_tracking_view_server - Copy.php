<?php include_once "inc/session.php"; ?>
<!DOCTYPE html>
<html lang="ar">
<?php
include_once "inc/config.php";
//$details_type=$_GET['details_type'];

include_once "inc/users_roles.php";
include_once "inc/header.php";





?>

            <header class="header ">

            <?php include_once "inc/nav.php"; ?>
            <?php include_once "inc/sidebar.php"; ?>
            <?php 
            if (strpos($url,'view') !== false) {
              echo '<section class="content content--full">';
            } else {
                echo '<section class="content">';
            }
            ?>
            <h2 style="text-align: center; color: red;">
                
                <p>جدول المتابعة
                  <?php if($hasPerm_tracking_table_write==1){?>
                <a href="work_track_add.php"><i style="font-size: 2.5rem" class="zwicon-plus-square"></i></a>
                <?php }?>
                </p>
      </h2>
                <h4 style="text-align: center; color: red;">
    
                </h4>


                              <div class="card" style="overflow: auto; width:100%; left:0px;">
                                <!-- <a style="" href="#"><i style="font-size: 2.5rem" class="zwicon-printer"></i></a> -->
                                  <div class="card-body">
                                      <div class="table-responsive">
                                      <div style="text-align: right;">
                                     
                   
                </div>

                <table class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                                            <thead>
                                                <tr>                                             
                                                   
                                                <th>حذف</th>
                                                    <th>تفاصيل</th>
                                                    <th>الحالة</th>
                                                    <th>المطلوب للدراسة</th>
                                                    <th>الجهة الطالبة</th>                                                
                                                    <th>الجهة المكلفة</th>
                                                   
                                                    <th>رقم الكتاب الوارد </th>
                                                    <th>تاريخ الاستلام</th> 
                                                  
                                                    <th>رقم الكتاب الصادر</th> 
                                                    <th>تاريخ الصدور</th> 

                                                    <th>الأخ المكلف</th>                                                  
                                                    <th>رقم كتاب رد الجهة المكلفة</th> 
                                                    <th >تاريخ الورود من الجهة المكلفة </th>

                                                    <th>رقم كتاب الرد على الجهة الطالبة</th>   
                                                    <th>تاريخ تسليم الجهة الطالبة</th>
                                                    <th>ملاحظات</th>
                                                    <th>الرقم الذاتي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                     
                </div></div>

      </div>

      <?php include_once "inc/footer_server.php";
           
           include_once "inc/footer_view_server_all.php";
       
           ?>
           
    </body>
</html>
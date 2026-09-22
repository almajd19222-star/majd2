<?php include_once "inc/session.php"; ?>
<?php if($admin == 1 || $admin == 7  || $admin==5 || $admin==6 || $admin == 9 || $admin == 10 || $admin == 11 || $admin == 2){ ?>
<!DOCTYPE html>
<html lang="ar">
<?php

@$jeha1=$_GET['jeha'];
@$details_type=$_GET['details_type'];

include_once "inc/config.php";
include_once "inc/users_roles.php";
include_once "inc/header.php";


/* $b = array('jeha', 'details_type','','');
$c = array_combine($b, $_GET);
foreach($c as $key => $value) {
  $$key = $value;
} */ 
/* echo $jeha1;
exit; */
/* $time_start = microtime(true);
@$limit = trim(htmlentities(@$_GET['limit']));
if(empty($limit)) { $limit = 100;} */

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
           
           <?php if (!empty($details_type)){ ?>
                    <p>المسح الأمني ( <?php echo @$_GET['details_type']; ?> )</p> 
            <?php }else{ ?>   
                    <p>المسح الأمني ( عرض عام )</p>
            <?php }?> 
                       
                </h2>
                <h4 style="text-align: center; color: red;">
                  <?php
                  if(!empty($_GET['move_id'])) {
                    echo "تم عملية النقل بنجاح";
                }
                  ?>
                </h4>



                   
                              <div class="card" style="overflow: auto; width:100%; left:0px;">
                               <!--  <a style="" href="#"><i style="font-size: 2.5rem" class="zwicon-printer"></i></a> -->
                                  <div class="card-body">
                                      <div class="table-responsive">
                                      <div style="text-align: right;">
                                      <script type="text/javascript">
                                        var details_type='<?php echo $details_type;  ?>';
                                        var data_type='<?php echo @$data_type;  ?>';
                                      var type='<?php echo @$type;  ?>';
                                      </script>
                                      
                    <?php if ($admin == 1 || $admin == 7  || $admin == 5){
                      echo '<a class="btn btn-danger btn_remove" target="_blank" href="print-elements.php?jeha='.$jeha1.'&details_type='.$details_type.'&type=ketab_all_studies&edit=1">طباعة نموذج 1</a>';

                      echo '<a class="btn btn-danger btn_remove" target="_blank" href="print-elements.php?jeha='.$jeha1.'&details_type='.$details_type.'&type=ketab_all_studies2&edit=1">طباعة نموذج 2</a>';
                     }


                     include_once "query/getJehat_server_view.php";
                   
                     if (!empty($details_type)){ ?>
                      <?php include_once "jehat.php"; ?>
                     <?php } ?>
                </div>
               
                <table  class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                  <thead>
                    <th>حذف</th>
                    <th>تفاصيل</th>                                                    
                    <th>طباعة</th>  
                    <th>مرحلة العمل</th> 
                    <th>الجهة</th>  
                    <th>الفعالية</th>
                    <th>الرمز العام</th>                                      
                    <th>رقم الكتاب</th>
                    <th>تاريخ الكتاب</th>
                                        
                    <th>الاسم</th>
                    <th>اسم الاب</th>
                    <th>النسبة</th>
                    <th>الرمز الشخصي</th>
                    <th>مسمى الفعالية</th>
                    <th>العنوان</th>
                    <th>الشركاء</th>
                    <th>إحداثية طول</th>
                    <th>إحداثية عرض</th>  
                    <th>الترخيص</th>    
                    <th>التواصل</th>  
                    <th>التعاون</th>
                    <th>الكاميرات</th>           
                    <th>معلومات أخرى</th>  
                    <th>نتيجة الدراسة</th>
                    <th>الرأي والمقترح</th>
                    <th>مصدر الدراسة</th>                                                                  
                    <th>المُدخل</th>
                    <th>تاريخ آخر تعديل</th>
                  </thead>
                  <tbody>

                </div>
              </div>

      </div>    

            <?php include_once "inc/footer_server.php"; ?>
            <?php include_once "inc/footer_view_server_all.php"; ?>
           
    </body>
</html>
         <?php }?>

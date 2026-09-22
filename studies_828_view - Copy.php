<?php include_once "inc/session.php"; ?>
<?php if($admin == 1 || $admin == 7  || $admin==5 || $admin==6){ ?>
<!DOCTYPE html>
<html lang="ar">
<?php


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
           <?php if( strpos($jeha_profile,'نظام') !== false ){echo 'مسح أمني شخصي';}else{ ?>
           <?php if (!empty($details_type)){ ?>
                    <p>المسح الأمني  828 ( <?php echo @$_GET['details_type']; ?> )</p> 
            <?php }else{ ?>   
                    <p>المسح الأمني 828 ( عرض عام )</p>
            <?php }?> 
            <?php }?>     
                </h2>



                   
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
                      echo '<a class="btn btn-danger btn_remove" target="_blank" href="print-elements.php?jeha='.$jeha1.'&details_type='.$details_type.'&type=ketab_all_studies_828&edit=1">طباعة نموذج 1</a>';

                      echo '<a class="btn btn-danger btn_remove" target="_blank" href="print-elements.php?jeha='.$jeha1.'&details_type='.$details_type.'&type=ketab_all_studies2_828&edit=1">طباعة نموذج 2</a>';
                     }
                
                      
                     include_once "query/getJehat_server_view.php";
                    
                      if (!empty($details_type)){
                        if ($admin == 1 || $admin == 7  || $admin == 6){
                        include_once "jehat.php";
                        }
                      } 
                    ?>
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
                    <th>إسم المسح</th> 
                    <th>الاسم</th> 
                    <th>الأب</th> 
                    <th>النسبة</th>                                                                 
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

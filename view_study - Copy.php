<?php include_once "inc/session.php"; ?>
<?php 
$details_type=$_SESSION['details_type'];
$jeha1=$_SESSION['jeha1'];
if($admin == 1 || $admin == 7  || $admin==5 || $admin==6){ ?>
<!DOCTYPE html>
<html lang="ar">
<?php
include_once "inc/config.php";
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
            <?php if ($jeha_profile == 'الإدارة المركزية للمعلومات'){ ?>
                    <p>الدراسات الأمنية ( <?php echo $details_type; ?> )</p> 
            <?php }else{ ?>
                    <p>مكتب الدراسات الأمنية ( <?php echo $details_type; ?> )</p>
            <?php } ?>               
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
                                      
                    <?php 
                     if ($admin == 1 || $admin == 7  || $admin == 5){
                    echo '<a class="btn btn-danger btn_remove" target="_blank" href="print-elements.php?jeha='.$jeha1.'&details_type='.$details_type.'&type=ketab_all_study">طباعة كتاب + دراسات</a>';

                    echo '<a class="btn btn-danger btn_remove" target="_blank" href="print-elements.php?jeha='.$jeha1.'&details_type='.$details_type.'&type=ketab_all_study2">طباعة كتاب + دراسات</a>';
                     }
                    if ($admin == 1 || $admin == 7  || $admin == 6){ ?>
                      <?php include_once "jehat.php"; ?>
                    <?php } ?>
                </div>
                <table  id="" class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                
                                                  
                                                    <th>حذف</th>
                                                  
                                                    <th>نسخ لقيد المعالجة</th>  
                                                
                                                         
                                                                                               
                                                    <th>طباعة الدراسة</th> 
                                                    <th>تفاصيل</th> 
                                                   
                                                     <th>تبعية المعلومات</th>
                                                   
                                                   <!--  <th>جهات المجتمع</th> -->
                                                    <th>الجهة المرسلة</th>
                                                    <th>الجهة المرسل إليها</th>
                                                   
                                                        <th>الرقم الذاتي</th>
                                                    
                                                </tr>
                                            </thead>
                </div></div>

      </div>

            <?php include_once "inc/footer_server_new.php"; ?>

           
    </body>
</html>
         <?php }?>

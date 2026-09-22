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


                     $sql_studies = "SELECT * FROM studies_all ORDER BY add_date DESC";
                     $result_sql_studies = mysqli_query($conn, $sql_studies);

                     
                     if (!empty($details_type)){ ?>
                      <?php 
                       if ($admin == 1 || $admin == 7  || $admin == 6){
                          include_once "jehat.php"; 
                       }


                      

                      ?>
                     <?php } ?>
                </div>
                
                <?php 
                if (mysqli_num_rows($result_sql_studies) > 0) {
                $td_class = "show-read-more"; ?>
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
<?php 
 
while($row = mysqli_fetch_assoc($result_sql_studies)) { 
  echo'<tr>';
  if ($admin == 1 || $admin == 7 || $admin==5){
    if ($details_type !=='صادر'){
    if($hasPerm_delete == '1'){
    echo'<td class="'.$td_class.'"><a href="delete.php?id='.$row['id'].'&type='.$row['type_code'].'&general_code='.$row['general_code'].'&delete_studies=true" onclick="'."return confirm('متأكد من الحذف؟');".'"><i style="font-size: 2.5rem" class="zwicon-delete"></i></a></td>';
      }
  }
}
echo '<td class="'.$td_class.'"> <a href="studies.php?id='.$row['id'].'&edit=1&details_type='.$row['details_type'].'&type_code='.$row['type_code'].'&edit_process=0"><i style="font-size: 2.5rem" class="zwicon-edit-circle"></i></a></td>';

if($admin != 6){
  echo '<td class="'.$td_class.'"> <a target="_blank" href="print-elements.php?id='.$row['id'].'&jeha='.$row['jeha'].'&details_type='.$row['details_type'].'&type='.$row['type_code'].'&edit=1"><i style="font-size: 2.5rem" class="zwicon-printer"></i></a></td>';                                    
  }
  echo  
                
  '<td class="'.$td_class.'">'.$row["details_type"].
  '<td class="'.$td_class.'">'.$row["jeha"].
  '</td><td class="'.$td_class.'">'.$row["type_code"]. 
  '</td><td class="'.$td_class.'">'.$row["general_code"].  
  '</td><td class="'.$td_class.'">'.$row["ketab_num"].
  '</td><td class="'.$td_class.'">'.$row["ketab_date"].
  '</td><td class="'.$td_class.'">'.$row["name"].
  '</td><td class="'.$td_class.'">'.$row["fname"].
  '</td><td class="'.$td_class.'">'.$row["lname"].
  '</td><td class="'.$td_class.'">'.$row["personal_code"].
  '</td><td class="'.$td_class.'">'.$row["place_name"].
  '</td><td class="'.$td_class.'">'.$row["place_address"].
  '</td><td class="'.$td_class.'">'.$row["partners_name"].
  '</td><td class="'.$td_class.'">'.$row["longitude"].
  '</td><td class="'.$td_class.'">'.$row["latitude"].
  '</td><td class="'.$td_class.'">'.$row["license"].
  '</td><td class="'.$td_class.'">'.$row["socialmedia"].
  '</td><td class="'.$td_class.'">'.$row["cooperation"].
  '</td><td class="'.$td_class.'">'.$row["cameras"].
  '</td><td class="'.$td_class.'">'.$row["additional_information"].
  '</td><td class="'.$td_class.'">'.$row["result"].
  '</td><td class="'.$td_class.'">'.$row["suggestion"].
  '</td><td class="'.$td_class.'">'.$row["source"].
  '</td><td class="'.$td_class.'">'.$row["added_by"].
  '</td><td class="'.$td_class.'">'.$row["add_date"];
  echo'</tr>';      
}?>
                      </tbody>
</table>
<?php
}else {
  echo "<div style='text-align:center;' class='fs'><h4>لا يوجد بيانات</h4></div>";
}
?>
                </div>
              </div>

      </div>    

            <?php include_once "inc/footer.php"; ?>
           
    </body>
</html>
         <?php }?>

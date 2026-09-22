<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php
$header1='وزارة الداخلية';
$header2=$jeha_profile;


$details_type = 'وارد';
$sql_public="SELECT distinct(ketab_num),ketab_date  from temp_studies_all ORDER BY ketab_num ASC";
$sql_public_result = mysqli_query($conn, $sql_public);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/print_page/print2.css">
    <title>Document</title>
</head>
<body dir="rtl">
<div id="pageborder"></div>

<?php if (@$_GET['import'] == 'true'){ ?>

<!--   <button class="noprint" style="position:relative" target="_blank" onclick="window.location.href='view_imported_table.php'">طباعة</button> -->
<input type="button" id="print" class="noprint" onclick="print()" style="position:relative" value="طباعة" />

    <?php //if($jeha_profile=='إدارة الأمن الداخلي'){?>
        <!-- <button class="noprint" style="position:relative" target="_blank" onclick="window.location.href='DBimport_dump_processing_120_new.php?import=continue'">استمرار عملية الاستيراد</button> -->
    <?php //}else{?>
        <button class="noprint" style="position:relative" target="_blank" onclick="window.location.href='DBimport_dump_processing_new.php?import=continue'">استمرار عملية الاستيراد</button>

<a class="btn btn-primary noprint" style="position:relative" target="_blank" href="DBimport_dump_processing_new_replace.php?import=continue"  onclick="return confirm('سيتم استبدال البيانات المتطابقة مع الجدول. اضغط نعم للاستمرار');">استمرار عملية الاستيراد مع الاستبدال</a>
    <?php // }?>
  <br><br>
  <button class="noprint" style="position:relative" target="_self" onclick="window.location.href='DBimport_cancel.php?import=continue'">إلغاء عملية الاستيراد</button>

<?php }else{?>
  
  
<!-- <script>
      print();
  </script> -->
  <?php }?>


<table class="header_style">
        <thead>
            <tr>                                     
                <td colspan="" style="border:none;">
                    <div id="container">
                        <div id="left">
                                                                              
                        </div>

                        <div id="center" style="font-family: 'Hacen Liner Screen'; color: black; width:300px">
                            <a>فهرس البريد الإلكتروني الوارد (المسح الأمني)
                           
                            </a>
              
                        </div>

                        <div id="right" style="width:200px;font-family: 'Hacen Liner Screen'; color: black;">
                            <!--<?php echo $header1; ?>
                            <br>    
                            <a id="imported_jeha"></a>-->
                            <?php include_once "print_header_right.php";    ?>
                        </div>
                    </div>
                </td>                       
            </tr>                                 
            </thead>
</table>

    <?php  if (mysqli_num_rows($sql_public_result)> 0) { ?>
        <table class="print_style">
           <!--  <thead> -->
                <tr>
                    <th colspan=11>الكتب</th>
                </tr>
                <tr>
                    <th style="width:10px"></th>
                    
                    <th>رقم الكتاب</th>
                    <th>تاريخ الكتاب</th>                   
                    <th>المسح الأمني</th>
                       
                </tr>
           <!--  </thead> -->
            <tbody>
                <?php 
                $ketab_count=0;
                $ketab_attach_count=0;
                $dewan_attach_count=0 ; 
                $dewan_count=0;
                $study_count=0;
                $count=1; 
                while($row_public = mysqli_fetch_assoc($sql_public_result)) { 
                   

                    //count studies
                    $sql_studies_count="SELECT count(id) as count_studies from `temp_studies_all` WHERE  ketab_num=".$row_public['ketab_num']." 
                    AND ketab_date = '".$row_public['ketab_date']."'  ORDER BY ketab_num ASC"; 
                    $sql_studies_count_result = mysqli_query($conn, $sql_studies_count);
                    if (mysqli_num_rows($sql_studies_count_result)> 0) {
                    $row_studies_count = mysqli_fetch_assoc($sql_studies_count_result);
                    $studies_count = $row_studies_count['count_studies'];
                    }else{
                        $studies_count = 0;
                    }

                    

                    
                   
                        echo '<tr style="page-break-inside:avoid; page-break-after:avoid;">';
                
                    ?>
                    
                        <td><?php echo $count; ?></td>
                       
                        
                       
                        <td>
                        <script>
                            document.getElementById("imported_jeha").textContent = "<?php echo $row_public['jeha']; ?>";
                        </script>
                            <?php echo $row_public['ketab_num'];
                        if(@$row_public['ketab_num']!==0){$ketab_count++;}
                        ?></td>
                        <td><?php echo $row_public['ketab_date'] ?></td>
                        
                      
                     <td><?php echo $studies_count; ?></td>
                       
                    </tr>
                <?php $count++;}?>
            </tbody>
           <!--  <tfoot> -->
                <tr>
                    <th colspan=2></th>
                    <th colspan=7>
                       
                       
                    </th>
                   <!--  <th></th>
                    <th></th> -->
                </tr>
            <!-- </tfoot> -->
        </table>
    <?php  }?>


</body>
</html>
<script>
    //print();
</script>
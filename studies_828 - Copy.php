<?php 
include_once "inc/session.php"; 

include_once "inc/config.php";
include_once "inc/users_roles.php";
?>
<?php 
@$details_type=$_GET['details_type'];
//828_Q10_L1_T_00001

if($_GET['type_code']=='SPE'){
    $type_code = 'SPE';
    $_SESSION['type_code'] = $type_code;
}

if (isset($_POST['submit'])) {
    
    $city_code= mysqli_real_escape_string($conn, $_POST['city_code']);
    $area_code= mysqli_real_escape_string($conn, $_POST['area_code']);
    $type_code= mysqli_real_escape_string($conn, $_POST['type_code']);
    $details_type= mysqli_real_escape_string($conn, $_POST['details_type']);
    //$_SESSION['details_type'] = $details_type;
    include_once "studies_type_code.php";

    if ($_GET['edit'] == '0') {
        $sql_type_num_max = "SELECT type_num FROM `$table_name` where type_num=(SELECT max(type_num) FROM `$table_name` WHERE  details_type='$details_type' AND jeha='$jeha_profile' AND area_code = '$area_code' AND city_code = '$city_code' AND type_code = '$type_code')";
            $result_type_num_max = mysqli_query($conn, $sql_type_num_max);
            $row_type_num_max = mysqli_fetch_assoc($result_type_num_max);

            if ($row_type_num_max > 0) {
                $type_num = $row_type_num_max['type_num'] + 1;
                $type_num = sprintf("%05d", $type_num);
                
            } else {
                $type_num = '00001';
            }
       
        $general_code = $area_code.'_'.$city_code.'_'.$type_code.'_'.$type_num;        
        $_SESSION['table_name'] = $table_name;
        $_SESSION['general_code'] = $general_code;
        $_SESSION['area_code'] = $area_code;
        $_SESSION['city_code'] = $city_code;
        $_SESSION['type_code'] = $type_code;
        $_SESSION['type_num'] = $type_num;

        header("Location: studies_828.php?details_type=".$details_type."&type_code=".$type_code."&type_num=".$type_num."&edit=0");
    }
}

if ($_GET['edit'] == '1') {
    $id = $_GET['id'];
    $type_code = $_GET['type_code']; 
    include_once "studies_type_code.php";
    
    $sql_select="SELECT * FROM `$table_name` WHERE id = $id";
    $result_sql_select = mysqli_query($conn, $sql_select);
    $row = mysqli_fetch_assoc($result_sql_select);
}
?>
<!DOCTYPE html>
<html lang="ar">

<?php include_once "inc/header.php"; ?>
<script src="resources/js/jquery.min2.2.0.js"></script>

    <header class="header">
    <?php include_once "inc/nav.php"; ?>
    <?php include_once "inc/sidebar.php"; ?>

    <?php 
    if (strpos($url,'view') !== false) {
        echo '<section class="content content--full">';
    } else {
        echo '<section class="content">';
    }
    ?>
    <div class="card">
        <div class="card-body">
            <h4 style="text-align: center; color: red;">
            <?php include_once "inc/errors.php"; ?>  
            </h4>
            <h4 class="card-title" >              
            إضافة جديد (<?php echo @$details_type; ?>)</h4>
            <h6 class="card-subtitle"></h6>
            <div class="table-responsive">

                <form id="myform" name="myform"  action="studies_828.php?details_type=<?php echo $details_type; ?>&type_code=0&edit=0" method="POST" accept-charset="utf-8" enctype="multipart/form-data">

                <!-- <button class="btn btn-outline-dark" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapseExample">
                <i class="zwicon-minus"></i>
                </button> -->

                    <table class="table table-borderless">
                        <tr>
                        <td>مرحلة العمل</td>      
                            <td>
                                <select name="details_type" id="" required>
                                    <option value="<?php if(!empty(@$_GET['details_type'])){ echo $_GET['details_type'];}else{} ?>"><?php if(!empty(@$_GET['details_type'])){ echo $_GET['details_type'];}else{} ?></option>
                                    <option value="صادر">صادر</option>
                                    <option value="قيد المعالجة">قيد المعالجة</option>
                                    <option value="وارد">وارد</option>                                   
                                </select>
                            </td> 
                            
                        </tr>
                        <?php if($_GET['type_code']=='SPE'){?> 
                                <input type="hidden" name="area_code" value="">
                                <input type="hidden" name="city_code" value="">
                        <?php }else{?>
                        <tr>
                            
                            <td>المنطقة</td>
                            <td>
                                <select name="area_code" id="" required>
                                    <option value=""></option>
                                    <option value="Q10">Q10</option>
                                    <option value="Q20">Q20</option>
                                    <option value="Q30">Q30</option>
                                    <option value="Q40">Q40</option>
                                    <option value="Q50">Q50</option>
                                    <option value="Q60">Q60</option>
                                    <option value="Q70">Q70</option>
                                    <option value="Q80">Q80</option>
                                    <option value="Q90">Q90</option>
                                    <option value="Sg">Sg</option>
                                    <option value="Rn">Rn</option>
                                    <option value="Kf">Kf</option>                                    
                                </select>
                            </td>

                            <td>القطاع</td>
                            <td>
                            <select name="city_code" id="" required>
                                    <option value=""></option>
                                    <option value="L01">L01</option>
                                    <option value="L02">L02</option>
                                    <option value="L03">L03</option>
                                    <option value="L04">L04</option>
                                    <option value="L05">L05</option>
                                    <option value="L06">L06</option>
                                    <option value="L07">L07</option>
                                    <option value="L08">L08</option>
                                    <option value="L09">L09</option>
                                    <option value="L10">L10</option>  
                                    <option value="L11">L11</option>    
                                    <option value="L12">L12</option>     
                                                                  
                                </select>
                            </td>
                        </tr>
                        <?php }?>
                        <tr>
                            <td>الجهة \ الفعالية</td>

                            <td>
                                <select name="type_code" id="" required>
                                    
                                    <?php if($_GET['type_code']=='SPE'){?> 
                                        <option value="SPE">أمنية شخصية</option>
                                    <?php }else{?>
                                        <option value=""></option>
                                    <option value="T">مدينة - بلدة - قرية</option>
                                    <option value="G">هدف</option>
                                    <!--<option value="SCE">مركز أمني</option>-->
                                    <option value="S">موقع</option>
                                    <option value="CP">الحواجز</option>
                                    <?php }?>
                                   
                                </select>
                            </td> 
                                       
                        </tr>

                      
                        
                        <tr>
                            <td colspan="4">
                                <input type="submit" name="submit" value="اظهر النموذج"/>
                            </td>
                        </tr> 
                    </table>
                    </form>
                
            </div>
        </div>
</div>
        <div class="card">
            <div class="card-body">               
                <div class="table-responsive">                    
                   <?php if($_GET['edit']=='1'){ ?>
                    <h4 class="text-center" style="color: red;">تعديل
                    <br>
                    <?php if($_GET['edit_process']=='true'){ 
                         echo "تم التعديل بنجاح";}
                    ?>
                </h4>
                    
                        <form id="myform2" name="myform2"  action="studies_add_process_828.php?details_type=<?php echo $details_type; ?>&type_code=<?php echo $_GET['type_code']; ?>&id=<?php echo $_GET['id']; ?>&edit=1" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php } if($_GET['edit']=='0'){?>
                        <form id="myform2" name="myform2"  action="studies_add_process_828.php?details_type=<?php echo $details_type; ?>&type_code=<?php echo $_GET['type_code']; ?>&edit=0" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php } ?>

                    <?php if($_GET['type_code']!=='0'){ ?>
                    <table class="table">

                    <tr>
                            <td width="100">
                                <label >رقم الكتاب</label>
                                </td>
                                <td width="100">
                                    <input type="number" class="number_input" name="ketab_num" value="<?php if($_GET['edit']=='1'){ echo $row['ketab_num'];}else{} ?>"/>
                                
                                </td>
                                <td width="100">
                                <label >تاريخ الكتاب</label>
                                </td>
                                <td width="100">
                                    <input  placeholder="" type="date" name="ketab_date" value="<?php if($_GET['edit']=='1'){ echo $row['ketab_date'];}else{} ?>">
                                </td>
                            </tr>
                    <?php if($_GET['edit']=='1'){ ?>
                        <?php if($_GET['type_code']=='SPE'){?> 
                                <input type="hidden" name="area_code" value="">
                                <input type="hidden" name="city_code" value="">
                        <?php }else{?>
                        <tr>
                            <td>المنطقة</td>
                            <td>
                                <select name="area_code" id="" required>
                                    <option value="<?php echo $row['area_code']; ?>"><?php echo $row['area_code']; ?></option>
                                    <option value="Q10">Q10</option>
                                    <option value="Q20">Q20</option>
                                    <option value="Q30">Q30</option>
                                    <option value="Q40">Q40</option>
                                    <option value="Q50">Q50</option>
                                    <option value="Q60">Q60</option>
                                    <option value="Q70">Q70</option>
                                    <option value="Q80">Q80</option>
                                    <option value="Q90">Q90</option>             
                                    <option value="Sg">Sg</option>
                                    <option value="Rn">Rn</option>
                                    <option value="Kf">Kf</option>                       
                                </select>
                            </td>

                            <td>القطاع</td>
                            <td>
                                <select name="city_code" id="" required>
                                    <option value="<?php echo $row['city_code']; ?>"><?php echo $row['city_code']; ?></option>
                                    <option value="L01">L01</option>
                                    <option value="L02">L02</option>
                                    <option value="L03">L03</option>
                                    <option value="L04">L04</option>
                                    <option value="L05">L05</option>
                                    <option value="L06">L06</option>
                                    <option value="L07">L07</option>
                                    <option value="L08">L08</option>
                                    <option value="L09">L09</option>
                                    <option value="L10">L10</option>  
                                    <option value="L11">L11</option>    
                                    <option value="L12">L12</option>                                   
                                </select>
                            </td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td>الجهة \ الفعالية</td>

                            <td>
                                <select name="type_code" id="" required>
                                <?php if($_GET['type_code']=='SPE'){?> 
                                        <option value="SPE">أمنية شخصية</option>
                                    <?php }else{?>
                                        <option value=""></option>
                                    <option value="T">مدينة - بلدة - قرية</option>
                                    <option value="G">هدف</option>
                                    <!--<option value="SCE">مركز أمني</option>-->
                                    <option value="S">موقع</option>
                                    <option value="CP">الحواجز</option>
                                    <?php }?>
                                </select>
                            </td> 
                                <td>
                                الرقم التسلسلي
                            </td>      
                            <td>
                                <input type="number" name="type_num" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="5" id="" value="<?php echo $row['type_num']; ?>">
                            </td>           
                        </tr>
                      
                    <?php } ?>
                    <?php if($_GET['type_code']=='SPE'){}else{?>
                    <tr>
                        <td>
                        الرمز العام:
                        </td>

                        <td>
                        <input type="text" name="general_code" value="<?php if ($_GET['edit']=='1') {
                        echo $row['general_code'];
                        } else {
                        $general_code = $_SESSION["general_code"];
                        echo $general_code;
                        } ?>">

                        </td>


                        </tr>
                        <?php } ?>
                    </table>
                    <?php } ?>
                        <?php 
                       
                            

                            //828 temlates
                            if ($_GET['type_code']=='T') {
                                include_once "studies/studies_828_town.php";
                            }

                            if($_GET['type_code']=='G'){
                                include_once "studies/studies_828_goal.php";
                            }

                            if($_GET['type_code']=='S'){
                                include_once "studies/studies_828_ML.php";
                            }

                            if($_GET['type_code']=='SCE'){
                                include_once "studies/studies_828_SC.php";
                            }

                            if($_GET['type_code']=='CP'){
                                include_once "studies/studies_828_CP.php";
                            }
                            if($_GET['type_code']=='SPE'){
                                include_once "studies/studies_828_SP.php";
                            }
                            //////////////
                          
                        
                        ?>
                                
                           
                               
                                  
                                

                                 </form>
                            </div>
                          </div>

        </div>


            <?php include_once "inc/footer.php"; ?>
    </body>
</html>

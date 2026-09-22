<?php 
include_once "inc/session.php"; 

include_once "inc/config.php";
include_once "inc/users_roles.php";
?>
<?php 
@$details_type=$_GET['details_type'];

if (isset($_POST['submit'])) {
    $area_code= mysqli_real_escape_string($conn, $_POST['area_code']);
    $city_code= mysqli_real_escape_string($conn, $_POST['city_code']);
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
            $type_num = sprintf("%04d", $type_num);
            
        } else {
            $type_num = '0001';
        }

        $general_code = $area_code.$city_code.$type_code.$type_num;
        $_SESSION['table_name'] = $table_name;
        $_SESSION['general_code'] = $general_code;
        $_SESSION['area_code'] = $area_code;
        $_SESSION['city_code'] = $city_code;
        $_SESSION['type_code'] = $type_code;
        $_SESSION['type_num'] = $type_num;

        header("Location: studies.php?details_type=".$details_type."&type_code=".$type_code."&edit=0");
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
            إضافة جديد</h4>
            <h6 class="card-subtitle"></h6>
            <div class="table-responsive">

                <form id="myform" name="myform"  action="studies.php?details_type=<?php echo $details_type; ?>&type_code=0&edit=0" method="POST" accept-charset="utf-8" enctype="multipart/form-data">

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
                        <tr>
                            <td>المنطقة</td>
                            <td>
                                <select name="area_code" id="" required>
                                    <option value=""></option>
                                    <option value="011">منطقة مدينة إدلب</option>
                                    <option value="012">منطقة أريحا</option>
                                    <option value="013">المنطقة الوسطى</option>
                                    <option value="014">منطقة سرمدا</option>
                                    <option value="015">منطقة حارم</option>
                                    <option value="016">منطقة جسر الشغور</option>
                                    <option value="017">المنطقة الشمالية</option>
                                    <option value="018">منطقة أطمة</option>
                                    <option value="019">M77</option>
                                </select>
                            </td>

                            <td>رمز المدن \ القرى</td>
                            <td>
                                <input type="number" name="city_code" required>
                            </td>
                        </tr>
                        <tr>
                            <td>الجهة \ الفعالية</td>

                            <td>
                            <select name="type_code" id="" required>
                                <option value=""></option>
                                <optgroup label="2021">
                                    <option value="SH">البسطات والبراكيات</option>
                                    <option value="UV">الجامعات</option>
                                    <option value="RF">الفصائل الثورية</option>
                                    <option value="CE">الفعاليات الأهلية غير الرسمية</option>
                                    <option value="ES">المحلات الإلكترونية</option>
                                    <option value="RE">المكاتب العقارية</option>
                                    <option value="TR">المهربين</option>
                                    <option value="UD">تجار السلاح الذين لا يملكون محلات</option>
                                    <option value="AS">جمعية</option>                                    
                                    <option value="MC">محل بيع وصيانة أجهزة الخليوي والحواسيب</option>
                                    <option value="FP">محلات الأسمدة والمبيدات</option>
                                    <option value="WA">محلات السلاح والذخيرة</option>
                                    <option value="CH">محلات الصرافة</option>
                                    <option value="TC">مركز تدريب</option>
                                    <option value="FS">محلات التزوير والأختام</option>
                                    <option value="CO">مكاتب السيارات</option>
                                    <option value="OR">منظمة</option>
                                </optgroup>
                                <optgroup label="2022">
                                    <option value="FA">مزرعة</option>
                                    <option value="PP">مطبعة</option>
                                    <option value="LW">مخرطة</option>
                                    <option value="MW">ورشة حدادة</option>
                                    <option value="BS">ورشة تصويج وبخ</option>
                                    <option value="CK">محل نسخ مفاتيح</option>
                                    <option value="CI">شبكات الاتصال والإنترنت</option>
                                    <option value="HO">فندق</option>
                                    <option value="TX">سيارة أجرة</option>
                                    <option value="SP">مسبح</option>
                                    <option value="AP">ملاهي</option>
                                    <option value="BB">نادي بناء أجسام</option>
                                    <option value="FG">نادي ألعاب قتالية</option>
                                    <option value="PH">مشفى خاص</option>
                                    <option value="MP">منصة إعلامية</option>
                                    <option value="CD">مركز دفاع مدني</option>
                                    <option value="SI">معهد دراسي</option>
                                    <option value="SC">شركة شحن بضائع</option>
                                    <option value="PS">محطة وقود</option>
                                    <option value="IF">منشأة صناعية</option>
                                    <option value="JS">محل صياغة الذهب والفضة</option>
                                    <option value="CA">وكالة تجارية</option>
                                    <option value="TM">علامة تجارية فارقة</option>
                                    <option value="QU">مقلع</option>
                                </optgroup>
                                <optgroup label="2023">
                                    <option value="CL">عشيرة</option>     
                                    <option value="RC">مطعم - مقهى كبير</option>   
                                    <option value="SM">سيارة بائع جوال</option>   
                                    <option value="PD">الصيدليات</option> 
                                    <option value="MS">المخازن الطبية ومستودعات الأدوية</option>
                                    <option value="SE">محلات الطاقة الشمسية</option>
                                    <option value="AC">المعاهد الشرعية</option>
                                    <option value="CU">المداجن</option>
                                    <option value="SK">مراكز جمع الخردة</option>
                                    <option value="GE">صالات الألعاب</option> 
                                    <option value="DF">معامل المنظفات</option> 
                                    <option value="MO">مكاتب الدراجات النارية</option>
                                    <option value="OC">مراكز تركيب أجهزة المراقبة</option>
                                    <option value="BA">محلات الحلاقة</option>
                                    <option value="ME">الإعلاميين</option>
                                    <option value="BM">منشآت صناعة المدخرات (البطاريات)</option>
                                    <option value="SO">المولات التجارية</option>  
                                    <option value="SW">سيارات بيع مياه الشرب</option> 
                                    <option value="TO">مكاتب الرقية والرقاة</option> 
                                    <!-- <option value="TP">مراكز بيع الدخان والأراكيل ومستلزماتها</option>  -->
                                    <option value="ET">محلات بيع وصيانة الأدوات الكهربائية</option> 
                                    <option value="PA">شركات الدعاية والإعلان</option> 
                                    <option value="CR">محلات زينة السيارات</option> 
                                    <option value="DC">مكاتب العملات الرقمية</option>      
                                    <!-- <option value="GA">الكراجات</option> -->   
                                    <option value="MA">وكالات الدراجات النارية</option>   
                                    <option value="OH">دور الأيتام</option>   
                                    <option value="BO">مكاتب المقاولات والتعهدات</option>   
                                    <option value="DE">شركات خدمات التوصيل</option>   
                                    <!-- <option value="FC">منشآت المدينة الصناعية</option> -->                   
                                </optgroup>
                            </select>
                                
                            </td> 
                                <!-- <td>
                                الرقم التسلسلي
                            </td>      
                            <td>
                                <input type="number" name="type_num" id="" value="<?php //echo $type_num; ?>">
                            </td>          -->            
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
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
                    }
                    ?>
                </h4>
                <?php $type_code=$_GET['type_code'];
                 if ($type_code == 'QU' || $type_code == 'CA' || $type_code == 'JS' || $type_code == 'TM' || $type_code == 'SC' || $type_code == 'CI' || $type_code == 'TX' || $type_code == 'LW' || $type_code == 'BS' || $type_code == 'SI' || $type_code == 'HO' || $type_code == 'SP' || $type_code == 'CK' || $type_code == 'CD' || $type_code == 'AP' || $type_code == 'FA' || $type_code == 'PH' || $type_code == 'PP' || $type_code == 'MP' || $type_code == 'FG' || $type_code == 'BB' || $type_code == 'MW' || $type_code == 'PS' || $type_code == 'IF' || $type_code == 'CL' || $type_code == 'RC' || $type_code == 'SM' || $type_code == 'PD' || $type_code == 'MS' || $type_code == 'SE' || $type_code == 'AC' || $type_code == 'CU' || $type_code == 'SK' || $type_code == 'GE' || $type_code == 'DF' || $type_code == 'MO' || $type_code == 'OC' || $type_code == 'BA' || $type_code == 'ME' || $type_code == 'BM' || $type_code == 'SO' || $type_code == 'SW' || $type_code == 'TO' || $type_code == 'TP' || $type_code == 'ET' || $type_code == 'PA' || $type_code == 'CR' || $type_code == 'DC' || $type_code == 'GA' || $type_code == 'MA' || $type_code == 'OH' || $type_code == 'BO' || $type_code == 'DE' || $type_code == 'FC') { ?>

                   
                    
       
                    <?php  if($_GET['edit']=='1'){?>
                        <form id="myform2" name="myform2"  action="studies_add_process_2022.php?details_type=<?php echo $details_type; ?>&type_code=<?php echo $_GET['type_code']; ?>&id=<?php echo $_GET['id']; ?>&edit=1" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php  }if($_GET['edit']=='0'){?>
                        <form id="myform2" name="myform2"  action="studies_add_process_2022.php?details_type=<?php echo $details_type; ?>&type_code=<?php echo $_GET['type_code']; ?>&edit=0" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php } ?>

                    <?php }else{ ?>
                        <?php  if($_GET['edit']=='1'){?>
                        <form id="myform2" name="myform2"  action="studies_add_process.php?details_type=<?php echo $details_type; ?>&type_code=<?php echo $_GET['type_code']; ?>&id=<?php echo $_GET['id']; ?>&edit=1" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php  }if($_GET['edit']=='0'){?>
                        <form id="myform2" name="myform2"  action="studies_add_process.php?details_type=<?php echo $details_type; ?>&type_code=<?php echo $_GET['type_code']; ?>&edit=0" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php } ?>

                        <?php  } ?>

                    <?php if($_GET['type_code']!=='0'){ ?>
                    <table class="table">

                    <tr>
                            <td width="100">
                                <label >رقم الكتاب</label>
                                </td>
                                <td width="100">
                                    <input type="text" class="number_input" name="ketab_num" value="<?php if($_GET['edit']=='1'){ echo $row['ketab_num'];}else{} ?>"/>
                                
                                </td>
                                <td width="100">
                                <label >تاريخ الكتاب</label>
                                </td>
                                <td width="100">
                                    <input  placeholder="" type="date" name="ketab_date" value="<?php if($_GET['edit']=='1'){ echo $row['ketab_date'];}else{} ?>">
                                </td>
                            </tr>
                    <?php if($_GET['edit']=='1'){ ?>
                          <tr>
                            <td >المنطقة</td>
                            <td>
                                <select name="area_code" id="" >
                                    <option value="<?php echo $row['area_code']; ?>"><?php echo $row['area_code']; ?></option>
                                    <option value="011">منطقة مدينة إدلب</option>
                                    <option value="012">منطقة أريحا</option>
                                    <option value="013">المنطقة الوسطى</option>
                                    <option value="014">منطقة سرمدا</option>
                                    <option value="015">منطقة حارم</option>
                                    <option value="016">منطقة جسر الشغور</option>
                                    <option value="017">المنطقة الشمالية</option>
                                    <option value="018">منطقة أطمة</option>
                                    <option value="019">M77</option>
                                </select>
                            </td>

                            <td>رمز المدن \ القرى</td>
                            <td>
                                <input type="number" name="city_code" value="<?php echo $row['city_code']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>الجهة \ الفعالية</td>

                            <td>
                            <select name="type_code" id="">
                                <option value="<?php echo $row['type_code']; ?>"><?php echo $row['type_code']; ?></option>
                                <optgroup label="2021">
                                    <option value="SH">البسطات والبراكيات</option>
                                    <option value="UV">الجامعات</option>
                                    <option value="RF">الفصائل الثورية</option>
                                    <option value="CE">الفعاليات الأهلية غير الرسمية</option>
                                    <option value="ES">المحلات الإلكترونية</option>
                                    <option value="RE">المكاتب العقارية</option>
                                    <option value="TR">المهربين</option>
                                    <option value="UD">تجار السلاح الذين لا يملكون محلات</option>
                                    <option value="AS">جمعية</option>
                                  
                                    <option value="MC">محل بيع وصيانة أجهزة الخليوي والحواسيب</option>
                                    <option value="FP">محلات الأسمدة والمبيدات</option>
                                    <option value="WA">محلات السلاح والذخيرة</option>
                                    <option value="CH">محلات الصرافة</option>
                                    <option value="TC">مركز تدريب</option>
                                    <option value="FS">محلات التزوير والأختام</option>
                                    <option value="CO">مكاتب السيارات</option>
                                    <option value="OR">منظمة</option>
                                </optgroup>
                                <optgroup label="2022">
                                    <option value="FA">مزرعة</option>
                                    <option value="PP">مطبعة</option>
                                    <option value="LW">مخرطة</option>
                                    <option value="MW">ورشة حدادة</option>
                                    <option value="BS">ورشة تصويج وبخ</option>
                                    <option value="CK">محل نسخ مفاتيح</option>
                                    <option value="CI">شبكات الاتصال والإنترنت</option>
                                    <option value="HO">فندق</option>
                                    <option value="TX">سيارة أجرة</option>
                                    <option value="SP">مسبح</option>
                                    <option value="AP">ملاهي</option>
                                    <option value="BB">نادي بناء أجسام</option>
                                    <option value="FG">نادي ألعاب قتالية</option>
                                    <option value="PH">مشفى خاص</option>
                                    <option value="MP">منصة إعلامية</option>
                                    <option value="CD">مركز دفاع مدني</option>
                                    <option value="SI">معهد دراسي</option>
                                    <option value="SC">شركة شحن بضائع</option>
                                    <option value="PS">محطة وقود</option>
                                    <option value="IF">منشأة صناعية</option>
                                    <option value="JS">محل صياغة الذهب والفضة</option>
                                    <option value="CA">وكالة تجارية</option>
                                    <option value="TM">علامة تجارية فارقة</option>
                                    <option value="QU">مقلع</option>
                                </optgroup>
                                <optgroup label="2023">
                                    <option value="CL">عشيرة</option>     
                                    <option value="RC">مطعم - مقهى كبير</option>   
                                    <option value="SM">سيارة بائع جوال</option>   
                                    <option value="PD">الصيدليات</option> 
                                    <option value="MS">المخازن الطبية ومستودعات الأدوية</option>
                                    <option value="SE">محلات الطاقة الشمسية</option>
                                    <option value="AC">المعاهد الشرعية</option>
                                    <option value="CU">المداجن</option>
                                    <option value="SK">مراكز جمع الخردة</option>
                                    <option value="GE">صالات الألعاب</option> 
                                    <option value="DF">معامل المنظفات</option> 
                                    <option value="MO">مكاتب الدراجات النارية</option>
                                    <option value="OC">مراكز تركيب أجهزة المراقبة</option>
                                    <option value="BA">محلات الحلاقة</option>
                                    <option value="ME">الإعلاميين</option>
                                    <option value="BM">منشآت صناعة المدخرات (البطاريات)</option>
                                    <option value="SO">المولات التجارية</option>  
                                    <option value="SW">سيارات بيع مياه الشرب</option> 
                                    <option value="TO">مكاتب الرقية والرقاة</option> 
                                    <!-- <option value="TP">مراكز بيع الدخان والأراكيل ومستلزماتها</option>  -->
                                    <option value="ET">محلات بيع وصيانة الأدوات الكهربائية</option> 
                                    <option value="PA">شركات الدعاية والإعلان</option> 
                                    <option value="CR">محلات زينة السيارات</option> 
                                    <option value="DC">مكاتب العملات الرقمية</option>    
                                    <!-- <option value="GA">الكراجات</option>  -->  
                                    <option value="MA">وكالات الدراجات النارية</option>   
                                    <option value="OH">دور الأيتام</option>   
                                    <option value="BO">مكاتب المقاولات والتعهدات</option>   
                                    <option value="DE">شركات خدمات التوصيل</option>   
                                    <!-- <option value="FC">منشآت المدينة الصناعية</option>  -->                        
                                </optgroup>
                            </select>
                            </td> 
                                <td>
                                الرقم التسلسلي
                            </td>      
                            <td>
                                <input type="number" name="type_num" id="" value="<?php echo $row['type_num']; ?>">
                            </td>           
                        </tr>
                    <?php } ?>
                    </table>
                    <?php } ?>
                        <?php 
                            ///////////// 2021 /////////////
                            if ($_GET['type_code']=='SH') {
                                include_once "studies/studies_SH.php";
                            }
                            if ($_GET['type_code']=='UV') {
                                include_once "studies/studies_UV.php";
                            }
                            if ($_GET['type_code']=='RF') {
                                include_once "studies/studies_RF.php";
                            }
                            if ($_GET['type_code']=='CE') {
                                include_once "studies/studies_CE.php";
                            }
                            if ($_GET['type_code']=='ES') {
                                include_once "studies/studies_ES.php";
                            }
                            if ($_GET['type_code']=='RE') {
                                include_once "studies/studies_RE.php";
                            }
                            if ($_GET['type_code']=='TR') {
                                include_once "studies/studies_TR.php";
                            }
                            if ($_GET['type_code']=='UD') {
                                include_once "studies/studies_UD.php";
                            }
                            if ($_GET['type_code']=='AS') {
                                include_once "studies/studies_AS.php";
                            }
                            
                            if ($_GET['type_code']=='MC') {
                                include_once "studies/studies_MC.php";
                            }
                            if ($_GET['type_code']=='FP') {
                                include_once "studies/studies_FP.php";
                            }
                            if ($_GET['type_code']=='WA') {
                                include_once "studies/studies_WA.php";
                            }
                            if ($_GET['type_code']=='CH') {
                                include_once "studies/studies_CH.php";
                            }
                            if ($_GET['type_code']=='TC') {
                                include_once "studies/studies_TC.php";
                            }
                            if ($_GET['type_code']=='FS') {
                                include_once "studies/studies_FS.php";
                            }
                            if ($_GET['type_code']=='CO') {
                                include_once "studies/studies_CO.php";
                            }
                            if ($_GET['type_code']=='OR') {
                                include_once "studies/studies_OR.php";
                            }
                            ///////////// 2022 /////////////
                            if ($_GET['type_code']=='QU') {
                                include_once "studies/2022/studies_QU.php";
                            }
                            if ($_GET['type_code']=='CA') {
                                include_once "studies/2022/studies_CA.php";
                            }
                            if ($_GET['type_code']=='JS') {
                                include_once "studies/2022/studies_JS.php";
                            }
                            if ($_GET['type_code']=='TM') {
                                include_once "studies/2022/studies_TM.php";
                            }
                            if ($_GET['type_code']=='SC') {
                                include_once "studies/2022/studies_SC.php";
                            }
                            if ($_GET['type_code']=='CI') {
                                include_once "studies/2022/studies_CI.php";
                            }
                            if ($_GET['type_code']=='TX') {
                                include_once "studies/2022/studies_TX.php";
                            }
                            if ($_GET['type_code']=='LW') {
                                include_once "studies/2022/studies_LW.php";
                            }
                            if ($_GET['type_code']=='BS') {
                                include_once "studies/2022/studies_BS.php";
                            }
                            if ($_GET['type_code']=='SI') {
                                include_once "studies/2022/studies_SI.php";
                            }
                            if ($_GET['type_code']=='HO') {
                                include_once "studies/2022/studies_HO.php";
                            }
                            if ($_GET['type_code']=='SP') {
                                include_once "studies/2022/studies_SP.php";
                            }
                            if ($_GET['type_code']=='CK') {
                                include_once "studies/2022/studies_CK.php";
                            }
                            if ($_GET['type_code']=='CD') {
                                include_once "studies/2022/studies_CD.php";
                            }
                            if ($_GET['type_code']=='AP') {
                                include_once "studies/2022/studies_AP.php";
                            }
                            if ($_GET['type_code']=='FA') {
                                include_once "studies/2022/studies_FA.php";
                            }
                            if ($_GET['type_code']=='PH') {
                                include_once "studies/2022/studies_PH.php";
                            }
                            if ($_GET['type_code']=='PP') {
                                include_once "studies/2022/studies_PP.php";
                            }

                            if ($_GET['type_code']=='PS') {
                                include_once "studies/2022/studies_PS.php";
                            }

                            if ($_GET['type_code']=='IF') {
                                include_once "studies/2022/studies_IF.php";
                            }

                            if ($_GET['type_code']=='MP') {
                                include_once "studies/2022/studies_MP.php";
                            }

                            if ($_GET['type_code']=='FG') {
                                include_once "studies/2022/studies_FG.php";
                            }

                            if ($_GET['type_code']=='BB') {
                                include_once "studies/2022/studies_BB.php";
                            }

                            if ($_GET['type_code']=='MW') {
                                include_once "studies/2022/studies_MW.php";
                            }
                            //2023
                            if ($_GET['type_code']=='CL') {
                                include_once "studies/2023/studies_CL.php";
                            } 
                            if ($_GET['type_code']=='RC') {
                                include_once "studies/2023/studies_RC.php";
                            }
                            if ($_GET['type_code']=='MS') {
                                include_once "studies/2023/studies_MS.php";
                            }
                            if ($_GET['type_code']=='SM') {
                                include_once "studies/2023/studies_SM.php";
                            }
                            if ($_GET['type_code']=='PD') {
                                include_once "studies/2023/studies_PD.php";
                            }
                            if ($_GET['type_code']=='SE') {
                                include_once "studies/2023/studies_SE.php";
                            }
                            if ($_GET['type_code']=='AC') {
                                include_once "studies/2023/studies_AC.php";
                            }
                            if ($_GET['type_code']=='CU') {
                                include_once "studies/2023/studies_CU.php";
                            }
                            if ($_GET['type_code']=='SK') {
                                include_once "studies/2023/studies_SK.php";
                            }
                            if ($_GET['type_code']=='GE') {
                                include_once "studies/2023/studies_GE.php";
                            }
                            if ($_GET['type_code']=='DF') {
                                include_once "studies/2023/studies_DF.php";
                            }
                            if ($_GET['type_code']=='MO') {
                                include_once "studies/2023/studies_MO.php";
                            }
                            if ($_GET['type_code']=='OC') {
                                include_once "studies/2023/studies_OC.php";
                            }
                            if ($_GET['type_code']=='BA') {
                                include_once "studies/2023/studies_BA.php";
                            }
                            if ($_GET['type_code']=='ME') {
                                include_once "studies/2023/studies_ME.php";
                            }
                            if ($_GET['type_code']=='BM') {
                                include_once "studies/2023/studies_BM.php";
                            }
                            if ($_GET['type_code']=='SO') {
                                include_once "studies/2023/studies_SO.php";
                            }
                            if ($_GET['type_code']=='SW') {
                                include_once "studies/2023/studies_SW.php";
                            }
                            if ($_GET['type_code']=='TO') {
                                include_once "studies/2023/studies_TO.php";
                            }
                            if ($_GET['type_code']=='TP') {
                                include_once "studies/2023/studies_TP.php";
                            }
                            if ($_GET['type_code']=='ET') {
                                include_once "studies/2023/studies_ET.php";
                            }
                            if ($_GET['type_code']=='PA') {
                                include_once "studies/2023/studies_PA.php";
                            }
                            if ($_GET['type_code']=='CR') {
                                include_once "studies/2023/studies_CR.php";
                            }
                            if ($_GET['type_code']=='DC') {
                                include_once "studies/2023/studies_DC.php";
                            }

                            if ($_GET['type_code']=='GA') {
                                include_once "studies/2023/studies_GA.php";
                            }
                            if ($_GET['type_code']=='MA') {
                                include_once "studies/2023/studies_MA.php";
                            }
                            if ($_GET['type_code']=='OH') {
                                include_once "studies/2023/studies_OH.php";
                            }
                            if ($_GET['type_code']=='BO') {
                                include_once "studies/2023/studies_BO.php";
                            }
                            if ($_GET['type_code']=='DE') {
                                include_once "studies/2023/studies_DE.php";
                            }
                            if ($_GET['type_code']=='FC') {
                                include_once "studies/2023/studies_DC.php";
                            }


                          
                          
                        
                        ?>
                                
                           
                               
                                  
                                

                                 </form>
                            </div>
                          </div>

        </div>


            <?php include_once "inc/footer.php"; ?>
    </body>
</html>

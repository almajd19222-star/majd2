<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php include_once "columns_names.php"; ?>
<?php
if (isset($_POST['submit'])){

  $r_num_old = mysqli_real_escape_string($conn, $_POST['r_num_old']);
  $r_date_old = mysqli_real_escape_string($conn, $_POST['r_date_old']);
  $jeha_old = mysqli_real_escape_string($conn, $_POST['jeha_old']);
  $details_type_old = mysqli_real_escape_string($conn, $_POST['details_type_old']);

  $r_num_new = mysqli_real_escape_string($conn, $_POST['r_num_new']);
  $r_date_new = mysqli_real_escape_string($conn, $_POST['r_date_new']);
  $jeha_new = mysqli_real_escape_string($conn, $_POST['jeha_new']);
  $details_type_new = mysqli_real_escape_string($conn, $_POST['details_type_new']);

  /* $sql_search = "SELECT
    e_year, ketab_num, ketab_date, details_type , jeha 
    FROM study where ketab_num=$r_num_old AND e_year=$year_old AND details_type = '$details_type_old' AND jeha = '$jeha_old' ORDER BY add_date DESC";
    $result = mysqli_query($conn, $sql_search); */
    
    //while($row = mysqli_fetch_assoc($result)) {

      $sql3 = "INSERT INTO `study` (`general_code`, `study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, `jeha`, `sader`, `e_id`, `details_type`, `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, `ketab_num`, `ketab_date`, `ketab_type` , `origin_sendfrom`, `id_num` , `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`,`estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, `isPrivate`)  
      
      SELECT `general_code`,`study_num`, `study_num_date`, `study_request_jeha`, `study_reason`, `study_date`, `nick_name`, `name`, `fname`, `lname`, `mname`, `pbirth`, `dbirth`, `subname`, `sex`, `national`, `awsaf`, `family_status`, `child_num`, `wife_name`, `wife_address`, `work_before`, `work_after`, `work_now`, `study`, `money_status`, `dealing`, `service`, `special`, `pre_address`, `address`, `address_type`, `fasael`, `opinion`, `travels`, `n_relatives`, `d_relatives`, `f_relatives`, `s_relatives`, `religon_status`, `mind`, `lead`, `personal`, `affected_others`, `affected_to`, `relation_others`, `speak`, `important_awsaf`, `sawabek`, `phone`, `details`, `brief`, `foto1`, `foto2`, `foto3`, `attach`, `attach_extension`, `added_by`, `add_date`, `edbara_num`, `edbara_date`, '$jeha_new', `sader`, `e_id`, '$details_type_new', `resala_type`, `sendto`, `sendfrom`, `study_jeha`, `study_organizer`, `study_masdar`, `study_opinion`, `study_result`, `negative_reason`, `dewan_num`, `dewan_date`, $r_num_new, '$r_date_new', `ketab_type` , `origin_sendfrom`, `id_num`, `nafeer_date`, `entisab_date`, `service_place`, `hts_opinion`, `hts_work_details`, `estimara_num`, `talab_num`, `wives_num`, `tasreeh_date`, `isPrivate` FROM study  where ketab_num=$r_num_old AND ketab_date='$r_date_old' AND details_type = '$details_type_old' AND jeha = '$jeha_old'";
      if(mysqli_query($conn, $sql3)){
        header ("Location: studies_in_oneKetab.php?r_num_new=$r_num_new&r_date_new=$r_date_new&details_type_new=$details_type_new&jeha_old=$jeha_old&details_type_old=$details_type_old&add=true");
        exit;
      }else{
        echo "Error: "  . "<br>" . mysqli_error($conn);
      }  

    //}

           

mysqli_close($conn);
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
                                    <h4 class="card-title" >تجميع دراسات من عدة جهات</hل4>
                                    <h6 class="card-subtitle"></h6>
                                    <div class="table-responsive">
                                  <form id="myform" name="myform"  action="studies_in_oneKetab.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                  <table id="" class="table table-borderless">
                                   <tbody>                                  
                                        
                                          <tr>
                                          <td >
                                            <label >رقم وتاريخ الكتاب المعني</label>
                                          </td>
                                         
                                          <td>                                              
                                            <input  type="text" class="number_input" required name="r_num_old" value="">
                                            <input  type="date" class="" required name="r_date_old" value="">
                                          </td>
                                          
                                          <td >
                                            <label >الجهة المعنية</label>
                                          </td>
                                           <td>                                              
                                          <?php 
                                            if($jeha_profile=='الإدارة المركزية للمعلومات'){
                                              $sqlall = "SELECT DISTINCT  send_name FROM jehat where sendtype='send_to' AND jeha='$jeha_profile'";
                                              $resultall = mysqli_query($conn, $sqlall); 
                                            }else{
                                              $sqlall = "SELECT 2nd_jeha , id FROM jehat where `jeha`='$jeha_profile' AND 2nd_jeha!='' AND 2nd_jeha!='null' AND user_id=$userid";
                                              $resultall = mysqli_query($conn, $sqlall); 
                                            }
                                          ?> 
                                          <select name="jeha_old" required >
                                          <option value="<?php echo $jeha_profile;?>"><?php echo $jeha_profile;?></option> 
                                          <option value="<?php echo @$_GET['jeha_old']; ?>"><?php echo @$_GET['jeha_old']; ?></option>                                        
                                          <?php while($rowall = mysqli_fetch_assoc($resultall)) { ?>
                                            <?php if($jeha_profile=='الإدارة المركزية للمعلومات'){?>
                                            <option value="<?php echo $rowall["send_name"] ?>"><?php echo $rowall["send_name"] ?></option>
                                           
                                            <?php }else{?>
                                              <option value="<?php echo $rowall["2nd_jeha"] ?>"><?php echo $rowall["2nd_jeha"] ?></option>                                              
                                          <?php } } ?>
                                      </select>
                                           </td>
                                           <td>مرحلة العمل المعنية</td>
                                           <td>
                                          <select required name="details_type_old">
                                            <option value="<?php echo @$_GET['details_type_old']; ?>"><?php echo @$_GET['details_type_old']; ?></option>                              
                                            <option value="صادر">صادر</option>
                                            <option value="قيد المعالجة">قيد المعالجة</option>
                                            <option value="وارد">وارد</option>                                        
                                          </select>
                                          </td>
                                        </tr> 

                                        <tr>
                                          <td >
                                            <label >رقم و تاريخ الكتاب المستهدف</label>
                                          </td>
                                          <td>
                                            <input type="text" class="number_input" required name="r_num_new" value="<?php echo @$_GET['r_num_new']; ?>">
                                            <input type="date" required name="r_date_new" value="<?php echo @$_GET['r_date_new']; ?>">
                                          
                                          </td>
                                          
                                          <td >
                                            <label >الجهة المستهدفة</label>
                                          </td>
                                           <td>                                              
                                          <?php 
                                            if($jeha_profile=='الإدارة المركزية للمعلومات'){
                                            $sqlall = "SELECT DISTINCT send_name FROM jehat where sendtype='send_to' AND jeha='$jeha_profile'";
                                            $resultall = mysqli_query($conn, $sqlall); 
                                            }else{
                                              $sqlall = "SELECT 2nd_jeha , id FROM jehat where `jeha`='$jeha_profile' AND 2nd_jeha!='' AND 2nd_jeha!='null' AND user_id=$userid";
                                            $resultall = mysqli_query($conn, $sqlall); 
                                            }
                                          ?> 
                                          <select name="jeha_new">
                                          <option value="<?php echo $jeha_profile;?>"><?php echo $jeha_profile;?></option>                                        
                                          <?php while($rowall = mysqli_fetch_assoc($resultall)) { ?>
                                            <?php if($jeha_profile=='الإدارة المركزية للمعلومات'){?>
                                            <option value="<?php echo $rowall["send_name"] ?>"><?php echo $rowall["send_name"] ?></option>
                                           
                                            <?php }else{?>
                                              <option value="<?php echo $rowall["2nd_jeha"] ?>"><?php echo $rowall["2nd_jeha"] ?></option>                                              
                                          <?php } } ?>
                                      </select>
                                           </td>
                                           <td>مرحلة العمل المستهدفة</td>
                                           <td>
                                          <select name="details_type_new" required>
                                            <option value="<?php echo @$_GET['details_type_new']; ?>"><?php echo @$_GET['details_type_new']; ?></option>                              
                                            <option value="صادر">صادر</option>
                                            <option value="قيد المعالجة">قيد المعالجة</option>
                                            <option value="وارد">وارد</option>                                        
                                          </select>
                                          </td>
                                        </tr> 
                                       
                                     </tbody>
                               </table>
                                   <input type="hidden" name="added_by" value="<?php echo $_SESSION["user"]; ?>"/>
                                  
                                 </br></br><input type="submit" name="submit" onclick="return confirm('متأكد من الاستمرار؟');" value="ادخل"/>

                                 </form>
                            </div>
                          </div>

        </div>


            <?php include_once "inc/footer.php"; ?>
    </body>
</html>

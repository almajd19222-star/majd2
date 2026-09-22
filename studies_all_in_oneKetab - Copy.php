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

     //
      $table_name[]='study';
      //////////// UPDATE studies tables ////////////
      $table_name[]='studies_association';
      $table_name[]='studies_association_attachment';
      $table_name[]='studies_association_projects';
      $table_name[]='studies_car_shops';
      $table_name[]='studies_computers_phones_shops';
      $table_name[]='studies_estate_offices';
      $table_name[]='studies_exchange_shops';
      $table_name[]='studies_factions';
      $table_name[]='studies_factions_attachment_1';
      $table_name[]='studies_factions_attachment_2';
      $table_name[]='studies_factions_attachment_3';
      $table_name[]='studies_factions_attachment_4';
      $table_name[]='studies_factions_attachment_5';
      $table_name[]='studies_factions_attachment_6';
      $table_name[]='studies_factions_attachment_7';
      $table_name[]='studies_factions_attachment_8';
      $table_name[]='studies_factions_attachment_9';
      $table_name[]='studies_factions_attachment_10';
      $table_name[]='studies_fertilizers_and_pesticides';
      $table_name[]='studies_fertilizers_and_pesticides_attachment';
      $table_name[]='studies_forgery_and_stamps_offices';
      $table_name[]='studies_it_shops';
      $table_name[]='studies_kiosks';
      $table_name[]='studies_organizations';
      $table_name[]='studies_organization_attachment';
      $table_name[]='studies_organization_projects';
      $table_name[]='studies_smugglers';
      $table_name[]='studies_training_centre';
      $table_name[]='studies_training_centre_attachment';
      $table_name[]='studies_training_centre_projects';
      $table_name[]='studies_universities';
      $table_name[]='studies_unofficial_civil_activities';
      $table_name[]='studies_weapon_shops';
      $table_name[]='studies_weapon_shops_attachment';      
      $table_name[]='studies_weapon_traders';
    //// 828 ////
      $table_name[]='studies_828_checkpoint_study';
      $table_name[]='studies_828_goal';
      $table_name[]='studies_828_military_site_study';
      $table_name[]='studies_828_military_site_study_attachment';
      $table_name[]='studies_828_personal_security_study';
      $table_name[]='studies_828_security_center_study';
      $table_name[]='studies_828_security_center_study_attachment';
      $table_name[]='studies_828_town';
      $table_name[]='studies_828_town_bakeries';
      $table_name[]='studies_828_town_council';
      $table_name[]='studies_828_town_demographic_information';
      $table_name[]='studies_828_town_education';
      $table_name[]='studies_828_town_schools';
      $table_name[]='studies_828_town_faculties';
      $table_name[]='studies_828_town_famous_families';
      $table_name[]='studies_828_town_famous_mosques';
      $table_name[]='studies_828_town_important_military_people';
      $table_name[]='studies_828_town_influencers';
      $table_name[]='studies_828_town_made_by';
      $table_name[]='studies_828_town_military_branches';
      $table_name[]='studies_828_town_military_places';
      $table_name[]='studies_828_town_mukhtar_name';
      $table_name[]='studies_828_town_new_military_places';
      $table_name[]='studies_828_town_organisation';
      $table_name[]='studies_828_town_public_utilities';
      $table_name[]='studies_828_town_rich_people';
      $table_name[]='studies_828_town_russian_cultral_centers';
      $table_name[]='studies_828_town_shari3a_institutes';
      $table_name[]='studies_828_town_shi3a_centers';
    //// 2022 ////
      $table_name[]='studies_2022';
      $table_name[]='studies_2022_attachments';
    //
      $number = count($table_name);

      if ($number >= 1) {
        for ($i=0; $i<$number; $i++) {
          $sql_table_name= $table_name[$i];
          $sql = "UPDATE `$sql_table_name` SET
          details_type='$details_type_new',
          jeha='$jeha_new',          
          ketab_num=$r_num_new,
          ketab_date='$r_date_new',          
          add_date = current_timestamp()
          where jeha = '$jeha_old' AND ketab_num = $r_num_old AND ketab_date='$r_date_old' AND details_type='$details_type_old'" ;              
          if (mysqli_query($conn, $sql)) {
          } else {
              echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
              exit;
          }
        }
    }

      $sql = "UPDATE `tbl_uploads` SET
      `details_type`='$details_type_new',
      `jeha`='$jeha_new',          
      `num`=$r_num_new,
      `date`='$r_date_new',          
      `add_date` = current_timestamp()
      where `jeha` = '$jeha_old' AND `num` = $r_num_old AND `date`='$r_date_old' AND details_type='$details_type_old'" ;              
      if (mysqli_query($conn, $sql)) {
      } else {
          echo "Error:  ".$sql_table_name. "<br>" . mysqli_error($conn);
          exit;
      }


      
        header ("Location: studies_all_in_oneKetab.php?r_num_new=$r_num_new&r_date_new=$r_date_new&details_type_new=$details_type_new&jeha_old=$jeha_old&details_type_old=$details_type_old&add=true");
      exit;

    

           

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
                                    <h4 class="card-title" >تجميع المسح الأمني والدراسات المرتبطة بها</h4>
                                    <h6 class="card-subtitle"></h6>
                                    <div class="table-responsive">
                                  <form id="myform" name="myform"  action="studies_all_in_oneKetab.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
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
                                         
                                              $sqlall = "SELECT 2nd_jeha , id FROM jehat where `jeha`='$jeha_profile' AND 2nd_jeha!='' AND 2nd_jeha!='null' AND user_id=$userid";
                                              $resultall = mysqli_query($conn, $sqlall); 
                                            
                                          ?> 
                                          <select name="jeha_old" required >
                                          <option value="<?php echo @$_GET['jeha_old']; ?>"><?php echo @$_GET['jeha_old']; ?></option>                                        
                                          <?php while($rowall = mysqli_fetch_assoc($resultall)) { ?>
                                            
                                              <option value="<?php echo $rowall["2nd_jeha"] ?>"><?php echo $rowall["2nd_jeha"] ?></option>                                              
                                          <?php  } ?>
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
                                          
                                          <select name="jeha_new">
                                          <option value="<?php echo $jeha_profile;?>"><?php echo $jeha_profile;?></option>                                        
                                          
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

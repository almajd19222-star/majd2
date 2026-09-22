<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php

$sendtosql = "SELECT id,  send_name ,  `sendtype` FROM jehat where sendtype='send_to' AND `jeha`='$jeha_profile'  ORDER BY send_name ASC";
$sendtoraw = mysqli_query($conn,$sendtosql);    

$sendfromsql = "SELECT id, send_name ,  `sendtype` FROM jehat where sendtype='send_from' AND `jeha`='$jeha_profile' ORDER BY send_name ASC";
$sendfromraw = mysqli_query($conn,$sendfromsql);

$id=$_GET['id'];
$sql="SELECT * FROM work_tracking WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])){
 
   
    
  

    @$status = mysqli_real_escape_string($conn, $_POST['status']);
    @$takleef_name = mysqli_real_escape_string($conn, $_POST['takleef_name']);
    @$requested_jeha = mysqli_real_escape_string($conn, $_POST['requested_jeha']);

    if(!empty($_POST['handle_from_date'])){
      @$handle_from_date = mysqli_real_escape_string($conn, $_POST['handle_from_date']);
    }else{
      @$handle_from_date=mysqli_real_escape_string($conn, '0000-00-00');
    }
    
    if(!empty($_POST['ketab_num_wared'])){
      @$ketab_num_wared = mysqli_real_escape_string($conn, $_POST['ketab_num_wared']);
    }else{
      @$ketab_num_wared=mysqli_real_escape_string($conn, 0);
    }

    if(!empty($_POST['takleef_date'])){
      @$takleef_date = mysqli_real_escape_string($conn, $_POST['takleef_date']);
    }else{
      @$takleef_date=mysqli_real_escape_string($conn, '0000-00-00');
    }

    
    if(!empty($_POST['ketab_num_sader'])){
      @$ketab_num_sader = mysqli_real_escape_string($conn, $_POST['ketab_num_sader']);
    }else{
      @$ketab_num_sader=mysqli_real_escape_string($conn, 0);
    }

    if(!empty($_POST['reply_date'])){
      @$reply_date = mysqli_real_escape_string($conn, $_POST['reply_date']);
    }else{
      @$reply_date=mysqli_real_escape_string($conn, '0000-00-00');
    }



    @$send_to = mysqli_real_escape_string($conn, $_POST['send_to']);

    if(!empty($_POST['ketab_num_reply'])){
      @$ketab_num_reply = mysqli_real_escape_string($conn, $_POST['ketab_num_reply']);
    }else{
      @$ketab_num_reply=mysqli_real_escape_string($conn, 0);
    }

    if(!empty($_POST['handle_to_date'])){
      @$handle_to_date = mysqli_real_escape_string($conn, $_POST['handle_to_date']);
    }else{
      @$handle_to_date=mysqli_real_escape_string($conn, '0000-00-00');
    }

    if(!empty($_POST['ketab_num_to'])){
      @$ketab_num_to = mysqli_real_escape_string($conn, $_POST['ketab_num_to']);
    }else{
      @$ketab_num_to=mysqli_real_escape_string($conn, 0);
    }

    @$study_for = mysqli_real_escape_string($conn, $_POST['study_for']);
    @$notes = mysqli_real_escape_string($conn, $_POST['notes']);

      @$added_by = mysqli_real_escape_string($conn, $_POST['added_by']);

      if(!empty($_POST['id_num'])){
        $id_num = mysqli_real_escape_string($conn, $_POST['id_num']);
      }else{
        $id_num=mysqli_real_escape_string($conn, 0);
      }
    

      $sql = "UPDATE work_tracking SET 
      `id_num`='$id_num', 
      `status`='$status',        
      `takleef_name`='$takleef_name',
      `requested_jeha`='$requested_jeha', 
      `handle_from_date`='$handle_from_date',
      `ketab_num_wared`='$ketab_num_wared',
      `takleef_date`='$takleef_date',
      `ketab_num_sader`=$ketab_num_sader,
      `send_to`='$send_to',
      `reply_date`='$reply_date',
      `ketab_num_reply`=$ketab_num_reply,
      `handle_to_date`='$handle_to_date',
      `ketab_num_to`=$ketab_num_to,
      `study_for`='$study_for',
      `notes`='$notes',
      `add_date` = current_timestamp()  
      where id = $id" ;

      if (mysqli_query($conn, $sql)) {       
        header ("Location: work_track_edit.php?id=$id&edit=true");
      }else {
        echo "Error work_track_edit: " . "<br>" . mysqli_error($conn);
        exit;
      }
    
  
  mysqli_close($conn);
}else {}
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
                                  <?php if(!empty($_GET['edit'])){ ?>
                                  <script>  //sessionStorage.clear();  </script>
                                  <?php echo "تم التعديل بنجاح";}
                                  ?>  
                                </h4>
                                    <h4 class="card-title" >إضافة</hل4>
                                    <h6 class="card-subtitle"></h6>
                                    <div class="table-responsive">
                                  <form id="myform" name="myform"  action="work_track_edit.php?id=<?php echo $id; ?>" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                
                        
                            <div class="" id="collapse1">
                            
                                  <table id="" class="table table-borderless">
                                   <tbody>  
                                   <?php if (strpos($jeha_profile, 'داخلي') !== false || strpos($jeha_profile, 'هيئة الرقابة والتفتيش') !== false || $jeha_profile == 'الإدارة المركزية للمعلومات'){ ?>       
                                      <tr>
                                          <td width="150">
                                              <label >الرقم الذاتي</label>
                                          </td>
                                          <td>
                                              <input  placeholder="الرقم الذاتي" maxlength="7" type="text" id="id_num" name="id_num" value="<?php echo $row['id_num']; ?>">
                                          </td>
                                          <td>        
                                      </tr>
                                  <?php }?> 
                                   <tr>
                                      <td>                                   
                                        <label>تاريخ الاستلام</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="handle_from_date" value="<?php echo $row['handle_from_date'] ?>">
                                      </td>

                                      <td>                                   
                                        <label>رقم الكتاب الوارد</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_wared" value="<?php echo $row['ketab_num_wared'] ?>">
                                      </td>

                                    </tr>

                                  

                                    <tr>
                                      <td width="150">
                                        <label >الجهة الطالبة</label>
                                      </td>
                                      <td>
                                        <input type="text" name="requested_jeha" id="sendfrom" value="<?php echo $row['requested_jeha'] ?>"> 
                                        <br>                                     
                                        <select  multiple>
                                          <option value="">لايوجد</option>                                             
                                          <?php while($sendfromrow = mysqli_fetch_assoc($sendfromraw)) {?>                                              
                                            <option class="sendfrom" value="<?php echo $sendfromrow['send_name'];?>"><?php echo $sendfromrow['send_name'];?></option>
                                          <?php }?>
                                        </select>                                                  
                                      </td>

                                      <td width="150">
                                        <label >الجهة المكلفة</label>
                                      </td>
                                      <td>
                                        <input type="text"  name="send_to" id="sendto" value="<?php echo $row['send_to'] ?>"> 
                                        <br>                                     
                                        <select  multiple>
                                          <option value="">لايوجد</option>                                             
                                          <?php while($sendtorow = mysqli_fetch_assoc($sendtoraw)) {?>                                              
                                            <option class="sendto" value="<?php echo $sendtorow['send_name'];?>"><?php echo $sendtorow['send_name'];?></option>
                                          <?php }?>
                                        </select>                                                  
                                      </td>

                                          </tr>
                                      
                                   

                                   

                                    <tr>
                                      <td>                                   
                                        <label>رقم الكتاب الصادر</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_sader" value="<?php echo $row['ketab_num_sader'] ?>">
                                      </td>

                                      <td>                                   
                                        <label>تاريخ الصدور</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="takleef_date" value="<?php echo $row['takleef_date'] ?>">
                                      </td>

                                    </tr>

                                  

                                    <tr>
                                    <td>                                   
                                        <label>رقم كتاب رد الجهة المكلفة</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_reply" value="<?php echo $row['ketab_num_reply'] ?>">
                                      </td>

                                      <td>                                   
                                        <label>تاريخ الورود من الجهة المكلفة</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="reply_date" value="<?php echo $row['reply_date'] ?>">
                                      </td>
                                    </tr>

                                   

                                  
                                    </tr>

                                    <tr>
                                      <td>                                   
                                        <label>رقم كتاب الرد على الجهة الطالبة</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_to" value="<?php echo $row['ketab_num_to'] ?>">
                                      </td>

                                      <td>                                   
                                        <label>تاريخ تسليم الجهة الطالبة</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="handle_to_date" value="<?php echo $row['handle_to_date'] ?>">
                                      </td>

                                    </tr>

                                    <tr>
                                      <td>                                   
                                        <label>الأخ المكلف</label>                                
                                      </td>
                                      <td>
                                        <input type="text" name="takleef_name" value="<?php echo $row['takleef_name'] ?>">
                                      </td>
                                      <td>                                   
                                        <label>المطلوب للدراسة</label>                                
                                      </td>
                                      <td>
                                        <input type="text" name="study_for" value="<?php echo $row['study_for'] ?>">
                                      </td>
                                     
                                          </tr>
                                      <tr>
                                    <td>                                   
                                        <label>ملاحظات</label>                                
                                      </td>
                                      <td>
                                        <textarea name="notes" id="" cols="30" rows="5"><?php echo $row['notes'] ?></textarea>
                                      </td>
                                      <td>                                   
                                        <label>الحالة</label>                                
                                      </td>
                                      <td>
                                        <select name="status" id="">
                                          <option value="<?php echo $row['status'] ?>"><?php echo $row['status'] ?></option>
                                          <option value="قيد العمل">قيد العمل</option>
                                          <option value="تم الرد">تم الرد</option>
                                          <option value="لم يتم الرد">لم يتم الرد</option>
                                          <option value="تعذر">تعذر</option>
                                        </select>
                                      </td>

                                    </tr>

                                          <tr>
                                      <td colspan="4">
                                      <input type="submit" name="submit" value="ادخل"/>
                                      </td>
                                      </tr> 
                                     </tbody>
                               </table>
                               
                                
                            </div>
                                   <input type="hidden" name="added_by" value="<?php echo $_SESSION["user"]; ?>"/>
                                  
                                

                                 </form>
                            </div>
                          </div>

        </div>


            <?php include_once "inc/footer.php"; ?>
    </body>
</html>

<?php include_once "inc/session.php"; ?>
<?php include_once "inc/config.php";
include_once "inc/users_roles.php"; ?>
<?php

$sendtosql = "SELECT id,  send_name ,  `sendtype` FROM jehat where sendtype='send_to' AND `jeha`='$jeha_profile'  ORDER BY send_name ASC";
$sendtoraw = mysqli_query($conn,$sendtosql);    

$sendfromsql = "SELECT id, send_name ,  `sendtype` FROM jehat where sendtype='send_from' AND `jeha`='$jeha_profile' ORDER BY send_name ASC";
$sendfromraw = mysqli_query($conn,$sendfromsql);




if (isset($_POST['submit'])){
 
    if(!empty($_POST['num'])){
      @$num = mysqli_real_escape_string($conn, $_POST['num']);
    }else{
      @$num=mysqli_real_escape_string($conn, 0);
    }
    
    
   //get last number of dewan_num in database
$sql_track_num_max = "SELECT num FROM work_tracking where num=(SELECT max(num) FROM work_tracking)";
$result_track_num_max = mysqli_query($conn, $sql_track_num_max);
$row_track_num_max = mysqli_fetch_assoc($result_track_num_max);

if($row_track_num_max > 0) {     
  $track_num_max = $row_track_num_max['num']+1;  
}else { $track_num_max = 1;}
///

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
    @$study_for = mysqli_real_escape_string($conn, $_POST['study_for']);
    @$notes = mysqli_real_escape_string($conn, $_POST['notes']);

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


      @$added_by = mysqli_real_escape_string($conn, $_POST['added_by']);

      if(!empty($_POST['id_num'])){
        $id_num = mysqli_real_escape_string($conn, $_POST['id_num']);
      }else{
        $id_num=mysqli_real_escape_string($conn, 0);
      }

      $sql = "INSERT INTO `work_tracking` (`id_num`,`status`, `num`, `takleef_name`, `requested_jeha`, `handle_from_date`, `ketab_num_wared`, `takleef_date`, `ketab_num_sader`, `send_to`, `reply_date`, `ketab_num_reply`, `handle_to_date`, `ketab_num_to`, `study_for`, `notes`, `jeha`, `added_by`, `add_date`) 
      VALUES ('$id_num', '$status', $track_num_max, '$takleef_name', '$requested_jeha', '$handle_from_date', $ketab_num_wared, '$takleef_date', $ketab_num_sader, '$send_to', '$reply_date', $ketab_num_reply, '$handle_to_date', '$ketab_num_to','$study_for', '$notes', '$jeha_profile','$added_by', current_timestamp())";

      if (mysqli_query($conn, $sql)) {       
        header ("Location: work_track_add.php?add=true");
      }else {
        echo "Error work_track_add: " . "<br>" . mysqli_error($conn);
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
                                  <?php include_once "inc/errors.php"; ?>  
                                </h4>
                                    <h4 class="card-title" >إضافة</hل4>
                                    <h6 class="card-subtitle"></h6>
                                    <div class="table-responsive">
                                  <form id="myform" name="myform"  action="work_track_add.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                                
                        
                            <div class="" id="collapse1">
                            
                                  <table id="" class="table table-borderless">
                                   <tbody>  
                                   <?php if (strpos($jeha_profile, 'داخلي') !== false || strpos($jeha_profile, 'هيئة الرقابة والتفتيش') !== false || $jeha_profile == 'الإدارة المركزية للمعلومات'){ ?>       
                                      <tr>
                                          <td width="150">
                                              <label >الرقم الذاتي</label>
                                          </td>
                                          <td>
                                              <input  placeholder="الرقم الذاتي" maxlength="7" type="text" id="id_num" name="id_num" value="">
                                          </td>
                                          <td>        
                                      </tr>
                                  <?php }?>  
                                   <tr>
                                      <td>                                   
                                        <label>تاريخ الاستلام</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="handle_from_date">
                                      </td>

                                      <td>                                   
                                        <label>رقم الكتاب الوارد</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_wared">
                                      </td>

                                    </tr>

                                  

                                    <tr>
                                      <td width="150">
                                        <label >الجهة الطالبة</label>
                                      </td>
                                      <td>
                                        <input type="text" readonly value="" name="requested_jeha" id="sendfrom"> 
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
                                        <input type="text" readonly value="" name="send_to" id="sendto"> 
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
                                        <input type="number" name="ketab_num_sader">
                                      </td>

                                      <td>                                   
                                        <label>تاريخ الصدور</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="takleef_date">
                                      </td>

                                    </tr>

                                  

                                    <tr>
                                    <td>                                   
                                        <label>رقم كتاب رد الجهة المكلفة</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_reply">
                                      </td>

                                      <td>                                   
                                        <label>تاريخ الورود من الجهة المكلفة</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="reply_date">
                                      </td>
                                    </tr>

                                   

                                  
                                    </tr>

                                    <tr>
                                      <td>                                   
                                        <label>رقم كتاب الرد على الجهة الطالبة</label>                                
                                      </td>
                                      <td>
                                        <input type="number" name="ketab_num_to">
                                      </td>

                                      <td>                                   
                                        <label>تاريخ تسليم الجهة الطالبة</label>                                
                                      </td>
                                      <td>
                                        <input type="date" name="handle_to_date">
                                      </td>

                                    </tr>

                                    <tr>
                                      <td>                                   
                                        <label>الأخ المكلف</label>                                
                                      </td>
                                      <td>
                                        <input type="text" name="takleef_name">
                                      </td>

                                      <td>                                   
                                        <label>المطلوب للدراسة</label>                                
                                      </td>
                                      <td>
                                        <input type="text" name="study_for">
                                      </td>

                                    </tr>
                                    <tr>
                                    <td>                                   
                                        <label>ملاحظات</label>                                
                                      </td>
                                      <td>
                                        <textarea name="notes" id="" cols="30" rows="5"></textarea>
                                      </td>
                                      <td>                                   
                                        <label>الحالة</label>                                
                                      </td>
                                      <td>
                                        <select name="status" id="">
                                          <option value=""></option>
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

 <h2 style="text-align: center; color: red;">
                
                          <p>جدول المتابعة
                            <?php if($hasPerm_tracking_table_write==1){?>
                          <a href="work_track_add.php"><i style="font-size: 2.5rem" class="zwicon-plus-square"></i></a>
                          <?php }?>
                          </p>
                </h2>
                <h4 style="text-align: center; color: red;">
                 
                </h4>


                    <?php
                        $sql="SELECT * FROM work_tracking where jeha = '$jeha_profile' ORDER BY add_date DESC";
                          ?>
                              
                      
                     
                   
                <?php if(!empty($sql)) {
                            $result = mysqli_query($conn, $sql);
                            
                            if (mysqli_num_rows($result) > 0) { 
                              
                              ?>

                                  <?php $td_class = "show-read-more"; ?>
                                        <table id=""  class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    
                                                    <th>حذف</th>
                                                    <th>تفاصيل</th>
                                                    <th>الحالة</th>
                                                    <th>المطلوب للدراسة</th>
                                                   <!--  <th>الرقم</th>  --> 
                                                    <th>الجهة الطالبة</th>                                                
                                                    <th>الجهة المكلفة</th>
                                                   
                                                    <th>رقم الكتاب الوارد </th>
                                                    <th>تاريخ الاستلام</th> 
                                                  
                                                    <th>رقم الكتاب الصادر</th> 
                                                    <th>تاريخ الصدور</th> 

                                                    <th>الأخ المكلف</th>                                                  
                                                    <th>رقم كتاب رد الجهة المكلفة</th> 
                                                    <th >تاريخ الورود من الجهة المكلفة </th>

                                                    <th>رقم كتاب الرد على الجهة الطالبة</th>   
                                                    <th>تاريخ تسليم الجهة الطالبة</th>
                                                    <th>ملاحظات</th>
                                                   <!--  <th>المُدخل</th>
                                                    <th>تاريخ آخر تعديل</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                <?php
                                while($row = mysqli_fetch_assoc($result)) {                                                             
                                    if($row["status"] !=='تم الرد'){                                    
                                    echo'<tr class="bg-danger">';
                                    }else{
                                    echo '<tr>';
                                    }
                                  
                                    echo'<td class="'.$td_class.'">';
                                    if($hasPerm_delete == '1'){
                                      echo '<a href="delete.php?id='.$row["id"].'&delete_work_track=true" onclick="'."return confirm('متأكد من الحذف؟');".'"><i style="font-size: 2.5rem" class="zwicon-delete"></i></a>';
                                     }
                                      echo '</td>';
                                    
                                      
                                    echo'<td class="'.$td_class.'">';
                                    if($hasPerm_tracking_table_update==1){
                                    echo '<a href="work_track_edit.php?id='.$row["id"].'"><i style="font-size: 2.5rem" class="zwicon-edit-circle"></i></a>';
                                    }
                                    echo '</td>';
                                  
                                   
                                        

                                    echo          
                                    '<td class="'.$td_class.'">'.$row["status"].'</td>'.  
                                    '<td class="'.$td_class.'">'.$row["study_for"].'</td>'.                     
                                    /* '<td class="'.$td_class.'">'.$row["num"].'</td>'.  */                                  
                                    
                                    '<td class="'.$td_class.'">'.$row["requested_jeha"].'</td>'.
                                    '<td class="'.$td_class.'">'.$row["send_to"].'</td>'.

                                    '<td class="'.$td_class.'">'.$row["ketab_num_wared"].'</td>'.                                    
                                    '<td class="'.$td_class.'">'.$row["handle_from_date"].'</td>'.
                                    
                                    '<td class="'.$td_class.'">'.$row["ketab_num_sader"].'</td>'.
                                    '<td class="'.$td_class.'">'.$row["takleef_date"].'</td>'.

                                    '<td class="'.$td_class.'">'.$row["takleef_name"].'</td>'.
                                    '<td class="'.$td_class.'">'.$row["ketab_num_reply"].'</td>'.
                                    '<td class="'.$td_class.'">'.$row["reply_date"].'</td>'.
                                    
                                    '<td class="'.$td_class.'">'.$row["ketab_num_to"].'</td>'.
                                    '<td class="'.$td_class.'">'.$row["handle_to_date"].'</td>'.

                                    '<td class="'.$td_class.'">'.$row["notes"].'</td>'

                                   /*  '<td class="'.$td_class.'">'.$row["added_by"].'</td>'.
                                    '<td >'.$row["add_date"].'</td>'; */
                                    ;
                                
                                    echo '</tr>';
                                    //$count++;
                                }
                          ?>

                        <?php  
                          }else {
                            echo "<div style='text-align:center;' class='fs'><h4>لا يوجد بيانات</h4></div>";
                          }
                        }
                        ?>
                      </tbody>
                  </table>
               


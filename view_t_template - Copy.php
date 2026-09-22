                                        <table  id="" class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                  <?php  if ($admin == 1 || $admin == 7 || $admin==5){ ?>
                                                  <th>حذف</th>
                                                  <?php }?>
                                                    <th>تفاصيل</th>                                                    
                                                    <th>طباعة</th>
                                                   
                                                  <!--  <th>رقم الديوان</th>
                                                   <th>تاريخ الديوان</th>   -->                                                   
                                                   <th>رقم ال<?php echo $data_type;?></th>
                                                    <th >تاريخ ال<?php echo $data_type;?></th>
                                                    <th>الجهة المرسلة</th>
                                                    <th>الجهة المرسل إليها</th>                                                   
                                                    <th>ملاحظات</th>
                                                    <th>صورة ال<?php echo $data_type;?></th>
                                                                                                       
                                                  <?php if ($admin == 1 || $admin == 7 ){ ?>
                                                    <th>المُدخل</th>
                                                    <th>تاريخ آخر تعديل</th>
                                                  <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                <?php
                                while($row = mysqli_fetch_assoc($result)) {                                    
                                    @$newcipheattach = $row['attach'];
                                    $attach_extension = $row['attach_extension'];
                                    echo'<tr>';

                                    if ($admin == 1 || $admin == 7 || $admin == 5){
                                      /* if ($details_type !=='صادر'){ */
                                      echo'<td><a href="delete.php?id='.$row["id"].'&jeha='.$jeha1.'&details_type='.$details_type.'&data_type='.$data_type.'&delete_tameem=true" onclick="'."return confirm('متأكد من الحذف؟ سيتم حذف بيانات الدراسة');".'"><i style="font-size: 2.5rem" class="zwicon-delete"></i></a></td>';
                                    /* } */
                                  }

                                  
                                    echo
                                    '<td> <a href="t_edit.php?id='.$row["id"].'&jeha='.$row["jeha"].'&data_type='.$row["data_type"].'&details_type='.$row["details_type"].'">
                                    <i style="font-size: 2.5rem" class="zwicon-edit-circle"></i></a></td>'.
                                    '<td> <a target="_blank" href="print-elements.php?id='.$row["id"].'&jeha='.$row["jeha"].'&data_type='.$row["data_type"].'&details_type='.$row["details_type"].'&type=tameem"><i style="font-size: 2.5rem" class="zwicon-printer"></i></a></td>';

                                    echo            
                                   
                                   /*  '<td>' .$row["dewan_num"].'</td>' .
                                    '<td>' .$row["dewan_date"].'</td>' . */
                                    '<td>' .$row["t_num"].
                                    '</td><td>' .$row["t_date"].                               
                                    '</td><td>'.$row["sendfrom"].
                                    '</td><td>'.$row["sendto"].                                   
                                    '</td><td>'.$row["notes"].'</td>';                                 
                                    
                                    @$attach = $row['attach'];                                               
                                    if (!empty($attach)){                                                                                
                                        echo '<td><a href="'.$url_host.'/inc/file_upload/file_decrypt.php?path='.$attach.'">تنزيل</a></td>';                                
                                    }else {echo '<td>لا يوجد مرفقات</td>';} 
                                  
                                  if($admin == 1 || $admin == 7 ){
                                    echo '<td>';if (@strlen($row["added_by"]) >= 20) {
                                    echo substr($row["added_by"], 0, 10). " ... " . substr($row["added_by"], -20);
                                }
                                else {
                                    echo $row["added_by"];
                                }echo '</td>'.
                                  '<td class="noprint">'.$row["add_date"].'</td>';
                                }
                                    echo '</tr>';
                                    //$count++;
                                }
                          ?>

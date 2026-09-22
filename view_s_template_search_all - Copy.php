
                                        <table  id="" class="example display cell-border compact row-border hover order-column stripe text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>تفاصيل</th>                                                    
                                                    <th>طباعة الدراسة</th> 
                                                    <th>الجهة المرسلة</th>
                                                    <th>الجهة المرسل إليها</th>  
                                                    <th>نوع البيانات</th> 
                                                    <?php if (strpos($jeha_profile, 'داخلي') !== false || strpos($jeha_profile, 'هيئة الرقابة والتفتيش') !== false || $jeha_profile == 'الإدارة المركزية للمعلومات'){ ?>
                                                        <th>الرقم الذاتي</th>
                                                        <?php }?>
                                                    <th>رقم الاضبارة</th>   
                                                    <th>تاريخ الاضبارة</th> 
                                                    <th>رقم الديوان</th>  
                                                    <th>تاريخ الديوان</th>  
                                                    <th>رقم الكتاب</th>  
                                                    <th>تاريخ الكتاب</th>                         
                                                    <th>الجهة الطالبة الدراسة</th>
                                                    <th>سبب إجراء الدراسة</th>
                                                    <th >تاريخ إجراء الدراسة</th> 
                                                    <th>منظم الدراسة</th>  
                                                    <th>مصدر الدراسة</th> 
                                                    <th>الرأي والمقترح</th>  
                                                    <th>نتيجة الدراسة</th>  
                                                    <th>السبب</th>                                                  
                                                                                               
                                                    <th>الاسم الكامل</th>
                                                    <th>اسم ونسبة الأم</th>
                                                    <th>مكان الولادة</th>
                                                    <th>تاريخ الولادة</th>
                                                    <th>الجنس</th>
                                                    <th>الجنسية</th>
                                                    <th>الاسم الحركي أو اللقب</th>
                                                    <th>الأوصاف</th>
                                                    <th>الوضع العائلي</th>
                                                    <th>عدد الأولاد</th>
                                                                                                     
                                                    <th>العمل قبل الثورة</th>
                                                    <th>العمل الحالي</th> 
                                                    <th>المؤهل العلمي</th> 
                                                    <th>الوضع المادي</th>                                          
                                                   <th >الناحية الأخلاقية في المجتمع</th>
                                                    <th>الخدمة الالزامية</th>
                                                    <th>الاختصاص</th>
                                                    <th >السكن السابق</th>
                                                    <th >السكن الحالي</th>
                                                    <th>ملك-اجار</th>
                                                    <th>الفصائل التي انضم إليها</th>
                                                    <th>موقفه من الثورة</th>
                                                    <th>الدول التي سافر إليها</th>
                                                    <th>أقاربه مع النظام</th>
                                                    <th>أقاربه مع تنظيم الدولة</th>
                                                    <th>أقاربه مع الفصائل</th>
                                                    <th>أقاربه تم سجنهم لدى إدارة الأمن العام</th>
                                                    <th>الالتزام الديني</th>
                                                    <th>التوجه الفكري</th>
                                                    <th>الأهلية القيادية</th>
                                                    <th>قوة الشخصية</th>
                                                    <th>مدى التأثر بالآخرين</th>
                                                    <th>مدى التأثير بالمجتمع</th>
                                                    <th>طبيعة العلاقة مع الآخرين</th>
                                                    <th>القدرة على المحاورة</th>
                                                    <th>أهم الصفات الشخصية</th>
                                                    <th>هل سجن سايقا؟</th>
                                                    <th>رقم التواصل</th>
                                                    <th style="display:none">تفاصيل اخرى</th>
                                                    <th style="display:none">موجز عن حياته</th>
                                                    <th>صورة</th>
                                                    <th>مرفقات</th>
                                                  <?php if ($admin == 1 || $admin == 7 ){ ?>
                                                    <th>المُدخل</th>
                                                    <th>تاريخ آخر تعديل</th>
                                                  <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                <?php
                                while($row = mysqli_fetch_assoc($result)) {
                                    @$newciphertext = $row['foto1'];
                                    @$newcipheattach = $row['attach'];
                                    @$study_attach_extension = $row['attach_extension'];
                                    $insert_year = $row['e_year'];
                                  /* if($row['edbara_num'] != ''){
                                    echo'<tr>';                                  
                                    echo '<td> <a href="j_edit.php?id='.$row["id"].'&#study_section"><i style="font-size: 2.5rem" class="zwicon-edit-circle"></i></a></td>';
                                  }else{ */
                                    echo'<tr class="bg-danger">';
                                    echo '<td> <a href="s_edit.php?id='.$row["id"].'"><i style="font-size: 2.5rem" class="zwicon-edit-circle"></i></a></td>';
                                 /*  } */
                                    echo '<td> <a target="_blank" href="print-elements.php?id='.$row["id"].'&jeha='.$row["jeha"].'&e_year='.$insert_year.'&type=study"><i style="font-size: 2.5rem" class="zwicon-printer"></i></a></td>';                                    
                                    echo  
                                    '<td>'.$row["sendfrom"].'</td>'.
                                    '<td>'.$row["sendto"].'</td>'.
                                    '<td>'.$row["details_type"].'</td>';
                                    if (strpos($jeha_profile, 'داخلي') !== false || strpos($jeha_profile, 'هيئة الرقابة والتفتيش') !== false || $jeha_profile == 'الإدارة المركزية للمعلومات'){
                                      echo '<td >' .$row["id_num"].'</td>';
                                    }      
                                    echo '<td>'.$row["edbara_num"].'</td>'.
                                    '<td>'.$row["edbara_date"].'</td>'. 
                                    '<td>'.$row["dewan_num"].'</td>'.
                                    '<td >'.$row["dewan_date"].'</td>'.                    
                                    '<td>'.$row["ketab_num"].'</td>'.
                                    '<td >'.$row["ketab_date"].'</td>';
                                      
                                    echo                 
                                    '<td>'.$row["study_request_jeha"].
                                    '</td><td>'.$row["study_reason"]. 
                                    '</td><td>'.$row["study_date"]. 
                                    '</td><td>'.$row["study_organizer"]. 
                                    '</td><td>'.$row["study_masdar"].
                                    '</td><td>'.$row["study_opinion"].
                                    '</td><td>'.$row["study_result"].
                                    '</td><td>'.$row["negative_reason"].                               
                                    '</td><td>'.$row["name"].' '.$row["fname"].' '.$row["lname"].
                                    '</td><td>'.$row["mname"].
                                    '</td><td>'.$row["pbirth"].
                                    '</td><td>'.$row["dbirth"].                                    
                                    '</td><td >'.$row["sex"].
                                    '</td><td >'.$row["national"].
                                    '</td><td >'.$row["nick_name"].
                                    '</td><td >'.$row["awsaf"].
                                    '</td><td >'.$row["family_status"].
                                    '</td><td >'.$row["child_num"].                                   
                                    '</td><td >'.$row["work_before"].
                                    '</td><td >'.$row["work_after"].
                                    '</td><td >'.$row["study"].
                                    '</td><td >'.$row["money_status"].
                                    '</td><td >'.$row["dealing"].
                                    '</td><td >'.$row["service"].
                                    '</td><td >'.$row["special"].
                                    '</td><td >'.$row["pre_address"].
                                    '</td><td >'.$row["address"].
                                    '</td><td >'.$row["address_type"].
                                    '</td><td >'.$row["fasael"].
                                    '</td><td >'.$row["opinion"].
                                    '</td><td >'.$row["travels"].
                                    '</td><td >'.$row["n_relatives"].
                                    '</td><td >'.$row["d_relatives"].
                                    '</td><td >'.$row["f_relatives"].
                                    '</td><td >'.$row["s_relatives"].
                                    '</td><td >'.$row["religon_status"].
                                    '</td><td >'.$row["mind"].
                                    '</td><td >'.$row["lead"].
                                    '</td><td >'.$row["personal"].
                                    '</td><td >'.$row["affected_others"].
                                    '</td><td >'.$row["affected_to"].
                                    '</td><td >'.$row["relation_others"].
                                    '</td><td >'.$row["speak"].
                                    '</td><td >'.$row["important_awsaf"].
                                    '</td><td >'.$row["sawabek"].
                                    '</td><td >'.$row["phone"].
                                    '</td><td style="display:none">'.$row["details"].
                                    '</td><td style="display:none">'.$row["brief"].'</td>';                        
                                    
                                    if(@strlen($newciphertext)>100){
                                    echo '<td>'.'<img width="65" height="80" src="data:image/jpeg;base64,'.base64_encode($newciphertext).'"/></td>';
                                  }else {echo '<td>'.'لا يوجد صورة'.'</td>';}                                  
                                  if(@strlen($newcipheattach)>100){
                                    echo '<td>'.'<a href="data:application/'.$study_attach_extension.';base64,'.base64_encode($newcipheattach).'">تنزيل</a></td>';
                                  }else {echo '<td>'.'لا يوجد مرفقات'.'</td>';}
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

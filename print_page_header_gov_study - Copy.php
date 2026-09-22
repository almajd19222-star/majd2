<?php 
$header1='حكومة الإنقاذ السورية';
$header2='وزارة الداخلية';
$header3='Syrian Salvation Government';
$header4='Ministry of interior';
$title='مكتب الدراسات الأمنية';
$logo_right =$url_host.'/resources/img/profile-pics/logo.png';
$logo_left =$url_host.'/resources/img/profile-pics/logo.png';

$month = date('m');
$day = date('d');
$year = date('Y');

$today = $year . '-' . $month . '-' . $day;
@$insert_year=$_GET['e_year'];
$jeha=$_GET['jeha'];
@$resala_type=$_GET['resala_type'];
?>
<table class="header_style">

<tr>                                     
  <td colspan="6" style="border:none;">
    <div id="container">
      <div id="left" style="padding-top: 20px;">
      
      <?php //echo '<img src="'.$logo_left.'" alt="" width="140" height="98">'; ?>
      <?php
          if(strpos($second_jeha,'ستخبار') !== false){
            echo '<img src="'.$logo_left.'" alt="" width="90" height="100">';
          }else{
            echo '<img src="'.$logo_left.'" alt="" width="140" height="98">';
          }
        ?>

      </div>

      <div id="center">
        

<?php echo $header1.'<br>'.$header2.'<br>'.$header3.'<br>'.$header4; ?>
       
           
        
    
      </div>

      <div id="right" style="padding-top: 20px;">
      <?php echo '<img src="'.$logo_right.'" alt="" width="140" height="98">'; ?>
      </div>
    </div>
</td>                       
</tr>
</table>
<table class="header_style2" >
<tr>
 
  
    <td colspan="6" class="title"><?php echo $title; ?></td>


</tr>
</table>
<?php if(@$_GET['type'] == 'report_brief3' || @$_GET['type'] == 'report_brief4'){ ?> 

<table class="table-bordered"  style="margin-top:5px;width:150px;font-family: 'Sakkal Majalla'; font-size: 10pt;">
  <tr>
    <td class="borders" style="width:180px;padding:0px 1px 2px 1px">
    رقم ال<?php echo $resala_type; ?>:
    </td>
    <td class="borders" style="width:150px;padding:0px 0px 0px 0px">

      <?php
          echo $row_reports['ketab_num'];
      ?>
    </td>
  </tr>
  <tr>
    <td class="borders"  style="width:auto;padding:0px 1px 2px 1px">
      تاريخ ال<?php echo $resala_type; ?>:
     
    </td>
    <td class="borders" style="width:220px;padding:0px 1px 0px 1px">
   
     <?php 
      $y=date('Y', strtotime($row_reports['ketab_date']));
      $m=date('m', strtotime($row_reports['ketab_date']));
      $d=date('d', strtotime($row_reports['ketab_date']));            
      echo Greg2Hijri($d,$m,$y, $string=true);
    ?>
      
    
    </td>
  </tr>
  <tr>
    <td class="borders"  style="width:auto;padding:0px 1px 2px 1px">
    
      الموافق لـ:
    </td>
    <td class="borders" style="width:220px;padding:0px 1px 0px 1px">
  
    <?php             
        echo $row_reports['ketab_date']; 
       ?>
      
    
    </td>
  </tr>
</table>
  

<?php } ?>  
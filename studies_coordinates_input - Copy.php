
<style>
    /* Styles to hide the number input arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Optional styles to prevent resizing of the number input */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<?php
    if($_GET['edit']=='1'){
        if($row['longitude']== ""){
            $zone = "";
            $x = "";
            $y = "";
        }else{
            $sections = explode(" ", $row['longitude']);
            $zone = @$sections[0]; // "27S"
            $x = @$sections[1];    // "XXXXXX"
            $y = @$sections[2];    // "YYYYYYY"
        }
    }
?>
    <select name="zone" id="">
        <?php if($_GET['edit']=='1'){  echo '<option value="'.$zone.'">'.$zone.'</option> ?>'; }else{}?>
        <option value="37S">37S</option>                                        
        <option value="36S">36S</option>
        <option value="38S">38S</option>
    </select>

    <input class="number_input" type="number" name="easting" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" placeholder="XXXXXX" value="<?php if($_GET['edit']=='1'){ echo $x;}else{} ?>">

    <input class="number_input" type="number" name="northing" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="7" placeholder="YYYYYYY" value="<?php if($_GET['edit']=='1'){ echo $y;}else{} ?>">
<select name="zone_attach[]" id="">
    <option value="37S">37S</option>                                        
    <option value="36S">36S</option>
    <option value="38S">38S</option>
</select>
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
<input class="number_input" type="number" name="easting_attach[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6" placeholder="XXXXXX" value="<?php if($_GET['edit']=='1'){ echo $row['longitude'];}else{} ?>">

<input class="number_input" type="number" name="northing_attach[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="7" placeholder="YYYYYYY" value="<?php if($_GET['edit']=='1'){ echo $row['longitude'];}else{} ?>">
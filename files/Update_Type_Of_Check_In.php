<?php
      if(isset($_GET['Visit_Type'])){
            $Visit_Type = $_GET['Visit_Type'];
      }else{
            $Visit_Type = '';
      }

      if($Visit_Type == 'CT SCAN'){
?>
            <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'>
                  <option>Radiology</option>
            </select>
<?php
      }else{
?>
            <select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required'> 
                  <option selected='selected'></option>
                  <option>Laboratory</option> 
                  <option>Procedure</option>
                  <option>Radiology</option> 
                  <option>Surgery</option>
                  <option>Optical</option>
                  <option>Dialysis</option>
                  <option>Others</option>
            </select>
<?php
      }
?>
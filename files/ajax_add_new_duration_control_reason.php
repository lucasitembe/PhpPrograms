<?php 
 include("./includes/connection.php");
$reason = $_POST['reason'];
$query=mysqli_query($conn,"INSERT INTO `tbl_duration_control_reason`(`reason`) VALUES ('$reason')") or die(mysqli_error($conn));
    if($query){
        $sql_result=mysqli_query($conn,"SELECT * FROM tbl_duration_control_reason") or die(mysqli_error($conn));
        
        $html = '<select name="reason" id="reason" class="form-control">
                        <option selected disabled>Select reason</option>';
        while($rows=mysqli_fetch_assoc($sql_result)){
            $dbreason=$rows['reason'];
            if($dbreason == $reason){$selected='selected';}else{$selected='';}
            $html .= '<option value="'.$rows['reason_id'].'" '.$selected.'>'.$dbreason.'</option>';
        }
        $html .= '</select>';
        echo $html;
    }
    

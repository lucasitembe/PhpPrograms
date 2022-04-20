<?php 
 include("./includes/connection.php");
$status = $_POST['status'];
$query=mysqli_query($conn,"INSERT INTO `tbl_surgery_status`(`status_description`) VALUES ('$status')") or die(mysqli_error($conn));
    if($query){
        $sql_result=mysqli_query($conn,"SELECT status_description FROM tbl_surgery_status") or die(mysqli_error($conn));
        
        $html = '<select name="" id="statuses" style="width: 180px;height:30px;">
                    <option value="" disabled>Select Status</option>';
        while($rows=mysqli_fetch_assoc($sql_result)){
            $status_description=$rows['status_description'];
            if($status_description == $status){$selected='selected';}else{$selected='';}
            $html .= '<option value="'.$status_description.'" '.$selected.'>'.$status_description.'</option>';
        }
        $html .= '</select>';
        echo $html;
    }
    

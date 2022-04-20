<?php
 include("./includes/connection.php");
 session_start();
 if(isset($_POST['depertment_id'])){
    $depertment_id = $_POST['depertment_id'];
}
$Employee_ID=$_SESSION['userinfo']['Employee_ID'];
$html = '<div id="options_list">
            <select  name="Ward_ID" style="width: 100%; height:30%"  id="Ward_ID" onclick="clearFocus(this)" required="required">
            ';
            $get_Ward=mysqli_query($conn,"SELECT ward_id FROM tbl_department_ward WHERE department_id='$depertment_id' AND ward_id IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))");
            if(mysqli_num_rows($get_Ward)>1)$html .= '<option value="">Select Ward</option>';else if(mysqli_num_rows($get_Ward)==0)$html .= '<option value="">No Ward Found</option>';
            while($ward=mysqli_fetch_array($get_Ward)){
                $wardID = $ward['ward_id'];
                
                $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$wardID'");
                while($Ward_Row=mysqli_fetch_array($Select_Ward)){
                    $ward_id=$Ward_Row['Hospital_Ward_ID'];
                    $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                    $html .= '<option value="'.$ward_id.'">'.$Hospital_Ward_Name.'</option>';
                }
            }
$html .= '</select>
        </div>';

echo $html;
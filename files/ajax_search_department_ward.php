<?php

 include("./includes/connection.php");
 
if(isset($_POST['search_department_ward_value'])){
   $search_department_ward_value=$_POST['search_department_ward_value'];
   $search_department_name_value = str_replace(" ", "%", $search_department_ward_value);
}else{
    $search_department_name_value="";
}


$html = '<div id="merged_wards_depertments_list">
        <table class="table">';
            
            $get_wards_departments = mysqli_query($conn,"SELECT DISTINCT d.department_id, f.finance_department_name FROM tbl_department_ward AS d, tbl_finance_department AS f WHERE f.enabled_disabled='enabled' AND f.finance_department_id = d.department_id AND f.finance_department_name LIKE '%$search_department_name_value%'");
            
            if(mysqli_num_rows($get_wards_departments)){
                while ($row1=mysqli_fetch_array($get_wards_departments)) {
                    $department_id = $row1['department_id'];

                    // echo $department_id; exit;
           
            $html.=' <tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
                    <th colspan="2">'. $row1['finance_department_name'] .'</th>
                </tr>';
             $get_department = mysqli_query($conn,"SELECT department_ward_id,ward_id FROM tbl_department_ward WHERE department_id='$department_id'  ORDER BY department_id ASC");
             $i=1;
             while ($row2=mysqli_fetch_array($get_department)) { 
                 $ward_id = $row2["ward_id"];
                 $department_ward_id=$row2["department_ward_id"];
                 $get_ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'");
                 $row3 = mysqli_fetch_array($get_ward);
                 
                 $ward_name = $row3['Hospital_Ward_Name'];
        
            $html.='<tr>
                    <td width="1%" style="border:1px solid #328CAF!important;">'. $i .'</td>
                    <td width="99%" style="border:1px solid #328CAF!important;">
                        <label for="merged_Depertment_ID_'.$department_ward_id .'"  style="font-weight:normal">
                            <input type="checkbox" id="merged_Depertment_ID_'. $department_ward_id .'" class="merged_Depertment_ID" value="'. $department_ward_id.'"> '. $ward_name .'
                        </label>
                    </td>
                </tr>';
            $i++;}}}else{
                $html.='<tr>
                    <td width="10%"></td>
                    <td width="90%">
                        <label>
                            <b style="color: red;">NO, FREE ITEM FOUND!</b>
                        </label>
                    </td>
                </tr>';
            }
     $html.=' </table>
                </div>';

echo $html;
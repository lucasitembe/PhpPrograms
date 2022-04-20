<?php

 include("./includes/connection.php");
 
if(isset($_POST['search_department_ward_value'])){
   $search_department_ward_value=$_POST['search_department_ward_value'];
   $search_department_name_value = str_replace(" ", "%", $search_department_ward_value);
}else{
    $search_department_name_value="";
}

// echo $search_department_name_value;
// exit;

$html = '<div id="merged_wards_depertments_list">
        <table class="table">';
         
                            $get_wards_departments = mysqli_query($conn,"SELECT DISTINCT d.sub_department_id, f.Sub_Department_Name FROM tbl_sub_department_ward AS d, tbl_sub_department AS f WHERE f.Sub_Department_ID = d.sub_department_id AND f.Sub_Department_Name LIKE '%$search_department_name_value%'");
                           
                            if(mysqli_num_rows($get_wards_departments)){
                                while ($row1=mysqli_fetch_array($get_wards_departments)) {
                                    $department_id = $row1['sub_department_id'];
                            $html.='<tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
                                    <th colspan="">'. $row1['Sub_Department_Name'] .'</th>
                                </tr>';
                                // echo $html; exit;
                             $get_department = mysqli_query($conn,"SELECT sub_department_ward_id,ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$department_id'");
                             $i=1;
                             while ($row2=mysqli_fetch_array($get_department)) { 
                                 $ward_id = $row2["ward_id"];
                                 $department_ward_id=$row2["sub_department_ward_id"];
                                 $get_ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'");
                                 $row3 = mysqli_fetch_array($get_ward);
                                 
                                 $ward_name = $row3['Hospital_Ward_Name'];
                            
                            $html.=' <tr>
                                    <td width="100%" style="border:1px solid #328CAF!important;">
                                        <label for="merged_Depertment_ID'.$department_ward_id .'"  style="font-weight:normal">
                                            <input type="checkbox" id="merged_Depertment_ID_'. $department_ward_id.'" class="merged_Depertment_ID" value="'. $department_ward_id .'">'. $ward_name .'
                                        </label>
                                    </td>
                                </tr>';
                            $i++;
                        }
                    }}else{
                                $html.='   <tr>
                                    <td width="10%"></td>
                                    <td width="90%">
                                        <label>
                                            <b style="color: red;">NO, MERGED ITEM FOUND!</b>
                                        </label>
                                    </td>
                                </tr>';
                             } 
     $html.=' </table>
                </div>';

echo $html;
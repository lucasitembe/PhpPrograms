<?php
    include("./includes/connection.php");
    $IP_Address = $_SERVER['REMOTE_ADDR']; 
    // echo $IP_Address;
    // exit();

    if(isset($_GET['getSubDepartment'])){
        $Department_ID = $_GET['Department_ID']; 
        // exit();


	// $Editing_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
   $Select_SubDepartment = "SELECT * from tbl_sub_department
                            where department_id = '$Department_ID' and 
                            Sub_Department_Status = 'active'";
    $result = mysqli_query($conn,$Select_SubDepartment);
    ?> 
    <?php
    $count_sn=1;
    ?>
    <table class="table">
    <?php
    if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_array($result)){
        $Sub_Department_ID=$row['Sub_Department_ID'];
        $Sub_Department_Name=$row['Sub_Department_Name'];
       
        echo  "<tr>
                <td>
                    <label style='font-weight:normal'>
                        <input type='checkbox' class='Sub_Department_ID' name='Sub_Department_ID' value='$Sub_Department_ID'> $Sub_Department_Name
                    </label>
                </td>
            </tr>";
    
         $count_sn++;
    }}else{
        echo "<tr>
                    <td>
                        <label style='color:red;'>
                            SORRY, NO SUB-DEPARTIMENT FOUND!
                        </label>
                    </td>  
                </tr>";
    }
?> 
   </table>


<?php }
 if(isset($_GET['Search_Employee'])){
      
    if(isset($_GET['Employee_Name'])){
		$Employee_Name = str_replace(" ", "%", $_GET['Employee_Name']);
	}else{
		$Employee_Name = '';
    }

    if(isset($_GET['Designation'])){
        $Designation = $_GET['Designation'];
    }else{
        $Designation = '';
    }
    
    if($Designation != null && $Designation != ''){
        $filter .= " emp.Employee_Type = '$Designation' and ";
    }else{
        $filter .= " ";
    }

    if($Employee_Name != null && $Employee_Name != ''){
        $filter .= " emp.Employee_Name like '%$Employee_Name%' and ";
    }


    $select = mysqli_query($conn,"SELECT * from tbl_employee emp, tbl_department dep where
    emp.department_id = dep.department_id and
    $filter
    emp.Employee_Name <> 'crdb' AND emp.Account_Status = 'active' order by Employee_Name limit 500") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    echo '<table class="table">';
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Employee_ID=$row['Employee_ID'];
            $Employee_Name=ucwords(strtolower($row['Employee_Name']));
            echo "<tr>
                    <td>
                        <label style='font-weight:normal'>
                            <input type='checkbox' class='Employee_ID' name='Employee_ID' value='$Employee_ID'> $Employee_Name
                        </label>
                    </td>
                    
            </tr>";
        }
    }else{
        echo "<tr>
                    <td>
                        <label style='color:red;'>
                            SORRY, EMPLOYEE FOUND!
                        </label>
                    </td>  
                </tr>";
    }
    echo '</table>';
 }

 if(isset($_POST['merge_selecteted_items'])){
    //  echo $_POST['merge_selecteted_items'];
    $Editing_Employee_ID = $_POST['Editing_Employee_ID'];

    if(isset($_POST['selected_subdepartments'])&&isset($_POST['selected_employees'])){
        $selected_subdepartments=$_POST['selected_subdepartments'];$selected_employees=$_POST['selected_employees'];


        foreach ($selected_subdepartments as $subdepartment){
            foreach ($selected_employees as $employee){
                #kuondoa data duplication
                $check_if_exist = mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Sub_Department_ID='$subdepartment' AND Employee_ID='$employee'");
                if (mysqli_num_rows($check_if_exist)==0) {
                    #kama haipo add
                    mysqli_query($conn,"INSERT INTO `tbl_employee_sub_department`(`Employee_ID`, `Sub_Department_ID`) VALUES ('$employee','$subdepartment')");
                }

                $record_data = mysqli_query($conn, "INSERT INTO tbl_user_editing (Editing_Employee, Edited_Employee, Activity_Done, Sub_Department_ID, Editing_Time, IP_Address) VALUES('$Editing_Employee_ID', '$employee', 'Inserting Sub Department', '$subdepartment', NOW(), '$IP_Address')") or die(mysqli_error($conn));
            }
        }
    }
 }

 if(isset($_POST['Employee_ID_Sub_Department_ID'])){
    $Employee_ID_Sub_Department_ID=$_POST['Employee_ID_Sub_Department_ID'];
	// $Editing_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
    $Editing_Employee_ID = $_POST['Editing_Employee_ID'];

     foreach ($Employee_ID_Sub_Department_ID as $item_id){

        $ids=explode("-",$item_id);
        $Employee_ID=$ids[0];$Sub_Department_ID=$ids[1];

         $sql_delete = mysqli_query($conn,"DELETE FROM `tbl_employee_sub_department` WHERE `tbl_employee_sub_department`.`Employee_ID` = '$Employee_ID' AND `tbl_employee_sub_department`.`Sub_Department_ID` = '$Sub_Department_ID'");
        
         if($sql_delete){
             $record_data = mysqli_query($conn, "INSERT INTO tbl_user_editing (Editing_Employee, Edited_Employee, Activity_Done, Sub_Department_ID, Editing_Time, IP_Address) VALUES('$Editing_Employee_ID', '$Employee_ID', 'Removing Sub Department', '$Sub_Department_ID', NOW(), '$IP_Address')") or die(mysqli_error($conn));

             echo "Data was deleted successfully.";

         }else{
             echo "There was an error, please try to delete again.";
         }
     }   
 }

 if(isset($_POST['search_subdepartment_employee_value'])){
    $search_subdepartment_employee_value=$_POST['search_subdepartment_employee_value'];
    if($search_subdepartment_employee_value!=""){  
        $search_subdepartment_employee_value = str_replace(" ", "%", $search_subdepartment_employee_value);
    } else{
        $search_subdepartment_employee_value="";
    }


    $html='<table class="table table-bordered">';
    
        $get_wards_departments = mysqli_query($conn,"SELECT DISTINCT d.Sub_Department_ID, d.Employee_ID, e.Employee_Name FROM tbl_employee_sub_department AS d, tbl_employee AS e WHERE e.Employee_ID = d.Employee_ID AND e.Employee_Name LIKE '%$search_subdepartment_employee_value%' GROUP BY Employee_Name ORDER BY Employee_Name ASC LIMIT 10");
        if(mysqli_num_rows($get_wards_departments) > 0){ 
        while ($row1=mysqli_fetch_array($get_wards_departments)) {
            $Sub_Department_ID = $row1['Sub_Department_ID'];
            $Employee_ID = $row1['Employee_ID'];
    
        $html .=' <tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
            <th colspan="3">'. $row1['Employee_Name'] .'</th>
        </tr>';
    
        $Select_Assigned_Sub_Department = mysqli_query($conn,"SELECT * from tbl_employee emp, tbl_department dept, tbl_sub_department sdept, tbl_employee_sub_department ed
                                                    where emp.employee_id = ed.employee_id and
                                                        sdept.department_id = dept.department_id and
                                                            sdept.sub_department_id = ed.sub_department_id and
                                                                emp.Employee_ID = '$Employee_ID'");
            $count_sn=1;
            $html .= "<tr><td class='boda'> </td><td class='boda'> <b>DEPARTMENT</b> </td><td class='boda'> <b>SUB DEPARTMENT</b></td>";
            while($row = mysqli_fetch_array($Select_Assigned_Sub_Department)){
                $Employee_ID_Sub_Department_ID=$Employee_ID."-".$row['Sub_Department_ID'];
                $html .= '<tr><td class="boda"> <input type="checkbox" class="Employee_ID_Sub_Department_ID" value="'.$Employee_ID_Sub_Department_ID.'"> </td>
                <td class="boda">'.ucfirst($row['Department_Name']).'</td>';
                $html .= '<td class="boda">'.strtoupper($row['Sub_Department_Name']).'</td></tr>';
                    $count_sn++;
                            }
            }
        }else{
        $html .=' <tr>
            <td width="10%"></td>
            <td width="90%">
                <label>
                    <b style="color: red;">NO, DATA FOUND!</b>
                </label>
            </td>
        </tr>';
    } 
    $html .=' </table>';

    echo $html;

 }
?>



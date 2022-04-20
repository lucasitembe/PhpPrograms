<?php
	include("./includes/connection.php");

	if(isset($_GET['Employee_Name'])){
		$Employee_Name = str_replace(" ", "%", $_GET['Employee_Name']);
	}else{
		$Employee_Name = '';
	}
        if(isset($_GET['Account_Status'])){
		$Account_Status = $_GET['Account_Status'];
	}else{
		$Account_Status = '';
	}
	
	if(isset($_GET['Department_ID'])){
        $Department_ID = $_GET['Department_ID'];
    }else{
        $Department_ID = '0';
    }
    if(isset($_GET['Designation'])){
        $Designation = $_GET['Designation'];
    }else{
        $Designation = 'All Designation';
    }
    if(isset($_GET['Employee_PFNO'])){
		$Employee_PFNO = $_GET['Employee_PFNO'];
	}else{
		$Employee_PFNO = '';
	}
	$filter = "";
	if($Department_ID != '0'){
        $filter .= " emp.Department_ID = '$Department_ID' and ";
    }
    if($Designation != 'All Designation'){
            $filter .= " emp.Employee_Type = '$Designation' and ";
    }else{
        $filter .= " ";
    }
    if($Employee_PFNO != ''){
    		$filter .= " emp.Employee_Number = '$Employee_PFNO' and ";
    }
	if($Employee_Name != null && $Employee_Name != ''){
		$filter .= " emp.Employee_Name like '%$Employee_Name%' and ";
	}
        if($Account_Status != null && $Account_Status != '' ){
		$filter .= " emp.Account_Status = '$Account_Status' and ";
	}
    $is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
        $is_hr = "&HRWork=true";   
    }
$htm='';

    $temp = 0;
    $htm .= '<table><tr><td >LIST OF EMPLOYEES</td></tr>
    <tr>
        <td ><b>SN</b></td>
        <td><b>EMPLOYEE NAME</b></td>        
        <td><b>EMPLOYEE PFNO</b></td>
        <td><b>EMPLOYEE LISENCE</b></td>
        <td>PHONE NUMBER</td>
        <td ><b>DESIGNATION</b></td>
        <td ><b>EMPLOYEE TITLE</b></td> 
        <td ><b>JOB CODE</b></td>
        <td ><b>DEPARTMENT</b></td>
        <td ><b>SIGNTURE</b></td>
    </tr>';

    
    $select = mysqli_query($conn,"SELECT * from tbl_employee emp, tbl_department dep where      emp.department_id = dep.department_id and $filter    emp.Employee_Name <> 'crdb' order by Employee_Name") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($row = mysqli_fetch_array($select)) {
           $Employee_Number=  $row['Employee_Number'];
            $Employee_Name = $row['Employee_Name'];
            $Employee_Type= $row['Employee_Type'];
            $Employee_Title= $row['Employee_Title'];
            $Employee_Job_Code = $row['Employee_Job_Code'];
            $Department_Name =$row['Department_Name'];
            $employee_signature = $row['employee_signature'];
            $Phone_Number = $row['Phone_Number'];
            $doctor_license = $row['doctor_license'];
            if(($employee_signature) != NULL && ($employee_signature) !=''){
                $signature='Taken';
            }else{
                $signature='Not taken';
            }
            $htm.= "<tr><td>".++$temp."</td><td>".strtoupper($Employee_Name)."</td>";
            $htm.= "<td>".strtoupper($Employee_Number)."</td><td>$doctor_license</td><td>$Phone_Number</td>";
            $htm.= "<td>".strtoupper($Employee_Type)."</td>";
            $htm.= "<td>".strtoupper($Employee_Title)."</td>";
            $htm.= "<td>".strtoupper($Employee_Job_Code)."</td>";
            $htm.= "<td>".strtoupper($Department_Name)."</td>";
            $htm.= "<td>$signature</td>";
           
        }
    }

$htm.= "</table>";

    header("Content-Type:application/xls");
    header("content-Disposition: attachement; filename= allEmp.xls");
    echo $htm;
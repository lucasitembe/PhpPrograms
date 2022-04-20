

<?php
@session_start(); 
include("./includes/connection.php");
if(isset($_POST['fromDate'])){
    @$fromDate =$_POST['fromDate'];
    @$toDate=$_POST['toDate'];
    @$patient_number=$_POST['patient_number'];
    @$Sponsor_ID = $_POST['Sponsor_ID'];
    @$medicine_name = $_POST['medicine_name'];
    $Employee_ID = $_POST['Employee_ID'];
    if($patient_number != ""){
        $filter = "AND Registration_ID='$patient_number'";
	}else{
        $filter=""; 
    }
    if($Sponsor_ID != "all"){
        $filter2 = " Sponsor_ID='$Sponsor_ID' AND";
	}else{
        $filter2="";
    }

    if($Employee_ID != "all"){
         $Employee_Type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Type  FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Type'];
         if($Employee_Type=='Doctor'){
            $filter2 = " doctor_id='$Employee_ID' AND";
         }else if($Employee_Type=='Nurse'){
            $filter2 = "  nurse_id='$Employee_ID' AND";
         }
    }else{
        $filter2="";
    }
    if($medicine_name != ""){
        $filter3 = "AND it.Product_Name like '%$medicine_name%'";
	}else{
        $filter3="";
    }
    $sn = 1;
		echo "<div style='background-color:white;'>";
			echo "<table width='100%'>";
			echo "<thead>";
            echo "<tr>
                    <th style='width:3%' >NO.</th>
                    <th style='width:6%' >Patient Registration</th>
                    <th style='width:15%;'>Patient Name</th>
                    <th style='width:20%;'>Sponsor Name</th>
                    <th style='width:20%;'>Ward Name</th>
                    <th style='width:20%;'>Doctor On Call</th>
                    <th style='width:20%;'>Nurse On Duty</th>
                    <th style='width:20%;'>Financial Dept. </th>
                    <th style='width:10%;'>Transaction Date Time</th>
                </tr>";
			echo "</thead><tbody>";
		
		$querySubcategory = mysqli_query($conn,"SELECT * FROM tbl_oncall_claims WHERE $filter2 date_time BETWEEN '$fromDate' AND '$toDate' ORDER BY Claim_ID DESC") or die(mysqli_error($conn));
        if(mysqli_num_rows($querySubcategory)>0){
            while($row1 = mysqli_fetch_assoc($querySubcategory)) {
                $Registration_ID = $row1['Registration_ID'];
                $Sponsor_ID_patient = $row1['sponsor_id'];
                $dept_id = $row1['dept_id'];
                $ward_id = $row1['ward_id'];
                $Transaction_Date_And_Time = $row1['date_time'];
                $doctor_id = $row1['doctor_id'];
                $nurse_id = $row1['nurse_id'];

                $Patient_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['Patient_Name'];
                
                $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM  tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID_patient'"))['Guarantor_Name'];
                
                $Department_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT finance_department_name FROM  tbl_finance_department WHERE finance_department_id='$dept_id'"))['finance_department_name'];
                $Ward_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'"))['Hospital_Ward_Name'];
                $doctor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$doctor_id'"))['Employee_Name'];
                $nurse_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM  tbl_employee WHERE Employee_ID='$nurse_id'"))['Employee_Name'];
                
                echo"<tr><td> ".$sn."</td><td> ".$Registration_ID."</td><td>".$Patient_name."</td><td>".$Guarantor_Name."</td><td>".$Ward_Name."</td><td>".$doctor_Name."</td><td>".$nurse_Name."</td><td>".$Department_Name."</td><td>".$Transaction_Date_And_Time."</td>";
            
                echo "</tr>";
                $sn++;
                }
        }else{
            echo "<tr><td></td></tr>";
        }
	
		echo "</tbody></table>";
        echo "</div>";
    }
        if(isset($_POST['filterdoctorcalls'])){
           
            $date_From = mysqli_real_escape_string($conn, $_POST['date_From']);
            $date_To = mysqli_real_escape_string($conn, $_POST['date_To']);
            $Employee_ID = $_POST['employeeID'];
            $Sponsor= mysqli_real_escape_string($conn,$_POST['Sponsor']);
            if (!empty($Sponsor) && $Sponsor != 'All') {
              
                $filters .="  AND pr.Sponsor_ID=$Sponsor";
                
            }
            $selectpatient = mysqli_query($conn, "SELECT dept_id,oc.ward_id,doctor_id,nurse_id, date_time, oc.sponsor_id, oc.Registration_ID FROM  tbl_oncall_claims oc, tbl_patient_registration pr WHERE oc.Registration_ID=pr.Registration_ID $filters  AND date_time BETWEEN '$date_From' AND '$date_To' AND  doctor_id='$Employee_ID'") or die(mysqli_error($conn));

            $sn = 1;
		echo "<div style='background-color:white;'>";
			echo "<table class='table table-hover'>";
			echo "<thead>";
            echo "<tr>
                    <th style='width:3%' >SN.</th>
                    <th style='width:6%' >Reg #:</th>
                    <th style='width:20%;'>Patient Name</th>
                    <th style='width:15%;'>Sponsor Name</th>
                    <th style='width:15%;'>Ward Name</th>
                    <th style='width:12%;'>Doctor On Call</th>
                    <th style='width:11%;'>Nurse On Duty</th>
                    <th style='width:7%;'>Financial Dept. </th>
                    <th style='width:10%;'>Claim Time</th>
                </tr>";
            echo "</thead><tbody>";
            if(mysqli_num_rows($selectpatient)>0){
                while($row1 = mysqli_fetch_assoc($selectpatient)) {
                    $Registration_ID = $row1['Registration_ID'];
                    $Sponsor_ID_patient = $row1['sponsor_id'];
                    $dept_id = $row1['dept_id'];
                    $ward_id = $row1['ward_id'];
                    $Transaction_Date_And_Time = $row1['date_time'];
                    $doctor_id = $row1['doctor_id'];
                    $nurse_id = $row1['nurse_id'];
    
                    $Patient_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'"))['Patient_Name'];
                    
                    $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM  tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID_patient'"))['Guarantor_Name'];
                    
                    $Department_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT finance_department_name FROM  tbl_finance_department WHERE finance_department_id='$dept_id'"))['finance_department_name'];
                    $Ward_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'"))['Hospital_Ward_Name'];
                    $doctor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$doctor_id'"))['Employee_Name'];
                    $nurse_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM  tbl_employee WHERE Employee_ID='$nurse_id'"))['Employee_Name'];
                    
                    echo"<tr><td> ".$sn."</td><td> ".$Registration_ID."</td><td>".$Patient_name."</td><td>".$Guarantor_Name."</td><td>".$Ward_Name."</td><td>".$doctor_Name."</td><td>".$nurse_Name."</td><td>".$Department_Name."</td><td>".$Transaction_Date_And_Time."</td>";
                
                    echo "</tr>";
                    $sn++;
                    }
            }else{
                echo "<tr><td colspan='9' style='color:red;'>No Result Found</td></tr>";
            }
        
            echo "</tbody></table>";
            echo "</div>";
            
            
        }
	
        if(isset($_POST['RoundReport'])){
            $filter ='';
            $Sponsor= mysqli_real_escape_string($conn,$_POST['Sponsor']);
            $employee_ID = $_SESSION['userinfo']['Employee_ID'];
            $date_From = mysqli_real_escape_string($conn, $_POST['date_From']);
            $date_To = mysqli_real_escape_string($conn, $_POST['date_To']);

            $filter=" AND   wr.Ward_Round_Date_And_Time BETWEEN '$date_From' AND '$date_To' AND wr.Employee_ID='$employee_ID'  AND wr.Process_Status='served'";

            if (!empty($Sponsor) && $Sponsor != 'All') {
                $filter .="  AND pr.Sponsor_ID=$Sponsor";
                $filters .="  AND pr.Sponsor_ID=$Sponsor";
                
            }
            echo '<center><table width =100% border="1"  class="table" id="doctorsperformancetbl">';
            echo "<thead style='background-color:#03c2fc'><tr>
                    <th width=3% style='text-align:left'>SN</th>
                    <th style='text-align:left'>DOCTOR'S NAME</th>
                    <th style='text-align: left;' width=18%>NUMBER OF PATIENTS</th>
                    <th>Total Call Claims</th>
                 </tr></thead><tbody>";
            $select_doctor_query = "SELECT DISTINCT(emp.Employee_ID),emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp INNER JOIN tbl_ward_round wr ON wr.Employee_ID=emp.Employee_ID  WHERE Employee_Type='Doctor' AND emp.Employee_ID='$employee_ID' ORDER BY Employee_Name ASC";
    
    
            $select_doctor_result = mysqli_query($conn,$select_doctor_query) or die(mysqli_error($conn));
    
            $empSN = 0;
            while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {
                $employeeID = $select_doctor_row['Employee_ID'];
                $employeeName = $select_doctor_row['Employee_Name'];
            
                $result_patient_no = mysqli_query($conn,"SELECT COUNT(wr.Registration_ID) AS total_patient  FROM tbl_ward_round wr , tbl_patient_registration pr WHERE wr.Registration_ID=pr.Registration_ID  $filter ORDER BY wr.consultation_ID") or die(mysqli_error($conn));
    
                if (mysqli_num_rows($result_patient_no) > 0) {
    
                    $patient_no_number = mysqli_fetch_assoc($result_patient_no)['total_patient'];

                    $totol_calls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(oc.Registration_ID) AS Total_claims FROM  tbl_oncall_claims oc, tbl_patient_registration pr WHERE oc.Registration_ID=pr.Registration_ID $filters  AND date_time BETWEEN '$date_From' AND '$date_To' AND  doctor_id='$employeeID'"))['Total_claims'];
                    $empSN ++;
                    echo "<tr><td>" . ($empSN) . "</td>";
                    echo "<td style='text-align:left'><a href='doctorsindivperfinpdetails.php?Employee_ID=$employeeID&Date_From=$date_From&Date_To=$date_To&Sponsor_ID=$Sponsor'>" . $employeeName . "</b></td>";
                    echo "<td style='text-align:center'><a href='doctorsindivperfinpdetails.php?Employee_ID=$employeeID&Date_From=$date_From&Date_To=$date_To&Sponsor_ID=$Sponsor'>" . number_format($patient_no_number) . "</b></td><td class='rowlist' onclick='open_total_calls_claims($employeeID)'>" . number_format($totol_calls) . "</td></tr>";
                }
            }
            echo "</table>";
           
        }
?>

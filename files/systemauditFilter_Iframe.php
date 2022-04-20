<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
				//get post data
            $branchID=mysqli_real_escape_string($conn,$_GET['branchID']);
            $employeeID=mysqli_real_escape_string($conn,$_GET['employeeID']);
            $Login_From=mysqli_real_escape_string($conn,$_GET['Login_From']);
            $Login_To=mysqli_real_escape_string($conn,$_GET['Login_To']);
            $Logout_From=mysqli_real_escape_string($conn,$_GET['Logout_From']);
            $Logout_To=mysqli_real_escape_string($conn,$_GET['Logout_To']);
            $Auth_From=mysqli_real_escape_string($conn,$_GET['Auth_From']);
            $Auth_To=mysqli_real_escape_string($conn,$_GET['Auth_To']);

            if($branchID == 0){//branch is not selected
                if($employeeID == 0){//no employee selected
                    if(($Login_From != '' || $Login_From != null) && ($Login_To != '' || $Login_To != null)){//login range selected
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        //run the query if login range is selected
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }elseif(($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null) ){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To' ORDER BY aud.Logout_Date_And_Time DESC");
                    }elseif(($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null)){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Authentication_Date_And_Time DESC");
                    }elseif((($Login_From != '' || $Login_From != null) && ($Login_To !='' || $Login_To !=null)) && (($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null)) && (($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null))){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                 <td style='text-align:left'><b>ACTIVITY</b></td>
                                  <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
                                        AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To'
                                        AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }else{
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                 <td style='text-align:left'><b>ACTIVITY</b></td>
                                  <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID ORDER BY aud.Login_Date_And_Time DESC");
                    }
                }else{//employee selected
                    if(($Login_From != '' || $Login_From != null) && ($Login_To != '' || $Login_To != null)){//login range selected
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        //run the query if login range is selected
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }elseif(($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null)){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To' ORDER BY aud.Logout_Date_And_Time DESC");
                    }elseif(($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null)){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Authentication_Date_And_Time DESC");
                    }elseif((($Login_From != '' || $Login_From != null) && ($Login_To !='' || $Login_To !=null)) && (($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null)) && (($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null))){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                 <td style='text-align:left'><b>ACTIVITY</b></td>
                                  <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
                                        AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To'
                                        AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }else{
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>ACTIVITY</b></td>
                                  <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Branch_ID=br.Branch_ID ORDER BY aud.Login_Date_And_Time DESC");
                    }
                }
            }else{//branch is selected
                if($employeeID == 0){//no employee selected
                    if(($Login_From != ''|| $Login_From != null) && ($Login_To != '' || $Login_To != null)){//login range selected
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        //run the query if login range is selected
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Branch_ID='$branchID'
									    AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }elseif(($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null) ){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Branch_ID='$branchID'
									    AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To' ORDER BY aud.Logout_Date_And_Time DESC");
                    }elseif(($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null)){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Branch_ID='$branchID'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Authentication_Date_And_Time DESC");
                    }elseif((($Login_From != '' || $Login_From != null) && ($Login_To !='' || $Login_To !=null)) && (($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null)) && (($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null))){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                 <td style='text-align:left'><b>ACTIVITY</b></td>
                                  <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Branch_ID='$branchID'
                                        AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To'
                                        AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }else{
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>ACTIVITY</b></td>
                                <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID='$branchID'
									    AND aud.Branch_ID=br.Branch_ID ORDER BY aud.Login_Date_And_Time DESC");
                    }
                }else{//employee selected
                    if(($Login_From != '' || $Login_From != null) && ($Login_To != '' || $Login_To != null)){//login range selected
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        //run the query if login range is selected
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Branch_ID='$branchID'
									    AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }elseif(($Logout_From != '' || $Logout_From != null) && $Logout_To != '' || $Logout_To != null ){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Branch_ID='$branchID'
									    AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To' ORDER BY aud.Logout_Date_And_Time DESC");
                    }elseif(($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null)){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Branch_ID='$branchID'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Authentication_Date_And_Time DESC");
                    }elseif((($Login_From != '' || $Login_From != null) && ($Login_To !='' || $Login_To !=null)) && (($Logout_From != '' || $Logout_From != null) && ($Logout_To != '' || $Logout_To != null)) && (($Auth_From != '' || $Auth_From != null) && ($Auth_To != '' || $Auth_To != null))){
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>ACTIVITY</b></td>
                                <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Branch_ID=br.Branch_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Branch_ID='$branchID'
                                        AND aud.Login_Date_And_Time BETWEEN '$Login_From' AND '$Login_To'
                                        AND aud.Logout_Date_And_Time BETWEEN '$Logout_From' AND '$Logout_To'
									    AND aud.Authentication_Date_And_Time BETWEEN '$Auth_From' AND '$Auth_To' ORDER BY aud.Login_Date_And_Time DESC");
                    }else{
                        echo "<tr id='thead'>
                                <td style='text-align:center;width: 3%'><b>SN</b></td>
                                <td style='text-align:left'><b>EMPLOYEE NAME</b></td>
                                <td style='text-align:left'><b>LOGIN DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOGOUT DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION DATE AND TIME</b></td>
                                <td style='text-align:left'><b>AUTHENTICATION LOCATION</b></td>
                                <td style='text-align:left'><b>ACTIVITY</b></td>
                                <td style='text-align:left'><b>ACTIVITY DATE AND TIME</b></td>
                                <td style='text-align:left'><b>LOCATION</b></td>
                                <td style='text-align:left'><b>IP ADDRESS</b></td>
                                <td style='text-align:left'><b>PC NAME</b></td>
                                <td style='text-align:left'><b>BRANCH</b></td>
                             </tr>";
                        echo "<tr>
                                <td colspan=4></td></tr>";
                        $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
									    WHERE aud.Employee_ID=emp.Employee_ID
									    AND aud.Employee_ID='$employeeID'
									    AND aud.Branch_ID='$branchID'
									    AND aud.Branch_ID=br.Branch_ID ORDER BY aud.Login_Date_And_Time DESC");
                    }
                }
            }


                        $sn=1;
                        while($audit_record_row=mysqli_fetch_array($audit_record)){
                            //return data

                            $employeeName=$audit_record_row['Employee_Name'];
                            $Description=$audit_record_row['Description'];
                            $Login_Date_And_Time=$audit_record_row['Login_Date_And_Time'];
                            $Logout_Date_And_Time=$audit_record_row['Logout_Date_And_Time'];
                            $Authentication=$audit_record_row['Authentication'];
                            $Authentication_Date_And_Time=$audit_record_row['Authentication_Date_And_Time'];
                            $Activity=$audit_record_row['Activity'];
                            $Activity_Date_And_Time=$audit_record_row['Activity_Date_And_Time'];
                            $Date_And_Time=$audit_record_row['Date_And_Time'];
                            $Location=$audit_record_row['Location'];
                            $IP_Address=$audit_record_row['IP_Address'];
                            $PC_Name=$audit_record_row['PC_Name'];
                            $Branch_ID=$audit_record_row['Branch_ID'];
                            $Branch_Name=$audit_record_row['Branch_Name'];

                            if($Description == "Authentication"){
                                $select=mysqli_query($conn,"SELECT  Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Location' ");
                                $row=mysqli_fetch_array($select);
                                $Location=$row['Sub_Department_Name'];
                            }
                            //display the data
                            echo "<tr>
                                                    <td>$sn</td>
                                                    <td>$employeeName</td>"; ?>
                                                    <?php
                                                        if(($Login_From == '' || $Login_From == null) && ($Login_To == '' || $Login_To == null)){ ?>

                                                        <?php }else{ ?>
                                                            <td>
                                                                <?php echo date('jS F, Y H:i:s',strtotime($Login_Date_And_Time)) ?>
                                                            </td>
                                                        <?php }
                                                            if(($Logout_From == '' || $Logout_From == null) && ($Logout_To == '' ||  $Login_To == null)){?>

                                                        <?php }else{?>
                                                                <td>
                                                                    <?php
                                                                    if($Logout_Date_And_Time == '0000-00-00 00:00:00'){
                                                                        echo "Not Yet Logged Out. ";
                                                                    }else{
                                                                        echo date('jS F, Y H:i:s',strtotime($Logout_Date_And_Time));
                                                                    }
                                                                    ?>
                                                                </td>
                                                            <?php }

                                                                if(($Auth_From == '' || $Auth_From == null) && ($Auth_From == '' ||  $Auth_From == null)){ ?>

                                                        <?php }else{ ?>
                                                                    <td>
                                                                        <?php
                                                                        if($Authentication_Date_And_Time == '0000-00-00 00:00:00' || $Authentication_Date_And_Time == ''){
                                                                            echo "No Authentication Recorded.";
                                                                        }else{
                                                                            echo $Authentication_Date_And_Time;
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                <?php }

                                                                    if(($Auth_From == '' || $Auth_From == null) && ($Auth_From == '' ||  $Auth_From == null)){ ?>
                                                                    <?php }else { ?>
                                                                        <?php
                                                                        if (empty($Authentication)) {
                                                                            ?>
                                                                            <td>
                                                                                <?php echo "No Authentication Location requested"; ?>
                                                                            </td>

                                                                        <?php } else {//no filter ?>
                                                                            <td>
                                                                                <?php echo $Authentication; ?>
                                                                            </td>
                                                                        <?php
                                                                        }
                                                                    }


                                                            if((($Login_From == '' || $Login_From == null) && (($Login_To == '' || $Login_To == null))) && ($Logout_From == '' || $Logout_From == null) && ($Auth_From == '' || $Auth_From == null)){?>
                                                                <td>
                                                                    <?php echo date('jS F, Y H:i:s',strtotime($Login_Date_And_Time)) ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if($Logout_Date_And_Time == '0000-00-00 00:00:00'){
                                                                        echo "Not Yet Logged Out. ";
                                                                    }else{
                                                                        echo date('jS F, Y H:i:s',strtotime($Logout_Date_And_Time));
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if($Authentication_Date_And_Time == '0000-00-00 00:00:00' || $Authentication_Date_And_Time == ''){
                                                                        echo "No Authentication Recorded.";
                                                                    }else{
                                                                        echo $Authentication_Date_And_Time;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        if(empty($Authentication)){
                                                                            echo "No Authentication Location Recorded.";
                                                                        }else{
                                                                            echo $Authentication;
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if(empty($Activity)){
                                                                        echo "No Activity.";
                                                                    }else{
                                                                        echo $Activity;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if(empty($Activity_Date_And_Time)){
                                                                        echo "No Activity Recorded.";
                                                                    }else{
                                                                        echo $Activity_Date_And_Time;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        echo $Location;
                                                                    ?>
                                                                </td>
                                                         <?php }else{ ?>
<!--                                                                <td>-->
<!--                                                                    --><?php ////echo date('jS F, Y H:i:s',strtotime($Login_Date_And_Time)) ?>
<!--                                                                </td>-->
<!--                                                                <td>-->
<!--                                                                    --><?php ////echo date('jS F, Y H:i:s',strtotime($Logout_Date_And_Time)) ?>
<!--                                                                </td>-->
<!--                                                                <td>-->
<!--                                                                    --><?php ////echo date('jS F, Y H:i:s',strtotime($Authentication_Date_And_Time)) ?>
<!--                                                                </td>-->
                                                            <?php } ?>
                                                        <?php
//                                                        if((($Login_From != '' || $Login_From != null) && (($Login_To != '' || $Login_To != null))) && ($Logout_From != '' || $Logout_From != null) && ($Auth_From != '' || $Auth_From != null)){
//                                                            echo "<td>$Location</td>";
//                                                        }else{
//                                                            echo "<td>$Location</td>";
//                                                        }
                                                 echo   "<td>$IP_Address</td>
                                                    <td>$PC_Name</td>
                                                    <td>$Branch_Name</td>

                                            </tr>";

                            $sn++;

                        }
			    ?>
			</table>
			<table>
			</table>
			</center>
			</center>
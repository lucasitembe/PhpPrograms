<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Search_Value'])){
        $Search_Value = $_GET['Search_Value'];
    }else{
        $Search_Value = '';
    }
    
    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }

    
    if(isset($_GET['Type_Of_Check_In'])){
        $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    }else{
        $Type_Of_Check_In = 'All';
    }

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = '0';
    }
?>
<legend style="background-color:#006400;color:white;padding:5px;" align='right'><b>PATIENTS LOCATION</b></legend>
<table width=100% id='location_tbl' class="display" border='1'>
    <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width=5%><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width=10%><b>PATIENT#</b></td>
            <td width=10%><b>LABORATORY</b></td>
            <td width=10%><b>RADIOLOGY</b></td>
            <td width=15%><b>PHARMACY</b></td>
            <td width=20%><b>PROCEDURE</b></td>
            <td><b>SURGERY</b></td>
        </tr>
    <tr><td colspan="9"><hr></td></tr>  
    
    <?php
        $temp = 0;
        $sql = "select sp.Guarantor_Name, pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, ci.AuthorizationNo, emp.Employee_Name
                                            from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp where
                                            pr.Registration_ID = ci.Registration_ID and
                                            ci.Employee_ID = emp.Employee_ID and
                                            pr.Sponsor_ID = sp.Sponsor_ID and";
        //get list of checked in patients
        if($Type_Of_Check_In == 'All'){
            if($Sponsor_ID == 0){
                if($Employee_ID == '0'){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }
            }else{
                if($Employee_ID == 0){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }
            }
        }else{ 
            if($Sponsor_ID == 0){
                if($Employee_ID == '0'){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                            ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }
            }else{
                if($Employee_ID == 0){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc limit 1") or die(mysqli_error($conn));
                    }
                }
            }
        }
        
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
                echo "<tr>
                    <td>".++$temp."</td>
                    <td>".$row['Patient_Name']."</td>
                    <td>".$row['Registration_ID']."</td>
                    <td>".$row['Guarantor_Name']."</td>
                    <td>".$row['Phone_Number']."</td>
                    <td>".$row['Type_Of_Check_In']."</td>
                    <td>".$row['Check_In_Date_And_Time']."</td>
                    <td>".$row['Employee_Name']."</td>
                    <td>".$row['AuthorizationNo']."</td>
                </tr>";
            }
        }
    ?>
</table>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
 
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
   $(document).ready(function(){
	   $('#location_tbl').dataTable({
            "bJQueryUI": true,
			});
   });
</script>

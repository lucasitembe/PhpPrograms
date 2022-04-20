<?php
@session_start();
include("./includes/connection.php");
$fromDate=mysqli_real_escape_string($conn,$_POST['fromDate']);
$toDate=mysqli_real_escape_string($conn,$_POST['toDate']);
$Sponsor_ID=mysqli_real_escape_string($conn,$_POST['Sponsor']);

$Type_Of_patient=$_POST['Type_Of_patient'];
$Type_Of_visit=$_POST['Type_Of_visit'];
$agetype = mysqli_real_escape_string($conn, $_POST['agetype']);
if(isset($_POST['ageFrom'])){
    $ageFrom = $_POST['ageFrom'];
}else{
    $ageFrom = 0;
}

if(isset($_POST['ageTo'])){
    $ageTo = $_POST['ageTo'];
}else{
    $ageTo = 0;
}
$filter=" AND TIMESTAMPDIFF($agetype ,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$ageFrom."' AND '".$ageTo."'";
if($Sponsor_ID!='All'){
	$filter .=" AND pr.Sponsor_ID=$Sponsor_ID ";
}
if($Type_Of_patient!='all'){
	$filter2=" AND ci.Type_Of_Check_In='$Type_Of_patient' ";
}else{
       $filter2="";
        
}
if($Type_Of_visit!='all'){
	$filter3=" AND ci.visit_type='$Type_Of_visit' ";
}else{
       $filter3="";
      
}

$select_patients=mysqli_query($conn,"SELECT DISTINCT Visit_Date,Registration_ID FROM tbl_check_in WHERE Check_In_Date_And_Time BETWEEN '$fromDate' AND '$toDate' GROUP BY Visit_Date ORDER BY Visit_Date ASC") or die(mysqli_error($conn));
echo "<center><table width='98%' style='background-color:white;font-size:15px;'>";
echo "<tr>";
echo "<th>SN</th><th>Date</th><th>Male</th><th>Female</th><th>Total</th>";
echo "</tr>";
$counter=1;
$total_male=0;
$total_female=0;
while ($row=mysqli_fetch_assoc($select_patients)) {
	$given_date=$row['Visit_Date'];
	$Registration_ID=$row['Registration_ID'];
	$male_count=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(ci.Registration_ID) AS count FROM tbl_check_in ci, tbl_patient_registration pr WHERE pr.Registration_ID=ci.Registration_ID $filter2 AND pr.Gender='Male' $filter AND ci.Visit_Date ='$given_date' $filter3"))['count'];
	$female_count=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(ci.Registration_ID) AS count FROM tbl_check_in ci, tbl_patient_registration pr WHERE pr.Registration_ID=ci.Registration_ID $filter2 AND pr.Gender='Female' $filter AND ci.Visit_Date ='$given_date' $filter3"))['count'];

	echo "<tr><td style='padding-left:10px;'>{$counter}</td><td><a href='#' onclick='viewpatientsattendence(\"$Registration_ID\",\"$Type_Of_patient\",\"$fromDate\",\"$toDate\",\"$Sponsor_ID\",\"$given_date\",\"$Type_Of_visit\");'  style='display:block;'>".$given_date.", ".date("l", strtotime($given_date))."</a></td><td style='text-align:center;'>".$male_count."</td><td style='text-align:center;'>".$female_count."</td><td style='text-align:right;padding-right:10px;'>".($male_count+$female_count)."</td></tr>";
	$counter++;
	$total_male+=$male_count;
	$total_female+=$female_count;
}
echo "</tr><td colspan='5'><hr></td>";
echo "<tr><td colspan='2'><b>Total Attendance</b></td><td style='text-align:center;'><b>".number_format($total_male)."</b></td><td style='text-align:center;'><b>".number_format($total_female)."</b></td><td style='text-align:right;padding-right:10px;'><b>".number_format($total_male+$total_female)."</b></td></tr>";
echo "<tr><td colspan='5'><hr></td></tr>";
echo "</table></center>";
?>
<!-- <script>
          
    function viewpatients(Registration_ID,Type_Of_patient,fromDate,toDate,Sponsor_ID){
       $.ajax({
            url:'fetch_admitted_patient.php',
            type:'post',
            data:{Registration_ID:Registration_ID,Type_Of_patient:Type_Of_patient,fromDate:fromDate,toDate:toDate,Sponsor_ID:Sponsor_ID},
            success:function(result){
                console.log(result);
                $('#displayPatientList').html(result);
            }
        });
        $("#displayPatientList").dialog('open');
    }
    
  
    $(document).ready(function () {
        $("#displayPatientList").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS LIST', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
         $('#radiology').show();
         $('select').select2();
    });
    </script>-->

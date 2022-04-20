<?php
include("./includes/connection.php");
include("./includes/header_general.php");
if (!isset($_SESSION['userinfo'])) {
    die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Nurse_Station_Works']) || isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            echo $_SESSION['userinfo']['Nurse_Station_Works']."doctor".$_SESSION['userinfo']['Doctors_Page_Outpatient_Work'];
            die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
        }
    } else {
        die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
    }
} else {
    die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
}


// script of puttin color to to table row
echo "<script type='text/javascript'>
$(document).ready(function(){
  $('tr:odd').css('background-color','#DEB887');
});
</script>";

//end of script


$Registration_ID = $_GET['Registration_ID'];
$Vital_ID = $_GET['Vital_ID'];
$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];

$query = "SELECT Nurse_DateTime,Vital_Value,Vital_ID FROM tbl_nurse n,tbl_nurse_vital nv
			WHERE nv.Nurse_ID = n.Nurse_ID AND 
			n.Registration_ID='$Registration_ID' AND 
			nv.Vital_ID='$Vital_ID' ORDER BY Nurse_DateTime DESC LIMIT 100";

$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$Vital = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Vital FROM tbl_vital v WHERE v.Vital_ID='$Vital_ID'"))['Vital'];

//get number of rows returned
$num_results = mysqli_num_rows($result); //$result->num_rows;

if ($result) {
    echo "<center><fieldset style='margin-top:18px;width:80%;'>";
    echo "<h5><b>$Vital History Report </b></h5>";
    echo "<fieldset class='vital' style='height:330px;overflow-y:scroll;'>";
    echo "<table   width='40%' 
	style='border-left:0px;border-right:0px;border-top:1px solid black;border-bottom:1px solid black;margin-bottom:20px;' >
	
<tr bgcolor='#D3D3D3'>
<th>VISITED DATE </th>
<th>VALUE</th>
</tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        $Nurse_DateTime = $row['Nurse_DateTime'];
        $Vital_Value = $row['Vital_Value'];
        $Vital_ID = $row['Vital_ID'];

        echo "<tr>";
        echo "<td>" . $row['Nurse_DateTime'] . "</td>";
        echo "<td>" . $row['Vital_Value'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</fieldset>";
    echo "</center>";
}
?>

<br>
<center>
    <fieldset style="margin-bottom:4px;width:80%" >
        <legend align=center><b>CHOOSE TYPE OF GRAPH BELOW</b></legend>
        <a  href='chart.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>' style='text-decoration: none' > <button style='width:15%; height:40px'>Line CHART</button></a>
        &nbsp;&nbsp;&nbsp;

        <a style='text-decoration: none' href='chart1.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>' ><button style='width:15%; height:40px'>Pie CHART</button></a>
        &nbsp;&nbsp;&nbsp;

        <a style='text-decoration: none' href='chart2.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>'><button style='width:25%; height: 40px'>Horizontal Bar CHART</button></a>
        &nbsp;&nbsp;&nbsp;

        <a style='text-decoration: none' href='chart3.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>'  ><button style='width:25%; height: 40px'>Vertical Bar CHART</button></a>
    </fieldset>
</center>
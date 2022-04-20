<?php
include("./includes/connection.php");
include("./includes/header_general.php");
//include the library
include "libchart/libchart/classes/libchart.php";

if (!isset($_SESSION['userinfo'])) {
    die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Nurse_Station_Works']) || isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
//        if ($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes' || $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
//            die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
//        }
//    } else {
//        die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
//    }
} else {
    die("<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
}

$Registration_ID = $_GET['Registration_ID'];
$Vital_ID = $_GET['Vital_ID'];
$Vital = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Vital FROM tbl_vital v WHERE v.Vital_ID='$Vital_ID'"))['Vital'];
echo " <a href='show_vital_history.php?Registration_ID=" . $_GET['Registration_ID'] . "&Vital_ID=$Vital_ID&Patient_Payment_Item_List_ID=" . $_GET['Patient_Payment_Item_List_ID'] . "' class='art-button-green'>
        BACK
       </a><br/><br/>";

//new date function (Contain years, Months and days)

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = '';
}

//end of the function 
//new pie chart instance
$chart = new VerticalBarChart(500, 300);

//data set instance
$dataSet = new XYDataSet();

//query all records from the database
$query = "SELECT Nurse_DateTime,Vital_Value,Patient_Name,Date_Of_Birth FROM tbl_nurse n,tbl_nurse_vital nv,tbl_Patient_Registration pr
			WHERE nv.Nurse_ID = n.Nurse_ID 
                        and pr.Registration_ID=n.Registration_ID
			AND n.Registration_ID=$Registration_ID
			AND nv.Vital_ID=$Vital_ID";


$result = mysqli_query($conn,$query);
$imgData = '';

//get number of rows returned
$num_results = mysqli_num_rows($result); //$result->num_rows;

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {
        $Nurse_DateTime = $row['Nurse_DateTime'];
        $Vital_Value = $row['Vital_Value'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

        //		   extract($row);
        $dataSet->addPoint(new Point("$Nurse_DateTime", $Vital_Value));
    }

    //finalize dataset
    $chart->setDataSet($dataSet);

    //set chart title
    $chart->setTitle("$Vital ,Patient_No $Registration_ID ,$Age");

    //render as an image and store under "generated" folder
  //  $image_name = "generated/chart_" . $_SESSION['userinfo']['Employee_ID'] . ".png";
     $image_name = "generated/chart_" . $Vital . "_" . $Registration_ID . ".png";
    $chart->render($image_name);

    //pull the generated chart where it was stored
    $imgData = "<img alt='Pie chart'  src='$image_name' style='border: 1px solid gray;margin-left:22%;'/>";
} else {
    $imgData = "No records  found in the database.";
}
?>
<center>
    <fieldset style="width:94%;">
        <center>
<?php echo $imgData ?>
        </center>
    </fieldset>
    <br>

    <fieldset style="width:94%;">
        <a  href='chart.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>' style='text-decoration: none' > <button style='width:15%; height:40px'>Line CHART</button></a>
        &nbsp;&nbsp;&nbsp;

        <a style='text-decoration: none' href='chart1.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>' ><button style='width:15%; height:40px'>Pie CHART</button></a>
        &nbsp;&nbsp;&nbsp;

        <a style='text-decoration: none' href='chart2.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>'><button style='width:25%; height: 40px'>Horizontal Bar CHART</button></a>
        &nbsp;&nbsp;&nbsp;

        <a style='text-decoration: none' href='chart3.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=<?php echo $_GET['Vital_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>'  ><button style='width:25%; height: 40px'>Vertical Bar CHART</button></a>

    </fieldset>
</center>
<?php
    include("./includes/connection.php");
    session_start();
    $section = $_GET['section'];
?>

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<link rel="stylesheet" href="style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>

<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
</style>

<?php
    
    //Find the current date to filter check in list     
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    
?>
    <center>
        <table width = '90%'>
            <tr>
                <td><b>SN</b></td>
		<td><b>RMN</b></td>
                <td><b>PATIENT NAME</b></td>  
                <td><b>ACTION</b></td>
            </tr>
<?php
    $select_patients = "SELECT * FROM tbl_patient_registration";
    $result = mysqli_query($conn,$select_patients);
    $i=1;
    while($row = mysqli_fetch_array($result)){
        ?>
	<tr><td colspan=6><hr></td></tr>
        <tr>
            <td><?php echo $i.". "?></td>
	    <td><?php echo $row['Registration_ID'];?></td>
            <td><?php echo $row['Patient_Name']; ?></td> 
            <td>
		<a href='departmentworkspage.php?section=<?php echo $section;?>&Registration_ID=<?php echo $row['Registration_ID'];?>&AllValue=true&Patients=All&PatientPayment=PatientPayment&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green' target='_parent'>
                PROCEED
                </a>
            </td>
        </tr>
        <?php
	$i++;
    }
?>
        </table>
    </center>
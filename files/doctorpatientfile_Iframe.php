<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    session_start();
    if(isset($_GET['name'])){
        $Patient_Name = $_GET['name'];
    }else{
        $Patient_Name = "";
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //Find the current date to filter check in list     
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
?>
    <center>
        <table width = '100%'>
            <tr id='thead'>
                <td><center><b>SN</b></center></td>
                <td><center><b>DATE</b></center></td>
                <td><center><b>DOCTORS REVIEW</b></center></td>
                <td><center><b>ATTACHMENT</b></center></td>
                <td><center><b>RESULTS</b></center></td>
            </tr>
<?php
    $select_patients = "SELECT * FROM tbl_consultation c WHERE c.Registration_ID = $Registration_ID";
    $result = mysqli_query($conn,$select_patients);
    $i=1;
    while($row = mysqli_fetch_array($result)){
        ?>
        <tr>
            <td><center><?php echo $i.". "; ?></center></td>
            <td><center><?php echo $row['Consultation_Date_And_Time']; ?></center></td>
            <td>
		<center>
		<a href='doctorpatientreview.php?consultation_ID=<?php echo $row['consultation_ID'];?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'>
		    <button style='width: 40%; height: 100%'><?php echo "REVIEW"; ?></button>
		</a>
		</center>
	    </td>
            <td>
		<center>
		<a href='doctorpatientattachments.php?consultation_ID=<?php if(isset($_GET['consultation_ID'])){ echo $_GET['consultation_ID']; } ?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'>
			    <button style='width: 40%; height: 100%'>
                            View Attachments
			    </button>
		</a>
		<a href='doctorattachmentupload.php?consultation_ID=<?php if(isset($_GET['consultation_ID'])){ echo $_GET['consultation_ID']; } ?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'>
			    <button style='width: 40%; height: 100%'>
				Attach
			    </button>
                </a>
		</center>
	    </td>
            <td>
		<center>
		<a href='doctorpatientresults.php?consultation_ID=<?php echo $row['consultation_ID'];?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'>
		    <button><?php echo 'RESULTS'; ?></button>
		</a>
		</center>
	    </td>
	</tr>
        <?php
	$i++;
    }
?>
    </table>
</center>
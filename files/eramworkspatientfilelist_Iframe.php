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
        <table width = '100%' border=1>
            <tr style='background: #1e90ff'>
                <td><b>SN</b></td>
                <td><b>DATE</b></td>
                <td><b>DOCTORS REVIEW</b></td>
                <td><b>ATTACHMENT</b></td>
                <td><b>RESULTS</b></td>
            </tr>
<?php
    $select_patients = "SELECT * FROM tbl_consultation c WHERE c.Registration_ID = $Registration_ID";
    $result = mysqli_query($conn,$select_patients);
    $i=1;
    while($row = mysqli_fetch_array($result)){
        ?>
        <tr>
            <td style='background: #1e90ff'><?php echo $i.". "; ?></td>
            <td><?php echo $row['Consultation_Date_And_Time']; ?></td>
            <td><a href='eramworksdoctorpatientreview.php?consultation_ID=<?php echo $row['consultation_ID'];?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'><?php echo "REVIEW"; ?></a></td>
            
	    <td><a href='eramworksattachmentpage.php?consultation_ID=<?php echo $row['consultation_ID'];?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'><?php echo 'ATACHMENTS'; ?></a></td>
            
	    <td>
		<!--<a href='doctorpatientresults.php?consultation_ID=<?php echo $row['consultation_ID'];?><?php if(isset($_GET['doctorsworkpage'])){ ?>&doctorsworkpage=yes<?php } ?>&Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>' target='_parent' style='text-decoration: none'><?php echo 'RESULTS'; ?></a>-->
		<a href='#' style='text-decoration: none'>RESULTS</a></td>
	</tr>
        <?php
	$i++;
    }
?>
    </table>
</center>
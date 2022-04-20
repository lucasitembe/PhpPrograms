<?php
    include("./includes/connection.php");
    session_start();
    if(isset($_GET['name'])){
        $Patient_Name = $_GET['name'];
    }else{
        $Patient_Name = "";
    }
    
    //Find the current date to filter check in list     
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
?>
    <center>
        <table width = '100%' border=1>
            <tr>
                <td><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td><b>MRN</b></td>
                <td><b>GENDER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>PHONE</b></td>
            </tr>
<?php
    $select_patients = "SELECT * FROM tbl_patient_registration pr, tbl_consultation c,tbl_sponsor s
                        WHERE c.Registration_ID = pr.Registration_ID AND s.Sponsor_ID = pr.Sponsor_ID AND pr.Patient_Name LIKE '%$Patient_Name%' GROUP BY pr.Registration_ID";
    $result = mysqli_query($conn,$select_patients);
    $i=1;
    while($row = mysqli_fetch_array($result)){
        ?>
        <tr>
            <td><a href='eramworkspatientfile.php?Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>' target='_parent' style='text-decoration: none'><?php echo $i.". "; ?></a></td>
            <td><a href='eramworkspatientfile.php?Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>' target='_parent' style='text-decoration: none'><?php echo $row['Patient_Name']; ?></a></td>
            <td><a href='eramworkspatientfile.php?Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>' target='_parent' style='text-decoration: none'><?php echo $row['Registration_ID']; ?></a></td>
            <td><a href='eramworkspatientfile.php?Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>' target='_parent' style='text-decoration: none'><?php echo $row['Gender']; ?></a></td>
            <td><a href='eramworkspatientfile.php?Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>' target='_parent' style='text-decoration: none'><?php echo $row['Guarantor_Name']; ?></a></td>
            <td><a href='eramworkspatientfile.php?Registration_ID=<?php echo $row['Registration_ID'];?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>' target='_parent' style='text-decoration: none'><?php echo $row['Phone_Number']; ?></a></td>
        </tr>
        <?php
	$i++;
    }
?>
    </table>
</center>
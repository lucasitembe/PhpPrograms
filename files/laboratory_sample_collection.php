<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work']))
		{
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
                ?>



<fieldset style="margin-top:5px;min-height:500px;">
  <center>
  <div style="overflow:scroll;height:550px;widht:100%;border-bottom:0px;">
    <table border="1"  style="width:90%" class="hiv_table" bgcolor="white">
        <tr>
        <th>S/N</th>
        <th>Patient Name</th>
        <th>Sponsor Name</th>
        <th>Doctor Requested</th>
        <th>Reg No.</th>
        <th>Date/Time Modified</th>
        <th>Time Of Sample Taken</th>
        <th>Detail</th>
        <th>Payment Status</th>
        <th>Status</th>
        <th>&nbsp;</th>
        </tr>
<?php


$sql=mysqli_query($conn,"select  'payment' as Status_From,il.Process_Status as Process_Status,p.Patient_Payment_ID as payment_id,p.Sponsor_Name as Sponsor_Name,i.Product_Name as item_name,i.Item_ID as item_id,'Direct From Payment' as comment,
 'Direct From Payment' as Employee,pr.Registration_ID as registration_number,pr.Patient_Name as patient_name
 from tbl_patient_payments as p 
join tbl_patient_payment_item_list as il on p.Patient_Payment_ID = il.Patient_Payment_ID
join tbl_employee as e on e.Employee_ID = p.Employee_ID
join tbl_patient_registration as pr on pr.Registration_ID =p.Registration_ID
join tbl_items as i on i.Item_ID =il.Item_ID 
where il.Check_In_Type='Laboratory'GROUP BY pr.Registration_ID");



// $sql=mysqli_query($conn,"select  'payment' as Status_From,il.Process_Status as Process_Status,p.Patient_Payment_ID as payment_id,p.Sponsor_Name as Sponsor_Name,i.Product_Name as item_name,i.Item_ID as item_id,'Direct From Payment' as comment,
//  'Direct From Payment' as Employee,pr.Registration_ID as registration_number,pr.Patient_Name as patient_name
//  from tbl_patient_payments as p 
// join tbl_patient_payment_item_list as il on p.Patient_Payment_ID = il.Patient_Payment_ID
// join tbl_employee as e on e.Employee_ID = p.Employee_ID
// join tbl_patient_registration as pr on pr.Registration_ID =p.Registration_ID
// join tbl_items as i on i.Item_ID =il.Item_ID 
// where il.Check_In_Type='Laboratory' 

// union all

// select 'cache' as Status_From,pl.Process_Status as Process_Status,pc.Payment_Cache_ID as payment_id,pc.Sponsor_Name as Sponsor_Name,i.Product_Name as item_name,i.Item_ID as item_id,
//  pl.Doctor_Comment as comment,e.Employee_Name as Employee,pr.Registration_ID as registration_number,pr.Patient_Name as patient_name 
//  from tbl_payment_cache as pc 
//  join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID 
// Join tbl_employee as e on e.Employee_ID =pc.Employee_ID
//  join tbl_patient_registration as pr on pr.Registration_ID =pc.Registration_ID 
//  join tbl_items as i on i.Item_ID =pl.Item_ID where pl.Check_In_Type='Laboratory' 
//  group by patient_name");

$i=1;
        while($rows=mysqli_fetch_array($sql)){
            ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><input name="" type="" style="width:98%" value="<?php echo $rows['patient_name']; ?>"></td>
            <td><input name="" type="" style="width:100%" value="<?php echo $rows['Sponsor_Name']; ?>"></td>
            <td><input name="" type="" style="width:98%" value="<?php echo $rows['Employee']; ?>"></td>
            <td><input name="" type="" style="width:98%" value="<?php echo $rows['registration_number']; ?>"></td>
            <td><input name="" type="" style="width:98%" value=""></td>
            <td><input name="" type="" style="width:98%" value=""></td>
            <td><a href="laboratory_sample_collection_details.php?Status_From=<?php echo $rows['Status_From']; ?>&item_id=<?php echo $rows['item_id']; ?>&patient_id=<?php echo $rows['registration_number']; ?>&payment_id=<?php echo $rows['payment_id']; ?>"><button>Details</button></a></td>
            <td><input name="" type="text" style="width:98%" value=""></td>
            <td><button><?php 
            if($rows['Process_Status']=='Sample Collected'){
                echo "<span style='color:blue;width:100%;'>Done</span>";
            }elseif ($rows['Process_Status'] == 'On Progress') {
                echo "<span style='color:green;width:100%;'>On Progress</span>";


            }else{
                echo "<span style='color:red;width:100%;'>Not Teken</span>";


            }
            ?>
            </button></td>
            <td><input name="" type="checkbox" style="width:98%"></td>
        </tr>
                <?php
        $i++;
}
?>

    </table>
    </div>
    <div align="right"><button>Make Payments</button><button>Delete Checked</button></div>               
</center>
</fieldset>
                
                
<?php
    include("./includes/footer.php");

?>
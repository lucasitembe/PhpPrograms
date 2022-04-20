<?php
    include("./includes/connection.php");
    $temp = 0;
    $Patient_Number = 0;
?>


<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
?>

<style>
		table,tr,td{
		border-collapse:collapse !important;
		border:none !important;
		
		}
	tr:hover{
	background-color:silver;
	cursor:pointer;
	}
 </style> 
 
<table width=100% border=0>
    <tr>
        <td width=10%><b>SN</b></td>
        <td><b>DOCTOR NAME</b></td>
        <td><b>PATIENT#</b></td>		
    </tr>
	<tr><td colspan=3><hr></td></tr>
    
    <?php
        $select = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        
        if($no > 0){
            while($data = mysqli_fetch_array($select)){
                $Employee_ID = $data['Employee_ID'];
                //get number of patients based on selected doctor
                $select_details = mysqli_query($conn,"select count(ppl.Patient_Payment_ID) as Amount from 
                                                tbl_patient_payments pp, tbl_patient_payment_item_list ppl where 
                                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                                (Patient_Direction = 'Direct To Doctor' or Patient_Direction = 'Direct To Doctor Via Nurse Station') and
                                                Consultant_ID = '$Employee_ID' and 
                                                Receipt_Date = '$Today'") or die(mysqli_error($conn));
                
                $Number = mysqli_num_rows($select_details);
                if($Number > 0){
                    while($row = mysqli_fetch_array($select_details)){
                        $Amount = $row['Amount'];
                    }
                }else{
                    $Amount = 0;
                }
?>
                <tr>
                    <td width=10%><b><?php echo ++$temp; ?></b></td>
                    <td><?php echo $data['Employee_Name']; ?></td>
                    <td><?php echo $Amount; ?></td>
                </tr>
<?php
                $Patient_Number = 9;    
            }
        }
    ?>
</table>
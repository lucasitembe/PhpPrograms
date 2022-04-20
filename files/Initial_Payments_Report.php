<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
	$Grand_Total_Unpaid = 0;
	$Grand_Total_Paid = 0;

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = 0;
	}

	
	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	
	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

    //generate values
    $val = '';
    $get_value = mysqli_query($conn,"select Item_ID from tbl_initial_items") or die(mysqli_error($conn));
    $nos = mysqli_num_rows($get_value);
    if($nos > 0){
        while ($row = mysqli_fetch_array($get_value)) {
            $val .= ','.$row['Item_ID'];
        }
    }
    $Filter_Value = substr($val, 1);

?>
<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>INITIAL PAYMENTS REPORT</b></legend>
    <table width=100% border=1>
<?php
	if($Item_ID == 0){
		$select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID order by Initial_ID") or die(mysqli_error($conn));
	}else{
		$select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID and i.Item_ID = '$Item_ID' order by Initial_ID") or die(mysqli_error($conn));
	}
    $no = mysqli_num_rows($select);
    if($no > 0){

    	//create dynamic total variables
    	for ($e=1; $e <= $no; $e++) {
    		$$e = 0;
    	}

        //display titles
        echo "<tr><td><b>SN</b></td><td><b>SPONSOR NAME</b></td>";
        while ($row = mysqli_fetch_array($select)) {
            echo '<td style="text-align: right;"><b>'.strtoupper($row['Product_Name']).'</b></td>';
        }
        echo "<td style='text-align: right;'><b>UNPAID</b></td><td style='text-align: right;'><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td></tr>";
        echo "<tr><td colspan='".($no+4)."'><hr></td></tr>";
        if($Sponsor_ID == 0){
        	$get_sponsor = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
        }else{
        	$get_sponsor = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor where Sponsor_ID = '$Sponsor_ID' order by Guarantor_Name") or die(mysqli_error($conn));        	
        }


        $num_sp = mysqli_num_rows($get_sponsor);
        if($num_sp > 0){


            while ($data = mysqli_fetch_array($get_sponsor)) {
                $Temp_Sponsor_ID = $data['Sponsor_ID'];
                $Total_Paid = 0;
                $V_controler = 1;

            //get paid patients
            $slk = mysqli_query($conn,"select pp.Check_In_ID from
                                    tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr where
                                    pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                    pp.Check_In_ID = ci.Check_In_ID and
                                    pr.Registration_ID = pp.Registration_ID and
                                    ppl.Item_ID in ($Filter_Value) and
                                    pr.Sponsor_ID = '$Temp_Sponsor_ID' and
                                    ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' group by ci.Check_In_ID") or die(mysqli_error($conn));
            $this_num = mysqli_num_rows($slk);

?>
                <tr>
                    <td><?php echo ++$temp; ?><b>.</b></td>
                    <td><?php echo $data['Guarantor_Name']; ?></td>
<?php
     	           	if($no > 0){
	     	           	if($Item_ID == 0){
							$select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID order by Initial_ID") or die(mysqli_error($conn));
						}else{
							$select = mysqli_query($conn,"select i.Product_Name, i.Item_ID from tbl_initial_items ii, tbl_items i where i.Item_ID = ii.Item_ID and i.Item_ID = '$Item_ID' order by Initial_ID") or die(mysqli_error($conn));
						}
     	           		while ($details = mysqli_fetch_array($select)) {
     	           			$Temp_Item_ID = $details['Item_ID'];
     	           				
     	           			
     	           			//calculate total 
     	           				$calculate = mysqli_query($conn,"select COUNT(pp.Check_In_ID) as Total from
     	           											tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr where
     	           											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
     	           											pp.Check_In_ID = ci.Check_In_ID and
     	           											pr.Registration_ID = pp.Registration_ID and
     	           											ppl.Item_ID = '$Temp_Item_ID' and
     	           											pr.Sponsor_ID = '$Temp_Sponsor_ID' and
     	           											ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
     	           											") or die(mysqli_error($conn));
     	           				$mynum = mysqli_num_rows($calculate);
     	           				if($mynum > 0){
     	           					while ($dt = mysqli_fetch_array($calculate)) {
     	           						$Total_Paid += $dt['Total']; //total paid per sponsor
     	           						$$V_controler += $dt['Total']; //Grand total per item select
     	           						echo '<td style="text-align: right;">'.$dt['Total'].'</td>';
     	           					}
     	           				}
     	           			$V_controler++;
     	           		}

                        
     	           		//get total checked in
     	           		$select_checkin = mysqli_query($conn,"select COUNT(ci.Check_In_ID) as Total_Checked from
     	           											tbl_check_in ci, tbl_patient_registration pr where
     	           											ci.Registration_ID = pr.Registration_ID and
     	           											pr.Sponsor_ID = '$Temp_Sponsor_ID' and
     	           											ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
     	           											") or die(mysqli_error($conn));
     	           		$num_checkin = mysqli_num_rows($select_checkin);
     	           		if($num_checkin > 0){
     	           			while ($rw = mysqli_fetch_array($select_checkin)) {
     	           				$Total_Checked = $rw['Total_Checked'];
     	           			}
     	           		}else{
     	           			$Total_Checked = 0;
     	           		}
     	           	}
     	    $Grand_Total_Unpaid += ($Total_Checked - $this_num);
     	    $Grand_Total_Paid += $Total_Checked;
?>
			<td style="text-align: right;">
                <label onclick="Preview_Unpaid_Details('<?php echo $Temp_Sponsor_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>')"><?php echo ($Total_Checked - $this_num); ?></label>
            </td>
            <td style="text-align: right;"><?php echo $Total_Checked; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
<?php            
            }
        }
    }
?>
        <tr><td colspan="<?php echo $no+4; ?>"><hr></td></tr>
        <tr><td colspan="2"><b>TOTAL</b></td>
<?php
                for($i = 1; $i <= $no; $i++){
                    echo "<td style='text-align: right;'><b>".$$i."</b></td>";
                }
?>
            <td style='text-align: right;'>
                <label onclick='Preview_Total_Unpaid('<?php echo $Date_From; ?>','<?php echo $Date_To; ?>')'><b><?php echo $Grand_Total_Unpaid; ?></b></label>
            </td>
            <td style='text-align: right;'><b><?php echo $Grand_Total_Paid; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;</td>

        </tr>
        <tr><td colspan="<?php echo $no+4; ?>"><hr></td></tr>
    </table>
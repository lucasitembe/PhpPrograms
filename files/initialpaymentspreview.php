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

    //get sponsor name
    if($Sponsor_ID == 0){
        $Sponsor_Name = 'All';
    }else{
        $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while ($data = mysqli_fetch_array($select)) {
                $Sponsor_Name = $data['Guarantor_Name'];
            }
        }else{
            $Sponsor_Name = 'All';
        }
    }

    $htm = "<center><table width ='100%' height = '30px'>
            <tr><td>
            <img src='./branchBanner/branchBanner.png'>
            </td></tr>
        </table></center>";
    $htm .= "<br/><span style='font-size: x-small;'><b>INITIAL PAYMENTS REPORT<br/>
                Sponsor ~ ".ucwords(strtolower($Sponsor_Name))."<br/>
                Start Date ~ ".$Date_From."<br/>
                End Date ~ ".$Date_To."<br/><br/>
            ";

    $htm .= '<table width=100% >';

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
        $htm .= "<tr><td><span style='font-size: x-small;'><b>SN</b></span></td><td><span style='font-size: x-small;'><b>SPONSOR NAME</b></span></td>";
        while ($row = mysqli_fetch_array($select)) {
            $htm .= '<td style="text-align: right;"><span style="font-size: x-small;"><b>'.strtoupper($row['Product_Name']).'</b></span></td>';
        }
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'><b>UNPAID</b></span></td>
                    <td style='text-align: right;'><span style='font-size: x-small;'><b>TOTAL</b></span>&nbsp;&nbsp;&nbsp;</td></tr>";
        $htm .= "<tr><td colspan='".($no+4)."'><hr></td></tr>";
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


            $htm .= "<tr>
                    <td><span style='font-size: x-small;'>".++$temp."<b>.</b></span></td>
                    <td><span style='font-size: x-small;'>".$data['Guarantor_Name']."</span></td>";

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
     	           						$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.$dt['Total'].'</span></td>';
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
			$htm .= '<td style="text-align: right;"><span style="font-size: x-small;">'.($Total_Checked - $this_num).'</span></td>
                    <td style="text-align: right;"><span style="font-size: x-small;">'.$Total_Checked.'</span>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
            }
        }
    }

        $htm .=  '<tr><td colspan="'.($no+4).'"><hr></td></tr>
            <tr><td colspan="2"><span style="font-size: x-small;"><b>TOTAL</b></span></td>';

                for($i = 1; $i <= $no; $i++){
                    $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'><b>".$$i."</span></b></td>";
                }
                $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'><b>".$Grand_Total_Unpaid."</b></span></td>
                        <td style='text-align: right;'><span style='font-size: x-small;'><b>".$Grand_Total_Paid."</b></span>&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $htm .= '</tr>
                    <tr><td colspan="'.($no+4).'"><hr></td></tr>
                </table>';

    include("./MPDF/mpdf.php");

    $mpdf=new mPDF('','A4-L',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>
<link rel="stylesheet" href="table.css" media="screen"> 
<style>
  .linkText:hover{
     color:red;
	 cursor:pointer;
  }
</style>
<script>
function Print_Receipt_Payment(Patient_Payment_ID){
 //alert(Patient_Payment_ID);
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
       var winClose=popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);
      //winClose.close();
     //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');
    
}
  
function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
<?php
    include("./includes/connection.php");
    $temp = 1;
    $total = 0;
    
    
    //get Search_Criteria and Search_Value
    if(isset($_GET['Search_Value'])){
        $Search_Value = $_GET['Search_Value'];
    }else{
        $Search_Value = '';
    }
    
    //get Search_Criteria and Search_Value
    if(isset($_GET['Search_Criteria'])){
        $Search_Criteria = $_GET['Search_Criteria'];
    }else{
        $Search_Criteria = '';
    }
    
    //echo $Search_Value."   ".$Search_Criteria; 
    
    
    //make decition which sql to 
    if($Search_Criteria == 'Patient Name'){
        $select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
                pr.Patient_Name, pp.Folio_Number
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
                        where pp.patient_payment_id = ppl.patient_payment_id and
                            pp.registration_id = pr.registration_id
                                and sp.sponsor_id = pp.sponsor_id and
                                    pr.Patient_Name like '%$Search_Value%'
                                    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
    }elseif($Search_Criteria == 'Registration Number'){
        $select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
                pr.Patient_Name, pp.Folio_Number
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
                        where pp.patient_payment_id = ppl.patient_payment_id and
                            pp.registration_id = pr.registration_id
                                and sp.sponsor_id = pp.sponsor_id and
                                    pr.Registration_ID = '$Search_Value'
                                    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
    }elseif($Search_Criteria == 'Receipt Number'){
        $select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
                pr.Patient_Name, pp.Folio_Number
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
                        where pp.patient_payment_id = ppl.patient_payment_id and
                            pp.registration_id = pr.registration_id
                                and sp.sponsor_id = pp.sponsor_id and
                                    pp.Patient_Payment_ID = '$Search_Value'
                                    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
    }else{
        $select_Filtered_Patients = "select sp.Guarantor_Name, pr.Registration_ID, pp.Patient_Payment_ID, sum((price - discount)*quantity) as Amount,
                pr.Patient_Name, pp.Folio_Number
                    from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_patient_registration pr, tbl_sponsor sp
                        where pp.patient_payment_id = ppl.patient_payment_id and
                            pp.registration_id = pr.registration_id
                                and sp.sponsor_id = pp.sponsor_id and
                                    pp.Patient_Payment_ID = '0'
                                    group BY ppl.patient_payment_id order by pp.Patient_Payment_ID";
    }
    
    
    //echo $select_Filtered_Patients; exit;
    
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width=5%><b>SN</b></td><td width="25%"><b>PATIENT NAME</b></td>
                    <td width="25%" style="text-align: center;"><b>FOLIO NUMBER</b></td>
			<td width="25%" style="text-align: center;"><b>RECEIPT NO</b></td>
			    <td width="25%" style="text-align: right;"><b>AMOUNT</b></td>
				</tr>';
                        
    $results = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
		    
    while($row = mysqli_fetch_array($results)){
	//echo "<tr><td colspan=5><hr></td></tr>";
	echo '<tr><td>'.$temp.'</td>';
	
        echo "<td>".ucfirst($row['Patient_Name'])."</td>";
        echo "<td style='text-align: center;'><a href='foliosummaryreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".$row['Folio_Number']."</a></td>";
		
        echo "<td style='text-align: center;'><span class='linkText' onClick='Print_Receipt_Payment(".$row['Patient_Payment_ID'].")' style='text-decoration: none;'>".$row['Patient_Payment_ID']."</span></td>";
		
		 echo "<td style='text-align: center;'><a href='foliosummaryreport.php?Folio_Number=".$row['Folio_Number']."&Insurance=".$row['Guarantor_Name']."&Registration_ID=".$row['Registration_ID']."&FolioSummaryReport=FolioSummaryReportThisForm' target='_blank' style='text-decoration: none;'>".number_format($row['Amount'])."</a></td>";
	echo "</tr>";
	$total = $total + $row['Amount'];
	$temp++;
    }echo "<tr><td colspan=5><hr></td></tr>";
    echo "<tr><td colspan=5 style='text-align: right;'><b> TOTAL : ".number_format($total)."</td></tr>";
    echo "<tr><td colspan=5><hr></td></tr>";
?></table></center>

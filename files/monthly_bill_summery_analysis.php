<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
<a href="monthly_bill_summery_analysis.php?Bill_Summery=Bill_Summery&amp;QualityAssuranceWorks=QualityAssuranceWorksThisPage" class="art-button-green">
            BILLS SUMMERY ANALYSIS
        </a>
        <a href='billslist.php?Status=PreviousBills&Requisition=RequisitionThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<fieldset>
    <legend>BILLS SUMMERY ANALYSIS</legend>
    <center>
        <table>
            <thead>
                <tr>
                    <td>Select Year</td>
                    <td>
						<?php
							$select_years = mysqli_query($conn,"SELECT DISTINCT YEAR(visit_date) as year FROM tbl_check_in WHERE YEAR(visit_date) <= YEAR(CURDATE()) AND YEAR(visit_date) > 2010 ORDER BY YEAR(visit_date) DESC ");
						echo "<select id='year'>";
						while($row = mysqli_fetch_assoc($select_years)){
							echo "<option value='".$row['year']."'>".$row['year']."<option>";
						}
						echo "</select>";
						?>
					</td>
                    <td>Select Month</td>
                    <td>
                        <select name="month" id="month">
                            <option value='' selected="selected">Select Month</option>
                            <option value='January'>January</option>
                            <option value='February'>February</option>
                            <option value='March'>March</option>
                            <option value='April'>April</option>
                            <option value='May'>May</option>
                            <option value='June'>June</option>
                            <option value='July'>July</option>
                            <option value='August'>August</option>
                            <option value='September'>September</option>
                            <option value='October'>October</option>
                            <option value='November'>November</option>
                            <option value='December'>December</option>
                        </select>
                    </td>
					<td>Select Sponsor</td>
					<td>
						<select name='sponsor' id="sponsor_id">
						<?php
							$select_sponsor = mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name FROM tbl_sponsor WHERE auto_item_update_api = 1");
						while($row = mysqli_fetch_assoc($select_sponsor)){
							echo "<option value='".$row['Sponsor_ID']."'>".$row['Guarantor_Name']."</option>";
						}
						?>

						</select></td>
                    <td><input type="button" name="btn-filter" value="FILTER" class="art-button-green"  onclick="Filter_Monthly_Bills();"></td>
                    <td><input type="button" name="btn-filter" value="PREVIEW PDF" class="art-button-green"  onclick="PREVIEW_PDF();"></td>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div style="width:100%; background-color: #fff; overflow-y: auto;" id="bill_display">
            <table style="width: 98%;font-size: 18px;">
                <tr>
                    <th>SN</th>
                    <th>Folio Number</th>
                    <th>Patient Name</th>
                    <th>Phone Number</th>
                    <th>Member Number</th>
                    <th>Sent Date</th>
                    <th  style='text-align: right;'>Amount</th>
                </tr>
            <?php
				      $current_month = intval(date("m"));
				      $current_year = date("Y");

                //get all monthly bills
                // $select_monthly_bills = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Phone_Number, pr.Member_Number, cf.Bill_ID, cf.Folio_No, cf.sent_date , SUM(ppl.Price * ppl.Quantity) as BillAmount FROM tbl_patient_registration pr, tbl_claim_folio cf, tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pr.Registration_ID = pp.Registration_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND cf.Bill_ID = pp.Bill_ID AND pp.Billing_Process_Status = 'billed' AND cf. claim_month = '$current_month' AND claim_year = '$current_year' GROUP BY cf.Bill_ID ORDER BY cf.Folio_No ASC ");

                //get the folios for the specified dates
                $select_monthly_bills = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Phone_Number, pr.Member_Number, cf.Bill_ID, cf.Folio_No, cf.sent_date FROM tbl_patient_registration pr, tbl_claim_folio cf, tbl_bills b WHERE pr.Registration_ID = cf.Registration_ID AND b.Bill_ID = cf.Bill_ID AND b.e_bill_delivery_status = 1 AND cf.claim_month = '$current_month' AND cf.claim_year = '$current_year' ORDER BY cf.Folio_No ASC ");

                // print_r("SELECT pr.Patient_Name, pr.Phone_Number, pr.Member_Number, cf.Bill_ID, cf.Folio_No, cf.sent_date FROM tbl_patient_registration pr, tbl_claim_folio cf, tbl_bills b WHERE cf.Bill_ID = b.Bill_ID AND pr.Registration_ID = cf.Registration_ID AND cf.claim_month = '$month' AND cf.claim_year = '$year'  AND b.Sponsor_ID = '$sponsor_id'  ORDER BY cf.Folio_No ASC ");
                $select_monthly_bills_list=[];
                $bills_list = '';
                while ($row = mysqli_fetch_assoc($select_monthly_bills)) {
                $bills_list .= $row['Bill_ID'].",";
                array_push($select_monthly_bills_list, array('Patient_Name'=>$row['Patient_Name'], 'Phone_Number'=>$row['Phone_Number'], 'Member_Number'=>$row['Member_Number'], 'Bill_ID'=>$row['Bill_ID'], 'Folio_No'=>$row['Folio_No'], 'sent_date'=>$row['sent_date']));
                }
                $select_monthly_bills_list = ((object)$select_monthly_bills_list);
               

                $bills_list = chop($bills_list, ',');
                //get bills amounts
                $bills_amount = [];
                $select_monthly_bills_amount = mysqli_query($conn,"SELECT pp.Bill_ID, SUM(ppl.Price * ppl.Quantity) as BillAmount FROM tbl_patient_payments pp, tbl_patient_payment_item_list ppl WHERE pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.Billing_Process_Status = 'billed' AND pp.Bill_ID IN($bills_list) GROUP BY pp.Bill_ID");

                while ($row = mysqli_fetch_assoc($select_monthly_bills_amount)) {
                    $bills_amount[$row['Bill_ID']] = $row['BillAmount'];
                }

                $count = 1;
                $Total_Amount = 0;
                // while( $monthly_bills_row = mysqli_fetch_assoc($select_monthly_bills_list)){
                foreach ($select_monthly_bills_list as $monthly_bills_row) {
                $Bill_ID = $monthly_bills_row['Bill_ID'];
                $Folio_No = $monthly_bills_row['Folio_No'];
                $Patient_Name= $monthly_bills_row['Patient_Name'];
                $Phone_Number = $monthly_bills_row['Phone_Number'];
                $Member_Number = $monthly_bills_row['Member_Number'];
                $Sent_Date = $monthly_bills_row['sent_date'];
                $BillAmount = $bills_amount[$Bill_ID];


                $Folio_Number = $monthly_bills_row['Folio_Number'];
                $Sponsor_ID = $monthly_bills_row['Sponsor_ID'];
                $Registration_ID = $monthly_bills_row['Registration_ID'];
                $Patient_Bill_ID = $monthly_bills_row['Patient_Payment_ID'];
                $Check_In_ID = $monthly_bills_row['Check_In_ID'];
                $Bill_ID = $monthly_bills_row['Bill_ID'];

                $typecode = "";
                $select111 = mysqli_query($conn,"SELECT cd.Admission_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
                                            if(mysqli_num_rows($select111) > 0){
                                                $Billing_Type ='Inpatient Credit';
                                                $typecode ="IN";
                                            }else{
                                                $Billing_Type ='Outpatient Credit';
                                                $typecode = "OUT";

                                            }

                echo "<tr><td>".($count++)."</td><td>".$Folio_No."</td><td>".$Patient_Name."</td><td>".$Phone_Number."</td><td>".$Member_Number."</td><td style='text-align: center;'>".$Sent_Date."</td><td  style='text-align: right;'>".number_format($BillAmount)."</td><td style='text-align: center;'>
                <input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$Folio_Number.",".$Sponsor_ID.",".$Registration_ID.",".$Patient_Bill_ID .",".$Check_In_ID.",".$Bill_ID.")' value='PREVIEW'></td><td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$Registration_ID.",".$Check_In_ID.",".$Bill_ID.",\"".$typecode."\")' value='PREVIEW'>

                </td></tr>";

  $Total_Amount +=$BillAmount;
}

?>
<tr>
  <td style='text-align: center;' colspan="6"><b>Total Amount</b></td>
  <td style='text-align: right;'><?=number_format($Total_Amount);?></td>
</tr>
</table>
        </div>
    </center>

</fieldset>
<script type="text/javascript">
    function Filter_Monthly_Bills(){
        var month = $("#month").val();
        var year = $("#year").val();
		    var sponsor_id = $("#sponsor_id").val();
        if(year.trim() == ''){
            alert("SELECT YEAR FIRST !!!");
        }else if(month.trim() == ''){
            alert("SELECT MONTH FIRST !!!");
        }else{
            $("#bill_display").html("<img src='images/ajax-loader_1.gif' alt='Loading....'>");

            $.ajax({
                url:"filter_monthly_bill_analysis.php",
                type:"post",
                data:{month:month,year:year,sponsor_id:sponsor_id},
                success:function(results){
                    $("#bill_display").html(results);
                }
            });
        }
    }
    function PREVIEW_PDF(){
        var month = $("#month").val();
        var year = $("#year").val();
        var sponsor_id = $("#sponsor_id").val();
        if(year.trim() == ''){
            alert("SELECT YEAR FIRST !!!");
        }else if(month.trim() == ''){
            alert("SELECT MONTH FIRST !!!");
        }else{
            window.open('Preview_Monthly_Bills_Analysis.php?month='+month+'&year='+year+'&sponsor_id='+sponsor_id);
        }
    }
</script>
<script type="text/javascript">
function Preview_Details(Folio_Number,Sponsor_ID,Registration_ID,Patient_Bill_ID,Check_In_ID,Bill_ID){
  
    window.open('eclaimreport.php?Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Patient_Bill_ID='+Patient_Bill_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID, '_blank');
}

function Preview_Case_Notes(Registration_ID,Check_In_ID,Bill_ID,typecode){

  //var winClose=popupwindow('case_note_preview.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID+'&typecode='+typecode, 'e-CLAIM DETAILS', 1200, 500);
window.open('case_note_preview.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID+'&typecode='+typecode,'_blank');
}
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<?php
include("./includes/footer.php");
?>


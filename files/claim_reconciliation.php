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
       <!--
        <a href='Eclaim_Billing_Session_Control.php?New_Bill=New_Bill&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            CREATE NEW BILL
        </a>
        -->
        <a href='monthly_bill_summery.php?Bill_Summery=Bill_Summery&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            BILLS SUMMERY
        </a>

        <?php
    }
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <!--a href='Eclaim_Billing_Session_Control.php?Previous_Bills=True&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            APPROVED BILLS
        </a-->
        <?php
    }
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='billslist.php?Status=PreviousBills&Requisition=RequisitionThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<style>
    button{
        color:#FFFFFF!important;
        height:27px!important;
    }
    table:nth-child(0) tr td{
      font-size: 18px;

    }
    table tr td select{
      width: 150px;
      height: 30px;
      font-size: 15px;
    }
</style>
<br/><br/>

<center>
  <table>
    <tr>
      <td>Claim Year:</td>
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
      <td>Claim Month:</td>
      <td>
        <select name="month" id="month">
            <option value='' selected="selected">Select Month</option>
            <option value='1'>January</option>
            <option value='2'>February</option>
            <option value='3'>March</option>
            <option value='4'>April</option>
            <option value='5'>May</option>
            <option value='6'>June</option>
            <option value='7'>July</option>
            <option value='8'>August</option>
            <option value='9'>September</option>
            <option value='10'>October</option>
            <option value='11'>November</option>
            <option value='12'>December</option>
        </select>
      </td>
      <td>Delivery Status</td>
      <td>
        <select id="status" name="">
          <option value="all">All Claims</option>
          <option value="received">Received Claims</option>
          <option value="not received">Not Received</option>
        </select>
      </td>
      <td> <input type="button" name="" value="FILTER" class="art-button-green" onclick="Show_Claims()"> </td>
    </tr>
  </table>
    <center>
        <fieldset>
            <legend align="right" ><b>Claim Reconciliation</b></legend>

      <fieldset style='overflow-y: scroll; height: 500px;'>
        <div id='Bills_Fieldset_List'>
          <center><table width = '100%' border=0 >
          <tr><td width=3%><b>SN</b></td><td width=5% style="text-align: left;"><b>Bill No</b></td>
              <td width=4%><b>Folio No.</b></td>
              <td width=8% style="text-align: left;"><b>Patient Number </b></td>
              <td width=15% style="text-align: left;"><b>Patient Name </b></td>
              <td width=7% style="text-align: left;"><b>Phone Number </b></td>
              <td width=6% style="text-align: left;"><b>Patient Type </b></td>
              <td width=7% style="text-align: left;"><b>Attendence Date</b></td>
              <td width=7% style="text-align: left;"><b>Created Date</b></td>
              <td width=7% style="text-align: right;"><b>Total Amount</b></td>

              <td width=9% style="text-align: center;"><b>Bill Status</b></td>
              <td width=9% style="text-align: center;"><b>Form 2A&B</b></td>

              <td width=6% style="text-align: center;"><b>Case Notes</b></td>
          </tr>
          <?php
            $select_claims = mysqli_query($conn,"SELECT bl.e_bill_delivery_status,bl.Bill_ID,cfn.FolioNo, bl.Bill_Date_And_Time FROM tbl_bills bl, tbl_claims_form_nhif cfn WHERE bl.Bill_ID = cfn.BillNo AND cfn.ClaimYear = (SELECT YEAR(NOW())) AND cfn.ClaimMonth = (SELECT MONTH(NOW())) ORDER BY cfn.FolioNo ASC ");

            $count = 1;
            $total_amount=0;
            $total_display=0;
            while ($row = mysqli_fetch_assoc($select_claims)) {

              $Bill_ID = $row['Bill_ID'];
				$FolioNo = $row['FolioNo'];

              //find the attendence date
              $patient_visit_date = mysqli_fetch_assoc(mysqli_query($conn,"SELECT visit_date FROM tbl_check_in WHERE Check_In_ID = (SELECT DISTINCT Check_In_ID FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID') "))['visit_date'];
				

              //calculate bill grand total
              $get_Total = mysqli_query($conn,"select sum((price - discount)*quantity) as Bill_Amount,pp.Folio_Number,pp.Check_In_ID,pp.Patient_Bill_ID,pp.Registration_ID,pp.Billing_Type,pr.Patient_Name, pr.Phone_Number, pp.Sponsor_ID from  tbl_patient_payments pp, tbl_patient_payment_item_list ppl,tbl_patient_registration pr where   pp.Patient_Payment_ID = ppl.Patient_Payment_ID and   pp.Registration_ID=pr.Registration_ID and   pp.Bill_ID = '$Bill_ID'") or die(mysqli_error($conn));
              $num_total = mysqli_num_rows($get_Total);

              if ($num_total > 0) {
                  while ($data = mysqli_fetch_array($get_Total)) {
                      $Sponsor_ID = $data['Sponsor_ID'];
                      $Bill_Amount = $data['Bill_Amount'];
                      $Folio_Number = $data['Folio_Number'];
                      $Patient_Bill_ID = $data['Patient_Bill_ID'];
                      $Registration_ID= $data['Registration_ID'];
                      $Patient_Name= $data['Patient_Name'];
                      $Phone_Number= $data['Phone_Number'];
                      $Billing_Type= $data['Billing_Type'];
                      $Check_In_ID= $data['Check_In_ID'];
                  }
                  $typecode = "";
                $Billing_Type = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Billing_Type FROM tbl_patient_payments WHERE Bill_ID = '$Bill_ID' AND Billing_Type ='Inpatient Credit' ORDER BY patient_payment_id DESC LIMIT 1"))['Billing_Type'];
                $select111 = mysqli_query($conn,"SELECT cd.Admission_ID FROM `tbl_admission` ad, tbl_check_in_details cd WHERE cd.Admission_ID = ad.Admision_ID AND cd.Registration_ID = $Registration_ID AND cd.Check_In_ID = '$Check_In_ID'");
                  if(mysqli_num_rows($select111) > 0){
                      $Billing_Type ='Inpatient Credit';
                      $typecode ="IN";
                  }else{
                      $Billing_Type ='Outpatient Credit';
                      $typecode = "OUT";

                  }
              } else {
                  $Bill_Amount = 0;
                  $Patient_Bill_ID = 0;
                  $Folio_Number = 0;
                  $Registration_ID= 0;
              }


              echo "<tr>";
              echo "<td>" . ($count++) . "</td>";
              echo "<td>" . $Bill_ID . "</td>";
              echo "<td>" . $FolioNo . "</td>";
              echo "<td>" . $Registration_ID. "</td>";
              echo "<td>" . $Patient_Name . "</td>";
              echo "<td>" . $Phone_Number . "</td>";
              echo "<td>" . explode(' ',$Billing_Type)[0] . "</td>";
              echo "<td>" . $patient_visit_date . "</td>";
              echo "<td>" . $row['Bill_Date_And_Time'] . "</td>";
              echo "<td style='text-align: right;'>" . number_format($Bill_Amount) . "</td>";

              //echo "<td style='text-align: center;'><a href='#' class='art-button-green'>Preview Bill</a></td>";
              echo "<td style='text-align: center;background-color:white;color:green'><b>Sent</b></td>";
              echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Details(".$Folio_Number.",".$Sponsor_ID.",".$Registration_ID.",".$Patient_Bill_ID .",".$Check_In_ID.",".$Bill_ID.")' value='PREVIEW'></td>";

              echo "<td style='text-align: center;'><input type='button' class='art-button-green' style='text-align: center;' onclick='Preview_Case_Notes(".$Registration_ID.",".$Check_In_ID.",".$Bill_ID.",\"".$typecode."\")' value='PREVIEW'></td>";
              echo "</tr>";
              if($invoice_created == 'no'){
                  $total_amount +=$Bill_Amount;
              }
                  $total_display +=$Bill_Amount;
            }
          ?>
          </table>
          </center>
        </div>
      </fieldset>
			 </fieldset>
    </center>


</center>

<?php
include("./includes/footer.php");
?>

<script type="text/javascript">
	
	var isPaused = false;
  function Show_Claims(){
    var month = $("#month").val();
    var status = $("#status").val();
    var year = $("#year").val();

    if(year=='' || month == ''){
      alert("SELECT YEAR AND MONTH");
      return false;
    }
	  
	  
	  $.ajax({
      url:"claims_from_nhif.php",
      type:"post",
      beforeSend:function(){
        $("#Bills_Fieldset_List").html("<img src='images/ajax-loader_1.gif' lat='Loading....'>");
      },
      data:{month:month, year:year},
      success:function(results){
        //$("#Bills_Fieldset_List").html(results);
		  isPaused = true;
      },
		complete:function(){
 			Refresh_Claims(month, status, year);	
		}
    });
	  
	  
  }

  function Refresh_Claims(month,status,year){
    
	  $.ajax({
      url:"find_nhif_claims.php",
      type:"post",
      beforeSend:function(){
        $("#Bills_Fieldset_List").html("<img src='images/ajax-loader_1.gif' lat='Loading....'>");
      },
      data:{month:month, status:status, year:year},
      success:function(results){
        $("#Bills_Fieldset_List").html(results);
      }
    });
  }

  function Preview_Case_Notes(Registration_ID,Check_In_ID,Bill_ID,typecode){
	window.open("saved_case_notes.php?Bill_ID="+Bill_ID,'_blank');
    /*window.open('case_note_preview.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID+'&typecode='+typecode,'_blank');*/
  }
 function Preview_Details(Folio_Number,Sponsor_ID,Registration_ID,Patient_Bill_ID,Check_In_ID,Bill_ID){
window.open("saved_claim_file.php?Bill_ID="+Bill_ID,'_blank');
		/*window.open('eclaimreport.php?Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Patient_Bill_ID='+Patient_Bill_ID+'&Check_In_ID='+Check_In_ID+'&Bill_ID='+Bill_ID, '_blank');*/
    }


</script>

<script type="text/javascript">
	$(document).ready(function(){
		
		var currentTime = new Date();
		
		var month = currentTime.getMonth() + 1;
		
		var year = currentTime.getFullYear();
		
		var status = 'all';
	  
	  $.ajax({
      url:"claims_from_nhif.php",
      type:"post",
      beforeSend:function(){
        $("#Bills_Fieldset_List").html("<img src='images/ajax-loader_1.gif' lat='Loading....'>");
      },
      data:{month:month, year:year},
      success:function(results){
        //$("#Bills_Fieldset_List").html(results);
      },
		complete:function(){
 			Refresh_Claims(month, status, year);	
		}
    });
	  
	});	
	
	    function resendBill(BID,Sponsor_ID,Folio_Number,Patient_Bill_ID,Registration_ID,btn) {
        // $("#"+$(btn).attr('id')).hide();
        var Bill_ID = BID;
        var Sponsor_ID = Sponsor_ID;
        var Folio_Number = Folio_Number;
        var Patient_Bill_ID = Patient_Bill_ID;
        var Registration_ID = Registration_ID;

        $.ajax({
            type: "GET",
            url: "createXMLBill.php",
            dataType: "json",
            data: {Bill_ID,Sponsor_ID,Folio_Number,Patient_Bill_ID,Registration_ID},
            beforeSend: function (xhr) {
                alertToastr('', 'Sending bill. please wait..... ', 'info', '', false);
            },
            success: function (data) {
                console.log(data);
                if (data.StatusCode === 200) {
                    alertToastr('SUCCESS', 'Patient bill sent successifully.... ', 'success', '', false);
                    $(btn).hide();
                    $('#td_' + Bill_ID).html('<b>Sent</b>').css('background-color', 'white').css('color', 'green');

                } else {
                    alertToastr('ERROR', data.Message, 'error', '', false);
                }
            }

            // $('#td_'+Bill_ID).html('<b>Sent</b>');
        });

    }
</script>

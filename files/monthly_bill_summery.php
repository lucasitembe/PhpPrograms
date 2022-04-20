<?php
include("./includes/connection.php");
include("./includes/header.php");
session_start();
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
$current_date = date('d', strtotime(date("Y-m-d")));
$current_month = date('F', strtotime(date("Y-m-d")));
$current_year = date("Y");
?>
<style>
    .rows_list{ 
        cursor: pointer; 
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
</style>

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

$conmpinfo = mysqli_query($conn, "SELECT * FROM tbl_system_configuration") or die(mysqli_error($conn));
if(mysqli_num_rows($conmpinfo)>0){
    while($row = mysqli_fetch_assoc($conmpinfo)){
        $Hospital_Name = $row['Hospital_Name'];
        $Box_Address = $row['Box_Address'];
        $Cell_Phone = $row['Cell_Phone'];
        $facility_code =$row['facility_code'];
        $Fax = $row['Fax'];
        $Tin = $row['Tin'];
    }
}else{
    $Hospital_Name = 'Not Set';
    $Box_Address = 'Not Set';
    $Cell_Phone = 'Not Set';
    $facility_code ='Not Set';
    $Fax = 'Not Set';
    $Tin= 'Not Set';
}
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$conmpinfo = mysqli_query($conn, "SELECT Employee_Name,Employee_Title,e.Phone_Number,employee_signature, trans_datetime,invoice_date, invoice_month, invoice_year,amount, Guarantor_Name FROM tbl_invoice i, tbl_employee e, tbl_sponsor s WHERE s.sponsor_id=i.Sponsor_ID AND  i.employee_id=e.Employee_ID AND i.Employee_ID='$Employee_ID' ") or die(mysqli_error($conn));
if(mysqli_num_rows($conmpinfo)>0){
    while($row = mysqli_fetch_assoc($conmpinfo)){
        $invoice_month = $row['invoice_month'];
        $invoice_year = $row['invoice_year'];
        $amount = $row['amount'];
        $trans_datetime =$row['trans_datetime'];
        $invoice_date = $row['invoice_date'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Employee_Name =$row['Employee_Name'];
        $Employee_Title = $row['Employee_Title'];
        $Phone_Number = $row['Phone_Number'];
        $employee_signature = $row['employee_signature'];

        if($employee_signature==""||$employee_signature==null){
            $signature="________________________";
        }else{
            $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
        }
    }
}else{
    $invoice_month = 'Not Set';
    $invoice_year = 'Not Set';
    $amount = 'Not Set';
    $trans_datetime ='Not Set';
    $invoice_date = 'Not Set';
    $Guarantor_Name= 'Not Set';
    $Employee_Title ='Not Set';
    $Phone_Number ='Not Set';
}

        $resultsamount = mysqli_query($conn,"SELECT   COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM  tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci, tbl_bills b WHERE  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID and ppl.Status<>'removed' AND ci.Check_In_ID = pp.Check_In_ID AND MONTHNAME(ci.Visit_Date) = '$current_month'  AND YEAR(ci.Visit_Date) = '$current_year' AND b.e_bill_delivery_status = 1 AND  pp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') AND pp.Bill_ID IS NOT NULL ") or die(mysqli_error($conn));
        $patientTotal_Amount=0;
        if(mysqli_num_rows($resultsamount)>0){
            while($row = mysqli_fetch_assoc($resultsamount)){
                $patientTotal_Amount =$row['Total_Amount'];
            }
        }
        $male=0;
        $female=0;
       
        
            
        $foliosentfemale = mysqli_query($conn, "SELECT COUNT(cf.Registration_ID) AS female FROM tbl_claim_folio cf, tbl_bills b, tbl_patient_registration pr  WHERE cf.Bill_ID=b.Bill_ID AND e_bill_delivery_status =1 AND MONTHNAME(Bill_Date)='$current_month' AND claim_year='$current_year' AND cf.Registration_ID=pr.Registration_ID AND Gender ='Female' ") or die(mysqli_error($conn));
        if(mysqli_num_rows($foliosentfemale)>0){
            while($rw = mysqli_fetch_assoc($foliosentfemale)){
                $female = $rw['female'];
            }
        }else{
            $female=0;
        }
        
        $foliosentmale = mysqli_query($conn, "SELECT COUNT(cf.Registration_ID) AS male FROM tbl_claim_folio cf, tbl_bills b, tbl_patient_registration pr  WHERE cf.Bill_ID=b.Bill_ID AND e_bill_delivery_status =1 AND MONTHNAME(Bill_Date)='$current_month' AND claim_year='$current_year' AND cf.Registration_ID=pr.Registration_ID AND Gender ='Male' ") or die(mysqli_error($conn));
        if(mysqli_num_rows($foliosentmale)>0){
            while($rw = mysqli_fetch_assoc($foliosentmale)){
                $male = $rw['male'];
            }
        }else{
            $male=0;
        }
        $total = $female + $male;
        $foliosent = mysqli_query($conn, "SELECT COUNT(Folio_No) AS TotalFolio FROM tbl_claim_folio cf, tbl_bills b WHERE cf.Bill_ID=b.Bill_ID AND e_bill_delivery_status =1 AND MONTHNAME(Bill_Date)='$current_month' AND claim_year='$current_year' ") or die(mysqli_error($conn));
        if(mysqli_num_rows($foliosent)>0){
            while($rw = mysqli_fetch_assoc($foliosent)){
                $TotalFolio = $rw['TotalFolio'];
            }
        }else{
            $TotalFolio=0;
        }
?>

<fieldset>
    <legend>BILLS SUMMERY AND REPORTS</legend>
    <center>
        <table >
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
                        <select name="month" id="month" onchange="Filter_Monthly_Bills()">
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
					<td>Select Sponsor</td>
					<td>
						<select name='sponsor' id="sponsor_id" onchange="Filter_Monthly_Bills()">
                            <option value="All">All NHIF's</option>
						<?php
                        $select_sponsor = mysqli_query($conn,"SELECT Sponsor_ID, Guarantor_Name FROM tbl_sponsor WHERE auto_item_update_api = 1") or die(mysqli_error($conn));
                        if(mysqli_num_rows($select_sponsor)>0){
                        while($row = mysqli_fetch_assoc($select_sponsor)){
                                echo "<option value='".$row['Sponsor_ID']."'>".$row['Guarantor_Name']."</option>";
                            }
                        }else{
                            echo "<option>no result found</option>";
                        }
						?>
						
						</select></td>
                    <td><input type="button" name="btn-filter" value="FILTER" class="art-button-green"  onclick="Filter_Monthly_Bills();"></td>
                    <td><input type="button" name="btn-filter" value="PREVIEW PDF" class="art-button-green"  onclick="PREVIEW_PDF();"></td>
					<td style='background-color:#fff;font-size:18px;'><input type="button" name="btn-filter" value="CREATE INVOICE" class="art-button-green"  onclick="Open_Dialog();"><span ><b>Invoice No:000001</b></span></td>
                </tr>
            </thead>
            
        </table><div id="progressbar"></div>
        <br>
        <div style="width:80%; background-color: #fff; overflow-y: auto;" id="bill_display">
            <p> <h3>eCLAIM MONTHLY REPORT</h3> </p>
            
            <table  style="width: 80%;font-size: 15px;">
                <thead>
                <tr>
                        <td >ACCREDITATION NUMBER</td>
                        <td ><?=$Fax ?></td>
                    </tr>
                    <tr>
                        <td >NAME OF FACILITY</td>
                        <td ><b><?=$Hospital_Name ?></b></td>
                    </tr>
                    <tr>
                        <td >ADDRESS: </td>
                        <td ><b><?=$Box_Address ?></b></td>
                    </tr>
                    <tr>
                        <td  colspan="2">REGION ............. DISTRICT ................</td>
                    </tr>
                    <tr>
                        <td >NUMBER OF BENEFITIERIES TREATED</td>
                        <td >
                            Male  <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo $male; ?>">
                            Female <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo  $female ?>">
                            Total <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo  $total ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>NUMBER OF FOLIO</td>
                        <td><input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo  number_format($TotalFolio); ?>"></td>
                    </tr>
                    <tr>
                        <td >DATE OF TREATEMENT</td>
                        <td >
                            FROM <input type="text" value="<?php echo '01-'.$current_month.'-'.$current_year; ?>" style="width: 20%; display:inline; text-align:center;" readonly> 
                            TO  <input type="text" value="<?php echo $current_date.'-'.$current_month.'-'.$current_year; ?>" style="width: 20%; display:inline; text-align:center;" readonly></td>
                    </tr>
                    <tr>
                        <td >AMOUNT CLAIMED: TZsh</td>
                        <td >
                             <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo number_format($patientTotal_Amount)."/="; ?>">
                        </td>
                    </tr>
                </thead>
            </table>
            <table style="width: 80%;font-size: 18px;">
	            <tr><td colspan='4'><b>BREAK DOWN OF AMOUNT CLAIMED</b></td></tr>
                <tr>
                    <th>SN</th>
                    <th>Category</th>
                    <th style='text-align: center;'>Quantity</th>
                    <th  style='text-align: right;'>Amount</th>
                </tr>
            <?php 
                $count =1;
			
               $results = mysqli_query($conn,"SELECT i.Item_ID, ic.Item_Category_Name, COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i, tbl_item_subcategory isub, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci, tbl_bills b, tbl_item_category ic WHERE i.Item_Subcategory_ID = isub.Item_Subcategory_ID AND ic.Item_Category_ID = isub.Item_Category_ID AND i.Item_ID = ppl.Item_ID and ppl.Status<>'removed' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID AND ci.Check_In_ID = pp.Check_In_ID AND MONTHNAME(ci.Visit_Date) = '$current_month' AND YEAR(ci.Visit_Date) = '$current_year'  AND b.e_bill_delivery_status = 1 AND  pp.Billing_Type IN ( 'Outpatient Credit', 'Inpatient Credit') AND pp.Bill_ID IS NOT NULL GROUP BY ic.Item_Category_ID") or die(mysqli_error($conn));

               $OutpatientTotal_Amount = 0;
               while ($row = mysqli_fetch_assoc($results)) {

                   echo "<tr><td>".$count."</td><td>".$row['Item_Category_Name']."</td><td style='text-align: center;'>".$row['Quantity']."</td><td style='text-align: right;'>".number_format($row['Total_Amount'])."</td></tr>";
                   $count++;
                   $OutpatientTotal_Amount +=$row['Total_Amount'];
				   
               }


            ?>
            <tr>
                <td colspan="3"  style='text-align:right;'><b>Total Amount:</b></td>
                <td style='text-align: right;'><b><?=number_format($OutpatientTotal_Amount);?></b></td>
            </tr>
            </table>
<!-- <table style="width: 80%;font-size: 18px;">
	<tr><td colspan='4'><b>InPatient</b></td></tr>
                <tr>
                    <th>SN</th>
                    <th>Category</th>
                    <th style='text-align: center;'>Quantity</th>
                    <th  style='text-align: right;'>Amount</th>
                </tr> -->
            <?php 
            //     $count =1;
            //    $results = mysqli_query($conn,"SELECT i.Item_ID, ic.Item_Category_Name, COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i, tbl_item_subcategory isub, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci, tbl_bills b, tbl_item_category ic WHERE i.Item_Subcategory_ID = isub.Item_Subcategory_ID AND ic.Item_Category_ID = isub.Item_Category_ID AND i.Item_ID = ppl.Item_ID and ppl.Status<>'removed' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID AND ci.Check_In_ID = pp.Check_In_ID AND MONTHNAME(ci.Visit_Date) = '$current_month' AND YEAR(ci.Visit_Date) = '$current_year' AND b.e_bill_delivery_status = 1 AND  pp.Billing_Type = 'Inpatient Credit' AND pp.Bill_ID IS NOT NULL GROUP BY ic.Item_Category_ID") or die(mysqli_error($conn));
              
            //    $InpatientTotal_Amount = 0;
            //    while ($row = mysqli_fetch_assoc($results)) {

            //        echo "<tr><td>".$count."</td><td>".$row['Item_Category_Name']."</td><td style='text-align: center;'>".$row['Quantity']."</td><td style='text-align: right;'>".number_format($row['Total_Amount'])."</td></tr>";
            //        $count++;
            //        $InpatientTotal_Amount +=$row['Total_Amount'];
				   
            //    }


            ?>
            <!-- <tr>
                <td colspan="3"  style='text-align:right;'><b>Total Amount:</b></td>
                <td style='text-align: right;'><b><?=number_format($InpatientTotal_Amount);?></b></td>
            </tr> -->
	<tr><td colspan='4'><b><hr></b></td></tr>
	<tr><td colspan='3' style='text-align:right;'><b>Grand Total:</b></td><td style='text-align: right;'><b><?=number_format($OutpatientTotal_Amount);?></b></td></tr>
	<tr><td colspan='4'><b><hr></b></td></tr>
</table>
            <table style="width: 80%;font-size: 15px;">
                <tr>
                    <th>NAME  </th>
                    <th><?=$Employee_Name ?></th>
                
                    <th>DESIGNATION</th>
                    <th><?=$Employee_Title ?></th>
                </tr>
                <tr>
                    <th>CONTACT: </th>
                    <th><?=$Phone_Number ?></th>
               
                    <th colspan="">DATE </th>
                    <th><?php echo $invoice_date; ?></th>
                </tr>
                <tr>
                    <th colspan="3">Signature:   <?php echo $signature; ?></th>
                    <th rowspan="3"width='20%'>
                    <img src='images/stamp.jpeg' width='120' height='120' style='float:left;'>
                    </th>
                </tr>
               
            </table>
			
        </div>
    </center>
	<div id='narration' style='display:none;'>
		<textarea style='height:150px;' id='narration_note' placeholder='Write Narration for the Invoice'></textarea>
		<br>
		&emsp;
		<br>
	<center>
		<input type='button' class='art-button-green' value='Cancel' onclick="Close_Dialog('close');">
		<input type='button' class='art-button-green' value='Save' onclick="Save_Narration();">
	</center>
	</div>
</fieldset>
<div id="categoryitem"></div>
<script type="text/javascript">
    function getCategoryitems(Item_Category_ID){
        var month = $("#month").val();
        var year = $("#year").val();
		var sponsor_id = $("#sponsor_id").val();
        var Product_name = $("#Product_name").val();
      document.getElementById('progressbar').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        $.ajax({
            url:"filter_monthly_bill.php",
            type:"post",
            data:{month:month,year:year,sponsor_id:sponsor_id, Item_Category_ID:Item_Category_ID},
            cache:false,
            success:function(responce){
                document.getElementById('progressbar').innerHTML = "";

                $("#categoryitem").dialog({
                    title: 'SERVICE GIVEN UNDER '+Product_name,
                    width: '90%',
                    height: 850,
                    modal: true,
                });
                $("#categoryitem").html(responce);
            }
        });
    }

    function previewBycategory(Item_Category_ID){
        var month = $("#month").val();
        var year = $("#year").val();
		var sponsor_id = $("#sponsor_id").val();
        var Product_name = $("#Product_name").val();
       window.open('filter_monthly_Billpdf.php?month='+month+'&year='+year+'&Product_name='+Product_name+'&sponsor_id='+sponsor_id+'&Item_Category_ID='+Item_Category_ID);  
       
    }

    function Filter_Monthly_Bills(){
        var month = $("#month").val();
        var year = $("#year").val();
		var sponsor_id = $("#sponsor_id").val();
        if(year.trim() == ''){
            alert("SELECT YEAR FIRST !!!");
        }else if(month.trim() == ''){
            alert("SELECT MONTH FIRST !!!");
        }else{
      document.getElementById('progressbar').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            $.ajax({
                url:"filter_monthly_bill.php", 
                type:"post",
                data:{month:month,year:year,sponsor_id:sponsor_id, filterreport:''},
                cache:false,
                success:function(results){
                     document.getElementById('progressbar').innerHTML = "";

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
            window.open('Preview_Monthly_Bills.php?month='+month+'&year='+year+'&sponsor_id='+sponsor_id);
        }
    }
	
	function Create_Invoice(){
        var month = $("#month").val();
        var year = $("#year").val();
        var sponsor_id = $("#sponsor_id").val();
        var narration = $("#narration_note").val();
		var Employee_ID     = "<?=$_SESSION['userinfo']['Employee_ID']?>";
        if(year.trim() == ''){
            alert("SELECT YEAR FIRST !!!");
        }else if(month.trim() == ''){
            alert("SELECT MONTH FIRST !!!");
        }else{
		var narration = $("#narration_note").val();
		
		$.ajax({
			url:'create_monthly_sponsor_invoice.php',
			type:'post',
			data:		{month:month,year:year,sponsor_id:sponsor_id,narration:narration,Employee_ID:Employee_ID},
            cache:false,
			success:function(results){
				alert(results);
			}
		});
        }
    }
	
	function Open_Dialog(){
		$("#narration").dialog('open');
	}
	function Close_Dialog(){
		$("#narration").dialog('close');
		$("#narration_note").val('');
	}
	function Save_Narration(){
		$("#narration").dialog('close');
		Create_Invoice();
	}
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
	$(document).ready(function () {
        $("#narration").dialog({autoOpen: false, width: '50%',height:'300', title: 'CREATE INVOICE', modal: true, position: 'middle'});

    });
</script>
<?php
include("./includes/footer.php");
?>





<!-- 1400238939 kantga -->

<!-- 37142389396 -->
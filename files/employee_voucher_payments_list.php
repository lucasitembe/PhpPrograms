<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
?>




<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
	
    <a href='employee_make_payments.php?employee=<?=$_GET['employee']?>' class='art-button-green'>
        BACK
    </a>
<?php  } }
	$payee_ID=mysqli_real_escape_string($conn,$_GET['employee']);
	$Employee_Name=$_SESSION['userinfo']['Employee_Name'];
	$select_supplier=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE  employee_ID=$payee_ID");
	if(mysqli_num_rows($select_supplier) > 0){
		while($row=mysqli_fetch_assoc($select_supplier)){
			$payee_name	= $row['Employee_Name'];
			$payee_ID	= $row['Employee_ID'];
			 $Email			= "";
			//$Address		= $row['Postal_Address'];
			$Telephone		= $row['Phone_Number'];
		}
	}else{
			$payee_name	= "";
			$payee_ID	= "";
			$Email		= "";
			$Address	= "";
			$Telephone	= "";
	}
 ?>
	
<fieldset>  
    <legend align="right"><b>VOUCHER PAYMENTS LIST: REVENUE CENTER</b></legend>
    <div style='background-color:#fff;'>
    <center> 
    	<div>
            <table width='60%'>
                <tr><td style='text-align:right;'><b>Start Date:</b></td><td><input type='text' name='Start_Date' id='date' required='required' style='text-align: center;' placeholder='Start Date' readonly='readonly'</td><td style='text-align:right;'><b>End Date: </b></td><td><input type='text' name='End_Date' id='date2' required='required' style='text-align: center;' placeholder='End Date' readonly='readonly'></td><td><input type='submit' name='filter' value='FILTER' class='art-button-green' onclick="Filter_Ledger_Details();"></td></tr>
            </table>
        </div>
    	<table width='80%'>
    		<tr style='background-color:gray;color:#fff;'><th>SN</th><th>EMPLOYEE NAME</th><th>VOUCHER NUMBER</th><th>PHONE NUMBER</th></tr>
    		<?php
    			$select_list=mysqli_query($conn,"SELECT * FROM tbl_voucher v ,tbl_employee e WHERE payee_type='employee' AND e.Employee_ID=v.Supplier_ID");
    			$count=1;
    			while ($row=mysqli_fetch_assoc($select_list)) {
    				extract($row);
    				$Search='no';
    				echo "<tr><td>".$count."</td><td>".$Employee_Name."</td><td style='cursor:pointer;' onclick='Display_Voucher_Details(\"{$voucher_ID}\",\"$Search\");'>".$voucher_ID."</td><td>".$Phone_Number."</td></tr>";
    				$count++;
    			}
    		?>
    	</table>
    </center>
	</div>
</fieldset>

<div id="show_voucher" style='background-color:#fff;font-size:180x;'></div>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript">
	function Display_Voucher_Details(voucher_ID,Search){
		$.ajax({
			url:'display_payment_voucher.php',
			type:'post',
			data:{voucher_ID:voucher_ID,Search:Search},
			success:function(results){
				$('#show_voucher').html(results);
			}
		});
        $("#show_voucher").dialog('open');
	}
    function Preview_Voucher(voucher_ID){
        window.open('preview_voucher.php?Voucher_ID='+voucher_ID+'&From=employee_payments');
    }
</script>
<script type="text/javascript">
$(document).ready(function () {
        $("#show_voucher").dialog({autoOpen: false, width: '70%',height:'500', title: 'Voucher Details', modal: true, position: 'middle'});
    });
</script>
<?php
    include("./includes/footer.php");
?>
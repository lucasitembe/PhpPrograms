<?php
	session_start();
	include("./includes/connection.php");
	$filter1=' ';
	$filter2=' ';
	if(isset($_GET['Customer_Name'])){
		$Customer_Name = $_GET['Customer_Name'];
	}else{
		$Customer_Name = '';
	}
	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}
	if($Customer_Name !==''){
		$filter1=" Patient_Name LIKE '%$Customer_Name%' AND ";
		$filter2=" WHERE Guarantor_Name LIKE '%$Customer_Name%'";
	}
	if($Registration_ID !==''){
		$filter1=" Registration_ID LIKE '%$Registration_ID%' AND ";
		$filter2=" WHERE Sponsor_ID LIKE '%$Registration_ID%'";
	}
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
    $Title = '<tr><td colspan="6"><hr></tr>
				<tr>
		        	<td width="5%"><b>SN</b></td>
		            <td width="25%"><b>CUSTOMER NAME</b></td>
		            <td width="10%"><b>CUSTOMER NUMBER</b></td>
		            <td width="15%"><b>PHONE NUMBER</b></td>
		            <td width="15%"><b>EMAIL ADDRESS</b></td>
		            <td width="8%"><b>CUSTOMER TYPE</b></td>
		        </tr>
		        <tr><td colspan="6"><hr></tr>';
?>
<legend align="right"><b>CUSTOMER BILLING LIST: REVENUE CENTER</b></legend>
		<table width = "100%">
	       <?php
	       		echo $Title; $m_records = 0; $Terminator = 0;
					$select=mysqli_query($conn,"SELECT sp.Sponsor_ID AS REGNO, sp.Guarantor_Name AS CUSTOMER_NAME, sp.Phone_Number AS PHONE , ' ' AS ADDRESS, 'SPONSOR' AS CUSTOMER_TYPE FROM tbl_sponsor sp $filter2 LIMIT 20 UNION ALL SELECT pr.Registration_ID AS REGNO, pr.Patient_Name AS CUSTOMER_NAME, pr.Phone_Number AS PHONE ,pr.Email_Address AS ADDRESS, 'CUSTOMER' AS CUSTOMER_TYPE FROM tbl_patient_registration pr WHERE $filter1 pr.registration_type='customer' LIMIT 20") or die(mysqli_error($conn)); 
			
	       		$nm = mysqli_num_rows($select);
	       		if($nm > 0){
	       			$temp = 0;
	       			while ($data = mysqli_fetch_array($select)){
	       				?>
		       				<tr>
					        	<td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ++$temp; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($data['CUSTOMER_NAME'])); ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['REGNO']; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['PHONE']; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['ADDRESS']; ?></a></td>
					            <td><a href='customerbillingdirectcash.php?CUSTOMERNO=<?php echo $data['REGNO'].'&CUSTOMER_TYPE='.$data['CUSTOMER_TYPE']; ?>&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['CUSTOMER_TYPE']; ?></a></td>
					        </tr>
	       				<?php
	       					if(($temp%21) == 0){ echo $Title; }
	       			}
	       		}

	       
	       ?>

        	<!--<td id='Search_Iframe'>
				 <iframe width='100%' height=380px src='search_list_direct_cash_patient_billing_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe> 
        	</td>-->
    	</tr>
	</table>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $branchID= '';
    $Date_From = '';
    $Date_To = '';
    //start authenticaton and authorization
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    } 
?>

<script type='text/javascript'>
    /*function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }*/
</script>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
?>
    <a href='./financialReports.php?FinanceWork=FinanceWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 
<!-- get current date-->
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
	$original_Date = $row['today'];
	$new_Date = date("Y-m-d", strtotime($original_Date));
	$Today = $new_Date; 
    }
?>

<!-- get employee details-->
<?php
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID']; 
    }else{
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
    
    $select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where employee_id = '$Employee_ID'");
    while($row = mysqli_fetch_array($select_Employee_Details)){
	$Employee_Name = $row['Employee_Name'];
	$Employee_ID = $row['Employee_ID'];
    }
?>
<br/><br/>
<center>

<form action='revenueFilter.php' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=80%>
        <tr>
            <td style='text-align: center;color: #002166 '><b>Branch</b></td>
            <td style='text-align: center;'><b>From</b></td>
            <td style='text-align: center;'><b>To</b></td>
            <td style='text-align: center;'><b>Insurance</b></td>
            <!--<td><b>Patient Type</b></td>
            <td><b>Payment Type</b></td>-->
        </tr>
        <tr>
	    <td>
		    <select name='Branch' id='Branch' required='required'>
                    <option selected='selected' value='0'>All</option>
                    <?php
                        $data = mysqli_query($conn,"select * from tbl_branches");
                        while($row = mysqli_fetch_array($data)){
			    $branchID=$row['Branch_ID'];
			    $branchName=$row['Branch_Name'];
                            echo "<option value='$branchID'>".$row['Branch_Name']."</option>";
                        }
                    ?>
                </select>
	    </td>
            <td><input type='text' name='Date_From' id='date_From' required='required'></td> 
            <td><input type='text' name='Date_To' id='date_To' required='required'></td>
            <td style='text-align: center;'>
                <select name='Sponsor_ID' id='Sponsor_ID'>
                    <option selected='selected' value='0'>All</option>
                    <?php
                        $data = mysqli_query($conn,"select * from tbl_sponsor");
                        while($row = mysqli_fetch_array($data)){
			    $gID=$row['Sponsor_ID'];
			    $gName=$row['Guarantor_Name'];
                            echo "<option value='$gID'>".$row['Guarantor_Name']."</option>";
                        }
                    ?>
                </select>
            </td>
            <!--<td style='text-align: center;'>
                <select name='Patient_Type' id='Patient_Type'>
                    <option selected='selected'>All</option>
                    <option>Outpatient</option>
                    <option>Inpatient</option>
                </select>
            </td> 
            <td style='text-align: center;'>
                <select name='Payment_Type' id='Payment_Type'>
                    <option selected='selected'>All</option>
                    <option>Cash</option>
                    <option>Credit</option>
                </select>
            </td>-->
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
	    <?php if(isset($_POST['Print_Filter'])){ ?>
	    <td style='text-align: center;'>
                <a href='previewrevenuecollectionbycategory.php?Branch=<?php echo $Branch; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Insurance=<?php echo $Insurance; ?>&Payment_Type=<?php echo $Payment_Type; ?>&Patient_Type=<?php echo $Patient_Type; ?>&PreviewRevenueCollectionByFolio=PreviewRevenueCollectionByFolioThisPage' class='art-button-green' target='_blank'>PREVIEW DETAILS</a>
            </td>
	    <?php } ?>
        </tr>
    </table>
    
</center>
</form>
	<script src="css/jquery.js"></script>
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
	</script>
	<!--End datetimepicker-->
	<table width=100%>
        <tr>
            <td style='text-align: center;' width="100%">
		<?php
		    //get posted dates
		    if(isset($_POST['Print_Filter'])){//if the submit button is clicked
			 $Branch= $_POST['Branch']."<br>";
			 $Date_From =$_POST['Date_From']."<br>";
			 $Date_To= $_POST['Date_To']."<br>";
			 $sponsorID= $_POST['Sponsor_ID']."<br>";
		    }
		    //select  employee details due according to session
		    if(isset($_SESSION['userinfo']['Employee_ID'])){
			$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
			$select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where employee_id = '$Employee_ID'");
			while($row = mysqli_fetch_array($select_Employee_Details)){
			    $Employee_Name = $row['Employee_Name'];
			    $Employee_ID = $row['Employee_ID'];
			}
		    }
					 //echo "<b>Logged in: ".$Employee_Name."</b>";		    
		?>
	    </td>
        </tr>        
    </table>
<fieldset>
    
            <legend align=center><b>REVENUE SUMMARY BY CATEGORIES</b></legend>
        <center>
            <?php
		echo '<center><table width =60% border=0>';
    echo "<tr>
                <td width=3%><b>SN</b></td>
                <td style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;CATEGORY</b></td>
                <td style='text-align: right;' width=10%><b>CASH</b></td>
                <td style='text-align: right;' width=10%><b>CREDIT</b></td>
		<td style='text-align: right;' width=10%><b>CANCELLED</b></td>
		<td style='text-align: right;' width=10%><b>TOTAL</b></td>
         </tr>";
    echo "<tr>
                <td colspan=4></td></tr>";
    //run the query to select all data from the database according to the branch id
    $filterRevenue = mysqli_query($conn,"
				     select ic2.Item_Category_ID,ic2.Item_Category_Name,
					(
					select SUM((ppl.Price-ppl.Discount)*quantity) from tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_items it,tbl_item_subcategory isc,tbl_item_category ic WHERE ppl.Item_ID=it.Item_ID AND
					pp.Patient_Payment_ID=ppl.Patient_Payment_ID and
					it.Item_Subcategory_ID=isc.Item_Subcategory_ID AND
					(pp.Billing_Type='Outpatient Cash' or pp.Billing_Type='Inpatient Cash' AND pp.Transaction_status='active') AND
					pp.Receipt_Date='CURDATE()' AND
					ic.Item_Category_ID=isc.Item_Category_ID AND ic.Item_Category_ID=ic2.Item_Category_ID
					) 
					as cash,
					(
					select SUM((ppl.Price-ppl.Discount)*quantity) from tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_items it,tbl_item_subcategory isc,tbl_item_category ic WHERE ppl.Item_ID=it.Item_ID AND
					pp.Patient_Payment_ID=ppl.Patient_Payment_ID and
					it.Item_Subcategory_ID=isc.Item_Subcategory_ID AND
					(pp.Billing_Type='Outpatient Credit' or pp.Billing_Type='Inpatient Credit' AND pp.Transaction_status='active') AND
					pp.Receipt_Date='CURDATE()' AND
					ic.Item_Category_ID=isc.Item_Category_ID AND ic.Item_Category_ID=ic2.Item_Category_ID
					) 
					as credit,
					(
					select SUM((ppl.Price-ppl.Discount)*quantity) from tbl_patient_payments pp,tbl_patient_payment_item_list ppl,tbl_items it,tbl_item_subcategory isc,tbl_item_category ic WHERE ppl.Item_ID=it.Item_ID AND
					pp.Patient_Payment_ID=ppl.Patient_Payment_ID and
					it.Item_Subcategory_ID=isc.Item_Subcategory_ID AND
					(pp.Transaction_status='cancelled') AND
					pp.Receipt_Date='CURDATE()' AND
					ic.Item_Category_ID=isc.Item_Category_ID AND ic.Item_Category_ID=ic2.Item_Category_ID
					)
					as cancelled
					FROM tbl_item_category ic2 GROUP BY ic2.Item_Category_ID	
				 
				 ");
    $Cash_Total=0;
    $Credit_Total=0;
    $Cancelled_Total=0;
    $res=mysqli_num_rows($filterRevenue);
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($filterRevenue);
	//return rows
	$catID=$row['Item_Category_ID'];
	$catName=$row['Item_Category_Name'];
	$cash=$row['cash'];
	$credit=$row['credit'];
	$cancelled=$row['cancelled'];
        echo "<tr><td>".($i+1)."</td>";
        echo "<td><a href='revenueCategory.php?categoryID=$catID&RevenueCategory=RevenueCategoryThisForm' style='text-decoration: none;'>".$row['Item_Category_Name']."</a></td>";
	$Cash_Total=$Cash_Total+$cash;
	echo "<td>".number_format($cash)."</td>";
	$Credit_Total=$Credit_Total+$credit;
	echo "<td>".number_format($credit)."</td>";
	$Cancelled_Total=$Cancelled_Total+$cancelled;
	echo "<td>".number_format($cancelled)."</td>";
	$total=$credit+$cash+$cancelled;
	echo "<td>".number_format($total)."</td>";
    }//end for loop
    echo "<tr><td colspan=2 style='text-align: right;'><b>&nbsp;&nbsp;Total</b></td>";
    echo "<td style='text-align: center;'><b>".number_format($Cash_Total)."</b></td>";
    echo "<td style='text-align: center;'><b>".number_format($Credit_Total)."</b></td>";
     echo "<td style='text-align: center;'><b>".number_format($Cancelled_Total)."</b></td>";
    $total_Credit_Cash_Cancelled=$Cash_Total+$Credit_Total+$Cancelled_Total;
    echo "<td style='text-align: center;'><b>".number_format($total_Credit_Cash_Cancelled)."</b></td>";
	    ?></table></center>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
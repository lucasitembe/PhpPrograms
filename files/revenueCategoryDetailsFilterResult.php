<?php
//get the values from the category
if($_POST['Print_Filter']){
    //echo $catID=$_POST['categoryID']."<br>";
    //echo $Branch=$_POST['Branch']."<br>";
    //echo $Date_From=$_POST['Date_From']."<br>";
    //echo $Date_To=$_POST['Date_To']."<br>";
    //echo $sponsorID=$_POST['Sponsor_ID']."<br>";    
}
?>

<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $branchID= '';
    $Date_From = '';
    $Date_To = '';
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

<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date_From").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
        
        $(function () { 
            $("#date_To").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->

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
    <a href='./revenueCategoryDetails.php?categoryID=<?php echo $_POST['categoryID'];?>FinanceWork=FinanceWorkThisPage' class='art-button-green'>
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
    
    <?php 
    if(isset($_POST['Print_Filter'])){
			 $Branch= $_POST['Branch'];
			 $Date_From =$_POST['Date_From'];
			 $Date_To= $_POST['Date_To'];
			 $sponsorID= $_POST['Sponsor_ID'];
			 $categoryID=$_POST['categoryID'];
    }
?>

<form action='revenueCategoryDetailsFilterResult.php' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=80%>
        <tr>
            <td style='text-align: center;'><b>Branch</b></td>
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
	    <td style="border: 0;">
		<input type="hidden" name="categoryID" value="<?php if(isset($_POST['categoryID'])){echo $categoryID=$_POST['categoryID'];}?>"/>
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
        </tr>
    </table>
</center>
</form>
<!--functions to display date and time for filters-->
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
		    //if isset using POST
			if(isset($_POST['Print_Filter'])){
			 $Branch= $_POST['Branch'];
			 $Date_From =$_POST['Date_From'];
			 $Date_To= $_POST['Date_To'];
			 $sponsorID= $_POST['Sponsor_ID'];
			 $catID=$_POST['categoryID'];
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
		    $query="SELECT * FROM tbl_item_category WHERE Item_Category_ID='$catID'";
		    $result=mysqli_query($conn,$query);
		    if($result){
			$row=mysqli_fetch_array($result);
			echo "<b>Financial Report From ".$Date_From." To ".$Date_To."</b>";
			echo "<br>";
					echo "<b>Logged in: ".$Employee_Name."</b>";
		    }else{
			echo "<b>Financial Report From ".$Date_From." To ".$Date_To."</b>";
		    }
		?>
	    </td>
        </tr>        
    </table>
    <?php
	if(isset($_POST['categoryID'])){
	    $catID=$_POST['categoryID'];
	    
	    //select cat name
	    $query="SELECT * FROM tbl_item_category WHERE Item_Category_ID='$catID'";
	    $result=mysqli_query($conn,$query);
	    if($result){
		$row=mysqli_fetch_array($result);
		$categoryName=$row['Item_Category_Name'];
	    }
	}
    ?>
<fieldset>  
            <legend align=center><b>REVENUE SUMMARY FOR <?php echo "<b style='color:blue;font-size:14px'>".$categoryName."</b>";?> CATEGORY</b></legend>
        <center>
            <?php
		echo '<center><table width =70% border=0>';
    echo "<tr>
                <td width=3%><b>SN</b></td>
		<td width=''><b>PATIENT NAME</b></td>
                <td width=''><b>ITEM NAME</b></td>
		<td width=''><b>QUANTITY</b></td>
		<td width=''><b>DISCOUNT</b></td>
		<td width=''><b>AMOUNT</b></td>
		<td width=''><b>RECEIPT NO</b></td>
		<td width=''><b>BILLING TYPE</b></td>
		<td width=''><b>DATE& TIME</b></td>
         </tr>";
    echo "<tr>
                <td colspan=4></td></tr>";
	if($Branch ==0 && $sponsorID==0){
	    //run the query if both branch and sponsor are set to all
	    $query="  SELECT *
				    FROM tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items it,tbl_sponsor sp,tbl_branches br, tbl_item_subcategory isc, tbl_item_category ic
				    WHERE ppl.Item_ID = it.Item_ID
				    AND it.Item_Subcategory_ID = isc.Item_Subcategory_ID
				    AND isc.Item_Category_ID = ic.Item_Category_ID
				    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
				    AND pp.Registration_ID=pr.Registration_ID
				    AND pr.Sponsor_ID=sp.Sponsor_ID
				    AND pp.Sponsor_ID=sp.Sponsor_ID
				    AND pp.branch_id=br.Branch_ID
				    AND pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
				    AND ic.Item_Category_ID ='$catID'
				 ";
			$filterRevenue = mysqli_query($conn,$query);
	}//end if branch both branch and sponsor are set to all
	elseif($Branch ==0 && $sponsorID!=0){
	    //run the query if branch is all and sponsor is not
	    $query="  SELECT *
				    FROM tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items it,tbl_sponsor sp,tbl_branches br, tbl_item_subcategory isc, tbl_item_category ic
				    WHERE ppl.Item_ID = it.Item_ID
				    AND it.Item_Subcategory_ID = isc.Item_Subcategory_ID
				    AND isc.Item_Category_ID = ic.Item_Category_ID
				    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
				    AND pp.Registration_ID=pr.Registration_ID
				    AND pr.Sponsor_ID=sp.Sponsor_ID
				    AND pp.Sponsor_ID=sp.Sponsor_ID
				    AND pp.branch_id=br.Branch_ID
				    AND pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
				    AND ic.Item_Category_ID ='$catID'
				    AND sp.Sponsor_ID='$sponsorID'
				 ";
			$filterRevenue = mysqli_query($conn,$query);
	}//end ELSEif branch is set to all and sponsor is not set to all
	elseif( $Branch !=0 && $sponsorID ==0 ){
		//run the query if branch is not set to all and sponsor is set to all
			$query="  SELECT *
				    FROM tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items it,tbl_sponsor sp,tbl_branches br, tbl_item_subcategory isc, tbl_item_category ic
				    WHERE ppl.Item_ID = it.Item_ID
				    AND it.Item_Subcategory_ID = isc.Item_Subcategory_ID
				    AND isc.Item_Category_ID = ic.Item_Category_ID
				    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
				    AND pp.Registration_ID=pr.Registration_ID
				    AND pr.Sponsor_ID=sp.Sponsor_ID
				    AND pp.Sponsor_ID=sp.Sponsor_ID
				    AND pp.branch_id=br.Branch_ID
				    AND pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
				    AND ic.Item_Category_ID ='$catID'
				    AND br.Branch_ID='$Branch'
				 ";
			$filterRevenue = mysqli_query($conn,$query);
	}//end if branch is not set to all and sponsor is set to all
	elseif($Branch !=0 && $sponsorID !=0){
	   //run the query if both branch and sponsor are not set to all
	   $query="  SELECT *
				    FROM tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items it,tbl_sponsor sp,tbl_branches br, tbl_item_subcategory isc, tbl_item_category ic
				    WHERE ppl.Item_ID = it.Item_ID
				    AND it.Item_Subcategory_ID = isc.Item_Subcategory_ID
				    AND isc.Item_Category_ID = ic.Item_Category_ID
				    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
				    AND pp.Registration_ID=pr.Registration_ID
				    AND pr.Sponsor_ID=sp.Sponsor_ID
				    AND pp.Sponsor_ID=sp.Sponsor_ID
				    AND pp.branch_id=br.Branch_ID
				    AND pp.Payment_Date_And_Time BETWEEN '$Date_From' AND '$Date_To'
				    AND ic.Item_Category_ID ='$catID'
				    AND br.Branch_ID='$Branch'
				    AND sp.Sponsor_ID='$sponsorID'
				 ";
			$filterRevenue = mysqli_query($conn,$query);
	}//END ELSEIF
	else{
	    //display all data for the selected category for the current date
	    $query="  SELECT *
				    FROM tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items it,tbl_sponsor sp,tbl_branches br, tbl_item_subcategory isc, tbl_item_category ic
				    WHERE ppl.Item_ID = it.Item_ID
				    AND it.Item_Subcategory_ID = isc.Item_Subcategory_ID
				    AND isc.Item_Category_ID = ic.Item_Category_ID
				    AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID
				    AND pp.Registration_ID=pr.Registration_ID
				    AND pr.Sponsor_ID=sp.Sponsor_ID
				    AND pp.Sponsor_ID=sp.Sponsor_ID
				    AND pp.branch_id=br.Branch_ID
				    AND pp.Payment_Date_And_Time ='CURDATE()'
				    AND ic.Item_Category_ID ='$catID'
				 ";
			$filterRevenue = mysqli_query($conn,$query);
	}//END ELSE
    //start processing and calculations
    $totalAmount=0;//the total amount of money
    $res=mysqli_num_rows($filterRevenue);
    for($i=0;$i<$res;$i++){
	$row=mysqli_fetch_array($filterRevenue);
	//return rows
	$catID=$row['Item_Category_ID'];
	$catName=$row['Item_Category_Name'];
	$itemID=$row['Item_ID'];
	$productName=$row['Product_Name'];
	$quantity=$row['Quantity'];
	$price=$row['Price'];
	$pdate=$row['Payment_Date_And_Time'];
	$patientName=$row['Patient_Name'];
	$receiptNo=$row['Patient_Payment_ID'];
	$receiptDate=$row['Receipt_Date'];
	$billType=$row['Billing_Type'];
	$discount=$row['Discount'];
	
	$amount=($price-$discount)*$quantity;
	$totalAmount=$totalAmount+$amount;
	//display the data
	echo "<tr><td>".($i+1)."</td>";
	echo "<td style='text-align: center;'>".$patientName."</td>";
	echo "<td style='text-align: center;'>".$productName."</td>";
	echo "<td style='text-align: center;'>".$quantity."</td>";
	echo "<td style='text-align: center;'>".$discount."</td>";
	echo "<td style='text-align: center;'>".number_format($amount)."</td>";
	echo "<td style='text-align: center;'><a href='#'>".$receiptNo."</a></td>";
	echo "<td style='text-align: center;'>".$billType."</td>";
	echo "<td style='text-align: center;'>".$receiptDate."</td>";
	echo "<tr>";
    }//end for loop
	    ?></table>
	    <?php
	    echo "<table width='70%'><tr>
	    <td colspan='5' style='border:1;'>
		<b style='font-size:14px;font-family:verdana'>
		    <i>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    Sub Total
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	     "?><?php
			echo number_format($totalAmount);
		    ?>
		   <?php echo" </i>
		</b>
	    </td>
	</tr></table>";
	    
	    ?>
	</center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
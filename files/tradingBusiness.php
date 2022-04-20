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
    
            <legend align=center><b>TRADING BUSINESS</b></legend>
        <center>
                <?php
                    echo '<center><table width =40% border=0>';
                    echo "<tr>
                                <td style='text-align:center;'><b>CATEGORY</b></td>
                                 <td style='text-align:center'><b>AMOUNT</b></td>
                         </tr>";
                         
                         //run the query to select data related to opening stock
                         $openingStock="SELECT * FROM tbl_branches";
                        echo "<tr>
                                <td style='text-align:left;'>Opening Balance</td>
                                 <td style='text-align:right'>0000</td>
                         </tr>";
                         echo "<tr>
                                <td style='text-align:left;'>Purshase</td>
                                 <td style='text-align:right'>0000</td>
                         </tr>";
                         echo "<tr>
                                <td style='text-align:left;'>Closing Balance</td>
                                 <td style='text-align:right'>0000</td>
                         </tr>";
                         echo "<tr>
                                <td style='text-align:left;'><b>Cost Of Sales</b></td>
                                 <td style='text-align:right'><b>0000</b></td>
                         </tr>";
                ?>
            </table>
        </center>
    </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
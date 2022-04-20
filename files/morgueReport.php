<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $Dead_IDID= '';
    $Date_From = '';
    $Date_To = '';
    //start authenticaton and authorization
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Morgue_Works'])){
	    if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>
    <a href='morguepage.php?MorgueWork=ReportWorkThisPage' class='art-button-green'>
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
    <table width="100%">
        <tr>
		    <td style='text-align: center;'>Branch</td>
            <td style='text-align: center;'>Reason for death</td> 
            <td style='text-align: center;'>From</td>
            <td style='text-align: center;'>To</td>
           <td style='text-align: center;'>Dead</td>
          
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
	                   <td>
                               <select name='Reason' id='region' onchange='getDistricts(this.value)'>
                                <option selected='selected' value='Reason for Death'>All reason</option>
					             <?php
					            $data = mysqli_query($conn,"select * from tbl_Reason_Dead_Body");
					           while($row = mysqli_fetch_array($data)){
					           ?>
					                    <option value='<?php echo $row['Reason_DeadBody'];?>'>
						                 <?php echo $row['Reason_DeadBody']; ?>
					                     </option>
				     	      <?php
					             }
					            ?>
                                    </select>
                   </td> 
            <td><input type='text' name='Date_From' id='date_From' required='required'></td> 
            <td><input type='text' name='Date_To' id='date_To' required='required'></td>
           
            <td style='text-align: center;'>
                <select name='Patient_Type' id='Patient_Type'>
                    <option selected='selected'>All</option>
                    <option>Outpatient</option>
                    <option>Inpatient</option> 
					<option>External</option>
                </select>
            </td> 
            
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
	    <?php if(isset($_POST['Print_Filter'])){ ?>
	    <td style='text-align: center;'>
                <a href='previewrevenuecollectionbycategory.php?Branch=<?php echo $Dead_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>
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
		  
		    if(isset($_POST['Print_Filter'])){
			 $Dead_ID= $_POST['Dead_ID']."<br>";
			 $Date_From =$_POST['Date_From']."<br>";
			 $Date_To= $_POST['Date_To']."<br>";
			
		    }
		    
		    if(isset($_SESSION['userinfo']['Employee_ID'])){
			$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
			$select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where employee_id = '$Employee_ID'");
			while($row = mysqli_fetch_array($select_Employee_Details)){
			    $Employee_Name = $row['Employee_Name'];
			    $Employee_ID = $row['Employee_ID'];
			}
		    }
					 		    
		?>
	    </td>
        </tr>        
    </table>
<fieldset>
    
            <legend align="center">DEAD BODY SUMMARY REPORT</legend>
        <center>
            <?php
		echo '<center><table width =100% border=0>';
    echo "<tr>
                <td width=3%>SN</td>
                <td style='text-align: center;' width=15%>&nbsp;DEAD BODY NAME</td>
                <td style='text-align: center;' width=15%>REASON FOR DEATH</td>
                <td style='text-align:center;' width=10%>MORGUE NAME</td>
		<td style='text-align: right;' width=10%>MEMBER FAMILY</td>
		<td style='text-align: right;' width=15%>NAME OF PERSON PICKING UP</td>
         </tr>";
    echo "<tr>
                <td colspan=6></td></tr>";
    $filterRevenue = mysqli_query($conn,"SELECT Dead_ID,Name_Of_Body,Reason,Morgue_Name,Member_Name(
					SELECT Delivery_ID,Person_Name from tbl_deadDelivery_person WHERE Dead_ID=Delivery_ID)
					FROM tbl_dead_regisration");
   
	    ?></table></center>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
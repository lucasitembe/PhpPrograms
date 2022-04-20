<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $Today = $original_Date;
    }
    
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['General_Ledger'] == 'yes'){ 
			echo "<a href='epaymentcollectionreports.php?ePaymentCollectionReports=ePaymentCollectionReportsThisForm' class='art-button-green'>BACK</a>";
		}
	}
?>


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>
<!-- end of the function -->


   
  
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>


<br/><br/>
<center>
    <table width=100%>
        <tr>
        	<td width="18%">
        		<input type="text" name="Patient_Name" id="Patient_Name" autocomplete="off" placeholder="~~~ Enter Patient Name ~~~" style="text-align: center;">
        	</td>
        	<td width="18%">
        		<input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" placeholder="~~~ Enter Patient Number ~~~" style="text-align: center;">
        	</td>
        	<td width="18%">
        		<input type="text" name="Bill_Number" id="Bill_Number" autocomplete="off" placeholder="~~~ Enter Bill Number ~~~" style="text-align: center;">
        	</td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
        </tr>
    </table>
</center>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:30});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:30});
    </script>
    <!--End datetimepicker-->
   
<fieldset style='overflow-y: scroll; height: 380px;background-color:white;margin-top:20px;' id='Fieldset_List'>
	<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>RECONCILIATION DETAILS</b></legend>
        <center>
</fieldset>

<?php
    include("./includes/footer.php");
?>
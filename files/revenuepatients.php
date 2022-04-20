<?php
    ini_set('max_execution_time', 300);
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
    
    
    
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $data='';
    $data.='<option value="All Sponsors">All Sponsors</option>';
    
    while ($row = mysqli_fetch_array($query)) {
         $data.= '<option value="'.$row['Sponsor_ID'].'$$>'.$row['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
    }
    
    $query= mysqli_query($conn,"SELECT te.Employee_ID,Employee_Name FROM tbl_employee te INNER JOIN tbl_patient_payments pp WHERE te.Employee_ID=pp.Employee_ID GROUP BY te.Employee_ID") or die(mysqli_error($conn));
    $staff='';
    $staff.='<option value="All">All</option>';
    
    while ($row = mysqli_fetch_array($query)) {
         $staff.= '<option value="'.$row['Employee_ID'].'">'.$row['Employee_Name'].'</option>';
    }
    
?>
<a href="generalledgercenter.php" style=""><button type="button" class="art-button-green">BACK</button></a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>


<!--patient report Revenue-->

 <br/><br/><br/> 
   
<fieldset style="background-color:white;font-size:larger">
    <legend align='center' style="padding: 10px;background-color:#037CB0;color: white;font-size: 18px;width:50%;margin-bottom: 15px;text-align:center  "><b>REVENUE COLLECTION BY PATIENT REPORT</b></legend>
      <form action="revenuepatientsummary.php" method="post">
        <center>
	    <table width = '60%'>
                <tr>
                    <td>From Date</td><td><input type="text" name="fromDate" class="FromDate" id="Date_From"> <td>To Date</td><td><input type="text" name="todate" class="toDate" id="Date_To"></td>
                </tr> 
                <tr>
                   <td>Sponsor Name</td>
                   <td colspan="3">
                       <select name="sponsor" style="padding:5px; font-size: 17px;width:100% ">
                            <?php echo $data;?>
                        </select>
                   </td>    
                </tr>
                <tr>
                   <td>Data Entry Name</td>
                   <td colspan="3">
                       <select name="EntryName" style="padding:5px; font-size: 17px;width:100% ">
                            <?php echo $staff;?>
                        </select>
                   </td>    
                </tr> 
                <tr>
                   <td>Patient Status</td>
                   <td colspan="3">
                       <select name="Status" style="padding:5px; font-size: 17px;width:100% ">
                           <option>All</option>
                           <option>Inpatient</option>
                           <option>Outpatient</option>
                        </select>
                   </td>    
                </tr> 
                <tr>
                   <td>Transaction Type</td>
                   <td colspan="3">
                       <select name="Transaction" style="padding:5px; font-size: 17px;width:100% ">
                           <option>All</option>
                           <option>Credit</option>
                           <option>Cash</option>
                           <!--<option>Canceled</option>-->
                        </select>
                   </td>    
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">
                     <button type="submit" name="showPatientPayment" class="art-button-green" id='selectDataEntry' style="padding:5px; font-size: 17px;width:98.5% ">Show sponsor Data</button>
                    </td>
                </tr>
        </table>
        </center>
            </form>
        
</fieldset><br/>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
#displaySelectedTests,#items_to_choose{
    overflow-y:scroll;
    overflow-x:hidden; 
}
</style>
 <script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:01});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:01});
	
</script>
<?php
    include("./includes/footer.php");
?>
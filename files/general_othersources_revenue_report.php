<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    $Link_Control = 'False';
    $Title = '';
    $Transaction_Type = '';
    $Date_From = '';
    $Date_To = '';
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ./index.php?InvalidPrivilege=yes");
    }
?>


<?php
    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['Section'])){
	    $Section = $_GET['Section'];
        if(strtolower($Section) == 'pharmacy'){
            echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
        }else{
?>
		<a href='performancereports.php?Section=<?php echo $Section; ?>&PerformanceReport=PerformanceReportThisPage' class='art-button-green'>BACK</a>
<?php
        }
	}else{
	    $Section = '';
	    echo "<a href='generalledgercenter.php?CUSTOMERNO=".$_GET['CUSTOMERNO']."&CUSTOMER_TYPE=".$_GET['CUSTOMER_TYPE']."&CustomerBillingDirectCash=CustomerBillingDirectCashThisPage' class='art-button-green'>BACK</a>";
	}
    }
    
    
    $cashiers = '';
$select_cashier = mysqli_query($conn,"select DISTINCT(e.Employee_ID), Employee_Name from tbl_employee e JOIN tbl_bank_transaction_cache b ON e.Employee_ID=b.Employee_ID") or die(mysqli_error($conn));
$nm = mysqli_num_rows($select_cashier);
while ($row = mysqli_fetch_array($select_cashier)) {
    $Employee_Name = $row['Employee_Name'];
    $Employee_ID = $row['Employee_ID'];

    $cashiers .="<option value='" . $Employee_ID . "'>$Employee_Name</option>";
}
?>

<br/><br/>
<center>

<fieldset>
<legend align="center" style="background-color:#006400;color:white;padding:5px;">
  <b>Revenue From Other Sources Report</b>
</legend>
	<br>
    <table width=100%>
      <tr>
        <td style='text-align: right;' width=10%>From</td>
        <td style="text-align: center; border: 1px #ccc solid;width: 15%;">
            <input type='text' autocomplete='off' name='Date_From' style="text-align:center;" id='date_From' style="background-color:#eeeeee;" required='required'>
        </td>
        <td style='text-align: right;' width=10%>To</td>
        <td style="text-align: center; border: 1px #ccc solid;width: 15%">
          <input type='text' name='Date_To' style="text-align:center;" autocomplete='off' id='date_To' style="background-color:#eeeeee;" required='required'>
        </td>
        <!--<td style='text-align: right;' width=10%>all</td>-->
        <td style="text-align: center; border: 1px #ccc solid;width: 15%">
           <select id="employee_id" style='text-align: center;width:80%;display:inline'>
                        <option  value="all">All Cashiers</option>
                        <?php echo $cashiers; ?>
                    </select>
        </td>
        <td style='text-align: center;' width=15%>
          <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick="Display_List()">
  			</td>
      </tr>
    </table>
  </center>
        <center>
            <!-- <iframe width='100%' id="display_list" height=330px src="othersources_revenue_report_ifrme.php?Section=<?php echo $Section; ?>&Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Transaction_Type=<?php echo $Transaction_Type; ?>"></iframe> -->
                    <div id="display_list">
                      <?php include_once "othersources_revenue_report_ifrme.php" ?>
                    </div>
            <br/><br/>
        </center>
</fieldset>

<?php
    include("./includes/footer.php");
?>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#date_From').datetimepicker({value:'',step:01});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:01});
</script>
<script type="text/javascript">
  function Display_List(){
      
    var employee_id= $("#employee_id").val();

    var Start_Date= $("#date_From").val();
    var End_Date= $("#date_To").val();
    if(Start_Date.trim() !='' && End_Date.trim() !=''){
      $.ajax({
        url:'othersources_revenue_report_ifrme.php',
        type:'post',
        data:{Start_Date:Start_Date,End_Date:End_Date,employee_id:employee_id},
        success:function(results){
          $("#display_list").html(results);
        }
      });
  }else{
    alert("Select Start Date and End Date");
  }
    return false
  }

  function Preview_List(){
    var Start_Date= $("#date_From").val();
    var End_Date= $("#date_To").val();

    if(End_Date.trim() !='' && Start_Date.trim() !=''){
      window.open('preview_other_sources_payment_list.php?Start_Date='+Start_Date+'&End_Date='+End_Date);
    }else {
      alert("Select Start Date and End Date");
    }
  }
  
     $(document).ready(function () {

        $('select').select2();

    });
</script>
<script src="js/select2.min.js"></script>
<script src="css/jquery-ui.js"></script>
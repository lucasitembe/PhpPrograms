<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $Title_Control = "False";
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes' && $_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
?>


<!--    Datepicker script--
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
   ---->
    
    
<!--    end of datepicker script-->


<?php
    /* if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Management_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT
    </a>
<?php  } } */ ?>


<?php
    if(isset($_SESSION['userinfo'])){
        //if($_SESSION['userinfo']['Management_Works'] == 'yes'){
	if(isset($_GET['Section'])){
	    if(strtolower($_GET['Section']) == 'generalledger'){
?>
    <a href='./generalledgercenter.php?GeneralLedger=GeneralLedgerThisPage' class='art-button-green'>
        BACK
    </a>
<?php
	    } }else{
?>
    <a href='./revenuecollectionreport.php?RevenueCollectionReport=RevenueCollectionReportThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 



<br/><br/>
<center>
    
<?php 
    if(isset($_POST['Print_Filter'])){
        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
        $Insurance = $_POST['Insurance'];
        $Payment_Type = $_POST['Payment_Type'];
        $Patient_Type = $_POST['Patient_Type'];
        $Title_Control = "True";
    }else{
	$Date_From = "";
        $Date_To = "";
        $Insurance = "";
        $Payment_Type = "";
        $Patient_Type = "";
    }
?>

<fieldset style='background-color: $cccccc; height: 90px;'>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <table width=100% style='background-color: white;'>
        <tr>
            <td style='text-align: center;'><b>From</b></td>
            <td style='text-align: center;'><b>To</b></td>
            <td style='text-align: center;'><b>Insurance</b></td>
            <td><b>Patient Type</b></td>
            <td><b>Payment Type</b></td>
        </tr>
        <tr>
            <td><input style='text-align: center;' type='text' name='Date_From' id='date_From_pc' required='required'></td> 
            <td><input style='text-align: center;' type='text' name='Date_To' id='date_To_pc' required='required'></td>
            <td style='text-align: center;'>
                <select name='Insurance' id='Insurance'>
                    <option selected='selected'>All</option>
                    <?php
                        $data = mysqli_query($conn,"select * from tbl_sponsor");
                        while($row = mysqli_fetch_array($data)){
                            echo '<option>'.$row['Guarantor_Name'].'</option>';
                        }
                    ?>
                </select>
            </td>
            <td style='text-align: center;'>
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
            </td>
            <td style='text-align: center;'>
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td> 
            <td style='text-align: center;'>
                <!--<input type='submit' name='previewrecept' onclick='previewreceptnumber()' class='art-button-green' value='PREVIEW'>-->
                <input class='art-button-green' style='color:white !important;' onclick='previewreceptnumber()' value="Preview"> 
            </td> 
          
        </tr>
    </table>
</fieldset>
    <table width=80%>
        <tr>
            <td colspan=7 style='text-align: center;'>
		<?php
		    if(strtolower($Title_Control) == 'true'){
			echo "<span style='color: green; text_align: center;'>
				<b>PAYMENT DETAILS FROM ".date('d/m/Y',strtotime($Date_From))." TO ".date('d/m/Y',strtotime($Date_To))."</b>
				</span>";
		    }
		?>
	    </td>
        </tr>
        
    </table>
</center>
<fieldset style='overflow-y:scroll; height:410px;'>  
    <legend align="left" style="background-color:#006400;color:white;padding:5px;">Revenue Collection By Receipt Number</legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td>
                    <div id="timeRangeSummary" style="width:100%">
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/></form>
<?php
    include("./includes/footer.php");
?>

<script>
  $('#Print_Filter').click(function(e){
   e.preventDefault();
   var fromDate=$('#date_From_pc').val();
   var toDate=$('#date_To_pc').val();
   var Insurance=$('#Insurance').val();
   var Patient_Type=$('#Patient_Type').val();
   var Payment_Type=$('#Payment_Type').val();
   $.ajax({
        type:'POST', 
        url:'Time_Range_Summary_report_Iframe.php',
        data:'fromDate='+fromDate+'&toDate='+toDate+'&Insurance='+Insurance+'&Patient_Type='+Patient_Type+'&Payment_Type='+Payment_Type,
        cache:false,
        success:function(html){
            $('#timeRangeSummary').html(html);
        }
     });
   
  });

</script>


    <script>
        $(function () { 
            $("#date_From_pc").datepicker({ 
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
            $("#date_To_pc").datepicker({ 
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
        
            
function previewreceptnumber(){
    var fromDate=$('#date_From_pc').val();
   var toDate=$('#date_To_pc').val();
   var Insurance=$('#Insurance').val();
   var Patient_Type=$('#Patient_Type').val();
   var Payment_Type=$('#Payment_Type').val();
   
		window.open('preview_all_recept_patients.php?fromDate='+fromDate+'&toDate='+toDate+'&Insurance='+Insurance+'&Patient_Type='+Patient_Type+'&Payment_Type='+Payment_Type, '_blank');
	}
        

    </script>                
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
			echo "<a href='epaymentcollections.php?ePaymentCollections=ePaymentCollectionsThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>


<!-- new date function--> 
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
     <table width=90% style="background-color:white;">
        <tr>
            <td style="text-align: right;"><b>Transactions Type</b></td>
            <td>
                <select name="Transaction_Type" id="Transaction_Type">
                    <option selected="selected">All</option>
                    <option value="Pending">Pending Transactions</option>
                    <option value="Completed">Completed Transactions</option>
                </select>
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
   
<fieldset style='overflow-y: scroll; height: 350px;background-color: white;margin-top:20px;' id='Fieldset_List'>
    <legend align='right' style="background-color:#006400;color: white;padding:5px;"><b>ePAYMENT TRANSACTION REPORTS</b></legend>
    <table width=100% border=1>
		<?php

		    echo "<tr id='thead'>
                <td width=5%><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td style='text-align: left;'><b>SPONSOR</b></td>
                <td style='text-align: right;'><b>PAYMENT CODE</b></td>
                <td style='text-align: right;'><b>AMOUNT REQUIRED</b></td>
                <td style='text-align: right;'><b>STATUS</b></td>
            </tr>";
		    echo '<tr><td colspan="6"><hr></td></tr>';
		?>
            </td>
        </tr>
    </table>
</fieldset>
<table width="100%">
	<tr>
		<td style="text-align: right; width: 100% " id="Report_Button_Area">
			 
		</td>
	</tr>
</table>

<script>
    function filter_list(){
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;

        if(window.XMLHttpRequest){
            myObjectFilter = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }
        myObjectFilter.onreadystatechange = function (){
            data29 = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Fieldset_List').innerHTML = data29;
            }
        }; //specify name of function that will handle server response........
        
        myObjectFilter.open('GET','Filter_Epayments_Report.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Transaction_Type='+Transaction_Type,true);
        myObjectFilter.send();
    }
</script>

<?php
    include("./includes/footer.php");
?>
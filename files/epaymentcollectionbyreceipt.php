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

    if(isset($_SESSION['userinfo'])){
		echo "<a href='epaymentcollectionreports.php?ePaymentCollectionReports=ePaymentCollectionReportsThisForm' class='art-button-green'>BACK</a>";
    }
?>


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
    $select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where Employee_Name = 'CRDB'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select_Employee_Details);
    if($nm > 0){
        while($row = mysqli_fetch_array($select_Employee_Details)){
        	$Employee_Name = $row['Employee_Name'];
        	$Employee_ID = $row['Employee_ID'];
        }
    }else{
        $Employee_Name = '';
        $Employee_ID = '';
    }
?><br/><br/>
<fieldset>
<center>
    <table width="70%">
        <td width="10%" style="text-align: right;"><b>Start Date</b></td>
        <td style="text-align: center; border: 1px #ccc solid;width: 15%;">
            <input type='text' autocomplete='off' name='Date_From' id='date_From' style="text-align: center;" placeholder="~~~ Start Date ~~~" readonly="readonly">
        </td>
        <td width="10%" style="text-align: right;"><b>End Date</b></td>
        <td style="text-align: center; border: 1px #ccc solid;width: 15%">
            <input type='text' name='Date_To' autocomplete='off' id='date_To' style="text-align: center;" placeholder="~~~ End Date ~~~" readonly="readonly">
        </td>
        <td width="10%" style="text-align: center;">
            <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Preview_Details()">
        </td>
        <td width="10%" style="text-align: center;">
            <input type="button" name="Preview" id="Preview" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Report()">
        </td>
    </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Fieldset_Details'>
    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>ePayment Revenue Collections</b></legend>

</fieldset>

<script type="text/javascript">
    function Preview_Details(){
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            if(window.XMLHttpRequest){
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Fieldset_Details').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            
            myObject.open('GET','ePayment_Preview_Details.php?Employee_ID='+Employee_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
            myObject.send();
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("date_From").focus();
            }else{
                document.getElementById("date_From").style = 'border: 3px solid white; text-align: center;';
            }
            if(End_Date == null || End_Date == ''){
                document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
                document.getElementById("date_To").focus();
            }else{
                document.getElementById("date_To").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        var Employee_ID = '<?php echo $Employee_ID; ?>';
        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            window.open('ecollectionbyreceipt.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Employee_ID='+Employee_ID+'&eCollectionByReceipt=eCollectionByReceiptThisPage', '_blank');
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date_From").style = 'border: 3px solid white; text-align: center;';
            }
            if(End_Date == null || End_Date == ''){
                document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date_To").style = 'border: 3px solid white; text-align: center;';
            }
        }
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
    });
    $('#date_From').datetimepicker({value:'',step:30});
        $('#date_To').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
    });
    $('#date_To').datetimepicker({value:'',step:30});
</script>

<?php
    include("./includes/footer.php");
?>
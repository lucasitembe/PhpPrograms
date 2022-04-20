<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    include_once("./functions/department.php");
    include_once("./functions/items.php");

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
	if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>

<?php
    if(isset($_SESSION['userinfo'])){ 
    	echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
    }
?>

<br/><br/>

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

<fieldset>
	<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>STOCK LEDGER REPORT</b></legend>
<?php
	$Title = '<tr><td colspan="3"><hr></td></tr>
				<tr>
				    <td width="5%"><b>SN</b></td>
				    <td ><b>ITEM NAME</b></td>
				    <td width="10%" style="text-align: right;"><b>BALANCE&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				</tr>
				<tr><td colspan="3"><hr></td></tr>';
?>
<table width="100%">

    <tr>
        <td width="18%" style='text-align: left;'>
            <select name="Sub_Department_ID" id="Sub_Department_ID" onchange="Get_Items_Filtered()">
                <?php
                    $Sub_Department_List = Get_Stock_Balance_Sub_Departments();
                    foreach($Sub_Department_List as $Sub_Department) {
                        echo "<option value='{$Sub_Department['Sub_Department_ID']}' > {$Sub_Department['Sub_Department_Name']} </option>";
                    }
                ?>
            </select>
        </td>
        <td width="15%"><input type="text" name="Start_Date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~" style="text-align: center;">
    </tr>

    <tr>
        <td width="18%" style='text-align: left;'>
            <select name='Classification' id='Classification' onchange='Get_Items_Filtered()'>
                <option value='all' selected>All Classification</option>
                <?php
                $Classification_List = Get_Item_Classification();
                foreach($Classification_List as $Classification) {
                    echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                }
                ?>
            </select>
        </td>
        <td width="15%"><input type="text" name="End_Date" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~" style="text-align: center;"></td>
    </tr>

    <tr>
        <td width="18%" style='text-align: left;'>
            <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='Get_Items_Filtered()'
                   placeholder='~~~ ~~~ Enter Item Name ~~~ ~~~' style="text-align: center;">
        </td>
        <td width="15%" style="text-align: right;">
            <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Filter_Details()">
            <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Print_Details()">
        </td>
    </tr>

    <tr>

    </tr>

	<tr>
		<td width="35%">
			<fieldset style="background-color:white; height:380px; overflow-y: scroll;">
					<table width="100%" id='Items_Fieldset'>
					</table>
			</fieldset>
		</td>
		<td width="65%">
			<table width="100%">
                <tr>
                    <td colspan=2>
                        <input type="text" name="Item_Name" id="Item_Name" value="" readonly="readonly" placeholder=" ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ Selected Product Name ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ ~~~~ " style="text-align: center;">
                        <input type="hidden" id="Item_ID" name="Item_ID" value="">
                    </td>
                </tr>
				<tr>
					<td colspan=2>
						<fieldset style="background-color:white; height:340px; overflow-y: scroll;" id="Ledger_Details">
							<table width="100%">
								<tr><td colspan="4"><hr></td></tr>
								<tr>
									<td width="20%"><b>DATE</b></td>
									<td><b>LOCATION/SUPPLIER</b>
									<td width="15%"><b>INWARD</b></td>
									<td width="15%"><b>OUTWARD</b></td>
								</tr>
								<tr><td colspan="4"><hr></td></tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</fieldset>

    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="js/select2.min.js"></script>

    <script type='text/javascript'>
        $(document).ready(function () {
            $('select').select2();
            Get_Items_Filtered();
        });
    </script>
    <style> .select2 { width: 100% !important; } </style>

<script type="text/javascript">
	function Filter_Details(){
		var Start_Date = document.getElementById("date").value;
		var End_Date = document.getElementById("date2").value;
		var Item_ID = document.getElementById("Item_ID").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
		if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Item_ID != null && Item_ID != ''){
			if(window.XMLHttpRequest) {
			    myObjectF_Details = new XMLHttpRequest();
			}else if(window.ActiveXObject){
			    myObjectF_Details = new ActiveXObject('Micrsoft.XMLHTTP');
			    myObjectF_Details.overrideMimeType('text/xml');
			}
		    
			myObjectF_Details.onreadystatechange = function (){
			    data88 = myObjectF_Details.responseText;
			    if (myObjectF_Details.readyState == 4) {
					document.getElementById('Ledger_Details').innerHTML = data88;
					getItemsListFiltered();
			    }
			}; //specify name of function that will handle server response........
			myObjectF_Details.open('GET','Filter_Ledger_Details.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_ID='+Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);
			myObjectF_Details.send();
		}else{
			if(Start_Date == null || Start_Date == ''){
				document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
			}
			if(End_Date == null || End_Date == ''){
				document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
			}
			if(Item_ID == null || Item_ID == ''){
				document.getElementById("Item_Name").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("Item_Name").style = 'border: 3px solid white; text-align: center;';
			}
		}
	}
</script>

<script type="text/javascript">
    function Print_Details(){
    	var Start_Date = document.getElementById("date").value;
		var End_Date = document.getElementById("date2").value;
		var Item_ID = document.getElementById("Item_ID").value;
		if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Item_ID != null && Item_ID != ''){
        	var winClose=popupwindow('previewstockledgerreport.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_ID='+Item_ID+'PreviewStockLedgerReport=PreviewStockLedgerReportThisPage', 'STOCK LEDGER REPORT', 1200, 500);
        }else{
        	if(Start_Date == null || Start_Date == ''){
				document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("date").style = 'border: 3px solid white; text-align: center;';
			}
			if(End_Date == null || End_Date == ''){
				document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("date2").style = 'border: 3px solid white; text-align: center;';
			}
			if(Item_ID == null || Item_ID == ''){
				document.getElementById("Item_Name").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("Item_Name").style = 'border: 3px solid white; text-align: center;';
			}
        }
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script type="text/javascript">
	function Get_Selected_Details(Item_Name,Item_ID){
		document.getElementById("Item_Name").value = Item_Name;
		document.getElementById("Item_ID").value = Item_ID;
		Filter_Details();
	}
</script>
<script>
    function Get_Items_Filtered() {
        var Search_Value = document.getElementById("Search_Value").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        var Classification = document.getElementById("Classification").value;
        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','pharmacystockledgerreport_items.php?Classification='+Classification+'&Sub_Department_ID='+Sub_Department_ID+'&Search_Value='+Search_Value,true);
        myObject.send();
    }
</script>

 <?php
    include("./includes/footer.php");
?>
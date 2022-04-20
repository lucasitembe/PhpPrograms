<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Msamaha_Works'])){
	    if($_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
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
    echo "<a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm' class='art-button-green'>BACK</a>";

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeff;
        cursor:pointer;
        border-collapse:collapse !important;
        border:none !important;
    }
</style>
<br/><br/>
<fieldset>
    <table width=100%>
        <tr>
        	<td style="text-align: right;"><b>Category Name</b></td>
			<td>
				<select name="Item_Category_ID" id="Item_Category_ID">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"select Item_Category_Name, Item_Category_ID from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
	    				$num = mysqli_num_rows($select);
	    				if($num > 0){
	    					while ($data = mysqli_fetch_array($select)) {
					?>
								<option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
					<?php 	}
						}
					?>
				</select>
			</td>
			<td style="text-align: right;"><b>Exemption Type</b></td>
			<td>
				<select name="msamaha_Items" id="msamaha_Items">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"select msamaha_Items, msamaha_aina from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select);
						if($num > 0){
							while ($data = mysqli_fetch_array($select)) {
					?>
								<option value="<?php echo $data['msamaha_Items']; ?>"><?php echo ucwords(strtolower($data['msamaha_aina'])); ?></option>
					<?php
							}
						}
					?>
				</select>
			</td>
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='~~~ Start Date ~~~' readonly='readonly' value=''>
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='~~~ End Date ~~~' readonly='readonly' value=''>
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Filter_Details()'>
            </td>
            <td width="8%">
            	<input type="button" name="Report_Button" id="Report_Button" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 400px;background-color:white;margin-top:20px;' id='Details_Area'>
	<legend style="background-color:#006400;color:white;padding:5px;"><b>EXEMPTION COST ANALYSIS REPORT</b></legend>
</fieldset>

<script type="text/javascript">
	function Filter_Details(){
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var msamaha_Items = document.getElementById("msamaha_Items").value;
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;

		if(Date_From != '' && Date_From != null && Date_To != '' && Date_To != null){
			if(window.XMLHttpRequest){
	            myObjectFilter = new XMLHttpRequest();
	        }else if(window.ActiveXObject){
	            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
	            myObjectFilter.overrideMimeType('text/xml');
	        }
	        
	        myObjectFilter.onreadystatechange = function (){
	            dataFilter = myObjectFilter.responseText;
	            if (myObjectFilter.readyState == 4) {
	                document.getElementById("Details_Area").innerHTML = dataFilter;
	            }
	        }; //specify name of function that will handle server response........
	        
	        myObjectFilter.open('GET','Exemption_Cost_Analysis_Report.php?Item_Category_ID='+Item_Category_ID+'&msamaha_Items='+msamaha_Items+'&Date_From='+Date_From+'&Date_To='+Date_To,true);
	        myObjectFilter.send();
	    }else{
	    	if (Date_From == '' || Date_From == null) {
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

	    	if (Date_To == '' || Date_To == null) {
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
	    }
	}
</script>

<script type="text/javascript">
	function Preview_Report(){
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var msamaha_Items = document.getElementById("msamaha_Items").value;
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;

		if(Date_From != '' && Date_From != null && Date_To != '' && Date_To != null){
			document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
			document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
			window.open('exemptioncostanalysis.php?Item_Category_ID='+Item_Category_ID+'&msamaha_Items='+msamaha_Items+'&Date_From='+Date_From+'&Date_To='+Date_To+'&ExemptionCostAnalysis=ExemptionCostAnalysisThisPage','_blank');
	    }else{
	    	if (Date_From == '' || Date_From == null) {
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

	    	if (Date_To == '' || Date_To == null) {
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
	    }
	}
</script>


<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
	$('#Date_From').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
	});
	$('#Date_From').datetimepicker({value:'',step:01});
	$('#Date_To').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
	});
	$('#Date_To').datetimepicker({value:'',step:01});
</script>
   

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>

    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
    include("./includes/footer.php");
?>
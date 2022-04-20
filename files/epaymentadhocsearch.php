<?php
	include("./includes/header.php");
	include("./includes/connection.php");
?>
<?php
	echo "<a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>BACK</a>";
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
<br/><br/>
<fieldset>
	<center>
		<table width="50%">
			<tr>
				<td style="text-align: right;" width="20%"><b>PAYMENT CODE</b></td>
				<td><input type="text" name="Payment_Code" id="Payment_Code" autocomplete="off" placeholder="Enter Payment Code" style="text-align: center;"></td>
				<td width="30%" style="text-align: center;"><input type="button" name="Search" id="Search" value="SEARCH RECEIPT" class="art-button-green" onclick="Search_Receipt()"></td>
			</tr>
		</table>
	</center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id="Transaction_Area">
	<legend><b>ePAYMENT AdHOC SEARCH</b></legend>
	<table width="100%">
		<tr><td colspan="10"><hr></td></tr>
		<tr>
			<td width="3%"><b>SN</b></td>
			<td><b>PATIENT NAME</b></td>
			<td width="10%"><b>PATIENT NUMBER</b></td>
			<td width="9%"><b>SPONSOR NAME</b></td>
			<td width="13%"><b>AGE</b></td>
			<td width="8%"><b>GENDER</b></td>
			<td width="12%"><b>PHONE NUMBER</b></td>
			<td width="8%"><b>RECEIPT#</b></td>
			<td width="6%" style="text-align: right;"><b>AMOUNT</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td width="7%"><b>ACTION</b></td>
		</tr>
		<tr><td colspan="10"><hr></td></tr>

	</table>
</fieldset>

<script type="text/javascript">
	function Search_Receipt(){
		var Payment_Code = document.getElementById("Payment_Code").value;
		if(Payment_Code != null && Payment_Code != ''){
			if(window.XMLHttpRequest){
				myObjectSearch = new XMLHttpRequest();
			}else if(window.ActiveXObject){
				myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
				myObjectSearch.overrideMimeType('text/xml');
			}
			myObjectSearch.onreadystatechange = function (){
				data = myObjectSearch.responseText;
				if (myObjectSearch.readyState == 4) {
					document.getElementById("Transaction_Area").innerHTML = data;
				}
			}; //specify name of function that will handle server response........
			
			myObjectSearch.open('GET','Epayment_Adhock_Search.php?Payment_Code='+Payment_Code,true);
			myObjectSearch.send();
		}else{
			document.getElementById("Payment_Code").style = 'border: 3px solid red; text-align: center;';
			document.getElementById("Payment_Code").focus();
		}
	}
</script>

<script>
	/*function Print_Receipt_Payment(Patient_Payment_ID){
		var winClose=popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);    
	}
	function popupwindow(url, title, w, h) {
	  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
	   var wTop = window.screenTop ? window.screenTop : window.screenY;

	    var left = wLeft + (window.innerWidth / 2) - (w / 2);
	    var top = wTop + (window.innerHeight / 2) - (h / 2);
	    var mypopupWindow= window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
	      
	      return mypopupWindow;
	}*/
</script>

<script type="text/javascript">
    function Print_Receipt_Payment(Patient_Payment_ID){
        var winClose=popupwindow('invidualsummaryreceiptprint.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualSummaryReport=IndividualSummaryReportThisForm', 'Receipt Patient', 530, 400);
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
<?php
	include("./includes/footer.php");
?>
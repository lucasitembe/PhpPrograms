<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Quality_Assurance'])){
	if($_SESSION['userinfo']['Quality_Assurance'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ 
?>
    <a href='Eclaim_Billing_Session_Control.php?New_Bill=New_Bill&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
        CREATE NEW BILL
    </a>
<?php } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ 
?>
    <!--a href='Eclaim_Billing_Session_Control.php?Previous_Bills=True&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
        APPROVED BILLS
    </a-->
<?php } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ 
?>
    <a href='qualityassuarancework.php?QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
        BACK
    </a>
<?php } } ?>

<br/><br/>

<center>
        <center>       
            <fieldset style='height: 450px;' >
                <legend align="right" ><b>Billing Processing</b></legend>
                <!--<form action='#' method='post' name='myForm' id='myForm'>-->
                    <table width=70%>
                            <tr>
                                <td style='text-align: right; width: 10%'><b>Start Date</b></td>
                                <td width=30%>
                                    <input type='text' name='Start_Date' id='date' required='required' style='text-align: center;' placeholder='Start Date' readonly='readonly'>
                                </td>
                                <td style='text-align: right; width: 10%'><b>End Date</b></td>
                                <td width=30%>
                                    <input type='text' name='End_Date' id='date2' required='required' style='text-align: center;' placeholder='End Date' readonly='readonly'>
                                </td>
                                <td style='text-align: right;'><b>Insurance</b></td>
                                <td>
                                    <select name='Sponsor_ID' id='Sponsor_ID' required='required'>
                                        <option selected='selected'></option>
                                        <?php
                                            $sql = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor
                                                                where Guarantor_Name <> 'CASH' order by Guarantor_Name") or die(mysqli_error($conn));
                                            $num = mysqli_num_rows($sql);
                                            if($num > 0){
                                                while($row = mysqli_fetch_array($sql)){
                                        ?>
                                                <option value='<?php echo $row['Sponsor_ID']; ?>'><?php echo $row['Guarantor_Name']; ?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td style='width: 5%;'>
                                    <input name='Submit' id='Submit' type='button' value='FILTER' class='art-button-green' onclick='Get_Selected_Bills()'>
                                </td>
                            </tr>
                    </table>
                <!--</form>-->
                
                <fieldset style='overflow-y: scroll; height: 320px;' id='Bills_Fieldset_List'>
                    <?php
                        echo '<center><table width = 100% border=0>';
                        echo '
                                <tr style="background-color: #ccc;">
                                    <td width=4% style="text-align: center;"><b>Sn</b></td>
                                    <td><b>Patient Name</b></td>
                                    <td width=10%><b>Patient#</b></td>
                                    <td width 15% style="text-align: left;"><b>Sponsor Name</b></td>
                                    <td width=10% style="text-align: center;"><b>Folio Number</b></td>
                                    <td width=10% style="text-align: right;"><b>Amount</b></td>
                                    <td width=13% style="text-align: right;"><b>First Served Date</b></td>
                                    <td width=20% style="text-align: center;"><b>Approved By</b></td>
                                </tr>';

                        echo '</table>';
                    ?>
		</fieldset>
        </center>
        <table width=100%>
            <tr>
                <td width="70%" style="text-align: right;" id="Total_Area"></td>
                <td style='text-align: right;'>
                    <input type='button' value='' id='Preview_Previous_Approved_Transaction' name='Preview_Previous_Approved_Transaction' class='art-button-green' style='visibility: hidden;'>
                    <input type='button' value='' id='Preview_Next_Approved_Transaction' name='Preview_Next_Approved_Transaction' class='art-button-green' style='visibility: hidden;'>
                    <input type='button' value='GENERATE BILL' id='Generate_Bill_Button' name='Generate_Bill_Button' class='art-button-green' style='visibility: hidden;' onclick=(Generate_Bill());>
                </td>
            </tr>
        </table>
</center>


<script>
	function Get_Selected_Bills(){
            var Start_Date = document.getElementById("date").value;
            var End_Date = document.getElementById("date2").value;
            var Sponsor_ID = document.getElementById("Sponsor_ID").value;
            
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != '' && Sponsor_ID != null) { 
                document.getElementById('Bills_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                if(window.XMLHttpRequest){
                        myObjectGetSelectedBills = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                        myObjectGetSelectedBills = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectGetSelectedBills.overrideMimeType('text/xml');
                }
                myObjectGetSelectedBills.onreadystatechange = function (){
                        data1 = myObjectGetSelectedBills.responseText;
                        if (myObjectGetSelectedBills.readyState == 4) {
                                document.getElementById('Bills_Fieldset_List').innerHTML = data1;
                                Validate_Transaction_List();
                                Calculate_Total_Approved();
                                //document.getElementById("Generate_Bill_Button").style.visibility = 'visible';
                                //document.getElementById("Preview_Previous_Approved_Transaction").style.visibility = 'visible';
                                //document.getElementById("Preview_Next_Approved_Transaction").style.visibility = 'visible';
                                //document.getElementById("Preview_Previous_Approved_Transaction").value = 'PREVIEW ALL APPROVED TRANSACTIONS BEFORE '+Start_Date;
                                //document.getElementById("Preview_Next_Approved_Transaction").value = 'PREVIEW ALL APPROVED TRANSACTIONS AFTER '+End_Date;
                        }
                }; //specify name of function that will handle server response........
                
                myObjectGetSelectedBills.open('GET','Get_Selected_Billis.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
                myObjectGetSelectedBills.send();
            }else{
		if(Sponsor_ID=='' || Sponsor_ID == null){
		    document.getElementById("Sponsor_ID").focus();
		    document.getElementById("Sponsor_ID").style = 'border-color: red';
		}
	    }
	}
</script>


<script>
    function Validate_Transaction_List(){
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != '' && Sponsor_ID != null) { 
            if(window.XMLHttpRequest){
                myObjectValidate = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectValidate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectValidate.overrideMimeType('text/xml');
            }
            myObjectValidate.onreadystatechange = function (){
                    data100 = myObjectValidate.responseText;
                    if (myObjectValidate.readyState == 4) {
                            var Feedback = data100;
                            if(Feedback == 'yes'){
                                document.getElementById("Generate_Bill_Button").style.visibility = 'visible';
                            }else{
                                document.getElementById("Generate_Bill_Button").style.visibility = 'hidden';
                            }
                    }
            }; //specify name of function that will handle server response........
            
            myObjectValidate.open('GET','Validate_Transaction_List.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
            myObjectValidate.send();
        }
    }
</script>

<script>
    /*function Selected_Payments_Title(){
            var Start_Date = document.getElementById("date").value;
            var End_Date = document.getElementById("date2").value;
            var Sponsor_ID = document.getElementById("Sponsor_ID").value;
            
            if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') { 
                if(window.XMLHttpRequest){
                        myObjectGetSelectedBills = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                        myObjectGetSelectedBills = new ActiveXObject('Micrsoft.XMLHTTP');
                        myObjectGetSelectedBills.overrideMimeType('text/xml');
                }
                myObjectGetSelectedBills.onreadystatechange = function (){
                        data1 = myObjectGetSelectedBills.responseText;
                        if (myObjectGetSelectedBills.readyState == 4) {
                                document.getElementById('Bills_Fieldset_List').innerHTML = data1;
                        }
                }; //specify name of function that will handle server response........
                
                myObjectGetSelectedBills.open('GET','Selected_Payments_Title.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
                myObjectGetSelectedBills.send();
            }
	}    */
</script>

<script>
    function Generate_Bill(){
	var Start_Date = document.getElementById("date").value;
	var End_Date = document.getElementById("date2").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	
	var Confirm_Message = confirm("Are you sure you want to create this bill\nStart Date : "+Start_Date+"\nEnd Date : "+End_Date);
	if (Confirm_Message == true) {
	    if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') { 
		if(window.XMLHttpRequest){
		    myObjectGenerateBill = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectGenerateBill = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGenerateBill.overrideMimeType('text/xml');
		}
		myObjectGenerateBill.onreadystatechange = function (){
		    data2 = myObjectGenerateBill.responseText;
		    if (myObjectGenerateBill.readyState == 4) {
			//document.getElementById('Bills_Fieldset_List').innerHTML = data2;
			//document.getElementById("Generate_Bill_Button").style.visibility = 'visible';
			//document.getElementById("Preview_Previous_Approved_Transaction").style.visibility = 'visible';
			//document.getElementById("Preview_Next_Approved_Transaction").style.visibility = 'visible';
			//document.getElementById("Preview_Previous_Approved_Transaction").value = 'PREVIEW ALL APPROVED TRANSACTIONS BEFORE '+Start_Date;
			//document.getElementById("Preview_Next_Approved_Transaction").value = 'PREVIEW ALL APPROVED TRANSACTIONS AFTER '+End_Date;
			var Feedback = data2;
			if (Feedback == 'Successfull'){
			    alert("Bill created successfully");
			    document.location = "./billslist.php?BillsList=BillsListThisPage";
			}else{
			    alert("Creating bill process fail!! Please try again");
			}
		    }
		}
	    }; //specify name of function that will handle server response........
	    myObjectGenerateBill.open('GET','Generate_Bill.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
	    myObjectGenerateBill.send();
	}
    }
</script>


<script>
    function Calculate_Total_Approved(){
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '' && Sponsor_ID != '' && Sponsor_ID != null) { 
            if(window.XMLHttpRequest){
                myObjectCalculateTotal = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectCalculateTotal = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectCalculateTotal.overrideMimeType('text/xml');
            }
            myObjectCalculateTotal.onreadystatechange = function (){
                    data29 = myObjectCalculateTotal.responseText;
                    if (myObjectCalculateTotal.readyState == 4) {
                        document.getElementById('Total_Area').innerHTML = data29;
                    }
            }; //specify name of function that will handle server response........
            
            myObjectCalculateTotal.open('GET','Calculate_Total_Approved.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
            myObjectCalculateTotal.send();
        }
    }
</script>

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
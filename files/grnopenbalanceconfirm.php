<script src='js/functions.js'></script>
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                        header("Location: ./index.php?InvalidPrivilege=yes");
                }else{
                    @session_start();
                    if(!isset($_SESSION['Storage_Supervisor'])){ 
                        header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                    }
            }
        }else{
                header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $canPakage = false;
    $display = "style='display:none'";

    if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
        $canPakage = true;
        $display = "";
    }
    if(isset($_GET['from_grn_multiple_approval'])){
       ?>
        <a href='grn_multiple_approval.php' class='art-button-green'>BACK</a>
<?php 
    }else{
?>
<a href='grnopenbalance.php?status=new&GrnOpenBalance=GrnOpenBalanceThisPage' class='art-button-green'>BACK</a>
<?php 
    }
    if(isset($_GET['Grn_Open_Balance_ID'])){
        $_SESSION['Grn_Open_Balance_ID'] = $_GET['Grn_Open_Balance_ID'];
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

<script>
	function Confirm_Remove_Item(Item_Name,Open_Balance_Item_ID){ 
		var Confirm_Message = confirm("Are you sure you want to remove \n"+Item_Name);
		
		if (Confirm_Message == true) {
			if(window.XMLHttpRequest) {
				My_Object_Remove_Item = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
				My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
				My_Object_Remove_Item.overrideMimeType('text/xml');
			}
				
			My_Object_Remove_Item.onreadystatechange = function (){
				data6 = My_Object_Remove_Item.responseText;
				if (My_Object_Remove_Item.readyState == 4) {
					document.getElementById('Items_Fieldset_List').innerHTML = data6;
					//update_total(Registration_ID);
					//update_Billing_Type();
					//Update_Claim_Form_Number();
				}
			}; //specify name of function that will handle server response........
				
			My_Object_Remove_Item.open('GET','Open_Balance_Remove_Item_From_List.php?Open_Balance_Item_ID='+Open_Balance_Item_ID,true);
			My_Object_Remove_Item.send();
		}
	}
</script>

<?php
    if(isset($_SESSION['Grn_Open_Balance_ID'])){
        $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
        $select_data = mysqli_query($conn,"select emp.Employee_Name, gob.Grn_Open_Balance_Description, gob.Created_Date_Time, sd.Sub_Department_Name from
                                        tbl_employee emp, tbl_grn_open_balance gob, tbl_sub_department sd where
                                            emp.Employee_ID = gob.Employee_ID and
                                                sd.Sub_Department_ID = gob.Sub_Department_ID and
                                                    gob.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select_data);
        if($num > 0){
            while($row = mysqli_fetch_array($select_data)){
                $Employee_Name = $row['Employee_Name'];
                $Sub_Department_Name = $row['Sub_Department_Name'];
                $Created_Date_Time = $row['Created_Date_Time'];
                $Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
            }
        }else{
            $Employee_Name = '';
            $Sub_Department_Name = '';
            $Created_Date_Time = '';
            $Grn_Open_Balance_ID = '';
            $Grn_Open_Balance_Description = '';
        }
    }else{
        $Employee_Name = '';
        $Sub_Department_Name = '';
        $Created_Date_Time = '';
        $Grn_Open_Balance_ID = '';
        $Grn_Open_Balance_Description = '';
    }
    
    $date = new DateTime($Created_Date_Time);
    $date_Display = $date->format('d-m-Y H:i:s');
?>





<!-- submit function-->
<script>
    function Authenticate_Submit_Open_Balance_Items() {
    	var Username = document.getElementById("Username").value;
    	var Password = document.getElementById("Password").value;
        var Grn_Open_Balance_ID='<?= $Grn_Open_Balance_ID ?>'

	if (Password != null && Password != '' && Username != null && Username != '') {
	    document.getElementById("Password").style = 'border: 3px';
	    document.getElementById("Username").style = 'border: 3px';

	    if(window.XMLHttpRequest) {
		myObjectAuthentication = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
		myObjectAuthentication = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectAuthentication.overrideMimeType('text/xml');
	    }

	    myObjectAuthentication.onreadystatechange = function (){
		data20 = myObjectAuthentication.responseText;
		if (myObjectAuthentication.readyState == 4) {
                    $("#Username").css("border","1px solid black");
                    $("#Password").css("border","1px solid black");
		    var Feedback = data20;
		    if (Feedback == 'all_approve_success') {
			Submit_Open_Balance_Items(); 
		    }else if(Feedback=="invalid_privileges"){
                        alert("Invalid Username or Password or you do not have enough privilage to approve this grn");
                        document.getElementById("Username").value = '';
			document.getElementById("Username").focus();
			document.getElementById("Password").value = '';
                    }else if(Feedback=="fail_to_approve"){
                        alert("Fail to approve..please try again");  
                        document.getElementById("Username").value = '';
			document.getElementById("Username").focus();
			document.getElementById("Password").value = '';
                    }else{
			alert(Feedback);
			document.getElementById("Username").value = '';
			document.getElementById("Username").focus();
			document.getElementById("Password").value = '';
		    }
		}
	    }; //specify name of function that will handle server response........
	    myObjectAuthentication.open('GET','Authentication_Submit_Open_Balance_Items.php?Username='+Username+'&Password='+Password+'&Grn_Open_Balance_ID='+Grn_Open_Balance_ID,true);
	    myObjectAuthentication.send();
	}else{
	    if (Password == null || Password == '') {
		document.getElementById("Password").style = 'border: 3px solid red';
		document.getElementById("Password").focus();
	    }else{
		document.getElementById("Password").style = 'border: 3px';
	    }
	    
	    if (Username == null || Username == '') {
		document.getElementById("Username").style = 'border: 3px solid red';
		document.getElementById("Username").focus();
	    }else{
		document.getElementById("Username").style = 'border: 3px';
	    }
	}
    }
</script>

<script>
    function Submit_Open_Balance_Items() {
        var sms = confirm("Are you sure you want to submit this open balance");
        if(sms == true){
        	if(window.XMLHttpRequest) {
        	    myObjectSubmitOpenBalance = new XMLHttpRequest();
        	}else if(window.ActiveXObject){ 
        	    myObjectSubmitOpenBalance = new ActiveXObject('Micrsoft.XMLHTTP');
        	    myObjectSubmitOpenBalance.overrideMimeType('text/xml');
        	}
        	
        	myObjectSubmitOpenBalance.onreadystatechange = function (){
        	    data21 = myObjectSubmitOpenBalance.responseText;
        	    if (myObjectSubmitOpenBalance.readyState == 4) {
            		var Feedback = data21;
                        console.log(Feedback);
            		if (Feedback == 'yes') {
            		    alert("PROCESS SUCCESSFULLY");
                        window.open("openbalancepreview.php?OpenBalancePreview=OpenBalancePreviewThisPage","_parent");
            		    //document.location = "./previousopenbalances.php?Status=PreviousOpenBalances&OpenBalance=OpenBalanceThisPage";
            		}else{
            		    alert("PROCESS FAIL!! PLEASE TRY AGAIN.");
            		}
        	    }
        	}; //specify name of function that will handle server response........
        	myObjectSubmitOpenBalance.open('GET','Submit_Open_Balance_Items.php',true);
        	myObjectSubmitOpenBalance.send();
        }
    }
</script>

<!-- end of subbmit function-->


<script>
    function update_Grn_Description(){
	var Grn_Description = document.getElementById("Grn_Description").value;
	if(window.XMLHttpRequest){
		myObjectUpdateDescription = new XMLHttpRequest();
	}else if(window.ActiveXObject){
		myObjectUpdateDescription = new ActiveXObject('Micrsoft.XMLHTTP');
		myObjectUpdateDescription.overrideMimeType('text/xml');
	}
	myObjectUpdateDescription.onreadystatechange = function (){
		data26 = myObjectUpdateDescription.responseText;
		if (myObjectUpdateDescription.readyState == 4) {
			//document.getElementById('Requisition_Description').value = data26;
		}
	}; //specify name of function that will handle server response........
	
	myObjectUpdateDescription.open('GET','Grn_Open_Balance_Update_Description.php?Grn_Description='+Grn_Description,true);
	myObjectUpdateDescription.send();
    }
</script>



<center>
<fieldset>
    <legend align="right" ><b>APPROVE GRN</b></legend>
    <table width=100%>
        <tr>
            <td width=10% style='text-align: right;'>Store Name</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Sub_Department_Name; ?>'>
            </td>
            <td width=10% style='text-align: right;'>Created Date & Time</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $date_Display; //$Created_Date_Time; ?>'>
            </td>
            <td width=10% style='text-align: right;'>Created Date & Time</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Created_Date_Time; ?>'>
            </td>
        </tr>
        <tr>
            <td width=10% style='text-align: right;'>GRN Description</td>
            <td width=15% colspan=3>
                <input type='text' name='Grn_Description' id='Grn_Description' value='<?php echo $Grn_Open_Balance_Description; ?>' onclick='update_Grn_Description()' onkeyup='update_Grn_Description()' onkeypress='update_Grn_Description()'>
            </td> 
            <td width=10% style='text-align: right;'>Prepared By</td>
            <td width=15%>
                <input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 310px; background-color: white;' id='Items_Fieldset_List'>
    <?php
        echo '<center><table width = 100% border=0>';
        echo '<tr><td colspan="12"><hr></td></tr>';
        echo '<tr><td width=4% style="text-align: center;">Sn</td>
        <td width=20%>Item Name</td>
        <td width=9% style="text-align: center;">Unit Of Measure</td>
        <td '.$display.' width="9%">Containers</td>
        <td '.$display.' width="9%">Items Per Container</td>
        <td width=9% style="text-align: center;">Quantity</td>
        <td width=9% style="text-align: right;">Buying Price</td>
        <td width=7% style="text-align: right;">Batch No</td>
        <td width=7% style="text-align: right;display:none">Manuf Date</td>
        <td width=7% style="text-align: right;">Expire Date</td>
        <td width=7% style="text-align: right;">Sub Total</td>
        <td width=5% style="text-align: center;display:none">Remove</td></tr>';
        echo '<tr><td colspan="12"><hr></td></tr>';
        
        if(isset($_SESSION['Grn_Open_Balance_ID'])){
                $Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
        }else{
                $Grn_Open_Balance_ID = 0;
        }
        $select_Open_Balance_Items = mysqli_query($conn,"select obi.batch_no,obi.Open_Balance_Item_ID, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price,
		obi.Manufacture_Date, obi.Expire_Date, obi.Container_Qty, obi.Items_Per_Container, itm.Unit_Of_Measure
		    from tbl_grn_open_balance_items obi, tbl_items itm where
			itm.Item_ID = obi.Item_ID and
			    obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID' order by obi.Open_Balance_Item_ID desc") or die(mysqli_error($conn)); 
        
        $Temp=1;
        while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
            echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
            echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
            echo "<td><input type='text' readonly='readonly' value='" . $row['Unit_Of_Measure'] . "' style='text-align: center;'></td>";
            echo "<td ".$display." ><input type='text'readonly='readonly' name='Container_Qty' id='Container_Qty' value='".$row['Container_Qty']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td ".$display." ><input type='text'readonly='readonly' name='Items_Per_Container' id='Items_Per_Container' value='".$row['Items_Per_Container']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td><input type='text'readonly='readonly' name='Item_Quantity' id='Item_Quantity' value='".$row['Item_Quantity']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td style='text-align: right;'><input type='text' name='Buying_Price' id='Buying_Price' value='".number_format($row['Buying_Price'],2)."' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)'readonly='readonly' onkeypress='numberOnly(this)'></td>";
            echo "<td style='display:none'><input type='text' style='text-align: right;' readonly='readonly' value='".$row['Manufacture_Date']."'></td>";
            echo "<td><input type='text' style='text-align: right;' readonly='readonly' value='".$row['batch_no']."'></td>";
            echo "<td><input type='text' style='text-align: right;' readonly='readonly' value='".$row['Expire_Date']."'></td>";
            echo "<td style='text-align: right;'><input readonly='readonly'type='text' value='".number_format($row['Item_Quantity'] * $row['Buying_Price'],2)."' style='text-align: right;'></td>";
        ?>
                <td width=6% style="text-align: center;display: none"><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
        <?php
            echo "</tr>";
            $Temp++;
        }
        echo '</table>';
    ?>
</fieldset>
<fieldset>
    <!-- <legend>Supervisor Authentication</legend> -->
    <table width=100%>
        <tr>
            <td style='text-align: right;'>Username</td>
            <td><input type='text' name='Username' id='Username' placeholder='Username' autocomplete='off'>
            <td style='text-align: right;'> Password</td>
            <td><input type='password' name='Password' id='Password' placeholder='Password' autocomplete='off'></td>
            <td style='text-align: center; width: 10%;'>
                <input type='button' name='Submit' id='Submit' value='APPROVE' class='art-button-green' onclick='Authenticate_Submit_Open_Balance_Items()'>
            </td>
        </tr>
    </table>
</fieldset>
</center>


<?php
    include("./includes/footer.php");
?>

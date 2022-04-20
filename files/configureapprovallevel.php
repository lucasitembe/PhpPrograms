<script src='js/functions.js'></script>
<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
            echo "<a href='assignemployeeapprovallevel.php?AssignEmployeeApprovalLevel=AssignEmployeeApprovalLevelThisPage' class='art-button-green'>ASSIGN EMPLOYEE APPROVAL LEVEL</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
            echo "<a href='procurementconfiguration.php?ProcurementConfiguration=ProcurementConfigurationThisPage' class='art-button-green'>BACK</a>";
        }
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
<br/><br/><br/><br/><br/><br/>
<fieldset style='overflow-y: scroll; height: 305px; background-color:white'>  
    <legend align=center><b>PROCUREMENT CONFIGURATION</b></legend>
        <center>
<?php
    $select = mysqli_query($conn,"select Approval_Levels from tbl_system_configuration") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Approval_Levels = $row['Approval_Levels'];
        }
    }else{
        $Approval_Levels = 0;
    }
    if($Approval_Levels < 1){
?>
        <table width = "60%">
            <tr>
                <td style='text-align: right;' width="25%">
                    SPECIFY APPROVAL LEVEL
                </td>
                <td style='text-align: left;'>
                    <input type="text" name="Approval_Level" id="Approval_Level" autocomplete="off" oninput="numberOnly(this);" onkeypress="numberOnly(this);" onkeyup="numberOnly(this);">
                    <input type="hidden" name="Update_Level" id="Update_Level" value="true">
                </td>
                <td style="text-align: center;" width="10%">
                    <input type="button" name="Submit" id="Submit" value="SAVE" class="art-button-green" onclick="Submit_Data();">
                </td>
            </tr>
        </table>
<?php
    }else{
        //check approval setup 
        $check = mysqli_query($conn,"select * from tbl_approval_level") or die(mysqli_error($conn));
        $no = mysqli_num_rows($check);
        if($no > 0){
            echo '<table width = "50%">';
            echo "<tr><td colspan='3'><hr></td></tr>";
            echo '<tr>
                    <td width="5%"><b>SN</b></td>
                    <td><b>APPROVAL LEVEL</b></td>
                    <td><b>APPROVAL TITLE</b></td>
                </tr>';
            echo "<tr><td colspan='3'><hr></td></tr>";
            $Level = 0;
            while ($data = mysqli_fetch_array($check)) {
?>
                    <tr>
                        <td><?php echo '<b>'.++$Level.'.</b>'; ?></td>
                        <td style='text-align: left;' width="25%">
                            <?php echo 'Approval Level '.$Level; ?>
                        </td>
                        <td style='text-align: left;' id="Label_<?php echo $Level; ?>">
                            <select name="<?php echo $data['Approval_ID']; ?>" id="<?php echo $data['Approval_ID']; ?>" onchange="Verify_Update_Approval_Level(<?php echo $Level; ?>)">
                                <option selected="selected" value="">~~~ Select Approval Title ~~~</option>
                                <option <?php if($data['Approval_Title'] =='Accountant'){ echo "selected='selected'"; } ?>>Accountant</option>
                                <option <?php if($data['Approval_Title'] =='Billing Personnel'){ echo "selected='selected'"; } ?>>Billing Personnel</option>
                                <option <?php if($data['Approval_Title'] =='Cashier'){ echo "selected='selected'"; } ?>>Cashier</option>
                                <option <?php if($data['Approval_Title'] =='Doctor'){ echo "selected='selected'"; } ?>>Doctor</option>
                                <option <?php if($data['Approval_Title'] =='Food Personnel'){ echo "selected='selected'"; } ?>>Food Personnel</option>
                                <option <?php if($data['Approval_Title'] =='Hospital Admin'){ echo "selected='selected'"; } ?>>Hospital Admin</option>
                                <option <?php if($data['Approval_Title'] =='IT Personnel'){ echo "selected='selected'"; } ?>>IT Personnel</option>
                                <option <?php if($data['Approval_Title'] =='Laboratory Technician'){ echo "selected='selected'"; } ?>>Laboratory Technician</option>
                                <option <?php if($data['Approval_Title'] =='Laundry Personnel'){ echo "selected='selected'"; } ?>>Laundry Personnel</option>
                                <option <?php if($data['Approval_Title'] =='Nurse'){ echo "selected='selected'"; } ?>>Nurse</option>
                                <option <?php if($data['Approval_Title'] =='Pharmacist'){ echo "selected='selected'"; } ?>>Pharmacist</option>
                                <option <?php if($data['Approval_Title'] =='Procurement'){ echo "selected='selected'"; } ?>>Procurement</option>
                                <option <?php if($data['Approval_Title'] =='Radiologist'){ echo "selected='selected'"; } ?>>Radiologist</option>
                                <option <?php if($data['Approval_Title'] =='Receptionist'){ echo "selected='selected'"; } ?>>Receptionist</option>
                                <option <?php if($data['Approval_Title'] =='Record Personnel'){ echo "selected='selected'"; } ?>>Record Personnel</option>
                                <option <?php if($data['Approval_Title'] =='Security Personnel'){ echo "selected='selected'"; } ?>>Security Personnel</option>
                                <option <?php if($data['Approval_Title'] =='Storekeeper'){ echo "selected='selected'"; } ?>>Storekeeper</option>
                                <option <?php if($data['Approval_Title'] =='Others<'){ echo "selected='selected'"; } ?>>Others</option>
                            </select>
                        </td>
                        <!-- <td width="60%" id="Label_<?php echo $Level; ?>">

                        </td> -->
                    </tr>
<?php
            }
            echo "<tr><td colspan='3'><hr></td></tr>";
            echo "</table>";
        }
?>
<?php } ?>
            
        </center>
</fieldset>
            <table width = "100%">
                <tr>
                    <td style="text-align: right;" width="100%">
                        <input type="button" class="art-button-green" value="RESET PROCUREMENT CONFIGURATION SETTING" onclick="Reset_Procurement_Configuration();">
                    </td>
                </tr>
            </table>

<div id="Employee_Details" style="width:25%;">
    <center>
        <b>Approval title changed successfully</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Employee_Details_Close()">
    </center>
</div>

<div id="Approval_Success" style="width:25%;">
    <center>
        <b>Approval title updated successfully</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Approval_Success_Close()">
    </center>
</div>

<div id="Reset_Successfully_Dialogy" style="width:25%;">
    <center>
        <b>Configutation reset successfully</b>
        <br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Redirect_Window()">
    </center>
</div>

<div id="Process_Details" style="width:25%;">
    <center>
        <b>Process fail! Selected approval title already assigned to another level</b><br/><br/>
        <input type="button" class="art-button-green" value="Ok" onclick="Process_Details_Close()">
    </center>
</div>

<div id="Resert_Procurement_Conf" style="width:25%;">
        Are you sure you want to reset procurement configuration?<br/> 
        This process will remove all approval titles & levels defined including procurement levels assigned to employees
    <br/>
    <center><span style="color: red" id="Invalid_Message_Area"></span></center>
    <table width="90%">
        <tr>
            <td width="30%"><b>Supervisor Username</b></td>
            <td colspan="3" width="70%"><input type="text" id="Username" autocomplete='off' placeholder="Supervisor Username" oninput="Clear_Message();" onkeyup="Clear_Message();"></td>
        </tr>
        <tr>
            <td width="30%"><b>Supervisor Password</b></td>
            <td width="70%"><input type="password" id="Password" autocomplete='off' placeholder="Supervisor Password" oninput="Clear_Message();" onkeyup="Clear_Message();"></td>
            <td><input type="button" class="art-button-green" value="Reset Configuration" onclick="Verify_Supervisor_Input();"></td>
            <td><input type="button" class="art-button-green" value="Cancel Process" onclick="Close_Dialogy();"></td>
        </tr>
    </table>
    <br/>
</div>

<script type="text/javascript">
    function Preview_Employee_Details(temp){
        var Approval_Title = document.getElementById(temp).value;
        var rs = confirm("System detects some employees assigned to previous approval title\nAny changes to this approval title automatically will remove all employees assigned to previons approval title\nCLick OK to proceed");
        if(rs == true){
            if(window.XMLHttpRequest){
                myObjectPreview = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPreview.overrideMimeType('text/xml');
            }
            myObjectPreview.onreadystatechange = function (){
                data262 = myObjectPreview.responseText;
                if (myObjectPreview.readyState == 4) {
                    var feedback = data262;
                    if(feedback == 'yes'){
                        $("#Employee_Details").dialog("open");
                    }
                }
            }; //specify name of function that will handle server response........
            
            myObjectPreview.open('GET','Preview_Employee_Details.php?temp='+temp+'&Approval_Title='+Approval_Title,true);
            myObjectPreview.send();
        }
   }
</script>
<script>
    function Submit_Data(){
        var Approval_Level = document.getElementById("Approval_Level").value;
        if(Approval_Level == null || Approval_Level == ''){
            document.getElementById("Approval_Level").focus();
            document.getElementById("Approval_Level").style = 'border: 3px solid red';
        }else if(Approval_Level < 1){
            alert("Approval Level Must Be Greater Than Zero");
            document.getElementById("Approval_Level").value = '';
            document.getElementById("Approval_Level").focus();
        }else if(Approval_Level > 5){
            alert("Approval Level Must Be Less Than Six");
            document.getElementById("Approval_Level").value = '';
            document.getElementById("Approval_Level").focus();
        }else{
            if(window.XMLHttpRequest){
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    var feedback = data;
                    if(feedback == 'yes'){
                        //alert("Approval Level Updated Successfully");
                        $("#Employee_Details").dialog("open");
                        document.location = "configureapprovallevel.php?ConfigureApprovalLevel=ConfigureApprovalLevelThisPage";
                    }else{
                        alert("Process Fail! Please Try Again");
                    }
                }
            }; //specify name of function that will handle server response........
            
            myObject.open('GET','Insert_Confige_Approval.php?Approval_Level='+Approval_Level,true);
            myObject.send();        
        }
    }
</script>

<script type="text/javascript">
    function Verify_Update_Approval_Level(temp){
        var Approval_Title = document.getElementById(temp).value;
        if(window.XMLHttpRequest){
            myObjectVerify = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectVerify.overrideMimeType('text/xml');
        }
        myObjectVerify.onreadystatechange = function (){
            data133 = myObjectVerify.responseText;
            if (myObjectVerify.readyState == 4) {

                var feedback = data133;
                if(feedback == 'yes'){
                    Preview_Employee_Details(temp);
                }else if(feedback == 'no'){
                   Update_Approval_Level(temp);
                }
            }
        }; //specify name of function that will handle server response........
        
        myObjectVerify.open('GET','Verify_Update_Confige_Approval.php?Approval_Title='+Approval_Title+'&temp='+temp,true);
        myObjectVerify.send();
    }
</script>

<script type="text/javascript">
    function Update_Approval_Level(temp){
        var Approval_Title = document.getElementById(temp).value;
        if(window.XMLHttpRequest){
            myObjectUpdate = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdate.overrideMimeType('text/xml');
        }
        myObjectUpdate.onreadystatechange = function (){
            data1 = myObjectUpdate.responseText;
            if (myObjectUpdate.readyState == 4) {
                var feedback = data1;
                if(feedback == 'yes'){
                   //alert("Approval title updated successfully");
                    $("#Approval_Success").dialog("open");
                }else if(feedback == 'Available'){
                    $("#Process_Details").dialog("open");
                    //alert("Process fail! Selected approval title already assigned to another level");
                   Refresh_Title_List(temp);
                }else{
                    alert("Process fail!. Please try again.");
                }
            }
        }; //specify name of function that will handle server response........
        
        myObjectUpdate.open('GET','Update_Confige_Approval.php?Approval_Title='+Approval_Title+'&temp='+temp,true);
        myObjectUpdate.send();
    }
</script>

<script type="text/javascript">
    function Refresh_Title_List(temp){
        if(window.XMLHttpRequest){
            myObjectUpdateMenu = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectUpdateMenu = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateMenu.overrideMimeType('text/xml');
        }
        myObjectUpdateMenu.onreadystatechange = function (){
            data100 = myObjectUpdateMenu.responseText;
            if (myObjectUpdateMenu.readyState == 4) {
                document.getElementById("Label_"+temp).innerHTML = data100;
            }
        }; //specify name of function that will handle server response........
        
        myObjectUpdateMenu.open('GET','Update_Confige_Approval_Menu.php?temp='+temp,true);
        myObjectUpdateMenu.send();
    }
</script>

<script type="text/javascript">
    function Clear_Message(){
        document.getElementById("Invalid_Message_Area").innerHTML = '';
    }
</script>
<script type="text/javascript">
    function Verify_Supervisor_Input(){
        var Username = document.getElementById("Username").value;
        var pwd = document.getElementById("Password").value;
        if(Username != '' && Username != null && pwd != '' && pwd != null){
            if(window.XMLHttpRequest){
                myObjectVerfySupervisor = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectVerfySupervisor = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectVerfySupervisor.overrideMimeType('text/xml');
            }
            myObjectVerfySupervisor.onreadystatechange = function (){
                data1009 = myObjectVerfySupervisor.responseText;
                if (myObjectVerfySupervisor.readyState == 4) {
                    var feedback = data1009;
                    if(feedback == 'yes'){
                        Reset_Configuration();
                    }else{
                        document.getElementById("Invalid_Message_Area").innerHTML = "Invalid username or password";
                        document.getElementById("Username").value = '';
                        document.getElementById("Password").value = '';
                        document.getElementById("Username").style = 'border: 3px solid red';
                        document.getElementById("Password").style = 'border: 3px solid red';
                    }
                }
            }; //specify name of function that will handle server response........
            
            myObjectVerfySupervisor.open('GET','Verify_Supervisor_Input.php?Username='+Username+'&Password='+pwd,true);
            myObjectVerfySupervisor.send();
        }else{
            if(Username == null || Username == ''){
                document.getElementById("Username").style = 'border: 3px solid red';
            }else{
                document.getElementById("Username").style = 'border: 3px solid white';
            }

            if(pwd == null || pwd == ''){
                document.getElementById("Password").style = 'border: 3px solid red';
            }else{
                document.getElementById("Password").style = 'border: 3px solid white';
            }
        }
    }
</script>

<script type="text/javascript">
    function Redirect_Window(){
        document.location = 'configureapprovallevel.php?ConfigureApprovalLevel=ConfigureApprovalLevelThisPage';
    }
</script>
<script type="text/javascript">
    function Reset_Configuration(){
        if(window.XMLHttpRequest){
            myObjectResetConf = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectResetConf = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectResetConf.overrideMimeType('text/xml');
        }
        myObjectResetConf.onreadystatechange = function (){
            data1010 = myObjectResetConf.responseText;
            if (myObjectResetConf.readyState == 4) {
                $("#Resert_Procurement_Conf").dialog("close");
                $("#Reset_Successfully_Dialogy").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectResetConf.open('GET','Reset_Procurement_Configuration.php',true);
        myObjectResetConf.send();
    }
</script>

<script type="text/javascript">
    function Close_Dialogy(){
        document.getElementById("Invalid_Message_Area").innerHTML = "";
        $("#Resert_Procurement_Conf").dialog("close");
    }
</script>

<script type="text/javascript">
    function Employee_Details_Close(){
        $("#Employee_Details").dialog("close");
    }
</script>

<script type="text/javascript">
    function Approval_Success_Close(){
        $("#Approval_Success").dialog("close");
    }
</script>

<script type="text/javascript">
    function Process_Details_Close(){
        $("#Process_Details").dialog("close");
    }
</script>

<script type="text/javascript">
    function Reset_Procurement_Configuration(){
        $("#Resert_Procurement_Conf").dialog("open");
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
   $(document).ready(function(){
      $("#Employee_Details").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ Information!',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#Approval_Success").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ Information!',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#Process_Details").dialog({ autoOpen: false, width:'50%',height:150, title:'eHMS 2.0 ~ Information!',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#Resert_Procurement_Conf").dialog({ autoOpen: false, width:'70%',height:260, title:'eHMS 2.0 ~ Alert Message!',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
        $("#Reset_Successfully_Dialogy").dialog({ autoOpen: false, width:'30%',height:150, title:'eHMS 2.0 ~ Information!',modal: true});
        $('.ui-dialog-titlebar-close').click(function(){
            Redirect_Window();
        });
   });
</script>

<?php
    include("./includes/footer.php");
?>
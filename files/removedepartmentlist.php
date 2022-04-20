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
     
?>
<a href='departmentpage.php?Department=DepartmentThisPage' class='art-button-green'>BACK</a>
<br/><br/>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>

<fieldset>
    <center>
        <table width="50%">
            <tr>
                <td>
                    <input type="text" name="Department_Name" id="Department_Name" placeholder="~~~~ ~~~ Enter Department Name ~~~ ~~~~" autocomplete="off" style="text-align: center;" oninput="Search_Department()" onkeypress="Search_Department()">
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px; background-color: white;" id="Department_Area">  
    <legend align="left"><b>DEPARTMENTS LIST</b></legend>
    <center>
        <table width = "80%">
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>DEPARTMENT NAME</b></td>
                <td width="20%"><b>NATURE OF THE DEPARTMENT</b></td>
                <td width="15%" style="text-align: center;"><b>ACTION</b></td>
            </tr>
            <tr><td colspan="4"><hr></td></tr>
        <?php
            $select = mysqli_query($conn,"select * from tbl_department order by Department_Name") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);
            if($num > 0){
                $temp = 0;
                while ($data = mysqli_fetch_array($select)) {
        ?>
                    <tr id="sss">
                        <td><?php echo ++$temp; ?><b>.</b></td>
                        <td><?php echo ucwords(strtolower($data['Department_Name'])); ?></td>
                        <td><?php echo ucwords(strtolower($data['Department_Location'])); ?></td>
                        <td style="text-align: center;">
        <?php if(strtolower($data['Department_Status']) == 'active'){ ?>
                            <input type="button" name="Remove_Button" id="Remove_Button" class="art-button-green" value="DISABLE DEPARTMENT" onclick="Remove_Department_Verify(<?php echo $data['Department_ID']; ?>)">
        <?php }else{ ?>
                            <input type="button" name="Activate_Button" id="Activate_Button" class="art-button-green" value="ACTIVATE DEPARTMENT" onclick="Activate_Department(<?php echo $data['Department_ID']; ?>)">
        <?php } ?>
                        </td>
                    </tr>
        <?php
                }
            }
        ?>
        </table>
    </center>
</fieldset>
<div id="Confirm_Message">
    Are you sure you want to disable selected department?<br/><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;" id="Button_Area">
                
            </td>
        </tr>
    </table>
</div>

<div id="Error_Report">
    
</div>

<div id="Success_Message">
    Department Disabled Successfully<br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;" id="Button_Area">
                <input type="button" class="art-button-green" value="CLOSE" onclick="Close_Conf_Message()">
            </td>
        </tr>
    </table>
</div>

<div id="Activate_Department">
    Are you sure you want to activate selected department?
    <table width="100%">
        <tr>
            <td style="text-align: right;" id="Activate_Msd"></td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    function Close_Activate_Message(){
        $("#Activate_Department").dialog("close");
    }
</script>
<script type="text/javascript">
    function Close_Confirm_Message(){
        $("#Confirm_Message").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Conf_Message(){
        $("#Success_Message").dialog("close");
    }
</script>

<script type="text/javascript">
    function Remove_Department_Verify(Department_ID){
        if(window.XMLHttpRequest){
            myObjectRem = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRem.overrideMimeType('text/xml');
        }
        myObjectRem.onreadystatechange = function (){
            dataRem = myObjectRem.responseText;
            if (myObjectRem.readyState == 4) {
                var feedback = dataRem;
                if(feedback == 'none'){
                    document.getElementById("Button_Area").innerHTML = '<input type="button" class="art-button-green" value="YES" onclick="Disable_Department('+Department_ID+')">&nbsp;<input type="button" class="art-button-green" value="CANCEL" onclick="Close_Confirm_Message()">';
                    $("#Confirm_Message").dialog("open");
                }else if(feedback == 'both'){
                    document.getElementById("Error_Report").innerHTML = "Selected department contains sub departments as well as employees. <br/>Please remove or disable all sub departments and employees belong to selected department";
                    $("#Error_Report").dialog("open");
                }else if(feedback == 'sub'){
                    document.getElementById("Error_Report").innerHTML = "Selected department contains sub departments<br/>Please remove or disable all sub departments belong to selected department";
                    $("#Error_Report").dialog("open");
                }else if(feedback == 'emp'){
                    document.getElementById("Error_Report").innerHTML = "Selected department contains employees. <br/>Please remove all employees belong to selected department";
                    $("#Error_Report").dialog("open");
                }else{
                    document.getElementById("Error_Report").innerHTML = "Process Fail! Please try again";
                    $("#Error_Report").dialog("open");
                }
            }
        }; //specify name of function that will handle server response........
        
        myObjectRem.open('GET','Remove_Department_Verify.php?Department_ID='+Department_ID,true);
        myObjectRem.send();
    }
</script>

<script type="text/javascript">
    function Disable_Department(Department_ID){
        if(window.XMLHttpRequest){
            myObjectRemove = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemove.overrideMimeType('text/xml');
        }
        myObjectRemove.onreadystatechange = function (){
            dataDisable = myObjectRemove.responseText;
            if (myObjectRemove.readyState == 4) {
                Search_Department();
                $("#Confirm_Message").dialog("close");
                $("#Success_Message").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectRemove.open('GET','Disable_Department.php?Department_ID='+Department_ID,true);
        myObjectRemove.send();
    }
</script>

<script type="text/javascript">
    function Search_Department(){
        var Department_Name = document.getElementById("Department_Name").value;
        if(window.XMLHttpRequest){
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }
        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById("Department_Area").innerHTML = dataSearch;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearch.open('GET','Search_Department.php?Department_Name='+Department_Name,true);
        myObjectSearch.send();
    }
</script>

<script type="text/javascript">
    function Activate_Department(Department_ID){
        document.getElementById("Activate_Msd").innerHTML = '<input type="button" class="art-button-green" value="YES" onclick="Activate('+Department_ID+')">&nbsp;<input type="button" class="art-button-green" value="CANCEL" onclick="Close_Activate_Message()">';
        $("#Activate_Department").dialog("open");
    }
</script>

<script type="text/javascript">
    function Activate(Department_ID){
        if(window.XMLHttpRequest){
            myObjectActivate = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectActivate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectActivate.overrideMimeType('text/xml');
        }
        myObjectActivate.onreadystatechange = function (){
            dataActivate = myObjectActivate.responseText;
            if (myObjectActivate.readyState == 4) {
                Search_Department();
                $("#Activate_Department").dialog("close");
                alert("Department Activated Successfully");
            }
        }; //specify name of function that will handle server response........

        myObjectActivate.open('GET','Activate_Department.php?Department_ID='+Department_ID,true);
        myObjectActivate.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
   $(document).ready(function(){
      $("#Confirm_Message").dialog({ autoOpen: false, width:'45%',height:150, title:'CONFIRMATION MESSAGE',modal: true});
      $("#Error_Report").dialog({ autoOpen: false, width:'55%',height:140, title:'eHMS 2.0 ~ Message',modal: true});
      $("#Success_Message").dialog({ autoOpen: false, width:'45%',height:140, title:'eHMS 2.0 ~ Success Message',modal: true});
      $("#Activate_Department").dialog({ autoOpen: false, width:'45%',height:140, title:'eHMS 2.0 ~ Activate Message',modal: true});
    });
</script>

<?php
    include("./includes/footer.php");
?>
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
<a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>BACK</a>

<br/><br/>
<fieldset>
    <center>
        <table width="50%">
            <tr>
                <td>
                    <input type="text" name="Search_Value" id="Search_Value" placeholder = "~~~~ ~~~~ ~~~~ ~~~~ Enter Currency Name ~~~~ ~~~~ ~~~~ ~~~~" style="text-align: center;" autocomplete="off" onkeypress="Search_Currency()" oninput="Search_Currency()">
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 360px; background-color: white;' id='Currency_Area'>
    <legend align="right"><b>MULTI-CURRENCY CONFIGURATION</b></legend>
    <table width="100%">
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>CURRENCY NAME</b></td>
            <td><b>CURRENCY SYMBOL</b></td>
            <td><b>CONVERSION RATE</b></td>
            <td width="15%" style="text-align: center;" colspan="2"><b>ACTION</b></td>
        </tr>
        <tr><td colspan="6"><hr></td></tr>
    <?php
        $select = mysqli_query($conn,"select * from tbl_multi_currency order by  Currency_Name") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            $temp = 0;
            while($data = mysqli_fetch_array($select)){
    ?>
                <tr>
                    <td><?php echo ++$temp; ?></td>
                    <td><?php echo $data['Currency_Name']; ?></td>
                    <td><?php echo $data['Currency_Symbol']; ?></td>
                    <td><?php echo $data['Conversion_Rate']; ?></td>
                    <td><input type="button" value="EDIT" class="art-button-green" onclick="Edit_Currency(<?php echo $data['Currency_ID']; ?>)"></td>
                    <td><input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Currency(<?php echo $data['Currency_ID']; ?>)"></td>
                </tr>
    <?php
            }
        }
    ?>
    </table>
</fieldset>
<fieldset>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" name="Add_New_Currency" id="Add_New_Currency" class="art-button-green" value="ADD NEW CURRENCY" onclick="Add_New_Currency()">
            </td>
        </tr>
    </table>
</fieldset>

<div id="New_Currency">
    <table width="100%">
        <tr>
            <td width="20%" style="text-align: right;"><b>Currency Name</b></td>
            <td><b><input type="text" name="Currency_Name" id="Currency_Name" placeholder="Currency Name e.g Tanzania Shilings" autocomplete="off"></b></td>
        </tr>
        <tr>
            <td width="20%" style="text-align: right;"><b>Currency Symbol</b></td>
            <td><b><input type="text" name="Currency_Symbol" id="Currency_Symbol" placeholder="Currency Symbol e.g Tshs" autocomplete="off"></b></td>
        </tr>
        <tr>
            <td width="20%" style="text-align: right;"><b>Conversion Rate</b></td>
            <td><b><input type="text" name="Conversion_Rate" id="Conversion_Rate" placeholder="Conversion Rate" autocomplete="off"></b></td>
        </tr>
        <tr><td colspan="2" style="text-align: center;"><b><span style="color: #037CB0;" id="Error_Area" >&nbsp;</span></b></td></tr>
        <tr>
            <td colspan="2" style="text-align: right;">
                <input type="button" name="Calcel_Button" id="Calcel_Button" value="CANCEL" class="art-button-green" onclick="Close_Dialog()">
                <input type="button" name="Add_Button" id="Add_Button" value="ADD CURRENCY" class="art-button-green" onclick="Add_Currency()">
            </td>
        </tr>
    </table>
</div>

<div id="Edit_Currency">
    <center id="Edit_Currency_Area">
        
    </center>
</div>

<div id="Remove_Curr">
    <center id="Remove_Curr_Area">
        
    </center>
</div>

<script type="text/javascript">
    function Add_Currency(){
        var Currency_Name = document.getElementById("Currency_Name").value;
        var Currency_Symbol = document.getElementById("Currency_Symbol").value;
        var Conversion_Rate = document.getElementById("Conversion_Rate").value;

        if(Currency_Name != null && Currency_Name != '' && Currency_Symbol != null && Currency_Symbol != '' && Conversion_Rate != null && Conversion_Rate != ''){
            if(window.XMLHttpRequest) {
                myObjectAddCurrency = new XMLHttpRequest();
            }else if(window.ActiveXObject){ 
                myObjectAddCurrency = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddCurrency.overrideMimeType('text/xml');
            }

            myObjectAddCurrency.onreadystatechange = function (){
                dataAdd = myObjectAddCurrency.responseText;
                if (myObjectAddCurrency.readyState == 4) {
                    var mrejesho = dataAdd;
                    if(mrejesho == 'yes'){
                        Refresh_Currencies();
                        $("#New_Currency").dialog("close");
                        alert("Currency Added Successfully");
                    }else{
                        if(mrejesho == 'repetition'){
                            document.getElementById("Error_Area").innerHTML = 'Currency Name or Currency Symbol already in use please use unique name & symbol';
                        }else{
                            document.getElementById("Error_Area").innerHTML = 'Process fail! Please try again.';
                        }
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectAddCurrency.open('GET','Add_New_Currency.php?Currency_Name='+Currency_Name+'&Currency_Symbol='+Currency_Symbol+'&Conversion_Rate='+Conversion_Rate,true);
            myObjectAddCurrency.send();
        }else{

            if (Currency_Name == '' || Currency_Name == null) {
                document.getElementById("Currency_Name").style = 'border: 3px solid red';
            }else{
                document.getElementById("Currency_Name").style = 'border: 2px solid black';
            }

            if (Currency_Symbol == '' || Currency_Symbol == null) {
                document.getElementById("Currency_Symbol").style = 'border: 3px solid red';
            }else{
                document.getElementById("Currency_Symbol").style = 'border: 2px solid black';
            }

            if (Conversion_Rate == '' || Conversion_Rate == null) {
                document.getElementById("Conversion_Rate").style = 'border: 3px solid red';
            }else{
                document.getElementById("Conversion_Rate").style = 'border: 2px solid black';
            }
        }
    }
</script>


<script type="text/javascript">
    function Search_Currency(){
        var Search_Value = document.getElementById("Search_Value").value;
        if(window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function (){
            dataSearsh = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
                document.getElementById('Currency_Area').innerHTML = dataSearsh;
            }
        }; //specify name of function that will handle server response........
        myObjectSearch.open('GET','Search_Currency.php?Search_Value='+Search_Value,true);
        myObjectSearch.send();
    }
</script>

<script type="text/javascript">
    function Add_New_Currency(){
        document.getElementById("Currency_Name").value = '';
        document.getElementById("Currency_Symbol").value = '';
        document.getElementById("Conversion_Rate").value = '';
        $("#New_Currency").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Dialog(){
        document.getElementById("Currency_Name").value = '';
        document.getElementById("Currency_Symbol").value = '';
        document.getElementById("Conversion_Rate").value = '';
        $("#New_Currency").dialog("close");
    }
</script>

<script type="text/javascript">
    function Close_Edit_Dialog(){
        $("#Edit_Currency").dialog("close");
    }
</script>

<script type="text/javascript">
    function Edit_Currency(Currency_ID){
        if(window.XMLHttpRequest) {
            myObjectEditCurrency = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectEditCurrency = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectEditCurrency.overrideMimeType('text/xml');
        }

        myObjectEditCurrency.onreadystatechange = function (){
            dataEdit = myObjectEditCurrency.responseText;
            if (myObjectEditCurrency.readyState == 4) {
                document.getElementById('Edit_Currency_Area').innerHTML = dataEdit;
                $("#Edit_Currency").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectEditCurrency.open('GET','Edit_Currency.php?Currency_ID='+Currency_ID,true);
        myObjectEditCurrency.send();
    }
</script>

<script type="text/javascript">
    function Update_Currency(Currency_ID){
        var Currency_Name = document.getElementById("Currency_Name_Edit").value;
        var Currency_Symbol = document.getElementById("Currency_Symbol_Edit").value;
        var Conversion_Rate = document.getElementById("Conversion_Rate_Edit").value;

        if(Currency_Name != null && Currency_Name != '' && Currency_Symbol != null && Currency_Symbol != '' && Conversion_Rate != null && Conversion_Rate != ''){
            if(window.XMLHttpRequest) {
                myObjectEditCur = new XMLHttpRequest();
            }else if(window.ActiveXObject){ 
                myObjectEditCur = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectEditCur.overrideMimeType('text/xml');
            }

            myObjectEditCur.onreadystatechange = function (){
                dataUpdate = myObjectEditCur.responseText;
                if (myObjectEditCur.readyState == 4) {
                    var feedback = dataUpdate;
                    if(feedback == 'yes'){
                        Refresh_Currencies();
                        $("#Edit_Currency").dialog("close");
                        alert("Currency Updated Successfully");
                    }else{
                        if(feedback == 'repetition'){
                            document.getElementById("Error_Area2").innerHTML = 'Currency Name or Currency Symbol already in use please use unique name & symbol';
                        }else{
                            document.getElementById("Error_Area2").innerHTML = 'Process fail! Please try again.';
                        }
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectEditCur.open('GET','Update_Currency.php?Currency_Name='+Currency_Name+'&Currency_Symbol='+Currency_Symbol+'&Conversion_Rate='+Conversion_Rate+'&Currency_ID='+Currency_ID,true);
            myObjectEditCur.send();
        }else{

            if (Currency_Name == '' || Currency_Name == null) {
                document.getElementById("Currency_Name_Edit").style = 'border: 3px solid red';
            }else{
                document.getElementById("Currency_Name_Edit").style = 'border: 2px solid black';
            }

            if (Currency_Symbol == '' || Currency_Symbol == null) {
                document.getElementById("Currency_Symbol_Edit").style = 'border: 3px solid red';
            }else{
                document.getElementById("Currency_Symbol_Edit").style = 'border: 2px solid black';
            }

            if (Conversion_Rate == '' || Conversion_Rate == null) {
                document.getElementById("Conversion_Rate_Edit").style = 'border: 3px solid red';
            }else{
                document.getElementById("Conversion_Rate_Edit").style = 'border: 2px solid black';
            }
        }
    }
</script>

<script type="text/javascript">
    function Refresh_Currencies(){
        document.getElementById("Search_Value").value = '';
        if(window.XMLHttpRequest) {
            myObjectRefresh = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefresh.overrideMimeType('text/xml');
        }

        myObjectRefresh.onreadystatechange = function (){
            dataRefresh = myObjectRefresh.responseText;
            if (myObjectRefresh.readyState == 4) {
                document.getElementById('Currency_Area').innerHTML = dataRefresh;
            }
        }; //specify name of function that will handle server response........
        myObjectRefresh.open('GET','Refresh_Currencies.php',true);
        myObjectRefresh.send();
    }
</script>


<script type="text/javascript">
    function Remove_Currency(Currency_ID){
        if(window.XMLHttpRequest) {
            myObjectRem = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectRem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRem.overrideMimeType('text/xml');
        }

        myObjectRem.onreadystatechange = function (){
            dataRem = myObjectRem.responseText;
            if (myObjectRem.readyState == 4) {
                document.getElementById('Remove_Curr_Area').innerHTML = dataRem;
                $("#Remove_Curr").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectRem.open('GET','Remove_Currency_Details.php?Currency_ID='+Currency_ID,true);
        myObjectRem.send();
    }
</script>

<script type="text/javascript">
    function Cancel_Remove(){
        $("#Remove_Curr").dialog("close");
    }
</script>

<script type="text/javascript">
    function Remove_Selected_Currency(Currency_ID){
        if(window.XMLHttpRequest) {
            myObjectRemove = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemove.overrideMimeType('text/xml');
        }

        myObjectRemove.onreadystatechange = function (){
            dataRemove = myObjectRemove.responseText;
            if (myObjectRemove.readyState == 4) {
                Refresh_Currencies();
                $("#Remove_Curr").dialog("close");
            }
        }; //specify name of function that will handle server response........
        myObjectRemove.open('GET','Remove_Selected_Currency.php?Currency_ID='+Currency_ID,true);
        myObjectRemove.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<script>
   $(document).ready(function(){
      $("#New_Currency").dialog({ autoOpen: false, width:'45%',height:220, title:'ADD NEW CURRENCY',modal: true});
      $("#Edit_Currency").dialog({ autoOpen: false, width:'45%',height:220, title:'EDIT CURRENCY',modal: true});
      $("#Remove_Curr").dialog({ autoOpen: false, width:'45%',height:220, title:'REMOVE CURRENCY',modal: true});
   });
</script>

<?php
    include("./includes/footer.php");
?>
<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
//    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
//	if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
//    }else{
//	@session_destroy();
//	header("Location: ../index.php?InvalidPrivilege=yes");
//    }

    if(isset($_SESSION['userinfo'])){
        if(isset($_GET['From'])){
            $From = mysqli_real_escape_string($conn,$_GET['From']);
        }else{
            $From = "";
        }

        if ($From == "PhrmacyReports") {
            echo "<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>";
        } else if ($From == "StorageAndSupplyReports") {
            echo "<a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage' class='art-button-green'>BACK</a>";
        } else {
            if(isset($_GET['from_to']) && $_GET['from_to'] == "my_store") {
                echo "<a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage' class='art-button-green'>BACK</a>";
            } else {
                echo "<a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>";
            }
        }
    }

    include_once("./functions/items.php");
    include_once("./functions/scripts.php");
    include_once("./functions/department.php");
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<br/>
<style>
	table,tr,td{ border-collapse:collapse !important; }
	tr:hover{ background-color:#eeeeee; cursor:pointer; }
 </style>

<center>
    <fieldset>
        <table width="100%">
            <tr>
                <td width="10%" style="text-align: right;"><b>Period</b></td>
                <td colspan=2>
                    <table width=100%>
                        <tr>
                            <?php
                                $Today_Date = Get_Today_Date();
                                $This_Month_Start_Date = Get_This_Month_Start_Date();
                            ?>
                            <td> Start Date</td>
                            <td><input type="text" name="Start_Date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~"
                                       style="text-align: center;" value="<?php echo $This_Month_Start_Date; ?>"></td>
                            <td> End Date</td>
                            <td><input type="text" name="End_Date" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~"
                                       style="text-align: center;" value="<?php echo $Today_Date; ?>"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="10%" style="text-align: right;"><b>Classification</b></td>
                <td style='text-align: left;'>
                    <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)'>
                        <option selected='All'>All</option>
                        <?php
                        $Classification_List = Get_Item_Classification();
                        foreach($Classification_List as $Classification) {
                            echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='Get_Items_List_Filtered()' placeholder='~~~~ ~~~~ ~~~~ ~~~~ Enter Item Name ~~~~ ~~~~ ~~~~ ~~~~' style="text-align: center;">
                </td>
                <td style="text-align: right;">
                    <input type="button" name="Search" id="Search" value="SEARCH" class="art-button-green" onclick="Get_Items_List_Filtered();">
                    <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Stock_Details();">
                </td>
            </tr>
            <tr>
                <td width="10%" style="text-align: right;"><b>Department</b></td>
                <td style='text-align: left;'>
                    <select name="Sub_Department_ID" id="Sub_Department_ID" onchange="Get_Items_List_Filtered();">
                        <?php
                        $Sub_Department_List = Get_Stock_Ledge_Sub_Departments($Employee_ID);
                        foreach($Sub_Department_List as $Sub_Department) {
                            echo "<option value='{$Sub_Department['Sub_Department_ID']}' > {$Sub_Department['Sub_Department_Name']} </option>";
                        }
                        ?>
                    </select>
                </td>
                <td style='text-align: left;'>
                    <select name="num_records" id="num_records" onchange="Get_Items_List_Filtered();">
                        <option value="100">100 records</option>
                        <option value="500">500 records</option>
                        <option value="1000">1000 records</option>
                        <option value="all">All records</option>
                    </select>
                </td>
                
            </tr>
        </table>
    </fieldset>

    <fieldset style="background-color:white; height:430px; overflow-y: scroll;">
        <legend align='right' style="background-color:#006400;color:white;padding:5px;">
            <b>STOCK DETAILS SUMMARY</b>
        </legend>
        <table width="100%" id='Items_Fieldset'> </table>
    </fieldset>
    <b style="color: #0079AE;">CURRENTLY (AVERAGE PRICE = LAST BUYING PRICE)</b>
</center>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
        $('select').select2();

        getItemsList("all");
    });
</script>
<style> .select2 { width: 100% !important; } </style>

<script>
    function Preview_Stock_Details(){
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        var Search_Value = document.getElementById("Search_Value").value;
         var num_records = document.getElementById("num_records").value;


        var URL = 'stockdetails_preview.php?Classification='+Item_Category_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Search_Value='+Search_Value+'&num_records='+num_records+'&Start_Date='+Start_Date+'&End_Date='+End_Date;
        OpenNewTab(URL);
    }
</script>

<script>
    function Get_Items_List_Filtered(){
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        var Search_Value = document.getElementById("Search_Value").value;
        var num_records = document.getElementById("num_records").value;
        document.getElementById('Items_Fieldset').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        
        if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
                //document.getElementById('Approval').readonly = 'readonly';
                document.getElementById('Items_Fieldset').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','stockdetails_search.php?Classification='+Item_Category_ID+'&Sub_Department_ID='+Sub_Department_ID+'&Search_Value='+Search_Value+'&num_records='+num_records+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
        myObject.send();
    }
</script>

<script>
    function getItemsList(Item_Category_ID){
        document.getElementById("Search_Value").value = '';
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        var num_records = document.getElementById("num_records").value;


        showPleaseWaitDialog();
        if(window.XMLHttpRequest) {
            myObject2 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject2.overrideMimeType('text/xml');
        }

        myObject2.onreadystatechange = function (){
		    data2 = myObject2.responseText;
		    if (myObject2.readyState == 4) {
			    document.getElementById('Items_Fieldset').innerHTML = data2;
		    }
            hidePleaseWaitDialog();
		}; //specify name of function that will handle server response........
        myObject2.open('GET','stockdetails_search.php?Classification='+Item_Category_ID+'&Sub_Department_ID='+Sub_Department_ID+'&num_records='+num_records+'&FilterCategory=True'+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
        myObject2.send();
    }
</script>

<?php include("./includes/footer.php"); ?>
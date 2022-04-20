<?php
    include("./includes/connection.php");
    include("./includes/header.php");

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
            echo "<a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>BACK</a>";
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
                            <td style='text-align:right;'> Start Date</td>
                            <td><input type="text" name="Start_Date" id="date" placeholder="~~~ ~~~ Start Date ~~~ ~~~"
                                       style="text-align: center;" value="<?php echo $This_Month_Start_Date; ?>"></td>
                            <td style="text-align: right;"> End Date</td>
                            <td><input type="text" name="End_Date" id="date2" placeholder="~~~ ~~~ End Date ~~~ ~~~"
                                       style="text-align: center;" value="<?php echo $Today_Date; ?>"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="10%" style="text-align: right;"><!--b>Classification</b--></td>
                <td style='text-align: left;'>
                    <!--select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)'>
                        <option selected='All'>All</option>
                        <?php
                        $Classification_List = Get_Item_Classification();
                        foreach($Classification_List as $Classification) {
                            echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                        }
                        ?>
                    </select-->
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td width="10%" style="text-align: right;"><b>Select Store</b></td>
                <td style='text-align: left;'>
                    <select name="Sub_Department_ID" id="Sub_Department_ID" onchange="Get_Items_List_Filtered();">
                        <?php
                        $Sub_Department_List = Get_Stock_Ledge_Sub_Departments($Employee_ID);
                        foreach($Sub_Department_List as $Sub_Department) {
                            echo "<option value='{$Sub_Department['Sub_Department_ID']}' > {$Sub_Department['Sub_Department_Name']} </option>";
                        }
                        ?>
                    </select>
                </td>Sub_Dep
                <td style="text-align: right;">
                    <span style="float: left;"><input type="text" name="rv_number_search" id="rv_number_search" placeholder="Search By RV-Number" style="text-align: center;" ></span>
                    <input type="button" name="Search" id="Search" value="SEARCH BY DATE" class="art-button-green" onclick="Get_Items_List_Filtered();">
                    <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Delivery_Note_Details();">
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="background-color:white; height:430px; overflow-y: scroll;">
        <legend align='right' style="background-color:#006400;color:white;padding:5px;">
            <b>DELIVERY NOTE DETAILS</b>
        </legend>
            <span style="background-color: white;"><b>CLICK ON RV NUMBER TO VIEW THE SPECIFIC RECEIVING GOODS REPORT</b></span>

        <table width="100%" id='Items_Fieldset'> </table>
    </fieldset>
</center>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
        /*$('select').select2();

        getItemsList("all");*/

        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sub_Department_ID=document.getElementById('Sub_Department_ID').value;
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
        myObject.open('GET','delivery_note_report_search.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObject.send();
    });
</script>

<script>
    function Preview_Delivery_Note_Details(){
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var rv_number_search=document.getElementById("rv_number_search").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

        if(rv_number_search.trim()!=='' && rv_number_search.trim()!==0){

            var URL = 'delivery_note_report_preview.php?FilterCategory=True&Start_Date='+Start_Date+'&End_Date='+End_Date+'&rv_number_search='+rv_number_search+'&Sub_Department_ID='+Sub_Department_ID;
            OpenNewTab(URL);
        }else{

            var URL = 'delivery_note_report_preview.php?FilterCategory=True&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sub_Department_ID='+Sub_Department_ID;
            OpenNewTab(URL);
        }

    }
</script>

<script>
    function Get_Items_List_Filtered(){
       // var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
        document.getElementById("rv_number_search").value='';
        var Sub_Department_ID=document.getElementById('Sub_Department_ID').value;
       // var Search_Value = document.getElementById("Search_Value").value;

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
        myObject.open('GET','delivery_note_report_search.php?FilterCategory=True&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sub_Department_ID='+Sub_Department_ID,true);
        myObject.send();
    }
</script>

    <!--    SEARCHING THE ORDER BY RV NUMBER-->
<script type="text/javascript">
    $("#rv_number_search").on('input',function(){
        var rv_number=$("#rv_number_search").val().trim();
        var Start_Date = $("#date").val();
        var End_Date = $("#date2").val();
        var Sub_Department_ID = $("#Sub_Department_ID").val();

        $.ajax({
            url:'delivery_note_report_search.php',
            type:'post',
            data:{Start_Date:Start_Date,End_Date:End_Date,rv_number:rv_number,Sub_Department_ID:Sub_Department_ID},
            success:function(results){
                document.getElementById('Items_Fieldset').innerHTML = results;
            }
        });
    });
</script>
    <!--   PREVIEWING THE CLICKED  RECEIVED ORDER    -->
<script type="text/javascript">
   function View_Report(e,grn,order_type){
        var rv_number=e.innerHTML;
   
         var URL = 'preview_order_by_rv.php?rv_number='+rv_number+'&search_type=specific&grn='+grn+'&order_type='+order_type;
            OpenNewTab(URL);
    }
</script>
<?php include("./includes/footer.php"); ?>
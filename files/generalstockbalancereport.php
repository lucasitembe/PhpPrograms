<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");

include_once("./functions/department.php");
include_once("./functions/items.php");
include_once("./functions/scripts.php");

if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
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
<?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
    <a href='stockreportconfiguration.php?StockReportConfiguration=StockReportConfigurationThisPage' class='art-button-green'>STOCK REPORT CONFIGURATION</a>
<?php  } ?>

<?php if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ ?>
    <a href='storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage' class='art-button-green'>BACK</a>
<?php  } ?>

    <br/><br/>
    <fieldset>
        <legend align='right' style="padding:5px;color:white;background-color: #006400;text-align:right;">
            <b>GENERAL STOCK BALANCE</b>
        </legend>

        <center>
            <table width="60%">
                <tr>

                    <td style="text-align: right;" width="20%"><b>CLASSIFICATION</b></td>
                    <td width="30%">
                        <select name='Classification' id='Classification' onchange="Get_Items_Filtered()">
                            <option selected='All'>All</option>
                            <?php
                            $Classification_List = Get_Item_Classification();
                            foreach($Classification_List as $Classification) {
                                echo "<option value='{$Classification['Name']}'> {$Classification['Description']} </option>";
                            }
                            ?>
                        </select>
                    </td>

                    <td style="text-align: right;" width="20%"><b>SUB DEPARTMENTS</b></td>
                    <td width="30%">
                        <select name="Sub_Department_ID" id="Sub_Department_ID" onchange="Get_Items_Filtered()">
                            <option selected="selected" value="0">All</option>
                            <?php
                            $Sub_Department_List = Get_Stock_Balance_Sub_Departments();
                            foreach($Sub_Department_List as $Sub_Department) {
                                echo "<option value='{$Sub_Department['Sub_Department_ID']}' > {$Sub_Department['Sub_Department_Name']} </option>";
                            }
                            ?>
                        </select>
                    </td>

                </tr>
            </table>
        </center>

        <center>
            <table width="60%">
                <tr>
                    <td width="60%">
                        <input type="text" name="Search_Value" id="Search_Value" style="text-align: center;" autocomplete="off" placeholder="~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~  Enter Item Name ~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~ ~~ " onkeypress="Get_Items_Filtered_Search();" oninput="Get_Items_Filtered_Search();" onkeyup="Get_Items_Filtered_Search();">
                    </td>
                    <td style="text-align: right;">
                        <input type="button" value="SEARCH" class="art-button-green" onclick="Get_Items_Filtered_Search();">
                        <input type="button" value="PREVIEW" class="art-button-green" onclick="Preview_Stock_Balance();">
                    </td>
                </tr>
            </table>
        </center>

    </fieldset>

    <fieldset style='overflow-y: scroll; height: 370px; background-color: white;' id='Items_Fieldset'>

    </fieldset>
<center><b style='color: #037CB0;'>BUYING PRICE = LAST BUYING PRICE</b></center>
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
        function Get_Items_Filtered(){
            var Classification = document.getElementById("Classification").value;
            var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
            document.getElementById("Search_Value").value = '';
            if(window.XMLHttpRequest){
                myObjectGetItems = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectGetItems = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItems.overrideMimeType('text/xml');
            }
            myObjectGetItems.onreadystatechange = function (){
                data22 = myObjectGetItems.responseText;
                if (myObjectGetItems.readyState == 4) {
                    document.getElementById('Items_Fieldset').innerHTML = data22;
                }
            }; //specify name of function that will handle server response........

            myObjectGetItems.open('GET','generalstockbalancereport_search.php?Classification='+Classification+'&Sub_Department_ID='+Sub_Department_ID,true);
            myObjectGetItems.send();
        }
    </script>

    <script type="text/javascript">
        function Get_Items_Filtered_Search(){
            var Classification = document.getElementById("Classification").value;
            var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
            var Search_Value = document.getElementById("Search_Value").value;
            if(Search_Value != null && Search_Value != ''){
                if(window.XMLHttpRequest){
                    myObjectGetItems = new XMLHttpRequest();
                }else if(window.ActiveXObject){
                    myObjectGetItems = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectGetItems.overrideMimeType('text/xml');
                }
                myObjectGetItems.onreadystatechange = function (){
                    data22 = myObjectGetItems.responseText;
                    if (myObjectGetItems.readyState == 4) {
                        document.getElementById('Items_Fieldset').innerHTML = data22;
                    }
                }; //specify name of function that will handle server response........

                myObjectGetItems.open('GET','generalstockbalancereport_search.php?Classification='+Classification+'&Sub_Department_ID='+Sub_Department_ID+'&Search_Value='+Search_Value,true);
                myObjectGetItems.send();
            }
        }
    </script>

    <script type="text/javascript">
        function Preview_Stock_Balance(){
            var Classification = document.getElementById("Classification").value;
            var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
            var Search_Value = document.getElementById("Search_Value").value;
            var URL = 'generalstockbalancereport_preview.php?Classification='+Classification+'&Sub_Department_ID='+Sub_Department_ID+'&Search_Value='+Search_Value;

            OpenNewTab(URL);
        }
    </script>
<?php
include("./includes/footer.php");
?>
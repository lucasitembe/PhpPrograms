<?php
include("./includes/header.php");
@session_start();
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
//    if (isset($_SESSION['userinfo'])) {
//        if (isset($_SESSION['userinfo']['General_Ledger'])) {
//            if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
//                header("Location: ./index.php?InvalidPrivilege=yes");
//            }
//        } else {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        @session_destroy();
//        header("Location: ../index.php?InvalidPrivilege=yes");
//    }
?>
<a href="storageandsupplyreports.php?StorageAndSupplyReports=StorageAndSupplyReportsThisPage" class="art-button-green">BACK</a>
<br/><br/>
<fieldset>
    <center>
    <table width="80%">
        <tr>
          <td style="width:40%">
            <input type="text" style="width:100%; text-align:center" id="Date_From" class="" name="date_from" value="" placeholder="Start Date">
          </td>

          <td style="width:30%">
            <input type="text" style="width:100%; text-align:center"  name="date_to" value="" id="Date_To" placeholder="End Date">
          </td>

          <td width="10%" style="text-align: right;"><b>Supplier Name</b></td>
            <td>
                <select name="Supplier_ID" id="Supplier_ID">
                    <option value="">~~~Select Supplier~~~</option>
            <?php
                $select = mysqli_query($conn,"select Supplier_ID, Supplier_Name from tbl_supplier order by Supplier_Name") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?>
                    <option value="<?php echo $data['Supplier_ID']; ?>"><?php echo ucwords(strtolower($data['Supplier_Name'])); ?></option>
            <?php
                    }
                }
            ?>
                </select>
            </td>
            <td style="text-align: center;" width="10%">
                <input style="text-align: center;" type="button" value="FILTER" class="art-button-green" onclick="Get_Details()">
            </td>
            <td style="text-align: center;" width="10%">
                <input style="text-align: center;" type="button" value="PREVIEW" class="art-button-green" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
    </center>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px;" id="Statement_Area">
    <legend align="left"><b>VENDER LEDGER STATEMENT</b></legend>
</fieldset>

<div id="Supplier_Missing">
    <center>Please Select Supplier</center>
</div>

<script type="text/javascript">
    function Preview_Report(){
        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Sub_Department_ID = "<?php echo $_SESSION['Storage_Info']['Sub_Department_ID'];?>";
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;

        if(Date_From == "" || Date_To == "") {
            alert("Fill both Date");
            exit;
        }

        if(Supplier_ID != null && Supplier_ID != ''){
            window.open("previewvenderstatement.php?Supplier_ID="+Supplier_ID+"&Date_From="+Date_From+"&Date_To="+Date_To+"&Sub_Department_ID="+Sub_Department_ID+"&PreviewVenderStatement=PreviewVenderStatementThisPage","_blank");
        }else{
            $("#Supplier_Missing").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Get_Details(){

        var Supplier_ID = document.getElementById("Supplier_ID").value;
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Sub_Department_ID = "<?php echo $_SESSION['Storage_Info']['Sub_Department_ID'];?>";

        if(Date_From == "" || Date_To == "") {
            alert("Fill both Date");
            exit;
        } else if(Supplier_ID != null && Supplier_ID != ''){
            document.getElementById('Statement_Area').innerHTML = '<legend align="left"><b>VENDER LEDGER STATEMENT</b></legend><div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            var startDate = $("#Date_From").val();
            var dateTo =$("#Date_To").val();
            // alert(dateTo)
            if(window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function (){
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('Statement_Area').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET','Vender_Ledger_Statement_Get_Details.php?Supplier_ID='+Supplier_ID+'&start_date='+startDate+'&end_date='+dateTo+'&Sub_Department_ID='+Sub_Department_ID,true);
            myObject.send();
        } else{
            alert("Fill a Supplier Name");
        }
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<!-- <script src="js/select2.min.js"></script> -->

<script>
    $(document).ready(function () {
        $('select').select2();
        $("#Supplier_Missing").dialog({autoOpen: false, width: "30%", height: 120, title: 'SUPPLIER MISSING!', modal: true});


        $('#Date_From,#start_date_op').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });

        $('#Date_From,#start_date_op').datetimepicker({value: '', step: 30});
        $('#Date_To,#end_date_op').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To,#end_date_op').datetimepicker({value: '', step: 30});
        $("#showdataResult").dialog({autoOpen: false, width: '98%', height: 550, title: 'PATIENT ORDERED ITEMS', modal: true});

    });
</script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php

include("./includes/footer.php");
?>

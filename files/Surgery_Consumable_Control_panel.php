<?php
    include("includes/header.php");
    include("includes/Surgery.Mode.php");

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Registration_ID = (isset($_GET['RI'])) ? $_GET['RI'] : 0;
    $Payment_Item_Cache_List_ID = (isset($_GET['PLID'])) ? $_GET['PLID'] : 0;
    $Sponsor_ID = (isset($_GET['Sp'])) ? $_GET['Sp'] : 0;
    $Session_Type = (isset($_GET['Session_Type'])) ? $_GET['Session_Type'] : 0;
    $Current_Sub_Department_Name = $_SESSION['Theater_Department_Name'];

    $Select_Store_Details = mysqli_query($conn, "SELECT Sub_Department_ID, Sub_Department_Name FROM tbl_sub_department sd, tbl_attached_theater att WHERE sd.Sub_Department_ID = att.Store_ID AND att.Theater_ID IN(SELECT Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Name = '$Current_Sub_Department_Name')") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_Store_Details)>0){
            while($pham = mysqli_fetch_assoc($Select_Store_Details)){
                $Sub_Department_ID = $pham['Sub_Department_ID'];
                $Pharmacy_Sub_Department_Name = $pham['Sub_Department_Name'];
            }
        }else{
            $Pharmacy_Sub_Department_Name = '<b>NO PHARMACY MERGED</b>';
        }
    
    if($Session_Type == 'Surgery'){
        $Session = 'Surgical';
    }elseif($Session_Type == 'Procedure'){
        $Session = 'PROCEDURE';
    }
    $Select_Item = mysqli_query($conn, "SELECT Product_Name, ilc.Item_ID FROM tbl_items it, tbl_item_list_cache ilc WHERE ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID' AND it.Item_ID = ilc.Item_ID") or die(mysqli_error($c));
        while($Item = mysqli_fetch_assoc($Select_Item)){
            $Product_Name = $Item['Product_Name'];
            $Item_ID = $Item['Item_ID'];
        }

?>
    
<a href='Theater_consumable_contorl.php?TheaterSetup=Setup&theater=yes' class='art-button-green'>BACK</a>

<br>

<fieldset>
    <legend aligN=center><?= strtoupper($Session) ?> MEDICINE & CONSUMABLE CONTROL</legend>
        <table class="table" style="background: #FFFFFF; width: 100%">
            <tr>
                <td><b>PATIENT NAME</b></td>
                <td><b>REGISTRATION No.</b></td>
                <td><b>AGE</b></td>
                <td><b>GENDER</b></td>
                <td><b>SPONSOR</b></td>
                <td><b>ADDRESS</b></td>        
            </tr>
            <tbody id="Patient_details">
            </tbody>
        </table>
</fieldset>
<fieldset>
<div class="box box-primary" style="height: 100px;overflow-y: scroll;overflow-x: hidden;">
    <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
        <tr>
            <td><h2>Service Name : <?= $Product_Name ?></h2></td>
            <td><h2>Pharmacy : <?= $Pharmacy_Sub_Department_Name ?></h2></td>
            <td><h2>Location : <?= $Current_Sub_Department_Name ?></h2></td>
        </tr>
    </table>

    <!-- <h2>Service Name :  ~~ Pharmacy : <?= $Pharmacy_Sub_Department_Name ?>  ~~ <?= $Session ?> Location : <?= $Current_Sub_Department_Name ?></h2> -->
        <center>
    <p style="margin:3px;color: #bd0d0d;font-weight:bold; font-size: 14px;"><i> Please Confirm Surgical Location and Merged Pharmacy before Processing this Request</i></p>
    </center>
</div>
<div class="box box-primary" style="height: 450px;overflow-y: scroll;overflow-x: hidden; margin-top: -20px;">
        <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
            <tr style='background: #dedede; position: static !important;' id='stickytypeheader'>
                <td style='width: 2%;'><b>SN</b></td>
                <td><b>PHARMACEUTICAL NAME</b></td>
                <td><b>UOM</b></td>
                <td style='width: 7%;text-align: right;'><b>BALANCE</b></td>
                <td style='width: 5%;text-align: right;'><b>PRICE</b></td>
                <td style='width: 10%;'><b>QUANTITY</b></td>
                <td style='width: 10%;text-align: right;'><b>SUB TOTAL</b></td>
            </tr>
        <tbody id='All_attached_Datas'>
        </tbody>
    </table>
</div>
<table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important; margin-top: -10px; padding: -40;">
        <tbody id='TotalCost'>
        </tbody>
</table>
</fieldset>
<div id="Biopsy_Form"  class="box box-primary" style="width:50%; background: #fff;">
<input type="text" name="Search Items" id="Product_Name" onkeyup="Search_Items()" style="width:100%; text-align: center; height: 45px;" placeholder="Search Product Name">
    <!-- <center id='' style="width:100%; background: #fff; margin-top: 10px;"> -->
<div class="box box-primary" style="width:100%; background: #fff; margin-top: 10px;">
        <table width=100% class="table table-collapse table-striped" value='' style='border-collapse: collapse !important; width: 100% !important; padding: 10; background: #fff;'>
            <tr style='background: #dedede; position: static !important;' id='stickytypeheader'>
                <td style='width: 3%;'><b>SN</b></td>
                <td><b>PHARMACEUTICAL NAME</b></td>
                <td><b>UOM</b></td>
                <td style='width: 10%;text-align: right;'><b>BALANCE</b></td>
                <td style='width: 10%;text-align: right;'><b>PRICE</b></td>
            </tr>
            <tbody id='Add_Postoperative_Area2' style="overflow-x: hidden; overflow-y: scroll;"></tbody>
        </table>
    </div>
</div>
<?php
include("includes/footer.php");
?>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
    $(document).ready(function() {
        GetPatientInfo();
        $("#Biopsy_Form").dialog({autoOpen: false, width: '70%', height: 650, title: 'SURGICAL MISSING ITEMS', modal: true});
    });

    function GetPatientInfo(){
        Registration_ID = '<?= $Registration_ID ?>';
        Action = 'Fetch Patient';

            // alert(Registration_ID);
        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Patient_details').innerHTML = dataPost;
                // alert(dataPost);
                GetAllAttachedMedicines();
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'includes/Surgery.request.handle.php?Action='+Action+'&Registration_ID='+Registration_ID, true);
        myObjectPost.send();
    }

    function GetAllAttachedMedicines(){
        Item_ID = '<?= $Item_ID ?>';
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';
        Sponsor_ID = '<?= $Sponsor_ID ?>';
        Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        Action = 'Fetch Medicine Attached';

            // alert(Registration_ID);
        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('All_attached_Datas').innerHTML = dataPost;
                Get_Total_Cost();
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'includes/Surgery.request.handle.php?Action='+Action+'&Sub_Department_ID='+Sub_Department_ID+'&Sponsor_ID='+Sponsor_ID+'&Item_ID='+Item_ID+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPost.send();
    }

    function Update_Quantity(Consumable_ID){
        Item_ID = '<?= $Item_ID ?>';
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';
        Sponsor_ID = '<?= $Sponsor_ID ?>';
        Action = 'Send Items';
        Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        Employee_ID = '<?= $Employee_ID ?>';
        Quantity = $("#Quantity"+Consumable_ID).val();
        Registration_ID = '<?= $Registration_ID ?>';
        Balance = $("#Balance"+Consumable_ID).val();
            if(Quantity == undefined){
                Quantity = 1;
            }
        // alert(typeof(Quantity));
        if(Sub_Department_ID == '' || Sub_Department_ID == undefined || Sub_Department_ID == 0){
            alert("PLEASE MERGE PHARMACY ASSOCIATED WITH THIS THEATHER BEFORE PROCEEDING!")
            exit();
        }
            if(Balance != undefined && Balance > 0){
                if(parseInt(Quantity) > parseInt(Balance)){
                    alert("The Balance Is Not Enough, Please Request before processing this Document");
                    exit();
                }else{
                    $.ajax({
                        type: "GET",
                        url: "includes/Surgery.request.handle.php",
                        data: {
                            Consumable_ID:Consumable_ID,
                            Sub_Department_ID:Sub_Department_ID,
                            Sponsor_ID:Sponsor_ID,
                            Action:Action,
                            Item_ID:Item_ID,
                            Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                            Control_Type:'Surgical Inclusive',
                            Employee_ID:Employee_ID,
                            Quantity:Quantity,
                            Registration_ID:Registration_ID
                        },
                        cache: false,
                        success: function (response) {
                            Calculate_Sub_Total(Consumable_ID,Quantity);
                            Get_Total_Cost();
                            GetAllAttachedMedicines();
                            // hold_data(Consumable_ID);
                        }
                    }); 
                }  
        }else{
            alert("The Selected Pharmaceutical Has No balance, Please request bafore processing This Document");
            exit();
        }
    }

    function Calculate_Sub_Total(Consumable_ID,Quantity) {
        Price = $("#Price"+Consumable_ID).val();
            var results = (Quantity * Price);
            let num = results.toFixed(2)
            // alert(Quantity);
           if (!isNaN(num)) {
            // results = results.toFixed(2);
               document.getElementById('Sub_Total'+Consumable_ID).value = num;
               Get_Total_Cost();
            //    alert(results);
           }
    }

    function Get_Total_Cost(){
        Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        Action = 'Get Total';

            // alert(Registration_ID);
        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('TotalCost').innerHTML = dataPost;
                // alert(dataPost);
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'includes/Surgery.request.handle.php?Action='+Action+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPost.send();
    }

    function Add_Items(){
        Payment_Item_Cache_List_ID = '<?php echo $Payment_Item_Cache_List_ID; ?>';
        Action = 'Get More Items';
        Datas = '';  


        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Add_Postoperative_Area2').innerHTML = dataPost;
                $("#Biopsy_Form").dialog("open");
                document.getElementById("Product_Name").value = Datas;
                Search_Items();
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'includes/Surgery.request.handle.php?Action='+Action+'&Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID, true);
        myObjectPost.send();
    }

    function Search_Items(){
        Product_Name = $("#Product_Name").val();
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';
        Sponsor_ID = '<?= $Sponsor_ID ?>';
        Employee_ID = '<?= $Employee_ID ?>';
        Action = 'Get More Items';

            $.ajax({
                type: "GET",
                url: "includes/Surgery.request.handle.php",
                data: {
                    Employee_ID:Employee_ID,
                    Sub_Department_ID:Sub_Department_ID,
                    Sponsor_ID:Sponsor_ID,
                    Product_Name:Product_Name,
                    Action:Action
                },
                cache: false,
                success: function (response) {
                    document.getElementById('Add_Postoperative_Area2').innerHTML = response;
                }
            });
    }

    function Add_Item(Item_ID){
        Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        Action = 'Add More Items';
        $.ajax({
            type: "GET",
            url: "url",
            data: "data",
            dataType: "dataType",
            success: function (response) {
                
            }
        });
    }

    function Submit_Consumable(){
        Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        Action = 'Check Details';
        if(confirm("You are about to Submit Pharmaceuticals consumed during this Surgery \n Did you confirm the Amount and Available Balance before Processing this Document?")){
            $.ajax({
                type: "GET",
                url: "includes/Surgery.request.handle.php",
                data: {
                    Action:Action,
                    Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID
                },
                cache: false,
                success: function (response) {
                    if(response != '' || response != undefined){
                            data = response;
                            post_to_store(data);
                        }else{
                            alert("You Can't Process this Empty Document n\ Please Fill the Required Fields or Leave it Blank");
                            exit();
                        }
                }
            });
        }
    }

    function post_to_store(data){
        Document_Number = $("#Control_ID").val();
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';
        Movement_Type = 'Inclusive Package';
        Employee_ID = '<?= $Employee_ID ?>';

        let Item_Object = {
            Document_Number:Document_Number,
            Sub_Department_ID: Sub_Department_ID,
            Movement_Type:Movement_Type,
            Employee_ID:Employee_ID,
            Items:data
        }

        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                deduct_qty_from_store:'deduct_qty_from_store',
                Item_Object:Item_Object
            },
            ache: false,
            success: function (response) {
                alert(response);
                // finalize_process(Document_Number);
                exit();
            }
        });
    }

    function finalize_process(Document_Number){
        Action = 'Submit Document';
        $.ajax({
            type: "GET",
            url: "includes/Surgery.request.handle.php",
            data: {
                Document_Number:Document_Number,
                Action:Action
            },
            cache: false,
            success: function (response) {
                if(response == 200){
                    alert("Consumable Documentation was Submitted Successfully!");
                    document.location = "Theater_consumable_contorl.php?TheaterSetup=Setup&theater=yes";
                }else{
                    alert("Consumable Documentation Failed To Submit, Please Contact Administrator for furter Assistance!");
                    exit();
                }
            }
        });
    }
</script>
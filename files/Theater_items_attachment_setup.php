<?php
    include("includes/header.php");
    include("includes/Surgery.Mode.php");
    
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Surgery = '';
    $response = json_decode(GetSubdepartmentForSurgery($conn,'Pharmacy','active'),true);
    $Items = json_decode(GetItemsForSurgery($conn,'Surgery',$Product_Name),true);

    // $Surgical = json_decode(GetSurgicalItems($conn,'Pharmacy',$Product_Name), true);
    // print_r($Surgical);
    // if(sizeof($Items) > 0){
        foreach($Items as $Surgical):
            $Item_Name = $Surgical['Product_Name'];
            $Item_ID = $Surgical['Item_ID'];

            $Surgery .= "<option value='".$Item_ID."'>".$Item_Name."</option>";
        endforeach;
    // print_r($Items);
    // print_r($Surgical);

?>
<a href="Theater_setup_Menu.php?TheaterSetup=Setup&theater=yes" class="art-button-green">BACK</a>

<br/>
<br/>

<fieldset>
    <legend align=center>SURGICAL PHARMACEUTICAL MANAGEMENT</legend>
    <div style="width: 49%; float: left;">
    <input type="text" name="Product_Name" id="Product_Name" onkeyup='search_Medicine()' style="width: 100%; text-align: center;" placeholder="Search By Name">
        <br><br>
        <div class="box box-primary" style="height: 535px;overflow-y: scroll;overflow-x: hidden">
            <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
                <tr style='background: #dedede;'>
                    <th style='width: 4%;'>ACTION</th>
                    <th>CONSUMABLE/MEDICAL ITEM</th>
                </tr>
                <?php
                // foreach($Items as $dets) :
                //     $Item_Name = $dets['Product_Name'];
                //     $Item_ID = $dets['Item_ID'];

                //     echo "<tr>
                //             <td><input type='radio' onclick='Add_Item(".$Item_ID.")'></td>";
                //     echo "<td>".$Item_Name."</td></tr>";
                // endforeach;
                ?>
                <tbody id='Search_Iframe'>

            </table>
        </div>
    </div>




    <div style="width: 49%; float: right;">
            <select name="Selected_Surgery" id="Selected_Surgery" onchange='Display_Merged_data()' style="width: 100%;">
            <?php echo $Surgery; ?>
        </select>
        <h2>Merged Pharmaceutical Into Items Surgical Items</h2>
        <div class="box box-primary" style="height: 515px;overflow-y: scroll;overflow-x: hidden">
            <table class="table table-collapse table-striped " style="border-collapse: collapse !important; width: 100% !important;">
                <tr style='background: #dedede;'>
                    <th style='width: 7%;text-align: left;'>SN</th>
                    <th style='text-align: left;'>CONSUMABLE/MEDICAL ITEM</th>
                    <th style='text-align: left; width: 10%;'>ACTION</th>
                </tr>
                <tbody id='Search_Iframe_Merged'>
            </table>
        </div>
    </div>
</fieldset>
<br>
<br>
<?php
include("includes/footer.php");
?>

<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 
<script src="js/select2.min.js"></script>


<script>
    $(document).ready(function() {
        $("#Selected_Surgery").select2();
        Display_Merged_data();
        search_Medicine();
    });

    function Add_Item(Item_ID){
        Selected_Surgery = $("#Selected_Surgery").val();
        Employee_ID = '<?= $Employee_ID ?>';
            if(confirm("Are You Sure You want To Merge This Item?")){
                $.ajax({
                type: "GET",
                url: "includes/Surgery.request.handle.php",
                data: {
                    Selected_Surgery:Selected_Surgery,
                    Item_ID:Item_ID,
                    Action: 'Merge Item',
                    Employee_ID:Employee_ID
                },
                cache: false,
                success: function (response) {
                        search_Medicine();
                }
            });
        }
    }

    function Remove_Dept(Attachement_ID){
        $.ajax({
            type: "GET",
            url: "includes/Surgery.request.handle.php",
            data: {
                Attachement_ID:Attachement_ID
            },
            cache: false,
            success: function (response) {
                // if(response == 200){
                //     alert("Surgical Department Location Merged Sucessfully!");
                    Display_Merged_data();
                // }else{
                    
                // }
            }
        });
    }

    function search_Medicine() {
        Product_Name = $("#Product_Name").val();
        Consultation_Type = 'Pharmacy';
        Action = 'Search';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Search_Iframe').innerHTML = dataPost;
                Display_Merged_data();
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'includes/Surgery.request.handle.php?Action='+Action+'&Consultation_Type='+Consultation_Type+'&Product_Name='+Product_Name, true);
        myObjectPost.send();
    }
    function Display_Merged_data() {
        Action = 'Fetch Merged';
        Selected_Surgery = $("#Selected_Surgery").val();

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Search_Iframe_Merged').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'includes/Surgery.request.handle.php?Action='+Action+'&Selected_Surgery='+Selected_Surgery, true);
        myObjectPost.send();
    }
    function Remove_Item(Data_ID){
        Action = 'Remove Surgery';
        if(confirm("Do want to remove Selected Item?")){
            $.ajax({
                type: "GET",
                url: "includes/Surgery.request.handle.php",
                data: {
                    Data_ID:Data_ID,
                    Action:Action
                },
                cache: false,
                success: function (response) {
                    if(response == 200){
                        Display_Merged_data();
                    }
                }
            });
        }
    }
</script>
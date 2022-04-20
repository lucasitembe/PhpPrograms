<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./functions/items.php");
include("./get_item_balance_for_particular_subdepartment.php");


if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 'Unknown Officer';
}


if (isset($_GET['jobcard_ID'])) {
    $Jobcard_ID = $_GET['jobcard_ID'];
}

if (isset($_GET['jobcard_ID'])) {
    $jobcard_ID = $_GET['jobcard_ID'];
}

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}

$get_details = mysqli_query($conn,"SELECT * from tbl_jobcards where Jobcard_ID = '$Jobcard_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($get_details);
if ($num > 0) {
    while ($row = mysqli_fetch_array($get_details)) {
        $Order_Description = $row['descriptions'];
        $Sub_Department_ID = $row['Sub_Department_ID'];
        $part_date = $row['part_date'];
    }
}
?>

<a href='assigned_requisition_engineering.php?section=Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>BACK</a>
<script type="text/javascript" language="javascript">
    function getItemListType(Type) {
        var Item_Category_Name = document.getElementById('Item_Category').value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        //   //getPrice();
        document.getElementById('BalanceNeeded').value = 'a';
        document.getElementById('BalanceStoreIssued').value = 'v';
        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemListType.php?Item_Category_Name=' + Item_Category_Name + '&Type=' + Type, true);
        mm.send();
    }
    function AJAXP2() {
        var data2 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data2;
    }
</script>

<form action='#' method='post' name='myForm' id='myForm' >
<?php
 $Jobcard_ID = mysqli_fetch_assoc(mysqli_query($conn,"select Jobcard_ID from tbl_jobcards where Jobcard_ID = '$jobcard_ID'"))['Jobcard_ID'];
?>
    <br/>
    <fieldset> <legend style="background-color:#006400;color:white" align='right'><b>Engineering Store Order JobCard No: <?php echo $Jobcard_ID?></b></legend>

        <style>
            table,tr,td{ border-collapse:collapse !important; /*border:none !important;*/ }
        </style>

        <table style="margin-top:5" width=100%>
            <tr>

                <td width='12%' style='text-align: right;'>Jobcard ID</td>
                <td width=5%>
                        <input type='text' name='Jobcard_ID' size=6 id='Jobcard_ID' readonly='readonly' value='<?php echo $Jobcard_ID; ?>'>
                </td>
                <td width='12%' style='text-align: right;'>Order Date</td>
                <td width='16%'>
 
                        <input type='text' readonly='readonly' name='Order_Date' id='Order_Date' value='<?php echo $part_date; ?>'>

                </td>
                <td style='text-align: right;'>Prepared By</td>
                <td>
                    <input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
                </td>
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Store Ordering</td>
                <td width='16%' id="Sub_Department_ID_Area">
                        <?php
                        //check pending Store Requestion
                            $Get_Store_Request = mysqli_query($conn,"SELECT Sub_Department_Name, sd.Sub_Department_ID from tbl_sub_department sd, tbl_jobcards rq where
                    		 									sd.Sub_Department_ID = rq.Sub_Department_ID and
                    		 									rq.Jobcard_ID = '$Jobcard_ID'") or die(mysqli_error($conn));
                            $nms = mysqli_num_rows($Get_Store_Request);
                            if ($nms > 0) {
                                while ($dt = mysqli_fetch_array($Get_Store_Request)) {
                                    echo "<input type='text' value='" . $dt['Sub_Department_Name'] . "' readonly='readonly'>";
                                }
                            }
                                ?>
                            <!--option selected="selected" value=""></option-->
                    
                </td>
                <td width='13%' style='text-align: right;'>
                    Order Description
                </td>
                <td colspan="4">
                    <?php
     
                        ?>
                        <input type='text' name='Order_Description' id='Order_Description' value='<?php echo $Order_Description; ?>' onclick='updateRequisitionDesc()' onkeyup='updateRequisitionDesc()'>
  
                </td>
            </tr>
        </table>
        </center>
    </fieldset>

    <script>
        function getItemsList(Item_Category_ID) {
            document.getElementById("Search_Value").value = '';
            /*document.getElementById("Item_Name").value = '';*/
            /*document.getElementById("Item_ID").value = '';*/

            Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            //alert(data);

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_Store_Order_List_Of_Items.php?Item_Category_ID=' + Item_Category_ID + '&Sub_Department_ID=' + Sub_Department_ID, true);
            myObject.send();
        }

        function getItemsListFiltered(Item_Name) {
            //document.getElementById("Item_Name").value = '';
            //document.getElementById("Item_ID").value = '';

            Sub_Department_ID = document.getElementById("Sub_Department_ID").value;

            var Item_Category_ID = document.getElementById("Item_Category_ID").value;
            // alert(Item_Category_ID);
            if (Item_Category_ID == '' || Item_Category_ID == null) {
                Item_Category_ID = 'All';
            }

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_Store_Order_List_Of_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name + '&Sub_Department_ID=' + Sub_Department_ID, true);
            myObject.send();
        }
    </script>
    <form action='#' method='POST'>
    <fieldset>
        <center>
        <style>
            table,tr,td{ border-collapse:collapse !important; /*border:none !important;*/ }
            .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
        
        .spare table tr th {
            background: gray;
            border: 1px solid #fff;
        }
        .spare table tr:nth-child(even){
            background-color: #eee;
        }
        .spare table tr:nth-child(odd){
            background-color: #fff;
        }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
                }

                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 14PX;
                }
        </style>
            <table  style="margin-top:5" width=100%>
                <tr>
                    <td>
                    <div class="spare">
                    <form action='#' method='POST'>
                    <input type="text"  name="Jobcard_ID" value="<?php echo $Jobcard_ID?>" hidden> 
                    <input type="text"  name="Jobcard_ID" value="<?php echo $Sub_Department_ID?>" hidden>
                    <button type="button" name="add_item" id='Jobcard_ID' class="btn btn-primary" onclick="mantainance_drugs()" >ADD ITEM(s)</button>
                        <table class="table" id='spare_list'>
                            <tr>
                                <th width="2%">S/N</th>
                                <th>ITEM(s) ORDERED</th>
                                <th width="7%">UOM</th>
                                <th width="7%">ITEM CODE</th>
                                <th width="7%">STORE BALANCE</th>
                                <th width="5%">QUANTITY</th>
                                <th width="10%">PRICE</th>
                                <th width="10%">TOTAL</th>
                                <th width="5%">ACTION</th>
                            </tr>
                            <tbody id="SpareConsumed">

                            </tbody>

                        </table>
                        </form>
                    </div>
                    <div id="drugdialog"></div>
                </td>
                    </td>
                </tr>
                <tr>
                    <td colslan='9'>&nbsp;</td>
                </tr>
                <tr>
                                <td colspan='6' style='text-align: center;'>
                                     <input type='submit' name='submit_form' id='submit_form' onclick='save_datas()' value='   SAVE INFORMATIONS   ' class='art-button-green'>
                                </td>
                            </tr>  
            </table>
        </center>
    </fieldset>
    </form>

<script>
//ADD SPARE PARTS
function mantainance_drugs(){
       $.ajax({
                type:'POST',
                url:'add_jobcard_items.php',           
                data:{add_maintanance:''},
                success:function(responce){
                    $("#drugdialog").dialog({
                        title: 'ADD SPARES/ITEMS FOR JOBCARD NO: <?php echo $Jobcard_ID?>',
                        width: 800, 
                        height: 600, 
                        modal: true
                        });
                    $("#drugdialog").html(responce);
                    diaplay_maintanance()
                }
            })
    }

    function diaplay_maintanance(){
            var Jobcard_ID = $("#Jobcard_ID").val();
            $.ajax({
                type: 'POST',
                url: 'add_jobcard_items.php',
                data: {Jobcard_ID:Jobcard_ID, select_maintanance:''},
                cache: false,
                success: function (responce){                  
                    $('#SpareConsumed').html(responce);
                }
            });
        }
        function add_maintanance(Item_ID){
            var Jobcard_ID = $("#Jobcard_ID").val();
          
            $.ajax({
                type: 'POST',
                url: 'add_jobcard_items.php',
                data: {Item_ID:Item_ID,Jobcard_ID:Jobcard_ID, insert_maintanance:''},
                cache: false,
                success: function (html){   
                    $("#drugdialog").dialog('close');               
                    diaplay_maintanance();
                    
                }
            });
        }
       
        function remove_maintanance(Jobcard_ID, Item_ID, Employee_ID){
            if(confirm("Are you Sure you want to remove This Item?")){
                $.ajax({
                    type: 'POST',
                    url: 'add_jobcard_items.php',
                    data: {Jobcard_ID:Jobcard_ID,Item_ID:Item_ID,Employee_ID:Employee_ID, removemaintanance:''},
                    success: function (responce){                  
                        diaplay_maintanance();
                    }
                });
            }
        }
        function update_maintanance_time(Jobcard_ID, Item_ID){
            var Price = $("#Price"+Item_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_jobcard_items.php',
                data: {Jobcard_ID:Jobcard_ID,Price:Price,Item_ID:Item_ID, updatetimemaintanance:''},
                success: function (responce){                 
                                       
                }
            });
        }
        function update_maintanance_Quantity(Jobcard_ID, Item_ID){
            var Quantity = $("#Quantity"+Item_ID).val();
            $.ajax({
                type: 'POST',
                url: 'add_jobcard_items.php',
                data: {Jobcard_ID:Jobcard_ID, Item_ID:Item_ID, Quantity:Quantity, updateQuantitymaintanance:''},
                success: function (responce){                  
                   
                }
            });
        }
        function search_maintanance_item(items){
            $.ajax({
                type: 'POST',
                url: 'add_jobcard_items.php',
                data: {items:items, search_maintanance_item:''},
                cache: false,
                success: function (html) {
                    console.log(html);
                    $('#Items_Fieldset').html(html);
                }
            });
        }


        function save_datas() {
            if(confirm("Are you sure you want to Complete this Jobcard List?")){
                alert("Jobcard list was Submitted successfully, Notify Head of Section for further Processing");
                document.location = "./assigned_requisition_engineering.php?section=Engineering_Works=Engineering_WorksThisPage";
            }
        }
    
    
    //END OF SPARE PARTS
 
    $(document).ready(function(){
          diaplay_maintanance();

    });
</script>

<?php
$update_requisition_for_engineering='';

 
if(isset($_POST['add_item'])){
       //$Requisition_ID=$_POST['Requisition_ID'];
       $Sub_Department_ID=$_POST['Sub_Department_ID'];


       
    if(!empty($Sub_Department_ID)){
       //  if(!empty($completed)){
           $update_requisition_for_engineering = "UPDATE tbl_jobcards SET Sub_Department_ID='$Sub_Department_ID' WHERE Jobcard_ID='$Jobcard_ID'";

     $save_result= mysqli_query($conn,$update_requisition_for_engineering);
     
       }
}
mysqli_close($conn);
?>

<?php 
include "./includes/footer.php";
?>
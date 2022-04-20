<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        //    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes' && $_SESSION['userinfo']['Reception_Works'] != 'yes' && $_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Pharmacy'] != 'yes'){
        //	header("Location: ./index.php?InvalidPrivilege=yes");
        //    }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$result = mysqli_query($conn,"SELECT * FROM tbl_item_category") or die(mysqli_error($conn));
$category = '<option></option>';

while ($row = mysqli_fetch_array($result)) {
    $category .= "<option value='" . $row['Item_Category_ID'] . "'>" . $row['Item_Category_Name'] . "</option>";
}
?>
<a href='edititemlist.php?EditItemList=EditItemListThisPage' class='art-button-green'>ADD PRICE</a>
<a href='addnewitemcategory.php?AddNewItem=AddNewItemThisPage' class='art-button-green'>ADD ITEM</a>
<a href='removecategorylist.php?RemoveCategory=RemoveCategoryThisForm' class='art-button-green'>REMOVE CATEGORY</a>

<?php
    if(isset($_GET['from']) && $_GET['from'] == "itemsConf") {
?>
<a href='edititemlist.php?EditItemList=EditItemListThisPage&from=itemsConf' class='art-button-green'>BACK</a>
<?php
    } else {
?>
<a href='itemsconfiguration.php?ItemsConfiguration=ItemsConfigurationThisPage' class='art-button-green'>BACK</a>
<?php
    }
?>

<script type="text/javascript">
    function retrieveItemsForCategory(category_id) {
        if (category_id !== '') {
            //alert(category_id);
            $(".searchSubcategory").attr("disabled", false);
            $(".searchSubcategory").attr("id", category_id);
            $.ajax({
                type: 'GET',
                url: "items_management_change.php",
                data: "category_id=" + category_id,
                success: function (data) {
                    if (data !== '') {
                        $('#itemsmanager').html(data);

                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        } else {
            $('#itemsmanager').html('');
            $(".searchSubcategory").attr("disabled", true);
            $(".searchSubcategory").val('');
        }
    }

    function categorysub(category_id, item_ID) {
        // alert(category_id+" "+item_ID);

        $.ajax({
            type: 'GET',
            url: "items_management_change.php",
            data: "updatesubcategory=true&categoryID=" + category_id + '&itemID=' + item_ID,
            success: function (data) {
                //alert(data);
                //if(data !==''){
                $('#' + item_ID).html(data);
                //}
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    function changeItemSubcategory(subcategoryID, itemID) {
        //alert($("#brandgeneric"+itemID).val());
        //alert(subcategoryID);
        //exit();
        var item_kind = $("#brandgeneric" + itemID).val();
        if (subcategoryID != '') {
            $.ajax({
                type: 'GET',
                url: "items_management_change.php",
                data: "changesubCategory=true&subcategoryID=" + subcategoryID + '&itemID=' + itemID + '&item_kind=' + item_kind,
                success: function (data) {
                    if (data == 1) {
                        alert("Item changed successfully");
                    } else {
                        alert("An error has occured.Try Again");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
    }

    function changeItemConsultationType(consultation_type, itemID) {
        //alert($("#brandgeneric"+itemID).val());
        //alert(subcategoryID);
        //exit();
        if (consultation_type != '') {
            $.ajax({
                type: 'GET',
                url: "items_management_change.php",
                data: "changesubConsultationType=true&consultation_type=" + consultation_type + '&itemID=' + itemID ,
                success: function (data) {
                    if (data == 1) {
                        alert("Item changed successfully");
                    } else {
                        alert("An error has occured.Try Again");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
    }

    function searchThisCategory(search_word) {
        var categoryID = $(".searchSubcategory").attr("id");
        //alert(categoryID+" "+search_word);
        $.ajax({
            type: 'GET',
            url: "items_management_change.php",
            data: "categoryID=" + categoryID + "&search_word=" + search_word,
            success: function (data) {
                if (data !== '') {
                    $('#itemsmanager').html(data);

                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
</script>
<br/><br/>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:yellow;
        cursor:pointer;
    }
</style> 

<div style="text-align:left">
    <select id="category" style="padding:5px;width:30%" onchange="retrieveItemsForCategory(this.value)">
        <?php echo $category; ?>
    </select>
    <input type="text" disabled="true"  style="padding:2px;width:30%"  class="searchSubcategory" onkeyup="searchThisCategory(this.value)" placeholder="Seach Item In This Category">

</div>
<fieldset width="100%" style="height:520px;overflow-y:scroll; ">

    <legend style="background-color:#006400;color:white;padding:12px;" align="right"><b>CATEGORIES MANAGEMENT</b></legend>

    <div style="height:377px"> 
        <table width="100%">
            <tr style="background-color:#006400;color:white;"><th >S/N</th><th>ITEM NAME</th><th>CONSULTATION TYPE</th><th>ITEM KIND</th><th>CATEGORIES</th><th>SUB CATEGORY</th></tr>

        </table>
        <table  width="100%" id="itemsmanager">


        </table>

    </div>
</fieldset>
<div style="text-align:left;font-size:20px;font-weight:bold" id="totalItem"></div>
<?php if (isset($_SESSION['total_items'])) {
    unset($_SESSION['total_items']);
} ?>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script 
<?php
include("./includes/footer.php");
?>
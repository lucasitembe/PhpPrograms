<?php
@session_start();
include("./includes/connection.php");
$temp = 1;
if (isset($_GET['Product_Name'])) {
    $Product_Name = $_GET['Product_Name'];
} else {
    $Product_Name = '';
}
$filter = '';

if (isset($_SESSION['Include_Non_Solid_Items']) && $_SESSION['Include_Non_Solid_Items'] == 'yes') {
    $filter .= "";
} else {
    $filter .= " and i.Can_Be_Sold = 'yes'";
}



$data = '';
$postedData = '';
$separator = '';
$isGeneral = 0;
$auto_item_update_api="";
?>

<?php
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'selectItems') {
        $item = $_POST['Item'];
        $sponsor = $_POST['Sponsor'];
        $Consultation_Type = $_POST['Consultation_Type'];
        $postedData = "action=" . $_POST['action'] . "&Sponsor=$sponsor&Item=$item";

        $sponsor_item_filter = '';
        $sp_query = mysqli_query($conn,"SELECT Guarantor_name,Sponsor_ID,item_update_api,auto_item_update_api FROM tbl_sponsor WHERE sponsor_id='$sponsor'") or die(mysqli_error($conn));

        if (mysqli_num_rows($sp_query) > 0) {
            $rowSp = mysqli_fetch_assoc($sp_query);
            $Guarantor_name = $rowSp['Guarantor_name'];
            $Sponsor_ID = $rowSp['Sponsor_ID'];
            $auto_item_update_api = $rowSp['auto_item_update_api'];

            if ($auto_item_update_api == '1') {
                $sponsor_item_filter = "";//" AND sponsor_id='$Sponsor_ID'";
            }
        }
        if($Consultation_Type!="All"){
           $filter.=" AND Consultation_Type='$Consultation_Type'"; 
        }
        if ($item == "All") {
            $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                         where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and i.Status='Available' and
                             isc.Item_category_ID = ic.Item_category_ID $filter $sponsor_item_filter LIMIT 50";
        } else {
            $qr = "select * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                    where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and i.Status='Available' and
                          isc.Item_category_ID = ic.Item_category_ID AND ic.Item_category_ID='" . $item . "' $filter $sponsor_item_filter LIMIT 50";
        }
    }

    $separator = 'TenganishaData&<a href="edititemlistPrint.php?' . $postedData . '" target="_blank" class="art-button-green" >Print Preview</a>';
} elseif (isset($_GET['action']) && $_GET['action'] == 'getItems') {
    $category_ID = $_GET['category_ID'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $search_word = $_GET['search_word'];
    $itemCode = $_GET['itemCode'];
    $filter2 = '';

    if(!empty($search_word)){
        $filter2 .= " AND Product_Name LIKE '%$search_word%'"; 
    }
    if(!empty($itemCode)){
        $filter2 .= " AND Product_Code = '$itemCode'"; 
    }

    $postedData = "action=getItems&Item=$category_ID&Sponsor=$Sponsor_ID&search_word=$search_word&itemCode=$itemCode";

    $sponsor_item_filter = '';
    $sp_query = mysqli_query($conn,"SELECT Guarantor_name,Sponsor_ID,item_update_api,auto_item_update_api FROM tbl_sponsor WHERE sponsor_id='$Sponsor_ID'") or die(mysqli_error($conn));

    if (mysqli_num_rows($sp_query) > 0) {
        $rowSp = mysqli_fetch_assoc($sp_query);
        $Guarantor_name = $rowSp['Guarantor_name'];
        $Sponsor_ID = $rowSp['Sponsor_ID'];
        $auto_item_update_api = $rowSp['auto_item_update_api'];

        if ($auto_item_update_api == '1') {
            $sponsor_item_filter = "";//" AND sponsor_id='$Sponsor_ID'";
        }
    }

    if ($category_ID == "All") {
        $qr = "SELECT * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                         where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and i.Status='Available' and
                             isc.Item_category_ID = ic.Item_category_ID $filter2  LIMIT 50";
    } else {
        $qr = "SELECT * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
                    where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and i.Status='Available' and
                          isc.Item_category_ID = ic.Item_category_ID AND ic.Item_category_ID='" . $category_ID . "' $filter2  LIMIT 50";
    }

    $separator = 'TenganishaData&<a href="edititemlistPrint.php?' . $postedData . '" target="_blank" class="art-button-green" >Print Preview</a>';
} else {
    $qr = "SELECT * from tbl_items i,tbl_item_subcategory isc, tbl_item_category ic
					where i.Item_Subcategory_ID = isc.Item_Subcategory_ID and i.Status='Available' and
					    isc.Item_category_ID = ic.Item_category_ID $filter LIMIT 50";
    $separator = '<a href="edititemlistPrint.php?action=selectItems&Sponsor=All&Item=All" target="_blank" class="art-button-green" >Print Preview</a>';
}
$data .= "<center><table width ='100%' id='show_Sponsor'>
         <thead><tr><td style='text-align:center;width: 5%'><b>SN</b></td>
            <td><b>CATEGORY</b></td>
		    <td><b>TYPE</b></td>
            <td><b>PRODUCT CODE</b></td>
            <td><b>ITEM CODE</b></td>
            <td><b>ITEM FOLIO NUMBER</b></td>
		    <td><b>PRODUCT NAME</b></td>
            <td style='width:5% !important; text-align: center;'><b>FAST TRACK PRICE</b></td>
            <td style='width:5% !important; text-align: center;'><b>ITEM PRICE</b></td>
            <td style='width:5%;'><b>ACTION</b></td>
            <td style='width:5%;'><b>LAST EDITED BY</b></td>
        </tr></thead><tbody>";

$select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $data .= "<tr><td style='text-align: center' id='thead'>" . $temp++ . "</td>";
    $data .= "<td><a href='edititem.php?Item_ID=" . $row['Item_ID'] . "&Sponsor_ID=".$Sponsor_ID."EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>" . $row['Item_Category_Name'] . "</a></td>";
    $data .= "<td><a href='edititem.php?Item_ID=" . $row['Item_ID'] . "&Sponsor_ID=".$Sponsor_ID."EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>" . $row['Consultation_Type'] . "</a></td>";
    $data .= "<td><a href='edititem.php?Item_ID=" . $row['Item_ID'] . "&Sponsor_ID=".$Sponsor_ID."EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>" . $row['Product_Code'] . "</a></td>";
    $data .= "<td><a href='edititem.php?Item_ID=" . $row['Item_ID'] . "&Sponsor_ID=".$Sponsor_ID."EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>" . $row['Facility_Product_Code'] . "</a></td>";
    $data .= "<td><a href='edititem.php?Item_ID=" . $row['Item_ID'] . "&Sponsor_ID=".$Sponsor_ID."EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>" . $row['item_folio_number'] . "</a></td>";
    $data .= "<td><a href='edititem.php?Item_ID=" . $row['Item_ID'] . "&Sponsor_ID=".$Sponsor_ID."EditItem=EditItemThisForm' target='_parent' style='text-decoration: none;'>" . $row['Product_Name'] . "</a></td>";
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'selectItems') {
            //die('doeneen');
            // $data .= $_GET['action'];
            $item = $_POST['Item'];
            $sponsor = $_POST['Sponsor'];


            if ($sponsor == 'All') {
                $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
            } else {
                $getPrice = mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='" . $row['Item_ID'] . "'");
            }
        }
    } elseif (isset($_GET['action'])) {
        if ($_GET['action'] == 'getItems') {
            //die('doeneen');
            // $data .= $_GET['action'];
            $item = $_GET['category_ID'];
            $sponsor = $_GET['Sponsor_ID'];
            $search_word = $_GET['search_word'];
            $itemCode = $_GET['itemCode'];
            $filter2 = '';
        
            if(isset($_GET['itemCode'])){
                $filter2 .= " AND Product_Name LIKE '%$search_word%'"; 
            }
            if(isset($_GET['search_word'])){
                $filter2 .= " AND Product_Code = '$itemCode'"; 
            }


            if ($sponsor == 'All') {
                $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
            } else {
                $getPrice = mysqli_query($conn,"SELECT * FROM tbl_item_price WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='" . $row['Item_ID'] . "'");
            }
        }
    } else {
        $getPrice = mysqli_query($conn,"SELECT * FROM tbl_general_item_price WHERE Item_ID='" . $row['Item_ID'] . "'");
    }

    $results = mysqli_fetch_assoc($getPrice);
    if (empty($results['Items_Price'])) {
        $results['Items_Price'] = 0;
    }

    //Get fast track price
    $slct = mysqli_query($conn,"SELECT Item_Price from tbl_fast_track_price where Item_ID = '" . $row['Item_ID'] . "'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if ($nm > 0) {
        $Pr = mysqli_fetch_assoc($slct);
        $Fast_Track_Price = $Pr['Item_Price'];
    } else {
        mysqli_query($conn,"INSERT into tbl_fast_track_price(Item_ID,Item_Price) values('" . $row['Item_ID'] . "','0')") or die(mysqli_error($conn));
        $Fast_Track_Price = 0;
    }

    $data .= "<td  ><input style='width:100px;' id='F_Item_" . $row['Item_ID'] . "' type='text' value='" . $Fast_Track_Price . "'></td>";
    $data .= "<td  ><input style='width:100px;' id='Item_" . $row['Item_ID'] . "' type='text' value='" . $results['Items_Price'] . "'></td>";
    $autoAPi = "<td><button onclick='updatePrice(" . $row['Item_ID'] . ")' id='" . $row['Item_ID'] . "'>Update</button></td>";
    if ($auto_item_update_api == '1') {
        $autoAPi = "<td>&nbsp;</td>";
    }
    $data .= $autoAPi;
    //GET THE LAST EMPLOYEE EDIT PRICE
    $last_edited_by="";
    $get_last_edit_employee_name= mysqli_query($conn,"SELECT Employee_Name FROM tbl_item_price ip INNER JOIN tbl_employee em ON ip.last_updated_by=em.Employee_ID WHERE Sponsor_ID='" . $sponsor . "' AND Item_ID='" . $row['Item_ID'] . "'");
    if(mysqli_num_rows($get_last_edit_employee_name)>0){
       $last_edited_by=mysqli_fetch_assoc($get_last_edit_employee_name)['Employee_Name']; 
    }
    $data .= "<td><b>$last_edited_by</b></td>";
    $data .= "</tr>";
}

$data .= '</tbody></table>
            </center>';

if ($isGeneral == 1) {
    echo $data;
} else {
    echo $data . $separator;
}
?>

<!--
<script src="css/jquery.js"></script>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script>
    $('#show_Sponsor').DataTable({
       "bJQueryUI":true     
    });
    
</script>-->


<!--
<script>
    $('.UpdatePrice').click(function(){
        var ItemID=$(this).attr('id');
        var Sponsor=$('#Sponsor_Name').val();
        var Item_Category_ID=$('#Item_Category_ID').val();
        var ItemVal=$('#Item_'+ItemID).val();
        $.ajax({
        type:'POST', 
        url:'requests/UpdateMultprice.php',
        data:'action=Update&ItemID='+ItemID+'&Sponsor='+Sponsor+'&Item_Category_ID='+Item_Category_ID+'&ItemVal='+ItemVal,
        cache:false,
        success:function(html){
           if(html=='success'){
               alert('Item updated successfully');
           } 
          // alert(html);
           // $('#showGeneral').html(html);
        }
       });
    });
</script>
-->

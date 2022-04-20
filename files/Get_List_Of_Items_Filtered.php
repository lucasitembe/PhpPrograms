<?php
include("./includes/connection.php");
if (isset($_GET['Item_Category_ID'])) {
    $Item_Category_ID = $_GET['Item_Category_ID'];
} else {
    $Item_Category_ID = 0;
}
if (isset($_GET['Item_Name'])) {
    $Item_Name = mysqli_real_escape_string($conn,$_GET['Item_Name']);
} else {
    $Item_Name = '';
}
if (isset($_GET['from_reception'])&&$_GET['from_reception']=="yes") {
    $from_reception ="c.can_be_used_on_registration='yes' and"; 
} else {
    $from_reception = "";
}
if(isset($_GET['nursecommunication'])){
    $nursecommunication = $_GET['nursecommunication'];
    if($nursecommunication =='fromnursecommunication'){
        $filter=" AND nurse_can_add='yes'";
    }
}else{
    $nursecommunication ='';
    $filter='';
}
$sponsor_item_filter = '';
if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];

    $sp_query = mysqli_query($conn,"SELECT Guarantor_name,Sponsor_ID,item_update_api,auto_item_update_api FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));

    if (mysqli_num_rows($sp_query) > 0) {
        $rowSp = mysqli_fetch_assoc($sp_query);
        $Guarantor_name = $rowSp['Guarantor_name'];
        $Sponsor_ID = $rowSp['Sponsor_ID'];
        $auto_item_update_api = $rowSp['auto_item_update_api'];
        $guarantor=$Guarantor_name;
        if ($auto_item_update_api == '1') {
            $sponsor_item_filter = '';//" AND sponsor_id='$Sponsor_ID'";
        }
    }
} else {
    $guarantor = '';
}

if ($Item_Category_ID == 'All') {
    $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and 
                        t.Visible_Status <> 'Others' and
                        t.Item_ID=ip.Item_ID and
                        t.Status = 'Available' and 
                        Product_Name like '%$Item_Name%' $sponsor_item_filter and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' and t.Item_Type<>'Pharmacy' $filter order by t.Product_Name limit 200";
} else {
    $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                    where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                        s.Item_Category_ID = c.Item_Category_ID and
                        c.Item_Category_ID = '$Item_Category_ID' and
                        t.Item_ID=ip.Item_ID and
                        t.Visible_Status <> 'Others' and
                        t.Status = 'Available' and 
                        Product_Name like '%$Item_Name%' $sponsor_item_filter and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' and t.Item_Type<>'Pharmacy' $filter order by t.Product_Name limit 200";
}


$result = mysqli_query($conn,$Select_Items);
?>


<table class="table table-condensed table-striped">
    <?php
    while ($row = mysqli_fetch_array($result)) {
        if ($auto_item_update_api == '1') {
           // echo "<script>alert('ndani')</script>";
            $item_ID = $row['Item_ID'];
            $queryPrice = mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$item_ID' AND  Sponsor_ID='$Sponsor_ID'");

            if (mysqli_num_rows($queryPrice) < 1) {
                continue;
            }
        }
        echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left; padding-left:5px;'>";
        ?>

        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>);
                Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $guarantor; ?>','<?php echo $Sponsor_ID; ?>')">

        <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
    }
    ?> 
</table>
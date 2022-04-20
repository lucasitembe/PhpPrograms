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


if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = 0;
}

if (isset($_GET['Type_Of_Check_In'])) {
    $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
} else {
    $Type_Of_Check_In = '';
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

        if ($auto_item_update_api == '1') {
            $sponsor_item_filter = ''; //" AND sponsor_id='$Sponsor_ID'";
        }
    }
} else {
    $Guarantor_Name = 0;
}

if (strtolower($Type_Of_Check_In) != 'radiology' && strtolower($Type_Of_Check_In) != 'procedure' && strtolower($Type_Of_Check_In) != 'laboratory') {
    if ($Item_Category_ID == 'All') {
        $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                t.Item_ID=ip.Item_ID and
                                (t.Consultation_type <> 'radiology' and t.Consultation_type <> 'procedure' and t.Consultation_type <> 'laboratory') and
                                Product_Name like '%$Item_Name%' $sponsor_item_filter and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0'
                                order by t.Product_Name";
    } else {
        $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                t.Item_ID=ip.Item_ID and
                                c.Item_Category_ID = '$Item_Category_ID' and
                                (t.Consultation_type <> 'radiology' and t.Consultation_type <> 'procedure' and t.Consultation_type <> 'laboratory') and
                                Product_Name like '%$Item_Name%' $sponsor_item_filter and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' order by t.Product_Name";
    }
} else {
    if ($Item_Category_ID == 'All') {
        $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                t.Item_ID=ip.Item_ID and
                                t.Consultation_type = '$Type_Of_Check_In' and
                                Product_Name like '%$Item_Name%' $sponsor_item_filter and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0'
                                order by t.Product_Name";
    } else {
        $Select_Items = "select * from tbl_items t, tbl_item_subcategory s, tbl_item_category c, tbl_item_price ip
                                where t.Item_Subcategory_ID = s.Item_Subcategory_ID and
                                s.Item_Category_ID = c.Item_Category_ID and
                                t.Item_ID=ip.Item_ID and
                                c.Item_Category_ID = '$Item_Category_ID' and
                                t.Consultation_type = '$Type_Of_Check_In' and
                                Product_Name like '%$Item_Name%' $sponsor_item_filter and ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' order by t.Product_Name";
    }
}

$result = mysqli_query($conn,$Select_Items);
?>

<table width=100%>
    <?php
    while ($row = mysqli_fetch_array($result)) {
        if ($auto_item_update_api == '1') {
            $item_ID = $row['Item_ID'];
            $queryPrice = mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$item_ID' AND  Sponsor_ID='$Sponsor_ID'");

            if (mysqli_num_rows($queryPrice) < 1) {
                continue;
            }
        }
        echo "<tr>
                    <td style='color:black; border:2px solid #ccc;text-align: left;'>";
        ?>

        <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?php echo $Sponsor_ID; ?>');">

        <?php
        echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</lable></td></tr>";
    }
    ?> 
</table>
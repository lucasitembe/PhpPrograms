<?php
session_start();
include("./includes/connection.php");
$Type_Of_Check_In = 'Pharmacy';

if (isset($_GET['Guarantor_Name'])) {
    $Guarantor_Name = $_GET['Guarantor_Name'];
} else {
    $Guarantor_Name = '';
}

//Get Sub_Department_ID
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

//Get Sub_Department_Name
$select = mysqli_query($conn, "select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = "";
}

?>

<style>
    table,
    tr,
    td {
        border: none !important;
    }

    tr:hover {
        background-color: #eeeeee;
        cursor: pointer;
    }
    
</style>
<table width=100% style='border-style: none;'>
    <tr>
        <td width=40%>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>
                        <b>Category : </b>
                        <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)' onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                            <option selected='selected' value="">All</option>
                            <?php

                            $data = mysqli_query($conn, "SELECT ic.Item_Category_Name, ic.Item_Category_ID
			    				    from tbl_item_category ic, tbl_item_subcategory isu, tbl_items i where
			                        ic.Item_category_ID = isu.Item_category_ID and
			                        i.Item_Subcategory_ID = isu.Item_Subcategory_ID and
			                        i.Consultation_Type = 'Pharmacy' and i.Status = 'Available' group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));

                            while ($row = mysqli_fetch_array($data)) {
                                echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <fieldset style='overflow-y: scroll; height: 270px;' id='New_Items_Fieldset'>
                            <table width=100%>
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID where Consultation_Type = 'Pharmacy' and Status = 'Available' AND ip.Sponsor_ID='$Sponsor_ID' and ip.Items_Price<>'0' order by Product_Name limit 100");



                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr><td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";

                                ?>

                                    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Item_Name(this.value,<?php echo $row['Item_ID']; ?>); Get_Item_Price(<?php echo $row['Item_ID']; ?>, '<?= $Sponsor_ID ?>');">

                                <?php
                                    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "'>" . $row['Product_Name'] . "</label></td></tr>";
                                }
                                ?>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <center><h3><?php echo strtoupper($Type_Of_Check_In); ?></h3></center><br/>
            <table width=100% border=0 style='font-size:12px'>
                <tr>
                    <td style='text-align: right;' width=30%>Item Name</td>
                    <td>
                        <input type='text' name='Item_Name' id='Item_Name' readyonly='readyonly' placeholder='Item Name'>
                        <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;' width=30%>Location</td>
                    <td>
                        <select name="Sub_Department_ID" id="Sub_Department_ID" style='width:100%'>
                            <option selected="selected" value="<?php echo $Sub_Department_ID; ?>"><?php echo $Sub_Department_Name; ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Price</td>
                    <td>
                        <input type='text' name='Price' id='Price' readonly='readonly' placeholder='Price'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Balance</td>
                    <td>
                        <input type='text' name='balance' readonly='readonly' id='balance'  placeholder='Balance'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Quantity</td>
                    <td>
                        <input type='text' name='Quantity' id='Quantity' value='1' placeholder='Quantity'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Dosage Duration(Days)</td>
                    <td>
                        <input type='number' style='width:100%' name='dosade_duration' id='dosade_duration' placeholder='Dosage Duration(Days)'>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan=2>
                        <textarea name='Comment' id='Comment' placeholder='Comment'></textarea>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan=2 style='text-align: center;'>
                        <br/>
                        <input type='button' name='Submit' id='Submit' class='art-button-green' style='float:left' value='ADD ITEM' onclick='Check_Dosage_Time()'>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
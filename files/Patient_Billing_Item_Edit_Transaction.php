<?php
session_start();
include("./includes/connection.php");

if (isset($_POST['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_POST['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = '';
}

if (isset($_POST['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = '';
}

$select = mysqli_query($conn,"select ppl.Check_In_Type,ppl.Item_ID,pp.Registration_ID,pp.Receipt_Date, i.Product_Name, ppl.Discount, pp.Sponsor_ID, pp.Billing_Type,
							ppl.Price, ppl.Quantity, ppl.Patient_Direction,i.Last_Buy_Price,
							ppl.Consultant_ID, ppl.Clinic_ID,pp.Patient_Payment_ID
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where 
							Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							i.Item_ID = ppl.Item_ID") or die(mysqli_error($conn));

$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Check_In_Type = $data['Check_In_Type'];
        $Product_Name = $data['Product_Name'];
        $Discount = $data['Discount'];
        $Price = $data['Price'];
        $Last_Buy_Price = $data['Last_Buy_Price'];
        $Quantity = $data['Quantity'];
        $Patient_Direction = $data['Patient_Direction'];
        $Consultant_ID = $data['Consultant_ID'];
        $Clinic_ID = $data['Clinic_ID'];
        $Check_In_Type = $data['Check_In_Type'];
        $Billing_Type = $data['Billing_Type'];
        $Sponsor_ID = $data['Sponsor_ID'];
        $Item_ID = $data['Item_ID'];
        $Patient_Payment_ID = $data['Patient_Payment_ID'];
        $Registration_ID = $data['Registration_ID'];
        $Receipt_Date = $data['Receipt_Date'];
    }
} else {
    $Check_In_Type = '';
    $Product_Name = '';
    $Discount = '';
    $Price = '';
    $Last_Buy_Price = "";
    $Quantity = '';
    $Patient_Direction = '';
    $Consultant_ID = '';
    $Clinic_ID = '';
    $Check_In_Type = '';
    $Billing_Type = '';
    $Sponsor_ID = '';
    $Item_ID = '';
    $Patient_Payment_ID = '';
    $Registration_ID = '';
    $Receipt_Date = '';
}


//get guarantor name
$get_guarantor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
$nmz = mysqli_num_rows($get_guarantor);
if ($nmz > 0) {
    while ($dtz = mysqli_fetch_array($get_guarantor)) {
        $Guarantor_Name = $dtz['Guarantor_Name'];
    }
} else {
    $Guarantor_Name = '';
}




//get consultant
if ($Consultant_ID > 0) {
    $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if ($nm > 0) {
        while ($dt = mysqli_fetch_array($select)) {
            $Consultant = $dt['Employee_Name'];
        }
    } else {
        $Consultant = '';
    }
} elseif ($Clinic_ID > 0) {
    $select = mysqli_query($conn,"select Clinic_Name from tbl_clinic where Clinic_ID = '$Clinic_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if ($nm > 0) {
        while ($dt = mysqli_fetch_array($select)) {
            $Consultant = $dt['Clinic_Name'];
        }
    } else {
        $Consultant = '';
    }
}

$admission_status = mysqli_query($conn,"SELECT Admission_Status FROM tbl_admission WHERE Registration_ID='$Registration_ID' ORDER BY Admission_Date_Time DESC LIMIT 1");
$row4 = mysqli_fetch_assoc($admission_status);
$admin_status = $row4['Admission_Status'];
?>

<table width="100%">

    <tr>
        <td style="text-align:left;width: 7%"><b>Billing Type</b></td>
                 <td style="text-align:left;width: 7%"><b>Sponsor</b></td>
		<td width="7%" style="text-align: left;"><b>Item Name</b></td>
        <td width="8%" style="text-align: right;"><b>Price</b></td>
        <td width="8%" style="text-align: right;"><b>Discount</b></td>
        <td width="8%" style="text-align: center;"><b>Quantity</b></td>
    </tr>
    <tr>

        <td>
            <select name="Billing_Type" id="Billing_Typez">
                <!--$admin_status-->

                <?php
                if ($Guarantor_Name == 'CASH' && $Billing_Type == 'Inpatient Cash') {
                    echo '<option value="Outpatient Credit"  >Outpatient Credit</option>';
                    echo '<option value="Outpatient Cash" >Outpatient Cash</option>';
                    echo '<option value="Inpatient Credit" >Inpatient Credit</option>';
                    echo '<option value="Inpatient Cash" >Inpatient Cash</option>';
                } elseif ($Guarantor_Name == 'CASH' && $Billing_Type == 'Outpatient Cash') {
                    echo '<option value="Outpatient Cash">Outpatient Cash</option>';
                    echo '<option value="Outpatient Credit"  >Outpatient Credit</option>';
                    echo '<option value="Inpatient Credit" >Inpatient Credit</option>';
                    echo '<option value="Inpatient Cash" >Inpatient Cash</option>';
                } else {
                    $outcre = '';
                    $outcash = '';
                    $incre = '';
                    $incash = '';
                    if ($Billing_Type == 'Outpatient Credit') {
                        $outcre = "selected";
                    }
                    if ($Billing_Type == 'Outpatient Cash') {
                        $outcash = "selected";
                    }
                    if ($Billing_Type == 'Inpatient Credit') {
                        $incre = "selected";
                    }
                    if ($Billing_Type == 'Inpatient Cash') {
                        $incash = "selected";
                    }
                    echo '<option value="Outpatient Credit" ' . $outcre . ' >Outpatient Credit</option>';
                    echo '<option value="Outpatient Cash" ' . $outcash . '>Outpatient Cash</option>';
                    echo '<option value="Inpatient Credit" ' . $incre . '>Inpatient Credit</option>';
                    echo '<option value="Inpatient Cash" ' . $incash . '>Inpatient Cash</option>';
                }
                ?>

            </select>
        </td>

                <td width="15%">
                    <select id="displaySponsor" style="width:100%">
                 
                        <?php
                         if($Guarantor_Name=='CASH' && ($Billing_Type=='Inpatient Cash' || $Billing_Type=='Outpatient Cash')){
                                echo '<option value="'.$Sponsor_ID.'">'.$Guarantor_Name.'</option>';
                         
                         }  else {
                          echo '<option value="'.$Sponsor_ID.'" gname="'.$Guarantor_Name.'">'.$Guarantor_Name.'</option>';
                         $guarantor_Name=  mysqli_query($conn,"select Guarantor_Name,Sponsor_ID from tbl_sponsor");
                             while($result=  mysqli_fetch_assoc($guarantor_Name)){
                                 echo '<option value="'.$result['Sponsor_ID'].'">'.$result['Guarantor_Name'].'</option>';
                             }
                         }
                         
                        ?>
                        
                    </select>
              </td>
	
		<td width="20%">
			<input type="text" name="Pro_Name" id="Pro_Name" readonly="readonly" value="<?php echo $Product_Name; ?>">
			<input type="hidden" name="Pro_ID" id="Pro_ID" value="0">
                        <input type="hidden" name="Sponsor_ID" id="Sponsor_ID" value="<?php echo  $Sponsor_ID;?>">
                        <input type="hidden" name="New_ID" id="New_ID" value="<?php echo $Item_ID;?>">
                        <input type="hidden" name="Old_ID" id="Old_ID" value="<?php echo $Item_ID;?>">
                        <input type="hidden" name="Receipt_Date" id="Receipt_Date" value="<?php echo $Receipt_Date;?>">
                        <input type="hidden" name="Item_List_ID" id="Item_List_ID" value="<?php echo $Patient_Payment_Item_List_ID;?>">
                        <input type="hidden" name="Patient_Payment_ID" id="Patient_Payment_ID" value="<?php echo  $Patient_Payment_ID;?>">
                        
                        
		</td>
		<td>
			<input type="text" name="Price" id="Edited_Price" value="<?php echo number_format($Price); ?>" readonly="readonly" style="text-align: right;">
		</td>
		<td>
			<input type="text" name="Price" id="Edited_Discount" value="<?php echo $Discount; ?>" style="text-align: right;">
		</td>
		<td>
                    <input type="text" name="Price" id="Edited_Quantity" value="<?php echo $Quantity; ?>" style="text-align: center;">
		</td>
	</tr>

    <tr><td colspan="7"><hr></td></tr>
    <tr>
        <td colspan="7" style="text-align: right;">
            <input type="button" name="Item_Button" id="Item_Button" value="CHANGE ITEM" class="art-button-green">
            <input type="button" name="Submit" id="Submits" value="UPDATE" class="art-button-green">
            <!--<input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Cancel_Edit_Process()">-->
        </td>
    </tr>
</table>


<div id="AllItem" style="display:none">

    <table width = 100%>
        <tr>
            <td style='text-align: center;'>
                <select name='Item_Category_ID' id='Item_Category_IDS'>
                    <option value="" selected='selected'></option>
                    <?php
                    $data = mysqli_query($conn,"select * from tbl_item_category order by Item_Category_Name");
                    while ($row = mysqli_fetch_array($data)) {
                        echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
                    }
                    ?>   
                </select>
            </td>
        </tr>
        <tr>
            <td><input type='text' id='Search_Product_Names' name='Search_Product_Name'  placeholder='~~~ ~~ Search Item Name ~~ ~~~' style='text-align: center;' autocomplete="off"></td>
        </tr>
        <tr>
            <td>
                <fieldset style='overflow-y: scroll; height: 300px;' id='Items_Area'>
                    <table width="100%" id="displayItems">
                        <?php
                        $result = mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE  Item_ID IN (SELECT Item_ID FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID') order by Product_Name LIMIT 100") or die(mysqli_error($conn));
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>
						<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                            ?>
                            <input type='radio' class="itemNum" name='selection' item='<?php echo $row['Item_ID']; ?>' id='<?php echo $row['Item_ID']; ?>a' value='<?php echo $row['Product_Name']; ?>' onclick="Get_Selected_Item(this.value,<?php echo $row['Item_ID']; ?>,<?php echo $Sponsor_ID; ?>)">
    <?php
    echo "</td><td style='color:black; border:2px solid #ccc;text-align: left;'><label for='" . $row['Item_ID'] . "a'>" . $row['Product_Name'] . "</label></td></tr>";
}
?> 
                    </table>
                </fieldset>		
            </td>
        </tr>
    </table> 

</div>

<script>
    $('#Item_Button').on('click', function () {
        $('#AllItem').dialog({
            modal: true,
            width: 400,
            minHeight: 400,
            resizable: true,
            draggable: true,
            title: "LIST OF ALL ITEMS",
        });

    });


    $('.itemNum').on('click', function () {
        var id = $(this).attr('item');
        var item = $(this).val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        $('#Pro_Name').val(item);
        $('#New_ID').val(id);
        $('#AllItem').dialog('close');
        $.ajax({
            type: 'POST',
            url: "requests/Sub_Item_price.php",
            data: "action=ViewItem&item=" + id + "&Sponsor=" + Sponsor_ID,
            success: function (html) {
                $('#Edited_Price').val(html);
            }
        });
    });


    $('#Submits').on('click', function (e) {
        e.stopImmediatePropagation();
        var Item_List_ID = $('#Item_List_ID').val();
        var Item_ID = $('#New_ID').val();
        var Pro_Name = $('#Pro_Name').val();
        var Old_ID = $('#Old_ID').val();
        var Receipt_Date = $('#Receipt_Date').val();
        var Price = $('#Edited_Price').val().replace(",", "");
        var Quantity = $('#Edited_Quantity').val().replace(",", "");
        var Last_Buy_Price = $('#Last_Buy_Price').val();
        var Discount = $('#Edited_Discount').val().replace(",", "");
        var Billing_Type = $('#Billing_Typez option:selected').val();
        var Patient_Payment_ID = $('#Patient_Payment_ID').val();
        var Sponsor_ID = $('#Sponsor_ID').val();
        var displaySponsor = $('#displaySponsor').val();
        var guarantorName = $('#displaySponsor').find(":selected").text();
        $.ajax({
            type: 'POST',
            url: "requests/Sub_Item_price.php",
            data: "action=UpdateItem&item=" + Item_ID +'&Pro_Name=' + Pro_Name + '&Last_Buy_Price=' + Last_Buy_Price +'&Price=' + Price + '&Quantity=' + Quantity + '&Discount=' + Discount + '&Item_List_ID=' + Item_List_ID + '&Billing_Type=' + Billing_Type + '&Patient_Payment_ID=' + Patient_Payment_ID + '&Sponsor_ID=' + Sponsor_ID + '&Old_ID=' + Old_ID + '&Receipt_Date=' + Receipt_Date + '&displaySponsor=' + displaySponsor + '&guarantorName=' + guarantorName,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
             
             alertMsg(html, "Success", 'information', 0, false, false, "", true, "Ok", true, .5, true);
              
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });
    });


    $('#Search_Product_Names').on('input', function () {
        var Billing_Type = $('#Billing_Typez option:selected').val();
        var category = $('#Item_Category_ID').val();
        var Sponsor_ID='<?= $Sponsor_ID ?>';
        var item = $(this).val();
        $.ajax({
            type: 'POST',
            url: "Search_Item_Edit_Names.php",
            data: "action=SearchProduct&item=" + item + '&category=' + category + '&Billing_Type=' + Billing_Type+'&Sponsor_ID='+Sponsor_ID,
            success: function (html) {
                $('#displayItems').html(html);
            }
        });

    });

    $('#Item_Category_IDS').on('change', function () {
        var category = $(this).val();
        var item = $('#Search_Product_Name').val();
        $.ajax({
            type: 'POST',
            url: "Search_Item_Edit_Names.php",
            data: "action=SearchProductCategory&item=" + item + '&category=' + category,
            success: function (html) {
                $('#displayItems').html(html);
            }
        });
    });
</script>

<script>
$("#displaySponsor").select2();
</script>




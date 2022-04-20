<script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') { ?>
    <center><b>List of Prescribed Items </b></center>
<?php } ?>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">

<script src="jquery.js"></script>
<script src="script.js"></script>
<script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
    .art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
    .ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
    .ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>
<script type='text/javascript'>
    function pharmacyQuantityUpdate(Payment_Item_Cache_List_ID, Quantity,checkbox) {
        var txtboxid =  $('#img_edit_qty_' + Payment_Item_Cache_List_ID).prev().attr('trg');
        var iterm_balance =  $('#balance'+Payment_Item_Cache_List_ID).val();

        //alert(iterm_balance);
        //var iterm_balance=$("#Item_Balance").val();

        if(parseInt(Quantity)>parseInt(iterm_balance)){
           alert("the quantity you have enter exceed the available on the stock");
           $('#'+Payment_Item_Cache_List_ID).val(0);
           calculate_Sub_Total(Payment_Item_Cache_List_ID);
        }else{

        $.ajax({
            type: 'GET',
            url: 'pharmacyQuantityUpdate.php',
            data: 'Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID + '&Quantity=' + Quantity,
            beforeSend: function (xhr) {
                $('.gty' + txtboxid).css('width', '75%');
                $('#img_edit_qty_' + Payment_Item_Cache_List_ID).show();
            },
            success: function (result) {
                if(parseInt(result) == 1){
                    update_total('<?php echo $Payment_Cache_ID; ?>', '<?php echo $Transaction_Type; ?>');
                }else{
                     alert('An error has occured.Please try again later!');
                     window.location.reload();
                }

            }, complete: function (jqXHR, textStatus) {
                $('.gty' + txtboxid).css('width', '100%');
                $('#img_edit_qty_' + Payment_Item_Cache_List_ID).hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
               alert('Failed to update quantity.Please check again the quantity entered if correct.');
               window.location.reload();
            }
        });
    }
    }



    function pharmacyDurationUpdate(Payment_Item_Cache_List_ID, duration,checkbox) {
        var txtboxid =  $('#img_edit_qty_' + Payment_Item_Cache_List_ID).prev().attr('trg');


       // var iterm_balance =  $('#balance'+Payment_Item_Cache_List_ID).val();

        //alert(iterm_balance);
        //var iterm_balance=$("#Item_Balance").val();

      //  if(parseInt(Quantity)>parseInt(iterm_balance)){


        $.ajax({
            type: 'GET',
            url: 'pharmacydurationUpdate.php',
            data: 'Payment_Item_Cache_List_ID=' + Payment_Item_Cache_List_ID + '&duration=' + duration,
            beforeSend: function (xhr) {
                $('.gty' + txtboxid).css('width', '75%');
                $('#img_edit_qty_' + Payment_Item_Cache_List_ID).show();
            },
            success: function (result) {
                if(parseInt(result) == 1){
                            // update_total('<?php echo $Payment_Cache_ID; ?>', '<?php echo $Transaction_Type; ?>');
                }else{
                     alert('An error has occured.Please try again later!');
                     window.location.reload();
                }

            }, complete: function (jqXHR, textStatus) {
                $('.gty' + txtboxid).css('width', '100%');
                $('#img_edit_qty_' + Payment_Item_Cache_List_ID).hide();
            }, error: function (jqXHR, textStatus, errorThrown) {
               alert('Failed to update duration.Please check again the quantity entered if correct.');
               window.location.reload();
            }
        });

    }
</script>

<script type="text/javascript">
    function calculate_Sub_Total(myval) {
        var Price = document.getElementById("P_" + myval).value;
        var Quantity = document.getElementById(myval).value;
        var Sub_Total = document.getElementById("Sub_" + myval).value;
        var Discount = document.getElementById("D_" + myval).value;

        Price = Price.replace(/,/g, '');
        var Total = (Price - Discount) * Quantity;

        Total = addCommas(Total);
        document.getElementById("Sub_" + myval).value = Total;
        update_total('<?php echo $Payment_Cache_ID; ?>', '<?php echo $Transaction_Type; ?>');
    }
</script>
<script>
    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>
<script type="text/javascript">
    function update_total(Payment_Cache_ID, Transaction_Type) {

        if (window.XMLHttpRequest) {
            myObjectUpdateTotal = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateTotal = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateTotal.overrideMimeType('text/xml');
        }

        myObjectUpdateTotal.onreadystatechange = function () {
            data6 = myObjectUpdateTotal.responseText;
            if (myObjectUpdateTotal.readyState == 4) {
             // alert(data6);
                document.getElementById('Total_Area').innerHTML = data6;
            }
        }; //specify name of function that will handle server response........

        myObjectUpdateTotal.open('GET', 'Pharmacy_Get_Grand_Total.php?Payment_Cache_ID=' + Payment_Cache_ID + '&Transaction_Type=' + Transaction_Type, true);
        myObjectUpdateTotal.send();
    }
</script>
<?php
@session_start();
include("./includes/connection.php");
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}
if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = '';
}

if (isset($_SESSION['Pharmacy'])) {
    $Sub_Department_Name = $_SESSION['Pharmacy'];
} else {
    $Sub_Department_Name = '';
}

if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

if(isset($_GET['Check_In_Type'])){
    $Check_In_Type=$_GET['Check_In_Type'];
}else{
   $Check_In_Type="";
}
$total = 0;
$temp = 1;
$data = '';


$dataAmount = '';
echo '<center><table width =100% border=0>';
if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
    echo '<tr><td colspan=10><hr></td></tr>';
    if (strtolower($Transaction_Status_Title) == 'not yet approved') {
        echo '<tr id="thead"><td style="text-align: center;" width=5%><b>Sn</b></td>
				<td><b>Item Name</b></td>
                                <td style="text-align: left;" width=15%><b>Brand Name</b></td>
				<td style="text-align: left;" width=9%><b>Specifications</b></td>

				<td style="text-align: right;" width=8%><b>Price</b></td>
				<td style="text-align: right;" width=8%><b>Discount</b></td>
				<td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left;" width=5%><b>duration(days)</b></td>
				<td style="text-align: center;" width=8%><b>Balance</b></td>
				<td style="text-align: center;" width=8%><b>Sub Total</b></td>
				<td style="text-align: center;" width=6%><b>Action</b></td>

                                </tr>';
    } else {
        echo '<tr id="thead"><td style="text-align: center;" width=5%><b>Sn</b></td>
				<td><b>Item Name</b></td>
                                <td style="text-align: left;" width=15%><b>Brand Name</b></td>
				<td style="text-align: left;" width=9%><b>Specifications</b></td>

				<td style="text-align: right;" width=8%><b>Price</b></td>
				<td style="text-align: right;" width=8%><b>Discount</b></td>
				<td style="text-align: center;" width=8%><b>Quantity</b></td>
                                <td style="text-align: left;" width=5%><b>duration(days)</b></td>
				<td style="text-align: center;" width=8%><b>Balance</b></td>
				<td style="text-align: center;" width=8%><b>Sub Total</b></td>

                                </tr>';
    }
    echo '<tr><td colspan=10><hr></td></tr>';
}

$select_Transaction_Items_Dispensed="";
if (isset($_GET['Status'])) {
    $removedStatus = $_GET['Status'];
    // echo $removedStatus;

    $select_Transaction_Items_removed = mysqli_query($conn,
            "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
        from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
            ilc.Transaction_Type = '$Transaction_Type'  and
                ilc.Check_In_Type='$Check_In_Type' and
                ilc.status = 'removed'");

    while ($row = mysqli_fetch_array($select_Transaction_Items_removed)) {
//             if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
//                 echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
//                 echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
//                 echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] = '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
//                 echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
//                 echo "<td>
//                 <input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
// ?>
                 <!-- // start changes  -->


  <!-- </td> -->

             <?php

//                 if ($row['Edited_Quantity'] == 0) {
//                     $Quantity = $row['Quantity'];
//                 } else {
//                     $Quantity = $row['Edited_Quantity'];
//                 }

//             ?>
             <!--calculate balance-->
             <?php
//             $Item_ID = $row['Item_ID'];
//             //get sub department id
//             if (isset($_SESSION['Pharmacy'])) {
//                 $Sub_Department_Name = $_SESSION['Pharmacy'];
//             } else {
//                 $Sub_Department_Name = '';
//             }

//             //get actual balance
//             $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
//                         Item_ID = '$Item_ID' and
//                         Sub_Department_ID =
//                             '$Sub_Department_ID'") or die(mysqli_error($conn));
//             $num = mysqli_num_rows($sql_get_balance);
//             if ($num > 0) {
//                 while ($dt = mysqli_fetch_array($sql_get_balance)) {
//                     $Item_Balance = $dt['Item_Balance'];
//                 }
//             } else {
//                 $Item_Balance = 0;
//             }
//             if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
//                 ?>
<!--                 <td style='text-align: right;' id='Balance' name='Balance'>
                    <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                    <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                </td>
                <td>
                    <input type='text' class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $row['dosage_duration']; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

 -->
              <!-- </td> -->
                <?php
         //        echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
         //        if ($no == 1) {
         //            echo '<td style="text-align: right;" width=8%>
         //          &nbsp;
         //       </td>';
         //        } else {
         //            echo '<td style="text-align: right;" width=8%>
         //                  <button type="button" class="removeItemFromCache art-button" onclick="removeitemphar(' . $row["Payment_Item_Cache_List_ID"] . ')">Re-Add</button>
         //                </td>';
         //        }
         //    }
         //    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
         //    $temp++;
         // echo "</tr>";



                // end changes
            }
        // }
}else if (strtolower($Transaction_Status_Title) == 'not yet approved'){
   $select_Transaction_Items_Dispensed = mysqli_query($conn,
        "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, its.Item_ID,ilc.dosage_duration,  ilc.Doctor_Comment, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
                            ilc.Check_In_Type='$Check_In_Type' and

			    ilc.Patient_Payment_ID = '$Patient_Payment_ID' and
				ilc.status = 'active'");


    $no = 0; 
//    $no = mysqli_num_rows($select_Transaction_Items_Dispensed); 
}else{


$select_Transaction_Items_Dispensed = mysqli_query($conn,
        "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, its.Item_ID,ilc.dosage_duration,  ilc.Doctor_Comment, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
                            ilc.Check_In_Type='$Check_In_Type' and

			    ilc.Patient_Payment_ID = '$Patient_Payment_ID' and
				ilc.status = 'dispensed'");


$no = mysqli_num_rows($select_Transaction_Items_Dispensed);
}
if ($no > 0) {

    //Check if there is active patient waiting

    $select_Transaction_Items_Active = mysqli_query($conn,
            "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type'  and
			    ilc.Check_In_Type='$Check_In_Type' and
				ilc.status = 'paid'");

    $no = mysqli_num_rows($select_Transaction_Items_Active);

    //display all medications that not approved
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Transaction_Items_Active)) {
                                             $Item_ID2=$row['Item_ID'];
            if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "'  style='text-align: center;' readonly='readonly'></td>";
                echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                     echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                $dosage_duration=$row['dosage_duration'];

            }
            ?>
            <?php if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') { ?>
                <td style='text-align:left;'>
                    <?php if ($row['Edited_Quantity'] == 0) {
                        $Quantity = $row['Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                    <?php } else {
                        $Quantity = $row['Edited_Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
 <?php } ?>
                </td>
                <td>
                    <input type='text' class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                </td>
            <?php
            } else {
                if ($row['Edited_Quantity'] == 0) {
                    $Quantity = $row['Quantity'];
                } else {
                    $Quantity = $row['Edited_Quantity'];
                }
            }
            ?>
            <!--calculate balance-->
            <?php
            $Item_ID = $row['Item_ID'];
            //get sub department id
            if (isset($_SESSION['Pharmacy'])) {
                $Sub_Department_Name = $_SESSION['Pharmacy'];
            } else {
                $Sub_Department_Name = '';
            }

            //get actual balance
            $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    '$Sub_Department_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($sql_get_balance);
            if ($num > 0) {
                while ($dt = mysqli_fetch_array($sql_get_balance)) {
                    $Item_Balance = $dt['Item_Balance'];
                }
            } else {
                $Item_Balance = 0;
            }
            if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                ?>
                <td style='text-align: right;' id='Balance' name='Balance'>
                    <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                    <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                </td>
                <?php
                echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                if ($no == 1) {
                    echo '<td style="text-align: right;" width=8%>
                  &nbsp;
               </td>';
                } else {
                    echo '<td style="text-align: right;" width=8%>
						  <button type="button" class="removeItemFromCache art-button" onclick="removeitemphar(' . $row["Payment_Item_Cache_List_ID"] . ')">Remove</button>
						</td>';
                }
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
            $temp++;
        } echo "</tr>";

        //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
        $Check_Items = "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type'  and
			    ilc.Check_In_Type='$Check_In_Type' and
				ilc.status = 'removed'";
        $Check_Items_Results = mysqli_query($conn,$Check_Items);
        $No_Of_Items = mysqli_num_rows($Check_Items_Results);
        if ($No_Of_Items > 0) {
            $dataAmount = "<td colspan=8 style='text-align: right;'><b> TOTAL 45: " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='openRemovedItemDialog()'>View Removed Items here</button>

                         </td>";
        } else {
            $dataAmount = "<td colspan=8 style='text-align: right;'><b> TOTAL 67: " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td>";
        }
    } else {
        //echo 'approved';
        //check if there is any paid
        $select_Transaction_Items_Paid = mysqli_query($conn,
                "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, its.Item_ID, ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    ilc.Check_In_Type='$Check_In_Type' and
					ilc.status = 'paid'");
        $no = mysqli_num_rows($select_Transaction_Items_Paid);
        // echo "<tr><td>MAlopa</td></tr>";
        if ($no > 0) {

            while ($row = mysqli_fetch_array($select_Transaction_Items_Paid)) {
                                    $Item_ID2=$row['Item_ID'];
                echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                echo "<td><input type='text' value='" . $row['Product_Name'] . "'></td>";
                     echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
               $dosage_duration=$row['dosage_duration'];
                ?>
                <td style='text-align:right;'>
                <?php if ($row['Edited_Quantity'] == 0) {
                    $Quantity = $row['Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                <?php } else {
                    $Quantity = $row['Edited_Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                <?php } ?>
                </td>
                <td>
                    <input type='text' class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                </td>
                <!--calculate balance-->
                <?php
                $Item_ID = $row['Item_ID'];
                //get sub department id
                if (isset($_SESSION['Pharmacy'])) {
                    $Sub_Department_Name = $_SESSION['Pharmacy'];
                } else {
                    $Sub_Department_Name = '';
                }

                //get actual balance
                $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($sql_get_balance);
                if ($num > 0) {
                    while ($dt = mysqli_fetch_array($sql_get_balance)) {
                        $Item_Balance = $dt['Item_Balance'];
                    }
                } else {
                    $Item_Balance = 0;
                }
                ?>
                <td style='text-align: right;' id='Balance' name='Balance'>
                                   <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                    <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                </td>
                <?php
                echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                $temp++;
            } echo "</tr>";
        } else {
            while($row = mysqli_fetch_array($select_Transaction_Items_Dispensed)) {
                if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                    echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                    echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                    echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                    echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                    echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                    echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
               $dosage_duration=$row['dosage_duration'];
                    }
                ?>
                <td style='text-align:right;'>
                <?php if ($row['Edited_Quantity'] == 0) {
                    $Quantity = $row['Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' readonly='readonly' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                <?php } else {
                    $Quantity = $row['Edited_Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' readonly='readonly' onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>',this.value)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                            <?php } ?>
                </td>
                <td>
                    <input type='text' readonly="readonly"  class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                </td>
                <!--calculate balance-->
                <?php
                $Item_ID = $row['Item_ID'];
                //get sub department id
                if (isset($_SESSION['Pharmacy'])) {
                    $Sub_Department_Name = $_SESSION['Pharmacy'];
                } else {
                    $Sub_Department_Name = '';
                }

                //get actual balance
                $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    '$Sub_Department_ID'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($sql_get_balance);
                if ($num > 0) {
                    while ($dt = mysqli_fetch_array($sql_get_balance)) {
                        $Item_Balance = $dt['Item_Balance'];
                    }
                } else {
                    $Item_Balance = 0;
                }
                ?>
                <td style='text-align: right;' id='Balance' name='Balance'>
                          <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                    <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                </td>
                <?php
                echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                //echo "<td style='text-align: right;' width='8%'><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                $temp++;
            } echo "</tr>";
        }
        echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='<?php echo $total; ?>'/> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
    }
} else {
    $select_Transaction_Items_Active = mysqli_query($conn,
            "select ilc.brand_id,ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, its.Item_ID,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    ilc.Check_In_Type='$Check_In_Type' and
				ilc.status = 'active'");

    $no = mysqli_num_rows($select_Transaction_Items_Active);

    //display all medications that not approved
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Transaction_Items_Active)) {
                                                  $Item_ID2=$row['Item_ID'];
                                                   $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
                                                   $brand_id_1=$row['brand_id'];
            if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                echo "<td><select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>";
                  /*
                    onChange='update_brand(this.value,$Payment_Item_Cache_List_ID)'
                  */
                $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                 echo '<option value="All">Select Brand Name</option>';
                while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                 echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                }

               echo  "</select> </td>";
                echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                $dosage_duration=$row['dosage_duration'];
                }
            ?>
            <?php if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') { ?>
                <td style='text-align:left;'>
                <?php if ($row['Edited_Quantity'] == 0) {
                    $Quantity = $row['Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                <?php } else {
                    $Quantity = $row['Edited_Quantity']; ?>
                        <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                         <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                <?php } ?>
                </td>
                <td>
                     <input type='text' class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                   <!--  <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                 -->
                </td>
            <?php
            } else {
                if ($row['Edited_Quantity'] == 0) {
                    $Quantity = $row['Quantity'];
                } else {
                    $Quantity = $row['Edited_Quantity'];
                }
            }
            ?>
            <!--calculate balance-->
            <?php
            $Item_ID = $row['Item_ID'];
            //get sub department id
            if (isset($_SESSION['Pharmacy'])) {
                $Sub_Department_Name = $_SESSION['Pharmacy'];
            } else {
                $Sub_Department_Name = '';
            }

            //get actual balance
            $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
					    Item_ID = '$Item_ID' and
						Sub_Department_ID =
						    '$Sub_Department_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($sql_get_balance);
            if ($num > 0) {
                while ($dt = mysqli_fetch_array($sql_get_balance)) {
                    $Item_Balance = $dt['Item_Balance'];
                }
            } else {
                $Item_Balance = 0;
            }
            if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                ?>
                <td style='text-align: right;' id='Balance' name='Balance'>
                    <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                    <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                </td>
                <?php
                echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";

                if ($no == 1) {
                    echo '<td style="text-align: right;" width=8%>
                  &nbsp;
               </td>';
                } else {
                    echo '<td style="text-align: right;" width=8%>
                      <button type="button" class="removeItemFromCache art-button" onclick="removeitemphar(' . $row["Payment_Item_Cache_List_ID"] . ')">Remove</button>
                    </td>';
                }
            }
            $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
            $temp++;
        } echo "</tr>";

        //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
        $Check_Items = $Check_Items = "select ilc.Item_ID, ilc.Price,ilc.dosage_duration, ilc.Doctor_Comment,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type'  and
			    ilc.Check_In_Type='$Check_In_Type' and
				ilc.status = 'removed'";
        $Check_Items_Results = mysqli_query($conn,$Check_Items);
        $No_Of_Items = mysqli_num_rows($Check_Items_Results);
        if ($No_Of_Items > 0) {
            $dataAmount = "<td colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total;'/>TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b>
		          <button type='button' class='removeItemFromCache art-button' onclick='vieweRemovedItem()'>View Removed Items there</button>

                         </td>";
        } else {
            $dataAmount = "<td colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total;'/>TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td>";
        }
    } else {
        //check if there is any removed medication but we make sure no any approved medication
        $select_Transaction_Items_Removed = mysqli_query($conn,
                "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type'  and
				ilc.Check_In_Type='$Check_In_Type' and
				    ilc.status = 'removed'");

        $no = mysqli_num_rows($select_Transaction_Items_Removed);
        if ($no > 0) {

            //check if there is any approved madication
            $select_Transaction_Items_Approved = mysqli_query($conn,
                    "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type'  and
				ilc.Check_In_Type='$Check_In_Type' and
				    ilc.status = 'approved'");

            $no = mysqli_num_rows($select_Transaction_Items_Approved);
            if ($no > 0) {


                //echo 'approved';
                //check if there is any paid
                $select_Transaction_Items_Paid = mysqli_query($conn,
                        "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    ilc.Check_In_Type='$Check_In_Type' and
					ilc.status = 'paid'");

                $no = mysqli_num_rows($select_Transaction_Items_Paid);
                if ($no > 0) {
                    //Check if there is no any dispensed medication
                    $select_Transaction_Items_Dispensed = mysqli_query($conn,
                            "select ilc.Item_ID, ilc.Price, its.Item_ID,ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    ilc.Patient_Payment_ID = '$Patient_Payment_ID' and
				    ilc.Check_In_Type='$Check_In_Type' and
					ilc.status = 'dispensed'");

                    $no = mysqli_num_rows($select_Transaction_Items_Dispensed);
                    if ($no > 0) {
                        while ($row = mysqli_fetch_array($select_Transaction_Items_Dispensed)) {
                                                           $Item_ID2 =$row['Item_ID'];
                            echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                            echo "<td><input type='text' value='" . $row['Product_Name'] . "'></td>";
                            echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                            echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                            echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                            echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                            $dosage_duration=$row['dosage_duration'];
                            ?>
                            <td style='text-align:right;'>
                            <?php if ($row['Edited_Quantity'] == 0) {
                                $Quantity = $row['Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                            <?php } else {
                                $Quantity = $row['Edited_Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                            <?php } ?>
                            </td>
                            <td>
                                <input type='text' class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                            </td>
                            <!--calculate balance-->
                            <?php
                            $Item_ID = $row['Item_ID'];
                            //get sub department id
                            if (isset($_SESSION['Pharmacy'])) {
                                $Sub_Department_Name = $_SESSION['Pharmacy'];
                            } else {
                                $Sub_Department_Name = '';
                            }

                            //get actual balance
                            $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($sql_get_balance);
                            if ($num > 0) {
                                while ($dt = mysqli_fetch_array($sql_get_balance)) {
                                    $Item_Balance = $dt['Item_Balance'];
                                }
                            } else {
                                $Item_Balance = 0;
                            }
                            ?>
                            <td style='text-align: right;' id='Balance' name='Balance'>
                                      <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                                <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                            </td>
                                <?php
                                echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                                // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
                                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                                $temp++;
                            } echo "</tr>";
                            echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/> TOTAL: " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                        } else {
                            while ($row = mysqli_fetch_array($select_Transaction_Items_Paid)) {
                                echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                                echo "<td><input type='text' value='" . $row['Product_Name'] . "'></td>";
                                     echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        // $query_sub_phamthetical = mysqli_query($conn,"SELECT br.brand_name, br.brand_id, bn.phamathetical_item_id FROM tbl_phamathetical_item_brand_name bn LEFT JOIN tbl_Brand_name br ON bn.brand_name_id=br.brand_id WHERE phamathetical_item_id='$Item_ID'") or die(mysqli_error($conn));

                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                                echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                                echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                                echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                                ?>
                            <td style='text-align:right;'>
                            <?php if ($row['Edited_Quantity'] == 0) {
                                $Quantity = $row['Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                            <?php } else {
                                $Quantity = $row['Edited_Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                            <?php } ?>
                            </td>
                            <td>
                                <input type='text'  class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                            </td>
                            <!--calculate balance-->
                            <?php
                            $Item_ID = $row['Item_ID'];
                            //get sub department id
                            if (isset($_SESSION['Pharmacy'])) {
                                $Sub_Department_Name = $_SESSION['Pharmacy'];
                            } else {
                                $Sub_Department_Name = '';
                            }

                            //get actual balance
                            $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($sql_get_balance);
                            if ($num > 0) {
                                while ($dt = mysqli_fetch_array($sql_get_balance)) {
                                    $Item_Balance = $dt['Item_Balance'];
                                }
                            } else {
                                $Item_Balance = 0;
                            }
                            ?>
                            <td style='text-align: right;' id='Balance' name='Balance'>
                                       <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                                <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                            </td>
                                <?php
                                echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                                // echo "<td style='text-align: right;' width=8%><a href='RemovePharmacyItem.php?Payment_Item_Cache_List_ID=".$row['Payment_Item_Cache_List_ID']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."' class='art-button-green' target='_Parent'>Remove</a></td>";
                                $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                                $temp++;
                            } echo "</tr>";
                            echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                        }
                    } else {
                        while ($row = mysqli_fetch_array($select_Transaction_Items_Approved)) {
                            if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                                echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                                echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                                echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                                echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                                echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                                echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                                $dosage_duration=$row['dosage_duration'];
                                }
                            ?>
                        <?php if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') { ?>
                            <td style='text-align:left;'>
                            <?php if ($row['Edited_Quantity'] == 0) {
                                $Quantity = $row['Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                                     <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                            <?php } else {
                                $Quantity = $row['Edited_Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                                     <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                            <?php } ?>
                            </td>
                            <td>
                                <input type='text'  class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                            </td>
                        <?php
                        } else {
                            if ($row['Edited_Quantity'] == 0) {
                                $Quantity = $row['Quantity'];
                            } else {
                                $Quantity = $row['Edited_Quantity'];
                            }
                        }
                        ?>
                        <!--calculate balance-->
                        <?php
                        $Item_ID = $row['Item_ID'];
                        //get sub department id
                        if (isset($_SESSION['Pharmacy'])) {
                            $Sub_Department_Name = $_SESSION['Pharmacy'];
                        } else {
                            $Sub_Department_Name = '';
                        }

                        //get actual balance
                        $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							Item_ID = '$Item_ID' and
							    Sub_Department_ID =
								'$Sub_Department_ID'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($sql_get_balance);
                        if ($num > 0) {
                            while ($dt = mysqli_fetch_array($sql_get_balance)) {
                                $Item_Balance = $dt['Item_Balance'];
                            }
                        } else {
                            $Item_Balance = 0;
                        }
                        if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                            ?>
                            <td style='text-align: right;' id='Balance' name='Balance'>
                                <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                                <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                            </td>
                            <?php
                            echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                        }
                        $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                        $temp++;
                    } echo "</tr>";
                    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                }
            } else {

                      $select_Transaction_Items_Paid = mysqli_query($conn,
                        "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type' and
				    ilc.Check_In_Type='$Check_In_Type' and
					ilc.status = 'paid'");


                while ($row = mysqli_fetch_array($select_Transaction_Items_Paid)) {

                    if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                        echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                        echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                        echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                        echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                        echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                        echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                        $dosage_duration=$row['dosage_duration'];
                        }
                    ?>
                    <?php if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') { ?>
                        <td style='text-align:left;'>
                        <?php if ($row['Edited_Quantity'] == 0) {
                            $Quantity = $row['Quantity']; ?>
                                <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                                 <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                        <?php } else {
                            $Quantity = $row['Edited_Quantity']; ?>
                                <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                                 <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                        <?php } ?>
                        </td>
                        <td>
                            <input type='text'  class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                        </td>
                    <?php
                    } else {
                        if ($row['Edited_Quantity'] == 0) {
                            $Quantity = $row['Quantity'];
                        } else {
                            $Quantity = $row['Edited_Quantity'];
                        }
                    }
                    ?>
                    <!--calculate balance-->
                    <?php
                    $Item_ID = $row['Item_ID'];
                    //get sub department id
                    if (isset($_SESSION['Pharmacy'])) {
                        $Sub_Department_Name = $_SESSION['Pharmacy'];
                    } else {
                        $Sub_Department_Name = '';
                    }

                    //get actual balance
                    $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
						    Item_ID = '$Item_ID' and
							Sub_Department_ID =
							    '$Sub_Department_ID'") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($sql_get_balance);
                    if ($num > 0) {
                        while ($dt = mysqli_fetch_array($sql_get_balance)) {
                            $Item_Balance = $dt['Item_Balance'];
                        }
                    } else {
                        $Item_Balance = 0;
                    }
                    if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                        ?>
                        <td style='text-align: right;' id='Balance' name='Balance'>
                            <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                            <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                        </td>
                        <?php
                        echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                    }
                    $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                    $temp++;
                } echo "</tr>";

                //CHECK IF THERE IS REMOVED ITEM TO DISPLAY THE BUTTON THAT VIEW REMOVED ITEM(S) WHEN NEEDED
                $Check_Items = "SELECT ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
	    from tbl_item_list_cache ilc, tbl_items its
                where ilc.item_id = its.item_id and
		    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			ilc.Transaction_Type = '$Transaction_Type' and
			    ilc.Check_In_Type='$Check_In_Type' and
				ilc.status = 'removed'";
                $Check_Items_Results = mysqli_query($conn,$Check_Items);
                $No_Of_Items = mysqli_num_rows($Check_Items_Results);
                if ($No_Of_Items > 0) {
                    echo "<tr><td colspan=5 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/>TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td><td colspan=2 style='text-align: right;'>
			    <a href='#' class = 'art-button' onclick='vieweRemovedItem()'>View Removed Medication</a>
				</td></tr>";
                } else {
                    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                }
            }
        } else {
            //check if there is any approved medication but no any paid medication
            $select_Transaction_Items_Approved = mysqli_query($conn,
                    "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		from tbl_item_list_cache ilc, tbl_items its
		    where ilc.item_id = its.item_id and
			ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
			    ilc.Transaction_Type = '$Transaction_Type' and
				ilc.Check_In_Type='$Check_In_Type' and
				    ilc.status = 'approved'");

            $no = mysqli_num_rows($select_Transaction_Items_Approved);
            if ($no > 0) {
                //echo 'approved';
                //check if there is no paid medication
                $select_Transaction_Items_Paid = mysqli_query($conn,
                        "select ilc.Item_ID, ilc.Price, its.Item_ID,ilc.Doctor_Comment,ilc.dosage_duration,  ilc.Discount, ilc.Quantity, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type'  and
				   ilc.Check_In_Type='$Check_In_Type' and
					ilc.status = 'paid'");
                $no = mysqli_num_rows($select_Transaction_Items_Paid);
                if ($no > 0) {

                    while ($row = mysqli_fetch_array($select_Transaction_Items_Paid)) {
                                               $Item_ID=$row['Item_ID'];
                        echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                        echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                        echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                        echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                        echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                        echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                       $dosage_duration=$row['dosage_duration'];
                        ?>
                        <td style='text-align:right;'>
                        <?php if ($row['Edited_Quantity'] == 0) {
                            $Quantity = $row['Quantity']; ?>
                                <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <?php } else {
                            $Quantity = $row['Edited_Quantity']; ?>
                                <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <?php } ?>
                        </td>
                        <td>
                            <input type='text' class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                        </td>
                        <!--calculate balance-->
                        <?php
                        $Item_ID = $row['Item_ID'];
                        //get sub department id
                        if (isset($_SESSION['Pharmacy'])) {
                            $Sub_Department_Name = $_SESSION['Pharmacy'];
                        } else {
                            $Sub_Department_Name = '';
                        }

                        //get actual balance
                        $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							Item_ID = '$Item_ID' and
							    Sub_Department_ID =
								'$Sub_Department_ID'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($sql_get_balance);
                        if ($num > 0) {
                            while ($dt = mysqli_fetch_array($sql_get_balance)) {
                                $Item_Balance = $dt['Item_Balance'];
                            }
                        } else {
                            $Item_Balance = 0;
                        }
                        ?>
                        <td style='text-align: right;' id='Balance' name='Balance'>
                            <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                            <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                        </td>
                        <?php
                        echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";

                        $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                        $temp++;
                    } echo "</tr>";
                    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                } else {
                    //get back to approved
                    while ($row = mysqli_fetch_array($select_Transaction_Items_Approved)) {
                        if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                            echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                            echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                            echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                        $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                         echo '<option value="All">Select Brand Name</option>';
                        while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                         echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                        }
                        ?><?php
                "</select>"

                . "</td>";
                            echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                            echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                            echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                            $dosage_duration=$row['dosage_duration'];

                        }
                        ?>
                        <?php if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') { ?>
                            <td style='text-align:left;'>
                            <?php if ($row['Edited_Quantity'] == 0) {
                                $Quantity = $row['Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                                     <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                            <?php } else {
                                $Quantity = $row['Edited_Quantity']; ?>
                                    <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                                     <img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_edit_qty_<?php echo $row["Payment_Item_Cache_List_ID"]; ?>'>
                            <?php } ?>
                            </td>
                            <td>
                                <input type='text'  class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $dosage_duration; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                            </td>
                        <?php
                        } else {
                            if ($row['Edited_Quantity'] == 0) {
                                $Quantity = $row['Quantity'];
                            } else {
                                $Quantity = $row['Edited_Quantity'];
                            }
                        }
                        ?>
                        <!--calculate balance-->
                        <?php
                        $Item_ID = $row['Item_ID'];
                        //get sub department id
                        if (isset($_SESSION['Pharmacy'])) {
                            $Sub_Department_Name = $_SESSION['Pharmacy'];
                        } else {
                            $Sub_Department_Name = '';
                        }

                        //get actual balance
                        $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($sql_get_balance);
                        if ($num > 0) {
                            while ($dt = mysqli_fetch_array($sql_get_balance)) {
                                $Item_Balance = $dt['Item_Balance'];
                            }
                        } else {
                            $Item_Balance = 0;
                        }
                        if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                            ?>
                            <td style='text-align: right;' id='Balance' name='Balance'>
                                       <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                                <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                            </td>
                            <?php
                            echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";
                        }
                        $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                        $temp++;
                    } echo "</tr>";
                    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b> <input type='text'hidden='hidden' id='total_txt' value='$total'/> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                }
            } else {
                 ?>

                        <?php
                //serch for paid -final
                //check if there is no paid medication
                $select_Transaction_Items_Paid = mysqli_query($conn,
                        "select ilc.Item_ID, ilc.Price, ilc.Doctor_Comment, its.Item_ID,ilc.dosage_duration,  ilc.Discount, ilc.Quantity,ilc.Payment_Item_Cache_List_ID,ilc.dosage_duration, its.Product_Name, ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID, ilc.Edited_Quantity
		    from tbl_item_list_cache ilc, tbl_items its
			where ilc.item_id = its.item_id and
			    ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
				ilc.Transaction_Type = '$Transaction_Type'  and
				   ilc.Check_In_Type='$Check_In_Type' and
					ilc.status = 'paid'");
                $no = mysqli_num_rows($select_Transaction_Items_Paid);
                if ($no > 0) {
                    while ($row = mysqli_fetch_array($select_Transaction_Items_Paid)) {
                                                     $Item_ID2=['Item_ID'];
                        echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
                        echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'></td>";
                        echo "<td>"
                        . "<select class='seleboxorg3'  name='brand_name' id='brand_name' style='width:250px; padding-top:4px; padding-bottom:4px;' onchange='Change_To_Brand(this,$Payment_Item_Cache_List_ID);'>"
                        ?><?php
                         $query_sub_phamthetical = mysqli_query($conn,"SELECT Item_ID , Product_Name FROM tbl_items WHERE item_kind = 'Brand' AND Generic_ID = '".$row['Item_ID']."'") or die(mysqli_error($conn));
                          echo '<option value="All">Select Brand Name</option>';
                         while ($row8 = mysqli_fetch_array($query_sub_phamthetical)) {
                          echo '<option value="' . $row8['Item_ID'] . '">' . $row8['Product_Name'] . '</option>';
                         }
                        ?><?php
                "</select>"

                . "</td>";
                        echo "<td style='text-align:right;'>" . (($_SESSION['hospitalConsultaioninfo']['enable_spec_dosage'] == '1') ? "<textarea readonly='readonly style='height:35px'>" . $row['Doctor_Comment'] . "</textarea>" : "<input type='text' readonly='readonly' value='" . $row['Doctor_Comment'] . "'/>") . "</td>";
                        echo "<td style='text-align:right;'><input type='text' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "' id='P_" . $row['Payment_Item_Cache_List_ID'] . "'  style='text-align:right;'></td>";
                        echo "<td><input type='text' style='text-align:right;' value='" . number_format($row['Discount']) . "' id='D_" . $row['Payment_Item_Cache_List_ID'] . "'  readonly='readonly'></td>";
                        $dosage_duration=$row['dosage_duration'];
                        ?>
                        <td style='text-align:right;'>
                        <?php if ($row['Edited_Quantity'] == 0) {
                            $Quantity = $row['Quantity']; ?>
                                <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <?php } else {
                            $Quantity = $row['Edited_Quantity']; ?>
                                <input type='text' class='validatesubmit gty<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' value='<?php echo $Quantity; ?>' oninput="numberOnly(this); calculate_Sub_Total(<?php echo $row['Payment_Item_Cache_List_ID']; ?>);" onkeyup="pharmacyQuantityUpdate2('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value)" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>
                        <?php } ?>
                        </td>
                        <td>
                            <input type='text'  class='validatesubmit dsgd<?php echo $temp; ?>' trg='<?php echo $temp; ?>' value='<?php echo $row['dosage_duration']; ?>' onkeyup="pharmacyDurationUpdate('<?php echo $row["Payment_Item_Cache_List_ID"]; ?>', this.value,this)" oninput="numberOnly(this);" name = '<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' id='duration_txt<?php echo $row["Payment_Item_Cache_List_ID"]; ?>' style='text-align: center;' size=13>

                        </td>
                        <!--calculate balance-->
                        <?php
                        $Item_ID = $row['Item_ID'];
                        //get sub department id
                        if (isset($_SESSION['Pharmacy'])) {
                            $Sub_Department_Name = $_SESSION['Pharmacy'];
                        } else {
                            $Sub_Department_Name = '';
                        }

                        //get actual balance
                        $sql_get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
							    Item_ID = '$Item_ID' and
								Sub_Department_ID =
								    '$Sub_Department_ID'") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($sql_get_balance);
                        if ($num > 0) {
                            while ($dt = mysqli_fetch_array($sql_get_balance)) {
                                $Item_Balance = $dt['Item_Balance'];
                            }
                        } else {
                            $Item_Balance = 0;
                        }
                        ?>
                        <td style='text-align: right;' id='Balance' name='Balance'>
                                           <input type="text" id="balance<?php echo $row['Payment_Item_Cache_List_ID']; ?>" value="<?php echo $Item_Balance; ?>" hidden="hidden">
                            <input type='text' class='validatesubmit baln<?php echo $temp; ?>' trg='<?php echo $temp; ?>' readonly='readonly' name='Item_Balance' id='Item_Balance' style='text-align: center;' value='<?php echo $Item_Balance; ?>'>
                        </td>
                        <?php
                        echo "<td><input type='text' name='Sub_Total' id='Sub_" . $row["Payment_Item_Cache_List_ID"] . "' readonly='readonly' value='" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $Quantity, 2) : number_format(($row['Price'] - $row['Discount']) * $Quantity)) . "' style='text-align:right;'></td>";

                        $total = $total + (($row['Price'] - $row['Discount']) * $Quantity);
                        $temp++;
                    } echo "</tr>";
                    echo "<tr><td id='Total_Area' colspan=8 style='text-align: right;'><b>  <input type='text'hidden='hidden' id='total_txt' value='$total'/>TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;' . "</b></td></tr>";
                }
            }
        }
    }
}
?></table></center>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
    $('select').select2();


    function update_brand(brand_id,Payment_Item_Cache_List_ID){
            $.ajax({
                type: "POST",
                url: "pharmacybrandUpdate.php",
                data:{brand_id:brand_id,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
                success: function (result) {
//                  console.log(result);
                }});
    }

</script>

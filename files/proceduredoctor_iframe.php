<!--<style>
    a{
        color:
</style>-->
<?php

@session_start();
include("./includes/connection.php");


$total = 0;
$temp = 1;
$data = '';
$sqlq = '';
$totalItem = '';
$totalDone = '';
$dataAmount = '';

$doctorproce = " and ilc.Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'";

$filter = ' AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW()) ';

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}



// echo  '$sqlq';exit; style="background-color:#037DB0;color:white;font-weight:bold"

echo '<center>
            <table width =100% border=0 class="display" id="procedwureinfoData">
            <thead >
                <tr >
                    <th><b>SN</b></th>
                    <th><b>PROCEDURE NAME</b></th>
                    <th><b>PROGRESS</b></th>
                    <th><b>REMARKS</b></th>
                    <th><b>TEMPLATE</b></th>

                </tr>
            </thead>';

$sqlq = " select ilc.Item_ID,ilc.Status, its.Product_Name,ilc.Transaction_Type,ilc.Payment_Item_Cache_List_ID, ilc.Payment_Cache_ID,ilc.remarks,pc.Billing_Type,ilc.Transaction_Type,ilc.payment_type,Require_Document_To_Sign_At_receiption
		from tbl_item_list_cache ilc,tbl_payment_cache pc, tbl_items its , tbl_sponsor sp
                where ilc.Payment_Cache_ID = pc.Payment_Cache_ID and
                    ilc.item_id = its.item_id and
                    sp.Sponsor_ID=pc.Sponsor_ID and
                    ilc.status IN ('active','paid')  and
                    ilc.removing_status='no' and
                    pc.Registration_ID='$Registration_ID' $doctorproce and
                                        ilc.Check_In_Type = 'Procedure' $filter
		 ";



$select_Transaction_Items_Active = mysqli_query($conn,$sqlq) or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Transaction_Items_Active);

$transStatust = false;
while ($row = mysqli_fetch_array($select_Transaction_Items_Active)) {
    $status = strtolower($row['Status']);
    $billing_Type = strtolower($row['Billing_Type']);
    $transaction_Type = strtolower($row['Transaction_Type']);
    $payment_type = strtolower($row['payment_type']);
    $require_approve = strtolower($row['Require_Document_To_Sign_At_receiption']);
    $displ = '';

    if (($billing_Type == 'outpatient cash' && $status == 'active') || ($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
        $transStatust = true;
        $msg = 'Procedure not paid.';
    } elseif ($billing_Type == 'outpatient credit' && $status == 'active' && $require_approve == 'mandatory') {
        $transStatust = true;
        $msg = 'Procedure not approved.';
    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

        if ($pre_paid == '1') {
            $transStatust = true;
            $msg = 'Procedure not paid.';
        } elseif ($payment_type == 'pre') {
            $transStatust = true;
            $msg = 'Procedure not paid.';
        }
    }


    if ($transStatust) {
        $displ = '<td style="text-align:center" colspan="2"><h5 style="color:#FF0E7B" class="notpaid">'.$msg.'<h5></td>';
    } else {
       $Item_ID=$row['Item_ID'];
        $displ = '<td style="text-align:center"><select class="Procedureprogress" id="item'.$Item_ID.'" onchange="check_if_this_procedure_exist_on_setup('.$row['Item_ID'].','.$Patient_Payment_ID.')" name="status_' . $row["Payment_Item_Cache_List_ID"] . '" style="width:90%;padding:5px;font-size:17px;">
					                 <option>Select progress</option>
							 <option value="served">Done</option>
							 <option value="pending">Pending</option>
							 <option value="not applicable">Not Applicable</option>
						   </select>

                  <input type="hidden" name="status_pro_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $status . '"/>
                  <input type="hidden" name="billing_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $billing_Type . '"/>
                  <input type="hidden" name="transaction_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $transaction_Type . '"/>
                  <input type="hidden" name="payment_type_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $payment_type . '"/>
                  <input type="hidden" name="require_approve_' . $row["Payment_Item_Cache_List_ID"] . '" value="' . $require_approve . '"/>

                  </td>';
        $displ .= "<td width='30%'><input type='hidden' value='" . $row['Payment_Item_Cache_List_ID'] . "' name='paymentItermCache[]'><textarea type='text' name='remarks_" . $row["Payment_Item_Cache_List_ID"] . "' id='" . $row["Payment_Item_Cache_List_ID"] . " style='' cols='8' rows='1'>" . ($row['remarks']) . "</textarea>

        <input type='button' name='btn_procedure_items' value='ADD CONSUMMABLES' class='art-button-green' onclick='Prosedure_Map_List(".$row['Item_ID'].",".$Patient_Payment_ID.");'>
        </td>";
    }

    echo "<tr><td width='5%'><input type='text' size=3 value = '" . $temp . "' style='text-align: center;' readonly='readonly'></td>";
    echo "<td><div style='width:100%;margin:4px;'>" . $row['Product_Name'] . "</div></td>";
    echo $displ;
    echo '<td style="text-align:center">';

      if(!$transStatust){
            echo ' <input type="button" name="OGD_Post_Operative_Button" id="OGD_Post_Operative_Button" class="art-button-green" value="COLONOSCOPY" onclick="OGD_Process('.$row["Payment_Item_Cache_List_ID"].')">
            <input type="button" name="OGD_Post_Operative_Button" id="OGD_Post_Operative_Button" class="art-button-green" value="UPPER GIT" onclick="Upper_Git_Process('.$row["Payment_Item_Cache_List_ID"].')">
            <input type="button" name="refraction" id="refraction" class="art-button-green" value="REFRACTION" onclick="open_refraction_procedure('.$row["Payment_Item_Cache_List_ID"].')">';
      }
      echo '<button type="button" class="art-button-green" onclick="removeFromList(' . $row["Payment_Item_Cache_List_ID"] . ')">REMOVE</button>
          </td>';
    $temp++;
    $transStatust = false;
    echo "</tr>";
}
echo "</table></center>";
?>
<input type="hidden" value='<?php echo $Payment_Item_Cache_List_ID;?>' id="Payment_Item_Cache_List_ID">
<input type="hidden" value='<?php echo $Registration_ID;?>' id="Registration_ID">
<input type="hidden" value='<?php echo $Employee_ID;?>' id="Employee_ID">
<div id="show"></div>
<script>
    function open_refraction_procedure(){
        var Payment_Item_Cache_List_ID=$("#Payment_Item_Cache_List_ID").val();
        var Registration_ID=$("#Registration_ID").val();
        var Employee_ID=$("#Employee_ID").val();
        $.ajax({
            type:'POST',
            url:'refraction_dialog.php',
            data : {Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                    Registration_ID:Registration_ID,
                    Employee_ID:Employee_ID
            },
            success : function(data){
                $('#show').dialog({
                    autoOpen:true,
                    width:'70%',
                    position:['center',150],
                    title:'Refraction Detail',
                    modal:true
                    
                });  
                $('#show').html(data);
                $('#show').dialog('data');
            }
            }) 

    }
</script>

<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$Payment_Cache_ID=0;
$Registration_ID=0;
if(isset($_GET['Payment_Cache_ID'])){
    $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
if(isset($_GET['Check_In_Type'])){
    $Check_In_Type=$_GET['Check_In_Type'];
}
$filter_pharmacy_items="";
$filter_Doctor_Room_items="";
if(isset($_GET['itemfrom'])){
    $itemfrom=$_GET['itemfrom'];
    if($itemfrom=="Pharmacy"){
       $filter_pharmacy_items="selected='selected'"; 
    }else if($itemfrom=="reception"){
        $filter_Doctor_Room_items="selected='selected'";
    }
}

$Sub_Department_Name="";
if(isset($_GET['Sub_Department_Name'])){
    $Sub_Department_Name=$_GET['Sub_Department_Name'];
    $Sub_Department_Name="~~".$Sub_Department_Name;
}

 if(isset($_GET['Payment_Cache_ID'])&&isset($_GET['Registration_ID'])){
  $Registration_ID=$_GET['Registration_ID'];
  $Payment_Cache_ID=$_GET['Payment_Cache_ID'];
 if($Payment_Cache_ID!=""||!empty($Payment_Cache_ID)){
    $filter="AND pc.Payment_Cache_ID='$Payment_Cache_ID'";  
  }
//   if($bill_payment_code!=""||!empty($bill_payment_code)){
//     $bill_payment_code='$bill_payment_code'";  
//   }
  $bill_payment_code=$_GET['bill_payment_code'];
  ?>
  <input type="hidden" id="bill_payment_code" value="<?php echo $bill_payment_code;?>">
  <?php

  $filter.="AND pr.Registration_ID='$Registration_ID'";
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT pr.Phone_Number,pr.Registration_ID,pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Sponsor_ID,pc.Payment_Date_And_Time,pc.Payment_Cache_ID FROM tbl_patient_registration pr,tbl_payment_cache pc WHERE pr.Registration_ID=pc.Registration_ID AND Billing_Type IN('Inpatient Cash','Outpatient Cash') $filter ORDER BY Payment_Date_And_Time DESC LIMIT 1") or die(mysqli_error());
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Date_Of_Birth=$patient_list_rows['Date_Of_Birth'];
         $Gender=$patient_list_rows['Gender'];
         $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
         $Payment_Date_And_Time=$patient_list_rows['Payment_Date_And_Time'];
         $Payment_Cache_ID=$patient_list_rows['Payment_Cache_ID'];
         $PhoneNumber = $patient_list_rows['Phone_Number'];
         $num=str_replace("255","0",$PhoneNumber);
                
         //filter only patient with active or approved item
//         $sql_select_active_or_approved_item_result=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID' AND Status IN('active','approved')") or die(mysqli_error());
//         if(mysqli_num_rows($sql_select_active_or_approved_item_result)<=0){
//             
//         } 
                $date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	
        //sql select payment sponsor
        $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
      }
  }
}
 ///remove for item dublication
 for($i=0;$i<4;$i++){
    $sql_select_dublicate_item_result=mysqli_query($conn,"SELECT MAX(Payment_Item_Cache_List_ID) AS Payment_Item_Cache_List_ID FROM `tbl_item_list_cache` WHERE `Payment_Cache_ID`='$Payment_Cache_ID' AND Status<>'removed' GROUP BY Item_ID,`Quantity` HAVING COUNT(Item_ID)>1") or die(mysqli_error());
    if(mysqli_num_rows($sql_select_dublicate_item_result)>0){
       while($dublicate_item_rows=mysqli_fetch_assoc($sql_select_dublicate_item_result)){
           $Payment_Item_Cache_List_ID_r=$dublicate_item_rows['Payment_Item_Cache_List_ID'];
           $sql_delete_dublicate_item_result=mysqli_query($conn,"UPDATE tbl_item_list_cache SET removing_status='yes',Status='removed' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID_r'") or die(mysqli_error());
       } 
    }
 }
?>
<!-- <a href="new_payment_method.php" class="art-button-green">NEW PAYMENT METHOD</a> -->
<a href="patient_sent_to_cashier_pending_items.php?Payment_Cache_ID=<?= $Payment_Cache_ID ?>&Registration_ID=<?= $Registration_ID ?>" class="art-button-green hide">PENDING ITEMS</a>
<?php  if($itemfrom=="Pharmacy"){
?>
<a href="pharmacyworkspage.php?section=Pharmacy&Registration_ID=<?= $Registration_ID ?>&Transaction_Type=Cash&Payment_Cache_ID=<?= $Payment_Cache_ID ?>&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type=<?= $Check_In_Type ?>" class="art-button-green">BACK</a>    
<?php 
}else if($itemfrom=="doctors_page"){
?>
<a href="clinicpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" class="art-button-green">BACK</a>
<?php 
}else if($itemfrom=="reception"){
   ?>
<a href="searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage" class="art-button-green">BACK</a>
<?php 
}else{
?>
<a href="pending_transactions.php" class="art-button-green">BACK</a>
<?php
} ?>
<fieldset style="height:100px;overflow-y: hidden;overflow-x: hidden">
    <legend align='center'><b>PATIENT PENDING TRANSACTION</b> <?= $Sub_Department_Name; ?></legend>

    <table class="table" style="background: #FFFFFF">
        <tr>
            <td width="8%">
                <b>Patient Name</b>
            </td>
            <td>
                <?= $Patient_Name ?>
            </td>
            <td width="8%"> <b>Patient Reg#</b></td>
            <td>
                <?= $Registration_ID ?>
            </td>
            <td width="5%"> <b>Age</b></td>
            <td><?= $age ?></td>
            <td width="5%"> <b>Gender</b></td>
            <td>
                <?= $Gender ?>
            </td>
            <td width="7%"> <b>Sponsor</b></td>
            <td>
                <?= $Guarantor_Name ?>
            </td>
            <td width="7%"> <b>Sent Date</b></td>
            <td>
                <?= $Payment_Date_And_Time ?>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style="height:350px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF">
    <div class="box box-primary">
    <table class="table table-bordered table-hover">
        <tr>
            <th width='50px'>S/No</th>
            <th>Item Name</th>
            <th style='text-align:right'>Item Price</th>
            <th style='text-align:right'>Item Discount</th>
            <th style='text-align:right'>Quantity</th>
            <th style='text-align:right'>Subtotal</th>
            <th width='100px'><label><input type="checkbox" id="select_all_checkbox"/> Select All</label></th>
            <th width='100px'>Sangira Code</th>
            <th width='100px'>Print</th>
        </tr>
        <tbody id='patient_sent_to_cashier_item_tbl'>
            <?php 
                //select all patient items sent to cashier
//                $grand_total=0;
//                $grand_total_discount=0;
//                $grand_total_price=0;
//                $grand_total_quantity=0;
//                $sql_select_patient_items_result=mysqli_query($conn,"SELECT card_and_mobile_payment_status,Payment_Item_Cache_List_ID,Product_Name,Price,Quantity,Edited_Quantity,Discount FROM tbl_item_list_cache ilc,tbl_items i WHERE ilc.Item_ID=i.Item_ID AND ilc.Payment_Cache_ID='$Payment_Cache_ID' AND (ilc.Check_In_Type='Pharmacy' AND ilc.Status='approved' OR (ilc.Check_In_Type<>'Pharmacy' AND (ilc.Status IN('active','approved')))) AND (card_and_mobile_payment_status='unprocessed' OR card_and_mobile_payment_status='pending')") or die(mysqli_error());
//               if(mysqli_num_rows($sql_select_patient_items_result)>0){
//                   $count_sn=1;
//                   while($item_list_rows=mysqli_fetch_assoc($sql_select_patient_items_result)){
//                      $Product_Name=$item_list_rows['Product_Name'];
//                      $Price=$item_list_rows['Price'];
//                      $Quantity=$item_list_rows['Quantity'];
//                      $Edited_Quantity=$item_list_rows['Edited_Quantity'];
//                      $Discount=$item_list_rows['Discount'];
//                      $Payment_Item_Cache_List_ID=$item_list_rows['Payment_Item_Cache_List_ID'];
//                      $card_and_mobile_payment_status=$item_list_rows['card_and_mobile_payment_status'];
//                      $change_color_style="";
//                      if($card_and_mobile_payment_status=="pending"){
//                          $change_color_style="style='color:#FFFFFF;background:green;font-weight:bold'";
//                      }
//                      if($Edited_Quantity>0){
//                          $item_quantity=$Edited_Quantity;
//                      }else{
//                          $item_quantity=$Quantity;
//                      }
//                      $grand_total_discount+=$Discount;
//                      $grand_total_price+=$Price;
//                      $grand_total_quantity+=$item_quantity;
//                      $total_price=$item_quantity*($Price-$Discount);
//                      echo "<tr>
//                                
//                                <td>$count_sn.</td>
//                                <td $change_color_style>$Product_Name</td>
//                                <td style='text-align:right'>".number_format($Price)."</td>
//                                <td style='text-align:right'>$Discount</td>
//                                <td style='text-align:right'>$item_quantity</td>
//                                <td style='text-align:right'>".number_format($item_quantity*($Price-$Discount))."<input type='text' value='$total_price' id='total_price$Payment_Item_Cache_List_ID' class='hide'/></td>
//                                <td class='select_item_column'>&nbsp;&nbsp;<input type='checkbox' value='$Payment_Item_Cache_List_ID' class='Payment_Item_Cache_List_ID'/></td>
//                           </tr>";
//                      $count_sn++;
//                    $grand_total+=($item_quantity*($Price-$Discount));
//                   }
//               } 
            ?>
<!--            <tr>
                <td colspan="2"><b>TOTAL</b></td><td style='text-align:right'><b><?= number_format($grand_total_price) ?></b></td><td style='text-align:right'><b><?= number_format($grand_total_discount) ?></b></td><td style='text-align:right'><b><?= $grand_total_quantity ?></b></td><td style='text-align:right'><b><?= number_format($grand_total) ?></b></td>
            </tr>-->
        </tbody>
    </table>
    </div>
</fieldset>
<!-- <fieldset> -->
    <!-- <label id="sangila_code_feedback" class="col-md-4" style="color:red;font-size:20px">SANGIRA CODE</label> -->
    <div class="col-md-2" style="float:right;"><input type="button" onclick="print_all_sangira()" value="PRINT SANGIRA CODE" class="art-button-green" style="height:35px!important"/></div>
    <!-- <label class="col-md-6">GRAND TOTAL AMOUNT FOR SELECTED ITEMS</label> -->
    <label id="grand_total_amount_for_selected_items" class="pull-right" style="font-size:20px"></label>
</fieldset>
<fieldset>
    <!-- <label class="col-md-2">Select Department</label> -->
    <div class="col-md-2">
        <select style='width:100%!important;' id='Check_In_Type' onchange="filter_items_by_department()">
            <option value="All">All Departments</option>
            <option value="Laboratory">Laboratory</option>
            <option value="Radiology">Radiology</option>
            <option value="Procedure">Procedure</option>
            <option value="Surgery">Surgery</option>
            <option value="Pharmacy" <?= $filter_pharmacy_items ?>>Pharmacy</option>
            <option value="Doctor Room" <?= $filter_Doctor_Room_items ?>>Doctor Room</option>
        </select>
    </div>
    <div class="col-md-2">
        <!-- <input type="text" placeholder="Phone Number" onkeyup="validate_number()" maxlength="10"  value="<?php echo $num; ?>" class="form-control" style="font-weight: bold;font-size:24px" id="patient_phone_number"/> -->
    </div>
    <!-- <label class="col-md-1">Collection Point: </label> -->
    <label id="sangila_code_feedback" class="col-md-4" style="color:red;font-size:20px">
        <?php 
            $id =$_SESSION['epayconfig'];
            $obj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT collection,url FROM tbl_collection_point_confg WHERE id ='$id'"));
            $url = $obj['url'];
            echo $obj['collection'];
        ?>
    </label>
    <div class="col-md-1" id="progress_dialog" style="display:none">
        <img src="images/ajax-loader_1.gif" width="" style="border-color:white ">
    </div>
    <div class="col-md-1" id="progress_dialog">
        <img id="progressStatus"  src="images/ajax-loader_1.gif" style="margin: 0;border: 0; display:none ">
    </div>
    
    <input type="button" class="art-button-green pull-right hide" value="Create e-Bill" onclick="open_patient_bill_dialog()"/>
</fieldset>
<?php 
if(isset($_SESSION['configData']) && $_SESSION['configData']['ShowCreateEpaymentBillOrMakePaymentButton']=='epayment'){
    $hide_m_pay='display:none';;
}else{
    $hide_e_pay='display:none';
}
?>
<fieldset style="padding-left:150px;<?= $hide_e_pay ?>">
    <?php 
                $sql_select_button_status_result=mysqli_query($conn,"SELECT new_payment_method_config_btn_name,visibility_status FROM tbl_new_payment_method_config_btn") or die(mysqli_error());
                if(mysqli_num_rows($sql_select_button_status_result)>0){
                   while($btn_config_rows=mysqli_fetch_assoc($sql_select_button_status_result)){
                       $new_payment_method_config_btn_name=$btn_config_rows['new_payment_method_config_btn_name'];
                       $visibility_status=$btn_config_rows['visibility_status'];
                       if($new_payment_method_config_btn_name=="afya_card_pay"){
                           $afya_card_pay=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="crdb_card_pay"){
                           $crdb_card_pay=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="crdb_mobile_epay"){
                          $crdb_mobile_epay=$visibility_status; 
                       }
                       if($new_payment_method_config_btn_name=="create_out_patient_bill"){
                           $create_out_patient_bill=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="nmb_mobile_epay"){
                           $nmb_mobile_epay=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="request_control_number"){
                           $request_control_number=$visibility_status;
                       }
                       if($new_payment_method_config_btn_name=="g_pesa_payment"){
                           $g_pesa_payment=$visibility_status;
                       }
                   }
                }
                ?>
    <div class="col-md-6 hide">
    </div>
    <div class="col-md-2 pull-right <?= $g_pesa_payment ?>">
        <input type="button" class="art-button-green pull-right" onclick="()" style="width: 90%" value="G-PESA" />
    </div>
    <div class="col-md-2 pull-right <?= $request_control_number ?>">
        <input type="button" class="art-button-green pull-right" onclick="request_control_number()" style="width: 90%" value="Request Control Number " />
    </div>
    
    
    <!--<div class="col-md-2"><input type="button" value="ISSUE CARD" class="art-button-green" style="width: 90%"/></div>-->
    <!-- <div class="col-md-2 pull-right <?= $create_out_patient_bill ?>"><input type="button" value="Create Outpatient Bill" class="art-button-green pull-right "  style="width: 90%"/></div> -->
    <div class="col-md-2 pull-right <?= $afya_card_pay ?>"><input type="button" value="AFYA CARD PAY" class="art-button-green pull-right " onclick="open_verify_card_dialogy('<?=$Registration_ID;?>')" style="width: 90%"/></div>
    <div class="col-md-2 pull-right <?= $nmb_mobile_epay ?>">
        <input type="button" class="art-button-green pull-right " onclick="make_epayment('to_nmb')" value="NMB  ePayment ECoSystem" style="width:90%;background:#F26F21"/>
    </div>
    <div class="col-md-2 pull-right <?=  $crdb_mobile_epay ?>">
        <!-- <input type="button" class="art-button-green pull-right " onclick="make_epayment('to_azania','<?= $_SESSION['epayconfig'];?>','<?= $url;?>')" value="Create Sangira Number" style="width:90%;"/> -->
        <!--<input type="button" class="art-button-green pull-right " onclick="make_epayment('to_crdb')" value="CRDB Mobile ePayment" style="width:90%;background: green"/>-->
    </div>
    <!-- <div class="col-md-2 pull-right <?= $crdb_card_pay ?>">
        <input type="button" class="art-button-green pull-right " onclick="crdb_card_makepayment()" style="width:90%;background: green" value="CRDB Card Payment" />
    </div> -->
</fieldset>
<fieldset style="<?= $hide_m_pay ?>" id="manual_payment_field">
    <?php 
    if(isset($_SESSION['configData']) && $_SESSION['configData']['showManulaOrOffline']=='manual'){
        ?>
    <input type="button" value="MANUAL PAYMENT" class="art-button-green pull-right" onclick="make_manual_payment()"/>
        <?php
    }else{
      ?>
        <input type="button" value="OFFLINE PAYMENT" class="art-button-green pull-right"/>  
      <?php  
    }
    ?>
</fieldset>
<fieldset id='print_and_synchronyzation_btn'>

</fieldset>

<div id="DisplayTransactionDetails" style="width:50%;" >
    <center id='Details_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<div id="print_receipt_msg" style="width:50%;" >
    <center id='Msg_Area'>

        <p>Payment is not completed!!!</b><br><br>Click RE-SYCHRONIZE button then <br><u><b>try re-printing </b></u>the receipt again</p>
    </center>
</div>
<div id="force_synchronization_message">
    force_move_data_for_receipt_print(Registration_ID,Transaction_ID,Payment_Code)
    
</div>
<div id="synchronize_msg" style="width:50%;" >
    <center id='synchronize_msg_Area'>
        <p>Synchronization Successful!.</p>
    </center>
</div>
<div id="patient_bill_dialog"></div>
<div id="contronumber_dialog"></div>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/ecr_pmnt.js"></script>
<?php
    include("./includes/footer.php");
?>
<div id="card_mobile_payment_feedback" class="hide"></div>
<script>
    $(document).ready(function(){
        $('select').select2();
        filter_items_by_department();
    });
    function validate_number(){
       var patient_phone_number=$("#patient_phone_number").val();
       var patient_phone_number=$("#patient_phone_number").val();
       var patient_phone_number_ = patient_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
       if(patient_phone_number_=="")patient_phone_number_=0;
       $("#patient_phone_number").val("0"+parseInt(patient_phone_number_));
    }
    function filter_items_by_department(){
        var Check_In_Type=$("#Check_In_Type").val();
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var bill_payment_code='<?= $bill_payment_code ?>';
//        alert(Payment_Cache_ID);
        $.ajax({
            type:'POST',
            url:'ajax_pending_filter_items_by_department.php',
            data:{Check_In_Type:Check_In_Type,Payment_Cache_ID:Payment_Cache_ID,bill_payment_code:bill_payment_code},
            success:function(data){
                console.log(Payment_Cache_ID);
                $("#patient_sent_to_cashier_item_tbl").html(data);
            }
        });
    }
    $("#select_all_checkbox").click(function (e){
        $(".Payment_Item_Cache_List_ID").not(this).prop('checked', this.checked); 
        calculate_bill_amount()
//        filter_items_by_department()
    });
    function calculate_bill_amount(){
        
        Number.prototype.format=function(n,x){
            var re='\\d(?=(\\d{'+(x||3)+'})+'+(n>0?'\\.':'$')+')';
            return this.toFixed(Math.max(0,~~n)).replace(new RegExp(re,'g'),'$&,');
        };
       var grand_total_price=0;
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            var Payment_Item_Cache_List_ID=$(this).val();
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
	}); 
        $("#grand_total_amount_for_selected_items").html("TSH. "+grand_total_price.format()); 
    }
    function open_patient_bill_dialog(){
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
            $.ajax({
                type:'POST',
                url:'ajax_open_patient_bill_dialog.php',
                data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID},
                success:function(data){
                    $("#patient_bill_dialog").html(data);
                    $("#patient_bill_dialog").dialog({title: 'PATIENT BILL',width: '90%', height: 500, modal: true,});
                    $('select').select2();
                }
            }); 
        }else{
            alert("Select Item to create bill");
            $(".select_item_column").css("background","red");
        }  
    }
    ///////////////////////////////////////////////////////////////////////////////////
    function  make_manual_payment(){
                var selected_Payment_Item_Cache_List_ID = []; 
        var grand_total_price=0;
         $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val(); 
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
	});
        console.log("total_price=1==>"+grand_total_price);
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var validate=0;
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
        if(validate<=0){
            if(confirm("Are you sure you want to create manual payment?")){
            $("#progress_dialog").show();
            $.ajax({
                type:'POST',
                url:'ajax_manual_create_epayment_bill.php',
                data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,grand_total_price:grand_total_price,Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID},
                success:function(data){
                    console.log("total_price=2==>"+grand_total_price);
                    console.log("1===>"+data);
                    if(data=="already_created"){
                        alert("Selected Item(s) Arleady Sent To POS");
                        $("#progress_dialog").hide();
                    }else{
                        if(data!="0"&&data!="fail"){
                            manual_payment_process_transaction(data);
                        }else{
                            alert("PROCESS FAIL!...TRY AGAIN"+data);
                            $("#progress_dialog").hide();
                        }
                    }
//                    $("#progress_dialog").hide();
                }
            });
          }}
        }else{
            alert("Select Item to create e-Bill");
            $(".select_item_column").css("background","red");
        }
    }
    function manual_payment_process_transaction(Transaction_ID){
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var Registration_ID='<?= $Registration_ID ?>';
        console.log("hii  ya manual");
        $.ajax({
                 type:'POST',
                url:'ajax_new_manualtransactiondetails.php',
                data:{Payment_Cache_ID:Payment_Cache_ID},
                success:function(data){
                    console.log("2===>"+data);
                    if(data!="fail"){
//                        alert("2===>"+data);
//                        ecr_paycode(data) 
                        var Payment_Code=data;
                        manual_payment_complete_payment_process(Payment_Code,Transaction_ID);
                    }else{
                        alert("Process Fail!...Try again");
                    }
                    $("#progress_dialog").hide();
                },error:function(x,y,z){
                    console.log(x+y+z);
                }
            });
    }
    function manual_payment_complete_payment_process(Payment_Code,Transaction_ID){
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var Registration_ID='<?= $Registration_ID ?>';
        console.log("receipt==="+Payment_Code+"trans_id"+Transaction_ID);
        $.ajax({
           type:'GET',
           url:'force_move_data_for_manual_receipt_print.php',
           data:{Payment_Code:Payment_Code,Registration_ID:Registration_ID,Transaction_ID:Transaction_ID},
           beforeSend: function (xhr) {
            $('#progressStatus').show();
        },
           success:function(data){
            	var html="<div class='pull-right'>&nbsp;&nbsp;<input type='button' class='art-button-green' value='PRINT DETAIL RECEIPT' onclick='Print_Receipt_Payment_new(\""+Payment_Code+"\")'></div>";
                $("#print_and_synchronyzation_btn").html(html);
                $('#manual_payment_field').hide();
                $('#progressStatus').hide();
               
           },
       error:function(x,n,m){
           console.log(x+n+m)
       }
           
       }); 
    }
    
    
    function make_epayment(payment_direction,id, url){
        var selected_Payment_Item_Cache_List_ID = []; 
          var grand_total_price=0;
//        $(".Payment_Item_Cache_List_ID_selected").each(function() {
//            selected_Payment_Item_Cache_List_ID.push($(this).val());
//           var Payment_Item_Cache_List_ID=$(this).val();
//           
//           grand_total_price+=$("total_price"+Payment_Item_Cache_List_ID).val();
//           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
//	});
      
         $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val();
           
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
//           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
	});
        
//        alert(grand_total_price);
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var validate=0;
        var patient_phone_number=$("#patient_phone_number").val();
        var patient_phone_number_ = patient_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
        if(patient_phone_number_==""){
            $("#patient_phone_number").css("border","2px solid red");
            $("#patient_phone_number").focus();
            validate++;
        }else{
           $("#patient_phone_number").css("border",""); 
        }
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
            
        //console.log("==>"+selected_Payment_Item_Cache_List_ID+"\n");
        if(validate<=0){
            <?php $Employee_ID=$_SESSION['userinfo']['Employee_ID']; ?>
                var Employee_ID='<?= $Employee_ID ?>'       
            $("#progress_dialog").show();
            $.ajax({
                type:'POST',
                url:url,
 //               url:'mobile_processing_payfolder/ajax_card_and_mobile_epayment.php',
//                url:'http://192.168.43.22/mobile_processing_payfolder/ajax_card_and_mobile_epayment.php',
                data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,grand_total_price:grand_total_price,Registration_ID:Registration_ID,patient_phone_number_:patient_phone_number_,Payment_Cache_ID:Payment_Cache_ID,Employee_ID:Employee_ID,payment_direction:payment_direction,collection:id},
                success:function(data){
                    console.log(data);
                    $("#card_mobile_payment_feedback").html(data);
                    if(data == "Accepted Successfully"){
                       alert("Payment Created Successfully");
                        filter_items_by_department();
                        get_sangira_sangira_code()
                        $("#patient_bill_dialog").dialog("close");

                    }else{
                        alert(data);
                    }
                    $("#progress_dialog").hide();
                }
            });
          }
        }else{
            alert("Select Item to create e-Bill");
            $(".select_item_column").css("background","red");
        } 
    }
    
    ///////////////////////////////////
//    function make_epayment(payment_direction){
//        var selected_Payment_Item_Cache_List_ID = []; 
//          var grand_total_price=0;
////        $(".Payment_Item_Cache_List_ID_selected").each(function() {
////            selected_Payment_Item_Cache_List_ID.push($(this).val());
////           var Payment_Item_Cache_List_ID=$(this).val();
////           
////           grand_total_price+=$("total_price"+Payment_Item_Cache_List_ID).val();
////           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
////	});
//      
//         $(".Payment_Item_Cache_List_ID:checked").each(function() {
//            selected_Payment_Item_Cache_List_ID.push($(this).val());
//            var Payment_Item_Cache_List_ID=$(this).val();
//           
//           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
////           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
//	});
//        
////        alert(grand_total_price);
//        var Registration_ID='<?= $Registration_ID ?>';
//        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
//        var validate=0;
//        var patient_phone_number=$("#patient_phone_number").val();
//        var patient_phone_number_ = patient_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
//        if(patient_phone_number_==""){
//            $("#patient_phone_number").css("border","2px solid red");
//            $("#patient_phone_number").focus();
//            validate++;
//        }else{
//           $("#patient_phone_number").css("border",""); 
//        }
//        var selected_Payment_Item_Cache_List_ID = []; 
//        $(".Payment_Item_Cache_List_ID:checked").each(function() {
//            selected_Payment_Item_Cache_List_ID.push($(this).val());
//	});
//        if(selected_Payment_Item_Cache_List_ID.length>0){
//            $(".select_item_column").css("background","white");
//            
//        //console.log("==>"+selected_Payment_Item_Cache_List_ID+"\n");
//        if(validate<=0){
//            <?php $Employee_ID=$_SESSION['userinfo']['Employee_ID']; ?>
//                var Employee_ID='<?= $Employee_ID ?>'       
//            $("#progress_dialog").show();
//            $.ajax({
//                type:'POST',
//                url:'http://127.0.0.1/mobile_processing_payfolder/ajax_card_and_mobile_epayment.php',
////                url:'http://192.168.43.22/mobile_processing_payfolder/ajax_card_and_mobile_epayment.php',
//                data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,grand_total_price:grand_total_price,Registration_ID:Registration_ID,patient_phone_number_:patient_phone_number_,Payment_Cache_ID:Payment_Cache_ID,Employee_ID:Employee_ID,payment_direction:payment_direction},
//                success:function(data){
//                    console.log(data);
//                    $("#card_mobile_payment_feedback").html(data);
//                    if(data == "Accepted Successfully"){
//                       alert("Payment Created Successfully");
//                        filter_items_by_department();
//                        get_sangira_sangira_code()
//                        $("#patient_bill_dialog").dialog("close");
//
//                    }else{
//                        alert("Process fail please try again");
//                    }
//                    $("#progress_dialog").hide();
//                }
//            });
//          }
//        }else{
//            alert("Select Item to create e-Bill");
//            $(".select_item_column").css("background","red");
//        } 
//    }
    function get_sangira_sangira_code(){
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
//        alert("nnn");
        $.ajax({
            type:'POST',
            url:'ajax_get_sangira_sangira_code.php',
            data:{Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID},
            success:function(data){
                $("#sangila_code_feedback").html("SANGIRA CODE =>"+data);
            },
            error:function(x,y,z){
               // alert(x+y+z);
            }
        });
    }
    
    function print_all_sangira(){
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        window.open("print_all_sangira_code_pdf.php?Registration_ID=" + Registration_ID+"&Payment_Cache_ID="+Payment_Cache_ID); 
    }
    
    function update_transaction_feedback(selected_Payment_Item_Cache_List_ID,grand_total_price){
        $.ajax({
                type:'POST',
                url:'ajax_update_transaction_feedback.php',
                data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,grand_total_price:grand_total_price},
                success:function(data){
                    console.log(data); 
                }
            }); 
    }
    function request_control_number(){
        
          var selected_Payment_Item_Cache_List_ID = []; 
          var grand_total_price=0;
//        $(".Payment_Item_Cache_List_ID_selected").each(function() {
//            selected_Payment_Item_Cache_List_ID.push($(this).val());
//           var Payment_Item_Cache_List_ID=$(this).val();
//           
//           grand_total_price+=$("total_price"+Payment_Item_Cache_List_ID).val();
//           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
//	});
      
         $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val();
           
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
//           alert(Payment_Item_Cache_List_ID+"==>"+grand_total_price)
	});
        
        
        var selected_Payment_Item_Cache_List_ID = [];
        $(".Payment_Item_Cache_List_ID_selected").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
          var validate=0;
        var patient_phone_number=$("#patient_phone_number").val();
        var patient_phone_number_ = patient_phone_number.replace(/^\s+/, '').replace(/\s+$/, '');
        if(patient_phone_number_==""){
            $("#patient_phone_number").css("border","2px solid red");
            $("#patient_phone_number").focus();
            validate++;
        }else{
           $("#patient_phone_number").css("border",""); 
        }
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
            
        if(validate<=0){
            $("#progress_dialog").show();
            $.ajax({
            type:'POST',
            url:'gepg_processing_payfolder/index.php',
            data:{Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID,grand_total:grand_total_price,selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,patient_phone_number:patient_phone_number},
            success:function(data){
                if(data=="fail"){
                    alert("Process fail...please try again");  
                }else{
                    $("#progress_dialog").hide();
                    //alert("Payment Created Successfully");
                    filter_items_by_department();
                    $("#patient_bill_dialog").dialog("close");
                    setTimeout(function(){read_return_controlnumber(data)}, 3000);
                    
                }
               console.log(data);
            }
        });
        }
        }else{
            alert("Select Item to create e-Bill");
            $(".select_item_column").css("background","red");
        }
      
    }
    function read_return_controlnumber(BillId){
        var Registration_ID='<?= $Registration_ID ?>';
        $.ajax({
            type:'POST',
            url:'ajax_read_return_controlnumber.php',
            data:{BillId:BillId,RI:Registration_ID },
            success:function(data){
                    $("#contronumber_dialog").html(data);
                    $("#contronumber_dialog").dialog({title: 'PAYMENT CONTROL NUMBER',width: '40%', height: 200, modal: true,});   
            }
        });
    }
    
    
    function crdb_card_makepayment(){
        var selected_Payment_Item_Cache_List_ID = []; 
        var grand_total_price=0;
         $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val(); 
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
	});
        console.log("total_price=1==>"+grand_total_price);
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var validate=0;
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
        if(validate<=0){
            $("#progress_dialog").show();
            $.ajax({
                type:'POST',
                url:'ajax_crdb_create_epayment_bill.php',
                data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,grand_total_price:grand_total_price,Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID},
                success:function(data){
                    console.log("total_price=2==>"+grand_total_price);
                    console.log("1===>"+data);
                    if(data=="already_created"){
                        alert("Selected Item(s) Arleady Sent To POS");
                        $("#progress_dialog").hide();
                    }else{
                        if(data!="0"&&data!="fail"){
                            crdbtransactiondetails(data);
                        }else{
                            alert("PROCESS FAIL!...TRY AGAIN"+data);
                            $("#progress_dialog").hide();
                        }
                    }
//                    $("#progress_dialog").hide();
                }
            });
          }
        }else{
            alert("Select Item to create e-Bill");
            $(".select_item_column").css("background","red");
        } 
    }
    function Print_Receipt_Payment_new(Payment_Code){
        $.ajax({
            type:'POST',
            url:'ajax_Print_Receipt_Payment_new.php',
            data:{Payment_Code:Payment_Code},
            success:function(data){
                if(data=="not_paid"){
                    $("#print_receipt_msg").dialog("open");
                }else{
                    window.open("invidualsummaryreceiptprint.php?Patient_Payment_ID=" + data + "&IndividualSummaryReport=IndividualSummaryReportThisForm");
                }
            }
        });
    }
    function crdbtransactiondetails(Transaction_ID){
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var Registration_ID='<?= $Registration_ID ?>';
        $.ajax({
                type:'POST',
                url:'ajax_new_crdbtransactiondetails.php',
                data:{Payment_Cache_ID:Payment_Cache_ID},
                success:function(data){
                    console.log("2===>"+data);
                    if(data!="fail"){
//                        alert("2===>"+data);
//                        ecr_paycode(data) 
                        var Payment_Code=data;
                        
                        var html="<div class='pull-right'><input type='button' value='RE-SYNCHRONIZE' class='art-button-green' onclick='sync_epayments_force(\""+Registration_ID+"\",\""+Transaction_ID+"\",\""+Payment_Code+"\")'>&nbsp;&nbsp;<input type='button' class='art-button-green' value='PRINT DETAIL RECEIPT' onclick='Print_Receipt_Payment_new(\""+Payment_Code+"\")'></div>";
                        $("#print_and_synchronyzation_btn").html(html);
//                        console.log(html);
                        ecr_paycode(data)
                    }else{
                        alert("Process Fail!...Try again");
                    }
                    $("#progress_dialog").hide();
                }
            });
          }
          
    function open_verify_card_dialogy(Registration_ID){
          var selected_Payment_Item_Cache_List_ID = []; 
          var grand_total_price=0;

         $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val();
           grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
	});
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        var validate=0;
       
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
        if(selected_Payment_Item_Cache_List_ID.length>0){
            $(".select_item_column").css("background","white");
        if(validate<=0){
            <?php $Employee_ID=$_SESSION['userinfo']['Employee_ID']; ?>
                var Employee_ID='<?= $Employee_ID ?>'       
            $("#progress_dialog").show();
            $.ajax({
                type:'POST',
                url:'ajax_open_verify_card_dialogy.php',
                data:{Registration_ID:Registration_ID,grand_total_price:grand_total_price},
                success:function(data){
                    $("#patient_bill_dialog").dialog({title: 'AFYA CARD DETAILS',width: '50%', height: 600, modal: true,});
                    $("#patient_bill_dialog").html(data);
                    $("#progress_dialog").hide();
                }
             });
            } 
        }else{
            alert("Select Item to create e-Bill");
            $("#select_all_checkbox").focus();
            $(".select_item_column").css("background","red");
        }
    }
          function print_sangira_code(card_and_mobile_payment_transaction_id,amount){
              var Registration_ID='<?= $Registration_ID ?>';
              window.open("print_sangira_code_pdf.php?Registration_ID=" + Registration_ID+"&amount="+amount+"&card_and_mobile_payment_transaction_id="+card_and_mobile_payment_transaction_id);              
          }
          
          function afyacard_card_equiry(card_no){
              $("#authorize_afya_card_dialog_tbl").html("<tr><td style=''><b>Verify Card onprogress . . .</b></td></tr>");
              $.ajax({
                type:'POST',
                url:'ajax_afyacard_card_equiry.php',
                data:{card_no:card_no},
                success:function(data){
                  $("#authorize_afya_card_dialog_tbl").append("<tr><td style='display:none'><b>"+data+"</b></td></tr>"); 
                  var respCode=$("respCode").html();
                  var respStatus=$("respStatus").html();
                  var respMessage=$("respMessage").html();
                  if(respCode=="0"&&respStatus=="success"){
                      $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>Verify Card Successfull</b></td></tr>"); 
                      $("#authorize_afya_card_dialog_tbl").append("<tr><td><b>Mwambie mgonjwa aweke kidole</b><input type='button' value='VERIFY FINGERPRINT' id='verify_finger_print_btn' class='art-button pull-right' onclick='verify_finger_print_for_afyacard_payment(\""+card_no+"\")'/></td></tr>"); 
                  }else{
                      $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red'><b>"+respMessage+"</b></td></tr>"); 
                  }
                 
                  
                }
             });
          }
          function verify_finger_print_for_afyacard_payment(card_no){
            $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>Finger Print verification . . .</b></td></tr>");  
            var Registration_ID='<?= $Registration_ID ?>';
             Number.prototype.format=function(n,x){
            var re='\\d(?=(\\d{'+(x||3)+'})+'+(n>0?'\\.':'$')+')';
            return this.toFixed(Math.max(0,~~n)).replace(new RegExp(re,'g'),'$&,');
        };
            $.ajax({
                type:'POST',
                url:'finger_print_engine.php',
                data:{Registration_ID:Registration_ID,operation:'verify_for_payment'},
                success:function(data){
                  if(data=="verification_successfull"){
                      $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>Finger Print Successfull</b></td></tr>");
//                      $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>Finger Print Successfull</b></td></tr>");
                      var total_amount_to_be_paid_by_afyacard=parseFloat($("#total_amount_to_be_paid_by_afyacard").val());
                      var cardBalance=parseFloat($("cardBalance").html());
                      if(total_amount_to_be_paid_by_afyacard>cardBalance){
                            $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red;font-size:17px'><b>Insuficient Balance</b><br/>Card Balance: <b>"+cardBalance.format()+"</b> <br/>Transaction Amount: <b>"+total_amount_to_be_paid_by_afyacard.format()+"</b><br/><span style='color:#000000'>Pesa ya kuongezea : <b>"+(total_amount_to_be_paid_by_afyacard-cardBalance).format()+"</b></span></td></tr>");
                      }else{
//                            $("#authorize_afya_card_dialog_tbl").append("<tr><td style=''>Card Balance:<b>"+cardBalance+card_no+"</b> Transaction Amount:<b>"+total_amount_to_be_paid_by_afyacard+"</b></td></tr>");
                            request_afyacard_payment(card_no)
                      }
                  }else{
                      $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red'><b>Invalid Finger Print</b></td></tr>");  
                  }
                    
                }
            }); 
          }
    function request_afyacard_payment(card_no){
        
        ////////////////////////////
        var selected_Payment_Item_Cache_List_ID = []; 
        var grand_total_price=0;
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
            var Payment_Item_Cache_List_ID=$(this).val();
            grand_total_price+=parseInt($("#total_price"+Payment_Item_Cache_List_ID).val());
	});
        var Registration_ID='<?= $Registration_ID ?>';
        var Payment_Cache_ID='<?= $Payment_Cache_ID ?>';
        <?php $Employee_ID=$_SESSION['userinfo']['Employee_ID']; ?>
        var Employee_ID='<?= $Employee_ID ?>'  
        var validate=0;
        var selected_Payment_Item_Cache_List_ID = []; 
        $(".Payment_Item_Cache_List_ID:checked").each(function() {
            selected_Payment_Item_Cache_List_ID.push($(this).val());
	});
          //////////////////////////////
            
            $("#authorize_afya_card_dialog_tbl").append("<tr><td><b>Processing Payment . . . </b></td></tr>");  
            var Registration_ID='<?= $Registration_ID ?>';
                $.ajax({
                    type:'POST',
                    url:'ajax_request_afyacard_payment.php',
                    data:{selected_Payment_Item_Cache_List_ID:selected_Payment_Item_Cache_List_ID,grand_total_price:grand_total_price,Registration_ID:Registration_ID,Payment_Cache_ID:Payment_Cache_ID,Employee_ID:Employee_ID,card_no:card_no},
                    success:function(data){
                        $("#afya_card_payment_feedback").html(data);
                       var respCode=$("respCode").html();
                       var respStatus=$("respStatus").html();
                       var respMessage=$("respMessage").html();
                        if(respCode=="0"&&respStatus=="success"){
                            save_returned_afya_card_payment();
                        }else{
                            $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red'><b>Transaction Fail=>"+respMessage+"</b></td></tr>");
                        }
                    },error:function(x,y,z){
                        $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>------:"+x+y+z+"</b></td></tr>");  
                    }
                });   
          }
        function save_returned_afya_card_payment(){
               var topupBankRef=$("topupBankRef").html();
               var topupReceiptNumber=$("topupReceiptNumber").html();
               var payerName=$("payerName").html();
               var serviceName=$("serviceName").html();
               var serviceDescription=$("serviceDescription").html();
               var amountPaid=$("amountPaid").html();
               var transactionDate=$("transactionDate").html();
//                $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red'><b>saving....=></b></td></tr>");
               $.ajax({
                  type:'POST',
                  url:'ajax_save_returned_afya_card_payment.php',
                  data:{topupBankRef:topupBankRef,topupReceiptNumber:topupReceiptNumber,payerName:payerName,serviceName:serviceName,serviceDescription:serviceDescription,amountPaid:amountPaid,transactionDate:transactionDate},
                  success:function(data){
                      if(data!="failed"){
                            $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>Payment Successfully</b></td></tr>");
                            $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red'><input type='button' class='art-button-green' value='PRINT RECEIPT' onclick='Print_Receipt_Payment("+data+")'></td></tr>");
                      }else{
                            $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:red'><b>"+data+"--->TRANSACTION FAIL</b></td></tr>");
                      }
                  },error:function(x,y,z){
                      $("#authorize_afya_card_dialog_tbl").append("<tr><td style='color:green'><b>------:"+x+y+z+"</b></td></tr>");  
                  }
              });
        }
        function Print_Receipt_Payment(Patient_Payment_ID){
        var winClose=popupwindow('individualpaymentreportindirect.php?Patient_Payment_ID='+Patient_Payment_ID+'&IndividualPaymentReport=IndividualPaymentReportThisPage', 'Receipt Patient', 530, 400);
    }
</script>
<script type="text/javascript" src="js/afya_card.js"></script>
<script type="text/javascript" src="js/finger_print.js"></script>
<div id="afyacard_verify_card_dialogy"></div>

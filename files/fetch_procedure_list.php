<?php
include("./includes/connection.php");

$search_for = $_POST['search_for'];
if($search_for == 'Procedure'){
  $Procedure_Name = $_POST['Procedure_Name'];
  $query = mysqli_query($conn,"SELECT * FROM `tbl_items` WHERE Consultation_Type='Procedure' AND Product_Name like '%$Procedure_Name%' LIMIT 100");
  $count_procedure =1;
  while ($row = mysqli_fetch_assoc($query)) {
    extract($row);
    echo $count_procedure."- <input type='radio' name='procedure' onclick='Select_Procedure(\"{$Product_Name}\",\"{$Item_ID}\");'>".$Product_Name."<br><hr>";
    $count_procedure++;
  }
}
if($search_for =='Items'){
  $Item_Name = $_POST['Item_Name'];
  $item_count = 1;
  $item_query = mysqli_query($conn,"SELECT * FROM `tbl_items` WHERE Consultation_Type='Pharmacy' AND Product_Name like '%$Item_Name%' LIMIT 100");
  while ($row = mysqli_fetch_assoc($item_query)) {
    extract($row);
    echo $item_count."- <input type='checkbox' name='procedure' id='item_".$Item_ID."' onclick='Select_Item(\"{$Product_Name}\",\"{$Item_ID}\");'>".$Product_Name."<br><hr>";
    $item_count++;
  }
}
if($search_for == 'map_items'){
  $Items = $_POST['Items'];
  $Employee_ID = $_POST['Employee_ID'];
  $Procedure = $_POST['Procedure'];
  $Status='fail';
  foreach ($Items as $item) {
    $query = mysqli_query($conn,"SELECT * FROM tbl_procedure_items_map WHERE  Procedure_ID = $Procedure AND Item_ID = $item");
    if(mysqli_num_rows($query) > 0){
      continue;
    }
    $results = mysqli_query($conn,"INSERT INTO tbl_procedure_items_map(Procedure_ID,Item_ID,Employee_ID) VALUES($Procedure,$item,$Employee_ID)");
    if($results){
      $Status='ok';
    }
  }
  echo $Status;
}

if($search_for == 'procedure_list'){
  $Procedure_ID = $_POST['Item_ID'];
  $count = 1;
  $query = mysqli_query($conn,"SELECT i.Item_ID, i.Product_Name FROM tbl_procedure_items_map pim, tbl_items i WHERE i.item_ID = pim.Item_ID AND Procedure_ID = $Procedure_ID");
  $data['data']='';
  while ($row = mysqli_fetch_assoc($query)) {
    $Item_ID=$row['Item_ID'];
    $data['data'].= '<tr id="'.$Item_ID.'"><td width="10%">'.$count.'</td><td width="80%">'.$row['Product_Name'].'</td><td width="8%"><input type="button" value="X" onclick="Remove_Procedure_Item('.$Item_ID.');"></td></tr>';
    $count++;
  }
  //$data['data'].= "<tr><td colspan='3' style='text-align:right;'><input type='button' name='btn_edit' value='Edit' class='art-button-green'></td></tr>";
  $data['count']=$count;
  echo json_encode($data);
}

if($search_for == 'remove_item'){
  $Item_ID = $_POST['Item_ID'];
  $Procedure_ID = $_POST['Procedure_ID'];
  mysqli_query($conn,"DELETE FROM tbl_procedure_items_map WHERE Item_ID = $Item_ID AND Procedure_ID = $Procedure_ID");

}
/*
  display list of assigned items to a procedure
*/
if($search_for == 'saved_list'){
  $Procedure_ID = $_POST['Procedure'];
  $Sponsor_ID = $_POST['Sponsor_ID'];
  $Employee_ID = $_POST['Employee_ID'];
  $query = mysqli_query($conn,"SELECT * FROM tbl_procedure_items_map pim, tbl_items i  WHERE i.Item_ID = pim.Item_ID AND pim.Procedure_ID = $Procedure_ID ");
  echo "<table width='100%' id='myTable'>";
  if(mysqli_num_rows($query) > 0){
    echo "<div>
      <b>Select Dispending Store:</b>
      <select id='Procedure_Despensing_Store' onchange='Update_Display_Balance(\"{$Procedure_ID}\");'>
        <option value=''>~~~Select Store~~~</option>";
        $sub_department = mysqli_query($conn,"SELECT DISTINCT sdep.Sub_Department_ID, sdep.Sub_Department_Name from tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed where dep.department_id = sdep.department_id and sdep.Sub_Department_Status = 'active' and ed.Employee_ID = '$Employee_ID' and ed.Sub_Department_ID = sdep.Sub_Department_ID and dep.Department_Location in('Storage And Supply') ");

        //
        // $sub_department = mysqli_query($conn,"SELECT DISTINCT sd.Sub_Department_ID, sd.Sub_Department_Name FROM tbl_sub_department sd, tbl_employee_sub_department esd, tbl_department d WHERE sd.Sub_Department_ID = esd.Sub_Department_ID AND d.Department_ID = sd.Department_ID AND d.Department_Name = 'Storage And Supply'  AND esd.Employee_ID = '$Employee_ID' ");
        //
        while ($row = mysqli_fetch_assoc($sub_department)) {
          echo "<option value='".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
        }
        echo"</select>
          </div>";
    echo "<tr><th width='5%'>SN</th><th width='65%'>Item Name</th><th width='10%'>Balance</th><th style='width:10%;'>Quantity</th><th style='width:7%; text-align:center;'>Remove</th><tr>";
    $count=1;
    while ($row = mysqli_fetch_assoc($query)) {
      echo "<tr class='item'><td>".$count."</td><td><input type='hidden' value='".$row['Item_ID']."'>".$row['Product_Name']."</td><td><input type='text' readonly='readonly'></td><td><input type='text' name='quantity_given' class='quantity_given' oninput='Check_Dispensing_Store(this);'></td><td style='text-align:center;'><input type='button' value='X' style='color:red;' onclick='Remove_Mapped_item(this);'></td></tr>";
      $count++;
    }
    echo "<tr><td colspan='4'>&emsp;</td></tr>";
    echo "<tr><td colspan='4'><input type='button' name='btn_save_quantity' style='float:right;width:120px;height:50px;font-size:18px;' value='Save' class='art-button-green' onclick='Save_Item_List(\"$Patient_Payment_ID\");'></td></tr>";
  }else{
    echo "<tr><td colspan='4'  style='text-align:center;height:200px;font-size:20px;'>No Items Attached to this Procedure</td></tr>";
  }
  echo "</table>";
}

if($search_for == 'store_balance'){
  $data = [];
  $Procedure_ID = $_POST['Procedure_ID'];
  $Store_ID = $_POST['Store_ID'];
  $Sponsor_ID = $_POST['Sponsor_ID'];
  $query = mysqli_query($conn,"SELECT * FROM tbl_procedure_items_map pim, tbl_items i  WHERE i.Item_ID = pim.Item_ID AND pim.Procedure_ID = $Procedure_ID ");
  while ($row = mysqli_fetch_assoc($query)) {
    $Item_ID = $row['Item_ID'];
    $balance = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_Balance FROM tbl_items_balance WHERE Sub_Department_ID = '$Store_ID' AND Item_ID = '$Item_ID'"))['Item_Balance'];
    array_push($data,$balance);
  }
  echo json_encode($data);
}

if($search_for == 'save_done_procedure'){
  $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
  $Procedure = $_POST['Procedure'];
  $quantity_item_list = $_POST['quantity_item_list'];
  $Employee_ID = $_POST['Employee_ID'];
  $Sponsor_ID = $_POST['Sponsor_ID'];
  $Store_ID = $_POST['Store_ID'];
  $Registration_ID = $_POST['Registration_ID'];
  $query = mysqli_query($conn,"SELECT * FROM tbl_patient_payment_item_list  WHERE Patient_Payment_ID=$Patient_Payment_ID limit 1");
  $result=mysqli_fetch_assoc($query);
  $Check_In_Type = $result['Check_In_Type'];
  $Category = $result['Category'];
  $Patient_Direction = $result['Patient_Direction'];
  $Clinic_ID = $result['Clinic_ID'];
  $ItemOrigin = $result['ItemOrigin'];
  $Billing_approval_status = $result['Billing_approval_status'];
  $Nursing_Status = $result['Nursing_Status'];
  $already_consulted = $result['already_consulted'];
  $Hospital_Ward_ID = $result['Hospital_Ward_ID'];
  $finance_department_id = $result['finance_department_id'];
  $Patient_Direction = $result['Patient_Direction'];

  $count = 0;
  $save_info = 'not ok';

  $items_data = json_decode($_POST['items_data']);
  //echo 'see the list '.print_r($items_data);
  foreach ($items_data as $key => $value) {
    //echo 'key = '.$value->ID.' , value = '.$value->quantity.'<br>';
    $Item_ID = $value->ID;
    $Quantity = $value->quantity;

    //check item Price
    $product_price = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price  WHERE  Sponsor_ID = '$Sponsor_ID' AND Item_ID = '$Item_ID'"))['Items_Price'];
    $query_result= false;
    if(!($product_price == 0)){
     mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Status,	Patient_Payment_ID,Transaction_Date_And_Time,	Process_Status,Nursing_Status,Sub_Department_ID,ServedDateTime,ItemOrigin,	Billing_approval_status, 	payment_type,	already_consulted,Hospital_Ward_ID,finance_department_id, clinic_location_id) VALUES('$Check_In_Type','$Category','$Item_ID',(SELECT Items_Price FROM tbl_item_price WHERE Item_ID=$Item_ID AND Sponsor_ID = $Sponsor_ID LIMIT 1),'$Quantity','$Patient_Direction',(SELECT Employee_Name FROM tbl_employee WHERE Employee_ID=$Employee_ID),$Employee_ID,'$Clinic_ID','served', $Patient_Payment_ID,(SELECT NOW()),	'served','$Nursing_Status','$Sub_Department_ID',(SELECT NOW()),'$ItemOrigin',	'$Billing_approval_status', '',	'$already_consulted', '$Hospital_Ward_ID','$finance_department_id', '$clinic_location_id')") or die(mysqli_error($conn));
     $query_result = true;
   }
     mysqli_query($conn,"UPDATE tbl_items_balance SET Item_Balance = (Item_Balance - '$Quantity') WHERE Sub_Department_ID = '$Store_ID' AND
     Item_ID = '$Item_ID'");
    $count++;
    if($query_result){
      $save_info = 'ok';
    }

  }
  echo $save_info;
}

if($search_for == 'validate_items'){
  $items_object = $_POST['items'];
  $invalid_items =[];
  $message =[];
  $procedure = '';
  $count = 0;
  $items = json_decode($items_object);
  foreach ($items as $key => $item) {
    $item_details = explode("_",$item);
    /*
    array index 0 = procedure id
    array index 1 = payment id
    array index 2 = procedure name
    */
    //echo 'item = '.$item_details[0].' payment = '.$item_details[1].' PRODUCT = '.$item_details[2].'<br>';
    $has_consummable = mysqli_query($conn,"SELECT Item_ID FROM tbl_procedure_items_map WHERE Procedure_ID = ".$item_details[0]." ");
    if(mysql_fetch_row($has_consummable) > 0){
      
      $result = mysqli_query($conn,"SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Item_ID IN (SELECT Item_ID FROM tbl_procedure_items_map WHERE Procedure_ID = ".$item_details[0].") AND Patient_Payment_ID = ".$item_details[1]." ");

      //print_r(mysqli_fetch_assoc($result));
      if(mysqli_num_rows($result) > 0){
        $message['found'] = true;
      }else {
        $message['found'] = false;
        $procedure .= $item_details[2].' and ';
        $count++;
      }
    }

  }
  $message['count'] = $count;
  $message['procedure'] = chop($procedure,' and ');

  echo json_encode($message);
}
 ?>

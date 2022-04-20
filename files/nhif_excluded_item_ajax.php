<?php
include("./includes/connection.php");
if(isset($_POST['item_code'])&&isset($_POST['item_name'])&&isset($_POST['nhif_package'])){
  $item_name=$_POST['item_name'];
  $item_code=$_POST['item_code'];
  $nhif_package=$_POST['nhif_package'];
  $consultation_type=$_POST['consultation_type'];
  if(!empty($item_name)){
    $filter=" AND i.Product_Name LIKE '%$item_name%'";
  }
  if(!empty($item_code)){
     $filter.=" AND  i.Product_Code ='$item_code'";
  }
  if(!empty($nhif_package)){
    $filter.=" AND nss.package_id='$nhif_package'";
 }
 if(!empty($consultation_type)){
    $filter.=" AND i.Consultation_Type ='$consultation_type'";
 }

        $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT ItemCode,nsp.package_name,i.Product_Name,i.Product_Code,i.Consultation_Type from tbl_nhif_services_status nss,tbl_nhif_scheme_package nsp,tbl_items i where nsp.package_id=nss.package_id and i.Product_Code=nss.ItemCode $filter  order by i.Product_Name limit 50") or die(mysqli_error($conn));


  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $package_name=$patient_list_rows['package_name'];
         $Product_Name=$patient_list_rows['Product_Name'];
         $Product_Code=$patient_list_rows['Product_Code'];
         $Consultation_Type=$patient_list_rows['Consultation_Type'];
         $ItemCode = $patient_list_rows['ItemCode'];

         
        echo "
                <tr class='rows_list' >
                        <td>$count_sn.</td>
                        <td>$package_name</td>
                        <td>$Consultation_Type</td>
                        <td>$Product_Code</td>
                        <td>$Product_Name</td>
                    </a>
                </tr>
                ";
        $count_sn++;
      }
  }
}





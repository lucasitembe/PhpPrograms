<?php

function getParameterData($conn,$itemId,$param,$age,$gender,$from,$to){
    // die("-------------------------------------".$from);
   if ($gender=="") {
       $query="SELECT COUNT(ref_test_result_ID) AS tests, ic.Item_ID,i.Product_Name,i.Consultation_Type,pr.parameter,pr.ref_test_result_ID,p.Parameter_Name,TIMESTAMPDIFF(YEAR,reg.Date_Of_Birth,ic.Transaction_Date_And_Time) As age FROM tbl_item_list_cache ic, tbl_test_results tr, tbl_tests_parameters_results pr,tbl_items i, tbl_parameters p, tbl_payment_cache pc,tbl_patient_registration reg WHERE reg.Registration_ID=pc.Registration_ID AND ic.Payment_Cache_ID=pc.Payment_Cache_ID AND ic.Payment_Item_Cache_List_ID = tr.payment_item_ID AND tr.test_result_ID = pr.ref_test_result_ID AND ic.Item_ID = i.Item_ID AND i.Consultation_Type = 'Laboratory' AND pr.parameter = p.parameter_ID AND  pr.TimeSubmitted >='$from' AND pr.TimeSubmitted <= '$to' AND p.parameter_ID='$param' AND i.Item_ID ='$itemId' GROUP BY ic.Item_ID, p.parameter_ID";
    // $query="SELECT COUNT(reg.Registration_ID) As tests FROM tbl_tests_parameters_results pr JOIN tbl_tests_parameters tp ON tp.ref_parameter_ID=pr.parameter 
    // JOIN tbl_parameters p  ON pr.parameter=p.parameter_ID JOIN tbl_test_results tr ON tr.test_result_ID=pr.ref_test_result_ID 
    // JOIN tbl_item_list_cache il ON il.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_payment_cache pc ON il.Payment_Item_Cache_List_ID=pc.Payment_Cache_ID 
    // JOIN tbl_patient_registration reg ON reg.Registration_ID=pc.Registration_ID WHERE p.parameter_ID='$param' AND tp.ref_item_ID='$itemId'AND pr.TimeSubmitted >='$from' AND pr.TimeSubmitted <= '$to'";
    
   } else { 
       $filter=getFilter($age);
       $query="SELECT COUNT(ref_test_result_ID) AS tests, ic.Item_ID,i.Product_Name,i.Consultation_Type,pr.parameter,pr.ref_test_result_ID,p.Parameter_Name,TIMESTAMPDIFF(YEAR,reg.Date_Of_Birth,ic.Transaction_Date_And_Time) As age FROM tbl_item_list_cache ic, tbl_test_results tr, tbl_tests_parameters_results pr,tbl_items i, tbl_parameters p, tbl_payment_cache pc,tbl_patient_registration reg WHERE reg.Registration_ID=pc.Registration_ID AND ic.Payment_Cache_ID=pc.Payment_Cache_ID AND ic.Payment_Item_Cache_List_ID = tr.payment_item_ID AND tr.test_result_ID = pr.ref_test_result_ID AND ic.Item_ID = i.Item_ID AND i.Consultation_Type = 'Laboratory' AND pr.parameter = p.parameter_ID AND  pr.TimeSubmitted >='$from' AND pr.TimeSubmitted <= '$to' AND p.parameter_ID='$param' AND i.Item_ID ='$itemId' AND reg.Gender='$gender' GROUP BY ic.Item_ID, p.parameter_ID $filter";
     
   }
    $select=mysqli_query($conn,$query);
  
    
    return mysqli_num_rows($select)<1?0:mysqli_fetch_assoc($select)['tests'];
}
function getFilter($age){
    switch ($age) {
        case '<1':
            return "HAVING age < 1";
            break;

        case '1.5':
            return "HAVING age = 1.5";
            break;

        case '1-4':
            return "HAVING age BETWEEN 1 AND 4";
            break;

        case '5-60':
            return "HAVING age BETWEEN 5 AND 60";
            break;
        
        case '60':
            return "HAVING age > 60";
            break;
        default:
            return "";
            break;
    }
}
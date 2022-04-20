<style>

    .myheader{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style> 
<?php
session_start();
include("../includes/connection.php");

if (isset($_GET['doctorReview'])) {
    $Registration_ID = filter_input(INPUT_GET, 'Registration_ID');
    $filter = filter_input(INPUT_GET, 'filter');
    
     $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' GROUP BY test_result_ID";
        $anwerQuery = mysql_query($validateQuery);
        $num_rows = mysql_num_rows($anwerQuery);
        $paymentID = array();
        while ($results = mysql_fetch_assoc($anwerQuery)) {
            $paymentID[] = $results['payment_item_ID'];
        }
        $listItems = implode(',', $paymentID);

    if (isset($_GET['consulted'])) {
        $filter.=" AND payment_item_ID IN ($listItems) ";
    }else{
         $filter.=" AND payment_item_ID NOT IN ($listItems) ";
    }

    $sql = "SELECT Payment_Item_Cache_List_ID,pc.consultation_id,i.Product_Name,pc.Billing_Type,maincomplain FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN tbl_consultation_history ch ON ch.consultation_ID=pc.consultation_id WHERE Registration_ID='" . $Registration_ID . "' AND ch.Saved='yes' AND pc.Billing_Type IN ('Outpatient Cash','Outpatient Credit') AND pc.consultation_id IS NOT NULL $filter "; // AND Payment_Item_Cache_List_ID='$payment_id'
    //  echo $sql;exit;



    $query_cons = mysql_query($sql) or die(mysql_error());

    $data = '<div id="tabsInfo">';

    $data .="<div class='myheader'>OUTPATIENT</div>";

    if (mysql_num_rows($query_cons) > 0) {

        $data .='<table width="100%" >
                        <tr style="background: #006400 !important;color: white;">
                            <td>Sn</td>
                            <td style="">
                             <b>Test Name</b>
                            <td>
                              Main Complain
                            </td>
                            <td style="">
                              Provisional Diagnosis
                            </td>
                            <td style="">
                              Differential Diagnosis
                            </td>
                        </tr>';
        $sn = 1;
        while ($row = mysql_fetch_array($query_cons)) {
            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
            $consultation_id = $row['consultation_id'];
            $Product_Name = $row['Product_Name'];
            $billing = $row['Billing_Type'];
            $maincomplain = $row['maincomplain'];

            $sql_dgn = "SELECT diagnosis_type,disease_name FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN tbl_consultation_history ch ON ch.consultation_ID=pc.consultation_id JOIN tbl_disease_consultation dc ON dc.consultation_ID=pc.consultation_id JOIN tbl_disease d ON dc.disease_ID = d.disease_ID WHERE  Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND ch.Saved='yes'"; // AND Payment_Item_Cache_List_ID='$payment_id'

            $provisional_diagnosis = '';
            $diferential_diagnosis = '';

            $select_qry = mysql_query($sql_dgn) or die(mysql_error());

            while ($row = mysql_fetch_assoc($select_qry)) {
                if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                    $provisional_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                    $diferential_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
            }





            $data .= '<tr>
             <td>' . $sn++ . '</td>
             <td>' . $Product_Name . '</td>
             <td>' . $maincomplain . '</td>
             <td>' . $provisional_diagnosis . '</td>
             <td>' . $diferential_diagnosis . '</td>
            </tr> 
           ';
        }
        // echo $Payment_Item_Cache_List_ID.' '.$maincomplain.' '.$billing.'<br/>';
        $data .= '</table>';
    } else {
        $data .= "<p style='font-size:30px;text-align:center;vertical-align:middle;'>Not Available</p>";
    }

    //Inpatient items
    $sql = "SELECT Payment_Item_Cache_List_ID,pc.consultation_id,i.Product_Name,pc.Billing_Type,Findings FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN  tbl_ward_round wr ON wr.Round_ID=pc.Round_ID WHERE pc.Registration_ID='" . $Registration_ID . "' AND wr.Process_Status='served' AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') AND pc.Round_ID IS NOT NULL $filter "; // AND Payment_Item_Cache_List_ID='$payment_id'
    //echo $sql;exit;
    $query_cons_inp = mysql_query($sql) or die(mysql_error());

    $data .="<br/><br/><div class='myheader'>INPATIENT</div>";
    if (mysql_num_rows($query_cons_inp) > 0) {


        $data .='<table width="100%" >
                        <tr style="background: #006400 !important;color: white;">
                            <td>Sn</td>
                            <td style="">
                             <b>Test Name</b>
                            <td>
                             Findings
                            </td>
                            <td style="">
                              Provisional Diagnosis
                            </td>
                            <td style="">
                              Differential Diagnosis
                            </td>
                        </tr>';
        $sn = 1;
        while ($row = mysql_fetch_array($query_cons_inp)) {
            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
            $consultation_id = $row['consultation_id'];
            $Product_Name = $row['Product_Name'];
            $billing = $row['Billing_Type'];
            $findings = $row['Findings'];

            $sql_dgn = "SELECT diagnosis_type,disease_name FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN tbl_ward_round wr ON wr.Round_ID=pc.Round_ID JOIN tbl_ward_round_disease wd ON wd.Round_ID=wr.Round_ID JOIN tbl_disease d ON wd.disease_ID = d.disease_ID WHERE  Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND wr.Process_Status='served'"; // AND Payment_Item_Cache_List_ID='$payment_id'

            $provisional_diagnosis = '';
            $diferential_diagnosis = '';

            $select_qry = mysql_query($sql_dgn) or die(mysql_error());

            while ($row = mysql_fetch_assoc($select_qry)) {
                if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                    $provisional_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                    $diferential_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
            }





            $data .= '<tr>
             <td>' . $sn++ . '</td>
             <td>' . $Product_Name . '</td>
             <td>' . $findings . '</td>
             <td>' . $provisional_diagnosis . '</td>
             <td>' . $diferential_diagnosis . '</td>
            </tr> 
           ';
        }
        // echo $Payment_Item_Cache_List_ID.' '.$maincomplain.' '.$billing.'<br/>';
        $data .= '</table>';
    } else {
        $data .= "<p style='font-size:30px;text-align:center;vertical-align:middle;'>Not Available</p>";
    }

    $data .='</div>';
    echo $data;
}elseif (isset($_GET['doctorReviewSpecColl'])) {
    $Registration_ID = filter_input(INPUT_GET, 'Registration_ID');
    $payment_id = filter_input(INPUT_GET, 'payment_cache_id');
    $filter = filter_input(INPUT_GET, 'filter');
    
    //echo $Registration_ID.' '.$payment_id.' '.$filter;
    
//     $sql = "SELECT Payment_Item_Cache_List_ID,pc.consultation_id,i.Product_Name,pc.Billing_Type,maincomplain "
//             . "FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID "
//             . "INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID "
//             . "JOIN tbl_consultation_history ch ON ch.consultation_ID=pc.consultation_id WHERE Registration_ID='" . $Registration_ID . "' AND ch.Saved='yes' AND pc.Billing_Type IN ('Outpatient Cash','Outpatient Credit') AND pc.consultation_id IS NOT NULL $filter "; // AND Payment_Item_Cache_List_ID='$payment_id'
//    //  echo $sql;exit;

 $sql = "select Payment_Item_Cache_List_ID,pc.consultation_id,i.Product_Name,pc.Billing_Type,maincomplain
                    from tbl_payment_cache as pc 
                    join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID 
                    join tbl_items as i on i.Item_ID =pl.Item_ID 
                    JOIN tbl_consultation_history ch ON ch.consultation_ID=pc.consultation_id
                    where pl.Check_In_Type='Laboratory' and pc.Payment_Cache_ID ='$payment_id'
                    AND ch.Saved='yes' AND pc.Billing_Type IN ('Outpatient Cash','Outpatient Credit') AND pc.consultation_id IS NOT NULL
                    and (pl.Status='active' or pl.Status='paid') $filter";
 
    $query_cons = mysql_query($sql) or die(mysql_error());
   
    $data = '<div id="tabsInfo">';

    $data .="<div class='myheader'>OUTPATIENT</div>";

    if (mysql_num_rows($query_cons) > 0) {

        $data .='<table width="100%" >
                        <tr style="background: #006400 !important;color: white;">
                            <td>Sn</td>
                            <td style="">
                             <b>Test Name</b>
                            <td>
                              Main Complain
                            </td>
                            <td style="">
                              Provisional Diagnosis
                            </td>
                            <td style="">
                              Differential Diagnosis
                            </td>
                        </tr>';
        $sn = 1;
        while ($row = mysql_fetch_array($query_cons)) {
            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
            $consultation_id = $row['consultation_id'];
            $Product_Name = $row['Product_Name'];
            $billing = $row['Billing_Type'];
            $maincomplain = $row['maincomplain'];

            $sql_dgn = "SELECT diagnosis_type,disease_name FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN tbl_consultation_history ch ON ch.consultation_ID=pc.consultation_id JOIN tbl_disease_consultation dc ON dc.consultation_ID=pc.consultation_id JOIN tbl_disease d ON dc.disease_ID = d.disease_ID WHERE  Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND ch.Saved='yes'"; // AND Payment_Item_Cache_List_ID='$payment_id'

            $provisional_diagnosis = '';
            $diferential_diagnosis = '';

            $select_qry = mysql_query($sql_dgn) or die(mysql_error());

            while ($row = mysql_fetch_assoc($select_qry)) {
                if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                    $provisional_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                    $diferential_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
            }





            $data .= '<tr>
             <td>' . $sn++ . '</td>
             <td>' . $Product_Name . '</td>
             <td>' . $maincomplain . '</td>
             <td>' . $provisional_diagnosis . '</td>
             <td>' . $diferential_diagnosis . '</td>
            </tr> 
           ';
        }
        // echo $Payment_Item_Cache_List_ID.' '.$maincomplain.' '.$billing.'<br/>';
        $data .= '</table>';
    } else {
        $data .= "<p style='font-size:30px;text-align:center;vertical-align:middle;'>Not Available</p>";
    }

    //Inpatient items
   // $sql = "SELECT Payment_Item_Cache_List_ID,pc.consultation_id,i.Product_Name,pc.Billing_Type,Findings FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN  tbl_ward_round wr ON wr.Round_ID=pc.Round_ID WHERE pc.Registration_ID='" . $Registration_ID . "' AND wr.Process_Status='served' AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') AND pc.Round_ID IS NOT NULL $filter "; // AND Payment_Item_Cache_List_ID='$payment_id'
 $sql = "select Payment_Item_Cache_List_ID,pc.consultation_id,i.Product_Name,pc.Billing_Type,Findings
                    from tbl_payment_cache as pc 
                    join tbl_item_list_cache as pl on pc.Payment_Cache_ID =pl.Payment_Cache_ID 
                    join tbl_items as i on i.Item_ID =pl.Item_ID 
                    join tbl_ward_round wr ON wr.Round_ID=pc.Round_ID
                    where pl.Check_In_Type='Laboratory' and pc.Payment_Cache_ID ='$payment_id'
                    AND wr.Process_Status='served' AND pc.Billing_Type IN ('Inpatient Cash','Inpatient Credit') AND pc.Round_ID IS NOT NULL
                    and (pl.Status='active' or pl.Status='paid') $filter";    
//echo $sql;exit;
    $query_cons_inp = mysql_query($sql) or die(mysql_error());

    $data .="<br/><br/><div class='myheader'>INPATIENT</div>";
    if (mysql_num_rows($query_cons_inp) > 0) {


        $data .='<table width="100%" >
                        <tr style="background: #006400 !important;color: white;">
                            <td>Sn</td>
                            <td style="">
                             <b>Test Name</b>
                            <td>
                             Findings
                            </td>
                            <td style="">
                              Provisional Diagnosis
                            </td>
                            <td style="">
                              Differential Diagnosis
                            </td>
                        </tr>';
        $sn = 1;
        while ($row = mysql_fetch_array($query_cons_inp)) {
            $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
            $consultation_id = $row['consultation_id'];
            $Product_Name = $row['Product_Name'];
            $billing = $row['Billing_Type'];
            $findings = $row['Findings'];

            $sql_dgn = "SELECT diagnosis_type,disease_name FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID JOIN tbl_ward_round wr ON wr.Round_ID=pc.Round_ID JOIN tbl_ward_round_disease wd ON wd.Round_ID=wr.Round_ID JOIN tbl_disease d ON wd.disease_ID = d.disease_ID WHERE  Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' AND wr.Process_Status='served'"; // AND Payment_Item_Cache_List_ID='$payment_id'

            $provisional_diagnosis = '';
            $diferential_diagnosis = '';

            $select_qry = mysql_query($sql_dgn) or die(mysql_error());

            while ($row = mysql_fetch_assoc($select_qry)) {
                if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                    $provisional_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
                if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                    $diferential_diagnosis.= ' ' . $row['disease_name'] . ';';
                }
            }





            $data .= '<tr>
             <td>' . $sn++ . '</td>
             <td>' . $Product_Name . '</td>
             <td>' . $findings . '</td>
             <td>' . $provisional_diagnosis . '</td>
             <td>' . $diferential_diagnosis . '</td>
            </tr> 
           ';
        }
        // echo $Payment_Item_Cache_List_ID.' '.$maincomplain.' '.$billing.'<br/>';
        $data .= '</table>';
    } else {
        $data .= "<p style='font-size:30px;text-align:center;vertical-align:middle;'>Not Available</p>";
    }

    $data .='</div>';
    echo $data;
    
}





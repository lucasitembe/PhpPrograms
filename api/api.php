<?php
include './Db.php';
$db=new Db();

/*
 * 
 * The moment is here we are going
 * 
 */



if(isset($_GET['gacc'])){
    $start_date= filter_input(INPUT_GET, 'start_date');
    $end_date= filter_input(INPUT_GET, 'end_date');
    
    $result = array();
    
//    if($_GET['gacc']=='profitloss'){
//        $result= $db->getProfitLoss($start_date, $end_date);
//    }if($_GET['gacc']=='balancesheet'){
//        $result= $db->getBalanceSheet($start_date, $end_date);
//    }if($_GET['gacc']=='receivables'){
//        $result= $db->getDebtors($start_date, $end_date); //
//    }if($_GET['gacc']=='payables'){
//        $result= $db->getPayables($start_date, $end_date);
//    }if($_GET['gacc']=='purchases'){
//        $result= $db->getPurchases($start_date, $end_date);
//    }

    if ($_GET['gacc'] == 'supplier') {
        $result = $db->getSuppliers();
    } else if ($_GET['gacc'] == 'sponsors') {
        $result = $db->getSponsors();
    } else if ($_GET['gacc'] == 'budget') {
        $dept_name = filter_input(INPUT_GET, 'dept_name');
        $result = $db->getActualAmount($start_date, $end_date, $dept_name);
    }
    
     echo json_encode($result);
}

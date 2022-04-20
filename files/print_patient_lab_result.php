<?php
$data = '<style>
    .wrapper{
        width:100%;
        margin:10px auto; 
        padding:10px; 
        //border:1px solid blue;
        font-family:"Times New Roman",Times,serif; //Gill, Helvetica, sans-serif
    }
    .labpatientInfo,
    .labResultsInfo,.patientDetails{
       // clear: both;
        // margin:10px; 
         
    }
    
    .labpatientInfo{
      background-color:#ccc;
      padding: 10px;
      width:100%;
      text-align:center;
      border-bottom:1px solid #ccc;
    }
    .labResults{
       width:100%;
    }
     h1{
        margin: 0;
        padding: 0;
        font-size:14px;
       
    }
    
    .product_name{
      margin:0;
      text-align:left;
      Gill, Helvetica, sans-serif;
      width:auto;
      display:inline;
    }
    .product_date{
       margin:0;
     text-align:right;
      display:inline;
      font-family: monospace,courier;
    }
    
   .valuePadding{
        padding-left: 7px;
   }
   
.separator{
  margin:10px;
  margin-bottom:3px;
  border-bottom:1px solid #ccc;
}
</style>';
//error_reporting(E_ERROR);
//include("./includes/header.php");
session_start();
include("./includes/connection.php");
 $Employee = $_SESSION['userinfo']['Employee_Name'];
$fromDate = '';
$toDate = '';
$Registration_ID = 0;
$Payment_Cache_ID = 0;
//$filter = ' AND DATE(tr.TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';


if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
}
if (isset($_GET['toDate'])) {
    $toDate = $_GET['toDate'];
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
}

if (isset($fromDate) && !empty($fromDate)) {
    $filter = " AND tr.TimeSubmitted >='" . $fromDate . "'";
}
if (isset($toDate) && !empty($toDate)) {
    $filter = " AND tr.TimeSubmitted <='" . $toDate . "'";
}
if (isset($toDate) && !empty($toDate) && isset($fromDate) && !empty($fromDate)) {
    // echo strpos($fromDate, 'CURDATE'); exit;
    if (strpos($fromDate, 'INTERVAL 1 DAY')) {
        $filter = "  AND tr.TimeSubmitted BETWEEN " . $fromDate . " AND " . $toDate . "";
    } else {
        $filter = "  AND tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
    }
}
if (isset($toDate) && empty($toDate) && isset($fromDate) && empty($fromDate)) {
    $filter = "";
}


//echo $filter.'<br/>';exit;
//Select category names

$queryItemCateg = "SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM tbl_item_list_cache ilc INNER JOIN tbl_test_results tr ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID INNER JOIN tbl_tests_parameters_results tpr ON tpr.ref_test_result_ID=tr.test_result_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=ilc.Item_ID JOIN tbl_item_subcategory its ON i.Item_Subcategory_ID=its.Item_Subcategory_ID  WHERE tpr.Submitted='Yes' AND tpr.Validated='Yes' AND Registration_ID='" . $Registration_ID . "' $filter GROUP BY its.Item_Subcategory_Name";
//echo($queryItemCateg);exit;
$queryResultsCat = mysqli_query($conn,$queryItemCateg) or die(mysqli_error($conn));
//patient infos
//get insurance name
$select = mysqli_query($conn,"select * from tbl_patient_registration pr JOIN tbl_sponsor sp  ON
    						sp.Sponsor_ID = pr.Sponsor_ID  WHERE pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$rowPatientInfo = mysqli_fetch_assoc($select);

$queryMoreInfo = mysqli_query($conn,"SELECT Folio_Number,Claim_Form_Number,Billing_Type FROM tbl_patient_payments where Registration_ID = '$Registration_ID' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
$moreInfo = mysqli_fetch_assoc($queryMoreInfo);

$date1 = new DateTime(Date("Y-m-d"));
$date2 = new DateTime($rowPatientInfo['Date_Of_Birth']);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days";



$data .='<div class="wrapper">';
$data .='  <img src="branchBanner/branchBanner.png" width="100%" height="auto">';
$data .= "<div class='patientDetails'>"
        . "<center><table width='100%' >";
$data .="<tr>
              <th style='text-align:center;' colspan='3'>PATIENT INFORMATION<br/></th>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td style='width:50%'><b>Name:</b><span class='valuePadding'>" . $rowPatientInfo['Patient_Name'] . "</span></td><td style='width:30%'><b>Date of Birth:</b><span class='valuePadding'>" . $rowPatientInfo['Date_Of_Birth'] . "</span></td><td style='width:20%'><b>Gender:</b><span class='valuePadding'>" . $rowPatientInfo['Gender'] . "</span></td>
            </tr>
            <tr>
              <td style='width:50%'><b>Registration ID:</b><span class='valuePadding'>" . $rowPatientInfo['Registration_ID'] . "</span></td><td style='width:33%'><b>Sponsor:</b><span class='valuePadding'>" . $rowPatientInfo['Guarantor_Name'] . "</span></td><td style='width:33%'><b>Phone:</b><span class='valuePadding'>" . $rowPatientInfo['Phone_Number'] . "</span></td>
            </tr>
            <tr>
              <td style='width:50%'><b>Member Number:</b><span class='valuePadding'>" . $rowPatientInfo['Member_Number'] . "</span></td><td style='width:33%'><b>Billing Type:</b><span class='valuePadding'>" . $moreInfo['Billing_Type'] . "</span></td><td style='width:33%'><b>Folio #:</b><span class='valuePadding'>" . $moreInfo['Folio_Number'] . "</span></td>
            </tr>
            <tr>
              <td style='width:50%'><b>Age:</b><span class='valuePadding'>" . $age . "</span></td><td style='width:33%'><b>Registered Date:</b><span class='valuePadding'>" . $rowPatientInfo['Registration_Date'] . "</span></td><td style='width:33%'><b>Claim #:</b><span class='valuePadding'>" . $moreInfo['Claim_Form_Number'] . "</span></td>
            </tr>
             <tr>
              <td style='width:50%'><b>Country:</b><span class='valuePadding'>" . $rowPatientInfo['Country'] . "</span></td><td style='width:33%'><b>Region:</b><span class='valuePadding'>" . $rowPatientInfo['Region'] . "</span></td><td style='width:33%'><b>District:</b><span class='valuePadding'>" . $rowPatientInfo['District'] . "</span></td>
            </tr>
            ";

$data .= '</table></center>'
        . '</div>';

while ($rowCat = mysqli_fetch_array($queryResultsCat)) {
    $sub_category_ID = $rowCat['Item_Subcategory_ID'];

    $queryItem = "SELECT i.Item_ID,tr.test_result_ID,tsr.TimeCollected,te.Employee_Name,i.Product_Name,tr.TimeSubmitted,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc INNER JOIN tbl_test_results tr ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID INNER JOIN tbl_tests_parameters_results tpr ON tpr.ref_test_result_ID=tr.test_result_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=ilc.Item_ID JOIN tbl_item_subcategory its ON i.Item_Subcategory_ID=its.Item_Subcategory_ID JOIN tbl_item_category ic ON its.Item_Category_ID=ic.Item_Category_ID INNER JOIN tbl_specimen_results tsr ON tsr.payment_item_ID=ilc.Payment_Item_Cache_List_ID INNER JOIN tbl_employee te ON te.Employee_ID=tsr.specimen_results_Employee_ID WHERE tpr.Submitted='Yes' AND tpr.Validated='Yes' AND Registration_ID='" . $Registration_ID . "' $filter AND its.Item_Subcategory_ID='$sub_category_ID' GROUP BY tpr.ref_test_result_ID";
//die($queryItem);
    $QueryResults = mysqli_query($conn,$queryItem) or die(mysqli_error($conn));

    $i = 1;


    // $data .= $rowCat['Item_Category_Name'] . ' <br/>';
    $data .='<br/><div class="labpatientInfo">
                     ' . $rowCat['Item_Subcategory_Name'] . '
             </div><br/>';

    while ($row = mysqli_fetch_assoc($QueryResults)) {
        $testIDUnic = $row['test_result_ID'];
        //$itemTime = $row['Payment_Item_Cache_List_ID'];

        $image = '';
        //$allveralComment = '';
        $queryAttach = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'") or die(mysqli_error($conn));
        $attach = mysqli_fetch_assoc($queryAttach);
        $image = '';
        if ($attach['Attachment_Url'] != '') {
            $image = "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='100%' height='100%' alt='Not Image File' /></a>";
        }

        $allveralComment = $attach['Description'];

        $data .='<div class="labResultsInfo">';

        $data .='<table width="100%" border="0"><tr> <td><h1 class="product_name">'.strtoupper($row['Product_Name']) . '</h1></td> <td><h1>Collected By: '.$row['Employee_Name'].' </h1></td> <td><h1>Specimen Collected: '.$row['TimeCollected']. '</h1></td> <td style="text-align:right"><h1 class="product_date">Gathered: ' . $row['TimeSubmitted'] . '</h1></td></tr></table>
                    <div class="labResults">';

        if (!empty($allveralComment)) {

            $data.="<table style='width:100%;border-spacing:0;border-collapse:collapse;'> 
                <tr>
                    <td colspan='1'><hr style='width:100%'/></td>
                </tr>
                <tr>
                    <th style='width:80%;text-align:left'>Description</th>
                </tr>
                <tr>
                    <td colspan='1'><hr style='width:100%'/></td>
                </tr>
                <tr>
                    <td >" . $allveralComment . "</td>
                </tr> 
                <tr>
                    <td colspan='1'><hr style='width:100%'/></td>
                </tr>
            </table>
            
            ";
        }

        $selectParameter = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr  JOIN tbl_parameters p ON tpr.parameter = p.parameter_ID  JOIN tbl_tests_parameters tp ON p.parameter_ID = tp.ref_parameter_ID   WHERE tp.ref_item_ID='" . $row['Item_ID'] . "' AND tpr.ref_test_result_ID='" . $row['test_result_ID'] . "' AND tpr.result <> '' GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME")or die(mysqli_error($conn));
        if (mysqli_num_rows($selectParameter) > 0) {
            $data .= "<center><table class='' style='width:100%'>";
            $data .= "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%'>S/N</th>
                <th width='' style='text-align:left'>Parameters</th>
                <th width='' style='text-align:left'>Results</th>
                <th width='' style='text-align:left'>UoM</th>
                <th width='' style='text-align:left'>M</th>
                <th width='' style='text-align:left'>V</th>
                <th width='' style='text-align:left'>S</th>
	        <th width='' style='text-align:left'>Normal Value</th>
                <th width='' style='text-align:left'>Status</th>
            </tr>";

            $sn = 1;

            while ($rowParameter = mysqli_fetch_array($selectParameter)) {
                $data .= '<tr>';
                $data .= '<td>' . $sn++ . '</td>';
                $data .= '<td>' . ucfirst(strtolower($rowParameter['Parameter_Name'])) . '</td>';
                $data .= '<td>' . $rowParameter['result'] . '</td>';
                $data .= '<td>' . $rowParameter['unit_of_measure'] . '</td>';
                if ($rowParameter['modified'] == "Yes") {
                    $data .= '<td><p class="modificationStats" id="' . $rowParameter['parameter_ID'] . '" value="' . $rowParameter['test_result_ID'] . '">&#x2714;</p></td>';
                } else {
                    $data .= '<td></td>';
                }

                if ($rowParameter['Validated'] == "Yes") {
                    $data .= '<td>&#x2714;</td>';
                    $Validated = true;
                } else {
                    $data .= '<td></td>';
                }
                if ($rowParameter['Submitted'] == "Yes") {
                    $data .= '<td>&#x2714;</td>';
                } else {
                    $data .= '<td></td>';
                }

                $data .= '<td>' . $rowParameter['lower_value'] . ' ' . $rowParameter['operator'] . ' ' . $rowParameter['higher_value'] . '</td>';

                $lower = $rowParameter['lower_value'];
                $upper = $rowParameter['higher_value'];
                $rowResult = $rowParameter['result'];
                $Saved = $rowParameter['Saved'];
                $result_type = $rowParameter['result_type'];
                if ($result_type == "Quantitative") {
                    if ($rowResult > $upper) {
                        $data .= '<td><p style="color:rgb(255,0,0)">H</p></td>';
                    } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                        $data .= '<td><p style="color:rgb(255,0,0)">L</p></td>';
                    } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                        $data .= '<td><p style="color:rgb(0,128,0)">N</p></td>';
                    } else {
                        $data .= '<td><p style="color:rgb(0,128,0)"></p></td>';
                    }
                } else if ($result_type == "Qualitative") {
                    $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
                } else {
                    $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
                }
            }
            $data .= "</table><br/>";
        }//end if there is result
        $data .= '</div>';
        $data .= '</div>';
    }
    //$data.='<div class="separator"></div>';
}

$data .='</div>';

//echo $data;

include("MPDF/mpdf.php");
$mpdf = new mPDF();
$mpdf->WriteHTML($data);
$mpdf->SetFooter('{PAGENO}/{nb}|  Printed By  '.$Employee.'                   {DATE d-m-Y H:m:s}');
$mpdf->Output();

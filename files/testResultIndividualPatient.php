<?php

$data = '<style>
    #wrapper{
        width:100%;
        margin:10px auto; 
        padding:10px; 
        //border:1px solid blue;
        font-family:"Times New Roman",Times,serif; //Gill, Helvetica, sans-serif
    }
    #labpatientInfo,
    #labResultsInfo{
         clear: both;
         margin:10px; 
    }
    #labResultsInfo{
       
        //border-bottom:1px solid #ccc;
    }
    #labpatientInfo{
      background-color:#ccc;
      padding 10px;
      text-align:center;
      border-bottom:1px solid #ccc;
    }
    #labResults{
        //border:1px solid #ffd96e;
    }
    #labResultsInfo h1{
        margin: 0;
        padding: 0;
        font-size:14px;
       
    }
    
    .product_name{
      text-align:left;
      Gill, Helvetica, sans-serif
    }
    .product_date{
      float:right;
      right:0;
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
include("./includes/connection.php");

$fromDate = '';
$toDate = '';
$Registration_ID = 0;
$Payment_Cache_ID = 0;
$disp='';
$htm = '';
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
    if(strpos($fromDate, 'INTERVAL 1 DAY')){
       $filter = "  AND DATE(tr.TimeSubmitted) = " . $toDate . "";
    }else{
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

$data .='<div id="wrapper">';
while ($rowCat = mysqli_fetch_array($queryResultsCat)) {
    $sub_category_ID = $rowCat['Item_Subcategory_ID'];

    $queryItem = "SELECT i.Item_ID,tr.test_result_ID,i.Product_Name,tr.TimeSubmitted,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc INNER JOIN tbl_test_results tr ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID INNER JOIN tbl_tests_parameters_results tpr ON tpr.ref_test_result_ID=tr.test_result_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=ilc.Item_ID JOIN tbl_item_subcategory its ON i.Item_Subcategory_ID=its.Item_Subcategory_ID JOIN tbl_item_category ic ON its.Item_Category_ID=ic.Item_Category_ID WHERE tpr.Submitted='Yes' AND tpr.Validated='Yes' AND Registration_ID='" . $Registration_ID . "' $filter AND its.Item_Subcategory_ID='$sub_category_ID' GROUP BY tpr.ref_test_result_ID";
//die($queryItem);
    $QueryResults = mysqli_query($conn,$queryItem) or die(mysqli_error($conn));

    $i = 1;


    // $data .= $rowCat['Item_Category_Name'] . ' <br/>';
    $data .='<div id="labpatientInfo">
                     ' . $rowCat['Item_Subcategory_Name'] . '
                </div>';

    while ($row = mysqli_fetch_assoc($QueryResults)) {
        $testIDUnic = $row['test_result_ID'];
        $itemTime = $row['Payment_Item_Cache_List_ID'];

        $image = '';
        $allveralComment = '';
        $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
        $attach = mysqli_fetch_assoc($query);
        $image = '';
        if ($attach['Attachment_Url'] != '') {
            $image = "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='100' height='80' alt='Not Image File' /></a>";
        }

        $allveralComment = $attach['Description'];

        $data .='<div id="labResultsInfo">';
        //$data .= $row['Product_Name']; //.' tp.ref_item_ID='. $row['Item_ID'].' tpr.ref_test_result_ID='. $row['test_result_ID'] . '  <br/>';
        $data .='<table width="100%" border="1"><tr><td><h1 class="product_name">' . strtoupper($row['Product_Name']) . '</h1></td><td style="text-align:right"><h1 class="product_date">Gathered: ' . $row['TimeSubmitted'] . '</h1></td></tr></table>
                    <div class="labResults">';

        if (!empty($allveralComment)) {
           

            $data.="<table style='width:100%;border-spacing:0;border-collapse:collapse;'> 
                <tr>
                    <td colspan='2'><hr style='width:100%'/></td>
                </tr>
                <tr>
                    <th style='width:80%;text-align:left'>Description</th>
                    <th style='width:20%'>Attachment</th>
                </tr>
                <tr>
                    <td colspan='2'><hr style='width:100%'/></td>
                </tr>
                <tr>
                    <td >" . $allveralComment . "</td>
                    <td >" . $image . "</td>
                </tr> 
                <tr>
                    <td colspan='2'><hr style='width:100%'/></td>
                </tr>
            </table>
            ";
        }else if(!empty($image)){
             $data.="<table style='width:100%;border-spacing:0;border-collapse:collapse;'> 
                <tr>
                    <td colspan='2'><hr style='width:100%'/></td>
                </tr>
                <tr>
                    <th style='width:80%;text-align:left'>Description</th>
                    <th style='width:20%'>Attachment</th>
                </tr>
                <tr>
                    <td colspan='2'><hr style='width:100%'/></td>
                </tr>
                <tr>
                    <td >" . $allveralComment . "</td>
                    <td >" . $image . "</td>
                </tr> 
                <tr>
                    <td colspan='2'><hr style='width:100%'/></td>
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
                $data .= '<td>' . $rowParameter['Parameter_Name'] . '</td>';
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

echo $data;



$disp .='<div id="wrapper">';
$disp .='<img src="branchBanner/branchBanner.png" width="100%" height="auto">';
$disp .='<div id="labpatientInfo">
                     ' . $htm . '
                </div>';
$disp .='<div id="labResultsInfo">
                    <h1 align="center">Date and time gathered: ' . date('d/m/Y H:m:s') . '</h1>
                    <div id="labResults">
                       ' . $data . '
                    </div>
                </div>';
$disp .='</div>';

<?php

ob_start();
@session_start();
include("./includes/connection.php");
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');
require_once ('jpgraph/jpgraph_line.php');



$monthsArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December');

$preview = '';

$graph_type = filter_input(INPUT_GET, 'graph_type');
$graph_category = filter_input(INPUT_GET, 'graph_category');
$graph_month = filter_input(INPUT_GET, 'graph_month');
$graph_year = filter_input(INPUT_GET, 'graph_year');
$prev = filter_input(INPUT_GET, 'prev');

$filter = " pp.Billing_Type = 'Outpatient Cash'";
$filter_cat = "";
$getCategory_name = 'All';
if (!empty($graph_category) && $graph_category != 'all') {
    $filter_cat = " AND s.Item_category_ID = '$graph_category' ";
    $getCategory_name = mysqli_fetch_array(mysqli_query($conn,"SELECT Item_Category_Name FROM tbl_item_category WHERE Item_Category_ID='$graph_category'"))['Item_Category_Name'];
}

$dataYCash = array();
$dataYCredit = array();
$dataYMsamaha = array();
$dataX = array();

if ($graph_type == 'monthly') {
    $days = getDays($graph_month, $graph_year);
    $monthname = $monthsArray[--$graph_month];

    $title = "Revenue summary statistics \n Category $getCategory_name \n $monthname $graph_year ";

    foreach ($days as $day) {
        $filter = " AND YEAR(Payment_Date_And_Time)='$graph_year' AND DAY(Payment_Date_And_Time)='$day' $filter_cat";
        $rs = getCreditCashAmount($filter);

        $dataYCash[] = $rs['Cash'];
        $dataYCredit[] = $rs['Credit'];
        $dataYMsamaha[] = $rs['Msamaha'];
        $dataX[] = $day;
    }

    create_graph($dataYCash, $dataYCredit, $dataYMsamaha, $dataX, $title);
} else if ($graph_type == 'annually') {
    $months = getMonths();

    $title = "Revenue summary statistics \n Category " . ucfirst(strtolower($getCategory_name)) . " \n  $graph_year";


    foreach ($months as $key => $value) {
        $filter = " AND YEAR(Payment_Date_And_Time)='$graph_year' AND MONTH(Payment_Date_And_Time)='$key' $filter_cat";

        $rs = getCreditCashAmount($filter);

        $dataYCash[] = $rs['Cash'];
        $dataYCredit[] = $rs['Credit'];
        $dataYMsamaha[] = $rs['Msamaha'];
        $dataX[] = $value;
    }

    create_graph($dataYCash, $dataYCredit, $dataYMsamaha, $dataX, $title);
}

if ($prev == 'y') {

    //echo $htm;exit;
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4-L');
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
    $stylesheet = file_get_contents('patient_file.css');
    //$mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($preview, 2);
    $mpdf->Output();
} else {
    echo $preview;
}

function getCreditCashAmount($filter) {
    $query = mysqli_query($conn,"SELECT
              (SELECT SUM((Price-Discount)*Quantity) FROM tbl_patient_payment_item_list ppil 
                 JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID=ppil.Patient_Payment_ID
                 JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID
                 JOIN tbl_items i ON ppil.Item_ID=i.Item_ID
                 JOIN tbl_item_subcategory s ON s.Item_Subcategory_ID=i.Item_Subcategory_ID
                 
                 WHERE (Billing_Type='Outpatient Cash' OR (Billing_Type='Inpatient Cash' AND pp.payment_type='pre')) AND LOWER(sp.Guarantor_Name) != 'msamaha' AND pp.Transaction_status !='cancelled' $filter
              ) AS Cash,
              (SELECT SUM((Price-Discount)*Quantity) FROM tbl_patient_payment_item_list ppil
              JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID=ppil.Patient_Payment_ID
              JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID
              JOIN tbl_items i ON ppil.Item_ID=i.Item_ID
              JOIN tbl_item_subcategory s ON s.Item_Subcategory_ID=i.Item_Subcategory_ID
              
                 WHERE (Billing_Type='Inpatient Cash' OR (Billing_Type='Inpatient Cash' AND pp.payment_type='post'))  AND LOWER(sp.Guarantor_Name) != 'msamaha' AND pp.Transaction_status !='cancelled' $filter
              ) AS Credit,
              (
              SELECT SUM((Price-Discount)*Quantity) FROM tbl_patient_payment_item_list ppil
              JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID=ppil.Patient_Payment_ID
              JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID
              JOIN tbl_items i ON ppil.Item_ID=i.Item_ID
              JOIN tbl_item_subcategory s ON s.Item_Subcategory_ID=i.Item_Subcategory_ID
              
              WHERE LOWER(sp.Guarantor_Name) = 'msamaha' AND pp.Transaction_status !='cancelled' $filter
              ) AS Msamaha
            
            ") or die(mysqli_error($conn));

    $result = mysqli_fetch_assoc($query);

    return array("Cash" => $result['Cash'], "Credit" => $result['Credit'], "Msamaha" => $result['Msamaha']);
}

function getDays($month, $year) {
    $list = array();

    for ($d = 1; $d <= 31; $d++) {
        $time = mktime(12, 0, 0, $month, $d, $year);
        if (date('m', $time) == $month) {

            $list[] = (int) date('d', $time);
        }
    }

    return $list;
}

function getMonths() {
    $mth = $GLOBALS['monthsArray'];
    $list = array();

    for ($d = 0; $d < count($mth); $d++) {
        $list[$d + 1] = $mth[$d];
    }

    return $list;
}

function create_graph($dataYCash, $dataYCredit, $dataYMsamaha, $dataX, $title) {
    // Create the graph. These two calls are always required
    $graph = new Graph(1050, 320, 'auto');
    $graph->SetScale("textlin");
    $graph->SetY2Scale("lin", 0, 100);
    $graph->SetY2OrderBack(false);



    $sumY = array_sum($dataYCash) + array_sum($dataYCredit) + array_sum($dataYMsamaha);
   
    //echo (array_sum($dataYCash).' '.array_sum($dataYCredit).' '.array_sum($dataYMsamaha).' '.$sumY);exit;
    $dataYCashNew = array();
    $dataYCreditNew = array();
    $dataYMsamahaNew = array();

    $targ = array();
    $alts1 = array();
    $alts2 = array();
    $alts3 = array();

    $fmtStr = "javascript:window.open('barcsim_details.php?id=%d','_new','width=500,height=300');void(0)";
    foreach ($dataYCash as $key => $value) {
        $dataYCashNew[$key] = ($value == 0) ? 0 : ($value / $sumY);

        $alts1[$key] = number_format($value, 2);
    }
    foreach ($dataYCredit as $key => $value) {
        $dataYCreditNew[$key] = ($value == 0) ? 0 : ($value / $sumY);

        $alts2[$key] = number_format($value, 2);
    }
    foreach ($dataYMsamaha as $key => $value) {
        $dataYMsamahaNew[$key] = ($value == 0) ? 0 : ($value / $sumY);

        $alts3[$key] = number_format($value, 2);
    }

    foreach ($dataX as $key => $value) {
        $targ[$key] = sprintf($fmtStr, $value);
    }

//$graph->SetMargin(100,100,100,100);
    $graph->Set90AndMargin(100, 100, 100, 100);

    $theme_class = new UniversalTheme;
    $graph->SetTheme($theme_class);

    $graph->SetBox(false);
    $graph->SetFrame(false);

    $graph->ygrid->SetFill(false);
    $graph->xaxis->SetTickLabels(array('A', 'B', 'C', 'D'));
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false, false);
// Setup month as labels on the X-axis
    $graph->xaxis->SetTickLabels($dataX);

// Create the bar plots
    $b1plot = new BarPlot($dataYCashNew);
    $b2plot = new BarPlot($dataYCreditNew);
    $b3plot = new BarPlot($dataYMsamahaNew);

    $b1plot->SetCSIMTargets($targ, $alts1);
    $b2plot->SetCSIMTargets($targ, $alts2);
    $b3plot->SetCSIMTargets($targ, $alts3);

    $gbplot = new GroupBarPlot(array($b1plot, $b2plot, $b3plot));



// ...and add it to the graPH
    $graph->Add($gbplot);

    $b1plot->SetColor("#0000CD");
    $b1plot->SetFillColor("#0000CD");
    $b1plot->SetLegend("Cash");

    $b2plot->SetColor("#B0C4DE");
    $b2plot->SetFillColor("#B0C4DE");
    $b2plot->SetLegend("Credit");

    $b3plot->SetColor("#8B008B");
    $b3plot->SetFillColor("#8B008B");
    $b3plot->SetLegend("Msamaha");


    $graph->legend->SetFrameWeight(1);
    $graph->legend->SetColumns(31);
    $graph->legend->SetColor('#4E4E4E', '#00A78A');

    $band = new PlotBand(VERTICAL, BAND_RDIAG, 31, "max", 'khaki4');
    $band->ShowFrame(true);
    $band->SetOrder(DEPTH_BACK);
    $graph->Add($band);
    $graph->title->setFont(FF_FONT2, FS_BOLD);
    $graph->title->Set($title);
// Display the graph
// Get the handler to prevent the library from sending the
// image to the browser
    $img = $graph->StrokeCSIM();
    //echo $img;exit;
//    ob_start();
//    imagepng($img);
//    $imageData = ob_get_contents();
//    ob_end_clean();

    $GLOBALS['preview'] = $img; //'<img src="data:image/png;base64,' . base64_encode($imageData) . '" width="1050" />';
//    ob_end_clean();
//    ob_end_flush();
}

<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	$start_date=$_GET['start_date'];
        $end_date=$_GET['end_date'];
        $search_item=$_GET['search_item'];

	$htm = '<table align="center" width="100%">
                <tr><td style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr><td style="text-align:center"><b>HAEMATOLOGY</b></td></tr>
                <tr><td style="text-align:center"><b>LABORATORY MACHINE INTEGRATION REPORT</b></td></tr>
                <tr><td style="text-align:center">START DATE : '.$start_date.' END DATE : '.$end_date.'</td></tr>
            </table>';

	$htm .= "<table width='100%' style='font-size:10px'>
                      <tr><td colspan='10'><hr style='width:100%;height:0.1px;margin-top:-0.1%;margin-bottom:-0.1%'/></td></tr>

            <tr>
                <th>S/No.</th>
                <th>Sample ID</th>
                <th>Lab Tech</th>
                <th>No Of Rows</th>
                <th>Observation Date</th>
                <th>Result Date</th>
                <th>Validated</th>
                <th>Sent To Doctor</th>
                <th>Sample Id Source</th>
                <th>Machine Type</th>
            </tr>
            <tbody id='lab_machine_intergration_body'>
                
            ";
        $sql_select_result=mysqli_query($conn,"SELECT *,COUNT(intergrated_lab_results_id) as received_result FROM tbl_intergrated_lab_results WHERE result_date BETWEEN '$start_date' AND '$end_date'  AND (sample_test_id LIKE '%$search_item%' OR operator LIKE '%$search_item%') GROUP BY result_date") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_result)>0){
            $count_sn=1;
            while($result_rows=mysqli_fetch_assoc($sql_select_result)){
                $sample_test_id=$result_rows['sample_test_id'];
                $operator=$result_rows['operator'];
                $result_date=$result_rows['result_date'];
                $obser_date=$result_rows['obser_date'];
                $validated=$result_rows['validated'];
                $sent_to_doctor=$result_rows['sent_to_doctor'];
                $received_result=$result_rows['received_result'];
                $machine_type=$result_rows['machine_type'];
                //verify the source of sample id
                $sql_check_if_valid_ehms_sample_code_resulr=mysqli_query($conn,"SELECT payment_item_ID FROM tbl_specimen_results WHERE TimeCollected BETWEEN '$start_date' AND '$end_date' AND payment_item_ID='$sample_test_id'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_check_if_valid_ehms_sample_code_resulr)>0){
                   $sample_id_value_source="from ehms"; 
                }else{
                   $sample_id_value_source="unknown";  
                }
                $htm.= "<tr><td colspan='10'><hr style='width:100%;height:0.1px;margin-top:-0.1%;margin-bottom:-0.1%'/></td></tr>";
                $htm .= "<tr>
                            <td>$count_sn</td>
                            <td>$sample_test_id</td>
                            <td>$operator</td>
                            <td>$received_result</td>
                            <td>$obser_date</td>
                            <td>$result_date</td>
                            <td>$validated</td>
                            <td>$sent_to_doctor</td>
                            <td>$sample_id_value_source</td>
                            <td>$machine_type</td>
                      </tr>";
                $count_sn++;
            }
        }
        $htm.= "<tr><td colspan='10'><hr style='width:100%;height:0.1px;margin-top:-0.1%;margin-bottom:-0.1%'/></td></tr>";
        $htm .="</tbody>
        </table>";
	

	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>
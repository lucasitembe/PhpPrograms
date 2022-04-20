<style>
.prevHistory:hover {
    cursor: pointer;
}
</style>
<div id="showdata" style="width:100%;  overflow:hidden;display:none;">
    <div id="parameters">
        <table width=100%>
            <tr>
                <td width="40%">
                    <strong>Enter Organism:</strong>
                </td>
                <td>
                    <input type='text' name='Organism' style='padding-left:12px; height:28px;' id='Organism'
                        required='required' placeholder='Enter Organism'>
                </td>
                <td>
                    <button class='art-button-green' id="itemIDAdd" onclick="addorganism1()"
                        style="margin-left:13px !important; background-color:white !important;">ADD</button>
                </td>
            </tr>
        </table>
        <div id="DelResults"></div>
        <div id="ItemParameters"></div>
    </div>

</div>
<?php
session_start();
include("../includes/connection.php");
$data = '';
$option = '';

//echo 'action Query';exit;

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'getResult') {
        $id = $_GET['id'];
        $barcode = $_GET['barcode'];
        $filter = $_GET['filter'];

        $filter = str_replace("na", "'", $filter);


        $payment_id = $_GET['payment_id'];
        if ($barcode == '') {
            $Query = "SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID WHERE Registration_ID='" . $id . "' $filter  GROUP BY Payment_Item_Cache_List_ID";
        } else {
            $Query = "SELECT * FROM tbl_item_list_cache INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID WHERE Registration_ID='" . $id . "'  AND payment_item_ID='" . $barcode . "' $filter  GROUP BY Payment_Item_Cache_List_ID";
        }

        $QueryResults = mysqli_query($conn,$Query) or die(mysqli_error($conn));
        $data .= "<center><table class='' style='width:100%'>";
        $data .= "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%' style='text-align:left;'>S/N</th>
                <th width='30%' style='text-align:left'>Test Name</th>
                <th width='15%' style='text-align:left'>Doctor's Notes</th>
                <th width='6%' style='text-align:left'>Time Spec Taken</th>
                <th width='6%' style='text-align:left'>Time Spec Submt</th>
                <th width='6%' style='text-align:left'>Time Spec Received</th>
                <th width='6%' style='text-align:left'>Time Result Sent</th>
                <th width='5%' style='text-align:left'>Test Status</th>
                <th width='32%' style='text-align:left'>Overall Remarks && Result</th>
                <th width='10%' style='text-align:left'>Attach</th>
                <th width='1%' style='text-align:left'>Attachments</th>
                <th width='1%' style='text-align:left'>Results</th>
      </tr>";
        $i = 1;

//echo mysqli_num_rows($QueryResults);
        $datamsg = mysqli_num_rows($QueryResults);
        $couterSubVal = 0;

        $checkIfAllowedValidate = false;
        if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
            $checkIfAllowedValidate = true;
        }

        $filter = str_replace("'", "na", $filter);

        while ($row = mysqli_fetch_assoc($QueryResults)) {
            $testIDUnic = $row['test_result_ID'];
            $itemTime = $row['Payment_Item_Cache_List_ID'];
            // echo "SELECT * from tbl_specimen_results WHERE payment_item_ID='$itemTime' GROUP BY payment_item_ID ORDER BY `payment_item_ID` ASC";
            $queryspecimen = mysqli_query($conn,"SELECT * from tbl_specimen_results WHERE payment_item_ID='$itemTime' GROUP BY payment_item_ID ORDER BY `payment_item_ID` ASC");
            $time = mysqli_fetch_row($queryspecimen);

            $image = '';
            $allveralComment = '';
            $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $id . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");

            $image = '';
            $allveralComment = '';

            while ($attach = mysqli_fetch_array($query)) {
                if ($attach['Attachment_Url'] != '') {
                    $image .= "<table><tr><td><input type='checkbox' id='close_id' onclick='remove_img(" . $attach['Attachment_ID'] . ",this,\"" . $row['Product_Name'] . "\"," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")'></td><td><a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='100' height='30' alt='Not Image File' /></a></td></tr></table>";
                }

                $allveralComment = $attach['Description'];
            }



            $RS = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $rowSt = mysqli_fetch_assoc($RS);
            $Submitted = $rowSt['Submitted'];
            $Validated = $rowSt['Validated'];
            $TimeSubmitted = $rowSt['TimeSubmitted'];

            //echo 'submitted and validated';

            if ($Submitted == 'Yes' && $Validated == 'Yes') {
                $resultSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='$id'");
                $numrows = mysqli_num_rows($resultSaved);


                //Count parameters Saved
                $numparameterSaved = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$itemTime'");
                $numSavedParameters = mysqli_num_rows($numparameterSaved);

                $result = mysqli_fetch_assoc($numparameterSaved)['result'];

                //die($numSavedParameters.' '."SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$itemTime'");
                //Count parameters Submitted
                $numparameterSubmitted = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Submitted='Yes' AND payment_item_ID='$itemTime'");
                $numSubmittedParameters = mysqli_num_rows($numparameterSubmitted);


//Count parameters Validated
                $numparameterValidated = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Validated='Yes' AND payment_item_ID='$itemTime'");
                $numValidatedParameters = mysqli_num_rows($numparameterValidated);

                //Count parameters None empty results
                $numparameterNoneempty = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$itemTime' AND result <>''")or die(mysqli_error($conn));
                $numNoneemptyParameters = mysqli_num_rows($numparameterNoneempty);

                $selected = "";
                if ($result == 'positive') {
                    $selected = "<option value='' selected='selected'>Select result</option>"
                            . "<option value='positive' selected='selected' >Positive</option>"
                            . "<option value='negative' >Negative</option>"
                            . "<option value='normal'>Normal</option>
                               <option value='abnormal'>Abnormal</option>
                               <option value='high'>High</option>
                               <option value='low'>Low</option>";
                } elseif ($result == 'negative') {
                    $selected = "<option value=''>Select result</option>"
                            . "<option value='positive' >Positive</option>"
                            . "<option value='negative' selected='selected'>Negative</option>"
                            . "<option value='normal'>Normal</option>
                               <option value='abnormal'>Abnormal</option>
                               <option value='high'>High</option>
                               <option value='low'>Low</option>";
                } elseif ($result == 'normal') {
                    $selected = "<option value=''>Select result</option>"
                            . "<option value='positive' >Positive</option>"
                            . "<option value='negative' >Negative</option>"
                            . "<option value='normal' selected='selected'>Normal</option>
                               <option value='abnormal'>Abnormal</option>
                               <option value='high'>High</option>
                               <option value='low'>Low</option>";
                } elseif ($result == 'abnormal') {
                    $selected = "<option value=''>Select result</option>"
                            . "<option value='positive' >Positive</option>"
                            . "<option value='negative' >Negative</option>"
                            . "<option value='normal' >Normal</option>
                               <option value='abnormal' selected='selected'>Abnormal</option>
                               <option value='high'>High</option>
                               <option value='low'>Low</option>";
                } elseif ($result == 'high') {
                    $selected = "<option value=''>Select result</option>"
                            . "<option value='positive' >Positive</option>"
                            . "<option value='negative' >Negative</option>"
                            . "<option value='normal'>Normal</option>
                               <option value='abnormal'>Abnormal</option>
                               <option value='high' selected='selected'>High</option>
                               <option value='low'>Low</option>";
                } elseif ($result == 'low') {
                    $selected = "<option value=''>Select result</option>"
                            . "<option value='positive' >Positive</option>"
                            . "<option value='negative'>Negative</option>"
                            . "<option value='normal'>Normal</option>
                               <option value='abnormal'>Abnormal</option>
                               <option value='high'>High</option>
                               <option value='low'  selected='selected'>Low</option>";
                } else {
                    $selected = "<option value=''>Select result</option>"
                            . "<option value='positive' >Positive</option>"
                            . "<option value='negative'>Negative</option>"
                            . "<option value='normal'>Normal</option>
                               <option value='abnormal'>Abnormal</option>
                               <option value='high'>High</option>
                               <option value='low'  >Low</option>";
                }

                $data .= "<tr>";
                $data .= "<td >" . $i++ . "</td>";
                $data .= "<td><input type='hidden' name='Payment_Item_Cache_List_ID[]' value='" . $row['Payment_Item_Cache_List_ID'] . "'><input class='printselected' id='" . $row['Payment_Item_Cache_List_ID'] . "' type='checkbox'>" . $row['Product_Name'] . "</td>";
                $data .= "<td><textarea class='doctorNotes' readonly='true' id='" . $row['Payment_Item_Cache_List_ID'] . "'>" . $row['Doctor_Comment'] . "</textarea></td>";
                $data .= "<td>" . $time[5] . "</td>";
                $data .= "<td>" . $row['TimeSubmitted'] . "</td>";
                $data .= "<td>" . $time[12]. "</td>";
                $data .= "<td>" . $TimeSubmitted. "</td>";
                $data .= "<td>" . $row['Status'] . "</td>";
                $data .= "<td  colspan='2'>"
                        . "<form id='uploadAttachmentLab_" . $row['Payment_Item_Cache_List_ID'] . "' action='attachmentLabResult.php?item_id=" . $row['Payment_Item_Cache_List_ID'] . "' method='post' enctype='multipart/form-data'>"
                        . "<input type='hidden' name='Registration_id' value='" . $id . "'>
                           <input type='hidden' name='ppilc' class='art-button-green' value='" . $row['Payment_Item_Cache_List_ID'] . "'> 
                           <input type='hidden' name='attachFIlesLabvaldate'   value='' id='attachFIlesLabvaldate_" . $row['Payment_Item_Cache_List_ID'] . "' />
                           <input type='hidden' name='attachFIlesLabsubmit'  value='' id='attachFIlesLabsubmit_" . $row['Payment_Item_Cache_List_ID'] . "' />
                           <input type='hidden' name='attachFIlesLabsave'  value='' id='attachFIlesLabsave_" . $row['Payment_Item_Cache_List_ID'] . "' />
                                            
                          "
                        . "<textarea style='width:100%;display:inline;margin:0px' class='overallRemarks' name='overallRemarks_" . $row['Payment_Item_Cache_List_ID'] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "'>" . $allveralComment . "</textarea>";
                $data .= "<select style='width:40%;display:inline;margin:0px;padding:4px' 
                          name='otherResult_" . $row['Payment_Item_Cache_List_ID'] . "'>
                        <option>$selected</option>
                        </select>" .
                        "<input style='width:60%;display:inline;margin:0px' class='labfileCh_" . $row['Payment_Item_Cache_List_ID'] . "' type='file' name='labfile_" . $row['Payment_Item_Cache_List_ID'] . "[]'  multiple='true'  class='imageUpload' id='" . $row['Payment_Item_Cache_List_ID'] . "' onchange='readFileName(this.value," . $row['Payment_Item_Cache_List_ID'] . ")'>"
                        . "</form>"
                        . "</td>";

                $data .= "<td style='text-align:center'>" . $image . "</td>";

                $valSub = '';

                $valSub = '<td style="width:80%">
                              <input type="button"  class="saveresult" id="fileuploadsave" style="width:auto" value="Save/update" onclick=\'saveInformation("' . $row['Product_Name'] . '","' . $row['Payment_Item_Cache_List_ID'] . '","' . $id . '")\' />
                             ';

                if ($checkIfAllowedValidate) {
                    $valSub .='
                                <input type="button"  class="validatesendresult" id="fileuploadsave" style="width:100%;padding:0 0px" value="Validate" onclick=\'validateInformation("' . $row['Product_Name'] . '","' . $row['Payment_Item_Cache_List_ID'] . '","' . $id . '")\' />
                           ';
                }
                $valSub .=" </td>";

                //  die($valSub);

                $Item_ID=$row['Item_ID'];
                $Product_Name=$row['Product_Name'];
 $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
                $data .= "<td>
	       <table border='0'>
                  <tr>
                   <td>
                     <input type='button' class='generalreslts' name='" . $row['Product_Name'] . "' ppil='" . $row['Payment_Item_Cache_List_ID'] . "' id='" . $row['Item_ID'] . "' patientID='$id' value='Results'>
	           </td>
                   <td>
                  	<input type='button' data-id='".$Item_ID."'  class='culture' name='" . $row['Product_Name'] . "' id='" . $row['payment_item_ID'] . "' value='Micro-biology' />
	           </td>
                   
                     " . $valSub . "
                   
                  </tr>
                  <tr><td colspan='3'><input type='button' onclick='get_result_from_integrated_machine(\"$Product_Name\",\"$Payment_Item_Cache_List_ID\")' value='Result From Intergration'></td></tr>
                </table>    	
	    </td>
            </tr>
	";
            } else {
                $couterSubVal +=1;
            }
        }

        if ($datamsg > 0) {
            $data.= "<tr>
               <td colspan='10' style='text-align:center'>
                 <input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File(" . $id . ")' class='art-button-green'/>
                  ";

            $data.= "&nbsp;&nbsp;&nbsp;<select id='resultLocation' style='padding:4px;font-size:18px'><option>Local</option><option>Outside</option></select>&nbsp;&nbsp;<a  href='printPatientsLabResults.php?registration_ID=" . $id . "&payment_ID=" . $payment_id . "' target='_blank' id='" . $id . "' payID='" . $payment_id . "'  class='art-button printngbtn'>Print Priview</a></td>
                </tr>";
        } else {
            $data.= "<tr>
             <td colspan='9' style='text-align:center;color:red;font-size:20px;'>
                  <input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File(" . $id . ")' class='art-button-green'/>
                </td>
             </tr>
			";
        }

        $data .= "</table></form>";

        echo $data;
    }
} elseif (isset($_GET['generalResult'])) {
    $id = $_GET['id'];
    $ppil = $_GET['ppil'];
    $patientID = $_GET['patientID'];
    $filter = $_GET['filter'];
    $sn = 1;
    $data = '';

    $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $id . "'  AND Registration_ID='" . $patientID . "' AND Validated = 'Yes' AND tr.payment_item_ID='" . $ppil . "'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
    $data = '';
    //die($selectQuery);
    $GetResults = mysqli_query($conn,$selectQuery);
    $data .= "<center><table class='' border=1 style='width:100%'>";
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
                <th width='' style='text-align:left'>Previous results</th>
            </tr>";
    $testID = '';
    $paymentID = '';
    $Validated = false;

    $operators = array('=', '>', '<', '>=', '<=', '-');

    while ($row2 = mysqli_fetch_assoc($GetResults)) {
        $testID = $row2['test_result_ID'];
        $paymentID = $row2['payment_item_ID'];
        $input = '';

        if ($row2['result_type'] == 'Quantitative') {
            $input = '<input type="text" class="Resultvalue Quantative" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '" placeholder="Numeric values only">';
        } else if ($row2['result_type'] == 'Qualitative') {
            $input = '<input type="text" class="Resultvalue Qualitative' . $row2['parameter_ID'] . '" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '">';
        }


        $data .= '<tr>';
        $data .= '<td>' . $sn++ . '</td>';
        $data .= '<td>' . $row2['Parameter_Name'] . '</td>';
        $data .= '<td>' . $input . '</td>';

        $data .= '<input type="hidden" class="parameterName" value="' . $row2['Parameter_Name'] . '">';
        $data .= '<input type="hidden" class="paymentID" value="' . $row2['test_result_ID'] . '">';
        $data .= '<input type="hidden" class="productID" value="' . $id . '">';
        $data .= '<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';
        $data .= '<td>' . $row2['unit_of_measure'] . '</td>';
        if ($row2['modified'] == "Yes") {
            $data .= '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
        } else {
            $data .= '<td></td>';
        }

        if ($row2['Validated'] == "Yes") {
            $data .= '<td>&#x2714;</td>';
            $Validated = true;
        } else {
            $data .= '<td></td>';
        }
        if ($row2['Submitted'] == "Yes") {
            $data .= '<td>&#x2714;</td>';
        } else {
            $data .= '<td></td>';
        }

        $data .= '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

        $lower = $row2['lower_value'];
        $upper = $row2['higher_value'];
        $operator = $row2['operator'];

        $rowResult = $row2['result'];
        $Saved = $row2['Saved'];
        $result_type = $row2['result_type'];
        if ($result_type == "Quantitative") {

            if (in_array($operator, $operators) && is_numeric($rowResult)) {
                if ($rowResult > $upper) {
                    $data .= '<td><p style="color:rgb(255,0,0)">H</p></td>';
                } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                    $data .= '<td><p style="color:rgb(255,0,0)">L</p></td>';
                } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                    $data .= '<td><p style="color:rgb(0,128,0)">N</p></td>';
                } else {
                    $data .= '<td><p style="color:rgb(0,128,0)">' . $rowResult . ' ' . $upper . '</p></td>';
                }
            }
        } else if ($result_type == "Qualitative") {
            $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
        } else {
            $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
        }


        //Get previous test results
        $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $id . "' AND tr.payment_item_ID<>'" . $ppil . "'";
        $Queryhistory = mysqli_query($conn,$historyQ);
        $myrows = mysqli_num_rows($Queryhistory);
        if ($myrows > 0) {

            $data .= '<td>
                        <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                    $myrows . ' Previous result(s)
                        </p>
                     </td>';
        } else {

            //echo $historyQ;
            $data .= '<td>No previous results</td>';
        }


        // if($row2['Validated']=='Yes'){
        $data .= '<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" readonly="true" style="display:none">';
        /*  } else {
          $data .= '<input type="checkbox" class="validated" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'" style="display:none">';
          } */

        //place submit here
        // if($row2['Submitted']=='Yes'){
        $data .= '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" disabled="true" style="display:none">';
        /* } else {
          $data .= '<input type="checkbox" class="Submitted" id="'.$row2['parameter_ID'].'" value="'.$row2['test_result_ID'].'" style="display:none">';
          } */


        $data .= '</tr>';
    }
    $testIDUnic = $testID;
    $data .= '<div id="testName">' . $row2['Product_Name'] . '</div>';
    $data .= '</table>';
    $data .= '<div></div>';

    //Count total number of parameters
    $resultSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='$id'");
    $numrows = mysqli_num_rows($resultSaved);


    //Count parameters Saved
    $numparameterSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$paymentID'");
    $numSavedParameters = mysqli_num_rows($numparameterSaved);

    //Count parameters Submitted
    $numparameterSubmitted = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Submitted='Yes' AND payment_item_ID='$paymentID'");
    $numSubmittedParameters = mysqli_num_rows($numparameterSubmitted);


//Count parameters Validated
    $numparameterValidated = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Validated='Yes' AND payment_item_ID='$paymentID'");
    $numValidatedParameters = mysqli_num_rows($numparameterValidated);

    //Count parameters None empty results
    $numparameterNoneempty = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$paymentID' AND result <>''")or die(mysqli_error($conn));
    $numNoneemptyParameters = mysqli_num_rows($numparameterNoneempty);

    //echo $numNoneemptyParameters.' '.$numrows;exit;
    if ($numSavedParameters == 0) {
        $data .= '<div> <input type="button" class="validateResulttr" name="' . $patientID . '" ppil="' . $ppil . '" id="' . $testIDUnic . '" value="Save Results"></div>';
    } elseif ($numNoneemptyParameters == $numrows) {
        $data .= '<div> 
		
		
		<input type="button" ppil="' . $ppil . '"  class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> ';

        //die($_SESSION['userinfo']['Laboratory_Result_Validation']);

        if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
            $data .= '<input type="button" ppil="' . $ppil . '" class="validateSubmittedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Validate"> ';
        }

        
        ?>
<?php
        $select_Patient_Phone = "
			SELECT Phone_Number FROM tbl_patient_registration WHERE Registration_ID = '$patientID'
		";
        $select_Patient_Phone_Qry = mysqli_query($conn,$select_Patient_Phone) or die(mysqli_error($conn));

        if (mysqli_num_rows($select_Patient_Phone_Qry) > 0) {
            while ($PPhone = mysqli_fetch_assoc($select_Patient_Phone_Qry)) {
                $Receiver = $PPhone['Phone_Number'];
            }
        } else {
            $Receiver = 'NoNumber';
        }
        ?>
<button id="SMSButton" class="art-button-green" onClick="SendSMS('Lab', '<?php echo $Receiver; ?>')">Send SMS
    Alert</button>
<span id="SMSRespond"></span>
<?php
        echo '</div>';
    } elseif ($numNoneemptyParameters < $numrows) {
        $data .= '<div> 
		
		<input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> 
		';
    }

    echo $data;
} elseif (isset($_POST['getCultureResults'])) {
    if ($_POST['getCultureResults'] == 'cultureview') {
        $paymentID = $_POST['paymentID'];
        $query = "SELECT * FROM tbl_item_list_cache JOIN tbl_culture_results ON Payment_Item_Cache_List_ID=payment_item_ID WHERE payment_item_ID='$paymentID' GROUP BY payment_item_ID";
        $myQuery = mysqli_query($conn,$query);
        $row2 = mysqli_fetch_assoc($myQuery);
       echo "<center><table cellspacing='0' cellpadding='0'  width=1000px;>";
        echo " <tr>
                   
                  </tr>";
              echo "<tr><td><center><b>Specimen Type</b></center></td>"
        . "<td>"
                . "<select class='seleboxorg3'  name='specimen' id='specimen' style='width:600px; padding-top:4px; padding-bottom:4px;'>"
                ?><?php 
                         $query_sub_specimen = mysqli_query($conn,"SELECT Specimen_Name,Specimen_ID FROM tbl_laboratory_specimen") or die(mysqli_error($conn));
                       $Specimen_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sn.Specimen_Name,sn.Specimen_ID,cul.Specimen,cul.payment_item_ID FROM tbl_laboratory_specimen sn,tbl_culture_results cul WHERE sn.Specimen_ID=cul.Specimen AND cul.payment_item_ID='$paymentID'"))['Specimen_Name'];
                       $Specimen_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sn.Specimen_Name,sn.Specimen_ID,cul.Specimen,cul.payment_item_ID FROM tbl_laboratory_specimen sn,tbl_culture_results cul WHERE sn.Specimen_ID=cul.Specimen AND cul.payment_item_ID='$paymentID'"))['Specimen_ID'];
                          echo '<option value="'.$Specimen_ID.'"> '.$Specimen_Name.'</option>';
                         while ($row = mysqli_fetch_array($query_sub_specimen)) {
                          echo '<option value="' . $row['Specimen_ID'] . '">' . $row['Specimen_Name'] . '</option>';
                         }
                        ?><?php
                "</select>"
                      
                . "</td></tr>";
                
                 echo "<tr>
                <td><center><b>Organism</b></center></td>";
                 "<div id='Cached'>";
              echo  "<td>
           
      <select class='seleboxorg3' name='new_organism_1' id='new_organism_1' style='width:600px; padding-top:4px; padding-bottom:4px;'>";
                 
                       ?><?php $query_sub_specimen1 = mysqli_query($conn,"SELECT organism_name,organism_id FROM tbl_organism") or die(mysqli_error($conn));
                        $organism_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT or1.organism_name,or1.organism_id,cul.Organism1,cul.payment_item_ID FROM tbl_organism or1,tbl_culture_results cul WHERE or1.organism_id=cul.Organism1 AND cul.payment_item_ID='$paymentID'"))['organism_name'];
                       $organism_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT or1.organism_name,or1.organism_id,cul.Organism1,cul.payment_item_ID FROM tbl_organism or1,tbl_culture_results cul WHERE or1.organism_id=cul.Organism1 AND cul.payment_item_ID='$paymentID'"))['organism_id'];
                          echo '<option value="'.$organism_id.'">'.$organism_name.'</option>';
                         while ($row1 = mysqli_fetch_assoc($query_sub_specimen1)) {
                           echo '<option value="' . $row1['organism_id'] . '">' . $row1['organism_name'] . '</option>';
                         }?><?php
                       
                echo "</select></div><button type='button' style='color:white !important; height:27px !important; margin-left:168px !important;' class='art-button-green' onclick='addorganism();'>Add Organism</button>     
             </td>
            </tr>";
        $wet_prepation1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wet_prepation FROM tbl_culture_results WHERE payment_item_ID='$paymentID'"))['wet_prepation'];
          echo "<tr>
                  <td>
                  <center> <b>Wet Prepation</b></center>
                  </td>
                  <td>
                  <textarea rows='2' cols='40' placeholder='Remarks' name='wet' id='wet'>$wet_prepation1</textarea> 
                  </td>
            
                </tr>";
        $Stein1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT gram_stein FROM tbl_culture_results WHERE payment_item_ID='$paymentID'"))['gram_stein'];
        $sign1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT sign FROM tbl_culture_results WHERE payment_item_ID='$paymentID'"))['sign'];
        $deseases1 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT deseases FROM tbl_culture_results WHERE payment_item_ID='$paymentID'"))['deseases'];
               
        echo "<tr>
                   <td>
                   
                  <center> <b> Gram Stein</b></center>
                  </td>    
                   <td colspan='3'>
                        <select style='width:300px; padding-top:4px; padding-bottom:4px;' name='sign' id='sign'>
                        <option value=$sign1>$sign1</option>
                         <option value='+Positive'>+Positive</option>
                          <option value='-Negative'>-Negative</option>
                        </select>";
                  $getone = mysqli_query($conn,"SELECT es.deseases_id,es.deseases_name,cu.Culture_ID,es.culture_id, cu.payment_item_ID FROM tbl_deseases es,tbl_culture_results cu WHERE es.culture_id=cu.Culture_ID AND cu.payment_item_ID='$paymentID'") or die (mysqli_error($conn));
                  $one= mysqli_num_rows($getone);
                         echo "<select style='width:300px; padding-top:4px; padding-bottom:4px;' name='desease' id='desease'>";
                           $option = '';
                           $desease_id=0;
                           $deseases_name="";
                          $one= mysqli_num_rows($getone);
                         
                           while($rowese = mysqli_fetch_assoc($getone))
                              {
                               $desease_id = $rowese['deseases_id'];
                               $deseases_name = $rowese['deseases_name'];
                              echo "<option value = '$desease_id'>$deseases_name</option>";
                               
                              }
                            
                        echo "</select><br>
                        <textarea rows='2' cols='40' placeholder='Remarks' name='stein' id='stein' value=''>$Stein1</textarea> 
                  </td>    
            </tr>";
        echo "<tr>
                <td>
               <center> <b>Biochemical Tests</b></center>
                </td>
                
                <td  id='CultureTbl1'>";  
            
              $result = mysqli_query($conn,"SELECT bl.biotest,bl.culture_id, cu.Culture_ID FROM tbl_biotest bl,tbl_culture_results cu WHERE bl.culture_id=cu.Culture_ID AND payment_item_ID='$paymentID'");
                  
                while($row3 = mysqli_fetch_assoc($result)){
                      $biotest1 = $row3['biotest'];
                               
            echo "<input placeholder='biogest' style='width:600px; padding-top:10px; padding-bottom:10px;' class='txtbox biometrict_test' type='text' name='biometrict_test[]' class='antibiotc'  value='$biotest1''>";     
                }
               echo "<input type='button'class='art-button-green' id='addrow1' style='margin-left:70px !important;' value='Add Biochemical Tests'>
                  </td>

             </tr>";
             $Item_ID = 0;
             $Product_Name=0;
        echo "<tr id='addhere' class='addnewrow'><td><center><b>Antibiotic</b></center></td>";
          $result_antibiotic=mysqli_query($conn,"SELECT an.antibiotic,an.culture_id,an.antibiotic_result,an.antibiotic_result,it.Item_ID,cu.payment_item_ID,cu.Culture_ID,it.Product_Name FROM tbl_antibiotic an,tbl_items it,tbl_culture_results cu WHERE an.culture_id=cu.Culture_ID AND an.antibiotic=it.Item_ID AND cu.payment_item_ID='$paymentID'");
              
               echo "<td id='CultureTbl'>";
                while($antibiotic = mysqli_fetch_assoc($result_antibiotic)){
                        $Item_ID = $antibiotic['antibiotic'];
                        $Product_Name = $antibiotic['Product_Name'];
                        $antibiotic_result = $antibiotic['antibiotic_result'];
//         $Item_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT an.antibiotic,an.culture_id,an.antibiotic_result,it.Item_ID,cu.payment_item_ID,cu.Culture_ID,it.Product_Name FROM tbl_antibiotic an,tbl_items it,tbl_culture_results cu WHERE an.culture_id=cu.Culture_ID an.antibiotic=it.Item_ID AND cu.payment_item_ID='$paymentID'"))['Item_ID'];
              echo "<select class='antibiotc' name='antibiotic[]' id='1' style='width:300px; padding-top:4px; padding-bottom:4px;'>";
                        echo "<option value='$Item_ID'>$Product_Name</option>'"; 
                          $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy'") or die(mysqli_error($conn));
                         while ($row = mysqli_fetch_array($query_sub_specimen)) {
                          echo '<option value="' . $row['Item_ID'] . '">' . $row['Product_Name'] . '</option>';
                         }
                       echo "</select>
                       <select class='seleboxorg1' name='sensitive[]' id='orgone_1' style='width:300px; padding-top:4px; padding-bottom:4px;'><option>$antibiotic_result</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select><br>";
                            }
//                            echo '<option value="'. $Item_ID.'">'.$Product_Name.'</option>';
                           echo "<input type='button' class='art-button-green' style='margin-left:750px !important;' id='addrow' name='' . $paymentID . '' value='Add antibiotics' />";
            
                echo "</td>";
                
                
            $cached_data.="<input type='hidden' id='rowCount' value='1'>";
           $cached_data.=" </tr>";
                    
             
         echo $cached_data;
        
      
        echo '</table>';
        echo 'Remarks:<textarea id="Remarks" style="width:50%;padding-left:5px;margin-top:5px">' . $row2['Remarks'] . '</textarea><br />';
        echo '<tr><td></td><td>';
               $Validated1="no";  
            $Submitted="No";
            $ValidatedBy=0;
            $SavedBy=0;
            $Saved="no";
            $Validate="no";
            $modified="No";
             $mysql_is_run= mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results WHERE ref_test_result_ID IN (SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$paymentID')");
              if(mysqli_num_rows($mysql_is_run)>0){
                $mysql_is_run_rows=mysqli_fetch_assoc($mysql_is_run); 
                $Validate=$mysql_is_run_rows['Validated'];  
                $Submitted=$mysql_is_run_rows['Submitted'];  
                $modified=$mysql_is_run_rows['modified'];  
                $Saved=$mysql_is_run_rows['Saved'];  
                $SavedBy=$mysql_is_run_rows['SavedBy'];  
                $ValidatedBy=$mysql_is_run_rows['ValidatedBy'];  
              }
        
//              if($Validated !="Yes"){
//                 echo '<input type="button" id="saveCulture7" onclick="validate_patient_culture('. $paymentID .')"  name="' . $paymentID . '" value="Validate" />';
//              }
//              if($Submitted !="Yes"){
//                 echo '<input type="button" id="saveCulture23" onclick="send_patient_culture_result(' . $paymentID . ')"  name="' . $paymentID . '" value="Send" /></td></tr>';
//              } 
               echo "<div id='buttonvalidate'>"
             
                       . "</div>";
              
            $checkIfAllowedValidateyy = "no";
        if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
            $checkIfAllowedValidateyy = "yes";
        }
             echo '<input type="button"  class="art-button-green" id="saveCulture24" onclick="preview_lab_result(' . $paymentID . ')"  name="' . $paymentID . '" value="Preview" />';
                
//                  if($modified=="No" && $checkIfAllowedValidateyy=="yes"){
//                echo '<input type="button" id="saveCulture7" onclick="validate_patient_culture('. $paymentID .')"  name="' . $paymentID . '" value="Modify" />';
//               }
//               if($Submitted=='no'){
//                echo '<input type="button" id="saveCulture23" onclick="send_patient_culture_result(' . $paymentID . ')"  name="' . $paymentID . '" value="Send" /></td></tr>';   
//              
//                }
        
//              $mysql_is_run2=0;$mysql_is_run2= mysqli_query($conn,"SELECT payment_item_ID FROM tbl_culture_results WHERE payment_item_ID='$paymentID')");
//              if(mysqli_num_rows($mysql_is_run2)<=0){
                  echo '<input type="button" id="saveCulture25" onclick="Updatelldata(' . $paymentID . ')"  name="' . $paymentID . '" value="Update" />';
            
    } elseif ($_POST['getCultureResults'] == 'culturesave') {

        $paymentID = mysqli_real_escape_string($conn,$_POST['paymentID']);
        $Biotest1 = mysqli_real_escape_string($conn,$_POST['Biotest1']);
        $Biotest2 = mysqli_real_escape_string($conn,$_POST['Biotest2']);
        $Biotest3 = mysqli_real_escape_string($conn,$_POST['Biotest3']);
        $Biotest4 = mysqli_real_escape_string($conn,$_POST['Biotest4']);
        $Biotest5 = mysqli_real_escape_string($conn,$_POST['Biotest5']);
        $Specimen = mysqli_real_escape_string($conn,$_POST['Specimen']);
        $Organism1 = mysqli_real_escape_string($conn,$_POST['Organism1']);
        $Organism2 = mysqli_real_escape_string($conn,$_POST['Organism2']);
        $Organism3 = mysqli_real_escape_string($conn,$_POST['Organism3']);
        $Remarks = mysqli_real_escape_string($conn,$_POST['Remarks']);

        $checkExistance = mysqli_query($conn,"SELECT * FROM tbl_culture_results WHERE payment_item_ID='$paymentID'");
        $numRows = mysqli_num_rows($checkExistance);
        if ($numRows > 0) {

            $update = "UPDATE tbl_culture_results SET Biotest1='$Biotest1',Biotest2='$Biotest2',Biotest3='$Biotest3',Biotest4='$Biotest4',Biotest5='$Biotest5',Specimen='$Specimen',Organism1='$Organism1',Organism2='$Organism2',Organism3='$Organism3',Remarks='$Remarks' WHERE payment_item_ID='$paymentID'";
        } else {

            $update = "INSERT INTO tbl_culture_results (payment_item_ID,Biotest1,Biotest2,Biotest3,Biotest4,Biotest5,Specimen,Organism1,Organism2,Organism3,Remarks) VALUES ('$paymentID','$Biotest1','$Biotest2','$Biotest3','$Biotest4','$Biotest5','$Specimen','$Organism1','$Organism2','$Organism3','$Remarks')";
        }
        $updateResult = mysqli_query($conn,$update);

        if ($updateResult) {
            $datastring = $_POST['datastring'];
            $results = explode('$>', $datastring);
            $message = '';
            foreach ($results as $value) {
                $data = explode("#@", $value);
                $antibiotic = $data[0];
                $org1 = $data[1];
                $org2 = $data[2];
                $org3 = $data[3];

                $checkpaymentexistancy = mysqli_query($conn,"SELECT * FROM tbl_culture_antibiotics WHERE payment_item_ID='$paymentID' AND Antbiotic='$antibiotic'");
                $numRows = mysqli_num_rows($checkpaymentexistancy);
                if ($numRows > 0) {
                    $update2 = "UPDATE tbl_culture_antibiotics SET Antbiotic='$antibiotic',Answerorg1='$org1',Answerorg2='$org2',Answerorg3='$org3' WHERE payment_item_ID='$paymentID' AND Antbiotic='$antibiotic'";
                } else {
                    $update2 = "INSERT INTO tbl_culture_antibiotics (payment_item_ID,Antbiotic,Answerorg1,Answerorg2,Answerorg3) VALUES ('$paymentID','$antibiotic','$org1','$org2','$org3')";
                }

                $antibioticData = mysqli_query($conn,$update2);
                if ($antibioticData) {
                    $message = 'Results successfully saved';
                }
            }

            echo $message;
        } else {
            echo 'Results not successfully saved,an error has occured';
        }
    }
}
?>


<div id="cultureResult">
    <div id="cultureStatus"></div>
    <div id="showCulture">

    </div>
</div>

<style>
.modificationStats:hover {
    text-decoration: underline;
    color: rgb(145, 0, 0);
    cursor: pointer;
}

.prevHistory:hover {
    text-decoration: underline;
    color: rgb(145, 0, 0);
    cursor: pointer;
}
</style>

<script>
function send_patient_culture_result(payment_id) {
    $.ajax({
        type: 'POST',
        url: 'ajax_send_updted_culture.php',
        data: {
            payment_id: payment_id
        },
        success: function(data) {
            console.log(data);
            //             get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID) 
            alert("data validated");

            //             $("#saveCulture23").hide();
            //              $("#saveCulture7").show();
        }
    });
}

function validate_patient_culture(payment_id) {
    //     alert(payment_id);
    $.ajax({
        type: 'POST',
        url: 'ajax_validate_updated_culture.php',
        data: {
            payment_id: payment_id
        },
        success: function(data) {
            //             alert("successful data modify");
            //             get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID) 
            console.log(data);
            //             $("#saveCulture7").hide();
        }
    });
}

function preview_lab_result(payment_id) {
    window.open("preview_micro_biology_result.php?payment_id=" + payment_id, "_blank");
}

function addorganism() {
    //alert(Item_ID+"  "+Item_Subcategory_ID+" "+item_Name);
    $("#showdata").dialog("option", "title");
    $("#itemIDAdd").attr("onClick", "addorganism1()");
    $("#Cached").html('');
    $("#showdata").dialog("open");

}

function addorganism1() {
    var Organism = document.getElementById('Organism').value;
    if (Organism == '') {
        alert("Enter Organism name");
        exit();
    }
    $.ajax({
        type: 'GET',
        url: 'requests/Addorganism.php',
        data: {
            Organism: Organism
        },
        success: function(result) {
            $('#Cached').html(result);
            alert("success data saved");
            document.getElementById('Organism').value = "";
            getdropdown();
        },
        error: function(x, y, z) {
            console.log(x + y + z);
        }
    });
}
// parameter
function getdropdown() {
    if (window.XMLHttpRequest) {
        show = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        show = new ActiveXObject('Micrsoft.XMLHTTP');
        show.overrideMimeType('text/xml');
    }
    show.onreadystatechange = AJAXStat;
    show.open('GET', 'requests/Addorganism.php', true);
    show.send();

    function AJAXStat() {
        var respond = show.responseText;
        document.getElementById('Cached').innerHTML = respond;
    }
}

function Updatelldata(paymentID) {
    var biometrict_test = [];
    var inps = document.getElementsByName('biometrict_test[]');
    for (var i = 0; i < inps.length; i++) {
        var inp = inps[i];
        biometrict_test.push(inp.value);
    }
    console.log(biometrict_test);
    var sensitive = [];
    var sensitives = document.getElementsByName('sensitive[]');
    for (var i = 0; i < sensitives.length; i++) {
        var inp = sensitives[i];
        sensitive.push(inp.value);
    }
    var antibiotic = [];
    var antibiotics = document.getElementsByName('antibiotic[]');
    for (var i = 0; i < antibiotics.length; i++) {
        var inp = antibiotics[i];
        antibiotic.push(inp.value);
    }

    //             alert(paymentID);
    //             alert(antibiotic);
    if (antibiotic == '') {
        alert("Enter Antibiotics");
        exit();
    }

    if (sensitive == '') {
        alert("Enter Sensitive");
        exit();
    }

    if (biometrict_test == '') {
        alert("Enter Biochemical test");
        exit();
    }
    var wet = $('#wet').val();
    if (wet == '') {
        alert("Enter wet description");
        exit();
    }
    var sign = $('#sign').val();
    if (sign == '') {
        alert("Enter sign");
        exit();
    }
    var desease = $('#desease').val();
    if (desease == '') {
        alert("Enter desease");
        exit();
    }
    var stein = $('#stein').val();
    if (stein == '') {
        alert("Enter stein");
        exit();
    }
    var specimen = $("#specimen").val();
    if (specimen == '') {
        alert("Enter specimen");
        exit();
    }
    var new_organism_1 = $('#new_organism_1').val();
    if (new_organism_1 == '') {
        exit();
    }

    var Remarks = $("#Remarks").val();
    if (Remarks == '') {
        alert("Enter Remarks");
        exit();
    }

    $.ajax({
        type: 'POST',
        url: 'Update_all_data_in_micro_biology.php',
        data: {
            paymentID: paymentID,
            biometrict_test: biometrict_test,
            sensitive: sensitive,
            antibiotic: antibiotic,
            Remarks: Remarks,
            specimen: specimen,
            stein: stein,
            desease: desease,
            sign: sign,
            wet: wet,
            new_organism_1: new_organism_1
        },
        success: function(result) {
            validate_patient_culture(paymentID);
            alert("success data Updated and modify");
            //              $("#saveCulture25").hide();
            console.log(result);
            //                buttonsendvalidate(paymentID);
        },
        error: function(x, y, z) {
            //                console.log("gtdfyj"+x+y+z);
        }
    });


}

function buttonsendvalidate(paymentID) {
    $.ajax({
        type: 'POST',
        url: 'ajax_button_update_culture.php',
        data: {
            paymentID: paymentID
        },
        success: function(data) {
            console.log(data);
            $("#buttonvalidate").html(data);
        },
        error: function(x, y, z) {
            //                console.log("gtdfyj"+x+y+z);
        }
    });

}
$(document).ready(function() {
    $("#showdata").dialog({
        autoOpen: false,
        width: '60%',
        title: 'New Organism',
        modal: true,
        position: 'center'
    });
    //alert(firstSelected);
});


// addition of rows in 
$('#addrow').click(function() {

    $.ajax({
        type: 'POST',
        url: 'requests/Antibiotic_select.php',
        data: "",
        success: function(result) {
            var rowCount = parseInt($('#rowCount').val()) + 1;
            var newRow = "<tr class='addnewrow tr" + rowCount + "'><td>" + result +
                "<td><input type='button' class='remove' row_id='" + rowCount +
                "' value='x'></td> </tr>";
            $('#CultureTbl').append(newRow);
            document.getElementById('rowCount').value = rowCount;
            $("select").select2();
        }
    });

});
$('#addrow1').click(function() {
    var rowCount = parseInt($('#rowCount').val()) + 1;
    var newRow = "<tr class='addnewrow tr" + rowCount +
        "'><td><input placeholder='enter new biogest' name='biometrict_test[]' class='txtbox' type='text' class='antibiotc' id='" +
        rowCount +
        " ' style='width:600px; padding-top:10px; padding-bottom:10px;'></td><td><input type='button' class='remove' row_id='" +
        rowCount + "' value='x'></td></tr>";
    $('#CultureTbl1').append(newRow);
    document.getElementById('rowCount').value = rowCount;
});
//
$(document).on('click', '.remove', function() {

    var id = $(this).attr('row_id');
    //alert(id);
    $('.tr' + id).remove().fadeOut();
});

//SEND
function SendSMS(department, receiver) {
    if (window.XMLHttpRequest) {
        sms = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        sms = new ActiveXObject('Micrsoft.XMLHTTP');
        sms.overrideMimeType('text/xml');
    }

    sms.onreadystatechange = AJAXSMS;
    sms.open('GET', 'SendSMS.php?Department=' + department + '&Receiver=' + receiver, true);
    sms.send();

    function AJAXSMS() {
        var smsrespond = sms.responseText;
        document.getElementById('SMSRespond').innerHTML = smsrespond;
    }
}
</script>


<script>
$(document).on('click', '.SubmitvalidatedResulttr', function(e) {
    e.stopImmediatePropagation();
    $('.Submitted').attr('checked', true);
    var itemID = $('.productID').val();
    var patientID = $(this).attr('name');

    var ppil = $(this).attr('ppil');
    alert(ppil)
    var parameterID, testID;
    var i = 1;
    var datastring;
    $('.Submitted').each(function() {
        parameterID = $(this).attr('id');
        testID = $(this).val();
        var x = $(this).is(':checked');
        if (x) {
            if ($(this).val() !== '') {
                if (i == 1) {
                    datastring = parameterID + '#@' + testID;
                } else {
                    datastring += "$>" + parameterID + '#@' + testID;
                }
            }
            i++;

        } else {

        }
    });
    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestResultsConsulted.php',
        data: 'SavegeneralResult=submitResult&testresults=' + datastring + '&itemID=' + itemID +
            '&patientID=' + patientID + '&ppil=' + ppil,
        cache: false,
        success: function(html) {
            alert('Results submitted to doctor successfully');
            $('#showGeneral').html(html);
        }
    });
});
</script>
<!-------culture results----->
<script>
$(document).on('click', '.culture', function(e) {
    e.stopImmediatePropagation();
    var name = $(this).attr('name');
    var payment = $(this).attr('id');
    var test = $(this).data('id');
    var data = {
        test: test,
        getCultureResults: "cultureview",
        paymentID: payment
    }

    $.ajax({
        type: 'POST',
        url: './laboratory/views/macro_micro_results.php',
        data: data,
        cache: false,
        success: function(html) {
            //alert(html);
            $('#showCulture').html(html);
        }
    });

    $('#cultureResult').dialog({
        modal: true,
        width: '98%',
        minHeight: 500,
        resizable: true,
        draggable: true,
    });

    $("#cultureResult").dialog('option', 'title', name);
});

$('#saveCulture').click(function(e) {
    e.stopImmediatePropagation();
    var payment = $(this).attr('name');
    var Biotest1 = $('#Biotest1').val();
    var Biotest2 = $('#Biotest2').val();
    var Biotest3 = $('#Biotest3').val();
    var Biotest4 = $('#Biotest4').val();
    var Biotest5 = $('#Biotest5').val();
    var Specimen = $('#Specimen').val();
    var Organism1 = $('#Organism1').val();
    var Organism2 = $('#Organism2').val();
    var Organism3 = $('#Organism3').val();
    var Remarks = $('#Remarks').val();
    var numbers = 1;
    var datastring = '';
    $('.antibiotc').each(function() {
        var id = $(this).attr('id');
        var antbiotic = $(this).val();
        var orgone = $('#orgone_' + id).val();
        var orgtwo = $('#orgtwo_' + id).val();
        var orgthree = $('#orgthree_' + id).val();
        if (numbers == 1) {
            datastring = antbiotic + '#@' + orgone + '#@' + orgtwo + '#@' + orgthree;
        } else {
            datastring += "$>" + antbiotic + '#@' + orgone + '#@' + orgtwo + '#@' + orgthree;
        }
        numbers = numbers + 1;
    });
    $.ajax({
        type: 'POST',
        url: 'requests/testResultsConsulted.php',
        data: 'getCultureResults=culturesave&paymentID=' + payment + '&Biotest1=' + Biotest1 +
            '&Biotest2=' + Biotest2 + '&Biotest3=' + Biotest3 + '&Biotest4=' + Biotest4 + '&Biotest5=' +
            Biotest5 + '&Specimen=' + Specimen + '&Organism1=' + Organism1 + '&Organism2=' + Organism2 +
            '&Organism3=' + Organism3 + '&Remarks=' + Remarks + '&datastring=' + datastring,
        cache: false,
        success: function(html) {
            $('#cultureStatus').html(html);
        }
    });
});

//    $('#addrow').click(function () {
//        var rowCount = parseInt($('#rowCount').val()) + 1;
//
//        var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><select class='antibiotc' id='" + rowCount + "'><option>----select----</option><option>Antibiotic1</option><option>Antibiotic2</option><option>Antibiotic3</option><option>Antibiotic4</option></select></td><td><select class='seleboxorg1' id='orgone_" + rowCount + "'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select><select class='seleboxorg2' id='orgtwo_" + rowCount + "'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select><select class='seleboxorg3' id='orgthree_" + rowCount + "'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select></td> <td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
//        $('#CultureTbl').append(newRow);
//
//        document.getElementById('rowCount').value = rowCount;
//    });
//
$(document).on('click', '.remove', function() {

    var id = $(this).attr('row_id');
    //alert(id);
    $('.tr' + id).remove().fadeOut();

});
</script>

<script>
function saveInformation(product_name, ppil, id) {
    var filter = "<?php echo $filter ?>";
    $('#attachFIlesLabsave_' + ppil).val(2);
    $('#attachFIlesLabvaldate_' + ppil).val('');
    $('#attachFIlesLabsubmit_' + ppil).val('');
    //alert('#uploadAttachmentLab_'+ppil);
    $('#uploadAttachmentLab_' + ppil).ajaxSubmit({
        beforeSubmit: function() {
            $('#progressDialogStatus').show();
        },
        success: function(data) {
            //create("default", {title: 'Success', text: data});

            if (parseInt(data) === 1) {
                create("default", {
                    title: 'Success',
                    text: 'Saved successifully'
                });
                //alertMsg("Saved successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                updateDialog(product_name, ppil, id, filter);
            }
        },
        complete: function(jqXHR, textStatus) {
            $('#progressDialogStatus').hide();
        }

    });
    return false;
}
</script>
<script>
function validateInformation(product_name, ppil, id) {
    var filter = "<?php echo $filter ?>";
    $('#attachFIlesLabsave_' + ppil).val('');
    $('#attachFIlesLabvaldate_' + ppil).val(2);
    $('#attachFIlesLabsubmit_' + ppil).val('');
    if (confirm('Are you sure you want to validate ' + product_name + ' result(s)?')) {
        $('#uploadAttachmentLab_' + ppil).ajaxSubmit({
            beforeSubmit: function() {
                $('#progressDialogStatus').show();
            },
            success: function(data) {
                // alert(data);
                if (parseInt(data) === 1) {
                    create("default", {
                        title: 'Success',
                        text: 'Validated successifully'
                    });
                    //alertMsg("Validated successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                    updateDialog(product_name, ppil, id, filter);
                }
            },
            complete: function(jqXHR, textStatus) {
                $("#progressDialogStatus").hide();
            }

        });
        return false;
    }
}
</script>
<script>
function updateDialog(product_name, ppil, id, filter) {
    var barcode = '';
    var id = id;
    var payment_id = ppil;

    var datastring = 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode + '&filter=' +
        filter;
    //alert(datastring);
    $.ajax({
        type: 'GET',
        url: 'requests/testResultsConsulted.php',
        data: datastring,
        cache: false,
        beforeSend: function(xhr) {
            $("#progressStatus").show();
        },
        success: function(html) {
            $('#showLabResultsHere').html(html);
        },
        complete: function(jqXHR, textStatus) {
            $("#progressStatus").hide();
        }
    });
}
</script>


<script type="text/javascript">
$(document).on('click', '.generalreslts', function(e) {
    e.stopImmediatePropagation();
    var patientID = $(this).attr('patientID');
    var ppil = $(this).attr('ppil');
    var name = $(this).attr('name');
    var filter = "<?php echo $filter ?>";
    $('#showGeneral').html('');
    var id = $(this).attr('id');

    //$("#labGeneral").dialog('option', 'title', testName);

    // $('#labResults').fadeOut(100);

    $.ajax({
        type: 'GET',
        url: 'requests/testResultsConsulted.php',
        data: 'generalResult=getGeneral&id=' + id + '&patientID=' + patientID + '&ppil=' + ppil +
            '&filter=' + filter,
        cache: false,
        beforeSend: function(xhr) {
            $('#showGeneral').html('');
            $("#progressStatusResults").show();
        },
        success: function(html) {
            // alert(html);
            $('#showGeneral').html(html);
        },
        complete: function(jqXHR, textStatus) {
            $("#progressStatusResults").hide();
        }
    });

    $('#labGeneral').dialog({
        modal: true,
        width: '90%',
        height: 550,
        resizable: true,
        draggable: true
    });

    $("#labGeneral").dialog('option', 'title', "Parameter Results");

});

$(document).on('click', '.validateResulttr', function(e) {
    e.stopImmediatePropagation();
    var payment = $('.paymentID').val();
    var productID = $('.productID').val();
    var patientID = $(this).attr('name');
    var ppil = $(this).attr('ppil');
    var parId, result;
    var i = 1;
    var datastring;
    var total = $('.Resultvalue').length;
    var temp = 0;
    //alert('validateResult');
    $('.Resultvalue').each(function() {
        parId = $(this).attr('id');
        result = $(this).val();

        if (result === '') {
            temp = temp + 1;

        }
    });

    if (temp === total) {
        alert("Please add atleast one result");
        exit();
    }

    $('.Resultvalue').each(function() {
        parId = $(this).attr('id');
        result = $(this).val();
        //alert('parId:'+parId+' result:'+result);
        //if(result != ''){
        if (i == 1) {
            datastring = parId + '#@' + result;
        } else {
            //paraID+=","+$(this).val();
            datastring += "$>" + parId + '#@' + result;
        }
        //}
        i++;

    });

    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestResultsConsulted.php',
        data: 'SavegeneralResult=getGeneral&testresults=' + datastring + '&payment=' + payment +
            '&productID=' + productID + '&patientID=' + patientID + '&ppil=' + ppil,
        cache: false,
        beforeSend: function(xhr) {
            $("#progressStatusResults").show();
        },
        success: function(html) {
            alert('Results saved successfully');
            $('#showGeneral').html(html);
        },
        complete: function(jqXHR, textStatus) {
            $("#progressStatusResults").hide();
        }
    });
});



$(document).on('click', '.validateSubmittedResulttr', function(e) {
    e.stopImmediatePropagation();
    $('.validated').attr('checked', true);
    var itemID = $('.productID').val();
    var patientID = $(this).attr('name');
    var parameterID, testID;
    var ppil = $(this).attr('ppil');
    var i = 1;
    var datastring;

    $('.Resultvalue').each(function() {
        var value = $(this).val();
        if (value == '') {
            alert('Fill all test parameter results to validate this test');
            exit();
        }

    });

    //alert($('.validated').length);
    $('.validated').each(function() {
        parameterID = $(this).attr('id');
        testID = $(this).val();
        var x = $(this).is(':checked');
        if (x) {
            if ($(this).val() !== '') {
                if (i == 1) {
                    datastring = parameterID + '#@' + testID;
                } else {
                    datastring += "$>" + parameterID + '#@' + testID;
                }
            }
            i++;
        } else {

        }
    });
    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestResultsConsulted.php',
        data: 'SavegeneralResult=Validation&testresults=' + datastring + '&itemID=' + itemID +
            '&patientID=' + patientID + '&ppil=' + ppil,
        cache: false,
        beforeSend: function(xhr) {
            $("#progressStatusResults").show();
        },
        success: function(html) {
            if (html == 'NOTSAVED') {
                alert('Please save the information before validating');
            } else {
                $('#showGeneral').html(html);
            }
        },
        complete: function(jqXHR, textStatus) {
            $("#progressStatusResults").hide();
        }
    });
});


$('#labGeneral').on("dialogclose", function() {

    $('#labResults').fadeIn(1000);
});


//Results modifications
$(document).on('click', '.modificationStats', function(e) {
    e.stopImmediatePropagation();
    $('#labGeneral').fadeOut();
    var parameter = $(this).attr('id');
    var paymentID = $('.paymentID').val();
    $.ajax({
        type: 'POST',
        url: 'requests/resultModification.php',
        data: 'parameter=' + parameter + '&paymentID=' + paymentID,
        cache: false,
        success: function(html) {
            // alert(html);
            //alert('Results saved/modified successfully');
            $('#historyResults1').html(html);
            // document.getElementById("historyResults1").innerHTML=html;
        }
    });

    $('#historyResults1').dialog({
        modal: true,
        width: 600,
        minHeight: 400,
        resizable: true,
        draggable: true,
        title: 'Results modification history'
    });

});


$('#historyResults1').on("dialogclose", function() {

    $('#labGeneral').fadeIn(1000);
});

$(document).on('click', '.prevHistory', function(e) {
    e.stopImmediatePropagation();
    var itemID = $('.productID').val();
    var patientID = $(this).attr('name');
    var parameterID = $(this).attr('id');
    var parameterName = $('.parameterName').val();
    var ppil = $(this).attr('ppil');
    $.ajax({
        type: 'POST',
        url: 'requests/resultModification.php',
        data: 'action=history&itemID=' + itemID + '&patientID=' + patientID + '&parameterID=' +
            parameterID + '&ppil=' + ppil,
        cache: false,
        success: function(html) {
            $('#historyResults1').html(html);
        }
    });

    $('#historyResults1').dialog({
        modal: true,
        width: 600,
        minHeight: 400,
        resizable: true,
        draggable: true
    });
    $("#historyResults1").dialog('option', 'title', parameterName);
});






$(".Quantative").bind("keydown", function(event) {
    //alert(event.keyCode);  
    if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event
        .keyCode == 13 ||
        // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        // Allow: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Allow: Ctrl+V
        (event.keyCode == 86 && event.ctrlKey === true) ||
        //Allow minus and decimal
        event.keyCode == 190 || event.keyCode == 173 ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    } else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event
                .keyCode > 105)) {
            event.preventDefault();
        }
    }
});

$('.printngbtn').click(function(e) {
    e.preventDefault();
    var datastring = '';
    var id = $(this).attr('id');
    var resultLocation = $('#resultLocation').val();
    var payID = $(this).attr('payID');
    var num = 1;
    var n = $(".printselected:checked").length;
    if (n > 0) {
        $('.printselected').each(function() {
            var id = $(this).attr('id');
            var checked = $(this).is(':checked');
            if (checked) {
                if (num == 1) {
                    datastring = id;
                } else {
                    datastring += '@' + id;
                }
                num++;
                // alert(datastring);
            } else {


            }

        });
        window.open('printPatientsLabResults.php?resultLocation=' + resultLocation + '&registration_ID=' + id +
            '&payment_ID=' + payID + '&datastring=' + datastring + '', "_blank");
    } else {
        alert('Choose at least one test to print');
    }

});
</script>
<script>
function Show_Patient_File(Registration_ID) {
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
    var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=' +
        Registration_ID + '&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
    //winClose.close();
    //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

}

function popupwindow(url, title, w, h) {
    var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h +
        '; center:yes;dialogTop:' + top + '; dialogLeft:' + left
    ); //'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    return mypopupWindow;
}
</script>

<script>
function remove_img(Attachment_ID, instance, product_name, ppil, id) {
    var filter = "<?php echo $filter ?>";
    if ($(instance).is(":checked")) {
        if (!confirm('Are sure you want to remove this item')) {
            $(instance).prop("checked", false);
            exit;
        }

        $.ajax({
            type: 'POST',
            url: 'requests/remove_attached_img.php',
            data: 'source=lab&Attachment_ID=' + Attachment_ID,
            beforeSend: function(xhr) {
                $("#progressStatus").show();
            },
            success: function(result) {
                if (result == '1') {
                    updateDialog(product_name, ppil, id, filter);
                } else {
                    alert('Process failed');
                }
            },
            complete: function(jqXHR, textStatus) {
                $("#progressStatus").hide();
            }
        });

    }
}
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
$(document).ready(function(e) {
    //         $("#saveCulture7").hide();
    $("select").select2();
});
</script>
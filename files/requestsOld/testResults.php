
<style>
    .prevHistory:hover{
        cursor:pointer;
    }
</style>
<?php
session_start();
include("../includes/connection.php");
$valSub = '';
if (isset($_GET['action'])) {
    $data = '';
    if ($_GET['action'] == 'getResult') {
        $id = $_GET['id'];
        $filterprev = str_replace("'", "na", $_GET['filter']);

        $filter = $_GET['filter'];
        $payment_id = $_GET['payment_id'];
        $barcode = $_GET['barcode'];
        if ($barcode == '') {
            $Query = "SELECT * FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE Registration_ID='" . $id . "' $filter GROUP BY Payment_Item_Cache_List_ID"; // AND Payment_Item_Cache_List_ID='$payment_id'
        } else {
            $Query = "SELECT * FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE Registration_ID='" . $id . "' AND payment_item_ID='" . $barcode . "' $filter  GROUP BY Payment_Item_Cache_List_ID"; // AND Payment_Item_Cache_List_ID='$payment_id'
        }

        //echo $Query;exit;

        $QueryResults = mysql_query($Query) or die(mysql_error());
        ;
        echo "<center><table class='' style='width:100%'>"
        . "";
        echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%' style='text-align:left;'>S/N</th>
                <th width='30%' style='text-align:left'>Test Name</th>
                <th width='25%' style='text-align:left'>Doctor's Notes</th>
                <th width='10%' style='text-align:left'>Time Specimen Taken</th>
                <th width='10%' style='text-align:left'>Time Specimen Submitted</th>
                <th width='5%' style='text-align:left'>Test Status</th>
                <th width='30%' style='text-align:left'>Overall Remarks && Result</th>
                <th width='1%' style='text-align:left'>Attachment</th>
                <th width='1%' style='text-align:left'>Results</th>
      </tr>";
        $i = 1;


//echo mysql_num_rows($QueryResults);
        $datamsg = mysql_num_rows($QueryResults);
        $couterSubVal = 0;

        $item_with_no_parameter = 0;
        $checkIfAllowedValidate = false;
        if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
            $checkIfAllowedValidate = true;
        }

        while ($row = mysql_fetch_assoc($QueryResults)) {
            $testIDUnic = $row['test_result_ID'];
            $itemTime = $row['Payment_Item_Cache_List_ID'];
            // echo "SELECT * from tbl_specimen_results WHERE payment_item_ID='$itemTime' GROUP BY payment_item_ID ORDER BY `payment_item_ID` ASC";
            $queryspecimen = mysql_query("SELECT * from tbl_specimen_results WHERE payment_item_ID='$itemTime' GROUP BY payment_item_ID ORDER BY `payment_item_ID` ASC");
            $time = mysql_fetch_row($queryspecimen);

            $RS = mysql_query("SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysql_error());
            $rowSt = mysql_fetch_assoc($RS);
            $Submitted = $rowSt['Submitted'];
            $Validated = $rowSt['Validated'];

            //echo 'submitted and validated';

            if ($Submitted == 'Yes' && $Validated == 'Yes') {
                $couterSubVal +=1;
            } else {

                $resultSaved = mysql_query("SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='" . $row['Item_ID'] . "'");
                $numrows = mysql_num_rows($resultSaved);


                //Count parameters Saved
                $numparameterSaved = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$itemTime'");
                $numSavedParameters = mysql_num_rows($numparameterSaved);

                //die($numSavedParameters.' '."SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$itemTime'");
                //Count parameters Submitted
                $numparameterSubmitted = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Submitted='Yes' AND payment_item_ID='$itemTime'");
                $numSubmittedParameters = mysql_num_rows($numparameterSubmitted);


//Count parameters Validated
                $numparameterValidated = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Validated='Yes' AND payment_item_ID='$itemTime'");
                $numValidatedParameters = mysql_num_rows($numparameterValidated);

                //echo "SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$itemTime' AND result <>''";
                //Count parameters None empty results
                $numparameterNoneempty = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$itemTime' AND result <>''")or die(mysql_error());
                $numNoneemptyParameters = mysql_num_rows($numparameterNoneempty);


                //$data .="<form id='uploadAttachmentLab_".$row['Payment_Item_Cache_List_ID']."' action='attachmentLabResult.php?item_id=".$row['Payment_Item_Cache_List_ID']."' method='post' enctype='multipart/form-data'>"
                //  . "<input type='hidden' name='Registration_id' value='".$id."'>";
                $data .= "<tr>";
                $data .= "<td>" . $i++ . "</td>";
                $data .= "<td><input type='hidden' name='Payment_Item_Cache_List_ID[]' value='" . $row['Payment_Item_Cache_List_ID'] . "'>" . $row['Product_Name'] . "</td>";
                $data .= "<td><textarea class='doctorNotes' readonly='true' id='" . $row['Payment_Item_Cache_List_ID'] . "'>" . $row['Doctor_Comment'] . "</textarea></td>";
                $data .= "<td>" . $time[5] . "</td>";
                $data .= "<td>" . $row['TimeSubmitted'] . "</td>";
                $data .= "<td>" . $row['Status'] . "</td>";
                $data .= "<td  colspan='2'>"
                        . "<form id='uploadAttachmentLab_" . $row['Payment_Item_Cache_List_ID'] . "' action='attachmentLabResult.php?item_id=" . $row['Payment_Item_Cache_List_ID'] . "' method='post' enctype='multipart/form-data'>"
                        . "<input type='hidden' name='Registration_id' value='" . $id . "'>
                           <input type='hidden' name='ppilc' class='art-button-green' value='" . $row['Payment_Item_Cache_List_ID'] . "'> 
                           <input type='hidden' name='attachFIlesLabvaldate'   value='' id='attachFIlesLabvaldate_" . $row['Payment_Item_Cache_List_ID'] . "' />
                           <input type='hidden' name='attachFIlesLabsubmit'  value='' id='attachFIlesLabsubmit_" . $row['Payment_Item_Cache_List_ID'] . "' />
                           <input type='hidden' name='attachFIlesLabsave'  value='' id='attachFIlesLabsave_" . $row['Payment_Item_Cache_List_ID'] . "' />
                           "
                        . "<textarea style='width:100%;display:inline;margin:0px' class='overallRemarks' name='overallRemarks_" . $row['Payment_Item_Cache_List_ID'] . "' id='" . $row['Payment_Item_Cache_List_ID'] . "'></textarea>";
                $data .= "<select style='width:40%;display:inline;margin:0px;padding:4px' name='otherResult_" . $row['Payment_Item_Cache_List_ID'] . "'>
                                <option value=''>Select result</option>
                                <option value='positive'>Positive</option>
                                <option value='negative'>Negative</option>
                                <option value='normal'>Normal</option>
                                <option value='abnormal'>Abnormal</option>
                                <option value='high'>High</option>
                                <option value='low'>Low</option>
                           </select>" .
                        "<input style='width:60%;display:inline;margin:0px' class='labfileCh_" . $row['Payment_Item_Cache_List_ID'] . "' type='file' name='labfile_" . $row['Payment_Item_Cache_List_ID'] . "[]'  multiple='true' id='" . $row['Payment_Item_Cache_List_ID'] . "' onchange='readFileName(this.value," . $row['Payment_Item_Cache_List_ID'] . ")'>"
                        . "</form>"
                        . "</td>";

                //Check if it has parameter
                //tbl_tests_parameters(ref_parameter_ID,ref_item_ID)
                $q_chck = mysql_query("SELECT ref_item_ID FROM tbl_tests_parameters WHERE ref_item_ID='" . $row['Item_ID'] . "'") or die(mysql_error());

                $check_par = false;
                //Check for paramenter settings

                if ($numSavedParameters == 0) {
                    if (mysql_num_rows($q_chck) > 0) {
                        $valSub = "<td style='width:80%'>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />
                              <input type='button'  class='Submitresult' disabled='disabled' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                    } else {

                        if ($_SESSION['hospitalConsultaioninfo']['enb_lab_wt_no_par'] == '1') {


                            //check if parameter default_parameter is availlable
                            $checkpar = mysql_query("SELECT Parameter_Name FROM tbl_parameters WHERE Parameter_Name ='default_parameter'") or die(mysql_error());

                            if (mysql_num_rows($checkpar) == 0) {
                                $insert = mysql_query("INSERT INTO tbl_parameters (Parameter_Name,unit_of_measure,lower_value,operator,higher_value,result_type) VALUES ('default_parameter','','','','','Qualitative')") or die(mysql_error());
                            }
                            //add default parameter call it default_parameter
                            $getDefParaID = mysql_query("SELECT parameter_ID FROM tbl_parameters WHERE Parameter_Name ='default_parameter'") or die(mysql_error());

                            $parameter_ID = mysql_fetch_assoc($getDefParaID)['parameter_ID'];

                            $queryResult = mysql_query("INSERT INTO tbl_tests_parameters VALUES('$parameter_ID','" . $row['Item_ID'] . "')") or die(mysql_error());

                            //$valSub='<td>'.$parameter_ID.'</td>';


                            $valSub = "<td style='width:80%'>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />
                              <input type='button'  class='Submitresult' disabled='disabled' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                        } else {
                            $valSub = "<td style='width:80%'>
                                    <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save' onclick=\"alert('No parameter set for this item! Please add paramenter to continue?')\" />";
                                    if ($checkIfAllowedValidate) {
                                     $valSub .="  <input type='button' disabled='disabled' class='validatesendresult' style='width:100%;padding:0 0px' id='fileupload' value='Validate' onclick=\"validateInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                                      }

                                   $valSub .=" <input type='button'  class='Submitresult' disabled='disabled' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"alert('No parameter set for this item! Please add paramenter to continue?')\" />";
                            $check_par = true;
                        }
                    }

                    
                    $valSub .=" </td>";

                    //echo $valSub;exit;
                } elseif ($numNoneemptyParameters == $numrows) {
                    $valSub = "<td style='width:80%'>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save/update' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                              if ($checkIfAllowedValidate) {
                                 $valSub .="  <input type='button'  class='validatesendresult' style='width:100%;padding:0 0px'  id='fileupload' value='Validate' onclick=\"validateInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                               }
                            $valSub .="<input type='button'  class='Submitresult' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";

                    $valSub .=" </td>";
                } elseif ($numNoneemptyParameters < $numrows) {
                    $valSub = "<td style='width:80%'>
                              $numNoneemptyParameters < $numrows
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save/update' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                              if ($checkIfAllowedValidate) {
                             $valSub .="  <input type='button' disabled='disabled' class='validatesendresult' style='width:100%;padding:0 0px'  id='fileupload' value='Validate' onclick=\"validateInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                            }
                             $valSub .="<input type='button'  class='Submitresult' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                    
                    $valSub .=" </td>";
                }

                $data .= "<td>
	       <table border='0' width='100%'>
                  <tr>
                   <td>
                     <input type='button' class='generalreslts' name='" . $row['Product_Name'] . "' ppil='" . $row['Payment_Item_Cache_List_ID'] . "' id='" . $row['Item_ID'] . "' patientID='$id' value='Results'>
                                          
	           </td>
                   <td>
                  	<input type='button' class='culture' name='" . $row['Product_Name'] . "' id='" . $row['payment_item_ID'] . "' value='Culture' />
                        <input type='button' name='Print_Barcode' value='Barcode' onclick='Print_Barcode_Payment(" . $id . ", " . $row['Payment_Item_Cache_List_ID'] . "," . $row['Item_ID'] . ")' class='Print_Barcode'>                                        </td>
                     
	           </td>
                   
                     " . $valSub . "
                   
                  </tr> 
                </table>    	
	    </td>
           
            </tr>
             </form>
	";
            }
        }

        $data.= "<tr>
               <td colspan='9' style='text-align:center'>
               <a href='previewSampledTests.php?filterprev=$filterprev&patient_id=" . $id . "' id='previewSampleTest' class='art-button-green' target='_blank'>SAMPLE COLLECTION(s) PREVIEW / PRINT</a></center>
      
                <input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File(" . $id . ")' class='art-button-green'/>
                </tr>
                </table>";

        echo $data;
    }
} elseif (isset($_GET['generalResult'])) {
    $id = $_GET['id'];
    $ppil = $_GET['ppil'];
    $patientID = $_GET['patientID'];
    $filter = $_GET['filter'];

    $sn = 1;
    $data = '';
    $QueryResults = "SELECT * FROM tbl_item_list_cache 
	     INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
		 INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID 
		 INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
		 JOIN tbl_parameters ON parameter_ID=ref_parameter_ID  
		 WHERE Item_ID='" . $id . "' AND Registration_ID='" . $patientID . "' AND payment_item_ID='" . $ppil . "'";

    //die($QueryResults);
    $result = mysql_query($QueryResults);
    $numRows = mysql_num_rows($result);
    if ($numRows > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $resultID = $row['test_result_ID'];
            $parameterID = $row['parameter_ID'];
            $Insert = "INSERT INTO tbl_tests_parameters_results (ref_test_result_ID,parameter,result,modified,Validated,Submitted,status) VALUES ('$resultID','$parameterID','','','','','')";
            $Query = mysql_query($Insert);
        }

        $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $id . "'  AND Registration_ID='" . $patientID . "' AND Validated != 'Yes' AND tr.payment_item_ID='" . $ppil . "'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
        $data = '';
        //die($selectQuery);
        $GetResults = mysql_query($selectQuery);
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
                <th width='' style='text-align:left'>Previous results</th>
            </tr>";

        $testID = '';
        $paymentID = '';
        $Validated = false;

        $datamsg = mysql_num_rows($GetResults);
        $operators = array('=', '>', '<', '>=', '<=');
        while ($row2 = mysql_fetch_assoc($GetResults)) {
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


            $data .= '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';


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
                    } elseif (($rowResult <= $lower) && ($rowResult <= $upper)) {
                        $data .= '<td><p style="color:rgb(0,128,0)">N</p></td>';
                    }
                } else {
                    $data .= '<td><p style="color:rgb(0,128,0)"></p></td>';
                }
            } else if ($result_type == "Qualitative") {
                $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
            } else {
                $data .= '<td><p style="color:rgb(0,0,128)"></p></td>';
            }


            //Get previous test results
            $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $id . "' AND tr.payment_item_ID<>'" . $ppil . "'";
            $Queryhistory = mysql_query($historyQ);
            $myrows = mysql_num_rows($Queryhistory);
            if ($myrows > 0) {
                //echo $historyQ;
                $data .= '<td>
                <p class="prevHistory" value="' . $id . '" ppil="' . $ppil . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                        $myrows . ' Previous result(s)'
                        . '</p>
               
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
        //die($datamsg);
        if ($datamsg > 0) {
            //Count total number of parameters
            $resultSaved = mysql_query("SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='$id'");
            $numrows = mysql_num_rows($resultSaved);


            //Count parameters Saved
            $numparameterSaved = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$paymentID'");
            $numSavedParameters = mysql_num_rows($numparameterSaved);

            //Count parameters Submitted
            $numparameterSubmitted = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Submitted='Yes' AND payment_item_ID='$paymentID'");
            $numSubmittedParameters = mysql_num_rows($numparameterSubmitted);


//Count parameters Validated
            $numparameterValidated = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Validated='Yes' AND payment_item_ID='$paymentID'");
            $numValidatedParameters = mysql_num_rows($numparameterValidated);

            //Count parameters None empty results
            $numparameterNoneempty = mysql_query("SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$paymentID' AND result <>''")or die(mysql_error());
            $numNoneemptyParameters = mysql_num_rows($numparameterNoneempty);

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

                $data .= '<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor"> ';
                ?>
                <?php
                $select_Patient_Phone = "
			SELECT Phone_Number FROM tbl_patient_registration WHERE Registration_ID = '$patientID'
		";
                $select_Patient_Phone_Qry = mysql_query($select_Patient_Phone) or die(mysql_error());

                if (mysql_num_rows($select_Patient_Phone_Qry) > 0) {
                    while ($PPhone = mysql_fetch_assoc($select_Patient_Phone_Qry)) {
                        $Receiver = $PPhone['Phone_Number'];
                    }
                } else {
                    $Receiver = 'NoNumber';
                }
                ?>
                <button id="SMSButton" class="art-button-green" onClick="SendSMS('Lab', '<?php echo $Receiver; ?>')">Send SMS Alert</button>
                <span id="SMSRespond"></span>				
                <?php
                echo '</div>';
            } elseif ($numNoneemptyParameters < $numrows) {
                $data .= '<div> 
		
		<input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> 
		
		<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor"> </div>';
            }
        } else {
            //echo '&(&^%77)&Test_Complete';exit;
        }
    } else {

        $data .= 'No parameter assigned to this test';
    }

    echo $data;
} elseif (isset($_POST['getCultureResults'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    if ($_POST['getCultureResults'] == 'cultureview') {
        $paymentID = $_POST['paymentID'];
        $query = "SELECT * FROM tbl_item_list_cache JOIN tbl_culture_results ON Payment_Item_Cache_List_ID=payment_item_ID WHERE payment_item_ID='$paymentID' GROUP BY payment_item_ID";
        $myQuery = mysql_query($query);
        $row2 = mysql_fetch_assoc($myQuery);
        echo "<center><table cellspacing='0' cellpadding='0' id='CultureTbl'>";
        echo "<tr>
                <td><b>Biochemical Tests</b></td>
                <td>1.<input id='Biotest1' class='txtbox' type='text' value='" . $row2['Biotest1'] . "' style='padding-left:5px;margin-top:5px;width:98%'><br />
                2.<input id='Biotest2' class='txtbox' type='text' value='" . $row2['Biotest2'] . "' style='padding-left:5px;margin-top:5px;width:98%'><br />
                3.<input id='Biotest3' class='txtbox' type='text' value='" . $row2['Biotest3'] . "' style='padding-left:5px;margin-top:5px;width:98%'><br />
                4.<input id='Biotest4' class='txtbox' type='text' value='" . $row2['Biotest4'] . "' style='padding-left:5px;margin-top:5px;width:98%'><br />
                5.<input id='Biotest5' class='txtbox' type='text' value='" . $row2['Biotest5'] . "' style='padding-left:5px;margin-top:5px;width:98%'><br />
                </td>
             </tr>";


        echo "<tr><td><b>Specimen Type</b></td><td>&nbsp;&nbsp;&nbsp;<input id='Specimen' class='txtbox' type='text' value='" . $row2['Specimen'] . "' style='padding-left:5px;margin-top:5px;width:98%'></td></tr>";

        echo "<tr>
                <td><b>Organism</b></td>
                <td>
                &nbsp;&nbsp;&nbsp;<input id='Organism1' class='txtbox' type='text' value='" . $row2['Organism1'] . "' style='width:293px;padding-left:5px;margin-top:5px'>
                <input id='Organism2' class='txtbox' type='text' value='" . $row2['Organism2'] . "' style='width:293px;padding-left:5px;margin-top:5px'>
                <input id='Organism3' class='txtbox' type='text' value='" . $row2['Organism3'] . "' style='width:293px;padding-left:5px;margin-top:5px'>
                </td>
            </tr>";
        $getAntibiotics = "SELECT * FROM tbl_culture_antibiotics WHERE payment_item_ID='$paymentID'";
        $querydata = mysql_query($getAntibiotics);
        $ifExist = mysql_num_rows($querydata);
        if ($ifExist > 0) {
            while ($result2 = mysql_fetch_assoc($querydata)) {
                echo "<tr id='addhere' class='addnewrow'><td><select class='antibiotc' id='1'><option>" . $result2['Antbiotic'] . "</option><option>Antibiotic1</option><option>Antibiotic2</option><option>Antibiotic3</option><option>Antibiotic4</option></select></td>
            <input type='hidden' id='rowCount' value='1'>

            <td>
                    <select class='seleboxorg1' id='orgone_1'><option>" . $result2['Answerorg1'] . "</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
                    
                    <select class='seleboxorg2' id='orgtwo_1'><option>" . $result2['Answerorg2'] . "</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
                    
                    <select class='seleboxorg3' id='orgthree_1'><option>" . $result2['Answerorg3'] . "</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
               </td>
               
            </tr>";
            }
        } else {
            echo "<tr id='addhere' class='addnewrow'><td><select class='antibiotc' id='1'><option>----select----</option><option>Antibiotic1</option><option>Antibiotic2</option><option>Antibiotic3</option><option>Antibiotic4</option></select></td>
              <input type='hidden' id='rowCount' value='1'>

            <td>
                    <select class='seleboxorg1' id='orgone_1'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
                    
                    <select class='seleboxorg2' id='orgtwo_1'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
                    
                    <select class='seleboxorg3' id='orgthree_1'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
               </td>
            </tr>";
        }

        echo '</table>';
        echo 'Remarks:<textarea id="Remarks" style="width:79%;padding-left:5px;margin-top:5px">' . $row2['Remarks'] . '</textarea><br />';
        echo '<tr><td><input type="button" id="addrow" value="Add antibiotics"></td><td><input type="button" id="saveCulture" name="' . $paymentID . '" value="Save results" /></td></tr>';
    } elseif ($_POST['getCultureResults'] == 'culturesave') {

        $paymentID = mysql_real_escape_string($_POST['paymentID']);
        $Biotest1 = mysql_real_escape_string($_POST['Biotest1']);
        $Biotest2 = mysql_real_escape_string($_POST['Biotest2']);
        $Biotest3 = mysql_real_escape_string($_POST['Biotest3']);
        $Biotest4 = mysql_real_escape_string($_POST['Biotest4']);
        $Biotest5 = mysql_real_escape_string($_POST['Biotest5']);
        $Specimen = mysql_real_escape_string($_POST['Specimen']);
        $Organism1 = mysql_real_escape_string($_POST['Organism1']);
        $Organism2 = mysql_real_escape_string($_POST['Organism2']);
        $Organism3 = mysql_real_escape_string($_POST['Organism3']);
        $Remarks = mysql_real_escape_string($_POST['Remarks']);

        $checkExistance = mysql_query("SELECT * FROM tbl_culture_results WHERE payment_item_ID='$paymentID'");
        $numRows = mysql_num_rows($checkExistance);
        if ($numRows > 0) {

            $update = "UPDATE tbl_culture_results SET Biotest1='$Biotest1',Biotest2='$Biotest2',Biotest3='$Biotest3',Biotest4='$Biotest4',Biotest5='$Biotest5',Specimen='$Specimen',Organism1='$Organism1',Organism2='$Organism2',Organism3='$Organism3',Remarks='$Remarks' WHERE payment_item_ID='$paymentID'";
        } else {

            $update = "INSERT INTO tbl_culture_results (payment_item_ID,Biotest1,Biotest2,Biotest3,Biotest4,Biotest5,Specimen,Organism1,Organism2,Organism3,Remarks) VALUES ('$paymentID','$Biotest1','$Biotest2','$Biotest3','$Biotest4','$Biotest5','$Specimen','$Organism1','$Organism2','$Organism3','$Remarks')";
        }
        $updateResult = mysql_query($update);

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

                $checkpaymentexistancy = mysql_query("SELECT * FROM tbl_culture_antibiotics WHERE payment_item_ID='$paymentID' AND Antbiotic='$antibiotic'");
                $numRows = mysql_num_rows($checkpaymentexistancy);
                if ($numRows > 0) {
                    $update2 = "UPDATE tbl_culture_antibiotics SET Antbiotic='$antibiotic',Answerorg1='$org1',Answerorg2='$org2',Answerorg3='$org3' WHERE payment_item_ID='$paymentID' AND Antbiotic='$antibiotic'";
                } else {
                    $update2 = "INSERT INTO tbl_culture_antibiotics (payment_item_ID,Antbiotic,Answerorg1,Answerorg2,Answerorg3) VALUES ('$paymentID','$antibiotic','$org1','$org2','$org3')";
                }

                $antibioticData = mysql_query($update2);
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

<!--Culture results-->
<div id="cultureResult">
    <div id="cultureStatus"></div>
    <div id="showCulture">

    </div>
</div>

<style>
    .modificationStats:hover{
        text-decoration: underline;
        color: rgb(145,0,0);
        cursor:pointer;
    }

    .prevHistory:hover{
        text-decoration: underline;
        color: rgb(145,0,0); 
        cursor:pointer;
    }

    select {
        width:306px;
    }


</style>

<script>

    //SENDING SMS
    function SendSMS(department, receiver) {
        if (window.XMLHttpRequest) {
            sms = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
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


    $('.SubmitvalidatedResulttr').click(function () {
        $('.Submitted').attr('checked', true);
        var itemID = $('.productID').val();
        var patientID = $(this).attr('name');
        var ppil = $(this).attr('ppil');

        var parameterID, testID;
        var i = 1;
        var datastring;
        $('.Submitted').each(function () {
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
            url: 'requests/SaveTestResults.php',
            data: 'SavegeneralResult=submitResult&testresults=' + datastring + '&itemID=' + itemID + '&patientID=' + patientID + '&ppil=' + ppil,
            cache: false,
            success: function (html) {
                alert('Results submitted to doctor successfully');
                $('#showGeneral').html(html);
            }
        });
    });



</script>
<!-------culture results----->
<script>

    $(document).on('click', '.culture', function () {
        var name = $(this).attr('name');
        var payment = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: 'requests/testResults.php',
            data: 'getCultureResults=cultureview&paymentID=' + payment,
            cache: false,
            success: function (html) {
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

    $('#saveCulture').click(function () {
        var payment = $(this).attr('name');
        var resultType = $('#resultType').val();
        var organism = $('#organism').val();
        var reagent = $('#reagents').val();
        var cultureDate = $('#dateTaken').val();
        var remarks = $('#remarks').val();
        $.ajax({
            type: 'POST',
            url: 'requests/testResults.php',
            data: 'getCultureResults=culturesave&paymentID=' + payment + '&organism=' + organism + '&reagent=' + reagent + '&cultureDate=' + cultureDate + '&remarks=' + remarks + '&resultType=' + resultType,
            cache: false,
            success: function (html) {
                // alert(html);
                $('#cultureStatus').html(html);
            }
        });

    });



</script>
<script>
    function saveInformation(product_name, ppil, id) {
        var filter = "<?php echo $filter ?>";
        $('#attachFIlesLabsave_' + ppil).val(1);
        $('#attachFIlesLabvaldate_' + ppil).val('');
        $('#attachFIlesLabsubmit_' + ppil).val('');
        //alert(filter);
        $('#uploadAttachmentLab_' + ppil).ajaxSubmit({
            beforeSubmit: function () {
                $('#progressDialogStatus').show();
            },
            success: function (data) {
               
                if (parseInt(data) === 1) {
                    $('#progressStatus').hide();
                    create("default", {title: 'Success', text: 'Saved successifully'});
                    //alertMsg("Saved successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                    updateDialog(product_name, ppil, id, filter);
                }
            }, complete: function (jqXHR, textStatus) {
                $('#progressDialogStatus').hide();
            }

        });
        return false;
    }

</script>
<script>
    function SubmitInformation(product_name, ppil, id) {
        var filter = "<?php echo $filter ?>";
        $('#attachFIlesLabsave_' + ppil).val('');
        $('#attachFIlesLabvaldate_' + ppil).val('');
        $('#attachFIlesLabsubmit_' + ppil).val(1);
        //alert('#uploadAttachmentLab_'+ppil);

        if (confirm('Are you sure you want to submit ' + product_name + ' result(s)?')) {
            $('#uploadAttachmentLab_' + ppil).ajaxSubmit({
                beforeSubmit: function () {
                    $('#progressDialogStatus').show();
                },
                success: function (data) {
                    // alert(data);
                    if (parseInt(data) === 1) {
                        $('#progressStatus').hide();
                        create("default", {title: 'Success', text: 'Submitted to doctor successifully'});
                        //alertMsg("Submitted to doctor successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                        updateDialog(product_name, ppil, id, filter);
                    }
                }, complete: function (jqXHR, textStatus) {
                    $('#progressDialogStatus').hide();
                }

            });
            return false;
        }
    }

</script>
<script>
    function validateInformation(product_name, ppil, id) {
        var filter = "<?php echo $filter ?>";
        $('#attachFIlesLabsave_' + ppil).val('');
        $('#attachFIlesLabvaldate_' + ppil).val(1);
        $('#attachFIlesLabsubmit_' + ppil).val('');
        if (confirm('Are you sure you want to validate ' + product_name + ' result(s)?')) {
            $('#uploadAttachmentLab_' + ppil).ajaxSubmit({
                beforeSubmit: function () {
                    $('#progressStatus').show();
                },
                success: function (data) {
                    // alert(data);
                    if (parseInt(data) === 1) {
                        $('#progressStatus').hide();
                        //alertMsg("Validated successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                        create("default", {title: 'Success', text: 'Validated successifully'});
                        updateDialog(product_name, ppil, id, filter);
                        //document.location.reload();
                        //$('#labResults').dialog("close");
                    }
//            }else{
//                alert(data);
//            }
                }, complete: function (jqXHR, textStatus) {
                    $('#progressDialogStatus').hide();
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
        $.ajax({
            type: 'GET',
            url: 'requests/testResults.php',
            data: 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode + '&filter=' + filter,
            cache: false,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                if (html != '') {
                    $('#showLabResultsHere').html(html);
                }

            }, complete: function (jqXHR, textStatus) {
                $("#progressStatus").hide();
            }
        });
    }
</script>

<script type="text/javascript">
    $('.generalreslts').click(function () {
        var patientID = $(this).attr('patientID');
        var ppil = $(this).attr('ppil');
        var name = $(this).attr('name');
        var filter = "<?php echo $filter ?>";

        $('#showGeneral').html();
        var id = $(this).attr('id');
        // alert(id);
        $.ajax({
            type: 'GET',
            url: 'requests/testResults.php',
            data: 'generalResult=getGeneral&id=' + id + '&patientID=' + patientID + '&ppil=' + ppil + '&filter=' + filter,
            cache: false,
            success: function (html) {
                // alert(html);
                //var data =html.split('&(&^%77)&');
                //if(data[1]=='Test_Complete'){
                // if(confirm('The test for this item has completed. Close dialog to continue!')){
                //$(this).dialog('close');
                //window.location='seachpatientfromspeciemenlist.php';				  
                //exit();
                //}
                //}
                $('#showGeneral').html(html);
            }
        });
        $('#labGeneral').dialog({
            modal: true,
            width: '90%',
            minHeight: 450,
            resizable: true,
            draggable: true,
            title: name
        });
        //$("#labGeneral").dialog('option', 'title', testName);

        $('#labResults').fadeOut(100);
    });
    $('.validateResulttr').click(function () {
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
        $('.Resultvalue').each(function () {
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

        $('.Resultvalue').each(function () {
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
        // alert(datastring);
        // exit();

        $.ajax({
            type: 'POST',
            url: 'requests/SaveTestResults.php',
            data: 'SavegeneralResult=getGeneral&testresults=' + datastring + '&payment=' + payment + '&productID=' + productID + '&patientID=' + patientID + '&ppil=' + ppil,
            cache: false,
            success: function (html) {
                alert('Results saved successfully');
                $('#showGeneral').html(html);
            }
        });
    });
    $('.validateSubmittedResulttr').on('click', function () {
        $('.validated').attr('checked', true);
        var itemID = $('.productID').val();
        var patientID = $(this).attr('name');
        var parameterID, testID;
        var ppil = $(this).attr('ppil');
        var i = 1;
        var datastring;
        $('.Resultvalue').each(function () {
            var value = $(this).val();
//            if (value == '') {
//                alert('Fill all test parameter results to validate this test');
//                exit();
//            }

        });
        //alert($('.validated').length);
        $('.validated').each(function () {
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
            url: 'requests/SaveTestResults.php',
            data: 'SavegeneralResult=Validation&testresults=' + datastring + '&itemID=' + itemID + '&patientID=' + patientID + '&ppil=' + ppil,
            cache: false,
            success: function (html) {
                if (html == 'NOTSAVED') {
                    alert('Please save the information before validating');
                } else {
                    $('#showGeneral').html(html);
                }
            }
        });
    });
    $('#labGeneral').on("dialogclose", function () {

        $('#labResults').fadeIn(1000);
    });
    //Results modifications
    $('.modificationStats').click(function () {
        $('#labGeneral').fadeOut();
        var parameter = $(this).attr('id');
        var paymentID = $('.paymentID').val();
        $.ajax({
            type: 'POST',
            url: 'requests/resultModification.php',
            data: 'parameter=' + parameter + '&paymentID=' + paymentID,
            cache: false,
            success: function (html) {
                //alert('Results saved/modified successfully');
                $('#historyResults1').html(html);
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
    $('#historyResults1').on("dialogclose", function () {

        $('#labGeneral').fadeIn(1000);
    });
    $('.prevHistory').click(function () {
        var itemID = $('.productID').val();
        var patientID = $(this).attr('name');
        var parameterID = $(this).attr('id');
        var parameterName = $('.parameterName').val();
        var ppil = $(this).attr('ppil');
        $.ajax({
            type: 'POST',
            url: 'requests/resultModification.php',
            data: 'action=history&itemID=' + itemID + '&patientID=' + patientID + '&parameterID=' + parameterID + '&ppil=' + ppil,
            cache: false,
            success: function (html) {
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
    $('.culture').click(function () {
        // $('#cultureResult').dialog('close');
        var name = $(this).attr('name');
        var payment = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: 'requests/testResults.php',
            data: 'getCultureResults=cultureview&paymentID=' + payment,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#showCulture').html(html);
            }
        });
        $('#cultureResult').dialog({
            modal: true,
            width: '99%',
            minHeight: 455,
            resizable: true,
            draggable: true,
        });
        $("#cultureResult").dialog('option', 'title', name);
    });
    $('#saveCulture').click(function () {
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
        $('.antibiotc').each(function () {
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
            url: 'requests/testResults.php',
            data: 'getCultureResults=culturesave&paymentID=' + payment + '&Biotest1=' + Biotest1 + '&Biotest2=' + Biotest2 + '&Biotest3=' + Biotest3 + '&Biotest4=' + Biotest4 + '&Biotest5=' + Biotest5 + '&Specimen=' + Specimen + '&Organism1=' + Organism1 + '&Organism2=' + Organism2 + '&Organism3=' + Organism3 + '&Remarks=' + Remarks + '&datastring=' + datastring,
            cache: false,
            success: function (html) {
                $('#cultureStatus').html(html);
            }
        });
    });
    $(".Quantative").bind("keydown", function (event) {
        //alert(event.keyCode);  
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
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
                                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                        event.preventDefault();
                                    }
                                }
                            });
                    $('#addrow').click(function () {
                        var rowCount = parseInt($('#rowCount').val()) + 1;
                        var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><select class='antibiotc' id='" + rowCount + "'><option>----select----</option><option>Antibiotic1</option><option>Antibiotic2</option><option>Antibiotic3</option><option>Antibiotic4</option></select></td><td><select class='seleboxorg1' id='orgone_" + rowCount + "'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select><select class='seleboxorg2' id='orgtwo_" + rowCount + "'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select><select class='seleboxorg3' id='orgthree_" + rowCount + "'><option>---------------------Select----------------------</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select></td> <td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td> </tr>";
                        $('#CultureTbl').append(newRow);
                        document.getElementById('rowCount').value = rowCount;
                    });
//
                    $(document).on('click', '.remove', function () {

                        var id = $(this).attr('row_id');
                        //alert(id);
                        $('.tr' + id).remove().fadeOut();
                    });</script>
<script>
                    function Show_Patient_File(Registration_ID) {
                        // alert(Registration_ID);
// var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
                        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=' + Registration_ID + '&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
                        //winClose.close();
                        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

                    }

                    function popupwindow(url, title, w, h) {
                        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                        var wTop = window.screenTop ? window.screenTop : window.screenY;
                        var left = wLeft + (window.innerWidth / 2) - (w / 2);
                        var top = wTop + (window.innerHeight / 2) - (h / 2);
                        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left); //'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                        return mypopupWindow;
                    }

</script>

<script>
                    $('.ui-dialog-titlebar-close').click(function () {
                        //window.location.reload();
                    });
</script>
<script>
                    function Print_Barcode_Payment(patID, ppil, Item_ID) {
                        // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
                        var winClose = popupwindow('barcode_specimen/BCGcode39.php?Registration_ID=' + patID + '&Item_ID=' + Item_ID + '&payment_Cache_Id=' + ppil, 'Print Barcode', 330, 230);
                        //winClose.close();
                        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

                    }

                    function popupwindow(url, title, w, h) {
                        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
                        var wTop = window.screenTop ? window.screenTop : window.screenY;

                        var left = wLeft + (window.innerWidth / 2) - (w / 2);
                        var top = wTop + (window.innerHeight / 2) - (h / 2);
                        var mypopupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                        return mypopupWindow;
                    }


</script>


<!--<script>
  function openChooser(ppil){
      //alert('#'+ppil);
      $('.labfileCh_'+ppil).click();
  }
</script>
<script >
    function readFileName(filename,ppil){
        alet('here');
        $('file_'+ppil).html(filename);
//	if(input.files && input.files[0]) {
//	    var reader = new FileReader();
//		reader.onload = function(e){
//                    $('#Patient_Picture').attr('src',e.target.result).width('50%').height('70%');
//		};
//		reader.readAsDataURL(input.files[0]);
//	}
    }
//    function clearPatientPicture() {
//        document.getElementById('Patient_Picture_td').innerHTML="<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
//    }
</script>-->

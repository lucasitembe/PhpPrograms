<style>
.prevHistory:hover {
    cursor: pointer;
}

/* .button{
        font-size: 16px;
        background: white;
        border-radius: 50px;
        padding: 20px;
        box-shadow: 5px 5px 10px black;
        width: 350px;
        outline: none;
    }

    ::-webkit-file-upload-button{
        color: white;
        background: #206a5d;
        padding: 20px;
        border: none;
        border-radius: 50px;
        box-shadow: 1px 0 1px #6b4559;
        outline: none;
    } */
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
<div id="addSpeciment" style="width:100%;  overflow:hidden;display:none;">
    <div id="parameters">
        <table width=100%>
            <tr>
                <td width="40%">
                    <strong>Enter Specimen:</strong>
                </td>
                <td>
                    <input type='text' name='specimen' style='padding-left:12px; height:28px;' id='specimen'
                        required='required' placeholder='Enter Specimen'>
                </td>
                <td>
                    <button class='art-button-green' id="specmenAdd" onclick="addSpecimen1()"
                        style="margin-left:13px !important; background-color:white !important;">ADD</button>
                </td>
            </tr>
        </table>
        <div id="DelResults"></div>
        <div id="ItemParameters"></div>
    </div>

</div>

<div id="addAnatomicalSite" style="width:100%;  overflow:hidden;display:none;">
    <div id="anatomicals">
        <table width=100%>
            <tr>
                <td width="40%">
                    <strong>Enter Anatomical Site:</strong>
                </td>
                <td>
                    <input type='text' name='anatomicalName' style='padding-left:12px; height:28px;' id='anatomicalName'
                        required='required' placeholder='Enter Anatomical Site'>
                </td>
                <td>
                    <button class='art-button-green' id="anatomicalAdd" onclick="saveAnatomical()"
                        style="margin-left:13px !important; background-color:white !important;">ADD</button>
                </td>
            </tr>
        </table>
        <div id="DelResults"></div>
        <div id="ItemParameters"></div>
    </div>

</div>

<div id="addShape" style="width:100%;  overflow:hidden;display:none;">
    <div id="parameters">
        <table width=100%>
            <tr>
                <td width="40%">
                    <strong>Enter Shape:</strong>
                </td>
                <td>
                    <input type='text' name='shape' style='padding-left:12px; height:28px;' id='shape'
                        required='required' placeholder='Enter Shape'>
                </td>
                <td>
                    <button class='art-button-green' id="shapeAdd" onclick="addShape1()"
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
include('../lab_remark_and_result.php');
$valSub = '';
$remark = "";
$Validated1="no";
if(isset($_GET['patientId'])){
    $patientId = $_GET['patientId'];
}

if (isset($_GET['action'])) {
    $data = '';
    if ($_GET['action'] == 'getResult') {
        $id = $_GET['id'];
        $filterprev = $_GET['filter'];
        $filterprev_kule = mysqli_real_escape_string($conn,str_replace("'", "&#39;", $_GET['filter']));

        $filter = $_GET['filter'];
        $payment_id = $_GET['payment_id'];
        $barcode = $_GET['barcode'];

        if ($barcode == '') {
            $Query = "SELECT * FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE Registration_ID='" . $id . "' $filter GROUP BY Payment_Item_Cache_List_ID"; // AND Payment_Item_Cache_List_ID='$payment_id'
            //die($Query );
        } else {
            $Query = "SELECT * FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE Registration_ID='" . $id . "' AND payment_item_ID='" . $barcode . "' $filter  GROUP BY Payment_Item_Cache_List_ID"; // AND Payment_Item_Cache_List_ID='$payment_id'
        }

        //echo $Query;exit;

        $QueryResults = mysqli_query($conn,$Query) or die(mysqli_error($conn));
        ;
        echo "<center><table class='' style='width:100%'>"
        . "";
        echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%' style='text-align:left;'>S/N</th>
                <th width='1%' style='text-align:left;'></th>
                <th width='30%' style='text-align:left'>Test Name</th>
                <th width='25%' style='text-align:left'>Doctor's Notes</th>
                <th width='10%' style='text-align:left'>Specimen Taken</th>
                <th width='10%' style='text-align:left'>Specimen Submitted</th>
                <th width='15%' style='text-align:left'>Collected By</th>
                <th width='30%' style='text-align:left'>Overall Remarks && Result</th>
                <th width='1%' style='text-align:left'>Attachment</th>
                <th width='1%' style='text-align:left'>Results</th>
      </tr>";
        $i = 1;


//echo mysqli_num_rows($QueryResults);
        $datamsg = mysqli_num_rows($QueryResults);
        $couterSubVal = 0;

        $item_with_no_parameter = 0;
        $checkIfAllowedValidate = false;
        if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
            $checkIfAllowedValidate = true;
        }

        while ($row = mysqli_fetch_assoc($QueryResults)) {
            $testIDUnic = $row['test_result_ID'];
            $itemTime = $row['Payment_Item_Cache_List_ID'];
            // echo "SELECT * from tbl_specimen_results WHERE payment_item_ID='$itemTime' GROUP BY payment_item_ID ORDER BY `payment_item_ID` ASC";
            $queryspecimen = mysqli_query($conn,"SELECT sr.TimeCollected, em.Employee_Name from tbl_specimen_results sr, tbl_employee em WHERE sr.payment_item_ID='$itemTime' AND em.Employee_ID = sr.specimen_results_Employee_ID GROUP BY sr.payment_item_ID ORDER BY sr.payment_item_ID ASC");
            while($details = mysqli_fetch_assoc($queryspecimen)){
                $TimeCollected = $details['TimeCollected'];
                $Employee_Name_Collected = $details['Employee_Name'];
            }

            $RS = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
            $rowSt = mysqli_fetch_assoc($RS);
            $Submitted = $rowSt['Submitted'];
            $Validated = $rowSt['Validated'];

            //echo 'submitted and validated';

            if ($Submitted == 'Yes' && $Validated == 'Yes') {
                $couterSubVal +=1;
            } else {

                $resultSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='" . $row['Item_ID'] . "'");
                $numrows = mysqli_num_rows($resultSaved);


                //Count parameters Savedselect
                $numparameterSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$itemTime'");
                $numSavedParameters = mysqli_num_rows($numparameterSaved);

                //die($numSavedParameters.' '."SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$itemTime'");
                //Count parameters Submitted
                $numparameterSubmitted = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Submitted='Yes' AND payment_item_ID='$itemTime'");
                $numSubmittedParameters = mysqli_num_rows($numparameterSubmitted);


//Count parameters Validated
                $numparameterValidated = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Validated='Yes' AND payment_item_ID='$itemTime'");
                $numValidatedParameters = mysqli_num_rows($numparameterValidated);

                //echo "SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$itemTime' AND result <>''";
                //Count parameters None empty results
                $numparameterNoneempty = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$itemTime' AND result <>''")or die(mysqli_error($conn));
                $numNoneemptyParameters = mysqli_num_rows($numparameterNoneempty);


                //$data .="<form id='uploadAttachmentLab_".$row['Payment_Item_Cache_List_ID']."' action='attachmentLabResult.php?item_id=".$row['Payment_Item_Cache_List_ID']."' method='post' enctype='multipart/form-data'>"
                //  . "<input type='hidden' name='Registration_id' value='".$id."'>";
                $data .= "<tr>";
                $data .= "<td>" . $i++ . "</td>";
                $data .= "<td style='width:3%;'><input type='checkbox' class='Patient_Payment_Item_List_ID_n_status' name='cPatient_Payment_Item_List_ID_n_status[]' id='" . $row['Payment_Item_Cache_List_ID'] . "'></td>";
                $data .= "<td><input type='hidden' name='Payment_Item_Cache_List_ID[]' value='" . $row['Payment_Item_Cache_List_ID'] . "'>" . $row['Product_Name'] . "</td>";
                $data .= "<td><textarea class='doctorNotes' readonly='true' id='" . $row['Payment_Item_Cache_List_ID'] . "'>" . $row['Doctor_Comment'] . "</textarea></td>";
                $data .= "<td>" . $TimeCollected . "</td>";
                $data .= "<td>" . $row['TimeSubmitted'] . "</td>";
                $data .= "<td>" . $Employee_Name_Collected . "</td>";
                $data .= "<td  colspan='2'>"
                        . "<form id='uploadAttachmentLab_' action='attachmentLabResult.php?item_id=" . $row['Payment_Item_Cache_List_ID'] . "' method='post' enctype='multipart/form-data'>"
                        . "<input type='hidden' name='Registration_id' value='" . $id . "'>";

                        //    <input type='hidden' name='ppilc' class='art-button-green' value='" . $row['Payment_Item_Cache_List_ID'] . "'>
                        //    <input type='hidden' name='attachFIlesLabvaldate'   value='' id='attachFIlesLabvaldate_" . $row['Payment_Item_Cache_List_ID'] . "' />
                        //    <input type='hidden' name='attachFIlesLabsubmit'  value='' id='attachFIlesLabsubmit_" . $row['Payment_Item_Cache_List_ID'] . "' />
                        //    <input type='hidden' name='attachFIlesLabsave'  value='' id='attachFIlesLabsave_" . $row['Payment_Item_Cache_List_ID'] . "' />

                $data .="  <input type='hidden' name='cache_ID' class = 'cache_ID' value='' id = 'cache_ID' />
                           <input type='hidden' name='attachFIlesLabsave_' class = 'attachFIlesLabsave_' value='' id = 'attachFIlesLabsave_' />
                           <input type='hidden' name='attachFIlesLabsubmit_' class = 'attachFIlesLabsubmit_' value='' id = 'attachFIlesLabsubmit_' />
                           <input type='hidden' name='attachFIlesLabvaldate_' class = 'attachFIlesLabvaldate_' value='' id = 'attachFIlesLabvaldate_' />
                           "?>
<?php
                        if(!empty(getRemarkDescriptionValue($row['Payment_Item_Cache_List_ID'])[0])){
                            $data .= "<textarea style='width:100%;display:inline;margin:0px' class='overallRemarks' name='overallRemarks_" . $row['Payment_Item_Cache_List_ID'] . "' id='overallRemarks_" . $row['Payment_Item_Cache_List_ID'] . "'> ".
                            getRemarkDescriptionValue($row['Payment_Item_Cache_List_ID'])[0]
                             ."</textarea>";
                        }
                        else{
                            $data .= "<textarea style='width:100%;display:inline;margin:0px' class='overallRemarks' name='overallRemarks_" . $row['Payment_Item_Cache_List_ID'] . "' id='overallRemarks_" . $row['Payment_Item_Cache_List_ID'] . "'> "
                             ."</textarea>";
                        }
                        ?>
<?php
                      if(!empty(getResult($row['Payment_Item_Cache_List_ID']))){
                        $data .= "<select style='width:100%;display:inline;margin:0px;padding:4px' name='otherResult_" . $row['Payment_Item_Cache_List_ID'] . "' id='otherResult_" . $row['Payment_Item_Cache_List_ID'] . "'>
                                <option selected value='".getResult($row['Payment_Item_Cache_List_ID'])."'>".getResult($row['Payment_Item_Cache_List_ID'])."</option>
                                <option value='positive'>Positive</option>
                                <option value='negative'>Negative</option>
                                <option value='negative*'>Negative*</option>
                                <option value='normal'>Normal</option>
                                <option value='abnormal'>Abnormal</option>
                                <option value='high'>High</option>
                                <option value='low'>Low</option>
                           </select>";
                      } else{
                        $data .= "<select style='width:100%;display:inline;margin:0px;padding:4px' id='otherResult_" . $row['Payment_Item_Cache_List_ID'] . "' name='otherResult_" . $row['Payment_Item_Cache_List_ID'] . "'>
                                <option value=''>Select result</option>
                                <option value='positive'>Positive</option>
                                <option value='negative'>Negative</option>
                                <option value='negative*'>Negative*</option>
                                <option value='normal'>Normal</option>
                                <option value='abnormal'>Abnormal</option>
                                <option value='high'>High</option>
                                <option value='low'>Low</option>
                           </select>";

                      }
                           ?>

<?php
                    $data .= "</form>"
                        . "</td>";
                        // }
                        ?>
<?php
                //Check if it has parameter
                //tbl_tests_parameters(ref_parameter_ID,ref_item_ID)
                $q_chck = mysqli_query($conn,"SELECT ref_item_ID FROM tbl_tests_parameters WHERE ref_item_ID='" . $row['Item_ID'] . "'") or die(mysqli_error($conn));

                $check_par = false;
                //Check for paramenter settings

                if ($numSavedParameters == 0) {
                    if (mysqli_num_rows($q_chck) > 0) {
                        $valSub = "<td style='width:80%'>
                                <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />
                              <input type='button'  class='Submitresult' disabled='disabled' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                    } else {

                        if ($_SESSION['hospitalConsultaioninfo']['enb_lab_wt_no_par'] == '1') {


                            //check if parameter default_parameter is availlable
                            $checkpar = mysqli_query($conn,"SELECT Parameter_Name FROM tbl_parameters WHERE Parameter_Name ='default_parameter'") or die(mysqli_error($conn));

                            if (mysqli_num_rows($checkpar) == 0) {
                                $insert = mysqli_query($conn,"INSERT INTO tbl_parameters (Parameter_Name,unit_of_measure,lower_value,operator,higher_value,result_type) VALUES ('default_parameter','','','','','Qualitative')") or die(mysqli_error($conn));
                            }
                            //add default parameter call it default_parameter
                            $getDefParaID = mysqli_query($conn,"SELECT parameter_ID FROM tbl_parameters WHERE Parameter_Name ='default_parameter'") or die(mysqli_error($conn));

                            $parameter_ID = mysqli_fetch_assoc($getDefParaID)['parameter_ID'];

                            $queryResult = mysqli_query($conn,"INSERT INTO tbl_tests_parameters VALUES('$parameter_ID','" . $row['Item_ID'] . "')") or die(mysqli_error($conn));

                            //$valSub='<td>'.$parameter_ID.'</td>';


                            $valSub = "<td style='width:80%'>
                            <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />
                              <input type='button'  class='Submitresult' disabled='disabled' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                        } else {
                            $valSub = "<td style='width:80%'>
                                    <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
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
                                <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save/update' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                              if ($checkIfAllowedValidate) {
                                 $valSub .="  
                                <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                                <input type='button'  class='validatesendresult' style='width:100%;padding:0 0px'  id='fileupload' value='Validate' onclick=\"validateInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                               }
                            $valSub .="
                            <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                            <input type='button'  class='Submitresult' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";

                    $valSub .=" </td>";
                } elseif ($numNoneemptyParameters < $numrows) {
                    $valSub = "<td style='width:80%'>
                                <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                              <input type='button'  class='saveresult' id='fileuploadsave' style='width:auto;' value='Save/update' onclick=\"saveInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                              if ($checkIfAllowedValidate) {
                             $valSub .="  
                        <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                             
                             <input type='button'  class='validatesendresult' style='width:100%;padding:0 0px'  id='fileupload' value='Validate' onclick=\"validateInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";
                            }
                             $valSub .="
                        <textarea style='display: none;' id='filter'>".$filterprev."</textarea>
                        <input type='button'  class='Submitresult' id='fileuploadsubmit' style='width:100%;padding:0 0px' value='Send' onclick=\"SubmitInformation('" . $row['Product_Name'] . "'," . $row['Payment_Item_Cache_List_ID'] . "," . $id . ")\" />";

                    $valSub .=" </td>";
                }
                $Product_Name=$row['Product_Name'];
                $Item_ID=$row['Item_ID'];
                 $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
                $data .= "<td>
	       <table border='0' width='100%'>
                  <tr>
                   <td>
                   <input type='button' class='generalreslts' name='" . $row['Product_Name'] . "' ppil='" . $row['Payment_Item_Cache_List_ID'] . "' id='" . $row['Item_ID'] . "' patientID='$id' value='Results'>";
                   $received_status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sr.received_status FROM tbl_specimen_results sr, tbl_item_list_cache ilc WHERE sr.payment_item_ID = '$Payment_Item_Cache_List_ID' AND ilc.Payment_Item_Cache_List_ID = sr.payment_item_ID AND Biopsy_ID > 0"))['received_status'];
                   if($received_status == 'received'){
                          $data .="&nbsp;&nbsp;&nbsp;<input type='button' onclick='Open_Biopsy_Results(\"$Product_Name\",\"$Payment_Item_Cache_List_ID\",\"$id\")'  value='    Biopsy    '>";
                    }

                      

                   $data .= "
	           </td>
                   <td>
                  	<input type='button' data-id='".$Item_ID."' class='culture' name='" . $row['Product_Name'] . "' id='" . $row['payment_item_ID'] . "' value='micro-biology' />
                        <input type='button' name='Print_Barcode' value='Barcode' onclick='Print_Barcode_Payment(" . $id . ", " . $row['Payment_Item_Cache_List_ID'] . "," . $row['Item_ID'] . ")' class='Print_Barcode'>                                        </td>

	           </td>

                     " . $valSub . "

                  </tr>
                  <tr>
                        <td>
                            <input type='button' onclick='get_result_from_integrated_machine(\"$Product_Name\",\"$Payment_Item_Cache_List_ID\")' value='Result From Intergration'>
                        </td>
                        <td>
                            <a href='patient_sent_to_cross_match.php?patient_id=" . $id . "' target='_blank'>
                            <input type='button' value='Cross Match'></a>
                        </td>
                        <td>
                        <input type='button' onclick='tb_result_form(\"$Product_Name\",\"$Payment_Item_Cache_List_ID\",\"$id\",\"$Employee_ID\")' value='TB Result Form'>
                        </td>
                 </tr>
                </table>
	    </td>

            </tr>
             </form>
	";
            }
        }

        $data.= "<tr>
               <td colspan='10' style='text-align:center'>
               <label style='margin-right: 50px;'><input type='checkbox' name='check' id='mark_all'>  Select All Test</label>

               <a href='previewSampledTests.php?filterprev=".$filterprev_kule."&patient_id=" . $id . "' id='previewSampleTest' class='art-button-green' target='_blank'>SAMPLE COLLECTION(s) PREVIEW / PRINT</a></center>
               <a href='previewSampledTests_separate.php?filterprev=".$filterprev_kule."&patient_id=" . $id . "' id='previewSampleTest' class='art-button hide' target='_blank'>SAMPLE COLLECTION(s) PREVIEW / PRINT SEPARATE </a></center>

                <input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File(" . $id . ")' class='art-button-green'/>
                <input type='button' class='hide' name='patient_file' id='patient_file' value='COMBINE SAMPLE' onclick='combine_sample(" . $id . ")' class='art-button-green'/>
                <input type='button' class='hide' name='patient_file' id='patient_file' value='SEND SMS TO PATIENT' onclick='send_mesg(" . $id . ")' class='art-button-green'/>
                <input style='width:20%;display:inline;margin:0px; background: #dedede; border-radius: 2px; height: 25px; color: #000;' class='button' type='file' name='labfile_'  multiple='true' id='labfile_'>
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
    $QueryResults = "SELECT test_result_ID,parameter_ID FROM tbl_item_list_cache
	     INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID
		 INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID
		 INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID
		 JOIN tbl_parameters ON parameter_ID=ref_parameter_ID
		 WHERE Item_ID='" . $id . "' AND Registration_ID='" . $patientID . "' AND payment_item_ID='" . $ppil . "'";

    //die($QueryResults);
    $result = mysqli_query($conn,$QueryResults);
    $numRows = mysqli_num_rows($result);
    if ($numRows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $resultID = $row['test_result_ID'];
            $parameterID = $row['parameter_ID'];
            $Insert = "INSERT INTO tbl_tests_parameters_results (ref_test_result_ID,parameter,result,modified,Validated,Submitted,status) VALUES ('$resultID','$parameterID','','','','','')";
            $Query = mysqli_query($conn,$Insert);
        }

        # check if item is in sorted mode
        $check_count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS num FROM tbl_items WHERE Item_ID = $id AND sort_by_number = 'yes'"))['num'];
        if($check_count > 0){
            $selectQuery = "SELECT parameter_ID,test_result_ID,payment_item_ID,result_type,result,Parameter_Name,p.unit_of_measure,modified,Validated,Submitted,lower_value,operator,higher_value,lower_value,Saved from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND
            ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $id . "'  AND Registration_ID='" . $patientID . "' AND Validated != 'Yes' AND tr.payment_item_ID='" . $ppil . "'  GROUP BY PARAMETER_NAME ORDER BY tp.arrangement";
        }else{
            $selectQuery = "SELECT parameter_ID,test_result_ID,payment_item_ID,result_type,result,Parameter_Name,p.unit_of_measure,modified,Validated,Submitted,lower_value,operator,higher_value,lower_value,Saved from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND
            ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $id . "'  AND Registration_ID='" . $patientID . "' AND Validated != 'Yes' AND tr.payment_item_ID='" . $ppil . "'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
        }

        $data = '';
        //die($selectQuery);
        $GetResults = mysqli_query($conn,$selectQuery) or die(mysqli_error($conn));
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

        $datamsg = mysqli_num_rows($GetResults);
        $operators = array('=', '>', '<', '>=', '<=');
        while ($row2 = mysqli_fetch_assoc($GetResults)) {
            $testID = $row2['test_result_ID'];
            $paymentID = $row2['payment_item_ID'];
            $input = '';

            if ($row2['result_type'] == 'Quantitative') {
                $parameter_ID=$row2['parameter_ID'];
                $input = '<input type="text" class="Resultvalue Quantative" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '" placeholder="Numeric values only">';
            } else if ($row2['result_type'] == 'Qualitative') {
                $input = '<input type="text" class="Resultvalue Qualitative' . $row2['parameter_ID'] . '" id="'.$row2['parameter_ID'].'" value="' . $row2['result'] . '">';
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
            $Queryhistory = mysqli_query($conn,$historyQ);
            $myrows = mysqli_num_rows($Queryhistory);
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
        $data .= 'Remarks:<textarea id="Remarks_new" style="width:50%;padding-left:5px;margin-top:5px"></textarea>';
        $data .= '<div></div>';
        //die($datamsg);
        if ($datamsg > 0) {
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

                $data .= '<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor"> ';
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
        $Item_ID = $_POST['Item_ID'];
       
        // include "../laboratory/views/macro_micro_results.php";

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

<!--Culture results-->
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

select {
    width: 306px;
}
</style>

<script>
function send_patient_culture_result(payment_id) {
    $.ajax({
        type: 'POST',
        url: 'ajax_send_culture.php',
        data: {
            payment_id: payment_id
        },
        success: function(data) {
            //             buttonsend(payment_id);
            //             get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID)
            alert("successful data sent");
            $("#saveCulture23").hide();
            //             $("#saveCulture7").show();
        }
    });
}

function validate_patient_culture(payment_id) {
    //     alert(payment_id);
    $.ajax({
        type: 'POST',
        url: 'ajax_validation_culture.php',
        data: {
            payment_id: payment_id
        },
        success: function(data) {
            //             get_result_from_integrated_machine(Product_Name,Payment_Item_Cache_List_ID)
            alert("successful data validated");
            $("#saveCulture7").hide()
            //             buttonsend(payment_id);
        }
    });
}

function preview_lab_result(payment_id) {
    window.open("preview_micro_biology_result.php?payment_id=" + payment_id, "_blank");
}

function addspecimen() {
    $("#addSpeciment").dialog("option", "title");
    $("#anatomicalAdd").attr("onClick", "addspecimen1()");
    $("#Cached").html('');
    $("#addSpeciment").dialog("open");

}

function addAnatomicalSite() {
    $("#addAnatomicalSite").dialog("option", "title");
    $("#specmenAdd").attr("onClick", "saveAnatomical()");
    $("#Cached").html('');
    $("#addAnatomicalSite").dialog("open");

}

function addnewShape() {
    $("#addShape").dialog("option", "title");
    $("#shapeAdd").attr("onClick", "addshape1()");
    $("#Cached").html('');
    $("#addShape").dialog("open");

}

function addshape1() {
    var ShapeName = document.getElementById('shape').value;
    if (ShapeName == '') {
        alert("Enter Shape name");
        exit();
    } else {
        document.getElementById('shape').value = "";
        $.ajax({
            type: 'GET',
            url: 'requests/AddSpecimen.php',
            data: {
                ShapeName: ShapeName
            },
            success: function(result) {
                $(".multipleSelect").html(result);
                alert("Shape Added  Successfully");
            }
        });
    }
}

function addspecimen1() {
    var Specimen = document.getElementById('specimen').value;
    if (Specimen == '') {
        alert("Enter Specimen name");
        exit();
    } else {
        $.ajax({
            type: 'GET',
            url: 'requests/AddSpecimen.php',
            data: {
                Specimen: Specimen
            },
            success: function(result) {
                alert("Specimen Added Successfully");
                $(".seleboxorg3").html(result);
            }
        });
    }
}

function saveAnatomical() {
    var anatomical = document.getElementById('anatomicalName').value;
    if (anatomical == '') {
        alert("Enter Anatomical site");
        exit();
    } else {
        $.ajax({
            type: 'GET',
            url: './laboratory/services/save_anatomical.php',
            data: {
                anatomical: anatomical
            },
            success: function(result) {
                alert("Anatomical Site Added Successfully");
                $(".anatomical").html(result);
            }
        });
    }
}

function removeSpecimen() {
    var Specimen = $(".seleboxorg3").val();
    if (Specimen == '') {
        alert("Please Select Specimen name");
        exit();
    } else {
        $.ajax({
            type: 'GET',
            url: 'requests/AddSpecimen.php',
            data: {
                Specimen_ID: Specimen
            },
            success: function(result) {
                $(".seleboxorg3").html(result);
                alert("Specimen Removed Successfull.");
            }
        });
    }
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
            $('#Cached2').html(result);
            getdropdown1();
            alert("success data saved");
            document.getElementById('Organism').value = "";
            $("#new_organism_1").select2();
        },
        error: function(x, y, z) {
            console.log(x + y + z);
        }
    });
}
// parameter
function getdropdown1() {
    if (window.XMLHttpRequest) {
        show = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        show = new ActiveXObject('Micrsoft.XMLHTTP');
        show.overrideMimeType('text/xml');
    }
    show.onreadystatechange = AJAXStat;
    show.open('GET', 'requests/Addorganism.php', true);
    show.send();
    $(".select").select2();

    function AJAXStat() {
        var respond = show.responseText;
        document.getElementById('Cached').innerHTML = respond;
    }
}

function savealldata(paymentID) {
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
    var selected = [];
    $('#multipleSelect :selected').each(function() {
        selected[$(this).val()] = $(this).text();
    });


    if (antibiotic == '') {
        alert("Enter Antibiotics");
        exit();
    }

    if (sensitive == '') {
        alert("Enter Sensitive");
        exit();
    }

    // if (biometrict_test == '') {
    //     alert("Enter Biochemical test");
    //     exit();
    // }
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
    var desease = $('#multipleSelect').val();
    if (desease == '') {
        alert("Enter desease");
        exit();
    }
    var stein = $('#stein').val();
    if (stein == '') {
        alert("Enter stein");
        exit();
    }
    // var specimen = $("#specimen").val();
    // if (specimen == '') {
    //     alert("Enter specimen");
    //     exit();
    // }
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
        url: 'savealldatainculture.php',
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
            alert("success data saved");
            $("#saveCulture25").hide();

            //                console.log(result);
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
        url: 'buttonsendvalidate.php',
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

function buttonsend(paymentID) {
    $.ajax({
        type: 'POST',
        url: 'buttonsend2.php',
        data: {
            paymentID: paymentID
        },
        success: function(data) {
            console.log(data);
            $("#buttonva\n\
     ").html(data);
        },
        error: function(x, y, z) {
            //                console.log("gtdfyj"+x+y+z);
        }
    });

}


function showbutton(objButton) {
    var a = objButton.value;
    if (a == 'Validate')
        $("#sub_section").show();

}


$(document).ready(function() {
    $("#showdata").dialog({
        autoOpen: false,
        width: '60%',
        title: 'New Organism',
        modal: true,
        position: 'center'
    });
    $("#addSpeciment").dialog({
        autoOpen: false,
        width: '60%',
        title: 'New Specimen',
        modal: true,
        position: 'center'
    });

    $("#addAnatomicalSite").dialog({
        autoOpen: false,
        width: '60%',
        title: 'New Anatomical Site',
        modal: true,
        position: 'center'
    });
    $("#addShape").dialog({
        autoOpen: false,
        width: '60%',
        title: 'New Shape',
        modal: true,
        position: 'center'
    });
    //alert(firstSelected);
});

//SENDING SMS
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
// function SubmitInformation(product_name, ppil, id) {
//     var filter = "<?php echo $filter ?>";
//     $('#attachFIlesLabsave_').val('');
//     $('#attachFIlesLabvaldate_').val('');
//     $('#attachFIlesLabsubmit_').val(1);

//     var selectedID = [];
//     Comments = [];
//     otherResults = [];
//     $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
//         selectedID.push(this.id);
//         Comments.push($('#overallRemarks_'+this.id).val());
//         otherResults.push($('#otherResult_'+this.id).val());
//     });


//     // exit();
//     if(selectedID.length == 0){
//         alert("Please Select Tests To Upload Results");
//         exit();
//     }
//     //alert('#uploadAttachmentLab_'+ppil);
//     document.getElementById("cache_ID").value = selectedID;
//     document.getElementById("overallRemarks_").value = Comments;
//     document.getElementById("otherResult_").value = otherResults;

//     if (confirm('Are you sure you want to submit Selected result(s)?')) {
//         $('#uploadAttachmentLab_').ajaxSubmit({
//             beforeSubmit: function () {
//                 $('#progressDialogStatus').show();
//             },
//             success: function (data) {
//                 // alert(data);
//                 if (data.includes("1")) {
//                     test_connection_to_ehms_mr_online(product_name, ppil, id, filter);
//                 }
//             }, complete: function (jqXHR, textStatus) {
//                 $('#progressDialogStatus').hide();
//             }

//         });
//         return false;
//     }
// }
function test_connection_to_ehms_mr_online(product_name, ppil, id, filter) {
    check_for_afyacard_configuration(product_name, ppil, id, filter);

}

function send_patient_result_to_ehms_mr_online(product_name, ppil, id, filter) {
    var Registration_ID = id;
    var Payment_Item_Cache_List_ID = ppil;
    alert(product_name);
    exit();
    $.ajax({
        type: 'POST',
        url: 'ajax_send_patient_result_to_ehms_mr_online.php',
        data: {
            Payment_Item_Cache_List_ID: Payment_Item_Cache_List_ID,
            Registration_ID: Registration_ID
        },
        success: function(data) {
            console.log(data);
            //alert(data);

            //                $('#progressStatus').hide();
            //                create("default", {title: 'Success', text: 'Submitted to doctor successifully'});
            //                //alertMsg("Submitted to doctor successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
            //                updateDialog(product_name, ppil, id, filter);
        },
        complete: function() {
            //alert("complete");
        },
        error: function(x, y, z) {
            alert(x + y + z);
        }
    });
}

function check_for_afyacard_configuration(product_name, ppil, id, filter) {
    $.ajax({
        type: 'POST',
        url: 'ajax_check_if_afya_card_config_is_on.php',
        data: {
            function_module: "afya_card_module"
        },
        success: function(data) {
            // alert(data)
            if (data == "enabled") {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_test_connection_to_ehms_mr_online.php',
                    data: {
                        check_server_connection: "check"
                    },
                    success: function(data) {

                        if (data == "connection_available") {

                            send_patient_result_to_ehms_mr_online(product_name, ppil, id,
                                filter)
                        } else {
                            $('#progressStatus').hide();
                            create("default", {
                                title: 'Success',
                                text: 'Submitted to doctor successifully'
                            });
                            //alertMsg("Submitted to doctor successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                            updateDialog(product_name, ppil, id, filter);
                        }
                    }
                });
            } else {
                $('#progressStatus').hide();
                create("default", {
                    title: 'Success',
                    text: 'Submitted to doctor successifully'
                });
                //alertMsg("Submitted to doctor successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                updateDialog(product_name, ppil, id, filter);
            }
        }
    });
}
</script>
<script>
//     function validateInformation(product_name, ppil, id) {
//         var filter = "<?php echo $filter ?>";
//         $('#attachFIlesLabsave_').val('');
//         $('#attachFIlesLabvaldate_').val(1);
//         $('#attachFIlesLabsubmit_').val('');
//         var selectedID = [];
//         Comments = [];
//         otherResults = [];
//         $(':checkbox[name="cPatient_Payment_Item_List_ID_n_status[]"]:checked').each(function () {
//             selectedID.push(this.id);
//             Comments.push($('#overallRemarks_'+this.id).val());
//             otherResults.push($('#otherResult_'+this.id).val());
//         });

//         if(selectedID.length == 0){
//             alert("Please Select Tests To Validate Results");
//             exit();
//         }
//         document.getElementById("cache_ID").value = selectedID;
//         document.getElementById("overallRemarks_").value = Comments;
//         document.getElementById("otherResult_").value = otherResults;
//         if (confirm('Are you sure you want to validate Selected result(s)?')) {
//             $('#uploadAttachmentLab_').ajaxSubmit({
//                 beforeSubmit: function () {
//                     $('#progressStatus').show();
//                 },
//                 success: function (data) {
//                     // alert(data);
//                     if (data.includes("1")) {
//                         $('#progressStatus').hide();
//                         //alertMsg("Validated successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
//                         create("default", {title: 'Success', text: 'Validated successifully'});
//                         updateDialog(product_name, ppil, id, filter);
//                         //document.location.reload();
//                         //$('#labResults').dialog("close");
//                     }
// //            }else{
// //                alert(data);
// //            }
//                 }, complete: function (jqXHR, textStatus) {
//                     $('#progressDialogStatus').hide();
//                 }

//             });
//             return false;
//         }
// }
</script>
<script>
function updateDialog(product_name, ppil, id, filter) {
    var barcode = '';
    var id = id;
    var payment_id = ppil;
    $.ajax({
        type: 'GET',
        url: 'requests/testResults.php',
        data: 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode + '&filter=' +
            filter,
        cache: false,
        beforeSend: function(xhr) {
            $("#progressStatus").show();
        },
        success: function(html) {
            if (html != '') {
                $('#showLabResultsHere').html(html);
            }

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

    $('#showGeneral').html();
    var id = $(this).attr('id');
    // alert(id);
    $.ajax({
        type: 'GET',
        url: 'requests/testResults.php',
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
    var remarks = $('#Remarks_new').val();
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
    // alert(datastring);
    // exit();

    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestResults.php',
        data: 'SavegeneralResult=getGeneral&testresults=' + datastring + '&payment=' + payment +
            '&productID=' + productID + '&patientID=' + patientID + '&ppil=' + ppil + '$remarks=' +
            remarks,
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

$(document).on('click', '.SubmitvalidatedResulttr', function(e) {
    e.stopImmediatePropagation();
    $('.Submitted').attr('checked', true);
    var itemID = $('.productID').val();
    var patientID = $(this).attr('name');
    var ppil = $(this).attr('ppil');
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
        url: 'requests/SaveTestResults.php',
        data: 'SavegeneralResult=submitResult&testresults=' + datastring + '&itemID=' + itemID +
            '&patientID=' + patientID + '&ppil=' + ppil,
        cache: false,
        beforeSend: function(xhr) {
            $("#progressStatusResults").show();
        },
        success: function(html) {
            alert('Results submitted to doctor successfully');
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
        //            if (value == '') {
        //                alert('Fill all test parameter results to validate this test');
        //                exit();
        //            }

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
        url: 'requests/SaveTestResults.php',
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

$(document).on('click', '.culture', function(e) {
    // $('#cultureResult').dialog('close');
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
        width: '99%',
        minHeight: 455,
        resizable: true,
        draggable: true,
    });
    $("#cultureResult").dialog('option', 'title', name);
});

$(document).on('click', '#saveCulture', function(e) {
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
        url: 'requests/testResults.php',
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
</script>
<script>
function Show_Patient_File(Registration_ID) {
    // alert(Registration_ID);
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
$('.ui-dialog-titlebar-close').click(function() {
    //window.location.reload();
});
</script>
<script>
function Print_Barcode_Payment(patID, ppil, Item_ID) {
    // var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
    var winClose = popupwindow('barcode_specimen/BCGcode39.php?Registration_ID=' + patID + '&Item_ID=' + Item_ID +
        '&payment_Cache_Id=' + ppil, 'Print Barcode', 330, 230);
    //winClose.close();
    //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

}

function popupwindow(url, title, w, h) {
    var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
    var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow = window.open(url, title,
        'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' +
        w + ', height=' + h + ', top=' + top + ', left=' + left);

    return mypopupWindow;
}



function send_mesg(id) {
    alert("Please Contact System Administrator for SMS Payments");
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
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script>
$(document).ready(function(e) {
    //        $("#saveCulture7").hide();
    $("select").select2();
    //        $("select").css('color','black');
});

$("#mark_all").change(function(e) {
    e.preventDefault();
    if (this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;

        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;
        });
    }

});
</script>
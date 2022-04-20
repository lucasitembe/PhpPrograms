<style>
    .prevHistory:hover{
        cursor:pointer;
    }
</style>
<?php
@session_start();
include("../includes/connection.php");
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$id = '';
//Save General Results here
$patientID = (isset($_POST['patientID'])) ? $_POST['patientID'] : '';

$select_phone = mysqli_query($conn,"SELECT Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$patientID'") or die(mysqli_error($conn));
$ph = mysqli_fetch_assoc($select_phone);
$Receiver = $ph['Phone_Number'];

$operators = array('=', '>', '<', '>=', '<=', '-');


if (isset($_POST['SavegeneralResult'])) {
    if ($_POST['SavegeneralResult'] == 'getGeneral') {
        $patientID = $_POST['patientID'];
        $ppil = $_POST['ppil'];
        //die($patientID);
        $payment = $_POST['payment'];
        $values = $_POST['testresults'];
        $results = explode('$>', $values);
        $statusCheck = '';
        //die( $values);
        // echo '<pre>';
        // print_r($results);
        // echo '</pre>';exit;
        foreach ($results as $value) {

            $data = explode("#@", $value);
            $id = $data[0];
            $val = $data[1];
            $checkIfValidated = "SELECT * FROM tbl_tests_parameters_results WHERE ref_test_result_ID='" . $payment . "' AND parameter='" . $id . "'";
            $QueryAnswer = mysqli_query($conn,$checkIfValidated);
            while ($GetAnswer = mysqli_fetch_assoc($QueryAnswer)) {
                $currentResult = $GetAnswer['result'];
                if (($currentResult == $val) && ($GetAnswer['Validated'] == "Yes")) {
                    
                } elseif (($currentResult !== $val) && ($GetAnswer['Validated'] == "Yes")) {
                    $update = "UPDATE tbl_tests_parameters_results SET result='" . $val . "',Saved='Yes',modified='Yes' WHERE ref_test_result_ID='" . $payment . "' AND parameter='" . $id . "'";
                    $runQuery = mysqli_query($conn,$update);
                    if ($runQuery) {
                        $insertModification = "INSERT INTO tbl_test_result_modification (test_result_ID,Parameter,employee_ID,result,timeModified) VALUES ('" . $GetAnswer['ref_test_result_ID'] . "','" . $GetAnswer['parameter'] . "','$Employee_ID','$currentResult',NOW())";
                        $modificationQuery = mysqli_query($conn,$insertModification);
                    }
                } elseif (($currentResult !== $val) && ($GetAnswer['Validated'] == "")) {
                    $update = "UPDATE tbl_tests_parameters_results SET result='" . $val . "',Saved='Yes',SavedBy='" . $Employee_ID . "' WHERE ref_test_result_ID='" . $payment . "' AND parameter='" . $id . "'";
                    //die($update);
                    $runQuery = mysqli_query($conn,$update);
                }
            }
        }

        $sn = 1;
        $productID = $_POST['productID'];
         $selectQuery = " select * from tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr 
		where ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID AND 
	          ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND  
			  ilc.Item_ID = tp.ref_item_ID 
			  and p.parameter_ID = tp.ref_parameter_ID and 
			  tpr.parameter = p.parameter_ID and 
			  tr.test_result_ID = tpr.ref_test_result_ID and 
			  ilc.Item_ID = '" . $productID . "' AND 
			  Registration_ID='" . $patientID . "' AND 
			  tr.payment_item_ID='" . $ppil . "' 
			  GROUP BY tpr.ref_test_result_ID,PARAMETER_NAME ORDER BY PARAMETER_NAME"; 
	  
	   /* $selectQuery = "SELECT * FROM tbl_item_list_cache 
	     INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
		 INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID 
		 INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
		 JOIN tbl_parameters ON parameter_ID=ref_parameter_ID  
		 WHERE Item_ID='" . $productID . "' AND Registration_ID='" . $patientID . "' AND payment_item_ID='" . $ppil . "'"; */
		 
		  $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $productID . "'  AND Registration_ID='" . $patientID . "' AND Validated != 'Yes' AND tr.payment_item_ID='" . $ppil . "'  GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";
      
	  
        $GetResults = mysqli_query($conn,$selectQuery);
        echo "<center><table class='' style='width:100%'>";
        echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%' style='text-align:left'>S/N</th>
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

        $datamsg = mysqli_num_rows($GetResults);

        if ($datamsg > 0) {
            while ($row2 = mysqli_fetch_assoc($GetResults)) {
                $testID = $row2['test_result_ID'];
                $paymentID = $row2['payment_item_ID'];
                $input = '';
                if ($row2['result_type'] == 'Quantitative') {
                    $input = '<input type="text" class="Resultvalue Quantative" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '" placeholder="Numeric values only">';
                } else if ($row2['result_type'] == 'Qualitative') {
                    $input = '<input type="text" class="Resultvalue Qualitative' . $row2['parameter_ID'] . '" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '">';
                }

                echo '<tr>';
                echo '<td>' . $sn++ . '</td>';
                echo '<td>' . $row2['Parameter_Name'] . '</td>';

                echo '<td>' . $input . '</td>';

                echo '<input type="hidden" class="parameterName" value="' . $row2['Parameter_Name'] . '">';
                echo '<input type="hidden" class="paymentID" value="' . $row2['test_result_ID'] . '">';
                echo '<input type="hidden" class="productID" value="' . $productID . '">';
                echo '<td>' . $row2['unit_of_measure'] . '</td>';
                if ($row2['modified'] == "Yes") {
                    echo '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
                } else {
                    echo '<td></td>';
                }
                if ($row2['Validated'] == "Yes") {
                    echo '<td>&#x2714;</td>';
                    $statusCheck = 'submit';
                } else {
                    echo '<td></td>';
                }
                if ($row2['Submitted'] == "Yes") {
                    echo '<td>&#x2714;</td>';
                } else {
                    echo '<td></td>';
                }

                echo '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

                $lower = $row2['lower_value'];
                $upper = $row2['higher_value'];
                $operator = $row2['operator'];
                $rowResult = $row2['result'];
                $result_type = $row2['result_type'];

                if ($result_type == "Quantitative") {

                    if (in_array($operator, $operators) && is_numeric($rowResult)) {
                        if ($rowResult > $upper) {
                            echo '<td><p style="color:rgb(255,0,0)">H</p></td>';
                        } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                            echo '<td><p style="color:rgb(255,0,0)">L</p></td>';
                        } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                            echo '<td><p style="color:rgb(0,128,0)">N</p></td>';
                        }
                    } else {
                        echo '<td><p style="color:rgb(0,128,0)"></p></td>';
                    }
                } else if ($result_type == "Qualitative") {
                    echo '<td><p style="color:rgb(0,0,128)"></p></td>';
                } else {
                    echo '<td><p style="color:rgb(0,0,128)"></p></td>';
                }


                //Get previous test results
                $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $id . "' AND tr.payment_item_ID<>'" . $ppil . "'";
                $Queryhistory = mysqli_query($conn,$historyQ);
                $myrows = mysqli_num_rows($Queryhistory);
                //$prev=$myrows-1;
                if ($myrows > 0) {
                    echo '<td>
                <p class="prevHistory" value="' . $id . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                    $myrows . ' Previous result(s)'
                    . '</p>
               
                </td>';
                } else {
                    echo '<td>No previous results</td>';
                }

                if ($row2['Validated'] == 'Yes') {
                    echo'<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" readonly="true" style="display:none">';
                } else {
                    echo'<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';
                }

                //place submit here
                echo '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" disabled="true" style="display:none">';

                echo '</tr>';
            }

            $testIDUnic = $testID;
            echo '<div id="testName">' . $row2['Product_Name'] . '</div>';
            echo '</table>';



            //Count total number of parameters
            $resultSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='$productID'") or die(mysqli_error($conn));
            $numrows = mysqli_num_rows($resultSaved);

            //Count parameters Saved
            $numparameterSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Saved='Yes' AND payment_item_ID='$paymentID'")or die(mysqli_error($conn));
            $numSavedParameters = mysqli_num_rows($numparameterSaved);


            //Count parameters Submitted
            $numparameterSubmitted = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Submitted='Yes' AND payment_item_ID='$paymentID'")or die(mysqli_error($conn));
            $numSubmittedParameters = mysqli_num_rows($numparameterSubmitted);


            //Count parameters Validated
            $numparameterValidated = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "' AND Validated='Yes' AND payment_item_ID='$paymentID'")or die(mysqli_error($conn));
            $numValidatedParameters = mysqli_num_rows($numparameterValidated);


            //Count parameters None empty results
            $numparameterNoneempty = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results INNER JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE ref_test_result_ID='" . $testIDUnic . "'  AND payment_item_ID='$paymentID' AND result <>''")or die(mysqli_error($conn));
            $numNoneemptyParameters = mysqli_num_rows($numparameterNoneempty);

            if ($numSavedParameters == 0) {
                echo '<div> 
		  <input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save Results"></div>';
            } elseif ($numNoneemptyParameters == $numrows) {
                echo '<div> 
	  <input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> ';


                if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
                    echo '<input type="button" ppil="' . $ppil . '" class="validateSubmittedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Validate"> ';
                }

                echo '<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor">';
                ?>
                <?php //$Receiver = "xxxxxx"; ?>
                <button id="SMSButton" class="art-button-green" onClick="SendSMS('Lab', '<?php echo $Receiver; ?>')" >Send SMS Alert</button>
                <span id="SMSRespond"></span>				
                <?php
                echo '</div>';
            } elseif ($numNoneemptyParameters < $numrows) {
                echo '<div> 
		<input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results">
		
		<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor"> </div>';
            }
        } else {
            echo "<tr><td style='text-align:center;color:blue;font-size:18px;font-weight:bold' colspan='10'>Parameter saved successfully</td></tr>";
        }
        // Validate results begins here
    } else if ($_POST['SavegeneralResult'] == 'Validation') {
        $values = $_POST['testresults'];
        $ppil = $_POST['ppil'];
        if ($values == "undefined") {
            
        } else {
            //check if all field have been filled out
            $resultsck = explode('$>', $values);
            $counterck = 0;
            foreach ($resultsck as $value) {
                $data = explode("#@", $value);
                $id = $data[0];
                $val = $data[1];
                $update = "Select result from tbl_tests_parameters_results  WHERE ref_test_result_ID='" . $val . "' AND parameter='" . $id . "' AND result <> '' ";
                $runQuery = mysqli_query($conn,$update) or die(mysqli_error($conn));

                $mrs = mysqli_fetch_assoc($runQuery);

                if (!empty($mrs['result'])) {
                    $counterck = $counterck + 1;
                    //echo $mrs['result'].' '.$val.' '.$id.'<br/>';
                }
                //echo $val.' '.$id.'<br/>';
            }

            if ($counterck == count($resultsck)) {
                $results = explode('$>', $values);
                foreach ($results as $value) {
                    $data = explode("#@", $value);
                    $id = $data[0];
                    $val = $data[1];
                    $update = "UPDATE tbl_tests_parameters_results SET Validated='Yes',TimeValidate=NOW(),ValidatedBy='" . $Employee_ID . "' WHERE ref_test_result_ID='" . $val . "' AND parameter='" . $id . "'";
                    $runQuery = mysqli_query($conn,$update);
                }
            } else {
                echo 'NOTSAVED';
                exit;
            }
        }

        $itemID = $_POST['itemID'];
        $patientID = $_POST['patientID'];
        $sn = 1;

        $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $itemID . "'  AND Registration_ID='" . $patientID . "' AND tr.payment_item_ID='" . $ppil . "' GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";

        //echo $selectQuery;//($itemID." ".$patientID);exit;
        $GetResults = mysqli_query($conn,$selectQuery) or die(mysqli_error($conn));
        echo "<center><table class='' style='width:100%'>";
        echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%' style='text-align:left'>S/N</th>
                <th width='' style='text-align:left'>Parameters</th>
                <th width='' style='text-align:left'>Results</th>
                <th width='' style='text-align:left'>UoM</th>
                <th width='' style='text-align:left'>M</th>
                <th width='' style='text-align:left'>V</th>
                <th width='' style='text-align:left'>S</th>
                <th width='' style='text-align:left'>Status</th>
				<th width='' style='text-align:left'>Normal Value</th>
                <th width='' style='text-align:left'>Previous results</th>
        </tr>";
        $testID = '';
        $paymentID = '';
        $datamsg = mysqli_num_rows($GetResults);

        if ($datamsg > 0) {

            while ($row2 = mysqli_fetch_assoc($GetResults)) {
                $testID = $row2['test_result_ID'];
                $paymentID = $row2['payment_item_ID'];
                echo '<tr>';
                echo '<td>' . $sn++ . '</td>';
                echo '<td>' . $row2['Parameter_Name'] . '</td>';
                if ($row2['result_type'] == 'Quantitative') {
                    echo '<td><input type="text" class="Resultvalue Quantative" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '" placeholder="Numeric values only"></td>';
                } else if ($row2['result_type'] == 'Qualitative') {
                    echo '<td><input type="text" class="Resultvalue Qualitative' . $row2['parameter_ID'] . '" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '"></td>';
                }


                echo '<input type="hidden" class="paymentID" value="' . $row2['test_result_ID'] . '">';
                echo '<input type="hidden" class="productID" value="' . $itemID . '">';
                echo '<td>' . $row2['unit_of_measure'] . '</td>';
                if ($row2['modified'] == "Yes") {
                    echo '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
                } else {
                    echo '<td></td>';
                }
                if ($row2['Validated'] == "Yes") {
                    echo '<td>&#x2714;</td>';
                } else {
                    echo '<td></td>';
                }
                if ($row2['Submitted'] == "Yes") {
                    echo '<td>&#x2714;</td>';
                } else {
                    echo '<td></td>';
                }
                echo '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

                echo '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';
                $lower = $row2['lower_value'];
                $upper = $row2['higher_value'];
                $rowResult = $row2['result'];
                $operator = $row2['operator'];
                $upper = $row2['higher_value'];
                $result_type = $row2['result_type'];

                if ($result_type == "Quantitative") {

                    if (in_array($operator, $operators) && is_numeric($rowResult)) {
                        if ($rowResult > $upper) {
                            echo '<td><p style="color:rgb(255,0,0)">H</p></td>';
                        } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                            echo '<td><p style="color:rgb(255,0,0)">L</p></td>';
                        } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                            echo '<td><p style="color:rgb(0,128,0)">N</p></td>';
                        } else {
                            echo '<td><p style="color:rgb(0,128,0)">' . $rowResult . ' ' . $upper . '</p></td>';
                        }
                    } else {
                        echo '<td><p style="color:rgb(0,128,0)"></p></td>';
                    }
                } else if ($result_type == "Qualitative") {
                    echo '<td><p style="color:rgb(0,0,128)"></p></td>';
                } else {
                    echo '<td><p style="color:rgb(0,0,128)"></p></td>';
                }



                //Get previous test results
                $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $itemID . "' AND tr.payment_item_ID<>'" . $ppil . "'";
                $Queryhistory = mysqli_query($conn,$historyQ);
                $myrows = mysqli_num_rows($Queryhistory);
                //$prev=$myrows-1;
                if ($myrows > 0) {
                    echo '<td>
                <p class="prevHistory" value="' . $id . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . '">' .
                    $myrows . ' Previous result(s)'
                    . '</p>
               
                </td>';
                } else {
                    echo '<td>No previous results</td>';
                }
                // if($row2['Validated']=='Yes'){
                echo'<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" readonly="true" style="display:none">';

                echo '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" checked="true" disabled="true" style="display:none">';


                echo '</tr>';
            }
            $testIDUnic = $testID;
            echo '<div id="testName">' . $row2['Product_Name'] . '</div>';
            echo '</table>';

            //Count total number of parameters
            $resultSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='$itemID'");
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

            if ($numSavedParameters == 0) {
                echo '<div> 
			  
			  <input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save Results"></div>';
            } elseif ($numNoneemptyParameters == $numrows) {
                echo '<div> 
			  <input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> ';


                if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
                    echo '<input type="button" ppil="' . $ppil . '" class="validateSubmittedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Validate"> ';
                }

                echo '<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor">';
                ?>
                <?php //$Receiver = "xxxxxx"; ?>
                <button id="SMSButton" class="art-button-green" onClick="SendSMS('Lab', '<?php echo $Receiver; ?>')" >Send SMS Alert</button>
                <span id="SMSRespond"></span>				
                <?php
                echo '</div>';
            } elseif ($numNoneemptyParameters < $numrows) {
                echo '<div> 
				
				<input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results">


				<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor">';
                ?>
                <?php //$Receiver = "xxxxxx"; ?>
                <button id="SMSButton" class="art-button-green" onClick="SendSMS('Lab', '<?php echo $Receiver; ?>')" >Send SMS Alert</button>
                <span id="SMSRespond"></span>				
                <?php
                echo '</div>';
            }
        } else {
            echo "<tr><td style='text-align:center;color:blue;font-size:18px;font-weight:bold' colspan='10'>Parameter validated and sent successfully</td></tr>";
        }


        //Submit results here
    } elseif ($_POST['SavegeneralResult'] == 'submitResult') {
        $itemID = $_POST['itemID'];
        $values = $_POST['testresults'];
        $patientID = $_POST['patientID'];
        $ppil = $_POST['ppil'];
        // echo ($itemID.' '.$patientID);//exit;

        if ($values == "undefined") {
            
        } else {
            $results = explode('$>', $values);

            // echo '<pre>';
            // print_r($results);
            // echo '</pre>';
            // exit;
            foreach ($results as $value) {
                $data = explode("#@", $value);
                $id = $data[0];
                $val = $data[1];
                $update = "UPDATE tbl_tests_parameters_results SET Submitted='Yes',TimeSubmitted=NOW() WHERE ref_test_result_ID='" . $val . "' AND parameter='" . $id . "' AND result !=''";
                $runQuery = mysqli_query($conn,$update);
            }
        }

        $sn = 1;
        $selectQuery = "select * from tbl_items item,tbl_item_list_cache ilc,tbl_payment_cache as tpc, tbl_test_results tr, tbl_tests_parameters tp, tbl_parameters p, tbl_tests_parameters_results tpr where item.Item_ID=ilc.Item_ID AND 
	  ilc.Payment_Cache_ID=tpc.Payment_Cache_ID AND ilc.Payment_Item_Cache_List_ID = tr.payment_item_ID and ilc.Item_ID = tp.ref_item_ID and p.parameter_ID = tp.ref_parameter_ID and tpr.parameter = p.parameter_ID and tr.test_result_ID = tpr.ref_test_result_ID and ilc.Item_ID = '" . $itemID . "'  AND Registration_ID='" . $patientID . "' AND Validated != 'Yes' AND tr.payment_item_ID='" . $ppil . "' GROUP BY PARAMETER_NAME ORDER BY PARAMETER_NAME";

        $GetResults = mysqli_query($conn,$selectQuery) or die(mysqli_error($conn));
        echo "<center><table class='' style='width:100%'>";
        echo "<tr style='background-color:rgb(200,200,200)'>
                <th width='1%' style='text-align:left'>S/N</th>
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
        $datamsg = mysqli_num_rows($GetResults);
        if ($datamsg > 0) {
            while ($row2 = mysqli_fetch_assoc($GetResults)) {
                $testID = $row2['test_result_ID'];
                $paymentID = $row2['payment_item_ID'];
                echo '<tr>';
                echo '<td>' . $sn++ . '</td>';
                echo '<td>' . $row2['Parameter_Name'] . '</td>';
                if ($row2['result_type'] == 'Quantitative') {
                    echo '<td><input type="text" class="Resultvalue Quantative" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '" placeholder="Numeric values only"></td>';
                } else if ($row2['result_type'] == 'Qualitative') {
                    echo '<td><input type="text" class="Resultvalue Qualitative' . $row2['parameter_ID'] . '" id="' . $row2['parameter_ID'] . '" value="' . $row2['result'] . '"></td>';
                }
                echo '<input type="hidden" class="paymentID" value="' . $row2['test_result_ID'] . '">';
                echo '<input type="hidden" class="productID" value="' . $itemID . '">';
                echo '<td>' . $row2['unit_of_measure'] . '</td>';
                if ($row2['modified'] == "Yes") {
                    echo '<td><p class="modificationStats" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '">&#x2714;</p></td>';
                } else {
                    echo '<td></td>';
                }
                if ($row2['Validated'] == "Yes") {
                    echo '<td>&#x2714;</td>';
                } else {
                    echo '<td></td>';
                }
                if ($row2['Submitted'] == "Yes") {
                    echo '<td>&#x2714;</td>';
                } else {
                    echo '<td></td>';
                }
                echo '<td>' . $row2['lower_value'] . ' ' . $row2['operator'] . ' ' . $row2['higher_value'] . '</td>';

                $lower = $row2['lower_value'];
                $upper = $row2['higher_value'];
                $rowResult = $row2['result'];
                $operator = $row2['operator'];
                $upper = $row2['higher_value'];
                $result_type = $row2['result_type'];
                if ($result_type == "Quantitative") {

                    if (in_array($operator, $operators) && is_numeric($rowResult)) {
                        if ($rowResult > $upper) {
                            echo '<td><p style="color:rgb(255,0,0)">H</p></td>';
                        } elseif (($rowResult < $lower) && ($rowResult !== "")) {
                            echo '<td><p style="color:rgb(255,0,0)">L</p></td>';
                        } elseif (($rowResult >= $lower) && ($rowResult <= $upper)) {
                            echo '<td><p style="color:rgb(0,128,0)">N</p></td>';
                        } else {
                            echo '<td><p style="color:rgb(0,128,0)">' . $rowResult . ' ' . $upper . '</p></td>';
                        }
                    } else {
                        echo '<td><p style="color:rgb(0,128,0)"></p></td>';
                    }
                } else if ($result_type == "Qualitative") {
                    echo '<td><p style="color:rgb(0,0,128)"></p></td>';
                } else {
                    echo '<td><p style="color:rgb(0,0,128)"></p></td>';
                }


                //Get previous test results
                $historyQ = "SELECT * FROM tbl_tests_parameters_results as tpr,tbl_test_results as tr,tbl_item_list_cache as tilc,tbl_payment_cache as pc,tbl_patient_registration as pr WHERE tpr.ref_test_result_ID=tr.test_result_ID AND tr.payment_item_ID=tilc.Payment_Item_Cache_List_ID AND pc.Payment_Cache_ID=tilc.Payment_Cache_ID AND pr.Registration_ID=pc.Registration_ID AND pr.Registration_ID='$patientID' AND tpr.parameter='" . $row2['parameter_ID'] . "' AND tilc.Item_ID='" . $itemID . "' AND tr.payment_item_ID<>'" . $ppil . "'";
                $Queryhistory = mysqli_query($conn,$historyQ);
                $myrows = mysqli_num_rows($Queryhistory);
                //$prev=$myrows-1;
                if ($myrows > 0) {
                    echo '<td>
                <p class="prevHistory" value="' . $itemID . '" name="' . $patientID . '" id="' . $row2['parameter_ID'] . ' ppil="' . $ppil . '"">' .
                    $myrows . ' Previous result(s)'
                    . '</p>
               
                </td>';
                } else {
                    echo '<td>No previous results</td>';
                }

                // if($row2['Submitted']=='Yes'){
                echo '<input type="checkbox" class="Submitted" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';
                /* }  */

                //Place validate here
                // if($row2['Validated']=='Yes'){
                echo '<input type="checkbox" class="validated" id="' . $row2['parameter_ID'] . '" value="' . $row2['test_result_ID'] . '" style="display:none">';
                // } 

                echo '</tr>';
            }
            $testIDUnic = $testID;
            echo '<div id="testName">' . $row2['Product_Name'] . '</div>';
            echo '</table>';

            //Count total number of parameters
            $resultSaved = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters WHERE ref_item_ID='$itemID'");
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

            if ($numSavedParameters == 0) {
                echo '<div> 
				  
				  <input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save Results"></div>';
            } elseif ($numNoneemptyParameters == $numrows) {
                echo '<div> 
			  <input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> ';


                if (isset($_SESSION['userinfo']['Laboratory_Result_Validation']) && $_SESSION['userinfo']['Laboratory_Result_Validation'] == 'yes') {
                    echo '<input type="button" ppil="' . $ppil . '" class="validateSubmittedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Validate"> ';
                }

                echo '<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor">';
            } elseif ($numNoneemptyParameters < $numrows) {
                echo '<div> 
				
				<input type="button" ppil="' . $ppil . '" class="validateResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Save/Update Results"> 
				
				<input type="button" ppil="' . $ppil . '" class="SubmitvalidatedResulttr" name="' . $patientID . '" id="' . $testIDUnic . '" value="Send to Doctor"> </div>';
            }
        } else {
            echo "<tr><td style='text-align:center;color:blue;font-size:18px;font-weight:bold' colspan='10'>Parameter sent and received successfully</td></tr>";
        }
    }
}
?>
<style>
    .modificationStats:hover{
        text-decoration: underline;
        color: rgb(145,0,0);
        cursor: pointer;
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
</script>

<script>
    $(".Quantative").bind("keydown", function (event) {

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

</script>
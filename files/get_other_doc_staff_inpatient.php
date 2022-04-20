<style>
    #tabsInfo ul li,h3{
        list-style: none;
        display:inline-block; 
    }
</style>
<?php
include("./includes/connection.php");
@session_start();

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
$consultation_ID = '';
$Registration_ID = '';
$Today = '';

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

$rsDoc = mysqli_query($conn,"SELECT *, TIMESTAMPDIFF(DAY,Ward_Round_Date_And_Time,NOW()) AS days_past  FROM tbl_ward_round wr JOIN tbl_employee e ON wr.employee_ID=e.employee_ID  WHERE wr.consultation_ID=$consultation_ID AND wr.Registration_ID=$Registration_ID AND Process_Status ='served' ORDER BY wr.Round_ID DESC LIMIT 50") or die(mysqli_error($conn));
//die("SELECT * FROM tbl_consultation_history ch LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID JOIN tbl_employee e ON ch.employee_ID=e.employee_ID WHERE ch.consultation_ID=$consultation_ID AND c.Registration_ID=$Registration_ID AND ch.employee_ID != $employee_ID");
$data = '';
$select_Patient = mysqli_query($conn,"SELECT
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_Patient);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Patient)) {
        $Patient_Name = $row['Patient_Name'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];
        // echo $Ward."  ".$District."  ".$Ward; exit;
    }
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->m . " Months";
    }
    if ($age == 0) {
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->d . " Days";
    }
}

$data = "<center>
	<table width='100%' style='background: #006400 !important;color: white;'>
	    <tr>
		<td>
		    <center>
			" . strtoupper($Patient_Name) . ", " . strtoupper($Gender) . ", " . ($age) . ", " . strtoupper($Guarantor_Name) . "
		    </center>
		</td>
	    </tr>
	</table>
        </center><br/>";

while ($doctorsInfo = mysqli_fetch_array($rsDoc)) {
    $doctorsName = $doctorsInfo['Employee_Name'];
    $doctorsID = $doctorsInfo['Employee_ID'];
    $Round_ID = $doctorsInfo['Round_ID'];
    $Days_Past = $doctorsInfo['days_past'];
    $Ward_Round_Date_And_Time = $doctorsInfo['Ward_Round_Date_And_Time'];

//          $doctorsID=$doctorsInfo['Employee_Name'];
    //Selecting Submitted Tests,Procedures, Drugs

    $select_payment_cache = "SELECT  Check_In_Type,Product_Name ,Doctor_Comment FROM tbl_payment_cache pc,tbl_item_list_cache ilc,tbl_items i
				WHERE pc.consultation_id = $consultation_ID
                                AND pc.Round_ID='$Round_ID'
				AND pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND i.Item_ID = ilc.Item_ID
				AND ilc.Status !='notsaved'";
    $cache_result = mysqli_query($conn,$select_payment_cache);
    $Radiology = '';
    $Laboratory = '';
    $Pharmacy = "";
    $Procedure = "";
    $Surgery = "";
    if (mysqli_num_rows($cache_result) > 0) {
        while ($cache_row = mysqli_fetch_assoc($cache_result)) {
            if ($cache_row['Check_In_Type'] == 'Radiology') {
                $Radiology.= ' ' . $cache_row['Product_Name'] . ';';
            }
            if ($cache_row['Check_In_Type'] == 'Laboratory') {
                $Laboratory.= ' ' . $cache_row['Product_Name'] . ';';
            }
            if ($cache_row['Check_In_Type'] == 'Pharmacy') {
                $Pharmacy.= ' ' . $cache_row['Product_Name'] . '[ Dosage: ' . $cache_row['Doctor_Comment'] . ' ]' . ';   ';
            }
            if ($cache_row['Check_In_Type'] == 'Procedure') {
                $Procedure.= ' ' . $cache_row['Product_Name'] . ';';
            }
            if ($cache_row['Check_In_Type'] == 'Surgery') {
                $Surgery.= ' ' . $cache_row['Product_Name'] . ';';
            }
        }
    }

    //End
    //Selesting Previously written consultation note for this consultation
    $consultation_result = mysqli_query($conn, "SELECT * FROM  tbl_ward_round wr WHERE wr.consultation_ID=$consultation_ID AND wr.employee_ID='$doctorsID' AND wr.Round_ID='$Round_ID'");

    if (mysqli_num_rows($consultation_result) > 0) {
        $round_row = mysqli_fetch_assoc($consultation_result);

        $Findings = $round_row['Findings'];
        $Comment_For_Laboratory = $round_row['Comment_For_Laboratory'];
        $Comment_For_Radiology = $round_row['Comment_For_Radiology'];
        $Comment_For_Procedure = $round_row['Comment_For_Procedure'];
        $Comments_For_Surgery = $round_row['Comment_For_Surgery'];
        $investigation_comments = $round_row['investigation_comments'];
        $remarks = $round_row['remarks'];
        //$Patient_Type = $consultation_row['Patient_Type'];
    } else {
        $Findings = '';
        $Comment_For_Laboratory = '';
        $Comment_For_Radiology = '';
        $Comment_For_Procedure = '';
        $Comment_For_Surgery = '';
        $investigation_comments = '';
        $remarks = '';
    }

    //End
    //selecting diagnosois
    $diagnosis_qr = "SELECT diagnosis_type,disease_name FROM tbl_ward_round_disease dc,tbl_disease d
		    WHERE Round_ID='$Round_ID' AND 
		    dc.disease_ID = d.disease_ID";
    $result = mysqli_query($conn,$diagnosis_qr) or die(mysqli_error($conn));
    $provisional_diagnosis = '';
    $diferential_diagnosis = '';
    $diagnosis = '';
    if (@mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['diagnosis_type'] == 'provisional_diagnosis') {
                $provisional_diagnosis.= ' ' . $row['disease_name'] . ';';
            }
            if ($row['diagnosis_type'] == 'diferential_diagnosis') {
                $diferential_diagnosis.= ' ' . $row['disease_name'] . ';';
            }
            if ($row['diagnosis_type'] == 'diagnosis') {
                $diagnosis.= ' ' . $row['disease_name'] . ';';
            }
        }
    }
    //End
    $editRound='';
    if($employee_ID== $doctorsID){
        
        if($Days_Past <=2){
        
          $editRound=' <a style="width:60%" href="inpatientclinicalnotes.php?src=edit&Registration_ID='.$Registration_ID.'&consultation_ID='.$consultation_ID.'&Round_ID='.$Round_ID.'" class="art-button-green">Edit</a>';
          $Round_ID = $doctorsInfo['Round_ID'];
        }
    }

    $data .='<div id="tabsInfo">
                        <table width="100%" style="background: #006400 !important;color: white;">
                        <tr>
                            <td style="text-align:right">
                             <b>Doctor</b>
                            </td>
                            <td>
                            ' . $doctorsName . '
                            </td>
                            <td style="text-align:right">
                              <b>Date:</b>
                            </td>
                            <td>
                            ' . $Ward_Round_Date_And_Time . '
                            </td>
                            <td width="10%" style="text-align:center">
                               '.$editRound.'
                            </td>
                        </tr>
                    </table>
                  <div id="complain">
            <table width=100% style="border: 0px;">';
    if (!empty($Findings)) {
        $data .='<tr>
                            <td width="15%" style="text-align:right;">
                                <!--<div style="margin:10px auto auto">-->  
                                 Main Complain
                              </div>
                            </td>
                            <td><textarea style="resize:none;padding-left:5px;" readonly="readonly" id="maincomplain" name="maincomplain">' . $Findings . '</textarea></td>
	              </tr>';
    }
    $data .='  </table>
        </div>
        <div id="observation">
            <table width=100% style="border: 0px;">';
    if (!empty($provisional_diagnosis)) {
        $data .='<tr>
                            <td width="15%" style="text-align:right;">Provisional Diagnosis</td>
                            <td><input  type="text" readonly="readonly"  class="provisional_diagnosis" name="provisional_diagnosis" readonly="readonly" value=' . $provisional_diagnosis . '>
                          </tr>';
    }if (!empty($diferential_diagnosis)) {
        $data .='<tr><td width="15%" style="text-align:right;">Differential Diagnosis</td><td><input type="text" readonly="readonly" id="diferential_diagnosis" class="diferential_diagnosis" name="diferential_diagnosis" value=' . $diferential_diagnosis . '>';
    }
    $data .='</table>
        </div>
         <div id="investigation">
            <table width=100% style="border: 0px;">';
    if (!empty($Laboratory)) {
        $data .=' <tr><td width="15%" style="text-align:right;">Laboratory</td><td><textarea style="resize:none;padding-left:5px;" readonly="readonly" id="laboratory" name="laboratory">' . $Laboratory . '</textarea></tr>';
    }if (!empty($Comment_For_Laboratory)) {
        $data .='<tr><td width="15%" style="text-align:right;">Comments For Laboratory</td><td><input type="text" id="Comment_For_Laboratory" name="Comment_For_Laboratory" value="' . $Comment_For_Laboratory . '"></td></tr>';
    }if (!empty($Radiology)) {
        $data .='<tr><td width="15%" style="text-align:right;">Radiology</td><td><textarea readonly="readonly"  style="resize: none;" readonly="readonly" type="text" id="provisional_diagnosis" class="Radiology" name="provisional_diagnosis">' . $Radiology . '</textarea></tr>';
    }if (!empty($Comment_For_Radiology)) {
        $data .='<tr><td width="15%" style="text-align:right;">Comments For Radiology</td><td><input type="text" readonly="readonly" id="Comment_For_Radiology" name="Comment_For_Radiology" value="' . $Comment_For_Radiology . '"></td></tr>';
    }if (!empty($investigation_comments)) {
        $data .='<tr><td width="15%" style="text-align:right;">Doctor\'s Investigation Comments</td><td><textarea style="resize: none;" id="investigation_comments" name="investigation_comments">' . $investigation_comments . '</textarea></td></tr>';
    }
    $data .=' </table>
        </div>
        <div id="diagnosis_treatment">
            <table width=100% style="border: 0px;">';
    if (!empty($diagnosis)) {
        $data .=' <tr><td width="15%" style="text-align:right;"><b>Final Diagnosis </b></td><td><input  type="text" readonly="readonly" id="diagnosis" class="final_diagnosis" name="diagnosis" value="' . $diagnosis . '">';
    }if (!empty($Procedure)) {
        $data .=' <tr><td width="15%" style="text-align:right;">Procedure</td><td><textarea style="resize: none;" type="text" readonly="readonly" class="Procedure" id="provisional_diagnosis" readonly="readonly" name="provisional_diagnosis">' . $Procedure . '</textarea></tr>';
    }if (!empty($Comments_For_Procedure)) {
        $data .='<tr><td width="15%" style="text-align:right;">Procedure Comments</td><td><textarea  style="resize: none;" rows="1" id="ProcedureComments" readonly="readonly" name="ProcedureComments">' . $Comments_For_Procedure . '</textarea></td></tr>';
    }
    if (!empty($Surgery)) {
        $data .='<tr><td width="15%" style="text-align:right;">Surgery</td><td><textarea style="resize: none;" type="text" readonly="readonly" class="Surgery" readonly="readonly"  id="provisional_diagnosis" name="provisional_diagnosis">' . $Surgery . '</textarea></tr>';
    }if (!empty($Comments_For_Surgery)) {
        $data .='<tr>
                                <td width="15%" style="text-align:right;">Sugery Comments</td>
                                <td><textarea readonly="readonly"  style="width:100%;resize: none;" rows="1" id="SugeryComments" name="SugeryComments">' . $Comments_For_Surgery . '</textarea>
                                </td>
                          </tr>';
    }

    if (!empty($Pharmacy)) {
        $data .='<tr><td width="15%" style="text-align:right;">Pharmacy</td><td><textarea style="resize: none;" readonly="readonly" id="provisional_diagnosis" readonly="readonly" class="Treatment" name="provisional_diagnosis">' . $Pharmacy . '</textarea>';
    }
    $data .=' </table>
        </div>
        <div id="remarks">
            <table width=100% style="border: 0px;">';
    if (!empty($remarks)) {
        $data .=' <tr>
                <td width="15%" style="text-align:right;">Remarks</td>
                <td>
                <textarea style="resize: none;" readonly="readonly" id="remark" name="remark">' . $remarks . '</textarea>
                </td>
	    </tr>';
    }
    $data .='</table>
        </div><br/><br/>
                 ';
}

echo $data;



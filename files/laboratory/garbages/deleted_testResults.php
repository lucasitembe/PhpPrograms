<?
// MicroBiology-----if ($_POST['getCultureResults'] == 'cultureview') {


$query = "SELECT * FROM tbl_item_list_cache JOIN tbl_culture_results ON Payment_Item_Cache_List_ID=payment_item_ID WHERE payment_item_ID='$paymentID' GROUP BY payment_item_ID";
$myQuery = mysqli_query($conn,$query);
$row2 = mysqli_fetch_assoc($myQuery);
echo "<center><table cellspacing='0' cellpadding='0'  width=1000px;>
         <tr></tr>";
      echo "<tr>
      <td><center><b>------Specimen Type</b></center></td>
      <td>
      <select class='seleboxorg3'  name='specimen' id='specimen' style='width:600px; padding-top:4px; padding-bottom:4px;'>"
        ?><?php
                 $query_sub_specimen = mysqli_query($conn,"SELECT Specimen_Name,Specimen_ID FROM tbl_laboratory_specimen WHERE Status='Active'") or die(mysqli_error($conn));
                  echo '<option value="All">~~~~~Select Specimen~~~~~</option>';
                 while ($row = mysqli_fetch_array($query_sub_specimen)) {
                  echo '<option value="' . $row['Specimen_ID'] . '">' . $row['Specimen_Name'] . '</option>';
                 }
                ?><?php
        echo "</select><button type='button' style='color:white !important; height:27px !important; margin-left:10px !important;' class='art-button-green' onclick='addspecimen();'>Add Specimen</button>"
                . "<button type='button' style='color:white !important; height:27px !important; margin-left:0px !important;' class='art-button-green' onclick='removeSpecimen();'>Remove Specimen</button>"
        ."</td></tr>";

         echo "<tr>
        <td><center><b>Organism</b></center></td>";
         "<div id='Cached2'>";
      echo  "<td>";
       echo "<div id='Cached'>
<select class='seleboxorg3' name='new_organism_1' id='new_organism_1' style='width:600px; padding-top:4px; padding-bottom:4px;'>";

               ?><?php $query_sub_specimen1 = mysqli_query($conn,"SELECT organism_name,organism_id FROM tbl_organism") or die(mysqli_error($conn));
                  echo '<option value="All">~~~~~Select organism~~~~~</option>';
                 while ($row1 = mysqli_fetch_assoc($query_sub_specimen1)) {
                   echo '<option value="' . $row1['organism_id'] . '">' . $row1['organism_name'] . '</option>';
                 }?><?php

        echo "</select><button type='button' style='color:white !important; height:27px !important; margin-left:168px !important;' class='art-button-green' onclick='addorganism();'>Add Organism</button>
    </div>
     </td>

    </tr>";

  echo "<tr>
          <td>
          <center> <b>Wet Prepation</b></center>
          </td>
          <td>
          <textarea rows='2' cols='40' placeholder='Remarks' name='wet' id='wet'></textarea>
          </td>

        </tr>";

echo "<tr>
           <td>
          <center> <b> Gram Stein</b></center>
          </td>
           <td colspan='3'>
                <select style='width:300px; padding-top:4px; padding-bottom:4px;' name='sign' id='sign'>
                <option value=''>------select-----</option>
                 <option value='Gram +'>Gram +</option>
                  <option value='Gram -'>Gram -</option>
                </select>

                 <select style='width:300px; padding-top:4px; padding-bottom:4px; name='multipleSelect' class='multipleSelect' id='multipleSelect' multiple>";
                 ?><?php $query_sub_specimen1 = mysqli_query($conn,"SELECT `Shape_Name` FROM `tbl_laboratory_shape` WHERE `status`='Active'") or die(mysqli_error($conn));
                  echo '<option value="">~~~~~Select Shape~~~~~</option>';
                 while ($row1 = mysqli_fetch_assoc($query_sub_specimen1)) {
                   echo '<option value="' . $row1['Shape_Name'] . '">' . $row1['Shape_Name'] . '</option>';
                 }?><?php

           echo "</select><button type='button' style='color:white !important; height:27px !important; margin-left:10px !important;' class='art-button-green' onclick='addnewShape();'>Add Shape</button><br>
                <textarea rows='2' cols='40' placeholder='Remarks' name='stein' id='stein' value=''></textarea>
          </td>
    </tr>";
echo "<tr id='addhere' class='addnewrow'><td><center><b>Antibiotic</b></center></td>"
        . "<td id='CultureTbl'>";
      $cached_data.="<select class='antibiotc' name='antibiotic[]' id='1' style='width:300px; padding-top:4px; padding-bottom:4px;'>";
           $cached_data.= '<option value="All">~~~~~Select Antibiotic~~~~~</option>'
                 ?><?php   $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy'") or die(mysqli_error($conn));
                 while ($row = mysqli_fetch_array($query_sub_specimen)) {
                  $cached_data.= '<option value="' . $row['Item_ID'] . '">' . $row['Product_Name'] . '</option>';
                 }?><?php
                $cached_data.= "</select>";
                $cached_data.= "<select class='seleboxorg1' name='sensitive[]' id='orgone_1' style='width:300px; padding-top:4px; padding-bottom:4px;'><option>-----select---</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select><input type='button' class='art-button-green' style='margin-left:165px !important;' id='addrow' name='' . $paymentID . '' value='Add antibiotics' />
            </td>
    <input type='hidden' id='rowCount' value='1'>

    </tr>";
 echo $cached_data;

echo '</table>';
echo 'Remarks:<textarea id="Remarks" style="width:50%;padding-left:5px;margin-top:5px">' . $row2['Remarks'] . '</textarea><br />';
echo '<tr><td></td><td>';
    $Validated1="no";
    $Submitted="No";
    $ValidatedBy=0;
    $SavedBy=0;
    $Saved="no";
    $Validate="No";

     $mysqli_is_run= mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results WHERE ref_test_result_ID IN (SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$paymentID')");
      if(mysqli_num_rows($mysqli_is_run)>0){
        $mysqli_is_run_rows=mysqli_fetch_assoc($mysqli_is_run);
        $Validate=$mysqli_is_run_rows['Validated'];
        $Submitted=$mysqli_is_run_rows['Submitted'];
        $modified=$mysqli_is_run_rows['modified'];
        $Saved=$mysqli_is_run_rows['Saved'];
        $SavedBy=$mysqli_is_run_rows['SavedBy'];
        $ValidatedBy=$mysqli_is_run_rows['ValidatedBy'];
      }
//

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
          $mysqli_is_run2=0;
      $mysqli_is_run2= mysqli_query($conn,"SELECT payment_item_ID FROM tbl_culture_results WHERE payment_item_ID='$paymentID'");
      if(mysqli_num_rows($mysqli_is_run2)<=0){
          echo '<input type="button" id="saveCulture25" onclick="savealldata(' . $paymentID . ')"  name="' . $paymentID . '" value="Save results" />';
      }
          if($Validate=="No" && $checkIfAllowedValidateyy=="yes"){
        echo '<input type="button" id="saveCulture7" onclick="validate_patient_culture('. $paymentID .')"  name="' . $paymentID . '" value="Validate" />';
       }
       if($Submitted=='No'){
        echo '<input type="button" id="saveCulture23" onclick="send_patient_culture_result(' . $paymentID . ')"  name="' . $paymentID . '" value="Send" /></td></tr>';

        }
<?php
include("./header.php");
include("../includes/connection.php");
include('./opticfunction.php');

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $employeeId = $_SESSION['userinfo']['Employee_ID'];
}

$Employee_ID = $employeeId;

if (isset($_GET['guarantorName'])) {
    $Guarantor_Name = $_GET['guarantorName'];
}

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
}
if (isset($_GET['patientId'])) {

    $patientId = mysqli_real_escape_string($conn,trim($_GET['patientId']));
}
$Registration_ID = $patientId;

if (isset($_GET['consultation_ID'])) {

    $consultation_ID = mysqli_real_escape_string($conn,trim($_GET['consultation_ID']));
}

if (isset($_GET['this_page_from'])) {

    $this_page_from = mysqli_real_escape_string($conn,trim($_GET['this_page_from']));
}

if (isset($_GET['Admision_ID'])) {
    $admissionId = $_GET['Admision_ID'];
}

include('excute_result.php');
?>


<?php
if (@$this_page_from == "doctor_inpatient") {
    ?>
  <!-- <a href="../doctorspageinpatientwork.php?Registration_ID=<?= $patientId ?>&this_page_from='doctor_inpatient'&consultation_ID=<?= $consultation_ID ?>" class="art-button-green">BACK</a> -->
<?php

} else if (@$this_page_from == "doctor_outpatient") {
    ?>
  <!-- <a href="../doctorspageoutpatientwork.php?Registration_ID=<?= $patientId ?>&this_page_from='doctor_inpatient'&consultation_ID=<?= $consultation_ID ?>" class="art-button-green">BACK</a> -->
<?php 
} else { ?>
  <!-- <a href="../nursecommunicationpage.php?Registration_ID=<?= $patientId ?>&consultation_ID=<?= $consultation_ID ?>&Admision_ID=<?= $admissionId ?>" class="art-button-green">BACK</a> -->
  <?php 
}
?>

<!-- body of the optic -->
<style media="screen">
  table{
    margin-top: 15px !important;
      border-collapse: collapse;
      border:none !important;
  }

  select option{
    background:#1E1E1E;
  }

  select option:hover{
    background-color:orange !important;
  }
  select option{
    padding-top:2px;
    padding-bottom:2px
    height:20px;
    background:#1E1E1E;
    color:#D7BA6E;
    font-size:14px;
  }

  table tr,td{
    border-collapse: collapse;
    border:none !important;
  }

.title{
  font-weight: bold;
  font-size: 20px;
  text-align:left;
  padding-left:10px;
  margin-top:10px;
}

.select-input{
  height:30px;
  width:100%;
}

#Items_Div_Area{
  margin-top:15px;
  background:#fff;
}
#refraction{
  width:90%;
}

#refrection .input{
  width:9%;
  table-layout:fixed;
}

.sides{
  width:49%;
  display:inline-block;
  vertcal-align:top;
}

.additems{
  margin-top:15px;
  float:right;
  display:block;
}
.art-button{
  height:40px !important;
  color:#fff !important;
}

.spec{
  margin-top:10px;
  /* display:inline-block; */
}
</style>

<fieldset>


<input type="hidden" id="patient_id" value="<?= $patientId; ?>">

  <legend align="center" style="font-weight:bold;width:20%">
  <div style=''>
      <p style='text-align:center'>O P T I C A L</p>
      <p style='color:yellow;text-align:center'><?= $Guarantor_Name . ' ' ?><?= $todayDate; ?></p>
      
      </div></legend>
  <div class="title">
    Patient Information
  </div>
  <table width="100%">
    <tr>
      <td>Patient Name</td>
      <td>
        <input type="text" name="patient_name" value="<?= getPatientName($patientId) ?>" id="patient_name" placeholder="Patient Name" style="padding-left:10px;">
      </td>

      <td style="text-align:right">
        Patient Notes Ref:
      </td>
      <td>
        <input type="text" name="notes_ref" value="" id='note_ref' placeholder="Notes Ref">
      </td>
    </tr>
    <tr>
      <td>Addresss</td>
      <td>
        <input type="text" name="address" value="<?= getPatientAddress($patientId) ?>" id="address" placeholder="Address" style="padding-left:10px;">
      </td>
      <td style="text-align:right">Surgeon:</td>
      <td>
        <input type="text" name="surgeon" value="" id="surgeon" placeholder="Surgeon">
      </td>
    </tr>
    <tr>
      <td>For Paying/Private: Receipt No:</td>
      <td>
        <input type="text" name="receipt_no" value="" placeholder="receipt_no" id="receipt_no">
      </td>
      <td style="text-align:right">Date</td>
      <td>
        <input type="text" name="date" value="<?= $today ?>" id="date" placeholder="Date">
      </td>
    </tr>
    <tr>
    <!-- <td>Bill Type</td>
    <td id="Billing_Type_Area">
                    <select name='Billing_Type' id='Billing_Type' onchange="Sponsor_Warning()">
                        <?php
                        $select_bill_type = mysqli_query($conn,
                            "SELECT Billing_Type
    								  from tbl_departmental_items_list_cache alc
    								  where alc.Employee_ID = '$Employee_ID' and
    								  Registration_ID = '$Registration_ID' LIMIT 1"
                        ) or die(mysqli_error($conn));

                        $no_of_items = mysqli_num_rows($select_bill_type);
                        if ($no_of_items > 0) {
                            while ($data = mysqli_fetch_array($select_bill_type)) {
                                $Billing_Type = $data['Billing_Type'];
                            }
                            echo "<option selected='selected'>" . $Billing_Type . "</option>";
                        } else {
                            if (strtolower($Guarantor_Name) == 'cash') {
                                echo "<option selected='selected'>Inpatient Cash</option>";
                            } else {
                                echo "<option selected='selected'>Inpatient Credit</option> 
    						      <option>Inpatient Cash</option>";
                            }
                        }
                        ?>
                            </select>
      </td> -->

      <!-- <td style='text-align: right;'>Claim Form Number</td>
                <td>
                    <?php //if (isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) != 'yes') { ?>
                                <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' readonly="readonly" value="<?php //echo $Claim_Form_Number; ?>">
                    <?php //} else { ?>
                                <input type='text' name='Claim_Form_Number' id='Claim_Form_Number' placeholder='Claim Form Number' autocomplete='off' readonly="readonly" value="<?php //echo $Claim_Form_Number; ?>">
                    <?php //} ?>
                </td> -->

    </tr>
  </table>
</fieldset>
<br />




<div class="sidesone">
<center>
<fieldset>

  <div class="title">
    VA
    </div>
    <!-- <fieldset> -->
    <table id='refraction'>
    <tr>
    <td></td>
    <td>RE</td>
    <td>LE</td>
    </tr>

      <tr>
        <td>VAU</td>
        <td class="input">
        <select name="vau_right" id="vau_right"  id='refraction' class="select-input">
        <?php
        if (!empty($vaRgihtEye[0]['vau'])) {
            ?>
          <option value="<?= $vaRgihtEye[0]['vau'] ?>"><?= $vaRgihtEye[0]['vau'] ?></option>
          <?php 
        } else {
            ?>
        <option value=""></option>
        <?php 
    } ?>
          <option value="6/6">6</option>
          <option value="6/9">6/9</option>
          <option value="6/12">6/12</option>
          <option value="6/18">6/18</option>
          <option value="6/24">6/24</option>
          <option value="6/36">6/36</option>
          <option value="6/60">6/60</option>
          <option value="5/60">5/60</option>
          <option value="4/60">4/60</option>
          <option value="3/60">3/60</option>
          <option value="2/60">2/60</option>
          <option value="1/60">1/60</option>
          <option value="HM">HM</option>
          <option value="LP">LP</option>
        </select>
        </td>

        <td class="input">
        <select name="vau_left" id="vau_left" class="select-input">

        <?php
        if (!empty($vaLeftEye[0]['ph'])) {
            ?>
          <option value="<?= $vaLeftEye[0]['ph'] ?>"><?= $vaLeftEye[0]['ph'] ?></option>
          <?php 
        } else {
            ?>
        <option value=""></option>
        <?php 
    } ?>
          <option value="6/6">6/6</option>
          <option value="6/9">6/9</option>
          <option value="6/12">6/12</option>
          <option value="6/18">6/18</option>
          <option value="6/24">6/24</option>
          <option value="6/36">6/36</option>
          <option value="6/60">6/60</option>
          <option value="5/60">5/60</option>
          <option value="4/60">4/60</option>
          <option value="3/60">3/60</option>
          <option value="2/60">2/60</option>
          <option value="1/60">1/60</option>
          <option value="HM">HM</option>
          <option value="LP">LP</option>
        </select> </td>
      </tr>

       <tr>
        <td width="5%">PH</td>
        <td class="input">
        <select name="ph_right" id="ph_right" class="select-input">
        <?php
        if (!empty($vaRgihtEye[0]['ph'])) {
            ?>
          <option value="<?= $vaRgihtEye[0]['ph'] ?>"><?= $vaRgihtEye[0]['ph'] ?></option>
          <?php 
        } else {
            ?>
        <option value=""></option>
        <?php 
    } ?>
          <option value="6/6">6/6</option>
          <option value="6/9">6/9</option>
          <option value="6/12">6/12</option>
          <option value="6/18">6/18</option>
          <option value="6/24">6/24</option>
          <option value="6/36">6/36</option>
          <option value="6/60">6/60</option>
          <option value="5/60">5/60</option>
          <option value="4/60">4/60</option>
          <option value="3/60">3/60</option>
          <option value="2/60">2/60</option>
          <option value="1/60">1/60</option>
          <option value="HM">HM</option>
          <option value="LP">LP</option>
        </select>
        </td>

        <td class="input">
        <select name="ph_left" id="ph_left" class="select-input">
        <?php
        if (!empty($vaLeftEye[0]['ph'])) {
            ?>
          <option value="<?= $vaLeftEye[0]['ph'] ?>"><?= $vaLeftEye[0]['ph'] ?></option>
          <?php 
        } else {
            ?>
        <option value=""></option>
        <?php 
    } ?>
          <option value="6/6">6/6</option>
          <option value="6/9">6/9</option>
          <option value="6/12">6/12</option>
          <option value="6/18">6/18</option>
          <option value="6/24">6/24</option>
          <option value="6/36">6/36</option>
          <option value="6/60">6/60</option>
          <option value="5/60">5/60</option>
          <option value="4/60">4/60</option>
          <option value="3/60">3/60</option>
          <option value="2/60">2/60</option>
          <option value="1/60">1/60</option>
          <option value="HM">HM</option>
          <option value="LP">LP</option>
        </select> </td>
      </tr>

      <tr>
      <td width="5%">Glasses</td>
        <td class="input">
        <select name="glasses-right" id="glasses_right" class="select-input">
        <?php
        if (!empty($vaRgihtEye[0]['glasses'])) {
            ?>
          <option value="<?= $vaRgihtEye[0]['glasses'] ?>"><?= $vaRgihtEye[0]['glasses'] ?></option>
          <?php 
        } else {
            ?>
        <option value=""></option>
        <?php 
    } ?>
          <option value="6/6">6/6</option>
          <option value="6/9">6/9</option>
          <option value="6/12">6/12</option>
          <option value="6/18">6/18</option>
          <option value="6/24">6/24</option>
          <option value="6/36">6/36</option>
          <option value="6/60">6/60</option>
          <option value="5/60">5/60</option>
          <option value="4/60">4/60</option>
          <option value="3/60">3/60</option>
          <option value="2/60">2/60</option>
          <option value="1/60">1/60</option>
          <option value="HM">HM</option>
          <option value="LP">LP</option>
        </select>
         </td>
        <td class="input">

      <select name="glasses-left" id="glasses_left" class="select-input">
      <?php
        if (!empty($vaLeftEye[0]['glasses'])) {
            ?>
          <option value="<?= $vaLeftEye[0]['glasses'] ?>"><?= $vaLeftEye[0]['glasses'] ?></option>
          <?php 
        } else {
            ?>
        <option value=""></option>
        <?php 
    } ?>
          <option value="6/6">6/6</option>
          <option value="6/9">6/9</option>
          <option value="6/12">6/12</option>
          <option value="6/18">6/18</option>
          <option value="6/24">6/24</option>
          <option value="6/36">6/36</option>
          <option value="6/60">6/60</option>
          <option value="5/60">5/60</option>
          <option value="4/60">4/60</option>
          <option value="3/60">3/60</option>
          <option value="2/60">2/60</option>
          <option value="1/60">1/60</option>
          <option value="HM">HM</option>
          <option value="LP">LP</option>
        </select>
        </td>
      </tr>

    </table>
      <!-- </fieldset> -->

</div>
    </div>
</fieldset>
</center>
<br />


<div class="">

<fieldset>
<div class="title">
Refraction
</div>

<table width="100%">
  <tr>
    <th colspan="4" style="text-align:center">RIGHT</th>
    <th colspan="3" style="text-align:center">LEFT</th>
  </tr>
  <tr>
  <td></td><td style="text-align:center">SPH</td>
  <td style="text-align:center">CYL</td>
  <td style="text-align:center">Prism Base</td>
  <td style="text-align:center">SPH</td>
  <td style="text-align:center">CYL</td>
  <td style="text-align:center">Prism Base</td>
  </tr>

  <tr>

  <td style="text-align:right">Distance Reading</td>
  <td>
    <input type="text" name="distance-sph-right" value="<?php if (!empty($responseDistanceReadingRight[0]['sph'])) echo $responseDistanceReadingRight[0]['sph']; ?>" style="padding-left:10px;" class="distance-reading-right">
  </td>
  <td>
    <input type="text" name="distance-cyl-right" value="<?php if (!empty($responseDistanceReadingRight[0]['cyl'])) echo $responseDistanceReadingRight[0]['cyl']; ?>"  class="distance-reading-right" style="padding-left:10px;">
  </td>
  <td>
    <input type="text" name="distance-prism-right" value="<?php if (!empty($responseDistanceReadingRight[0]['prism_base'])) echo $responseDistanceReadingRight[0]['prism_base']; ?>"  class="distance-reading-right" style="padding-left:10px;">
  </td>
  <td>
    <input type="text" name="distance-sph-left" value="<?php if (!empty($responseDistanceReadingLeft[0]['sph'])) echo $responseDistanceReadingLeft[0]['sph']; ?>"  class="distance-reading-left">
  </td>
  <td><input type="text" name="distance-cyl-left" value="<?php if (!empty($responseDistanceReadingLeft[0]['cyl'])) echo $responseDistanceReadingLeft[0]['cyl']; ?>"  class="distance-reading-left"></td>
  <td><input type="text" name="distance-prism-left" value="<?php if (!empty($responseDistanceReadingLeft[0]['prism_base'])) echo $responseDistanceReadingLeft[0]['prism_base']; ?>"  class="distance-reading-left"></td>

  </tr>

  <tr>
  <td style="text-align:right">Spectacle Distance</td>
  <td><input type="text" value="<?php if (!empty($spectacleDistanceRight[0]['sph'])) echo $spectacleDistanceRight[0]['sph']; ?>" class="spectacle-right" name="spectacle-sph-right"></td>
  <td><input type="text" value="<?php if (!empty($spectacleDistanceRight[0]['cyl'])) echo $spectacleDistanceRight[0]['cyl']; ?>" class="spectacle-right" name="spectacle-cyl-right"></td>
  <td><input type="text" value="<?php if (!empty($spectacleDistanceRight[0]['prism_base'])) echo $spectacleDistanceRight[0]['prism_base']; ?>" class="spectacle-right" name="spectacle-prism-right"></td>
  <td><input type="text" value="<?php if (!empty($spectacleDistanceLeft[0]['sph'])) echo $spectacleDistanceLeft[0]['sph']; ?>" class="spectacle-left" name="spectacle-sph-left"></td>
  <td><input type="text" value="<?php if (!empty($spectacleDistanceLeft[0]['cyl'])) echo $spectacleDistanceLeft[0]['cyl']; ?>" class="spectacle-left" name="spectacle-cyl-left"></td>
  <td><input type="text" value="<?php if (!empty($spectacleDistanceLeft[0]['prism_base'])) echo $spectacleDistanceLeft[0]['prism_base']; ?>" class="spectacle-left" name="spectacle-prism-left"></td>
  </tr>

  <tr>
  <td style="text-align:right">I.P. Distance </td>
  <td><input type="text" name="ip-sph-right"  value="<?php if (!empty($ipDistanceRight[0]['sph'])) echo $ipDistanceRight[0]['sph']; ?>" style="padding-left:10px;" class="ip-right"></td>
  <td><input type="text" name="ip-cyl-right"  value="<?php if (!empty($ipDistanceRight[0]['cyl'])) echo $ipDistanceRight[0]['cyl']; ?>" style="padding-left:10px;" class="ip-right"></td>
  <td><input type="text" name="ip-prism-right"  value="<?php if (!empty($ipDistanceRight[0]['prism_base'])) echo $ipDistanceRight[0]['prism_base']; ?>" style="padding-left:10px;" class="ip-right"></td>
  <td><input type="text" name="ip-sph-left"  value="<?php if (!empty($ipDistanceLeft[0]['sph'])) echo $ipDistanceLeft[0]['sph']; ?>" style="padding-left:10px;" class="ip-left"></td>
  <td><input type="text" name="ip-cyl-left"  value="<?php if (!empty($ipDistanceLeft[0]['cyl'])) echo $ipDistanceLeft[0]['cyl']; ?>" style="padding-left:10px;" class="ip-left"></td>
  <td><input type="text" name="ip-prism-left"  value="<?php if (!empty($ipDistanceLeft[0]['prism_base'])) echo $ipDistanceLeft[0]['prism_base']; ?>" style="padding-left:10px;" class="ip-left"></td>
  </tr>

</table>


<div style="margin-top:15px; width:100%" class="spec">
    <span style="margin-right:10px;">
      <strong>Spectacle Offered:</strong>
    </span>

    <label>
    <span style="margin-right:0px;">
      <input type="radio" name="offer-spectacle" value="yes" class="offer-spectacle" <?php if (!empty($sepctacleStatus) && $sepctacleStatus == "yes") echo "checked"; ?>
    </span>
    <span>YES</span>
    </label>

    <label>
    <span class="">
      <input type="radio" name="offer-spectacle" value="no" class="offer-spectacle" <?php if (!empty($sepctacleStatus) && $sepctacleStatus == "no") echo "checked"; ?> >
    </span>
    <span>NO</span>
    </label>

  </div>
<div style="" class="spec">
    <input type="hidden" name="spectaclestatus" value="<?= $sepctacleStatus; ?>" id="spectaclestatus">
    <?php
    if (!empty($sepctacleStatus) && $sepctacleStatus == "no") {
        ?>
          <input type="text" id="reason" placeholder="reasons" style="margin-left:10%;padding-left:5px; height:40px; width:90%;" value="<?php if (!empty($noSpectacleReason)) echo $noSpectacleReason; ?>">

     <?php

} else {
    ?>
      <input type="text" id="reason" placeholder="reasons" style="margin-left:10%;padding-left:5px; height:40px; width:90%;" value="<?php if (!empty($noSpectacleReason)) echo $noSpectacleReason; ?>">


    <?php 
}
?>

</div>

<table width="100%">

  <tr>
  <td width="10%">
    <strong>Signed By:</strong>
  </td>
  
  <input type="hidden" id="Consultant_ID" value="<?= getEmpleyeeName($employeeId) ?>">
  
  <td width="80%">
    <input type="text" name="employee_name" value="<?= getEmpleyeeName($employeeId) ?>" style="padding-left:10px;">
  </td>
  </tr>
  </table>

  <!-- <div class="additems">
    <button class="art-button-green add-items" id="Validate_Type_Of_Check_In" >ADD ITEMS</button>
  </div> -->

<br />

<!-- <div style="margin-top:50px; ">
			<?php if (isset($_GET['Sub_Department_ID'])) {
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    } else {
        $Sub_Department_ID = 0;
    } ?>
                        <fieldset id="Picked_Items_Fieldset"  style='overflow-y: scroll;height: 190px;background:#fff;'>
                            <center>
                                <table width =100% border=0>
                                    <tr id="thead">
                                        <td style="" width=5%><b>Sn</b></td>
                                        <td><b>Item Name</b></td>
                                        <td width="12%"><b>Location</b></td>
                                        <td style="text-align: left;" width=17%><b>Comment</b></td>
                                        <td style="text-align: right;" width=8%><b>Price</b></td>
                                        <td style="text-align: right;" width=8%><b>Quantity</b></td>
                                        <td style="text-align: right;" width=8%><b>Sub Total</b></td>
                                        <td style="text-align: center;" width=6%><b>Action</b></td></tr>
                                    <?php
                                    $temp = 0;
                                    $total = 0;
                                    $select_Transaction_Items = mysqli_query($conn,
                                        "SELECT Item_Cache_ID, Product_Name, Price, Quantity,Registration_ID,Comment,Sub_Department_ID
                                                from tbl_items t, tbl_departmental_items_list_cache alc
                                                where alc.Item_ID = t.Item_ID and
                                                alc.Employee_ID = '$Employee_ID' and
                                                Registration_ID = '$Registration_ID'"
                                    ) or die(mysqli_error($conn));

                                    $no_of_items = mysqli_num_rows($select_Transaction_Items);
                                    while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                                        $Temp_Sub_Department_ID = $row['Sub_Department_ID'];
                                        //get sub department name
                                        $select_sub_department = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Temp_Sub_Department_ID'") or die(mysqli_error($conn));
                                        $my_num = mysqli_num_rows($select_sub_department);
                                        if ($my_num > 0) {
                                            while ($rw = mysqli_fetch_array($select_sub_department)) {
                                                $Sub_Department_Name = $rw['Sub_Department_Name'];
                                            }
                                        } else {
                                            $Sub_Department_Name = '';
                                        }
                                        echo "<tr><td>" . ++$temp . "</td>";
                                        echo "<td>" . $row['Product_Name'] . "</td>";
                                        echo "<td>" . $Sub_Department_Name . "</td>";
                                        echo "<td>" . $row['Comment'] . "</td>";
                                        echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'], 2) : number_format($row['Price'])) . "</td>";
                                        echo "<td style='text-align:right;'>" . $row['Quantity'] . "</td>";
                                        echo "<td style='text-align:right;'>" . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Price'] * $row['Quantity'], 2) : number_format($row['Price'] * $row['Quantity'])) . "</td>";
                                        ?>
                                        <td style="text-align: center;">
                                            <input type='button' style='color: red; font-size: 10px;' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Item_Cache_ID']; ?>,<?php echo $row['Registration_ID']; ?>)'>
                                        </td>
    <?php
    $total = $total + ($row['Price'] * $row['Quantity']);
}
echo "</tr></table>";
?>
                                    </fieldset>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td style='text-align: right; width: 70%;' id='Total_Area'>
                                            <h4>Total : <?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code']; ?></h4>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </fieldset>

</div> -->


  <div id="Items_Div_Area" style="margin-top:10px">

  </div>


  <div id="ePayment_Window" style="width:50%;" >
    <span id='ePayment_Area'>
        
    </span>
</div>

<!-- <div id="Send_To_Cashier_Warning">
    <center>No Items Selected</center>
</div>

<div id="Sponsor_Warning">
    <center>The Bill type selected, patient will pay cash. <br/>Are you sure?</center><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" value="YES" onclick="Response('yes')" class="art-button-green">
                <input type="button" value="NO" onclick="Response('no')" class="art-button-green">
            </td>
        </tr>
    </table> -->
</div>

</fieldset>

</div>
<br />

<center>

<div  style="margin-top:15px;">
  <input type="button" class="art-button-green" id='save' style="width:20%; height:30px; margin-top:15px;" value="SAVE DATA">
</div>

</center>

<center>
  <!-- <div style='margin-top:12px;' id="files"></div> -->
</center>
<input type="hidden" name="check-in-type" value="OPTHALMOLOGY" id="Type_Of_Check_In">
<input type="hidden" id="Clinic_ID" name="clinic_id" value="10">
<?php
include("../includes/footer.php");
?>

 <script src="../js/optic.js"></script>

 <script type='text/javascript'>

  $(document).ready(function(){
    $("#Items_Div_Area").dialog({ autoOpen: false, width: 950, height: 450, title: 'ADD NEW ITEM', modal: true });
  })    
    $("#Validate_Type_Of_Check_In").click(function(){
      var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
        if(Type_Of_Check_In == '' || Type_Of_Check_In == null){
            document.getElementById("Type_Of_Check_In").style = 'border: 3px solid red';
            document.getElementById("Type_Of_Check_In").focus();
        }else{
            document.getElementById("Type_Of_Check_In").style = 'border: 3px white';
            Refresh_Items_Div();
            //openItemDialog();
        }
    })
   
      function Validate_Type_Of_Check_In() {
        // var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
        // if (Type_Of_Check_In == '' || Type_Of_Check_In == null) {
        //     document.getElementById("Type_Of_Check_In").style = 'border: 3px solid red';
        //     document.getElementById("Type_Of_Check_In").focus();
        // } else {
        //     document.getElementById("Type_Of_Check_In").style = 'border: 3px white';
        //     var Clinic_ID = document.getElementById("Clinic_ID").value;
        //     if (Clinic_ID == '' || Clinic_ID == null) {
        //         alert("Select clinic first")
        //         exit;
        //     }
            // openItemDialog();
          alert("malopa")
        // }
    }


  //  });

  

   function Refresh_Items_Div() {
    var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
        var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
        if(window.XMLHttpRequest) {
            myObjectRefreshDiv = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectRefreshDiv = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefreshDiv.overrideMimeType('text/xml');
        }
        //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
        myObjectRefreshDiv.onreadystatechange = function (){
            data999 = myObjectRefreshDiv.responseText;
            if (myObjectRefreshDiv.readyState == 4) { 
                document.getElementById('Items_Div_Area').innerHTML = data999;
                clearContent();
                openItemDialog();
            }
        }; //specify name of function that will handle server response........
          
        myObjectRefreshDiv.open('GET','../Refresh_Optical_Payments_Div.php?Type_Of_Check_In='+Type_Of_Check_In+'&Guarantor_Name='+Guarantor_Name,true);
        myObjectRefreshDiv.send();
    }


     function clearContent() {
        document.getElementById("Quantity").value = '';
        document.getElementById("Item_Name").value = '';
        document.getElementById("Item_ID").value = '';
        document.getElementById("Price").value = '';
        document.getElementById("Comment").value = '';
        document.getElementById("Search_Value").value = '';
    }

    function openItemDialog(){
      $("#Items_Div_Area").dialog("open");
   }


function Get_Item_Name(Item_Name,Item_ID){
	    document.getElementById('Quantity').value = '';
	    document.getElementById('Comment').value = '';

	    var Temp = '';
	    if(window.XMLHttpRequest) {
            myObjectGetItemName = new XMLHttpRequest();
	    }else if(window.ActiveXObject){
    		myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
    		myObjectGetItemName.overrideMimeType('text/xml');
	    }
	    
	    document.getElementById("Item_Name").value = Item_Name;
	    document.getElementById("Item_ID").value = Item_ID;
        document.getElementById("Quantity").value = 1;
        //document.getElementById("Quantity").focus();
  }
  


    function Get_Item_Price(Item_ID,Guarantor_Name){
      var Billing_Type = document.getElementById("Billing_Type").value;
      if(window.XMLHttpRequest) {
	  myObject = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObject.overrideMimeType('text/xml');
      }
      //document.location = "./Get_Items_Price.php?Item_Name="+Item_Name;
      myObject.onreadystatechange = function (){
	  data = myObject.responseText;
	  
	  if (myObject.readyState == 4) { 
	      document.getElementById('Price').value = data;
	      //alert(data);
	  }
      }; //specify name of function that will handle server response........
      
      myObject.open('GET','../Get_Items_Price.php?Item_ID='+Item_ID+'&Guarantor_Name='+Guarantor_Name+'&Billing_Type='+Billing_Type,true);
      myObject.send();
  }


  function Get_Selected_Item() {
                                var Billing_Type = document.getElementById("Billing_Type").value;
                                var Item_ID = document.getElementById("Item_ID").value;
                                var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
                                var Quantity = document.getElementById("Quantity").value;
                                var Registration_ID = <?php echo $Registration_ID; ?>;
                                var consultation_ID = '<?php echo $consultation_ID; ?>';
                                var Comment = document.getElementById("Comment").value;
                                var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
                                var Consultant_ID = document.getElementById("Consultant_ID").value;
                                // var Claim_Form_Number = document.getElementById("Claim_Form_Number").value;
                                var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;
                                var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
                                var Price =document.getElementById("Price").value ;

                                if (Registration_ID != '' && Registration_ID != null && Item_Name != '' && Item_Name != null && Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Billing_Type != '' && Billing_Type != null && Type_Of_Check_In != null && Type_Of_Check_In != '') {

                                    if (Sub_Department_ID == '') {
                                        alert("Select location");
                                        exit;
                                    }

                                    if (parseFloat(Price) > 0) {
                                       
                                    }else{
                                         alert('Process fail!. Item missing price.');
                                        exit;
                                    }

                                    if (window.XMLHttpRequest) {
                                        myObject2 = new XMLHttpRequest();
                                    } else if (window.ActiveXObject) {
                                        myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                                        myObject2.overrideMimeType('text/xml');
                                    }
                                    myObject2.onreadystatechange = function () {
                                        data = myObject2.responseText;

                                        if (myObject2.readyState == 4) {
                                            //alert("One Item Added");
                                            document.getElementById('Picked_Items_Fieldset').innerHTML = data;
                                            //update_Billing_Type(Registration_ID);
                                            //Update_Claim_Form_Number();
                                            document.getElementById("Item_Name").value = '';
                                            document.getElementById("Item_ID").value = '';
                                            document.getElementById("Quantity").value = '';
                                            document.getElementById("Price").value = '';
                                            document.getElementById("Comment").value = '';
                                            document.getElementById("Search_Value").focus();
                                            alert("Item Added Successfully");
                                            update_billing_type(Registration_ID);
                                            update_total();
                                            update_process_buttons(Registration_ID);
                                        }
                                    }; //specify name of function that will handle server response........

                                    
                                    myObject2.open('GET', '../Optical_Add_Selected_Item.php?Registration_ID=' + Registration_ID + '&Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Consultant_ID=' + Consultant_ID + '&Billing_Type=' + Billing_Type + '&Guarantor_Name=' + Guarantor_Name + '&Sponsor_ID=' + Sponsor_ID + '&Claim_Form_Number=' + Claim_Form_Number + '&Billing_Type=' + Billing_Type + '&Comment=' + Comment + '&Sub_Department_ID=' + Sub_Department_ID + '&Type_Of_Check_In=' + Type_Of_Check_In, true);
                                    myObject2.send();

                                } else if (Registration_ID != '' && Registration_ID != null && (Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) != '' && Quantity != '' && Quantity != null) {
                                    alertMessage();
                                } else {
                                    if (Quantity == '' || Quantity == null) {
                                        document.getElementById("Quantity").focus();
                                        document.getElementById("Quantity").style = 'border: 3px solid red';
                                    }
                                }
                            }


    function getItemsListFiltered(Item_Name){
      document.getElementById("Item_Name").value = '';
      document.getElementById("Item_ID").value = '';
      document.getElementById("Comment").value = '';
      document.getElementById("Quantity").value = '';
      var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
      var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
      var Item_Category_ID = document.getElementById("Item_Category_ID").value;
      if (Item_Category_ID == '' || Item_Category_ID == null) {
	  Item_Category_ID = 'All';
      }
      
      if(window.XMLHttpRequest) {
	  myObject = new XMLHttpRequest();
      }else if(window.ActiveXObject){ 
	  myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	  myObject.overrideMimeType('text/xml');
      }
  
      myObject.onreadystatechange = function (){
	 data135 = myObject.responseText;
	 if (myObject.readyState == 4) {
	     //document.getElementById('Approval').readonly = 'readonly';
	     document.getElementById('Items_Fieldset').innerHTML = data135;
	 }
      }; //specify name of function that will handle server response........
      myObject.open('GET','../Optical_Get_List_Of_Departmental_Filtered_Items.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name+'&Guarantor_Name='+Guarantor_Name+'&Type_Of_Check_In='+Type_Of_Check_In,true);
      myObject.send();
   }
 </script>

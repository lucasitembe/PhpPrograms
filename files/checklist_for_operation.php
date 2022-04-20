<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    if(!isset($_SESSION['userinfo'])){
      	@session_destroy();
      	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
//	if(isset($_SESSION['userinfo']['Admission_Works'])){
//	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
//		header("Location: ./index.php?InvalidPrivilege=yes");
//	    }
//	}else{
//	    header("Location: ./index.php?InvalidPrivilege=yes");
//	}
    }else{
	       @session_destroy();
	       header("Location: ../index.php?InvalidPrivilege=yes");

    }

    if(isset($_SESSION['userinfo'])){

      $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }


    if(isset($_GET['Registration_ID'])){
      $Registration_ID = $_GET['Registration_ID'];
    }else{
      $Registration_ID = 0;
    }

    if(isset($_GET['Admision_ID'])){
      $Admision_ID = $_GET['Admision_ID'];
    }else{
      $Admision_ID = 'NULL';
    }

    if(isset($_GET['consultation_ID'])){
      $consultation_ID = $_GET['consultation_ID'];
    }else{
      $consultation_ID = 0;
    }

    if(isset($_GET['Patient_Payment_Item_List_ID'])){
      $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
      $Patient_Payment_Item_List_ID = 0;
    }


    // <!-- new date function (Contain years, Months and days)-->
    $Today = "";
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
    //<!-- end of the function -->



// $item_list_id = explode(',',$Patient_Payment_Item_List_ID);
// $item_list_id = $item_list_id[0];



  $sql = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,
            pp.Patient_Payment_ID,sp.Guarantor_Name,ppl.Patient_Direction,ppl.Consultant_ID,em.Employee_Type,em.Employee_Name,c.Doctor_Comment,i.Product_Name
            FROM tbl_patient_payment_item_list ppl
            INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
            INNER JOIN tbl_item_list_cache c ON ppl.Patient_Payment_ID = c.Patient_Payment_ID
            INNER JOIN tbl_items i ON i.Item_ID = c.Item_ID
            JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
            JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
            LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID
            WHERE
            ppl.Nursing_Status='served' AND
           ppl.Patient_Payment_Item_List_ID IN ($Patient_Payment_Item_List_ID) ORDER BY ppl.Patient_Payment_Item_List_ID DESC";

//die($sql);
           $comment_from_doctor = mysqli_query($conn,"SELECT c.Doctor_Comment FROM tbl_item_list_cache c
                                  INNER JOIN tbl_patient_payment_item_list p ON p.Patient_Payment_ID = c.Patient_Payment_ID
                                  WHERE p.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'");

           $comment_from_doctor1 = mysqli_fetch_assoc($comment_from_doctor)['Doctor_Comment'];




           // $Paracetemol = "SELECT pr.Registration_ID, ppl.Consultant_ID,em.Employee_Type,em.Employee_Name,c.Doctor_Comment,i.Product_Name
           //        FROM tbl_patient_payment_item_list ppl
           //        INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
           //        INNER JOIN tbl_item_list_cache c ON ppl.Patient_Payment_ID = c.Patient_Payment_ID
           //        INNER JOIN tbl_items i ON i.Item_ID = c.Item_ID
           //        JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
           //        JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
           //        LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID
           //        WHERE
           //        ppl.Nursing_Status='served' AND i.Product_Name like '%Paracetemol%'
           //        Patient_Payment_Item_List_ID=' $Patient_Payment_Item_List_ID'";



$select_Patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));

$no = mysqli_num_rows($select_Patient);

//$array = array();
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Patient)) {
        $Registration_ID = $row['Registration_ID'];
        $Patient_Name = $row['Patient_Name'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Patient_Payment_ID = $row['Patient_Payment_ID'];
        $Patient_Direction = $row['Patient_Direction'];
        $Consultant_ID = $row['Consultant_ID'];
        $Consultant = $row['Employee_Name'];
        $Employee_Type = $row['Employee_Type'];
        $doctor_comment = $row['Doctor_Comment'];
        $product_name = $row['Product_Name'];

        //$array[]=$row;
    }

  //  print_r($array);


    
 } 
//else {
//     $Registration_ID = '';
//     $Patient_Name = '';
//     $Date_Of_Birth = '';
//     $Gender = '';
//     $Guarantor_Name = '';
//     $Patient_Direction = '';
//     $Patient_Payment_ID = '';
//     $Consultant_ID = '';
//     $Consultant = '';
//     $Employee_Type = '';
//     $Age = '';
// }





// select vital signs data
 // $nurse_details2 = mysqli_query($conn,"SELECT * FROM tbl_nurse n RIGHT JOIN tbl_nurse_vital nv ON n.Nurse_ID = nv.Nurse_ID
 //                                      WHERE
 //                                      n.Registration_ID ='$Registration_ID'
 //                                      AND   n.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'
 //                                      ORDER BY Nurse_DateTime DESC LIMIT 11
 //                                      ") or die(mysqli_error($conn));
 //  $weight = "";
 //  $allergies = "";
 //  while($vt = mysqli_fetch_assoc($nurse_details2))
 //  {
 //    // weight
 //    if($vt['Vital_ID'] == 2)
 //    {
 //      $weight = $vt['Vital_Value'];
 //    }
 //
 //     $allergies = $vt['Allegies'];
 //
 //  }

$nurse_details = mysqli_query($conn,"SELECT body_weight  FROM tbl_nursecommunication_observation
                                     WHERE  Registration_ID ='$Registration_ID' AND Patient_Payment_Item_List_ID IN($Patient_Payment_Item_List_ID)  ORDER BY date DESC LIMIT 1") or die(mysqli_error($conn));


  $weight = "";
  while($vt = mysqli_fetch_assoc($nurse_details))
  {
    // weight
      $weight = $vt['body_weight'];
  }


  // operation to be DONE
  // $operation_tobe_done1 = mysqli_query($conn,"SELECT i.Product_Name FROM tbl_items i INNER JOIN tbl_patient_payment_item_list p ON i.Item_ID = p.Item_ID
  //                                            WHERE p.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
  $operation_tobe_done1 = mysqli_query($conn,"SELECT i.Product_Name,p.Patient_Payment_Item_List_ID FROM tbl_items i INNER JOIN tbl_patient_payment_item_list p ON i.Item_ID = p.Item_ID
                                             WHERE p.Patient_Payment_Item_List_ID IN($Patient_Payment_Item_List_ID)") or die(mysqli_error($conn));



   //$operation_tobe_done = mysqli_fetch_assoc($operation_tobe_done1) ['Product_Name'];



  // die("SELECT * FROM tbl_checklist_for_operation WHERE Registration_ID = '$Registration_ID' AND Patient_Payment_Item_List_ID = '<div id=queryDiv></div>'");

   $select_operation = mysqli_query($conn,"SELECT * FROM tbl_checklist_for_operation WHERE Registration_ID = '$Registration_ID' ORDER BY Created_date DESC LIMIT 1") or die(mysqli_error($conn));

   while ($r = mysqli_fetch_assoc($select_operation)) {
      $estimated_blood_loss = $r['Estimated_Blood_Loss'];
      $team_brief = $r['Patient_Discussed_At_Team_Brief'];
      $blood_transfusion = $r['Blood_Transfusion_Predicted'];
      $patient_consent = $r['Confirm_Patient_Consent'];
      $patient_discussed = $r['Patient_Discussed_At_Team_Brief'];
      $operation_confirmed = $r['Operation_Confirmed'];
      $equipments_available = $r['All_Equipment_Available'];
      $antibiotics_needed = $r['Antibiotics_Needed'];
      $available = $r['Available'];
      $imaging_displayed = $r['Essential_Imaging_Displayed'];
      $pulse_oximeter = $r['Pulse_Oximeter'];
      $aspiration = $r['Aspiration'];
      $analgesia_morphine = $r['Analgesia'];
      $swabs_counted = $r['Swabs_Counted'];
      $equipment_problems = $r['Equipment_Problems_Addressed'];
      $operation_documented = $r['Operation_Documented'];
      $any_patient_concerns = $r['Any_Patient_Concerns'];
      $handover_to_ward = $r['Hand_Over_To_Ward_Staff'];
      $consultation_ID = $r['consultation_id'];
      $Admision_ID = $r['Admision_ID'];
      $Registration_ID = $r['Registration_ID'];
      $Employee_ID = $r['Employee_ID'];
      $Patient_Payment_Item_List_ID = $r['Patient_Payment_Item_List_ID'];
      $Created_date = $r['Created_date'];
      $hb = $r['Hb'];
      $Consultant_approved = $r['Consultant_Approved'];
      $Any_essential_imaging = $r['Any_Essential_Imaging'];
      $allegies = $r['allergies'];
   }


   // get patient details
   if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
     $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
         FROM
           tbl_patient_registration pr,
           tbl_sponsor sp
         WHERE
           pr.Registration_ID = '" . $Registration_ID . "' AND
           sp.Sponsor_ID = pr.Sponsor_ID
           ") or die(mysqli_error($conn));
     $no = mysqli_num_rows($select_patien_details);
     if ($no > 0) {
       while ($row = mysqli_fetch_array($select_patien_details)) {
         $Member_Number = $row['Member_Number'];
         $Patient_Name = $row['Patient_Name'];
         $Registration_ID = $row['Registration_ID'];
         $Gender = $row['Gender'];
         $Guarantor_Name = $row['Guarantor_Name'];
         $Sponsor_ID = $row['Sponsor_ID'];
         $DOB = $row['Date_Of_Birth'];
       }

       $Age = floor((strtotime(date('Y-m-d')) - strtotime($DOB)) / 31556926) . " Years";
       // if($age == 0){
       
       $date1 = new DateTime($Today);
       $date2 = new DateTime($DOB);
       $diff = $date1->diff($date2);
       $Age = $diff->y . " Years, ";
       $Age .= $diff->m . " Months, ";
       $Age .= $diff->d . " Days";

       
     } else {
       $Guarantor_Name = '';
       $Member_Number = '';
       $Patient_Name = '';
       $Gender = '';
       $Registration_ID = 0;
     }
   } else {
     $Member_Number = '';
     $Patient_Name = '';
     $Gender = '';
     $Registration_ID = 0;
   }





 ?>
 <a href='nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>' class='art-button-green'>BACK</a>
 <br/><br/>

 <!-- **************************MAIN DIV ****************************************************************** -->
 <div class="container-fluid">

<link rel="stylesheet" href="/js/bootstrap.css">
<style media="screen">
  a.tab{
    color:white;
    background-color:#037CB0;
  }

  a.tab:link{
    color:white;
  }

  a.tab:visited{
    color:white;
  }



  a:active{
    color:#037CB0;
    background-color:white;
  }
</style>



<form class="form-horizontal" action="#">
 <ul class="nav nav-tabs">
  <li class="active tab"><a data-toggle="tab" href="#home">Basic Details</a></li>
  <li><a class="tab" data-toggle="tab" href="#menu1">Before Induction</a></li>
  <li><a class="tab" data-toggle="tab" href="#menu2">Previous Records</a></li>
 </ul>

<!-- *********BASIC DETAILS********* -->
 <div class="tab-content">
  <div id="home" class="tab-pane fade in active">
  <fieldset>      
        <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
                <b>PRE-OPERATIVE CHECKLIST FOR OPERATION</b><br />
                <span style='color:yellow;'><?= $Patient_Name;?>  |<?=$Gender;?>| <?=$Age;?> | <?=$Guarantor_Name;?></span></b>
        </legend>
      
    
    <center>
      <fieldset>
        <legend>Checklist for Operations Responsible Staff in Italics</legend>
        <table border="1">
          <tr>
            <!-- <td>Day before:name</td> -->
            <!-- <td colspan="3">
              <input type="text" class="form-control" name="patient_name" id="patient_name" value="< ?= $Patient_Name;?>">
              <input type="hidden"  name="consultation_ID" id="consultation_ID" value="< ?= $consultation_ID?>">
              <input type="hidden"  name="Admision_ID" id="Admision_ID" value="< ?= $Admision_ID?>">
              <input type="hidden"  name="Registration_ID" id="Registration_ID" value="< ?= $Registration_ID?>">
              <input type="hidden"  name="Employee_ID" id="Employee_ID" value="< ?= $Employee_ID?>">
              <input type="hidden"  name="Created_date" id="Created_date" value="< ?= $Created_date?>">
              <input type="hidden"  name="Patient_Payment_Item_List_ID" id="Patient_Payment_Item_List_ID" value="< ?= $Patient_Payment_Item_List_ID?>"> -->
            <!-- </td> --> 
            
          </tr>


          <tr>
            <td>(Clerking Weight:</td>
            <td><input type="text" class="form-control" name="weight" id="weight" value="<?= $weight; ?>"></td>
            <td>Hb</td>
            <td><input type="text" class="form-control" name="hb" id="hb" value="<?php if(!empty($hb)){ echo $hb; }?>">
              <input type="hidden"  name="consultation_ID" id="consultation_ID" value="<?= $consultation_ID?>">
              <input type="hidden"  name="Admision_ID" id="Admision_ID" value="<?= $Admision_ID?>">
              <input type="hidden"  name="Registration_ID" id="Registration_ID" value="<?= $Registration_ID?>">
              <input type="hidden"  name="Employee_ID" id="Employee_ID" value="<?= $Employee_ID?>">
              <input type="hidden"  name="Created_date" id="Created_date" value="<?= $Created_date?>">
              <input type="hidden"  name="Patient_Payment_Item_List_ID" id="Patient_Payment_Item_List_ID" value="<?= $Patient_Payment_Item_List_ID?>"> 
            </td>
            <!-- <td>Age:</td>
            <td><input type="text" class="form-control" name="age" id="age" value="< ?= $Age;?>"></td> -->
            <td>Allergies Known:</td>
            <td><textarea class="form-control" rows="2" name="allergies" id="allergies"><?=$allergies;?></textarea></td>
          </tr>

          <tr>
            <td>Doctor Indication for operation:</td>
            <td colspan="5"><textarea class="form-control" rows="2" name="indication_for_operation" id="indication_for_operation"><?= $comment_from_doctor1?></textarea></td>
          </tr>

          <tr>
            <td>Operation to be done:</td>
            <!-- <td colspan="4"><input type="text" class="form-control" name="operation_to_be_done" id="operation_to_be_done" value="< ?= $operation_tobe_done;?>"></td> -->
            <td colspan="4">
              <div class="form-group">
               <b style="color:red;">Select Surgery:</b>
               <select  id="Patient_Payment_Item_List_ID" name="Patient_Payment_Item_List_ID" style="width:160px;">
                 <?php

                   while($r = mysqli_fetch_assoc($operation_tobe_done1)){
                     echo "<option value='".$r['Patient_Payment_Item_List_ID']."'>".$r['Product_Name']."</option>";
                  }

                  ?>
               </select>
               </div>
            </td>
          </tr>

          <tr>
            <td>Consultant approved:</td>
            <td><label><input type="radio" name="consultant_approved" id="consultant_approvedYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="consultant_approved" id="consultant_approvedNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <td>Any essential imaging needed?</td>
            <td><label><input type="radio" name="essential_imaging" id="essential_imagingYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="essential_imaging" id="essential_imagingNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>
        </table>
      </fieldset>
    </center>
  </fieldset>  
  </div>





  <!-- ********BEFORE INDUCTION******** -->
  <div id="menu1" class="tab-pane fade">
    <center>
      <fieldset>
        <legend>Before Induction</legend>
        <table border="1">
          <tr>
            <th>Morning team brief completed</th>
            <td><label><input type="radio" name="team_brief" id="team_briefYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="team_brief" id="team_briefNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>
        </table>
      </fieldset>
    </center>
    <br>

    <!-- scrub team -->
    <center>
      <fieldset>
        <legend>Scrub team</legend>
        <table border="1">
          <tr>
            <th>Confirm patient & consent?</th>
            <td><label><input type="radio" name="patient_consent" id="patient_consentYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="patient_consent" id="patient_consentNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Patient discussed at team brief?</th>
            <td><label><input type="radio" name="patient_discussed" id="patient_discussedYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="patient_discussed" id="patient_discussedNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Operation confirmed (plus site)?</th>
            <td><label><input type="radio" name="operation_confirmed" id="operation_confirmedYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="operation_confirmed" id="operation_confirmedNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>All equipments available/sterile?</th>
            <td><label><input type="radio" name="equipments_available" id="equipments_availableYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="equipments_available" id="equipments_availableNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>
        </table>
      </fieldset>
    </center>
    <br/>
    <!-- end scrub team -->


    <!-- surgeon -->
    <center>
      <fieldset>
        <legend>Surgeon</legend>
        <table border="1">
          <tr>
            <th>Antibiotics needed?(Ward nurse to bring)</th>
            <td><label><input type="radio" name="antibiotics_needed" id="antibiotics_neededYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="antibiotics_needed" id="antibiotics_neededNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Estimated blood loss</th>
            <td colspan="2"><input type="text" class="form-control" name="estimated_blood_loss" id="estimated_blood_loss"></td>
          </tr>

          <tr>
            <th>Blood transfusion predicted?</th>
            <td><label><input type="radio" name="blood_transfusion" id="blood_transfusionYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="blood_transfusion" id="blood_transfusionNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Available?</th>
            <td><label><input type="radio" name="available" id="availableYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="available" id="availableNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Essential imaging displayed?</th>
            <td><label><input type="radio" name="imaging_displayed" id="imaging_displayedYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="imaging_displayed" id="imaging_displayedNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

        </table>
      </fieldset>
    </center>
    <br/>
    <!-- end surgeon -->


    <!-- anaesthetists -->
    <center>
      <fieldset>
        <legend>Anaesthetists</legend>
        <table border="1">
          <tr>
            <th>Pulse oximeter/machine/drugs ready?</th>
            <td><label><input type="radio" name="pulse_oximeter" id="pulse_oximeterYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="pulse_oximeter" id="pulse_oximeterNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>


          <tr>
            <th>Aspiration/airway risk?</th>
            <td><label><input type="radio" name="aspiration" id="aspirationYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="aspiration" id="aspirationNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Analgesia (eg morphine 0.1 mg/kg,pethidine 1 mg/kg)?</th>
            <td><label><input type="radio" name="analgesia_morphine" id="analgesia_morphineYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="analgesia_morphine" id="analgesia_morphineNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>
        </table>
      </fieldset>
    </center>
    <br/>
    <!-- end anaesthetists -->


    <!-- operation finishing -->
    <center>
      <fieldset>
        <legend>Operation Finishing</legend>
        <table border="1">
          <tr>
            <th>Swabs counted?</th>
            <td><label><input type="radio" name="swabs_counted" id="swabs_countedYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="swabs_counted" id="swabs_countedNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>


          <tr>
            <th>(scrub team) Equipment problems addressed?</th>
            <td><label><input type="radio" name="equipment_problems" id="equipment_problemsYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="equipment_problems" id="equipment_problemsNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>Operation documented in the records book?</th>
            <td><label><input type="radio" name="operation_documented" id="operation_documentedYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="operation_documented" id="operation_documentedNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>

          <tr>
            <th>(surgeon/anaesthetist) Any patient concerns?</th>
            <td><label><input type="radio" name="any_patient_concerns" id="any_patient_concernsYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
            <td><label><input type="radio" name="any_patient_concerns" id="any_patient_concernsNo" style="width:20px;height:20px;" value="no">No</label></td>
          </tr>
        </table>
      </fieldset>
    </center>
    <br/>
    <!-- end operation finishing  -->

  <center>
    <fieldset>
      <legend>Recovery</legend>
      <table border="1">
        <tr>
          <th>Handover to ward staff</th>
          <td><label><input type="radio" name="handover_to_ward" id="handover_to_wardYes" style="width:20px;height:20px;" value="yes">Yes</label></td>
          <td><label><input type="radio" name="handover_to_ward" id="handover_to_wardNo" style="width:20px;height:20px;" value="no">No</label></td>
        </tr>
        <tr>
          <td><input type="button" class="btn art-button" value="Save" onclick="saveOperation(this.value)"> &nbsp;&nbsp;&nbsp;</td>
        </tr>
      </table>
    </fieldset>
  </center>
  </div>











  <!-- ********* PREVIOUS RECORDS ****** -->
  <div id="menu2" class="tab-pane fade">
    <?php
      $select_created_date = mysqli_query($conn,"SELECT *
                           FROM tbl_checklist_for_operation
                           WHERE Registration_ID = '$Registration_ID'
                           -- Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'
                           ORDER BY Created_date DESC") or die(mysqli_error($conn));

     ?>
      <fieldset>
        <legend>All Patient's Records</legend>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>DATE</th>
            </tr>
          </thead>

          <tbody>
          <?php

            $sn = 1;
            while($dt = mysqli_fetch_assoc($select_created_date))
            {
              $consultation_ID2 = $dt['consultation_id'];
              $Admision_ID2 = $dt['Admision_ID'];
              $Registration_ID2 = $dt['Registration_ID'];
              $Employee_ID2  = $dt['Employee_ID'];
              $date = $dt['Created_date'];
              $estimated_blood_loss2 = $dt['Estimated_Blood_Loss'];
              $team_brief2 = $dt['Patient_Discussed_At_Team_Brief'];
              $blood_transfusion2 = $dt['Blood_Transfusion_Predicted'];
              $patient_consent2 = $dt['Confirm_Patient_Consent'];
              $patient_discussed2 = $dt['Patient_Discussed_At_Team_Brief'];
              $operation_confirmed2 = $dt['Operation_Confirmed'];
              $equipments_available2 = $dt['All_Equipment_Available'];
              $antibiotics_needed2 = $dt['Antibiotics_Needed'];
              $available2 = $dt['Available'];
              $imaging_displayed2 = $dt['Essential_Imaging_Displayed'];
              $pulse_oximeter2 = $dt['Pulse_Oximeter'];
              $aspiration2 = $dt['Aspiration'];
              $analgesia_morphine2 = $dt['Analgesia'];
              $swabs_counted2 = $dt['Swabs_Counted'];
              $equipment_problems2 = $dt['Equipment_Problems_Addressed'];
              $operation_documented2 = $dt['Operation_Documented'];
              $any_patient_concerns2 = $dt['Any_Patient_Concerns'];
              $handover_to_ward2 = $dt['Hand_Over_To_Ward_Staff'];
              $consultation_ID2 = $dt['consultation_id'];
              $Admision_ID2 = $dt['Admision_ID'];
              $Registration_ID2 = $dt['Registration_ID'];
              $Employee_ID2 = $dt['Employee_ID'];
              $Patient_Payment_Item_List_ID2 = $dt['Patient_Payment_Item_List_ID'];
              $Created_date2 = $dt['Created_date'];
              $hb2 = $dt['Hb'];
              $Consultant_approved2 = $dt['Consultant_Approved'];
              $Any_essential_imaging2 = $dt['Any_Essential_Imaging'];
              $allegies2 = $dt['allergies'];
              $weight2 = $dt['weight'];


              // query for selecting basic details of patient
              $sql2 = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,
                        pp.Patient_Payment_ID,sp.Guarantor_Name,ppl.Patient_Direction,ppl.Consultant_ID,em.Employee_Type,em.Employee_Name,c.Doctor_Comment,i.Product_Name
                        FROM tbl_patient_payment_item_list ppl
                        INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                        INNER JOIN tbl_item_list_cache c ON ppl.Patient_Payment_ID = c.Patient_Payment_ID
                        INNER JOIN tbl_items i ON i.Item_ID = c.Item_ID
                        JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                        JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                        LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID
                        WHERE
                        ppl.Nursing_Status='served' AND
                       ppl.Patient_Payment_Item_List_ID IN($Patient_Payment_Item_List_ID2)";

                       $select_Patient2 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
                       $no2 = mysqli_num_rows($select_Patient2);

                       while ($row2 = mysqli_fetch_array($select_Patient2)) {
                           $Registration_ID2 = $row2['Registration_ID'];
                           $Patient_Name2 = $row2['Patient_Name'];
                           $Date_Of_Birt2h = $row2['Date_Of_Birth'];
                           $doctor_comment2 = $row2['Doctor_Comment'];
                           $product_name2 = $row['Product_Name'];
                       }



             //  // query select weight of patient from observation
             //  $nurse_details2 = mysqli_query($conn,"SELECT body_weight  FROM tbl_nursecommunication_observation
             //  WHERE  Registration_ID ='$Registration_ID2' AND Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID2' ORDER BY saved_date DESC LIMIT 1") or die(mysqli_error($conn));
             //
             //  $weight2 = "";
             // $weight2 = mysqli_fetch_assoc($nurse_details2)['body_weight'];


             // operation to be DONE
             $operation_tobe_done3 = mysqli_query($conn,"SELECT i.Product_Name FROM tbl_items i INNER JOIN tbl_patient_payment_item_list p ON i.Item_ID = p.Item_ID
                                                        WHERE p.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID2'") or die(mysqli_error($conn));
              $operation_tobe_done2 = mysqli_fetch_assoc($operation_tobe_done3) ['Product_Name'];
              $operation_tobe_done2_ID = mysqli_fetch_assoc($operation_tobe_done3) ['Patient_Payment_Item_List_ID'];


              $comment_from_doctor = mysqli_query($conn,"SELECT c.Doctor_Comment FROM tbl_item_list_cache c
                                     INNER JOIN tbl_patient_payment_item_list p ON p.Patient_Payment_ID = c.Patient_Payment_ID
                                     WHERE p.Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID2'");

              $comment_from_doctor2 = mysqli_fetch_assoc($comment_from_doctor)['Doctor_Comment'];



              echo "<tr>";
              echo "<td>".$sn."</td>";
              echo "<td><input type=\"button\" class=\"btn btn-info btn-lg\" style='background-color:#037CB0;color:white;' data-toggle=\"modal\" data-target=\"#myModalp$sn\" value='$date'>";
                include 'checklist_for_operation_pre.php';
              echo'</td>';
              echo '</tr>';
              $sn ++;
            }

          ?>

       </tbody>
        </table>
      </fieldset>
  </div>


 </div>
</form>

</div>
 <!-- *************************END MAIN DIV *************************************************************** -->

<!-- scripts -->
<!-- <script type="text/javascript" src="js/jquery.js"></script> -->
<!-- <script type="text/javascript" src="js/bootstrap.min.js"></script> -->





<script type="text/javascript">

  function saveOperation(act)
  {
    //alert(act)

    var action = act;
    var allergies = $("#allergies").val();
    var weight = $("#weight").val();
     var Created_date = $("#Created_date2").val();
    var estimated_blood_loss = $("#estimated_blood_loss").val();
    var hb = $("#hb").val();
    var estimated_blood_loss2 = $("#estimated_blood_loss2").val();
    var hb2 = $("#hb2").val();
    var consultation_ID = $("#consultation_ID").val();
    var Patient_Payment_Item_List_ID = $("#Patient_Payment_Item_List_ID").val();
    var Patient_Payment_Item_List_ID2 = $("#Patient_Payment_Item_List_ID2").val();
    var Admision_ID = $("#Admision_ID").val();
    var Registration_ID = $("#Registration_ID").val();
    var Employee_ID = $("#Employee_ID").val();
    var blood_transfusion = $("input[name='blood_transfusion']:checked").val();
    var essential_imaging = $("input[name='essential_imaging']:checked").val();
    var consultant_approved = $("input[name='consultant_approved']:checked").val();
    var team_brief = $("input[name='team_brief']:checked").val();
    var patient_consent = $("input[name='patient_consent']:checked").val();
    var patient_discussed = $("input[name='patient_discussed']:checked").val();
    var operation_confirmed = $("input[name='operation_confirmed']:checked").val();
    var equipments_available = $("input[name='equipments_available']:checked").val();
    var antibiotics_needed = $("input[name='antibiotics_needed']:checked").val();
    var available = $("input[name='available']:checked").val();
    var imaging_displayed = $("input[name='imaging_displayed']:checked").val();
    var pulse_oximeter = $("input[name='pulse_oximeter']:checked").val();
    var aspiration = $("input[name='aspiration']:checked").val();
    var analgesia_morphine = $("input[name='analgesia_morphine']:checked").val();

    var swabs_counted = $("input[name='swabs_counted']:checked").val();
    var equipment_problems = $("input[name='equipment_problems']:checked").val();
    var operation_documented = $("input[name='operation_documented']:checked").val();
    var any_patient_concerns = $("input[name='any_patient_concerns']:checked").val();
    var handover_to_ward = $("input[name='handover_to_ward']:checked").val();

    //alert('Swabs ='+swabs_counted+',Equipment ='+equipment_problems+',Operation ='+operation_documented+', Any Patient ='+any_patient_concerns+', Handover ='+handover_to_ward);
    if(confirm('Are sure want to '+action+' this?'))
    {

      $.ajax({
       url:"save_checklist_for_operation.php",
       type:"POST",
       data:{
         blood_transfusion:blood_transfusion,
         estimated_blood_loss:estimated_blood_loss,
         estimated_blood_loss2:estimated_blood_loss2,
         team_brief:team_brief,
         patient_consent:patient_consent,
         patient_discussed:patient_discussed,
         operation_confirmed:operation_confirmed,
         equipments_available:equipments_available,
         antibiotics_needed:antibiotics_needed,
         available:available,
         imaging_displayed:imaging_displayed,
         pulse_oximeter:pulse_oximeter,
         aspiration:aspiration,
         analgesia_morphine:analgesia_morphine,
         swabs_counted:swabs_counted,
         equipment_problems:equipment_problems,
         operation_documented:operation_documented,
         any_patient_concerns:any_patient_concerns,
         handover_to_ward:handover_to_ward,
         consultation_ID:consultation_ID,
         Admision_ID:Admision_ID,
         Registration_ID:Registration_ID,
         Employee_ID:Employee_ID,
         Patient_Payment_Item_List_ID:Patient_Payment_Item_List_ID,
         Patient_Payment_Item_List_ID2:Patient_Payment_Item_List_ID2,
         Created_date:Created_date,
         hb:hb,
         hb2:hb2,
         consultant_approved:consultant_approved,
         essential_imaging:essential_imaging,
         allergies:allergies,
         weight:weight,
         "action":action
       },
       success:function(data){
         alert(data)
         location.reload(true);
         console.log(data);
       }
     })

    }

  }
</script>
<?php   include("./includes/footer.php"); ?>

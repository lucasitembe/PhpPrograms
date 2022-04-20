<?php
include("./includes/header.php");
include("./includes/connection.php");

$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
  @session_destroy();
  header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {

} else {
  @session_destroy();
  header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {

  if ($_SESSION['outpatient_nurse_com'] == "no") {
    ?>
        <!-- <a href='searchpatientinward.php?Registration_ID=<?php echo filter_input(INPUT_GET, 'Registration_ID'); ?>&BackTonurseCommunication=BackTonurseCommunicationPage' class='art-button-green'>
            PATIENT LIST
        </a> -->
        <?php

      } else {
        ?>
       <!-- <a href='searchnurseform.php?section=Nurse&NurseWorks=NurseWorksThisPage' class='art-button-green'>
            PATIENT LIST
        </a> -->
      <?php

    }
  }


  $nav = '';

  if (isset($_GET['discharged'])) {
    $nav = '&discharged=discharged';
  }

  if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
} else {
    $consultation_ID = 0;
}


if (isset($_GET['Admision_ID'])) {
    $Admision_ID = $_GET['Admision_ID'];
} else {
    $Admision_ID = 0;
}
// if (isset($_GET['Patient_Payment_Item_List_ID'])) {
//     $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
// } else {
//     $Patient_Payment_Item_List_ID = 0;
// }

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

  // get patient details
  if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
  			FROM
  				tbl_patient_registration pr,
  				tbl_sponsor sp
  			WHERE
  				pr.Registration_ID = '" .$Registration_ID. "' AND
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

  // $age = date_diff(date_create($DOB), date_create('today'))->y;
  $age = date_diff(date_create($DOB), date_create('today'))->y;
  $checkgender = '';
  if (strtolower($Gender) == 'male') {
    $checkgender = "onclick='notifyUser(this)'";
  }

  ?>
  <?php 
  if($this_page_from=='ThisPageFromInpatientclinicalnotes'){

  }else{?>
    <a  href="nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo  $consultation_ID; ?>&Admision_ID=<?php echo  $Admision_ID; ?>"     class='art-button-green' >
            BACK 
         </a>
<?php
  }
  ?>

<div id="result"></div>
<br />
<br />
<fieldset>
  <legend style="font-weight:bold" align=center>
    <div class="">
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
        <p style="margin:0px;padding:0px;">LABOUR ANTENATAL NEONAATAL RECORD</p>
        <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?= $Patient_Name ?> |</span><span style="margin-right:3px;"><?= $Gender ?> |</span> <span style="margin-right:3px;"><?= $age ?> | </span> <span style="margin-right:3px;"><?= $Guarantor_Name ?></span> </p>
      </div>

    </div>
    <input type="hidden" id="patient_gender" value="<?=$Gender?>">
    <input type="hidden" id="employee_id" value="<?=$_SESSION['userinfo']['Employee_ID']?>">
    <input type="hidden" id="patient_age" value="<?=$age?>">
    <input type="hidden" id="Registration_ID" value="<?=$Registration_ID?>">
    <input type="hidden" id="patient_name" value="<?=$Patient_Name?>">
    <input type="hidden" id="consultation_ID" value="<?=$consultation_ID?>">
      </legend>
 <center>
     <table width = 80%>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
               <a href="optical_nurse_station.php?consultation_ID=<?= $consultation_ID; ?>&&Registration_ID=<?= $Registration_ID; ?>&&Admision_ID=<?= $Admision_ID; ?>">
                 <button style='width: 100%; height: 100%'>
                              VA Record
                            </button>
                </a>

            </td>
        </tr>
         <tr>
         <td style='text-align: center; height: 40px; width: 33%;'>
               <!-- <a href="optical_nurse_station.php?consultation_ID=< ?= $consultation_ID; ?>&&Registration_ID=< ?= $Registration_ID; ?>&&Admision_ID=< ?= $Admision_ID; ?>">
                    <button style='width: 100%; height: 100%'>
                                Examination Of Operated Eye
                    </button>
                </a> -->
                <a href="#">
                    <button style='width: 100%; height: 100%' onclick="examination_of_operated_eye_dialog(<?php echo $Registration_ID;?>)">
                                Examination Of Operated Eye
                    </button>
                </a>
            </td>

         </tr>
          
         

     </table>
     <div id="optical_inpatient_section"></div>
 </center>
 </fieldset>

 <!-- mother details -->
<input type="hidden" name="">
<!-- mother details -->
<div id="result2"></div>


 <?php
include("./includes/footer.php");
?>
<script>
     function examination_of_operated_eye_dialog(Registration_ID){
            
            var consultation_ID=$("#consultation_ID").val();
            var Patient_Name=$("#patient_name").val();
            // alert(consultation_ID)
            // alert(Patient_Name)
            // alert(Registration_ID)
            $.ajax({
                type:'post',
                url: 'examination_of_operated_eye.php',
                data : {Registration_ID:Registration_ID,
                        consultation_ID:consultation_ID,
                },
                success : function(data){
                    $('#optical_inpatient_section').dialog({
                        autoOpen:true,
                        width:'85%',
                        position: ['center',0],
                        title:'Patient Name :  '+Patient_Name,
                        modal:true
                       
                    });  
                    $('#optical_inpatient_section').html(data);
                    $('#optical_inpatient_section').dialog('data');
                }
            })
        }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
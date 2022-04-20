<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admission_id'])) {
   $admision_id = $_GET['admission_id'];
}

if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

// get patient details
if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $patient_id . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
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

$age = date_diff(date_create($DOB), date_create('today'))->y;


?>
<a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a>

<br /><br />


<fieldset>
  <legend style="font-weight:bold;" align=center>
    <legend style="font-weight:bold" align=center>
      <div class="">
        <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
          <p style="margin:0px;padding:0px;text-align:center;">PATOGRAPH RECORD</p>
          <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
        </div>

      </div>
        </legend>
  </legend>
  <center>
    <table width="60%">
      <!-- <tr>
        <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href="fetal_condition.php?consultation_id=<?= $consultation_id;?>&&patient_id=<?=$patient_id;?>&&admision_id=<?=$admision_id?>">
                      <button style='width: 100%; height: 100%'>
                         Fetal Condition
                       </button>
                     </a>

        </td>
      </tr> -->
    </tr>
        <td style='text-align: center; height: 40px; width: 33%;'>
          <a href="progress_of_labour.php?consultation_id=<?= $consultation_id;?>&&patient_id=<?=$patient_id;?>&&admission_id=
            <?=$admision_id;?>">
            <button style='width: 100%; height: 100%'>
              Progress Of Labour
            </button>
            </a>
        </td>
    </tr>
    <tr>

      <td style='text-align: center; height: 40px; width: 33%;'>
                  <a href="martenal_condition.php?consultation_id=<?= $consultation_id;?>&&patient_id=<?=$patient_id;?>
                    &&admission_id=
          <?=$admision_id;?>"><button style='width: 100%; height: 100%'>Martenal Condition   </button></a>

      </td>

      </tr>

    </table>

  </center>
</fieldset>
<?php
include("./includes/footer.php");
?>

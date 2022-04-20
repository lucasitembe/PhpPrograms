<?php
include('header.php');
include('../includes/connection.php');

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];

}

// echo $registration_id;
// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $registration_id . "' AND
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


include('patient_demographic.php');

 ?>

 <a href="../nursecommunicationpage.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>



<center>
  <fieldset>
    <legend align=center style="font-weight:bold; font-size:13px;">CRITICAL CARE OBSERVATION UNIT <br/>
      <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span>
    </legend>

    <table width="60%">
      <?php 
      $select_warrant = mysqli_query($conn, "SELECT Icu_form_ID, COUNT(Icu_form_ID) AS totalrequest FROM tbl_anasthesia_icuform WHERE consultation_ID='$consultation_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
      if(mysqli_num_rows($select_warrant)>0){
        while($row = mysqli_fetch_assoc($select_warrant)){
          $Icu_form_ID = $row['Icu_form_ID'];
          $totalrequest = $row['totalrequest'];
          
        }
      }else{
        $Icu_form_ID =0;
        $totalrequest =0;
      }
      echo "";
      ?>
      <tr>
                  <td>
                    <a href='#'>
                      <button style='width:100%;'  onclick="open_warrant_form_dialogy('<?=$consultation_ID?>', '<?=$Registration_ID?>')">REFERRAL WARRANT FORM <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?=$totalrequest?></span></button>
                    </a>
                  </td>
                </tr>
      <tr>
        <td><a href="form_one.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form one</button>
        </a>
      </td>
      </tr>

      <tr>
        <td><a href="form_two.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form two</button>
        </a>
      </td>
      </tr>

      <tr>
        <td><a href="form_three.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form three</button>
        </a>
      </td>
      </tr>

      <tr>
        <td><a href="form_four.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form four</button>
        </a>
      </td>
      </tr>
      <tr>
        <td><a href="form_five.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form Five</button>
        </a>
      </td>
      </tr>

      <tr>
        <td><a href="form_six.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form Six</button>
        </a>
      </td>
      </tr>

      <tr>
        <td><a href="form_seven.php?Registration_ID=<?=$registration_id?>&Admision_ID=<?=$admission_id?>&consultation_ID=<?=$consultation_id?>">
          <button style="width:100%;">form Seven</button>
        </a>
      </td>
      </tr>

    </table>

  </fieldset>
</center>
<div id="warrantForm"></div>
<script>
  function open_warrant_form_dialogy(consultation_ID, Registration_ID){
        $.ajax({
            type:'POST',
            url:'../add_anaesthetic_item.php',           
            data:{consultation_ID:consultation_ID,Registration_ID:Registration_ID, icuformdialog:''},
            success:function(responce){
                $("#warrantForm").dialog({
                    title: 'REFERRAL WARRANT INTO THE INTENSIVE CARE UNIT',
                    width: 1000, 
                    height: 700, 
                    modal: true
                    });
                $("#warrantForm").html(responce);                
            }
        })
    }
</script>
<?php
  include("../includes/footer.php");
?>
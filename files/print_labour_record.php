<?php
include("./includes/connection.php");
include("MPDF/mpdf.php");
if (isset($_GET['patient_id']) && isset($_GET['admision_id'])) {
  $patient_id = $_GET['patient_id'];
  $admision_id = $_GET['admission_id'];
  // echo $admision_id;

  $htm = "<img src='./branchBanner/branchBanner.png'>";
  $htm .= "<p style='font-weight:bold; text-align:center;'> LABOUR RECORD</p>";
  $htm .= "<style>
    table{
      border-collapse:collapse;
      border:1px solid grey;
      border-left:none;
      border-right:none;
    }

    table th, td {
      border: none;
  }

  tr td{
    text-align:left;
  }

  table thead th{
    font-size:12px;
  }



  </style>";


  // start select patient labour info if exist one

  $select_labour_record = "SELECT * FROM  tbl_labour_record WHERE patient_id='$patient_id' AND admission_id='$admision_id'";
  if ($result_labour_record = mysqli_query($conn,$select_labour_record)) {
    if ($num  = mysqli_num_rows($result_labour_record) > 0) {
      while ($row_labour = mysqli_fetch_assoc($result_labour_record)) {
        $general_state_of_admission = $row_labour['general_state_of_admission'];
        $date = $row_labour['date'];
        $ph = $row_labour['ph'];
        $temp = $row_labour['temp'];
        $lie = $row_labour['lie'];
        $pulse = $row_labour['pulse'];
        $respiration = $row_labour['respiration'];
        $blood_pressure = $row_labour['bloodpressure'];
        $colour = $row_labour['colour'];
        $blood = $row_labour['blood'];
        $specific_gravity =$row_labour['specific_gravity'];
        $keytones = $row_labour['keytones'];
        $urobilinogen = $row_labour['urobilinogen'];
        $alumin = $row_labour['albumin'];
        $leucocetes = $row_labour['leucocytes'];
        $sugar = $row_labour['sugar'];
        $clinical_appearance= $row_labour['clinical_appearance'];
        $varicobe_veins = $row_labour['varicose_veins'];
        $odema = $row_labour['oedema'];
        $mental_status = $row_labour['mental_status'];
        $inspections = $row_labour['inspection'];
        $shape = $row_labour['shape'];
        $scars = $row_labour['scars'];
        $pendulousis_oval = $row_labour['pendulous_oval'];
        $hb = $row_labour['hb'];
        $vdrl = $row_labour['vdrl'];
        $elisa = $row_labour['elisa'];
        $bloodgroup = $row_labour['blood_group'];
        $fundal_height = $row_labour['fundal_height'];
        $engagement_in_relationship_to_brim = $row_labour['engagement_in_relation_to_brim'];
        $frequency_type_of_contractions = $row_labour['frequency_and_type_of_contractions'];
        $presentaion = $row_labour['presentation'];
        $foetal_heart_rate = $row_labour['foetal_heart_rate'];
        $position = $row_labour['position'];
        $date_time = $row_labour['date_and_time'];
        $dilation = $row_labour['dilation'];
        $presenting_part = $row_labour['presenting_part'];
        $station = $row_labour['station'];
        $position = $row_labour['position'];
        $moulding = $row_labour['moulding'];
        $caput = $row_labour['caput'];
        $membrane_liqour= $row_labour['membrane_liqour'];
        $if_ruptured_date_and_time = $row_labour['if_ruptured_date'];
        $admitted_by = $row_labour['admitted_by'];
        $examiner = $row_labour['examiner'];
        $summary = $row_labour['summary'];
        $remarks = $row_labour['remarks'];
        $name= $row_labour['obstrecian_pedstrician_informed_by_name'];
        $cervic_state= $row_labour['cervic_state'];
        $sacral_promontory= $row_labour['sacral_promontory'];
        $sacral_curve= $row_labour['sacral_curve'];
        $subpubic_angle= $row_labour['subpubic_angle'];
        $ischial_spines= $row_labour['ischial_spines'];
        $sacral_tuberosites = $row_labour['sacral_tuberosites'];
        $expected_mode_of_delivery = $row_labour['expected_mode_of_delivery'];
      }

    }

  }


// end labour info :

$htm .="<table id='top' width=100%>
        <tr>
        <td style='text-align:left;'>DATE AND TIME:</td>
        <td>".$date_time."</td>
        <td style='border-left: 1px solid grey;text-align:left;' >
        TEMP:</td>
        <td>".$general_state_of_admission."</td>
        <td colspan='4' style='border-left: 1px solid grey;font-weight:bold;'>URINALYSIS</td>

        </tr>

        <tr>
        <td style='text-align:left;'>GENERAL STATE OF ADMISSION:</td>
        <td>".$weight."</td>
        <td style='border-left: 1px solid grey;text-align:left;' >PULSE:</td>
        <td>".$hb."</td>
        <td style='border-left:1px solid grey;'>COLOUR</td>
        <td></td>
        <td>SPECIFIC GRAVITY</td>
        <td></td>
      </tr>

        <tr>
        <td style='text-align:left;'></td>
        <td>"."</td>
        <td style='border-left: 1px solid grey;text-align:left;' >
        RESPIRATION:</td>
        <td>".$bloodgroup."</td>
        <td style='border-left:1px solid grey;'>PH:</td>
        <td></td>
        <td>ALBUMIN:</td>
        <td></td>

        </tr>

        <tr>
        <td style='text-align:left;'></td>
        <td>"."</td>
        <td style='border-left: 1px solid grey;text-align:left;' >VDRL:</td>
        <td>".$vdrl."</td>

        <td style='border-left: 1px solid grey;text-align:left;' >SUGAR:</td>
        <td>".$ph."</td>
        <td>BLOOD:</td>
        <td></td>
        </tr>

        <tr>
        <td style='text-align:left;'></td>
        <td></td>
        <td style='border-left: 1px solid grey;text-align:left' >BLOOD PRESSURE:</td>
        <td>".$blisa."</td>

        <td style='border-left: 1px solid grey;text-align:left' >UROBILINOGEN:</td>
        <td>".$alumin."</td>
        <td>LEUCOCYTES</td>
        <td></td>
        </tr>

        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style='border-left: 1px solid grey;text-align:left' >KEYTONES:</td>
        <td>".$alumin."</td>
        <td></td>
        <td></td>
        </tr>

      </table>"
      ;
      $htm .="<table id='top' width='100%'>
              <tr>
              <td colspan='2' style='text-align:left; font-weight:bold;'>PHYSICAL EXAMINATION:</td>
              <td style='border-left: 1px solid grey;text-align:left; font-weight:bold;' >
              INVESTIGATIONS DONE:</td>
              </tr>

              <tr>
              <td style='text-align:left;'>CLINICAL APPEARANCE:</td>
              <td>".$weight."</td>
              <td style='border-left: 1px solid grey;text-align:left;' >HB:</td>
              <td>".$hb."</td>
              </tr>

              <tr>
              <td style='text-align:left;'>VARICOSE VEINS</td>
              <td>"."</td>
              <td style='border-left: 1px solid grey;text-align:left;' >
              VDRL:</td>
              <td>".$bloodgroup."</td>

              </tr>

              <tr>
              <td style='text-align:left;'>OEDEMA</td>
              <td>"."</td>
              <td style='border-left: 1px solid grey;text-align:left;' >ELISA I & II:</td>
              <td>".$vdrl."</td>

              </tr>

              <tr>
              <td style='text-align:left;'>MENTALA STATUS </td>
              <td></td>
              <td style='border-left: 1px solid grey;text-align:left' >BLOOD GROUP:</td>
              <td>".$blisa."</td>
              </tr>

              <tr>
              <td>INSPECTION</td>
              <td></td>
              <td style='border-left: 1px solid grey;text-align:left'>dfdf</td>
              <td></td>
              </tr>

              <tr>
              <td colspan='2' style='font-weight:bold;'>ABDOMINAL PALPATION</td>
              <td></td>
              <td></td>
              <td></td>
              </tr>

            </table>"
            ;



$htm .= "";


  $mpdf = new mPDF('s', 'A4');

  $mpdf->SetDisplayMode('fullpage');
  $mpdf->WriteHTML($htm);
  $mpdf->Output();
  exit;
}

 ?>

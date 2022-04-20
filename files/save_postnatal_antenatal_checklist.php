<?php
session_start();
include("./includes/connection.php");
include("MPDF/mpdf.php");
//header('Content-Type: application/json');
//header("Access-Control-Allow-Origin: *");
if(strpos($_SERVER['HTTP_ORIGIN'], 'javascript') == false)
																			{
		header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
	}


//***************************** SAVE ANTENATAL CHECKLIST DATA **************************************************************************
$data=json_decode(file_get_contents("php://input"));

//save antenatal checklist data
if ($data->action == 'save') {
	$Registration_ID = mysqli_real_escape_string($conn,trim($data->Registration_ID));
	$Employee_ID = mysqli_real_escape_string($conn,trim($data->Employee_ID));
	$marital_status = mysqli_real_escape_string($conn,trim($data->marital_status));
	$lnmp = mysqli_real_escape_string($conn,trim($data->lnmp));
	$living = mysqli_real_escape_string($conn,trim($data->living));
	$edd = mysqli_real_escape_string($conn,trim($data->edd));
	$ga = mysqli_real_escape_string($conn,trim($data->ga));
	$gravida = mysqli_real_escape_string($conn,trim($data->gravida));
	$para = mysqli_real_escape_string($conn,trim($data->para));
	$abortion = mysqli_real_escape_string($conn,trim($data->abortion));
	$bt = mysqli_real_escape_string($conn,trim($data->bt));
	$bp = mysqli_real_escape_string($conn,trim($data->bp));
	$pr = mysqli_real_escape_string($conn,trim($data->pr));
	$rr = mysqli_real_escape_string($conn,trim($data->rr));
	$weight = mysqli_real_escape_string($conn,trim($data->weight));
	$signs_of_anemia = mysqli_real_escape_string($conn,trim($data->signs_of_anemia));
	$legs = mysqli_real_escape_string($conn,trim($data->legs));
	$breast_abnormalities = mysqli_real_escape_string($conn,trim($data->breast_abnormalities));
	$Oedema = mysqli_real_escape_string($conn,trim($data->Oedema));
	$varicose_vein = mysqli_real_escape_string($conn,trim($data->varicose_vein));
	$any_deformity = mysqli_real_escape_string($conn,trim($data->any_deformity));
	$fetal_lie = mysqli_real_escape_string($conn,trim($data->fetal_lie));
	$fetal_presentation = mysqli_real_escape_string($conn,trim($data->fetal_presentation));
	$fetal_heart_rate = mysqli_real_escape_string($conn,trim($data->fetal_heart_rate));
	$head_level = mysqli_real_escape_string($conn,trim($data->head_level));
	$contractions = mysqli_real_escape_string($conn,trim($data->contractions));
	$admission_date = mysqli_real_escape_string($conn,trim($data->admission_date));
	$address = mysqli_real_escape_string($conn,trim($data->address));
	$Admision_ID = mysqli_real_escape_string($conn,trim($data->Admision_ID));
	$consultation_id = mysqli_real_escape_string($conn,trim($data->consultation_id));

$sql = "INSERT INTO tbl_postinatal_antenatal_records(
        Registration_ID,Employee_ID,Admision_ID,consultation_id,marital_status,lnmp,living,edd,ga,gravida,para,abortion,bt,bp,pr,rr,
        weight,signs_of_anemia,legs,breast_abnormalities,Oedema,varicose_vein,any_deformity,fetal_lie,
        fetal_presentation,fetal_heart_rate,head_level,contractions,admission_date,address
        )
        VALUES('$Registration_ID','$Employee_ID','$Admision_ID','$consultation_id','$marital_status','$lnmp','$living','$edd','$ga',
        '$gravida','$para','$abortion','$bt','$bp','$pr','$rr','$weight','$signs_of_anemia',
        '$legs','$breast_abnormalities','$Oedema','$varicose_vein','$any_deformity','$fetal_lie',
        '$fetal_presentation','$fetal_heart_rate','$head_level','$contractions','$admission_date','$address')";

$save_data = mysqli_query($conn,$sql);
if($save_data)
{
  echo "Saved Successfully ";
}
else{
  echo "Failed".mysqli_error($conn);
}

mysqli_close($conn);

}





//******************************** RETRIEVE ANTENATAL CHECKLIST DATA *******************************************************************

//get antenatal checklist data
if ($_GET['action'] == 'showTb') {
  $Registration_ID = $_GET['regId'];
  $query = "SELECT   Registration_ID,p.Employee_ID,marital_status,lnmp,living,edd,ga,gravida,para,abortion,bt,bp,pr,rr,
            weight,signs_of_anemia,legs,breast_abnormalities,Oedema,varicose_vein,any_deformity,fetal_lie,
            fetal_presentation,fetal_heart_rate,head_level,contractions,admission_date,saved_time,address, e.Employee_ID,e.Employee_Name
            FROM tbl_postinatal_antenatal_records p
            INNER JOIN tbl_employee e
            ON p.Employee_ID = e.Employee_ID
            WHERE Registration_ID = '$Registration_ID' ORDER BY saved_time DESC";

  $execute = mysqli_query($conn,$query);

  $output = array();
 if (mysqli_num_rows($execute) > 0) {

        while ($r = mysqli_fetch_assoc($execute)) {

            $output[] = array(
              'save_time'=>$r['saved_time'],
              'bt'=>$r['bt'],
              'bp'=>$r['bp'],
              'pr'=>$r['pr'],
              'rr'=>$r['rr'],
              'weight'=>$r['weight'],
              'signs_of_anemia'=>$r['signs_of_anemia'],
              'breast_abnormalities'=>$r['breast_abnormalities'],
              'legs'=>$r['legs'],
              'Oedema'=>$r['Oedema'],
              'varicose_vein'=>$r['varicose_vein'],
              'any_deformity'=>$r['any_deformity'],
              'fetal_lie'=>$r['fetal_lie'],
              'fetal_presentation'=>$r['fetal_presentation'],
              'fetal_heart_rate'=>$r['fetal_heart_rate'],
              'head_level'=>$r['head_level'],
              'contractions'=>$r['contractions'],
              'Employee_Name'=>$r['Employee_Name']
            );
        }

        echo json_encode($output);


    }
  else {
    die("No record Found".mysqli_error($conn));

  }

}


//get antenatal checklist data by year
if ($_GET['action'] == 'showTb1' && $_GET['year']) {
  $Registration_ID = $_GET['regId'];
  $y  = $_GET['year'];
  $query = "SELECT   Registration_ID,p.Employee_ID,marital_status,lnmp,living,edd,ga,gravida,para,abortion,bt,bp,pr,rr,
            weight,signs_of_anemia,legs,breast_abnormalities,Oedema,varicose_vein,any_deformity,fetal_lie,
            fetal_presentation,fetal_heart_rate,head_level,contractions,admission_date,saved_time,address, e.Employee_ID,e.Employee_Name
            FROM tbl_postinatal_antenatal_records p
            INNER JOIN tbl_employee e
            ON p.Employee_ID = e.Employee_ID
            WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y'  ORDER BY saved_time DESC";

  $execute = mysqli_query($conn,$query);

  $output = array();
 if (mysqli_num_rows($execute) > 0) {

        while ($r = mysqli_fetch_assoc($execute)) {

            $output[] = array(
              'save_time'=>$r['saved_time'],
              'bt'=>$r['bt'],
              'bp'=>$r['bp'],
              'pr'=>$r['pr'],
              'rr'=>$r['rr'],
              'weight'=>$r['weight'],
              'signs_of_anemia'=>$r['signs_of_anemia'],
              'breast_abnormalities'=>$r['breast_abnormalities'],
              'legs'=>$r['legs'],
              'Oedema'=>$r['Oedema'],
              'varicose_vein'=>$r['varicose_vein'],
              'any_deformity'=>$r['any_deformity'],
              'fetal_lie'=>$r['fetal_lie'],
              'fetal_presentation'=>$r['fetal_presentation'],
              'fetal_heart_rate'=>$r['fetal_heart_rate'],
              'head_level'=>$r['head_level'],
              'contractions'=>$r['contractions'],
              'Employee_Name'=>$r['Employee_Name']
            );
        }

        echo json_encode($output);


    }
  else {
    die("No record Found".mysqli_error($conn));

  }

}



//********************************PRINT PREVIEW *******************************************************


if ($_GET['printAction'] == 'print_preview') {

  if (isset($_GET['consultation_id'])) {
    $consultation_id = $_GET['consultation_id'];
  }

  if (isset($_GET['Employee_ID'])) {
    $Employee_ID = $_GET['Employee_ID'];
  }

  if (isset($_GET['admission_id'])) {
    $admision_id = $_GET['admission_id'];
  }
  if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
  }
  if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }
  if (isset($_GET['admission_id'])) {
    $admision_id = $_GET['admission_id'];
  }

  if (isset($_GET['patient_id'])) {
   $patient_id = $_GET['patient_id'];
  }



  $today = Date("Y-m-d");


  $htm = "<img src='./branchBanner/branchBanner.png'>";
  $htm .= "<p style='font-weight:bold; text-align:center;'> ANTENATAL CHECKLIST</p>";

  // get patient details
  if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn,"
  		SELECT pr.Sponsor_ID,pr.Member_Number,pr.Patient_Name,pr.Registration_ID,pr.Gender,sp.Guarantor_Name,pr.Date_Of_Birth
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

  $age = date_diff(date_create($DOB), date_create('today'))->y;
  $select_forprint = "SELECT   Registration_ID,p.Employee_ID,marital_status,lnmp,living,edd,ga,gravida,para,abortion,bt,bp,pr,rr,
            weight,signs_of_anemia,legs,breast_abnormalities,Oedema,varicose_vein,any_deformity,fetal_lie,
            fetal_presentation,fetal_heart_rate,head_level,contractions,admission_date,saved_time,address, e.Employee_ID,e.Employee_Name
            FROM tbl_postinatal_antenatal_records p
            INNER JOIN tbl_employee e
            ON p.Employee_ID = e.Employee_ID
            WHERE Registration_ID = '$Registration_ID' ORDER BY saved_time DESC";

  $execute_forprint = mysqli_query($conn,$select_forprint);

  $output = array();



    $sql_marital_status = mysqli_query($conn,"SELECT marital_status FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $marital_status = mysqli_fetch_assoc($sql_marital_status)['marital_status'];

    $sql_lnmp = mysqli_query($conn,"SELECT lnmp FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $lnmp = mysqli_fetch_assoc($sql_lnmp)['lnmp'];

    $sql_edd = mysqli_query($conn,"SELECT edd FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $edd = mysqli_fetch_assoc($sql_edd)['edd'];

    $sql_ga = mysqli_query($conn,"SELECT ga FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $ga = mysqli_fetch_assoc($sql_ga)['ga'];

    $sql_gravida = mysqli_query($conn,"SELECT gravida FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $gravida = mysqli_fetch_assoc($sql_gravida)['gravida'];

    $sql_para = mysqli_query($conn,"SELECT para FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $para = mysqli_fetch_assoc($sql_para)['para'];

    $sql_lliving = mysqli_query($conn,"SELECT living FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $living = mysqli_fetch_assoc($sql_lliving)['living'];

    $sql_arbotion = mysqli_query($conn,"SELECT abortion FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'  ORDER BY DATE(saved_time) ASC LIMIT 1");
    $arbotion = mysqli_fetch_assoc($sql_arbotion)['abortion'];

    $sql_admission_date = mysqli_query($conn,"SELECT admission_date FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID'");
    $admission_date = mysqli_fetch_assoc($sql_admission_date)['admission_date'];

    $sql_address = mysqli_query($conn,"SELECT address FROM tbl_postinatal_antenatal_records WHERE Registration_ID ='$Registration_ID' ");
    $address = mysqli_fetch_assoc($sql_address)['address'];

  $htm .='
  <center>
  <fieldset>
    <legend style="font-weight:bold"align=center>
      <div style="height:34px;margin:0px;padding:0px;font-weight:bold; text-align:center;">
        <p style="color:#3DBCFD;margin:0px;padding:0px; "><span style="margin-right:3px;">' . $Patient_Name . '|</span><span style="margin-right:3px;">' . $Gender . '|</span>
        <span style="margin-right:3px;">' . $age . 'Years| </span>
        <span style="margin-right:3px;">' . $Guarantor_Name . '</span> </p>
      </div>
  </legend>
  </fieldset>
  <table border="1" class="table table-bordered" style="width:100%;border-collapse: collapse;">
    <tr>
      <td><b>Patient Name:</b>'.$Patient_Name.'</td>
      <td><b>Age:</b> '.$age.'</td>
      <td><b>Marital Status:</b> '.$marital_status.'</td>
      <td><b>LNMP:</b> '. $lnmp.'</td>
      <td><b>EDD:</b> '. $edd.'</td>
    </tr>
    <tr>
      <td><b>GA:</b> '.$ga.'</td>
      <td><b>GRAVIDA:</b> '.$gravida.'</td>
      <td><b>PARA:</b> '.$para.'</td>
      <td><b>LIVING:</b> '.$living.'</td>
      <td><b>ABORTION:</b> '.$arbotion.'</td>
    </tr>
    <tr>
      <td><b>ADDRESS:</b> '.$address.'</td>
      <td><b>DATE OF ADMISSION:</b> '. $admission_date.'</td>
    </tr>
  </table>';




  $htm.='


        <table border="1" class="table table-bordered" style="width:100%;border-collapse: collapse;">
           <thead>
             <tr>
               <th rowspan="2">DATE TIME</th>
               <th colspan="5">VITAL SIGNS</th>
               <th colspan="6">PHYSICAL EXAMINATION</th>
               <th colspan="5">ABDOMINAL EXAMINATION</th>
               <th rowspan="2">Checked By</th>
             </tr>
             <tr>
               <th>BT(°C)</th>
               <th>BP(mmHg)</th>
               <th>PR(b/min)</th>
               <th>RR(br/min)</th>
               <th>WEIGHT(kg)</th>
               <th>Signs of anaemia</th>
               <th>Breast abnormalities</th>
               <th>Legs</th>
               <th>Oedema</th>
               <th>Varicose vein</th>
               <th>Any Deformity</th>
               <th>Fetal Lie</th>
               <th>Fetal presentation</th>
               <th>Fetal Heart Rate(b/min)</th>
               <th>Head Level</th>
               <th>Contractions</th>
             </tr>
           </thead>';

           while ($rw = mysqli_fetch_assoc($execute_forprint)) {

             $save_time = $rw['saved_time'];
             $bt = $rw['bt'];
             $bp = $rw['bp'];
             $pr = $rw['pr'];
             $rr = $rw['rr'];
             $weight = $rw['weight'];
             $signs_of_anemia = $rw['signs_of_anemia'];
             $breast_abnormalities = $rw['breast_abnormalities'];
             $legs = $rw['legs'];
             $Oedema = $rw['Oedema'];
             $varicose_vein = $rw['varicose_vein'];
             $any_deformity = $rw['any_deformity'];
             $fetal_lie = $rw['fetal_lie'];
             $fetal_presentation = $rw['fetal_presentation'];
             $fetal_heart_rate = $rw['fetal_heart_rate'];
             $head_level = $rw['head_level'];
             $contractions = $rw['contractions'];
             $Employee_Name = $rw['Employee_Name'];

           $htm .=' <tr>
             <td>'.$save_time.'</td>
             <td>'.$bt.'</td>
             <td>'.$bp.'</td>
             <td>'.$pr.'</td>
             <td>'.$rr.'</td>
             <td>'.$weight.'</td>
             <td>'.$signs_of_anemia.'</td>
             <td>'.$breast_abnormalities.'</td>
             <td>'.$legs.'</td>
             <td>'.$Oedema.'</td>
             <td>'.$varicose_vein.'</td>
             <td>'.$any_deformity.'</td>
             <td>'.$fetal_lie.'</td>
             <td>'.$fetal_presentation.'</td>
             <td>'.$fetal_heart_rate.'</td>
             <td>'.$head_level.'</td>
             <td>'.$contractions.'</td>
             <td>'.$Employee_Name.'</td>
           </tr>';
         }

        $htm .='<tbody>
           </tbody>
         </table>
         </center>';

         $mpdf = new mPDF('s', 'A4');
         $mpdf->SetDisplayMode('fullpage');
         $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
         $mpdf->WriteHTML($htm);
         $mpdf->Output();
         exit;
}

 ?>

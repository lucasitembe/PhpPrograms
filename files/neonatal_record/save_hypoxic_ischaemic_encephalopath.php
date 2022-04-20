<?php
include('../includes/connection.php');
include("../MPDF/mpdf.php");

//header("Access-Control-Allow-Origin: *");
if(strpos($_SERVER['HTTP_ORIGIN'], 'javascript') == false)
																			{
		header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
}


$data = json_decode(file_get_contents('php://input'));
$year = date("Y");

// *********************** Save Data ******************************************************
if ($data->action == 'save_thompson') {

 $employee_ID = mysqli_real_escape_string($conn,trim($data->Employee_ID));
 $Registration_ID = mysqli_real_escape_string($conn,trim($data->Registration_ID));
 $baby_name = mysqli_real_escape_string($conn,trim($data->baby_name));
 $birth_weight = mysqli_real_escape_string($conn,trim($data->birth_weight));
 $sex = mysqli_real_escape_string($conn,trim($data->sex));
 $apgar_score1min = mysqli_real_escape_string($conn,trim($data->apgar_score1min));
 $apgar_score5min = mysqli_real_escape_string($conn,trim($data->apgar_score5min));
 $referral = mysqli_real_escape_string($conn,trim($data->referral));
 $referral_from = mysqli_real_escape_string($conn,trim($data->referral_from));
 $history_or_dx = mysqli_real_escape_string($conn,trim($data->history_or_dx));
 $birth_date = mysqli_real_escape_string($conn,trim($data->birth_date));
 $selectTone = mysqli_real_escape_string($conn,trim($data->selectTone));
 $selectLOC = mysqli_real_escape_string($conn,trim($data->selectLOC));
 $selectFits = mysqli_real_escape_string($conn,trim($data->selectFits));
 $selectMoro = mysqli_real_escape_string($conn,trim($data->selectMoro));
 $selectGrasp = mysqli_real_escape_string($conn,trim($data->selectGrasp));
 $selectSuck = mysqli_real_escape_string($conn,trim($data->selectSuck));
 $selectRespiratory = mysqli_real_escape_string($conn,trim($data->selectRespiratory));
 $selectFontanelle = mysqli_real_escape_string($conn,trim($data->selectFontanelle));
 $selectDay = mysqli_real_escape_string($conn,trim($data->selectDay));
 $remarks = mysqli_real_escape_string($conn,trim($data->remarks));
 $Admision_ID = mysqli_real_escape_string($conn,trim($data->Admision_ID));
 $consultation_id = mysqli_real_escape_string($conn,trim($data->consultation_id));



 $save_thompson = "INSERT INTO tbl_hypoxic_ischaemic_encephalopath(
                   baby_name,birth_weight,sex,apgar_score1min,apgar_score5min,referral,referral_from,history_or_dx,birth_date,tone,
                   loc,fits,moro,grasp,suck,respiratory,fontanelle,day,remarks,Employee_ID,Registration_ID,Admision_ID,consultation_id)
                   VALUES('$baby_name','$birth_weight','$sex','$apgar_score1min','$apgar_score5min','$referral','$referral_from','$history_or_dx',
                   '$birth_date','$selectTone','$selectLOC','$selectFits','$selectMoro','$selectGrasp','$selectSuck',
                   '$selectRespiratory','$selectFontanelle','$selectDay','$remarks','$employee_ID','$Registration_ID','$Admision_ID','$consultation_id')";

                   $execute = mysqli_query($conn,$save_thompson);

                   if ($execute) {
                     echo "Record Added Successfully!";
                   }
                   else {
                     die("Failed to Save Data".mysqli_error($conn));
                   }

                   mysqli_close($conn);
}
// ********************************End***************************************************



// ************************************ SUM SIGNS *******************************************

//sum day1
if ($_GET['action'] == 'sum_d1') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 1  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d1 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day1 by year
if ($_GET['action'] == 'sum_d11' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
$sql_day1 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 1  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d1 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}




//sum day2
if ($_GET['action'] == 'sum_d2') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day2 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 2  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d2 = 0;
             $execute_day2 = mysqli_query($conn,$sql_day2);

             if (mysqli_num_rows($execute_day2) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day2)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d2 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d2);

             }else{
               echo "";
             }

}



//sum day2 by year
if ($_GET['action'] == 'sum_d21' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day2 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 2  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d2 = 0;
             $execute_day2 = mysqli_query($conn,$sql_day2);

             if (mysqli_num_rows($execute_day2) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day2)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d2 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d2);

             }else{
               echo "";
             }

}




//sum day3
if ($_GET['action'] == 'sum_d3') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day3 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 3  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d3 = 0;
             $execute_day3 = mysqli_query($conn,$sql_day3);

             if (mysqli_num_rows($execute_day3) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day3)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d3 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d3);

             }else{
               echo "";
             }

}




//sum day3 by year
if ($_GET['action'] == 'sum_d31' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
$sql_day3 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 3  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d3 = 0;
             $execute_day3 = mysqli_query($conn,$sql_day3);

             if (mysqli_num_rows($execute_day3) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day3)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d3 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d3);

             }else{
               echo "";
             }

}





//sum day4
if ($_GET['action'] == 'sum_d4') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day4 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 4  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d4 = 0;
             $execute_day4 = mysqli_query($conn,$sql_day4);

             if (mysqli_num_rows($execute_day4) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day4)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d4 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d4);

             }else{
               echo "";
             }

}



//sum day4 by year
if ($_GET['action'] == 'sum_d41' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
$sql_day4 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 4  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d4 = 0;
             $execute_day4 = mysqli_query($conn,$sql_day4);

             if (mysqli_num_rows($execute_day4) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day4)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d4 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d4);

             }else{
               echo "";
             }

}




//sum day5
if ($_GET['action'] == 'sum_d5') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day5 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 5  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d5 = 0;
             $execute_day5 = mysqli_query($conn,$sql_day5);

             if (mysqli_num_rows($execute_day5) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day5)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d5 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d5);

             }else{
               echo "";
             }

}




//sum day5 by year
if ($_GET['action'] == 'sum_d51' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day5 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 5  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d5 = 0;
             $execute_day5 = mysqli_query($conn,$sql_day5);

             if (mysqli_num_rows($execute_day5) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day5)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d5 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d5);

             }else{
               echo "";
             }

}




//sum day6
if ($_GET['action'] == 'sum_d6') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day6 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 6  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d6 = 0;
             $execute_day6 = mysqli_query($conn,$sql_day6);

             if (mysqli_num_rows($execute_day6) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day6)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d6 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d6);

             }else{
               echo "";
             }

}



//sum day6 by year
if ($_GET['action'] == 'sum_d61' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day6 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 6  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d6 = 0;
             $execute_day6 = mysqli_query($conn,$sql_day6);

             if (mysqli_num_rows($execute_day6) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day6)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d6 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d6);

             }else{
               echo "";
             }

}





//sum day1
if ($_GET['action'] == 'sum_d7') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day7 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 7  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d7 = 0;
             $execute_day7 = mysqli_query($conn,$sql_day7);

             if (mysqli_num_rows($execute_day7) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day7)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d7 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d7);

             }else{
               echo "";
             }

}



//sum day1 by year
if ($_GET['action'] == 'sum_d71' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
$sql_day7 = "SELECT tone,loc,fits,moro,grasp,suck,respiratory,fontanelle,day
             FROM   tbl_hypoxic_ischaemic_encephalopath
             WHERE  Registration_ID = '$Registration_ID' AND day = 7  AND YEAR(saved_time) = '$y'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d7 = 0;
             $execute_day7 = mysqli_query($conn,$sql_day7);

             if (mysqli_num_rows($execute_day7) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day7)) {
                 $tn = $d1['tone']; $lc = $d1['loc']; $ft = $d1['fits']; $mr = $d1['moro']; $gr = $d1['grasp'];
                 $sk = $d1['suck'];  $rp = $d1['respiratory']; $fn = $d1['fontanelle'];

                 $sum_d7 = $tn + $lc + $ft + $mr + $gr + $sk + $rp + $fn;
               }
               echo json_encode($sum_d7);

             }else{
               echo "";
             }

}


// ************************************ End SUM SIGNS *******************************************



// *****************************basic info ************************************************
$year = date("Y");
if ($_GET['action'] == 'basic_info') {
  $Registration_ID = $_GET['Registration_ID'];
  $sql_basic = "SELECT baby_name,birth_weight,sex,apgar_score1min,apgar_score5min,referral,referral_from,history_or_dx,birth_date,Registration_ID
                FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_basic = mysqli_query($conn,$sql_basic);
                $basic_output = array();

                while($r = mysqli_fetch_assoc($execute_basic))
                {
                  $basic_output[] = $r;
                }

                echo json_encode($basic_output);
}

// by year
if ($_GET['action'] == 'basic_info11' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_basic1 = "SELECT baby_name,birth_weight,sex,apgar_score1min,apgar_score5min,referral,referral_from,history_or_dx,birth_date,Registration_ID
                FROM tbl_hypoxic_ischaemic_encephalopath WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_basic1 = mysqli_query($conn,$sql_basic1);
                $basic_output1 = array();

                while($r1 = mysqli_fetch_assoc($execute_basic1))
                {
                  $basic_output1[] = $r1;
                }

                echo json_encode($basic_output1);
}


// ********************** end *************************************************************



// ************************************ SIGN ***************************************************
// get tone1
if ($_GET['action'] == 'get_tone1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone = "SELECT  tone,saved_time,remarks FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone = mysqli_query($conn,$sql_tone);
                $tone_output = 0;

                while($t = mysqli_fetch_assoc($execute_tone))
                {
                  $tone_output = $t;
                }

                echo json_encode($tone_output);

}


// get tone1 by year
if ($_GET['action'] == 'get_tone11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone = "SELECT  tone,saved_time,remarks FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone = mysqli_query($conn,$sql_tone);
                $tone_output = 0;

                while($t = mysqli_fetch_assoc($execute_tone))
                {
                  $tone_output = $t;
                }

                echo json_encode($tone_output);

}





// get tone2
if ($_GET['action'] == 'get_tone2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone2 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone2 = mysqli_query($conn,$sql_tone2);
                $tone_output2 = 0;

                while($t2 = mysqli_fetch_assoc($execute_tone2))
                {
                  $tone_output2 = $t2;
                }

                echo json_encode($tone_output2);

}


// get tone2 by year
if ($_GET['action'] == 'get_tone21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone2 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone2 = mysqli_query($conn,$sql_tone2);
                $tone_output2 = 0;

                while($t2 = mysqli_fetch_assoc($execute_tone2))
                {
                  $tone_output2 = $t2;
                }

                echo json_encode($tone_output2);

}



// get tone3
if ($_GET['action'] == 'get_tone3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone3 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone3 = mysqli_query($conn,$sql_tone3);
                $tone_output3 = 0;

                while($t3 = mysqli_fetch_assoc($execute_tone3))
                {
                  $tone_output3 = $t3;
                }

                echo json_encode($tone_output3);

}


// get tone3 by year
if ($_GET['action'] == 'get_tone31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone3 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone3 = mysqli_query($conn,$sql_tone3);
                $tone_output3 = 0;

                while($t3 = mysqli_fetch_assoc($execute_tone3))
                {
                  $tone_output3 = $t3;
                }

                echo json_encode($tone_output3);

}


// get tone4
if ($_GET['action'] == 'get_tone4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone4 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone4 = mysqli_query($conn,$sql_tone4);
                $tone_output4 = 0;

                while($t4 = mysqli_fetch_assoc($execute_tone4))
                {
                  $tone_output4 = $t4;
                }

                echo json_encode($tone_output4);

}



// get tone4 by year
if ($_GET['action'] == 'get_tone41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone4 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone4 = mysqli_query($conn,$sql_tone4);
                $tone_output4 = 0;

                while($t4 = mysqli_fetch_assoc($execute_tone4))
                {
                  $tone_output4 = $t4;
                }

                echo json_encode($tone_output4);

}



// get tone5
if ($_GET['action'] == 'get_tone5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone5 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone5 = mysqli_query($conn,$sql_tone5);
                $tone_output5 = 0;

                while($t5 = mysqli_fetch_assoc($execute_tone5))
                {
                  $tone_output5 = $t5;
                }

                echo json_encode($tone_output5);

}



// get tone5 by year
if ($_GET['action'] == 'get_tone51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone5 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone5 = mysqli_query($conn,$sql_tone5);
                $tone_output5 = 0;

                while($t5 = mysqli_fetch_assoc($execute_tone5))
                {
                  $tone_output5 = $t5;
                }

                echo json_encode($tone_output5);

}


// get tone6
if ($_GET['action'] == 'get_tone6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone6 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone6 = mysqli_query($conn,$sql_tone6);
                $tone_output6 = 0;

                while($t6 = mysqli_fetch_assoc($execute_tone6))
                {
                  $tone_output6 = $t6;
                }

                echo json_encode($tone_output6);

}



// get tone6 by year
if ($_GET['action'] == 'get_tone61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone6 = "SELECT  tone,saved_time FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone6 = mysqli_query($conn,$sql_tone6);
                $tone_output6 = 0;

                while($t6 = mysqli_fetch_assoc($execute_tone6))
                {
                  $tone_output6 = $t6;
                }

                echo json_encode($tone_output6);

}



// get tone7
if ($_GET['action'] == 'get_tone7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_tone7 = "SELECT  tone,saved_time,remarks FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone7 = mysqli_query($conn,$sql_tone7);
                $tone_output7 = 0;

                while($t7 = mysqli_fetch_assoc($execute_tone7))
                {
                  $tone_output7 = $t7;
                }

                echo json_encode($tone_output7);

}


// get tone7 by year
if ($_GET['action'] == 'get_tone71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_tone7 = "SELECT  tone,saved_time,remarks FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_tone7 = mysqli_query($conn,$sql_tone7);
                $tone_output7 = 0;

                while($t7 = mysqli_fetch_assoc($execute_tone7))
                {
                  $tone_output7 = $t7;
                }

                echo json_encode($tone_output7);

}

// ***********************end tones ************************************************



// ****************************** LOC ************************************************

// get LOC1
if ($_GET['action'] == 'get_loc1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc1 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc1 = mysqli_query($conn,$sql_loc1);
                $loc_output1 = 0;

                while($t1 = mysqli_fetch_assoc($execute_loc1))
                {
                  $loc_output1 = $t1;
                }

                echo json_encode($loc_output1);

}



// get LOC1 by year
if ($_GET['action'] == 'get_loc11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc1 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc1 = mysqli_query($conn,$sql_loc1);
                $loc_output1 = 0;

                while($t1 = mysqli_fetch_assoc($execute_loc1))
                {
                  $loc_output1 = $t1;
                }

                echo json_encode($loc_output1);

}



// get LOC2
if ($_GET['action'] == 'get_loc2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc2 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc2 = mysqli_query($conn,$sql_loc2);
                $loc_output2 = 0;

                while($t2 = mysqli_fetch_assoc($execute_loc2))
                {
                  $loc_output2 = $t2;
                }

                echo json_encode($loc_output2);

}


// get LOC2 by year
if ($_GET['action'] == 'get_loc21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc2 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc2 = mysqli_query($conn,$sql_loc2);
                $loc_output2 = 0;

                while($t2 = mysqli_fetch_assoc($execute_loc2))
                {
                  $loc_output2 = $t2;
                }

                echo json_encode($loc_output2);

}



// get LOC3
if ($_GET['action'] == 'get_loc3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc3 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc3 = mysqli_query($conn,$sql_loc3);
                $loc_output3 = 0;

                while($t3 = mysqli_fetch_assoc($execute_loc3))
                {
                  $loc_output3 = $t3;
                }

                echo json_encode($loc_output3);

}


// get LOC3 by year
if ($_GET['action'] == 'get_loc31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc3 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc3 = mysqli_query($conn,$sql_loc3);
                $loc_output3 = 0;

                while($t3 = mysqli_fetch_assoc($execute_loc3))
                {
                  $loc_output3 = $t3;
                }

                echo json_encode($loc_output3);

}



// get LOC4
if ($_GET['action'] == 'get_loc4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc4 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc4 = mysqli_query($conn,$sql_loc4);
                $loc_output4 = 0;

                while($t4 = mysqli_fetch_assoc($execute_loc4))
                {
                  $loc_output4 = $t4;
                }

                echo json_encode($loc_output4);

}



// get LOC4 by year
if ($_GET['action'] == 'get_loc41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc4 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc4 = mysqli_query($conn,$sql_loc4);
                $loc_output4 = 0;

                while($t4 = mysqli_fetch_assoc($execute_loc4))
                {
                  $loc_output4 = $t4;
                }

                echo json_encode($loc_output4);

}



// get LOC5
if ($_GET['action'] == 'get_loc5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc5 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc5 = mysqli_query($conn,$sql_loc5);
                $loc_output5 = 0;

                while($t5 = mysqli_fetch_assoc($execute_loc5))
                {
                  $loc_output5 = $t5;
                }

                echo json_encode($loc_output5);

}


// get LOC5 by year
if ($_GET['action'] == 'get_loc51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc5 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc5 = mysqli_query($conn,$sql_loc5);
                $loc_output5 = 0;

                while($t5 = mysqli_fetch_assoc($execute_loc5))
                {
                  $loc_output5 = $t5;
                }

                echo json_encode($loc_output5);

}



// get LOC6
if ($_GET['action'] == 'get_loc6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc6 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc6 = mysqli_query($conn,$sql_loc6);
                $loc_output6 = 0;

                while($t6 = mysqli_fetch_assoc($execute_loc6))
                {
                  $loc_output6 = $t6;
                }

                echo json_encode($loc_output6);

}


// get LOC6 by year
if ($_GET['action'] == 'get_loc61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc6 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc6 = mysqli_query($conn,$sql_loc6);
                $loc_output6 = 0;

                while($t6 = mysqli_fetch_assoc($execute_loc6))
                {
                  $loc_output6 = $t6;
                }

                echo json_encode($loc_output6);

}



// get LOC7
if ($_GET['action'] == 'get_loc7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_loc7 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc7 = mysqli_query($conn,$sql_loc7);
                $loc_output7 = 0;

                while($t7 = mysqli_fetch_assoc($execute_loc7))
                {
                  $loc_output7 = $t7;
                }

                echo json_encode($loc_output7);

}


// get LOC7 by year
if ($_GET['action'] == 'get_loc71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_loc7 = "SELECT loc FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_loc7 = mysqli_query($conn,$sql_loc7);
                $loc_output7 = 0;

                while($t7 = mysqli_fetch_assoc($execute_loc7))
                {
                  $loc_output7 = $t7;
                }

                echo json_encode($loc_output7);

}

// ******************************end LOC ************************************************


// ******************************Fits ************************************************
// get Fits1
if ($_GET['action'] == 'get_fits1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits1 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits1 = mysqli_query($conn,$sql_fits1);
                $fits_output1 = 0;

                while($f1 = mysqli_fetch_assoc($execute_fits1))
                {
                  $fits_output1 = $f1;
                }

                echo json_encode($fits_output1);

}


// get Fits1 by year
if ($_GET['action'] == 'get_fits11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fits1 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits1 = mysqli_query($conn,$sql_fits1);
                $fits_output1 = 0;

                while($f1 = mysqli_fetch_assoc($execute_fits1))
                {
                  $fits_output1 = $f1;
                }

                echo json_encode($fits_output1);

}



// get Fits2
if ($_GET['action'] == 'get_fits2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits2 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits2 = mysqli_query($conn,$sql_fits2);
                $fits_output2 = 0;

                while($f2 = mysqli_fetch_assoc($execute_fits2))
                {
                  $fits_output2 = $f2;
                }

                echo json_encode($fits_output2);

}


// get Fits2 by year
if ($_GET['action'] == 'get_fits21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fits2 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits2 = mysqli_query($conn,$sql_fits2);
                $fits_output2 = 0;

                while($f2 = mysqli_fetch_assoc($execute_fits2))
                {
                  $fits_output2 = $f2;
                }

                echo json_encode($fits_output2);

}



// get Fits3
if ($_GET['action'] == 'get_fits3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits3 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits3 = mysqli_query($conn,$sql_fits3);
                $fits_output3 = 0;

                while($f3 = mysqli_fetch_assoc($execute_fits3))
                {
                  $fits_output3 = $f3;
                }

                echo json_encode($fits_output3);

}



// get Fits3 by year
if ($_GET['action'] == 'get_fits31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fits3 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits3 = mysqli_query($conn,$sql_fits3);
                $fits_output3 = 0;

                while($f3 = mysqli_fetch_assoc($execute_fits3))
                {
                  $fits_output3 = $f3;
                }

                echo json_encode($fits_output3);

}



// get Fits4
if ($_GET['action'] == 'get_fits4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits4 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits4 = mysqli_query($conn,$sql_fits4);
                $fits_output4 = 0;

                while($f4 = mysqli_fetch_assoc($execute_fits4))
                {
                  $fits_output4 = $f4;
                }

                echo json_encode($fits_output4);

}



// get Fits4 by year
if ($_GET['action'] == 'get_fits41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fits4 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits4 = mysqli_query($conn,$sql_fits4);
                $fits_output4 = 0;

                while($f4 = mysqli_fetch_assoc($execute_fits4))
                {
                  $fits_output4 = $f4;
                }

                echo json_encode($fits_output4);

}




// get Fits5
if ($_GET['action'] == 'get_fits5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits5 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits5 = mysqli_query($conn,$sql_fits5);
                $fits_output5 = 0;

                while($f5 = mysqli_fetch_assoc($execute_fits5))
                {
                  $fits_output5 = $f5;
                }

                echo json_encode($fits_output5);

}



// get Fits5 by year
if ($_GET['action'] == 'get_fits51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y =$_GET['year'];
   $sql_fits5 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits5 = mysqli_query($conn,$sql_fits5);
                $fits_output5 = 0;

                while($f5 = mysqli_fetch_assoc($execute_fits5))
                {
                  $fits_output5 = $f5;
                }

                echo json_encode($fits_output5);

}




// get Fits6
if ($_GET['action'] == 'get_fits6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits6 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits6 = mysqli_query($conn,$sql_fits6);
                $fits_output6 = 0;

                while($f6 = mysqli_fetch_assoc($execute_fits6))
                {
                  $fits_output6 = $f6;
                }

                echo json_encode($fits_output6);

}



// get Fits6 by year
if ($_GET['action'] == 'get_fits61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fits6 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits6 = mysqli_query($conn,$sql_fits6);
                $fits_output6 = 0;

                while($f6 = mysqli_fetch_assoc($execute_fits6))
                {
                  $fits_output6 = $f6;
                }

                echo json_encode($fits_output6);

}



// get Fits7
if ($_GET['action'] == 'get_fits7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fits7 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits7 = mysqli_query($conn,$sql_fits7);
                $fits_output7 = 0;

                while($f7 = mysqli_fetch_assoc($execute_fits7))
                {
                  $fits_output7 = $f7;
                }

                echo json_encode($fits_output7);

}



// get Fits7 by year
if ($_GET['action'] == 'get_fits71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fits7 = "SELECT fits FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fits7 = mysqli_query($conn,$sql_fits7);
                $fits_output7 = 0;

                while($f7 = mysqli_fetch_assoc($execute_fits7))
                {
                  $fits_output7 = $f7;
                }

                echo json_encode($fits_output7);

}


// ******************************end Fits ************************************************




// ****************************** Posture ************************************************


// get Posture1
if ($_GET['action'] == 'get_posture1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture1 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture1 = mysqli_query($conn,$sql_posture1);
                $posture_output1 = 0;

                while($p1= mysqli_fetch_assoc($execute_posture1))
                {
                  $posture_output1 = $p1;
                }

                echo json_encode($posture_output1);

}


// get Posture1 by year
if ($_GET['action'] == 'get_posture11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_posture1 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture1 = mysqli_query($conn,$sql_posture1);
                $posture_output1 = 0;

                while($p1= mysqli_fetch_assoc($execute_posture1))
                {
                  $posture_output1 = $p1;
                }

                echo json_encode($posture_output1);

}



// get Posture2
if ($_GET['action'] == 'get_posture2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture2 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture2 = mysqli_query($conn,$sql_posture2);
                $posture_output2 = 0;

                while($p2 = mysqli_fetch_assoc($execute_posture2))
                {
                  $posture_output2 = $p2;
                }

                echo json_encode($posture_output2);

}


// get Posture2 by year
if ($_GET['action'] == 'get_posture21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_posture2 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture2 = mysqli_query($conn,$sql_posture2);
                $posture_output2 = 0;

                while($p2 = mysqli_fetch_assoc($execute_posture2))
                {
                  $posture_output2 = $p2;
                }

                echo json_encode($posture_output2);

}



// get Posture3
if ($_GET['action'] == 'get_posture3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture3 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture3 = mysqli_query($conn,$sql_posture3);
                $posture_output3 = 0;

                while($p3 = mysqli_fetch_assoc($execute_posture3))
                {
                  $posture_output3 = $p3;
                }

                echo json_encode($posture_output3);

}


// get Posture3 by year
if ($_GET['action'] == 'get_posture31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_posture3 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture3 = mysqli_query($conn,$sql_posture3);
                $posture_output3 = 0;

                while($p3 = mysqli_fetch_assoc($execute_posture3))
                {
                  $posture_output3 = $p3;
                }

                echo json_encode($posture_output3);

}



// get Posture4
if ($_GET['action'] == 'get_posture4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture4 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture4 = mysqli_query($conn,$sql_posture4);
                $posture_output4 = 0;

                while($p4 = mysqli_fetch_assoc($execute_posture4))
                {
                  $posture_output4 = $p4;
                }

                echo json_encode($posture_output4);

}


// get Posture4 by year
if ($_GET['action'] == 'get_posture41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y  = $_GET['year'];
   $sql_posture4 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture4 = mysqli_query($conn,$sql_posture4);
                $posture_output4 = 0;

                while($p4 = mysqli_fetch_assoc($execute_posture4))
                {
                  $posture_output4 = $p4;
                }

                echo json_encode($posture_output4);

}



// get Posture5
if ($_GET['action'] == 'get_posture5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture5 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture5 = mysqli_query($conn,$sql_posture5);
                $posture_output5 = 0;

                while($p5 = mysqli_fetch_assoc($execute_posture5))
                {
                  $posture_output5 = $p5;
                }

                echo json_encode($posture_output5);

}


// get Posture5 by year
if ($_GET['action'] == 'get_posture51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_posture5 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture5 = mysqli_query($conn,$sql_posture5);
                $posture_output5 = 0;

                while($p5 = mysqli_fetch_assoc($execute_posture5))
                {
                  $posture_output5 = $p5;
                }

                echo json_encode($posture_output5);

}



// get Posture6
if ($_GET['action'] == 'get_posture6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture6 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture6 = mysqli_query($conn,$sql_posture6);
                $posture_output6 = 0;

                while($p6 = mysqli_fetch_assoc($execute_posture6))
                {
                  $posture_output6 = $p6;
                }

                echo json_encode($posture_output6);

}


// get Posture6 by year
if ($_GET['action'] == 'get_posture61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_posture6 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture6 = mysqli_query($conn,$sql_posture6);
                $posture_output6 = 0;

                while($p6 = mysqli_fetch_assoc($execute_posture6))
                {
                  $posture_output6 = $p6;
                }

                echo json_encode($posture_output6);

}



// get Posture7
if ($_GET['action'] == 'get_posture7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_posture7 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture7 = mysqli_query($conn,$sql_posture7);
                $posture_output7 = 0;

                while($p7 = mysqli_fetch_assoc($execute_posture7))
                {
                  $posture_output7 = $p7;
                }

                echo json_encode($posture_output7);

}



// get Posture7 by year
if ($_GET['action'] == 'get_posture71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_posture7 = "SELECT posture FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_posture7 = mysqli_query($conn,$sql_posture7);
                $posture_output7 = 0;

                while($p7 = mysqli_fetch_assoc($execute_posture7))
                {
                  $posture_output7 = $p7;
                }

                echo json_encode($posture_output7);

}




// ******************************end Posture ************************************************




// ****************************** Moro ************************************************

// get Moro1
if ($_GET['action'] == 'get_moro1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro1 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro1 = mysqli_query($conn,$sql_moro1);
                $moro_output1 = 0;

                while($m1 = mysqli_fetch_assoc($execute_moro1))
                {
                  $moro_output1 = $m1;
                }

                echo json_encode($moro_output1);

}


// get Moro1 by year
if ($_GET['action'] == 'get_moro11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro1 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro1 = mysqli_query($conn,$sql_moro1);
                $moro_output1 = 0;

                while($m1 = mysqli_fetch_assoc($execute_moro1))
                {
                  $moro_output1 = $m1;
                }

                echo json_encode($moro_output1);

}



// get Moro2
if ($_GET['action'] == 'get_moro2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro2 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro2 = mysqli_query($conn,$sql_moro2);
                $moro_output2 = 0;

                while($m2 = mysqli_fetch_assoc($execute_moro2))
                {
                  $moro_output2 = $m2;
                }

                echo json_encode($moro_output2);

}



// get Moro2 by year
if ($_GET['action'] == 'get_moro21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro2 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro2 = mysqli_query($conn,$sql_moro2);
                $moro_output2 = 0;

                while($m2 = mysqli_fetch_assoc($execute_moro2))
                {
                  $moro_output2 = $m2;
                }

                echo json_encode($moro_output2);

}




// get Moro3
if ($_GET['action'] == 'get_moro3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro3 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro3 = mysqli_query($conn,$sql_moro3);
                $moro_output3 = 0;

                while($m3 = mysqli_fetch_assoc($execute_moro3))
                {
                  $moro_output3 = $m3;
                }

                echo json_encode($moro_output3);

}


// get Moro3 by year
if ($_GET['action'] == 'get_moro31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro3 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro3 = mysqli_query($conn,$sql_moro3);
                $moro_output3 = 0;

                while($m3 = mysqli_fetch_assoc($execute_moro3))
                {
                  $moro_output3 = $m3;
                }

                echo json_encode($moro_output3);

}



// get Moro4
if ($_GET['action'] == 'get_moro4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro4 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro4 = mysqli_query($conn,$sql_moro4);
                $moro_output4 = 0;

                while($m4 = mysqli_fetch_assoc($execute_moro4))
                {
                  $moro_output4 = $m4;
                }

                echo json_encode($moro_output4);

}




// get Moro4 by year
if ($_GET['action'] == 'get_moro41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro4 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro4 = mysqli_query($conn,$sql_moro4);
                $moro_output4 = 0;

                while($m4 = mysqli_fetch_assoc($execute_moro4))
                {
                  $moro_output4 = $m4;
                }

                echo json_encode($moro_output4);

}



// get Moro5
if ($_GET['action'] == 'get_moro5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro5 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro5 = mysqli_query($conn,$sql_moro5);
                $moro_output5 = 0;

                while($m5 = mysqli_fetch_assoc($execute_moro5))
                {
                  $moro_output5 = $m5;
                }

                echo json_encode($moro_output5);

}



// get Moro5 by year
if ($_GET['action'] == 'get_moro51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro5 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro5 = mysqli_query($conn,$sql_moro5);
                $moro_output5 = 0;

                while($m5 = mysqli_fetch_assoc($execute_moro5))
                {
                  $moro_output5 = $m5;
                }

                echo json_encode($moro_output5);

}




// get Moro6
if ($_GET['action'] == 'get_moro6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro6 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro6 = mysqli_query($conn,$sql_moro6);
                $moro_output6 = 0;

                while($m6 = mysqli_fetch_assoc($execute_moro6))
                {
                  $moro_output6 = $m6;
                }

                echo json_encode($moro_output6);

}



// get Moro6 by year
if ($_GET['action'] == 'get_moro61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro6 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro6 = mysqli_query($conn,$sql_moro6);
                $moro_output6 = 0;

                while($m6 = mysqli_fetch_assoc($execute_moro6))
                {
                  $moro_output6 = $m6;
                }

                echo json_encode($moro_output6);

}





// get Moro7
if ($_GET['action'] == 'get_moro7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_moro7 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro7 = mysqli_query($conn,$sql_moro7);
                $moro_output7 = 0;

                while($m7 = mysqli_fetch_assoc($execute_moro7))
                {
                  $moro_output7 = $m7;
                }

                echo json_encode($moro_output7);

}



// get Moro7 by year
if ($_GET['action'] == 'get_moro71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_moro7 = "SELECT moro FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_moro7 = mysqli_query($conn,$sql_moro7);
                $moro_output7 = 0;

                while($m7 = mysqli_fetch_assoc($execute_moro7))
                {
                  $moro_output7 = $m7;
                }

                echo json_encode($moro_output7);

}


// ******************************end Moro ************************************************




// ****************************** Grasp ************************************************
// get Grasp1
if ($_GET['action'] == 'get_grasp1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp1 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp1 = mysqli_query($conn,$sql_grasp1);
                $grasp_output1 = 0;

                while($g = mysqli_fetch_assoc($execute_grasp1))
                {
                  $grasp_output1 = $g;
                }

                echo json_encode($grasp_output1);

}


// get Grasp1 by year
if ($_GET['action'] == 'get_grasp11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp1 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp1 = mysqli_query($conn,$sql_grasp1);
                $grasp_output1 = 0;

                while($g = mysqli_fetch_assoc($execute_grasp1))
                {
                  $grasp_output1 = $g;
                }

                echo json_encode($grasp_output1);

}


// get Grasp2
if ($_GET['action'] == 'get_grasp2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp2 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp2 = mysqli_query($conn,$sql_grasp2);
                $grasp_output2 = 0;

                while($g2 = mysqli_fetch_assoc($execute_grasp2))
                {
                  $grasp_output2 = $g2;
                }

                echo json_encode($grasp_output2);

}



// get Grasp2 by year
if ($_GET['action'] == 'get_grasp21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp2 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp2 = mysqli_query($conn,$sql_grasp2);
                $grasp_output2 = 0;

                while($g2 = mysqli_fetch_assoc($execute_grasp2))
                {
                  $grasp_output2 = $g2;
                }

                echo json_encode($grasp_output2);

}




// get Grasp3
if ($_GET['action'] == 'get_grasp3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp3 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp3 = mysqli_query($conn,$sql_grasp3);
                $grasp_output3 = 0;

                while($g3 = mysqli_fetch_assoc($execute_grasp3))
                {
                  $grasp_output3 = $g3;
                }

                echo json_encode($grasp_output3);

}



// get Grasp3 by year
if ($_GET['action'] == 'get_grasp31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp3 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp3 = mysqli_query($conn,$sql_grasp3);
                $grasp_output3 = 0;

                while($g3 = mysqli_fetch_assoc($execute_grasp3))
                {
                  $grasp_output3 = $g3;
                }

                echo json_encode($grasp_output3);

}



// get Grasp4
if ($_GET['action'] == 'get_grasp4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp4 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp4 = mysqli_query($conn,$sql_grasp4);
                $grasp_output4 = 0;

                while($g4 = mysqli_fetch_assoc($execute_grasp4))
                {
                  $grasp_output4 = $g4;
                }

                echo json_encode($grasp_output4);

}


// get Grasp4 bby year
if ($_GET['action'] == 'get_grasp41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp4 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp4 = mysqli_query($conn,$sql_grasp4);
                $grasp_output4 = 0;

                while($g4 = mysqli_fetch_assoc($execute_grasp4))
                {
                  $grasp_output4 = $g4;
                }

                echo json_encode($grasp_output4);

}



// get Grasp5
if ($_GET['action'] == 'get_grasp5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp5 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp5 = mysqli_query($conn,$sql_grasp5);
                $grasp_output5 = 0;

                while($g5 = mysqli_fetch_assoc($execute_grasp5))
                {
                  $grasp_output5 = $g5;
                }

                echo json_encode($grasp_output5);

}


// get Grasp5 by year
if ($_GET['action'] == 'get_grasp51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp5 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp5 = mysqli_query($conn,$sql_grasp5);
                $grasp_output5 = 0;

                while($g5 = mysqli_fetch_assoc($execute_grasp5))
                {
                  $grasp_output5 = $g5;
                }

                echo json_encode($grasp_output5);

}





// get Grasp6
if ($_GET['action'] == 'get_grasp6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp6 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp6 = mysqli_query($conn,$sql_grasp6);
                $grasp_output6 = 0;

                while($g6 = mysqli_fetch_assoc($execute_grasp6))
                {
                  $grasp_output6 = $g6;
                }

                echo json_encode($grasp_output6);

}



// get Grasp6 by year
if ($_GET['action'] == 'get_grasp61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp6 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp6 = mysqli_query($conn,$sql_grasp6);
                $grasp_output6 = 0;

                while($g6 = mysqli_fetch_assoc($execute_grasp6))
                {
                  $grasp_output6 = $g6;
                }

                echo json_encode($grasp_output6);

}



// get Grasp7
if ($_GET['action'] == 'get_grasp7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_grasp7 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp7 = mysqli_query($conn,$sql_grasp7);
                $grasp_output7 = 0;

                while($g7 = mysqli_fetch_assoc($execute_grasp7))
                {
                  $grasp_output7 = $g7;
                }

                echo json_encode($grasp_output7);

}


// get Grasp7 by year
if ($_GET['action'] == 'get_grasp71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_grasp7 = "SELECT grasp FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_grasp7 = mysqli_query($conn,$sql_grasp7);
                $grasp_output7 = 0;

                while($g7 = mysqli_fetch_assoc($execute_grasp7))
                {
                  $grasp_output7 = $g7;
                }

                echo json_encode($grasp_output7);

}


// ******************************end Grasp ************************************************




// ****************************** Suck ************************************************

// get Suck1
if ($_GET['action'] == 'get_suck1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck1 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck1 = mysqli_query($conn,$sql_suck1);
                $suck_output1 = 0;

                while($s1 = mysqli_fetch_assoc($execute_suck1))
                {
                  $suck_output1 = $s1;
                }

                echo json_encode($suck_output1);

}


// get Suck1 by year
if ($_GET['action'] == 'get_suck11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck1 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck1 = mysqli_query($conn,$sql_suck1);
                $suck_output1 = 0;

                while($s1 = mysqli_fetch_assoc($execute_suck1))
                {
                  $suck_output1 = $s1;
                }

                echo json_encode($suck_output1);

}



// get Suck2
if ($_GET['action'] == 'get_suck2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck2 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck2 = mysqli_query($conn,$sql_suck2);
                $suck_output2 = 0;

                while($s2 = mysqli_fetch_assoc($execute_suck2))
                {
                  $suck_output2 = $s2;
                }

                echo json_encode($suck_output2);

}


// get Suck2 by year
if ($_GET['action'] == 'get_suck21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck2 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck2 = mysqli_query($conn,$sql_suck2);
                $suck_output2 = 0;

                while($s2 = mysqli_fetch_assoc($execute_suck2))
                {
                  $suck_output2 = $s2;
                }

                echo json_encode($suck_output2);

}


// get Suck3
if ($_GET['action'] == 'get_suck3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck3 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck3 = mysqli_query($conn,$sql_suck3);
                $suck_output3 = 0;

                while($s3 = mysqli_fetch_assoc($execute_suck3))
                {
                  $suck_output3 = $s3;
                }

                echo json_encode($suck_output3);

}



// get Suck3 by year
if ($_GET['action'] == 'get_suck31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck3 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck3 = mysqli_query($conn,$sql_suck3);
                $suck_output3 = 0;

                while($s3 = mysqli_fetch_assoc($execute_suck3))
                {
                  $suck_output3 = $s3;
                }

                echo json_encode($suck_output3);

}




// get Suck4
if ($_GET['action'] == 'get_suck4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck4 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck4 = mysqli_query($conn,$sql_suck4);
                $suck_output4 = 0;

                while($s4 = mysqli_fetch_assoc($execute_suck4))
                {
                  $suck_output4 = $s4;
                }

                echo json_encode($suck_output4);

}



// get Suck4 by year
if ($_GET['action'] == 'get_suck41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck4 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck4 = mysqli_query($conn,$sql_suck4);
                $suck_output4 = 0;

                while($s4 = mysqli_fetch_assoc($execute_suck4))
                {
                  $suck_output4 = $s4;
                }

                echo json_encode($suck_output4);

}



// get Suck5
if ($_GET['action'] == 'get_suck5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck5 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck5 = mysqli_query($conn,$sql_suck5);
                $suck_output5 = 0;

                while($s5 = mysqli_fetch_assoc($execute_suck5))
                {
                  $suck_output5 = $s5;
                }

                echo json_encode($suck_output5);

}



// get Suck5 year
if ($_GET['action'] == 'get_suck51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck5 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck5 = mysqli_query($conn,$sql_suck5);
                $suck_output5 = 0;

                while($s5 = mysqli_fetch_assoc($execute_suck5))
                {
                  $suck_output5 = $s5;
                }

                echo json_encode($suck_output5);

}




// get Suck6
if ($_GET['action'] == 'get_suck6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck6 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck6 = mysqli_query($conn,$sql_suck6);
                $suck_output6 = 0;

                while($s6 = mysqli_fetch_assoc($execute_suck6))
                {
                  $suck_output6 = $s6;
                }

                echo json_encode($suck_output6);

}


// get Suck6 by year
if ($_GET['action'] == 'get_suck61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck6 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck6 = mysqli_query($conn,$sql_suck6);
                $suck_output6 = 0;

                while($s6 = mysqli_fetch_assoc($execute_suck6))
                {
                  $suck_output6 = $s6;
                }

                echo json_encode($suck_output6);

}


// get Suck7
if ($_GET['action'] == 'get_suck7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_suck7 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck7 = mysqli_query($conn,$sql_suck7);
                $suck_output7 = 0;

                while($s7 = mysqli_fetch_assoc($execute_suck7))
                {
                  $suck_output7 = $s7;
                }

                echo json_encode($suck_output7);

}


// get Suck7 by year
if ($_GET['action'] == 'get_suck71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_suck7 = "SELECT suck FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_suck7 = mysqli_query($conn,$sql_suck7);
                $suck_output7 = 0;

                while($s7 = mysqli_fetch_assoc($execute_suck7))
                {
                  $suck_output7 = $s7;
                }

                echo json_encode($suck_output7);

}



// ******************************end Suck ************************************************




// ****************************** Respiratory ************************************************

// get Respiratory1
if ($_GET['action'] == 'get_respiratory1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory1 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory1 = mysqli_query($conn,$sql_respiratory1);
                $respiratory_output1 = 0;

                while($r1 = mysqli_fetch_assoc($execute_respiratory1))
                {
                  $respiratory_output1 = $r1;
                }

                echo json_encode($respiratory_output1);

}


// get Respiratory1 by year
if ($_GET['action'] == 'get_respiratory11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory1 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory1 = mysqli_query($conn,$sql_respiratory1);
                $respiratory_output1 = 0;

                while($r1 = mysqli_fetch_assoc($execute_respiratory1))
                {
                  $respiratory_output1 = $r1;
                }

                echo json_encode($respiratory_output1);

}



// get Respiratory2
if ($_GET['action'] == 'get_respiratory2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory2 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory2 = mysqli_query($conn,$sql_respiratory2);
                $respiratory_output2 = 0;

                while($r2 = mysqli_fetch_assoc($execute_respiratory2))
                {
                  $respiratory_output2 = $r2;
                }

                echo json_encode($respiratory_output2);

}


// get Respiratory2 by year
if ($_GET['action'] == 'get_respiratory21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory2 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory2 = mysqli_query($conn,$sql_respiratory2);
                $respiratory_output2 = 0;

                while($r2 = mysqli_fetch_assoc($execute_respiratory2))
                {
                  $respiratory_output2 = $r2;
                }

                echo json_encode($respiratory_output2);

}


// get Respiratory3
if ($_GET['action'] == 'get_respiratory3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory3 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory3 = mysqli_query($conn,$sql_respiratory3);
                $respiratory_output3 = 0;

                while($r3 = mysqli_fetch_assoc($execute_respiratory3))
                {
                  $respiratory_output3 = $r3;
                }

                echo json_encode($respiratory_output3);

}


// get Respiratory3 by year
if ($_GET['action'] == 'get_respiratory31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory3 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory3 = mysqli_query($conn,$sql_respiratory3);
                $respiratory_output3 = 0;

                while($r3 = mysqli_fetch_assoc($execute_respiratory3))
                {
                  $respiratory_output3 = $r3;
                }

                echo json_encode($respiratory_output3);

}



// get Respiratory4
if ($_GET['action'] == 'get_respiratory4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory4 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory4 = mysqli_query($conn,$sql_respiratory4);
                $respiratory_output4 = 0;

                while($r4 = mysqli_fetch_assoc($execute_respiratory4))
                {
                  $respiratory_output4 = $r4;
                }

                echo json_encode($respiratory_output4);

}



// get Respiratory4 by year
if ($_GET['action'] == 'get_respiratory41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory4 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory4 = mysqli_query($conn,$sql_respiratory4);
                $respiratory_output4 = 0;

                while($r4 = mysqli_fetch_assoc($execute_respiratory4))
                {
                  $respiratory_output4 = $r4;
                }

                echo json_encode($respiratory_output4);

}


// get Respiratory5
if ($_GET['action'] == 'get_respiratory5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory5 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory5 = mysqli_query($conn,$sql_respiratory5);
                $respiratory_output5 = 0;

                while($r5 = mysqli_fetch_assoc($execute_respiratory5))
                {
                  $respiratory_output5 = $r5;
                }

                echo json_encode($respiratory_output5);

}


// get Respiratory5 by year
if ($_GET['action'] == 'get_respiratory51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory5 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory5 = mysqli_query($conn,$sql_respiratory5);
                $respiratory_output5 = 0;

                while($r5 = mysqli_fetch_assoc($execute_respiratory5))
                {
                  $respiratory_output5 = $r5;
                }

                echo json_encode($respiratory_output5);

}


// get Respiratory6
if ($_GET['action'] == 'get_respiratory6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory6 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory6 = mysqli_query($conn,$sql_respiratory6);
                $respiratory_output6 = 0;

                while($r6 = mysqli_fetch_assoc($execute_respiratory6))
                {
                  $respiratory_output6 = $r6;
                }

                echo json_encode($respiratory_output6);

}


// get Respiratory6 by year
if ($_GET['action'] == 'get_respiratory61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory6 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory6 = mysqli_query($conn,$sql_respiratory6);
                $respiratory_output6 = 0;

                while($r6 = mysqli_fetch_assoc($execute_respiratory6))
                {
                  $respiratory_output6 = $r6;
                }

                echo json_encode($respiratory_output6);

}



// get Respiratory7
if ($_GET['action'] == 'get_respiratory7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_respiratory7 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory7 = mysqli_query($conn,$sql_respiratory7);
                $respiratory_output7 = 0;

                while($r7 = mysqli_fetch_assoc($execute_respiratory7))
                {
                  $respiratory_output7 = $r7;
                }

                echo json_encode($respiratory_output7);

}


// get Respiratory7 by year
if ($_GET['action'] == 'get_respiratory71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_respiratory7 = "SELECT respiratory FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_respiratory7 = mysqli_query($conn,$sql_respiratory7);
                $respiratory_output7 = 0;

                while($r7 = mysqli_fetch_assoc($execute_respiratory7))
                {
                  $respiratory_output7 = $r7;
                }

                echo json_encode($respiratory_output7);

}

// ****************************** end Respiratory ************************************************




// ******************************  fontanelle ************************************************
// get fontanelle1
if ($_GET['action'] == 'get_fontanelle1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle1 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle1 = mysqli_query($conn,$sql_fontanelle1);
                $fontanelle_output1 = 0;

                while($fn1 = mysqli_fetch_assoc($execute_fontanelle1))
                {
                  $fontanelle_output1 = $fn1;
                }

                echo json_encode($fontanelle_output1);

}



// get fontanelle1 by year
if ($_GET['action'] == 'get_fontanelle11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y  = $_GET['year'];
   $sql_fontanelle1 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle1 = mysqli_query($conn,$sql_fontanelle1);
                $fontanelle_output1 = 0;

                while($fn1 = mysqli_fetch_assoc($execute_fontanelle1))
                {
                  $fontanelle_output1 = $fn1;
                }

                echo json_encode($fontanelle_output1);

}



// get fontanelle2
if ($_GET['action'] == 'get_fontanelle2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle2 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle2 = mysqli_query($conn,$sql_fontanelle2);
                $fontanelle_output2 = 0;

                while($fn2 = mysqli_fetch_assoc($execute_fontanelle2))
                {
                  $fontanelle_output2 = $fn2;
                }

                echo json_encode($fontanelle_output2);

}



// get fontanelle2 by year
if ($_GET['action'] == 'get_fontanelle21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fontanelle2 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle2 = mysqli_query($conn,$sql_fontanelle2);
                $fontanelle_output2 = 0;

                while($fn2 = mysqli_fetch_assoc($execute_fontanelle2))
                {
                  $fontanelle_output2 = $fn2;
                }

                echo json_encode($fontanelle_output2);

}


// get fontanelle3
if ($_GET['action'] == 'get_fontanelle3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle3 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle3 = mysqli_query($conn,$sql_fontanelle3);
                $fontanelle_output3 = 0;

                while($fn3 = mysqli_fetch_assoc($execute_fontanelle3))
                {
                  $fontanelle_output3 = $fn3;
                }

                echo json_encode($fontanelle_output3);

}



// get fontanelle3 by year
if ($_GET['action'] == 'get_fontanelle31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fontanelle3 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle3 = mysqli_query($conn,$sql_fontanelle3);
                $fontanelle_output3 = 0;

                while($fn3 = mysqli_fetch_assoc($execute_fontanelle3))
                {
                  $fontanelle_output3 = $fn3;
                }

                echo json_encode($fontanelle_output3);

}




// get fontanelle4
if ($_GET['action'] == 'get_fontanelle4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle4 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle4 = mysqli_query($conn,$sql_fontanelle4);
                $fontanelle_output4 = 0;

                while($fn4 = mysqli_fetch_assoc($execute_fontanelle4))
                {
                  $fontanelle_output4 = $fn4;
                }

                echo json_encode($fontanelle_output4);

}


// get fontanelle4 by year
if ($_GET['action'] == 'get_fontanelle41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fontanelle4 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle4 = mysqli_query($conn,$sql_fontanelle4);
                $fontanelle_output4 = 0;

                while($fn4 = mysqli_fetch_assoc($execute_fontanelle4))
                {
                  $fontanelle_output4 = $fn4;
                }

                echo json_encode($fontanelle_output4);

}




// get fontanelle5
if ($_GET['action'] == 'get_fontanelle5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle5 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle5 = mysqli_query($conn,$sql_fontanelle5);
                $fontanelle_output5 = 0;

                while($fn5 = mysqli_fetch_assoc($execute_fontanelle5))
                {
                  $fontanelle_output5 = $fn5;
                }

                echo json_encode($fontanelle_output5);

}



// get fontanelle5 by year
if ($_GET['action'] == 'get_fontanelle51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fontanelle5 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle5 = mysqli_query($conn,$sql_fontanelle5);
                $fontanelle_output5 = 0;

                while($fn5 = mysqli_fetch_assoc($execute_fontanelle5))
                {
                  $fontanelle_output5 = $fn5;
                }

                echo json_encode($fontanelle_output5);

}



// get fontanelle6
if ($_GET['action'] == 'get_fontanelle6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle6 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle6 = mysqli_query($conn,$sql_fontanelle6);
                $fontanelle_output6 = 0;

                while($fn6 = mysqli_fetch_assoc($execute_fontanelle6))
                {
                  $fontanelle_output6 = $fn6;
                }

                echo json_encode($fontanelle_output6);

}



// get fontanelle6 by year
if ($_GET['action'] == 'get_fontanelle61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fontanelle6 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle6 = mysqli_query($conn,$sql_fontanelle6);
                $fontanelle_output6 = 0;

                while($fn6 = mysqli_fetch_assoc($execute_fontanelle6))
                {
                  $fontanelle_output6 = $fn6;
                }

                echo json_encode($fontanelle_output6);

}




// get fontanelle7
if ($_GET['action'] == 'get_fontanelle7' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_fontanelle7 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle7 = mysqli_query($conn,$sql_fontanelle7);
                $fontanelle_output7 = 0;

                while($fn7 = mysqli_fetch_assoc($execute_fontanelle7))
                {
                  $fontanelle_output7 = $fn7;
                }

                echo json_encode($fontanelle_output7);

}


// get fontanelle7 by year
if ($_GET['action'] == 'get_fontanelle71' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_fontanelle7 = "SELECT fontanelle FROM tbl_hypoxic_ischaemic_encephalopath
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '7'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_fontanelle7 = mysqli_query($conn,$sql_fontanelle7);
                $fontanelle_output7 = 0;

                while($fn7 = mysqli_fetch_assoc($execute_fontanelle7))
                {
                  $fontanelle_output7 = $fn7;
                }

                echo json_encode($fontanelle_output7);

}
// ****************************** end fontanelle ************************************************


// **************************** End SIGN *******************************************************




 ?>

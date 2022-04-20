<?php
include('../includes/connection.php');
include("../MPDF/mpdf.php");

//header("Access-Control-Allow-Origin: *");
if(strpos($_SERVER['HTTP_ORIGIN'], 'javascript') == false)
																			{
		header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
}




// ********************* SAVE SILVERMAN ***************************************************************************************
$data = json_decode(file_get_contents('php://input'));
$year = date("Y");

if ($data->action == 'save_silverman') {
  $employee_ID = mysqli_real_escape_string($conn,trim($data->Employee_ID));
  $Registration_ID = mysqli_real_escape_string($conn,trim($data->Registration_ID));
  $Upper_chest = mysqli_real_escape_string($conn,trim($data->Upper_chest));
  $Lower_chest = mysqli_real_escape_string($conn,trim($data->Lower_chest));
  $Xiphoid_retraction = mysqli_real_escape_string($conn,trim($data->Xiphoid_retraction));
  $Nasal_flaring = mysqli_real_escape_string($conn,trim($data->Nasal_flaring));
  $Expiratory_grant = mysqli_real_escape_string($conn,trim($data->Expiratory_grant));
  $patient_name = mysqli_real_escape_string($conn,trim($data->patient_name));
  $admission_date = mysqli_real_escape_string($conn,trim($data->admission_date));
  $Gestation_age_at_birth = mysqli_real_escape_string($conn,trim($data->Gestation_age_at_birth));
  $sex = mysqli_real_escape_string($conn,trim($data->sex));
  $day = mysqli_real_escape_string($conn,trim($data->day));
  $Admision_ID = mysqli_real_escape_string($conn,trim($data->Admision_ID));
  $consultation_id = mysqli_real_escape_string($conn,trim($data->consultation_id));


  $sql_silverman = "INSERT INTO tbl_silverman_anderson_score_chart(
                    Registration_ID,Employee_ID,Admision_ID,consultation_id,Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,
                    patient_name,admission_date,Gestation_age_at_birth,sex,day)
                    VALUES('$Registration_ID','$employee_ID','$Admision_ID','$consultation_id','$Upper_chest','$Lower_chest','$Xiphoid_retraction','$Nasal_flaring',
                    '$Expiratory_grant','$patient_name','$admission_date','$Gestation_age_at_birth','$sex','$day')";

                    $execute = mysqli_query($conn,$sql_silverman);

                    if ($execute) {
                      echo "Record Saved Successfully!";
                    }else{
                      die("Failed to Save Record".mysqli_error($conn));
                    }
}
// ******************************************** end ********************************************************************************************


// *************************************** RETRIEVE BASIC INFO *********************************************************************************
if ($_GET['action'] == 'basic_info') {
  $Registration_ID = $_GET['Registration_ID'];
  $sql_basic = "SELECT Registration_ID,patient_name,admission_date,Gestation_age_at_birth,sex
                FROM tbl_silverman_anderson_score_chart WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year'
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
  $sql_basic1 = "SELECT Registration_ID,patient_name,admission_date,Gestation_age_at_birth,sex
                FROM tbl_silverman_anderson_score_chart WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_basic1 = mysqli_query($conn,$sql_basic1);
                $basic_output1 = array();

                while($r1 = mysqli_fetch_assoc($execute_basic1))
                {
                  $basic_output1[] = $r1;
                }

                echo json_encode($basic_output1);
}


// **************************************** end ***********************************************************************************************



// ***************************************************** ITEM SCORE ****************************************************************************
// get Upper_chest1
if ($_GET['action'] == 'get_upper1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Upper_chest1 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest1 = mysqli_query($conn,$sql_Upper_chest1);
                $Upper_chest1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest1))
                {
                  $Upper_chest1_output = $t;
                }

                echo json_encode($Upper_chest1_output);

}


// get Upper_chest1 by year
if ($_GET['action'] == 'get_upper11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Upper_chest11 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest11 = mysqli_query($conn,$sql_Upper_chest11);
                $Upper_chest11_output = 0;


                  while($t = mysqli_fetch_assoc($execute_Upper_chest11))
                  {
                    $Upper_chest11_output = $t;
                  }
                  //echo "Data available";

                  echo json_encode($Upper_chest11_output);


}



// get Upper_chest2
if ($_GET['action'] == 'get_upper2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Upper_chest2 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest2 = mysqli_query($conn,$sql_Upper_chest2);
                $Upper_chest2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest2))
                {
                  $Upper_chest2_output = $t;
                }

                echo json_encode($Upper_chest2_output);

}


// get Upper_chest2 by year
if ($_GET['action'] == 'get_upper21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Upper_chest2 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest2 = mysqli_query($conn,$sql_Upper_chest2);
                $Upper_chest2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest2))
                {
                  $Upper_chest2_output = $t;
                }

                echo json_encode($Upper_chest2_output);

}



// get Upper_chest3
if ($_GET['action'] == 'get_upper3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Upper_chest3 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest3 = mysqli_query($conn,$sql_Upper_chest3);
                $Upper_chest3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest3))
                {
                  $Upper_chest3_output = $t;
                }

                echo json_encode($Upper_chest3_output);

}


// get Upper_chest3 by year
if ($_GET['action'] == 'get_upper31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Upper_chest3 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest3 = mysqli_query($conn,$sql_Upper_chest3);
                $Upper_chest3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest3))
                {
                  $Upper_chest3_output = $t;
                }

                echo json_encode($Upper_chest3_output);

}



// get Upper_chest4
if ($_GET['action'] == 'get_upper4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Upper_chest4 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest4 = mysqli_query($conn,$sql_Upper_chest4);
                $Upper_chest4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest4))
                {
                  $Upper_chest4_output = $t;
                }

                echo json_encode($Upper_chest4_output);

}


// get Upper_chest4 by year
if ($_GET['action'] == 'get_upper41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Upper_chest4 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest4 = mysqli_query($conn,$sql_Upper_chest4);
                $Upper_chest4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest4))
                {
                  $Upper_chest4_output = $t;
                }

                echo json_encode($Upper_chest4_output);

}



// get Upper_chest5
if ($_GET['action'] == 'get_upper5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Upper_chest5 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest5 = mysqli_query($conn,$sql_Upper_chest5);
                $Upper_chest5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest5))
                {
                  $Upper_chest5_output = $t;
                }

                echo json_encode($Upper_chest5_output);

}


// get Upper_chest5 by year
if ($_GET['action'] == 'get_upper51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Upper_chest5 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest5 = mysqli_query($conn,$sql_Upper_chest5);
                $Upper_chest5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest5))
                {
                  $Upper_chest5_output = $t;
                }

                echo json_encode($Upper_chest5_output);

}




// get Upper_chest6
if ($_GET['action'] == 'get_upper6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Upper_chest6= "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest6 = mysqli_query($conn,$sql_Upper_chest6);
                $Upper_chest6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest6))
                {
                  $Upper_chest6_output = $t;
                }

                echo json_encode($Upper_chest6_output);

}


// get Upper_chest6 by year
if ($_GET['action'] == 'get_upper61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Upper_chest6 = "SELECT  Upper_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Upper_chest6 = mysqli_query($conn,$sql_Upper_chest6);
                $Upper_chest6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Upper_chest6))
                {
                  $Upper_chest6_output = $t;
                }

                echo json_encode($Upper_chest6_output);

}







// get Lower_chest1
if ($_GET['action'] == 'get_lower1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Lower_chest1= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest1 = mysqli_query($conn,$sql_Lower_chest1);
                $Lower_chest1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest1))
                {
                  $Lower_chest1_output = $t;
                }

                echo json_encode($Lower_chest1_output);

}


// get Lower_chest1 by year
if ($_GET['action'] == 'get_lower11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Lower_chest1= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest1 = mysqli_query($conn,$sql_Lower_chest1);
                $Lower_chest1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest1))
                {
                  $Lower_chest1_output = $t;
                }

                echo json_encode($Lower_chest1_output);

}



// get Lower_chest2
if ($_GET['action'] == 'get_lower2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Lower_chest2= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest2 = mysqli_query($conn,$sql_Lower_chest2);
                $Lower_chest2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest2))
                {
                  $Lower_chest2_output = $t;
                }

                echo json_encode($Lower_chest2_output);

}


// get Lower_chest2 by year
if ($_GET['action'] == 'get_lower21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Lower_chest2= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest2 = mysqli_query($conn,$sql_Lower_chest2);
                $Lower_chest2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest2))
                {
                  $Lower_chest2_output = $t;
                }

                echo json_encode($Lower_chest2_output);

}



// get Lower_chest3
if ($_GET['action'] == 'get_lower3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Lower_chest3= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest3 = mysqli_query($conn,$sql_Lower_chest3);
                $Lower_chest3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest3))
                {
                  $Lower_chest3_output = $t;
                }

                echo json_encode($Lower_chest3_output);

}


// get Lower_chest3 by year
if ($_GET['action'] == 'get_lower31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Lower_chest3= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest3 = mysqli_query($conn,$sql_Lower_chest3);
                $Lower_chest3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest3))
                {
                  $Lower_chest3_output = $t;
                }

                echo json_encode($Lower_chest3_output);

}



// get Lower_chest4
if ($_GET['action'] == 'get_lower4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Lower_chest4= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest4 = mysqli_query($conn,$sql_Lower_chest4);
                $Lower_chest4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest4))
                {
                  $Lower_chest4_output = $t;
                }

                echo json_encode($Lower_chest4_output);

}


// get Lower_chest4 by year
if ($_GET['action'] == 'get_lower41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Lower_chest4= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest4 = mysqli_query($conn,$sql_Lower_chest4);
                $Lower_chest4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest4))
                {
                  $Lower_chest4_output = $t;
                }

                echo json_encode($Lower_chest4_output);

}




// get Lower_chest5
if ($_GET['action'] == 'get_lower5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Lower_chest5= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest5 = mysqli_query($conn,$sql_Lower_chest5);
                $Lower_chest5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest5))
                {
                  $Lower_chest5_output = $t;
                }

                echo json_encode($Lower_chest5_output);

}


// get Lower_chest5 by year
if ($_GET['action'] == 'get_lower51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Lower_chest5= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest5 = mysqli_query($conn,$sql_Lower_chest5);
                $Lower_chest5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest5))
                {
                  $Lower_chest5_output = $t;
                }

                echo json_encode($Lower_chest5_output);

}



// get Lower_chest6
if ($_GET['action'] == 'get_lower6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Lower_chest6= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest6 = mysqli_query($conn,$sql_Lower_chest6);
                $Lower_chest6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest6))
                {
                  $Lower_chest6_output = $t;
                }

                echo json_encode($Lower_chest6_output);

}


// get Lower_chest6 by year
if ($_GET['action'] == 'get_lower61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Lower_chest6= "SELECT  Lower_chest,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Lower_chest6 = mysqli_query($conn,$sql_Lower_chest6);
                $Lower_chest6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Lower_chest6))
                {
                  $Lower_chest6_output = $t;
                }

                echo json_encode($Lower_chest6_output);

}







// get Xiphoid_retraction1
if ($_GET['action'] == 'get_xiphoid1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Xiphoid_retraction1= "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction1 = mysqli_query($conn,$sql_Xiphoid_retraction1);
                $Xiphoid_retraction1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction1))
                {
                  $Xiphoid_retraction1_output = $t;
                }

                echo json_encode($Xiphoid_retraction1_output);

}


// get Xiphoid_retraction1 by year
if ($_GET['action'] == 'get_xiphoid11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Xiphoid_retraction1= "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction1 = mysqli_query($conn,$sql_Xiphoid_retraction1);
                $Xiphoid_retraction1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction1))
                {
                  $Xiphoid_retraction1_output = $t;
                }

                echo json_encode($Xiphoid_retraction1_output);

}




// get Xiphoid_retraction2
if ($_GET['action'] == 'get_xiphoid2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Xiphoid_retraction2= "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction2 = mysqli_query($conn,$sql_Xiphoid_retraction2);
                $Xiphoid_retraction2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction2))
                {
                  $Xiphoid_retraction2_output = $t;
                }

                echo json_encode($Xiphoid_retraction2_output);

}


// get Xiphoid_retraction2 by year
if ($_GET['action'] == 'get_xiphoid21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Xiphoid_retraction2= "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction2 = mysqli_query($conn,$sql_Xiphoid_retraction2);
                $Xiphoid_retraction2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction2))
                {
                  $Xiphoid_retraction2_output = $t;
                }

                echo json_encode($Xiphoid_retraction2_output);

}



// get Xiphoid_retraction3
if ($_GET['action'] == 'get_xiphoid3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Xiphoid_retraction3= "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction3 = mysqli_query($conn,$sql_Xiphoid_retraction3);
                $Xiphoid_retraction3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction3))
                {
                  $Xiphoid_retraction3_output = $t;
                }

                echo json_encode($Xiphoid_retraction3_output);

}


// get Xiphoid_retraction3 by year
if ($_GET['action'] == 'get_xiphoid31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Xiphoid_retraction3= "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction3 = mysqli_query($conn,$sql_Xiphoid_retraction3);
                $Xiphoid_retraction3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction3))
                {
                  $Xiphoid_retraction3_output = $t;
                }

                echo json_encode($Xiphoid_retraction3_output);

}




// get Xiphoid_retraction4
if ($_GET['action'] == 'get_xiphoid4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Xiphoid_retraction4 = "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction4 = mysqli_query($conn,$sql_Xiphoid_retraction4);
                $Xiphoid_retraction4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction4))
                {
                  $Xiphoid_retraction4_output = $t;
                }

                echo json_encode($Xiphoid_retraction4_output);

}


// get Xiphoid_retraction4 by year
if ($_GET['action'] == 'get_xiphoid41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Xiphoid_retraction4 = "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction4 = mysqli_query($conn,$sql_Xiphoid_retraction4);
                $Xiphoid_retraction4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction4))
                {
                  $Xiphoid_retraction4_output = $t;
                }

                echo json_encode($Xiphoid_retraction4_output);

}




// get Xiphoid_retraction5
if ($_GET['action'] == 'get_xiphoid5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Xiphoid_retraction5 = "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction5 = mysqli_query($conn,$sql_Xiphoid_retraction5);
                $Xiphoid_retraction5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction5))
                {
                  $Xiphoid_retraction5_output = $t;
                }

                echo json_encode($Xiphoid_retraction5_output);

}


// get Xiphoid_retraction5 by year
if ($_GET['action'] == 'get_xiphoid51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Xiphoid_retraction5 = "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction5 = mysqli_query($conn,$sql_Xiphoid_retraction5);
                $Xiphoid_retraction5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction5))
                {
                  $Xiphoid_retraction5_output = $t;
                }

                echo json_encode($Xiphoid_retraction5_output);

}




// get Xiphoid_retraction6
if ($_GET['action'] == 'get_xiphoid6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Xiphoid_retraction6 = "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction6 = mysqli_query($conn,$sql_Xiphoid_retraction6);
                $Xiphoid_retraction6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction6))
                {
                  $Xiphoid_retraction6_output = $t;
                }

                echo json_encode($Xiphoid_retraction6_output);

}


// get Xiphoid_retraction6 by year
if ($_GET['action'] == 'get_xiphoid61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Xiphoid_retraction6 = "SELECT  Xiphoid_retraction,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Xiphoid_retraction6 = mysqli_query($conn,$sql_Xiphoid_retraction6);
                $Xiphoid_retraction6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Xiphoid_retraction6))
                {
                  $Xiphoid_retraction6_output = $t;
                }

                echo json_encode($Xiphoid_retraction6_output);

}





// get Nasal_flaring1
if ($_GET['action'] == 'get_flaring1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Nasal_flaring1 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring1 = mysqli_query($conn,$sql_Nasal_flaring1);
                $Nasal_flaring1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring1))
                {
                  $Nasal_flaring1_output = $t;
                }

                echo json_encode($Nasal_flaring1_output);

}


// get Nasal_flaring1 by year
if ($_GET['action'] == 'get_flaring11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Nasal_flaring1 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                          WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                          ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring1 = mysqli_query($conn,$sql_Nasal_flaring1);
                $Nasal_flaring1_output = 0;

                if (mysqli_num_rows($execute_Nasal_flaring1) > 0) {

                  //echo "get data";

                  while($t = mysqli_fetch_assoc($execute_Nasal_flaring1))
                  {
                    $Nasal_flaring1_output = $t;
                  }

                  echo json_encode($Nasal_flaring1_output);

                }else {
                  echo "get_flaring11 failed";
                }

}







// get Nasal_flaring2
if ($_GET['action'] == 'get_flaring2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Nasal_flaring2 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring2 = mysqli_query($conn,$sql_Nasal_flaring2);
                $Nasal_flaring2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring2))
                {
                  $Nasal_flaring2_output = $t;
                }

                echo json_encode($Nasal_flaring2_output);

}


// get Nasal_flaring2 by year
if ($_GET['action'] == 'get_flaring21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Nasal_flaring2 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring2 = mysqli_query($conn,$sql_Nasal_flaring2);
                $Nasal_flaring2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring2))
                {
                  $Nasal_flaring2_output = $t;
                }

                echo json_encode($Nasal_flaring2_output);
}




// get Nasal_flaring3
if ($_GET['action'] == 'get_flaring3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Nasal_flaring3 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring3 = mysqli_query($conn,$sql_Nasal_flaring3);
                $Nasal_flaring3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring3))
                {
                  $Nasal_flaring3_output = $t;
                }

                echo json_encode($Nasal_flaring3_output);

}


// get Nasal_flaring3 by year
if ($_GET['action'] == 'get_flaring31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Nasal_flaring3 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring3 = mysqli_query($conn,$sql_Nasal_flaring3);
                $Nasal_flaring3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring3))
                {
                  $Nasal_flaring3_output = $t;
                }

                echo json_encode($Nasal_flaring3_output);
}





// get Nasal_flaring4
if ($_GET['action'] == 'get_flaring4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Nasal_flaring4 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring4 = mysqli_query($conn,$sql_Nasal_flaring4);
                $Nasal_flaring4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring4))
                {
                  $Nasal_flaring4_output = $t;
                }

                echo json_encode($Nasal_flaring4_output);

}


// get Nasal_flaring4 by year
if ($_GET['action'] == 'get_flaring41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Nasal_flaring4 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring4 = mysqli_query($conn,$sql_Nasal_flaring4);
                $Nasal_flaring4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring4))
                {
                  $Nasal_flaring4_output = $t;
                }

                echo json_encode($Nasal_flaring4_output);

}



// get Nasal_flaring5
if ($_GET['action'] == 'get_flaring5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Nasal_flaring5 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring5 = mysqli_query($conn,$sql_Nasal_flaring5);
                $Nasal_flaring5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring5))
                {
                  $Nasal_flaring5_output = $t;
                }

                echo json_encode($Nasal_flaring5_output);

}


// get Nasal_flaring5 by year
if ($_GET['action'] == 'get_flaring51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Nasal_flaring5 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring5 = mysqli_query($conn,$sql_Nasal_flaring5);
                $Nasal_flaring5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring5))
                {
                  $Nasal_flaring5_output = $t;
                }

                echo json_encode($Nasal_flaring5_output);

}



// get Nasal_flaring6
if ($_GET['action'] == 'get_flaring6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Nasal_flaring6 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring6 = mysqli_query($conn,$sql_Nasal_flaring6);
                $Nasal_flaring6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring6))
                {
                  $Nasal_flaring6_output = $t;
                }

                echo json_encode($Nasal_flaring6_output);

}


// get Nasal_flaring6 by year
if ($_GET['action'] == 'get_flaring61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Nasal_flaring6 = "SELECT  Nasal_flaring,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Nasal_flaring6 = mysqli_query($conn,$sql_Nasal_flaring6);
                $Nasal_flaring6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Nasal_flaring6))
                {
                  $Nasal_flaring6_output = $t;
                }

                echo json_encode($Nasal_flaring6_output);

}








// get Expiratory_grants1
if ($_GET['action'] == 'get_expiratory1' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Expiratory_grants1 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants1 = mysqli_query($conn,$sql_Expiratory_grants1);
                $Expiratory_grants1_output = 0;

                if (mysqli_num_rows($execute_Expiratory_grants1) > 0) {
                    while($t = mysqli_fetch_assoc($execute_Expiratory_grants1))
                    {
                      $Expiratory_grants1_output = $t;
                    }

                    echo json_encode($Expiratory_grants1_output);
                }else{
                    echo "Expiratory_grants1 failed";
                }


}


// get Expiratory_grants1 by year
if ($_GET['action'] == 'get_expiratory11' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Expiratory_grants1 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '1'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants1 = mysqli_query($conn,$sql_Expiratory_grants1);
                $Expiratory_grants1_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants1))
                {
                  $Expiratory_grants1_output = $t;
                }

                echo json_encode($Expiratory_grants1_output);

}




// get Expiratory_grants2
if ($_GET['action'] == 'get_expiratory2' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Expiratory_grants2 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants2 = mysqli_query($conn,$sql_Expiratory_grants2);
                $Expiratory_grants2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants2))
                {
                  $Expiratory_grants2_output = $t;
                }

                echo json_encode($Expiratory_grants2_output);

}


// get Expiratory_grants2 by year
if ($_GET['action'] == 'get_expiratory21' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Expiratory_grants2 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '2'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants2 = mysqli_query($conn,$sql_Expiratory_grants2);
                $Expiratory_grants2_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants2))
                {
                  $Expiratory_grants2_output = $t;
                }

                echo json_encode($Expiratory_grants2_output);

}




// get Expiratory_grants3
if ($_GET['action'] == 'get_expiratory3' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Expiratory_grants3 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants3 = mysqli_query($conn,$sql_Expiratory_grants3);
                $Expiratory_grants3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants3))
                {
                  $Expiratory_grants3_output = $t;
                }

                echo json_encode($Expiratory_grants3_output);

}


// get Expiratory_grants3 by year
if ($_GET['action'] == 'get_expiratory31' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Expiratory_grants3 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '3'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants3 = mysqli_query($conn,$sql_Expiratory_grants3);
                $Expiratory_grants3_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants3))
                {
                  $Expiratory_grants3_output = $t;
                }

                echo json_encode($Expiratory_grants3_output);
}



// get Expiratory_grants4
if ($_GET['action'] == 'get_expiratory4' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Expiratory_grants4 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants4 = mysqli_query($conn,$sql_Expiratory_grants4);
                $Expiratory_grants4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants4))
                {
                  $Expiratory_grants4_output = $t;
                }

                echo json_encode($Expiratory_grants4_output);

}


// get Expiratory_grants4 by year
if ($_GET['action'] == 'get_expiratory41' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Expiratory_grants4 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '4'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants4 = mysqli_query($conn,$sql_Expiratory_grants4);
                $Expiratory_grants4_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants4))
                {
                  $Expiratory_grants4_output = $t;
                }

                echo json_encode($Expiratory_grants4_output);
}



// get Expiratory_grants5
if ($_GET['action'] == 'get_expiratory5' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Expiratory_grants5 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants5 = mysqli_query($conn,$sql_Expiratory_grants5);
                $Expiratory_grants5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants5))
                {
                  $Expiratory_grants5_output = $t;
                }

                echo json_encode($Expiratory_grants5_output);

}


// get Expiratory_grants5 by year
if ($_GET['action'] == 'get_expiratory51' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Expiratory_grants5 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '5'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants5 = mysqli_query($conn,$sql_Expiratory_grants5);
                $Expiratory_grants5_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants5))
                {
                  $Expiratory_grants5_output = $t;
                }

                echo json_encode($Expiratory_grants5_output);
}



// get Expiratory_grants6
if ($_GET['action'] == 'get_expiratory6' && $_GET['Registration_ID']) {
   $Registration_ID = $_GET['Registration_ID'];
   $sql_Expiratory_grants6 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$year' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants6 = mysqli_query($conn,$sql_Expiratory_grants6);
                $Expiratory_grants6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants6))
                {
                  $Expiratory_grants6_output = $t;
                }

                echo json_encode($Expiratory_grants6_output);

}


// get Expiratory_grants6 by year
if ($_GET['action'] == 'get_expiratory61' && $_GET['year']) {
   $Registration_ID = $_GET['Registration_ID'];
   $y = $_GET['year'];
   $sql_Expiratory_grants6 = "SELECT  Expiratory_grant,saved_time FROM tbl_silverman_anderson_score_chart
                WHERE Registration_ID = '$Registration_ID' AND YEAR(saved_time) = '$y' AND day = '6'
                ORDER BY saved_time ASC LIMIT 1";

                $execute_Expiratory_grants6 = mysqli_query($conn,$sql_Expiratory_grants6);
                $Expiratory_grants6_output = 0;

                while($t = mysqli_fetch_assoc($execute_Expiratory_grants6))
                {
                  $Expiratory_grants6_output = $t;
                }

                echo json_encode($Expiratory_grants6_output);
}










//******************************************************** end *******************************************************************************


// ************************************ SUM ITEM SCORE ********************************************************************************************

//sum day1
if ($_GET['action'] == 'sum_d1') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
             FROM   tbl_silverman_anderson_score_chart
             WHERE  Registration_ID = '$Registration_ID' AND day = 1  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
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
  $sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
               FROM   tbl_silverman_anderson_score_chart
               WHERE  Registration_ID = '$Registration_ID' AND day = 1  AND YEAR(saved_time) = '$y'
               ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}




//sum day2
if ($_GET['action'] == 'sum_d2') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
             FROM   tbl_silverman_anderson_score_chart
             WHERE  Registration_ID = '$Registration_ID' AND day = 2  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day2 by year
if ($_GET['action'] == 'sum_d21' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
               FROM   tbl_silverman_anderson_score_chart
               WHERE  Registration_ID = '$Registration_ID' AND day = 2  AND YEAR(saved_time) = '$y'
               ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day3
if ($_GET['action'] == 'sum_d3') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
             FROM   tbl_silverman_anderson_score_chart
             WHERE  Registration_ID = '$Registration_ID' AND day = 3  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day3 by year
if ($_GET['action'] == 'sum_d31' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
               FROM   tbl_silverman_anderson_score_chart
               WHERE  Registration_ID = '$Registration_ID' AND day = 3  AND YEAR(saved_time) = '$y'
               ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day4
if ($_GET['action'] == 'sum_d4') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
             FROM   tbl_silverman_anderson_score_chart
             WHERE  Registration_ID = '$Registration_ID' AND day = 4  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day4 by year
if ($_GET['action'] == 'sum_d41' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
               FROM   tbl_silverman_anderson_score_chart
               WHERE  Registration_ID = '$Registration_ID' AND day = 4  AND YEAR(saved_time) = '$y'
               ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day5
if ($_GET['action'] == 'sum_d5') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
             FROM   tbl_silverman_anderson_score_chart
             WHERE  Registration_ID = '$Registration_ID' AND day = 5  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day5 by year
if ($_GET['action'] == 'sum_d51' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
               FROM   tbl_silverman_anderson_score_chart
               WHERE  Registration_ID = '$Registration_ID' AND day = 5  AND YEAR(saved_time) = '$y'
               ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}


//sum day6
if ($_GET['action'] == 'sum_d6') {
  $Registration_ID = $_GET['Registration_ID'];
$sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
             FROM   tbl_silverman_anderson_score_chart
             WHERE  Registration_ID = '$Registration_ID' AND day = 6  AND YEAR(saved_time) = '$year'
             ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}



//sum day1 by year
if ($_GET['action'] == 'sum_d61' && $_GET['year']) {
  $Registration_ID = $_GET['Registration_ID'];
  $y = $_GET['year'];
  $sql_day1 = "SELECT Upper_chest,Lower_chest,Xiphoid_retraction,Nasal_flaring,Expiratory_grant,day
               FROM   tbl_silverman_anderson_score_chart
               WHERE  Registration_ID = '$Registration_ID' AND day = 6  AND YEAR(saved_time) = '$y'
               ORDER BY saved_time ASC LIMIT 1";

             $sum_d1 = 0;
             $execute_day1 = mysqli_query($conn,$sql_day1);

             if (mysqli_num_rows($execute_day1) > 0) {
               while ($d1 = mysqli_fetch_assoc($execute_day1)) {
                 $tn = $d1['Upper_chest']; $lc = $d1['Lower_chest']; $ft = $d1['Xiphoid_retraction']; $mr = $d1['Nasal_flaring']; $gr = $d1['Expiratory_grant'];


                 $sum_d1 = $tn + $lc + $ft + $mr + $gr;
               }
               echo json_encode($sum_d1);

             }else{
               echo "";
             }

}





// **************************************************** end *****************************************************************************************


?>

<?php
    include("./includes/connection.php");
        session_start();
        
            $Registration_ID= $_GET['Registration_ID'];
            $consultation_ID= $_GET['consultation_ID'];
            $mdrt= $_GET['mdrt'];
            $ecg_comments= str_replace("'", "&#39;", $_GET['ecg_comments']);
            $ecg= $_GET['ecg'];
            $rbg= $_GET['rbg'];
            $pH= $_GET['pH'];
            $pCO2= $_GET['pCO2']." mmHG";
            $pO2= $_GET['pO2']." mmHG";
            $Hct= $_GET['Hct']." %";
            $S02= $_GET['S02']." %";
            $Hb= $_GET['Hb']." g/dL";
            $Na= $_GET['Na']." mmol/L";
            $K= $_GET['K']." mmol/L";
            $iCA= $_GET['iCA']." mmol/L";
            $Cl= $_GET['Cl']." mmol/L";
            $Li= $_GET['Li']." mmol/L";
            $nCa= $_GET['nCa']." mmol/L";
            $GLU= $_GET['GLU']." mg/dL";
            $LAC= $_GET['LAC']." mmol/L";
            $HCO3= $_GET['HCO3']." mmol/L";
            $TCO2= $_GET['TCO2']." mmol/L";
            $SBC= $_GET['SBC']." mmol/L";
            $O2Ct= $_GET['O2Ct']." Vol%";
            $p02= $_GET['p02']." %";
            $BE= $_GET['BE']." mmol/L";
            $BE_B= $_GET['BE_B']." mmol/L";
            $BE_ECF= $_GET['BE_ECF']." mmol/L";
            $AG_NA= $_GET['AG_NA']." mmol/L";
            $AG_K= $_GET['AG_K']." mmol/L";
            $Employee_ID= $_GET['Employee_ID'];

            if(!empty($consultation_ID) && !(empty($Registration_ID))){
                $creating_emd_notes_file = mysqli_query($conn, "INSERT INTO tbl_episode_of_care (Employee_ID, consultation_ID, Registration_ID, mdrt, ecg_comments, ecg, rbg, pH, pCO2, pO2, Hct, S02, Hb, Na, K, iCA, Cl, Li, nCa, GLU, LAC, HCO3, TCO2, SBC, O2Ct, p02, BE, BE_B, BE_ECF, AG_NA, AG_K, Episode_Date_Time) VALUES ('$Employee_ID', '$consultation_ID', '$Registration_ID', '$mdrt', '$ecg_comments', '$ecg', '$rbg', '$pH', '$pCO2', '$pO2', '$Hct', '$S02', '$Hb', '$Na', '$K', '$iCA', '$Cl', '$Li', '$nCa', '$GLU', '$LAC', '$HCO3', '$TCO2', '$SBC', '$O2Ct', '$p02', '$BE', '$BE_B', '$BE_ECF', '$AG_NA', '$AG_K', NOW())") or die(mysqli_error($conn));

                if($creating_emd_notes_file){
                    echo 200;
                }else{
                    echo 201;
                }
            }

mysqli_close($conn);
?>
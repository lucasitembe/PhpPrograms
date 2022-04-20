<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
    
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
   /*
   if(isset($_SESSION['userinfo']))
    {
        if(isset($_SESSION['userinfo']['Rch_Works']))
        {
            if($_SESSION['userinfo']['Rch_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
        }else
            {
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
    }else
        { @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

   if(isset($_SESSION['userinfo'])){
       if($_SESSION['userinfo']['Rch_Works'] == 'yes')
            { 
            echo "<a href='#' class='art-button-green'>BACK</a>";
            }
    }
        */
?>
<script>
        $(function () { 
            $("#Date_Of_TT_Dose1").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            $("#Date_Of_TT_Dose2").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            $("#Date_Of_TT_Dose3").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            $("#Date_Of_TT_Dose4").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            $("#Date_Of_TT_Dose5").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            $("#Recommendation_Date_For_Status_Review").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
            $("#Date_Of_Previous_Hiv_Test").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
<!--javascript codes to validate form-->
<script src="powercharts_antinatal.js"></script>



<?php
    //test if isset using post
    if(isset($_POST['submit'])){
	$Rch_Registration_Date=date('Y-m-d');
	//receive values from the form
        $Rch_ID=$_POST['Rch_ID'];
	$Registration_ID=$_POST['Registration_ID'];
	$Neighborhood_Leader=$_POST['Neighborhood_Leader'];
	$Stage_Of_Current_Pregnancy=$_POST['Stage_Of_Current_Pregnancy'];
	$Husband_Partner_Name=$_POST['Husband_Partner_Name'];
	$Previous_Pregnancies=$_POST['Previous_Pregnancies'];
	$Number_Of_Births=$_POST['Number_Of_Births'];
	$Number_Of_Aborted_Pregnancies=$_POST['Number_Of_Aborted_Pregnancies'];
	$Children_Died_Within_Seven_Days=$_POST['Children_Died_Within_Seven_Days'];
	$Number_Of_Current_Children=$_POST['Number_Of_Current_Children'];
	$Age_Of_Last_Child=$_POST['Age_Of_Last_Child'];
	$Patient_HB=$_POST['Patient_HB'];
	$Blood_Pressure=$_POST['Blood_Pressure'];
	$Height=$_POST['Height'];
	$Sugar_Result=$_POST['Sugar_Result'];
	$Previous_CS=$_POST['Previous_CS'];
	$Age_Under_20=$_POST['Age_Under_20'];
	$Age_Over_35=$_POST['Age_Over_35'];
	$Tested_Syphilis=$_POST['Tested_Syphilis'];
	$Last_Result=$_POST['Last_Result'];
	$TT_Card=$_POST['TT_Card'];
	$Date_Of_TT_Dose1=$_POST['Date_Of_TT_Dose1'];
	$Date_Of_TT_Dose2=$_POST['Date_Of_TT_Dose2'];
	$Date_Of_TT_Dose3=$_POST['Date_Of_TT_Dose3'];
	$Date_Of_TT_Dose4=$_POST['Date_Of_TT_Dose4'];
	$Date_Of_TT_Dose5=$_POST['Date_Of_TT_Dose5'];
	$Husband_Syphilis_Test=$_POST['Husband_Syphilis_Test'];
	$Husband_Syphilis_Test_Result=$_POST['Husband_Syphilis_Test_Result'];
	$Mother_Syphilis_Treatment=$_POST['Mother_Syphilis_Treatment'];
	$Husband_Syphilis_Treatment=$_POST['Husband_Syphilis_Treatment'];
	$Any_Sexual_Transmitted_Desease=$_POST['Any_Sexual_Transmitted_Desease'];
	$Received_Std_Treatment=$_POST['Received_Std_Treatment'];
	$Husband_Std_Desease=$_POST['Husband_Std_Desease'];
	$Husband_Std_Treatment=$_POST['Husband_Std_Treatment'];
	$Mother_Previous_Hiv_Test=$_POST['Mother_Previous_Hiv_Test'];
	$Date_Of_Previous_Hiv_Test=$_POST['Date_Of_Previous_Hiv_Test'];
	$Current_Hiv_Status=$_POST['Current_Hiv_Status'];
	$Previous_Hiv_Result=$_POST['Previous_Hiv_Result'];
	$Partiner_Hiv_Test=$_POST['Partiner_Hiv_Test'];
	$Partiner_Hiv_Status=$_POST['Partiner_Hiv_Status'];
	$Taking_ARV_Therapy=$_POST['Taking_ARV_Therapy'];
	$Partiner_Received_Councilling=$_POST['Partiner_Received_Councilling'];
	$ARV_Medication_Type=$_POST['ARV_Medication_Type'];
	$ARV_Medication_Duration=$_POST['ARV_Medication_Duration'];
	$Received_Pre_Testing_Councilling=$_POST['Received_Pre_Testing_Councilling'];
        $Received_Post_Testing_Councilling=$_POST['Received_Post_Testing_Councilling'];
	$Signed_Hiv_Declaration=$_POST['Signed_Hiv_Declaration'];
	$Malaria_Test_Result=$_POST['Malaria_Test_Result'];
	$Recommendation_Date_For_Status_Review=$_POST['Recommendation_Date_For_Status_Review'];
	$Received_ITN_LLN=$_POST['Received_ITN_LLN'];
	$IPT1=$_POST['IPT1'];
	$IPT2=$_POST['IPT2'];
	$Iron_Folic_Clinic1=$_POST['Iron_Folic_Clinic1'];
	$Iron_Folic_Clinic2=$_POST['Iron_Folic_Clinic2'];
	$Iron_Folic_Clinic3=$_POST['Iron_Folic_Clinic3'];
	$Iron_Folic_Clinic4=$_POST['Iron_Folic_Clinic4'];
	$Worm_Medication=$_POST['Worm_Medication'];
	$Patient_To_Be_Refered_Admitted=$_POST['Patient_To_Be_Refered_Admitted'];
	$Patient_Refered_Facility=$_POST['Patient_Refered_Facility'];
	$Patient_Refered_Reason=$_POST['Patient_Refered_Reason'];
	$Received_Hiv_Feeding_Information=$_POST['Received_Hiv_Feeding_Information'];
	$Other_Comments=$_POST['Other_Comments'];
        
        
        //run the query to select dta before update
        $select_rch_update=mysqli_query($conn,"SELECT * FROM tbl_rch WHERE Rch_ID='$Rch_ID'");
        while($select_rch_update_row=mysqli_fetch_array($select_rch_update)){
        $Rch_ID_update=$select_rch_update_row['Rch_ID'];
        $Neighborhood_Leader_update=$select_rch_update_row['Neighborhood_Leader'];
        $Stage_Of_Current_Pregnancy_update=$select_rch_update_row['Stage_Of_Current_Pregnancy'];
        $Husband_Partner_Name_update=$select_rch_update_row['Husband_Partner_Name'];
	$Previous_Pregnancies_update=$select_rch_update_row['Previous_Pregnancies'];
	$Number_Of_Births_update=$select_rch_update_row['Number_Of_Births'];
	$Number_Of_Aborted_Pregnancies_update=$select_rch_update_row['Number_Of_Aborted_Pregnancies'];
	$Children_Died_Within_Seven_Days_update=$select_rch_update_row['Children_Died_Within_Seven_Days'];
	$Number_Of_Current_Children_update=$select_rch_update_row['Number_Of_Current_Children'];
	$Age_Of_Last_Child_update=$select_rch_update_row['Age_Of_Last_Child'];
	$Patient_HB_update=$select_rch_update_row['Patient_HB'];
	$Blood_Pressure_update=$select_rch_update_row['Blood_Pressure'];
	$Height_update=$select_rch_update_row['Height'];
	$Sugar_Result_update=$select_rch_update_row['Sugar_Result'];
	$Previous_CS_update=$select_rch_update_row['Previous_CS'];
	$Age_Under_20_update=$select_rch_update_row['Age_Under_20'];
	$Age_Over_35_update=$select_rch_update_row['Age_Over_35'];
	$Tested_Syphilis_update=$select_rch_update_row['Tested_Syphilis'];
	$Last_Result_update=$select_rch_update_row['Last_Result'];
	$TT_Card_update=$select_rch_update_row['TT_Card'];
	$Date_Of_TT_Dose1_update=$select_rch_update_row['Date_Of_TT_Dose1'];
	$Date_Of_TT_Dose2_update=$select_rch_update_row['Date_Of_TT_Dose2'];
	$Date_Of_TT_Dose3_update=$select_rch_update_row['Date_Of_TT_Dose3'];
	$Date_Of_TT_Dose4_update=$select_rch_update_row['Date_Of_TT_Dose4'];
	$Date_Of_TT_Dose5_update=$select_rch_update_row['Date_Of_TT_Dose5'];
	$Husband_Syphilis_Test_update=$select_rch_update_row['Husband_Syphilis_Test'];
	$Husband_Syphilis_Test_Result_update=$select_rch_update_row['Husband_Syphilis_Test_Result'];
	$Mother_Syphilis_Treatment_update=$select_rch_update_row['Mother_Syphilis_Treatment'];
	$Husband_Syphilis_Treatment_update=$select_rch_update_row['Husband_Syphilis_Treatment'];
	$Any_Sexual_Transmitted_Desease_update=$select_rch_update_row['Any_Sexual_Transmitted_Desease'];
	$Received_Std_Treatment_update=$select_rch_update_row['Received_Std_Treatment'];
	$Husband_Std_Desease_update=$select_rch_update_row['Husband_Std_Desease'];
	$Husband_Std_Treatment_update=$select_rch_update_row['Husband_Std_Treatment'];
	$Mother_Previous_Hiv_Test_update=$select_rch_update_row['Mother_Previous_Hiv_Test'];
	$Date_Of_Previous_Hiv_Test_update=$select_rch_update_row['Date_Of_Previous_Hiv_Test'];
	$Current_Hiv_Status_update=$select_rch_update_row['Current_Hiv_Status'];
	$Previous_Hiv_Result_update=$select_rch_update_row['Previous_Hiv_Result'];
	$Partiner_Hiv_Test_update=$select_rch_update_row['Partiner_Hiv_Test'];
	$Partiner_Hiv_Status_update=$select_rch_update_row['Partiner_Hiv_Status'];
	$Taking_ARV_Therapy_update=$select_rch_update_row['Taking_ARV_Therapy'];
	$Partiner_Received_Councilling_update=$select_rch_update_row['Partiner_Received_Councilling'];
	$ARV_Medication_Type_update=$select_rch_update_row['ARV_Medication_Type'];
	$ARV_Medication_Duration_update=$select_rch_update_row['ARV_Medication_Duration'];
	$Received_Pre_Testing_Councilling_update=$select_rch_update_row['Received_Pre_Testing_Councilling'];
        $Received_Post_Testing_Councilling_update=$select_rch_update_row['Received_Post_Testing_Councilling'];
	$Signed_Hiv_Declaration_update=$select_rch_update_row['Signed_Hiv_Declaration'];
	$Malaria_Test_Result_update=$select_rch_update_row['Malaria_Test_Result'];
	$Recommendation_Date_For_Status_Review_update=$select_rch_update_row['Recommendation_Date_For_Status_Review'];
	$Received_ITN_LLN_update=$select_rch_update_row['Received_ITN_LLN'];
	$IPT1_update=$select_rch_update_row['IPT1'];
	$IPT2_update=$select_rch_update_row['IPT2'];
	$Iron_Folic_Clinic1_update=$select_rch_update_row['Iron_Folic_Clinic1'];
	$Iron_Folic_Clinic2_update=$select_rch_update_row['Iron_Folic_Clinic2'];
	$Iron_Folic_Clinic3_update=$select_rch_update_row['Iron_Folic_Clinic3'];
	$Iron_Folic_Clinic4_update=$select_rch_update_row['Iron_Folic_Clinic4'];
	$Worm_Medication_update=$select_rch_update_row['Worm_Medication'];
	$Patient_To_Be_Refered_Admitted_update=$select_rch_update_row['Patient_To_Be_Refered_Admitted'];
	$Patient_Refered_Facility_update=$select_rch_update_row['Patient_Refered_Facility'];
	$Patient_Refered_Reason_update=$select_rch_update_row['Patient_Refered_Reason'];
	$Received_Hiv_Feeding_Information_update=$select_rch_update_row['Received_Hiv_Feeding_Information'];
	$Other_Comments_update=$select_rch_update_row['Other_Comments'];
        }
	
        //run the query to insert data into the tbl_paediatric_records
	$Record_Date=date("Y-m-d");
	$insert_rch_info=mysqli_query($conn,"INSERT INTO tbl_rch_records SET
	Rch_ID='$Rch_ID_update',
	Neighborhood_Leader='$Neighborhood_Leader_update',
	Stage_Of_Current_Pregnancy='$Stage_Of_Current_Pregnancy_update',
	Husband_Partner_Name='$Husband_Partner_Name_update',
	Previous_Pregnancies='$Previous_Pregnancies_update',
	Number_Of_Births='$Number_Of_Births_update',
	Number_Of_Aborted_Pregnancies='$Number_Of_Aborted_Pregnancies_update',
	Children_Died_Within_Seven_Days='$Children_Died_Within_Seven_Days_update',
	Number_Of_Current_Children='$Number_Of_Current_Children_update',
	Age_Of_Last_Child='$Age_Of_Last_Child_update',
	Patient_HB='$Patient_HB_update',
	Blood_Pressure='$Blood_Pressure_update',
	Height='$Height_update',
	Sugar_Result='$Sugar_Result_update',
	Previous_CS='$Previous_CS_update',
	Age_Under_20='$Age_Under_20_update',
	Age_Over_35='$Age_Over_35_update',
	Tested_Syphilis='$Tested_Syphilis_update',
	Last_Result='$Last_Result_update',
	TT_Card='$TT_Card_update',
	Date_Of_TT_Dose1='$Date_Of_TT_Dose1_update',
	Date_Of_TT_Dose2='$Date_Of_TT_Dose2_update',
	Date_Of_TT_Dose3='$Date_Of_TT_Dose3_update',
	Date_Of_TT_Dose4='$Date_Of_TT_Dose4_update',
	Date_Of_TT_Dose5='$Date_Of_TT_Dose5_update',
	Husband_Syphilis_Test='$Husband_Syphilis_Test_update',
	Husband_Syphilis_Test_Result='$Husband_Syphilis_Test_Result_update',
	Mother_Syphilis_Treatment='$Mother_Syphilis_Treatment_update',
	Husband_Syphilis_Treatment='$Husband_Syphilis_Treatment_update',
	Any_Sexual_Transmitted_Desease='$Any_Sexual_Transmitted_Desease_update',
	Received_Std_Treatment='$Received_Std_Treatment_update',
	Husband_Std_Desease='$Husband_Std_Desease_update',
	Husband_Std_Treatment='$Husband_Std_Treatment_update',
	Mother_Previous_Hiv_Test='$Mother_Previous_Hiv_Test_update',
	Date_Of_Previous_Hiv_Test='$Date_Of_Previous_Hiv_Test_update',
	Current_Hiv_Status='$Current_Hiv_Status_update',
	Previous_Hiv_Result='$Previous_Hiv_Result_update',
	Partiner_Hiv_Test='$Partiner_Hiv_Test_update',
	Partiner_Hiv_Status='$Partiner_Hiv_Status_update',
	Taking_ARV_Therapy='$Taking_ARV_Therapy_update',
	Partiner_Received_Councilling='$Partiner_Received_Councilling_update',
	ARV_Medication_Type='$ARV_Medication_Type_update',
	ARV_Medication_Duration='$ARV_Medication_Duration_update',
	Received_Pre_Testing_Councilling='$Received_Pre_Testing_Councilling_update',
        Received_Post_Testing_Councilling='$Received_Post_Testing_Councilling_update',
	Signed_Hiv_Declaration='$Signed_Hiv_Declaration_update',
	Malaria_Test_Result='$Malaria_Test_Result_update',
	Recommendation_Date_For_Status_Review='$Recommendation_Date_For_Status_Review_update',
	Received_ITN_LLN='$Received_ITN_LLN_update',
	IPT1='$IPT1_update',
	IPT2='$IPT2_update',
	Iron_Folic_Clinic1='$Iron_Folic_Clinic1_update',
	Iron_Folic_Clinic2='$Iron_Folic_Clinic2_update',
	Iron_Folic_Clinic3='$Iron_Folic_Clinic3_update',
	Iron_Folic_Clinic4='$Iron_Folic_Clinic4_update',
	Worm_Medication='$Worm_Medication_update',
	Patient_To_Be_Refered_Admitted='$Patient_To_Be_Refered_Admitted_update',
	Patient_Refered_Facility='$Patient_Refered_Facility_update',
	Patient_Refered_Reason='$Patient_Refered_Reason_update',
	Received_Hiv_Feeding_Information='$Received_Hiv_Feeding_Information_update',
	Other_Comments='$Other_Comments_update',
        Record_Date='$Record_Date'
        ") or die(mysqli_error($conn));
        
        
	//run the query to insert data into the database
	$Last_Update=date("Y-m-d H:i:s");
	$insert_rch_info=mysqli_query($conn,"UPDATE tbl_rch SET
	Neighborhood_Leader='$Neighborhood_Leader',
	Stage_Of_Current_Pregnancy='$Stage_Of_Current_Pregnancy',
	Husband_Partner_Name='$Husband_Partner_Name',
	Previous_Pregnancies='$Previous_Pregnancies',
	Number_Of_Births='$Number_Of_Births',
	Number_Of_Aborted_Pregnancies='$Number_Of_Aborted_Pregnancies',
	Children_Died_Within_Seven_Days='$Children_Died_Within_Seven_Days',
	Number_Of_Current_Children='$Number_Of_Current_Children',
	Age_Of_Last_Child='$Age_Of_Last_Child',
	Patient_HB='$Patient_HB',
	Blood_Pressure='$Blood_Pressure',
	Height='$Height',
	Sugar_Result='$Sugar_Result',
	Previous_CS='$Previous_CS',
	Age_Under_20='$Age_Under_20',
	Age_Over_35='$Age_Over_35',
	Tested_Syphilis='$Tested_Syphilis',
	Last_Result='$Last_Result',
	TT_Card='$TT_Card',
	Date_Of_TT_Dose1='$Date_Of_TT_Dose1',
	Date_Of_TT_Dose2='$Date_Of_TT_Dose2',
	Date_Of_TT_Dose3='$Date_Of_TT_Dose3',
	Date_Of_TT_Dose4='$Date_Of_TT_Dose4',
	Date_Of_TT_Dose5='$Date_Of_TT_Dose5',
	Husband_Syphilis_Test='$Husband_Syphilis_Test',
	Husband_Syphilis_Test_Result='$Husband_Syphilis_Test_Result',
	Mother_Syphilis_Treatment='$Mother_Syphilis_Treatment',
	Husband_Syphilis_Treatment='$Husband_Syphilis_Treatment',
	Any_Sexual_Transmitted_Desease='$Any_Sexual_Transmitted_Desease',
	Received_Std_Treatment='$Received_Std_Treatment',
	Husband_Std_Desease='$Husband_Std_Desease',
	Husband_Std_Treatment='$Husband_Std_Treatment',
	Mother_Previous_Hiv_Test='$Mother_Previous_Hiv_Test',
	Date_Of_Previous_Hiv_Test='$Date_Of_Previous_Hiv_Test',
	Current_Hiv_Status='$Current_Hiv_Status',
	Previous_Hiv_Result='$Previous_Hiv_Result',
	Partiner_Hiv_Test='$Partiner_Hiv_Test',
	Partiner_Hiv_Status='$Partiner_Hiv_Status',
	Taking_ARV_Therapy='$Taking_ARV_Therapy',
	Partiner_Received_Councilling='$Partiner_Received_Councilling',
	ARV_Medication_Type='$ARV_Medication_Type',
	ARV_Medication_Duration='$ARV_Medication_Duration',
	Received_Pre_Testing_Councilling='$Received_Pre_Testing_Councilling',
        Received_Post_Testing_Councilling='$Received_Post_Testing_Councilling',
	Signed_Hiv_Declaration='$Signed_Hiv_Declaration',
	Malaria_Test_Result='$Malaria_Test_Result',
	Recommendation_Date_For_Status_Review='$Recommendation_Date_For_Status_Review',
	Received_ITN_LLN='$Received_ITN_LLN',
	IPT1='$IPT1',
	IPT2='$IPT2',
	Iron_Folic_Clinic1='$Iron_Folic_Clinic1',
	Iron_Folic_Clinic2='$Iron_Folic_Clinic2',
	Iron_Folic_Clinic3='$Iron_Folic_Clinic3',
	Iron_Folic_Clinic4='$Iron_Folic_Clinic4',
	Worm_Medication='$Worm_Medication',
	Patient_To_Be_Refered_Admitted='$Patient_To_Be_Refered_Admitted',
	Patient_Refered_Facility='$Patient_Refered_Facility',
	Patient_Refered_Reason='$Patient_Refered_Reason',
	Received_Hiv_Feeding_Information='$Received_Hiv_Feeding_Information',
	Other_Comments='$Other_Comments',
        Last_Update='$Last_Update'
        
        WHERE Rch_ID='$Rch_ID'
        
        ") or die(mysqli_error($conn));
	
	
	//confirm query success
	if($insert_rch_info){?>
	    <script>
		alert("RCH Outpatient Credit Information successfully updated");
		location.href="rchconsultedcreditpatientlist.php";
	    </script>
	<?php }else{?>
	    <script>
		alert("Failed to update RCH Outpatient Credit Information");
		location.href="rchconsultedcreditpatientlist.php";
	    </script>
	<?php }
	
    }
?>




<?php
//get the current date
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date; 
		}
//    select patient information
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
		$Claim_Number_Status = $row['Claim_Number_Status'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
	    
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days.";
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Claim_Number_Status = '';
	    $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
	    $Claim_Number_Status = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
?>



<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "rchlist.php?Billing_Type=OutpatientCash&RchList=RchListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "rchlist.php?Billing_Type=OutpatientCredit&RchList=RchListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "rchlist.php?Billing_Type=InpatientCash&RchList=RchListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "rchlist.php?Billing_Type=InpatientCredit&RchList=RchListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "rchlist.php?Billing_Type=PatientFromOutside&RchList=RchListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option selected='selected'>Select type of patient list</option>
    <option>
	Outpatient Cash
    </option>
    <option>
	Outpatient Credit
    </option>
    <option>
	Inpatient Cash
    </option>
    <option>
	Inpatient Credit
    </option>
    <option>
	Patient from outside
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> 



<!-- link menu -->
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "rchlist.php?Billing_Type=OutpatientCash&RchList=RchListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "rchlist.php?Billing_Type=OutpatientCredit&RchList=RchListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "rchlist.php?Billing_Type=InpatientCash&RchList=RchListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "rchlist.php?Billing_Type=InpatientCredit&RchList=RchListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "rchlist.php?Billing_Type=PatientFromOutside&RchList=RchListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <option selected='selected'>Select type of patient list</option>
    <option>
	Outpatient Cash
    </option>
    <option>
	Outpatient Credit
    </option>
    <option>
	Inpatient Cash
    </option>
    <option>
	Inpatient Credit
    </option>
    <option>
	Patient from outside
    </option>
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label>--> 

<fieldset><legend align='right'><b>Rch Works</b></legend>
	<table width=100%>
		<tr>
		    <td style="text-align:right;"><b>Patient Name</b></td><td><input type="text" name="" readonly='readonly' value='<?php if(isset($Patient_Name)){ echo $Patient_Name; } ?>' id=""></td>
		     <td style="text-align:right;"><b>Patient Number</b></td><td><input type="text" name="" readonly='readonly' value='<?php if(isset($Registration_ID)){ echo $Registration_ID; } ?>' id="" ></td>
		</tr>
		<tr>
		    <td style="text-align:right;"><b>Gender</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($Gender)){ echo $Gender; } ?>' ></td></td>
		    <td style="text-align:right;"><b>Sponsor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($Guarantor_Name)){ echo $Guarantor_Name; } ?>' ></td>
		</tr>
		<tr>
		    <td style="text-align:right;"><b>Age</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php if(isset($age)){ echo $age; } ?>' ></td></td>
                    <td style="text-align:right;"><b>RCH ID</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php echo $_GET['Rch_ID']; ?>' ></td></td>
		</tr>
		<tr>
		    <!--<td style="text-align:right;"><b>Doctor</b></td><td><input type="text" name="" id="" readonly='readonly' value='<?php
									/*if(isset($Consultant)){
									    if($Patient_Direction=='Direct To Clinic'){
										echo $_SESSION['userinfo']['Employee_Name'];
									    }else{
										echo $Consultant;
									    }
									    }*/ ?>' ></td>-->
		</tr>
	</table>
</fieldset>
<?php
//run the query to select data according to the Rch_ID
if(isset($_GET['Rch_ID'])){
    $Rch_ID=$_GET['Rch_ID'];
    //run the query select data from the tbl_rch according to the Rch_ID
    $select_rch_info=mysqli_query($conn,"SELECT * FROM tbl_rch WHERE Rch_ID='$Rch_ID'");
    while($select_rch_info_row=mysqli_fetch_array($select_rch_info)){
        //return data
        $Rch_ID=$select_rch_info_row['Rch_ID'];
	$Registration_ID=$select_rch_info_row['Registration_ID'];
	$Neighborhood_Leader=$select_rch_info_row['Neighborhood_Leader'];
	$Stage_Of_Current_Pregnancy=$select_rch_info_row['Stage_Of_Current_Pregnancy'];
	$Husband_Partner_Name=$select_rch_info_row['Husband_Partner_Name'];
	$Previous_Pregnancies=$select_rch_info_row['Previous_Pregnancies'];
	$Number_Of_Births=$select_rch_info_row['Number_Of_Births'];
	$Number_Of_Aborted_Pregnancies=$select_rch_info_row['Number_Of_Aborted_Pregnancies'];
	$Children_Died_Within_Seven_Days=$select_rch_info_row['Children_Died_Within_Seven_Days'];
	$Number_Of_Current_Children=$select_rch_info_row['Number_Of_Current_Children'];
	$Age_Of_Last_Child=$select_rch_info_row['Age_Of_Last_Child'];
	$Patient_HB=$select_rch_info_row['Patient_HB'];
	$Blood_Pressure=$select_rch_info_row['Blood_Pressure'];
	$Height=$select_rch_info_row['Height'];
	$Sugar_Result=$select_rch_info_row['Sugar_Result'];
	$Previous_CS=$select_rch_info_row['Previous_CS'];
	$Age_Under_20=$select_rch_info_row['Age_Under_20'];
	$Age_Over_35=$select_rch_info_row['Age_Over_35'];
	$Tested_Syphilis=$select_rch_info_row['Tested_Syphilis'];
	$Last_Result=$select_rch_info_row['Last_Result'];
	$TT_Card=$select_rch_info_row['TT_Card'];
	$Date_Of_TT_Dose1=$select_rch_info_row['Date_Of_TT_Dose1'];
	$Date_Of_TT_Dose2=$select_rch_info_row['Date_Of_TT_Dose2'];
	$Date_Of_TT_Dose3=$select_rch_info_row['Date_Of_TT_Dose3'];
	$Date_Of_TT_Dose4=$select_rch_info_row['Date_Of_TT_Dose4'];
	$Date_Of_TT_Dose5=$select_rch_info_row['Date_Of_TT_Dose5'];
	$Husband_Syphilis_Test=$select_rch_info_row['Husband_Syphilis_Test'];
	$Husband_Syphilis_Test_Result=$select_rch_info_row['Husband_Syphilis_Test_Result'];
	$Mother_Syphilis_Treatment=$select_rch_info_row['Mother_Syphilis_Treatment'];
	$Husband_Syphilis_Treatment=$select_rch_info_row['Husband_Syphilis_Treatment'];
	$Any_Sexual_Transmitted_Desease=$select_rch_info_row['Any_Sexual_Transmitted_Desease'];
	$Received_Std_Treatment=$select_rch_info_row['Received_Std_Treatment'];
	$Husband_Std_Desease=$select_rch_info_row['Husband_Std_Desease'];
	$Husband_Std_Treatment=$select_rch_info_row['Husband_Std_Treatment'];
	$Mother_Previous_Hiv_Test=$select_rch_info_row['Mother_Previous_Hiv_Test'];
	$Date_Of_Previous_Hiv_Test=$select_rch_info_row['Date_Of_Previous_Hiv_Test'];
	$Current_Hiv_Status=$select_rch_info_row['Current_Hiv_Status'];
	$Previous_Hiv_Result=$select_rch_info_row['Previous_Hiv_Result'];
	$Partiner_Hiv_Test=$select_rch_info_row['Partiner_Hiv_Test'];
	$Partiner_Hiv_Status=$select_rch_info_row['Partiner_Hiv_Status'];
	$Taking_ARV_Therapy=$select_rch_info_row['Taking_ARV_Therapy'];
	$Partiner_Received_Councilling=$select_rch_info_row['Partiner_Received_Councilling'];
	$ARV_Medication_Type=$select_rch_info_row['ARV_Medication_Type'];
	$ARV_Medication_Duration=$select_rch_info_row['ARV_Medication_Duration'];
	$Received_Pre_Testing_Councilling=$select_rch_info_row['Received_Pre_Testing_Councilling'];
        $Received_Post_Testing_Councilling=$select_rch_info_row['Received_Post_Testing_Councilling'];
	$Signed_Hiv_Declaration=$select_rch_info_row['Signed_Hiv_Declaration'];
	$Malaria_Test_Result=$select_rch_info_row['Malaria_Test_Result'];
	$Recommendation_Date_For_Status_Review=$select_rch_info_row['Recommendation_Date_For_Status_Review'];
	$Received_ITN_LLN=$select_rch_info_row['Received_ITN_LLN'];
	$IPT1=$select_rch_info_row['IPT1'];
	$IPT2=$select_rch_info_row['IPT2'];
	$Iron_Folic_Clinic1=$select_rch_info_row['Iron_Folic_Clinic1'];
	$Iron_Folic_Clinic2=$select_rch_info_row['Iron_Folic_Clinic2'];
	$Iron_Folic_Clinic3=$select_rch_info_row['Iron_Folic_Clinic3'];
	$Iron_Folic_Clinic4=$select_rch_info_row['Iron_Folic_Clinic4'];
	$Worm_Medication=$select_rch_info_row['Worm_Medication'];
	$Patient_To_Be_Refered_Admitted=$select_rch_info_row['Patient_To_Be_Refered_Admitted'];
	$Patient_Refered_Facility=$select_rch_info_row['Patient_Refered_Facility'];
	$Patient_Refered_Reason=$select_rch_info_row['Patient_Refered_Reason'];
	$Received_Hiv_Feeding_Information=$select_rch_info_row['Received_Hiv_Feeding_Information'];
	$Other_Comments=$select_rch_info_row['Other_Comments'];
	
        
    }
    
    
    
    
    
}else{
    $Rch_ID='';
}


?>

<fieldset style="margin-top:5px;">  
   <div class="powercharts_body">
    <div class="tabcontents" id="tabcontents">
        <table  class="power_header"  border="0" >
                            <tr>
                            <td>
                                <ul class="tabs" data-persist="true" >
                                    <li><a href="#view1" >Antinental Clinic Page 1</a></li>
                                    <li><a href="#view2"  >Antinental Clinic Page 2</a></li>
                                    <li><a href="#view3"  >Antinental Clinic Page 3</a></li>
                                </ul>
                        </td>
                    </tr>
                </table>
	    <!--form to be submitted-->
            <form action="rchinfoedit.php" name="myForm" onsubmit="return validateForm();" method="POST">
		<div id="view1">
                        <center>
        <table  class="hiv_table" border="0" >
                <tr>
                    <td width="30%" class="powercharts_td_left">Neighborhood Leaders</td><td><input name="Neighborhood_Leader" type="text" value="<?php echo $Neighborhood_Leader?>"</td>
                    <td class="powercharts_td_left">Husband/Partner Name</td>
                    <td>
                        <input name="Husband_Partner_Name" type="text" value="<?php echo $Husband_Partner_Name?>">
                        <input type="hidden" name="Registration_ID" value="<?php echo $Registration_ID;?>"/>
                        <input type="hidden" name="Rch_ID" value="<?php echo $Rch_ID?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Stage Of Current Pregnancy(weeks)</td><td><input name="Stage_Of_Current_Pregnancy" type="text" value="<?php echo $Stage_Of_Current_Pregnancy?>"></td>
                    <td class="powercharts_td_left">How Many Previous Pregnancies?</td><td><input name="Previous_Pregnancies" type="text" value="<?php echo $Previous_Pregnancies?>"></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">How Many Births</td><td><input name="Number_Of_Births" type="text" value="<?php echo $Number_Of_Births?>"></td>
                    <td  width="26%" class="powercharts_td_left">How Many Still Births/Aborted Pregnancies?</td><td><input name="Number_Of_Aborted_Pregnancies" type="text" value="<?php echo $Number_Of_Aborted_Pregnancies?>"></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">How Many Children Who Died Within 7 Days Of Birth</td><td><input name="Children_Died_Within_Seven_Days" type="text" value="<?php echo $Children_Died_Within_Seven_Days?>"></td>
                    <td  width="26%" class="powercharts_td_left">How Many Current Children?</td><td><input name="Number_Of_Current_Children" type="text" value="<?php echo $Number_Of_Current_Children?>"></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Age Of Last Child</td><td><input name="Age_Of_Last_Child" type="text" value="<?php echo $Age_Of_Last_Child;?>"></td>
                    <td  width="26%" class="powercharts_td_left">Patients HB</td><td><input name="Patient_HB" type="text" value="<?php echo $Patient_HB?>"></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Blood Pressure</td><td><input name="Blood_Pressure" type="text" value="<?php echo $Blood_Pressure?>"></td>
                    <td  width="26%" class="powercharts_td_left">Height</td><td><input name="Height" type="text" value="<?php echo $Height?>"></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Sugar Result</td><td><input name="Sugar_Result" type="text" value="<?php echo $Sugar_Result;?>"></td>
                    <td  width="26%" class="powercharts_td_left">Has The Patient Had a C/S Previously?</td><td>
                                                                                                                <select  style="width: 300px" class="select_contents" name="Previous_CS" id="Previous_CS"> 
                    <option>Select From List</option>
                    <?php
                        if($Previous_CS == "Yes"){
                            echo "<option class='select_contents' selected='selected'>Yes</option>";
                        }else{
                            echo "<option class='select_contents' >Yes</option>";
                        }
                        if($Previous_CS == "No"){
                            echo "<option class='select_contents' selected='selected'>No</option>";
                        }else{
                            echo "<option class='select_contents'>No</option>";
                        }
                        
                    ?>
                    
                    
                </select></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Are You Under  The Age Of 20?</td><td>
                    <select  style="width: 300px" class="select_contents" name="Age_Under_20" id="Age_Under_20">
                                <option value="<?php echo $Age_Under_20;?>" selected="selected"></option>
                               <option> Select From List</option>
                    <?php
                        if($Age_Under_20 == "Yes"){
                            echo "<option class='select_contents' selected='selected'>Yes</option>";
                        }else{
                            echo "<option class='select_contents' >Yes</option>";
                        }
                        if($Age_Under_20 == "No"){
                            echo "<option class='select_contents' selected='selected'>No</option>";
                        }else{
                            echo "<option class='select_contents'>No</option>";
                        }
                        
                    ?>
                            </select>
                                                                                    </td>
                    <td  width="26%" class="powercharts_td_left">Are You Over The Age Of 35?</td><td>
                                                    <select  style="width: 300px" class="select_contents" name="Age_Over_35" id="Age_Over_35"> 
                                <option> Select From List</option>
                                <?php
                        if($Age_Over_35== "Yes"){
                            echo "<option class='select_contents' selected='selected'>Yes</option>";
                        }else{
                            echo "<option class='select_contents' >Yes</option>";
                        }
                        if($Age_Over_35 == "No"){
                            echo "<option class='select_contents' selected='selected'>No</option>";
                        }else{
                            echo "<option class='select_contents'>No</option>";
                        }
                        ?>
                                                </select>
                            </td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Have You Been Tested For Syphilis?</td><td>
                                                        <select  style="width: 300px" class="select_contents" name="Tested_Syphilis" id="Tested_Syphilis">
                                                        <option> Select From List</option>
                                <?php
                        if($Tested_Syphilis== "Yes"){
                            echo "<option class='select_contents' selected='selected'>Yes</option>";
                        }else{
                            echo "<option class='select_contents' >Yes</option>";
                        }
                        if($Tested_Syphilis == "No"){
                            echo "<option class='select_contents' selected='selected'>No</option>";
                        }else{
                            echo "<option class='select_contents'>No</option>";
                        }
                        ?>
                                                    </select>
                                                    </td>
                    <td  width="26%" class="powercharts_td_left">What Was The Result?</td><td>
                    <select name="Last_Result" id="Last_Result">
                        <option> Select From List</option>
                                <?php
                        if($Last_Result== "Positive"){
                            echo "<option class='select_contents' selected='selected'>Positive</option>";
                        }else{
                            echo "<option class='select_contents' >Positive</option>";
                        }
                        if($Last_Result == "Negative"){
                            echo "<option class='select_contents' selected='selected'>Negative</option>";
                        }else{
                            echo "<option class='select_contents'>Negative</option>";
                        }
                        if($Last_Result == "Unknown"){
                            echo "<option class='select_contents' selected='selected'>Unknown</option>";
                        }else{
                            echo "<option class='select_contents'>Unknown</option>";
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Has a TT Card?</td><td width="25%">
                                                                                    <select  style="width: 300px" class="select_contents" name="TT_Card" id="TT_Card"> 
                                                                                        <option> Select From List</option>
                                <?php
                        if($TT_Card== "Yes"){
                            echo "<option class='select_contents' selected='selected'>Yes</option>";
                        }else{
                            echo "<option class='select_contents' >Yes</option>";
                        }
                        if($TT_Card == "No"){
                            echo "<option class='select_contents' selected='selected'>No</option>";
                        }else{
                            echo "<option class='select_contents'>No</option>";
                        }
                        ?>
                                                                                            </select>
                                                                                                </td>
                </tr>
                <tr>
                   <td></td></td><td colspan="3"><hr></td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Date Of TT Dose</td><td colspan="3">                            
                            <table width="100%">
                                <tr>
                                    <td class="powercharts_td_left">Date of TT Dose 1</td><td><input name="Date_Of_TT_Dose1" type="text" id="Date_Of_TT_Dose1" value="<?php echo $Date_Of_TT_Dose1?>"></td><td class="powercharts_td_left">Date of TT Dose 4</td><td><input name="Date_Of_TT_Dose4" id="Date_Of_TT_Dose4" type="text" value="<?php echo $Date_Of_TT_Dose1?>"></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">Date of TT Dose 2</td><td><input name="Date_Of_TT_Dose2" type="text" id="Date_Of_TT_Dose2" value="<?php echo $Date_Of_TT_Dose2?>"></td><td class="powercharts_td_left">Date of TT Dose 5</td><td><input name="Date_Of_TT_Dose5" id="Date_Of_TT_Dose5" type="text" value="<?php echo $Date_Of_TT_Dose1?>"></td>
                                </tr>
                                <tr>
                                    <td class="powercharts_td_left">Date of TT Dose 3</td><td><input name="Date_Of_TT_Dose3" id="Date_Of_TT_Dose3" type="text" value="<?php echo $Date_Of_TT_Dose3?>"></td><td colspan="2"></td>
                                </tr>
                            </table>
                </tr>
                    </table>
                    </center>
                </div>

                <div id="view2">
                <center>    <table class="hiv_table" border="0" >
                        <tr>
                            <td width="30%" class="powercharts_td_left">Have You Been Tested For Syphilis?</td><td>
                                                                                            <select  style="width: 300px" class="select_contents" name="Mother_Syphilis_Treatment" id="Mother_Syphilis_Treatment"> 
                                                                                               <option> Select From List</option>
                                        <?php
                                if($Mother_Syphilis_Treatment== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Mother_Syphilis_Treatment == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                            </select>
                                                                                            </td>
                            <td width="30%" class="powercharts_td_left">What Was The Results?</td><td>
                                    <select name="Last_Result" id="Last_Result1">
                                        
                                        <option> Select From List</option>
                                        <?php
                                if($Last_Result== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Last_Result == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                if($Last_Result == "Unknown"){
                                    echo "<option class='select_contents' selected='selected'>Unknown</option>";
                                }else{
                                    echo "<option class='select_contents'>Unknown</option>";
                                }
                                ?>
                                                                                                </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Has Your Husband/Partner Been Tested For Syphils?</td><td>
                                                                                    <select  style="width: 300px" class="select_contents" name="Husband_Syphilis_Test" id="Husband_Syphilis_Test"> 
                                                                                                <option> Select From List</option>
                                        <?php
                                if($Husband_Syphilis_Test== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Husband_Syphilis_Test == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                            </select>
                                                                                            </td>

                        <td width="30%" class="powercharts_td_left">What Was The Result For Your Husband/Partner?</td><td>
                                                                                                 <select name="Husband_Syphilis_Test_Result" id="Husband_Syphilis_Test_Result">
                                                                                                    <option> Select From List</option>
                                        <?php
                                if($Husband_Syphilis_Test_Result== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Husband_Syphilis_Test_Result == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                if($Husband_Syphilis_Test_Result == "Unknown"){
                                    echo "<option class='select_contents' selected='selected'>Unknown</option>";
                                }else{
                                    echo "<option class='select_contents'>Unknown</option>";
                                }
                                ?>
                                                                                                </select>
                                                                                                </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">have you received treatment for syphilis?</td><td>
                             <select  style="width: 300px" class="select_contents" name="Mother_Syphilis_Treatment" id="Mother_Syphilis_Treatment">
                                    <option> Select From List</option>
                                        <?php
                                if($Mother_Syphilis_Treatment== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Mother_Syphilis_Treatment == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                </select></td>
                            <td width="30%" class="powercharts_td_left">has your husband/partner received treatment for syphilis?</td><td>
                             <select  style="width: 300px" class="select_contents" name="Husband_Syphilis_Treatment" id="Husband_Syphilis_Treatment">
                                   <option> Select From List</option>
                                        <?php
                                if($Husband_Syphilis_Treatment== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Husband_Syphilis_Treatment == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">have you ever had any other sexually transmitted disease?</td><td>
                             <select  style="width: 300px" class="select_contents" name="Any_Sexual_Transmitted_Desease" id="Any_Sexual_Transmitted_Desease"> 
                                                                                                <option> Select From List</option>
                                        <?php
                                if($Any_Sexual_Transmitted_Desease== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Any_Sexual_Transmitted_Desease == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                            </select></td>
                            <td width="30%" class="powercharts_td_left">have you ever received treatment for an STD's?</td><td>
                             <select  style="width: 300px" class="select_contents" name="Received_Std_Treatment" name="Received_Std_Treatment"> 
                                                                                               <option> Select From List</option>
                                        <?php
                                if($Received_Std_Treatment== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Received_Std_Treatment == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                            </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Has your husband/Partner ever had any other STD's?</td><td>
                             <select  style="width: 300px" class="select_contents" name="Husband_Std_Desease" name="Husband_Std_Desease"> 
                                     <option> Select From List</option>
                                        <?php
                                if($Husband_Std_Desease== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Husband_Std_Desease == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                            </select>
                                                                                            </td>
                            <td width="30%" class="powercharts_td_left">Has your husband/Partner ever received treatment for an STD's?</td><td>
                             <select  style="width: 300px" class="select_contents" name="Husband_Std_Treatment" id="Husband_Std_Treatment"> 
                                                                                                <option> Select From List</option>
                                        <?php
                                if($Husband_Std_Treatment== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Husband_Std_Treatment == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                            </select>
                                                                                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Previous HIV test</td><td>
                                                                                    <select name="Mother_Previous_Hiv_Test" id="Mother_Previous_Hiv_Test">
                                                                                         <option> Select From List</option>
                                        <?php
                                if($Mother_Previous_Hiv_Test== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Mother_Previous_Hiv_Test == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                if($Mother_Previous_Hiv_Test == "Unsecure"){
                                    echo "<option class='select_contents' selected='selected'>Unsecure</option>";
                                }else{
                                    echo "<option class='select_contents'>Unsecure</option>";
                                }
                                ?>
                                                                                    </select></td>
                            <td width="30%" class="powercharts_td_left">Current HIV status</td><td>
                                                                                    <select name="Current_Hiv_Status" id="Current_Hiv_Status">
                                                                                        <option> Select From List</option>
                                        <?php
                                if($Current_Hiv_Status== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Current_Hiv_Status == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                if($Current_Hiv_Status == "Unsecure"){
                                    echo "<option class='select_contents' selected='selected'>Unsecure</option>";
                                }else{
                                    echo "<option class='select_contents'>Unsecure</option>";
                                }
                                ?>
                                                                                    </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Result of previous HIV test?</td><td>
                                                                                    <select name="Previous_Hiv_Result" id="Previous_Hiv_Result">
                                                                                         <option> Select From List</option>
                                        <?php
                                if($Previous_Hiv_Result== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Previous_Hiv_Result == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                if($Previous_Hiv_Result == "Unsecure"){
                                    echo "<option class='select_contents' selected='selected'>Unsecure</option>";
                                }else{
                                    echo "<option class='select_contents'>Unsecure</option>";
                                }
                                ?>
                                                                                    </select></td>
                            <td width="30%" class="powercharts_td_left">date of previous HIV tests?</td><td><input name="Date_Of_Previous_Hiv_Test" id="Date_Of_Previous_Hiv_Test" type="text" value="<?php echo $Date_Of_Previous_Hiv_Test?>"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Has your husband/Partner/Wife/Nother been tested for HIV?</td><td>
                                                                                                                    <select name="Partiner_Hiv_Test" id="Partiner_Hiv_Test">
                                                                                                                        <option> Select From List</option>
                                        <?php
                                if($Partiner_Hiv_Test== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Partiner_Hiv_Test == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                                                    </select></td>
                            <td width="30%" class="powercharts_td_left">HIV status of husband/Wife/Mother/Partner</td><td>
                                                                                    <select name="Partiner_Hiv_Status" id="Partiner_Hiv_Status">
                                                                                        <option> Select From List</option>
                                        <?php
                                if($Partiner_Hiv_Status== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Partiner_Hiv_Status == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                if($Partiner_Hiv_Status == "Unsecure"){
                                    echo "<option class='select_contents' selected='selected'>Unsecure</option>";
                                }else{
                                    echo "<option class='select_contents'>Unsecure</option>";
                                }
                                ?>
                                                                                    </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Is the patient taking ARV therapy?</td><td>
                                                                    <select name="Taking_ARV_Therapy" id="Taking_ARV_Therapy">
                                                                        <option> Select From List</option>
                                        <?php
                                if($Taking_ARV_Therapy== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Taking_ARV_Therapy == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                if($Taking_ARV_Therapy == "Does Not Apply"){
                                    echo "<option class='select_contents' selected='selected'>Does Not Apply</option>";
                                }else{
                                    echo "<option class='select_contents'>Does Not Apply</option>";
                                }
                                ?>
                                                                    </select></td>
                            <td width="30%" class="powercharts_td_left">Has your Husband/Partner/Wife/Mother received councelling for HIV?</td><td>
                                                                                                                    <select name="Partiner_Received_Councilling" id="Partiner_Received_Councilling">
                                                                                                                        <option> Select From List</option>
                                        <?php
                                if($Partiner_Received_Councilling== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Partiner_Received_Councilling == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                                                                                                                    </select></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Which ARV medication are they taking?</td><td>
                                    <select name="ARV_Medication_Type" id="ARV_Medication_Type">
                                        <option> Select From List</option>
                                        <?php
                                if($ARV_Medication_Type== "ARV Propholaxis"){
                                    echo "<option class='select_contents' selected='selected'>ARV Propholaxis</option>";
                                }else{
                                    echo "<option class='select_contents' >ARV Propholaxis</option>";
                                }
                                if($ARV_Medication_Type == "ART"){
                                    echo "<option class='select_contents' selected='selected'>ART</option>";
                                }else{
                                    echo "<option class='select_contents'>ART</option>";
                                }
                                if($ARV_Medication_Type == "CTX"){
                                    echo "<option class='select_contents' selected='selected'>CTX</option>";
                                }else{
                                    echo "<option class='select_contents'>CTX</option>";
                                }
                                if($ARV_Medication_Type == "Other"){
                                    echo "<option class='select_contents' selected='selected'>Other</option>";
                                }else{
                                    echo "<option class='select_contents'>Other</option>";
                                }
                                ?>
                                    </select></td>
                            <td width="30%" class="powercharts_td_left">How long have they been taking arv?</td><td><input name="ARV_Medication_Duration" type="text" value="<?php echo $ARV_Medication_Duration?>"></td>
                        </tr>
                    </table></center>
                </div>
                <div id="view3">
                    <center>
                <table  class="hiv_table" border="0" >
                    <tr>
                        <td width="30%" class="powercharts_td_left">Hatient has receives pre-testing counselling</td>
                        <td>
                            <select name="Received_Pre_Testing_Councilling" id="Received_Pre_Testing_Councilling">
                                <option> Select From List</option>
                                        <?php
                                if($Received_Pre_Testing_Councilling== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Received_Pre_Testing_Councilling == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                if($Received_Pre_Testing_Councilling == "Does Not Apply"){
                                    echo "<option class='select_contents' selected='selected'>Does Not Apply</option>";
                                }else{
                                    echo "<option class='select_contents'>Does Not Apply</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td width="30%" class="powercharts_td_left">Patient has signed HIV declaration</td>
                        <td>
                            <select name="Signed_Hiv_Declaration" id="Signed_Hiv_Declaration">
                                <option> Select From List</option>
                                        <?php
                                if($Signed_Hiv_Declaration== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Signed_Hiv_Declaration == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                if($Signed_Hiv_Declaration == "Does Not Apply"){
                                    echo "<option class='select_contents' selected='selected'>Does Not Apply</option>";
                                }else{
                                    echo "<option class='select_contents'>Does Not Apply</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                <tr>
                    <td class="powercharts_td_left">Patient has received post-result counselling</td>
                    <td>
                        <select name="Received_Post_Testing_Councilling" id="Received_Post_Testing_Councilling">
                            <option> Select From List</option>
                                        <?php
                                if($Received_Post_Testing_Councilling== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Received_Post_Testing_Councilling == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                if($Received_Post_Testing_Councilling == "Does Not Apply"){
                                    echo "<option class='select_contents' selected='selected'>Does Not Apply</option>";
                                }else{
                                    echo "<option class='select_contents'>Does Not Apply</option>";
                                }
                                ?>
                        </select>
                    </td>
                    <td width="30%" class="powercharts_td_left">What is the result of your malaria test?</td>
                    <td>
                        <select name="Malaria_Test_Result" id="Malaria_Test_Result">
                            <option> Select From List</option>
                                        <?php
                                if($Malaria_Test_Result== "Positive"){
                                    echo "<option class='select_contents' selected='selected'>Positive</option>";
                                }else{
                                    echo "<option class='select_contents' >Positive</option>";
                                }
                                if($Malaria_Test_Result == "Negative"){
                                    echo "<option class='select_contents' selected='selected'>Negative</option>";
                                }else{
                                    echo "<option class='select_contents'>Negative</option>";
                                }
                                ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="powercharts_td_left">Recommended date for status review</td><td ><input name="Recommendation_Date_For_Status_Review" id="Recommendation_Date_For_Status_Review" type="text" value="<?php echo $Recommendation_Date_For_Status_Review;?>"></td>
                    <td class="powercharts_td_left">Have you received your ITN/LLN?</td><td>
                        <select name="Received_ITN_LLN" id="Received_ITN_LLN">
                            <option> Select From List</option>
                                        <?php
                                if($Received_ITN_LLN== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Received_ITN_LLN == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                        </select>
                    </td>
                </tr>
                <tr>
                <td class="powercharts_td_left">IPT1</td><td><input name="IPT1" type="text" value="<?php echo $IPT1?>"></td>
                <td class="powercharts_td_left">IPT2</td><td><input name="IPT2" type="text" value="<?php echo $IPT2?>"></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">Iron and folic acid supplements supplied on clinic 1</td><td><input name="Iron_Folic_Clinic1" type="text" value="<?php echo $Iron_Folic_Clinic1?>"></td>
                <td class="powercharts_td_left">Iron and folic acid supplements supplied on clinic 2</td><td><input name="Iron_Folic_Clinic2" type="text" value="<?php echo $Iron_Folic_Clinic2?>"></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">Iron and folic acid supplements supplied on clinic 3</td><td><input name="Iron_Folic_Clinic3" type="text" value="<?php echo $Iron_Folic_Clinic3?>"></td>
                <td class="powercharts_td_left">Iron and folic acid supplements supplied on clinic 4</td><td><input name="Iron_Folic_Clinic4" type="text" value="<?php echo $Iron_Folic_Clinic4?>"></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">Medication for worms</td><td colspan="3"><input name="Worm_Medication" type="text" value="<?php echo $Worm_Medication?>"></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">Is the patient to be referred/Admitted</td><td colspan="3">
                <select name="Patient_To_Be_Refered_Admitted" id="Patient_To_Be_Refered_Admitted">
                <option> Select From List</option>
                                        <?php
                                if($Patient_To_Be_Refered_Admitted== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Patient_To_Be_Refered_Admitted == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                ?>
                </select></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">The patient is being referred to which facility?</td><td colspan="3"><input name="Patient_Refered_Facility" type="text" value="<?php echo $Patient_Refered_Facility?>"></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">The patient is being referred for what reason?</td><td colspan="3"><textarea name="Patient_Refered_Reason"><?php echo $Patient_Refered_Reason?></textarea></td>
                </tr>
                <tr>
                <td class="powercharts_td_left">If the patient is HIV postive with children have they received information on feeding?</td>
		<td>
		    <select name="Received_Hiv_Feeding_Information" id="Received_Hiv_Feeding_Information">
                        <option> Select From List</option>
                                <?php
                                if($Received_Hiv_Feeding_Information== "Yes"){
                                    echo "<option class='select_contents' selected='selected'>Yes</option>";
                                }else{
                                    echo "<option class='select_contents' >Yes</option>";
                                }
                                if($Received_Hiv_Feeding_Information == "No"){
                                    echo "<option class='select_contents' selected='selected'>No</option>";
                                }else{
                                    echo "<option class='select_contents'>No</option>";
                                }
                                if($Received_Hiv_Feeding_Information == "Does Not Apply"){
                                    echo "<option class='select_contents' selected='selected'>Does Not Apply</option>";
                                }else{
                                    echo "<option class='select_contents'>Does Not Apply</option>";
                                }
                                ?>
		    </select>
		</td>
                </tr>
                <tr>
                <td class="powercharts_td_left" nowrap="3">Other Comments</td><td colspan="3" rowspan="3"><textarea name="Other_Comments"><?php echo $Other_Comments?></textarea>
                </td>
                </tr>
                </table>
        </center>
                </div>
           
        </div>
    <script>
           $(function() {
           $( "#tabcontents" ).tabs();
           });
        </script>
    <center>
        <table>
		    <tr>
                        <td>
			    <input type="submit" name="submit" value="Update and Go Back" class="art-button-green" style="text-align: center"/>
                            <a href="rchconsultedcreditpatientlist.php?RchConsultedPatientCreditlist=RchConsultedCreditPatientlistThispage" class="art-button-green">Go Back</a>
			</td>
                    </tr>
		</table>
    </center>
     </form>
    </fieldset>
       
    <?php
    include("./includes/footer.php");
?>
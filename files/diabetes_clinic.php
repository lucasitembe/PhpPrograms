<?php
    include("./includes/header.php");
    include("./includes/connection.php");
     $temp=1;
	// $temp=++;
	 
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
  $Registration_ID =$_GET['Registration_ID'];
  $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
  $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
  $Employee_ID=$_SESSION['userinfo']['Employee_ID'];


  $admisions = "SELECT * from  tbl_admission_clinic where Registration_ID = $Registration_ID ORDER BY admission_ID DESC LIMIT 10";
            $admission_result = mysqli_query($conn, $admisions);

  $diabete_education = "SELECT * from diabetes_education where Registration_ID = $Registration_ID ORDER BY diabetes_educ_ID DESC LIMIT 10";
            $diabete_education_result = mysqli_query($conn, $diabete_education);


  $funcdoscopy = "SELECT * from tbl_fundoscopy where Registration_ID = $Registration_ID ORDER BY fundoscopy_ID DESC LIMIT 10";
            $fundoscopy_result = mysqli_query($conn, $funcdoscopy);

  $follow_up_visits = "SELECT * from  follow_up_visit where Registration_ID = $Registration_ID ORDER BY followup_visit_ID DESC LIMIT 10";
            $followup_result = mysqli_query($conn, $follow_up_visits);
           
    $regural_checkup  = "SELECT * from regural_check where Registration_ID = $Registration_ID ORDER BY regural_check_ID DESC LIMIT 10";
            $chackup_result = mysqli_query($conn, $regural_checkup);
            
    $diabetic_clinic  = "SELECT * from diabetic_clinic where Registration_ID = '$Registration_ID' ORDER BY diabetic_clinic_ID DESC LIMIT 1";
            $clinicresult = mysqli_query($conn, $diabetic_clinic);
            $rows = mysqli_fetch_assoc($clinicresult);

    $Registration_ID = "";
    $consultation_ID = "";
    $Patient_Payment_Item_List_ID = "";

    if(isset($_GET['Patient_Payment_Item_List_ID']) && $_GET['Patient_Payment_Item_List_ID'] != "") {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    } else {
        $Patient_Payment_Item_List_ID = "";
    }

     if(isset($_GET['consultation_ID']) && $_GET['consultation_ID'] != "") {
        $consultation_ID = $_GET['consultation_ID'];
    } else {
        $consultation_ID = "";
    }

     if(isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != "") {
        $Registration_ID = $_GET['Registration_ID'];
    } else {
        $Registration_ID = "";
    }
           
            
?>
    <style>
        /* .btn-success {
            border-radius: 5px; 
            box-shadow: 5px 8px 15px rgba(10, 15, 25, 255);
            background-repeat:no-repeat;
            height: 5px;
        } */
    </style>


 
    <a href="diabetic_clinic_bydate.php?Registration_ID=<?= $Registration_ID ?>&from_doctor&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>" class="art-button-green">PREVIEW RECORDS</a>
    <a href="nursecommunicationpage.php?Registration_ID=<?= $Registration_ID; ?>&consultation_ID=<?= $consultation_ID; ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID; ?>" class="art-button-green">BACK</a>
    
    <script>
    function goBack() {
        window.history.back();
    }
    </script>
	

	
	<!--new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }

// end of the function -->


        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }

		if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_Name'])){
                $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
            }else{
                $Employee_Name = 'Unknown';
            }
        }

?>


<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select Registration_ID,
                                    Old_Registration_Number, Patient_Name,Title,
                                    Date_Of_Birth,Gender, Tribe, pr.Region,pr.District,pr.Ward,pr.Sponsor_ID,Member_Number,Member_Card_Expire_Date,
									pr.Phone_Number,Email_Address,Occupation,Employee_Vote_Number,Emergence_Contact_Name,
									Emergence_Contact_Number,Company,Employee_ID,Registration_Date_And_Time,Patient_Picture,Guarantor_Name
                                      from tbl_patient_registration pr, tbl_sponsor sp where pr.Sponsor_ID = sp.Sponsor_ID  and
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
      
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Patient_Name = $row['Patient_Name'];
                $Title = $row['Title'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Tribe = $row['Tribe'];
                $Gender = $row['Gender'];
		        $Region = $row['Region'];
                $District = $row['District'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Ward = $row['Ward'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Member_Number = $row['Member_Number'];
		        $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
                $Patient_Picture = $row['Patient_Picture'];
		        $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Occupation = $row['Occupation'];
                $Email_Address = $row['Email_Address'];
		        $Guarantor_Name = $row['Guarantor_Name'];
				 }
	 
	  $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
	 
	     
        }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
	            $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
                $Guarantor_Name=''; 
             			
        }
    }else{
                $Registration_ID = '';
                $Old_Registration_Number = '';
                $Patient_Name = '';
                $Title = '';
                $Date_Of_Birth = '';
                $Gender = '';
		        $Region = '';
                $District = '';
                $Member_Card_Expire_Date = '';
                $Ward = '';
                $Sponsor_ID = '';
                $Member_Number = '';
		        $Emergence_Contact_Name = '';
                $Emergence_Contact_Number = '';
                $Company = '';
                $Employee_ID = '';
                $Registration_Date_And_Time = '';
                $Patient_Picture = '';
		        $Employee_Vote_Number = '';
                $Occupation = '';
                $Email_Address='';
		        $Guarantor_Name='';
				
        }
	
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

 
<br/>
<br/>



<fieldset>
    <legend align="center">DIABETES CLINIC FORM</legend>
    <form action="diabetic_clinic_db.php" method="post">
            <?php
                $Registration_ID = $_GET['Registration_ID'];  
            ?>
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
                    <div class="row col-md-12">

                        <div class="col-md-6">
                            <h4 align="center"><U> Diabetes Clinic</U></h4><br>
                            <table class="table" id="tabled">
                                <tbody>
                                    <tr>
                                        <th> HOSPITAL NO.</th>
                                        <td><input type="text"class="form-control" disabled='disabled' name="diabetic_clinic_no" style="align: center;" value="<?php echo $Registration_ID; ?>"> </td>
                                    </tr>
                                    <tr>
                                        <th>Patient Name</th>
                                        <td><input type="text" class="form-control" name="name" disabled='disabled' value="<?php echo $Patient_Name; ?>"> </td>
                                    </tr>
                                    <tr>
                                        <th>Yeah born</th>
                                        <td><input type="text" class="form-control date" name="dob" disabled='disabled' value="<?php echo $age; ?>"> </td>
                                    </tr>
                                    <tr>
                                        <th>Sex</th>
                                        <td>
                                            <input type="text" class="form-control" disabled='disabled' value="<?php echo $Gender; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Maried?</th>
                                        <td>
                                            <input type="text" class="form-control" disabled='disabled' value="<?php //echo $married; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tribe</th>
                                        <td><input type="text" class="form-control" name="Tribe" disabled='disabled' value="<?php echo $Tribe; ?>"> </td>
                                    </tr>
                                    <tr>
                                        <th>Place of residence</th>
                                        <td><input type="text" class="form-control" name="place_of_residence" disabled='disabled' value="<?php echo $Region .' '.$District .' '.$Ward; ?>"> </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                           <?php
                            if($rows['clinic_type'] =="TYPE I"){
                            $checked_yes ="checked ='checked'";
                            }else if($rows['clinic_type'] =="TYPE II"){
                                $checked_no ="checked = 'checked'";
                            }

                            if($rows['physical_activity'] =="light"){
                                $light ="checked ='checked'";
                                }else if($rows['physical_activity'] =="moderate"){
                                    $moderate ="checked = 'checked'";
                                }else if($rows['physical_activity'] =="heavy"){
                                    $heavy ="checked = 'checked'";
                                }else if($rows['physical_activity'] =="self_reliant"){
                                    $self_reliant ="checked = 'checked'";
                                }else if($rows['physical_activity'] =="depedent"){
                                    $depedent ="checked = 'checked'";
                                }
                                
                                if($rows['kind_of_treatment'] =="drug"){
                                    $drug ="checked ='checked'";
                                }else if($rows['kind_of_treatment'] =="insulin"){
                                    $insulin ="checked = 'checked'";
                                }else if($rows['kind_of_treatment'] =="diet"){
                                    $diet ="checked ='checked'";
                                }

                                if($rows['self_injecting'] =="yes"){
                                    $checkedyes ="checked ='checked'";
                                }else if($rows['self_injecting'] =="no"){
                                    $checkedno ="checked = 'checked'";
                                }

                                
                           ?> 
                        <div class="col-md-6"><input type="text" value="<?php echo $Registration_ID; ?>" name="Registration_ID" hidden>
                            <div class="row">
                                <table class="table" id="tableh">
                                    <tr>
                                        <th>Diabetic Clinic No.</th>
                                        <td><input type="text" class="form-control" name="diabetic_clinic_no" value="<?php echo $rows['diabetic_clinic_no']; ?>"></td>
                                    </tr>
                                </table>   
                            </div>            
                                
                                <div class="row">
                                    <table class="table borderless" id="type">
                                        <tr>
                                            <th>Type I</th><td><input type="radio" class="checkbox" value="TYPE I" <?php echo $checked_yes; ?> name="clinic_type"></td>
                                            <th>Type II</th><td><input type="radio"  class="checkbox" <?php echo $checked_no; ?> value="TYPE II" name="clinic_type"></td>
                                        </tr>
                                    </table>
                                </div>
                                
                            <div class="row">
                                <table class="table" id="tabley">
                                    <tr>
                                        <th>Year of diagnosis</th>
                                        <td><input type="text" value="<?php echo $rows['year_of_diagnosis']; ?>" name="year_of_diagnosis" class="form-control date text-center"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row">
                                <table class="table" id="tabley">
                                    <tr>
                                        <th>Occupation</th>
                                        <td><input type="text" value="<?php echo $rows['occupation']; ?>" class="form-control" name="occupation"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row">
                                <table class="table" id="tabley">
                                    <tr>
                                        <th>Closest Hospital</th>
                                        <td><input type="text" value="<?php echo $rows['closest_hospital']; ?>" class="form-control" name="closest_hospital" > </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group">
                                <div class="row center" id="physical">
                                    <h5><u>Physical activity</u></h5>                
                                </div>
                                <div class="row">
                                    <table class="table" style="border: none;">
                                        <tr>
                                            <th>Light</th><td><input type="radio" <?php echo $light; ?>  value="light" name="physical_activity"></td>
                                            <th>Moderate</th><td><input type="radio"  value="moderate"<?php echo $moderate; ?>  name="physical_activity"></td>
                                            <th>Heavy</th><td><input type="radio" <?php echo $heavy; ?>  value="heavy" name="physical_activity"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="row">
                                    <table class="table" style="border: none;">
                                        <tr>
                                            <th>Self-reliant</th><td><input type="radio"  value="self_reliant" <?php echo $self_reliant; ?> name="physical_activity"></td>
                                            <th>Dependent</th><td><input type="radio"  value="dependent" <?php echo $dependent; ?> name="physical_activity"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="row center" id="physical">
                                    <h5><u>Kind of treatment</u></h5>                
                                </div>
                                <div class="row">
                                    <table class="table" style="border: none;">
                                        <tr>
                                            <th>Diet</th><td><input type="checkbox"  value="diet" <?php echo $diet; ?>  name="kind_of_treatment"></td>
                                            <th>Drug</th><td><input type="checkbox"  value="drug" <?php echo $drug; ?>  name="kind_of_treatment"></td>
                                            <th>Insulin</th><td><input type="checkbox"  value="insulin" <?php echo $insulin; ?>  name="kind_of_treatment"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="row center" id="physical">
                                    <h5><u>Self-injecting?</u></h5>                
                                </div>
                                <div class="row">
                                    <table class="table" style="border: none;">
                                        <tr>
                                            <th>Yes</th><td><input type="radio"  <?php echo $checkedyes; ?> value="yes" name="self_injecting"></td>
                                            <th>No</th><td><input type="radio" <?php echo $checkedno; ?> value="no" name="self_injecting"></td>
                                        </tr>
                                    </table>
                                </div>
                                
                            </div>
                                    
                            
                            
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-5">
                            <div class="row"><h4><u>FIRST VISIT</u></h4></div>
                            <table style="border: none;">
                                <tr>
                                    <th>Body weight</th>
                                    <td><input type="text" value="<?php echo $rows['body_weight']; ?>" required class="form-control" name="body_weight" id="weight"></td>
                                </tr>
                                <tr>
                                    <th>Height</th>
                                    <td><input type="text" required class="form-control"  onkeyup='calculateBMI()' value="<?php echo $rows['height']; ?>" name="height" id="height"></td>
                                </tr>
                                <tr>
                                    <th>BMI</th>
                                    <td><input type="text" required class="form-control"  name="bim" id="bmi"value="<?php echo $rows['bmi']; ?>" ></td>
                                </tr>
                                <tr>
                                    <th>RBG</th>
                                    <td><input type="text" value="<?php echo $rows['rbg']; ?>" required class="form-control" name="rbg"></td>
                                </tr>
                                <tr>
                                    
                                    
                                </tr>
                            </table>
                            
                        </div>
                        <div class="col-md-7">
                                <div class="form-group">
                                    <div class="row"><h5><u>Special Needs</u> </h5></div>
                                    <div class="row">
                                        <textarea name="special_needs" required id="" cols="30" rows="2" class="form-control"><?php echo $rows['special_needs']; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table class="table" >
                                        <thead>
                                            <tr>
                                                <th>Other diagnoses</th>
                                                <th>Since</th>
                                                <th>Treatment</th>
                                            </tr>
                                        </thead>
                                        <tbody id="addform">
                                            <tr >
                                                <td><input type="text" required class="form-control" value="<?php echo $rows['other_diagnosis']; ?>" name="other_diagnosis"></td>
                                                <td><input type="text" value="<?php echo $rows['since']; ?>" required class="form-control date" name="since"></td>
                                                <td><input type="text" value="<?php echo $rows['treatment']; ?>" required class="form-control" name="treatment"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            
                        </div>
                    </div>
                    <div class="row col-md-offset-9 col-md-3">
                        <?php if(mysqli_num_rows($clinicresult)>0){

                        }else{?>
                            <button type="submit" name="clinic" class="btn btn-success  btn-sm pull-right">Submit</button>
                        <?php }?>
                                
                            </div>  
                </form>
  
</fieldset>
<fieldset>
        <legend align="center">Regular Checkup</legend>
        <form action="diabetic_clinic_db.php" method="post">
            <?php
                $Registration_ID = $_GET['Registration_ID'];  
            ?>
            <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <table class="table table-bordered" >           
                <thead>
                
                    <tr>
                        <th><h4>Date</h4></th>
                        <th>Hb</th>
                        <th>HbA1c</th>
                        <th>Microalb</th>
                        <th>BUN</th>
                        <th>Crea</th>
                        <th colspan="2">ESR</th>
                    </tr>                
                </thead>
                <tbody>
                <?php while($row =mysqli_fetch_assoc($chackup_result)){?>
                        <tr>
                            <td><?php echo $row['created_at']; ?></td>
                            <td><?php echo $row['hb']; ?></td> 
                            <td><?php echo $row['hba1c']; ?></td>
                            <td><?php echo $row['microalb']; ?></td>
                            <td><?php echo $row['bun']; ?></td>
                            <td><?php echo $row['crea']; ?></td>
                            <td><?php echo $row['esr']; ?></td>
                        </tr>
                        <?php  } ?>
                    <tr>
                        <td><input type="text" class="form-control date text-center" name="created_at" value="<?php echo date('Y-M-d H:i:s');?>" ></td>
                        <td><input type="text" required class="form-control" name="hb"></td> 
                        <td><input type="text" required class="form-control" name="hba1c"></td>
                        <td><input type="text" required class="form-control" name="microalb"></td>
                        <td><input type="text" required class="form-control" name="bun"></td>
                        <td><input type="text" required class="form-control" name="crea"></td>
                        <td><input type="text" required class="form-control" name="esr"></td>                                     
                        <td><button name="regular_check"class="btn btn-success btn-block btn-sm">Save</button></td>
                    </tr>
                
                </tbody>
            </table>
        </form>
      
</fieldset>
<fieldset>
        <legend align="center">FUNDOSCOPY</legend>
        <form action="diabetic_clinic_db.php" method="post">
        <?php
        $Registration_ID = $_GET['Registration_ID'];  
        ?>
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <table class="table table-bordered" >
                <thead>
                    <tr>
                        <th><h4>Date</h4></th>
                        <th colspan="2"><h5>Fundoscopy, Vibration test and other special investigation</h5></th>
                    </tr>               
                </thead>
                <tbody id="btn">
                <?php while($fundo =mysqli_fetch_assoc($fundoscopy_result)){?>
                    <tr> 
                        <td><?php echo $fundo['created_at'];?></td>
                        <td><input type="text" class="form-control" name="fundoscopy_test"  colspan="2" value="<?php echo $fundo['fundoscopy_test'];?>" disabled></td>
                    </tr>
                <?php } ?>
                    <tr> 
                        <td><input type="text"  required class="form-control date text-center" name="created_at" required value="<?php echo date('Y-M-d H:i:s');?>" ></td>
                        <td><input type="text" required class="form-control" name="fundoscopy_test" required></td>
                        <td><button  class="btn btn-success btn-center btn-sm btn-block" type="submit"  name="vbTest" >Save</button></td> 
                    </tr>
                </tbody>
            </table>
        </form>        
</fieldset>
<fieldset>
        <legend align="center">DIABETES EDUCATION</legend>
        <form action="diabetic_clinic_db.php" method="post"><input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <table class="table table-bordered" id="tabled">
                <thead>                    
                    <tr id="tabled">                    
                        <th>Date</th>
                        <th>General</th>
                        <th>Diet</th>
                        <th>Insulin/Injection Technique</th>
                        <th>Urine Testing</th>
                        <th>Hyper -Hypoglycemic</th>
                        <th>Foot Care</th>
                        <th>Late Complications</th>
                        <th colspan="2">Drugs</th>
                        
                    </tr>                
                </thead>
                <tbody>
                <?php while($rows =mysqli_fetch_assoc($diabete_education_result)){?>
                        <tr>
                            <td><?php echo $rows['created_at'];?></td>
                            <td><textarea type="text" class="form-control" name="general"  disabled><?php echo $rows['general'];?></textarea></td>
                            <td><input type="text" class="form-control" name="diet" value="<?php echo $rows['diet'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="injection_technique" value="<?php echo $rows['injection_technique'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="urine_testing" value="<?php echo $rows['urine_testing'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="hyper_hypoglycemic" value="<?php echo $rows['hyper_hypoglycemic'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="foot_care" value="<?php echo $rows['foot_care'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="late_complication" value="<?php echo $rows['late_complication'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="drugs" value="<?php echo $rows['drugs'];?>" disabled></td>                            
                        </tr>
                    <?php }?>
                    <tr>
                        <td><input type="text" class="form-control date text-center" name="created_at" value="<?php echo date('Y-M-d H:i:s');?>"></td>
                        <td><textarea type="text" required class="form-control" name="general"></textarea></td>
                        <td><input type="text" required class="form-control" name="diet"></td>
                        <td><input type="text" required class="form-control" name="injection_technique"></td>
                        <td><input type="text" required class="form-control" name="urine_testing"></td>
                        <td><input type="text" required class="form-control" name="hyper_hypoglycemic"></td>
                        <td><input type="text" required class="form-control" name="foot_care"></td>
                        <td><input type="text" required class="form-control" name="late_complication"></td>
                        <td><input type="text" required class="form-control" name="drugs"></td>
                        <td><button type="submit" class="btn btn-success btn-sm btn-block" name="diabetes_education">Save</button></td>
                    </tr>
                    
                </tbody>
            </table>
        </form>
</fieldset>

<fieldset>
        <legend align="center">ADMISSION</legend>
        <form action="diabetic_clinic_db.php" method="post">
        <?php
            $Registration_ID = $_GET['Registration_ID'];  
        ?>
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <table class="table table-bordered" id="tabled">
                <thead>                                                          
                        <tr>
                            <th>Date</th>
                            <th colspan="2">Diagnosis</th>
                        </tr>                   
                </thead>
                <tbody>
                <?php while($rowss =mysqli_fetch_assoc($admission_result)){?>
                    <tr>
                        <td><!--<input type="text" class="form-control date" name="admission_date" value="--><?php echo $rowss['created_at'];?></td>
                        <td><?php echo $rowss['admission_diagnosis'];?></td>
                        
                    </tr>
                <?php } ?>
                    <tr>
                        <td><input type="text" class="form-control date text-center" name="created_at" value=<?php echo date('Y-M-D'); ?>></td>
                        <td><textarea type="text" required class="form-control" name="admission_diagnosis"></textarea></td>
                        <td><button type="submit" class="btn btn-success btn-center btn-sm btn-block" name="admission">Save</button></td>
                    </tr>
                </tbody>
            </table>
        </form>
</fieldset>
<fieldset>
        <legend align="center">Follow-up Visits</legend>
        <form action="diabetic_clinic_db.php" method="post">
        <?php
            $Registration_ID = $_GET['Registration_ID'];  
        ?>
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
            <table class="table table-bordered" id="tabled">
                <thead >
                    <tr>
                        <th>Date</th>
                        <th>Bwt</th>
                        <th>BP</th>
                        <th>RBG</th>
                        <th colspan="2">Clinical Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    //if(mysqli_num_rows($followup_result)>0){
                    
                    while($rowes =mysqli_fetch_assoc($followup_result)){?>
                        <tr>
                            <td><?php echo $rowes['created_at'];?></td>
                            <td><input type="text" class="form-control" name="bwt" value="<?php echo $rowes['bwt'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="bp" value="<?php echo $rowes['bp'];?>" disabled></td>
                            <td><input type="text" class="form-control" name="rbg" value="<?php echo $rowes['rbg'];?>" disabled></td>
                            <td><?php echo $rowes['clinical_notes'];?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><input type="text" required class="form-control date text-center" name="created_at" value=<?php echo date('Y-M-D'); ?>></td>
                        <td><input type="text" required class="form-control" name="bwt"></td>
                        <td><input type="text" required class="form-control" name="bp"></td>
                        <td><input type="text" required class="form-control" name="rbg"></td>
                        <td><textarea type="text" required class="form-control" name="clinical_notes"></textarea></td>
                        <td><button class="btn btn-success btn-sm btn-block" name="follow_up_visit"  >Save</button></td>
                    </tr>
                    
                </tbody>
            </table>
        </form>
</fieldset>

<script>
    count_row= 0; 
    function addcheckup(){
        count_row++;
        var table = $('#btn').append("<tr id='"+count_row+"'><td><input type='Date' class='form-control' name='date'></td><td><input type='text' class='form-control' name='fundoscopy'></td><td><button title='Remove input field' class='btn btn-danger' onclick='removerow("+count_row+")'>X</button></td></tr>");
    }

    function removerow(row_id){
            $('#'+row_id).remove();
        }
    function save_fundoscopy_informatio(){
        $.ajax({
            type:'POST',
            url:'diabetic_clinic_db.php',
            data:{},
            sucess:function(responce){
                alert("Saved successful");
            }
        });
    }

    function follow_up_visit(){
        alert("Yesssssssssssssssss");
    }
</script>
<!-- SCript of BMI calculate-->	
<script type='text/javascript'>
    function calculateBMI() {
        var Weight = document.getElementById('weight').value;
        var Height = document.getElementById('height').value;
        if (Weight != '' && Height != '') {
            if (Height != 0) {
                var bmi = (Weight * 10000) / (Height * Height);
                document.getElementById('bmi').value = bmi.toFixed(2);
            }
        }
    }
</script>
<!-- End of script of BMI -->	

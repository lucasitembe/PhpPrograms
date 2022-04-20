<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<!--  insert data from the form  -->

<?php
    if(isset($_POST['submittedAddNewMsamahaPatientForm'])){
        
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
        
        $Old_Registration_Number = mysqli_real_escape_string($conn,$_POST['Old_Registration_Number']);
        $Patient_Title = mysqli_real_escape_string($conn,$_POST['Patient_Title']);

        if(isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together'){
            $Patient_First_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', '', $_POST['Patient_First_Name']));
            $Patient_Middle_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', '', $_POST['Patient_Middle_Name']));
            $Patient_Last_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', '', $_POST['Patient_Last_Name']));
            $Patient_Name = $Patient_First_Name.' '.$Patient_Middle_Name.' '.$Patient_Last_Name;
        }else{
            $Patient_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', ' ', $_POST['Patient_Name']));
        }

        $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);
        $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
        $Country = mysqli_real_escape_string($conn,$_POST['country']);
        $region = mysqli_real_escape_string($conn,$_POST['region']);
        $District = mysqli_real_escape_string($conn,$_POST['District']);
        $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
        $Guarantor_Name = mysqli_real_escape_string($conn,$_POST['Guarantor_Name']);
        $Member_Number = mysqli_real_escape_string($conn,$_POST['Member_Number']);
        $Member_Card_Expire_Date = mysqli_real_escape_string($conn,$_POST['Member_Card_Expire_Date']);
        $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
        $Email = mysqli_real_escape_string($conn,$_POST['Email']);
        $Occupation = mysqli_real_escape_string($conn,$_POST['Occupation']);
        $Employee_Vote_Number = mysqli_real_escape_string($conn,$_POST['Employee_Vote_Number']);
        $Emergence_Contact_Name = mysqli_real_escape_string($conn,$_POST['Emergence_Contact_Name']);
        $Emergence_Contact_Number = mysqli_real_escape_string($conn,$_POST['Emergence_Contact_Number']);
        $Company = mysqli_real_escape_string($conn,$_POST['Company']);
        //die($Gender.' '.$Country);
        //Msamaha Data
        $Na_ya_Wodi = mysqli_real_escape_string($conn,$_POST['Na_ya_Wodi']);
        $Name_Balozi = mysqli_real_escape_string($conn,$_POST['Name_Balozi']);
        $Aina_ya_msamaha= mysqli_real_escape_string($conn,$_POST['Aina_ya_msamaha']);
        $education_Level = mysqli_real_escape_string($conn,$_POST['Education_Level']);
        $Work_Wife = mysqli_real_escape_string($conn,$_POST['Work_Wife']);
        $Mahudhulio = mysqli_real_escape_string($conn,$_POST['Mahudhulio']);
        $Idadi_Mahudhulio = mysqli_real_escape_string($conn,$_POST['Idadi_Mahudhulio']);
        $Ni_ndugu_yako_yupi = mysqli_real_escape_string($conn,$_POST['Ni_ndugu_yako_yupi']);
        $amewahi_kutibiwa_mahali = mysqli_real_escape_string($conn,$_POST['amewahi_kutibiwa_mahali']);
        $mengineyo_yanayoonyesha_uwezo_wa_kuchangia = mysqli_real_escape_string($conn,$_POST['mengineyo_yanayoonyesha_uwezo_wa_kuchangia']);
        $amevaa_nguo_za_thamani = mysqli_real_escape_string($conn,$_POST['amevaa_nguo_za_thamani']);
        $anastahili_kupata_msamaha = mysqli_real_escape_string($conn,$_POST['anastahili_kupata_msamaha']);
        $Mapendekezo_ya_msamaha = mysqli_real_escape_string($conn,$_POST['Mapendekezo_ya_msamaha']);
        $anayependekeza_msamaha = mysqli_real_escape_string($conn,$_POST['anayependekeza_msamaha']);
        $anayependekeza_msamaha_id = mysqli_real_escape_string($conn,$_POST['anayependekeza_msamaha_id']);
        $cheo_anayependekeza_msamaha = mysqli_real_escape_string($conn,$_POST['cheo_anayependekeza_msamaha']);
        $sahihi_anayependekeza_msamaha = mysqli_real_escape_string($conn,$_POST['sahihi_anayependekeza_msamaha']);
        $Imehadhimishwa = mysqli_real_escape_string($conn,$_POST['Imehadhimishwa']);
        $Jina_la_anayehadhimisha = mysqli_real_escape_string($conn,$_POST['Jina_la_anayehadhimisha']);
        $cheo_anayehadhimisha= mysqli_real_escape_string($conn,$_POST['cheo_anayehadhimisha']);
        $sahihi_anayehadhimisha = mysqli_real_escape_string($conn,$_POST['sahihi_anayehadhimisha']);
        $Namba_katika_Rejista_ya_kupatiwa_msamaha = mysqli_real_escape_string($conn,$_POST['Namba_katika_Rejista_ya_kupatiwa_msamaha']);
        
        //fileUploaded
        $attach_file_name=$_FILES['attach_file']['name'];
        $attach_file_type=$_FILES['attach_file']['type'];
        $attach_file_size=$_FILES['attach_file']['size'];
        $attach_file_tmp_name=$_FILES['attach_file']['tmp_name'];
        
        
        
        
        //End of Msamaha Data
        
        
	
	//check if there is another patient based on entered member number
	$select_Membership_Id_Number_Status = mysqli_query($conn,"select Membership_Id_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'") or die(mysqli_error($conn));
	$row = mysqli_fetch_assoc($select_Membership_Id_Number_Status);
	$Membership_Id_Number_Status = $row['Membership_Id_Number_Status'];
	$num=0;
	if(strtolower($Membership_Id_Number_Status) == 'mandatory' ){
	    $check_info = mysqli_query($conn,"select * from tbl_Patient_Registration
					    where sponsor_id = (select Sponsor_ID from tbl_Sponsor where Guarantor_Name = '$Guarantor_Name' limit 1) and
						Member_Number = '$Member_Number' limit 1") or die(mysqli_error($conn));
	    
	    $num = mysqli_num_rows($check_info);
	    if($num > 0){
		while($row = mysqli_fetch_array($check_info)){
		    $Temp_Patient_Name = $row['Patient_Name'];
		    $Temp_Date_Of_Birth = $row['Date_Of_Birth'];
		    $Temp_Gender = $row['Gender'];
		    $Temp_Emergence_Contact_Name = $row['Emergence_Contact_Name'];
		}
	    }else{
		$Temp_Patient_Name = '';
		$Temp_Date_Of_Birth = '';
		$Temp_Gender = '';
		$Temp_Emergence_Contact_Name = '';
	    }
	}
	if($num > 0 ){
?>
	    <script>
		
		var Temp_Patient_Name = '<?php echo $Temp_Patient_Name; ?>';
		var Temp_Date_Of_Birth = '<?php echo $Temp_Date_Of_Birth; ?>';
		var Temp_Gender = '<?php echo $Temp_Gender; ?>';
		var Temp_Entered_Patient_Name = '<?php echo $Patient_Name; ?>';
		var Old_Registration_Number  = '<?php echo $Old_Registration_Number; ?>';
		var Patient_Title = '<?php echo $Patient_Title; ?>'; 
		var Patient_Name = '<?php echo $Patient_Name; ?>';
		var Date_Of_Birth = '<?php echo $Date_Of_Birth; ?>';
		var Gender = '<?php echo $Gender; ?>';
		var Country = '<?php echo $Country; ?>';
		var region = '<?php echo $region; ?>';
		var District = '<?php echo $District; ?>';
		var Ward = '<?php echo $Ward; ?>';
		var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
		var Member_Number = '<?php echo $Member_Number; ?>';
		var Member_Card_Expire_Date = '<?php echo $Member_Card_Expire_Date; ?>';
		var Phone_Number = '<?php echo $Phone_Number; ?>';
		var Email = '<?php echo $Email; ?>';
		var Occupation = '<?php echo $Occupation; ?>';
		var Employee_Vote_Number = '<?php echo $Employee_Vote_Number; ?>';
		var Emergence_Contact_Name = '<?php echo $Emergence_Contact_Name; ?>';
		var Emergence_Contact_Number = '<?php echo $Emergence_Contact_Number; ?>';
		var Company = '<?php echo $Company; ?>';
                
                //Msamaha
                
                
		
		alert('SORRY, PROCESS FAIL!!!\n\nMay be This Patient Already Registrered\n\n\nThis MEMBER NUMBER already used by\n Patient Name : '+Temp_Patient_Name+'\nDate of birth : '+Temp_Date_Of_Birth+'\nGender : '+Temp_Gender+'\n\nIf The Patient is Exactly ('+Temp_Patient_Name+') Select him/her from the registred list\nTo proceed with services.\nOthewise enter the member number correctly');
		
		document.getElementById("Patient_Name").value = Patient_Name;
		document.getElementById("date2").value = Date_Of_Birth;
		document.getElementById("Gender").value = Gender;
		document.getElementById("Country").value = Country;
		document.getElementById("Ward").value = Ward;
		document.getElementById("Guarantor_Name").value = Guarantor_Name;
		document.getElementById("Member_Number").value = Member_Number;
		document.getElementById("date").value = Member_Card_Expire_Date;
		document.getElementById("Phone_Number").value = Phone_Number;
		document.getElementById("Email").value = Email;
		document.getElementById("Occupation").value = Occupation;
		document.getElementById("Employee_Vote_Number").value = Employee_Vote_Number;
		document.getElementById("Emergence_Contact_Name").value = Emergence_Contact_Name;
		document.getElementById("Emergence_Contact_Number").value = Emergence_Contact_Number;
		document.getElementById("Company").value = Company;
		document.getElementById("Old_Registration_Number").value = Old_Registration_Number;
		document.getElementById("Patient_Title").value = Patient_Title;
		document.getElementById("Member_Number").focus();
		document.getElementById('eVerify_btn').style.visibility = "";
	    </script>
<?php	}else{
	    if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Employee_ID'])){
		    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		}else{
		    $Employee_ID = 0;
		}
	    }
	   
	    //select patient registration date and time
	    $data = mysqli_query($conn,"select now() as Registration_Date_And_Time");
	    while($row = mysqli_fetch_array($data)){
		$Registration_Date_And_Time = $row['Registration_Date_And_Time'];
	    }
		
		
	    $Insert_Sql = "insert into tbl_patient_registration(
			Old_Registration_Number,Title,Patient_Name,
			    Date_Of_Birth,Gender,Country,Region,District,Ward,
				Sponsor_ID,
				    Member_Number,Member_Card_Expire_Date,
					Phone_Number,Email_Address,Occupation,
					    Employee_Vote_Number,Emergence_Contact_Name,
						Emergence_Contact_Number,Company,
						    Employee_ID,Registration_Date_And_Time,District_ID,Registration_Date)
	    
			values('$Old_Registration_Number','$Patient_Title','$Patient_Name',
			'$Date_Of_Birth',
			    '$Gender','$Country','$region','$District','$Ward',
			    (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
			    '$Member_Number','$Member_Card_Expire_Date',
				'$Phone_Number','$Email','$Occupation',
				'$Employee_Vote_Number','$Emergence_Contact_Name',
				    '$Emergence_Contact_Number','$Company',
				    '$Employee_ID','$Registration_Date_And_Time',(select District_ID from tbl_district where District_Name = '$District'),(select now()))";
		if(!mysqli_query($conn,$Insert_Sql)){
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){
				    $controlforminput = 'not valid';
		    }else{
			die(mysqli_error($conn));
		    }
		}else{
		    $selectThisRecord = mysqli_query($conn,"select Registration_ID  from tbl_patient_registration where
												Employee_ID = '$Employee_ID' and
											    Emergence_Contact_Name = '$Emergence_Contact_Name' and
											    Registration_Date_And_Time = '$Registration_Date_And_Time' order by Registration_ID desc limit 1") or die(mysqli_error($conn));
                    
                    
		    
		    while($row = mysqli_fetch_array($selectThisRecord)){
			    $Registration_ID = $row['Registration_ID']; 
		    }
                    
                    if(!empty($attach_file_name)){
                        $attach_file_name=$Registration_ID.$attach_file_name;
                    }else{
                        $attach_file_name='';
                        $attach_file_size='';
                        $attach_file_type='';
                    }
                    
                    $sql="INSERT INTO tbl_msamaha VALUES('','$Registration_ID',NOW(),'$Na_ya_Wodi','$Name_Balozi','$Aina_ya_msamaha','$education_Level','$Work_Wife','$Mahudhulio','$Idadi_Mahudhulio','$Ni_ndugu_yako_yupi','$amewahi_kutibiwa_mahali','$amevaa_nguo_za_thamani','$mengineyo_yanayoonyesha_uwezo_wa_kuchangia','$Mapendekezo_ya_msamaha','$anastahili_kupata_msamaha','$anayependekeza_msamaha_id','$cheo_anayependekeza_msamaha','$sahihi_anayependekeza_msamaha','$Imehadhimishwa','$Jina_la_anayehadhimisha','$cheo_anayehadhimisha','$sahihi_anayehadhimisha','$Namba_katika_Rejista_ya_kupatiwa_msamaha','$attach_file_name','$attach_file_type','$attach_file_size')";
                    $statusReg='';
                    //die($sql);
                    //mysqli_query($conn,$sql) or die(mysqli_error($conn));
                    
                    if(mysqli_query($conn,$sql)){
                         if(!empty($attach_file_name)){
                             $movFile=move_uploaded_file($attach_file_tmp_name,"msamaha_attachments/".$attach_file_name);
                           
                         }
                    }
                    echo "<script type='text/javascript'>
			    alert('PARTIENT ADDED SUCCESSFULLY');
			    document.location = './visitorformMsamaha.php?Registration_ID=".$Registration_ID."&VisitorForm=VisitorFormThisPage';
			    </script>";
		}
	}
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
 <!--<a href='msamahalist2.php?RegisteredPatient=RegisterPatientThisPage' class='art-button-green'>
      SEARCH TO EDIT REGISTERED
    </a> -->
   <a href='msamaha_setup.php?RegisteredPatient=RegisterPatientThisPage' class='art-button-green'>
      MSAMAHA CONFIGURATION
    </a>
    <a href='msamahapanel.php?RegisteredPatient=RegisterPatientThisPage' class='art-button-green'>
      BACK
    </a>
  
  
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>


<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='searchiframemsamaha.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<script>
    $(function() {
    $( "#tabs" ).tabs();
  });
</script>

<script type="text/javascript" language="javascript">
    function getDistricts() {
    	var Region_Name = document.getElementById("region").value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetDistricts.php?Region_Name='+Region_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('District').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>


<br/>
          
<fieldset>  
            <legend align="center"><b>COST SHARING FORM</b></legend>
			<br/>
                        <form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                        
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Patient Registration</a></li>
    <li><a href="#tabs-2">Maelezo Msamaha</a></li>
   
  </ul>
  <div id="tabs-1">
      <?php include './registerpatientmsamaha.php';?>
      <!--<p>THis is the nmess</p>-->
  </div>
  <div id="tabs-2">
      <div style="width:48%;float:left">  
            
      <fieldset style="width:92%;">
          <legend>&nbsp;A: UTAMBULISHO</legend>
          <table width=100%>
               <tr>
                   <td style="text-align:right;">Namba ya Wodi/Kliniki ya Wagonjwa wa Nje</td>
                   <td width="40%"><input type='text' name='Na_ya_Wodi' id='Na_ya_Wodi' ></td>
              </tr>
              <tr>
                   <td style="text-align:right;">Jina la Balozi</td>
                   <td width="40%"><input type='text' name='Name_Balozi' id='Name_Balozi' ></td>
              </tr>
              <tr>
                   <td style="text-align:right;"><b style='color: red'>Aina ya msamaha</b></td>
                           <td width="40%">
                               <div id="displayMsamaha">
                                    <select name="Aina_ya_msamaha" id="Aina_ya_msamaha" style="padding:4px;width:99%" required>
                                        <option></option>
                                        <?php
                                         $Query= mysqli_query($conn,"SELECT * FROM tbl_msamaha_items");

                                          while ($row=  mysqli_fetch_assoc($Query)){
                                              echo '<option>'.$row['msamaha_aina'].'</option>'; 

                                         }
                                        ?>

                                    </select>
                                </div>
                       </td>
                       <td><input type="button" id="addmsamaha" class="art-button-green" value="Add"></td>
              </tr>
          </table>
      </fieldset>
      <fieldset style="width:92%;">
          <legend>&nbsp;B: MAELEZO YA ZIADA</legend>
          <table width=100%>
                <tr>
                    <td style="text-align:right;">Kiwango cha elimu</td>
                    <td width="40%"><input type='text' name='Education_Level' id='Education_Level' ></td>
                </tr>
                 <tr>
                    <td style="text-align:right;">Kazi ya mke/mlezi</td>
                    <td width="40%"><input type='text' name='Work_Wife' id='Work_Wife' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Idadi ya mahudhurio(wagojwa wa nje kwa miezi 6 iliyopita)</td>
                    <td width="40%"><input type='text' name='Mahudhulio' id='Mahudhulio' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Idadi ya kulazwa hospitali kwa miezi 6 iliyopita</td>
                    <td width="40%"><input type='text' name='Idadi_Mahudhulio' id='Idadi_Mahudhulio' ></td>
                </tr>
                <tr>
                    <td style="text-align:right;">Ni ndugu yako yupi anayeweze kukulipia malipo ya matibabu</td>
                    <td width="40%"><input type='text' name='Ni_ndugu_yako_yupi' id='Ni_ndugu_yako_yupi' ></td>
                </tr>
          </table>
      </fieldset>
      <fieldset style="width:92%;">
          <legend>&nbsp;C: UCHUNGUZI</legend>
          <table width=100%>
               <tr>
                   <td style="text-align:right;">Je mgonjwa amewahi kutibiwa mahali pengine?</td>
                           <td width="40%">
                               <select name='amewahi_kutibiwa_mahali' id='amewahi_kutibiwa_mahali' style="padding:4px;width:99%">
                                   <option>Ndio</option>
                                   <option>Hapana</option>
                               </select>
                       </td>
              </tr>
              <tr>
                   <td style="text-align:right;">Je mgonjwa amevaa nguo za thamani?</td>
                           <td width="40%">
                               <select name='amevaa_nguo_za_thamani' id='amevaa_nguo_za_thamani' style="padding:4px;width:99%">
                                   <option>Ndio</option>
                                   <option>Hapana</option>
                               </select>
                       </td>
              </tr>
              <td style="text-align:right;">Kama kuna mengineyo yanayoonyesha uwezo wa kuchangia:Eleza</td>
                    <td width="40%">
                        <textarea rows="8" cols="20" name='mengineyo_yanayoonyesha_uwezo_wa_kuchangia' id='mengineyo_yanayoonyesha_uwezo_wa_kuchangia'></textarea>
                        <!--<input type='text' name='mengineyo_yanayoonyesha_uwezo_wa_kuchangia' id='mengineyo_yanayoonyesha_uwezo_wa_kuchangia' required='required'>-->
                    </td>
          </table>
      </fieldset>
      </div>
      <div style="float:right;width:48%; ">
      <fieldset style="width:92%;">
          <legend>&nbsp;D: MAPENDEKEZO YA MSAMAHA</legend>
          <table width=100%>
                <tr>
                   <td style="text-align:right;">Mapendekezo ya msamaha</td>
                   <td width="40%"><input type='text' name='Mapendekezo_ya_msamaha' id='Mapendekezo_ya_msamaha' ></td>
              </tr>
               <tr>
                   <td style="text-align:right;"><b style='color: red'>Je anastahili kupata msamaha huo?</b></td>
                   <td width="40%"> <select name='anastahili_kupata_msamaha' id='anastahili_kupata_msamaha' style="padding:4px;width:99%" required>
                                   <option>Ndio</option>
                                   <option>Hapana</option>
                               </select></td>
              </tr>
              <tr>
                   <td style="text-align:right;">Jina la anayependekeza msamaha</td>
                   <td width="40%">
                         <input type='hidden' name='anayependekeza_msamaha_id' id='anayependekeza_msamaha_id' readonly value="<?php echo $_SESSION['userinfo']['Employee_ID'];?>">
                     
                       <input type='text' name='anayependekeza_msamaha' id='anayependekeza_msamaha' readonly value="<?php echo $_SESSION['userinfo']['Employee_Name'];?>">
                   </td>
              </tr>
               <tr>
                   <td style="text-align:right;">Cheo</td>
                   <td width="40%">
                       <input type='text' name='cheo_anayependekeza_msamaha' id='cheo_anayependekeza_msamaha' readonly value="<?php echo $_SESSION['userinfo']['Employee_Title'];?>">
                   </td>
              </tr>
              <tr>
                   <td style="text-align:right;"><b style='color: red'>Sahihi</b></td>
                   <td width="40%"><select name='sahihi_anayependekeza_msamaha' required id='sahihi_anayependekeza_msamaha' style="padding:4px;width:99%">
                                   <option>Nimekubali</option>
                                   <option>Sijakubali</option>
                       </select>
                   </td>
              </tr>
          </table>
      </fieldset>
      <fieldset style="width:92%;">
          <legend>&nbsp;E: IMEHADHIMISHWA</legend>
          <table width=100%>
                <tr>
                   <td style="text-align:right;"><b style='color: red'>Imehadhimishwa?</b></td>
                   <td width="40%"><select name='Imehadhimishwa' id='Imehadhimishwa' style="padding:4px;width:99%" required>
                                   <option>Ndio</option>
                                   <option>Hapana</option>
                               </select>
                   </td>
              </tr>
               <tr>
                   <td style="text-align:right;"><b style='color: red'>Jina la anayehadhimisha</b></td>
                   <td width="40%"> <input type='text' name='Jina_la_anayehadhimisha' required id='Jina_la_anayehadhimisha' value="<?= $_SESSION['userinfo']['Employee_Name'] ?>">
                   </td>
              </tr>
             
               <tr>
                   <td style="text-align:right;"><b style='color: red'>Cheo</b></td>
                   <td width="40%"><input type='text' name='cheo_anayehadhimisha' id='cheo' required value="<?= $_SESSION['userinfo']['Department_Name'] ?>"></td>
              </tr>
              <tr>
                   <td style="text-align:right;"><b style='color: red'>Sahihi</b></td>
                   <td width="40%"><select name='sahihi_anayehadhimisha' required id='sahihi_anayehadhimisha' style="padding:4px;width:99%">
                                   <option>Nimekubali</option>
                                   <option>Sijakubali</option>
                       </select>
                   </td>
              </tr>
              <tr>
                   <td style="text-align:right;">Namba katika Rejista ya kupatiwa msamaha wa muda(Kama amesamehewa)</td>
                   <td width="40%"> <input type='text' name='Namba_katika_Rejista_ya_kupatiwa_msamaha' id='Namba_katika_Rejista_ya_kupatiwa_msamaha' >
                   </td>
              </tr>
          </table>
      </fieldset>
      <fieldset style="width:92%;">
          <legend>File Attachment</legend>
          <table width=100%>
                <tr>
                   <td style="text-align:right;">Attach File</td>
                   <td width="40%">
                   
                       <input type="file" name="attach_file" id="attach_file" style="padding:5px;background:white;width:96%   "/>
                   </td>
              </tr>
          </table>
      </fieldset>
        <fieldset style="width:92%;">
          <table width=100%>
               <tr>
                 <td colspan=2 style='text-align:center; ' width="40%">
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' onclick="return check_is_district_selected(),Validate_Date()"class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
									<input type="Reset" name="reset" value="CANCEL" class='art-button-green'>
                                    <input type='hidden' name='submittedAddNewMsamahaPatientForm' value='true'/> 
                                </td>
                            </tr>
                            </table>
      </fieldset>   
      </div>
      <div style="clear:both;">
      
    </div>      
   </div>
  
</div>
    </form>
                        <script>
                            function check_is_district_selected(){
                                var district=$("#District").val();
                                if(district==""){
                                    alert("Select District On patient Registration Tab");
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                        </script>
        <div id="NewMsamaha" style="display: none">
            <input type="text" id="msamahaName" style="padding-left: 5px"> <br /><br /> 
            <center> <input type="button" id="saveMsamaha" class="art-button-green" value="Save"></center>
        </div>
         
</fieldset><br/>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function readImage(input){
	if(input.files && input.files[0]) {
	    var reader = new FileReader();
		reader.onload = function(e){
                    $('#Patient_Picture').attr('src',e.target.result).width('50%').height('70%');
		};
		reader.readAsDataURL(input.files[0]);
	}
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML="<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Employee_Name="+Employee_Name+"'></iframe>";
    }
</script>


<script type="text/javascript" language="javascript">
    function getDistricts() {
    	var Region_Name = document.getElementById("region").value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetDistricts.php?Region_Name='+Region_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('District').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>

<script type="text/javascript">
    function get_Regions(){
        var country = document.getElementById("country").value;

        if (window.XMLHttpRequest) {
            myObjectRegs = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRegs = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegs.overrideMimeType('text/xml');
        }

        myObjectRegs.onreadystatechange = function () {
            dataReg = myObjectRegs.responseText;
            if (myObjectRegs.readyState == 4) {
                document.getElementById('region').innerHTML = dataReg;
                getDistricts();
            }
        }; //specify name of function that will handle server response........
        myObjectRegs.open('GET', 'get_Regions.php?country='+country, true);
        myObjectRegs.send();
    }
</script>

<!--		NHIF VERIFICATION FUNCTION		-->
<script type="text/javascript" language="javascript">
    //get verification button
    function setVerify(sponsor){
	if (sponsor=='NHIF') {
	    document.getElementById('eVerify_btn').style.visibility = "";
	}else{
	    document.getElementById('eVerify_btn').style.visibility = "hidden";
	    document.getElementById("Patient_Name").value = '';
	    document.getElementById("Patient_Name").removeAttribute('readonly');
	    document.getElementById("Employee_Vote_Number").value = '';
	    document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
	    document.getElementById("date").value = '';
	    document.getElementById("date").removeAttribute('disabled');
	    document.getElementById("date2").value = '';
	    document.getElementById("date2").removeAttribute('disabled');
	    document.getElementById("Gender").innerHTML = "<option></option><option>Male</option><option>Female</option>";
	    document.getElementById("Member_Number").setAttribute('style','border-color:default;width: 150px;text-align: left;');
	}
    } 

</script>
<script src="js/token.js"></script>
<script>
    function MemberNumberMandate(sponsor){
        $.ajax({
            url: "./MemberNumberMandateStatus.php?sponsor="+sponsor,
            type: "GET"
        }).done(function(result){
            if( result.replace(" ",'') == "Mandatory"){
                document.getElementById('Member_Number').setAttribute('required','required');
            }else{
                document.getElementById('Member_Number').removeAttribute('required');
            }
        });
    }
</script>
<script>
    $('#addmsamaha').on('click',function(){
        $('#NewMsamaha').dialog({
          modal:true, 
          width:400,
          minHeight:100,
          resizable:true,
          draggable:true, 
          title:"Ongeza Aina Ya Msamaha",
        }); 
    });
    
    $('#saveMsamaha').on('click',function(){
      var msamahaName=$('#msamahaName').val();
      if(msamahaName=='' || msamahaName=='NULL'){
          alert('Andika aina ya msamaha');
          return false;
      }else{
        $.ajax({
        type:'POST',
        url:"requests/Savemsamaha.php",
        data:"action=save&msamahaName="+msamahaName,
         success:function(html){
             $('#displayMsamaha').html(html);
             alert("Successfully added");
             $('#msamahaName').val(''); 
        }
        });
         
      }
    });
</script>
<script>
    function Validate_Date() {
        var Today = new Date(); //current date
        var Date_Of_Birth = new Date(document.getElementById("date2").value);
        var Initial_Date = new Date("1900, 01, 01");
        if(Date_Of_Birth=="Invalid Date"){
           alert("select date of birth")
           return false;
       }
        if (Date_Of_Birth < Initial_Date || Date_Of_Birth > Today) {
            alert("Invalid Date Of Birth");
            document.getElementById("date2").value = '';
            return false;
        }
        return true;
    }
</script>

<?php
    include("./includes/footer.php");
?>
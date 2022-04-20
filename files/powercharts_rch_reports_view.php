



				<script type="text/javascript" src="min.js"></script>
				
				
				
				<script src="ui2\jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="ui2\jquery-ui.css">


<script type='text/javascript'>

$(function() {
	
	//modal box
	
	 $( "#dialog-1" ).dialog({
               autoOpen: false,  
            });
            
               
            
	$("#hudhurio1").show();
	
	$("#hudhurio2").hide();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").hide();
	
	
	$("#b2").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();
	
	$("#hudhurio3").fadeIn();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").hide();
	});
	
	
	
	
	
	$("#b3prev").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").fadeIn();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").hide();
	});
	
	
	$("#b3").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").fadeIn();
	
	$("#hudhurio5").hide();
	});
	
	
	
	$("#b4prev").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();
	
	$("#hudhurio3").fadeIn();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").hide();
	});
	
	
	$("#b4").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").fadeIn();
	});
	
	
	
	
	
	$("#b5").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").hide();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").fadeIn();
	
	$("#hudhurio5").hide();
	});
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	$("#b1").click(function(){
	    $("#hudhurio1").hide();
	    $("#hudhurio2").fadeIn();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").hide();
	});
	
	
	$("#b2prev").click(function(){
	    
	    $("#hudhurio1").fadeIn();
	    $("#hudhurio2").hide();
	
	$("#hudhurio3").hide();
	
	$("#hudhurio4").hide();
	
	$("#hudhurio5").hide();
	
	});
		//Cur status
		
});
</script>	

<?php
   
    include("./includes/connection.php");
   
  
  
  
  if(isSet($_POST['d']))
{
    
  $date1 = $_POST['d'];
  $date2 = $_POST['d2'];
  $selecttype = $_POST['sel'];
  
 
 
  
  
 // 
 //if ($selecttype=='new'){
 //   
 //   $select = mysqli_query($conn,"select pr.Patient_Name as pn,pr.Date_Of_Birth,pr.Member_Number,vs.arv_therapy as the,fv.partiner_name,vs.curr_hiv_status as st,pr.Gender,fv.muda as f from tbl_patient_registration pr,tbl_hiv_first_visit fv,tbl_hiv_visits vs
 //   where pr.registration_id = fv.pr_r AND DATE(muda) >= '$date1' AND DATE(muda)<= '$date2'
 //   AND  fv.hiv_id = vs.first_v_id GROUP BY first_v_id
 //   
 //   ") or die(mysqli_error($conn));
 //

 #start //WEEKS less than 12 weeeks pregnant And  Age <20
 $Weeks_Less_than_12_weeks = "SELECT * FROM tbl_rch WHERE umriwamimba<12 AND age<20 AND DATE(timenow) >= '$date1' AND DATE(timenow)<= '$date2'";
 
 $Age_Less_than_12_weeksresult= mysql_numrows($l=mysqli_query($conn,$Weeks_Less_than_12_weeks));
 
 
 //WEEKS less than 12 weeeks pregnant And  Age >20
 $Weeks_Less_than_12_weeks_Age_Greater20 = "SELECT * FROM tbl_rch WHERE umriwamimba<12 AND age<20 AND DATE(timenow) >= '$date1' AND DATE(timenow)<= '$date2'";
 
 $Age_Less_than_12_weeksresult_greater_than_20= mysql_numrows($l2=mysqli_query($conn,$Weeks_Less_than_12_weeks_Age_Greater20));
 
 $resultplus=$Age_Less_than_12_weeksresult_greater_than_20 + $Age_Less_than_12_weeksresult;
  #End 
  
  
 #start //Age Great than 12 pregnant and age< 20
 $Age_Greater_than_12_weeks_less20 = "SELECT * FROM tbl_rch WHERE umriwamimba>=12 AND age < 20 AND DATE(timenow) >= '$date1' AND DATE(timenow)<= '$date2'";
 
 $Age_Greater_than_12_weeksresult_less20= mysql_numrows($llx=mysqli_query($conn,$Age_Greater_than_12_weeks_less20 ));
 
 
 #Age Great than 12 pregnant and age>= 20
 $Age_Greater_than_12_weeks_greater20 = "SELECT * FROM tbl_rch WHERE umriwamimba>=12 AND age >= 20 AND DATE(timenow) >= '$date1' AND DATE(timenow)<= '$date2'";
 
 $Age_Greater_than_12_weeksresult_greater_20= mysql_numrows($llxx=mysqli_query($conn, $Age_Greater_than_12_weeks_greater20 ));
 $sumofgreater12andless = $Age_Greater_than_12_weeksresult_less20 + $Age_Greater_than_12_weeksresult_greater_20;
 #End
 $sum_and_sum_less20yrs= $Age_Less_than_12_weeksresult +$Age_Greater_than_12_weeksresult_less20;
 
 $sum_and_sum_Greater20yrs= $Age_Greater_than_12_weeksresult_greater_20+$Age_Less_than_12_weeksresult_greater_than_20;
 
 $summation =$sum_and_sum_less20yrs + $sum_and_sum_Greater20yrs;
 
 ///EndendendEnd
 
 
 
 #Revisit customer With age less than 20
 
 $Revisit_Customer_With_Age_Less20 = "SELECT COUNT(tvs.rch_id) as coun, tvs.rch_id,tvs.vdate,tr.age,tr.rch_id FROM tbl_rch_visits tvs ,tbl_rch tr WHERE tr.age < 20 AND (DATE(tvs.vdate) >= '$date1' AND DATE(tvs.vdate)<= '$date2') AND tr.rch_id=tvs.rch_id GROUP BY tvs.rch_id HAVING coun > 1";
 
  $Revisit_Customer_With_Age_Less20_Result= mysql_numrows($llxxl=mysqli_query($conn,$Revisit_Customer_With_Age_Less20));
  
  
  #Revisit customer With age as or > than 20
 
 $Revisit_Customer_With_Age_Greater20 = "SELECT COUNT(tvs.rch_id) as coun, tvs.rch_id,tvs.vdate,tr.age,tr.rch_id FROM tbl_rch_visits tvs ,tbl_rch tr WHERE tr.age >= 20 AND (DATE(tvs.vdate) >= '$date1' AND DATE(tvs.vdate)<= '$date2') AND tr.rch_id=tvs.rch_id GROUP BY tvs.rch_id HAVING coun > 1";
 
  $Revisit_Customer_With_Age_Greater20_Result= mysql_numrows($llxxly=mysqli_query($conn,$Revisit_Customer_With_Age_Greater20));
  
  #Total of revisit
  
  $Revisit_Total = $Revisit_Customer_With_Age_Greater20_Result + $Revisit_Customer_With_Age_Less20_Result;
  
  #End
  
  #Total preg woman who measured blood
  
  
  
  $_measured_Customer_With_Age_Greater20 = "SELECT kiwangochadamu FROM tbl_rch tr WHERE  (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND kiwangochadamu > 0 AND age >= 20";
 
  $_Measured_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyf=mysqli_query($conn,$_measured_Customer_With_Age_Greater20));
  
  
  
   $_measured_Customer_With_Age_Less20 = "SELECT kiwangochadamu FROM tbl_rch tr WHERE  (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND kiwangochadamu > 0 AND age < 20";
 
  $_Measured_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyfo=mysqli_query($conn,$_measured_Customer_With_Age_Less20));
  $_Measured_Customer_With_Age_Less20_Total = $_Measured_Customer_With_Age_Greater20_Result + $_Measured_Customer_With_Age_Less20_Result;
  
  #End
  
  
  #Starts Chanjo ya TT2*
  $TT2_Customer_With_Age_Greater20 = "SELECT tr.age,COUNT(tt.rch_idtt) AS f,tt.tmenowtt,tr.age,tr.rch_id FROM tbl_rch_tt tt LEFT JOIN tbl_rch tr ON tr.rch_id=tt.rch_idtt WHERE tr.age >= 20 AND (DATE(tt.tmenowtt) >= '$date1' AND DATE(tt.tmenowtt)<= '$date2') GROUP BY tt.rch_idtt HAVING f >= 2";
 
  $TT2_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyy=mysqli_query($conn,$TT2_Customer_With_Age_Greater20 ));
  
  $TT2_Customer_With_Age_Less20 = 
 "SELECT tr.age,COUNT(tt.rch_idtt) AS f,tt.tmenowtt,tr.age,tr.rch_id FROM tbl_rch_tt tt LEFT JOIN tbl_rch tr ON tr.rch_id=tt.rch_idtt WHERE tr.age < 20 AND (DATE(tt.tmenowtt) >= '$date1' AND DATE(tt.tmenowtt)<= '$date2') GROUP BY tt.rch_idtt HAVING f >=2";

  $TT2_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyyz=mysqli_query($conn,$TT2_Customer_With_Age_Less20 ));
  
  #Total of tt
  
  $TTsum = $TT2_Customer_With_Age_Greater20_Result+ $TT2_Customer_With_Age_Less20_Result;
  
  #End
  
  
  
  
  
   #Starts More than 4 Pregnants
  $Preg4_Customer_With_Age_Greater20 = "SELECT tr.age,tm.time,tm.mimbazaidiya4,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age >= 20 AND tm.mimbazaidiya4 = 'ndiyom4' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $Preg4_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyyc=mysqli_query($conn,$Preg4_Customer_With_Age_Greater20 ));
  
 $Preg4_Customer_With_Age_Less20_Result =0;
 
 //
 //#4 preg < 20 yrs
 // $Preg4_Customer_With_Age_Less20 = "SELECT tr.age,tm.time,tm.mimbazaidiya4,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age < 20 AND tm.mimbazaidiya4 = 'ndiyom4' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 //
 // $Preg4_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyyzu=mysqli_query($conn,$Preg4_Customer_With_Age_Less20 ));
 // 
 // #Total of tt
 // 
 // $Preg4sum =  $Preg4_Customer_With_Age_Less20_Result+ $Preg4_Customer_With_Age_Greater20_Result;
 // 
 // #End
  
  
  
  
  
 #age< 20 yrs
  $Age_Less20 = "SELECT tr.age,tvs.vdate,tr.rch_id FROM tbl_rch_visits tvs LEFT JOIN tbl_rch tr ON tr.rch_id=tvs.visit_id WHERE tr.age < 20  AND (DATE(tvs.vdate) >= '$date1' AND DATE(tvs.vdate)<= '$date2')";
 
 $Age_Less20_Result= mysql_numrows($llxxlyyzux=mysqli_query($conn,$Age_Less20));
 
 
 
 
  
 #Age >35
  $Age_Greater35 = "SELECT tr.age,tvs.vdate,tr.rch_id FROM tbl_rch_visits tvs LEFT JOIN tbl_rch tr ON tr.rch_id=tvs.visit_id WHERE tr.age > 35  AND (DATE(tvs.vdate) >= '$date1' AND DATE(tvs.vdate)<= '$date2')";
 
 $Age_Greater35_Result= mysql_numrows($llxxlyyzuxn=mysqli_query($conn,$Age_Greater35));
 
 
 
 
 #Select all with anaemia and age > 20
 
 
  
  $_Anaemia_Customer_With_Age_Greater20 = "SELECT kiwangochadamu FROM tbl_rch tr WHERE  (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND kiwangochadamu <8.5 AND age >= 20";
 
  $_Anaemia_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyfui=mysqli_query($conn,$_Anaemia_Customer_With_Age_Greater20));
  
  
  
   $_Anaemia_Customer_With_Age_Less20 = "SELECT kiwangochadamu FROM tbl_rch tr WHERE  (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND kiwangochadamu < 8.5 AND age < 20";
 
  $_Anaemia_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyfo=mysqli_query($conn,$_Anaemia_Customer_With_Age_Less20 ));
  $Anaemia_Customer_Total = $_Anaemia_Customer_With_Age_Greater20_Result +  $_Anaemia_Customer_With_Age_Less20_Result;
  
  #End
  
  
  
  
  
  
  #BP Pressure
 
 
  
  $_Bp_Customer_With_Age_Greater20 = "SELECT tr.age,tm.time,tm.bp,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age >= 20 AND tm.bp = 'ndiyobp' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $_Bp_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyfui=mysqli_query($conn,$_Bp_Customer_With_Age_Greater20));
  
  
  
   $_Bp_Customer_With_Age_Less20 = "SELECT tr.age,tm.time,tm.bp,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age < 20 AND tm.bp = 'ndiyobp' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $_Bp_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyfo=mysqli_query($conn,$_Bp_Customer_With_Age_Less20 ));
  $Bp_Customer_Total = $_Bp_Customer_With_Age_Greater20_Result +  $_Bp_Customer_With_Age_Less20_Result;
  
  #End
  
  
  #Kifua Kikuu
  
   $Tb_Customer_With_Age_Greater20 = "SELECT tr.age,tm.time,tm.tb,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age >= 20 AND tm.tb = 'ndiyotb' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $Tb_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyfuix=mysqli_query($conn,$Tb_Customer_With_Age_Greater20));
  
  
  
   $Tb_Customer_With_Age_Less20 = "SELECT tr.age,tm.time,tm.tb,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age < 20 AND tm.tb = 'ndiyotb' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $Tb_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyfo=mysqli_query($conn,$Tb_Customer_With_Age_Less20 ));
  $Tb_Customer_Total = $Tb_Customer_With_Age_Greater20_Result +  $Tb_Customer_With_Age_Less20_Result;
  
  #End
  
  
  
  #Sukari Mkojoni
  
   $Sukari_Customer_With_Age_Greater20 = "SELECT tr.age,tr.sukarimkojoni,tr.rch_id FROM tbl_rch tr WHERE tr.age >= 20 AND tr.sukarimkojoni = 'anasukari' AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Sukari_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyfuixrk=mysqli_query($conn,$Sukari_Customer_With_Age_Greater20));
  
  
  
   $Sukari_Customer_With_Age_Less20 = "SELECT tr.age,tr.sukarimkojoni,tr.rch_id FROM tbl_rch tr WHERE tr.age < 20 AND tr.sukarimkojoni = 'anasukari' AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
  $Sukari_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyfoopm=mysqli_query($conn,$Sukari_Customer_With_Age_Less20));
  
  $Sukari_Customer_Total = $Sukari_Customer_With_Age_Greater20_Result +  $Sukari_Customer_With_Age_Less20_Result;
  
  #End
  
  
  
  
  
   #Protein kwenye mkojo
  
   $Protein_Customer_With_Age_Greater20 = "SELECT tr.age,tm.time,tm.protenuria,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age >= 20 AND tm.protenuria = 'ndiyoprotenuria' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $Protein_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfuixui=mysqli_query($conn,$Protein_Customer_With_Age_Greater20));
  
  
  
   $Protein_Customer_With_Age_Less20 = "SELECT tr.age,tm.time,tm.protenuria,tr.rch_id FROM tbl_rch_mahudhurio tm LEFT JOIN tbl_rch tr ON tr.rch_id=tm.rch_idm WHERE tr.age < 20 AND tm.protenuria = 'ndiyoprotenuria' AND (DATE(tm.time) >= '$date1' AND DATE(tm.time)<= '$date2')";
 
  $Protein_Customer_With_Age_Less20_Result= mysql_numrows($llxxlyfop=mysqli_query($conn,$Protein_Customer_With_Age_Less20 ));
  $Protein_Customer_Total = $Protein_Customer_With_Age_Greater20_Result +  $Protein_Customer_With_Age_Less20_Result;
  
  #End
  
  
  #All who measured Syphills
  
  $Syphils_Customer_With_Age_Greater20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfuixuil=mysqli_query($conn,$Syphils_Customer_With_Age_Greater20));
  
  $Syphils_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Less20_Result = mysql_numrows($llxxlyfuixuilh=mysqli_query($conn,$Syphils_Customer_With_Age_Less20));
  
  $Syphils_Total=$Syphils_Customer_With_Age_Greater20_Result + $Syphils_Customer_With_Age_Less20_Result;
  #end
  
  
   #All who measured Syphills positive
  
  $Syphils_Customer_With_Age_Greater20positive = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age >= 20 AND ts.matokeoke='positive' AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Greater20_Resultpositive = mysql_numrows($llxxlyfuixuilp=mysqli_query($conn,$Syphils_Customer_With_Age_Greater20positive));
  
  
  
  
  $Syphils_Customer_With_Age_Less20positive = "SELECT ts.matokeoke,tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age < 20 AND ts.matokeoke='positive' AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Less20_Resultpositive = mysql_numrows($llxxlyfuixuilhuo=mysqli_query($conn,$Syphils_Customer_With_Age_Less20positive));
  
  $Syphils_Totalpositive=$Syphils_Customer_With_Age_Greater20_Resultpositive + $Syphils_Customer_With_Age_Less20_Resultpositive;
  
  
  #End
  
  
   #All who Treated Syphills positive
  
  $Syphils_Customer_With_Age_Greater20treated = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age >= 20 AND ts.kaswtibake='ametibiwa' AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Greater20_Resulttreated = mysql_numrows($llxxlyfuixuilph=mysqli_query($conn,$Syphils_Customer_With_Age_Greater20treated));
  
  
  
  
  $Syphils_Customer_With_Age_Less20treated ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age < 20 AND ts.kaswtibake='ametibiwa' AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Less20_Resulttreated = mysql_numrows($llxxlyfuixuilhuox=mysqli_query($conn,$Syphils_Customer_With_Age_Less20treated));
  
  $Syphils_Totaltreated=$Syphils_Customer_With_Age_Greater20_Resulttreated + $Syphils_Customer_With_Age_Less20_Resulttreated;
  
  
  
  
  #All who measured Syphills men 
  
  $Syphils_Customer_With_Age_Greater20me = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Greater20_Resultme = mysql_numrows($llxxlyfuixuilp=mysqli_query($conn,$Syphils_Customer_With_Age_Greater20me));
  
  $Syphils_Customer_With_Age_Less20me = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2')";
 
  $Syphils_Customer_With_Age_Less20_Resultme = mysql_numrows($llxxlyfuixuilh=mysqli_query($conn,$Syphils_Customer_With_Age_Less20me));
  
  $Syphils_Totalme=$Syphils_Customer_With_Age_Greater20_Resultme + $Syphils_Customer_With_Age_Less20_Resultme;
  #end
  
  
  #All who measured Syphills men positive
  
  $Syphils_Customer_With_Age_Greater20mepositive = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ts.matokeome='positive'";
 
  $Syphils_Customer_With_Age_Greater20_Resultmepositive = mysql_numrows($llxxlyfuixuilpxx=mysqli_query($conn,$Syphils_Customer_With_Age_Greater20mepositive));
  
  $Syphils_Customer_With_Age_Less20mepositive = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ts.matokeome='positive'";
 
  $Syphils_Customer_With_Age_Less20_Resultmepositive = mysql_numrows($llxxlyfuixuilhc=mysqli_query($conn,$Syphils_Customer_With_Age_Less20mepositive));
  
  $Syphils_Totalmepositive=$Syphils_Customer_With_Age_Greater20_Resultmepositive + $Syphils_Customer_With_Age_Less20_Resultmepositive;
  #end
  
  
  #All who Syphills men treated
  
  $Syphils_Customer_With_Age_Greater20metreated = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ts.kaswtibame='ametibiwa'";
 
  $Syphils_Customer_With_Age_Greater20_Resultmetreated = mysql_numrows($llxxlyfuicxuilbnpxx=mysqli_query($conn,$Syphils_Customer_With_Age_Greater20metreated));
  
  $Syphils_Customer_With_Age_Less20metreated = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_syphil_disease ts LEFT JOIN tbl_rch tr ON tr.rch_id=ts.rch_idk WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ts.kaswtibame='ametibiwa'";
 
  $Syphils_Customer_With_Age_Less20_Resultmetreated = mysql_numrows($llxxlyfuixvucimnlhc=mysqli_query($conn,$Syphils_Customer_With_Age_Less20metreated));
  
  $Syphils_Totalmetreated=$Syphils_Customer_With_Age_Greater20_Resultmetreated + $Syphils_Customer_With_Age_Less20_Resultmetreated;
  #end
  
  
  #Stds for women
  
  $Std_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngomatokeoke='positive'";
 
  $Std_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfuicxuilbnpbvxxl=mysqli_query($conn,$Std_Customer_With_Age_Greater20));
  
  $Std_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngomatokeoke='positive'";
 
  $Std_Customer_With_Age_Less20_Result = mysql_numrows($llxxlyfuixvucimnlhkoc=mysqli_query($conn,$Std_Customer_With_Age_Less20));
  
  $Std_Total=$Std_Customer_With_Age_Greater20_Result + $Std_Customer_With_Age_Less20_Result;
  #end
  
#stds treated
  
  $Std_Customer_With_Age_Greater20treated ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngoketiba='ngonofeametibiwa'";
 
  $Std_Customer_With_Age_Greater20_Resulttreated = mysql_numrows($llxxlyfuicxuilbnpbvxxgfl=mysqli_query($conn,$Std_Customer_With_Age_Greater20treated));
  
  $Std_Customer_With_Age_Less20treated = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngoketiba='ngonofeametibiwa'";
 
  $Std_Customer_With_Age_Less20_Resulttreated = mysql_numrows($llxxlyfuixvucimnlhfdkoc=mysqli_query($conn,$Std_Customer_With_Age_Less20treated));
  
  $Std_Totaltreated=$Std_Customer_With_Age_Greater20_Resulttreated + $Std_Customer_With_Age_Less20_Resulttreated;
  #end
    
   #Stds for men
  
  $Std_Customer_With_Age_Greater20me ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngomatokeome='positive'";
 
  $Std_Customer_With_Age_Greater20_Resultme = mysql_numrows($llxxlyfuicxuilbnpbvxxlgf=mysqli_query($conn,$Std_Customer_With_Age_Greater20me));
  
  $Std_Customer_With_Age_Less20me = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngomatokeome='positive'";
 
  $Std_Customer_With_Age_Less20_Resultme = mysql_numrows($llxxlyfuixvucimnlhkoc=mysqli_query($conn,$Std_Customer_With_Age_Less20me));
  
  $Std_Totalme=$Std_Customer_With_Age_Greater20_Resultme + $Std_Customer_With_Age_Less20_Resultme;
  #end
  
  
  
  #stds treated men
  
  $Std_Customer_With_Age_Greater20treatedme ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngometiba='ngonomeametibiwa'";
 
  $Std_Customer_With_Age_Greater20_Resulttreatedme = mysql_numrows($llxxlyfuicxuilbnpxcbvxxgfld=mysqli_query($conn,$Std_Customer_With_Age_Greater20treatedme));
  
  $Std_Customer_With_Age_Less20treatedme = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch_stds ss LEFT JOIN tbl_rch tr ON tr.rch_id=ss.rch_id WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ss.ngometiba='ngonomeametibiwa'";
 
  $Std_Customer_With_Age_Less20_Resulttreatedme = mysql_numrows($llxxlyfuixvucimnlhfdkocd=mysqli_query($conn,$Std_Customer_With_Age_Less20treatedme));
  
  $Std_Totaltreatedme=$Std_Customer_With_Age_Greater20_Resulttreatedme + $Std_Customer_With_Age_Less20_Resulttreatedme;
  #end
  
  
  
  #PMCT  Positive Before Attending Clinic
  $Pmct_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.vvureadyke='feanamaambukizi'";
 
  $Pmct_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfuicxuilbnpxcbvpmcxxgfld=mysqli_query($conn,$Pmct_Customer_With_Age_Greater20));
  
  $Pmct_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.vvureadyke='feanamaambukizi'";
 
  $Pmct_Customer_With_Age_Less20_Result = mysql_numrows($llxxlyfuixvucimnlhfdkopmccd=mysqli_query($conn,$Pmct_Customer_With_Age_Less20));
  
  $Pmct_Total=$Pmct_Customer_With_Age_Greater20_Result + $Pmct_Customer_With_Age_Less20_Result;
  #End
  
  
  
  #PMCT  Those who got Councel before 
  $PmctCouncel_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.unasihike='feamepataunasihi'";
 
  $PmctCouncel_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictgxuilbnpxcbvpmcxxgfld=mysqli_query($conn,$PmctCouncel_Customer_With_Age_Greater20));
  
  $PmctCouncel_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.unasihike='feamepataunasihi'";
 
  $PmctCouncel_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfureixvucimnlhfdkopmccd=mysqli_query($conn,$PmctCouncel_Customer_With_Age_Less20));
  
  $PmctCouncel_Total=$PmctCouncel_Customer_With_Age_Greater20_Result + $PmctCouncel_Customer_With_Age_Less20_Result;
  #End
  
  
  #PMCT  Those who got first Test at Clinic
  $PmctFirstTest_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.amepimake='amepimavvufe'";
 
  $PmctFirstTest_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictgxuilbnpxcbvpmcxxkogfld=mysqli_query($conn,$PmctFirstTest_Customer_With_Age_Greater20));
  
  $PmctFirstTest_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.amepimake='amepimavvufe'";
 
  $PmctFirstTest_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfureixvucimnlhfdkopplmccd=mysqli_query($conn,$PmctFirstTest_Customer_With_Age_Less20));
  
  $PmctFirstTest_Total=$PmctFirstTest_Customer_With_Age_Greater20_Result + $PmctFirstTest_Customer_With_Age_Less20_Result;
  #End
  
   #PMCT  Those who got first Test at Clinic and Positive.
  $PmctFirstTestPositive_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip1ke='positivekipimo1fe'";
 
  $PmctFirstTestPositive_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictgxuilbnpsdxcbvpmcxxkogfld=mysqli_query($conn,$PmctFirstTestPositive_Customer_With_Age_Greater20));
  
  $PmctFirstTestPositive_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip1ke='positivekipimo1fe'";
 
  $PmctFirstTestPositive_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfureixvucimnlhfdkopgfplmccd=mysqli_query($conn,$PmctFirstTestPositive_Customer_With_Age_Less20));
  
  $PmctFirstTestPositive_Total=$PmctFirstTestPositive_Customer_With_Age_Greater20_Result + $PmctFirstTestPositive_Customer_With_Age_Less20_Result;
  #End
  
  
  #PMCT  Those who got first Test at Clinic and Positive and age less than 25
  $PmctFirstTestPositive_Customer_With_Age_Greater20less25 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE (tr.age >= 20 AND tr.age <25) AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip1ke='positivekipimo1fe'";
 
  $PmctFirstTestPositive_Customer_With_Age_Greater20_Resultless25 = mysql_numrows($llxxlyfudsictgxuilbnpsdxcbvpmcxxkplkogfld=mysqli_query($conn,$PmctFirstTestPositive_Customer_With_Age_Greater20less25));
  
  
  $PmctFirstTestPositive_Customer_With_Age_Less20less25 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip1ke='positivekipimo1fe'";
 
  $PmctFirstTestPositive_Customer_With_Age_Less20_Resultless25 = mysql_numrows($llxxlfgyfureixvucimnlhfdkopgfplmccd=mysqli_query($conn,$PmctFirstTestPositive_Customer_With_Age_Less20less25));
  
  $PmctFirstTestPositive_Totalless25=$PmctFirstTestPositive_Customer_With_Age_Greater20_Resultless25 + $PmctFirstTestPositive_Customer_With_Age_Less20_Resultless25;
  #End
  
  
  
  #PMCT  Those who got Councel After Measure
  $PmctCouncelAfter_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20  AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.unasihibaadake='amepataunasihibaadafe'";
 
  $PmctCouncelAfter_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictgxuilbnpsdxcbvpymcxxkplkogferld=mysqli_query($conn,$PmctCouncelAfter_Customer_With_Age_Greater20));
  
  
  $PmctCouncelAfter_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.unasihibaadake='amepataunasihibaadafe'";
 
  $PmctCouncelAfter_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfureixvucimnlhfdkopopgfplmccd=mysqli_query($conn,$PmctCouncelAfter_Customer_With_Age_Less20));
  
  $PmctCouncelAfter_Total=$Pmct_Customer_With_Age_Greater20_Result + $PmctCouncelAfter_Customer_With_Age_Less20_Result;
  #End
  
  
  
   
  #PMCT  Those who got Measure WITH THEIR Partiners
  $PmctPartiner_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND (pm.amepimake='amepimavvufe' AND pm.amepimame='amepimavvume')";
 
  $PmctPartiner_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictggxuilbnpsdxcbvpymcxxkplkogferld=mysqli_query($conn,$PmctPartiner_Customer_With_Age_Greater20));
  
  
  $PmctPartiner_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.unasihibaadake='amepataunasihibaadafe'";
 
  $PmctPartiner_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfurecvixvucimnlhfdkopopgfplmccd=mysqli_query($conn,$PmctPartiner_Customer_With_Age_Less20));
  
  $PmctPartiner_Total=$PmctPartiner_Customer_With_Age_Greater20_Result + $PmctPartiner_Customer_With_Age_Less20_Result;
  #End
  
  
  
  
  #PMCT  Those who got Measure Secondly
  $PmctSecond_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip2ke <> 'hajapima'";
 
  $PmctSecond_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictggx=mysqli_query($conn,$PmctSecond_Customer_With_Age_Greater20));
  
  
  $PmctSecond_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip2ke <> 'hajapima'";
 
  $PmctSecond_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfurecvixmk=mysqli_query($conn,$PmctSecond_Customer_With_Age_Less20));
  
  $PmctSecond_Total = $PmctSecond_Customer_With_Age_Greater20_Result + $PmctSecond_Customer_With_Age_Less20_Result;
  #End
  
  #PMCT  Those who got Measure Secondly Positive
  $PmctSecondPositive_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip2ke = 'positivekipimo2fe'";
 
  $PmctSecondPositive_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictggx=mysqli_query($conn,$PmctSecondPositive_Customer_With_Age_Greater20));
  
  
  $PmctSecondPositive_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.matokeokip2ke = 'positivekipimo2fe'";
 
  $PmctSecondPositive_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfgyfurecvixmk=mysqli_query($conn,$PmctSecondPositive_Customer_With_Age_Less20));
  
  $PmctSecondPositive_Total = $PmctSecondPositive_Customer_With_Age_Greater20_Result + $PmctSecondPositive_Customer_With_Age_Less20_Result;
  #End
  
  
  
  #PMCT  Those Who Measured With Their Husband
  
  $PmctTestedPartiner_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.amepimame = 'amepimavvume'";
 
  $PmctTestedPartiner_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictglpgx=mysqli_query($conn,$PmctTestedPartiner_Customer_With_Age_Greater20));
  
  
  $PmctTestedPartiner_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.amepimame = 'amepimavvume'";
 
  $PmctTestedPartiner_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfkjgyfurecvixmk=mysqli_query($conn,$PmctTestedPartiner_Customer_With_Age_Less20));
  
  $PmctTestedPartiner_Total = $PmctTestedPartiner_Customer_With_Age_Greater20_Result + $PmctTestedPartiner_Customer_With_Age_Less20_Result;
  #End
  
  
  #PMCT  Those Partiner Tested and Positive
  
  $PmctTestedPartinerPositive_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.amepimame = 'amepimavvume' AND pm.matokeokip1me = 'positivekipimo1me'";
 
  $PmctTestedPartinerPositive_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictgoplpgx=mysqli_query($conn,$PmctTestedPartinerPositive_Customer_With_Age_Greater20));
  
  
  $PmctTestedPartinerPositive_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND pm.amepimame = 'amepimavvume' AND pm.matokeokip1me='positivekipimo1me'";
 
  $PmctTestedPartinerPositive_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfkjgyfurecopvixmk=mysqli_query($conn,$PmctTestedPartinerPositive_Customer_With_Age_Less20));
  
  $PmctTestedPartinerPositive_Total = $PmctTestedPartinerPositive_Customer_With_Age_Greater20_Result + $PmctTestedPartinerPositive_Customer_With_Age_Less20_Result;
  #End
  
  
  #PMCT  Those Partiner Tested AND Results Are Discorant
  
  $PmctTestedDiscorant_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ((pm.matokeokip1me = 'positivekipimo1me' AND pm.matokeokip1ke = 'negativekipimo1fe') OR (pm.matokeokip1me = 'negativekipimo1me' AND pm.matokeokip1ke = 'positivekipimo1fe'))";
 
  $PmctTestedDiscorant_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsictiof=mysqli_query($conn,$PmctTestedDiscorant_Customer_With_Age_Greater20));
  
  
  $PmctTestedDiscorant_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_pmct pm LEFT JOIN tbl_rch tr ON tr.rch_id=pm.rch_id_pmct WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND ((pm.matokeokip1me = 'positivekipimo1me' AND pm.matokeokip1ke = 'negativekipimo1fe') OR (pm.matokeokip1me = 'negativekipimo1me' AND pm.matokeokip1ke = 'positivekipimo1fe'))";
 
  $PmctTestedDiscorant_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfkjgyfurecopvixmk=mysqli_query($conn,$PmctTestedDiscorant_Customer_With_Age_Less20));
  
  $PmctTestedDiscorant_Total = $PmctTestedDiscorant_Customer_With_Age_Greater20_Result + $PmctTestedDiscorant_Customer_With_Age_Less20_Result;
  #End
  
  
  
  #PMCT THOSE WHO GOT FEEDING COUNCEL
  
  $PmctFeedCouncel_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.ulishajiushauri='Amepataushauri'";
 
  $PmctFeedCouncel_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfudsicfdtiof=mysqli_query($conn,$PmctFeedCouncel_Customer_With_Age_Greater20));
  
  
  $PmctFeedCouncel_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.ulishajiushauri='Amepataushauri'";
 
  $PmctFeedCouncel_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfkjgyopfurecopvixmk=mysqli_query($conn,$PmctFeedCouncel_Customer_With_Age_Less20));
  
  $PmctFeedCouncel_Total = $PmctFeedCouncel_Customer_With_Age_Greater20_Result + $PmctFeedCouncel_Customer_With_Age_Less20_Result;
  #End
  
  
  #MALARIA THOSE WHO GOT HATI PUNGUZO
  
  $MalariaHatipunguzo_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.hati='Amepatahati'";
 
  $MalariaHatipunguzo_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfuUIOdsicfdtiof=mysqli_query($conn,$MalariaHatipunguzo_Customer_With_Age_Greater20));
  
  
  $MalariaHatipunguzo_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.hati='Amepatahati'";
 
  $MalariaHatipunguzo_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfkjgyoOPpfurecopvixmk=mysqli_query($conn,$MalariaHatipunguzo_Customer_With_Age_Less20));
  
  $MalariaHatipunguzo_Total = $MalariaHatipunguzo_Customer_With_Age_Greater20_Result + $MalariaHatipunguzo_Customer_With_Age_Less20_Result;
  #End
  
  
  #MALARIA THOSE WHO POSITIVE
  
  $MalariaPositive_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.malaria='positive'";
 
  $MalariaPositive_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfuUIOlpdsicfdtiof=mysqli_query($conn,$MalariaPositive_Customer_With_Age_Greater20));
  
  
  $MalariaPositive_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.malaria='positive'";
 
  $MalariaPositive_Customer_With_Age_Less20_Result = mysql_numrows($llxxlfkjpigyoOPpfurecopvixmk=mysqli_query($conn,$MalariaPositive_Customer_With_Age_Less20));
  
  $MalariaPositive_Total = $MalariaPositive_Customer_With_Age_Greater20_Result + $MalariaPositive_Customer_With_Age_Less20_Result;
  #End
  
   #Count those with IPT 2
  $IPT2_Customer_With_Age_Greater20 = "SELECT tr.age,COUNT(ipt.rch_id_ipt) AS fl,ipt.timenowipt,tr.age,tr.rch_id FROM tbl_rch_ipt ipt LEFT JOIN tbl_rch tr ON tr.rch_id=ipt.rch_id_ipt WHERE tr.age >= 20 AND (DATE(ipt.timenowipt) >= '$date1' AND DATE(ipt.timenowipt)<= '$date2') AND ipt_date <> '0000-00-00' GROUP BY ipt.rch_id_ipt HAVING fl = 2";
 
  $IPT2_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyOyPIy=mysqli_query($conn,$IPT2_Customer_With_Age_Greater20 ));
  
  $IPT2_Customer_With_Age_Less20 = 
 "SELECT tr.age,COUNT(ipt.rch_id_ipt) AS flo,ipt.timenowipt,tr.age,tr.rch_id FROM tbl_rch_ipt ipt LEFT JOIN tbl_rch tr ON tr.rch_id=ipt.rch_id_ipt WHERE tr.age < 20 AND (DATE(ipt.timenowipt) >= '$date1' AND DATE(ipt.timenowipt)<= '$date2') AND ipt_date <> '0000-00-00' GROUP BY ipt.rch_id_ipt HAVING flo = 2";
 
  $IPT2_Customer_With_Age_Less20_Result= mysql_numrows($llxxIOHlyyyz=mysqli_query($conn,$IPT2_Customer_With_Age_Less20 ));
  
  #Total of tt
  
  $IPT2sum = $IPT2_Customer_With_Age_Greater20_Result+ $IPT2_Customer_With_Age_Less20_Result;
  
  #End
  
  
  
  #Count those with IPT 4
  $IPT4_Customer_With_Age_Greater20 = "SELECT tr.age,COUNT(ipt.rch_id_ipt) AS fl,ipt.timenowipt,tr.age,tr.rch_id FROM tbl_rch_ipt ipt LEFT JOIN tbl_rch tr ON tr.rch_id=ipt.rch_id_ipt WHERE tr.age >= 20 AND (DATE(ipt.timenowipt) >= '$date1' AND DATE(ipt.timenowipt)<= '$date2') AND ipt_date <> '0000-00-00' GROUP BY ipt.rch_id_ipt HAVING fl = 4";
 
  $IPT4_Customer_With_Age_Greater20_Result= mysql_numrows($llxxlyOyPpIy=mysqli_query($conn,$IPT4_Customer_With_Age_Greater20 ));
  
  $IPT4_Customer_With_Age_Less20 = 
 "SELECT tr.age,COUNT(ipt.rch_id_ipt) AS flo,ipt.timenowipt,tr.age,tr.rch_id FROM tbl_rch_ipt ipt LEFT JOIN tbl_rch tr ON tr.rch_id=ipt.rch_id_ipt WHERE tr.age < 20 AND (DATE(ipt.timenowipt) >= '$date1' AND DATE(ipt.timenowipt)<= '$date2') AND ipt_date <> '0000-00-00' GROUP BY ipt.rch_id_ipt HAVING flo = 4";
 
  $IPT4_Customer_With_Age_Less20_Result= mysql_numrows($llxhjxIOHlyyyz=mysqli_query($conn,$IPT4_Customer_With_Age_Less20 ));
  
  #Total of tt
  
  $IPT4sum = $IPT4_Customer_With_Age_Greater20_Result+ $IPT4_Customer_With_Age_Less20_Result;
  
  #End
  
  
  #Count those with IFA
  $IFA_Customer_With_Age_Greater20 = "SELECT tr.age,COUNT(ifa.rch_ifa_id) AS fla,ifa.tme_nowifa,tr.age,tr.rch_id FROM tbl_rch_ifa ifa LEFT JOIN tbl_rch tr ON tr.rch_id=ifa.rch_ifa_id WHERE tr.age >= 20 AND (DATE(ifa.tme_nowifa) >= '$date1' AND DATE(ifa.tme_nowifa)<= '$date2') AND ifa_amount <> '0' GROUP BY ifa.rch_ifa_id HAVING fla > 1";
 
  $IFA_Customer_With_Age_Greater20_Result= mysql_numrows($llxxdlyOyPFpIy=mysqli_query($conn,$IFA_Customer_With_Age_Greater20 ));
  
  $IFA_Customer_With_Age_Less20 = 
"SELECT tr.age,COUNT(ifa.rch_ifa_id) AS fla,ifa.tme_nowifa,tr.age,tr.rch_id FROM tbl_rch_ifa ifa LEFT JOIN tbl_rch tr ON tr.rch_id=ifa.rch_ifa_id WHERE tr.age < 20 AND (DATE(ifa.tme_nowifa) >= '$date1' AND DATE(ifa.tme_nowifa)<= '$date2') AND ifa_amount <> '0' GROUP BY ifa.rch_ifa_id HAVING fla > 1";
 
  $IFA_Customer_With_Age_Less20_Result= mysql_numrows($llxhjxIOHlyFdyyz=mysqli_query($conn,$IFA_Customer_With_Age_Less20 ));
  
  #Total of tt
  
  $IFAsum = $IFA_Customer_With_Age_Greater20_Result+ $IFA_Customer_With_Age_Less20_Result;
  
  #End
  
  
  #Albendazole
  
  $Albendazole_Customer_With_Age_Greater20 ="SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age >= 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.albendazole='amepataalbend'";
 
  $Albendazole_Customer_With_Age_Greater20_Result = mysql_numrows($llxxlyfusUIOlpdsicfdtiof=mysqli_query($conn,$Albendazole_Customer_With_Age_Greater20));
  
  
  $Albendazole_Customer_With_Age_Less20 = "SELECT tr.age,tr.timenow,tr.rch_id FROM tbl_rch tr WHERE tr.age < 20 AND (DATE(tr.timenow) >= '$date1' AND DATE(tr.timenow)<= '$date2') AND tr.albendazole='amepataalbend'";
 
  $Albendazole_Customer_With_Age_Less20_Result = mysql_numrows($llxxldfkjpigyoOPpfurecopvixmk=mysqli_query($conn,$Albendazole_Customer_With_Age_Less20));
  
  $Albendazole_Total = $Albendazole_Customer_With_Age_Greater20_Result + $Albendazole_Customer_With_Age_Less20_Result;
  #End
  
  
  
  
  
  
 echo "<div id='all'>";
  echo"<a class='art-button-green' style='float:right;'>Print</a>";
  echo "<div id='hudhurio1'>";
    echo "<table style='width:100%'>";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < Miaka 20</th><th>Umri Miaka 20 na zaidi</th><th>Jumla</th></tr>";
    
     echo "<tr><td style='text-align:center'>1</td><td>Idadi ya Wajawazito waliotegemewa</td><td><input type='text' readonly value='0'></td><td><input type='text' readonly value='0'></td>
     
     <td><input type='text' readonly value='0'></td></tr>";
     
     echo "<tr><th style='text-align:center'>2</th><th  style='background-color:#006400;color:white;padding:2px;' colspan='7'>Hudhurio la kwanza</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>2a</td><td>Umri wa mimba chini ya wiki 12(<12 Weeks)</td><td><input type='text' readonly value=' $Age_Less_than_12_weeksresult '></td><td><input type='text' readonly value='$Age_Less_than_12_weeksresult_greater_than_20'></td>
     
     <td><input type='text' readonly value='$resultplus'></td></tr>";
     
     
      echo "<tr><td style='text-align:center'>2b</td><td>Umri wa mimba wiki 12 au zaidi (12+weeks)</td><td><input type='text' readonly value='$Age_Greater_than_12_weeksresult_less20'></td><td><input type='text' readonly value='$Age_Greater_than_12_weeksresult_greater_20'></td>
     
     <td><input type='text' readonly value='$sumofgreater12andless'></td></tr>";
     
     echo "<tr><td style='text-align:center'></td><td style='font-weight:bold;font-style:italic;'>Jumla ya Hudhurio la Kwanza(2a+2b)</td><td><input type='text' style='font-weight:bold' readonly value='$sum_and_sum_less20yrs'></td><td><input type='text' style='font-weight:bold' readonly value=' $sum_and_sum_Greater20yrs'></td>
     
     <td><input type='text' style='font-weight:bold' readonly value='$summation'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>2c</td><td>Wateja Wa Marudio</td><td><input type='text' readonly value='$Revisit_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Revisit_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Revisit_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>2d</td><td>Hudhurio la nne wajawazito wote</td><td><input type='text' readonly value='0'></td><td><input type='text' readonly value=''></td>
     
     <td><input type='text' readonly value='0'></td></tr>";
     
     echo "<tr><td style='text-align:center'></td><td style='font-weight:bold;font-style:italic;'>Jumla ya Mahudhurio Yote(2c+2d)</td><td><input type='text' readonly value='0'></td><td><input type='text' readonly value='0'></td>
     
     <td><input type='text' readonly value='0'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>2e</td><td>Idadi ya wajawazito waliopima damu hudhurio la kwanza</td><td><input type='text' readonly value='$_Measured_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$_Measured_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$_Measured_Customer_With_Age_Less20_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>2e</td><td>Wajawazito waliopata Chanjo ya TT2+</td><td><input type='text' readonly value='$TT2_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$TT2_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$TTsum'></td></tr>";
     echo "<tr><td colspan='7' style='padding-left:600px;';><input type='submit' id='b1' value='Vidokezo Vya Hatari' class='art-button-green'></td></tr>";
     echo "</table>";
     
     
    echo "</div>";
    
    //Div 2
    
    
     echo "<div id='hudhurio2' >";
     
      echo "<div id='hudhurio2scr' style='overflow-y:scroll; height:380px;' >";
    echo "<table style='width:100%' >";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < Miaka 20</th><th>Umri Miaka 20 na zaidi</th><th>Jumla</th></tr>";
    
    
     
     echo "<tr><th style='text-align:center'>4</th><th  style='background-color:#006400;color:white;padding:2px;' colspan='7'>Vidokezo vya Hatari</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>4a</td><td>Mimba zaidi ya 4</td><td><input style='background-color:gray;' type='text' readonly value=''></td><td><input type='text' readonly value='$Preg4_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Preg4_Customer_With_Age_Greater20_Result'></td></tr>";
     
     
      echo "<tr><td style='text-align:center'>4b</td><td>Umri chini ya Miaka 20</td><td><input type='text' readonly value='$Age_Less20_Result'></td><td><input style='background-color:gray;' type='text' readonly value=''></td>
     
     <td><input type='text' readonly value='$Age_Less20_Result'></td></tr>";
     
     echo "<tr><td style='text-align:center'>4c</td><td >Umri zaidi ya miaka 35</td><td><input style='background-color:gray;' type='text' readonly value=''></td><td><input type='text' readonly value='$Age_Greater35_Result'></td>
     
     <td><input type='text' readonly value='$Age_Greater35_Result'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>4d</td><td>Upungufu mkubwa wa damu <8.5g/dl - Anaemia hudhurio la kwanza</td><td><input type='text' readonly value='$_Anaemia_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$_Anaemia_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Anaemia_Customer_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4e</td><td>Shinikizo la damu (BP=>140/90mm/hg)</td><td><input type='text' readonly value='$_Bp_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$_Bp_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Bp_Customer_Total'></td></tr>";
     
     echo "<tr><td style='text-align:center'>4f</td><td >Kifua kikuu</td><td><input type='text' readonly value=' $Tb_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Tb_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Tb_Customer_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4g</td><td>Sukari kwenye Mkojo</td><td><input type='text' readonly value='$Sukari_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Sukari_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Sukari_Customer_Total'></td></tr>";
     
     echo "<tr><td style='text-align:center'>4h</td><td>Protein kwenye Mkojo</td><td><input type='text' readonly value='$Protein_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Protein_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Protein_Customer_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4i</td><td>Waliopima Kaswende</td><td><input type='text' readonly value=' $Syphils_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Syphils_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4j</td><td>Waligundulika na maambukizi ya Kaswende</td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Less20_Resultpositive'></td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Greater20_Resultpositive'></td>
     
     <td><input type='text' readonly value='$Syphils_Totalpositive'></td></tr>";
     
     
     
     echo "<tr><td style='text-align:center'>4k</td><td>Waliopata matibabu ya kaswende</td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Less20_Resulttreated'></td><td><input type='text' readonly value=' $Syphils_Customer_With_Age_Greater20_Resulttreated'></td>
     
     <td><input type='text' readonly value='$Syphils_Totaltreated'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4l</td><td>Wenza/Waume waliopima kaswende</td><td><input type='text' readonly value='0'></td><td><input type='text' readonly value='0'></td>
     
     <td><input type='text' readonly value='0'></td></tr>";
     
     
     
     echo "<tr><td style='text-align:center'>4m</td><td>Wenza/Waume waliogundulika na maambukizi ya Kaswende</td><td><input type='text' readonly value=' $Syphils_Customer_With_Age_Less20_Resultmepositive'></td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Greater20_Resultmepositive'></td>
     
     <td><input type='text' readonly value='$Syphils_Totalmepositive'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4n</td><td>Wenza/Waume waliopata matibabu ya Kaswende</td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Less20_Resultmetreated'></td><td><input type='text' readonly value='$Syphils_Customer_With_Age_Greater20_Resultmetreated'></td>
     
     <td><input type='text' readonly value='$Syphils_Totalmetreated'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>4o</td><td>Waliopatikana na magonjwa ya mambukizo ya ngono yasiyo kaswende</td><td><input type='text' readonly value='$Std_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Std_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Std_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4p</td><td>Waliopata tiba sahihi ya magonjwa ya mambukizo ya ngono yasiyo kaswende</td><td><input type='text' readonly value='$Std_Customer_With_Age_Less20_Resulttreated'></td><td><input type='text' readonly value='$Std_Customer_With_Age_Greater20_Resulttreated'></td>
     
     <td><input type='text' readonly value='$Std_Totaltreated'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4q</td><td>Wenza/Waume waliopatikana na magonjwa ya mambukizo ya ngono yasiyo kaswende</td><td><input type='text' readonly value='$Std_Customer_With_Age_Less20_Resultme'></td><td><input type='text' readonly value='$Std_Customer_With_Age_Greater20_Resultme'></td>
     
     <td><input type='text' readonly value='$Std_Totalme'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>4r</td><td>Wenza/Waume waliopata tiba sahihi ya magonjwa ya ngono yasiyo kaswende</td><td><input type='text' readonly value='$Std_Customer_With_Age_Less20_Resulttreatedme'></td><td><input type='text' readonly value='$Std_Customer_With_Age_Greater20_Resulttreatedme'></td>
     
     <td><input type='text' readonly value='$Std_Totaltreatedme'></td></tr>";
     
     
     
     echo "</table>";
     
     
    echo "</div>";
    echo "<table>";
    echo "<tr><td colspan='7' style='padding-left:600px;border:none'><input type='submit' id='b2prev' value='Hudhurio La Kwanza' class='art-button-green'><input type='submit' id='b2' value='PMTCT' class='art-button-green'></td></tr>";
     echo "</table>";
     echo "</div>";
    
    
    
    
    
    
     echo "<div id='hudhurio3'>";
     echo "<div id='hudhurio3scrc' style='overflow-y:scroll; height:380px;' >";
    echo "<table style='width:100%'>";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < Miaka 20</th><th>Umri Miaka 20 na zaidi</th><th>Jumla</th></tr>";
    
    
     
     echo "<tr><th style='text-align:center'>5</th><th  style='background-color:#006400;color:white;padding:2px;' colspan='7'>PMTCT</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>5a</td><td>Tayari wana maambukizi ya VVU kabla ya kuanza kliniki</td><td><input type='text' readonly value='$Pmct_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Pmct_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Pmct_Total'></td></tr>";
     
     
      echo "<tr><td style='text-align:center'>5b</td><td>Wajawazito wote waliopata ushauri nasaha kabla ya kupima VVU kliniki</td><td><input type='text' readonly value='$PmctCouncel_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctCouncel_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctCouncel_Total'></td></tr>";
     
     echo "<tr><td style='text-align:center'>5c</td><td >Wajawazito Waliopima VVU kipimo cha kwanza kliniki</td><td><input  type='text' readonly value='$PmctFirstTest_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctFirstTest_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctFirstTest_Total'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>5d</td><td>Wajawazito Waliokutwa na VVU (positive) kipimo cha kwanza</td><td><input type='text' readonly value='$PmctFirstTestPositive_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctFirstTestPositive_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctFirstTestPositive_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>5e</td><td>Wajawazito waliokutwa na VVU (positive) kipimo cha kwanza walio chini ya umri wa miaka 25</td><td><input type='text' readonly value='$PmctFirstTestPositive_Customer_With_Age_Less20_Resultless25'></td><td><input type='text' readonly value='$PmctFirstTestPositive_Customer_With_Age_Greater20_Resultless25'></td>
     
     <td><input type='text' readonly value='$PmctFirstTestPositive_Totalless25'></td></tr>";
     
     echo "<tr><td style='text-align:center'>5f</td><td >Wajawazito waliopata ushauri baada ya kupima</td><td><input type='text' readonly value='$PmctCouncelAfter_Customer_With_Age_Less20_Result
  '></td><td><input type='text' readonly value='$Pmct_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctCouncelAfter_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>5g</td><td>Wajawazito waliopimwa VVU na wenza wao(couple) kwa pamoja katika kliniki ya wajawazito</td><td><input type='text' readonly value='$PmctPartiner_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctPartiner_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctPartiner_Total'></td></tr>";
     
     echo "<tr><td style='text-align:center'>5h</td><td>Wajawazito waliopima VVU kipimo cha pili</td><td><input type='text' readonly value='$PmctSecond_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctSecond_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctSecond_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>5i</td><td>Wajawazito  waliokutwa na maambukizi ya VVU kipimo cha pili</td><td><input type='text' readonly value='$PmctSecondPositive_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctSecondPositive_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctSecondPositive_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>5j</td><td>Wenza waliopima VVU kipimo cha kwanza Kliniki ya Wajawazito</td><td><input type='text' readonly value='$PmctTestedPartiner_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctTestedPartiner_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctTestedPartiner_Total'></td></tr>";
     
     
     
     echo "<tr><td style='text-align:center'>5k</td><td>Wenza waliogundulika kuwa na maambukizi ya VVU kipimo cha Kwanza katika kliniki ya Wajawazito</td><td><input type='text' readonly value='$PmctTestedPartinerPositive_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctTestedPartinerPositive_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctTestedPartinerPositive_Total'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>5l</td><td>Wenza waliopima VVU kipimo cha pili Kliniki ya wajawazito</td><td><input type='text' readonly value='NIL'></td><td><input type='text' readonly value='NIL'></td>
     
     <td><input type='text' readonly value='NIL'></td></tr>";
     
     
     
     echo "<tr><td style='text-align:center'>5m</td><td>Wenza waliogundulika kuwa na maambukizi ya VVU kipimo cha pili katika kliniki ya wajawazito</td><td><input type='text' readonly value='NIL'></td><td><input type='text' readonly value='NIL'></td>
     
     <td><input type='text' readonly value='NIL'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>5n</td><td>Wajawazito na Wenza waliopata majibu tofauti (discordant) baada ya kupima VVU kliniki ya wajawazito</td><td><input type='text' readonly value='$PmctTestedDiscorant_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctTestedDiscorant_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctTestedDiscorant_Total'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>5o</td><td>Waliopata ushauri juu ya ulishaji wa mtoto</td><td><input type='text' readonly value='$PmctFeedCouncel_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$PmctFeedCouncel_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$PmctFeedCouncel_Total'></td></tr>";
     
     
     
     
     echo "</table>";
     
     
    echo "</div>";
    echo "<table>";
    echo "<tr><td colspan='7' style='padding-left:600px;';><input type='submit' id='b3prev' value='Vidokezo Vya Hatari' class='art-button-green'><input type='submit' id='b3' value='Malaria' class='art-button-green'></td></tr>";
     echo "</table>";
      echo "</div>";
    
   //div 4
   
   
   
     echo "<div id='hudhurio4'>";
    echo "<table style='width:100%'>";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < Miaka 20</th><th>Umri Miaka 20 na zaidi</th><th>Jumla</th></tr>";
    
    
     
     echo "<tr><th style='text-align:center'>6</th><th  style='background-color:#006400;color:white;padding:2px;' colspan='7'>Malaria</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>6a</td><td>Waliopewa Vocha Ya Hati Punguzo</td><td><input type='text' readonly value='$MalariaHatipunguzo_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$MalariaHatipunguzo_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$MalariaHatipunguzo_Total'></td></tr>";
     
     
      echo "<tr><td style='text-align:center'>6b</td><td>Waliopima Malaria kutumia MRDT</td><td><input type='text' readonly value='NIL'></td><td><input type='text' readonly value='NIL'></td>
     
     <td><input type='text' readonly value='NIL'></td></tr>";
     
     echo "<tr><td style='text-align:center'>6c</td><td >Waliopima Malaria Positive</td><td><input type='text' readonly value='$MalariaPositive_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$MalariaPositive_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$MalariaPositive_Total'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>6d</td><td>Waliopewa IPT2</td><td><input type='text' readonly value='$IPT2_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$IPT2_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$IPT2sum'></td></tr>";
     
     
     echo "<tr><td style='text-align:center'>6e</td><td>Waliopewa IPT4</td><td><input type='text' readonly value='$IPT4_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$IPT4_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$IPT4sum'></td></tr>";
     
     
     
     echo "<tr><td colspan='7' style='padding-left:600px;';><input type='submit' id='b4prev' value='PMTCT' class='art-button-green'><input type='submit' id='b4' value='Huduma Nyingine' class='art-button-green'></td></tr>";
     echo "</table>";
     
     
    echo "</div>";
    
    
    
    //DIV 5
     echo "<div id='hudhurio5'>";
    echo "<table style='width:100%'>";
    echo "<tr><th>Namba</th><th>Maelezo</th><th>Umri < Miaka 20</th><th>Umri Miaka 20 na zaidi</th><th>Jumla</th></tr>";
    
    
     
     echo "<tr><th style='text-align:center'>6</th><th  style='background-color:#006400;color:white;padding:2px;' colspan='7'>Huduma Nyingine</th></tr>";
     
     
      echo "<tr><td style='text-align:center'>6a</td><td>Waliopewa Iron/Folic Acid (I,F,IF,A) vidonge vya kutosha mpaka hudhurio linalofuata</td><td><input type='text' readonly value='$IFA_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$IFA_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$IFAsum'></td></tr>";
     
     
      echo "<tr><td style='text-align:center'>6b</td><td>Waliopewa dawa za minyoo(Albendazole/Mebendazole)</td><td><input type='text' readonly value='$Albendazole_Customer_With_Age_Less20_Result'></td><td><input type='text' readonly value='$Albendazole_Customer_With_Age_Greater20_Result'></td>
     
     <td><input type='text' readonly value='$Albendazole_Total'></td></tr>";
     
     echo "<tr><td style='text-align:center'>6c</td><td >Waliopewa rufaa wakati wa ujauzito</td><td><input type='text' readonly value='NIL'></td><td><input type='text' readonly value='NIL'></td>
     
     <td><input type='text' readonly value='NIL'></td></tr>";
     
     
     
     
     echo "<tr><td style='text-align:center'>6d</td><td>Waliopewa rufaa kwenda CTC</td><td><input type='text' readonly value='NIL'></td><td><input type='text' readonly value='NIL'></td>
     
     <td><input type='text' readonly value='NIL'></td></tr>";
     
     
     
     
     
     echo "<tr><td colspan='7' style='padding-left:600px;';><input type='submit' id='b5' value='Malaria' class='art-button-green'></td></tr>";
     echo "</table>";
     
     
    echo "</div>";
    
     echo "</div>";
    
    
    
  } ?>
  
  




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
<link rel="stylesheet" type="text/css" href="js\datepickerforrch\demo-jquery-datepicker\jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="js\datepickerforrch\demo-jquery-datepicker\demos.css">
		
		
		
		
		<script src="js\datepickerforrch\demo-jquery-datepicker\jquery-1.5.1.js"></script>
		<script src="js\datepickerforrch\demo-jquery-datepicker\jquery.ui.core.js"></script>
		<script src="js\datepickerforrch\demo-jquery-datepicker\jquery.ui.widget.js"></script>
		<script src="js\datepickerforrch\demo-jquery-datepicker\jquery.ui.datepicker.js"></script>
		<script> 
			$(function() {
				$( "#datepickery" ).datepicker();
			});
			

		</script> 





<!-- link menu -->

<a href="searchvisitorsoutpatientlistforhiv.php" class="art-button-green" >Back</a>
<select id="sele">
						<option value="prev">Previous Visits</option>
						<option value="new">New Visit</option>
						 <?php
   
   if(isset($_GET['ctcno'])){
	
	$rx= $_GET['ctcno'];
	
	
$checkifyupo = "SELECT hiv_id,pr_r,status FROM tbl_hiv_first_visit WHERE pr_r= '$rx' AND status='active'";

$row=mysqli_fetch_array($qresult=mysqli_query($conn,$checkifyupo)); 
	
	$last=$row['hiv_id'];
	
	
	$checkifyupogo = "SELECT DATE(muda_huu) as pacha FROM tbl_hiv_visits WHERE first_v_id= '$last' GROUP BY DATE(muda_huu)";
if(mysqli_num_rows($qresulte=mysqli_query($conn,$checkifyupogo))>0) {
    
  while( $fetchdete=mysqli_fetch_array($qresulte)) {?>
	  <option value="<?php echo date("Y-m-d",strtotime($fetchdete[ 'pacha']));  ?>">
	  <?php echo date("jS F Y",strtotime($fetchdete[ 'pacha']));  ?>
	
	 
	  </option>
	  
	  
  <?php }

   }}
   
   
   ?>
					
						 
  			
						
		<option value="close">Close File</option>				
						
						</select>

<fieldset style="margin-top:5px;"> 
<legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>CTC</b></legend> 
   <div class="powercharts_body">
   
  			
						
						
						
						
						
						
						
						
				
				
				<script type="text/javascript" src="min.js"></script>
				
				<script type="text/javascript">				
 
    //Saving to database
     $(function() {
	
	$("#modal-rch").hide();
	$("#modal-rchload").hide();
	
	
	
	
	
		
	$('#sele').change(
	    
	    
    function() {
	 $("#ol").hide();
	 $("#modal-rch").hide();
	 $("#modal-rchload").show();
	var f = $('#sele option:selected').val();
		 var h = $('#cb').val();
		
		
		if(f=="new"){ $("#modal-rch").hide();
		$("#modal-rchload").hide();
		    $("#ol").show();
		    
			} 
else if(f=="close") {
   
	$.ajax({
    type: "POST",
    url: 'closerch.php',
    data:"rchx="+f+"&h="+h,
    success: function(data){
        $("#modal-rch").html(data);
	    
    }
		}); 
		
		
$("#modal-rch").show();			
	$("#ol").hide();
    
	
	
	
	
	}	else if(f=="prev") {  
	$("#modal-rch").hide();
	$("#modal-rchload").hide();	
	
	$("#ol").show();
			}
		
		else{
		    $("#ol").hide();
		    $("#modal-rchload").show();
		
		
$.ajax({
    type: "POST",
    url: 'powercharts_hiv_re_view.php',
    data:"rch2="+f+"&h="+h,
    success: function(data){
        $("#modal-rch").html(data);
	$("#modal-rch").show();
	$("#modal-rchload").hide();
	
    }
		}); 
		
	
		//$("#modal-rchload").hide();
		}
       // var val2 = $('#drop option:selected').val();

        // Do something with val1 and val2 ...
    }
);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$("#sv").click(function(){
		//Cur status
		var cur_status = $('#hiv_status option:selected').val();
		if (cur_status=='none') {
			$('#hiv_status').css({"background": "#FFCECE"});
			var err1=1; }else {
			$('#hiv_status').css({"background": "white"});
		var err1=0;
		}
		
		//Prev test
		var prev_test = $('#prevtest option:selected').val();
		if (prev_test=='none') {
			$('#prevtest').css({"background": "#FFCECE"});
			var err2=1; }else {
			$('#prevtest').css({"background": "white"});
		var err2=0;
		}
		
		//Pre testing counceling
		var pre_test = $('#pretest option:selected').val();
		if (pre_test=='none') {
			$('#pretest').css({"background": "#FFCECE"});
			var err3=1; }else {
			$('#pretest').css({"background": "white"});
		var err3=0;
		}
		
		
		//Post result counceling
		var post_res = $('#postre option:selected').val();
		if (post_res=='none') {
			$('#postre').css({"background": "#FFCECE"});
			var err4=1; }else {
			$('#postre').css({"background": "white"});
		var err4=0;
		}
		
		
		//Therapy
		var therapy_arv = $('#therapy option:selected').val();
		if (therapy_arv=='none') {
			$('#therapy').css({"background": "#FFCECE"});
			var err5=1; }else {
			$('#therapy').css({"background": "white"});
		var err5=0;
		}
		
		
		//Medications
		
		var medi = $('#medication option:selected').val();
		if (medi=='none') {
			$('#medication').css({"background": "#FFCECE"});
			var err6=1; }else {
			$('#medication').css({"background": "white"});
		var err6=0;
		}
		
		
		//Result for previous test
		var prev_result = $('#respre option:selected').val();
		if (prev_result=='none') {
			$('#respre').css({"background": "#FFCECE"});
			var err7=1; }else {
			$('#respre').css({"background": "white"});
		var err7=0;
		}
		
		//Declaration
		var declaration = $('#decla option:selected').val();
		if (declaration=='none') {
			$('#decla').css({"background": "#FFCECE"});
			var err8=1; }else {
			$('#decla').css({"background": "white"});
		var err8=0;
		}
		
		//Has partiner been tested?
		var partinertest = $('#part option:selected').val();
		if (partinertest=='none') {
			$('#part').css({"background": "#FFCECE"});
			var err9=1; }else {
			$('#part').css({"background": "white"});
		var err9=0;
		}
		
		
		//Feeding
		var feeding = $('#feed option:selected').val();
		if (feeding=='none') {
			$('#feed').css({"background": "#FFCECE"});
			var err10=1; }else {
			$('#feed').css({"background": "white"});
		var err10=0;
		}
		
		//Review date
		var date_rev = $('#date').val();
		if (date_rev=='') {
			$('#date').css({"background": "#FFCECE"});
			var err11=1; }else {
			$('#date').css({"background": "white"});
		var err11=0;
		}
		
		
		
		//Date of prev test
		var date_prev_test = $('#date2').val();
		if (date_prev_test=='') {
			$('#date2').css({"background": "#FFCECE"});
			var err12=1; }else {
			$('#date2').css({"background": "white"});
		var err12=0;
		}
		
		//Partiner Name
		var partn = $('#partinername').val();
		if (partn=='') {
			partn ="unknown";
		}
		
		
		
		var priid = $('#prid').val();
		if($('#arv').val() == "") {var arvn= 0;} else{ var arvn=$('#arv').val();}
		
		if($('#mother').val() == "") {var mothern= "unknown";} else{ var mothern=$('#mother').val();}
		
		if($('#comment').val() == "") {var com= "unknown";} else{ var com=$('#comment').val();}
		
		if($('#month').val() == "") {var months= 0;} else{ var months=$('#month').val();}
		if($('#day').val() == "") {var days= 0;} else{ var days=$('#day').val();}
	
		
		if(err1==1 || err2==1 || err3==1 || err4==1 || err5==1 || err6==1 || err7==1 || err8==1 || err9==1 || err10==1 || err11==1 || err12==1) {
			
		
		
		alert('Please fill all pink areas!');
			
			//code
		} else {
			
			
				
$.ajax({
    type: "POST",
    url: 'powercharts_hiv_re_insert.php',
    data:"cur_stat="+ cur_status +"&prev_tes="+ prev_test+"&pre_test_cou="+pre_test+"&postresult="+ post_res+"&therapy="+ therapy_arv+"&medic="+ medi+"&prevresult="+ prev_result+"&dec="+declaration+"&partiner_test="+partinertest +"&feed="+feeding+"&revie="+date_rev+"&date_of_prev="+date_prev_test+"&partnernam="+partn+"&moth="+mothern+"&comment="+com+"&pri="+priid+"&month="+months+"&day="+days+"&yrs="+arvn,
    success: function(data){
        alert(data);
		//location.href="f.php";
    }
});

		
		}		
		
		
		
		});
	
	////hiv status
	//$('#hiv_status').change(function() {var cur_status = $('#hiv_status option:selected').val();
	//if (cur_status=='none') {
	//	
	//	alert("Please u good");
	//	//code
	//}
	//});
	//
	////Previous test
	//$('#hiv_status').change(function() {var cur_status = $('#hiv_status option:selected').val();});
	
	
	
	
	
     });
	</script>




        <div class="tabcontents" >
	    
		<?php 
if(isset($_GET['ctcno'])){
	
	$pn= $_GET['ctcno'];	
	$select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender from
				    tbl_patient_registration pr
					where pr.registration_id ='$pn'") or die(mysqli_error($conn));
							
    //display all items
        while($row2 = mysqli_fetch_array($select_Patient_Details)){
		
	    $Today = Date("Y-m-d");
	    $Date_Of_Birth = $row2['Date_Of_Birth'];
	    $date1 = new DateTime($Today);
	    $date2 = new DateTime($Date_Of_Birth);
	    $diff = $date1 -> diff($date2);
	    $age = $diff->y;
			
            $fname= explode(' ',$row2['Patient_Name'])[0];
			
            
			
			
			$mname='';
			
	    if(sizeof(explode(' ',$row2['Patient_Name']))>= 3){
			
			
			
			
			
			$mname=explode(' ',$row2['Patient_Name'])[sizeof(explode(' ',$row2['Patient_Name'])) - 2];
			
		$lname=explode(' ',$row2['Patient_Name'])[sizeof(explode(' ',$row2['Patient_Name'])) - 1];
		
	    
		
		}
		
		else{
		    
		$lname=explode(' ',$row2['Patient_Name'])[1];
	    }
  	
} }

?>
			
            
	<div id="ol">		   
	  <table width="100%" class="hiv_table" border="0" > 
                         <tr>
                             <td colspan=""><button  class="art-button-green" id="sv" >Update and Go Back</button></td>
			     
			 <td colspan="2"><input id="cb" type="hidden" value="<?php echo $pn; ?>" ><input readonly value="<?php echo strtoupper( $fname." ".$mname." ".$lname); ?>" type="text"><input type="hidden" id="prid" value="<?php echo $pn; ?>"></td>
                             
                             
                        </tr>
                         <tr>
                             <td width="100%" colspan="4"><hr></td>
                        </tr>
                        <tr>
                            <td width="30%" class="powercharts_td_left">Husband/Wife/Partner Name</td>
                            <td><input name="" type="text" id="partinername"  ></td>
                            <td class="powercharts_td_left">Mother's Name (if a child under 18 tears)</td>
                            <td><input name="" type="text" id="mother"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">How Long Have They Been Taking ARV?</td>
                            <td><input name="" type="text" id="arv" placeholder="Years" style="width:35px;"><input name="" type="text" id="month" placeholder="Months" style="width:45px;" ><input style="width:30px;" name="" type="text" id="day" placeholder="days"></td>
                            <td class="powercharts_td_left">Which ARV Medication are They Taking?</td>
                            <td><select id="medication">
			    <option value="none">Select From List</option>
                                <option>ARV Propholaxis</option>
                                <option>ART</option>
                                <option>CTX</option>
                                <option>Other</option>
                            </select>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Current HIV Status</td>
                            <td width="12%">
                                <Select id="hiv_status">
                                    <option value="none"> Select From List</option>
                                    <option>Postive</option>
                                    <option>Negative</option>
                                    <option>Unsure</option>
                                </Select>
                            </td>
                            <td class="powercharts_td_left" style="text-align:right">Date Of Previous HIV Test</td>
                            <td><input name="" type="date" id="date2"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Previous HIV Test</td>
                            <td><select id="prevtest">
                                <option value="none">Select From List</option>
                                <option>Yes</option>
                                <option>No</option>
                                <option>Unsure</option>
                            </select></td>
                            <td class="powercharts_td_left"  style="text-align:right"> Result of Previous HIV Tests</td>
                            <td><select id="respre">
                                <option value="none">Select From List</option>
                                <option>Postive</option>
                                <option>Negative</option>
                                <option>Unknown</option>
                            </select></td>
                        </tr>                   
                        <tr>
                            <td class="powercharts_td_left">Patient Has Received Pre-Testing Counseling?</td>
                            <td>
                                <Select class="select_contents" id="pretest">
                                    <option value="none"> Select From List</option>
                                         <option class="select_contents">Yes</option>
                                    <option class="select_contents">No</option>
                                    <option>Does Not Apply</option>
                                </Select>
                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Patient Has Received Post -Result Counseling?</td>
                            <td>
                                <Select class="select_contents" id="postre">
                                    <option value="none"> Select From List</option>
                                         <option class="select_contents">Yes</option>
                                    <option class="select_contents">No</option>
                                    <option>Does Not Apply</option>
                                </Select>
                                </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Recommended Date For Status Review</td>
                            <td><input name="" type="date" id="date" ></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Is The Patient Taking ARV Therapy?</td>
                            <td>
                                <Select class="select_contents" id="therapy">
                                    <option value="none"> Select From List</option>
                                         <option class="select_contents">Yes
					 <option class="select_contents">No</option>
                                    <option>Does Not Apply</option>
                                </Select>
                            </td>
                            <td class="powercharts_td_left">Patient Has Signed HIV Declaration</td>
                            <td >
                                <Select class="select_contents" id="decla">
                                    <option value="none">Select From List</option>
                                         <option class="select_contents">Yes<option class="select_contents">No</option>
                                    <option>Does Not Apply</option>
                                </Select>
                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" colspan="3">Has Your Husband/Partner/Wife/Mother Been Tested For HIV?</td>
                            <td>
                                <Select class="select_contents" id="part">
                                    <option value="none"> Select From List</option>
                                         <option class="select_contents">Yes <option class="select_contents">No</option>
                                    <option>Unknown</option>
                                </Select>
                            </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left" colspan="3">If The Patient Is HIV Positive With Children,Have They Received Information On Feeding?</td>
                            <td colspan="2">
                                <Select class="select_contents" id="feed">
                                    <option value="none"> Select From List</option>
                                         <option class="select_contents"> Yes <option class="select_contents">No</option>
                                    <option>Unknown</option>
                                </Select>
                                </td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left">Other Comments</td>
                            <td rowspan="5" colspan="3"><textarea rows="5" id="comment"></textarea>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                        <tr>
                            <td class="powercharts_td_left"></td>
                        </tr>
                         <tr>
                             <td colspan="4" rowspan="2" class="powercharts_footer">
                                "Please ensure that the patient is fully counselled and always aware of the treaments available for HIV positive results before sending them for testing"<br/>
                                "All patients being sent for HIV testing must sign an 'HIV DECLARATION' form,please give to reception for scanning to their file record"<br/>
                                   "If you are screening/testing both partners, you must fill this HIV History for both patients please"</td></td>
                        </tr>
                    </table>
                </div>
						
						
						<!--Div ya pili------------------------------------------------------------->
						
						
						<div id="modal-rch" style="height:auto; width:auto;background-color:none">
						<b>Loarc.....</b>
						
						</div>
						
						<div id="modal-rchload" style="height:auto; width:auto;background-color:none">
						<b>Loading.....</b>
						
						</div>


						
        
						
    <?php
    include("./includes/footer.php");
?>





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
<legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>CTC REPORTS</b></legend> 
   <div class="powercharts_body">
   
  			
						
						
						
						
						
						
						
						
				
				
				<script type="text/javascript" src="min.js"></script>
				
				
				
				<script src="ui2\jquery-ui.js"></script
<link rel="stylesheet" type="text/css" href="ui2\jquery-ui.css">

				
				<script type="text/javascript">				
 
    //Saving to database
     $(function() {
	
	//modal box
	
	 $( "#dialog-1" ).dialog({
               autoOpen: false,
	       modal:true
            });
            
               
            
	
	
	
	
	
	
	
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
    
    $( "#dialog-1" ).dialog( "open" );
    
    $(".ui-widget-overlay").attr('style','background-color: #000; opacity:1; z-index:1000;');
   
//	$.ajax({
//    type: "POST",
//    url: 'closerch.php',
//    data:"rchx="+f+"&h="+h,
//    success: function(data){
//        $("#modal-rch").html(data);
//	    
//    }
//		}); 
//		
//		
//$("#modal-rch").show();			
//	$("#ol").hide();
    
	
	
	
	
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$("#filt").click(function(){
		//Cur status
		
		
		var sel = $('#sel option:selected').val();
		
		if (sel=='none') {
			$('#sel').css({"border-color": "red"});
			var err1=1; }else {
			$('#sel').css({"border-color": "white"});
		var err1=0;
		}
		
		if($('#date').val() == "") {
		    var err2=1;
		
		$('#date').css({"border-color": "red"});
		} else{ var date=$('#date').val();
		var err2=0;
		$('#date').css({"border-color": "white"});
		}
		
		
		if($('#date1').val() == "") {
		var err3=1;
		$('#date1').css({"border-color": "red"});
		} else{ var date1=$('#date1').val();
		var err3=0;
		$('#date1').css({"border-color": "white"});
		}
		
		////Prev test
		//var prev_test = $('#prevtest option:selected').val();
		//if (prev_test=='none') {
		//	$('#prevtest').css({"background": "#FFCECE"});
		//	var err2=1; }else {
		//	$('#prevtest').css({"background": "white"});
		//var err2=0;
		//}
		//
		////Pre testing counceling
		//var pre_test = $('#pretest option:selected').val();
		//if (pre_test=='none') {
		//	$('#pretest').css({"background": "#FFCECE"});
		//	var err3=1; }else {
		//	$('#pretest').css({"background": "white"});
		//var err3=0;
		//}
		//
		//
		////Post result counceling
		//var post_res = $('#postre option:selected').val();
		//if (post_res=='none') {
		//	$('#postre').css({"background": "#FFCECE"});
		//	var err4=1; }else {
		//	$('#postre').css({"background": "white"});
		//var err4=0;
		//}
		//
		//
		////Therapy
		//var therapy_arv = $('#therapy option:selected').val();
		//if (therapy_arv=='none') {
		//	$('#therapy').css({"background": "#FFCECE"});
		//	var err5=1; }else {
		//	$('#therapy').css({"background": "white"});
		//var err5=0;
		//}
		//
		//
		////Medications
		//
		//var medi = $('#medication option:selected').val();
		//if (medi=='none') {
		//	$('#medication').css({"background": "#FFCECE"});
		//	var err6=1; }else {
		//	$('#medication').css({"background": "white"});
		//var err6=0;
		//}
		//
		//
		////Result for previous test
		//var prev_result = $('#respre option:selected').val();
		//if (prev_result=='none') {
		//	$('#respre').css({"background": "#FFCECE"});
		//	var err7=1; }else {
		//	$('#respre').css({"background": "white"});
		//var err7=0;
		//}
		//
		////Declaration
		//var declaration = $('#decla option:selected').val();
		//if (declaration=='none') {
		//	$('#decla').css({"background": "#FFCECE"});
		//	var err8=1; }else {
		//	$('#decla').css({"background": "white"});
		//var err8=0;
		//}
		//
		////Has partiner been tested?
		//var partinertest = $('#part option:selected').val();
		//if (partinertest=='none') {
		//	$('#part').css({"background": "#FFCECE"});
		//	var err9=1; }else {
		//	$('#part').css({"background": "white"});
		//var err9=0;
		//}
		//
		//
		////Feeding
		//var feeding = $('#feed option:selected').val();
		//if (feeding=='none') {
		//	$('#feed').css({"background": "#FFCECE"});
		//	var err10=1; }else {
		//	$('#feed').css({"background": "white"});
		//var err10=0;
		//}
		//
		////Review date
		//var date_rev = $('#date').val();
		//if (date_rev=='') {
		//	$('#date').css({"background": "#FFCECE"});
		//	var err11=1; }else {
		//	$('#date').css({"background": "white"});
		//var err11=0;
		//}
		//
		//
		//
		////Date of prev test
		//var date_prev_test = $('#date2').val();
		//if (date_prev_test=='') {
		//	$('#date2').css({"background": "#FFCECE"});
		//	var err12=1; }else {
		//	$('#date2').css({"background": "white"});
		//var err12=0;
		//}
		//
		////Partiner Name
		//var partn = $('#partinername').val();
		//if (partn=='') {
		//	partn ="unknown";
		//}
		//
		
		
		//var priid = $('#prid').val();
		//if($('#arv').val() == "") {var arvn= 0;} else{ var arvn=$('#arv').val();}
		//
		//if($('#mother').val() == "") {var mothern= "unknown";} else{ var mothern=$('#mother').val();}
		//
		//if($('#comment').val() == "") {var com= "unknown";} else{ var com=$('#comment').val();}
		//
		//if($('#month').val() == "") {var months= 0;} else{ var months=$('#month').val();}
		//if($('#day').val() == "") {var days= 0;} else{ var days=$('#day').val();}
	
		
		if(err1==1 || err2==1 || err3==1 ) {
			
		
		
		alert('Please fill all red areas!');
			
			//code
		} else {
			
		$("#modal-rchload").show()	
				
$.ajax({
    type: "POST",
    url: 'powercharts_hiv_reports_view.php',
    data:"d="+ date +"&d2="+ date1+"&sel="+sel,
    success: function(data){
        $("#ol").html(data);
		//location.href="f.php";
		
		$("#modal-rchload").hide()
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
			
         <table width="100%" class="hiv_table" border="0" > 
                         
	  
                         <tr>
                            
			     
			 <td colspan="2">From<input id="date" type="date" style="width: 150px;" >&nbsp;To&nbsp;<input id="date1" type="date" style="width: 150px;" >&nbsp;<select id="sel">
			    <option value="none">Select from List</option>
			    <option value="new">New Visit</option>
			    <option value="re">visit</option>
			    <option value="rev">Re-visit</option>
			    
			  </select>&nbsp;<input id="filt" style="height:20px; " type="submit" value="Filter" class="art-button-green">
                          </td>   
                             
                        </tr>
                         
	  </table>
	 
   
      <!-- HTML --> 
      <div id="dialog-1" title="Close CTC File" style="" >
	Choose file:
	<select id="sel">
			    <option value="none">Select from List</option>
			    <option value="new">New Visit</option>
			    <option value="re">Re-visit</option>
			    <option value="re">Re-visit</option>
			    
			  </select>
	<input type="submit" value="CloseFile">
	
	
      </div>
     
	<div id="ol" style="margin-top:20px;">		   
	  
			 
                       
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

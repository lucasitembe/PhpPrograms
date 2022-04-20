



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

<a href="searchvisitorsoutpatientlistrchmahudhurio.php" class="art-button-green" >Back</a>
<select id="sele">
						<option value="prev">Previous Report</option>
						<option value="new">New Visit</option>
						 <?php
   
   if(isset($_GET['rchno'])){
	
	$rx= $_GET['rchno'];
	
	
	$checkifyupogo = "SELECT DATE(vdate) as pacha FROM tbl_rch_visits WHERE rch_id= '$rx' GROUP BY DATE(vdate) ";
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
<legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REPRODUCTIVE & CHILD HEALTH - RCH</b></legend> 
   <div class="powercharts_body">
   
  
                
						
						
						
						
						
						
						
						
						
				
				
				<script type="text/javascript" src="min.js"></script>
				
				
<script type="text/javascript" >




var kadi,unasime,unasike,prid,kitongoji,mta,mumejina,yearofreg,noofreg,umriwasasa,balo,kiti,nyumbano,haemo,shinikizo_kipimo,ure,sukari_kipimo,umri_chini,umri_juu,tt1,tt2,tt3,tt4,mimbano,kuzaa,hai,haribika,kifo,kaswende,kaswendeme,kaswendetib,kaswendetibme,maambukizivvu,maambukizivvume,ulishajivar,albendazolevar,hativar,malariavar,ipt1var,ipt2var,ipt3var,ipt4var,ifa1var,ifa2var,ifa3var,ifa4var,rufdate,rufalipopelekwa,rufalipotoka,rufmaoni,ngonotibafemale,ngonotibamale;

	

$(function() {



	
	
	$('#sele').change(
    function() {
		
		
	
        
		
		var f = $('#sele option:selected').val();
		var h = $('#rchnoe').val();
		
		if(f=="new"){
			$("#modal-rch").hide();			
	$("#pili").hide();
	$("#kwanza").show();
			
			
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
	$("#pili").hide();
	$("#kwanza").hide();
	
	
	
	
	}	else if(f=="prev") {  
	$("#modal-rch").hide();			
	$("#pili").hide();
	$("#kwanza").show();
			}
		
		else{
		
		
$.ajax({
    type: "POST",
    url: 'powercharts.php',
    data:"rch2="+f+"&h="+h,
    success: function(data){
        $("#modal-rch").html(data);
    }
		}); 
		
		
$("#modal-rch").show();			
	$("#pili").hide();
	$("#kwanza").hide();		
		}
       // var val2 = $('#drop option:selected').val();

        // Do something with val1 and val2 ...
    }
);
    
		$("#modal-rch").hide();			
	$("#pili").hide();
	
	$("#kaswtibafe").hide();
	$("#kaswtibame").hide();
	$("#ngonotibafe").hide();
	$("#ngonotibame").hide();
	
	
	
	
	$(".nn").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
				
				// $('.nn').css({
                  
                   // "background": "#FFCECE"});
					
				
				
            } 
        });
	
	
	
	
	
	
	
	
	// here we gotolink
	

	
	
	$("#b1kwanza").click(function() {
		
		
		    //Shinikizo
			
			
		/* 	
        if($("input[name=shinikizo]:checked").val() == "anashinikizo"){
			
			shinikizo_kipimo = "anashinikizo";
			$('#lv').css({
                  
                    "background": "none"});
			
		}
		 else if 
			
			($("input[name=shinikizo]:checked").val() == "hanashinikizo"){
			
			shinikizo_kipimo = "hanashinikizo";
			$('#lv').css({
                  
                    "background": "none"});
			
		}
    else { $('#lv').css({
                  
                    "background": "#FFCECE"});
	
					var error15 = 1;
	
	}
 */
	//umri wa sasa wa mama mjamzito
	
	
	umriwasasa=$('#umri').val();
	
	
	 //Kifo katika wiki Moja
			
        /* if($("input[name=kifo]:checked").val() == "kifokipo"){
			
			 kifo = "kifokipo";
			$('#kif').css({
                  
                    "background": "none"});
					
			
		}
		 else if 
			
			($("input[name=kifo]:checked").val() == "hakunakifo"){
			
		 kifo = "hakunakifo";
			$('#kif').css({
                  
                    "background": "none"});
			
		}
    else { $('#kif').css({
                  
                    "background": "#FFCECE"});
	
					var error26 = 1;
	
	}
 */
		
	
					
					
					
					/* //Umri chini ya miaka 20
					
					   
			
        if($("input[name=umrichini]:checked").val() == "anamiaka_chini_ya_20"){
			
			 umri_chini = "anamiaka_chini_ya_20";
			$('#miaka').css({
                  
                    "background": "none"});
							var error17 = 0;
					
			
		}
		 else if 
			
			($("input[name=umrichini]:checked").val() == "hanamiaka_chini_ya_20"){
			
		 umri_chini = "hanamiaka_chini_ya_20";
			$('#miaka').css({
                  
                    "background": "none"});
					var error17 = 0;
			
		}
    else { $('#miaka').css({
                  
                    "background": "#FFCECE"});
					
							var error17 = 1;
	
	
	
	}
 */
	
	
	
	
	
	
	
	
	//Zaidi ya 35 umri
	
	
	
	
		
					
					   
		/* 	
        if($("input[name=umrijuu]:checked").val() == "anamiaka_juu_ya_35"){
			
			umri_juu = "anamiaka_juu_ya_35";
			$('#miaka35').css({
                  
                    "background": "none"});
					var error18 = 0;
			
		}
		 else if 
			
			($("input[name=umrijuu]:checked").val() == "hanamiaka_juu_ya_35"){
			
			 umri_juu = "hanamiaka_juu_ya_35";
			$('#miaka35').css({
                  
                    "background": "none"});
					
					var error18 = 0;
			
		}
    else { $('#miaka35').css({
                  
                    "background": "#FFCECE"});
					
					var error18 = 1;
	
	
	
	}
	 */
	
	
	
	//Chanjo ya tt
	
	/* if($("input[name=kadi]:checked").val() == "anakadi"){
			
			 kadi = "anakadi";
			$('#ka').css({
                  
                    "background": "none"});
					var error19 = 0 ;
			
		}
		 else if 
			
			($("input[name=kadi]:checked").val() == "hanakadi"){
			
			 kadi = "hanakadi";
			$('#ka').css({
                  
                    "background": "none"});
			var error19 = 0 ;
		}
    else { $('#ka').css({
                  
                    "background": "#FFCECE"});
					
					var error19 = 1 ;
	
	
	
	}
 */
	
	
	
	
	
	
	
	
	
	
	
					//Jina la Mume/Mwenza
	
		
		
		
		
		
		
		if($("#hajulikani").is(":checked")){
         var haju="sel"; 
   }
   else{
        
		
		var haju=""; 
   }
        





		
		
		
		
			
		
		
//Chukua variable zote
		 
		 var num = $("#num").val();
		 
		 if($('#kijiji').val() == "") {
$('#kijiji').css({
                  
                    "background": "#FFCECE"});
					
					var error1 = 1; 
					
					} else{ 
					
					kitongoji = $('#kijiji').val();
					
					var error1 = 0; 
					
					}
					
					
					//Date if empty
					
					
					if($('#da').val() == "") {
$('#da').css({
                  
                    "background": "#FFCECE"});
					
					var error20 = 1; 
					
					} else{
						
					var datevisit = $('#da').val();
					//alert (datevisit);
					var error20 = 0; 
					
					}
					
					
					
					
					
/* 				//TT2
if($('#t2').val() != "") { 

tt2 = $('#t2').val();
} else {
tt2 = "not_updated";	
	
}				
	

				//TT3
if($('#t3').val() != "") { 

tt3 = $('#t3').val();
} else {
tt3 = "not_updated";	
	
}	


				//TT4
if($('#t4').val() != "") { 

tt4 = $('#t4').val();
} else {
tt4 = "not_updated";	
	
}	
 */
	
					
					
					
					
					
					
					
			/* 		Mimba ya ngapi?
					
					
					// if($('#mimba').val() == "") {
// $('#mimba').css({
                  
                    // "background": "#FFCECE"});
					
					// var error22 = 1; 
					
					// } else{ 
					
					// mimbano = $('#mimba').val();
					
					// var error22 = 0; 
					
					// }
					
					
					
					
			Kuzaa mara ngapi?
					
					
					// if($('#kuzaamara').val() == "") {
// $('#kuzaamara').css({
                  
                    // "background": "#FFCECE"});
					
					// var error23 = 1; 
					
					// } else{ 
					
					// var error23 = 0; 
					// kuzaa=$('#kuzaamara').val();
					
					// }		
					
					
					Watoto Hai
					
					
					// if($('#watotohai').val() == "") {
// $('#watotohai').css({
                  
                    // "background": "#FFCECE"});
					
					// var error24 = 1; 
					
					// } else{ 
					
					// hai = $('#watotohai').val();
					
					// var error24 = 0; 
					
					// }		
					
					
					
					
					Haribika
					
					
					// if($('#zilizoharibika').val() == "") {
// $('#zilizoharibika').css({
                  
                    // "background": "#FFCECE"});
					
					// var error25 = 1; 
					
					// } else{ 
					
					// haribika = $('#zilizoharibika').val();
					
					// var error25 = 0; 
					
					// }		 */
					
					
					
					
					
					
					
					
					
					
					
					 if($('#yr').val() == "none") {
$('#yr').css({
                   
                    "background": "#FFCECE"});
					var error2 = 1; 
					
					} else{ 
					
					yearofreg= $('#yr').val(); 
					
					var error2 = 0; 
					
					}
					
					
					
					
					
					
				if($('#num').val() == "") {
$('#num').css({
                    
                    "background": "#FFCECE"});
					var error3 = 1; 
					} else{ 
					
					noofreg = $('#num').val();
					
					var error3 = 0; 
					
					}
					
						
				
					
					
					
					
					
					

					
					
					if(($('#mume').val() == "" && haju == "") ||
($('#mume').val() != "" && haju == "sel")

					) {
$('#mume').css({
	

                    "background": "#FFCECE"});
					var error7 = 1; 
					} else{

					if($('#mume').val() != "") { mumejina =$('#mume').val(); }
					
					else { mumejina="unknown";}
					
					var error7 = 0; 
					
					}
					
					
					//Balozi
					
					if($('#balozi').val() != "") { balo =$('#balozi').val(); }
					
					else { balo="unknown";}
					
					//Nyumba namba.
					
					if($('#nyumba').val() != "") { nyumbano =$('#nyumba').val(); }
					
					else { nyumbano="unknown";}
					
					
					//M kiti
					
					if($('#mkiti').val() != "") { kiti =$('#mkiti').val(); }
					
					else { kiti="unknown";}
					
					
					
					
				/* 	if($('#ur').val() == "") {
$('#ur').css({
                    
                    "background": "#FFCECE"});
					var error8 = 1; 
					} else{ 
					
					ure= $('#ur').val();
					
					var error8 = 0; 
					
					} */
					
					
				
				
				//remove all if not empty
				
				
 
 if($('#kijiji').val() != "") {
$('#kijiji').css({
                    
                    "background": "#FFF"});}
		

if($('#yr').val() != "none") {
$('#yr').css({
                    
                    "background": "#FFF"});}	



if($('#num').val() != "") {
$('#num').css({
                    
                    "background": "#FFF"});}




					
					
					
					


/* if($('#hb').val() != "") {
$('#hb').css({
                    
                    "background": "#FFF"});}
					

					
					
if($('#ur').val() != "") {
$('#ur').css({
                    
                    "background": "#FFF"});}
					
					 */
					
					
					
										
if($('#t1').val() != "") {
$('#t1').css({
                    
                    "background": "#FFF"});}

					
					
					
															
if($('#mimba').val() != "") {
$('#mimba').css({
                    
                    "background": "#FFF"});}
					
					
																				
if($('#kuzaamara').val() != "") {
$('#kuzaamara').css({
                    
                    "background": "#FFF"});}
					
					
																				
if($('#watotohai').val() != "") {
$('#watotohai').css({
                    
                    "background": "#FFF"});}
					
					if($('#da').val() != "") {
$('#da').css({
                    
                    "background": "#FFF"});}
					
																									
if($('#zilizoharibika').val() != "") {
$('#zilizoharibika').css({
                    
                    "background": "#FFF"});}




if($('#mume').val() == "" && haju == "sel") {
$('#mume').css({
                    
                    "background": "#FFF"});}



if($('#mume').val() != "" && haju != "sel") {
$('#mume').css({
                    
                    "background": "#FFF"});}					
					
					
					
					
				
					
					
					
	if(error1==1 || error2==1 || error3==1 ||  error20 ==1 )	{
		
		
		
	alert("Tafadhali jaza maeneo yote yenye rangi ya pinki - ni ya lazima!");
		
		
		
		
		
		
	}			
 else{

       
   
		
		
		$("#kwanza").hide().fadeOut("slow");
	$("#pili").fadeIn();	
 }
 
 					
					
 
 
 
 
 
		
	});
	
	
	//Button 2
	$("#b2prev").click(function() {
		$("#pili").hide();
	$("#kwanza").fadeIn();	});
	
	
	//Button ya save codes
	
	$("#bsave").click(function() {
		
		
		//TT1
					
					
					
					
					// if($('#t1').val() == "") {
// $('#t1').css({
                  
                    // "background": "#FFCECE"});
					
					// var error21 = 1; 
					
					// } else{ 
					
					
					var tt1 = $('#t1').val();
					// var error21 = 0; 
					
					// }
		
		
		//Sukari kwenye mkojo
/* 
 if($("input[name=sukari]:checked").val() == "anasukari"){
			
			sukari_kipimo = "anasukari";
			$('#suk').css({
                  
                    "background": "none"});
					
					error16 = 0;
			
		}
		 else if 
			
			($("input[name=sukari]:checked").val() == "hanasukari"){
			
			sukari_kipimo = "hanasukari";
			$('#suk').css({
                  
                    "background": "none"}); 
					error16 = 0;
			
		}
    else { $('#suk').css({
                  
                    "background": "#FFCECE"});
					
					error16 = 1;
					
	}
					
					
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//Shinikizo
			
			
			
        if($("input[name=shinikizo]:checked").val() == "anashinikizo"){
			
			shinikizo_kipimo = "anashinikizo";
			$('#lv').css({
                  
                    "background": "none"});
			
		}
		 else if 
			
			($("input[name=shinikizo]:checked").val() == "hanashinikizo"){
			
			shinikizo_kipimo = "hanashinikizo";
			$('#lv').css({
                  
                    "background": "none"});
			
		}
    else { $('#lv').css({
                  
                    "background": "#FFCECE"});
	
					var error15 = 1;
	
	}
		
		
		
		if($('#hb').val() == "") {
$('#hb').css({
                    
                    "background": "#FFCECE"});
					var error6 = 1; 
					} else{

					haemo = $('#hb').val();
					
					var error6 = 0; 
					
					}
		
		var datevisit=$('#da').val();
		
	prid=$('#prid').val();;
		
		//Functions for Kaswende validations
		if (!$("input[name=kaswendwfematokeo]:checked").val()) {
			
			
			$('#kaswfeerr').css({
                    
                    "background": "#FFCECE"});
					var errp21 = 1;
      
    } 
	else {
		$('#kaswfeerr').css({
                    
                    "background": "none"});
					var errp21 = 0;
					
					
		if($("input[name=kaswendwfematokeo]:checked").val()=="kaswfepositive"){
		
		var kaswende ="positive"; 
		
		if (!$("input[name=kaswendwfetiba]:checked").val()) {   
$('#kaswtibafe').css({
                    
                    "background": "#FFCECE"});
					var errp22 = 1;

		}
		
		else{
			
			var errp22 = 0;

				$('#kaswtibafe').css({
                    
                    "background": "none"});

		if($("input[name=kaswendwfetiba]:checked").val()=="kaswfeametibiwa") 
			{
				var kaswendetib= "ametibiwa";
				
				
				
			}
			
			
			
		if($("input[name=kaswendwfetiba]:checked").val()=="kaswfehajatibiwa") 
			{
				$('#kaswtibafe').css({
                    
                    "background": "none"});
				var kaswendetib= "hajatibiwa";
				
				
			}	
			
}
		
		
		
		
		
		
	} else{
		var kaswende="negative"; 
		var kaswendetib = "hanakaswendehamnatiba";
	var errp21 = 0;
	var errp22 = 0;
	
	}

	}
	
	
	
	
	
	
	
	//Functions for kaswende male validations
	
	
		if (!$("input[name=kaswendwmematokeo]:checked").val()) {
			
			
			$('#kaswmeerr').css({
                    
                    "background": "#FFCECE"});
					var errp23 = 1;
      
    } 
	else {
		$('#kaswmeerr').css({
                    
                    "background": "none"});
					var errp23 = 0;
					
					
		if($("input[name=kaswendwmematokeo]:checked").val()=="kaswmepositive"){
		
		var kaswendeme ="positive"; 
		
		if (!$("input[name=kaswendwmetiba]:checked").val()) {   
$('#kaswtibame').css({
                    
                    "background": "#FFCECE"});
					var errp24 = 1;

		}
		
		else{

		var errp24 = 0;
				$('#kaswtibame').css({
                    
                    "background": "none"});

		if($("input[name=kaswendwmetiba]:checked").val()=="kaswmeametibiwa") 
			{
				var kaswendetibme= "ametibiwa";
				
				
				
			}
			
			
			
		if($("input[name=kaswendwmetiba]:checked").val()=="kaswmehajatibiwa") 
			{
				$('#kaswtibame').css({
                    
                    "background": "none"});
				var kaswendetibme= "hajatibiwa";
				
				
			}	
			
}
		
		
		
		
		
		
	} else{ var kaswendeme="negative";
	var kaswendetibme = "hanakaswendehamnatibame";
         var errp23 = 0;
		  var errp24 = 0;
		   

	}

	}
		
		
		
		
		
		
		
		//Functions for Magonjwa ya ngono for females validations
		
		
	
	
		if (!$("input[name=ngonofematokeo]:checked").val()) {
			
			
			$('#ngonomatokeoerr').css({
                    
                    "background": "#FFCECE"});
					var errp25 = 1;
      
    } 
	else {
		$('#ngonomatokeoerr').css({
                    
                    "background": "none"});
					var errp25 = 0;
					
					
		if($("input[name=ngonofematokeo]:checked").val()=="ngonofepositive"){
		
		var ngono ="positive"; 
		
		if (!$("input[name=ngonofetiba]:checked").val()) {   
$('#ngonotibafe').css({
                    
                    "background": "#FFCECE"});
					var errp26 = 1;

		}
		
		else{

		var errp26 = 0;
				$('#ngonotibafe').css({
                    
                    "background": "none"});

		
				
				
				var ngonotibafemale= $("input[name=ngonofetiba]:checked").val();
					
			
}
		
		
		
		
		
		
	} else{ var ngono="negative";
	var ngonotibafemale="hanagonohamnatiba";
         var errp25 = 0;
		  var errp26 = 0;
		   

	}

	}
	
	
	
	
	
	//mtaa
	
	if($('#mtaa').val() != "") {
		
		 mta = $('#mtaa').val();
		
}else{    mta = "unknown";    }


//Functions for Magonjwa ya ngono for females validations
		
		
	
	
		if (!$("input[name=ngonomematokeo]:checked").val()) {
			
			
			$('#ngonomematokeoerr').css({
                    
                    "background": "#FFCECE"});
					var errp27 = 1;
      
    } 
	else {
		$('#ngonomematokeoerr').css({
                    
                    "background": "none"});
					var errp27 = 0;
					
					
		if($("input[name=ngonomematokeo]:checked").val()=="ngonomepositive"){
		
		var ngonome ="positive"; 
		
		if (!$("input[name=ngonometiba]:checked").val()) {   
$('#ngonotibame').css({
                    
                    "background": "#FFCECE"});
					var errp28 = 1;

		}
		
		else{

		var errp28 = 0;
				$('#ngonotibame').css({
                    
                    "background": "none"});

		var ngonotibamale=$("input[name=ngonometiba]:checked").val(); 
			
}
		
		
		
		
		
		
	} else{ var ngonome ="negative";
	var ngonotibamale= "hamnangonohamnatiba";
         var errp27 = 0;
		  var errp28 = 0;
		   

	}

	}	
	 */
	
	// 
	
	
	// Functions checking for PMCT VALIDATION
	
/* 
	if (!$("input[name=maambukizife]:checked").val() || !$("input[name=maambukizime]:checked").val()) {
			
			
			$('#mambuki').css({
                    
                    "background": "#FFCECE"});
					var errp31 = 1;
      
    } 
	else {
		$('#mambuki').css({
                    
                    "background": "none"});
					var errp31 = 0;
					if ($("input[name=maambukizife]:checked").val() == "feanamaambukizi") { var maambukizivvu="feanamaambukizi"} else {var maambukizivvu= "fehanamaambukizi"}
					
					if ($("input[name=maambukizime]:checked").val() == "meanamaambukizi") { maambukizivvume="meanamaambukizi"} else { var maambukizivvume= "mehanamaambukizi"}
					
	}
	
	
	
	//Unasihi vLIDATION
	
	
	if (!$("input[name=unasihife]:checked").val() || !$("input[name=unasihime]:checked").val()) {
			
			
			$('#unasihiid').css({
                    
                    "background": "#FFCECE"});
					var errp34 = 1;
      
    } 
	else {
		$('#unasihiid').css({
                    
                    "background": "none"});
					var errp34 = 0;
					var unasike = $("input[name=unasihife]:checked").val();
					
					var unasime=$("input[name=unasihime]:checked").val();
					
	}
	
	//Unasihi baada
	
	
	//Unasihi  baada vLIDATION
	
	
	if (!$("input[name=unasihibaadayakupimafe]:checked").val() || !$("input[name=unasihibaadayakupimame]:checked").val()) {
			
			
			$('#unasihiidb').css({
                    
                    "background": "#FFCECE"});
					var errp34 = 1;
      
    } 
	else {
		
		$('#unasihiidb').css({
                    
                    "background": "none"});
					var errp34 = 0;
					var unasihibaada = $("input[name=unasihibaadayakupimafe]:checked").val();
					var unasihibaadame=$("input[name=unasihibaadayakupimame]:checked").val();
					
					
					
	}
	
	
	
	//VVU AMEPIMA VALIDATION
	
	
	//
	
	
	if (!$("input[name=kipimovvufe]:checked").val() || !$("input[name=kipimovvume]:checked").val()) {
$('#kip').css({ "background": "#FFCECE"}); var errp35 = 1; } 
	else {
							var pmkipimovvufe  = $("input[name=kipimovvufe]:checked").val();
							var pmkipimovvume  = $("input[name=kipimovvume]:checked").val();

		$('#kip').css({
                    
                    "background": "none"});
					var errp35 = 0;
					
	}
	
	 */
	
	
	//VVU 10
	
	/* 
	if (!$("input[name=kipimo1vvufe]:checked").val() || !$("input[name=kipimo1vvume]:checked").val()) {
			
			
			$('#kip1').css({
                    
                    "background": "#FFCECE"});
					var errp36 = 1;
      
    } 
	else {
		$('#kip1').css({
                    
                    "background": "none"});
					var errp36 = 0;
					
					var kipimo1vvufe  = $("input[name=kipimo1vvufe]:checked").val();
					var kipimo1vvume  = $("input[name=kipimo1vvume]:checked").val();
					
	} */
	
	
	
	//Kipimo cha pili cha vvu
	
	//VVU 10
	
	
	if (!$("input[name=kipimo2vvufe]:checked").val()) {
			
			
			$('#kip2').css({
                    
                    "background": "#FFCECE"});
					var errp41 = 1;

      
    } 
	else {
		$('#kip2').css({
                    
                    "background": "none"});
					var errp41 = 0;
					var pmkipimo2vvufe  = $("input[name=kipimo2vvufe]:checked").val();
					
	}
	
	
	//uLISHAJI WA Mtoto
	
	/* 
	if (!$("input[name=ulishajiwamtoto]:checked").val()) {
			
			
			$('#ulishajit').css({
                    
                    "background": "#FFCECE"});
					var errp42 = 1;
      
    } 
	else {
		$('#ulishajit').css({
                    
                    "background": "none"});
					var errp42 = 0;
				ulishajivar=	$("input[name=ulishajiwamtoto]:checked").val();
					
	}
	 */
	
	
	//Mebendazole amepata ? au hajapata?
	
	/* 
	if (!$("input[name=meb]:checked").val()) {
			
			
			$('#mebend').css({
                    
                    "background": "#FFCECE"});
					var errp43 = 1;
      
    } 
	else {
		$('#mebend').css({
                    
                    "background": "none"});
					var errp43 = 0;
					albendazolevar=	$("input[name=meb]:checked").val();
					
	} */
	
	
	//BS MALARIA
	
	/* 
	if (!$("input[name=bs]:checked").val()) {
			
			
			$('#bsid').css({
                    
                    "background": "#FFCECE"});
					var errp44 = 1;
      
    } 
	else {
		$('#bsid').css({
                    
                    "background": "none"});
					var errp44 = 0;
					
					malariavar = $("input[name=bs]:checked").val();
					
	}
	 */
	
	
	
	//Hati punguzo
	
	
	/* 
	
	if (!$("input[name=hati]:checked").val()) {
			
			
			$('#hatiid').css({
                    
                    "background": "#FFCECE"});
					var errp45 = 1;
      
    } 
	else {
		$('#hatiid').css({
                    
                    "background": "none"});
					var errp45 = 0;
					hativar =$("input[name=hati]:checked").val();
					
	}
	 */
	
	
	//IPTS AND IFAs
	// IPT1
				
				if($('#ipt1').val() != "") { ipt1var =$('#ipt1').val(); }
					
					else { ipt1var="unknown";}
					
					
					/* // IPT2
				
				if($('#ipt2').val() != "") { ipt2var =$('#ipt2').val(); }
					
					else { ipt2var="unknown";}
					
					
					
					// IPT3
				
				if($('#ipt3').val() != "") { ipt3var =$('#ipt3').val(); }
					
					else { ipt3var="unknown";}
					
					
					// IPT4
				
				if($('#ipt4').val() != "") { ipt4var =$('#ipt4').val(); }
					
					else { ipt4var="unknown";}*/
					
					// IFA1
				
				if($('#ifa1').val() != "") { ifa1var =$('#ifa1').val(); }
					
					else { ifa1var="unknown";}
					
					
					//rchno
					if($('#rchnoe').val() != "") { var rchnovar =$('#rchnoe').val(); }
					
					else { fsf="unknown";}
					
					 
					// IFA2
				
				/* if($('#ifa2').val() != "") { ifa2var =$('#ifa2').val(); }
					
					else { ifa2var="unknown";}
					
					
					// IFA1
				
				if($('#ifa3').val() != "") { ifa3var =$('#ifa3').val(); }
					
					else { ifa3var="unknown";}
					
					
					// IFA4
				
				if($('#ifa4').val() != "") { ifa4var =$('#ifa4').val(); }
					
					else { ifa4var="unknown";}
	 */
	
	
	
	
	
	
	//rufaadate
	
	//M rufaa date
					/* 
					if($('#rufaadate').val() != "") { rufdate =$('#rufaadate').val(); }
					
					else { rufdate="unknown";}
					
					 */
					
					
					//ALIKOPELEKWA
					/* 
					if($('#alikopele').val() != "") { rufalipopelekwa =$('#alikopele').val(); }
					
					else { rufalipopelekwa="unknown";}
					
					
					//ALIKOTOKA
					
					if($('#alipoto').val() != "") { rufalipotoka =$('#alipoto').val(); }
					
					else { rufalipotoka="unknown";}
					
					
					if($('#mao').val() != "") { rufmaoni =$('#mao').val(); }
					
					else { rufmaoni="unknown";} */
					
					//kuharibika mimba
					
		if($("#km").is(":checked")){var mahudhuriokuharibikam="ndiyokuharibikamimba"; }else{ var mahudhuriokuharibikam="hapanakuharibikamimba"; }
		
		//Protenuria
		if($("#prote").is(":checked")){var mahudhurioprotenuria="ndiyoprotenuria"; }else{ var mahudhurioprotenuria="hapanaprotenuria"; }
		
		//uzito
		if($("#kutoongezekauzito").is(":checked")){var mahudhuriokutoongezekauzito="ndiyokutoongezekauzito"; }else{ var mahudhuriokutoongezekauzito="hapanakutoongezekauzito"; }
		
		//mlalo mbaya
		if($("#ml").is(":checked")){var mahudhurioml="ndiyoml"; }else{ var mahudhurioml="hapanaml"; }
		
		//cizor
		if($("#cs").is(":checked")){var mahudhuriocs="ndiyocs"; }else{ var mahudhuriocs="hapanacs"; }
		
		//tb
		if($("#tb").is(":checked")){var mahudhuriotb="ndiyotb"; }else{ var mahudhuriotb="hapanatb"; }
		
		//anaemia
		if($("#anaemia").is(":checked")){var mahudhurioanaemia="ndiyoanaemia"; }else{ var mahudhurioanaemia="hapanaanaemia"; }
		
		//bp
		if($("#bp").is(":checked")){var mahudhuriobp="ndiyobp"; }else{ var mahudhuriobp="hapanabp"; }
		
		//Damu ukeni
		if($("#du").is(":checked")){var mahudhuriodu="ndiyodu"; }else{ var mahudhuriodu="hapanadu"; }
		
		
		//Mimba zaidi ya 4
		if($("#m4").is(":checked")){var mahudhuriom4="ndiyom4"; }else{ var mahudhuriom4="hapanam4"; }
		
		//Vacuum
		if($("#ve").is(":checked")){var mahudhuriove="ndiyove"; }else{ var mahudhuriove="hapanave"; }
		
		
		
					
					
					
	
	
	//Checking at validations
	
	
					
	if( errp41==1
	
	)	{
		
		
		
	alert("Tafadhali jaza maeneo yote yenye rangi ya pinki - ni ya lazima!");
		
		
		
		
		
		
	}			
 else{
	 
	//Saving data to database

	//balo,kiti,nyumbano,haemo,shinikizo_kipimo,ure,sukari_kipimo,umri_chini,umri_juu,tt1,tt2,tt3,tt4,mimbano,kuzaa,hai,haribika,kifo,kaswende,kaswendeme,kaswendetib,kaswendetibme,maambukizivvu,maambukizivvume,
	
	//ulishajivar,albendazolevar,hativar,malariavar,ipt1var,ipt2var,ipt3var,ipt4var,ifa1var,ifa2var,ifa3var,ifa4var,rufdate,rufalipopelekwa,rufalipotoka,rufmaoni
	
	
$.ajax({
    type: "POST",
    url: 'powercharts_antinatal_insert_visit.php',
    data:"rchnox="+rchnovar+"&ipt1="+ipt1var+"&ifa1="+ifa1var+"&mahudhuriokm="+mahudhuriokuharibikam+"&mahudhuriopro="+mahudhurioprotenuria+"&mahudhuriokutoong="+mahudhuriokutoongezekauzito+"&mahudhuriomlalo="+mahudhurioml+"&mahudhuriocizor="+mahudhuriocs+"&mahudhuriotuba="+mahudhuriotb+"&mahudhurioana="+mahudhurioanaemia+"&mahudhuriobloodp="+mahudhuriobp+"&mahudhuriodamu="+mahudhuriodu+"&mahudhuriomz4="+mahudhuriom4+"&mahudhuriov="+mahudhuriove+"&pmctkipimo2vvufe="+pmkipimo2vvufe+"&ttn1="+tt1,
    success: function(data){
        alert(data);
		
		location.href="searchvisitorsoutpatientlistrchmahudhurio.php";
		
    }
});
 
   
  


 }
	
	
	
	
		
		
		
	});
	
	
	
	
	//Functions for 
		
	
	
	
	//show kaswende ametibiwa ndiyo/hapana kwa female
	$("#kaswendwfe1").click(function() {
		
	$("#kaswtibafe").show(1000);
	
	

	});
	
	
	
	
	
	//Hide kaswende ametibiwa ndiyo/hapana kwa female
	$("#kaswendwfe2").click(function() {
		
	$("#kaswtibafe").hide(1000);
	
	$('#kaswendwfe1t').removeAttr("checked");
	$('#kaswendwfe2t').removeAttr("checked");


	});
	
	
	
	
	//show kaswende ametibiwa ndiyo/hapana kwa female
	$("#kaswendwme1").click(function() {
		
	$("#kaswtibame").show(1000);
	
	

	});
	
	
	
	
	
	//Hide kaswende ametibiwa ndiyo/hapana kwa female
	$("#kaswendwme2").click(function() {
		
	$("#kaswtibame").hide(1000);
	
	$('#kaswendwme1t').removeAttr("checked");
	$('#kaswendwme2t').removeAttr("checked");
	});
	
	
	
	//Ngono
	
	
	//show kaswende ametibiwa ndiyo/hapana kwa female
	$("#ngonofe1").click(function() {
		
	$("#ngonotibafe").show(1000);
	
	

	});
	
	
	
	
	
	//Hide kaswende ametibiwa ndiyo/hapana kwa female
	$("#ngonofe2").click(function() {
		
	$("#ngonotibafe").hide(1000);
	
	$('#ngonofe1t').removeAttr("checked");
	$('#ngonofe2t').removeAttr("checked");


	});
	
	
	
	
	//show ngono ametibiwa ndiyo/hapana kwa female
	$("#ngonome1").click(function() {
		
	$("#ngonotibame").show(1000);
	
	

	});
	
	$("#ngonome2").click(function() {
		
	$("#ngonotibame").hide(1000);
	
	

	});
	
	
	
	
	
	//Hide kaswende ametibiwa ndiyo/hapana kwa female
	$("#ngonomet").click(function() {
		
	$("#ngonotibame").hide(1000);
	
	$('#ngonome1t').removeAttr("checked");
	$('#ngonome2t').removeAttr("checked");

});


$("#patientlist").click(function() {
		
	$("#ngonotibame").hide(1000);
	
	$('#ngonome1t').removeAttr("checked");
	$('#ngonome2t').removeAttr("checked");

});




	
	
	
	
	
	
	
	
	
	
	
	
$(".comment_button").click(function() {

var test = $("#content").val();
var dataString = 'content='+ test;

if(test=='')
{
alert("Please Enter Some Text");
}
else
{
$("#flash").show();
$("#flash").fadeIn(400).html('<img src="ajax-loader.gif" align="absmiddle"> <span class="loading">Loading Comment...</span>');

$.ajax({
type: "POST",
url: "powercharts_antinatal_insert_visit.php",
data: dataString,
cache: false,
success: function(html){
$("#display").after(html);
document.getElementById('content').value='';
document.getElementById('content').focus();
$("#flash").hide();
}
});
} return false;
});
});
</script>



<?php 
if(isset($_GET['rchno'])){
	
	$pn= $_GET['rchno'];
	
	
	$checkifyupo = "SELECT * FROM tbl_rch WHERE rch_id= '$pn' AND status='active'";
if(mysqli_num_rows($qresult=mysqli_query($conn,$checkifyupo))>0) {

$fetchdet=mysqli_fetch_array($qresult);


	
	
	$select_Patient_Details = mysqli_query($conn,"
				select pr.Patient_Name,pr.Date_Of_Birth,pr.Member_Number,pr.Gender from
				    tbl_patient_registration pr
					where pr.registration_id ='$fetchdet[pr_id]'") or die(mysqli_error($conn));
							
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
            
            
	
} 



?>
        <div class="tabcontents" >
		
			
            <div id="kwanza">
               <center> 
			   
	<table  class="" border="0"  align="left" style="width:999px"  >
                        <tr>
                            <td width="20%" class="powercharts_td_left">Tarehe:</td><td width="40%"><input  type="text" value="<?php 
							
							$t= date("d-M-Y");
							echo date("jS F Y",strtotime($t));
							
							?>" name="tarehe1" id='da'><input type="hidden" id="rchnoe" value="<?php echo $pn; ?>"></td>
                            <td  style="text-align:right;" width="20%">Namba Ya Usajili:</td><td  width="40%" colspan="2">
							
							<input readonly value="<?php echo $fetchdet['nayausajili']; ?>" style="width:100px;"  type="text" placeholder="Andika Namba" ></td>
                        </tr>
						<tr>
                            <td width="20%" class="powercharts_td_left">Jina Kamili la Mteja:</td><td ><input name="jinakamili" readonly value="<?php echo strtoupper( $fname." ".$mname." ".$lname); ?>" type="text"></td>
                            <td  colspan="" align="right" style="text-align:right;">Umri:</td><td>
							<input name="" readonly id="umri" type="text" value="<?php echo $age; ?>" placeholder="umri" style="width:70px;"></td> 
                        </tr>
						
						<tr>
                            <td width="20%" class="powercharts_td_left">Jina la Kijiji/Kitongoji:</td><td><input value="<?php echo $fetchdet['kijiji']; ?>" readonly type="text"></td>
                            <td  colspan="" align="right" style="text-align:right;">Balozi:</td><td>
							<input readonly value="<?php echo $fetchdet['balozi']; ?>" type="text" placeholder="Jina la Balozi" style="width:240px;"></td> 
                        </tr>
						
						<tr>
                            <td width="20%" class="powercharts_td_left">Mtaa/Barabara:</td><td><input value="<?php echo $fetchdet['mtaa']; ?>" type="text" readonly ></td>
                            <td  colspan="" align="right" style="text-align:right;">Na ya Nyumba:</td><td>
							<input readonly value="<?php echo $fetchdet['nyumbano']; ?>" type="text" placeholder="Na ya Nyumba" style="width:240px;"></td> 
                        </tr>
						
						
						<tr>
                            <td width="20%" class="powercharts_td_left">Jina la Mume/Mwenza:</td><td><input value="<?php echo $fetchdet['mume']; ?>" readonly type="text" >
							<!--<br><input name="hana"  id="hajulikani" type="checkbox">Jina Halijulikani--></td>
                            <td  colspan="" align="right" style="text-align:right;">Jina la M/Kiti <br>Serikali ya Mtaa/Kitongoji:</td><td>
							<input readonly value="<?php echo $fetchdet['mkiti']; ?>" type="text" placeholder="Jina la M/Kiti" style="width:240px;"><p ></p></td> 
                        </tr>
						
						
						</table> 
						
						<table align="left">	   
                       
						
						
							<tr><td align="center" colspan="2" style="padding-left:480px; border:none"> <button id="b1kwanza" style="cursor:pointer" class="art-button-green" > Next </button>  </td></tr>
							</table>
							
						
						</div>
						
						
						<!--Div ya pili------------------------------------------------------------->
						
						<div id="pili" >
               <center> 
			   
<!--	<table  class="" border="0"  align="left" style="width:650px"  >
	
	
                        <tr>
                            <th  class="powercharts_td_left" colspan="2">Kipimo cha Kaswende</th>
                        </tr>
						<tr>
                            <td width="30%" align="center" style="text-align:center; font-weight:bold;">KE</td>
							<td style="text-align:center; font-weight:bold;" class="powercharts_td_left" >ME</td>
							
							
                             
                        </tr>
						
						<tr>
                            <td  class="powercharts_td_left">
							
							<table style="width:250px;">
							<tr id="kaswfeerr" >
							<td width="30%">Matokeo:</td>
							<td width="30%"><input value="kaswfepositive" type="radio" id="kaswendwfe1" name="kaswendwfematokeo">Positive</td><td width="30%"><input id="kaswendwfe2"  name="kaswendwfematokeo" value="kaswfenegative" type="radio">Negative</td>
							</tr>
							
							<tr id="kaswtibafe">
							<td>Ametibiwa?:</td>
							<td ><input  type="radio" value="kaswfeametibiwa" id="kaswendwfe1t" name="kaswendwfetiba">Ndiyo</td><td><input  value="kaswfehajatibiwa"  id="kaswendwfe2t" name="kaswendwfetiba" type="radio">Hapana</td>
							</tr>
							
							</table>
							
							</td><td>
							
							
							<table>
							<tr id="kaswmeerr">
							<td>Matokeo:</td>
							<td ><input  type="radio" id="kaswendwme1" value="kaswmepositive" name="kaswendwmematokeo">Positive</td><td><input id="kaswendwme2"  name="kaswendwmematokeo" type="radio" value="kaswmenegative">Negative</td>
							</tr>
							
							<tr id="kaswtibame">
							<td>Ametibiwa?:</td>
							<td ><input value="kaswmeametibiwa" type="radio" id="kaswendwme1t" name="kaswendwmetiba" >Ndiyo</td><td><input value="kaswmehajatibiwa"  id="kaswendwme2t" name="kaswendwmetiba" type="radio">Hapana</td>
							</tr>
							
							</table>
							
							
							
							
							
							
							
							
							
							
							</td>
                             
                        </tr>
						
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
                            <td colspan="2" style="text-align:center; font-weight:bold;"> Vipimo Vya Magonjwa ya Ngono</td>
							</tr>
							
							 
                       
						
						
						<tr>
						
                            <td width="30%" class="powercharts_td_left">KE</td><td>ME</td></tr>
                            <td  colspan="" align="right" style="text-align:right;">
							
							<table>
							<tr id="ngonomatokeoerr" >
							<td>Matokeo:</td>
							<td><input type="radio" id="ngonofe1" name="ngonofematokeo" value="ngonofepositive">Positive</td><td><input id="ngonofe2" value="ngonofenegative"  name="ngonofematokeo" type="radio">Negative</td>
							</tr>
							
							<tr id="ngonotibafe">
							<td>Ametibiwa?:</td>
							<td ><input type="radio" id="ngonofe1t" name="ngonofetiba" value="ngonofeametibiwa">Ndiyo</td><td><input  id="ngonofe2t" name="ngonofetiba" value="ngonofehajatibiwa" type="radio">Hapana</td>
							</tr>
							
							</table></td>
							
							<td>
							
							
							<table>
							<tr id="ngonomematokeoerr" >
							<td>Matokeo:</td>
							<td><input type="radio" id="ngonome1" name="ngonomematokeo" value="ngonomepositive">Positive</td><td><input id="ngonome2" value="ngonomenegative"  name="ngonomematokeo" type="radio">Negative</td>
							</tr>
							
							<tr id="ngonotibame">
							<td>Ametibiwa?:</td>
							<td ><input type="radio" id="ngonome1t" name="ngonometiba" value="ngonomeametibiwa">Ndiyo</td><td><input  id="ngonome2t" name="ngonometiba" value="ngonomehajatibiwa" type="radio">Hapana</td>
							</tr>
							
							</table>
							
							
							
							
						
						
						<tr>
                            <td colspan="10" style="height:27px;">&nbsp;</td></tr>
                        </table> -->
						
						
							
							
						
						</table> 
						<table style="margin-top:10px; float:left;">
						<tr>
                            <th   colspan"2" style="font-weight:bold;background-color:#006400;color:white" ><b>MAHUDHURIO YA MARUDIO</b></th><th style="font-weight:bold;background-color:#006400;color:white"><b>HUDUMA YA PMCT</b></th>
							<th style="font-weight:bold;background-color:#006400;color:white"><b>MALARIA</b></th><th style="font-weight:bold;background-color:#006400;color:white"><b>HUDUMA YA TT</b></th><th style="font-weight:bold;background-color:#006400;color:white"><b>IRONIC FOLIC ACID</b></th>
							
							</tr>
							<tr><td>
							<table style="width:350px;">
							
							<tr><td><input type="checkbox" id="km"> (KM)<span style="font-size:10px; "> <i> Kuharibika Mimba</i></span></td>
							<td><input type="checkbox" id="anaemia">(A)<span style="font-size:10px; "> <i>Anaemia</i></span></td>
							</tr>
							<tr><td><input type="checkbox" id="prote">(P) <span style="font-size:10px; "> <i>Protenuria</i></span></td><td><input type="checkbox" id="bp">(H)<span style="font-size:10px; "> <i>High BP</i></span>
							
							</td> </tr>
							<tr><td><input type="checkbox" id="kutoongezekauzito">(U)<span style="font-size:10px; "> <i>Kutoongezeka Uzito<i></span></td><td>
							<input type="checkbox" id="du">(D)
							<span style="font-size:10px; "> <i>Damu Ukeni</i></span></td>
							</tr>
							
							
							<tr><td><input type="checkbox" id="ml">(M)<span style="font-size:10px; "> <i> Mlalo mbaya wa mtoto</i></span></td>
							<td><input type="checkbox" id="m4">(M4+)<span style="font-size:10px; "> <i>Mimba 4+</i></span></td>
							</tr>
							
							<tr><td><input type="checkbox" id="cs"> (C/S)<span style="font-size:10px; "> <i>Kuzaa kwa Operation</i></span></td>
							<td><input type="checkbox" id="ve">(VE)<span style="font-size:10px; "> <i>Vacuum Extraction</i></td>
							</tr>
							<tr><td><input type="checkbox" id="tb"> TB</td><td></td> </tr>
							
							</table>
							
							</td><td>
							
							<table  class="" border="0" style=" AUTO; height:AUTO; margin-bottom:10px; " align="right" >
						
                            
							
							<table style="width:200px; margin-top: -1px;">
							
							
							
							<tr id="kip2" ><td>Matokeo ya Kipimo cha VVU</td><td style="width: 50%"><input type="radio" name="kipimo2vvufe" value="positivekipimo2fe">Positive<br><input type="radio" name="kipimo2vvufe" value="negativekipimo2fe" >Negative<br><input type="radio" name="kipimo2vvufe" value="bado" >Hajapima</td></tr>
							</table>
							
							
							
							
							
							
						
							
							
							
							</td>  <td><table>
							<tr><td  colspan="2" ><B>Andika tarehe ya IPT</B> </td></tr> 
							
							<tr><td>IPT</td><td><input id="date" type="text" style="width:110px;" name="ds"></td></tr>
							</table>
						</td><td>
						<table><tr>
                            <td colspan="3" width="50%" class="powercharts_td_left" style="font-weight:bold;" >Tarehe ya TT</td></tr>
                           
							
							
							
							<tr><td colspan="2" >TT</td><td> <input id="date2" type="text" name="tt1" style="width:;"><a href=""></a> <br>
							
							</td></tr> </table>
					
						
						
						</td>
						<td><table>
						<tr><td align="right" style="text-align:right" ><b>Idadi ya Vidonge vya "I" Iron/ "FA" Folic Acid</b> </td>
							
							<td>
							<input type="text" style="width:100px;" name="mimbasdasba" id="ifa1"> 
							
							</td></tr>
						</table>
						
						</td>
						
						
						
						
						</tr>
							
							
							
							
							
							
							
							
							</table>
							

							<br><br><br><br>
							<span style="margin-top:35px;"> <button id="b2prev" style="cursor:pointer" class="art-button-green"> Prev </button>&nbsp;
							
							
							<button id="bsave" style="cursor:pointer" class="art-button-green"> Update </button>
									
									
									
								
							
							</span>
						
						</div>
						
							
							
						
						
						</div>
						<div id="modal-rch" style="height:auto; width:auto;background-color:none">
						<b>Loading.....</b>
						
						</div>


						
        
						
    <?php
} }
    include("./includes/footer.php");
?>

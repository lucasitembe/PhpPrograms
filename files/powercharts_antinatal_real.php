
<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
    
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
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
    
?>
   <script> 
        $(function() {
           $( "#datepickery" ).datepicker();
        });
    </script> 





<!-- link menu -->

<a href="searchvisitorsoutpatientlistforrch.php" class="art-button-green" >Back</a></td>
             
<!--

<fieldset style="margin-top:1px; style='overflow-y: scroll; height:475px">  -->
<legend style="background-color:#006400;color:white;padding:2px;" align="right"><b>REJESTA YA WATOTO</b></legend>
   <div class="powercharts_body">
                				
<script type="text/javascript" >
   
//		
//$.ajax({
//    type: "POST",
//    url: 'powercharts.php',
//    data:"rch2="+f+"&h="+h,
//    success: function(data){
//        $("#modal-rch").html(data);
//    }
//}); 
//);
//	
//$.ajax({
//    type: "POST",
//    url: 'powercharts_antinatal_insert.php',
//    data:"cont="+ kadi +"&name="+ unasime+"&datevi="+ datevisit+"&pri="+ prid+"&kito="+ kitongoji+"&mtaawa="+ mta+"&jinalamume="+ mumejina+"&yearof="+ yearofreg+"&noofre="+ noofreg+"&umriwamjamzito="+umriwasasa+"&balozi="+balo+"&mkiti="+kiti+"&nyumbanamba="+nyumbano+"&hb="+haemo+"&shini="+shinikizo_kipimo+"&uref="+ure+"&sukari="+sukari_kipimo+"&umrichini20="+umri_chini+"&umrijuu35="+umri_juu+"&ttn1="+tt1+"&ttn2="+tt2+"&ttn3="+tt3+"&ttn4="+tt4+"&mimbanambari="+mimbano+"&kuzaamar="+kuzaa+"&watotoha="+hai+"&haribu="+haribika+"&deathinweek="+kifo+"&kaswe="+kaswende+"&kasweme="+kaswendeme+"&kaswtib="+kaswendetib+"&kaswtibme="+kaswendetibme+"&ulishaji="+ulishajivar+"&albend="+albendazolevar+"&hati="+hativar+"&malar="+malariavar+"&ipt1="+ipt1var+"&ipt2="+ipt2var+"&ipt3="+ipt3var+"&ipt4="+ipt4var+"&ifa1="+ifa1var+"&ifa2="+ifa2var+"&ifa3="+ifa3var+"&ifa4="+ifa4var+"&rufadate="+rufdate+"&rufpelekwa="+rufalipopelekwa+"&rufkotoka="+rufalipotoka+"&rufmaon="+rufmaoni+"&ngonof="+ngono+"&ngonotibaf="+ngonotibafemale+"&ngonom="+ngonome+"&ngonotibam="+ngonotibamale+"&mahudhuriokm="+mahudhuriokuharibikam+"&mahudhuriopro="+mahudhurioprotenuria+"&mahudhuriokutoong="+mahudhuriokutoongezekauzito+"&mahudhuriomlalo="+mahudhurioml+"&mahudhuriocizor="+mahudhuriocs+"&mahudhuriotuba="+mahudhuriotb+"&mahudhurioana="+mahudhurioanaemia+"&mahudhuriobloodp="+mahudhuriobp+"&mahudhuriodamu="+mahudhuriodu+"&mahudhuriomz4="+mahudhuriom4+"&mahudhuriov="+mahudhuriove+"&pmctmaambukizivvufe="+maambukizivvu+"&pmctmaambukizivvume="+maambukizivvume+"&pmctunasike="+unasike+"&pmctunasime="+unasime+"&pmctunasibaadake="+unasihibaada+"&pmctunasibaadame="+unasihibaadame+"&pmctkipimovvufe="+pmkipimovvufe+"&pmctkipimovvume="+pmkipimovvume+"&pmctkipimo1vvufe="+kipimo1vvufe+"&pmctkipimo1vvume="+kipimo1vvume+"&pmctkipimo2vvufe="+pmkipimo2vvufe+"&mimba_umri="+umriwamimba+"&mto="+mwishomto,
//    success: function(data){
//        alert(data);
//		location.href="searchvisitorsoutpatientlistrchmahudhurio.php";
//    }
//});
//
//
//$.ajax({
//type: "POST",
//url: "powercharts_antinatal_insert.php",
//data: dataString,
//cache: false,
//success: function(html){
//$("#display").after(html);
//document.getElementById('content').value='';
//document.getElementById('content').focus();
//$("#flash").hide();
//}
//});
//} return false;
//});

</script>

<script>
  $('#next_button').on('click',function(){
     alert('mmmmmm'); 
  });

</script>

<?php 
if(isset($_GET['pn'])){
	
	$pn= $_GET['pn'];

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
    <div class="tabcontents" >
        <ul style="display:none">
            <li><a href="#tabs-1">HISTORY TAKING</a></li>
            <li><a href="#tabs-2">PHYSICAL EXAMINATION</a></li>
            <li><a href="#tabs-3">RESPIRATION &amp; CARDIOVASCULAR SYSTEMS</a></li>
            <li><a href="#tabs-4">LABORATORY TESTS &AMP; CHEST X-RAY</a></li>
        </ul>
        
        <div style="width:100%;display:none" id="kwanza" >
            
            
            
            
            
            
            
            
            
               <center> 
			   
	<table  class="" border="0"  align="left" style="width:100%;margin-top:-5px;"  >
            <tr>
                <td width="20%" class="powercharts_td_left" style="text-align:right">
                    Tarehe ya leo
                </td>
                <td width="40%">
                    <input  type="text" name="tarehe1" id='da' readonly  value="<?php $t= date("d-M-Y");
                                            echo date("jS F Y",strtotime($t));

                                            ?>">
                    <input type="hidden" id="prid" value="<?php echo $pn; ?>">
                </td>
                <td  style="text-align:right;" width="20%">Namba Ya Utambulisho</td><td  width="40%" colspan="2">
                    <select name="noyear" id="yr">
                        <option value="none">Chagua Mwaka</option>					
                        <option value="2015" >2015</option>
                        <option value="2014">2014</option>
                        <option value="2013">2013</option>
                        <option value="2012">2012</option>
                        <option value="2011">2011</option>
                        <option value="2010">2010</option>
                    </select>

                    <input style="width:100px;" name="" type="text" placeholder="Andika Namba" id="num">
                </td>
            </tr>
            <tr>
                <td width="20%" class="powercharts_td_left" style="text-align:right">Namba ya usajili wa vizazi(Birth registration)</td><td ><input name="jinakamili" value="" type="text"></td>
                <td  colspan="" align="right" style="text-align:right;">Jina la Mtoto</td><td>
                <input name="" id="" type="text" value="" style="width:240px;"></td> 
            </tr>
						
            <tr>
                <td width="20%" class="powercharts_td_left" style="text-align:right">Tarehe ya kuzaliwa</td><td><input name="name" id="kijiji" type="text"></td>
                <td  colspan="" align="right" style="text-align:right;">Mahali Anapoishi(Kitongoji/Mtaa) au Jina la mwenyekiti wa kitongoji</td><td>
                <input name="baloz" id="balozi" type="text" style="width:240px;"></td> 
            </tr>
						
            <tr>
                <td width="20%" class="powercharts_td_left" style="text-align:right">Jinsia</td>
                  <td>
                      <select id="jinsia" style="width:400px">
                        <option value="Me">Me</option>
                        <option value="Ke">Ke</option>
                    </select>
                  </td>
            </tr>					
    </table> 
						
<table align="left" style="width:100%">	   					
    <tr><td>
        <table  class="" border="0" style=" width:65%;margin-top:0px; " align="right" >
         <tr>
            <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya Chanjo</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Tarehe ya Chanjo ya PENTA</td></tr>
            <td width="40%"  colspan="" align="right" style="text-align:right;">
                <table width="100%">
                    <tr>
                        <td>
                            BCG &nbsp;&nbsp;&nbsp;
                        </td>
                        <td><input id="date" type="text" name="tt1" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td >
                           OPVO
                        </td>
                        <td>
                            <input id="opv_date" type="text" name="tt1" style="width:240px;" class="nn">
                        </td>
                    </tr>
                </table>
							
							
        </td><td >
        <table width="100%">
        <tr><td style="text-align:right;width:100px;">1. </td><td width="25%"> <input class="nn" type="text" style="width:340px;" name="mimba" id="penta1"></td></tr>
        <tr><td style="text-align:right;width:100px;">2.</td><td> <input type="text" style="width:340px;" name="mimbanamba" class="nn" id="penta2"></td></tr>

        <tr><td style="text-align:right;width:100px;">3.</td><td> <input class="nn" type="text" style="width:340px;" name="mimbanamba" id="penta3"></td></tr>
     </table>
    </td></tr>
    </table>						
        <table style="margin-top:0px; width:30%;">
            <tr style="background-color:#006400;color:white">
                <th  class="powercharts_td_left">Taarifa za Mama</th>
            </tr>
            <tr>
                <td  colspan="" align="right" style="text-align:right;">Jina la Mama</td><td>
                <input name="" id="mamajina" type="text" style="width:240px;"></td> 
            </tr>
            <tr>
                <td  colspan="" align="right" style="text-align:right;">Ana kinga ya pepopunda a(TT2+)?</td><td>
                <input name="" id="tt2" type="text" style="width:240px;"></td> 
            </tr>
            
            <tr>
                <td  colspan="" align="right" style="text-align:right;">Hali ya VVU</td><td>
                <input name="" id="vvu" type="text" style="width:240px;"></td> 
            </tr>
            
            <tr>
                <td  colspan="" align="right" style="text-align:right;">(HEID no)</td><td>
                <input name="" id="heid" type="text" style="width:240px;"></td> 
            </tr>
    </table>
</td>
</tr>
</table>
	
    <!--Last phase Starats here-->   
 						
<table align="left" style="width:100%">	   					
    <tr><td>
        <table  class="" border="0" style=" width:65%;margin-top:0px; " align="right" >
         <tr>
            <td width="42%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Tarehe ya Chanjo Pneumococcal(PCV13)</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Tarehe ya Chanjo ya Rota</td><td style="font-weight:bold; background-color:#006400;color:white" width="62%">Tarehe ya Chanjo ya Surua/Rubella</td></tr>
            <td width="40%"  colspan="" align="right" style="text-align:right;">
                <table width="100%">
                    <tr>
                        <td>
                            1
                        </td>
                        <td><input id="pvc_1_date" type="text" name="tt1" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td >
                           2.
                        </td>
                        <td>
                            <input id="pvc_2_date" type="text" name="tt1" style="width:240px;" class="nn">
                        </td>
                    </tr>
                    <tr>
                        <td >
                           3.
                        </td>
                        <td>
                            <input id="pvc_3_date" type="text" name="tt1" style="width:240px;" class="nn">
                        </td>
                    </tr>
                </table>
							
							
        </td>
        <td >
        <table width="100%">
        <tr><td style="text-align:right;width:100px;">1. </td><td width="25%"> <input class="nn" type="text" style="width:200px;" name="mimba" id="rota_date_1"></td></tr>
        <tr><td style="text-align:right;width:100px;">2.</td><td> <input type="text" style="width:200px;" name="mimbanamba" class="nn" id="rota_date_2"></td></tr>
        </table>
        </td>
        <td>
            <table width="100%">
            <tr><td style="text-align:right;width:100px;">1. </td><td width="25%"> <input class="nn" type="text" style="width:200px;" name="mimba" id="surua_date_1"></td></tr>
            <tr><td style="text-align:right;width:100px;">2.</td><td> <input type="text" style="width:200px;" name="mimbanamba" class="nn" id="surua_date_2"></td></tr>
            </table>
        </td>
        
        
        
        </tr>
    </table>						
        <table style="margin-top:0px; width:30%;">
            <tr style="background-color:#006400;color:white">
                <th  class="powercharts_td_left">Tarehe ya Chanjo ya Polio</th>
            </tr>
           <tr>
                        <td>
                            1
                        </td>
                        <td><input id="polio_1_date" type="text" name="tt1" style="width:240px;"></td>
                    </tr>

                    <tr>
                        <td >
                           2.
                        </td>
                        <td>
                            <input id="polio_2_date" type="text" name="tt1" style="width:240px;" class="nn">
                        </td>
                    </tr>
                    <tr>
                        <td >
                           3.
                        </td>
                        <td>
                            <input id="polio_3_date" type="text" name="tt1" style="width:240px;" class="nn">
                        </td>
                    </tr>
    </table>
</td>
</tr>
<tr><td align="center" colspan="2" style="padding-left:400px;"> <button id="next_button" style="cursor:pointer; width:60px" class='art-button-green' > Next </button>  </td></tr>
</table>
					
</div>
						
						
                    <!-------------------------------------------Div ya pili---------------------------------------->
						
<div id="pili" >
    <center> 
	<table  class="" border="0"  align="left" style="width:48%">
	
	
                        <tr style="background-color:#006400;color:white">
                            <th  class="powercharts_td_left" colspan="2">Vitamini A</th>
                        </tr>		
                        <tr>
                        <td  class="powercharts_td_left">
							
                        <table style="width:400px;">
                            <tr >
                               <td width="35%">Miezi 6</td>
                               <td width="35%">
                                   <select id="" name="" style="width:300px">
                                       <option value="">~~~~~~~~~~~~~~~~~Select~~~~~~~~~~~~~~~</option>
                                       <option value="N">N</option>
                                       <option value="Y">Y</option>
                                   </select>
                               </td>
                           </tr>

                           <tr>
                           <td>Chini ya Mwaka</td>
                           <td >
                               <select id="" name="" style="width:300px">
                                       <option value="">~~~~~~~~~~~~~~~~~Select~~~~~~~~~~~~~~~</option>
                                       <option value="N">N</option>
                                       <option value="Y">Y</option>
                                </select>
                           </td>
                           </tr>
                           
                           <tr>
                           <td>Mwaka 1-5</td>
                           <td>
                               <select id="" name="" style="width:300px">
                                       <option value="">~~~~~~~~~~~~~~~~~Select~~~~~~~~~~~~~~~</option>
                                       <option value="N">N</option>
                                       <option value="Y">Y</option>
                                </select>
                           </td>
                           </tr>
							
                        </table>
                        </td>
                            
                             
                        </tr>
						
						<!--<tr><td colspan="2">&nbsp;</td></tr>-->
                        <tr>
                            <td colspan="2" style="text-align:center; font-weight:bold; background-color:#006400;color:white">Mabendazole/Abendazole kila miezi 6</td>
                        </tr>
                           
                        <td  colspan="" align="right" style="text-align:right;">
							
                            <table>
                            <tr>
                                <td>
                                    Miezi 12
                                </td>
                                <td>
                                    <input type="text">
                                </td>
                                
                                
                                
                                <td>
                                    Miezi 24
                                </td>
                                <td>
                                    <input type="text">
                                </td>
                            </tr>

                            <tr>
                            <td>
                                Miezi 18
                            </td>
                            <td >
                                <input type="text">
                            </td>
                            
                            <td>
                                Miezi 30
                            </td>
                            <td >
                                <input type="text">
                            </td>
                            </tr>
                            </table>
                        </td>
							
                        <td>
							
						<!--<tr>
                            <td colspan="10" style="height:27px;">&nbsp;</td></tr> -->
                        </table>
						
                        <table  class="" border="0" style=" width:50%; height:430px; margin-top:-36px; " align="right" >
                            <tr>
                            <td width="50%" class="powercharts_td_left" style="font-weight:bold; background-color:#006400;color:white">Ukuaji wa mtoto</td></tr>
                            <td width="40%"  colspan="" align="right" style="text-align:right;">
                                <table style="width:100%">
                                <tr>
                                    <td>
                                        Miezi 9:Uzito/Umri
                                    </td>
                                    <td>
                                        <input type="text">
                                    </td>
                                    <td>
                                        Miezi 18:Uzito/Umri
                                    </td>
                                    <td>
                                        <input type="text">
                                    </td>
                                </tr>

                                <tr>
                                    <td>Miezi 9:Uzito/Urefu</td>
                                    <td>
                                        <input type="text" value="">
                                    </td>
                                    <td>
                                        Miezi 18:Uzito/Urefu
                                    </td>
                                    <td>
                                        <input type="text" >
                                    </td>
                                </tr>
                                
                                
                                
                                
                                
                                <tr>
                                    <td>
                                        Miezi 9:Urefu/Umri
                                    </td>
                                    <td>
                                        <input  type="text" >
                                    </td>
                        
                                   <td >
                                       Miezi 18:Urefu/Umri
                                   </td>
                                   <td> 
                                       <input type="text">
                                   </td>
                                </tr>
                                <tr >
                                    <td>
                                        Miezi 36:Uzito/Umri
                                    </td>
                                    <td>
                                        <input type="text" >
                                    </td>
                                    
                                     <td >
                                       Miezi 48:Uzito/Urefu
                                   </td>
                                   <td> 
                                       <input type="text">
                                   </td>
                                       
                                </tr>

                                <tr>
                                    <td>
                                        Miezi 36:Uzito/Urefu
                                    </td>
                                    <td>
                                        <input type="text" name="" >
                                    </td>
                                    
                                    <td>
                                        Miezi 48:Uzito/Urefu
                                    </td>
                                    <td>
                                        <input type="text" name="" >
                                    </td>
                                    
                                </tr>
                                
                                <tr>
                                    <td>
                                        Miezi 36:Urefu/Umri
                                    </td>
                                    <td>
                                        <input type="text" name="" >
                                    </td>
                                    
                                    
                                    <td>
                                        Miezi 48:Urefu/Umri
                                    </td>
                                    <td>
                                        <input type="text" name="" >
                                    </td>
                                </tr>
                                
                        <tr>
							
                            <td colspan="7">
                                <table width="100%">
                                    <tr>
                                        <td style="font-weight:bold; background-color:#006400;color:white" colspan="10">Rufaa</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Andika kituo alikotoka mtoto
                                        </td>
                                        <td> 
                                            <input type="text" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Kituo alikopelekwa
                                        </td>
                                        <td> 
                                            <input type="text" >
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            Sababu ya rufaa
                                        </td>
                                        <td> 
                                            <input type="text" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Maelezo mengineyo/maoni
                                        </td>
                                        <td> 
                                            <input type="text" >
                                        </td>
                                    </tr>
                                    




                                  </table>
                                </td>
							
                        </tr>
                        </table>


                        </td></tr>
                        </td> 
                        </tr>
						
                        <tr>
                            
                        </tr>
						
						 <br><BR>
    <table style="margin-top:10px; float:left; width:48%;">
        <tr style="background-color:#006400;color:white">
         <th >Hatipunguzo Chandarua &amp;&amp;Ulishaji wa mtoto</b></th>
        </tr>
            <tr>
                <td>
            <table>
            <tr>
                <td>
                    Hatipunguzo Chandarua
                </td>
                <td>
                    <select id="" style="width:200px">
                        <option value="">
                            ~~~~~~~~~~~~~~~Select~~~~~~~~~~~
                        </option>
                        <option value="N">
                            N
                        </option>
                        <option value="H">
                            H
                        </option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>
                    Maziwa ya mama pekee(EBF)(Mtoto umri miezi 6)
                </td>
                <td>
                    <select id="" style="width:200px">
                        <option value="">
                            ~~~~~~~~~~~~~~~Select~~~~~~~~~~~
                        </option>
                        <option value="N">
                            N
                        </option>
                        <option value="H">
                            H
                        </option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td>
                    Maziwa mbadala
                </td>
                <td>
                    <select id="" style="width:200px">
                        <option value="">
                            ~~~~~~~~~~~~~~~Select~~~~~~~~~~~
                        </option>
                        <option value="N">
                            N
                        </option>
                        <option value="H">
                            H
                        </option>
                    </select>
                </td>
            </tr>
            
            </table>

            </td>
            <td>
            
            </td>

            </tr>
    </table>
</div>
</div>
						
	  <?php
    include("./includes/footer.php");
?>

<link href="css/jquery-ui.css" rel="stylesheet">
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $("#tabcontents").tabs();
</script>
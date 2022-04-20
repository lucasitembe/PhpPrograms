<?php
include("./includes/connection.php");
if(isSet($_POST['rch2']))
{
$rch2date=$_POST['rch2'];
$prid=$_POST['h'];
$checkifyupo = "SELECT * FROM tbl_rch WHERE rch_id= '$prid' AND status='active'";
if(mysqli_num_rows($qresult=mysqli_query($conn,$checkifyupo))>0) {
$fetchdet=mysqli_fetch_array($qresult);
#kaswende
$kaswende = "SELECT * FROM tbl_rch_syphil_disease WHERE rch_idk= '$fetchdet[rch_id]'";
$qresultkaswe=mysqli_query($conn,$kaswende);
$fetchdetkasw=mysqli_fetch_array($qresultkaswe);
#ngono
$ngono= "SELECT * FROM tbl_rch_stds WHERE rch_id= '$fetchdet[rch_id]'";
$qresultngono=mysqli_query($conn,$ngono);
$fetchdetngono=mysqli_fetch_array($qresultngono);
#TTVIEW1
$tt11 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND	 tmenowtt like '%$rch2date%' AND tt_no=0
";
$ttre=mysqli_query($conn,$tt11);
$rowlast=mysqli_fetch_array($ttre);
$ttlast1=$rowlast['date'];
#TTVIEW2
$tt12 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tmenowtt like '%$rch2date%' AND tt_no=2";
$ttre2=mysqli_query($conn,$tt12);
$rowlast2=mysqli_fetch_array($ttre2);
$ttlast2=$rowlast2['date'];
#TTVIEW3
$tt13 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]'	AND tmenowtt like '%$rch2date%' AND tt_no=3";
$ttre3=mysqli_query($conn,$tt13);
$rowlast3=mysqli_fetch_array($ttre3);
$ttlast3=$rowlast3['date'];
#TTVIEW4
$tt14 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tmenowtt like '%$rch2date%' AND tt_no=4";
$ttre4=mysqli_query($conn,$tt14);
$rowlast4=mysqli_fetch_array($ttre4);
$ttlast4=$rowlast4['date'];
#Mahudhurio ya Marudio
$marudio = "SELECT * FROM tbl_rch_mahudhurio WHERE rch_idm= '$fetchdet[rch_id]' AND time like '%$rch2date%' ";
$marudioq=mysqli_query($conn,$marudio);
$marudior=mysqli_fetch_array($marudioq);
#PMCT
$pmct = "SELECT * FROM tbl_pmct WHERE rch_id_pmct= '$fetchdet[rch_id]'";
$pmctq=mysqli_query($conn,$pmct);
$pmctr=mysqli_fetch_array($pmctq);
#IPT1
$ipt1s = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]'	AND timenowipt like '%$rch2date%' AND 
ipt_no=0";
$iptq=mysqli_query($conn,$ipt1s);
$iptre=mysqli_fetch_array($iptq);
$reipt1=$iptre['ipt_date'];
#IPT2
$ipt2s = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]'	AND timenowipt like '%$rch2date%' AND 
ipt_no=2";
$ipt2q=mysqli_query($conn,$ipt2s);
$iptre2=mysqli_fetch_array($ipt2q);
$reipt2=$iptre2['ipt_date'];
#IPT3
$ipt3s = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]'	AND timenowipt like '%$rch2date%' AND 
ipt_no=3";
$ipt3q=mysqli_query($conn,$ipt3s);
$a= mysqli_num_rows($ipt3q);
$iptre3=mysqli_fetch_array($ipt3q);
$reipt3=$iptre3['ipt_date'];
#IPT4
$ipt4s = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]'	AND timenowipt like '%$rch2date%' AND 
ipt_no=4";
$ipt4q=mysqli_query($conn,$ipt4s);
$iptre4=mysqli_fetch_array($ipt4q);
$reipt4=$iptre4['ipt_date'];
?>
<script type="text/javascript" >
$(function() {
$('#cf').click(function() {
alert('Its available');
});
$("#pilir").hide();
$("#b1kwanzar").click(function() {
$("#kwanzar").hide().fadeOut("slow");
$("#pilir").fadeIn(); });
$("#b2prevr").click(function() {
$("#pilir").hide().fadeOut("slow");
$("#kwanzar").fadeIn(); });
});
</script>
<?php
if(isset($_POST['h'])){
$pn= $_POST['h'];
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
} }
?>
<div class="tabcontents"  >
<div id="kwanzar">
<center>
<table	class="" border="0"	 align="left" style="width:100%;"  >
<tr>
<td width="20%" class="powercharts_td_left" style="text-align: right">Tarehe</td><td width="40%"><input	 type="text" 
value="<?php echo date("d-M-Y",strtotime( $rch2date)); ?>" name="tarehe1" id='da' readonly ><input readonly type=
"hidden" id="prid" value="<?php echo $pn; ?>"></td>
<td	 style="text-align:right;" width="20%">Namba Ya Usajili</td><td	 width="40%" colspan="2">
<input readonly value="<?php echo $fetchdet['nayausajili']; ?>" style="width:100px;" name="" type="text" placeholder=
"Andika Namba" id="num"></td>
</tr>
<tr>
<td width="20%" class="powercharts_td_left" style="text-align:right">Jina Kamili la Mteja</td><td ><input name=
"jinakamili" readonly value="<?php echo strtoupper( $fname." ".$mname." ".$lname); ?>" type="text"></td>
<td	 colspan="" align="right" style="text-align:right;">Umri</td><td>
<input name="" readonly id="umri" type="text" value="<?php echo $age; ?>" placeholder="umri" style="width:70px;"></td>
</tr>
<tr>
<td width="20%" style="text-align:right;" class="powercharts_td_left">Jina la Kijiji/Kitongoji</td><td><input name=
"name" id="kijiji" value="<?php echo $fetchdet['kijiji']; ?>" type="text"></td>
<td	 colspan="" align="right" style="text-align:right;">Balozi</td><td>
<input readonly value="<?php echo $fetchdet['balozi']; ?>" name="baloz" id="balozi" type="text" placeholder="Jina la 
Balozi" style="width:240px;"></td>
</tr>
<tr>
<td width="20%" class="powercharts_td_left" style="text-align:right;">Mtaa/Barabara</td><td><input name="n" type=
"text" id="mtaa" readonly value="<?php echo $fetchdet['mtaa']; ?>"></td>
<td	 colspan="" align="right" style="text-align:right;">Na ya Nyumba</td><td>
<input readonly name="" id="nyumba" value="<?php echo $fetchdet['nyumbano']; ?>" type="text" placeholder="Na ya 
Nyumba" style="width:240px;"></td>
</tr>
<tr>
<td width="20%" class="powercharts_td_left" style="text-align:right;">Jina la Mume/Mwenza</td><td><input name="name" 
type="text" id="mume" readonly value="<?php echo $fetchdet['mume']; ?>"></td>
<td	 colspan="" align="right" style="text-align:right;">Jina la M/Kiti <br>Serikali ya Mtaa/Kitongoji</td><td>
<input readonly id="mkiti" value="<?php echo $fetchdet['mkiti']; ?>" name="kij" type="text" placeholder="Jina la 
M/Kiti" style="width:240px;"><p ></p></td>
</tr>
</table>
<table align="left"	 style="width: 100%">
<tr><td style="width: 33%;font-weight:bold;background-color:#006400;color:white">Vipimo/Taarifa Muhimu</td>
<td	 class="powercharts_td_left" style="width: 30%;font-weight:bold;background-color:#006400;color:white"> Chanjo ya 
TT</td><td style="width: 80%;font-weight:bold;background-color:#006400;color:white">Taarifa ya Mimba Zilizopita
</td></tr>
<tr>
<td>
<table style=" width:100%;">
<tr><td>Kiwango cha damu HB <i>mfano 11.0</i><input readonly class="nn" id="hb" type="text" style="width:60px;" name=
"mimbanamba" value="<?php echo $fetchdet['kiwangochadamu']; ?>"></td>
</tr>
<tr><td id="lv">Shinikizo la damu (BP)<input type="radio" value="anashinikizo"	name="shinikizo" <?php if($fetchdet[
'shinikizo']=="anashinikizo") { echo "checked"; }  ?> disabled>Ndiyo &nbsp; <input
<?php if($fetchdet['shinikizo']=="hanashinikizo") { echo "checked"; }  ?> disabled
type="radio"  name="shinikizo" value="hanashinikizo">Hapana</td> </tr>
<tr><td>Urefu (cm)<input readonly value="<?php echo $fetchdet['urefu']; ?>" type="text"	 class="nn" id="ur" style=
"width:60px;" name="mimbanamba"></td>
</tr>
<tr><td id="suk">Sukari kwenye Mkojo<input
<?php if($fetchdet['sukarimkojoni']=="anasukari") { echo "checked"; }  ?> disabled
type="radio" value="anasukari"	name="sukari">Ndiyo &nbsp; <input
<?php if($fetchdet['sukarimkojoni']=="hanasukari") { echo "checked"; }	?> disabled
type="radio" value="hanasukari"	 name="sukari">Hapana</td> </tr>
<tr><td id="miaka">Umri chini ya miaka 20<input
<?php if($fetchdet['agechini20']=="anamiaka_chini_ya_20") { echo "checked"; }  ?> disabled
value="anamiaka_chini_ya_20" type="radio"  name="umrichini" >Ndiyo &nbsp; <input type="radio"
<?php if($fetchdet['agechini20']=="hanamiaka_chini_ya_2") { echo "checked"; }  ?> disabled
name="umrichini" value="hanamiaka_chini_ya_20">Hapana</td> </tr>
<tr><td id="miaka35">Umri zaidi ya miaka 35<input
<?php if($fetchdet['agejuu35']=="anamiaka_juu_ya_35") { echo "checked"; }  ?> disabled
type="radio"  name="umrijuu" value="anamiaka_juu_ya_35">Ndiyo &nbsp; <input type="radio" value="hanamiaka_juu_ya_35"
<?php if($fetchdet['agejuu35']=="hanamiaka_juu_ya_35") { echo "checked"; }	?> disabled
name="umrijuu">Hapana</td> </tr>
</table>
</td>
<td width="40%"	 colspan="" align="right" style="text-align:right;">
<table width="100%">
<tr><td id="ka">Ana Kadi? &nbsp; <input
<?php if($fetchdet['tt_chanjo_kadi']=="anakadi") { echo "checked"; }  ?> disabled
value="anakadi" type="radio" name="kadi">Ndiyo &nbsp;<input
<?php if($fetchdet['tt_chanjo_kadi']=="hanakadi") { echo "checked"; }  ?> disabled type="radio" name="kadi" value=
"hanakadi">Hapana</td></tr>
<?php if(mysqli_num_rows($ttre)>0){
?>
<tr><td > TT1 &nbsp; <input value="<?php echo date("jS F Y",strtotime( $ttlast1)); ?>" type="text"	style=
"width:150px;"> <br> TT2: &nbsp;<input	type="text" value="" style="width:150px;"><br>
TT3 &nbsp; <input value="" type="text"	style="width:150px;">
<br>
TT4 &nbsp; <input value=""	type="text" name="tt4" style="width:150px;">
</td></tr>
<?php }
if(mysqli_num_rows($ttre2)>0){
#TTVIEW2
$tt12one = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tt_no=0";
$ttre2one=mysqli_query($conn,$tt12one);
$rowlast2one=mysqli_fetch_array($ttre2one);
$ttlast2one=$rowlast2one['date'];
?>
<tr><td > TT1 &nbsp; <input value="<?php echo date("jS F Y",strtotime( $ttlast2one)); ?>" type="text"  style=
"width:150px;"> <br> TT2 &nbsp; <input	type="text" value="<?php echo date("jS F Y",strtotime( $ttlast2)); ?>" style=
"width:150px;"><br>
TT3 &nbsp; <input value="" type="text"	style="width:150px;">
<br>
TT4 &nbsp; <input value=""	type="text" name="tt4" style="width:150px;">
</td></tr>
<?php }
if(mysqli_num_rows($ttre3)>0){
#TTVIEW2
$tt12x = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tt_no=2";
$ttre2x=mysqli_query($conn,$tt12x);
$rowlast2x=mysqli_fetch_array($ttre2x);
$ttlast2x=$rowlast2x['date'];
#TTVIEW1 in 3
$tt12y = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tt_no=0";
$ttre2y=mysqli_query($conn,$tt12y);
$rowlast2y=mysqli_fetch_array($ttre2y);
$ttlast2y=$rowlast2y['date'];
?>
<tr><td >TT1 &nbsp; <input value="<?php echo date("jS F Y",strtotime( $ttlast2y)); ?>" type="text"	style=
"width:150px;"> <br> TT2 &nbsp; <input	type="text" value="<?php echo date("jS F Y",strtotime( $ttlast2x)); ?>" style
="width:150px;"><br>
TT3 &nbsp; <input value="<?php echo date("jS F Y",strtotime( $ttlast3)); ?>" type="text"  style="width:150px;">
<br>
TT4 &nbsp; <input value=""	type="text" name="tt4" style="width:150px;">
</td></tr>
<?php }
if(mysqli_num_rows($ttre4)>0){
#TTVIEW1
$c4 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tt_no=0";
$ttre24=mysqli_query($conn,$c4);
$rowlast24=mysqli_fetch_array($ttre24);
$ttlast24=$rowlast24['date'];
#TTVIEW2
$c42 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tt_no=2";
$ttre242=mysqli_query($conn,$c42);
$rowlast242=mysqli_fetch_array($ttre242);
$ttlast242=$rowlast242['date'];
#TTVIEW3
$c423 = "SELECT * FROM tbl_rch_tt WHERE rch_idtt = '$fetchdet[rch_id]' AND tt_no=3";
$ttre2423=mysqli_query($conn,$c423);
$rowlast2423=mysqli_fetch_array($ttre2423);
$ttlast2423=$rowlast2423['date'];
?>
<tr><td > TT1 &nbsp; <input value="<?php echo  date("jS F Y",strtotime( $ttlast24)); ?>" type="text"  style=
"width:150px;"> <br> TT2 &nbsp; <input	type="text" value="<?php echo $ttlast242; ?>" style="width:150px;"><br>
TT3 &nbsp; <input value="<?php echo date("jS F Y",strtotime( $ttlast2423)); ?>" type="text"	 style="width:150px;">
<br>
TT4 &nbsp; <input value="<?php echo date("jS F Y",strtotime(  $ttlast4)); ?>"  type="text" name="tt4" style=
"width:150px;">
</td></tr>
<?php }
?>
<tr><td><b>Umri wa mimba kwa wiki&nbsp;</b><input style="width:50px;" type="text" value="
<?php
$time= date("Y-m-d");
function diff_in_weeks_and_days($from, $to) {
$day   = 24 * 3600;
$from  = strtotime($from);
$to	   = strtotime($to) + $day;
$diff  = abs($to - $from);
$weeks = floor($diff / $day / 7);
$days  = $diff / $day - $weeks * 7;
$out   = array();
if ($weeks) $out[] = "$weeks Week" . ($weeks > 1 ? 's' : '');
if ($days)	$out[] = "$days Day" . ($days > 1 ? 's' : '');
return implode(', ', $out);
}
$t1t= date("Y-m-d",strtotime(  $fetchdet['timenow']));
echo diff_in_weeks_and_days($t1t,$time ) +$fetchdet['umriwamimba'];
?>
"></td></tr>
</table>
</td><td style="width: 70%">
<table style="width: 100%">
<tr><td style="text-align: right" >Mimba ya Ngapi? </td><td> <input readonly class="nn" type="text" style=
"width:60px;" name="mimba" value="<?php echo $fetchdet['mimbayangapi']; ?>" id="mimba"></td></tr>
<tr><td style="text-align: right" >Umezaa mara Ngapi? </td><td> <input value="<?php echo $fetchdet['marazakuzaa']; ?>
" type="text" readonly style="width:60px;" name="mimbanamba" class="nn" id="kuzaamara"></td></tr>
<tr><td	 style="text-align: right">Watoto hai </td><td> <input value="<?php echo $fetchdet['watotohai']; ?>" class=
"nn" type="text" style="width:60px;" readonly name="mimbanamba" id="watotohai"></td></tr>
<tr><td	 style="text-align: right">Mimba zilizo haribika </td><td style="width:100px;" > <input value="<?php echo 
$fetchdet['zilizoharibika']; ?>" readonly class="nn" type="text" style="width:60px;" name="mimbanamba" id=
"zilizoharibika"></td></tr>
<tr><td style="text-align: right">FSB/MSB/Kifo cha Mtoto katika wiki moja? </td><td id="kif"> <input disabled type=
"radio" <?php if($fetchdet['kifochamtotoinweek']=="kifokipo") { echo "checked"; }  ?> >Ndiyo<br><input disabled type=
"radio" <?php if($fetchdet['kifochamtotoinweek']=="hakunakifo") { echo "checked"; }	 ?> >Hapana</td></tr>
</table>
</td></tr>
</table>
</td></tr>
<tr><td align="center" colspan="2" style="padding-left:400px;"> <button id="b1kwanzar" style=
"cursor:pointer;font-weight:bold;" class="art-button-green" > Next </button>	</td></tr>
</table>
</div>
<!--Div ya pilir------------------------------------------------------------->
<div id="pilir" >
<center>
<table	class="" border="0"	 align="left" style="width:43%"	 >
<tr>
<td width="30%" align="center" style="text-align:center; font-weight:bold;">MWANAMKE</td>
<td style="text-align:center; font-weight:bold;" class="powercharts_td_left" >MWANAUME</td>
</tr>
<tr>
<th	 class="powercharts_td_left" colspan="2" style="background-color:#006400;color:white;text-align:center" >Kipimo 
cha Kaswende</th>
</tr>
<tr>
<td	 class="powercharts_td_left">
<table style="width:250px;">
<tr id="kaswfeerr" >
<td width="30%">Matokeo:</td>
<td width="30%">
<input	<?php if($fetchdetkasw['matokeoke']=="positive") { echo "checked"; }  ?> disabled type="radio" >Positive
</td><td width="30%"><input	 disabled type="radio" <?php if($fetchdetkasw['matokeoke']=="negative") { echo "checked";
 }	?>>Negative</td>
</tr>
<tr id="kaswtibafe">
<td>Ametibiwa?:</td>
<td ><input <?php if($fetchdetkasw['kaswtibake']=="ametibiwa") { echo "checked"; }	?>
type="radio" disabled >Ndiyo</td><td><input <?php if($fetchdetkasw['kaswtibake']=="hajatibiwa") { echo "checked"; }	 
?>	disabled  type="radio">Hapana</td>
</tr>
</table>
</td><td>
<table>
<tr id="kaswmeerr">
<td>Matokeo:</td>
<td ><input disabled <?php if($fetchdetkasw['matokeome']=="positive") { echo "checked"; }  ?>  type="radio" >Positive
</td><td><input <?php if($fetchdetkasw['matokeome']=="negative") { echo "checked"; }  ?> disabled  type="radio" >
Negative</td>
</tr>
<tr id="kaswtibame">
<td>Ametibiwa?:</td>
<td ><input disabled <?php if($fetchdetkasw['kaswtibame']=="ametibiwa") { echo "checked"; }	 ?> value=
"kaswmeametibiwa" type="radio" id="kaswendwme1t" name="kaswendwmetiba" >Ndiyo</td><td><input disabled <?php if(
$fetchdetkasw['kaswtibame']=="hajatibiwa") { echo "checked"; }	?>	type="radio">Hapana</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" style="background-color:#006400;color:white;text-align:center; font-weight:bold;"> Vipimo Vya 
Magonjwa ya Ngono</td>
</tr>
<tr>
<td	 colspan="" align="right" style="text-align:right;">
<table>
<tr id="ngonomatokeoerr" >
<td>Matokeo:</td>
<td>
<input <?php if($fetchdetngono['ngomatokeoke']=="positive") { echo "checked"; }	 ?> disabled  type="radio"	>Positive
</td><td>
<input <?php if($fetchdetngono['ngomatokeoke']=="negative") { echo "checked"; }?>  disabled  type="radio">Negative</td>
</tr>
<tr id="ngonotibafe">
<td>Ametibiwa?:</td>
<td ><input <?php if($fetchdetngono['ngoketiba']=="ngonofeametibiwa") { echo "checked"; }  ?>  disabled type="radio" 
>Ndiyo</td><td><input <?php if($fetchdetngono['ngoketiba']=="ngonofehajatibiwa") { echo "checked"; }  ?>  disabled	 
type="radio">Hapana</td>
</tr>
</table></td>
<td>
<table>
<tr id="ngonomematokeoerr" >
<td>Matokeo:</td>
<td><input
<?php if($fetchdetngono['ngomatokeome']=="positive") { echo "checked"; }  ?>
disabled type="radio" >Positive</td><td>
<input <?php if($fetchdetngono['ngomatokeome']=="negative") { echo "checked"; }  ?> type="radio" disabled>Negative</td>
</tr>
<tr id="ngonotibame">
<td>Ametibiwa?:</td>
<td ><input disabled type="radio"
<?php if($fetchdetngono['ngometiba']=="ngonomeametibiwa") { echo "checked"; }  ?>
>Ndiyo</td><td><input disabled <?php if($fetchdetngono['ngometiba']=="ngonomehajatibiwa") { echo "checked"; }  ?>	
type="radio">Hapana</td>
</tr>
</table>
</table>
<table	class="" border="0" style=" width:45%; height:430px; " align="right" >
<td width="40%" class="powercharts_td_left" style="font-weight:bold;" colspan="2">
<table style="width:100%;">
<tr><td></td><td><B>MWANAMKE</B></td><td><b>MWANAUME</b></td></tr>
<tr><td colspan="3" style="background-color:#006400;color:white;text-align:center;font-weight: bold; height:25px;">
Huduma ya PMCT</td></tr>
<tr>
<tr id="mambuki"><td>Tayari ana maambukizi?</td><td>
<?php if($pmctr['vvureadyke']=="fehanamaambukizi") { echo "Hapana"; } else{echo "Ndiyo";} ?>
</td><td><?php if($pmctr['vvureadyme']=="mehanamaambukizi") { echo "Hapana"; } else{echo "Ndiyo";} ?></td></tr>
<tr id="unasihiid"><td>Amepata Unasihi?</td>
<td><?php if($pmctr['unasihike']=="fehajapataunasihi") { echo "Hapana"; } else{echo "Ndiyo";} ?></td>
<td>
<?php if($pmctr['unasihime']=="mehajapataunasihi") { echo "Hapana"; } else{echo "Ndiyo";} ?>
</td></tr>
<tr id="kip"><td>Amepima VVU?</td><td>
<?php if($pmctr['amepimake']=="hajapimavvufe") { echo "Hapana"; } else{echo "Ndiyo";} ?>
</td><td>
<?php if($pmctr['amepimame']=="hajapimavvume") { echo "Hapana"; } else{echo "Ndiyo";} ?>
</td></tr>
<tr id="kip1"><td>Matokeo ya Kipimo cha kwanza cha VVU</td><td>
<?php if($pmctr['matokeokip1ke']=="negativekipimo1fe") { echo "Negative"; } else{echo "Positive";} ?></td>
<td>
<?php if($pmctr['matokeokip1me']=="negativekipimo1me") { echo "Negative"; } else{echo "Positive";} ?></td>
</tr>
<tr id="unasihiidb"><td>Amepata Unasihi baada ya kupima?</td><td>
<?php if($pmctr['unasihibaadake']=="hajapataunasihibaadake") { echo "Hapana"; } else{echo "Ndiyo";} ?>
</td><td>
<?php if($pmctr['unasihibaadame']=="hajapataunasihibaadame") { echo "Hapana"; } else{echo "Ndiyo";} ?>
</td></tr>
</table>
</td></tr>
<td width="40%"	 colspan="2" align="right" style="text-align:right;">
<table style="width: 100%;" >
<tr><td colspan="4" style="background-color:#006400;color:white;text-align:center;font-weight: bold;">Malaria
</td></tr>
<tr id="bsid"><td style="width: 20px;" >
<tr><td style="width: 220px; text-align: right">Matokeo ya T au BS </td><td style="width: 10px;">
<?php if($fetchdet['malaria']=="negative") { echo "Negative"; }else { echo "Positive"; } ?>
</td><td style="text-align: right" >Amepata hati punguzo?</td><td>
<?php if($fetchdet['hati']=="amepatahati") { echo "Ndiyo"; }else { echo "Hapana"; } ?>
</td></tr> </table>
</td></tr>
<tr><td	 colspan="2" >	<table>
<?php
if(mysqli_num_rows($iptq)>0){
?>
<tr><td>IPT1</td><td><input	 type="text" style="width:110px;" value="<?php echo
date("jS F Y",strtotime($reipt1)); ?>"></td>
<td>IPT2</td><td><input	 type="text" style="width:110px;" value=""></td>
<td>IPT3</td><td><input	 type="text" style="width:110px;" value="" ></td><td>IPT4:</td><td><input  type="text" style=
"width:110px;" value=""></td></tr>
<?php }
if(mysqli_num_rows($ipt2q)>0){
$ipt1sin2 = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]' AND ipt_no=0";
$iptqin2=mysqli_query($conn,$ipt1sin2);
$iptrein2=mysqli_fetch_array($iptqin2);
$reipt1in2=$iptrein2['ipt_date'];?>
<tr><td>IPT1:</td><td><input  type="text" style="width:110px;" value="<?php echo
date("jS F Y",strtotime($reipt1in2)); ?>"></td> <td>IPT2:</td><td><input  type="text" style="width:110px;" value="
<?php echo
date("jS F Y",strtotime($reipt2)); ?>"></td> <td>IPT3:</td><td><input  type="text" style="width:110px;" value="" 
></td>
<td>IPT4:</td><td><input  type="text" style="width:110px;" value=""></td></tr>
<?php
}
?>
<?php
if(mysqli_num_rows($ipt3q)>0){
$qs = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]' AND ipt_no=0";
$qsq=mysqli_query($conn,$qs);
$q=mysqli_fetch_array($qsq);
$reqs=$q['ipt_date'];
$qs2 = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]' AND ipt_no=2";
$qsq2=mysqli_query($conn,$qs2);
$q2=mysqli_fetch_array($qsq2);
$reqs2=$q2['ipt_date'];?>
<tr><td>IPT1:</td><td><input  type="text" style="width:110px;" value="<?php echo
date("jS F Y",strtotime($reqs)); ?>"></td><td>IPT2:</td><td><input	type="text" style="width:110px;" value="<?php echo
date("jS F Y",strtotime($reqs2)); ?>"></td> <td>IPT3:</td><td><input  type="text" style="width:110px;" value="<?php 
echo
date("jS F Y",strtotime($reipt3)); ?>" ></td><td>IPT4:</td><td><input  type="text" style="width:110px;" value=""
></td></tr>
<?php }
if(mysqli_num_rows($ipt4q)>0){
$qs4 = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]' AND ipt_no=0";
$qsq4=mysqli_query($conn,$qs4);
$q4=mysqli_fetch_array($qsq4);
$reqs4=$q4['ipt_date'];
$qs24 = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]' AND ipt_no=2";
$qsq24=mysqli_query($conn,$qs24);
$q24=mysqli_fetch_array($qsq24);
$reqs24=$q24['ipt_date'];
$qs243 = "SELECT * FROM tbl_rch_ipt WHERE rch_id_ipt = '$fetchdet[rch_id]' AND ipt_no=3";
$qsq243=mysqli_query($conn,$qs243);
$q243=mysqli_fetch_array($qsq243);
$reqs243=$q243['ipt_date'];?>
<tr><td>IPT1:</td><td><input  type="text" style="width:110px;" value="<?php echo
date("jS F Y",strtotime($reqs4)); ?>"></td> <td>IPT2:</td><td><input  type="text" style="width:110px;" value="<?php 
echo
date("jS F Y",strtotime($reqs24)); ?>"></td> <td>IPT3:</td><td><input  type="text" style="width:110px;" value="<?php 
echo
date("jS F Y",strtotime($reqs243)); ?>" ></td><td>IPT4:</td><td><input	type="text" style="width:110px;" value="<?php
 echo
date("jS F Y",strtotime($reipt4)); ?>"></td></tr>
<?php }	 ?>
</table>
</td></tr>
<tr><td colspan="2" ><b>Idadi ya Vidonge vya "I" Iron/ "FA" Folic Acid/:</b> 1.<input type="text" style="width:26px;"
value="<?php
$ifa1 = "SELECT * FROM tbl_rch_ifa WHERE rch_ifa_id = '$fetchdet[rch_id]' AND ifa_no=0";
$ifa1q=mysqli_query($conn,$ifa1);
$if=mysqli_fetch_array($ifa1q);
$reifa1=$if['ifa_amount'];
echo $reifa1;
?>"
>
2.<input type="text" style="width:26px;"
value="<?php
$ifa2 = "SELECT * FROM tbl_rch_ifa WHERE rch_ifa_id = '$fetchdet[rch_id]' AND ifa_no=2";
$ifa2q=mysqli_query($conn,$ifa2);
$if2=mysqli_fetch_array($ifa2q);
$reifa2=$if2['ifa_amount'];
echo $reifa2;?>">
3.<input type="text" style="width:26px;"
value="<?php
$ifa3 = "SELECT * FROM tbl_rch_ifa WHERE rch_ifa_id = '$fetchdet[rch_id]' AND ifa_no=3";
$ifa3q=mysqli_query($conn,$ifa3);
$if3=mysqli_fetch_array($ifa3q);
$reifa3=$if3['ifa_amount'];
echo $reifa3;
?>"
>
4.<input type="text" style="width:26px;"
value="<?php
$ifa4 = "SELECT * FROM tbl_rch_ifa WHERE rch_ifa_id = '$fetchdet[rch_id]' AND ifa_no=4";
$ifa4q=mysqli_query($conn,$ifa4);
$if4=mysqli_fetch_array($ifa4q);
$reifa4=$if4['ifa_amount'];
echo $reifa4;
?>"
>
</td></tr>
<tr><td><b>Matokeo ya Kipimo cha pili cha VVU</b></td><td>
<?php if($pmctr['matokeokip2ke']=="negativekipimo2fe") { echo "Negative"; }
elseif($pmctr['matokeokip2ke']=="bado") { echo "Hajapima"; }
else{echo "Positive";} ?>
</td></tr>
<tr id="ulishajit"><td><b>Amepata ushauri juu ya ulishaji wa mtoto?</b></td><td>
<?php if($fetchdet['ulishajiushauri']=="Amepataushauri") { echo "Ndiyo"; }else { echo "Hapana"; } ?>
</td></tr>
<tr id="mebend"><td ><b>Amepata Albendazole/
Mebendazole?:</b> </td><td>
<?php if($fetchdet['albendazole']=="amepataalbend") { echo "Ndiyo"; }else { echo "Hapana"; } ?>
</td></tr>
</table>
<tr><td>
</td></tr>
</td>
</tr>
</table> <br><BR>
<table style=" float:left; width:43%;">
<tr>
<th	  colspan"2" style="background-color:#006400;color:white;text-align:center" >Mahudhurio ya Marudio:</th><th style
="background-color:#006400;color:white;text-align:center"><b>Rufaa</b></th></tr>
<tr><td>
<table>
<tr><td><input type="checkbox" disabled
<?php if($marudior['km']=="ndiyokuharibikamimba") { echo "checked"; }  ?>
> (KM)<span style="font-size:10px; "> <i> Kuharibika Mimba</i></span></td>
<td><input disabled
<?php if($marudior['anaemia']=="ndiyoanaemia") { echo "checked"; }	?> type="checkbox" >(A)<span style=
"font-size:10px; "> <i>Anaemia</i></span></td>
</tr>
<tr><td><input type="checkbox" disabled
<?php if($marudior['protenuria']=="ndiyoprotenuria") { echo "checked"; }  ?>>(P) <span style="font-size:10px; "> <i>
Protenuria</i></span></td><td><input type="checkbox" disabled
<?php if($marudior['km']=="ndiyobp") { echo "checked"; }  ?>>(H)<span style="font-size:10px; "> <i>High BP</i></span>
</td> </tr>
<tr><td><input type="checkbox"
disabled
<?php if($marudior['kutoongezeka_uzito']=="ndiyokutoongezekauzi") { echo "checked"; }  ?>
>(U)<span style="font-size:10px; "> <i>Kutoongezeka Uzito<i></span></td><td>
<input type="checkbox"
disabled
<?php if($marudior['damu_ukeni']=="ndiyodu") { echo "checked"; }  ?>
>(D)
<span style="font-size:10px; "> <i>Damu Ukeni</i></span></td>
</tr>
<tr><td><input type="checkbox"
disabled
<?php if($marudior['mlalo_mbaya']=="ndiyoml") { echo "checked"; }  ?>
>(M)<span style="font-size:9px; "> <i> Mlalo mbaya wa mtoto</i></span></td>
<td><input type="checkbox"
disabled
<?php if($marudior['mimbazaidiya4']=="ndiyom4") { echo "checked"; }	 ?>
>(M4+)<span style="font-size:10px; "> <i>Mimba 4+</i></span></td>
</tr>
<tr><td><input type="checkbox"
disabled
<?php if($marudior['operation']=="ndiyocs") { echo "checked"; }	 ?>
> (C/S)<span style="font-size:9px; "> <i> Operation</i></span></td>
<td><input type="checkbox"
disabled
<?php if($marudior['vacuum']=="ndiyove") { echo "checked"; }  ?>
>(VE)<span style="font-size:9px; "> <i>Vacuum</i></td>
</tr>
<tr><td><input type="checkbox" disabled
<?php if($marudior['tb']=="ndiyotb") { echo "checked"; }  ?>> TB</td><td></td> </tr>
</table>
</td><td><table>
<tr>
<td>Tarehe</td><td><input value="<?php echo $fetchdet['rufaadate']; ?>"	 type="date"></td>
</tr>
<tr>
<td>Alikopelekwa</td><td><input value="<?php echo $fetchdet['alikopelekwa']; ?>" type="text"></td>
</tr>
<tr>
<td>Alikotoka</td><td><input value="<?php echo $fetchdet['alikotoka']; ?>" type="text"></td>
</tr>
<tr>
<td>Maoni</td><td><textarea id="mao"><?php echo $fetchdet['maoni']; ?></textarea></td>
</tr>
</table></td></tr>
<tr><td style="border: none;"><span style="margin-top:0px;"> <button id="b2prevr" style="cursor:pointer" class=
"art-button"> Prev </button> <!--&nbsp; <button id="cf">Check</button> -->
</span>
</td></tr>
</table>
</div>
</div>
<?php }}
?>
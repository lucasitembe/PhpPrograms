

<?php 




function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

 include("./includes/connection.php");
if(isSet($_POST['name']))
{
	
	$prid=test_input($_POST['pri']);
	
$kadi=test_input($_POST['cont']);


$kitongoji=test_input($_POST['kito']);
$mtaa=test_input($_POST['mtaawa']);
$mume=test_input($_POST['jinalamume']);
$yearofreg=test_input($_POST['yearof']);

$noofreg=test_input($_POST['noofre']);
$umri=test_input($_POST['umriwamjamzito']);

$date1=test_input($_POST['datevi']);

$mimbaumri=test_input($_POST['mimba_umri']);
$mwishomto=test_input($_POST['mto']);

$balozi=test_input($_POST['balozi']);
$mkiti=test_input($_POST['mkiti']);
$nyumbano=test_input($_POST['nyumbanamba']);
$hb=test_input($_POST['hb']);
$shinikizo=test_input($_POST['shini']);
$uref=test_input($_POST['uref']);
$sukari=test_input($_POST['sukari']);
$umrichini=test_input($_POST['umrichini20']);
$umrijuu=test_input($_POST['umrijuu35']);

$mimbano=test_input($_POST['mimbanambari']);
$kuzaamara=test_input($_POST['kuzaamar']);
$hai=test_input($_POST['watotoha']);
$haribika=test_input($_POST['haribu']);
$deathinweek=test_input($_POST['deathinweek']);
$kaswende=test_input($_POST['kaswe']);
$kaswendeme=test_input($_POST['kasweme']);
$kaswtib=test_input($_POST['kaswtib']);
$kaswtibme=test_input($_POST['kaswtibme']);
$ulishaji=test_input($_POST['ulishaji']);
$albend=test_input($_POST['albend']);
$hati=test_input($_POST['hati']);
$malar=test_input($_POST['malar']);
$ipt1=test_input($_POST['ipt1']);
$ifa1=test_input($_POST['ifa1']);

$rufdate=test_input($_POST['rufadate']);
$rufalikopelekwa=test_input($_POST['rufpelekwa']);
$rufalikotoka=test_input($_POST['rufkotoka']);
$rufmaoni=test_input($_POST['rufmaon']);

$ngonofemale=test_input($_POST['ngonof']);
$ngonotibafemale=test_input($_POST['ngonotibaf']);
$ngonomale=test_input($_POST['ngonom']);
$ngonotibamale=test_input($_POST['ngonotibam']);

//mahudhurio ya marudio

//mahudhuriocizor="+mahudhuriocs+"&mahudhuriotuba="+mahudhuriotb+"&mahudhurioana="+mahudhurioanaemia+"&mahudhuriobloodp="+mahudhuriobp+"&mahudhuriodamu="+mahudhuriodu+"&mahudhuriomz4="+mahudhuriom4+"&mahudhuriov="+mahudhuriove

$km= $_POST['mahudhuriokm'];
$prote= $_POST['mahudhuriopro'];
$uzito= $_POST['mahudhuriokutoong'];
$ml= $_POST['mahudhuriomlalo'];
$cs= $_POST['mahudhuriocizor'];
$tb= $_POST['mahudhuriotuba'];
$km= $_POST['mahudhuriokm'];
$anaemia= $_POST['mahudhurioana'];
$bp= $_POST['mahudhuriobloodp'];
$du= $_POST['mahudhuriodamu'];
$m4= $_POST['mahudhuriomz4'];
$ve= $_POST['mahudhuriov'];


//PMCT



$maambukizivvufe= $_POST['pmctmaambukizivvufe'];
$maambukizivvume= $_POST['pmctmaambukizivvume'];

$unasike= $_POST['pmctunasike'];


$unasime= $_POST['pmctunasime'];


$unasihibaadake= $_POST['pmctunasibaadake'];

$unasihibaadame= $_POST['pmctunasibaadame'];

$kipimovvufe= $_POST['pmctkipimovvufe'];

$kipimovvume= $_POST['pmctkipimovvume'];

$kipimo1vvufe= $_POST['pmctkipimo1vvufe'];

$kipimo1vvume= $_POST['pmctkipimo1vvume'];

$kipimo2vvuke= $_POST['pmctkipimo2vvufe'];

//tt1



$tt1=$_POST['ttn1'];


//MAHUDHURIO

//

$checkifyupo = "SELECT pr_id,status FROM tbl_rch WHERE pr_id= '$prid' AND status='active'";
if(mysqli_num_rows($qresult=mysqli_query($conn,$checkifyupo))>0) {echo "Patient is already registered in RCH LIST Please press Update";}
else{

$sql="INSERT INTO tbl_rch(pr_id,kijiji, mtaa, mume, kiwangochadamu, shinikizo, urefu, sukarimkojoni, agechini20, agejuu35, mwaka, nayausajili, age, balozi, nyumbano, mkiti, tt_chanjo_kadi,mimbayangapi,ulishajiushauri, albendazole, malaria, hati, marazakuzaa, watotohai, zilizoharibika, kifochamtotoinweek, rufaadate, alikopelekwa, alikotoka, maoni,umriwamimba,umriwamtotowamwisho) 




VALUES ('$prid','$kitongoji','$mtaa','$mume','$hb','$shinikizo','$uref','$sukari','$umrichini','$umrijuu','$yearofreg','$noofreg','$umri','$balozi','$nyumbano','$mkiti','$kadi','$mimbano','$ulishaji','$albend','$malar','$hati','$kuzaamara','$hai','$haribika','$deathinweek','$rufdate','$rufalikopelekwa','$rufalikotoka','$rufmaoni','$mimbaumri','$mwishomto')";

if(mysqli_query($conn,$sql)) {


$last = mysql_insert_id();
//ngono
$sql3 = "INSERT INTO tbl_rch_stds(rch_id, ngomatokeoke, ngomatokeome, ngoketiba, ngometiba) VALUES ('$last','$ngonofemale','$ngonomale','$ngonotibafemale','$ngonotibamale')";

mysqli_query($conn,$sql3);

#TT
$sqltt = "INSERT INTO tbl_rch_tt(rch_idtt, date) VALUES ('$last','$tt1')";



if(mysqli_query($conn,$sqltt)) { } else { echo   mysqli_error($conn);}

#

#ipt1
$sqlipt = "INSERT INTO tbl_rch_ipt(rch_id_ipt, ipt_date) VALUES ('$last','$ipt1')";



if(mysqli_query($conn,$sqlipt)) { } else { echo   mysqli_error($conn);}

#IFA
$sqlifa = "INSERT INTO tbl_rch_ifa(rch_ifa_id, ifa_amount) VALUES ('$last','$ifa1')";



if(mysqli_query($conn,$sqlifa)) { } else { echo   mysqli_error($conn);}

#VISITS
$sqlv = "INSERT INTO tbl_rch_visits(rch_id) VALUES('$last')";



if(mysqli_query($conn,$sqlv)) { } else { echo   mysqli_error($conn);}

//PMCT
 

$sql7 ="INSERT INTO tbl_pmct(rch_id_pmct, vvureadyke, vvureadyme, unasihike, unasihime, amepimake, amepimame, matokeokip1ke, matokeokip1me, unasihibaadake, unasihibaadame, matokeokip2ke) VALUES ('$last','$maambukizivvufe','$maambukizivvume','$unasike','$unasime','$kipimovvufe','$kipimovvume','$kipimo1vvufe','$kipimo1vvume','$unasihibaadake','$unasihibaadame','$kipimo2vvuke')";

if(mysqli_query($conn,$sql7)) { } else { echo   mysqli_error($conn);}

//MAHUDHURIO

$sql4 = "INSERT INTO tbl_rch_mahudhurio(rch_idm,km,protenuria,kutoongezeka_uzito,mlalo_mbaya,operation,tb,anaemia,bp,damu_ukeni,mimbazaidiya4,vacuum) VALUES ('$last','$km','$prote','$uzito','$ml','$cs','$tb','$anaemia','$bp','$du','$m4','$ve')";

if(mysqli_query($conn,$sql4)) { } else { echo   mysqli_error($conn);}








$sql2 = "INSERT INTO tbl_rch_syphil_disease(rch_idk, matokeoke, matokeome, kaswtibake, kaswtibame) VALUES ('$last','$kaswende','$kaswendeme','$kaswtib','$kaswtibme')";

if(mysqli_query($conn,$sql2)) {
echo "Successfully saved";
}

} else { echo mysqli_error($conn);}





}
}
?>

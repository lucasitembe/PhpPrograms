

<?php

 
 include("./includes/connection.php");
if(isSet($_POST['rchnox']))
{
	
	$last= $_POST['rchnox'];
	
	

$ipt1=$_POST['ipt1'];
$ifa1=$_POST['ifa1'];


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



$kipimo2vvuke= $_POST['pmctkipimo2vvufe'];

//tt1



$tt1=$_POST['ttn1'];

$todaycash= date("Y-m-d");
						

//Check if she attended by today
$ct="SELECT * from tbl_rch_visits WHERE rch_id = '$last' AND vdate like '%$todaycash%'";
$lp=mysqli_query($conn,$ct);
$ccd= mysqli_num_rows($lp);
if($ccd>0) {
	echo "Mgonjwa amekwisha fanya Mahudhurio leo";
	exit;
}

#TT
$counttt="SELECT * from tbl_rch_tt WHERE rch_idtt = '$last'";
$countttquery=mysqli_query($conn,$counttt);

$cc= mysqli_num_rows($countttquery);

$c=$cc+1;

$sqltt = "INSERT INTO tbl_rch_tt(rch_idtt, date,tt_no) VALUES ('$last','$tt1','$c')";


if(mysqli_query($conn,$sqltt)) { } else { echo   mysqli_error($conn);}



#ipt1
$countiptq="SELECT * from tbl_rch_ipt WHERE rch_id_ipt ='$last'";
$countipt=mysqli_query($conn,$countiptq);
$ccip= mysqli_num_rows($countipt);

$cr=$ccip+1;
$sqlipt = "INSERT INTO tbl_rch_ipt(rch_id_ipt, ipt_date,ipt_no) VALUES ('$last','$ipt1','$cr')";



if(mysqli_query($conn,$sqlipt)) { } else { echo   mysqli_error($conn);}


#IFA
$countifa="SELECT * from tbl_rch_ifa WHERE rch_ifa_id ='$last'";
$countif=mysqli_query($conn,$countifa);
$ccifa= mysqli_num_rows($countif);

$cf=$ccifa+1;
$sqlifa = "INSERT INTO tbl_rch_ifa(rch_ifa_id, ifa_amount,ifa_no) VALUES ('$last','$ifa1','$cf')";



if(mysqli_query($conn,$sqlifa)) { } else { echo   mysqli_error($conn);}

#VISITS
$sqlv = "INSERT INTO tbl_rch_visits(rch_id) VALUES('$last')";



if(mysqli_query($conn,$sqlv)) { } else { echo   mysqli_error($conn);}

//PMCT
 

/* /* $sql7 ="INSERT INTO tbl_pmct(rch_id_pmct, vvureadyke, vvureadyme, unasihike, unasihime, amepimake, amepimame, matokeokip1ke, matokeokip1me, unasihibaadake, unasihibaadame, matokeokip2ke) VALUES ('$last','$maambukizivvufe','$maambukizivvume','$unasike','$unasime','$kipimovvufe','$kipimovvume','$kipimo1vvufe','$kipimo1vvume','$unasihibaadake','$unasihibaadame','$kipimo2vvuke')"; 

if(mysqli_query($conn,$sql7)) { } else { echo   mysqli_error($conn);}
 */
//MAHUDHURIO

$sql4 = "INSERT INTO tbl_rch_mahudhurio(rch_idm,km,protenuria,kutoongezeka_uzito,mlalo_mbaya,operation,tb,anaemia,bp,damu_ukeni,mimbazaidiya4,vacuum) VALUES ('$last','$km','$prote','$uzito','$ml','$cs','$tb','$anaemia','$bp','$du','$m4','$ve')";

if(mysqli_query($conn,$sql4)) { echo "Mahudhurio yamekamilika"; } else { echo   mysqli_error($conn);}






/* 

$sql2 = "INSERT INTO tbl_kaswende(rch_idk, matokeoke, matokeome, kaswtibake, kaswtibame) VALUES ('$last','$kaswende','$kaswendeme','$kaswtib','$kaswtibme')";

if(mysqli_query($conn,$sql2)) {
echo "Successfully Updated";
}

} else { echo mysqli_error($conn);}
 */



}

?>

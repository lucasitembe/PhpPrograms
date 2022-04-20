<?php
include("./includes/connection.php"); 
if(isset($_POST['registration_id']))
{
	$registration_id = mysqli_real_escape_string($conn,trim($_POST['registration_id']));
	$procedure = mysqli_real_escape_string($conn,trim($_POST['procedures']));
	$diagnosis = mysqli_real_escape_string($conn,trim($_POST['diagnosis']));
	$surgeon = mysqli_real_escape_string($conn,trim($_POST['surgeon']));
	$consent = mysqli_real_escape_string($conn,trim($_POST['consent']));
	$anethesia = mysqli_real_escape_string($conn,trim($_POST['anethesia']));
	$assist_anethesia = mysqli_real_escape_string($conn,trim($_POST['assist_anethesia']));
	$significant_history = mysqli_real_escape_string($conn,trim($_POST['significant_history']));
	$family_history = mysqli_real_escape_string($conn,trim($_POST['family_history']));
	$cormobidities = mysqli_real_escape_string($conn,trim($_POST['cormobidities']));
	$allergies = mysqli_real_escape_string($conn,trim($_POST['allergies']));
	$medication = mysqli_real_escape_string($conn,trim($_POST['medication']));

	$obese = mysqli_real_escape_string($conn,trim($_POST['obese']));
	$healthy = mysqli_real_escape_string($conn,trim($_POST['healthy']));
	$weak = mysqli_real_escape_string($conn,trim($_POST['weak']));

	$pale = mysqli_real_escape_string($conn,trim($_POST['pale']));

	$cooperative = mysqli_real_escape_string($conn,trim($_POST['cooperative']));
	$aweke = mysqli_real_escape_string($conn,trim($_POST['aweke']));
	$unconscious = mysqli_real_escape_string($conn,trim($_POST['unconscious']));
	$aggressive = mysqli_real_escape_string($conn,trim($_POST['aggressive']));
	$dehydrated = mysqli_real_escape_string($conn,trim($_POST['dehydrated']));
	$special_pregnantbp =mysqli_real_escape_string($conn,trim($_POST['special_pregnantbp']));
	$bp = mysqli_real_escape_string($conn,trim($_POST['bp']));
	$temp = mysqli_real_escape_string($conn,trim($_POST['temp']));
	$rr = mysqli_real_escape_string($conn,trim($_POST['rr']));
	$spo2 = mysqli_real_escape_string($conn,trim($_POST['spo2']));
	$wt = mysqli_real_escape_string($conn,trim($_POST['wt']));
	$ht = mysqli_real_escape_string($conn,trim($_POST['ht']));
	$bmi = mysqli_real_escape_string($conn,trim($_POST['bmi']));
	$rgb = mysqli_real_escape_string($conn,trim($_POST['rgb']));
	$cvs = mysqli_real_escape_string($conn,trim($_POST['cvs']));
	$lungs = mysqli_real_escape_string($conn,trim($_POST['lungs']));
	$system = mysqli_real_escape_string($conn,trim($_POST['system']));
	$normal = mysqli_real_escape_string($conn,trim($_POST['normal']));
	$limited = mysqli_real_escape_string($conn,trim($_POST['limited']));
	$micrognathia = mysqli_real_escape_string($conn,trim($_POST['micrognathia']));
	$next_extension = mysqli_real_escape_string($conn,trim($_POST['next_extension']));
	$thyromental_distance = mysqli_real_escape_string($conn,trim($_POST['thyromental_distance']));
	$loose = mysqli_real_escape_string($conn,trim($_POST['loose']));
	$impant = mysqli_real_escape_string($conn,trim($_POST['impant']));
	$normal_teeth = mysqli_real_escape_string($conn,trim($_POST['normal_teeth']));
	$rft =mysqli_real_escape_string($conn,trim( $_POST['rft']));
	$lft = mysqli_real_escape_string($conn,trim($_POST['lft']));
	$elect = mysqli_real_escape_string($conn,trim($_POST['elect']));
	$hb_level = mysqli_real_escape_string($conn,trim($_POST['hb_level']));
	$blood_group = mysqli_real_escape_string($conn,trim($_POST['blood_group']));
	$fio2 = mysqli_real_escape_string($conn,trim($_POST['fio2']));
	$sao2 = mysqli_real_escape_string($conn,trim($_POST['sao2']));
	$be = mysqli_real_escape_string($conn,trim($_POST['be']));
	$bic = mysqli_real_escape_string($conn,trim($_POST['bic']));
	$pco2 = mysqli_real_escape_string($conn,trim($_POST['pco2']));
	$ph = mysqli_real_escape_string($conn,trim($_POST['ph']));
	$cxr = mysqli_real_escape_string($conn,trim($_POST['cxr']));
	$ecg_echo_ctc_scan = mysqli_real_escape_string($conn,trim($_POST['ecg_echo_ctc_scan']));
	$anethetic_risk = mysqli_real_escape_string($conn,trim($_POST['anethetic_risk']));
	$proposed_anethesia = mysqli_real_escape_string($conn,trim($_POST['proposed_anethesia']));
	$fasting_for = mysqli_real_escape_string($conn,trim($_POST['fasting_for']));
	$blood_unit = mysqli_real_escape_string($conn,trim($_POST['blood_unit']));
	$date = mysqli_real_escape_string($conn,trim($_POST['date']));
	$name = mysqli_real_escape_string($conn,trim($_POST['name']));
	$anethisiology_opinion = mysqli_real_escape_string($conn,trim($_POST['anethisiology_opinion']));
	$patient_fasted = mysqli_real_escape_string($conn,trim($_POST['patient_fasted']));
	$elective_surgery = mysqli_real_escape_string($conn,trim($_POST['elective_surgery']));
	$emergency_surgery = mysqli_real_escape_string($conn,trim($_POST['emergency_surgery']));
	$central_line = mysqli_real_escape_string($conn,trim($_POST['central_line']));
	$spo2_morinitoring = mysqli_real_escape_string($conn,trim($_POST['spo2_morinitoring']));
	$nbp = mysqli_real_escape_string($conn,trim($_POST['nbp']));
	$ecg = mysqli_real_escape_string($conn,trim($_POST['ecg']));
	$etco2 = mysqli_real_escape_string($conn,trim($_POST['etco2']));
	$gases = mysqli_real_escape_string($conn,trim($_POST['gases']));
	$iv =mysqli_real_escape_string($conn,trim($_POST['iv']));
	$inhalational = mysqli_real_escape_string($conn,trim($_POST['inhalational']));
	$rsi = mysqli_real_escape_string($conn,trim($_POST['rsi']));
	$intubation =mysqli_real_escape_string($conn,trim($_POST['intubation']));
	$comment = mysqli_real_escape_string($conn,trim($_POST['comment']));
	$circle = mysqli_real_escape_string($conn,trim($_POST['circle']));
	$t_iece = mysqli_real_escape_string($conn,trim($_POST['t_iece']));
	$mask = mysqli_real_escape_string($conn,trim($_POST['mask']));
	$lma = mysqli_real_escape_string($conn,trim($_POST['lma']));
	$nasal = mysqli_real_escape_string($conn,trim($_POST['nasal']));
	$type = mysqli_real_escape_string($conn,trim($_POST['type']));
	$size = mysqli_real_escape_string($conn,trim($_POST['size']));
	$spont = mysqli_real_escape_string($conn,trim($_POST['spont']));
	$cont = mysqli_real_escape_string($conn,trim($_POST['cont']));
	$tv = mysqli_real_escape_string($conn,trim($_POST['tv']));
	$press = mysqli_real_escape_string($conn,trim($_POST['press']));
	$peep = mysqli_real_escape_string($conn,trim($_POST['peep']));
	$ie = mysqli_real_escape_string($conn,trim($_POST['ie']));
	$air = mysqli_real_escape_string($conn,trim($_POST['air']));
	$o2 = mysqli_real_escape_string($conn,trim($_POST['o2']));
	$haloth = mysqli_real_escape_string($conn,trim($_POST['haloth']));
	$isofl = mysqli_real_escape_string($conn,trim($_POST['isofl']));
	$sevofl = mysqli_real_escape_string($conn,trim($_POST['sevofl']));
	$others = mysqli_real_escape_string($conn,trim($_POST['others']));
	$local_type = mysqli_real_escape_string($conn,trim($_POST['local_type']));
	$conc = mysqli_real_escape_string($conn,trim($_POST['conc']));
	$amount = mysqli_real_escape_string($conn,trim($_POST['amount']));
	$position = mysqli_real_escape_string($conn,trim($_POST['position']));
	$comments = mysqli_real_escape_string($conn,trim($_POST['comments']));
	$done_by = mysqli_real_escape_string($conn,trim($_POST['done_by']));
	$hr_pr = mysqli_real_escape_string($conn,trim($_POST['hr_pr']));




$insert_anethesia = "INSERT INTO tbl_anethesia(registration_id,procedures,diagnosis,surgeon,
consent,anethesia,assist_anethesia,significant_history,family_history,cormobidities,
allergies,medication,obese,healthy,weak,pale,cooperative,aweke,unconscious,aggressive,dehydrated,
special_pregnantbp,bp,hr_pr,temp,rr,spo2,wt,ht,bmi,rgb,cvs,lungs,system,normal,limited,micrognathia,
next_extension,thyromental_distance,loose,impant,normal_teeth,rft,lft,elect,hb_level,blood_group,fio2,
sao2,be,bic,pco2,ph,cxr,ecg_echo_ctc_scan,anethetic_risk,proposed_anethesia,fasting_for,blood_unit,
date,name,anethisiology_opinion,patient_fasted,elective_surgery,emergency_surgery,central_line,
spo2_morinitoring,nbp,ecg,etco2,gases,iv,inhalational,rsi,intubation,comment,
circle,t_iece,mask,lma,nasal,type,size,spont,cont,tv,press,peep,ie,air,o2,haloth,isofl,sevofl,others,
local_type,conc,amount,position,comments,done_by) VALUES('$registration_id','$procedure','$diagnosis',
'$surgeon','$consent','$anethesia','$assist_anethesia','$significant_history','$family_history',
'$cormobidities','$allergies','$medication','$obese',
'$healthy','$weak','$pale','$cooperative','$aweke','$unconscious','$aggressive','$dehydrated',
'$special_pregnantbp','$bp','$hr_pr','$temp','$rr','$spo2','$wt','$ht','$bmi','$rgb','$cvs','$lungs',
'$system','$normal','$limited','$micrognathia','$next_extension','$thyromental_distance','$loose',
'$impant','$normal_teeth','$rft','$lft','$elect','$hb_level','$blood_group','$fio2','$sao2','$be','$bic',
'$pco2','$ph','$cxr','$ecg_echo_ctc_scan','$anethetic_risk','$proposed_anethesia','$fasting_for',
'$blood_unit','$date','$name','$anethisiology_opinion','$patient_fasted','$elective_surgery',
'$emergency_surgery','$central_line','$spo2_morinitoring','$nbp','$ecg','$etco2','$gases','$iv',
'$inhalational','$rsi','$intubation','$comment','$circle','$t_iece','$mask','$lma','$nasal','$type','size','$spont','$cont','$tv','$press','$peep','$ie','$air','$o2','$haloth','$isofl','$sevofl','$others',
'$local_type','$conc','$amount','$position','$comments','$done_by')";

if ($result = mysqli_query($conn,$insert_anethesia)) {
	echo "Successfully saved";
}else{
	echo "Failed to save data " . mysqli_error($conn);
}

	
}else{
	echo "no id passed";
}
	





// 	echo $registration_id;


// }	

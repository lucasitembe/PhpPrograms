<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		$Employee_Title = $_SESSION['userinfo']['Employee_Title'];
	}else{
		$Employee_ID = '0';
		$Employee_Title = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Na_ya_Wodi'])){
		$Na_ya_Wodi = $_GET['Na_ya_Wodi'];
	}else{
		$Na_ya_Wodi = '';
	}

	if(isset($_GET['Name_Balozi'])){
		$Name_Balozi = $_GET['Name_Balozi'];
	}else{
		$Name_Balozi = '';
	}


	if(isset($_GET['Aina_ya_msamaha'])){
		$Aina_ya_msamaha = $_GET['Aina_ya_msamaha'];
	}else{
		$Aina_ya_msamaha = '';
	}

	if(isset($_GET['Education_Level'])){
		$Education_Level = $_GET['Education_Level'];
	}else{
		$Education_Level = '';
	}

	if(isset($_GET['Work_Wife'])){
		$Work_Wife = $_GET['Work_Wife'];
	}else{
		$Work_Wife = '';
	}

	if(isset($_GET['Mahudhulio'])){
		$Mahudhulio = $_GET['Mahudhulio'];
	}else{
		$Mahudhulio = '';
	}

	if(isset($_GET['Idadi_Mahudhulio'])){
		$Idadi_Mahudhulio = $_GET['Idadi_Mahudhulio'];
	}else{
		$Idadi_Mahudhulio = '';
	}

	if(isset($_GET['Ni_ndugu_yako_yupi'])){
		$Ni_ndugu_yako_yupi = $_GET['Ni_ndugu_yako_yupi'];
	}else{
		$Ni_ndugu_yako_yupi = '';
	}

	if(isset($_GET['amewahi_kutibiwa_mahali'])){
		$amewahi_kutibiwa_mahali = $_GET['amewahi_kutibiwa_mahali'];
	}else{
		$amewahi_kutibiwa_mahali = '';
	}

	if(isset($_GET['amevaa_nguo_za_thamani'])){
		$amevaa_nguo_za_thamani = $_GET['amevaa_nguo_za_thamani'];
	}else{
		$amevaa_nguo_za_thamani = '';
	}

	if(isset($_GET['mengineyo_yanayoonyesha_uwezo_wa_kuchangia'])){
		$mengineyo_yanayoonyesha_uwezo_wa_kuchangia = $_GET['mengineyo_yanayoonyesha_uwezo_wa_kuchangia'];
	}else{
		$mengineyo_yanayoonyesha_uwezo_wa_kuchangia = '';
	}

	if(isset($_GET['Mapendekezo_ya_msamaha'])){
		$Mapendekezo_ya_msamaha = $_GET['Mapendekezo_ya_msamaha'];
	}else{
		$Mapendekezo_ya_msamaha = '';
	}

	if(isset($_GET['anastahili_kupata_msamaha'])){
		$anastahili_kupata_msamaha = $_GET['anastahili_kupata_msamaha'];
	}else{
		$anastahili_kupata_msamaha = '';
	}

	if(isset($_GET['sahihi_anayependekeza_msamaha'])){
		$sahihi_anayependekeza_msamaha = $_GET['sahihi_anayependekeza_msamaha'];
	}else{
		$sahihi_anayependekeza_msamaha = '';
	}

	if(isset($_GET['Imehadhimishwa'])){
		$Imehadhimishwa = $_GET['Imehadhimishwa'];
	}else{
		$Imehadhimishwa = '';
	}

	if(isset($_GET['Jina_la_anayehadhimisha'])){
		$Jina_la_anayehadhimisha = $_GET['Jina_la_anayehadhimisha'];
	}else{
		$Jina_la_anayehadhimisha = '';
	}


	if(isset($_GET['cheo_anayehadhimisha'])){
		$cheo_anayehadhimisha = $_GET['cheo_anayehadhimisha'];
	}else{
		$cheo_anayehadhimisha = '';
	}


	if(isset($_GET['sahihi_anayehadhimisha'])){
		$sahihi_anayehadhimisha = $_GET['sahihi_anayehadhimisha'];
	}else{
		$sahihi_anayehadhimisha = '';
	}


	if(isset($_GET['Namba_katika_Rejista_ya_kupatiwa_msamaha'])){
		$Namba_katika_Rejista_ya_kupatiwa_msamaha = $_GET['Namba_katika_Rejista_ya_kupatiwa_msamaha'];
	}else{
		$Namba_katika_Rejista_ya_kupatiwa_msamaha = '';
	}

	if($Employee_ID != '0' && $Registration_ID != 0 && $Registration_ID != null && $Registration_ID != ''){
		$insert = mysqli_query($conn,"insert into tbl_msamaha(
								Registration_ID, Attendance_Date, na_wodi, jina_la_balozi,
								aina_ya_msamaha, kiwango_cha_elimu, kazi_mke, idadi_mahudhurio,
								idadi_kulazwa, ni_ndugu_yako, amewahi_kutibiwa, mgonjwa_amevaa_nguo, 
								mengineyo, mapendekezo_msamaha, anastahili, anayependekeza,
								cheo_ajayependekeza, sahihi_anayependekeza, imehadhimishwa, jina_anayehadhimisha,
								cheo_anayehadhimisha, sahihi_anayehadhimisha, namba_ktk_rejesta) 
							values('$Registration_ID',NOW(),'$Na_ya_Wodi','$Name_Balozi',
								'$Aina_ya_msamaha','$Education_Level','$Work_Wife','$Mahudhulio',
								'$Idadi_Mahudhulio','$Ni_ndugu_yako_yupi','$amewahi_kutibiwa_mahali','$amevaa_nguo_za_thamani',
								'$mengineyo_yanayoonyesha_uwezo_wa_kuchangia','$Mapendekezo_ya_msamaha','$anastahili_kupata_msamaha','$Employee_ID',
								'$Employee_Title','$sahihi_anayependekeza_msamaha','$Imehadhimishwa','$Jina_la_anayehadhimisha',
								'$cheo_anayehadhimisha','$sahihi_anayehadhimisha','$Namba_katika_Rejista_ya_kupatiwa_msamaha')") or die(mysqli_error($conn));
		if($insert){
			echo "yes";
		}else{
			echo "no";
		}
	}else{
		echo "string";
	}
?>
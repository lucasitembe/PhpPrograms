<?php
	session_start();
	include("./includes/connection.php");
   
       if(isset($_POST['no_routine'])){
		$no_routine = $_POST['no_routine'];
	}else{
		$no_routine = 0;
	}
       if(isset($_POST['yes_routine'])){
		$yes_routine = $_POST['yes_routine'];
	}else{
		$yes_routine = 0;
	}
       if(isset($_POST['routine'])){
		$routine = $_POST['routine'];
	}else{
		$routine = 0;
	}
       if(isset($_POST['priority'])){
		$priority = $_POST['priority'];
	}else{
		$priority = 0;
	}
       if(isset($_POST['ugent'])){
		$ugent = $_POST['ugent'];
	}else{
		$ugent = 0;
	}
       if(isset($_POST['na_follow'])){
		$na_follow = $_POST['na_follow'];
	}else{
		$na_follow = 0;
	}
       if(isset($_POST['no_follow'])){
		$no_follow = $_POST['no_follow'];
	}else{
		$no_follow = 0;
	}
       if(isset($_POST['yes_follow'])){
		$yes_follow = $_POST['yes_follow'];
	}else{
		$yes_follow = 0;
	}
       if(isset($_POST['NA'])){
		$NA = $_POST['NA'];
	}else{
		$NA = 0;
	}
       if(isset($_POST['no'])){
		$no = $_POST['no'];
	}else{
		$no = 0;
	}
       if(isset($_POST['safe'])){
		$safe = $_POST['safe'];
	}else{
		$safe = 0;
	}
       if(isset($_POST['relevant_supplied'])){
		$relevant_supplied = $_POST['relevant_supplied'];
	}else{
		$relevant_supplied = 0;
	}
       if(isset($_POST['zimmer_frame'])){
		$zimmer_frame = $_POST['zimmer_frame'];
	}else{
		$zimmer_frame = 0;
	}
       if(isset($_POST['sticks'])){
		$sticks = $_POST['sticks'];
	}else{
		$sticks = 0;
	}
       if(isset($_POST['mobile_safety'])){
		$mobile_safety = $_POST['mobile_safety'];
	}else{
		$mobile_safety = 0;
	}
       if(isset($_POST['mobile_aids'])){
		$mobile_aids = $_POST['mobile_aids'];
	}else{
		$mobile_aids = 0;
	}
       if(isset($_POST['addition_comments'])){
		$addition_comments = $_POST['addition_comments'];
	}else{
		$addition_comments = 0;
	}
       if(isset($_POST['any_lag'])){
		$any_lag = $_POST['any_lag'];
	}else{
		$any_lag = 0;
	}
       if(isset($_POST['slr'])){
		$slr = $_POST['slr'];
	}else{
		$slr = 0;
	}
       if(isset($_POST['knee_flex'])){
		$knee_flex = $_POST['knee_flex'];
	}else{
		$knee_flex = 0;
	}
       if(isset($_POST['irq'])){
		$irq = $_POST['irq'];
	}else{
		$irq = 0;
	}
       if(isset($_POST['sq'])){
		$sq = $_POST['sq'];
	}else{
		$sq = 0;
	}
       if(isset($_POST['raymed'])){
		$raymed = $_POST['raymed'];
	}else{
		$raymed = 0;
	}
       if(isset($_POST['wool_crepe'])){
		$wool_crepe = $_POST['wool_crepe'];
	}else{
		$wool_crepe = 0;
	}
       if(isset($_POST['brace'])){
		$brace = $_POST['brace'];
	}else{
		$brace = 0;
	}
       if(isset($_POST['checked_stable'])){
		$checked_stable = $_POST['checked_stable'];
	}else{
		$checked_stable= 0;
	}
       if(isset($_POST['reason_causes'])){
		$reason_causes = $_POST['reason_causes'];
	}else{
		$reason_causes = 0;
	}
       if(isset($_POST['altered'])){
		$altered = $_POST['altered'];
	}else{
		$altered = 0;
	}
       if(isset($_POST['infact'])){
		$infact = $_POST['infact'];
	}else{
		$infact= 0;
	}
       if(isset($_POST['pmh_dh'])){
		$pmh_dh = $_POST['pmh_dh'];
	}else{
		$pmh_dh = 0;
	}
       if(isset($_POST['operation_findings'])){
		$operation_findings = $_POST['operation_findings'];
	}else{
		$operation_findings = 0;
	}
       if(isset($_POST['interests_id'])){
		$interests_id = $_POST['interests_id'];
	}else{
		$interests_id = 0;
	}
       if(isset($_POST['treatment_id'])){
		$treatment_id = $_POST['treatment_id'];
	}else{
		$treatment_id = 0;
	}
       if(isset($_POST['Registration_ID'])){
		$Registration_ID = $_POST['Registration_ID'];
	}else{
		$Registration_ID = 0;
	}
   
         $mysqli_check_simulation_data=mysqli_query($conn,"SELECT knee_arthoscopy_ID FROM  tbl_knee_arthoscopy_save WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()");
   if(mysqli_num_rows($mysqli_check_simulation_data) > 0){
       
               $knee_arthoscopy_ID= mysqli_fetch_assoc(mysqli_query($conn,"SELECT knee_arthoscopy_ID FROM tbl_knee_arthoscopy_save WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()"))['knee_arthoscopy_ID'];
       
        $sql_save_data = mysqli_query($conn,"UPDATE tbl_knee_arthoscopy_save SET treatment_id='$treatment_id',interests_id='$interests_id',operation_findings='$operation_findings',pmh_dh='$pmh_dh',infact='$infact',altered='$altered',reason_causes='$reason_causes',checked_stable='$checked_stable',brace='$brace',raymed='$raymed',wool_crepe='$wool_crepe',sq='$sq',irq='$irq',knee_flex='$knee_flex',slr='$slr',any_lag='$any_lag',addition_comments='$addition_comments',mobile_aids='$mobile_aids',mobile_safety='$mobile_safety',sticks='$sticks',zimmer_frame='$zimmer_frame',relevant_supplied='$relevant_supplied',yes='$yes',no='$no',NA='$NA',yes_follow='$yes_follow',no_follow='$no_follow',na_follow='$na_follow',ugent='$ugent',priority='$priority',routine='$routine',yes_routine='$yes_routine',no_routine='$no_routine',date_time_transaction=NOW() WHERE knee_arthoscopy_ID='$knee_arthoscopy_ID'");
   }else{
        $mysqli_save_arthoscopy = mysqli_query($conn,"INSERT INTO tbl_knee_arthoscopy_save(Registration_ID,treatment_id,interests_id,operation_findings,pmh_dh,infact,altered,reason_causes,checked_stable,brace,raymed,wool_crepe,sq,irq,knee_flex,slr,any_lag,addition_comments,mobile_aids,mobile_safety,sticks,zimmer_frame,relevant_supplied,yes,no,NA,yes_follow,no_follow,na_follow,ugent,priority,routine,yes_routine,no_routine,date_time_transaction)values('$Registration_ID','$treatment_id','$interests_id','$operation_findings','$pmh_dh','$infact','$altered','$reason_causes','$checked_stable','$brace','$raymed','$wool_crepe','$sq','$irq','$knee_flex','$slr','$any_lag','$addition_comments','$mobile_aids','$mobile_safety','$sticks','$zimmer_frame','$relevant_supplied','$yes','$no','$NA','$yes_follow','$no_follow','$na_follow','$ugent','$priority','$routine','$yes_routine','$no_routine',NOW())");
        
   }
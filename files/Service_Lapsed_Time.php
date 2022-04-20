<?php
    require_once('./includes/connection.php');
	if(isset($_GET['LastTimeGiven'])){
		$LastTimeGiven = $_GET['LastTimeGiven'];
		$LastTimeGiven1 = strtotime($LastTimeGiven);
		echo time_ago($LastTimeGiven);
	} else {
		echo '';
	}
	
	function time_ago( $date ){
		if( empty( $date ) )
		{
			return "No date provided";
		}
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
               // mysql_query_Type("SELECT NOW() AS   datenow") ;
		$now =   strtotime(mysqli_fetch_assoc( mysqli_query($conn,"SELECT NOW() AS   datenow"))['datenow']); //time();
		$unix_date = strtotime( $date );
		// check validity of date
		if( empty( $unix_date ) )
		{
			return "Never Given";
		}
		// is it future date or past date
		if( $now > $unix_date ){
			$difference = $now - $unix_date;
			$tense = "ago";
		} else {
			$difference = $unix_date - $now;
			$tense = ".";
		}
		for( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ ) {
			$difference /= $lengths[$j];
		}
		$difference = round( $difference );
		if( $difference != 1 ){
			$periods[$j].= "s";
		}
		return "$difference $periods[$j] {$tense}";
	}
?>
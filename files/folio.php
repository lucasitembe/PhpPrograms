<?php
include("./includes/connection.php");
$Folio_Number = '';
$Sponsor_ID = 1;
//GENERATING FOLIO NUMBER 
		//get the current date		
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date; 
		}
                //check if the current date and the last folio number are in the same month
                //select the current folio number to check the month
		$current_folio = mysqli_query($conn,"select Folio_Number, Folio_date from tbl_folio where sponsor_id = '$Sponsor_ID' order by folio_id desc limit 1");;
                $no = mysqli_num_rows($current_folio); 
                if($no > 0){
                    while($row = mysqli_fetch_array($current_folio)){
                        $Folio_Number = $row['Folio_Number'];
                        $Folio_date = $row['Folio_date'];
                    } 
                }else{
                    $Folio_Number = 1;
                    $Folio_date = 0;
                }
	    
	    //END OF GENERATING FOLIO NUMBER
        echo 'Today date is '.$Today;
        echo '<br/>Current Folio Number is '.$Folio_Number;
        echo '<br/>Folio Date is '.$Folio_date;
        echo '<br/>Sponsor id is 1';
        echo '<br/>------------------------------------------------------------<br/><br/>';
        
        //compare month and year
        //check the current month and year
        $Current_Month_and_year = substr($Today,0,7);
        echo $Current_Month_and_year;
        
        echo '<br/>----------------------------------------<br/>';
        //check month and year of the last folio number
        $Last_Folio_Month_and_year = substr($Folio_date,0,7);
        echo $Last_Folio_Month_and_year;
            
        if($Last_Folio_Month_and_year == $Current_Month_and_year){
            
            echo 'Equal';
            mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id)
                        values(($Folio_Number+1),(select now()),'$Sponsor_ID')") or die(mysqli_error($conn));            
        }else{
            echo 'Not Equal'; 
            mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id)
                        values(1,(select now()),'$Sponsor_ID')") or die(mysqli_error($conn));            
        }
        
        
        
        
        
        
        echo '<br/><br/><br/>';
        
        $current_folio = mysqli_query($conn,"select Folio_Number, Folio_date from tbl_folio where sponsor_id = '$Sponsor_ID' order by folio_id desc limit 1");;
                $no = mysqli_num_rows($current_folio); 
                if($no > 0){
                    while($row = mysqli_fetch_array($current_folio)){
                        $Folio_Number = $row['Folio_Number'];
                        $Folio_date = $row['Folio_date'];
                    } 
                }else{
                    $Folio_Number = 1;
                    $Folio_date = 0;
                }
                
        echo 'Today date is '.$Today;
        echo '<br/>Current Folio Number is '.$Folio_Number;
        echo '<br/>Folio Date is '.$Folio_date;
        echo '<br/>Sponsor id is 1';
        echo '<br/>------------------------------------------------------------<br/><br/>';
?>
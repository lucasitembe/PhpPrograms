 <?php
  //  include("./includes/header.php");
    /*  @session_start();
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	   /* if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
/* 	    }*/
	/* }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    } */ 
	 include("./includes/connection.php");
?>


<?php
    $temp = 1;
	if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
	}
	//$Registration_ID = $_GET['Registration_ID']; 
	
//ft.Registration_ID='$Registration_ID'

	//function of list of patients from reception
	$Vital =mysqli_query($conn,"SELECT SUM(price) as vitas  from tbl_food_transaction 
	where Food_Time='LUNCH'  ") or die(mysqli_error($conn));
	$Vitals = mysqli_num_rows($Vital);
	if($Vitals>0){
            while($row = mysqli_fetch_array($Vital)){
                $vitas = $row['vitas'];
				}
				}
	//end of function of count
	
	
	//AND fm.Food_Name=ft.Food_Name
	$select_Food_Name=mysqli_query($conn,"select fm.Food_Name FROM tbl_food_menu fm,tbl_food_transaction ft
	WHERE fm.Food_Menu_ID=ft.Food_Menu_ID") or die(mysqli_error($conn));
	$results = mysqli_num_rows($select_Food_Name);
	if($results>0){
            while($row = mysqli_fetch_array($select_Food_Name)){
                $Food_Name = $row['Food_Name'];
				}
				}
	
	?>
	
	<?php
// $htm ;
	//to select info of patient
	if(isset($_GET['Registration_ID'])){  
		$Registration_ID = $_GET['Registration_ID']; 
	//	$Food_transaction_ID = $_GET['Food_transaction_ID'];   and Food_transaction_ID='$Food_transaction_ID'
	
		$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, Gender, 
			Date_Of_Birth,Food_transaction_ID,
			ft.Food_Standard,ft.Food_Time,ft.Days_of_Week,fm.Food_Name,
			fm.Food_Menu_ID,ft.Food_Menu_ID,Comments
		FROM 
			tbl_Patient_Registration pr, tbl_sponsor sp, tbl_food_transaction ft,tbl_food_menu fm
		WHERE 
			pr.sponsor_id = sp.sponsor_id and 
			pr.Registration_ID = ft.Registration_ID  AND 
			fm.Food_Menu_ID=ft.Food_Menu_ID AND
				pr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_Patient);
	  $Food_transaction_ID=0;						
	 if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Patient_Name = $row['Patient_Name'];
				$Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name']; 
                $Food_transaction_ID = $row['Food_transaction_ID']; 
                $Food_Time = $row['Food_Time']; 
                $Food_Name = $row['Food_Name']; 
                $Days_of_Week = $row['Days_of_Week']; 
                $Food_Standard = $row['Food_Standard'];
				$Comments= $row['Comments'];
				
	}
		$Age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
		 if($Age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->m." Months";
	    }
	    if($Age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->d." Days";
	    }
	}	}
	
	
	?>
	           
		<?php
		//$htm;
		$htm = "<p align='center'> GHARAMA ZA CHAKULA ZA MGONJWA WA KULAZWA </p>";
		$htm .="<fieldset style='width:100%;height:360px;'>";
	
		 $htm .= "<center><table width ='100%' border='1px' style='border-collapse:collapse;' >";
		 
		//$htm .= "<tr><td>Patient Name</td>";
		$htm .= "<tr ><td colspan='6' style='text-align:center;'>" .$Patient_Name. 
		",".$Registration_ID. ",".$Guarantor_Name. ",".$Gender. ",".$Age. "</td>";
			
		/* $htm .= "<td>Patient No </td>";
		$htm .= "<td>" .$Registration_ID. "</td>";
				
		$htm .= "<td>Sponsor</td>";
		$htm .= "<td>". $Guarantor_Name. "</td>";
			
		$htm .= "<td>Gender </td>";
		$htm .= "<td>".$Gender. "</td>";
				
		$htm .=	"<td>Age </td>"; */
		//$htm .= "<td>" .$Age. "</td>
		
		$htm .= "</tr>";
		
	//	$htm .= "</tr></table>";
	
	
    //$htm .=  '<center><table width ="100%" border="1px" >';
    $htm .= '<tr bgcolor="#D3D3D3"><td width="2%" style="text-align:center;"><b>SN</b></td>
				<td align="center" >TRANS DATE</td>
				<td align="center" >TRANSACTION NO</td>
				<td align="center" >FOOD TIME</td>
				<td align="center" >DIET STANDARD</td>
				<td align="center" >MENU NAME</td>
				<td align="center" >PRICE</td></tr>';
				
    $select_total = ("SELECT * FROM tbl_food_menu fm,
						tbl_food_transaction ft,tbl_patient_registration pr
		    WHERE  
					ft.Food_Menu_ID=fm.Food_Menu_ID AND 
					ft.Registration_ID=pr.Registration_ID AND 
					ft.Registration_ID='$Registration_ID'
					 GROUP BY  Food_transaction_ID ORDER BY Food_transaction_ID DESC ");
					
	$result1 = mysqli_query($conn,$select_total);
     $Pricesum = 0;
   $number=1;
    while($row = mysqli_fetch_array($result1)){
	  $Food_Name =$row['Food_Name'];
	  $price = $row['price'];
	  $Food_Standard = $row['Food_Standard'];
	  $Trans_Date_Time = $row['Trans_Date_Time'];
	  $Patient_Name = $row['Patient_Name'];
	  $Registration_ID = $row['Registration_ID'];
	  $Food_transaction_ID = $row['Food_transaction_ID'];
	  $Food_Time = $row['Food_Time'];
        
	 $Pricesum+=$price;
					
		$htm .= "<tr>";
		$htm .= "<td >".$number."</td>";
	
		$htm .=  "<td >".substr($Trans_Date_Time,0,10)."</td>";
		$htm .=  "<td align='center'>".$Food_transaction_ID."</td>";
		$htm .=  "<td >".$Food_Time."</td>";
		
		$htm .= "<td >".$Food_Standard."</td>";
		$htm .=  "<td >".ucwords(strtolower($Food_Name))."</td>";
		
		$htm .= "<td align='right'>" .number_format($price)."</td>";
	 
	$htm .=  "</tr>";
	$number++;
	}
		
	$htm .= "<tr><td colspan=7><hr></td></tr>";
	
/* 	 if(mysqli_num_rows($result)==0){

	
$htm .=  "<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td> </tr>";
    } */
	
	//align='right'
  $htm .=	"<tr><td colspan=6 style='text-align:right;'><b>TOTAL</b></td>";
   $htm .=	" <td  style='text-align: right' >" .number_format($Pricesum). "</td> </tr>";
   
$htm .= "</table></center>";


$htm .= "</1></center>";

//echo $htm;
      include("MPDF/mpdf.php");
    $mpdf=new mPDF(); 
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;    
?>
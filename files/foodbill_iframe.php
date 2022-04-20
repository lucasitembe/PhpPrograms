<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
	 if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }

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
	?>
	
	<?php
	echo '<center><table width ="100%" border="0px">';
    echo '<tr bgcolor="#D3D3D3" id="thead"><td style="text-align:center;width:5%;"><b>SN</b></td>
				<td align="center" >PATIENT NAME</td>
				<td align="center" >PATIENT NO</td>
				<td align="center" >TRANSACTION NO</td>
				<td align="center" >DIET STANDARD</td>
				<td align="center" >MENU NAME</td>
				<td align="center" >PRICE</td>
			
		</tr>';	
   
   $select_total = "SELECT * FROM tbl_food_menu fm,tbl_food_transaction ft,tbl_patient_registration pr
		    WHERE  ft.Food_Menu_ID=fm.Food_Menu_ID AND 
				ft.Registration_ID=pr.Registration_ID AND 
					Patient_Name LIKE '%$Patient_Name%'
						GROUP BY  Food_transaction_ID ORDER BY Food_transaction_ID DESC ";
					
	$result = mysqli_query($conn,$select_total);
     $Pricesum = 0;
   
    while($row = mysqli_fetch_assoc($result)){
	  $price = $row['price'];
	  $Food_Name = $row['Food_Name'];
	  $price = $row['price'];
	  $Food_Standard = $row['Food_Standard'];
	  $Trans_Date_Time = $row['Trans_Date_Time'];
	  $Patient_Name = $row['Patient_Name'];
	  $Registration_ID = $row['Registration_ID'];
	  $Food_transaction_ID = $row['Food_transaction_ID'];
    
	  $Pricesum+=$price;
		
		echo "<tr ><td id='thead'>".$temp." </td>";
	
		echo "<td ><a href='foodpatientbilling.php?Registration_ID=".$row['Registration_ID']."&Foodbiling=FoodbillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))." </a></td>";
	
		echo "<td ><a href='foodpatientbilling.php?Registration_ID=".$row['Registration_ID']."&Foodbiling=FoodbillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		
		echo "<td ><a href='foodpatientbilling.php?Registration_ID=".$row['Registration_ID']."&Foodbiling=FoodbillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Food_transaction_ID']."</a></td>";
		
		echo "<td width=20%><a href='foodpatientbilling.php?Registration_ID=".$row['Registration_ID']."&Foodbiling=FoodbillingThisForm' target='_parent' style='text-decoration: none;'>".$row['Food_Standard']." </a></td>";
		
		echo "<td width=20%><a href='foodpatientbilling.php?Registration_ID=".$row['Registration_ID']."&Foodbiling=FoodbillingThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Food_Name']))."</a></td>";
		
	
		?>
		<td style='text-align: right'><a href='foodpatientbilling.php?Registration_ID=<?php echo $Registration_ID ;?>&Foodbiling=FoodbillingThisForm' target='_parent' style='text-decoration: none;'><?php if($price !=0){echo number_format($price);}else{echo '0';}?></a></td>
		<?php
	 $temp++;
	 echo "</tr>";
	}
	echo"<tr>
			<td colspan=7><hr></td>
    </tr>";
		if(mysqli_num_rows($result)==0){
	?>
	<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
   </tr>
     <?php } ?>
	<tr>
		<td colspan='6'style='text-align: right'><b>TOTAL</b></td>
		<td align='right'><input type='text' value='<?php echo number_format($Pricesum); ?>' style='width: 100%;text-align: right' readonly='readonly'></td>
	</tr>  	
</table>
</center>
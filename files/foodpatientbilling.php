 <?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='foodbilling.php' class='art-button-green'>
       BACK
    </a>
<?php  } } ?>

 <br>
 <br>
 <center>
 <fieldset style="width:80%;height:360px;overflow-y:scroll;">

<?php
    include("./includes/connection.php");
    $temp = 1;
	if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
	}
	$Registration_ID = $_GET['Registration_ID']; 
	
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
	WHERE fm.Food_Menu_ID=ft.Food_Menu_ID
	") or die(mysqli_error($conn));
	$results = mysqli_num_rows($select_Food_Name);
	if($results>0){
            while($row = mysqli_fetch_array($select_Food_Name)){
                $Food_Name = $row['Food_Name'];
				}
				}
	
	?>
	
	<?php
	
	
	//to select info of patient
	if(isset($_GET['Registration_ID'])){  
		$Registration_ID = $_GET['Registration_ID']; 
	//	$Food_transaction_ID = $_GET['Food_transaction_ID'];   and Food_transaction_ID='$Food_transaction_ID'
	
		$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, Gender, Date_Of_Birth,Food_transaction_ID,
		ft.Food_Standard,ft.Food_Time,ft.Days_of_Week,fm.Food_Name,fm.Food_Menu_ID,ft.Food_Menu_ID,Comments
			FROM tbl_Patient_Registration pr, tbl_sponsor sp, tbl_food_transaction ft,tbl_food_menu fm
		WHERE pr.sponsor_id = sp.sponsor_id and pr.Registration_ID = ft.Registration_ID  AND fm.Food_Menu_ID=ft.Food_Menu_ID AND
		pr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_Patient);
	  $Food_transaction_ID=0;							}
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
	}
	
	
	?>
	<!-- to remove item and reduce totola value to the table-->
	<script type='text/javascript'>
        function removeitem(Payment_Item_Cache_List_ID){
             if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','removeitemcahe.php?Payment_Item_Cache_List_ID='+Payment_Item_Cache_List_ID,true);
	    mm.send();
        }
        function AJAXP() {
	var data = mm.responseText;
            if(mm.readyState == 4){
                document.location.reload();
            }
        }
    </script>
	<!--end of script-->
	
	
	
		<table  class='hiv_table'>
		<tr>
				<td width='10%' style="resize:none;text-align:right;">Patient Name</td>
				<td width='20%' colspan="2"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name;?>" /> </td>
			
				<td width='8%' style="resize:none;text-align:right;">Patient No</td>
				<td width='8%'><input type="text" name="Registration_ID" id="Registration_ID"  readonly='readonly' value="<?php echo$Registration_ID;?>" /> </td>
				
				<td width='6%' style="resize:none;text-align:right;">Sponsor</td>
				<td width='10%' colspan="2"><input type="text"  name="Guarantor_Name" disabled='disabled' id="Guarantor_Name" value="<?php echo$Guarantor_Name;?>" ></td>
			
				<td width='6%' style="resize:none;text-align:right;">Gender</td>
				<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender;?>" disabled='disabled' ></td>
				
				<td width='6%' style="resize:none;text-align:right;">Age</td>
				<td width='6%'><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age;?>" ></td>
		</tr>
		
	</table>
	<hr>
	
	<?php
    echo '<center><table width ="100%" border="0px" >';
    echo '<tr bgcolor="#D3D3D3"><td width="2%" style="text-align: center;"><b>SN</b></td>
				<td align="center" >TRANS DATE</td>
				<td align="center" >TRANSACTION NO</td>
				<td align="center" >FOOD TIME</td>
				<td align="center" >DIET STANDARD</td>
				<td align="center" >MENU NAME</td>
				<td align="center" >PRICE</td>
			
				
				 </tr>';	
    $select_total = "SELECT * FROM tbl_food_menu fm,tbl_food_transaction ft,tbl_patient_registration pr
		    WHERE  ft.Food_Menu_ID=fm.Food_Menu_ID AND 	ft.Registration_ID=pr.Registration_ID AND ft.Registration_ID='$Registration_ID'
			 GROUP BY  Food_transaction_ID ORDER BY Food_transaction_ID DESC ";
					
	$result = mysqli_query($conn,$select_total);
     $Pricesum = 0;
   
    while($row = mysqli_fetch_assoc($result)){
	  $price = $row['price'];
	  $Food_Name =$row['Food_Name'];
	  $price = $row['price'];
	  $Food_Standard = $row['Food_Standard'];
	  $Trans_Date_Time = $row['Trans_Date_Time'];
	  $Patient_Name = $row['Patient_Name'];
	  $Registration_ID = $row['Registration_ID'];
	  $Food_transaction_ID = $row['Food_transaction_ID'];
	  $Food_Time = $row['Food_Time'];
        
	 $Pricesum+=$price;
					
		echo "<tr ><td><input type='text' size=3 value = '".$temp."' style='text-align: center;' readonly='readonly'></td>";
	
		echo "<td ><input style='width: 100%;'type='text' value='".substr($row['Trans_Date_Time'],0,10)."' readonly='readonly'  ></td>";
		echo "<td ><input style='width: 100%;'type='text' value='".$row['Food_transaction_ID']."' readonly='readonly'  ></td>";
		echo "<td ><input style='width: 100%;'type='text' value='".$row['Food_Time']."' readonly='readonly'  ></td>";
		
		echo "<td width=20%><input style='width: 100%;'	type='text' value='".$row['Food_Standard']."' > </td>";
		echo "<td width=20%><input style='width: 100%;'	type='text' value='".ucwords(strtolower( $row['Food_Name']))."' > </td>";
		
	/* 	echo "<td><input style='width: 100%;text-align: center;'
				type='text' value='".$row['Quantity']."'  readonly='readonly' ></td>";
		
		echo "<td> NA&nbsp</td>"; */
		?>
		<td align='right'><input style='width: 100%;text-align: right;' type='text' value='<?php if($price !=0){echo number_format($price);}else{echo '0';}?>'readonly ></td>
		
	
	
		<?php
	 $temp++;
	 echo "</tr>";
	}
	
	
	echo"<tr>
	<td colspan=8><hr></td>
    </tr>";
	
	 if(mysqli_num_rows($result)==0){
	?>
	
	<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>

    </tr>
     <?php
    }
    ?>

    <tr>
    <td colspan=6 style="resize:none;text-align:right;"><b>TOTAL</b></td>
    <td align='right'><input type='text' value='<?php echo number_format($Pricesum); ?>' style='width: 100%;text-align: right' readonly='readonly'</td>
	
  
    </tr>
   	
</table></center>


</fieldset>

</center>
<?php
    include("./includes/footer.php");
	
	?>

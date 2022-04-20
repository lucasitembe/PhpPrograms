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
    <a href='foodkitchen.php' class='art-button-green'>
       BACK
    </a>
<?php  } } ?>

 <br>
 <br>
 <center>
 <fieldset style="width:80%;height:360px;overflow:scroll;">
  <?php
 
  
  //to find no of patient who want kind of food
  
  //declaration 
 


	 $i=1;
				
  $select_Food=mysqli_query($conn,"SELECT * 
			FROM 
				tbl_food_transaction ft,tbl_food_menu fm,tbl_patient_registration pr	
			WHERE ft.Food_Time='DINNER'  and DATE(Trans_Date_Time)='".DATE("Y-m-d")."' AND

			ft.Food_Menu_ID=fm.Food_Menu_ID AND pr.Registration_ID=ft.Registration_ID GROUP BY  fm.Food_Menu_ID
			
					 ") or die (mysqli_error($conn));
					 
					 	 while($row1 = mysqli_fetch_array($select_Food)){
					$Food_Menu_ID = $row1['Food_Menu_ID'];
					$Food_Name = $row1['Food_Name'];
			
			//table header for each table
	echo"<b><div style='font-size:18px;text-align:left;'>
		   ".$i.".  ".ucwords(strtolower($row1['Food_Name']))."</div></b>";
			 $i++;			
//END OF header

 $temp = 1;
$select_Name=mysqli_query($conn,"SELECT * 
			FROM 
				tbl_food_transaction ft,tbl_food_menu fm,tbl_patient_registration pr	
			WHERE ft.Food_Time='DINNER'  and DATE(Trans_Date_Time)='".DATE("Y-m-d")."' AND 

			pr.Registration_ID=ft.Registration_ID AND ft.Food_Menu_ID=fm.Food_Menu_ID AND fm.Food_Menu_ID='$Food_Menu_ID' AND fm.Food_Name='$Food_Name'
					 ") or die (mysqli_error($conn));
					 
					 
			echo '<center><table width =100% border=0>';
						echo '<tr><th width ="2%">SN</th>
								<th >PATIENT NAME</th>
								<th >PATIENT NO</th>
								<th >TRANSACTION NO</th>
								<th >MENU STANDARD</th>
								
								
								</tr>';
								
			 $no = mysqli_num_rows($select_Name);
		
	if($no>0){
	
	while($row = mysqli_fetch_array($select_Name)){
						$Patient_Name = $row['Patient_Name'];
						$Food_Name = $row['Food_Name'];
						$Registration_ID = $row['Registration_ID'];
						$Food_transaction_ID = $row['Food_transaction_ID'];
						$Food_Standard = $row['Food_Standard'];
						
						
						
				
				
echo "<tr><td style='text-align:center;'>".$temp."</td><td><input type='text' value='".$row['Patient_Name']."' name='Product_Name' readonly='readonly'></td>";
echo "<td><input type='text' value='".$row['Registration_ID']."'  name='Doctor_Comment'  readonly='readonly'></td>";
echo "<td><input type='text' value='".$row['Food_transaction_ID']."'  name='Doctor_Comment'  readonly='readonly'></td>";
echo "<td><input type='text' value='".$row['Food_Standard']."'  name='Doctor_Comment'  readonly='readonly'></td>";

 $temp++;

			}
				echo"</tr>";
			echo '</table>';
			}
			echo '</center>';
			echo '<br/>';			
					
				  
		
		}
		
?>

</fieldset>
</center>
<?php
    include("./includes/footer.php");
?>
<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Patient_Name'])){
        $Patient_Name = $_GET['Patient_Name'];   
    }else{
        $Patient_Name = '';
    }
	
	 if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];   
    }else{
        $Registration_ID = '';
    }

//table for technical instruction
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">	<td style="width:5%;"><b>SN</b></td>
				<td><b>PATIENT NAME</b></td>
				<td><b>PATIENT NO</b></td>
				<td><b>GENDER</b></td>
				 <td><b>TRANSACTION NO</b></td>
				 <td><b>MENU NAME</b></td>
				 <td><b>PHONE NO</b></td>
                            </tr>';
    $select_Food = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, Gender, Date_Of_Birth,Food_transaction_ID,pr.Phone_Number,
		ft.Food_Standard,ft.Food_Time,ft.Days_of_Week,fm.Food_Name,fm.Food_Menu_ID,ft.Food_Menu_ID
			FROM tbl_Patient_Registration pr, tbl_sponsor sp, tbl_food_transaction ft,tbl_food_menu fm
		WHERE pr.Patient_Name like '%$Patient_Name%' AND fm.Food_Menu_ID=ft.Food_Menu_ID
		AND pr.sponsor_id = sp.sponsor_id and pr.Registration_ID = ft.Registration_ID ORDER BY Food_transaction_ID desc") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_Food)){
        echo "<tr><td id='thead'>".$temp."</td><td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Food_transaction_ID=".$row['Food_transaction_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
		
		echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Food_transaction_ID=".$row['Food_transaction_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
		
        echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Food_transaction_ID=".$row['Food_transaction_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        
        echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Food_transaction_ID=".$row['Food_transaction_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Food_transaction_ID']."</a></td>";
        echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Food_transaction_ID=".$row['Food_transaction_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Food_Name']))."</a></td>";
		
		 echo "<td><a href='foodcomments.php?Registration_ID=".$row['Registration_ID']."&Food_transaction_ID=".$row['Food_transaction_ID']."&Food=FoodThisForm' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
    $temp++;
	echo "</tr>";
    }   
?></table></center>
<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
 
	 if(isset($_GET['laundry_in_or_out_ID'])){
        $laundry_in_or_out_ID = $_GET['laundry_in_or_out_ID'];   
    }else{
        $laundry_in_or_out_ID = '';
    }

//table for technical instruction
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead">	<td width = 5%><b>SN</b></td>
				<td><b>PRODUCT NAME</b></td>
				<td><b>TRANS NO</b></td>
				<td><b>FROM</b></td>
                            </tr>';
					
					if($laundry_in_or_out_ID !=''){
					$select_laundry_out=mysqli_query($conn,"SELECT * FROM tbl_laundry_in_or_out
		WHERE Status='IN' and  laundry_in_or_out_ID='$laundry_in_or_out_ID' ORDER BY laundry_in_or_out_ID DESC") or die(mysqli_error($conn));
		}else{
			$select_laundry_out=mysqli_query($conn,"SELECT * FROM tbl_laundry_in_or_out
		WHERE Status='IN' ORDER BY laundry_in_or_out_ID DESC") or die(mysqli_error($conn));
		}
	
			while($row=mysqli_fetch_array($select_laundry_out)){
			   $laundry_in_or_out_ID = $row['laundry_in_or_out_ID'];
			   $Quantity = $row['Quantity'];
			   $Product_Name = $row['Product_Name'];
			   $From_To = $row['From_To'];
		
        echo "<tr><td>".$temp."</td><td><a href='laundryout.php?laundry_in_or_out_ID=".$row['laundry_in_or_out_ID']."&Laundry=LaundryThisForm' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Product_Name']))."</a></td>";
		
		echo "<td><a href='laundryout.php?laundry_in_or_out_ID=".$row['laundry_in_or_out_ID']."&Laundry=LaundryThisForm' target='_parent' style='text-decoration: none;'>".$row['laundry_in_or_out_ID']."</a></td>";
		
        echo "<td><a href='laundryout.php?laundry_in_or_out_ID=".$row['laundry_in_or_out_ID']."&Laundry=LaundryThisForm' target='_parent' style='text-decoration: none;'>".$row['From_To']."</a></td>";
        
        echo "</tr>";
    $temp++;
    }  
?>
</table></center>
<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    $temp = 1;
	if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
	}
	
$Registration_ID = $_GET['Registration_ID']; 
	
	
	?>
	<!-- to remove item and reduce totola value to the table-->
	<script type='text/javascript'>
        function removeitem(Payment_Item_Cache_List_ID) {
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
	
	<?php
	
    echo '<center><table width ="100%" border="0px">';
    echo '<tr bgcolor="#D3D3D3"><td width="5%" style="text-align: center;"><b>DATE</b></td>
			<td width="35%">DIET TYPE</td>
				<td width=8%>PRICE</td>
	
				 </tr>';	
				 
    $select_food_diet = mysqli_query($conn,"SELECT * FROM tbl_fooddiet 
		    WHERE Registration_ID='$Registration_ID' AND Meal_Time='DINNER' ");
			
	/* $result = mysqli_query($conn,$qr);
     $Cashsum = 0;
    $Creditsum = 0;
    while($row = mysqli_fetch_assoc($result)){
        
		if($row['Transaction_Type']=='Cash'){
			$Cash = $row['Price']*$row['Quantity'];
			$Credit = 0;
		}else{
			$Credit = $row['Price']*$row['Quantity'];
			$Cash = 0;
		}
		$Cashsum+=$Cash;
		$Creditsum+=$Credit;
					 */
					 
		 while($row = mysqli_fetch_array($select_food_diet)){
                $Description = $row['Description'];
                $Restriction = $row['Restriction'];
				$Diet_Std = $row['Diet_Std'];
				$Food_Date = $row['Food_Date'];
				$Food_Date_Time = $row['Food_Date_Time'];
            
					 
		echo "<tr><td>".$row['Food_Date_Time']. "</td>";
	
		echo "<td >".$row['Diet_Std']."</td>";
		
		echo "<td width=20%></td>";
		
		$temp++;
	} 
		?>
		
	
</tr>
	
</table></center>
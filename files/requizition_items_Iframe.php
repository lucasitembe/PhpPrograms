<?php
    include("./includes/connection.php"); 
	
	    if(isset($_GET['requision_id'])){
        $requision_id = $_GET['requision_id'];
    }else{
	$requision_id=0;
	}
    echo '<center><table width =100% border=1>';
    echo '<tr><td><b>S/N</b></td>
                <td><b>ITEM DESCRIPTION</b></td>
                            <td><b>QUANTITY REQUIRED</b></td>';
    
    
    $select_Transaction_Items = mysqli_query($conn,"select * from tbl_requizition_items where Requizition_ID='$requision_id'"); 

	$i=1;    
    while($row = mysqli_fetch_array($select_Transaction_Items)){
        echo "<tr><td>".$i."</td>";
        echo "<td>".$row['Item_Name']."</td>";
        echo "<td>".$row['Quantity_Required']."</td>";
       echo "</tr>";
       $i++;
    }
?></table></center>


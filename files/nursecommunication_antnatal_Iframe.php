<?php
    include("./includes/connection.php"); 
	
    echo '<center><table width =100% border="0">';         
    echo '<tr><td><b>S/N</b></td>
                <td><b>Date and Time</b></td>
                            <td><b>BP</b></td><td><b>Pulse</b></td><td><b>Temperature</b></td>
                            <td><b>Respiration</b></td><td><b>FHR</b></td><td><b>FH</b></td><td><b>Urine For Proten</b></td>';
    
    
  //  $select_Transaction_Items = mysqli_query($conn,"select * from tbl_requizition_items where Requizition_ID='$requision_id'"); 

	$i=1;    
    while($i<8){
            if ($i%2==0){
            $color='#FDF2FF';
            }else{
            $color='white';
            }
            echo "<tr bgcolor=".$color.">";
            echo "<td>".$i."</td>";
            echo "<td>".DATE('d-m-y')."</td>";
            echo "<td>Val</td>";
            echo "<td>Val</td>";
            echo "<td>Val</td>";
            echo "<td>Val</td>";
            echo "<td>Val</td>";
            echo "<td>Val</td>";
            echo "<td>Val</td>";
           echo "</tr>";
           $i++;
    }
?></table></center>
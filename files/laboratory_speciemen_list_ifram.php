<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    //Find the current date to filter check in list
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td width = 5%><b>SN</b></td>
    <td><b>Sample Name</b></td>
     <td><b>Sample Container</b></td>
    <td width=20%>Action</td>';


    $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen WHERE Status='Active'");
        while($row = mysqli_fetch_array($select_lab_products)){
        echo "<tr><td>".$temp."</td>
        <td>".$row['Specimen_Name']."</td>
        <td>".$row['Sample_Container']."</td>
        <td style='width:150px'><button class='EditSpecimen' id=".$row['Specimen_ID']." name='".$row['Specimen_Name']."'>EDIT</button>
        <button class='DeleteSpecimen' id=".$row['Specimen_ID'].">DEACTIVATE</button></td>";

        $temp++;
        echo "</tr>";
      }   
       
  ?>
</table>
</center>
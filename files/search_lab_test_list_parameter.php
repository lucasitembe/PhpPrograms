<link rel="stylesheet" href="table.css" media="screen">
<?php
    include("./includes/connection.php");
    $temp = 1;
    if(isset($_GET['Product_Name'])){
        $Product_Name = $_GET['Product_Name'];   
    }else{
        $Product_Name = '';
    } 
    //Find the current date to filter check in list
    
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date; 
    }
    echo '<center><table width =100% border=0>';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
    <td><b>TEST NAME</b></td></tr>';


  $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_items where Consultation_Type='Laboratory' and Product_Name like '%$Product_Name%'")
                                               or die(mysqli_error($conn));
                                                                
    



                                          while($row = mysqli_fetch_array($select_lab_products)){




        echo "<tr><td id='thead'>".$temp."</td>
        <td><a href='laboratory_parameter_setup.php?Item_ID=".$row['Item_ID']."' target='_parent' style='text-decoration: none;'>".$row['Product_Name']."</a></td>";

        $temp++;
        echo "</tr>";
      }   
       
  ?>
</table>
</center>
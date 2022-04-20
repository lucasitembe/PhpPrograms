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
    <td><b>Test Name</b></td>
    <td>Action</td>';


  $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_laboratory_parameters where Laboratory_Parameter_Name like '%$Product_Name%'")
                                               or die(mysqli_error($conn));
                                                                
    



                                          while($row = mysqli_fetch_array($select_lab_products)){




        echo "<tr><td id='thead'>".$temp."</td>
        <td>".$row['Laboratory_Parameter_Name']."</td>
         <td style='width:150px'><a href='laboratory_setup_parameters.php?Update_Specimen=tru&Laboratory_Parameter_ID=".$row['Laboratory_Parameter_ID']."&Action=Edit' target='_parent'><button>EDIT</button></a>
        <a  href='laboratory_setup_parameters.php?addLaboratorySpecimen=speciemn&Laboratory_Parameter_ID=".$row['Laboratory_Parameter_ID']."&Action=Delete' target='_parent'><button>DELETE</button></a></td>";

        $temp++;
        echo "</tr>";
      }   
       
  ?>
</table>
</center>
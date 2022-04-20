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
    echo '<center>
        <table width =100% id="listAllLabItems" class="display">
          <thead>
            <tr>
                <td width = 5%><b>SN</b></td>
                <td><b>TEST NAME</b></td>
                <td><b>SUB CATEGORY</b></td>
                <td><b>MANAGE SPECIMEN &amp; PARAMETERS</b></td>
            </tr>
          </thead>';
            
  $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_items JOIN tbl_item_subcategory ON tbl_items.Item_Subcategory_ID=tbl_item_subcategory.Item_Subcategory_ID where Consultation_Type='Laboratory' ");
                       while($row = mysqli_fetch_array($select_lab_products)){

        echo "<tr><td>".$temp."</td>
        <td>".$row['Product_Name']."</td>
        <td>".$row['Item_Subcategory_Name']."</td>
        <td>
            <a href='laboratory_setup_test.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage&Item_ID=".$row['Item_ID']."' target='_parent' style='text-decoration: none;' class='viewSpecimenhere' id='".$row['Item_ID']."' name='".$row['Product_Name']."' style='border-style:none'>Manage specimen</a>
            <button class='viewParameterhere' id='".$row['Item_ID']."' name='".$row['Product_Name']."' style='border-style:none'>Manage Parameters</button>
        </td>";
        $temp++;
        echo "</tr>";
      }   
       
  ?>
</table>
</center>

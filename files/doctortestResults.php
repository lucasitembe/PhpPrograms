  <?php
include("./includes/connection.php");
 if (isset($_POST['payment'])) {
    $payment = $_POST['payment'];
} else {
    $payment = '';
}
$gram_stein="";
$Culture_ID="";
$wet_prepation="";
$sign="";
$gram_stein="";
$Specimen="";
$Remarks="";

        $query = "SELECT Culture_ID,wet_prepation,gram_stein,sign,deseases,payment_item_ID,Specimen,Organism1,Remarks FROM tbl_culture_results WHERE payment_item_ID='$payment'";
        $myQuery = mysqli_query($conn,$query);
       while($row2 = mysqli_fetch_assoc($myQuery)){
        
                       $deseases = $row2['deseases'];
                       $Culture_ID = $row2['Culture_ID'];
                       $payment_item_ID = $row2['payment_item_ID'];
                       $wet_prepation = $row2['wet_prepation'];
                       $sign = $row2['sign'];
                        $Specimen = $row2['Specimen'];
                       $gram_stein = $row2['gram_stein'];
                       $Organism1 = $row2['Organism1'];
                       $Remarks = $row2['Remarks'];
                       
         $specimen_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Specimen_Name FROM tbl_laboratory_specimen WHERE Specimen_ID='$Specimen'"))['Specimen_Name'];
         $organism_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT organism_name FROM tbl_organism WHERE organism_id='$Organism1'"))['organism_name'];
                                           
       }               
//         
         echo "<br>";
         echo "<br>";
        echo "<center><table cellspacing='0' cellpadding='0'  width=1000px; class='table'>";
        echo "<tr><td><center><b>Specimen Type</b></center></td>
        <td  colspan='3'>
                $specimen_name 
                </td></tr>";
        
           echo "<tr>
                <td><center><b>Organism</b></center></td>
                <td  colspan='3'>
                $organism_name
                </td>
           
           <div id=''>";      
        
        echo "               
</td>
            </tr>";
          echo "<tr >
                  <td width='10%'>
                  <center> <b>WET PREPATION</b></center>
                  </td>
                  <td colspan='3'>
                  $wet_prepation
                  </td>
            
                </tr>";
           echo "<tr>
                   <td rowspan='2'>
                  <center> <b> GRAM STEIN</b></center>
                  </td>    
                   <td width='50%'>
                       $sign
                     
                  </td>
                  <td>";
                  $query1 = "SELECT deseases_name FROM tbl_deseases WHERE culture_id='$Culture_ID'";
                 $myQuery3 = mysqli_query($conn,$query1);
                  
                  while($row3 = mysqli_fetch_assoc($myQuery3)){
                 $deseases_name = $row3['deseases_name'];
                 echo  "$deseases_name ,";
                    }
                 echo "</td>    
                 <tr>
                  <td colspan='2'>
                    $gram_stein
                  </td>
                  </tr>
            </tr>";
               echo "<tr><td><center><b>Biochemical Tests</b></center></td><td  colspan='3'><table class='table'>";
             $query1 = "SELECT biotest FROM tbl_biotest WHERE culture_id='$Culture_ID'";
        $myQuery3 = mysqli_query($conn,$query1);
       
       while($row3 = mysqli_fetch_assoc($myQuery3)){
                      
                         $biotest = $row3['biotest'];
       echo "<tr><td>
                $biotest
               </td></tr>";
        }
        
     echo "</table></td></tr><tr><tr><td><center><b>Antibiotic</b></center></td><td  colspan='3'><table class='table'>";
             $query2 = "SELECT antibiotic,an.antibiotic_result FROM tbl_antibiotic an WHERE culture_id='$Culture_ID'";
        $myQuery4 = mysqli_query($conn,$query2);
       while($row4 = mysqli_fetch_assoc($myQuery4)){
                      
                         $antibiotic = $row4['antibiotic'];
                         $antibiotic_result = $row4['antibiotic_result'];
                 $item_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items an WHERE Item_ID='$antibiotic'"))['Product_Name'];     
                         
       echo "<tr><td>
                $item_name
               </td>";
        echo "<td>
                $antibiotic_result
               </td></tr>";
        }
        echo "</table>";
         echo $cached_data;
      
        echo '</table>';
        echo 'Remarks:<textarea id="Remarks" style="width:50%;padding-left:5px;margin-top:5px" disabled>' . $Remarks . '</textarea><br />';
       echo '<input type="button"  class="art-button-green" id="saveCulture24" onclick="preview_lab_result(' .  $payment . ')"  name="' .  $payment . '" value="Preview" />';
       
       
        ?>

 <script>
 function preview_lab_result(payment_id){
    window.open("preview_micro_biology_result.php?payment_id="+payment_id,"_blank");
 }
 </script>
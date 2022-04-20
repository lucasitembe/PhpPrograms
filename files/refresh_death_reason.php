<?php
 include("./includes/connection.php");
 $Registration_ID=$_GET['Registration_ID'];

 $select_added_disease_result=mysqli_query($conn,"SELECT *FROM tbl_disease_caused_death_cache WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
 if(mysqli_num_rows($select_added_disease_result)>0){
     $count=1;
     while($added_reason_cache_rows=mysqli_fetch_assoc($select_added_disease_result)){
         $disease_death_ID=$added_reason_cache_rows['disease_death_ID'];
         $disease_name=$added_reason_cache_rows['disease_name'];
         $disease_code=$added_reason_cache_rows['disease_code'];
         echo "<tr>
                    <td style='50px'>$count</td>
                    <td>$disease_name</td>
                    <td>$disease_code</td>
                    <td>
                        <input type='button' value='X' onclick='remove_added_death_disease($disease_death_ID,$Registration_ID)'/>
                    </td>
              </tr>";
         $count++;
     }
 }
 ?>

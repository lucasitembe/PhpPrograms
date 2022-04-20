<?php
include("../includes/connection.php");

  
               
              $cached_data.="<select class='antibiotc' name='antibiotic[]' id='1' style='width:300px; padding-top:4px; padding-bottom:4px;'>";
                   $cached_data.= '<option value="All">~~~~~Select Antibiotic~~~~~</option>'
                         ?><?php   $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy'") or die(mysqli_error($conn));
                         while ($row = mysqli_fetch_array($query_sub_specimen)) {
                          $cached_data.= '<option value="' . $row['Item_ID'] . '">' . $row['Product_Name'] . '</option>';
                         }?><?php
                        $cached_data.= "</select>";
                        $cached_data.= "<select class='seleboxorg1' name='sensitive[]' id='orgone_1' style='width:300px; padding-top:4px; padding-bottom:4px;'><option>-----select---</option><option>Sensitive</option><option>Resistant</option><option>Intermediate</option></select>
                    </td>
            <input type='hidden' id='rowCount' value='1'>

            ";
         echo $cached_data;

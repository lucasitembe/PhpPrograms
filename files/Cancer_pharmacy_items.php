<?php
include("../includes/connection.php");
 
    if(isset($_POST['addrow1'])){               
              $cached_data.="<select class='antibiotc' name='Item_IDs[]' id='1' style='width:350px; padding-top:4px; padding-bottom:4px;' id='Item_ID'>";
                   $cached_data.= '<option value="All">~~~~~Select phamethetical item~~~~~</option>'
                         ?><?php   $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' ") or die(mysqli_error($conn));
                         while ($row = mysqli_fetch_array($query_sub_specimen)) {
                          $cached_data.= '<option value="' . $row['Product_Name'] . '">' . $row['Product_Name'] . '</option>';
                         }?><?php
                        $cached_data.= "</select>
                    </td>
            <input type='hidden' id='rowCount' value='1'>";
         echo $cached_data;
    }

    if(isset($_POST['addrow2'])){               
        $cached_data.="<select class='antibiotc' name='Item_IDss[]' id='1' style='width:350px; padding-top:4px; padding-bottom:4px;' id='Item_ID'>";
             $cached_data.= '<option value="All">~~~~~Select phamethetical item~~~~~</option>'
                   ?><?php   $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' ") or die(mysqli_error($conn));
                   while ($row = mysqli_fetch_array($query_sub_specimen)) {
                    $cached_data.= '<option value="' . $row['Product_Name'] . '">' . $row['Product_Name'] . '</option>';
                   }?><?php
                  $cached_data.= "</select>
              </td>
      <input type='hidden' id='rowCount' value='1'>

      ";
   echo $cached_data;
}

if(isset($_POST['addrow3'])){               
    $cached_data.="<select class='antibiotc' name='Item_IDsss[]' id='1' style='width:350px; padding-top:4px; padding-bottom:4px;' id='Item_ID'>";
         $cached_data.= '<option value="All">~~~~~Select phamethetical item~~~~~</option>'
               ?><?php   $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' ") or die(mysqli_error($conn));
               while ($row = mysqli_fetch_array($query_sub_specimen)) {
                $cached_data.= '<option value="' . $row['Product_Name'] . '">' . $row['Product_Name'] . '</option>';
               }?><?php
              $cached_data.= "</select>
          </td>
    <input type='hidden' id='rowCount' value='1'> ";
    echo $cached_data;
}

if(isset($_POST['addrow_one'])){
    $cached_data.="<select class='antibiotc' name='Item_ID_physician[]' id='1' style='width:350px; padding-top:4px; padding-bottom:4px;' id='Item_ID'>";
         $cached_data.= '<option value="All">~~~~~Select phamethetical item~~~~~</option>'
               ?><?php   $query_sub_specimen = mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Pharmacy' ") or die(mysqli_error($conn));
               while ($row = mysqli_fetch_array($query_sub_specimen)) {
                $cached_data.= '<option value="' . $row['Product_Name'] . '">' . $row['Product_Name'] . '</option>';
               }?><?php
              $cached_data.= "</select>
          </td>
    <input type='hidden' id='rowCount' value='1'> ";
    echo $cached_data;
}
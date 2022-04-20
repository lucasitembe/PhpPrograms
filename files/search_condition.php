

<?php
    include("./includes/connection.php");
        if(isset($_POST['search_disease'])){
        $search_disease = $_POST['search_disease'];
        $search_result= mysqli_query($conn,"SELECT disease_code,disease_ID,disease_name FROM tbl_disease d JOIN tbl_disease_subcategory ds ON d.subcategory_ID=ds.subcategory_ID JOIN tbl_disease_category dc ON dc.disease_category_ID=ds.disease_category_ID
        WHERE ds.subcategory_description !='' AND disease_version='icd_10' AND disease_name LIKE '%$search_disease%'") or die(mysqli_error($conn));
        if(mysqli_num_rows($search_result)>0){
        while($row = mysqli_fetch_assoc($search_result)){
          
            $disease_name=$row['disease_name'];
            ?>
            
            <tr>
                <td width="10%">
                    <input style="width:30px;height:30px;pointer:cursor; margin-left:15px;"type="checkbox" name="pre_existing_condition[]"  id="pre_existing_condition" value="<?php echo strtoupper($disease_name); ?>:" >
                </td>
                <td colspan="2">
                    <label style="padding-left:20px;"> <?php echo strtoupper($disease_name); ?></label>
                </td>
            </tr>
       <?php
        }
        } else{
            echo "<tr><td colspan='3'>No result found</td></tr>";
        }
}

// if(isset($_POST['load_condition'])){
//     $search_result= mysqli_query($conn,"SELECT disease_code,disease_ID,disease_name FROM tbl_disease d JOIN tbl_disease_subcategory ds ON d.subcategory_ID=ds.subcategory_ID JOIN tbl_disease_category dc ON dc.disease_category_ID=ds.disease_category_ID
//     WHERE ds.subcategory_description !='' AND disease_version='icd_10'") or die(mysqli_error($conn));
//     if(mysqli_num_rows($search_result)>0){
//         while($row = mysqli_fetch_assoc($search_result)){
          
//             $disease_name=$row['disease_name'];
//             ?>
            
//             <tr>
//                 <td width="10%">
//                     <input style="width:30px;height:30px;pointer:cursor; margin-left:15px;"type="checkbox" name="pre_existing_condition[]"  id="pre_existing_condition" value="<?php echo strtoupper($disease_name); ?>:" >
//                 </td>
//                 <td colspan="2">
//                     <label style="padding-left:20px;"> <?php echo strtoupper($disease_name); ?></label>
//                 </td>
//             </tr>
//        <?php
//         }
//         } else{
//             echo "<tr><td colspan='3'>No result found</td></tr>";
//         }
// }

?>
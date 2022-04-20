
<?php
    include("./includes/connection.php");
    
   // if(isset($_POST['Search_group_name'])){
        $Disease_Group_Name = $_POST['search_group_name'];
        //$sql="SELECT *from tbl_disease_group WHERE disease_group_for='icd_10'";
        $search_result= mysqli_query($conn,"SELECT * FROM tbl_disease_group WHERE disease_group_name LIKE '%$Disease_Group_Name%'") or die(mysqli_error($conn));
        $temp = 0;
        if(mysqli_num_rows($search_result)>0){
        while($row = mysqli_fetch_assoc($search_result)){
            $disease_group_id=$row['disease_group_id'];
            $disease_group_name=$row['disease_group_name'];
            $temp++;
            echo "
                <tr>
                    <td  width=10% >
                    <input type='text' value='".$temp++."' readonly>
                    </td>
                    <td>
                        <input type='text' value='$disease_group_name' readonly>
                    </td>
                
                    <td  width=6%>
                        <select id='remark".$row['disease_group_id']."' onchange='update_remark(".$row['disease_group_id'].")'>
                            <option>".$row['remarks']."</option>
                            <option value='Yes'>Yes</option>
                            <option value='No'>No</option>
                        </select>
                    </td>
                
                </tr>
            ";
       
        }
    } else{
        echo "<tr><td colspan='3'>No result found</td></tr>";
    }
//}
?>
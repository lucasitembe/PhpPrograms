<table class="table">
    <tr>
        <th width="50px">S/No.</th>
        <th>Sub Department Name</th>
        <th width="100px">Action</th>
    </tr>
    <?php 
     $sql_select_subdepartment_result=mysqli_query($conn,"SELECT Sub_Department_Name,Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Status='active' AND privileges='high'") or (mysqli_error($conn));
     if(mysqli_num_rows($sql_select_subdepartment_result)>0){
         $count=1;
         while($sub_d_rows=mysqli_fetch_assoc($sql_select_subdepartment_result)){
             $Sub_Department_Name=$sub_d_rows['Sub_Department_Name'];
             $Sub_Department_ID=$sub_d_rows['Sub_Department_ID'];
             echo "<tr>
                        <td>$count</td>
                        <td>$Sub_Department_Name</td>
                        <td>
                            <input type='button' class='art-button-green' onclick='remove_high_privileges_to_this_sub_d($Sub_Department_ID)' value='REMOVE'/>
                        </td>
                   </tr>";
             $count++;
         }
     }
     ?>
</table>


<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
<a href="new_revenue_collection_summary.php" class="art-button-green">BACK</a>
<fieldset>  
    <legend align=center><b>GENERAL LEDGER PREVIOUS COLLECTION SETUP</b></legend>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">PREVIOUS COLLECTION CONFIGURATION</h4>
                </div>
                <div class="box-body" style="height:400px;overflow-y: scroll">
                    <table class="table table-hover">
                        <th width="50px">S/No.</th>
                        <th>Check In Type</th>
                        <th>Sub Department Name</th>
                        <th>Sub Department Id</th>
                        <th>update Button</th>
                        <?php 
                            //select all checktype
                            $sql_select_all_check_in_type_result=mysqli_query($conn,"SELECT Patient_Payment_Item_List_ID,Check_In_Type,Sub_Department_ID FROM tbl_patient_payment_item_list GROUP BY Check_In_Type ORDER BY Sub_Department_ID DESC") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_all_check_in_type_result)>0){
                                $count_sn=1;
                                while($rows=mysqli_fetch_assoc($sql_select_all_check_in_type_result)){
									
                                    $Check_In_Type=$rows['Check_In_Type'];
                                    $Sub_Department_ID=$rows['Sub_Department_ID'];
                                    $Sub_Department_Name="";
                                    $Patient_Payment_Item_List_ID=$rows['Patient_Payment_Item_List_ID'];
                                    
									$sql_select_select_department_name_result=mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
									if(mysqli_num_rows($sql_select_select_department_name_result)>0){
									$Sub_Department_Name=mysqli_fetch_assoc($sql_select_select_department_name_result)['Sub_Department_Name'];
									}
                                    echo "<tr>
                                            <td>$count_sn.</td>
                                            <td>$Check_In_Type</td>
                                            <td>$Sub_Department_Name</td>
                                            <td><input type='text' value='$Sub_Department_ID' id='sub_id$Patient_Payment_Item_List_ID'/></td>
                                            <td width='50px'>
                                                <input type='button' value='UPDATE' class='art-button-green' onclick='update_item_with_subdepartment($Patient_Payment_Item_List_ID,\"$Check_In_Type\")'/>
                                            </td>
                                        </tr>";
                                    $count_sn++;
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php
include("./includes/footer.php");
?>
<script>
    function update_item_with_subdepartment(Patient_Payment_Item_List_ID,Check_In_Type){
        var Sub_Department_ID=$("#sub_id"+Patient_Payment_Item_List_ID).val();
        $.ajax({
            type:'POST',
            url:'ajax_update_item_with_subdepartment.php',
            data:{Sub_Department_ID:Sub_Department_ID,Check_In_Type:Check_In_Type},
            success:function(data){
                if(data=="success"){
                   alert("success"); 
                }
                if(data=="fail"){
                   alert("failed"); 
                }
                if(data=="zero"){
                   alert("Process fail...sub department id cannot be zero"); 
                }
                console.log(data);
            }
        });        
    }
</script>
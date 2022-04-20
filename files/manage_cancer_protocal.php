<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
session_start();

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$select_protocal = mysqli_query($conn, "SELECT cancer_type_id,Protocal_status, date_time,Cancer_Name, Employee_Name FROM tbl_cancer_type ct, tbl_employee e WHERE added_by=Employee_ID ") or die(mysqli_error($conn));
?>
<a href='cancer_setup.php' class='art-button-green'>
       BACK
    </a>
<fieldset>
    <legend align='center'>MANAGE CANCER PROTOCAL</legend>
    <div class="row">
        <input type="text" class="form-control" id="protocal_name" placeholder="~~~Search Protocal Name ~~~" style="width: 60%; text-align:center;" onkeyup="search_protocal()">
    </div>
    <br>
    <table class="table table-hover table-striped table-responsive" style="height:auto; overflow:scroll;">
        <thead>
            <tr style="background-color: #CCC;">
                <th width='5%'>#</th>
                <th width='40%'>NAME OF PROTOCAL</th>
                <th width='10%'>PROTOCAL STATUS</th>
                <th width='15%'>CREATED BY</th>
                <th width='10%'>CREATED AT</th>
                <th width='20%'>ACTION</th>
            </tr>
        </thead>
        <tbody id="protocalbody">
            <?php 
                $num=0;
                if(mysqli_num_rows($select_protocal)>0){
                    while($row =mysqli_fetch_assoc($select_protocal)){
                        $cancer_type_id = $row['cancer_type_id'];
                        $num++;
                        $Cancer_Name = $row['Cancer_Name'];
                        $Protocal_status = $row['Protocal_status'];
                        echo "<tr>
                            <td>$num</td>
                            <td>".$row['Cancer_Name']."<input type='text' style='display:none;' id='protocal_names$cancer_type_id' value='$Cancer_Name'></td>
                            <td>$Protocal_status</td>
                            <td>".$row['Employee_Name']."<input type='text' style='display:none;' id='Protocal_status$cancer_type_id' value='$Protocal_status'> </td>
                            <td>".$row['date_time']."</td>
                            <td><input type='button' class='art-button-green' onclick='update_chemo_name($cancer_type_id)' value='EDIT'> <a href='update_cancer_protocal.php?cancer_type_id=$cancer_type_id' class='art-button-green'>PREVIEW</a></td>
                        </tr>";
                    }
                }else{
                    echo "<tr><td colspan='5'>No data Found</td></tr>";
                }
            ?>
        </tbody>
    </table>
</fieldset>
<div id="administer_protocalname"></div>
<script>
    function search_protocal(){
        var protocal_name  = $("#protocal_name").val();
        $.ajax({
            type:'POST',
            url:'Ajax_update_cancer_protocal.php',
            data:{protocal_name:protocal_name,Search_value:''},
            success:function(responce){
                $("#protocalbody").html(responce);
            }
        })
    }
    function update_chemo_name(cancer_type_id){
        var protocal_name  = $("#protocal_names"+cancer_type_id).val();
        var Protocal_status = $("#Protocal_status"+cancer_type_id).val();
        $.ajax({
            url:'Ajax_update_cancer_protocal.php', 
            type:'POST',
            data:{protocal_name:protocal_name,Protocal_status:Protocal_status,cancer_type_id:cancer_type_id, administer_protocalname:''},
            success:function(responce){
                $("#administer_protocalname").dialog({
                    title: 'EDIT PROTOCAL NAME: '+protocal_name,
                    width: '50%',
                    height: 250,
                    modal: true,
                });
                $("#administer_protocalname").html(responce); 
                
            }
        });
    }

    function editptotocalname(cancer_type_id){
        var Protocal_status = $("#Protocal_status").val();
        var Edited_name = $("#Edited_name").val();
        if(confirm("Are you sure you want to Update "+Edited_name+"Protocal?")){
            $.ajax({
                type:'POST',
                url:'Ajax_update_cancer_protocal.php',
                data:{protocal_name:Edited_name,cancer_type_id:cancer_type_id,Protocal_status:Protocal_status, updateptotocal:''},
                success:function(responce){
                    if(responce=='Success'){
                        location.reload();
                    }else{
                        alert(responce);
                    }
                }
            })
        }
    }
</script>
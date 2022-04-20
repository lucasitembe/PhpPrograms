<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


?>
<a href="dhis2_add_datasets.php" class="art-button-green">ADD DATASET</a>
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
</a>
<br/><br/>
<br/><br/>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DHIS2 API~</b>Datasets</legend>
    <center>
        <table width = 70%>
            <tr>
                <?php 
                    $sql_select_datasets_results=mysqli_query($conn,"SELECT dhis2_auto_dataset_id,dhis2_dataset_name,dataset_id,ipd_opd FROM tbl_dhis2_datasets") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_datasets_results)>0){
                        $count=1;
                        while($data_set_rows=mysqli_fetch_assoc($sql_select_datasets_results)){
                            $dhis2_auto_dataset_id=$data_set_rows['dhis2_auto_dataset_id'];
                            $dhis2_dataset_name=$data_set_rows['dhis2_dataset_name'];
                            $dataset_id=$data_set_rows['dataset_id'];
                            $ipd_opd=$data_set_rows['ipd_opd'];
                            if($ipd_opd == "IPD"){
                               ?>
                               <td style='text-align: center; height: 40px; width: 50%;'>
<!--                                   <a href="dhis2_hmis_dataelements.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>"><button style='width: 100%; height: 100%'><?= $dhis2_dataset_name ?></button></a>-->
                                   <a href="dhis2_hmis_dataelements_new_ipd.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>"><button style='width: 100%; height: 100%'><?= $dhis2_dataset_name ?></button></a>
                               </td>   
                            <?php 
                            }else{
                            ?>
                               <td style='text-align: center; height: 40px; width: 50%;'>
<!--                                   <a href="dhis2_hmis_dataelements.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>"><button style='width: 100%; height: 100%'><?= $dhis2_dataset_name ?></button></a>-->
                                   <a href="dhis2_hmis_dataelements_new.php?dhis2_auto_dataset_id=<?= $dhis2_auto_dataset_id ?>&&dhis2_dataset_name=<?= $dhis2_dataset_name ?>&&dataset_id=<?= $dataset_id ?>"><button style='width: 100%; height: 100%'><?= $dhis2_dataset_name ?></button></a>
                               </td>   
                            <?php
                            }
                            if($count%2==0){
                                echo "</tr><tr>";
                            }
                            $count++;
                        }
                    }
                ?>
            </tr>
        </table>
        <br/><br/>
    </center>
</fieldset>

<br/>
<br/>
<br/>
<?php
include("./includes/footer.php");
?>

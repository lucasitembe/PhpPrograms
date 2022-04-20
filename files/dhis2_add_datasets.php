<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if(isset($_POST['save_btn'])){
    $dhis2_dataset_name=$_POST['dhis2_dataset_name'];
    $dataset_id=$_POST['dataset_id'];
    $reporttype=$_POST['reporttype'];
    $sql_insert_data_set_result=mysqli_query($conn,"INSERT INTO tbl_dhis2_datasets(dhis2_dataset_name,dataset_id,ipd_opd) VALUES('$dhis2_dataset_name','$dataset_id','$reporttype')") or die(mysqli_error($conn));

    if($sql_insert_data_set_result){
        ?>
            <script>alert("Saved Successfully");</script>
        <?php
    }else{
       ?>
            <script>alert("Process Fail");</script>
       <?php  
    }
}

?>
<a href='dhis2_api.php' class='art-button-green'>
        BACK
</a>
<br/><br/>
<style>
    table,tr,td{
        border:none!important;
    }
</style>
<fieldset>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DATASETS</b></legend>
    <div  class="col-md-6"style='margin-top:15px;height: 370px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF'>
        <form action="" method="POST">
            <table class="table">
                <tr>
                    <caption><b>Add Dataset</b></caption>
                </tr>
                <tr>
                    <td>DataSet Name</td>
                    <td><input type="text" placeholder="Enter DataSet Name" name="dhis2_dataset_name"required="" class="form-control"/></td>
                </tr>
                <tr>
                    <td>DataSet Id</td>
                    <td><input type="text" placeholder="Enter Dataset Id" name="dataset_id" required="" class="form-control"/></td>
                </tr>
                <tr>
                    <td>Report Type</td>
                    <td>
                        <select style="width:100%;" name="reporttype">
                            <option>OPD</option>
                            <option>IPD</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="SAVE"  name="save_btn"class="art-button-green pull-right"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <div  class="col-md-6"style='margin-top:15px;height: 370px;overflow-y: scroll;overflow-x: hidden;background: #FFFFFF'>
        <table class="table">
            <tr><th>S/No.</th>
                <th>DATASET NAME</th>
                <th>DATASET ID</th>
                <th>ACTION</th>
            </tr>
            <?php 
                $sql_select_datasets_results=mysqli_query($conn,"SELECT dhis2_auto_dataset_id,dhis2_dataset_name,dataset_id FROM tbl_dhis2_datasets") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_datasets_results)>0){
                    $count=1;
                    while($data_set_rows=mysqli_fetch_assoc($sql_select_datasets_results)){
                        $dhis2_auto_dataset_id=$data_set_rows['dhis2_auto_dataset_id'];
                        $dhis2_dataset_name=$data_set_rows['dhis2_dataset_name'];
                        $dataset_id=$data_set_rows['dataset_id'];
                        
                        echo "<tr>
                                <td>$count.</td>
                                <td>$dhis2_dataset_name</td>
                                <td>$dataset_id</td>
                                <td>
                                    <input type='button' value='EDIT' class='art-button-green'/>
                                </td>
                             </tr>";
                        $count++;
                    }
                }
            ?>
        </table>
    </div>
</fieldset>
<?php
include("./includes/footer.php");
?>


<?php 
    include("./includes/connection.php");
    session_start();
    if(isset($_POST['addpatholoy'])){?>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr style="margin-top:30px;"> 
                <td width='25%'>
                    <label >Define Pathorogy:</label>
                </td>
                <td>
                    <input type="text" id='Defined_pathorogy' style='width:100%;' class="form-control">
                </td>
                <td width='15%'>
                    <span><a class='art-button-green' href='#' type='button' style='width:100%;' onclick='save_pathorogy()'>ADD </a></span>  
                </td>
            </tr>
        </table>
    <?php }

    if(isset($_POST['savepatholoy'])){
        $Defined_pathorogy = $_POST['Defined_pathorogy'];

        $Employee_ID =$_SESSION['userinfo']['Employee_ID'];
        $savesql = mysqli_query($conn, "INSERT INTO tbl_cancer_pathorogy(pathorogy_name, added_by) VALUES('$Defined_pathorogy', '$Employee_ID')") or die(mysqli_error($conn));
        if($savesql){
            echo "Added successful";
        }else{
            echo "failed try again";
        }
    }

    if(isset($_POST['addstagedialog'])){?>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr style="margin-top:30px;"> 
                <td width='25%'>
                    <label >Define stage:</label>
                </td>
                <td>
                    <input type="text" id='Defined_stage' style='width:100%;' class="form-control">
                </td>
                <td width='15%'>
                    <span><a class='art-button-green' href='#' type='button' style='width:100%;' onclick='save_stage()'>ADD </a></span>  
                </td>
            </tr>
        </table>
    <?php }

    if(isset($_POST['savestage'])){
        $Defined_stage = $_POST['Defined_stage'];
        $Pathorogy_ID = $_POST['Pathorogy_ID'];
        $Employee_ID =$_SESSION['userinfo']['Employee_ID'];
        $savesql = mysqli_query($conn, "INSERT INTO tbl_cancer_stages(stage_name,Pathorogy_ID, added_by) VALUES('$Defined_stage','$Pathorogy_ID', '$Employee_ID')") or die(mysqli_error($conn));
        if($savesql){
            echo "Added successful";
        }else{
            echo "failed try again";
        }
    }

    if(isset($_POST['addprotocal'])){ $Stage_ID= $_POST['Stage_ID']; ?>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr style="margin-top:30px;"> 
                <td width='25%'>
                    <label >Define Protocal:</label>
                </td>
                <td>
                    <input type="text"  id="type_of_cancer" autocomplete="off" style='width:100%' class='form-control' placeholder="Define Here"/>                </td>
                <td width='15%'>
                    <span><input type="button" name="SAVE" value="SAVE" class="art-button-green" onclick="add_type_of_cancer(<?= $Stage_ID ?>)" ></span>  
                </td>
            </tr>
        </table>
    <?php }

    
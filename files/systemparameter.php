<?php
include("./includes/connection.php");
include("./includes/header.php");
include("./includes/cleaninput.php");
include_once("./functions/items.php");
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$employeeid = $_SESSION['userinfo']['Employee_ID'];

$Id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Id FROM `audit` WHERE Employee_Id = '$employeeid' ORDER BY Id DESC LIMIT 1"))['Id'];
$Activities = " Accessed ~ <b> System Parameters </b>";
    if($Id > 0){
        // die("INSERT INTO audit_logs(Activities_Log_Id, Action, Date_Time) VALUES('$Id', '$Activities', NOW())");
        $Inssert_Audit = mysqli_query($conn, "INSERT INTO audit_logs(Activities_Log_Id, `Action`, Date_Time) VALUES('$Id', '$Activities', NOW())") or die(mysqli_error($conn));
    }
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<a href='systemconfiguration.php?SystemConfiguration=SystemConfigurationThisPage' class='art-button-green'>BACK</a>

<br/><br/>
<?php
$result = mysqli_query($conn,"SELECT configname,configvalue,configdesc FROM tbl_config") or die(mysqli_error($conn));
?>
<form action="#" method="post" onsubmit="return confirm('Are you sure you want to save changes?')"> 
    <fieldset style='overflow-y: scroll; height: 360px; background-color: white;' id='Currency_Area'>
        <legend align="right"><b>SYSTEM PARAMETER CONFIGURATION</b></legend>
        <center>   
            <table width="60%">
                <table class="table table-bordered  table-striped table-hover"> 
                    <thead> 
                        <tr> 
                            <th>#</th> 
                            <th>Config name</th> 
                            <th>Config value</th> 
                        </tr> </thead> 
                    <tbody> 
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";

                            $cnfname = '';
                            $pieces = preg_split('/(?=[A-Z])/', $row['configname']);

                            foreach ($pieces as $piec) {
                                $cnfname .= ' ' . $piec;
                            }

                            if($row['configname']=="showManulaOrOffline"){
                                echo "<td>" . $cnfname . " " . $row['configdesc'] . "</td>";
                                echo "<td>"; ?>

                                <select name='<?= $row['configname'] ?>' style="width:180px;padding:3px;">
                                    <option value='offline'configname <?php if(strtolower($row['configvalue'])=='offline'){echo 'selected';} ?> >offline</option>
                                    <option value='manual' <?php if(strtolower($row['configvalue'])=='manual'){echo 'selected';} ?> >manual</option>
                                </select>
                                    <!--<input type='text' name='" . $row['configname'] . "' value='" . $row['configvalue'] . "' title='" . $row['configdesc'] . "'>-->

                            <?php    echo "</td>";
                                echo "</tr>";
                            }
                            else if($row['configname']=="ShowCreateEpaymentBillOrMakePaymentButton"){
                              ////////////////////////////////////////////////////////////////////////////////
                                
                                 echo "<td>" . $cnfname . " " . $row['configdesc'] . "</td>";
                                echo "<td>"; ?>

                                <select name='<?= $row['configname'] ?>' style="width:180px;padding:3px;">
                                    <option value='makepayment' <?php if(strtolower($row['configvalue'])=='makepayment'){echo 'selected';} ?> >Makepayment</option>
                                    <option value='epayment' <?php if(strtolower($row['configvalue'])=='epayment'){echo 'selected';} ?> >Epayment</option>
                                </select>
                                    <!--<input type='text' name='" . $row['configname'] . "' value='" . $row['configvalue'] . "' title='" . $row['configdesc'] . "'>-->

                            <?php    echo "</td>";
                                echo "</tr>";
                                
                                ////////////////////////////////////////////////////////
                            }
                            else if($row['configname']=="Icd_10OrIcd_9"){
                              ////////////////////////////////////////////////////////////////////////////////
                                
                                 echo "<td>" . $cnfname . " " . $row['configdesc'] . "</td>";
                                echo "<td>"; ?>

                                <select name='<?= $row['configname'] ?>' style="width:180px;padding:3px;">
                                    <option value='icd_9' <?php if(strtolower($row['configvalue'])=='icd_9'){echo 'selected';} ?> >ICD 9</option>
                                    <option value='icd_10' <?php if(strtolower($row['configvalue'])=='icd_10'){echo 'selected';} ?> >ICD 10</option>
                                </select>
                                    <!--<input type='text' name='" . $row['configname'] . "' value='" . $row['configvalue'] . "' title='" . $row['configdesc'] . "'>-->

                            <?php    echo "</td>";
                                echo "</tr>";
                                
                                ////////////////////////////////////////////////////////
                            }
                            else if($row['configname']=="IntegratedToAccounting"){
                                echo "<td>" . $cnfname . " " . $row['configdesc'] . "</td>";
                                echo "<td>"; ?>

                                <select name='<?= $row['configname'] ?>' style="width:180px;padding:3px;">
                                   <!-- <option value='Yes' <?php if(strtolower($row['configvalue'])=='yes'){echo 'selected';} ?> >Yes</option>-->
                                    <option value='No' <?php if(strtolower($row['configvalue'])=='no'){echo 'selected';} ?> >No</option>
                                </select>
                                    <!--<input type='text' name='" . $row['configname'] . "' value='" . $row['configvalue'] . "' title='" . $row['configdesc'] . "'>-->

                            <?php    echo "</td>";
                                echo "</tr>";
                            } 
                            else if($row['configname']=="NhifApiConfiguration"){
                                echo "<td>" . $cnfname . " " . $row['configdesc'] . "</td>";
                                echo "<td>"; ?>

                                <select name='<?= $row['configname'] ?>' style="width:180px;padding:3px;">
                                    <option value='singleserver' <?php if(strtolower($row['configvalue'])=='singleserver'){echo 'selected';} ?> >Single Server</option>
                                    <option value='multipleserver' <?php if(strtolower($row['configvalue'])=='multipleserver'){echo 'selected';} ?> >Multiple Server</option>
                                </select>
                                    <!--<input type='text' name='" . $row['configname'] . "' value='" . $row['configvalue'] . "' title='" . $row['configdesc'] . "'>-->

                            <?php    echo "</td>";
                                echo "</tr>";
                            } else {
                                echo "<td>" . $cnfname . " " . $row['configdesc'] . "</td>";
                                echo "<td><input type='text' name='" . $row['configname'] . "' value='" . $row['configvalue'] . "' title='" . $row['configdesc'] . "'></td>";
                                echo "</tr>";
                            }
            
                        }
                        ?>

                    </tbody> 
                </table> 

                <tr><td colspan="2" style="text-align: center;"><b><span style="color: #037CB0;" id="Error_Area" >&nbsp;</span></b></td></tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </table> 
        </center>
    </fieldset>
    <fieldset>
        <table width="100%">
            <tr>
                <td colspan="2" style="text-align: right;">
                    <input type="reset" name="Calcel_Button" id="Calcel_Button" value="CANCEL" class="art-button-green">
                    <input type="submit" name="saveparameters" id="Add_Button" value="SAVE PARAMETER" class="art-button-green" >
                </td>
            </tr>
        </table>
    </fieldset>
</form> 

<?php
if (isset($_POST['saveparameters'])) {
    $_POST = sanitize_input($_POST);

    $has_error = false;
    Start_Transaction();

    foreach ($_POST as $key => $value) {
        if ($key != 'submit') {
            $sql = "UPDATE tbl_config SET configvalue='$value' WHERE configname='$key'";
            $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

            if (!$result) {
                $has_error = true;
            }
        }
    }

    $Activities = " Modified ~ <b> System Parameters </b>";
    if($Id > 0){
        // die("INSERT INTO audit_logs(Activities_Log_Id, Action, Date_Time) VALUES('$Id', '$Activities', NOW())");
        $Inssert_Audit = mysqli_query($conn, "INSERT INTO audit_logs(Activities_Log_Id, `Action`, Date_Time) VALUES('$Id', '$Activities', NOW())") or die(mysqli_error($conn));
    }

    if (!$has_error) {
        Commit_Transaction();
        echo "<script>
                alert('Process Successful');
                document.location = 'systemparameter.php';
             </script>";
    } else {
        Rollback_Transaction();
        echo "<script>
                    alert('Process Fail! Please Try Again');
                    document.location = 'systemparameter.php';
              </script>";
    }
}
?>


<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>
<script src="js/numeral/min/numeral.min.js"></script>

<?php
include("./includes/footer.php");
?>
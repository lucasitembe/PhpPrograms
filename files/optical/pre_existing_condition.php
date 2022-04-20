<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
?>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['General_Ledger'] == 'yes') {
        ?>
<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
<link rel="stylesheet" href="table.css" media="screen">
<br/>
<fieldset style='overflow-y:scroll; height:440px' >
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>PRE EXISTING CONDITION </b><b style="color:#e0d8d8;"></b> <b style="color: #e0d8d8;"></b></legend>

    <?php

    $temp = 1;
    echo '<center><table width =70% border=0>';
    echo '<tr id="thead">
            <td style="width:10%;"><b>SN</b></td>
            <td><b>PRE EXISTING CONDITION</b></td>
            <td width="10%"><b>STATUS</b></td>
        </tr>';
    $select_existing_condition = mysqli_query($conn,"SELECT * FROM pre_existing_condition") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_existing_condition)){
        echo "<tr>
                <td id='thead'>$temp</td>
                <td>$row[1]</td> 
                <td>$row[2]</td> 
            </tr>"; 
        $temp++;
    } 
    
?>
    <tr>
        <td colspan="3" style='text-align: right;'><input type="button" value="Add" class="art-button-green" onclick="open_pre_existing_condition_dialog();" ></td>
    </tr>
    <div id="show"></div>
</table></center>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<script src="media/js/sum().js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#doctorsperformancetbl').dataTable({
        "bJQueryUI": true,
    });
</script>
<script>
    function open_pre_existing_condition_dialog(){
        // var disease_code=$("#disease_code").val();
        // var disease_name=$("#disease_name").val();
        $.ajax({
            type:'GET',
            url:'pre_existing_dialog.php',
            data : {},
                success : function(data){
                    $('#show').dialog({
                        autoOpen:true,
                        width:'90%',
                        title:'NEW PRE EXISTING CONDITION',
                        modal:true
                       
                    });  
                    $('#show').html(data);
                    $('#show').dialog('data');
                }
            }) 
    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
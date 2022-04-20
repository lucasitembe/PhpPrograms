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
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;"><b>ALLERGIES </b><b style="color:#e0d8d8;"></b> <b style="color: #e0d8d8;"></b></legend>
    <div style="overflow-y:auto:height:300px">
    <?php
    $temp = 1;
    echo '<center><table width =70% border=0>';
    echo '<tr id="thead">
            <td style="width:10%;"><b>SN</b></td>
            <td><b>ALLERGIES</b></td>
        </tr>';
        $select_allergies = mysqli_query($conn,"SELECT * FROM allergies order by allergies_ID DESC") or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_allergies)){
            echo "<tr>
                    <td id='thead'>$temp</td>
                    <td onclick='edit_allergy($row[0]);'><label>$row[1]<label></td> 
                </tr>"; 
            $temp++;
        } 
    
?>
    </div>
    <tr>
    </tr>
    <div id="show"></div>
</table></center>

</fieldset>
<div style='text-align:right;margin-right:290px;'>
    <input type="button" value="Add Allergy" class="art-button-green" onclick="allergies();">  
</div>
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
    function allergies(){
        // var disease_code=$("#disease_code").val();
        // var disease_name=$("#disease_name").val();
        $.ajax({
            type:'GET',
            url:'allergies_dialog.php',
            data : {},
                success : function(data){
                    $('#show').dialog({
                        autoOpen:true,
                        width:'50%',
                        position:["center",230],
                        title:'NEW ALLERGIES',
                        modal:true
                       
                    });  
                    $('#show').html(data);
                    $('#show').dialog('data');
                }
            }) 
    }
</script>
<script>
    function edit_allergy(allergy_id){
        //alert(allergy_id);
        $.ajax({
                type:'post',
                url: 'edit_allergies.php',
                data : {
                     allergy_id:allergy_id
               },
               success : function(data){
                    $('#show').dialog({
                        autoOpen:true,
                        width:'50%',
                        position: ['center',200],
                        title:'EDIT ALLERGY',
                        modal:true
                    });  
                    $('#show').html(data);
               }
           });

    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
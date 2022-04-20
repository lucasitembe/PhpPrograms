<?php
    error_reporting(!E_NOTICE);
    include("./includes/header.php");
    include("./includes/connection.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if (isset($_SESSION['userinfo'])) {
//        if (isset($_SESSION['userinfo']['Admission_Works'])) {
//            if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//                header("Location: ./index.php?InvalidPrivilege=yes");
//            }
//        } else {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

// }
if (isset($_GET['admission_id'])) {
    $admision_id = $_GET['admission_id'];
 }
  if (isset($_GET['patient_id'])) {
   $Registration_ID = $_GET['patient_id'];
  }
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }

    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
    }else{
        $consultation_ID = 0;
    }

    echo "<a href='martenal_condition.php?patient_id=".$Registration_ID. "&admission_id=".$admision_id."&consultation_ID=".$consultation_ID."&NurseCommunication=NurseCommunicationThisPage' class='art-button-green'>BACK $admision_id</a>";
?>
<div id="result1"></div>
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
<style>
    td.pr{
        text-align: right
    }

</style>
<style>
        input[type="checkbox"]{
            width: 30px; 
            height: 30px;
            cursor: pointer;
    }
    </style>
<br/><br/>
<fieldset>
    <legend style="text-align:right"><b>PATOGRAPH RECORD</b></legend>
        <?php
             $select_date=mysqli_query($conn,"SELECT DISTINCT date(date_time), admission_id  FROM tbl_fetal_heart_rate_cache where patient_id='$Registration_ID' order by fetal_heart_rate_cache_id DESC");
                 while($takedate=mysqli_fetch_array($select_date)){
                     $saved_time=$takedate[0];
                     $admission_id=$takedate[1];
                     ?>
                     <center>
                        <input style="width:80%;"  type="button" class="art-button-green form-control" onclick="Previous_Records(<?= $saved_time;?>,<?= $admission_id;?>,<?= $Registration_ID;?>)" value="<?= $saved_time;?><?= $admission_id;?>"></br>
                     </center>
                     <?php
                 }
            ?>
    </fieldset>
</form>


</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
    function Previous_Records(saved_time,admission_id,Registration_ID){
        // var Registration_ID = $(".Registration_ID").val();
         //alert(Registration_ID);
        $.ajax({
                type:'post',
                url: 'maternal_condition_previous_result.php',
                data : {
                     Registration_ID:Registration_ID,
                     admission_id:admission_id,
                     saved_time:saved_time
               },
               success : function(data){
                $('#result1').html(data);
                    $('#result1').dialog({
                        autoOpen:true,
                        width:'60%',
                        // position: ['center',200],
                        title:'PATIENT RECORD:',
                        modal:true
                    });  
                    
               }
           });
     }
<script>

<?php
include("./includes/footer.php");
?>
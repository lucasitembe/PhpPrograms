<style>
   table {
      background-color: #fff;
   }

   td {
      padding: 8px;
   }

   .filter {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
      gap: 1em;
      margin-bottom: 1em
   }

   #table{
      /* overflow: scroll; */
      /* height: 60vh; */
      border-top: 2px solid rgba(34, 138, 170);
   }
</style>
<?php
include("./includes/connection.php");
    if (isset($_GET['Admision_id'])) {
        $Admision_id = $_GET['Admision_id'];
      }
      
      if (isset($_GET['patient_id'])) {
       $Registration_ID = $_GET['patient_id'];
      }
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
      }else{
        $E_Name = '';
      }
    $patient_name = $_GET['patient_name'];
    $patient_age = $_GET['patient_age'];
    $patient_gender = $_GET['patient_gender'];
?>
    <table width="100%">
    <tr>
        <td><b>Patient Reg.No </b></td>
        <td><?= $Registration_ID ?></td>
        <td><b>Patient Name</b></td>
        <td><?= $patient_name ?></td>
    </tr>
    <tr>
        <td><b>Patient Age</b></td>
        <td><?= $patient_age." " ?>Years</td>
        <td><b>Patient Gender</b></td>
        <td><?= $patient_gender ?></td>
    </tr>
</table>
<fieldset id="check" style="height: 640px;overflow-y: auto;">
<?php
    $select_admission=mysqli_query($conn,"SELECT DISTINCT admission_id  FROM tbl_fetal_heart_rate_cache where patient_id='$Registration_ID' order by fetal_heart_rate_cache_id DESC");
    if(mysqli_num_rows($select_admission)>0){
        ?>
        <table class="table table-hover table-striped">
                <thead style='background-color:#bdb5ac;'>
                    <tr>
                        <td style='color:white;text-align:center;'>Saved date</td>
                        <td colspan="2" style='color:white;text-align:center;'>Action</td>
                    </tr>
                </thead>

                <tbody>
        <?php
        while($takedate=mysqli_fetch_array($select_admission)){
            // $saved_time=$takedate[0];
            $admission_id=$takedate[0];
            $select_date=mysqli_query($conn,"SELECT date(date_time) FROM tbl_fetal_heart_rate_cache where admission_id='$admission_id' order by fetal_heart_rate_cache_id ASC LIMIT 1");
            $admission=mysqli_fetch_array($select_date)[0];

            ?>
            
            <!-- <center><h3 style='color:white;width:60%;margin-top:20px;' class="art-button-green form-control" onclick="open_data(<?php echo $admission_id;?> )"><?php echo $admission;?> </h3></br></center> -->
            <tr>
                <td style='text-align:center;'><?php echo $admission;?></td>
                <td onclick="open_data(<?php echo $admission_id;?> )" class='art-button-green' style='color:white;text-align:center;'>PREVIEW DATA</td>
                <!-- <td onclick="open_data(< ?php echo $admission_id;?> )" class='art-button-green' style='color:white;text-align:center;'>PREVIEW PATOGRAPH</td> -->
                <td class='art-button-green' style='color:white;text-align:center;'><a href="martenal_condition_patograph_record.php?patient_id=<?php echo $Registration_ID;?>&admision_id=<?php echo $admission_id;?>" target="_blank">PREVIEW PATOGRAPH</a></td>
            </tr>
            <?php
        }
    }else{
            echo "<center><h3>No Result Found</h3></center>";
    }
?>
 </tbody>
    </table>
    </fieldset>
<input type="hidden" value="<?php echo $Registration_ID;?>" id="Registration_ID">
<div id="result"></div>
<script>
   function open_data(admission_id){

    var Registration_ID=$('#Registration_ID').val();
        // alert(Registration_ID);
        // alert(admission_id);
        $.ajax({
                type:'GET',
                url: 'previous_matenal.php',
                data : {
                    Registration_ID:Registration_ID,
                    admission_id:admission_id,
                  
               },
               success : function(data){
                $('#result').html(data);
                    $('#result').dialog({
                        autoOpen:true,
                        width:'90%',
                        position: ['center',0],
                        title:'PATOGRAPH RECORD',
                        modal:true
                    });  
                    $('#result').html(data);
                    
               }
           });
    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
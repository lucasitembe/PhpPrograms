<?php 
     include("./includes/header.php");
     include("./includes/connection.php");
?>
<style>
    .rows_list{
    color: #328CAF!important;
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<?php 
    if(isset($_GET['Registration_ID'])){
        $Patient_id = $_GET['Registration_ID'];
        $Patient_name = $_GET['Patient_name'];
    }
    ?>
        <a href='./choose_occupation_therapy_result.php?Registration_ID=<?php echo $Patient_id ?>&&Patient_name=<?php echo $Patient_name ?>' class='art-button-green'>
            BACK
        </a>
        <br>
        <br>
    <?php
    $select_all_assement = mysqli_query($conn,"SELECT pediatric_id,therapist,created_at,main_concern,birth_history FROM tbl_pediatric_assesment_informations WHERE patient_id = $Patient_id ORDER BY pediatric_id DESC") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_all_assement)){
        echo '
        <br><fieldset style="margin-top:20px">
        <center><legend>--Occupational Therapy Informations For '.$Patient_name.'--</legend></center>
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>SN</th>
                <th>Assesed At</th>
                <th>Current Presentation</th>
                <th>Communication</th>
                <th>Therapist</th>
            </tr>
        </thead>
        <tbody>
        ';
        $count =1;
        while($assement_info_rows = mysqli_fetch_assoc($select_all_assement)){
            $created_at = $assement_info_rows['created_at'];
            $pediatric_id = $assement_info_rows['pediatric_id'];
            $therapist = $assement_info_rows['therapist'];
            $main_concern = $assement_info_rows['main_concern'];
            $birth_history = $assement_info_rows['birth_history'];
            echo '
            <tr class="rows_list" onclick="open_occupational_therapy_info('.$Patient_id.',\''.$pediatric_id.'\')">
            <td>'.$count.'</td>
            <td>'.$created_at.'</td>
            <td>'.$main_concern.'</td>
            <td>'.$communication.'</td>
            <td>'.$therapist.'</td>
            </tr>
            
            ';
        $count++;
        }
        echo '
        
        </tbody>
        </table>

        ';
    }
    else{
        echo '
        <table class="table table-bordered">
        <tr><th>No data Available..</th></tr>
        </table>
        </fieldset>
        '; 
    }
  
?>
<div id="occupational_therapy_diolog"></div>
<script>
    function open_occupational_therapy_info(patient_id,pediatric_id){
        $.ajax({
            type:'POST',
            url:'preview_pediatric_data.php',
            data:{patient_id:patient_id,pediatric_id:pediatric_id},
            success:function(data){
                $("#occupational_therapy_diolog").dialog({
                        title: 'OCCUPATIONAL THERAPY',
                        width: '90%',
                        height: '600',
                        modal: true,
                        resizable: false,
                    });
                    $("#occupational_therapy_diolog").html(data);
        }
        });
    }
</script>
<?php 
    include("./includes/footer.php");
?>

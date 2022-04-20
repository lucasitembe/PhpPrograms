<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    echo "<link rel='stylesheet' href='fixHeader.css'>";
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Sub_Department_Name = $_SESSION['Admission'];

    $qr = "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
                $ward_results = mysqli_query($conn,$qr);
                if(mysqli_num_rows($ward_results)>0){
                    while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                        $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                        $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                        
                        $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";

                    }
                    echo " <a href='admissionreports.php?section=&Admissionreports=AllReports&ActiveReports' class='art-button-green'>
                        BACK
                    </a>";
                } 
    ?>
    
<fieldset>
        <legend align=center><b>Early Warning Score</b></legend>
        <div class="col-md-3"></div>
        <div class="col-md-6 center">
            <table width='' class="table" >
                <tr>
                    <td width="50%">
                        <?php 
                           $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                            $select_wards = mysqli_query($conn, "SELECT * FROM tbl_hospital_ward WHERE ward_type='ordinary_ward' AND Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID')) AND ward_status='active'" ) or die(mysqli_error($conn));
                            $count_wards = mysqli_num_rows($select_wards);
                            echo "<select style='width:100%' id='Hospital_Ward_ID' onchange='filter_patient()'>";
                            echo $Display;
                            if($count_wards > 0){
                                    while($ward = mysqli_fetch_assoc($select_wards)){

                                            $WName = $ward['Hospital_Ward_Name'];
                                            $WID = $ward['Hospital_Ward_ID'];
                                           
                                            echo "<option value='".$WID."'>".$WName."</option>";
                                    }
                            }
                            echo "</select>";
                        
                        ?>
                    </td>
                    <td>
                        <input type="text" placeholder="Search . . ." id="search_table" style="text-align: center" class="form-control">
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12" style="height: 450px;overflow-y: scroll;">
            <table class="table fixTableHead" style="background: #FFFFFF">
                <caption><b>EARLY WANING SCORE</b></caption>
                <thead>
                    <tr style="background: #DEDEDE">
                        <td width='50px'><b>S/No.</b></td>
                        <td><b>PATIENT NAME</b></td>
                        <td><b>PATIENT NUMBER</b></td>
                        <td><b>ROOM&BED</b></td>
                        <td width='100px' style="text-align: center"><b>SCORE</b></td>
                        <td width='300px'><b>REMARKS</b></td>
                    </tr>
                </thead>
                <tbody id="early_warning_score_tbody">
                    
                </tbody>
            </table>
        </div>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<script>
    $(document).ready(function(){
        $('select').select2();
    });
    function filter_patient(){
       var Hospital_Ward_ID=$("#Hospital_Ward_ID").val();
      
       $.ajax({
           type:'POST',
           url:'ajax_filter_ealy_warning_score.php',
           data:{Hospital_Ward_ID:Hospital_Ward_ID},
           success:function(data){
               $("#early_warning_score_tbody").html(data);
//               console.log(data);
           }
       });
    }
</script>
 <script>
$(document).ready(function(){
  $("#search_table").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#early_warning_score_tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script> 
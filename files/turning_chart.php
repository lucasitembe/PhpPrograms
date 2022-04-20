<?php
    $indexPage = false;
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){ 
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
   $can_broadcast=$_SESSION['userinfo']['can_broadcast'];

   if (isset($_GET['discharged'])) {
       $nav = '&discharged=discharged';
   }

   if (isset($_GET['consultation_ID'])) {
       $consultation_id = $_GET['consultation_ID'];
   }
   if (isset($_GET['Admision_ID'])) {
       $Admision_ID = $_GET['Admision_ID'];
   }
   if (isset($_GET['Registration_ID'])) {
       $patient_id = $_GET['Registration_ID'];
   }
   if (isset($_GET['this_page_from'])) {
       $this_page_from = $_GET['this_page_from'];
   }
?>

<a href="nursecommunicationpage.php?consultation_ID=<?= $consultation_id; ?>&Registration_ID=<?= $patient_id; ?>&Admision_ID=<?= $Admision_ID ?>" class="art-button-green">BACK</a>
<br/> 
            <fieldset>
                    <legend align="center" ><b>TURNING CHART</b></legend> 
                    <?php 
                        $patient_id = $_GET['Registration_ID'];
                        $consultant_id = $_GET['consultation_ID'];
                        $get_pateint_details = mysqli_query($conn,"SELECT Registration_ID,Patient_Name,Gender,Date_Of_Birth FROM tbl_patient_registration WHERE Registration_ID = $patient_id") or die(mysqli_error($conn));
                        while($patient_detail_rows = mysqli_fetch_assoc($get_pateint_details)){
                          $Registration_ID = $patient_detail_rows['Registration_ID'];
                          $Patient_Name = $patient_detail_rows['Patient_Name'];
                          $Gender = $patient_detail_rows['Gender'];
                          $Date_Of_Birth = $patient_detail_rows['Date_Of_Birth'];
                        }
                        echo '
                        <table class="table table-bordered" style="background-color:#fff">
                        <tr>
                            <th>Hosp No:</th>
                            <td>'.$Registration_ID.'</td>
                            <th>Name:</th>
                            <td>'.$Patient_Name.'</td>
                            <th>Sex:</th>
                            <td>'.$Gender.'</td>
                            <th>Date of Birth:</th>
                            <td>'.$Date_Of_Birth.'</td>
                        </tr>';
                        $select_bed_result = mysqli_query($conn, "SELECT Bed_Name, ad.ward_room_id, ad.Hospital_Ward_ID, hw.Hospital_Ward_Name, wr.room_name,  ad.Registration_ID FROM 
                        tbl_admission ad ,tbl_patient_registration pr, tbl_hospital_ward hw, tbl_ward_rooms wr WHERE ad.Registration_ID=$patient_id AND ad.ward_room_id = wr.ward_room_id AND 
                        ad.Hospital_Ward_ID =hw.Hospital_Ward_ID AND ad.Admision_ID='$Admision_ID'  ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
                        while($bed_row = mysqli_fetch_assoc($select_bed_result)){
                            $ward = $bed_row['Hospital_Ward_Name'];
                            $bed = $bed_row['Bed_Name'];
                            $room = $bed_row['room_name'];
                        }
                       echo' <tr>
                            <th>Ward:</th>
                            <td>'.$ward.'</td>
                            <th>ROOM:</th>
                            <td>'.$room.'</td>
                            <th>Bed No:</th>
                            <td>'.$bed.'</td>
                        </tr>
                    </table><br>
                        ';
                    ?>
                  
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:20%">Date</th>
                            <th style="width:20%">Time</th>
                            <th style="width:20%">Key</th>
                        <tr style="text-align: center;">
                            <td style="text-align: center;"><input style="text-align: center;vertical-align: middle;" class="form-control date" readonly name="date" id="date" placeholder="~~~ Select Date ~~~"></td>
                            <td style="text-align: center;vertical-align: middle;">
                                <select class="form-control" name="time" id="time">
                                    <option value="">~~~ Select Time ~~~</option>
                                    <option value="8am">8am</option>
                                    <option value="10am">10am</option>
                                    <option value="12md">12md</option>
                                    <option value="2pm">2pm</option>
                                    <option value="4pm">4pm</option>
                                    <option value="6pm">6pm</option>
                                    <option value="8pm">8pm</option>
                                    <option value="10pm">10pm</option>
                                    <option value="12mn">12mn</option>
                                    <option value="2am">2am</option>
                                    <option value="4am">4am</option>
                                    <option value="6am">6am</option>
                                </select>
                            </td>
                            <td style="text-align: center;vertical-align: middle;">
                                <select class="form-control" name="key" id="key">
                                    <option value="">~~~ Select Key ~~~</option>
                                    <option value="Bk">Back</option>
                                    <option value="LT">Left Lateral</option>
                                    <option value="Rt">Right Lateral</option>
                                    <option value="RSL">Recumbeting Supping Lateral</option>
                                    <option value="SU">Sitting-Up</option>
                                    <option value="P">Prone</option>
                                    <option value="SP">Semi-Prone</option>
                                    <option value="M">Mobile</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td colspan="3"><input onclick="save_turning_details(<?php echo $Registration_ID?>)" class="art-button-green pull-right" type="button" value="Save"></td></tr>
                    </table><br>
                  
                  <center>
                  <table class="table" style="width:70%; ">
                        <tr style="vertical-align: middle;">
                        <td style="vertical-align: middle;">Start Date</td>
                        <td><input style="vertical-align: middle;text-align: center;" readonly class="form-control date" placeholder="Start Date"  id='start_date' style='text-align:center'/></td>
                        <td style="vertical-align: middle;">End Date</td>
                        <td><input style="vertical-align: middle;text-align: center;" readonly class="form-control date" placeholder="End Date" id='end_date' style='text-align:center'/></td>
                        <td style="text-align: center;vertical-align: middle;">
                        <input style="vertical-align: middle;text-align: center;" type="button" value="FILTER" onclick="filter_report(<?php echo $patient_id?>)" class="art-button-green"/>
                        </td>
                        </tr>
                    </table><br />
                  </center>
                  <div id="turning_chart">
                    <?php 
                    echo '
                    <table class="table" style="background-color:#fff">
                    <tr>
                        <th>Date</th>
                        <th>8am</th>
                        <th>10am</th>
                        <th>12md</th>
                        <th>2pm</th>
                        <th>4pm</th>
                        <th>6pm</th>
                        <th>8pm</th>
                        <th>10pm</th>
                        <th>12mn</th>
                        <th>2am</th>
                        <th>4am</th>
                        <th>6am</th>
                    </tr>
                    ';
                    $get_turning_data = mysqli_query($conn,"SELECT chart_date,chart_time,chart_id FROM tbl_turning_chart_details WHERE Patient_Registration_id = $patient_id GROUP BY chart_date ORDER BY chart_id DESC") or die(mysqli_error($conn));
                    while($patient_detail_rows = mysqli_fetch_assoc($get_turning_data)){
                        $chart_date = $patient_detail_rows['chart_date'];
                        $chart_id = $patient_detail_rows['chart_id'];
                        $chart_time1 = $patient_detail_rows['chart_time'];
                    echo '<tr>
                    <td >'.$chart_date.'</td>
                    ';
                        
                        $count_hr=0;
                        $first = "";
                        $second = "";
                        $third = "";
                        $fourth = "";
                        $fifth = "";
                        $sixth = "";
                        $seventh = "";
                        $eighth = "";
                        $ninth = "";
                        $tenth = "";
                        $eleventh = "";
                        $twelth = "";
                    while($count_hr<12){
                        if($count_hr==0){
                            $chart_time="8am";
                        }
                        if($count_hr==1){
                            $chart_time="10am";
                        }
                        if($count_hr==2){
                            $chart_time="12md";
                        }
                        if($count_hr==3){
                            $chart_time="2pm";
                        }
                        if($count_hr==4){
                            $chart_time="4pm";
                        }
                        if($count_hr==5){
                            $chart_time="6pm";
                        }
                        if($count_hr==6){
                            $chart_time="8pm";
                        }
                        if($count_hr==7){
                            $chart_time="10pm";
                        }
                        if($count_hr==8){
                            $chart_time="12mn";
                        }
                        if($count_hr==9){
                            $chart_time="2am";
                        }
                        if($count_hr==10){
                            $chart_time="4am";
                        }
                        if($count_hr==11){
                            $chart_time="6am";
                        }

                        $get_time_and_key = mysqli_query($conn,"SELECT chart_date,chart_time,chart_key,  created_at FROM tbl_turning_chart_details WHERE Patient_Registration_id = $patient_id AND chart_date = '$chart_date' AND chart_time='$chart_time' ORDER BY chart_id DESC") or die(mysqli_error($conn));
                        while($patient_time_key_rows = mysqli_fetch_assoc($get_time_and_key)){
                        $chart_time = $patient_time_key_rows['chart_time'];
                        $chart_key = $patient_time_key_rows['chart_key'];
                        $chart_date1 = $patient_time_key_rows['chart_date'];
                        $chart_created = $patient_time_key_rows['created_at'];
                   
                        if($chart_time == "8am"){$first = '<strong>'. $chart_key.'</strong>   ('.$chart_created.')';}
                        else if($chart_time == "10am"){$second = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')' ;}
                        else if($chart_time == "12md"){$third = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "2pm"){$fourth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "4pm"){$fifth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "6pm"){$sixth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "8pm"){$seventh = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "10pm"){$eighth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "12mn"){$ninth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "2am"){$tenth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "4am"){$eleventh = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        else if($chart_time == "6am"){$twelth = '<strong>'. $chart_key.'</strong>  ('.$chart_created.')';}
                        

                    }
                        $count_hr++;
                    }
                    
                    echo '
                    <td>'.$first.'</td>  
                    <td>'.$second.'</td>
                    <td>'.$third.'</td>
                    <td>'.$fourth.'</td>
                    <td>'.$fifth.'</td>
                    <td>'.$sixth.'</td>
                    <td>'.$seventh.'</td>
                    <td>'.$eighth.'</td>
                    <td>'.$ninth.'</td>
                    <td>'.$tenth.'</td>
                    <td>'.$eleventh.'</td>
                    <td>'.$twelth.'</td>

                    </tr>
                    ';
                    }
                    echo ' </table><br />
                    <table>
                        <tr><a target="_blank" href="turning_chart_pdf.php?patient_id='.$patient_id.'" class="art-button-green"><b>Preview in PDF</b></a></tr>
                    </table>
                    ';
                    
                     
                    ?>
                </div>     
             </fieldset>


 <script>
     function save_turning_details(Registration_id){
       var date = $('#date').val();
       var time = $('#time').val();
       var key = $('#key').val();
       //console.log(date,time,key);
       $.ajax({
            url: 'save_turning_chart.php',
            type: 'POST',
            data: {Registration_id:Registration_id,date:date,time:time,key:key},
            success:function(data){
                $('#turning_chart').empty();
               $('#turning_chart').html(data);
            }
       });
     }
     function filter_report(patient_id){
       var start_date=$("#start_date").val();
        var end_date=$("#end_date").val();    
       if(start_date==""){
            $("#start_date").css("border","2px solid red");
            exit;
        }else{
           $("#start_date").css("border",""); 
        }
        if(end_date==""){
            $("#end_date").css("border","2px solid red");
            exit;
        }else{
           $("#end_date").css("border",""); 
        }
        document.getElementById('turning_chart').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'POST',
            url:'filter_turning_chart.php',
            data:{
                start_date:start_date,
                end_date:end_date,
                patient_id:patient_id,
                },
            success:function(data){
                $('#turning_chart').empty();
               $('#turning_chart').html(data);
            }
        });
    }

    $(document).ready(function() {
        $("#key").select2();
        $("#time").select2();
    });


 </script>                               


<?php
    include("./includes/footer.php");
?>
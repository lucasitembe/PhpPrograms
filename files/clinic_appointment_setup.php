<?php
include("./includes/connection.php");
include("./includes/header.php");
?>
<link rel="stylesheet" href="table.css" media="screen"> 
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

<br/>
<br/>
<br/>
    <fieldset style='overflow-y: scroll; height: 420px; background: white;'>
    <legend align='center'>CLINIC APPOINTMENT MANDATE CONFIGURATION</legend>
        <center>
            <table width =100% border=0>
                    <tr id="thead">
                        <td width=3%><b>SN</b></td>
                        <td style="text-align: center;"><b>CLINIC NAME</b></td>
                        <td width="15%" style="text-align: center;"><b>AVAILABILITY</b></td>
                        <td width="15%" style="text-align: center;"><b>APPOINTMENT MANDATORY</b></td>
                    </tr>

                    <?php
                        $Clinic_selection = mysqli_query($conn, "SELECT Clinic_ID, Clinic_Name, Clinic_Status, Appointment_mandate FROM tbl_clinic");

                        $num = 1;
                    if(mysqli_num_rows($Clinic_selection)>0){
                        while($row = mysqli_fetch_array($Clinic_selection)){
                            $Clinic_ID = $row['Clinic_ID'];
                            $Clinic_Name = $row['Clinic_Name'];
                            $Clinic_Status = $row['Clinic_Status'];
                            $Appointment_mandate = $row['Appointment_mandate'];

                                    if($Appointment_mandate == 'Yes'){
                                        $Yes = "selected";
                                    }else{
                                        $No = "selected";
                                    }

                                $Selection = "<select id='appointment_change".$Clinic_ID."' onchange='appointment_change(".$Clinic_ID.")' style='width: 40%;'>
                                                <option>$Appointment_mandate</option>
                                                <option value='No'>No</option>
                                                <option value='Yes'>Yes</option>
                                            </select>";
                                

                            echo "<tr>
                                        <td>$num</td>
                                        <td>$Clinic_Name</td>
                                        <td>$Clinic_Status</td>
                                        <td>$Selection</td>
                                </tr>";
                                $num++;
                        }
                    }
                    ?>
                </table>
        </center>
    </fieldset>


<script>
    function appointment_change(Clinic_ID) {
        var Appoint = 'appointment_change'+Clinic_ID;
        var appointment_change = $("#" + Appoint).val();

        if (confirm("Are You Sure you want to Save this Clinic Setup?")) {
                $.ajax({
                    url: "ajax_save_clinic_appointment.php",
                    type: "post",
                    data: {Clinic_ID:Clinic_ID,appointment_change:appointment_change},
                    cache: false,
                    success: function(responce){
                        alert(responce);
                        location.reload(); 
                    }
                });
        }
    }
</script>
<br/>
<br/>
<br/>
<?php
include("./includes/footer.php");
?>
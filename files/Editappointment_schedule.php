<?php 

	require_once('includes/connection.php');
	
	if (isset($_POST['Time_ID'])) {
           $Time_ID = $_POST['Time_ID'];
           } else {
            $Time_ID = "";
           }

	$EditRow = "SELECT * FROM tbl_time_appointment WHERE Time_ID = '$Time_ID'";
	$EditParam_qry = mysqli_query($conn,$EditRow) or die(mysqli_error($conn));
	if($EditParam_qry){
	$row=mysqli_fetch_assoc($EditParam_qry);
                     $time_from=$row['time_from']; 
                     $time_to=$row['time_to']; 
                     $App_ID=$row['App_ID']; 
                     
                     //$App_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT App_ID FROM tbl_time_appointment WHERE time_from='$time_from' AND time_to='$time_to'"))['App_ID'];
                     
                     $Date = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Date FROM tbl_date_appointment WHERE App_ID='$App_ID'"))['Date'];
                     
                     
                     $Appointment_Idadi=$row['Appointment_Idadi']; 
		echo " <div class='box-body'>
                    <div class='row' style='height:100px;overflow-y: auto;overflow-x: hidden'>
                        <div class='col-md-12'>
                            <table class='table'>
                                <tr>
                                <caption><b>Date of Appointment</b></caption>
                                </tr>
                                <tr>
                                    <td><input type='text' id='edit_date' required='true' value='$Date'></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            <hr/>
                        </div>
                    </div>
                    <div class='row' style='height:200px;overflow-y: auto;overflow-x: hidden'>
                        <div class='col-md-12'>
                            <table class='table'>
                                <tr>
                                <caption><b>TIME SETING</b></caption>
                                </tr>
                                <tr>
                                    <td>Start Time</td>
                                    <td>End Time</td>
                                    <td>Number of Patient</td>
                                </tr>
                                <tr>
                                    <td>
					<input id='edit_time_from' type='text' class='time' value='$time_from' />

                                    </td>
                                    <td>
                                    <input id='edit_time_to' type='text' class='time' value='$time_to' />
                                    </td>
                                    <td>
                                    <input type='text' class='time'  id='edit_total_number' value='$Appointment_Idadi'/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
               
                    <div><input type='button' class='art-button pull-right'value='SAVE' onclick='Edite_appointment_schedule($App_ID,$Time_ID)'/></div>
                </div>
		   ";
	} else {
		echo 'error';
	}
	
?>
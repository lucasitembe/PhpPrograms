<?php 
    include("./includes/connection.php");

    $Start_From = (isset($_GET['Start_From'])) ? $_GET['Start_From'] : 0;
    $Date_From = (isset($_GET['Date_From'])) ? $_GET['Date_From'] : 0;
    $employee = (isset($_GET['employee'])) ? $_GET['employee'] : 0;
    $Query = "";

    if($Start_From === 0 || $Start_From == ""){
        $Query = "SELECT * FROM audit ORDER BY Id DESC LIMIT 50";
    }else if($Start_From != "" && $Start_From != 0 && $employee != ""){
        $Query = "SELECT * FROM audit WHERE Employee_Id = '$employee' AND Login_Time  BETWEEN '$Date_From' AND '$Start_From' ORDER BY Id DESC";
    }else{
        $Query = "SELECT * FROM audit WHERE Login_Time BETWEEN '$Date_From' AND '$Start_From' ORDER BY Id DESC";
    }

    $output = '';
    $outActivities = "";
    $count = 1;
    $select_login_logout = mysqli_query($conn,$Query);

    if(mysqli_num_rows($select_login_logout) > 0){
        while($row = mysqli_fetch_array($select_login_logout)){
            $id = $row["Employee_Id"];
            $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$id' LIMIT 1"))["Employee_Name"];
            $ActivityId = $row['Id'];

            $select_activities = mysqli_query($conn,"SELECT * FROM audit_logs WHERE Activities_Log_Id = $ActivityId");
            $i=1;
            if(mysqli_num_rows($select_activities) > 0){
                while($data = mysqli_fetch_assoc($select_activities)){
                    $outActivities .= "
                        <tr>
                            <td style='text-align:center'>$i</td>
                            <td>".$data['Date_Time']."</td>
                            <td>".$data['Action']."</td>
                        </tr>
                    ";
                    $i++;
                }
            }else{
                $outActivities .= "
                    <tr>
                        <td colspan='3' style='text-align:center;color:#000'>No Activity Found</td>
                    </tr>
                ";
            }

            $output .='
                <tr>
                    <td style="text-align:center">'.$count++.'</td>
                    <td>'.$Employee_Name.'</td>
                    <td> <b> Ip Address ~ '.$row["Ip_Address"].'</b><br>'.$row["Browser_Os"].'</td>
                    <td>'.$row["Login_Time"].'</td>
                    <td>'.$row["logout_Time"].'</td>
                    <td>
                        <table width="100%">
                            <tr style="background-color:#ddd">
                                <td width="8%" style="text-align:center"><b>S/N</b></td>
                                <td width="20%"><b>Time</b></td>
                                <td><b>Activities</b></td>
                            </tr>
                            <tr>
                                '.$outActivities.'
                            </td>
                        </table>
                    </td>
                </tr>
            ';
            $outActivities = "";
        }
    }else{
        $output .= "
            <tr>
                <td colspan='6' style='text-align:center;font-weight:bold'>No Data Found</td>
            </tr>
        ";
    }

    echo $output;
?>
<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    include("./includes/connection.php");
    session_start();
?>
<center>
            <?php
		echo '<center><table width =100% border=0>';
		echo "<tr>
			    <td style='text-align:center;width: 3%'>SN</td>
			    <td style='text-align:left'>EMPLOYEE NAME</td>
			    <td style='text-align:left'>LOGIN DATE AND TIME</td>
			    <td style='text-align:left'>LOGOUT DATE AND TIME</td>
			    <td style='text-align:left'>AUTHENTICATION DATE AND TIME</td>
			    <td style='text-align:left'>AUTHENTICATION LOCATION</td>
			    <td style='text-align:left'>ACTIVITY</td>
			    <td style='text-align:left'>ACTIVITY DATE AND TIME</td>
                <td style='text-align:left'>LOCATION</td>
                <td style='text-align:left'>IP ADDRESS</td>
                <td style='text-align:left'>PC NAME</td>
                <td style='text-align:left'>BRANCH</td>
		     </tr>";
		    echo "<tr>
				<td colspan=4></td></tr>";
                               //run the query to select all logs
                               $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
                                                         WHERE aud.Branch_ID=br.Branch_ID AND
                                                         aud.Employee_ID=emp.Employee_ID ORDER BY aud.Login_Date_And_Time DESC LIMIT 500");
                               
                               $sn=1;
                               while($audit_record_row=mysqli_fetch_array($audit_record)){
                                //return data
                                
                                $employeeName=$audit_record_row['Employee_Name'];
                                $Description=$audit_record_row['Description'];
                                $Login_Date_And_Time=$audit_record_row['Login_Date_And_Time'];
                                $Logout_Date_And_Time=$audit_record_row['Logout_Date_And_Time'];
                                $Authentication=$audit_record_row['Authentication'];
                                $Authentication_Date_And_Time=$audit_record_row['Authentication_Date_And_Time'];
                                $Activity=$audit_record_row['Activity'];
                                $Activity_Date_And_Time=$audit_record_row['Activity_Date_And_Time'];
                                $Date_And_Time=$audit_record_row['Date_And_Time'];
                                $Location=$audit_record_row['Location'];
                                $IP_Address=$audit_record_row['IP_Address'];
                                $PC_Name=$audit_record_row['PC_Name'];
                                $Branch_ID=$audit_record_row['Branch_ID'];
                                $Branch_Name=$audit_record_row['Branch_Name'];
                                
                                if($Description == "Authentication"){
                                    $select=mysqli_query($conn,"SELECT  Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Location' ");
                                    $row=mysqli_fetch_array($select);
                                    $Location=$row['Sub_Department_Name'];                                    
                                }
                                //display the data
                                echo "<tr>
                                        <td>$sn</td>
                                        <td>$employeeName</td>
                                        <td>".date('jS F, Y H:i:s',strtotime($Login_Date_And_Time))."</td>
                                        <td>"; ?>
                                            <?php
                                                if($Logout_Date_And_Time == '0000-00-00 00:00:00'){
                                                 echo "Not Yet Logged Out.";

                                            }else{
                                                    echo date('jS F,Y H:i:s',date(strtotime($Logout_Date_And_Time)));
                                                }

                                        echo "</td>
                                        <td>"; ?>
                                            <?php
                                                 if($Authentication_Date_And_Time == '0000-00-00 00:00:00' || $Authentication_Date_And_Time == null){
                                                     echo "No Authentication Recorded.";
                                                 }else{
                                                     echo $Authentication_Date_And_Time;
                                                     //echo date('jS F,Y H:i:s',date(strtotime($Authentication_Date_And_Time)));
                                                 }
                                        echo "</td>";
                                        echo "<td>"; ?>
                                            <?php
                                            if(empty($Authentication)){
                                                echo "No Authentication Location requested.";

                                            }else{
                                                echo $Authentication;
                                            }
                                        echo "</td>";
                                       echo "<td>"; ?>
                                       <?php
                                       if(empty($Activity)){
                                           echo "No Activity.";

                                       }else{
                                           echo $Activity;
                                       }
                                       echo "</td>";
                                       echo "<td>"; ?>
                                       <?php
                                       if($Activity_Date_And_Time == '0000-00-00 00:00:00' || $Activity_Date_And_Time == null){
                                           echo "No Activity Recorded.";

                                       }else{
                                           echo $Activity_Date_And_Time;
                                       }
                                       echo "</td>";
                                        echo "<td>$Location</td>
                                        <td>$IP_Address</td>
                                        <td>$PC_Name</td>
                                        <td>$Branch_Name</td>
                                </tr>";
                                
                                $sn++;
                                
                               }
			    ?>    
			</table>
			<table>
			</table>
			</center>
			</center>
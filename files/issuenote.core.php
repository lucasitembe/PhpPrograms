<?php 
    include './includes/connection.php';

	# load all issue note filtered and default loaded
    if(isset($_GET['load_issue_note'])){
        $output = "";
        $Sub_Department_Name = $_GET['Sub_Department_Name'];
        $Store_Issue = $_GET['Store_Issue'];
        $Search_Values = $_GET['Search_Values'];
        $temp = 1;

		if(isset($_GET['Start_Date'])){
			$Start_Date = $_GET['Start_Date'];
			$End_Date = $_GET['End_Date'];

			$sql = "SELECT rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name from
			tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where rq.store_issue = sd.sub_department_id and rq.store_issue = '$Store_Issue' and emp.employee_id = rq.employee_id and rq.requisition_status in ('submitted','edited','saved','Not Approved') and rq.Sent_Date_Time between '$Start_Date' and '$End_Date' order by rq.Requisition_ID desc";
		}else{
			$sql = "SELECT rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name from
			tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where rq.store_issue = sd.sub_department_id and rq.store_issue = '$Store_Issue' and
			rq.Store_Issue IN ($Search_Values) and
			emp.employee_id = rq.employee_id and rq.requisition_status in ('submitted','edited','saved','Not Approved')and Store_Issue='$Store_Issue' order by rq.Requisition_ID desc";
		}

        $sql_select = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	    if($num > 0){
			while($row = mysqli_fetch_array($sql_select)){
			    $Store_Need = $row['Store_Need'];
			    $Store_Issue = $row['Store_Issue'];
			    $select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Store_Need'") or die(mysqli_error($conn));
			    $no_of_rows = mysqli_num_rows($select_store_need);
			    if($no_of_rows > 0){
				while($data = mysqli_fetch_array($select_store_need)){
				    $Sub_Department_Name = $data['Sub_Department_Name'];
				}
			    }else{
				$Sub_Department_Name = $row['Sub_Department_Name'];
			    }

			    //get store issue
			    $select_store_issue = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Store_Issue'") or die(mysqli_error($conn));
			    $no_of_rows = mysqli_num_rows($select_store_issue);
			    if($no_of_rows > 0){
					while($data = mysqli_fetch_array($select_store_issue)){
					    $Sub_Department_Name_Issue = $data['Sub_Department_Name'];
					}
			    }else{
					$Sub_Department_Name_Issue = $row['Sub_Department_Name'];
			    }	    
			    
			    $output .= '<tr>
                    <td style="text-align: center;padding:8px">'.$temp.'</td>
				    <td style="padding:8px">'.$row['Requisition_ID'].'</td>
				    <td style="padding:8px">'.$row['Created_Date_Time'].'</td>
				    <td style="padding:8px">'.$Sub_Department_Name.'</td>	
				    <td style="padding:8px">'.$Sub_Department_Name_Issue.'</td>	
				    <td style="padding:8px">'.$row['Requisition_Description'].'</td> 
				    <td style="padding:8px">
                        <center>
				            <a href="Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID='.$row['Requisition_ID'].'" class="art-button-green">&nbsp;&nbsp;&nbsp;Process&nbsp;&nbsp;&nbsp;</a>
                        </center>
                    </td>
				</tr>';
	            $temp++;
			}
        }else{
			$disp = (isset($_GET['Start_Date'])) ? " Between <b> ".$_GET['Start_Date']."<b> ~ <b>".$_GET['End_Date']."</b>" : " For Today";
			$output .= '<tr><td colspan="7" style="padding:12px;text-align:center;font-size:14px"> No Issue Note Found '.$disp.'</td></tr>';
		}
        echo $output;
    }

	# load unprocessed issue issue notes
	if(isset($_GET['load_unapproved_issue_note'])){
		$output = "";
		$temp = 1;
		$Search_Values = $_GET['Search_Values'];
        $Store_Issue = $_GET['Store_Issue'];

		if(isset($_GET['Start_Date'])){
			$Start_Date = $_GET['Start_Date'];
			$End_Date = $_GET['End_Date'];
			$store_need_id = $_GET['store_need_id'];

			$store_need = ($store_need_id == "all") ? "" : " AND rq.Store_Need = $store_need_id";

			$sql = "SELECT rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name from
					tbl_requisition rq, tbl_sub_department sd, tbl_employee emp where
					rq.store_issue = sd.sub_department_id $store_need and rq.store_issue = '$Store_Issue' and 
					emp.employee_id = rq.employee_id and
					rq.requisition_status = 'Not Approved' and
					rq.Created_Date_Time between '$Start_Date' and '$End_Date' order by rq.Requisition_ID desc";
		}else{
			$sql = "SELECT rq.Requisition_Description, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Issue, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name from tbl_requisition rq, 		tbl_sub_department sd, tbl_employee emp WHERE rq.store_issue = sd.sub_department_id and rq.store_issue = '$Store_Issue' and
				rq.Store_Issue IN ($Search_Values) and
				emp.employee_id = rq.employee_id and rq.requisition_status = 'Not Approved' order by rq.Requisition_ID desc";
		}

		$sql_select = mysqli_query($conn,$sql);
		$num = mysqli_num_rows($sql_select);
	    if($num > 0){
			while($row = mysqli_fetch_array($sql_select)){
			    $Store_Need = $row['Store_Need'];
			    $Store_Issue = $row['Store_Issue'];
			    $select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Store_Need'") or die(mysqli_error($conn));
			    $no_of_rows = mysqli_num_rows($select_store_need);
			    if($no_of_rows > 0){
				while($data = mysqli_fetch_array($select_store_need)){
				    $Sub_Department_Name = $data['Sub_Department_Name'];
				}
			    }else{
					$Sub_Department_Name = $row['Sub_Department_Name'];
			    }

			    $select_store_issue = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Store_Issue'") or die(mysqli_error($conn));
			    $no_of_rows = mysqli_num_rows($select_store_issue);
			    if($no_of_rows > 0){
					while($data = mysqli_fetch_array($select_store_issue)){
					    $Sub_Department_Name_Issue = $data['Sub_Department_Name'];
					}
			    }else{
					$Sub_Department_Name_Issue = $row['Sub_Department_Name'];
			    }	    
			    
			    $output .= '<tr>
					<td style="text-align: center;padding:8px">'.$temp.'</td>
				    <td style="padding:8px">'.$row['Requisition_ID'].'</td>
				    <td style="padding:8px">'.$row['Sent_Date_Time'].'</td>
				    <td style="padding:8px">'.$Sub_Department_Name.'</td>	
				    <td style="padding:8px">'.$Sub_Department_Name_Issue.'</td>	
				    <td style="padding:8px">'.$row['Requisition_Description'].'</td> 
				    <td style="text-align: center;padding:8px">
				    	<a href="Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID='.$row['Requisition_ID'].'" class="art-button-green">PROCESS APPROVE</a>
				    </td>
				</tr>';
	            $temp++;
			}
	    }else{
			$disp = (isset($_GET['Start_Date'])) ? " Between <b> ".$_GET['Start_Date']."<b> ~ <b>".$_GET['End_Date']."</b>" : " For Today";
			$output .= '<tr><td colspan="7" style="padding:12px;text-align:center;font-size:14px">No Unprocessed Issue Note Found '.$disp.'</td></tr>';
		}
		echo $output;
	}

	if(isset($_GET['load_issues'])){
		echo "yesss";
	}
?>
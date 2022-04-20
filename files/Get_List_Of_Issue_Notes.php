<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}

	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}
        
        if(isset($_GET['Order_No'])){
		$Order_No = $_GET['Order_No'];
	}

    //get employee id 
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }
    
	//get sub departments
    $Search_Values = '';
    $select = mysqli_query($conn,"select Sub_Department_ID from tbl_employee_sub_department where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
    	while ($row = mysqli_fetch_array($select)) {
    		if($Search_Values == ''){
    			$Search_Values .= $row['Sub_Department_ID'];
    		}else{
    			$Search_Values .= ','.$row['Sub_Department_ID'];    			
    		}
    	}
    }
?>
<legend style='background-color:#006400;color:white;padding:5px;' align=right><b>&nbsp;&nbsp;GRN Against Issue Note, List Of Issues&nbsp;&nbsp;&nbsp;&nbsp;</b></legend>
	    <center><table width = "100%">
		<tr id='thead'>
		    <tr><td colspan="10"><hr></td></tr>
		    <td width=4% style='text-align: center;'><b>SN</b></td>
		    <td width=7% style='text-align: left;'><b>ISSUE N<u>O</u></b></td>
		    <td width=10% style='text-align: left;'><b>REQUISITION N<u>O</u></b></td>
		    <td width=13% style='text-align: left;'><b>REQUESTED DATE</b></td>
		    <td width=17% style='text-align: left;'><b>REQUISITION PREPARED BY</b></td>
		    <td width=13%><b>ISSUE DATE & TIME</b></td>
		    <td width=15%><b>STORE NEED</b></td>
		    <td width=15%><b>STORE ISSUE</b></td>
		    <td width=15%><b>DESCRIPTION</b></td>
		    <td style='text-align: center;' width=10%><b>ACTION</b></td>
			<tr><td colspan="10"><hr></td></tr>
		</tr>
	
	<?php 
        
        $filter="  and rq.Store_Need IN ($Search_Values) ";  
  
  if(!empty($Start_Date) && !empty($End_Date)){
      $filter = " and isu.Issue_Date_And_Time between '$Start_Date' and '$End_Date' and rq.Store_Need IN ($Search_Values) ";    
  }
  
  if(!empty($Order_No)){
    $filter = " and isu.Issue_ID = '$Order_No' ";    
  }
  
        
	    $temp = 1;   
	    //get top 50 grn open balances based on selected employee id
            $Store_Issue=$_SESSION['Storage_Info']['Sub_Department_ID'];
	    $Sub_Department_Name = $_SESSION['Storage'];
	    $sql_select = mysqli_query($conn,"select rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
									tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu where
									rq.store_issue = sd.sub_department_id and
									emp.employee_id = rq.employee_id and
									rq.requisition_status = 'served' and
									isu.Requisition_ID = rq.Requisition_ID 
                                                                        $filter and Store_Issue='$Store_Issue'
								        order by rq.Requisition_ID desc limit 200") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($sql_select);
	   

	   if($num > 0){
		while($row = mysqli_fetch_array($sql_select)){
			$Store_Need = $row['Store_Need'];

			//get store need
			$get_store = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
			$get_store_num = mysqli_num_rows($get_store);
			if($get_store_num > 0){
				while ($dt = mysqli_fetch_array($get_store)) {
					$S_Need = $dt['Sub_Department_Name'];
				}
			}else{
				$S_Need = '';
			}
		    echo '<tr><td style="text-align: center;">'.$temp.'</td>
			    <td>'.$row['Issue_ID'].'</td>
			    <td>'.$row['Requisition_ID'].'</td>
			    <td>'.$row['Sent_Date_Time'].'</td>
			    <td>'.$row['Employee_Name'].'</td>	
			    <td>'.$row['Issue_Date_And_Time'].'</td>	
			    <td>'.$S_Need.'</td> 	
			    <td>'.$row['Sub_Department_Name'].'</td> 	
			    <td>'.$row['Requisition_Description'].'</td> 
			    <td style="text-align: center;">
			    <a href="Control_Grn_Session.php?New_Grn=True&Issue_ID='.$row['Issue_ID'].'" class="art-button-green">&nbsp;&nbsp;&nbsp;Process&nbsp;&nbsp;&nbsp;</a>
			    </td>
				
			</tr>';
	
        	$temp++;
		}
	  }
	  	echo '</table>';
		
	?>
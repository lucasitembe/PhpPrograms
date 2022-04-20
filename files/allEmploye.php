<?php
 include("./includes/connection.php");
 $data='';$temp=1;
 $data .= '<center><table width =100%>';
     $data .= '<tr id="thead"><td style="width:5%;"><b>SN</b></td><td><b>EMPLOYEE  NAME</b></td>
                <td><b>EMPLOYEE TYPE</b></td>
                    <td><b>EMPLOYEE NUMBER</b></td>
                        <td><b>EMPLOYEE TITLE</b></td>
                            <td><b>JOB CODE</b></td>
                                <td><b>BRANCH</b></td></tr>';
    if(isset($_GET['employee'])){
	   $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_employee  WHERE Employee_Name like '%".$_GET['employee']."%' order by employee_number") or die(mysqli_error($conn));
	}else{
	  $select_Filtered_Employees = mysqli_query($conn,
            "select * from tbl_employee") or die(mysqli_error($conn));
	}
	
    

		    
    while($row = mysqli_fetch_array($select_Filtered_Employees)){
        $data .= "<tr>
		              <td id='thead'>".$temp."</td>
					  <td>
					     <span onclick='showData(\"".$row['Employee_ID']."\",\"".$row['Employee_Name']."\")'>".ucwords(strtolower($row['Employee_Name']))."</span>
					  </td>";
        $data .= "<td><p  id=".$row['Employee_ID']."' style='text-decoration: none;'>".$row['Employee_Type']."</p></td>";
        $data .= "<td><p  id=".$row['Employee_ID']."'  style='text-decoration: none;'>".$row['Employee_Number']."</p></td>";
        $data .= "<td><p  id=".$row['Employee_ID']."'  style='text-decoration: none;'>".$row['Employee_Title']."</p></td>";
        $data .= "<td><p  id=".$row['Employee_ID']."' style='text-decoration: none;'>".$row['Employee_Job_Code']."</p></td>";
        $data .= "<td><p  id=".$row['Employee_ID']."' style='text-decoration: none;'>".$row['Employee_Branch_Name']."</p></td>";
	$temp++;
    }   $data .= "</tr>";
	
	$data .="</table></center>";
	
	echo $data;
?>
 
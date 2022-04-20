<link rel="stylesheet" href="table.css" media="screen">
<?php
include("./includes/connection.php");
$temp = 1;

$Jobcard_Number = $_GET['Jobcard_Number'];
$Employee_ID = $_GET['Employee_ID'];

$filter = '';



//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
}
//end

if (!empty($Jobcard_Number)) {
    $filter .= " AND j.jobcard_ID = '$Jobcard_Number'";
}

if (!empty($Employee_ID)) {
    $filter .= " AND j.requesting_engineer LIKE '%$Employee_ID%'";
}


echo '<table width =100% border=0>';

echo '<tr id="thead">
            <td style="width:2%; text-align: center;"><b>SN</b></td>
            <td style="width:3%; text-align: center;"><b>JOBCARD #</b></td>
            <td style="width:6%; text-align: center;"><b>JOBCARD DATE</b></td>
            <td style="width:8%; text-align: center;"><b>INITIALIZED BY</b></td>
            <td style="text-align: center;width:8%;"><b>DEPARTMENT</b></td>                 
	 </tr>';

             
             
     $display_table.="</tr>";
				

     $query_data=mysqli_query($conn,"SELECT j.Authorize_Comment, j.part_date, j.requesting_engineer, j.jobcard_ID, sd.Sub_Department_Name FROM tbl_jobcards j, tbl_sub_department sd WHERE j.status='Rejected' AND sd.Sub_Department_ID = j.Sub_Department_ID $filter ORDER BY j.jobcard_ID DESC");
     
     $num=0;
     while($result_query=mysqli_fetch_array($query_data)){
                                          $Authorize_Comment=$result_query['Authorize_Comment'];
                                          $Authorised_at=$result_query['Authorised_at'];
                                          $requesting_engineer=$result_query['requesting_engineer'];
                                          $jobcard_ID=$result_query['jobcard_ID'];
                                          $Employee_Name=$result_query['Employee_Name'];
                                          $Sub_Department_Name=$result_query['Sub_Department_Name'];
                                          $part_date=$result_query['part_date'];
             $num++;
       
         $display_table.="   
         <tr> 
            <td><center><a href='rejected_jobcard_review.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent' title='Click To Correct This Jobcard'>".$num."</a></center></a></td>
            <td style='text-align:center;'><a href='rejected_jobcard_review.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent' title='Click To Correct This Jobcard'>".$jobcard_ID."</a></td>
            <td><a href='rejected_jobcard_review.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent' title='Click To Correct This Jobcard'>".$part_date."</a></td>
            <td><a href='rejected_jobcard_review.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent' title='Click To Correct This Jobcard'>".$requesting_engineer."</a></td>
            <td><a href='rejected_jobcard_review.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent' title='Click To Correct This Jobcard'>".$Sub_Department_Name."</a></td>
        </tr>";
     }
     
     $display_table.="</table>";
     
     echo $display_table;
?>
</table>



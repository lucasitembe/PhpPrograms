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

$date_From = $_GET['date_From'];
$date_To = $_GET['date_To'];

if(!empty($date_From) && !empty($date_To)){
    $filter = " AND j.part_date BETWEEN '$date_From' AND '$date_To'";
}
             
             
     $display_table.="</tr>";
				
     $query_data=mysqli_query($conn,"SELECT j.Authorize_Comment, j.Approved_at, j.part_date, j.requesting_engineer, j.jobcard_ID, em.Employee_Name, sd.Sub_Department_Name FROM tbl_jobcards j, tbl_employee em, tbl_sub_department sd WHERE j.status='Approved' AND em.Employee_ID = j.Approved_by AND sd.Sub_Department_ID = j.Sub_Department_ID $filter ORDER BY j.jobcard_ID DESC LIMIT 50");
     
     $num=0;
     while($result_query=mysqli_fetch_array($query_data)){
                                          $Authorize_Comment=$result_query['Authorize_Comment'];
                                          $Authorised_at=$result_query['Approved_at'];
                                          $requesting_engineer=$result_query['requesting_engineer'];
                                          $jobcard_ID=$result_query['jobcard_ID'];
                                          $Employee_Name=$result_query['Employee_Name'];
                                          $Sub_Department_Name=$result_query['Sub_Department_Name'];
                                          $part_date=$result_query['part_date'];  
                                          
                                        $Store_Order_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Store_Order_ID FROM tbl_store_orders WHERE jobcard_ID = '$jobcard_ID'"))['Store_Order_ID'];
                                        if($Store_Order_ID > 0){
                                            $Select_Status = mysqli_query($conn, "SELECT purchase_requisition_id FROM tbl_purchase_requisition WHERE Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));

                                            if(mysqli_num_rows($Select_Status) > 0){
                                                $Procurement_Status = 'PR Approval Stage';
                                            }else{
                                                $Procurement_Status = 'Procurement Department';
                                            }
                                        }
             $num++;
       
         $display_table.="   
         <tr> 
            <td><center><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$num."</a></center></a></td>
            <td style='text-align:center;'><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$jobcard_ID."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$part_date."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$Procurement_Status."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$requesting_engineer."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$Sub_Department_Name."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$Employee_Name."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$Authorised_at."</a></td>
            <td><a href='jobcard_view.php?Process_Jobcard=True&jobcard_ID=".$jobcard_ID."' style='text-decoration: none;' target='_parent'>".$Authorize_Comment."</a></td>
        </tr>";
     }
     
     $display_table.="</table>";
     
     echo $display_table;
?>
</table>



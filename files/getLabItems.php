<style>
    .linkstyle{
        color:#3EB1D3;
    }
</style>
<?php
  include("./includes/connection.php");
    $fromDate = 'NOW()';
    $toDate = 'NOW()';
    $Registration_ID = 0;
    
if (isset($_GET['action'])) {
    $fromDate = $_GET['fromdate'];
    $toDate = $_GET['todate'];
    $Registration_ID = $_GET['Registration_ID'];
}

$data='';

$data .= '<center><table width =100% border=0 id="specimencollected" class="display">';
$data .= "<thead>
                <tr>
			    <th style='text-align: left;' width=3%'>SN</th>
			    <th style='text-align: left;' width=22%'>ITEM NAME</th>
			    <th style='text-align: left;' width=15%'>SPECIMEN COLLECTED</th>
			    <th style='text-align: left;' width=10%'>TIME COLLECTED</th>
			    <th style='text-align: left;' width=15%'>EMPLOYEE COLLECTED</th>
			    <th style='text-align: left;' width=8%'>SENT BY</th>
                            <th style='text-align: left;' width=15%'>TIME SENT</th>
			    <th style='text-align: left;' width=18%'>TRANSACTION DATE</th>
                </tr>
            </thead>";
$count = 0;

 $select_data = "SELECT i.Product_Name,ls.Specimen_Name,sp.TimeCollected,sp.specimen_results_Employee_ID,ilc.Consultant_ID,ilc.Transaction_Date_And_Time,ilc.Payment_Date_And_Time "
         . "FROM tbl_specimen_results sp INNER JOIN tbl_item_list_cache ilc ON sp.payment_item_ID=ilc.Payment_Item_Cache_List_ID "
         . "JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID " 
         . "JOIN tbl_items i ON i.Item_ID=ilc.Item_ID "
         . "JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID "
         . "JOIN tbl_laboratory_specimen ls ON sp.Specimen_ID=ls.Specimen_ID "
         . " WHERE sp.TimeCollected BETWEEN '$fromDate' AND '$toDate' AND pr.Registration_ID='$Registration_ID' ORDER BY sp.TimeCollected ASC";
 
  // die($select_data);
    $select_data_result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select_data_result)) {
        $Product_Name = $row['Product_Name'];
        $Specimen_Name = $row['Specimen_Name'];
        $TimeCollected= $row['TimeCollected'];
        $specimen_results_Employee_ID = $row['specimen_results_Employee_ID'];
        $Consultant_ID = $row['Consultant_ID'];
        $Transaction_Date_And_Time= $row['Transaction_Date_And_Time'];
        $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
        
        $specimen_results_Employee_Name= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID=$specimen_results_Employee_ID"))['Employee_Name'];
        $Consultant_Name= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID=$Consultant_ID"))['Employee_Name'];
        
        $data .= "<tr><td>" . ($count + 1) . "</td>";
        $data .= "<td style='text-align:left'><span  class='linkstyle' >" . $Product_Name . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $Specimen_Name . "</span></td>";
        $data .= "<td style='text-align:left'><span  class='linkstyle' >" . $TimeCollected . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $specimen_results_Employee_Name . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $Consultant_Name . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $Transaction_Date_And_Time . "</span></td>";
        // $data .= "<td style='text-align:left; width:15%'>".$row['Specimen_Name']."</td>";
        $data .= "<td style='text-align:left'><span  class='linkstyle' >" . $Payment_Date_And_Time . "</span></td>
                </tr>";
        $count++;
       
    }
    
    $data .= "</table></center>";
    
    echo $data;

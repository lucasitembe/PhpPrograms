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
     $filter='';
    
if (isset($_GET['action'])) {
    $fromDate = $_GET['fromdate'];
    $toDate = $_GET['todate'];
    $Registration_ID = $_GET['Registration_ID'];
     $filter = " WHERE ilc.Transaction_Date_And_Time BETWEEN '$fromDate' AND '$toDate' AND ilc.Check_In_Type='Procedure' AND ilc.Status IN ('active','paid')  AND pr.Registration_ID='$Registration_ID'";
   
}

$data='';

$data .= '<center><table width ="100%" border="0" id="" class="display">';
$data .= "<thead>
                <tr>
			    <th style='text-align: left;' width='3%'>SN</th>
			    <th style='text-align: left;' width='22%'>ITEM NAME</th>
			    <th style='text-align: left;' width='15%'>SUBCATEGORY</th>
			    <th style='text-align: left;' width='8%'>SENT BY</th>
                            <th style='text-align: left;' width='15%'>DOCTOR'S COMMENTS</th>
			    <th style='text-align: left;' width='18%'>TRANSACTION DATE</th>
                </tr>
          </thead>";
$count = 0;

 $select_data = "SELECT i.Product_Name,isc.Item_Subcategory_Name,ilc.Doctor_Comment,ilc.Consultant_ID,ilc.Transaction_Date_And_Time "
         . "FROM tbl_item_list_cache ilc "
         . "INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID " 
         . "JOIN tbl_items i ON i.Item_ID=ilc.Item_ID "
         . "JOIN tbl_item_subcategory isc ON i.Item_Subcategory_ID=isc.Item_Subcategory_ID "
         . "JOIN tbl_patient_registration pr ON pr.Registration_ID=pc.Registration_ID "
         . " $filter ORDER BY ilc.Transaction_Date_And_Time ASC";
 
  // die($select_data);
    $select_data_result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select_data_result)) {
        $Product_Name = $row['Product_Name'];
        $Subcategory_Name = $row['Item_Subcategory_Name'];
        $Doctor_Comment = $row['Doctor_Comment'];
        $Consultant_ID = $row['Consultant_ID'];
        $Transaction_Date_And_Time= $row['Transaction_Date_And_Time'];
        
        $Consultant_Name= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID=$Consultant_ID"))['Employee_Name'];
        
         
        $data .= "<tr><td>" . ($count + 1) . "</td>";
        $data .= "<td style='text-align:left'><span  class='linkstyle' >" . $Product_Name . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $Subcategory_Name . "</span></td>";
        $data .= "<td style='text-align:left'><span  class='linkstyle' >" . $Consultant_Name . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $Doctor_Comment . "</span></td>";
        $data .= "<td style='text-align:left '><span  class='linkstyle' >" . $Transaction_Date_And_Time . "</span></td>";
        $data .= " </tr>";
        $count++;
       
    }
    
    $data .= "</table></center>";
    
    echo $data;

<link rel="stylesheet" href="table.css" media="screen">
    <link rel="stylesheet" href="style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
</style>
<table width=100%>
    <tr id='thead'>
        <td width=4% style='text-align: center;'><b>SN</b></td>
		 <td width=8%><b>Requisition N<u>0</u></b></td>
        <td width=15%><b>Created Date & Time</b></td>
        <td width=10%><b>Store Need</b></td>
		<!--<td width=10%><b>Department Name</b></td>-->
        <td width=20%><b>Item Description</b></td>
        <td width=40%><b>Requisition Status</b></td>
        <td style='text-align: center;'><b>Action</b></td>
    </tr> 
<?php
    @session_start();
    $temp = 1;
    include("./includes/connection.php");
    if(isset($_SESSION['Storage'])){
        $Sub_Department_Name = $_SESSION['Storage'];
    }else{
        $Sub_Department_Name = '';
    }
    
    //select order data
    $select_Order_Details = mysqli_query($conn,"select * from tbl_sub_department, tbl_requisition rn, tbl_sub_department sd where
    sd.sub_department_id = sd.sub_department_id and rn.Requisition_ID =rn.Requisition_ID and
    sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by Requisition_ID") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Order_Details);
    if($no > 0){
        while($row = mysqli_fetch_array($select_Order_Details)){
            echo "<tr><td style='text-align:center;'>".$temp."</td>";
            echo "<td>".$row['Requisition_ID']."</td>";
            echo "<td>".$row['Created_Date']."</td>";
            echo "<td>".$row['Sub_Department_Name']."</td>";
            echo "<td>".$row['Requisition_Description']."</td>";
            echo "<td>".$row['Requisition_Status']."</td>";
            echo "<td><a href='issue_note.php?Requisition_ID=".$row['Requisition_ID']."&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Parent' class='art-button-green'>Process</a></td></tr>";
            $temp++;
        }
        echo "</tr>";
    }
?>

</table>
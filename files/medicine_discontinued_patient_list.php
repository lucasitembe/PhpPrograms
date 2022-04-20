<?php
@session_start();
include("./includes/connection.php");
if(isset($_POST['list_id'])){
    $filter= " ";
    if(isset($_POST['Date_From']) && !empty($_POST['Date_From']) && isset($_POST['Date_To']) && !empty($_POST['Date_To'])){
        $Date_From=mysqli_real_escape_string($conn,$_POST['Date_From']);
        $Date_To=mysqli_real_escape_string($conn,$_POST['Date_To']);
        $filter = " AND img.Time_Given BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }
    $Item_ID=$_POST['list_id'];
    $select=mysqli_query($conn,"SELECT distinct pr.Patient_Name,e.Employee_Name, img.Time_Given from tbl_inpatient_medicines_given img, tbl_items i, tbl_patient_registration pr, tbl_employee e WHERE img.Registration_ID=pr.Registration_ID AND img.Discontinue_Status='yes' AND i.Item_ID=img.Item_ID AND e.Employee_ID=img.Employee_ID AND img.Item_ID =$Item_ID $filter");

    $select_product=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID=$Item_ID ");
    $product=mysqli_fetch_assoc($select_product);
    echo '<h4>Medicine Name: '.$product['Product_Name'].' </h4>

<table width="100%" border="1" style="border-collapse: collapse;"  cellpadding="6">
    <thead>
        <th>SN</th>
        <th>Patient Name</th>
        <th>Date Discontinued</th>
        <th>Person Discontinued</th>
    </thead>
    <tbody><BR>';
    $sn=1;
    while($row=mysqli_fetch_assoc($select)){
        echo '<tr>
            <td>'.$sn.'</td>
            <td>'.$row['Patient_Name'].'</td>
            <td>'.$row['Time_Given'].'</td>
            <td>'.$row['Employee_Name'].'</td>
        </tr>';
        $sn++;
    }
       echo '
    </tbody>
</table>';
}
?>
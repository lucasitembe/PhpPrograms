<?php 
    include("./includes/connection.php");
    
    $bill_type = (isset($_GET['value'])) ? $_GET['value'] : 0;
    $output = "";

    if($bill_type == 'Outpatient Cash'){
        $filter = " payment_method = 'cash'";
    }else{
        $filter = " payment_method = 'credit'";
    }

    $sqlQueryGetSponsor = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor WHERE $filter");
    while($data = mysqli_fetch_assoc($sqlQueryGetSponsor)){
        $id = $data['Sponsor_ID'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $output .= "<option value='$id'>$Guarantor_Name</option>";
    }

    echo 
        "<option value=''>Select Temporary Sponsor</option>".$output;
?>
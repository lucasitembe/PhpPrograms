<?php
include("./includes/connection.php"); 
//Retreive sponsor name
if(isset($_GET['sponsorNameHolder'])){
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $data='<option value=""></option>';
    $data.='<option value="All Sponsors">All Sponsors</option>';
    while ($row = mysqli_fetch_array($query)) {
         $data.= '<option value="'.$row['Sponsor_ID'].'$$>'.$row['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
    }
    
    echo $data;
}

//Retreive sponsor name
if(isset($_GET['DataEntryNameHolder'])){
    $query=  mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee") or die(mysqli_error($conn));
    $data='<option value=""></option>';
    $data.='<option value="All Data Entry">All Data Entry</option>';
    while ($row = mysqli_fetch_array($query)) {
         $data.= '<option value="'.$row['Employee_ID'].'$$>'.$row['Employee_Name'].'">'.$row['Employee_Name'].'</option>';
    }
    
    echo $data;
}
        
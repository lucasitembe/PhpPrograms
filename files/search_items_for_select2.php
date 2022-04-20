<?php
include("./includes/connection.php");
// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
$search = strip_tags(trim($_GET['q'])); 
$consultationType = strip_tags(trim($_GET['consultationType'])); 


if($consultationType == 'Radiology'){
    $result_limit=mysqli_query($conn,"SELECT Item_ID,Product_Name FROM tbl_items WHERE Ct_Scan_Item = 'yes' LIMIT 1") or die(mysqli_error($conn));
if(mysqli_num_rows($result_limit)>0){
    $query = "SELECT Item_ID,Product_Name FROM tbl_items WHERE Product_Name LIKE '%$search%' AND Consultation_Type= '$consultationType' AND Ct_Scan_Item = 'no' LIMIT 10";
}else{
    $query = "SELECT Item_ID,Product_Name FROM tbl_items WHERE Product_Name LIKE '%$search%' AND Consultation_Type= '$consultationType' LIMIT 10";
}
}else{
        $query = "SELECT Item_ID,Product_Name FROM tbl_items WHERE Product_Name LIKE '%$search%' AND Consultation_Type= '$consultationType' LIMIT 10";

}
// Do Prepared Query 


// Add a wildcard search to the search variable

$result=mysqli_query($conn,$query) or die(mysqli_error($conn));

// Make sure we have a result
if(mysqli_num_rows($result) >0){
// Do a quick fetchall on the results
if($consultationType == 'Radiology'){
     $data[] = array('id' => 'ct-scan', 'text' => 'CT Scan');	 
}    
while ($row = mysqli_fetch_array($result)) {
   $data[] = array('id' => $row['Item_ID'], 'text' => $row['Product_Name']);	 
}
}else {
   $data[] = array('id' => '0', 'text' => 'No Test Found');
}


// return the result in json
echo json_encode($data);

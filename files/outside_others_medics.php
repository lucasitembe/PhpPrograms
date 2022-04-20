<?php
include("./includes/connection.php");
// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
$search = strip_tags(trim($_GET['q'])); 
$consultationType = strip_tags(trim($_GET['consultationType'])); 

$Registration_ID = $_GET['Reg_ID'];
$consultation_ID = $_GET['consultation_ID'];
// Do Prepared Query 
$query = "SELECT Item_ID,Product_Name FROM tbl_items WHERE Product_Name LIKE '%$search%' AND Consultation_Type= '$consultationType' AND  Status='Available' AND Can_Be_Sold='yes' AND Ct_Scan_Item='no' AND Ward_Round_Item='no'  LIMIT 10";

// Add a wildcard search to the search variable

$result=mysqli_query($conn,$query) or die(mysqli_error($conn));

// Make sure we have a result
if(mysqli_num_rows($result) >0){
    
// Do a quick fetchall on the results
while ($row = mysqli_fetch_array($result)) {
     $select_service = "
		SELECT * FROM tbl_inpatient_medicines_given 
			WHERE Item_ID = '".$row['Item_ID']."' AND 
                              Registration_ID = '$Registration_ID' AND
                              consultation_ID = '$consultation_ID' AND
                              Discontinue_Status='yes'    
                        ";
            $selected_service = mysqli_query($conn,$select_service) or die(mysqli_error($conn));
//            if (mysqli_num_rows($selected_service) == 0) {
              $data[] = array('id' => $row['Item_ID'], 'text' => $row['Product_Name']);
//            }
   	 
}

}else {
   $data[] = array('id' => '0', 'text' => 'No Test Found n');
}


// return the result in json
echo json_encode($data);

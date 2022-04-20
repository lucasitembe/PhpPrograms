<?php
//header("Content-Type:application/json");
set_time_limit(0);
include("./includes/connection.php");
include_once './nhif3/constants.php';
include_once './functions/items.php';
include_once './nhif3/ServiceManager.php';

$manager = new ServiceManager();

$temp = 0;

if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}

//$url = 'http://verification.nhif.or.tz/apiserver/api/v1/Packages/getItems';//base_url . 'verification/authorizecard?CardNo=' . '&FacilityCode=' . 01146 . '&UserName=kairuki';
//echo FacilityCode."::".UserName."::".public_key."::".private_key."::".base_url;
//$url="https://verification.nhif.or.tz/apiserver/api/v1/Packages/GetPricePackage?FacilityCode=04299";
 //$objs = $manager->GetApiData($url);
   
  $objs = $manager->GetPricePackage(FacilityCode);
/////////////////////////////////////////
  $decodedText1 = html_entity_decode($objs);
  $objs1 = json_decode($decodedText1, true);
  // echo $manager->GetPriceData();
  // echo $manager->GetApiData("101101406772");
//////////////////////////

$i=1;
$sql_empty_remote_category=mysqli_query($conn,"DELETE FROM tbl_remote_item_categories") or die(mysqli_error($conn));
foreach ($objs1 as $val) {
  $ItemTypeID=$val['ItemTypeID'];
 //echo "ndani<br/>".$i++;
  if($ItemTypeID==0)continue;
  $TypeName="itemtype$ItemTypeID";
  $sql_check_if_itemid_exist=mysqli_query($conn,"SELECT *FROM tbl_remote_item_categories WHERE ItemTypeID='$ItemTypeID'") or die(mysqli_error($conn));
  
  if(mysqli_num_rows($sql_check_if_itemid_exist)){
      continue;
  }
  $result=mysqli_query($conn,"INSERT INTO tbl_remote_item_categories(ItemTypeID,TypeName) VALUES('$ItemTypeID','$TypeName')");
    
    if($result){
       // echo $i++."<br/>";
    }else{
        echo "fail";
    }  
}
//echo "nje";

if (!empty($Sponsor_ID)) {
    $sp_query = mysqli_query($conn,"SELECT Guarantor_name,Sponsor_ID,item_update_api,auto_item_update_api,api_item_package FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));

    $rowSp = mysqli_fetch_assoc($sp_query);
    $Guarantor_name = $rowSp['Guarantor_name'];
    $Sponsor_ID = $rowSp['Sponsor_ID'];
    $item_update_api = $rowSp['item_update_api'];
    $auto_item_update_api = $rowSp['auto_item_update_api'];
    $api_item_package = $rowSp['api_item_package'];

    $url = $item_update_api . 'FacilityCode=' . FacilityCode;
    //echo $url;exit;
    //echo "---->".$api_item_package."<----<br/>";
    if ($auto_item_update_api == '1') {
        Start_Transaction();
        $has_error = false;
        //update item  
      
        //$objs =  $manager->GetApiData($url);;
         $objs = $manager->GetPricePackage(FacilityCode);
//        echo '<pre>';
//        print_r($objs);exit;

        //mysqli_query($conn,"DELETE FROM tbl_item_update_tem WHERE sponsor_id='$Sponsor_ID'") or die(mysqli_error($conn));
        $decodedText = html_entity_decode($objs);
        $objs = json_decode($decodedText, true);
        
        if (count($objs) == 0) {
            echo 'Failed to get content from the API. Please make sure you have internet connection. If problem pesists, Contact system administrator.';
            exit;
        }
        
        foreach ($objs as $obj) {
            //echo $obj['ItemName'];
            
            if (empty($obj['ItemName']) || is_null($obj['ItemName']) || (!empty($api_item_package) && $obj['PackageID'] != $api_item_package)) {
                //echo "<br/>==>".$obj['ItemName']."==>".$obj['PackageID'];
                continue;
            }
           $ItemCode=$obj['ItemCode'];
           $ItemTypeID=$obj['ItemTypeID'];
           $ItemName=$obj['ItemName'];
           $PackageID=$obj['PackageID'];
           $Strength=$obj['Strength'];
           $Dosage=$obj['Dosage'];
           $UnitPrice=$obj['UnitPrice'];
           $IsRestricted=$obj['IsRestricted'];
           $MaximumQuantity=$obj['MaximumQuantity'];
           
            $sql = "INSERT INTO tbl_item_update_tem (sponsor_id,ItemCode, ItemTypeID, ItemName, PackageID, Strength, Dosage, UnitPrice, IsRestricted, MaximumQuantity) 
                    VALUES ( '$Sponsor_ID','$ItemCode', '$ItemTypeID', '" . mysqli_real_escape_string($conn,str_replace("'", '', $ItemName)) . "', '$PackageID', '" . mysqli_real_escape_string($conn,str_replace("'", '', $Strength)) . "',
                       '" . mysqli_real_escape_string($conn,str_replace("'", '', $Dosage)) . "', '$UnitPrice', '$IsRestricted', '$MaximumQuantity')";

            $query = mysqli_query($conn,$sql) or die("error".mysqli_error($conn));
            
            
//            echo $sql.'<br/>';

            if ($query) {
                //echo "inserted<br/>";
                $temp++;
            } else {
                echo 'Duplicate Item Found. Ignoring...........'; 
            }
        }

        if ($temp > 0) {
            $sql_delete_previous_items_price_result=mysqli_query($conn,"DELETE FROM tbl_item_price WHERE Sponsor_ID='$Sponsor_ID'") or die(mysqli_error($conn));
        
            $has_error = insertGetItem($Sponsor_ID);
        }

        if ($has_error) {
            Rollback_Transaction();
            echo 'An error has occured.No changes are made to the system.Please Try agin later';
        } else {
            Commit_Transaction();
            echo 'Items Updated Successifully';
        }
    } else {
        echo 'System not configured for auto item update from the sponsor';
    }
} else {
    echo 'Please select sponsor';
}

function insertGetItem($Sponsor_ID) {
    echo "ndani ya insert function<br/>";
	global $conn;
    $has_error = false;
    $query = mysqli_query($conn,"SELECT * FROM tbl_item_update_tem WHERE sponsor_id='$Sponsor_ID'");

    while ($row = mysqli_fetch_array($query)) {
        $getCategory = mysqli_query($conn,"SELECT ItemTypeID FROM tbl_remote_item_categories WHERE ItemTypeID='" . $row['ItemTypeID'] . "' ") or die(mysqli_error($conn));
        $remotCat = mysqli_fetch_assoc($getCategory);
        $ItemTypeID = $remotCat['ItemTypeID'];
        $sub_cat_id = getSubCategoryID($ItemTypeID);

        if (!is_numeric($sub_cat_id)) {
            $has_error = true;
            return $has_error;
        }

        $item_type = '';
        $Consultation_Type = '';
        $Classification = '';

//        if (strtolower($remotCat['ItemGroup']) == 'outpatient') {
//            $item_type = 'Pharmacy';
//            $Consultation_Type = 'Pharmacy';
//            $Classification = 'Pharmaceuticals';
//        } else {
//            $item_type = 'Service';
//            $Consultation_Type = $remotCat['ItemGroup'];
//        }

        ///////////////////////////////////this is for temporal use only it should be rewriten on next update
        if($ItemTypeID=="1"){
            $item_type = 'Service';
            $Consultation_Type = "Others";  
        }else if($ItemTypeID=="2"){
          $item_type = 'Service';
          $Consultation_Type = "Others";   
        }
        else if($ItemTypeID=="3"){
            $item_type = 'Pharmacy';
            $Consultation_Type = "Pharmacy"; 
        }
        else if($ItemTypeID=="4"){
           $item_type = 'Service';
            $Consultation_Type = "Surgery";  
        }
        else if($ItemTypeID=="5"){
           $item_type = 'Service';
            $Consultation_Type = "Laboratory";  
        }
        else if($ItemTypeID=="6"){
            $item_type = 'Service';
            $Consultation_Type = "Procedure"; 
        }
        else if($ItemTypeID=="7"){
            $item_type = 'Service';
            $Consultation_Type = "Others"; 
        }
        else if($ItemTypeID=="8"){
            $item_type = 'Service';
            $Consultation_Type = "Surgery"; 
        }
        else if($ItemTypeID=="10"){
            $item_type = 'Service';
            $Consultation_Type = "Procedure"; 
        }else{
            $item_type = 'Service';
            $Consultation_Type = "Others";
        }
        
        
        /////////////////////////
        
        $itemName = trim($row['ItemName'] . ' ' . $row['Strength']);
        $Product_Code=$row['ItemCode'];
//        $query_item = mysqli_query($conn,"SELECT Item_ID FROM tbl_items where Product_Name='$itemName'") or die(mysqli_error($conn));
        $query_item = mysqli_query($conn,"SELECT Item_ID FROM tbl_items where Product_Code='$Product_Code'") or die(mysqli_error($conn));
        echo "nje---------:<br/>:<--";
        if (mysqli_num_rows($query_item) > 0) {
            echo "ndani get item<br/>";
            $item_ID = mysqli_fetch_assoc($query_item)['Item_ID'];
        } else {
            echo "ndani_insert_item<br/>";
            $sql = "
            INSERT INTO tbl_items (Item_Type, Product_Code, Unit_Of_Measure, Product_Name, Item_Subcategory_ID, Consultation_Type,Classification) 
            VALUES ('$item_type', '" . $row['ItemCode'] . "', '" . $row['Dosage'] . "', '" . $itemName . "', '" . $sub_cat_id . "', '" . $Consultation_Type . "', '" . $Classification . "')
             ";

            $q1 = mysqli_query($conn,$sql) or die(mysqli_error($conn));

            if (!$q1) {
                $has_error = true;
            }

            $query_item = mysqli_query($conn,"SELECT Item_ID FROM tbl_items where Product_Code='$Product_Code'") or die(mysqli_error($conn));
            $item_ID = mysqli_fetch_assoc($query_item)['Item_ID'];
        }

        
        $has_error = update_multiprice($item_ID, $Sponsor_ID, $row['UnitPrice']);
    }

    return $has_error;
}

function getSubCategoryID($ItemTypeID) {
	global $conn;
    $query = mysqli_query($conn,"SELECT Item_Category_ID FROM tbl_item_category WHERE ItemTypeID='$ItemTypeID'");
    $has_error = false;
    $cat_id = '';
     
    if (mysqli_num_rows($query) > 0) {
        $cat_id = mysqli_fetch_assoc($query)['Item_Category_ID'];
        $sql_select_category_name_result=mysqli_query($conn,"SELECT *FROM tbl_remote_item_categories WHERE ItemTypeID='$ItemTypeID'") or die(mysqli_error($conn));
        $category = mysqli_fetch_assoc($sql_select_category_name_result)['TypeName'];
        
    } else {
        $sql_select_category_name_result=mysqli_query($conn,"SELECT *FROM tbl_remote_item_categories WHERE ItemTypeID='$ItemTypeID'") or die(mysqli_error($conn));
        $category = mysqli_fetch_assoc($sql_select_category_name_result)['TypeName'];
        $q1 = mysqli_query($conn,"INSERT INTO tbl_item_category (Item_Category_Name) VALUES('$category')") or die(mysqli_error($conn));

        if (!$q1) {
            $has_error = true;
        }

        $query2 = mysqli_query($conn,"SELECT Item_Category_ID FROM tbl_item_category WHERE ItemTypeID='$ItemTypeID'");
        $cat_id = mysqli_fetch_assoc($query2)['Item_Category_ID'];
    }

    $query_sub = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='$cat_id'");

    $sub_cat_id = '';
    if (mysqli_num_rows($query_sub) > 0) {
        $sub_cat_id = mysqli_fetch_assoc($query_sub)['Item_Subcategory_ID'];
    } else {
        $q1 = mysqli_query($conn,"INSERT INTO tbl_item_subcategory (Item_Subcategory_Name,Item_category_ID) VALUES('$category','$cat_id')") or die(mysqli_error($conn));

        if (!$q1) {
            $has_error = true;
        }

        $query_sub2 = mysqli_query($conn,"SELECT Item_Subcategory_ID FROM tbl_item_subcategory WHERE Item_Category_ID='$cat_id'");
        $sub_cat_id = mysqli_fetch_assoc($query_sub2)['Item_Subcategory_ID'];
    }

    if ($has_error) {
        return true;
    }

    return $sub_cat_id;
}

function update_multiprice($item_ID, $Sponsor_ID, $UnitPrice) {
    global $conn;
	$has_error = false;

    $query = mysqli_query($conn,"SELECT Items_Price FROM tbl_item_price WHERE Item_ID='$item_ID' AND  Sponsor_ID='$Sponsor_ID'");

    if (mysqli_num_rows($query) > 0) {
        $q1 = mysqli_query($conn,"UPDATE tbl_item_price SET Items_Price='$UnitPrice' WHERE Item_ID='$item_ID' AND  Sponsor_ID='$Sponsor_ID'");

        if (!$q1) {
            $has_error = true;
        }
    } else {
        $q1 = mysqli_query($conn,"INSERT INTO tbl_item_price (Item_ID,Sponsor_ID,Items_Price) VALUES('$item_ID','$Sponsor_ID','$UnitPrice')") or die(mysqli_error($conn));

        if (!$q1) {
            $has_error = true;
        }
    }

    return $has_error;
}

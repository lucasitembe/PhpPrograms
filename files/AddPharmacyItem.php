<?php
    include("./includes/connection.php");
    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = '';
    } 
    if($Payment_Item_Cache_List_ID != ''){
        $sql = "select ilc.Transaction_Type, pc.Registration_ID, ilc.Payment_Cache_ID from tbl_payment_cache pc, tbl_item_list_cache ilc where
                        pc.payment_cache_id = ilc.payment_cache_id and ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_array($result)){
            $Payment_Cache_ID = $row['Payment_Cache_ID'];
            $Registration_ID = $row['Registration_ID'];
            $Transaction_Type = $row['Transaction_Type']; 
        }
    }else{
        $Payment_Cache_ID = '';
        $Registration_ID = '';
        $Transaction_Type = ''; 
    }
    
    if($Payment_Item_Cache_List_ID != ''){
        $Remove_Item = "update tbl_item_list_cache set status = 'active', process_Status = 'inactive' where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'";
        if(!mysqli_query($conn,$Remove_Item)){
            mysqli_error($conn);
        }else{
            header("location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage");
        }
    }else{
        header("location: ./pharmacyworkspage.php?section=Pharmacy&Registration_ID=$Registration_ID&Transaction_Type=$Transaction_Type&Payment_Cache_ID=$Payment_Cache_ID&NR=True&PharmacyWorks=PharmacyWorksThisPage");
    }
?>
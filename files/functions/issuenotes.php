<?php
    include("./includes/connection.php");

    function Can_Edit_Issue_Note($Issue_ID) {
        $Can_Edit_Issue_Note = true;
        $Grn_Issue_Note = mysqli_query($conn,"SELECT * FROM tbl_grn_issue_note where Issue_ID = '".$Issue_ID."' limit 1") or die(mysqli_error($conn));
        $Grn_Issue_Note_No = mysqli_num_rows($Grn_Issue_Note);
        if($Grn_Issue_Note_No){
            while($Grn_Issue_Note_Data = mysqli_fetch_array($Grn_Issue_Note)){
                $Can_Edit_Issue_Note = false;
            }
        }
        return $Can_Edit_Issue_Note;
    }

    function Store_Order_Department_Issuing($Requisition_ID) {
        $Sub_Department = array( 'Sub_Department_Name' => 'Issuing Store', 'Sub_Department_ID'=>0 );
        $select_store_need = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from tbl_sub_department sd, tbl_requisition req where
							sd.Sub_Department_ID = req.Store_Issue and
								req.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select_store_need);
        if($num_rows > 0){
            while($row = mysqli_fetch_array($select_store_need)){
                $Sub_Department = array( 'Sub_Department_Name' => $row['Sub_Department_Name'],
                    'Sub_Department_ID' => $row['Sub_Department_ID'] );
            }
        }

        return $Sub_Department;
    }

    function Store_Order_Department_Requesting($Requisition_ID) {
        $Sub_Department = array( 'Sub_Department_Name' => 'Issuing Store', 'Sub_Department_ID'=>0 );
        $select_store_need = mysqli_query($conn,"select Sub_Department_Name, Sub_Department_ID from tbl_sub_department sd, tbl_requisition req where
                                sd.Sub_Department_ID = req.Store_Need and
                                    req.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
        $num_rows = mysqli_num_rows($select_store_need);
        if($num_rows > 0){
            while($row = mysqli_fetch_array($select_store_need)){
                $Sub_Department = array( 'Sub_Department_Name' => $row['Sub_Department_Name'],
                    'Sub_Department_ID' => $row['Sub_Department_ID'] );
            }
        }

        return $Sub_Department;
    }

    function Get_Active_Items_For_Issue_Note($Requisition_ID, $Issue_ID) {
        $Item_List = array();

        $Item_Result = Query_DB("SELECT Item_ID, Quantity_Issued,Requisition_Item_ID
                                            from tbl_requisition_items where
                                            Requisition_ID = '{$Requisition_ID}' and
                                            Issue_ID = '{$Issue_ID}' and
                                            Status = 'active'");
        $hasError = $Item_Result["error"];
        if (!$hasError) {
            $Item_List = $Item_Result["data"];
        } else {
            echo $Item_Result["errorMsg"];
        }

        return $Item_List;
    }

?>
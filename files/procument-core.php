<?php 
    session_start();
    include("./includes/connection.php");

    if(isset($_GET['request']) &&  $_GET['request'] == "load_store_order"){
        $output = "";
        $sql = "";
        $count = 1;
        $Start_Date = $_GET['Start_Date'];
        $user_End_Date = $_GET['End_Date'];

        $duration = -1;
        $Filter_Value = substr($user_End_Date,0,11);

        $mod_date=date_create($Filter_Value);
        date_sub($mod_date,date_interval_create_from_date_string("$duration days"));
        $End_Date =  date_format($mod_date,"Y-m-d");

        $dates_filter = ($_GET['status'] == "load") ? " AND " : "between '$Start_Date' and '$End_Date' AND"  ;

        $sql="SELECT so.Store_Order_ID,so.jobcard_ID, so.Approval_Date_Time, emp.Employee_Name,sd.Sub_Department_Name,so.Sub_Department_ID
              FROM tbl_store_orders so, tbl_employee emp, tbl_sub_department sd
              WHERE Order_Status = 'Approved' AND
                    emp.Employee_ID = so.Employee_ID AND
                    so.Sub_Department_ID = sd.Sub_Department_ID AND
                    so.Approval_Date_Time  $dates_filter
                    (SELECT count(*) FROM tbl_store_order_items soi
                    WHERE soi.Store_Order_ID = so.Store_Order_ID AND
                    Procurement_Status in ('active', 'selected') ) > 0
              ORDER BY Store_Order_ID DESC limit 100";


        $Approved_Store_Order_SQL = mysqli_query($conn,$sql) or die(mysqli_error($conn));

        $Approved_Store_Order_Num = mysqli_num_rows($Approved_Store_Order_SQL);
        if($Approved_Store_Order_Num > 0){
            while($row = mysqli_fetch_array($Approved_Store_Order_SQL)){
                $Sub_Department_ID=$row['Sub_Department_ID'];
                $Sub_Department_Name=$row['Sub_Department_Name'];
                
                $output .= '<tr>
                        <td style="text-align: center;padding:8px">'.$count++.'</td>
                        <td style="padding:8px">'.$row['Employee_Name'].'</td>
                        <td style="padding:8px">'.$row['Store_Order_ID'].'</td>
                        <td style="padding:8px">'.$row['jobcard_ID'].'</td>
                        <td style="padding:8px">'.$row['Approval_Date_Time'].'</td>
                        <td style="padding:8px">'.$row['Sub_Department_Name'].'</td>
                        <td style="padding:8px">'.$row['Employee_Name'].'</td>
                        <td style="text-align: center;padding:8px">
                            <a href="create_purchase_requisition.php?Store_Order_ID='.$row['Store_Order_ID'].'&Sub_Department_ID='.$Sub_Department_ID.'&Sub_Department_Name='.$Sub_Department_Name.'"class="art-button-green" target="_parent">
                                CREATE PURCHASE REQUISITION <b>(PR)</b>
                            </a>
                            <a href="Control_Purchase_Order_Sessions.php?Store_Order_ID='.$row['Store_Order_ID'].
                                '&Single_Supplier=True&Selected_Store_Order=True"
                                class="art-button-green" target="_parent" style="display:none">
                                PO Single Supplier
                            </a>
                            <a href="Control_Purchase_Order_Sessions.php?Store_Order_ID='.$row['Store_Order_ID'].
                                '&Multi_Supplier=True&Selected_Store_Order=True"
                                class="art-button-green" target="_parent" style="display:none">
                                PO Multi Supplier
                            </a>
                            <a href="previousstoreorderreport.php?Store_Order_ID='.$row['Store_Order_ID'].
                                '&PreviousStoreOrder=PreviousStoreOrderThisPage" class="art-button-green" target="_blank">
                                PREVIEW
                            </a>
                    </td>
                </tr>';
            }
        }
        echo $output;
    }

    if(isset($_GET['status']) && $_GET['status'] == "loading-waiting-requistions"){
        $output = "";
        $count = 1;

        $Query = "SELECT reference_document,pr.purchase_requisition_id,pr.Store_Order_ID,pr.purchase_requisition_description,pr.created_date_time,emp.Employee_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_purchase_requisition pr,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE pr.employee_creating=emp.Employee_ID AND pr.Supplier_ID=0 AND pr.store_requesting=sd.Sub_Department_ID AND pr.pr_status='waiting_supplier' GROUP BY purchase_requisition_id ORDER BY purchase_requisition_id DESC";

        $select_waiting_supplier_purchase_requisition = mysqli_query($conn,$Query) or die(mysqli_errno($conn));

        if(mysqli_num_rows($select_waiting_supplier_purchase_requisition) > 0){
            while($pr_rows = mysqli_fetch_assoc($select_waiting_supplier_purchase_requisition)){
                $Store_Order_ID=$pr_rows['Store_Order_ID'];
                $purchase_requisition_description=$pr_rows['purchase_requisition_description'];
                $created_date_time=$pr_rows['created_date_time'];
                $Employee_Name=$pr_rows['Employee_Name'];
                $purchase_requisition_id=$pr_rows['purchase_requisition_id'];
                $Sub_Department_Name=$pr_rows['Sub_Department_Name'];
                $Sub_Department_ID=$pr_rows['Sub_Department_ID'];
                $reference_document=$pr_rows['reference_document'];
                $attachment="";
                if($reference_document!=""){
                    $attachment="<a href='attachment/$reference_document' target='_blank' class='art-button-green'>Preview</a>";
                }else{
                    $attachment = "<span style='color:green;font-weight:500'>No Reference Document</span>";
                }

                $output .= "<tr>
                    <td style='padding:8px;text-align:center'>".$count++."</td>
                    <td style='padding:8px'><center>$Store_Order_ID</center></td>
                    <td style='padding:8px'><center>$purchase_requisition_id</center></td>
                    <td style='padding:8px'>$created_date_time</td>
                    <td style='padding:8px;color:green'>Not Provided</td>
                    <td style='padding:8px'>$Sub_Department_Name</td>
                    <td style='padding:8px'>$Employee_Name</td>
                    <td style='padding:8px'>$purchase_requisition_description</td>
                    <td style='text-align:center;padding:8px'>$attachment</td>
                    <td style='text-align:center;font-weight:500;padding:8px'><a href='view_waiting_supplier.php?purchase_requisition_id=$purchase_requisition_id' class='art-button-green' >Provide Supplier</a></td>
                </tr>";
            }
        }else{
            $output = "
                <tr>
                    <td style='color:red;text-align:center;padding:10px' colspan='10'>No Requistion Without Supplier Found</td>
                </tr>
            ";
        }
        echo $output;
    }

    if(isset($_POST['submit_supplier'])){
        $purchase_requisition_id = $_POST['purchase_requisition_id'];
        $Supplier_ID = $_POST['Supplier_ID'];

        $Query = "UPDATE tbl_purchase_requisition SET Supplier_ID = '$Supplier_ID',pr_status = 'active' WHERE purchase_requisition_id = '$purchase_requisition_id'";
        $update_pr_status_and_supplier = mysqli_query($conn,$Query) OR die(mysqli_errno($conn));
        echo (!$update_pr_status_and_supplier) ? 'samething went wrong try again' : 1;
    }

    if(isset($_POST['auto_create_new_order'])){
        include_once("./functions/items.php");

        $itemsId_to_be_created = explode(",",$_POST['itemsId_to_be_created']);
        $item_per_container_count = explode(",",$_POST['item_per_container_count']);
        
        $quantity_required = explode(",",$_POST['quantity_required']);
        $container_qty = explode(',',$_POST['container_qty_count']);
        $Employee_ID = $_POST['employee_ID'];
        $purchase_requisition_id = $_POST['purchase_requisition_id'];
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
        $Order_Description = "Recreated order";
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        $Store_ID = "";
        array_pop($itemsId_to_be_created);

        #create store that wil contains the items
        $new_order_query = "INSERT INTO tbl_store_orders(Order_Description,Sent_Date_Time,Created_Date_Time,Created_Date,Sub_Department_ID,Employee_ID,Branch_ID,prepared)
                            VALUES('$Order_Description',(select now()),(select now()),(select now()),'$Sub_Department_ID','$Employee_ID','$Branch_ID','automatic')";
        $create_new_order = mysqli_query($conn,$new_order_query) OR die(mysqli_errno($conn));

        if($create_new_order){
            $sql_get_last_auto_order_created = "SELECT Store_Order_ID FROM tbl_store_orders 
                                                WHERE Sub_Department_ID = '$Sub_Department_ID' AND Employee_ID = '$Employee_ID' AND Order_Status = 'pending' AND Control_Status IN ('available','pending') AND prepared = 'automatic'";
            $Store_Order_ID = mysqli_fetch_assoc(mysqli_query($conn,$sql_get_last_auto_order_created))['Store_Order_ID'];
            $Store_ID = $Store_Order_ID;

            for($i = 0;$i < sizeof($itemsId_to_be_created);$i++){
                $Last_Buying_Price = ($last_buying_prices > 0) ? $last_buying_prices : 0;

                $sql_attach_items_to_the_orders = "INSERT INTO tbl_store_order_items(Store_Order_ID,Quantity_Required,Item_Remark,Item_ID,Container_Qty,Items_Qty,Last_Buying_Price)
                                                   VALUES('$Store_Order_ID','".$quantity_required[$i]."','from recreated order','".$itemsId_to_be_created[$i]."','".$container_qty[$i]."','".$item_per_container_count[$i]."','$Last_Buying_Price')";
                $attach_items_to_the_orders = mysqli_query($conn,$sql_attach_items_to_the_orders) or die(mysqli_errno($conn));

                $query_discard_item = "UPDATE tbl_purchase_requisition_items SET item_status = 'DISCARD' WHERE purchase_requisition_id = '$purchase_requisition_id' AND Item_ID = '$itemsId_to_be_created[$i]'";
                $update_items_to_discard_status = mysqli_query($conn,$query_discard_item) or die(mysqli_errno($conn));
            }
        }

        # submit the order
        $query_submit_order = "UPDATE tbl_store_orders SET Order_Status = 'Approved', Approval_Date_Time = (select now()), Supervisor_ID = '$Employee_ID' WHERE Store_Order_ID = '$Store_ID'";
        $update_order_to_approved = mysqli_query($conn,$query_submit_order) or die(mysqli_errno($conn));

        echo (!$update_order_to_approved) ? "samething went try again later" : 1;
    }

    if(isset($_GET['load_orders_to_transfer'])){
        $count=1;
        $query_load_orders_to_transfer = "SELECT lpo.local_purchase_order_id,lpo.purchase_requisition_id,lpo.Store_Order_ID,lpo.purchase_requisition_description,lpo.created_date_time,emp.Employee_Name,sup.Supplier_Name,sd.Sub_Department_Name,sd.Sub_Department_ID FROM tbl_local_purchase_order lpo,tbl_employee emp,tbl_supplier sup,tbl_sub_department sd WHERE lpo.employee_creating=emp.Employee_ID AND lpo.Supplier_ID=sup.Supplier_ID AND lpo.transfer_status = 'not_transferred' AND lpo.store_requesting=sd.Sub_Department_ID  ORDER BY purchase_requisition_id DESC";

        $load_orders_to_transfer = mysqli_query($conn,$query_load_orders_to_transfer) or die(mysqli_errno($conn));
        if(mysqli_num_rows($load_orders_to_transfer) > 0){
            while($lpo_rows = mysqli_fetch_assoc($load_orders_to_transfer)){
                $Store_Order_ID=$lpo_rows['Store_Order_ID'];
                $purchase_requisition_description=$lpo_rows['purchase_requisition_description'];
                $created_date_time=$lpo_rows['created_date_time'];
                $Employee_Name=$lpo_rows['Employee_Name'];
                $Supplier_Name=$lpo_rows['Supplier_Name'];
                $purchase_requisition_id=$lpo_rows['purchase_requisition_id'];
                $local_purchase_order_id=$lpo_rows['local_purchase_order_id'];
                $Sub_Department_Name=$lpo_rows['Sub_Department_Name'];
                $Sub_Department_ID=$lpo_rows['Sub_Department_ID'];

                echo "<tr style='background-color:white'>
                        <td style='text-align:center'>$count</td>
                        <td><center>$Store_Order_ID</center></td>
                        <td><center>$purchase_requisition_id</center></td>
                        <td><center>$local_purchase_order_id</center></td>
                        <td>$created_date_time</td>
                        <td>$Supplier_Name</td>
                        <td>$Sub_Department_Name</td>
                        <td>$Employee_Name</td>
                        <td>$purchase_requisition_description</td>
                        <td><a href='procurement_recieving_and_transfer.php?purchase_requisition_id=$purchase_requisition_id&local_purchase_order_id=$local_purchase_order_id' class='art-button-green' >Recieve And Transfer</a></td>
                    </tr>";
                $count++;
            }
        }else{
            echo "<tr style='background-color:white'>
                    <td style='text-align:center' style='text-align:center;' colspan='10'>Not order to be transferred found </td>
                  </tr>
                ";
        }
    }


    if(isset($_POST['verify_and_transfer_lpo'])){
        $verify_and_transfer_lpo = $_POST['verify_and_transfer_lpo'];
        $Employee_ID = $_POST['Employee_ID'];
        $local_purchase_order_id = $_POST['local_purchase_order_id'];
        $recieving_store = $_POST['recieving_store'];
        $output = "";

        $query_update_lpo = "UPDATE tbl_local_purchase_order SET transfer_status = 'transferred',store_recieving = '$recieving_store',transfer_by = '$Employee_ID' WHERE local_purchase_order_id = '$local_purchase_order_id'";
        $update_transfer_status = mysqli_query($conn,$query_update_lpo) or die(mysqli_error($conn));

        if($update_transfer_status){
            $output = "Transfer Successful";
        }else{
            $output = "Something went contact the system administrator for help";
        }
        echo $output;
    }

    if(isset($_POST['Items_Price'])){
        $Items_Price = $_POST['Items_Price'];
        $purchase_requisition_id = $_POST['purchase_requisition_id'];
        $Item_ID = $_POST['Item_Id'];
        $query_update_price = "UPDATE tbl_purchase_requisition_items SET buying_price = '$Items_Price' WHERE purchase_requisition_id = '$purchase_requisition_id' AND Item_ID = '$Item_ID'";

        $update_prices = mysqli_query($conn,$query_update_price) or die(mysqli_errno($conn));
        if(!$update_prices){
            echo "Not Update";
        }else{
            echo "Updated";
        }
    }

    if(isset($_GET['loadPharmacyItems']) || isset($_GET['search_by_item_name'])){
        $items_array = array();
        $routes_array = array();
        $output = "";
        $count = 1;
        $routesItems = "";

        $getRoute = mysqli_query($conn,"SELECT * FROM tbl_items_routes") or die(mysqli_errno($conn));

        if($_GET['search_by_item_name'] == 'search_by_item_name'){
            $fetch_items = mysqli_query($conn,"SELECT Item_ID,Item_Type,Product_Code,Unit_Of_Measure,Product_Name,Consultation_Type,route_type FROM tbl_items WHERE Product_Name LIKE '%".mysqli_real_escape_string($conn,$_GET['item_name'])."%' ORDER BY  Item_ID ASC LIMIT 15") OR die(mysqli_errno($conn));
        }else if($_GET['item_name'] == ""){
            $fetch_items = mysqli_query($conn,"SELECT Item_ID,Item_Type,Product_Code,Unit_Of_Measure,Product_Name,Consultation_Type,route_type FROM tbl_items ORDER BY  Item_ID ASC LIMIT 15") OR die(mysqli_errno($conn));
        }else{
            $fetch_items = mysqli_query($conn,"SELECT Item_ID,Item_Type,Product_Code,Unit_Of_Measure,Product_Name,Consultation_Type,route_type FROM tbl_items ORDER BY  Item_ID ASC LIMIT 15") OR die(mysqli_errno($conn));
        }

        while($data = mysqli_fetch_assoc($fetch_items)){ array_push($items_array,$data); }
        while($route = mysqli_fetch_assoc($getRoute)){ array_push($routes_array,$route); }

        for($i = 0;$i < sizeof($routes_array);$i++){
            $routesItems .=" <option value='".$routes_array[$i]['route_name']."'>".$routes_array[$i]['route_name']."</option> ";
        }


        for($i=0;$i < sizeof($items_array);$i++){
            $output .= "
                <tr style='background-color:white'>
                    <td><center>".$count++."</center></td>
                    <td>".$items_array[$i]['Product_Code']." ~ (".($items_array[$i]['Unit_Of_Measure'] == '' ? '<b>Not Provided</b>' : $items_array[$i]['Unit_Of_Measure']).")</td>
                    <td>".$items_array[$i]['Consultation_Type']."</td>
                    <td>".$items_array[$i]['Product_Name']."</td>
                    <td>
                        <center>
                            <select id='".$items_array[$i]['Item_ID']."' onchange='changeRouteType(".$items_array[$i]['Item_ID'].")'>
                                ".($items_array[$i]['route_type'] == "" ? '<option>Select Route</option>' : '<option>'.$items_array[$i]['route_type'].'</option><option value="">None</option>')."
                                ".$routesItems."
                            </select>
                        </center>
                    </td>
                </tr>
            ";
        }

        echo $output;
    }

    if(isset($_GET['routeDialogue'])){
        $route_array = array();
        $output = "";
        $count = 1;
        $fetch_routes = mysqli_query($conn,"SELECT * FROM tbl_items_routes") or die(mysqli_errno($conn));

        while($data = mysqli_fetch_assoc($fetch_routes)){ array_push($route_array,$data); }

        for($i = 0;$i < sizeof($route_array);$i++){
            $output .= "
                <tr>
                    <td><center>".$count++."</center></td>
                    <td>".$route_array[$i]['route_name']."</td>
                    <td><a href='#' class='art-button-green' onclick='deleteRoute(".$route_array[$i]['id'].")'>REMOVE<a/></td>
                </tr>
            ";
        }
        echo $output;
    }

    if(isset($_POST['newRouteAdded'])){
        $output = "";
        $sql = "INSERT INTO tbl_items_routes (route_name) VALUES ('".mysqli_real_escape_string($conn,$_POST['newRoute'])."')";
        $Query = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
        
        if(!$Query){
            $output = "Error found, please contact administrator for support";
        }else{
            $output = "Route Added Successfully";
        }

        echo $output;
    }

    if(isset($_POST['deleteItemRoute'])){
        $output = "";
        $sql = "DELETE FROM tbl_items_routes WHERE id = ".$_POST['routeId']."";
        $Query = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
        
        if(!$Query){
            $output = "Error found, please contact administrator for support";
        }else{
            $output = "Route Deleted Successfully";
        }
        echo $output;
    }

    if(isset($_POST['changeValue'])){
        $changed_value = $_POST['changedValue'];
        $ItemId = $_POST['ItemId'];

        $sql = "UPDATE tbl_items SET route_type = '$changed_value' WHERE Item_ID = '$ItemId'";

        $Query = mysqli_query($conn,$sql) or die(mysqli_error($conn)); 
        
        if(!$Query){
            $output = "Error found, please contact administrator for support";
        }else{
            $output = "Route Update Successfully";
        }
        echo $output;
    }
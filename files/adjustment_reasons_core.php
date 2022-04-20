<?php 
    include './includes/connection.php';

    if(isset($_GET['load_reasons'])){
        $get_reasons = mysqli_query($conn,"SELECT * FROM tbl_adjustment") or die(mysqli_errno($conn));
        $out = "";
        $option = "";
        $count = 1;
        while($data = mysqli_fetch_assoc($get_reasons)){
            $option = ($data['enable_disable'] == "") ? "<option value=''>Select Action</option>" : "<option value='".$data['enable_disable']."' selected='selected'>".$data['enable_disable']."</option>";

            $output .= "<tr style='background-color:#fff'>
                <td style='padding: 10px;'><center>".$count++."</center></td>
                <td style='padding: 10px;'>".$data['name']."</td>
                <td style='padding: 10px;'>
                    <select>
                        <option value='".$data['nature']."'>".$data['nature']."</option>
                    </select>
                </td>
                <td style='padding: 10px;'>
                    <select id='".$data['id']."' onchange='enable_disable(".$data['id'].")'>
                        ".$option."
                        <option value='enable'>enable</option>
                        <option value='disable'>disable</option>
                    </select>
                </td>
            </tr>";
        }
        echo $output;
    }

    if(isset($_POST['availability'])){
        $id = $_POST['id'];
        $enable_disable = $_POST['enable_disable'];

        if(mysqli_query($conn,"UPDATE tbl_adjustment SET enable_disable = '$enable_disable' WHERE id = $id")){
            echo "Updated Successfull";
        }else{
            echo "Not updated contact admin for support";
        }
    }

    if(isset($_POST['add_reason'])){
        $name = $_POST['name'];
        $nature = $_POST['nature'];

        if(mysqli_query($conn,"INSERT tbl_adjustment(name,nature,enable_disable) VALUES('$name','$nature','enable') ")){
            echo "Added Successfull";
        }else{
            echo "Not updated contact admin for support";
        }
    }

    if(isset($_GET['filter_by_items'])){
        $output = '';
        $counter = 1;
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $search_name = $_GET['search_name'];
        $Current_Store_ID = $_GET['Current_Store_ID'];
        $reason_id = $_GET['reason_id'];

        $filter_reason = $_GET['reason_id'] == 'all' ? "" : " AND ta.id = $reason_id";
        $filter_product_name = $_GET['search_name'] == '' ? "" : " AND ti.Product_Name LIKE '%$search_name%'";

        $get_adjustments = mysqli_query($conn,"SELECT Product_Name,td.Disposed_Date,name,tdi.Quantity_Disposed,te.Employee_Name,Disposal_Description FROM tbl_stock_ledger_controler AS tslc, tbl_disposal AS td,tbl_items AS ti,tbl_disposal_items AS tdi,tbl_adjustment as ta,tbl_employee AS te WHERE tslc.Movement_Type = 'ADJUSTMENT' AND tslc.Sub_Department_ID = '$Current_Store_ID' AND ti.`Item_ID`= tdi.`Item_ID` AND td.Disposal_ID = tdi.Disposal_ID AND ta.id = td.reason_for_adjustment AND te.Employee_ID = td.Employee_ID AND tslc.Item_ID = tdi.Item_ID AND Disposed_Date BETWEEN '$start_date' AND '$end_date' $filter_reason $filter_product_name ") or die(mysqli_errno($conn));

        if(mysqli_num_rows($get_adjustments) > 0){
            while($data = mysqli_fetch_assoc($get_adjustments)){
                $output .= '
                    <tr style="background-color: #fff;">
                        <td style="padding: 8px;"><center>'.$counter++.'</center></td>
                        <td style="padding: 8px;">'.$data['Product_Name'].'</td>
                        <td style="padding: 8px;">'.$data['Disposed_Date'].'</td>
                        <td style="padding: 8px;">'.$data['name'].'</td>
                        <td style="padding: 8px;"><center>'.$data['Quantity_Disposed'].'</center></td>
                        <td style="padding: 8px;">'.$data['Employee_Name'].'</td>
                        <td style="padding: 8px;">'.$data['Disposal_Description'].'</td>
                    </tr>
                ';
            }
        }else{
            $product_name = strlen($filter_product_name) > 0 ? " for ".$_GET['search_name'] : "";
            $output .= '<tr style="background-color: #fff;">
                        <td colspan="8" style="padding: 8px;text-align:center;font-weight:bold">No Data Found From '.$start_date.' To '.$end_date.$product_name.'</td>
                    </tr>';
        }

        echo $output;;
    }
?>
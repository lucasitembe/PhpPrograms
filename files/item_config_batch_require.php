<?php
    include("./includes/connection.php");

    # Load 15 defaut items
    if(isset($_GET['request']) == 'load_data'){
        $fetch_items = mysqli_query($conn,"SELECT Item_ID,Item_Type,Product_Name,exp_batch_req,Consultation_Type FROM tbl_items ORDER BY  Item_ID ASC LIMIT 15") OR die(mysqli_errno($conn));
        $output = "";
        $counter = 1;

        if(mysqli_num_rows($fetch_items) > 0){
            while($data = mysqli_fetch_assoc($fetch_items)){

                $check_uncheck = ($data['exp_batch_req'] == "yes") ? "checked='checked'" : "";
                $check = ($data['exp_batch_req']     == "yes") ? "yes" : "no";

                $output .= "
                    <tr>
                        <td style='padding: 8px;text-align:center'>".$counter++."</td>
                        <td style='padding: 8px;'>".$data['Item_Type']."</td>
                        <td style='padding: 8px;'>".$data['Consultation_Type']."</td>
                        <td style='padding: 8px;'>".$data['Product_Name']."</td>
                        <td style='padding: 8px;text-align:center'>
                            <input type='checkbox' $check_uncheck id='".$data['Item_ID']."' value='$check' onclick='check_uncheck(".$data['Item_ID'].")'>
                        </td>
                    </tr>
                ";
            }
        }else{
            $output .= "
                <tr><td colspan='4' style='font-weight:bold'>No Items Found</td></tr>
            "; 
        }

        echo $output;
    }

    # Filter by item name
    if(isset($_GET['seach_items'])){
        $counter = 1;
        $item_name = $_GET['item_name'];
        $fetch_items = mysqli_query($conn,"SELECT Item_ID,Item_Type,Product_Name,exp_batch_req,Consultation_Type FROM tbl_items WHERE Product_Name LIKE '%$item_name%' ORDER BY  Item_ID ASC LIMIT 15") OR die(mysqli_errno($conn));

        if(mysqli_num_rows($fetch_items) > 0){
            while($data = mysqli_fetch_assoc($fetch_items)){

                $check_uncheck = ($data['exp_batch_req'] == "yes") ? "checked='checked'" : "";
                $check = ($data['exp_batch_req']     == "yes") ? "yes" : "no";

                $output .= "
                    <tr>
                        <td style='padding: 8px;text-align:center'>".$counter++."</td>
                        <td style='padding: 8px;'>".$data['Item_Type']."</td>
                        <td style='padding: 8px;'>".$data['Consultation_Type']."</td>
                        <td style='padding: 8px;'>".$data['Product_Name']."</td>
                        <td style='padding: 8px;text-align:center'>
                            <input type='checkbox' $check_uncheck id='".$data['Item_ID']."' value='$check' onclick='check_uncheck(".$data['Item_ID'].")'>
                        </td>
                    </tr>
                ";
            }
        }else{
            $output .= "
                <tr><td colspan='5' style='font-weight:bold;padding:8px;text-align:center'>No Items Found</td></tr>
            "; 
        }

        echo $output;
    }

    if(isset($_POST['btn_check_uncheck'])){
        $Item_Id = $_POST['Item_Id'];
        $append_value = $_POST['append_value'];

        $update = mysqli_query($conn,"UPDATE tbl_items SET exp_batch_req = '$append_value' WHERE Item_ID = '$Item_Id'") or die(mysqli_error($conn));

        if(!$update){
            die('something went wrong');
        }
    }
?>

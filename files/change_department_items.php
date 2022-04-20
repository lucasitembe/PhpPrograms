<?php 
    include "./includes/connection.php";
    $Payment_Cache_Id = (isset($_GET['Payment_Cache_Id'])) ? $_GET['Payment_Cache_Id'] : 0;
    $Sub_Department_id = (isset($_GET['Sub_Department_id'])) ? $_GET['Sub_Department_id'] : 0;
    $output = "";
    $output_row = "";
    $count = 1;

    $select_item_from = mysqli_query($conn,"SELECT ilc.Item_ID,i.Product_Name,ilc.`Sub_Department_ID` FROM `tbl_item_list_cache` as ilc, tbl_items AS i WHERE ilc.`Payment_Cache_ID` = '$Payment_Cache_Id' AND i.Item_ID = ilc.Item_ID AND ilc.`Sub_Department_ID` = '$Sub_Department_id' AND (ilc.Status ='active' OR ilc.Status ='approved') AND ilc.Check_In_Type='Pharmacy'");
    if(mysqli_num_rows($select_item_from) > 0){
        while($data = mysqli_fetch_array($select_item_from)){
            $Product_Name = $data['Product_Name'];
            $Item_ID = $data['Item_ID'];

            $select_departments = mysqli_query($conn,"SELECT tsd.Sub_Department_Name,tsd.Sub_Department_ID FROM tbl_sub_department AS tsd, tbl_department as td
            WHERE tsd.Department_ID = td.Department_ID AND td.Department_Location = 'Pharmacy'");
            while($row = mysqli_fetch_array($select_departments)){
                $Sub_Department_Name = $row['Sub_Department_Name'];
                $Sub_Department_ID = $row['Sub_Department_ID'];
                $option = $Sub_Department_ID.",".$Item_ID;
        
                $output_row .= "
                    <option value=".$option.">".$Sub_Department_Name."</option>
                ";
            }
        

            $output .= "
                <tr>
                    <td style='text-align:center'>".$count++."</td>
                    <td>".$Product_Name."</td>
                    <td>
                        <input type='hidden' value='".$Item_ID."'/>
                        <select class='change_department'>
                            ".$output_row."
                        </select>
                    </td>
                </tr>
            ";
            
            $output_row ="";
        }
    }else{
        $output .="
            <tr>
                <td colspan='3' style='text-align:center;color:red'>
                    <p>No Data Found</p>
                </td>
            </tr>
        ";
    }
?>

<style>
    #change_department_table{
        border: 1px solid #ccc !important;
    }

    #change_department_table tr, td{
        border: 1px solid #ccc ;
    } 
</style>

<table id="change_department_table" style="width:100%">
    <tr style="background-color: #eee;padding:15px;">
        <td width='10%' style="text-align: center;padding:5px;border:1px solid #ccc;padding:8px">S/N</td>
        <td>Items Name</td>
        <td width='25%'>Action</td>
    </tr>

    <?=$output?>
</table>

<script>
    $(document).ready(() => {
        $("select.change_department").change(function() {
            var selected_department_id = $(this).children("option:selected").val();
            var selected_department_name = $(this).children("option:selected").text();
            var Payment_Cache_Id = '<?=$Payment_Cache_Id?>';
            var change_department = "change_department";


            if(confirm("Are you sure you want item department for the patient")){
                $.ajax({
                    type: "GET",
                    url: "phamacy_handle.php",
                    data: {
                        selected_department_id:selected_department_id,
                        Payment_Cache_Id:Payment_Cache_Id,
                        change_department:change_department
                    },
                    cache:false,
                    success: (response) => {
                        alert(response)
                    }
                });
            }
        });
    })
</script>
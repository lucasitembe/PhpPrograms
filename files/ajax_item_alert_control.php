<?php
 include("./includes/connection.php");

$sponsor_id=$_POST['sponsor_id'];

if(isset($_POST['serch_item'])){
    $Product_Name = str_replace(" ", "%", $_POST['serch_item']);
}else{
    $Product_Name = '';
}
if(isset($_POST['Consultation_Type'])){
    $Consultation_Type = $_POST['Consultation_Type'];
}else{
    $Consultation_Type = '';
}

$html.='<div id="items_list_table">
                    <table class="table table-bordered" >
                        <tr>
                            <th width="3%">S/N</th>
                            <th> ITEM NAME </th>
                            <th width="20%"> MIN DURATION <br> ( <i> only days allowed </i> ) </th>
                            <th style="text-align:center" width="3%"> ACTION </th>
                        </tr>';
                            $i=0;
                            $result = mysqli_query($conn,"SELECT * FROM tbl_items it, tbl_item_price pr WHERE it.Item_ID=pr.Item_ID and it.Consultation_Type='$Consultation_Type' AND it.Product_Name LIKE '%$Product_Name%' AND pr.sponsor_id='$sponsor_id' LIMIT 100");
                        if(mysqli_num_rows($result)>0){
                            while($items = mysqli_fetch_assoc($result) ){
                                $item_id = $items['Item_ID'];

                        $html.='<tr>
                            <td> '.($i+1) .' </td>
                            <td> '.$items['Product_Name'].' </td>
                            <td style="text-align:center;" >';

                        $result2 = mysqli_query($conn,"SELECT * FROM tbl_items_alert_control WHERE item_id='$item_id' AND sponsor_id='$sponsor_id'");
                            while($items2 = mysqli_fetch_assoc($result2) ){$alert_item_id=$items2['item_id']; $item_alert_control_id=$items2['item_alert_control_id'];$alert_duration=$items2['duration'];}
                            if($alert_item_id==$item_id){$duration=$alert_duration;}else{$duration="";}  
                        $html.='
                            <input style="max-width:50px;text-align:center;" type="text" name="duration" class="" id="duration-'.$item_id.'" value="'. $duration .'">
                             DAYS 
                            </td>
                            <td> 
                                <button value="'.$item_id.'" onclick="save_alert_control(this.value)" class="btn" style="background-color:#006400;padding:4px;color:#fff; max-height:25px;">SAVE</button>    
                            </td>
                        </tr>';
                       $i++; }
                    }else{
                        $html.= '<tr><th style="color:red;" colspan="4">NO ITEM FOUND!</th></tr>';
                    } 
                    $html.='</table>
                        </div>';

        echo $html;
        ?>

<?php

 include("./includes/connection.php");
 
if(isset($_POST['search_free_item_value'])){
   $search_free_item_value=$_POST['search_free_item_value'];
   $search_free_item_value = str_replace(" ", "%", $search_free_item_value);
}else{
    $search_free_item_value="";
}

$html = '<div id="free_items_list">
    <table class="table">';
    
        $get_sponsors = mysqli_query($conn,"SELECT DISTINCT f.sponsor_id, s.Guarantor_Name FROM tbl_free_items AS f, tbl_sponsor AS s WHERE f.sponsor_id = s.Sponsor_ID AND s.Guarantor_Name like '%$search_free_item_value%'");

        if(mysqli_num_rows($get_sponsors)>0){
        while ($row1=mysqli_fetch_array($get_sponsors)) {
            $sponsor_id = $row1['sponsor_id'];
    
    $html.='    <tr style="border:2px solid #328CAF!important;background: #C0C0C0;">
            <th colspan="2">'.$row1['Guarantor_Name']. '</th>
        </tr>';
    
     $get_free_items = mysqli_query($conn,"SELECT free_item_id, item_id FROM tbl_free_items WHERE sponsor_id='$sponsor_id'  ORDER BY item_id ASC");
     $i=1;
     while ($row2=mysqli_fetch_array($get_free_items)) { 
         $item_id = $row2["item_id"];
         $item_name = mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id' AND Consultation_Type='Laboratory'");
         $row3 = mysqli_fetch_array($item_name);
         
         $item_name = $row3['Product_Name'];
 $html.=' 
        <tr>
            <td width="1%" style="border:1px solid #328CAF!important;">'. $i .'</td>
            <td width="99%" style="border:1px solid #328CAF!important;">
                <label for="free_item_id_'.$item_id.'"  style="font-weight:normal">
                    <input type="checkbox" id="free_item_id'.$item_id.'" class="free_item_id" value="'. $row2['free_item_id'] .'"> '. $row3['Product_Name'] .'
                </label>
            </td>
        </tr>';
     $i++;}
    }}else{
        $html.="<tr><td><label style='color:red;'> NO, ITEM FOUND! </label></td></tr>";
    }
     $html.=' </table>
                </div>';

echo $html;
<?php
session_start();
include("./includes/connection.php");

if(isset($_POST['Requisition_Item_ID'])){
    $Requisition_Item_ID=$_POST['Requisition_Item_ID'];
}

if(isset($_POST['Issue_ID'])){
    $Issue_ID=$_POST['Issue_ID'];
}


$result=mysqli_query($conn,"SELECT Requisition_Item_ID FROM tbl_issuemanual_items WHERE Issue_ID='$Issue_ID'") or die(mysqli_error($conn));

if(mysqli_num_rows($result) > 1){
    $result=mysqli_query($conn,"DELETE FROM tbl_issuemanual_items WHERE Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
    $status= 0;
}  else {
    mysqli_query($conn,"DELETE FROM tbl_issuemanual_items WHERE Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
    mysqli_query($conn,"DELETE FROM tbl_issuesmanual WHERE Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
    $status= 1;
    
    if(isset($_SESSION['IssueManual_ID'])){
        unset($_SESSION['IssueManual_ID']);
    }
}

 $select_Issue_Note = mysqli_query($conn,"select Quantity_Required, itm.Product_Name, Quantity_Issued, Item_Remark,Requisition_Item_ID
                                                    from tbl_issuemanual_items isi, tbl_items itm where
                                                        itm.Item_ID = isi.Item_ID and
                                                            isi.Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
 $data = '<center><table width = 100% border=0>';
                    $data .= '<tr>
                              <td width=4% style="text-align: center;">Sn</td>
                              <td width=40%>Item Name</td>
                              <td width=13% style="text-align: center;">Quantity Required</td>
                              <td width=13% style="text-align: center;">Quantity Issued</td>
                              <td width=25% style="text-align: center;">Remark</td>
                              <td style="text-align: center; width: 7%;">Remove</td>
                            </tr>';
    
    $Temp = 1;
    
            while ($row = mysqli_fetch_array($select_Issue_Note)) {
                $data .= "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
                $data .= "<td><input type='text' readonly='readonly' value='" . $row['Product_Name'] . "'></td>";
                $data .= "<td><input type='text' value='" . $row['Quantity_Required'] . "' style='text-align: center;'></td>";
                $data .= "<td><input type='text' value='" . $row['Quantity_Issued'] . "' style='text-align: center;'></td>";
                $data .= "<td><input type='text' value='" . $row['Item_Remark'] . "'></td>";
                $data .= "<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item(\"".str_replace("'", "", $row['Product_Name'])."\", ".$row['Requisition_Item_ID'].",".$Issue_ID.")'></td>";
                $data .= "</tr>";
                $Temp++;
            }
           
            $data .= '</table>';
    
    
    echo $status.'gpitgtendanisha'.$data;

    
    

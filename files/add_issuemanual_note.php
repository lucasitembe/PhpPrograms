<?php
 @session_start();
    include("./includes/connection.php");
    
     if(isset($_POST['IssueManual_ID'])){
        $IssueManual_ID = $_POST['IssueManual_ID'];
    }
    
    if(isset($_POST['Item_ID'])){
        $Item_ID = $_POST['Item_ID'];
    }
    
    if(isset($_POST['Quantity_Required'])){
        $Quantity_Required = $_POST['Quantity_Required'];
    }
    
    if(isset($_POST['Item_Remark'])){
        $Item_Remark = $_POST['Item_Remark'];
    }
    
    if(isset($_POST['Quantity_Issued'])){
        $Quantity_Issued = $_POST['Quantity_Issued'];
    }
    
    if(isset($_POST['storeissuign'])){
        $storeissuign = $_POST['storeissuign'];
    }
    
    if(isset($_POST['constcenter'])){
        $constcenter = $_POST['constcenter'];
    }
    
    if(isset($_POST['Balance'])){
        $Balance = $_POST['Balance'];
    }
    
    if(isset($_POST['employee_requested'])){
        $employee_requested = $_POST['employee_requested'];
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
    
    //get branch id
    if(isset($_SESSION['userinfo']['Branch_ID'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }
    
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }
    $status='';
    $date_created='';
    
    $check = mysqli_query($conn,"SELECT Item_ID,Store_Issuing FROM tbl_issuemanual_items isi JOIN tbl_issuesmanual iss
                                ON isi.Issue_ID=iss.Issue_ID
                            WHERE isi.Item_ID='$Item_ID' AND iss.Store_Issuing='$storeissuign' AND iss.status='pending'
           ") or die(mysqli_error($conn));

if(mysqli_num_rows($check) > 0){
    echo 'exits';
}else{
    
    if(!empty($IssueManual_ID) && $IssueManual_ID !='new'){
        //check if this item has already been inserted
        
        $chck=  mysqli_query($conn,"SELECT Item_ID FROM tbl_issuemanual_items WHERE Issue_ID='$IssueManual_ID' AND Item_ID='$Item_ID'") or die(mysqli_error($conn));
        
        if(mysqli_num_rows($chck) >0 ){
            $status='Item Already Added for this Issue Note';
        }  else {
            $insert="INSERT INTO tbl_issuemanual_items (Quantity_Required,Item_Remark,Balance_Before_Issue,Item_ID,Quantity_Issued,Issue_ID)
                             VALUES('$Quantity_Required','$Item_Remark','$Balance','$Item_ID','$Quantity_Issued','$IssueManual_ID')";
            
            if(mysqli_query($conn,$insert)){
                $status=  'With Issue ID';     
            }  else {
                $status=  mysqli_error($conn);   
            }
        }
        
    }else{
        //Just insert and retrieve
        $insertRs=  mysqli_query($conn,"INSERT INTO tbl_issuesmanual (Issue_Date_And_Time,Employee_Issuing,Employee_Receiving,Store_Issuing,Store_Need,Branch_ID)
                             VALUES (NOW(),'$Employee_ID','$employee_requested','$storeissuign','$constcenter','$Branch_ID')") or die(mysqli_error($conn));
        if($insertRs){
            $lastIssueId=mysqli_query($conn,"SELECT Issue_ID,DATE(Issue_Date_And_Time) AS date_created FROM tbl_issuesmanual WHERE Employee_Issuing='$Employee_ID' ORDER BY Issue_ID DESC LIMIT 1") or die(mysqli_error($conn));
            $get= mysqli_fetch_assoc($lastIssueId);
            $date_created=$get['date_created'];
            $IssueManual_ID=  $get['Issue_ID'];
            
             $insert="INSERT INTO tbl_issuemanual_items (Quantity_Required,Item_Remark,Balance_Before_Issue,Item_ID,Quantity_Issued,Issue_ID)
                             VALUES('$Quantity_Required','$Item_Remark','$Balance','$Item_ID','$Quantity_Issued','$IssueManual_ID')";
            
            if(mysqli_query($conn,$insert)){
                $status=  'Without Issue';     
            }  else {
                $status=  mysqli_error($conn);   
            }
            
        }
    }
    
    //select current
     $data = '<center><table width = 100% border=0>';
                    $data .= '<tr>
                              <td width=4% style="text-align: center;">Sn</td>
                              <td width=40%>Item Name</td>
                              <td width=13% style="text-align: center;">Quantity Required</td>
                              <td width=13% style="text-align: center;">Quantity Issued</td>
                              <td width=25% style="text-align: center;">Remark</td>
                              <td style="text-align: center; width: 7%;">Remove</td>
                            </tr>';


          $select_Issue_Note = mysqli_query($conn,"select Quantity_Required, itm.Product_Name, Quantity_Issued, Item_Remark,Requisition_Item_ID
                                                    from tbl_issuemanual_items isi, tbl_items itm where
                                                        itm.Item_ID = isi.Item_ID and
                                                            isi.Issue_ID = '$IssueManual_ID'") or die(mysqli_error($conn));

    
    $Temp = 1;
    $hasData=0;
      if(mysqli_num_rows($select_Issue_Note) >0 ){
            while ($row = mysqli_fetch_array($select_Issue_Note)) {
                $data .= "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
                $data .= "<td><input type='text' readonly='readonly' value='" . $row['Product_Name'] . "'></td>";
                $data .= "<td><input type='text' value='" . $row['Quantity_Required'] . "' style='text-align: center;'></td>";
                $data .= "<td><input type='text' value='" . $row['Quantity_Issued'] . "' style='text-align: center;'></td>";
                $data .= "<td><input type='text' value='" . $row['Item_Remark'] . "'></td>";
                $data .= "<td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item(\"".str_replace("'", "", $row['Product_Name'])."\", ".$row['Requisition_Item_ID'].",".$IssueManual_ID.")'></td>";
                $data .= "</tr>";
                $Temp++;
            }
            $hasData=1;
      }    
            $data .= '</table>';
    $_SESSION['IssueManual_ID']=$IssueManual_ID;
    
    echo $IssueManual_ID.'gpitgtendanisha'.$date_created.'gpitgtendanisha'.$hasData.'gpitgtendanisha'.$data;
}
    
   // $data .= 'Item_ID='.$Item_ID.'&Quantity_Required='.$Quantity_Required.'&Item_Remark='.$Item_Remark.'&Quantity_Issued='.$Quantity_Issued.'&storeissuign='.$storeissuign.'&constcenter='.$constcenter.'&Balance'.$Balance.'&employee_requested='.$employee_requested;
           
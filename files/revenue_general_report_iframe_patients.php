<?php 
include("./includes/connection.php");

        if(isset($_POST['Date_From'])){
            $Start_Date = $_POST['Date_From'];
        }

        if(isset($_POST['Date_To'])){
            $End_Date = $_POST['Date_To'];
        }
        if(isset($_POST['Billing_Type'])){
            $Billing_Type = $_POST['Billing_Type'];
        }

        if(isset($_POST['Sponsor_ID'])){
            $Sponsor_ID = $_POST['Sponsor_ID'];        
        }
        if(isset($_POST['Item_ID'])){
            $Item_ID = $_POST['Item_ID'];
        }
        if(isset($_POST['Status'])){
            $Status = $_POST['Status'];
        }
        if(isset($_POST['working_department_ipd'])){
            $working_department_ipd = $_POST['working_department_ipd'];
        }
        $Sponsor_ID = $_POST['Sponsor_ID'];
        $Product_name= $_POST['Product_name']; 
        $Check_In_Type = $_POST['Check_In_Type'];
        $Item_ID = $_POST['Item_ID'];
        $filter='';
        if($working_department_ipd != 'All'){
            $filter .= " AND ilc.finance_department_id = '$working_department_ipd' ";
        }
        if($Sponsor_ID !='All'){
            $filter .= " AND pc.Sponsor_ID = '$Sponsor_ID'";
        } 
        if($Check_In_Type != "All"){        
            $filter .= " AND Check_in_type = '$Check_In_Type'";
        }
        if($Item_ID !='All'){
            $filter .= " AND ilc.Item_ID='$Item_ID'";
        }
        if($Status !=''){
            if($Status=='Done'){
                $filter .=" AND ilc.Status NOT IN ('active', 'paid') ";
            }else if($Status=='Active'){
                $filter .=" AND ilc.Status = 'active' ";
            }else if($Status=='paid'){
                $filter .=" AND ilc.Status <> 'active' ";
            }
        }
        if($Billing_Type != 'All'){
            if($Billing_Type=='Credit'){
                $filter.=" AND pc.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit')";
            }else{
                $filter.=" AND pc.Billing_Type IN ('Outpatient Cash', 'Inpatient Cash')";
            }
        }

       
?>
<div class="patients">
    <table class="table">
        <thead>
            <tr>
                <th>SN</th>
                <th>PATIENT NAME</th>
                <th>Reg#.</th>
                <th>SEX</th>
                <th>AGE</th>
                <th>BILLING TYPE</th>
                <th>SERVICE DATE</th>
                <th>SERVICE NAME</th>
                <th>RESULTS</th>
                <th>ORDERED BY</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_select_wagonjwa = mysqli_query($conn, "SELECT Patient_Name,Item_ID,pc.Registration_ID,Consultant,  Date_Of_Birth, Gender, Payment_Item_Cache_List_ID, Transaction_Date_And_Time, Billing_Type FROM tbl_patient_registration pr,tbl_item_list_cache ilc,tbl_payment_cache pc WHERE  Item_ID='$Item_ID'  AND pc.Payment_Cache_ID= ilc.Payment_Cache_ID AND  pr.Registration_ID=pc.Registration_ID AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' $filter ") or die(mysqli_error($conn));
                $num=0;
                if(mysqli_num_rows($sql_select_wagonjwa)>0){
                    while($row = mysqli_fetch_assoc($sql_select_wagonjwa)){
                        $Patient_Name = $row['Patient_Name'];
                        $Registration_ID = $row['Registration_ID'];
                        $Date_Of_Birth = $row['Date_Of_Birth'];
                        $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
                        $Gender = $row['Gender'];
                        $Item_ID = $row['Item_ID'];
                        $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                        $Billing_Type = $row['Billing_Type'];
                        $Consultant = $row['Consultant'];
                        $Today = date('Y-m-d');
                        $date1 = new DateTime($Today);
                        $date2 = new DateTime($Date_Of_Birth);
                        $diff = $date1 -> diff($date2);
                        $age = $diff->y." Yrs";
                        // $age .= $diff->m." Months, ";
                        // $age .= $diff->d." Days";
                        $Product_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'"))['Product_Name'];
                        $image='';
                        $query = mysqli_query($conn,"SELECT Attachment_Url,Description from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $Payment_Item_Cache_List_ID . "'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($query)>0){
                            while ($attach = mysqli_fetch_array($query)) {
                                if ($attach['Attachment_Url'] != '') {
                                    if (empty($attach['Description'])) {

                                    } else {
                                        $image .= "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/attachement.png' width='50' height='50' alt='Not Image File' /></a>&nbsp;&nbsp;";
                                    }
                                }
                                if (!empty($attach['Description'])) {
                                    $remarks = $attach['Description'];
                                }else{
                                    $remarks=" Attachment";
                                }
                            }
                        }else{
                            $remarks="Result not shown";
                            $image='';
                        }
                        $num++;
                        echo "<tr>
                            <td>$num</td>
                            <td>$Patient_Name</td>
                            <td>$Registration_ID</td>                            
                            <td>$Gender</td>
                            <td>$age</td>
                            <td>$Billing_Type</td>
                            <td>$Transaction_Date_And_Time</td>
                            <td>$Product_Name</td>
                            <td>$remarks $image</td>
                            <td>$Consultant</td>
                        </tr>";
                    }
                }else{
                    echo "<tr>
                   <td colspan='9' style='color:red;'>No result found</td> 
                    </tr>";
                }
            ?>
            <tr>
                <td colspan="9"><input type="button" class="art-button-green" value="PRINT" onclick="print_by_Item('<?=$Item_ID?>', '<?=$Product_name?>')"></td>
            </tr>
        </tbody>
    </table>
</div>
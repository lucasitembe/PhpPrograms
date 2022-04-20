<?php 
include("./includes/connection.php");

                
        if(isset($_GET['Date_From'])){
            $Start_Date = $_GET['Date_From'];
        }

        if(isset($_GET['Date_To'])){
            $End_Date = $_GET['Date_To'];
        }
        if(isset($_GET['Billing_Type'])){
            $Billing_Type = $_GET['Billing_Type'];
        }

        if(isset($_GET['Sponsor_ID'])){
            $Sponsor_ID = $_GET['Sponsor_ID'];        
        }
        if(isset($_GET['Item_ID'])){
            $Item_ID = $_GET['Item_ID'];
        }else{
            $Item_ID='All';
        }
        if(isset($_GET['Status'])){
            $Status = $_GET['Status'];
        }
        if(isset($_GET['working_department_ipd'])){
            $working_department_ipd = $_GET['working_department_ipd'];
        }
        
        $Product_name= $_GET['Product_name']; 
        $Check_In_Type = $_GET['Check_In_Type'];
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
$htm = "<table width ='100%' height = '30px'>
        <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
        <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>SERVICE DONE REPORT</span></b></td></tr></br>
        <tr><th><u> <h3>$Product_name</h3> </u></th></tr>
        <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>FROM ".@date("d F Y H:i:s",strtotime($Start_Date))." TO ".@date("d F Y H:i:s",strtotime($End_Date))."</span></b></td></tr>
    </table>";
$htm.= "
        <style>
            td{
                font-size:11px;
            }
        </style>
    <table width='100%'>

            <tr>
                <th>SN</th>
                <th>PATIENT NAME</th>
                <th>Reg#.</th>
                <th>SEX</th>
                <th>AGE</th>
                <th>BILLING TYPE</th>
                <th>SERVICE DATE</th>
                <th>SERVICE NAME</th>
                <th>RESULT</th>
                <th>ORDERED BY</th>
            </tr>
        
        <tbody>";

            $sql_select_wagonjwa = mysqli_query($conn,"SELECT Patient_Name,Item_ID,pc.Registration_ID,Consultant,  Date_Of_Birth, Gender,  Transaction_Date_And_Time,Payment_Item_Cache_List_ID, Billing_Type FROM tbl_patient_registration pr,tbl_item_list_cache ilc,tbl_payment_cache pc WHERE  Item_ID='$Item_ID'  AND pc.Payment_Cache_ID= ilc.Payment_Cache_ID AND  pr.Registration_ID=pc.Registration_ID AND Transaction_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' $filter ") or die(mysqli_error($conn));
                $num=0;
                if(mysqli_num_rows($sql_select_wagonjwa)>0){
                    while($row = mysqli_fetch_assoc($sql_select_wagonjwa)){
                        $Patient_Name = $row['Patient_Name'];
                        $Registration_ID = $row['Registration_ID'];
                        $Date_Of_Birth = $row['Date_Of_Birth'];
                        $Gender = $row['Gender'];
                        $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
                        $Transaction_Date_And_Time = $row['Transaction_Date_And_Time'];
                        $Billing_Type = $row['Billing_Type'];
                        $Consultant = $row['Consultant'];
                        $Today = date('Y-m-d');
                        $date1 = new DateTime($Today);
                        $date2 = new DateTime($Date_Of_Birth);
                        $diff = $date1 -> diff($date2);
                        $age = $diff->y." Yrs ";
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
                                }
                            }
                        }else{
                            $remarks="Result not shown";
                            $image='';
                        }
                        $num++;
                        $htm.= "<tr>
                            <td>$num</td>
                            <td>".strtoupper($Patient_Name)."</td>
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
                    $htm.= "<tr>
                   <td colspan='6' style='color:red;'>No result found</td> 
                    </tr>";
                }
            $htm.= "
        </tbody>
    </table>
";


include("./MPDF/mpdf.php");
$mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
$mpdf->SetFooter('|{PAGENO}/{nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($htm);
$mpdf->Output();
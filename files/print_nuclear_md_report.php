<?php
        session_start();
        include("./includes/connection.php");

        if(isset($_SESSION['userinfo']['Employee_Name'])){
            $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        }else{
            $Employee_Name = '';
        }
        
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
        if(isset($_GET['Registration_ID'])){
            $Registration_ID=$_GET['Registration_ID'];
         }else{
            $Registration_ID=""; 
         }
         $anasthesia_record_id =$_GET['anasthesia_record_id'];
         $created_at = $_GET['anasthesia_created_at'];
         $pdetails = "SELECT Patient_Name,Gender,pr.Registration_ID,pr.Region,pr.District,pr.Country,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age,Guarantor_Name FROM tbl_patient_registration pr JOIN tbl_sponsor sp ON sp.Sponsor_ID=pr.Sponsor_ID  WHERE Registration_ID = '$Registration_ID'";
            $pdetails_results = mysqli_query($conn,$pdetails) or die(mysqli_error($conn));
            while ($pdetail = mysqli_fetch_assoc($pdetails_results)) {
                $Patient_Name = $pdetail['Patient_Name'];
                $Registration_ID = $pdetail['Registration_ID']; 
                $Gender = $pdetail['Gender'];
                $age = $pdetail['age'];
                $Region = $pdetail['Region'];
                $District = $pdetail['District'];
                $Country = $pdetail['Country'];
                $Guarantor_Name = $pdetail['Guarantor_Name'];
            }
            $select = mysqli_query($conn,"SELECT Product_Name,Doctor_Comment,Consultant, ilc.Status, ilc.Item_ID from tbl_items i, tbl_item_list_cache ilc where  i.Item_ID = ilc.Item_ID and  ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
            $no = mysqli_num_rows($select);
            if($no > 0){
                while ($dt = mysqli_fetch_array($select)) {
                    $Product_Name = $dt['Product_Name'];
                    $Status = $dt['Status'];
                    $Item_ID = $dt['Item_ID'];
                    $Doctor_Comment = $dt['Doctor_Comment'];
                    $Consultant = $dt['Consultant'];
                }
            }
            $select_report = mysqli_query($conn, "SELECT FindsReport,attachment,Employee_Title,mr.created_at, Employee_Name FROM tbl_nuclear_medicine_report mr, tbl_employee e WHERE e.Employee_ID=mr.Employee_ID AND Registration_ID='$Registration_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($select_report)>0){
                while($report_rw = mysqli_fetch_assoc($select_report)){
                    $FindsReport  = $report_rw['FindsReport'];
                    $Employee_Title = $report_rw['Employee_Title'];
                    $Employee_Name = $report_rw['Employee_Name'];
                    $Datedone = $report_rw['created_at'];
                    $attachment = $report_rw['attachment'];

                   
                   
                    
                }
            }
         $htm ='<table align="center" width="100%">
                    <tr><td style="text-align:center" colspan=""><img src="./branchBanner/branchBanner.png"></td></tr>
                   </table> 
                   <table align="center" width="100%">
                   <caption>NUCLEAR MEDICINE SCAN REPORT</caption> 
                   <tr><th style="text-align: right;" colspan="2"><u>For Attention: </u></th><td colspan="4">'.$Consultant.'</td></tr>
                    <tr>
                    <td style="text-align: right;" width="10%"><b>Name:</b></td>
                    <td width="30%">' . $Patient_Name . '</td>
                    <td style="text-align: right;" width="20%"><b>Gender:</b></td>
                    <td>' . $Gender . '</td>
                    <td style="text-align: right;"><b>Reg #:</b></td>
                    <td  width="15%">' . $Registration_ID . '</td>
                <tr>
                    <td style="text-align: right;"><b>Sponsor:</b></td>
                    <td>' . $Guarantor_Name . '</td>
                    <td style="text-align: right;"><b>Age:</b></td>
                    <td>' . $age . ' years</td>
                    <td style="text-align: right;" ><b>Address:</b></td>
                    <td>' .$Country.', '. $Region . ', '.$District.'.</td>
                </tr>
               
                </table>';
               
                $htm .="<table width='100%'>

                    <tr><th>Clinical Summary</th><td colspan='3'>$Doctor_Comment</td></tr>
                    <tr><th>SCAN</th><td>$Product_Name</td><th>Date Done</th><td>$Datedone</td></tr>
                </table>";
                $htm .= "<div class='col-md-12'>$FindsReport</div><br/>
                         <br/>";

                        $selctimige = mysqli_query($conn, "SELECT * FROM tbl_nm_image WHERE Patient_Payment_Item_List_ID='$Payment_Item_Cache_List_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));

                        if(mysqli_num_rows($selctimige)>0){
                            while($rw = mysqli_fetch_assoc($selctimige)){
                                $Nuclearmedicine_image= $rw['Nuclearmedicine_image'];
                            $Pic_ID =$rw['Pic_ID'];

                                $imaging = "<a href='Nm/imageuploads/".$Nuclearmedicine_image."' title='Nuclear Medicine attachment For ID#. $Registration_ID' class='fancyboxRadimg' target='_blank'><img    alt=''  src='Nm/imageuploads/".$Nuclearmedicine_image."'  alt='Nuclear Medicine attachment'/></a>";

                                $htm.= "<div class='row col-md-2' style='width: 100%;height: 90px; padding-bottom: 50px;'>$imaging </div>";
                            }
                        }else{
                            $htm.= "No any attachment";
                        }
                       $htm.=" <div class='col-md-12'><b>Done by:</b>  $Employee_Name <br/> <b>Title: </b>$Employee_Title                             
                            </div>
                        ";

         
                include("./MPDF/mpdf.php");
                $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
                $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
                $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
                // LOAD a stylesheet
                $stylesheet = file_get_contents('mpdfstyletables.css');
                $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text
        
                $mpdf->WriteHTML($htm,2);
        
                $mpdf->Output('mpdf.pdf','I');
                exit;
            ?>
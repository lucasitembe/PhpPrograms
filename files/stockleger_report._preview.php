

<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include("./includes/connection.php");
    include("./functions/supplier.php");
    include("./functions/department.php");
    include("./functions/patient.php");

	if(isset($_GET['Start_Date'])){
		$Start_Date = $_GET['Start_Date'];
	}else{
		$Start_Date = '';
	}

	if(isset($_GET['End_Date'])){
		$End_Date = $_GET['End_Date'];
	}else{
		$End_Date = '';
	}

	if(isset($_GET['Item_ID'])){
		$Item_ID = $_GET['Item_ID'];
	}else{
		$Item_ID = '';
	}
        
        $Product_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$Item_ID'"))['Product_Name'];

    if(isset($_GET['Sub_Department_ID'])){
        $Sub_Department_ID = $_GET['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    
     $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Sub_Department_ID'"))['Sub_Department_Name'];
    
        if(isset($_GET['item_folio_number'])){
        $item_folio_number = $_GET['item_folio_number'];
    }else{
       $item_folio_number="";
    }

    
    $htm = "<table width ='100%' height = '30px'  cellspacing='0' cellpadding='0'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr><td>&nbsp;</td></tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2>STOCK LEDGER REPORT</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table border='1' width='100%' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10>";
    $htm .= "<tr>";
    $htm .= "<td><b>Start Date :</b> </td><td> {$Start_Date} </td>";
    $htm .= "<td><b>End Date :</b> </td><td> {$End_Date} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td><b>Item Name :</b> </td><td colspan='3'> {$Product_Name} </td></tr><tr><td ><b>Item Folio No.</b> </td><td colspan='3'>$item_folio_number </td>";
    $htm .= "<tr><td><b>Location :</b> </td><td colspan='3'> {$Sub_Department_Name} </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";


    //get details
    $select = mysqli_query($conn,"SELECT * FROM tbl_stock_ledger_controler
                           WHERE Movement_Date BETWEEN '$Start_Date' AND '$End_Date'
                           AND Item_ID = '$Item_ID'
                           AND Sub_Department_ID = '$Sub_Department_ID'
                           ORDER BY Movement_Date_Time") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select); //echo $no; exit();
    if($no > 0){
        $controler = 'yes';
        $Total_inward = 0;
        $Total_outward = 0;
        $All_total_inward=0;
        $All_total_outward=0;
        $temp = 0;
        $Running_Balance = 0;

      $htm .= "<table width='100%' border='1' style='font-size:12px;border-collapse: collapse;' cellpadding=5 cellspacing=10>
                
                <tr><td width='3%'><b>SN</b></td>
                    <td width='8%'><b>DOC NO</b></td>
                    <td width='10%'><b>DOC DATE</b></td>
                    <td width='45%'><b>NARRATION</b></td>
                    <td width='12%' style='text-align: right'><b>INWARD FLOW</b></td>
                    <td width='11%' style='text-align: right'><b>OUTWARD FLOW</b></td>
                    <td width='15%' style='text-align: right'><b>RUNNING BALANCE</b>&nbsp;&nbsp;</td>
                    <td width='12%' style='text-align: right'><b>REASON</b></td>
                    <td width='13%' style='text-align: right'><b>EMPLOYEE</b></td>
                  
                </tr>
                ";
    	while ($data = mysqli_fetch_array($select)) {
            $Movement_Type = $data['Movement_Type'];
            $Internal_Destination = $data['Internal_Destination'];
            $External_Source = $data['External_Source'];
            $Pre_Balance = $data['Pre_Balance'];
            $Movement_Date = $data['Movement_Date'];
            $Movement_Date_Time = $data['Movement_Date_Time'];
            $Registration_ID = $data['Registration_ID'];
            $Document_Number = $data['Document_Number'];
            $Item_ID2 = $data['Item_ID'];

            if($controler == 'yes'){
               $htm .= "<tr><td colspan='7' style='text-align: right;'><b>B/F : ".$Pre_Balance."&nbsp;&nbsp;&nbsp;&nbsp;</b></td></tr>";
               
                $controler = 'no';
            }
            if($Movement_Type == 'From External'){

                //Get Supplier
                $Supplier = Get_Supplier($External_Source);
                if(!empty($Supplier)) {
                    $Supplier_Name = $Supplier['Supplier_Name'];
                }else{
                    $Supplier_Name = '';
                }

                $Employee_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_grn_open_balance WHERE Grn_Open_Balance_ID='$Document_Number'"))['Employee_ID'];
                $Name_employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $All_total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);

              $htm .= " <tr><td> ".++$temp ."</td>
                    <td>".$data['Document_Number']."</td>
                    <td>" .$data['Movement_Date']."</td>
                    <td>". strtoupper($Supplier_Name)."</td>
                    <td style='text-align: right'>". ($data['Post_Balance'] - $data['Pre_Balance'])."</td>
                    <td style='text-align: right'>0</td>
                    <td style='text-align: right'>" .$data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td style='text-align: right'></td>
                    <td style='text-align: right'>". $Name_employee."</td>
                </tr>";

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Without Purchase'){

                //Get Supplier
                $Supplier = Get_Supplier($External_Source);
                if(!empty($Supplier)) {
                    $Supplier_Name = $Supplier['Supplier_Name'];
                }else{
                    $Supplier_Name = '';
                }

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $All_total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);

             $htm .=  "<tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". strtoupper($Supplier_Name)."</td>
                    <td style='text-align: right'>".($data['Post_Balance'] - $data['Pre_Balance'])."</td>
                    <td style='text-align: right'>0</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td style='text-align: right'></td>
                    <td style='text-align: right'>". $Name_employee."</td>
                </tr>";

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Open Balance'){
               $data_data = $data['Pre_Balance'];
               $Total_inward = $data['Post_Balance'];
                $Document_Number;
                
                $Total_outward = 0;
                
//                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                
              $ID_reason = mysqli_fetch_assoc(mysqli_query($conn,"SELECT reason_id FROM tbl_grn_open_balance_items WHERE Grn_Open_Balance_ID='$Document_Number' AND Item_ID='$Item_ID2'"))['reason_id'];
                
                $Name_reasons = mysqli_fetch_assoc(mysqli_query($conn,"SELECT reasons FROM tbl_reasons_adjustment WHERE reason_id='$ID_reason'"))['reasons'];
                
                
//                $Grn_Open_Balance_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Grn_Open_Balance_ID FROM tbl_grn_open_balance_items WHERE Grn_Open_Balance_ID='$Document_Number' AND Item_ID='$Item_ID'"))['Grn_Open_Balance_ID'];
                
                $Employee_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_grn_open_balance WHERE Grn_Open_Balance_ID='$Document_Number'"))['Employee_ID'];
                $Name_employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
                
                $All_total_inward += $data['Post_Balance'];

              $htm .=  " <tr><td> ".++$temp."</td>
                    <td>". $Document_Number."</td>
                    <td>". $Movement_Date."</td>
                    <td>OPEN BALANCE / STOCK TAKING</td>
                    <td style='text-align: right'>". $data['Post_Balance']."</td>
                    <td style='text-align: right'>0</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                     <td style='text-align: right'>". $Name_reasons." </td>
                      <td style='text-align: right'>". $Name_employee."</td>
                </tr>";

                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Issue Note'){

                //Get Internal Destination
                $Sub_Department = Get_Sub_Department($Internal_Destination);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }
                $Issue_ID = $data['Document_Number'];
                
                $Employee_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_issues WHERE Requisition_ID='$Document_Number'"))['Employee_ID'];
                
//                $Store_Issue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_requisition WHERE Requisition_ID='$Document_Number'"))['Store_Issue'];
                
                $Name_issues = mysqli_fetch_assoc(mysqli_query($conn,"SELECT em.Employee_Name FROM tbl_employee em, tbl_issues ti WHERE em.Employee_ID=ti.Employee_ID AND ti.Issue_ID='$Issue_ID'"))['Employee_Name'];
                
                
                

                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
            
               $htm .=  " <tr><td>". ++$temp."</td>
                    <td>" .$data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>";
                  
                      $htm .=   "<td>
                       Issue ({$Sub_Department_Name})
                       </td>";
                  
                  $htm .= "<td>0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td style='text-align: left;'>Issue</td>
                    <td style='text-align: left;'>". $Name_issues."</td>
                </tr>";
        
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Dispensed'){

                //Get Patient Name
                 $Item_ID = $data['Item_ID'];
                $Patient = Get_Patient($Registration_ID);
                if(!empty($Patient)) {
                    $Patient_Name = $Patient['Patient_Name'];
                }else{
                    $Patient_Name = '';
                }

                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                
                 $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                //Get payment mode
                $Payment_Mode = 'Cash';
                $Document_Number = $data['Document_Number'];
                $slct = mysqli_query($conn,"select Billing_Type, sp.Exemption, pp.payment_type from tbl_patient_payments pp, tbl_sponsor sp where
                                        pp.Sponsor_ID = sp.Sponsor_ID and
                                        pp.Patient_Payment_ID = '$Document_Number'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slct);
                if($nm > 0){
                    while($rw = mysqli_fetch_array($slct)){
                        if(strtolower($rw['Billing_Type']) == 'outpatient credit' || strtolower($rw['Billing_Type']) == 'inpatient credit' || (strtolower($rw['Billing_Type']) == 'inpatient cash' && $rw['payment_type'] == 'post')){
                            if(strtolower($rw['Exemption']) == 'yes'){
                                $Payment_Mode = 'Exemption';
                            }else{
                                $Payment_Mode = 'Credit';
                            }
                        }else{
                            $Payment_Mode = 'Cash';
                        }
                    }
                }
                
                
                $ID_Dispensor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Dispensor FROM tbl_item_list_cache WHERE Patient_Payment_ID='$Document_Number' AND Item_ID='$Item_ID'"))['Dispensor'];
                
                $Name_Dispensor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$ID_Dispensor'"))['Employee_Name'];
                
          $htm .= "<tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>Dispensed to ". ucwords(strtolower($Patient_Name))." - ".$Payment_Mode."</td>
                    <td>0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td>Dispense</td>
                    <td>". $Name_Dispensor."</td>
                </tr>";

                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'GRN Agains Issue Note'){
                
                //select department from
                
                $Document_Number = $data['Document_Number'];
                
                $Name_store_Store_Issue_id = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Store_Issue FROM tbl_requisition WHERE Requisition_ID='$Document_Number'"))['Store_Issue'];
                
                $Name_departemt_issue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID='$Name_store_Store_Issue_id'"))['Sub_Department_Name'];
                
                $Employee_ID_employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_requisition WHERE Requisition_ID='$Document_Number'"))['Employee_ID'];
                
                $Name_employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID_employee'"))['Employee_Name'];
                
                //Get Department received GRN
                $Store_ID = $data['Sub_Department_ID'];
                $Sub_Department = Get_Sub_Department($Store_ID);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $All_total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
         
                $htm .= " <tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td >". "Received from-~~".$Name_departemt_issue."</td>
                    <td>". ($data['Post_Balance'] - $data['Pre_Balance'])."</td>
                    <td>0</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td>Requisition</td>
                    <td>". $Name_employee."</td>
                </tr>";
            
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Disposal'){

                //Get Department Disposing
                $Store_ID = $data['Sub_Department_ID'];
                $Sub_Department = Get_Sub_Department($Store_ID);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                
                 $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
            
                $htm .= " <tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>Disposal</td>
                    <td>0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                </tr>";
             
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Outward'){

                $Supplier = Get_Supplier($External_Source);
                if(!empty($Supplier)) {
                    $Supplier_Name = $Supplier['Supplier_Name'];
                }else{
                    $Supplier_Name = 'Unknown Supplier';
                }

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
               
                $htm .= " <tr><td>". ++$temp."</td>
                    <td>". "<a target='_blank' href='returnoutward_preview.php?Outward_ID={$data['Document_Number']}'>{$data['Document_Number']}</a>"."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "<a target='_blank' href='returnoutward_preview.php?Outward_ID={$data['Document_Number']}'>Return TO ({$Supplier_Name})</a>"."</td>
                    <td>0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                </tr>";
             
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Inward'){

                $Sub_Department = Get_Sub_Department($Internal_Destination);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $All_total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
             
              $htm .= "<tr><td>". ++$temp."</td>
                    <td>". "<a target='_blank' href='returninward_preview.php?Inward_ID={$data['Document_Number']}'>{$data['Document_Number']}</a>"."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "<a target='_blank' href='returninward_preview.php?Inward_ID={$data['Document_Number']}'>Return From ({$Sub_Department_Name})</a>"."</td>
                    <td>". ($data['Post_Balance'] - $data['Pre_Balance'])."</td>
                    <td>0</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                </tr>";
              
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Inward Outward'){

                $Sub_Department = Get_Sub_Department($Internal_Destination);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
         
               $htm .= "  <tr><td>". ++$temp."</td>
                    <td>". "<a target='_blank' href='returninward_preview.php?Inward_ID={$data['Document_Number']}'>{$data['Document_Number']}</a>"."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "<a target='_blank' href='returninward_preview.php?Inward_ID={$data['Document_Number']}'>Return To ({$Sub_Department_Name})</a>"."</td>
                    <td>0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                </tr>";
           
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Issue Note Manual'){

                //Get Internal Destination
                $Sub_Department = Get_Sub_Department($Internal_Destination);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }
                
                
                                $Employee_Issuing = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Issuing FROM tbl_issuesmanual WHERE Issue_ID='$Document_Number'"))['Employee_Issuing'];
                
//                $Store_Issue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_requisition WHERE Requisition_ID='$Document_Number'"))['Store_Issue'];
                
                $Name_issues = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_Issuing'"))['Employee_Name'];

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
               
                $htm .= " <tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "Issue (".$Sub_Department_Name.")"."</td>
                    <td>0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td>Issue Note Manual</td>
                    <td>". $Name_issues."</td>
                </tr>";
         
                $Grand_Balance = $data['Post_Balance'];
            }  else if($Movement_Type == 'Stock Taking Under'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 $All_total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
           
              $htm .= "<tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "Stock Taking (Under)"."</td>
                    <td> 0</td>
                    <td>". ($data['Pre_Balance'] - $data['Post_Balance'])."</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                </tr>";
            
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Stock Taking Over'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $All_total_inward  += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
           
               $htm .= "  <tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "Stock Taking (Over)"."</td>
                    <td>". ($data['Post_Balance'] - $data['Pre_Balance'])."</td>
                    <td> 0</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                </tr>";

                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Received From Issue Note Manual'){
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                
                $All_total_inward  += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;

                //Get Internal Destination
                $Sub_Department = Get_Sub_Department($Internal_Destination);
                if(!empty($Sub_Department)) {
                    $Sub_Department_Name = $Sub_Department['Sub_Department_Name'];
                }else{
                    $Sub_Department_Name = '';
                }
                
                           $Employee_receive = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Receiving FROM tbl_issuesmanual WHERE Issue_ID='$Document_Number'"))['Employee_Receiving'];
                
//                $Store_Issue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_ID FROM tbl_requisition WHERE Requisition_ID='$Document_Number'"))['Store_Issue'];
                
                $Name_issues_receve = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_receive'"))['Employee_Name'];

        
               $htm .= " <tr><td>". ++$temp."</td>
                    <td>". $data['Document_Number']."</td>
                    <td>". $data['Movement_Date']."</td>
                    <td>". "Issue Note Manual From <b>".$Sub_Department_Name."</b>"."</td>
                    <td>". ($data['Post_Balance'] - $data['Pre_Balance'])."</td>
                    <td>0</td>
                    <td style='text-align: right'>". $data['Post_Balance']."&nbsp;&nbsp;</td>
                    <td></td>
                    <td>". $Name_issues_receve."</td>
                </tr>";
              
                $Grand_Balance = $data['Post_Balance'];
            }
        }
      
        $htm .=  "<tr><td colspan='4'></td>
                <td style='text-align: right;'>".$Total_inward."</td>
                <td style='text-align: right;'>".$Total_outward."</td>
                <td style='text-align: right;'>".$Grand_Balance."&nbsp;&nbsp;</td>
                      <td style='text-align: right;'></td>
                <td style='text-align: right;'></td>
                </tr>";
     
    
        $htm .= "<tr><td colspan='4'> <b>All Grand Total</b> </td>
                <td style='text-align: right;'>".$All_total_inward."</td>
                <td style='text-align: right;'>". $All_total_outward."</td>
                <td style='text-align: right;'>".$Grand_Balance."&nbsp;&nbsp;</td>
                      <td style='text-align: right;'></td>
                <td style='text-align: right;'></td>
                </tr>";
       
         $htm .= "</table>";
    }else{
        //Get Item Balance
        $select = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            $result = mysqli_fetch_assoc($select);
            $Item_Balance = $result['Item_Balance'];
        }else{
            mysqli_query($conn,"insert into tbl_items_balance(Item_ID,Sub_Department_ID) values('$Item_ID','$Sub_Department_ID')") or die(mysqli_error($conn));
        }
        $Date = mysqli_fetch_assoc(mysqli_query($conn,"select now() as Today"));
        $Curent_Date = $Date['Today'];
        $htm .= "<table width='100%' border='1'  cellspacing='0' cellpadding='0'>
                <tr><td colspan='7'><hr></td></tr>
                <tr><td width='3%'><b>SN</b></td>
                    <td width='8%'><b>DOC NO</b></td>
                    <td width='10%'><b>DOC DATE</b></td>
                    <td><b>NARRATION</b></td>
                    <td width='12%' style='text-align: right'><b>INWARD FLOW</b></td>
                    <td width='13%' style='text-align: right'><b>OUTWARD FLOW</b></td>
                    <td width='15%' style='text-align: right'><b>RUNNING BALANCE</b>&nbsp;&nbsp;</td>
                </tr>
                <tr><td colspan='7'><hr></td></tr>";
        $htm .= "<tr><td colspan='7' style='text-align: center;'><h3>No stock movement found between ".$Start_Date." and ".$End_Date."</h3></td></tr>";
        
        //echo "<tr><td colspan='7' style='text-align: center;'><h3>Current Balance : ".$Item_Balance.", Balance Date : ".$Curent_Date."&nbsp;&nbsp;&nbsp;&nbsp;</h3></td></tr>";
        $htm .= "</table>";
    }

    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>

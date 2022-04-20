<?php
@session_start();
include './includes/connection.php';

$Employee_Type= $_SESSION['userinfo']['Employee_Type'];
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

                      $totalPPP = 0; 
                      $filter = '';
                      $rad_Type = 'All';

                      
                      // if (isset($_GET['fromDate'])) {
                          $fromDate = $_GET['fromDate'];
                          $toDate = $_GET['toDate'];
                          $Item_ID = $_GET['Item_ID'];
                          $employee_id = $_GET['employee_id'];
                          $filter .= " AND ilc.ServedDateTime BETWEEN '$fromDate' AND '$toDate'";
                      

                          

                          if($Item_ID != 'All'){
                              $filter .= " AND ilc.Item_ID = '$Item_ID'";
                          }
                      // }
                      
                      if($employee_id != 'All'){
                          $empType =" AND po.Employee_ID = '$employee_id'";
                      }else{
                          $empType = '';
                      }
                      
                      $htm = "<table width ='100%' border='0' class='nobordertable'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png' width='100%'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>SURGERY PERFORMANCE REPORT</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($fromDate)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($toDate)) . "</b></td></tr>
                      </table>";
                      
                      $selectAllRadEmployee_qry = mysqli_query($conn, "SELECT po.Employee_ID, em.Employee_Name, em.Employee_Job_Code FROM tbl_employee em, tbl_post_operative_notes po WHERE em.Employee_ID = po.Employee_ID $empType GROUP BY po.Employee_ID") or die(mysqli_error($conn));
                      $emp_num = 1;
                      while ($emp = mysqli_fetch_array($selectAllRadEmployee_qry)) {
                          $empname = $emp['Employee_Name'];
                          $employee_job_code = $emp['Employee_Job_Code'];
                          $empid = $emp['Employee_ID'];
                      
                          $jobCode = explode('/', $employee_job_code);
                          $filterRad = '';
                      
                          // print_r($jobCode);
                      
                         // $htm .=($check_has_patient) . '<br/>';
                          $check_has_patient_result = mysqli_query($conn,"SELECT ilc.Payment_Item_Cache_List_ID, ilc.Price, ilc.Patient_Payment_ID, ilc.ServedDateTime FROM tbl_item_list_cache ilc, tbl_payment_cache pp WHERE pp.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Status='served' AND ilc.ServedBy = '$empid' AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_post_operative_notes WHERE Post_Operative_Status = 'Saved') $filter ORDER BY ilc.ServedDateTime ASC") or die(mysqli_error($conn));
                          $pat_no = mysqli_num_rows($check_has_patient_result);
                          if ($pat_no > 0) {
                              $htm .="<div class='daterange' style='font-weight: bold;'>".$emp_num.". " . $empname . "/<span> $employee_job_code </span><span style='float:right'> - PATIENT NO. $pat_no </span></div>";
                              $htm .='<table width =100% border="0" id="patientspecimenCollected" class="display">';
                              $htm .="
                              <tr>
                                      <th  width='3%'>SN</th>
                                      <th style='text-align: left;'><b>PATIENT NAME</th>
                                      <th style='text-align: left;'>REG #</th>
                                      <th style='text-align: left;'>SPONSOR</th>
                                      <th style='text-align: left;'>SURGERY</th>
                                      <th style='text-align: left;'>SURGERY DATE</th>
                          </tr>";
                      
                              $count = 1;
                              $Price = 0;
                              while($details = mysqli_fetch_array($check_has_patient_result)){
                                  $datee = $details['ServedDateTime'];
                                  $thisDate = date('d, M Y', strtotime($datee)) . '';
                                  $Payment_Item_Cache_List_ID = $details['Payment_Item_Cache_List_ID'];
                                  $Patient_Payment_ID = $details['Patient_Payment_ID'];
                                  $Price = $details['Price'];
                      
                                  $notIn = array('-1');
                      
                      
                                  $select_data_patient_result = mysqli_query($conn,"SELECT pp.Registration_ID, ilc.Price, ilc.Discount, i.Product_Name, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name FROM tbl_item_list_cache ilc, tbl_patient_registration pr, tbl_payment_cache pp, tbl_sponsor sp, tbl_items i WHERE  pp.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Item_ID=i.Item_ID AND pr.Registration_ID = pp.Registration_ID AND sp.Sponsor_ID = pp.Sponsor_ID AND ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                                  if (mysqli_num_rows($select_data_patient_result) > 0) {
                      
                                      while ($row = mysqli_fetch_assoc($select_data_patient_result)) {
                                          $Registration_ID = $row['Registration_ID'];
                                          $Patient_Name = $row['Patient_Name'];
                                          $Registration_ID = $row['Registration_ID'];
                                          $Guarantor_Name = $row['Guarantor_Name'];
                                          $Gender = $row['Gender'];
                                          $dob = $row['Date_Of_Birth'];
                                          $Product_Name = $row['Product_Name'];
                                          $Price = $row['Price'];
                                          $Discount = $row['Discount'];   
                      
                                              $htm .="<tr><td>" . $count . "</td>";
                                              $htm .="<td style='text-align:left; '>" . $Patient_Name . "</td>";
                                              $htm .="<td style='text-align:left; '>" . $Registration_ID . "</td>";
                                              $htm .="<td style='text-align:left; '>" . $Guarantor_Name . "</td>";
                                              $htm .="<td style='text-align:left; '>" . $Product_Name . "</td>";
                                              $htm .="<td style='text-align:left; '>" . $thisDate . "</td>";
                                              $htm .=" </tr>";
                      
                                      $count++;
                                    $totalPPP++;
                                    $Price_sub_total += ($Price - $Discount);

                                      }
                                  }
                                //   $htm .="<tr>
                                //             <td colspan='5' style='text-align: right; font-size: 13px; font-weight: bold;'>REVENUE GENERATED</td>
                                //             <td>".number_format($Price_sub_total, 2)."</td></tr>";
                              }
                          $emp_num++;
                          $Grand_Total += $Price_sub_total;
                          $htm .="</table><br/>";
                          }
                          
                      }
                      $htm .= "<div style='margin:0px 0px 10px 0px;width:100%;text-align:right;font-family: times;font-size: small;font-weight: bold;border:2px solid #ccc'>TOTAL PATIENT: " . number_format($totalPPP) . "<span style='float:right'></span></div>";
                      $htm .= "<div style='margin:0px 0px 10px 0px;width:100%;text-align:right;font-family: times;font-size: small;font-weight: bold;border:2px solid #ccc'>TOTAL REVENUE COLLECTED: " . number_format($Price_sub_total) . "<span style='float:right'></span></div>";
 
include("MPDF/mpdf.php");

$mpdf = new mPDF('', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($data, 2);

$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;

<?php
  session_start();
  include("includes/connection.php");
  $temp = 1;
  
  if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }
  if(isset($_GET['Date_From'])){
    $Date_From = $_GET['Date_From'];
  }else{
    $Date_From = '0000/00/00 00:00:00';
  }

  if(isset($_GET['Date_To'])){
    $Date_To = $_GET['Date_To'];
  }else{
    $Date_To = '0000/00/00 00:00:00';
  }

  if(isset($_GET['employee_ID'])){
    $employee_ID = $_GET['employee_ID'];
  }else{
    $employee_ID = '';
  }


  if(isset($_GET['employee_ID'])){
    $employee_ID = $_GET['employee_ID'];
    $query=mysqli_query($conn,"SELECT Employee_Name from tbl_employee WHERE Employee_ID='$employee_ID'");
    $result=  mysqli_fetch_assoc($query);
    $employee_name = $result['Employee_Name'];
    
    if($employee_ID == ''){
      $employee_name = 'ALL'; 
    }else{
      $employee_name=$result['Employee_Name'];
    }
  }else{
    $employee_ID_Query='';  
  }


  $htm = '<table width ="100%" height="30px">
      		  <tr><td><img src="./branchBanner/branchBanner.png" width=100%></td></tr>
      		  <tr><td style="text-align: center;"><b>WAGONJWA WA MSAMAHA</b></td></tr>
            <tr><td style="text-align: center;"><b>EMPLOYEE: '.$employee_name.'</b></td></tr>
            <tr><td style="text-align: center;"><b>KUANZIA ' . $Date_From . ' HADI ' . $Date_To . '</b></td></tr>
          </table>';


  $htm .= '<table width = "100%" border="1" style="border-collapse: collapse;">
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>AINA YA MSAMAHA</b></td>
                <td width="15%" style="text-align: center;"><b>MALE</b></td>
                <td width="15%" style="text-align: center;"><b>FEMALE</b></td>
                <td width="15%" style="text-align: center;"><b>TOTAL</b></td>
            </tr>';

  //create filter
  $filter = " ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and sp.Exemption = 'yes' and ";

  if($employee_ID != '' && $employee_ID != null){
    $filter .= "ci.Employee_ID = '$employee_ID' and ";
  }

  $Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
  while ($row = mysqli_fetch_array($Today_Date)) {
      $original_Date = $row['today'];
      $new_Date = date("Y-m-d", strtotime($original_Date));
      $Today = $new_Date;
      $age = '';
  }


          //select msahama type
            $grandtotal_count_male=0;
            $grandtotal_count_female=0;
        $sql_select_aina_ya_msamaha_result=mysqli_query($conn,"SELECT msamaha_aina FROM tbl_msamaha_items") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_aina_ya_msamaha_result)>0){
            $count_sn=1;
           
            while($aina_msamaha_rows=mysqli_fetch_assoc($sql_select_aina_ya_msamaha_result)){
                $msamaha_aina=$aina_msamaha_rows['msamaha_aina'];
               /////////////////////////////////////////////////////
               $details = mysqli_query($conn,"select Gender, pr.Registration_ID, aina_ya_msamaha from
	 							tbl_patient_registration pr, tbl_sponsor sp, tbl_check_in ci,tbl_msamaha msh where
	 							$filter
                                                                                msh.Registration_ID=ci.Registration_ID and
                                                                                pr.Sponsor_ID = sp.Sponsor_ID and
                                                                                ci.Registration_ID = pr.Registration_ID and aina_ya_msamaha='$msamaha_aina'") or die(mysqli_error($conn));
               
               $count_male=0;
               $count_female=0;
               if(mysqli_num_rows($details)>0){
                         while($msamaha_rows=mysqli_fetch_assoc($details)){
                            $Gender=$msamaha_rows['Gender'];
                             $aina_ya_msamaha=$msamaha_rows['aina_ya_msamaha'];
                             if($Gender=="Male"){
                                $count_male++;
                             }else{
                                $count_female++;
                             }
                         }
                }
                $grandtotal_count_male+=$count_male;
                $grandtotal_count_female+=$count_female;
                $htm .= "<tr>
                           <td>$count_sn</td>
                           <td>".strtoupper($msamaha_aina)."</td>
                           <td style='text-align:center' >$count_male</td>
                           <td style='text-align:center' >$count_female</td>
                           <td style='text-align:center' >".($count_female+$count_male)."</td>
                     <tr>";
               /////////////////////////////////////////////////////
                $count_sn++;
            }
        }
     
    $htm .="
        <tr>
            <td colspan='2'><b>TOTAL</b></td>
            <td style='text-align:center'>$grandtotal_count_male</td>
            <td style='text-align:center'>$grandtotal_count_female</td>
            <td style='text-align:center'>".($grandtotal_count_female+$grandtotal_count_male)."</td>
        </tr>";
  
  
  $htm .= "</table>";
  include("./MPDF/mpdf.php");
  $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
  $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
  $mpdf->WriteHTML($htm);
  $mpdf->Output();
?>
<legend align="left"><b id="dateRange">WAGONJWA WA MSAMAHA</span></b></legend>
<table width="100%">
	<tr><td colspan="5"><hr></td></tr>
    <tr>
        <td width="5%"><b>SN</b></td>
        <td><b>AINA YA MSAMAHA</b></td>
        <td width="15%" style="text-align: center;"><b>MALE</b></td>
        <td width="15%" style="text-align: center;"><b>FEMALE</b></td>
        <td width="15%" style="text-align: center;"><b>TOTAL</b></td>
    </tr>
   	<tr><td colspan="5"><hr></td></tr>
<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	if(isset($_GET['employee_ID'])){
		$employee_ID = $_GET['employee_ID'];
	}else{
		$employee_ID = '';
	}

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
        $sql_select_aina_ya_msamaha_result=mysqli_query($conn,"SELECT msamaha_aina FROM tbl_msamaha_items") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_aina_ya_msamaha_result)>0){
            $count_sn=1;
            $grandtotal_count_male=0;
            $grandtotal_count_female=0;
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
                echo "<tr>
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
     
?>      
        <tr><td colspan="5"><hr/></td></tr>
        <tr>
            <td colspan="2"><b>TOTAL</b></td>
            <td style='text-align:center'><?= $grandtotal_count_male ?></td>
            <td style='text-align:center'><?= $grandtotal_count_female ?></td>
            <td style='text-align:center'><?= $grandtotal_count_female+$grandtotal_count_male ?></td>
        </tr>
</table>
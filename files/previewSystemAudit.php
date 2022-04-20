<?php
    session_start();
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>
<?php
 $htm="<img src='branchBanner/branchBanner1.png'>";
$htm.="<fieldset>";
    
    $htm.="<legend align=center><b>SYSTEM AUDIT REPORT</b></legend>";
        $htm.="<center>";
		$htm.= "<center><table width =100% border=0>";
		$htm.= "<tr>
			    <td width=3% style='text-align:left'><b>SN</b></td>
			    <td style='text-align:left'><b>EMP NAME</b></td>
                            <td style='text-align:left'><b>DESCRIPTION</b></td>
                            <td style='text-align:left'><b>DATE</b></td>
                            <td style='text-align:left'><b>LOCATION</b></td>
                            <td width='30%' style='text-align:left'><b>IP ADDR</b></td>
                            <td style='text-align:left'><b>PC NAME</b></td>
			    <td style='text-align:left'><b>BRANCH</b></td>
		     </tr>";
		    $htm.= "<tr>
				<td colspan=4></td></tr>";
                               //run the query to select all logs
                               $audit_record=mysqli_query($conn,"SELECT * FROM tbl_audit aud,tbl_employee emp,tbl_branches br
                                                         WHERE aud.Branch_ID=br.Branch_ID AND
                                                         aud.Employee_ID=emp.Employee_ID");
                               
                               $sn=1;
                               while($audit_record_row=mysqli_fetch_array($audit_record)){
                                //return data
                                
                                $employeeName=$audit_record_row['Employee_Name'];
                                $Description=$audit_record_row['Description'];
                                $Date_And_Time=$audit_record_row['Date_And_Time'];
                                $Location=$audit_record_row['Location'];
                                $IP_Address=$audit_record_row['IP_Address'];
                                $PC_Name=$audit_record_row['PC_Name'];
				$Branch_ID=$audit_record_row['Branch_ID'];
                                $Branch_Name=$audit_record_row['Branch_Name'];
				
				if($Description == "Authentication"){
                                    $select=mysqli_query($conn,"SELECT  Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Location' ");
                                    $row=mysqli_fetch_array($select);
                                    $Location=$row['Sub_Department_Name'];                                    
                                }
				
				
				
                                
                                //display the data
                                $htm.= "<tr>
                                        <td>$sn</td>
                                        <td>$employeeName</td>
                                        <td style='text-align:center'>$Description</td>
                                        <td style='text-align:left'>$Date_And_Time</td>
                                        <td width='20%' style='text-align:left'>$Location</td>
                                        <td width='20%' style='text-align:left'>$IP_Address</td>
                                        <td>$PC_Name</td>
					<td>$Branch_Name</td>
                                </tr>";
                                
                                $sn++;
                                
                               }
			       
			$htm.="</table>";
			$htm.="<table>";
			$htm.="</table>";
			$htm.="</center>";
			$htm.="</center>";
$htm.="</fieldset>";

include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 


?>



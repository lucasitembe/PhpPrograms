<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Engineering_Works'])){
	    if($_SESSION['userinfo']['Engineering_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $current_Employee_Name = $_SESSION['userinfo']['Employee_Name'];
?>
<a href='engineering_works.php?Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
        BACK
    </a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    $count_requisition = "SELECT COUNT(Jobcard_ID) as Jobcard FROM tbl_jobcards WHERE status = 'Pending'";
$counted_requisition = mysqli_query($conn,$count_requisition) or die(mysqli_error($conn));	
while($requisitionscount = mysqli_fetch_assoc($counted_requisition)){
    $AssignRequests = $requisitionscount['Jobcard']; 
}

$count_requisition = "SELECT COUNT(Jobcard_ID) as Jobcard FROM tbl_jobcards WHERE status = 'Certified'";
$counted_requisition = mysqli_query($conn,$count_requisition) or die(mysqli_error($conn));	
while($requisitionscount = mysqli_fetch_assoc($counted_requisition)){
    $AuthorizeRequests = $requisitionscount['Jobcard']; 
}
$count_requisition = "SELECT COUNT(Jobcard_ID) as Jobcard FROM tbl_jobcards WHERE status = 'Authorized'";
$counted_requisition = mysqli_query($conn,$count_requisition) or die(mysqli_error($conn));	
while($requisitionscount = mysqli_fetch_assoc($counted_requisition)){
    $ApproveRequests = $requisitionscount['Jobcard']; 
}

$count_requisition = "SELECT COUNT(Jobcard_ID) as Jobcard FROM tbl_jobcards WHERE status = 'Rejected'";
$counted_requisition = mysqli_query($conn,$count_requisition) or die(mysqli_error($conn));	
while($requisitionscount = mysqli_fetch_assoc($counted_requisition)){
    $RejectedRequests = $requisitionscount['Jobcard']; 
}

$count_requisition = "SELECT COUNT(Jobcard_ID) as Jobcard FROM tbl_jobcards WHERE status = 'Approved' and requesting_engineer = '$current_Employee_Name'";
$counted_requisition = mysqli_query($conn,$count_requisition) or die(mysqli_error($conn));	
while($requisitionscount = mysqli_fetch_assoc($counted_requisition)){
    $ApprovedRequests = $requisitionscount['Jobcard']; 
}

?>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>JOBCARDS MENU</b></legend>
        <center><table width = 60%>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='job_card.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Certify Job <?php if($AssignRequests > 0){ ?><span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $AssignRequests; ?></span><?php } ?>
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
             
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='Authorize_list.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Authorize JobCard <?php if($AuthorizeRequests > 0){ ?><span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $AuthorizeRequests; ?></span><?php } ?>
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='Approve_list.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Approve JobCard(s) <?php if($ApproveRequests > 0){ ?><span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $ApproveRequests; ?></span><?php } ?>
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='Rejected_jobcard_list.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Rejected JobCard(s) <?php if($RejectedRequests > 0){ ?><span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $RejectedRequests; ?></span><?php } ?>
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='approved_list.php?engineering_works=engineering_WorkThisPage.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Approved JobCard(s)
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
 
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
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
?>
<a href='./engineering_works.php?Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
        BACK
    </a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    $count_requisition = "SELECT COUNT(requisition_ID) as requisition FROM tbl_engineering_requisition WHERE requisition_status = 'pending'";
$counted_requisition = mysqli_query($conn,$count_requisition) or die(mysqli_error($conn));	
while($requisitionscount = mysqli_fetch_assoc($counted_requisition)){
    $AssignRequests = $requisitionscount['requisition']; 
}
?>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>ENGINEERING PROJECT MANAGEMENT</b></legend>
        <center><table width = 60%>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='labour_service_agreement.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Labour Service Agreement
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
             
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='approved_jobcard_engineering.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                        Civil Works - Cover Sheet
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                           Project Planner
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
                  
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
           
                    <a href='Jobcard_menu.php'>
                        <button style='width: 100%; height: 100%'>
                            Job Cards
                        </button>
                    </a>
                
                     
                        
                </td>
            </tr>
            
            
             
             <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                <a href='report_list_engineering.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Reports
                        </button>
                    </a>
                
                        
                </td>  
            </tr>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Certify_Job'] == 'yes') { ?>
                        <a href='usahihi_5_why_analysis_list.php?section=Engineering_Works=Engineering_WorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        5-Why Analysis (Usahihi) 
                                    </button>
                                </a>
        <?php } elseif ($_SESSION['userinfo']['Authorize_Job'] == 'yes') { ?>
                        <a href='usahihi_5_why_analysis_list.php?section=Engineering_Works=Engineering_WorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        5-Why Analysis (Usahihi) 
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    5-Why Analysis (Usahihi)
                                </button>
                                <?php
                            }?> 
                  </div>
                        
                </td>  
            </tr>               
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
           
                    <a href='project_menu.php'>
                        <button style='width: 100%; height: 100%'>
                            Projects
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
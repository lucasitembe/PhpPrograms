<?php
    include("./includes/header.php");
    // if(!isset($_SESSION['userinfo'])){
	// @session_destroy();
	// header("Location: ../index.php?InvalidPrivilege=yes");
    // }
    // if(isset($_SESSION['userinfo'])){
	// if(isset($_SESSION['userinfo']['Engineering_Works'])){
	//     if($_SESSION['userinfo']['Engineering_Works'] != 'yes'){
	// 	header("Location: ./index.php?InvalidPrivilege=yes");
	//     }
	// }else{
	//     header("Location: ./index.php?InvalidPrivilege=yes");
	// }
    // }else{
	// @session_destroy();
	//     header("Location: ../index.php?InvalidPrivilege=yes");
    // }
?>
<a href='./index.php?Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>
<!-- <script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script> -->
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>ENGINEERING WORKS</b></legend>
        <center><table width = 60%>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='received_engineering_requisition.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Job Assignment & Collection
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='received_engineering_requisition.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Job Assignment & Collection
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
             
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='assigned_requisition_engineering.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Maintenance & Procurement
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
                  
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
           
                    <a href='Job_Card.php'>
                        <button style='width: 100%; height: 100%'>
                            Job Card 
                        </button>
                    </a>
                
                     
                        
                </td>
            </tr>
            
            
             
             <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                        <button style='width: 100%; height: 100%'>
                           Material Received For Job
                        </button>
                    </a>
                
                        
                </td>  
            </tr>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <!--<a href='Job_Card.php?section=Rch&RchWorks=RchWorksThisPage'>-->
                        <button style='width: 100%; height: 100%'>
                           PPM
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
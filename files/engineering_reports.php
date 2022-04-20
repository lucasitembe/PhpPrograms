<?php
include("includes/connection.php");
include("includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

?>

<a href="managementworkspage.php?ManagementWorksPage=ThisPage" class='art-button-green'>BACK</a>

<br>
<br>
<br>
<br>
<fieldset>
    <legend align=center>ENGINEERING REPORTS</legend>

    <center><table width = 60%>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='kazi_mbalimbali.php?section=Engineering_Works=Engineering_WorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                           Engineering Report
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='mrv_dashboard.php?engineering_works=engineering_WorkThisPage'>
                        <button style='width: 100%; height: 100%'>
                           MRV Dashboard
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
        </table>
    </center>
<br>
<br>
</fieldset>
<br>
<br>
<br>

<?php
include("includes/footer.php");
?>
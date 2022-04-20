<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    ?>
<a href="human_resource.php?HRWork=HRWorkThisPage" class="art-button-green">BACK</a>
<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>APPROVAL CONFIGURATION</b></legend>
        <center>
            <table width = 60%>
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='add_document_approval_level.php'>
                            <button style='width: 100%; height: 100%'>
                            Add Approval Level Title
                            </button>
                        </a>  
                    </td>
                    <td style='text-align: center; height: 40px; width: 25%;'>
                        <a href='assign_approval_level_to_document.php'>
                            <button style='width: 100%; height: 100%'>
                                Assign Approval level To Documents
                            </button>
                        </a>  
                    </td>

                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 25%;' colspan="2">
                        <a href='assign_approval_level_to_employee.php'>
                            <button style='width: 100%; height: 100%'>
                                Assign Employee Approval Level
                            </button>
                        </a>  
                    </td>
                    

                </tr>
            </table>
        </center>
</fieldset>

<?php
    include("./includes/footer.php");
?>
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
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied! Only Engineers Allowed!");
   document.location = "./engineering_works1.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>ENGINEERING WORKS</b></legend>
        <center><table width = 60%>
            
        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                <a href='requisition_for_engineering.php'>
                        <button style='width: 100%; height: 100%'>
                            Request For Engineering Services
                        </button>
                    </a>
                   
                     
                        
                </td>  
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Engineering_Works'] == 'yes') { ?>
                        <a href='engineering_works.php?Engineering_Works=Engineering_WorksThisPage'>
                                    <button style='width: 100%; height: 100%'>
                                        Engineering Department Works 
                                    </button>
                                </a>
        <?php } else { ?>
                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                    Engineering Department Works
                                </button>
                                <?php
                            }?>
                  </div>
                        
                </td>  
            </tr>    
        </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
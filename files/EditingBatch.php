<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	$temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
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
<a href='bloodworkpage.php' class='art-button-green'>
        BACK
    </a>
<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>


<center>
    <table width=80%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW BATCH</b></legend>
                    <table>
                         
                            <tr> 
				
				<td style='text-align: center; height: 40px; width: 10%;'>
				    <a href='newbloodbatch.php?AddNewBatch=AddBatchThisPage'>
					<button style='width: 100%; height: 90%;'>
					    Add Batch
					</button>
				    </a>
				</td> 
			    
				<td style='text-align: center; height: 40px; width: 10%;'>
				    <a href='SearchBatch.php?EditBatch=EditBatchThisForm'>
					<button style='width: 100%; height: 90%'>
					    Edit Batch
					</button>
				    </a>
				</td>  
                            </tr> 
                    </table>
            </fieldset>
        </center></td></tr></table>
</center>









<?php
    include("./includes/footer.php");
?>
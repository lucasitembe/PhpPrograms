<?php
    include("./includes/header.php");
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
<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='laundryworkpage.php' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<br>
<br>
<br><br>
<br>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<style type="text/stylysheet">
	

</style>
<center>
<fieldset style="width:40%;">
<table>
		
		<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                   
                    <a href='laundryin.php'>
                        <button style='width: 100%; height: 100%'>
                            <b>LAUNDRY-IN</b>
                        </button>
                    </a>
                    
                </td>
				</tr>
	
			<tr>
				<td style='text-align: center; height: 40px; width: 33%;'>
						
							<a href='laundryoutpage.php' >
									
									<button style='width: 100%; height: 100%'>
									<b>LAUNDRY-OUT</b>
								</button>
								 
							   
							</a>
						   
				</td>
			</tr>
			<tr>
			<td style='text-align: center; height: 40px; width: 33%;'>
					
						<a href='laundrydisposed.php' >
							
								<button style='width: 100%; height: 100%'>
							   
								<b>LAUNDRY-DISPOSED</b>
							</button>
							 
							
						</a>
					   
				</td>
		</tr>
		
</table>
</fieldset>

</center>




<?php
    include("./includes/footer.php");
?>
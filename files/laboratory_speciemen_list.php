<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
    if(isset($_SESSION['userinfo']['Laboratory_Works'])){
        if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
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

            <!-- link menu -->


            <?php
                                                              if(isset($_SESSION['userinfo'])){
                                                                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
                                                                    { 
                                                                    echo "<a href='laboratory_setup_sample.php' class='art-button-green'>NEW SPECIMEN</a>";
                                                                    }
                                                            } 

                                                              if(isset($_SESSION['userinfo'])){
                                                                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
                                                                    { 
                                                                    echo "<a href='laboratory_setup_sample.php' class='art-button-green'>BACK</a>";
                                                                    }
                                                            }  
                                                    ?>


                                                    <script type='text/javascript'>
                                                        function access_Denied(){ 
                                                               alert("Access Denied");
                                                                      document.location = "./index.php";
                                                        }
                                                        </script>


<script language="javascript" type="text/javascript">
	function searchItem(Specimen_Name){
		document.getElementById('Search_Iframe').innerHTML = 
	"<iframe width='100%' height=380px src='laboratory_speciemen_list_ifram.php?Specimen_Name="+Specimen_Name+"'></iframe>";
	}
</script>

<br/>
<br/>
<center>
	<table style="width:40%;margin-top:5px;">
		<tr>
			<td>
				<input type='text' name='Search_Item' id='Search_Item' oninput='searchItem(this.value)'  placeholder='~~~~~~~~~~~~~~~~~~Search Specimen Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
			</td>

		</tr>

	</table>
	<br>
</center>
<fieldset>  
	<legend align=center>
		<b>
			 LABORATORY ITEM LIST
		</b>
	</legend>
<center>
	<table width='100%' border=1>
		<tr>
			<td id='Search_Iframe'>
					<iframe width='100%' height=380px src='laboratory_speciemen_list_ifram.php'></iframe>
			</td>
		</tr>
	</table>
</center>
</fieldset>
<br/>
<?php
include("./includes/footer.php");


if(isset($_GET['addnewsample'])){
    if($_GET['addnewsample'] == 'true'){
            if(filter_input(INPUT_GET, 'Action') =='Edit'){
       echo' <script type="text/javascript">
                alert("Specimen Edited Successfully");
        </script>';
            }else if(filter_input(INPUT_GET, 'Delete')){
        echo' <script type="text/javascript">
                alert("Specimen Deleted Successfully");
        </script>';   
            }


}else 
    if($_GET['addnewsample'] == 'false'){

        if(filter_input(INPUT_GET, 'Action') =='Edit'){
       echo' <script type="text/javascript">
                alert("Fail To Edit Specimen");
        </script>';
            }else if(filter_input(INPUT_GET, 'Delete')){
       echo' <script type="text/javascript">
                alert("Fail To Delete Specimen");
        </script>'; 
            }

}
}
                                                            ?>


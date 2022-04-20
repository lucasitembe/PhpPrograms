<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	$requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Laboratory_Works']))
		{
			if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            echo "<a href='laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage' class='art-button-green'>BACK</a>";
            }
    }
               

if(!isset($_GET['addlaboratorysample'])){
    ?>
<br/><br/><br/><br/><br/>
    <center>
            <fieldset>
            <legend align="center"><b>LAB CONFIGURATIONS</b></legend>               
            <table style="width:50%;margin_top:20px;">
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                            <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                                <a href='searchlabtestlist.php'>
                                    <button style='width: 100%; height: 100%'>
                                        Test Set-up
                                            </button>
                                                </a>
                                                    <?php
                                                         }else{
                                                                 ?> 
                                                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                        Test Set-up 
                                                                            </button> 
                                                                                <?php 
                                                                                    } 
                                                                                        }else{ 
                                                                                                ?> 
                                                                                                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                                                        Test Set-up 
                                                                                                            </button> 
                                                                                                                <?php
                                                                                                                     } 
                                                                                                                        ?>
                                                                                                                            </td>
                                                                                                                                 </tr>

                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                            <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                                <a href='searchlabtestlistparameter.php'>
                                    <button style='width: 100%; height: 100%'>
                                        Parameter Setting
                                            </button>
                                                </a>
                                                    <?php 
                                                        }else{
                                                             ?> 
                                                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                    Parameter Setting 
                                                                        </button> 
                                                                            <?php
                                                                                 } 
                                                                                    }else{ 
                                                                                        ?> 
                                                                                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                                                                                Parameter Setting 
                                                                                                    </button> 
                                                                                                        <?php
                                                                                                             }
                                                                                                                 ?>
                                                                                                                    </td>
                                                                                                                         </tr>


            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                    <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                    <a href='laboratory_setup_sample.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Manage Specimen
                        </button>
                    </a>
                    <?php }else{ ?> 
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Manage Specimen 
                        </button> 
                <?php } }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                       Manage Specimen 
                    </button> 
                <?php } ?>
                </td>
        </tr>

        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
            <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                <a href='laboratory_setup_parameters.php'>
                    <button style='width: 100%; height: 100%'>
                        Manage Parameter
                    </button>
                </a>
                <?php }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Manage Parameter
                    </button> 
            <?php } }else{ ?> 
                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                    Manage Parameter
                </button> 
            <?php } ?>
            </td>
        </tr>
        <tr>
            <td style='text-align: center; height: 40px; width: 33%;'>
            <?php if(isset($_SESSION['userinfo']['Laboratory_Works'])){ ?>
                <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                <a href='laboratoryItemList.php'>
                    <button style='width: 100%; height: 100%'>
                        Test Availability
                    </button>
                </a>
                <?php }else{ ?> 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                         Test Availability
                    </button> 
            <?php } }else{ ?> 
                <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                     Test Availability
                </button> 
            <?php } ?>
            </td>
        </tr>
	<tr>
	    <td style='text-align: center; height: 40px; width: 33%;'>
		<?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
		<a href='itemsconfiguration.php?Section=Laboratory&ItemsConfiguration=ItemConfigurationThisPage'>
		    <button style='width: 100%; height: 100%'>
			Items Configuration
		    </button>
		</a>
		<?php }else{ ?>
		 
		    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
			Items Configuration 
		    </button>
	      
		<?php } ?>
	    </td>
	</tr>
        <tr>
			<td style='text-align: center; height: 40px; width: 33%;'>
		<?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
		<a href='tatconfiguration.php?Section=Laboratory&ItemsConfiguration=ItemConfigurationThisPage'>
				<button style='width: 100%; height: 100%'>
			TAT Configuration
				</button>
		</a>
		<?php }else{ ?>

				<button style='width: 100%; height: 100%' onclick="return access_Denied();">
			TAT Configuration
				</button>

		<?php } ?>
			</td>
	</tr>
<tr>
            <td>
                <a href="rejection_reasons_configuration.php">
                    <button style='width: 100%; height: 100%'>
			        Rejection Reason Configuration
                    </button>
                </a>
            </td>
        </tr>
    </table>  
        </fieldset>   
        </center>
<?php
    }else if(isset($_GET['addlaboratorysample'])) {

    $sample_insert=mysqli_query($conn,"insert into tbl_laboratory_samples (Sample_Name) "
                                ." values('".$_POST['Sample_Name']."') ");

        if($sample_insert){
                echo 1;
        }else{
                echo 2;
        }
    }          
                

    include("./includes/footer.php");
?>

<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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

            <!-- link menu -->


            <?php
                                                              if(isset($_SESSION['userinfo'])){
                                                                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
                                                                    { 
                                                                    echo "<a href='laboratory_setup_parameters.php' class='art-button-green'>NEW PARAMETER</a>";
                                                                    }
                                                            }

                                                              if(isset($_SESSION['userinfo'])){
                                                                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
                                                                    { 
                                                                    echo "<a href='laboratory_setup_parameters.php' class='art-button-green'>BACK</a>";
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
                                                        function searchItem(Parameter_Name){
                                                            document.getElementById('Search_Iframe').innerHTML = 
                                                        "<iframe width='100%' height=320px src='laboratory_parameter_list_ifram.php?Parameter_Name="+Parameter_Name+"'></iframe>";
                                                        }
                                                    </script>

                                                    <br/>
                                                    <br/>
                                                    <center>
                                                        <table style="width:40%;margin-top:5px;">
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='Search_Item' id='Search_Item' onclick='searchItem(this.value)' onkeypress='searchItem(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~~Search Parameter Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
                                                                </td>

                                                            </tr>

                                                        </table>
														<br>
                                                    </center>
                                                    <fieldset>  
                                                                <legend align=center>
                                                                    <b>
                                                                         LABORATORY PARAMETER LIST
                                                                    </b>
                                                                </legend>
                                                            <center>
                                                                <table width='100%' border=1>
                                                                    <tr>
                                                                        <td id='Search_Iframe'>
                                                                                <iframe width='100%' height=420px src='laboratory_parameter_list_ifram.php'></iframe>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </center>
                                                            </fieldset>
                                                            <br/>
                                                            <?php
                                                                include("./includes/footer.php");
                                                            ?>
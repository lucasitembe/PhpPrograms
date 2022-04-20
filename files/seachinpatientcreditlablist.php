<?php
    include("./includes/laboratory_specimen_collection_header.php");
?>


                                                    <script language="javascript" type="text/javascript">
                                                        function searchPatient(Patient_Name){
                                                             var from_value =<?php echo filter_input(INPUT_GET, 'from'); ?>
                                                            document.getElementById('Search_Iframe').innerHTML = 
                                                        "<iframe width='100%' height=320px src='seach_inpatientcash_lablistiframe.php?from="+from_value+"Patient_Name="+Patient_Name+"'></iframe>";
                                                        }
                                                    </script>

                                                    <br/>
                                                    <br/>
                                                    <center>
                                                        <table style="width:40%;margin-top:5px;">
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
                                                                </td>

                                                            </tr>

                                                        </table>
                                                    </center>
                                                    <fieldset>  
                                                                <legend align=center>
                                                                    <b>
                                                                         OUTPATIENT
                                                                    </b>
                                                                </legend>
                                                            <center>
                                                                <table width='100%' border=1>
                                                                    <tr>
                                                                        <td id='Search_Iframe'>
                                                                                <iframe width='100%' height=420px src='seach_inpatientcash_lablistiframe.php?from=<?php echo filter_input(INPUT_GET, 'from'); ?>'></iframe>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </center>
                                                            </fieldset>
                                                            <br/>
                                                            <?php
                                                                include("./includes/footer.php");
                                                            ?>
<?php
if(isset($_GET['from']))
    if($_GET['from'] =='result'){
        include("./includes/laboratory_result_header.php");
}else if($_GET['from'] =='specimenCollection'){
        include("./includes/laboratory_specimen_collection_header.php");
}
?>
<fieldset style='margin-top:10px;'>
                            <legend align=right><b> OUTPATIENT CASH LAB LIST</b></legend>
                <script language="javascript" type="text/javascript">
                    function searchPatient(Patient_Name){
                        var from_value =<?php echo filter_input(INPUT_GET, 'from'); ?>
                        document.getElementById('Search_Iframe').innerHTML = 
                        "<iframe width='100%' height=320px src='seach_outpatientcash_lablistiframe.php?from="+from_value+"Patient_Name="+Patient_Name+"'></iframe>";
                    }
                </script>

                                                
                                                    <center>
                                                        <table  class="hiv_table" style="width:40%;margin-top:5px;">
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
                                                                </td>

                                                            </tr>

                                                        </table>
                                                    </center>
                                                    <center>
                                                        <hr width="100%">
                                                    </center>

                                                            <center>
                                                                <table  class="hiv_table" style="width:100%">
                                                                    <tr>
                                                                        <td id='Search_Iframe'>
                                                                                <iframe width='100%' height=430px src='seach_outpatientcash_lablistiframe.php?from=<?php echo filter_input(INPUT_GET, 'from'); ?>'></iframe>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </center>
                                                            </fieldset>
                                                            <br/>
                                                            <?php
                                                                include("./includes/footer.php");
                                                            ?>
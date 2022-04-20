<?php
if(isset($_GET['from']))
    if($_GET['from'] =='result'){
        include("./includes/laboratory_result_header.php");
}else if($_GET['from'] =='specimenCollection'){
        include("./includes/laboratory_specimen_collection_header.php");
}
?>
<?php
//get sub department id
$Sub_Department_ID='';
if(isset($_SESSION['Laboratory'])){
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while($row = mysqli_fetch_array($select_sub_department)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
}else{
    $Sub_Department_ID = '';
}
?>
<fieldset style='margin-top:10px;'>
                            <legend align=right><b> OUTPATIENT CREDIT LAB LIST</b></legend>
                <script language="javascript" type="text/javascript">
                    function searchPatient(){
			var Patient_Name=document.getElementById("Patient_Name").value;
                        var Sub_Department_ID='<?php echo $Sub_Department_ID;?>';
                        document.getElementById('Search_Iframe').innerHTML = 
                             "<iframe width='100%' height=420px src='seach_outpatientcredit_lablistiframe.php?Patient_Name="+Patient_Name+"&Sub_Department_ID="+Sub_Department_ID+"'></iframe>";
                        
                    }
                </script>

                                                
                                                    <center>
                                                        <table  class="hiv_table" style="width:40%;margin-top:5px;">
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='' id='Patient_Name' onkeyup="searchPatient(this.value)" placeholder='~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
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
                                                                                <iframe width='100%' height=420px src='seach_outpatientcredit_lablistiframe.php?Patient_Name="<?echo "";?>"&Sub_Department_ID=<?php echo $Sub_Department_ID?>&from=<?php echo filter_input(INPUT_GET, 'from'); ?>'></iframe>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </center>
                                                            </fieldset>
                                                            <br/>
                                                            <?php
                                                                include("./includes/footer.php");
                                                            ?>
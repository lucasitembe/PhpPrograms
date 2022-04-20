<?php
    // include("../includes/connection.php");
    include("./includes/connection.php");
    include("./includes/header.php");
    session_start();
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_GET['Patient_Payment_ID'])) {
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    } else {
        $Patient_Payment_ID = 0;
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    } else {
        $Patient_Payment_Item_List_ID = 0;
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Surgery = $new_Date;
    }

    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = $Today.' 00:00';
    }



    if(isset($_GET['Payment_Item_Cache_List_ID'])){
        $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
        $Payment_Item_Cache_List_ID = 0;
    }
// $Doctor_Comment="";
    //get Payment_Cache_ID and consultation_id
    $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID,ilc.Doctor_Comment from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $consultation_id = $data['consultation_id'];
            $consultation_id_to_use=$consultation_id;
            $Doctor_Comment = $data['Doctor_Comment'];
        }
    }else{
        $Payment_Cache_ID = 0;
        $consultation_id = 0;
    }

    //get procedure name
    $select = mysqli_query($conn,"SELECT Product_Name, ilc.Status, ilc.Item_ID from tbl_items i, tbl_item_list_cache ilc where
                            i.Item_ID = ilc.Item_ID and
                            ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($dt = mysqli_fetch_array($select)) {
            $Product_Name = $dt['Product_Name'];
            $Status = $dt['Status'];
            $Item_ID = $dt['Item_ID'];
        }
    }else{
        $Product_Name = '';
        $Status = '';
    }
    $select_item_template = mysqli_query($conn, "SELECT * FROM tbl_template_report_nm WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_item_template)>0){
        while($rows = mysqli_fetch_assoc($select_item_template)){
            $findings = $rows['findings'];
            $procedure_done = $rows['procedure_done'];
            $protocal = $rows['protocal'];
            $functions = $rows['functions'];
            $phase = $rows['phase'];
            $Template_ID = $rows['Template_ID'];
        }
    }
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];

    //get patient details
    $select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from 
                            tbl_patient_registration pr, tbl_sponsor sp where 
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Patient_Name = $data['Patient_Name'];
            $Gender = $data['Gender'];
            $Date_Of_Birth = $data['Date_Of_Birth'];
            $Guarantor_Name = $data['Guarantor_Name'];
            $Member_Number = $data['Member_Number'];
        }
    }else{
        $Patient_Name = '';
        $Gender = '';
        $Date_Of_Birth = '';
        $Guarantor_Name = '';
        $Member_Number = '';
    }

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $Age = $diff->y." Years, ";
    $Age .= $diff->m." Months, ";
    $Age .= $diff->d." Days";

    
?>

<a href="#" onclick="goBack()"class="art-button-green">BACK</a>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
</style>

<script>
function goBack() {
    window.history.back();
}
</script>
<script src="editor/js/jquery1.js"></script>
  <script src="editor/js/jquery2.js"></script>
  <script src="editor/js/jquery3.js"></script>
  <!-- include libs stylesheets -->
  <link rel="stylesheet" href="editor/css/css1.css" />

  <!-- include summernote -->
  <link rel="stylesheet" href="editor/css/summernote-bs4.css">
  <script type="text/javascript" src="editor/js/summernote-bs4.js"></script>

  <link rel="stylesheet" href="editor/css/example.css">
  
  <script type="text/javascript">
    $(document).ready(function() {
      $('.summernote').summernote({
        height: 400,
        tabsize: 2
      });
    });
  </script>
<fieldset>
    <legend align='center'><b>NUCLEAR MEDICINE SCAN</b></legend>
    <table width="100%"> 
        <tr><td  width="9%" style="text-align: right;">Patient Name</td>
        <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
        <td width="9%" style="text-align: right;">Sponsor Name</td>
        <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        <td style="text-align: right;">Gender</td>
        <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
        <td style="text-align: right;">Age</td>
        <td><input type="text" value="<?php echo $Age; ?>" readonly="readonly"></td>
        <td style="text-align:right;" >Procedure Date</td>
        <td><input type="text" autocomplete="off" name="Procedure_Date" id="Procedure_Date" value="<?php echo $Surgery_Date; ?>" readonly="readonly"></td>
        </tr>
        </table>
        <hr width="100%"/>

    <table width="100%">        
            <tr width="100%">
                <td style="width:13%; text-align:right" >Clinical Summary:</td>
                <td width='85%'>
                        <textarea readonly="readonly" rows='1'> <?= $Doctor_Comment ?></textarea>
                </td>
            </tr>
    </table>   

</fieldset>
<fieldset>
    <table width=100%>
        <tr>            
            <td width=40%>
                
                <label for="">SCAN:</label>
                <span style="">
                    <input id='Procedure_Name'  name='Procedure_Name' required='required' style="display:inline; width: 80%;" readonly="readonly" value="<?php echo $Product_Name; ?>">
                </span>                
            </td>
            <td style="text-align:right;" width="30%">
                <label for="">Date Done:</label>
                <span>
                    <input type="text" value="<?php echo $Today; ?>" style="display:inline; width: 70%;">
                </span>
            </td>
            <td width="30%">
                <label for="">Performed By</label>
                <input type="text" readonly="readonly" style="display:inline; width: 70%;" value="<?php echo $Employee_Name; ?>">
            </td>
        </tr> 
    </table>
</fieldset>
<fieldset>
    <center>
                
            <?php 
                if($protocal=='Yes'){
                    echo "<table  width='100%'>
                            <tr>
                                <td>
                                    <label for=''>Protocal</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Protocal[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Radiopharmathetical:</label>
                                    <input type='text'  style='display:inline; width: 70%;'  name='Protocal[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Inj. Site:</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Protocal[]' value=''>
                                </td>
                            </tr>
                        </table>
                        STRESS PROTOCAL
                        <table  width='100%'>
                            <tr>
                                <td>
                                    <label for=''>Stress Protocal</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Stress[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Resting pulse:</label>
                                    <input type='text'  style='display:inline; width: 70%;'  name='Stress[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Resting BP:</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Stress[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Caffeine intake</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Stress[]' value=''>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for=''>Dose:</label>
                                    <input type='text'  style='display:inline; width: 70%;'  name='Stress[]' value=''>
                                </td>
                                <td>
                                    <label for=''>BP after infusion BP:</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Stress[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Side effects:</label>
                                    <input type='text'  style='display:inline; width: 70%;'  name='Stress[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Aminophylline:</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Stress[]' value=''>
                                </td>
                            </tr>
                        </table>
                        ";
                }else{
                    echo "<table  width='100%'>
                            <tr>                                
                                <td>
                                    <label for=''>Radiopharmathetical:</label>
                                    <input type='text'  style='display:inline; width: 70%;'  name='Protocal[]' value=''>
                                </td>
                                <td>
                                    <label for=''>Inj. Site:</label>
                                    <input type='text'  style='display:inline; width: 70%;' name='Protocal[]' value=''>
                                </td>
                            </tr>
                        </table>
                        <table width='100%'>        
                            <tr>
                                <td width='100%'>
                                    <label>Technical Quality:</label>
                                    <textarea rows='1' class='form-control' id='' name='Protocal[]' style='display:inline; width: 85%;' placeholder='Conclusion'></textarea>
                                </td>                
                            </tr>
                        </table>
                        ";
                } 

                if($findings == 'Yes'){
                    echo "SCHINTIGRAPHIC FINDINGS";
                    echo "<table width='100%'>        
                    <tr>
                        <td width='100%'>
                            <label>Technical Quality:</label>
                            <textarea rows='1' class='form-control' id='' name='Protocal[]' style='display:inline; width: 95%;' placeholder='Technical Quality'></textarea>
                        </td>                
                    </tr>
                    <tr>
                        <td width='100%'>
                            <label>$Product_Name:</label>
                            <textarea rows='1' class='form-control' id='' name='Protocal[]' style='display:inline; width: 85%;' placeholder='$Product_Name'></textarea>
                        </td>                
                    </tr>
                </table>";
                }else{
                    echo "<table width='100%'>        
                            <tr>
                                <td width='100%'>
                                    <label>Findings:</label>
                                    <textarea rows='1' class='form-control' id='' name='Protocal[]' style='display:inline; width: 95%;' placeholder='Findings'></textarea>
                                </td>                
                            </tr>
                        </table>";
                }

            ?>

        <table>
        <tr>
            <td>
                <label for="">GATED IMAGES</label>
                <input type="file" name="Gated_images" id="">
            </td>
        </tr>
        </table>
        <table width="100%">        
            <tr>
                <td width="100%">
                    <label>Conclusion:</label>
                    <textarea rows="1" class="form-control" id="" name="conclusion[]" style="display:inline; width: 95%;" placeholder="Conclusion"><?= $summary_of_assessment_bfr_procedure ?></textarea>
                </td>                
            </tr>
        </table>
    </center>
</fieldset>


<div class="container">
    <h1>Summernote with Bootstrap 5</h1>  
    <div class="summernote"><p>Hello World</p></div>
</div>

<?php
    include("../includes/footer.php");
?>

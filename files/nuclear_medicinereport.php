<?php
    // include("../includes/connection.php");
    include("./includes/connection.php");
    include("./includes/header.php");
    session_start();
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_GET['Payment_Cache_ID'])) {
        $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    } else {
        $Payment_Cache_ID = 0;
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
    $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID,ilc.Doctor_Comment from   tbl_item_list_cache ilc, tbl_payment_cache pc where   pc.Payment_Cache_ID = ilc.Payment_Cache_ID and   Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
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
    $select = mysqli_query($conn,"SELECT Product_Name, ilc.Status, ilc.Item_ID from tbl_items i, tbl_item_list_cache ilc where  i.Item_ID = ilc.Item_ID and  ilc.Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
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
    $select = mysqli_query($conn,"SELECT Patient_Name, Gender, Date_Of_Birth, Guarantor_Name, Member_Number from   tbl_patient_registration pr, tbl_sponsor sp where    pr.Sponsor_ID = sp.Sponsor_ID and    Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
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

<a href="nuclear_medicineassessmentform.php?Registration_ID=<?=$Registration_ID?>&Item_List_ID=<?=$Payment_Item_Cache_List_ID?>" class="art-button-green">ASSESSMENT FORM</a>

<a href="#" onclick="goBack()"class="art-button-green">BACK</a>


<br/><br/>
<style>
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    input[type='radio'] { 
            width: 25px; 
            height: 25px; 
        } 
    input[type='radio'] { 
        width: 25px; 
        height: 25px; 
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
        tabsize: 2,
        width:1350
      });
    });
  </script>
<fieldset>
    <legend align="center"><b>NUCLEAR MEDICINE SCAN</b></legend>
    <table width="100%"> 
        <tr><td  width="9%" style="text-align: right;"><b>Patient Name</b></td>
        <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
        <td width="9%" style="text-align: right;"><b>Sponsor Name</b></td>
        <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        <td style="text-align: right;"><b>Gender</b> </td>
        <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
        <td style="text-align: right;"><b>Age</b></td>
        <td><input type="text" value="<?php echo $Age; ?>" readonly="readonly"></td>
        <td style="text-align:right;" ><b>Procedure Date </b></td>
        <td><input type="text" autocomplete="off" name="Procedure_Date" id="Procedure_Date" value="<?php echo $Surgery_Date; ?>" readonly="readonly"></td>
        </tr>
        <tr width="100%">
            <td style="text-align:right" ><b>Clinical Summary:</b></td>
            <td colspan='9' >
                <textarea readonly="readonly" style="display:inline; width: 100%;" rows='1'> <?= $Doctor_Comment ?></textarea>
            </td>
        </tr>
        </table>
     
    <table width="100%" >        
        <tr>            
            <td width=40% style="text-align:right">
                
                <label for="">SCAN:</label>
                <span >
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
<style>
     input[type="radio"]{
                    width: 5px; 
                    height: 5px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
    #imagesection{
        padding-bottom: 100px;
    }
</style>
<fieldset>
    <legend>SCAN PROCEDURE AND REPORT RESULTS </legend>
<div class="fluid">

    <?php 
    
    $select_report = mysqli_query($conn, "SELECT FindsReport,Employee_Title,attachment, Employee_Name FROM tbl_nuclear_medicine_report mr, tbl_employee e WHERE e.Employee_ID=mr.Employee_ID AND Registration_ID='$Registration_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    if(mysqli_num_rows($select_report)>0){
        while($report_rw = mysqli_fetch_assoc($select_report)){
            $FindsReport  = $report_rw['FindsReport'];
            $Employee_Title = $report_rw['Employee_Title'];
            $Employee_Name = $report_rw['Employee_Name'];
            $attachment = $report_rw['attachment'];
            echo "<div class='col-md-12'>$FindsReport </div>
            
                <div class='col-md-12'>Consultant Name:  $Employee_Name <br/>Employee Title:  $Employee_Title 
               
              </div>";

              echo "<br/>";
                echo "<a href='print_nuclear_md_report.php?Registration_ID=$Registration_ID&Payment_Item_Cache_List_ID=$Payment_Item_Cache_List_ID' target='blank' class='art-button pull-right'>PRINT REPORT</a>
                
            ";

        }
    }else{
    
    ?>
    <textarea class="summernote" style="width:90%;" id="sumernotes" name="summernotes">
   
    <br>
    <p>Conclusion:</p>
    
    </textarea>
     
     <div class="row " style="padding: 20px;">
         <input type="file" name="" id="attachmentment">
        <input type="button" class="art-button-green" value="UPLOAD ATTACHMENT" onclick="uploadattachmentfiles()"> 
    
     </div>
     
     
    <input type="button" value="SAVE" class="art-button-green" id="btnsavesummernots" >
<?php } ?>
</div>

</fieldset>
<fieldset style="height:200px;margin:auto;  overflow-x:hidden;overflow-y:scroll;">
    <legend>ATTACHMENT UPLOADED</legend>
    <div id="imagesection" ></div>
</fieldset>

<div id="showdata" style="height:450px;margin:auto;  overflow-x:hidden;overflow-y:scroll;display:none ">
    <div id="my">
    </div>
</div>
<div id="checklistform"></div>

<script>
    $('#btnsavesummernots').click(function (){
        // var attachmentment = $("#attachmentment").val();
        // if(attachmentment !=''){          
        //     uploadattachmentfiles();
           
        // }else{    
            var attachment= "Not uploaded";      
            save_summernotes(attachment);            
        // }        
    })

    </script>

    <script>

    function save_summernotes(attachment){
       var summernote = $("#sumernotes").val();
       var Registration_ID = '<?= $Registration_ID ?>';
       var Payment_Item_Cache_List_ID = '<?= $Payment_Item_Cache_List_ID ?>';
        if(confirm("Are you sure you want to save this info")){
            $.ajax({
                type:'POST',
                url:'Nm/item_report_setup.php',
                data:{summernote:summernote,Registration_ID:Registration_ID,attachment:attachment, Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,  savesummernote:''},
                success:function(responce){
                    alert(responce); 
                    location.reload()                             
                }
            })
        }
    }

    function uploadattachmentfiles(){
        var file_input= $("#attachmentment").val();
        var fd = new FormData();
        var files = $('#attachmentment')[0].files[0];
        fd.append('file',files);
        
        $.ajax({
            type:'POST',
            url:'nm_image_upload.php',
            data:fd,
            processData: false,
            contentType: false,
            success:function(responce){  
                if(responce=='Nothing')  {
                    alert("Failed To upload try again or contact System administrator");
                } else{          
                    saveImages(responce);
                }             
            }
        });
    }

    function saveImages(attachment){
        var Registration_ID='<?= $Registration_ID ?>'; 
        var Payment_Item_Cache_List_ID='<?= $Payment_Item_Cache_List_ID ?>'; 
        $.ajax({
            type:'POST',
            url:'nuclear_medicine_image_upload.php',
            data:{attachment:attachment, Registration_ID:Registration_ID, Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID, save_image:''},           
            success:function(responce){  
                load_image_attached();            
            }
        });
    }

    function load_image_attached(){
        var Registration_ID='<?= $Registration_ID ?>'; 
        var Payment_Item_Cache_List_ID='<?= $Payment_Item_Cache_List_ID ?>'; 
        $.ajax({
            type:'POST',
            url:'nuclear_medicine_image_upload.php',
            data:{Registration_ID:Registration_ID, Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID, load_image:''},           
            success:function(responce){  
                $("#imagesection").html(responce);          
            }
        });
    }
    function removeImage(Pic_ID){
        $.ajax({
            type:'POST',
            url:'nuclear_medicine_image_upload.php',
            data:{Pic_ID:Pic_ID, remove_image:''},           
            success:function(responce){  
                console.log(responce)
                load_image_attached();            
            }
        });
    }

    $(document).ready(function(){
        load_image_attached();
    })
</script>

<script>
    function open_therapychecklist(){
        var Name = '<?php echo $Patient_Name; ?>';
        var Id = '<?php echo $Registration_ID; ?>';
        var Age = '<?php echo $Age; ?>';
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{therapychecklist:''},
            success:function(data){
                $("#checklistform").dialog({
                    title: "DAY OF THERAPY CHECKLIST | NAME: "+Name+" | FILE NO: "+Id+" | AGE: "+Age,
                    width: '80%',
                    height: 650,
                    modal: true,
                })
                $("#checklistform").html(data);
                
            }
        })
    }

    function open_assessmentform(){
        var Name = '<?php echo $Patient_Name; ?>';
        var Id = '<?php echo $Registration_ID; ?>';
        var Age = '<?php echo $Age; ?>';
        $.ajax({
            type:'POST',
            url:'Nm/item_report_setup.php',
            data:{assessmentform:''},
            success:function(data){
                $("#checklistform").dialog({
                    title: "DAY OF THERAPY CHECKLIST | NAME: "+Name+" | FILE NO: "+Id+" | AGE: "+Age,
                    width: '80%',
                    height: 650,
                    modal: true,
                })
                $("#checklistform").html(data);
                
            }
        })
    }
</script>


<script>
    function CloseImage() {
        document.getElementById('imgViewerImg').src = '';
        document.getElementById('imgViewer').style.visibility = 'hidden';
    }

    function zoomIn(imgId, inVal) {
        if (inVal == 'in') {
            var zoomVal = 10;
        } else {
            var zoomVal = -10;
        }
        var Sizevalue = document.getElementById(imgId).style.width;
        Sizevalue = parseInt(Sizevalue) + zoomVal;
        document.getElementById(imgId).style.width = Sizevalue + '%';
    }
    
    function uploadImages() {
        $('#radimagingform').ajaxSubmit({
            beforeSubmit: function () {
                //alert('submiting');
            },
            success: function (result) {
                // alert(result);
                var data = result.split('<1$$##92>');
                if (data[0] != '') {
                    alert(data[0]);
                }
                // alert(data[1]);
                $('#my').html(data[1]);

            }

        });
        return false;
    }

    function radiologyviewimage(Registration_ID, Payment_Item_Cache_List_ID) {        
        $.ajax({
            type: 'GET',
            url: 'image_upload.php',
            data:{ Registration_ID:Registration_ID,Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID},
            success: function (result) {
                $('#showdata').dialog({
                    title:"PATIENT RADIOLOGY IMAGING",
                    modal: true,
                    width: '90%',
                    height: 450,
                    resizable: true,
                    draggable: true,
                }) 
                $("#my").html(result); 
                // var data = result.split('<1$$##92>');
                // if (data[0] != '') {
                //     alert(data[0]);
                // }
                // $('#my').html(data[1]);
            }, error: function (err, msg, errorThrows) {
                alert(err);
            }
        });
            
    }
    </script>

<?php
    include("../includes/footer.php");
?>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<!-- <script src="media/js/jquery.js" type="text/javascript"></script> -->
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<!-- <script src="css/jquery-ui.js"></script> -->

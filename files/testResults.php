<style>
.prevHistory:hover {
    cursor: pointer;
}
</style>
<?php
include("../includes/connection.php");

$Registration_ID=$_GET['Registration_ID'];
$returnLastVisitID = mysqli_query($conn,"SELECT Folio_Number FROM tbl_payment_cache WHERE Registration_ID='" . $Registration_ID . "' ORDER BY Payment_Cache_ID DESC LIMIT 1");

$rs = mysqli_fetch_assoc($returnLastVisitID);

$Folio_Number = $rs['Folio_Number'];

//$filter=' AND DATE(tpr.TimeSubmitted) = DATE(NOW()) ';
    
          $Date_From=  filter_input(INPUT_GET, 'Date_From');
          $Date_To=  filter_input(INPUT_GET, 'Date_To');
          
         
       
        if(isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)){
          $filter="  AND tpr.TimeSubmitted BETWEEN '". $Date_From."' AND '".$Date_To."'";
        }
        
        //echo $filter;exit;

 $hospitalConsultType=$_SESSION['hospitalConsultaioninfo']['consultation_Type'];
    $emp='';
    $doctors_selected_clinic = $_SESSION['doctors_selected_clinic'];
	$Laboratory =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Laboratory FROM tbl_clinic c WHERE c.Clinic_ID='$doctors_selected_clinic'"))['Laboratory'];
	
if ($hospitalConsultType == 'One patient to one doctor') {
    
    if(isset($_GET['consultation_id']) && is_numeric($_GET['consultation_id'])){
       $emp = " AND consultation_id='" . $_GET['consultation_id']. "'"; 
    }else{
      $emp = " AND Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'";
    }
}

        if($Laboratory == "Yes"){
        $Query = "SELECT * FROM tbl_item_list_cache 
                INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
                INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
                JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID 
                JOIN tbl_tests_parameters_results AS tpr ON tpr.ref_test_result_ID=tbl_test_results.test_result_ID
                WHERE Registration_ID='" . $Registration_ID . "'   AND tpr.Submitted='Yes' GROUP BY tpr.ref_test_result_ID ORDER BY Payment_Item_Cache_List_ID DESC";
        }else{
            $filter=' AND DATE(tpr.TimeSubmitted) = DATE(NOW()) ';
            $Query = "SELECT * FROM tbl_item_list_cache 
                INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
                INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
                JOIN  tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID 
                JOIN tbl_tests_parameters_results AS tpr ON tpr.ref_test_result_ID=tbl_test_results.test_result_ID
                WHERE Registration_ID='" . $Registration_ID . "' $emp $filter  AND tpr.Submitted='Yes' GROUP BY tpr.ref_test_result_ID  ORDER BY Payment_Item_Cache_List_ID DESC";

        }
//die($Query);
$QueryResults = mysqli_query($conn,$Query) or die(mysqli_error($conn));
echo "<center><table id='labresult' style='width:100%'>";
echo "<thead>
                <tr style='background-color:rgb(200,200,200)'>
                    <th width='1%'><b>S/N</b></th>
                    <th align='right'><b>Test Name</b></th>
                    <th width='20%'><b>Doctor's Notes</b></th>
                    <th width='15%'><b>Lab Remarks</b></th>
                    <th width='8%'><b>Micro-Biology</b></th>
                    <th width='10%'><b>Intergrated Lab Result</b></th>
                    <th width='2%'><b>Attachment</b></th>
                    <th width='5%'><b>Status</b></th>
                    <th width='1%'><b>Results</b></th>
                    <th width='10%'>Date</th>
                </tr>
            </thead><tbody>";
$i = 1;
$select_datetoday = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CURDATE() as today" ))['today'];

if (mysqli_num_rows($QueryResults) > 0) {
    while ($row = mysqli_fetch_assoc($QueryResults)) {
        $st = '';
        
        $select_date= $row['Transaction_Date_And_Time'];
        $selected_date = mysqli_fetch_assoc(mysqli_query($conn, "SELECT DATE($select_date) as selected_date" ))['selected_date'];

        $RS = mysqli_query($conn,"SELECT Submitted,Validated FROM tbl_tests_parameters_results AS tpr WHERE ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $rowSt = mysqli_fetch_assoc($RS);
        
        $totalParm=  mysqli_num_rows($RS);
        $result="";
        
        $postvQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'positive' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $positive= mysqli_num_rows($postvQry);
        
        $negveQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'negative' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $negative = mysqli_num_rows($negveQry);
        
        $abnormalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'abnormal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $abnormal = mysqli_num_rows($abnormalQry);
        
        $normalQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'normal' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $normal= mysqli_num_rows($normalQry);
        
        $highQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'high' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $high = mysqli_num_rows($highQry);
        
        $lowQry = mysqli_query($conn,"SELECT result FROM tbl_tests_parameters_results AS tpr WHERE result = 'low' AND ref_test_result_ID='" . $row['test_result_ID'] . "'")or die(mysqli_error($conn));
        $low = mysqli_num_rows($lowQry);
        
        if($positive == $totalParm){
           $result="Positive"; 
        }elseif ($negative == $totalParm) {
           $result="Negative";  
        }elseif ($abnormal == $totalParm) {
           $result=  "Abnormal";  
        }elseif ($normal == $totalParm) {
           $result="Normal";  
        }elseif ($high == $totalParm) {
           $result="High";  
        }elseif ($low == $totalParm) {
           $result="Low";  
        }
        
        $Submitted = $rowSt['Submitted'];
        $Validated = $rowSt['Validated'];
        if ($Validated == 'Yes') {
            $st = '<span style="color:blue;text-align:center;font-size: 14px;font-weight: bold;">Done</span>';
        } else {
            $st = '<span style="text-align:center;color: red;font-size: 14px;font-weight: bold;">Provisional</span>';
        }
        $allveralComment='';
        //retrieve attachment info
        $query = mysqli_query($conn,"select * from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='" . $row['Payment_Item_Cache_List_ID'] . "'");
        $image = '';
         while ($attach = mysqli_fetch_array($query)) {
                if ($attach['Attachment_Url'] != '') {
                    $image .= "<a href='patient_attachments/" . $attach['Attachment_Url'] . "' class='fancybox2' rel='gallery' target='_blank' title='" . $row['Product_Name'] . "'><img src='patient_attachments/" . $attach['Attachment_Url'] . "' width='30' height='15' alt='Not Image File' /></a>&nbsp;&nbsp;";
                }
                
                $allveralComment = $attach['Description'];
        }

         $hide_btn="";
         $Product_Name=$row['Product_Name'];
         $Item_ID=$row['Item_ID'];
         $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
        
        $sql_select_patient_lab_intergrate_result=mysqli_query($conn,"SELECT * FROM tbl_intergrated_lab_results WHERE sample_test_id='$Payment_Item_Cache_List_ID' AND sent_to_doctor='yes'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_patient_lab_intergrate_result)<=0){
            $hide_btn="class='hide'";
        }else{
            $hide_btn="class='art-button-green'";
        }
       if($i==1){
           $background = "style='background:green;'";
       }else{
        $background = "style='background:white;'";
       }
       
        echo "<tr $background>";
        echo "<td>" . $i++ . "</td>";
        echo "<td style='text-align:left'>" . $row['Product_Name'] . "</td>";
        echo "<td >" . $row['Doctor_Comment'] . "</td>";
        echo "<td >" . $allveralComment . "</td>";
        echo "<td ><input type='button' class='culture' name='" . $row['Product_Name'] . "' id='" .$Payment_Item_Cache_List_ID. "'  onclick='doctortestResult(\"$Payment_Item_Cache_List_ID\",\"$Item_ID\")'   value='Micro-Biology' /></td>";
        echo "<td style='text-align:center'><input type='button' $hide_btn onclick='preview_lab_result(\"$Product_Name\",\"$Payment_Item_Cache_List_ID\")' value='View Result'></td>";
        echo "<td style='text-align:center'>" . $image . "</td>";
        echo "<td>" . $st . "</td>";
      
        if(!empty($result)){
            echo "<td style='background-color: white; text-align: center; color: rgb(101, 82, 18); font-weight: bold; font-size: 12px;'>".$result."</td>"; 
        }  else {
            echo "<td><input type='button' class='generalresltsdoctor' name='" . $row['Product_Name'] . "' ppil='" . $row['Payment_Item_Cache_List_ID'] . "' patientID='" . $Registration_ID . "' id='" . $row['Item_ID'] . "' value='Results'></td>";
        }
        echo "<td >".$row['Transaction_Date_And_Time']."</td>";
         echo "</tr>";
    }
} else {
   // echo '<tr><td colspan="9" style="text-align:center;font-size:20px;color:red">You do not have result for this patient</td></tr>';
}

echo "</tbody></table></center>";
?>

<!--Culture results-->
<div id="cultureResult">
    <div id="cultureStatus"></div>
    <div id="showCulture">

    </div>
</div>
<style>
.modificationStatsdoctor:hover {
    text-decoration: underline;
    cursor: pointer;
    color: rgb(145, 0, 0);
}

.prevHistory:hover {
    text-decoration: underline;
    cursor: pointer;
    color: rgb(145, 0, 0);
}
</style>
<script>
function doctortestResult(payment, test) {
    $.ajax({
        type: 'GET',
        url: './laboratory/views/reports/macro_micro_doctor_view.php',
        data: {
            paymentID: payment,
            test: test
        },
        cache: false,
        success: function(html) {
            // console.log(html);
            $('#showCulture').html(html);
        }
    });
    //        $('#cultureResult').dialog({
    //            modal: true,
    //            width: '99%',
    //            minHeight: 455,
    //            resizable: true,
    //            draggable: true,
    //        });
    //        $("#cultureResult").dialog('option', 'title', name);

    $('#showCulture').dialog({
        modal: true,
        width: '99%',
        minHeight: 455,
        resizable: true,
        draggable: true,
    });
    $("#showCulture").dialog('option', 'title', 'Micro-Biology Result');

}
$('.generalresltsdoctor').click(function() {
    var name = $(this).attr('name');
    var patientID = $(this).attr('patientID');
    //alert('alert');
    $('#showGeneral').html();
    var id = $(this).attr('id');
    var ppil = $(this).attr('ppil');
    $.ajax({
        type: 'POST',
        url: 'requests/LabResultsDoctorView.php',
        data: 'generalResult=getGeneral&id=' + id + '&patientID=' + patientID + '&ppil=' + ppil,
        cache: false,
        success: function(html) {
            // alert(html);
            $('#showGeneral').html(html);
        }
    });
    $('#labGeneral').dialog({
            modal: true,
            width: '90%',
            minHeight: 450,
            resizable: true,
            draggable: true,
            title: name
        }).dialog("widget")
        .next(".ui-widget-overlay")
        .css("background-color", "rgb(255,255,255)");

    //$("#labGeneral").dialog('option', 'title', testName);

    $('#labResults').fadeOut(100);
});

$('.validateResult').click(function() {
    var payment = $('.paymentID').val();
    var productID = $('.productID').val();
    var parId, result;
    var i = 1;
    var datastring;
    var total = $('.Resultvalue').length;
    var temp = 0;

    $('.Resultvalue').each(function() {
        parId = $(this).attr('id');
        result = $(this).val();

        if (result === '') {
            temp = temp + 1;

        }
    });

    if (temp === total) {
        alert("Please add atleast one result");
        exit();
    }

    $('.Resultvalue').each(function() {
        parId = $(this).attr('id');
        result = $(this).val();



        if ($(this).val() !== '') {
            if (i == 1) {
                datastring = parId + '#@' + result;
            } else {
                //paraID+=","+$(this).val();
                datastring += "$>" + parId + '#@' + result;
            }
        }
        i++;

    });
    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestResults.php',
        data: 'SavegeneralResult=getGeneral&testresults=' + datastring + '&payment=' + payment +
            '&productID=' + productID,
        cache: false,
        success: function(html) {
            $('#showGeneral').html(html);
        }
    });
});

$('.validateSubmittedResult').on('click', function() {
    $('.validated').attr('checked', true);
    var itemID = $('.productID').val();
    var parameterID, testID;
    var i = 1;
    var datastring;
    $('.validated').each(function() {
        parameterID = $(this).attr('id');
        testID = $(this).val();
        var x = $(this).is(':checked');
        if (x) {
            if ($(this).val() !== '') {
                if (i == 1) {
                    datastring = parameterID + '#@' + testID;
                } else {
                    datastring += "$>" + parameterID + '#@' + testID;
                }
            }
            i++;
        } else {

        }
    });
    $.ajax({
        type: 'POST',
        url: 'requests/SaveTestResults.php',
        data: 'SavegeneralResult=Validation&testresults=' + datastring + '&itemID=' + itemID,
        cache: false,
        success: function(html) {
            //alert(html);
            $('#showGeneral').html(html);
        }
    });
});


$('#labGeneral').on("dialogclose", function() {

    $('#labResults').fadeIn(1000);
});

//Results modifications
$('.modificationStatsdoctor').click(function() {
    //alert('testlab');

    $('#labGeneral').fadeOut();
    var parameter = $(this).attr('id');
    var paymentID = $('.paymentID').val();
    $.ajax({
        type: 'POST',
        url: 'requests/resultModification.php',
        data: 'parameter=' + parameter + '&paymentID=' + paymentID,
        cache: false,
        success: function(html) {
            $('#historyResults1').html(html);
        }
    });

    $('#historyResults1').dialog({
        modal: true,
        width: 600,
        minHeight: 400,
        resizable: true,
        draggable: true,
        title: 'Results modification history'
    });

});


$('#historyResults1').on("dialogclose", function() {

    $('#labGeneral').fadeIn(1000);
});

/* $('.prevHistory').click(function () {
 alert('I am here');exit;
 var itemID = $('.productID').val();
 var patientID = $(this).attr('name');
 var parameterID = $(this).attr('id');
 var parameterName = $('.parameterName').val();
 var ppil = $(this).attr('ppil');
 $.ajax({
 type: 'POST',
 url: 'requests/resultModification.php',
 data: 'action=history&itemID=' + itemID + '&patientID=' + patientID + '&parameterID=' + parameterID + '&ppil=' + ppil,
 cache: false,
 success: function (html) {
 $('#historyResults1').html(html);
 }
 });
 
 $('#historyResults1').dialog({
 modal: true,
 width: 600,
 minHeight: 400,
 resizable: true,
 draggable: true
 });
 $("#historyResults1").dialog('option', 'title', parameterName);
 }); */



$(".Quantative").bind("keydown", function(event) {

    if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event
        .keyCode == 13 ||
        // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) ||
        // Allow: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Allow: Ctrl+V
        (event.keyCode == 86 && event.ctrlKey === true) ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    } else {
        // Ensure that it is a number and stop the keypress
        if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event
                .keyCode > 105)) {
            event.preventDefault();
        }
    }
});
</script>
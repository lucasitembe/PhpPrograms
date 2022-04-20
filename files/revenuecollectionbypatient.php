<?php
include("./includes/header.php");
include("./includes/connection.php");
$temp = 1;
$total = 0;

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = '';
}

$Date_From = '';
$Date_To = '';
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='./qualityassuarancework.php?QualityAssuranceWork=QualityAssuranceWorkThiPage' class='art-button-green'>
            BACK
        </a>
    <?php }
} ?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
?>
<style>
/*    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }*/
</style>
<br/><br/>
<center>
    <fieldset>
        <legend align='right'><b>REVENUE COLLECTION BY PATIENT REPORT</b></legend>
        <table width=100%>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="8%" style="text-align: right;"><b>Start Date</b></td>
                            <td width="16%" style='text-align: center;'>
                                <input type='text' name='Date_From' id='date' style='text-align: center;' placeholder="~~~ ~~~ Enter Start Date ~~~ ~~~">
                            </td>
                            <td width="8%" style="text-align: right;"><b>End Date</b></td>
                            <td width="16%" style='text-align: center;'>
                                <input type='text' name='Date_To' id='date2' style='text-align: center;' placeholder="~~~ ~~~ Enter End Date ~~~ ~~~">
                            </td>
                            <td width="8%" style="text-align: right;"><b>Sponsor Name</b></td>
                            <td style='text-align: left; color:black; border:2px solid #ccc;'>
                                <select name='Sponsor_ID' id='Sponsor_ID'>
                                    <option selected='selected' value=""></option>
                                    <?php
                                    //$data = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor where Guarantor_Name <> 'cash' order by Guarantor_Name") or die(mysqli_error($conn));

                                    $data = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_array($data)) {
                                        echo '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="7%" style="text-align: right;"><b>Patient Type</b></td>
                            <td>
                                <select name="Patient_Type" id="Patient_Type">
                                    <option selected="selected">All</option>
                                    <option>Outpatient</option>
                                    <option>Inpatient</option>
                                </select>
                            </td>
                            <td width="7%" style="text-align: right;"><b>Billing Type</b></td>
                            <td>
                                <select name="Billing_Type" id="Billing_Type">
                                    <option selected="selected">All</option>
                                    <option>Cash</option>
                                    <option>Credit</option>
                                </select>
                            </td>
                            <td colspan="2" width="16%" style='text-align: center; color:black; border:2px solid #ccc;'>
                                <input type='button' name='FILTER' id='FILTER' class='art-button-green' value='FILTER' onclick='filter_rev_by_p_report()'>
                                <input type='button' name='Preview_Report' id='Preview_Report' class='art-button-green' value='PREVIEW' onclick='Preview_Report()'>
                                <input type='button' name='Excel_Report' id='Excel_Report' class='art-button-green' value='EXPORT EXCEL' onclick='Excel_Report()'>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td style="text-align: center;">
                                <input type="text" id="Patient_Name" name="Patient_Name" style="text-align: center;" placeholder=' ~~~ ~~~ ~~~ Enter Patient Name ~~~ ~~~ ~~~ ' autocomplete='off' oninput="filter_rev_by_p_report()" onkeyup="filter_rev_by_p_report()">
                            </td>
                            <td style="text-align: center;">
                                <input type="text" id="Registration_ID" name="Registration_ID" style="text-align: center;" placeholder=' ~~~ ~~~ ~~~ Enter Patient Number ~~~ ~~~ ~~~ ' autocomplete='off' oninput="filter_rev_by_p_report()" onkeyup="filter_rev_by_p_report()">
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>
</center>
</fieldset>
<fieldset style='overflow-y: scroll; height: 380px; background: white;' id='Fieldset_List'>
</fieldset>

<div id="items_items">

</div>

<script type="text/javascript">


</script>

<script type="text/javascript">
    function filter_rev_by_p_report(){
       var Start_Date=$("#date").val();
       var End_Date=$("#date2").val();
       var Sponsor_ID=$("#Sponsor_ID").val();
       var Billing_Type=$("#Billing_Type").val();
       var Patient_Name=$("#Patient_Name").val();
       var Registration_ID=$("#Registration_ID").val();
       var Billing_Type=$("#Billing_Type").val();
       var Patient_Type=$("#Patient_Type").val();
       if(Start_Date==""){
           $("#date").css("border","2px solid red");
           exit;
       }
       if(End_Date==""){
           $("#date2").css("border","2px solid red");
           exit;
       }
       if(Sponsor_ID!=""){
           document.getElementById('Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_filter_rev_by_p_report.php',
                data:{Start_Date:Start_Date,Sponsor_ID:Sponsor_ID,Billing_Type:Billing_Type,Patient_Name:Patient_Name,Registration_ID:Registration_ID,End_Date:End_Date,Patient_Type:Patient_Type,Billing_Type:Billing_Type},
                success:function(data){
                    $("#Fieldset_List").html(data);
                }
            });
        }else{
            alert("Select Sponsor first");
        }
    }


    function Preview_Report() {
        var Start_Date=$("#date").val();
       var End_Date=$("#date2").val();
       var Sponsor_ID=$("#Sponsor_ID").val();
       var Billing_Type=$("#Billing_Type").val();
       var Patient_Name=$("#Patient_Name").val();
       var Registration_ID=$("#Registration_ID").val();
       var Patient_Type=$("#Patient_Type").val();
       if(Start_Date==""){
           $("#date").css("border","2px solid red");
           exit;
       }
       if(End_Date==""){
           $("#date2").css("border","2px solid red");
           exit;
       }
       if(Sponsor_ID!=""){
                window.open('rev_by_p_report_pdf.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID+'&Billing_Type='+Billing_Type+'&Patient_Name='+Patient_Name+'&Registration_ID='+Registration_ID+'&Patient_Type='+Patient_Type, '_blank');
       }else{
            alert("Select Sponsor first");
        }
    }

	function Excel_Report() {
        var Start_Date=$("#date").val();
       var End_Date=$("#date2").val();
       var Sponsor_ID=$("#Sponsor_ID").val();
       var Billing_Type=$("#Billing_Type").val();
       var Patient_Name=$("#Patient_Name").val();
       var Registration_ID=$("#Registration_ID").val();
       var Patient_Type=$("#Patient_Type").val();
       if(Start_Date==""){
           $("#date").css("border","2px solid red");
           exit;
       }
       if(End_Date==""){
           $("#date2").css("border","2px solid red");
           exit;
       }
       if(Sponsor_ID!=""){
                window.open('preview_excel_revenue_report.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID+'&Billing_Type='+Billing_Type+'&Patient_Name='+Patient_Name+'&Registration_ID='+Registration_ID+'&Patient_Type='+Patient_Type, '_blank');
       }else{
            alert("Select Sponsor first");
        }
    }



</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#items_items").dialog({autoOpen: false, width: '80%',height:'500', title: 'PATIENTS LIST', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/

    });
</script>

<script>
  function preview_category_items(Item_Subcategory_ID,Check_In_ID){

    $.ajax({
      url:"fetch_items_for_items_attendence.php",
      type:"post",
      data:{Item_Subcategory_ID,Check_In_ID},
      success:function(result){
        $("#items_items").html(result);
      }
    });
    $("#items_items").dialog('open');

  }
</script>
<?php
include("./includes/footer.php");
?>

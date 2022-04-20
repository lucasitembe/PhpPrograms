<?php
include("./includes/connection.php");
include("./includes/header.php");
// include("../signature/index.php");

session_start();
$consultation_id = $_GET['consultation_id'];
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
            $select_Patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE   Registration_ID = '$Registration_ID'");
            while ($row = mysqli_fetch_array($select_Patient)) {
            $Patient_Name = ucwords(strtolower($row['Patient_Name']));
        //     $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = " Miaka ".$diff->y;
            $age .= ", Miezi ".$diff->m;
            $age .= ", Na Siku ".$diff->d;

            if($Gender == 'Male'){
                $Gender = 'Mwanaume';
            }elseif($Gender == 'Female'){
                $Gender = 'Mwanamke';
            }
    
    }

  $date2= date('d, D, M, Y');
  $time= date('h:m:s');

                $select_conset_detail = mysqli_query($conn,"SELECT cd.Consent_ID, cd.consent_by, cd.Signed_at, em.Employee_Name, cd.consent_amputation, cd.behalf FROM tbl_consert_blood_forms_details cd, tbl_employee em WHERE Registration_ID = '$Registration_ID' AND consultation_id = '$consultation_id' AND em.Employee_ID = cd.Employee_ID");
                $datazangu = ($select_conset_detail > 0);
                if($datazangu){
                    while ($row = mysqli_fetch_array($select_conset_detail)) {
                        // `PROCEDURES`, `REPRESENTATIVE`, `WITNESS_NAME`, `DOCTOR`, `DATE_SIGNED`,
                        $consent_by=$row['consent_by'];
                        $Signed_at=$row['Signed_at'];
                        $Employee_Name=$row['Employee_Name'];
                        $Consent_ID=$row['Consent_ID'];
                        $consent_amputation = $row['consent_amputation'];
                        $behalf = $row['behalf'];

                    }
                }    
     }

$Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Current_Employee_Name= $_SESSION['userinfo']['Employee_Name'];

           $select_Filtered_Doctors = mysqli_query($conn,
              "SELECT * from tbl_employee where
                   Employee_Type = 'doctor' order by employee_name") or die(mysqli_error($conn)); 
                   while($row = mysqli_fetch_array($select_Filtered_Doctors)){
           $select.="
           <option value=".$row['Employee_ID']."> Dr. ".$row['Employee_Name']." </option>
           ";
         }

     



    
    // else{
    //     echo "<input type='text'  style='width: 700px; border-top: none; border-left: none; border-right: none; border-bottom: 2px solid #000;' value=".$Registration_ID.">";   
    // }

    $msg="
    <tr>
<td colspan='4' style='text-align:center;' >
<font color='blue;'> <b> Sasa unaweza Kuchukua Saini wa Wahusika kwa ajili ya Ridhaa</b></font>
</td>
</tr>
    ";
       
      //  header('location:print_dailysis_formpdf.php');
      //  $_SESSION['patient_id'] = $Registration_ID;
   
 
 $doctor_button="
 <a target='_blank' href='../esign/employee_signature.php?Employee_ID=".$Responsible_dr."&ChangeUserPassword=ChangeUserPasswordThisPage' class='art-button-green'>CHUKUA SAINI YA DAKTARI</a>
 ";
 $patient_button="
 <a target='_blank' href='../esign/signature.php?Registration_ID=".$Registration_ID." 'class='art-button-green'  style='border-radius: 5px; height: 35px;'>CHUKUA SAINI YA ".strtoupper($consent_by)."</a>

 ";
 $witnes_button="
 <a target='_blank' href='../signaturesignatur/index.php?Registration_ID= ".$Registration_ID." ' class='art-button-green'> CHUKUA SAINI YA DAKTARI</a>
 ";
 $printbtn="
 <a target='_blank' href='printrtheater_blood_consent.php?Registration_ID=".$Registration_ID."&Consent_ID=".$Consent_ID."' class='art-button-green' style='border-radius: 5px; height: 35px;'>ONESHA NA CHAPISHA</a>

 ";
?>
    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

<!DOCTYPE html>
<html>
<head>
<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */             
                    .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
                }

                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 16PX;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr th{
                    border:none!important;
                    padding: 5px;
                    font-size: 18PX;
                }
                *{
                  font-size: 17px;
                }
                .button_container{
                  width: 90%;
                  border: 1px solid silver;
                  margin: 10px;
                }
                .button_alignment{
                  width: 33%;
                  border: 1px solid silver;
                  position: relative;
                  display: inline-block;
                  margin: auto;
                }

            #surgery_doctor{
                padding: 5px;
                border-top: none;
                font-weight: bold;
                font-size: 16px;
            }
            p{
                text-align: justify;
            }
</style>
</head>
<body>
<center>
                              <div class="button_container hide">
                                    <div class="button_alignment">
                                          <a href='theater_concert_form.php?Registration_ID=<?php echo $Registration_ID; ?>&PatientBilling=PatientBillingThisForm'>
                                              <button style='width: 100%; height: 100%'>
                                                  ENGLISH VERSION
                                              </button>
                                          </a>
                                    </div>
                                    <div class="button_alignment">
                                          <a href='#'>
                                              <button style='width: 100%; height: 100%'>
                                                  TOLEO LA KISWAHILI
                                              </button>
                                          </a>
                                    </div>
                              </div>                     
  <br/>
<table class="table"id="spu_lgn_tbl" width='60%'>
                <tbody>
                </tbody>
        </table>
<section style="width:79%; ">
<fieldset ><form action="" method="post">
<legend align="center"><b>FOMU YA RIDHAA YA KUONGEZEWA DAMU - BLOOD TRANSFUSION</b></legend>

<!-- <table   style="color:black;text-align:center; text; font-family:Times New Roman, Times, serif;font-size: 20px; border='0';"> -->

<table style="font-size:20px; border: none;" width = 100%; border='0' id='spu_lgn_tbl'>
<?php if($Consent_ID > 0){ echo $msg; }?>
<tr>           <td colspan="4" style="text-align:center"><img src="./branchBanner/branchBanner.png"></td></tr>
                <tr>
                <td colspan="4" style="text-align:center"><b>FOMU YA RIDHAA YA KUONGEZEWA DAMU - BLOOD TRANSFUSION</b></td> 
                </tr>
                <tr>
                <td colspan="4"style="text-align:center">Jina la Mgonjwa: <b> <?php echo $Patient_Name; ?></b>&nbsp;&nbsp;&nbsp;Umri: <b><?php echo $age; ?> </b> &nbsp;&nbsp;&nbsp; Jinsia: <b><?php echo $Gender; ?></b></td>   
                </tr>
                <tr>
                <td colspan="4" style="text-align:center">Namba ya Faili: <b><?php echo $Registration_ID; ?></b></td>               
                </tr>
</table>

<table style="font-size:20px" width = 90%; border="0" id='spu_lgn_tbl'>
                
<tr>
    <td colspan="4"> 
        <p>Ninaelewa kwamba, ni haki yangu kupewa taarifa zinazohusu huduma ninazopewa na kufanya maamuzi juu ya kuongezewa damu kama sehemu ya matibabu yangu kama ilishauriwa na daktari wangu.
        <p>   
    </td>
</tr>
<tr>
    <th colspan="4"> 				
    MATIBABU PENDEKEZWA 
    </th>
</tr>
<tr>
    <td colspan="4"> 
        <p>Baada ya kujadiliana na daktari, nimeelewa kwamba kuongezewa damu au mazao ya damu ni muhimu kama sehemu ya matibabu yangu. Ili kufanikisha hili nimeelekezwa kwamba sampuli ya damu itachukuliwa kutoka kwenye mwili wangu na kupelekwa maabara kwa ajili ya kupima wingi wa damu, kundi la damu, pamoja na mlinganisho wa damu yangu na damu nitakayoongezewa. Pia nimeelezwa kwamba uongezewaji wa damu utafanyika kwa utaalamu wa hali ya juu na kwamba damu nitakayoonezewa ni salama kwa kiasi kikubwa.
        <p>   
    </td>
</tr>
<tr>
    <th colspan="4"> 				
    MATIBABU MBADALA 
    </th>
</tr>
<tr>
    <td colspan="4"> 
        <p>Ninafahamu  kwamba kuna mbadala wa kuongezewa damu ikiwepo kukataa kuongezewa damu. Mbadala mwengine waweza kuwa:-.
        <p>>> Kuongezewa vitu visivyotokana na damu, mfano vitamini mbalimbali, vitakavyosaidia mwili wangu kutengeneza damu.
        <p>>> Dawa nyinginezo ili kusaidia mfumo wangu wa damu au kuounguza madhara ya upungufu wa damu.
        <p><br>
        <p>Naelewa kwamba tiba mbadala zina ufanisi mdogo na zinafanya kazi polepole kuliko kuongezewa damu au mazao ya damu. Naelewa kwamba kukataa kuongezewa damu kunaweza kupelekea madhara makubwa kwenye moyo wangu au viungo vingine kutokana na upungufu wa damu, na kunawezza kusababisha shambulio la moyo (Heart attack), kiharusi (Stroke), na madhara mengine ikiwemo kifo.
    </td>
</tr>
<tr>
    <th colspan="4"> 				
    MADHARA 
    </th>
</tr>
<tr>
    <td colspan="4"> 
        <p>Naelewa kwamba licha ya utaalamu na umakini wa madaktari na wahudumu wengine wa afya, kuna uwezekano wa kupata madhara yatokanayo na kuongezewa damu. Madhara mengi ni madogo na yanatibika kirahisi, kuna uwezekano mdogo wa kifo au kupata ulemavu wa kudumu endapo nitapata madhara makubwa yatokanayo na kuongezewa damu. Ninafahamu pia kwamba kuna uwezekano wa kupata madhara kwenye mapafu ambayo ni madhara adimu ila ni makubwa kutokana na mjibizo wa mwili dhidi ya damu anayoongezewa mtu.

        <p>Nimeelezwa kwamba japo kuna uwezekano mdogo wa kupata madhara yatokanayo na kuongezewa damu, wataalamu wa afya wamesomea na wanafahamu namna ya kuzitambua dalili za madhara hayo endapo madhara hayo yatatokea kwa bahati mbaya.
        <p>Naelewa kwamba damu itakayotumika imekusanywa na kituo cha ukusanyaji damu kutokana kwa wachangiaji wa hiari kwa utaratibu uliowekwa wa kitaalamu na kwamba damu imepimwa magonjwa yanayoweza kuwambukizwa kwa kupitia huduma ya kuongezewa damu kwamba kuna uwezekano mdogo sana wa kuongezewa damu yenye maambukizi. Pamoja na taratibu zote hizi kuna uwezekano mdogo wa maambukizi ya Bacteria, Malaria, Hoka ya Ini B na C (Hepatitis B&C), Kaswende na Virusi ya Ukimwi (HIV) na maambukizi mengineyo kulingana na teknolojia inayotumika na Mpango wa Taifa wa Damu Salama katika upimaji.
        <p>Naelewa kwamba iwapo nahitaji kuongezewa damu au mazao ya damu kwa dharula, hali hiyo ya dharula inaweza kupelekea kutumika kwa damu ambayo haijafanyiwa vipimo kamili kama kundi la damu na mlinganisho wa damu yangu na nitakayoongezewa (Compatibility Testing). Hata hivyo damu hiyo itakuwa ile inayoweza kutumika bila kuwa na madhara au ina uwezekano mdogo wa kusababisha madhara.
    </td>
</tr>
<tr>
    <th colspan="4"> 				
    KURIDHIA AU KUTORIDHIA 
    </th>
</tr>
<tr>
    <td colspan="4"> 
        <p>Naelewa kwamba nina haki na nafasi ya kuongea na daktari anayenitibu kuhusu tiba ya kuongezewa damu au mazao ya damu na kujadili kwa undani kuhusu faida na madhara yake pamoja na njia mbadala zilizopo na madhara yake. Kwa kutia saini hapa chini ninathibitisha kwamba nimeelewa faida, madhara na tiba mbadala zilizopo, na kwamba nimepata wasaa wa kuuliza maswali na kueleweshwa vyema.
    </td>
</tr>
<tr>
<td colspan='4'>
<p style='text-align: center'><input class="form_group" name="consent_amputation" value="Agree" type="radio" id="consent_amputation" <?php if($consent_amputation == "Agree") { echo "checked"; } ?> > <b>Ninakubali</b>
<input class="form_group" name="consent_amputation" value="Disagree" type="radio" id="consent_amputation" <?php if($consent_amputation == "Disagree") { echo "checked"; } ?> > <b>Ninakataa</b>: Kupata huduma ya kuongezewa damu kutokana na maelezo hapo juu  
</td>
</tr>
<tr>
<td colspan='4'><hr></td>
</tr>
<tr>
<td colspan='4' style='text-align: center; padding:4px;'>
<label>Imeridhiwa na:  </label><input type="radio" id="vehicle" name='vehicle'  <?php if($consent_by == "Mgonjwa") { echo "checked"; } ?> value="Mgonjwa">
<label> Mgonjwa</label><input type="radio" id="vehicle" name='vehicle'  <?php if($consent_by == "Mlezi") { echo "checked"; } ?> value="Mlezi">
<label>Mlezi</label><input type="radio" id="vehicle"  <?php if($consent_by == "Director") { echo "checked"; } ?>name='vehicle' value="Director">
<label> Director</label></td>
</tr>
<tr>

<?php if($Consent_ID > 0){ echo $msg; } ?> 
</tr>
<tr>
<td>
<tr>
                <td  colspan="2"style="text-align:left" ><b>Jina la <?php echo $consent_by ;?>: </b>****(Jaza kama anayesaini sio Mgonjwa)
                <input type="text" name="behalf" id='behalf' placeholder="Guardian/Proxy:..." value="<?php if ($consent_by == 'Mgonjwa'){
                    echo $Patient_Name;
                    }else{
                        echo $behalf;
                    } ?> ">
                </td>
                <td style="text-align:right" >Tarehe: 
                <td style="text-align:left" ><b>
                <?php echo $Signed_at ?>
                </td>
                </tr>
<tr>
<td><input type="button" name="" onclick="save_consent_data2()" width="100%" class="art-button-green" value="HAKIKI NA HIFADHI" style="border-radius: 5px; height: 35px;">
<?php if($Consent_ID > 0) { echo $patient_button; }?>
</td>
<td>

</td>
<td>
<?php
if(mysqli_num_rows($select_conset_detail)>0)  {
    echo $printbtn; 
}
?>
</td>
</tr>
</table>
<div id="procedure_list"></div>

</fieldset>
</form>
</section>
</center>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>





<script>
 
  var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  //['blockquote', 'code-block'],

  //[{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

 // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean']                                         // remove formatting button
];
  var quill = new Quill('.editorC', {
    modules: {
      toolbar: toolbarOptions
    },
    theme: 'snow'
  });
  function logHtmlContentC() {
    
    console.log(quill.root.innerHTML);
    var htmlcodeC=quill.root.innerHTML;
    var Registration_ID=<?php echo $Registration_ID;?>;
   
    //alert(htmlcodeC)
    // alert(Registration_ID)


    $.ajax({
        type:'POST',
        url:'save_param.php',
        data:{htmlcodeC:htmlcodeC,
        location:"to_update",
        Registration_ID:Registration_ID

        },
        success(response){
            alert(response);
        }
    });

  }
  
  function ajax_procedure_dialog_open(){
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{procedure_dialog:''},
            success:function(responce){                
                $("#procedure_list").dialog({
                    title: 'PROPOSSED PROCEDURE',
                    width: '85%',
                    height: 600,
                    modal: true,
                });
                $("#procedure_list").html(responce)
                ajax_search_procedure()
            }
        });
    }

    function ajax_search_procedure(){
        var Product_Name = $("#procedure_name").val();
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Product_Name:Product_Name, search_procedure:''},
            success:function(responce){
                $("#list_of_all_procedure").html(responce);
            }
        });
    }
    function save_anasthesia_procedure(Item_ID){
        var Registration_ID='<?= $Registration_ID ?>';
        $.ajax({
            type: 'POST',
            url: 'ajax_anasthesia_procedure_dialog1.php',
            data:{Registration_ID:Registration_ID,Item_ID:Item_ID, save_procedure:'save_procedure'},
            success:function(responce){
                display_selected_procedure()
            }
        });
    }
    function  display_selected_procedure(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Registration_ID:Registration_ID, display_procedure:'display_procedure'  },
            success:function(responce){
                $("#list_of_selected_procedure").html(responce)
            }
        });
    }

    function remove_anasthesia_procedure(Procedure_ID){
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Procedure_ID:Procedure_ID, remove_procedure:''},
            success:function(responce){
                display_selected_procedure()
            }
        });
    }
    function view_procedure_selected(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        
        $.ajax({
            type:'POST',
            url:'ajax_anasthesia_procedure_dialog1.php',
            data:{Registration_ID:Registration_ID,view_procedure:''},
            success:function(responce){
                $("#proposed_procedure").val(responce)
                $("#procedure_list").dialog("close")}
        });
    }
</script>
<script>
    $(document).ready(function (e){
        $("#surgery_doctor").select2();
        $("#surgery_doctor2").select2();
    });
</script>
<!-- <input type="text" name="Registration_ID" hidden value="<?php ;?>" > -->
<!-- <input type="text" id="Registration_ID" value="<?php ;?>" > -->

<script>
    function save_consent_data2(){
        var vehicle = $("input[name = 'vehicle']:checked").val();
        var consent_amputation = $("input[name = 'consent_amputation']:checked").val();
        var Registration_ID = '<?php echo $_GET['Registration_ID']; ?>';
        var Employee_ID = '<?= $Current_Employee_ID; ?>';
        var consultation_id = '<?= $consultation_id ?>';
        var behalf = $("#behalf").val();


        if(Registration_ID != '0' && consent_amputation != null){
            if (confirm("Una hakika taarifa ulizojaza ni sahihi na unataka kuendelea?")) {
                $.ajax({
                    url: "ajax_save_Blood_consert_form.php",
                    type: "post",
                    data: {Registration_ID:Registration_ID,vehicle:vehicle,Employee_ID:Employee_ID,consent_amputation:consent_amputation,consultation_id:consultation_id,behalf:behalf},
                    cache: false,
                    success: function(responce){
                        alert(responce);
                        location.reload(); 
                    }
                });
            }
		}else{
            alert("Tafadhali, Bainisha kama Mgonjwa/Mlezi amekubali au amekataa kupatiwa huduma ya damu.!");
            exit();
        }
	}
</script>

</html>

<?php
    include("./includes/footer.php");
?>

<?php 
    include("./includes/connection.php");
    $Registration_ID=$_POST['Registration_ID'];
    $Payment_Item_Cache_List_ID=$_POST['Payment_Item_Cache_List_ID'];
    $consultation_ID=$_POST['consultation_ID'];

?>

<html>
<head>
    <style>
        input[type="checkbox"]{
            width: 30px; 
            height: 30px;
            cursor: pointer;
    }
    </style>
</head>

<body>
<div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em">
        <div class="one">
            <table class="table">
                <caption></caption>
                <thead>
                    <tr>
                        <th  colspan="2" style="text-align:center;">OBJECTIVE</th>
                    <tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th>R</th>
                        <th>L</th>
                    <tr>
                    <tr>
                        <td><input type="text" class="form-control" id="objective_RE"></td>
                        <td><input type="text" class="form-control" id="objective_LE"></td>     
                    <tr>
                </tbody>
            </table>
        </div>
        <div class="two">
            <table class="table">
                <caption></caption>
                <thead>
                    <tr>
                        <th  colspan="2" style="text-align:center;">SUBJECTIVE</th>
                    <tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th>R</th>
                        <th>L</th>
                    <tr>
                    <tr>
                        <td><input type="text" class="form-control" id="subjective_RE"></td>
                        <td><input type="text" class="form-control" id="subjective_LE"></td>     
                    <tr>
                </tbody>
            </table>
               
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;" width=100%>
        <div class="one">
            ADD<td><input type='text' id='add_remark' class="form-control" >
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            P.D<td><input type='text' id='pd' class="form-control" >
        </div>
        <div class="two">
            N.P.C<td><input type='text' id='npc' class="form-control" >   
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            E.O.M<td><input type='text' id='eom' class="form-control" >
        </div>
        <div class="two">
            PHORIA<td><input type='text' id='phoria' class="form-control">   
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr 1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Diagnosis Management<textarea id="diagnosis_management" class="form-control"></textarea>
        </div>
        <div class="two">
            Low Vision Assesment Notes<textarea id="vision_assesment_note" class="form-control"></textarea>   
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Orthoptics Notes<textarea id="orthoptics_notes" class="form-control"></textarea>
            
        </div>
    </div>
    <div style="margin:0px;display:grid;grid-template-columns:1fr; gap:1em;margin-top:10px;">
        <div class="one">
            Remarks<textarea id="refraction_remark" class="form-control"></textarea>
            
        </div>
    </div>
    <table class="table">
        <caption></caption>
        <thead>
            <tr>
                <th colspan="5" style="text-align:center;">Diagnosis Optometry</th>
            <tr>
        </thead>
        <tbody>
            
            <tr>
                <td>
                    <input type="checkbox" id="MYOPIA" class="diagnosis">MYOPIA
                </td>
                <td>
                    <input type="checkbox" id="HYPEROPIA" class="diagnosis">HYPEROPUA
                </td>
                <td>
                    <input type="checkbox" id="PRESBYOPIA" class="diagnosis">PRESBYOPIA
                </td>
                <td>
                    <input type="checkbox" id="ASTIGMATISM" class="diagnosis">ASTIGMATISM
                </td>
                <td>
                    <input type="checkbox" id="ANTIMETROPIA" class="diagnosis">ANTIMETROPIA
                </td>

            <tr>
        </tbody>
    </table>

   
    <tr>
        <td style="margin-top:10px;"><input type="button" value="Save" class="btn btn-primary  btn-block" onclick="save_data()"></td>
    </tr>
    <div id="result"></div>
    <input type="hidden" id="Registration_ID" value="<?php echo $Registration_ID?>">
    <input type="hidden" id="Payment_Item_Cache_List_ID" value="<?php echo $Payment_Item_Cache_List_ID?>">
    <input type="hidden" id="consultation_ID" value="<?php echo $consultation_ID?>">

    <script>
   function save_data(){
        var Registration_ID = $("#Registration_ID").val();
        var Payment_Item_Cache_List_ID = $("#Payment_Item_Cache_List_ID").val();
        var consultation_ID = $("#consultation_ID").val();
        var objective_RE=$("#objective_RE").val();
        var objective_LE=$("#objective_LE").val();
        var subjective_RE=$("#subjective_RE").val();
        var subjective_LE=$("#subjective_LE").val();

        var phoria=$("#phoria").val();
        var pd=$("#pd").val();
        var npc=$("#npc").val();
        var eom=$("#eom").val();
        var add_remark=$("#add_remark").val();
        var diagnosis_management=$("#diagnosis_management").val();
        var vision_assesment_note=$("#vision_assesment_note").val();
        var orthoptics_notes=$("#orthoptics_notes").val();
        var refraction_remark=$("#refraction_remark").val();
        var diagnosis2 = "";
                if($('#ASTIGMATISM').is(":checked")) 
                {
                    diagnosis2= 'ASTIGMATISM'+',';
                }
                if($('#MYOPIA').is(":checked")) 
                {  
                    diagnosis2 += 'MYOPIA'+',';
                }
                if($('#HYPEROPIA').is(":checked")) 
                {  
                    diagnosis2 += 'HYPEROPIA'+',';
                }
                if($('#PRESBYOPIA').is(":checked")) 
                {  
                    diagnosis2 += 'PRESBYOPIA'+',';
                }
                if($('#ANTIMETROPIA').is(":checked")) 
                {  
                    diagnosis2 += 'ANTIMETROPIA'+',';
                }
                var that = this;
            if(objective_RE !='' || objective_LE !='' || subjective_RE !='' || subjective_LE !='' || phoria !='' || pd !='' || npc !='' || eom !='' || phoria !='' || diagnosis_management !='' || vision_assesment_note !='' || orthoptics_notes !=''){
            if(confirm("Are you Sure you want to Save")){
                $.ajax({
                type:'post',
                url: 'save_reflaction_data.php',
                data : {
                    objective_RE:objective_RE,
                    objective_LE:objective_LE,
                    subjective_RE:subjective_RE,
                    subjective_LE:subjective_LE,
                    phoria:phoria,
                    pd:pd,
                    eom:eom,
                    npc:npc,
                    diagnosis_management:diagnosis_management,
                    vision_assesment_note:vision_assesment_note,
                    orthoptics_notes:orthoptics_notes,
                    diagnosis2:diagnosis2,
                    Payment_Item_Cache_List_ID:Payment_Item_Cache_List_ID,
                    Registration_ID:Registration_ID,
                    consultation_ID:consultation_ID,
                    refraction_remark:refraction_remark,
                    add_remark:add_remark
                },
                success : function(response){
                    //alert("data Successfully saved");
                    
                    //$('#result').html(response);
                    $('#objective_RE').val("");
                    $('#objective_LE').val("");
                    $('#subjective_RE').val("");
                    $('#subjective_LE').val("");
                    $('#phoria').val("");
                    $('#pd').val("");
                    $('#npc').val("");
                    $('#eom').val("");
                    $('#add_remark').val("");
                    $('#refraction_remark').val("");
                    $('#diagnosis_management').val("");
                    $('#vision_assesment_note').val("");
                    $('#orthoptics_notes').val("");
                    $('#ANTIMETROPIA').prop("checked",false);
                    $('#ASTIGMATISM').prop("checked",false);
                    $('#MYOPIA').prop("checked",false);
                    $('#HYPEROPIA').prop("checked",false);
                    $('#PRESBYOPIA').prop("checked",false);
                    location.reload(true);



                }
            });
            
            }
        }
        else{
            alert("Please fill atleast one of the field above");
        }
        
               
               
            
        }
       
    </script>
    <!--script src="ajax_opical.js"></script-->

    <body>

</html>
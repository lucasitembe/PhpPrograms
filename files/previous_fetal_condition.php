<?php
// include("./includes/header.php");
// include("./includes/connection.php");
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])) {
   $admision_id = $_GET['admision_id'];
}

if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

// get patient details
if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $patient_id . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}

$age = date_diff(date_create($DOB), date_create('today'))->y;


 ?>
<!-- <a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a> -->

<style media="screen">

  #chart2{
    height: 250px !important;
    /* margin:15px auto; */
    width: 93vw !important;
  }

  #chart2{
    height: 130px;
    margin:15px auto;
    width: 85vw;
  }

#table{
  width: 90vw;
  background: white;
  float: left;
  margin-left: 25px;
}
  table{
    border-collapse: collapse;

  }

/* #time td{
  width: 1.5%;
} */
  table,tr,td{
    border: 1px solid grey;
    box-sizing: border-box;
  }
#table td{
  width: 1.9%;
}
#table #time{
  height: 20px;
}
#table-input
{
  background: #fff;
  width: 20%;
  margin-top:20px;
}

#table-input td{
  text-align: center !important;
}
#table-input td:hover{
  background: grey;
}
.btn-inputl{
  display: block;
  height: 25px !important;
  width: 60px !important;
  background: #fff;
}
.btn-inputm{
  display: block;
  height: 25px !important;
  width: 60px !important;
  background: #fff;
}
.btn-input:hover{
  background: grey;
}
.show-time{
  text-align: center;
}
#remark td{
  box-sizing: border-box;
}

#number-time{
  float: left;
  margin-left: 117px;
}
#table #moulding{
  height: 20px !important;
}
#liqour_remark{
  height: 20px !important;
}
#time td {
  padding-left: -3;
  border: none;
}

#number-time{
  width: 84vw;
}
#number-time tr td{
  border: none !important;
  width: 2.7% !important;
  box-sizing: border-box;
  font-size: 10px;
  text-align: left;
}
#number-time ,tr{
  border: none !important;
}
#time{
  border: none !important;
}
</style>


 <center>
   <!-- <fieldset> -->
     <!-- <legend style="font-weight:bold"align=center>
       <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
         <p style="margin:0px;padding:0px;">FETAL CONDITION</p>
         <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
       </div>
   </legend> -->
     <form class="" action="" method="post" id="labour_form">
       <input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id; ?>">
       <input type="hidden" name="admission_id" id="admission_id" value="<?=$admision_id; ?>">
       <div id="chart2">
       </div>

       <!-- <div class="">
         <span>Fetal Heart Rate</span><span><input style="width:10%;;" type="text" name="fetal_heart_rate" value="" id="pointx"> </span>
         <span>Time</span><span><input style="width:10%;;" id="pointy" type="text" name="time" value=""> </span>
         <span><button type="button"style="width:100px; height:30px !important;" id="addy"  name="button">Add</button> </span>
       </div> -->

       <div class="">

         <hr style="border:10px solid #C0C0C0; width:96vw;" />
       </div>

       <div id="chart">
         <br />
          <table id="table">
            <tr id="liqour_remark">
              <td style="width:8%; font-weight:bold;">State Of Liqour</td>
              <?php
              for ($i=0; $i <= 48; $i++) {
                ?>
                <td id="<?=($i/2);?>"></td>
              <?php }
               ?>
               <br />
            </tr>
            <tr id="moulding">
              <td style="width:8%; font-weight:bold;">Moulding</td>
              <?php
              for ($i=0; $i <=48; $i++) {
                ?>
                <td id="<?=($i/2);?>"></td>
              <?php }
               ?>
            </tr>
            </table>
            <table id="number-time">
              <tr id="time">

                <?php
                for ($i=0; $i <= 48; $i++) {
                  ?>
                  <td id="<?=($i/2);?>"><?=($i/2);?></td>
                  
                <?php }
                 ?>
              </tr>

            </table>
       </div>


       <div class="">
          <br />
         <!-- <table id='table-input' width="100%;">
           <tr>
             <td ><p style="width:100px !important; margin:0px; padding:0px;">State Of Liqour</p></td>
             <td><button class="btn-inputl">C</button></td>
             <td><button class="btn-inputl">I</button></td>
             <td><button class="btn-inputl">M</button></td>
             <td></td>
             <td ><p style="width:100px !important; margin:0px; padding:0px;">Moulding</p></td>
             <td><button class="btn-inputm">0</button></td>
             <td><button class="btn-inputm">+1</button></td>
             <td><button class="btn-inputm">+2</button></td>
             <td><button class="btn-inputm">+3</button></td>
              <td><button class="audit" id="audit" class="art-button-green">Audit</button></td>
           </tr>
         </table> -->


         <div id="show_dialogl">
           <center>
             <br />
           <table style="border:none;width:100%;">
            <tr style="height:35px; width:60%;">
              <td style="text-align:center" >State Of Liqour</td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">Select Time</td>
            </tr>

              <tr style="height:35px; width:60%;">
              <td style="text-align:center" id="selected_liqour"></td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">
                <select class="show-time" name="time" required>
                  <option value="">--Select Time--</option>
                  <?php
                  for ($i=0; $i <= 48 ; $i++){
                    ?>
                    <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
                  <?php
                  }
                   ?>
                </select>
              </td>
            </tr>
           </table>
           <br />
           <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savel" value="Save">
         </center>
         </div>


         <div id="show_dialogm">
           <center>
             <br />
           <table style="border:none;width:100%;">
            <tr style="height:35px; width:60%;">
              <td style="text-align:center" >Moulding</td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">Select Time</td>
            </tr>

              <tr style="height:35px; width:60%;">
              <td style="text-align:center" id="selected_moulding"></td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">
                <select class="show-timem" name="time" required>
                  <option value="">--Select Time--</option>
                  <?php
                  for ($i=0; $i <= 48 ; $i++){
                    ?>
                    <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
                  <?php
                  }
                   ?>
                </select>
              </td>
            </tr>
           </table>
           <br />
           <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savem" value="Save">
         </center>
         </div>

</form>

<div id="auditl">

</div>

<div id="auditm">

</div>

<!-- </fieldset> -->





<!-- <script type="text/javascript" src="jquery.min.js"></script> -->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="jquery.jqplot.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="../src/plugins/jqplot.pointLabels.js"></script>

<script type="text/javascript" src="../plugins/jqplot.logAxisRenderer.min.js"></script>
<script type="text/javascript" src="../plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="../plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="../plugins/jqplot.categoryAxisRenderer.min.js"></script>

<script type="text/javascript" src="plugins/jqplot.highlighter.js">

</script>
<link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />


<script type="text/javascript">



var points=[[]];

function plotFetalGraph(){

  var plot3 = $.jqplot('chart2', [points],
    {
  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:0.5,
      label:"Hour"
    },
    yaxis:{
      max:180,
      min:80,
      tickInterval:20,
      label:" Fetal Heart Rate ",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
        }
    },
  },
  grid: {
              drawBorder: false,
              shadow: false,
              background: 'white'
          },
  highlighter: {
        show: true,
        sizeAdjust: 10.5
      },

      cursor: {
        show: false
      },


      series:[
          {

            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#ccc',
            markerOptions: { style:'circle' }
          },
          {

            showLine:true,
            markerOptions: { size: 7, style:"circle" }
          },

      ]
    }
  );

}
//end function


$(document).ready(function(){

  // select all points in table:
  var patient_id = $("#patient_id").val();
  var  admission_id = $("#admission_id").val();



  $.ajax({
    url:"get_moulding.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data)
    {

      var json_data = JSON.parse(data)
      var time = json_data[0].moulding_time;
      // console.log(time)
      for (var i = 0; i < json_data.length;i++) {
        var current = json_data[i];

        // console.log("test")
      $("#moulding #"+(current.moulding_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(current.moulding);
        // console.log(current.moulding)
      }
      }

  })


// display liqour remark
$.ajax({
    url:"get_liqour_remark.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data)
    {

      var json_data = JSON.parse(data)
      var time = json_data[0].moulding_time;
      // console.log(time)
      for (var i = 0; i < json_data.length;i++) {
        var current = json_data[i];

        console.log((current.liqour_remark_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" ) );
      $("#liqour_remark #"+(current.liqour_remark_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(current.liqour_remark);
        // console.log(current.moulding)
      }
      }

  })


  var d=[]
  $.ajax({
    type:"POST",
    url:"select_fetal_heart_rate_points.php",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      if (data != "") {

        var jsonData = JSON.parse(data);
        for (var i = 0; i < jsonData.length; i++) {
      var counter = jsonData[i];
      d=[parseFloat(counter.y) , counter.x]
      points.push(d);

      }

}
    // console.log(points);  // console.log();
    plotFetalGraph();
    }
  });
  // end points selection




$("#addy").click(function(e){
  e.preventDefault()
  var x = $("#pointx").val();
  var y = $("#pointy").val();
  // console.log();
  // console.log((points[points.length - 2])[0]);

// var total_points = (points[points.length])[0];
// console.log(total_points);

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

if (x!="" && y != ""){
  if (y>=0 && y <=24 ){
      // if ( y > total_points)  {
    if(x>=80 && x<=180){
      $.ajax({
        type:"GET",
        url:"add_fetal_heart_rate_toc_cache.php",
        data:{x:x,y:y,patient_id:patient_id,admission_id:admission_id},
        success:function(data){
          if (data != "") {
          points.push(JSON.parse(data));
          $("#pointx").css("border","1px  black");
          $("#pointy").css("border","1px  black");
          $("#pointx").val("");
          $("#pointy").val("");
          }

          // console.log(points)

          plotFetalGraph()
          // end drawing

        }
      })
    }else {
      alert("You exceed fetal heart rate")
      $("#pointx").css("border","2px solid red");
     return false;
    }

  // }else {
  //   alert("The entered time is behind the previos maximum time of the chart")
  // }
  }else {
    alert("your exceed normal hours")
    $("#pointy").css("border","2px solid red");
     return false;
  }

}else {
  alert("Please Fill in both Fetal Heart Rate and Time value");
  $("#pointy").css("border","2px solid red");
  $("#pointx").css("border","2px solid red");
}
})



// start moulding graph
// plotMouldingGraph()
  // end Moilding grp

});
// validate time

// start moulding graph
  // end Moilding grp

$(document).ready(function(e){

  $('#show_dialogl').dialog({
               autoOpen: false,
               modal: true,
               width: 550,
               height:300,
               title: 'State Of Liqour'
           });

// Moolding dialog
           $('#show_dialogm').dialog({
                        autoOpen: false,
                        modal: true,
                        width: 550,
                        height:300,
                        title: 'Moulding'
                    });



                    $('#auditl').dialog({
                                 autoOpen: false,
                                 modal: true,
                                 width: 800,
                                 height:400,
                                 title: 'Moulding'
                             });

                    $('#show_dialogm').dialog({
                            autoOpen: false,
                            modal: true,
                            width: 550,
                            height:300,
                            title: 'Moulding'
                            });




           var liqour_remark;

           // process liqour remark
             $(".btn-inputl").click(function(e){
               e.preventDefault();

           var val = $(this).text();

           liqour_remark = val;
           // console.log(liqour_remark);
              $("#selected_liqour").html(val)
               $("#show_dialogl").dialog("open");

             })

// save liqour remark
             $("#savel").click(function(e){

               var patient_id = $("#patient_id").val();
               var admission_id = $("#admission_id").val();

               var time = $(".show-time").val();
               var liquor = $("#selected_liqour").val();
               // console.log(liqour_remark)
               // console.log(time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" ) );
               $("#liqour_remark #"+time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" )).html(liqour_remark);
               $("#show_dialogl"
             ).dialog("close");
             // alert(time)
             $.ajax({
               type:"post",
               url:"save_moulding_liqour.php",
               data:{patient_id:patient_id,admission_id:admission_id,
                 liqour_remark:liqour_remark,liqour_remark_time:time},
               success:function(data){
                 console.log(data);
               }
             })

             })
// end liqpour remark

// start processing moulding
              var moulding;
             $(".btn-inputm").click(function(e){
               e.preventDefault();

           var val = $(this).text();

           moulding = val;
           // console.log(val);
              $("#selected_moulding").html(val)
               $("#show_dialogm").dialog("open");

             })

             $("#savem").click(function(e){
               e.preventDefault();

               var time = $(".show-timem").val();
               var patient_id = $("#patient_id").val();
               var admission_id = $("#admission_id").val();


               $("#moulding #"+time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" )).html(moulding);
               $("#show_dialogm").dialog("close");

               $.ajax({
                 type:"post",
                 url:"save_moulding.php",
                 data:{patient_id:patient_id,admission_id:admission_id,
                   moulding:moulding,moulding_time:time},
                 success:function(data){
                   console.log(data);
                 }
               })
             })
             

             $("#audit").click(function(e){
               e.preventDefault();
               var patient_id = $("#patient_id").val();
               var admission_id = $('#admission_id').val();
               // alert(patient_id + " " +admission_id)
               $.ajax({
                 url:"liqour_molding_audit.php",
                 type:"POST",
                 data:{patient_id:patient_id,admission_id:admission_id},
                 success:function(data){
                   console.log(data)
                  $("#auditl").dialog("open");
                  $("#auditl").html(data);
                 }
               })


             })
           })

</script>

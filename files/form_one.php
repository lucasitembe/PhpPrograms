<?php
include('header.php');
include('../includes/connection.php');

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])){
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];

}

// echo $registration_id;
// get patient details
if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $registration_id . "' AND
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

 <a href="icu.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>


<style media="screen">
  .block{
    display: inline-block;
  }

  #aside{
    width: 20%;
    background: yellow;
  }

  #section{
    width: 79.5%;
    background: red;
  }

.tab{
  width: 49.5%;
  display: inline-block;
  margin: 0px !important;
  padding: 0px;
  background: red;
}

#top{
  border-collapse: collapse;
  border:none !important;

}

#top tr td{
  border-collapse: collapse;
  border:none !important;
}

#top tr{
  border-collapse: collapse;
  border:none !important;
}

.container{
			width: 100%;
			margin: 0 auto;
      padding:0px;
      background: #fff;
		}
.side{
  display: inline-block;
  vertical-align: top;
}


		ul.tabs{
			margin: 0px;
			padding: 0px;
			list-style: none;
      background: #037DB0;
		}
		ul.tabs li{
			background: none;
			color: #222;
			display: inline-block;
			padding: 10px 15px;
			cursor: pointer;
      width: 49.8%;
		}

		ul.tabs li.current{
			background: #ededed;
			color: #222;
		}

		.tab-content{
      margin-top: 5px;
			display: none;
			background: #ededed;
			padding: 15px;
      border:1px solid grey;
      background: #fff;
		}

		.tab-content.current{
			display: inherit;
		}

    #mark{
      width:10%;
    }
    #mark-content{
      width:89%;
    }
p{
  text-align: left;
}

#icu{}
#icu tr,td{
  border: 1px solid grey !important;

}



#icu tr{
  table-layout: fixed;
  height: 20px !important;
  box-sizing: border-box;
}
#icu{

}

</style>

<center>
  <fieldset>
    <legend align=center style="font-weight:bold; font-size:13px;">CRITICAL CARE OBSERVATION UNIT</legend>


    <table width="100%" id="top">
      <tr>
        <td style="text-align:right; font-weight:bold">Date</td>
        <td>
          <input type="text" style="padding-left:2px;"name="date" value="" placeholder="Date and Time">
        </td>

      </tr>
      <tr>
        <td style="text-align:right; font-weight:bold; width:5%;">Diagnosis</td>
        <td>
          <input type="text" style="padding-left:2px;"name="Diagnosis" value="" placeholder="Diagnosis">
        </td>

        <td style="text-align:right; font-weight:bold; width:5%;">Working</td>
        <td>
          <input type="text" style="padding-left:2px;" name="working" value="" placeholder="Working">
        </td>

      </tr>
      </table>
      <table width="100%;">
      <tr>
        <td style="text-align:right; font-weight:bold; width:12%;">Reason for icu admission</td>
        <td colspan="3"><input type="text" style="padding-left:2px;" name="admission_reason" value="" onload="" placeholder="Reasons For ICU admission"> </td>
      </tr>
      <tr>
        <td style="text-align:right; font-weight:bold; width:12%;">PMH</td>
        <td colspan="3"><input type="text"style="padding-left:2px;"  name="pmh" value="" onload="" placeholder="PMH"> </td>
      </tr>
    </table>

    <div class="container">

	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1" >0700--1800</li>
		<!-- <li class="tab-link" data-tab="tab-2" >1900--0600</li> -->

	</ul>

	<div id="tab-1" class="tab-content current">

    <div class="side" id="mark">

    <p><span>0</span> <span>Unresponse</span> </p>
    <p><span>1</span> <span>Unresponse</span> </p>
    <p><span>2</span> <span>Response to touch or name</span> </p>
    <p><span>3</span> <span>Calm and cooperative</span> </p>
    <p><span>4</span> <span>Restless but cooperative</span> </p>
    <p><span>5</span> <span>Agigitate</span> </p>
    <p><span>6</span> <span>Unresponse</span> </p>
    </div>
    <div class="side" id="mark-content">
      <table width="100%" id="icu">
        <tr >
          <!-- <td></td> -->
          <td></td>
          <td style="width:150px !important"></td>
          <td>0700</td>
          <td>0800</td>
          <td>0900</td>
          <td>1000</td>
          <td>1100</td>
          <td>1200</td>
          <td>1300</td>
          <td>1400</td>
          <td>1500</td>
          <td>1600</td>
          <td>1700</td>
          <td>1800</td>
          <td>1900</td>
          <td>2000</td>
          <td>2100</td>
          <td>2200</td>
          <td>2300</td>
          <td>0000</td>
          <td>0200</td>
          <td>0100</td>
          <td>0300</td>
          <td>0400</td>
          <td>0500</td>
          <td>0600</td>
        </tr>
        <tr>
          <td></td>
          <td style="text-align:center">SAS</td>

          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <td style="text-align:center; border-top:none !important;">Pain score(0-10)</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>

        </tr>
        <tr>
          <td ></td>
          <td rowspan="2">
            <span style="float:left !important">Blood Pressure</span> <span style="float:right !important">200</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <!-- <td style="border-bottom:none; !important"></td> -->
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important">X systolic</span> <span style="float:right !important">190</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">180</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important">X systolic</span> <span style="float:right !important">170</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">160</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">150</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">140</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">130</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>

        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">120</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">110</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">100</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">90</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">80</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">70</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">60</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">50</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span>
            <span style="float:right !important">40</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important;padding-top:5px !important; " ></span>Temperature <span style="float:right !important">41</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>

        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span>Periphery-Re dot <span style="float:right !important">40</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span>
            <span>Core - Blue dot <span style="float:right !important">39</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">38</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">37</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>

        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">36</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>

        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">35</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>

        <tr>
          <td></td>
          <td rowspan="2">
            <span style="float:left !important"></span> <span style="float:right !important">34</span>
          </td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr >
          <td></td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>

        <tr>
          <td>4</td>
          <td>cvp</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td>1</td>
          <td>Heart Rate</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td>2</td>
          <td>Rhythm</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td>3</td>
          <td>MAP</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
        <tr>
          <td>3</td>
          <td>SPO2</td>
          <?php
          for ($i=0; $i < 24; $i++) {
            ?>
              <td><input type="text" name="" value=""> </td>
          <?php }
           ?>
        </tr>
      </table>
    </div>

	</div>

	<div id="tab-2" class="tab-content">
		 Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
	</div>

</div><!-- container -->


  </fieldset>
</center>




<script type="text/javascript">


$(document).ready(function(){

$('ul.tabs li').click(function(){
  var tab_id = $(this).attr('data-tab');

  $('ul.tabs li').removeClass('current');
  $('.tab-content').removeClass('current');

  $(this).addClass('current');
  $("#"+tab_id).addClass('current');
})

})

</script>

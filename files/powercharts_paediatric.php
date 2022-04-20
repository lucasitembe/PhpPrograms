<link rel="stylesheet" href="css/jquery-ui.css" media="screen">
<script src="css/jquery.js"></script>
<script src="css/jquery-ui.js"></script>
<script>
 $(document).ready(function(){
  var regno=$('.showID').attr('id');
	$.ajax({
	type:'POST', 
	url:'requests/paediatricDetails.php',
	data:'CheckExistence=view3&regno='+regno,
	cache:false,
	success:function(html){
	// alert(html);
	}
	});
});
</script>




<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work']))
		{
			if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");}
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
        
        if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes')
            { 
            echo "<a  onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
	//href='clinicalnotes.php?Registration_ID='
                ?>


 <table><tr><a href="" class="art-button-green" >Save and Go Back</a></td><td width="70%"  colspan="3"></td></tr></table><td>

<div class="powercharts_body">
<br/>
<fieldset style='height:450px'>
	<legend align=right style="background:#0079AE; color:white; padding:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAEDIATRIC WORKS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</legend>
	<div class="showID" id="<?php echo $_GET['Registration_ID'];?>"></div>
        <table width="100%" class="power_header" border="0" >            
            <tr>
                        </tr>
                         <tr>
                          
                        </tr>

		     <tr>
                        <td colspan="4" style="padding-top:0px;">
                            <ul class="tabs" data-persist="true">
                               <center><li><button style='width:450px; height:40px' class="art-button-green" id="Nutriation">Nutrition History</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Growth">Growth Monitoring</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Allegies">Allergies & Irritations</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Physical">Physical Examination</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Vaccination">Vaccination/Disease Prevention</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Children">Children Born to HIV Positive Mothers</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Development">Physical & Mental Development Milestone</button></li>
                                <li><button style='width:450px; height:40px' class="art-button-green" id="Diahorrea">Children Under 5 Diagrnosed With Diahorrea</button></li></center> 
                            </ul>
                        </td>
                    </tr>
		    
		    
                    <tr>
                         <!--<td width="100%" colspan="2" style="padding-top:0px;"><hr></td>-->
                    </tr>
            </table>



        <div class="tabcontents" style="margin-top:-300px">

            <div id="view1" style="display: none;">
                <div id="displayview1">
                    
                </div>

            </div>
            
            

            <div id="view2" style="display: none">
                <div id="displayview2">
                    
                </div> 
            </div>
            
            
            
            <div id="view3" style="display: none">
                <div id="displayview3">
                    
                    
                </div>
            </div>
            
            
            <div id="view4" style="display: none">
                <div id="displayview4">
                    
                </div>
            </div>
            
            
            <div id="view5" style="display:none">
                <div id="displayview5">
                    
                </div>

            </div>
            <div id="view6" style="display:none">
                <div id="displayview6">
                    
                </div>
            </div>
        <div id="view7" style="display: none">
            <div id="displayview7">
                
            </div>
         
        </div>
            <div id="view8" style="display: none">  
                <div id="displayview8">
                    
                    
                </div>
            </div>

        </div></fieldset>
    </div>

    
<?php
    include("./includes/footer.php");
?>


<script type="text/javascript">
    $('#Allegies').click(function(){
       var txt=$(this).text();
       var regno=$('.showID').attr('id');
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view1&regno='+regno,
        cache:false,
        success:function(html){
		   $('#displayview1').html(html);
					  
		   $('#view1').dialog({
			modal:true, 
			width:'97%',
			// height:600,
			resizable:true,
			draggable:true,
		   });

			$("#view1").dialog('option', 'title', txt);
			
        }
      });
    });
    
    
    $('#Vaccination').click(function(){
       var txt=$(this).text();
	   var regno=$('.showID').attr('id');
      $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view2&regno='+regno,
        cache:false,
        success:function(html){
            $('#displayview2').html(html);
		     $('#view2').dialog({
			modal:true, 
			width:'97%',
			resizable:true,
			draggable:true,
             });

           $("#view2").dialog('option', 'title', txt);
        
        }
       });
       
       });

      $('#Growth').click(function(){
       var txt=$(this).text();
       var regno=$('.showID').attr('id');
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view3&regno='+regno,
        cache:false,
        success:function(html){
          $('#displayview3').html(html);
		     
		   $('#view3').dialog({
			 modal:true, 
			width:'97%',
			resizable:true,
			draggable:true,
		   });

			$("#view3").dialog('option', 'title', txt);
		}
        });
       });
       
    $('#Physical').click(function(){
       var txt=$(this).text();
	   var regno=$('.showID').attr('id');
     $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view4&regno='+regno,
        cache:false,
        success:function(html){
           $('#displayview4').html(html);
		   $('#view4').dialog({
			modal:true, 
			width:'97%',
			resizable:true,
			draggable:true,
		   });

           $("#view4").dialog('option', 'title', txt);
        
        }
        });
        
     });
     
 
    $('#Development').click(function(){
      var txt=$(this).text();
	  var regno=$('.showID').attr('id');
       $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view5&regno='+regno,
        cache:false,
        success:function(html){
            $('#displayview5').html(html);
		    $('#view5').dialog({
			 modal:true, 
			width:'97%',
			resizable:true,
			draggable:true,
		   });

        $("#view5").dialog('option', 'title', txt);
        
        }
        });
     });
     

    $('#Nutriation').click(function(){
        var txt=$(this).text();
        var regno=$('.showID').attr('id');
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view6&regno='+regno,
        cache:false,
        success:function(html){
            $('#displayview6').html(html);
			       
			   $('#view6').dialog({
				modal:true, 
				width:'97%',
				resizable:true,
				draggable:true,
			   });

               $("#view6").dialog('option', 'title', txt);
        
        }
        });
        
     });

    $('#Children').click(function(){
       var txt=$(this).text();
       var regno=$('.showID').attr('id');
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view7&regno='+regno,
        cache:false,
        success:function(html){
            $('#displayview7').html(html);
		   $('#view7').dialog({
			modal:true, 
			width:'97%',
			resizable:true,
			draggable:true,
		   });

			$("#view7").dialog('option', 'title', txt);
        
        }
        });
    });
    

    $('#Diahorrea').click(function(){
       var txt=$(this).text();
       var regno=$('.showID').attr('id');
        $.ajax({
        type:'POST', 
        url:'requests/paediatricDetails.php',
        data:'view=view8&regno='+regno,
        cache:false,
        success:function(html){
            $('#displayview8').html(html);
			$('#view8').dialog({
			modal:true, 
			width:'97%',
			resizable:true,
			draggable:true,
		   });

			$("#view8").dialog('option', 'title', txt);
        
        }
        });
    });

</script>

        

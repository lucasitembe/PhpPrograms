<link rel="stylesheet" href="table.css" media="screen">

<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Laboratory_Works']))
		{
			if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
?>
<style>
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
</style>
    <?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            echo "<button class='art-button-green' id='newSpecimen'>ADD NEW SPECIMEN</button>";
            }
    }
    

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
            { 
            echo "<a href='laboratory_setup.php' class='art-button-green'>BACK</a>";
            }
    }
               
?>

 <br><br>
 <fieldset>  
 <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>MANAGE SPECIMEN</b></legend>

 <br>
 <div id='Search_Iframe' style="width:100%;height:420px;overflow-x:hidden;overflow-y:auto">
    
    <?php include 'laboratory_speciemen_list_ifram.php';?> 
    
 </div>
        <div id="addNewSample" style="display: none">
            <div id="sampleDetails">
            <fieldset>  
            <form method='post' name='myForm' id='myForm'  enctype="multipart/form-data">
            <table width=100%>
                <tr>
                    <td width=25% style="text-align:right;"><b>Sample Name</b></td>
                    <td width=75%>
                        <b><input type='text' name='Sample_Name' id='Sample_Name' required='required' placeholder='Enter Sample Name' value=""></b>
                    </td> 
                </tr>
                <tr>
                    <td width=25% style="text-align:right;"><b>Sample Container</b></td>
                    <td width=75%>
                         <input type='text' name='Sample_Container' id='Sample_Container' required='required' placeholder='Enter Sample_Container' 
                                value="">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='button' name='submit' id='submit' value='   SAVE   ' class='art-button submit'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    </td>
            
                </tr>
            </table>
    </form>
</fieldset>
</div>                
</div>    
 </fieldset>  

<script src="css/jquery-ui.js"></script>
<script>
    $('#newSpecimen').click(function(){
      $('#addNewSample').dialog({
      modal:true, 
      width:600,
      resizable:true,
      draggable:true,
      title:'ADD NEW SAMPLE'
    });
    });
    
    
    
    //Save parameters
    $('.submit').click(function(){
       var Sample_Name=$("#Sample_Name").val();
       var Sample_Container=$("#Sample_Container").val();
       if(Sample_Name=='' || Sample_Container==''){
           alert('Fill all the required details to continue');
           exit();
       }
        $.ajax({
        type:'POST', 
        url:'requests/saveSample.php',
        data:'action=SaveSample&Sample_Container='+Sample_Container+'&Sample_Name='+Sample_Name,
        cache:false,
        success:function(html){
           $('#Search_Iframe').html(html);
        }
     });
    });
    
    
    
    //Edit specimen
    $('.EditSpecimen').click(function(){
        var id=$(this).attr('id');
        var name=$(this).attr('name');
        $.ajax({
        type:'POST', 
        url:'requests/saveSample.php',
        data:'action=EditSample&id='+id,
        cache:false,
        success:function(html){
           $('#sampleDetails').html(html);
        }
     });
        
      $('#addNewSample').dialog({
      modal:true, 
      width:600,
	   resizable:true,
      draggable:true,
    });

       $("#addNewSample").dialog('option', 'title', 'Edit  '+name);
    });
    
    //Delete specimen
    $('.DeleteSpecimen').click(function(){
      var deleteme=$(this).attr('id');
      if(confirm('Are you sure you want to deactivate this?')){
         $.ajax({
        type:'POST', 
        url:'requests/saveSample.php',
        data:'action=SaveDeactivate&id='+deleteme,
        cache:false,
        success:function(html){
           $('#Search_Iframe').html(html);
        }
     }); 
      }else{
          exit();
      }
    
    });
    
</script>

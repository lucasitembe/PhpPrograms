<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

?>
<a href='itemsconfiguration.php?ItemConfiguration=ItemConfigurationThisPage' class='art-button-green'>
    BACK
</a>

<script>
  function getItemsForConsultation(consultationType) {
        
         if(consultationType !='' && consultationType !='SELECT CONSULTATION TYPE'){ 
             //alert(consultationType);
             var dd;
                $.ajax({
                    type: 'POST',
                    url: "search_item_for_test.php",
                    data: "getItemByConsType=true&consultationType="+consultationType,
                    dataType:"json",
                    success: function (data) {
                        dd=data;
                         $('#loadDataFromItems').html(data['ITems_Not_In_Category']);
                         $('#itemsForThisCategory').html(data['ITems_In_Category']);
                         $("#moveItemToRight").attr("onclick","moveItemToRight('"+consultationType+"')");
                         $("#moveItemToLeft").attr("onclick","moveItemToLeft('"+consultationType+"')");
                         $("#search_item_not_in_category").attr("onkeyup","searchItemsLeft(this.value,'"+consultationType+"')");
                         $("#search_item_in_category").attr("onkeyup","searchItemsRight(this.value,'"+consultationType+"')");
                         $("#totalItemCategory").html(data['No_Of_Item_In_Category']);
                         $("#totalItemNotCategory").html(data['No_Of_Item_Not_In_Category']);
                         $("#search_item_not_in_category").attr('disabled',false);
                         $("#search_item_in_category").attr('disabled',false);
                    },error: function (jqXHR, textStatus, errorThrown) {
                      alert(errorThrown);               
                    }
                });
              }else{
                  //alert("Please select consultation type");
                    $('#loadDataFromItems').html('');
                    $('#itemsForThisCategory').html('');
                    $("#moveItemToRight").attr("onclick","moveItemToRight()");
                    $("#moveItemToLeft").attr("onclick","moveItemToLeft()");
                    $("#search_item_not_in_category").attr("onkeyup","searchItemsLeft(this.value,'')");
                    $("#search_item_in_category").attr("onkeyup","searchItemsRight(this.value,'')");
                    $("#totalItemCategory").html('');
                    $("#totalItemNotCategory").html('');
                    $("#moveItemToRight").attr('disabled','disabled');
                    $("#moveItemToLeft").attr('disabled','disabled');
                    $("#moveItemToRight").attr('style','width:60%;font-size:30px;opacity:0.3');
                    $("#moveItemToLeft").attr('style','width:60%;font-size:30px;opacity:0.3');
                    $("#search_item_not_in_category").val('');
                    $("#search_item_in_category").val('');
                    $("#search_item_not_in_category").attr('disabled','disabled');
                    $("#search_item_in_category").attr('disabled','disabled');
            }
    }
    
    function moveItemToRight(consultationType){
        //alert("Move items to right:"+consultationType);
        var ids;  var i=1;
        
        $('.itemsNotInCategory').each(function(){
             var id=$(this).attr("id");
            if($(this).is(':checked')){
                if(i==1){
                    ids=id;
                }else{
                    ids+="<$$>"+id;
                }
            }
            i++;
        });
        
                //alert(ids);
       //ajax request
       
       $.ajax({
                    type: 'POST',
                    url: "search_item_for_test.php",
                    data: "getItemByConsType=moveItemToRigth&consultationType="+consultationType+"&items="+ids,
                    dataType:"json",
                    success: function (data) {
                          alert('Item(s) moved to '+consultationType+' successifully');
                         $('#loadDataFromItems').html(data['ITems_Not_In_Category']);
                         $('#itemsForThisCategory').html(data['ITems_In_Category']);
                         $("#moveItemToRight").attr("onclick","moveItemToRight('"+consultationType+"')");
                         $("#moveItemToLeft").attr("onclick","moveItemToLeft('"+consultationType+"')");
                         $("#totalItemCategory").html(data['No_Of_Item_In_Category']);
                         $("#totalItemNotCategory").html(data['No_Of_Item_Not_In_Category']);

                         $("#moveItemToRight").attr('disabled','disabled');
                         $("#moveItemToRight").attr('style','width:60%;font-size:30px;opacity:0.3');
                         $("#totalSelectedNotInConsultation").val(0);
                         
                    },error: function (jqXHR, textStatus, errorThrown) {
                      alert(errorThrown);               
                    }
                });
       
    }
    function moveItemToLeft(consultationType){
        //alert("Move items to left:"+consultationType);
        var ids;  var i=1;
        
        $('.itemsInCategory').each(function(){
             var id=$(this).attr("id");
            if($(this).is(':checked')){
                if(i==1){
                    ids=id;
                }else{
                    ids+="<$$>"+id;
                }
            }
            i++;
        });
        
      // alert(ids);
       //ajax request
       
       $.ajax({
                    type: 'POST',
                    url: "search_item_for_test.php",
                    data: "getItemByConsType=moveItemToLeft&consultationType="+consultationType+"&items="+ids,
                    dataType:"json",
                    success: function (data) {
                         alert('Item(s) removed from '+consultationType+' successifully');
                         $('#loadDataFromItems').html(data['ITems_Not_In_Category']);
                         $('#itemsForThisCategory').html(data['ITems_In_Category']);
                         $("#moveItemToRight").attr("onclick","moveItemToRight('"+consultationType+"')");
                         $("#moveItemToLeft").attr("onclick","moveItemToLeft('"+consultationType+"')");
                         $("#totalItemCategory").html(data['No_Of_Item_In_Category']);
                         $("#totalItemNotCategory").html(data['No_Of_Item_Not_In_Category']);
                         
                         $("#moveItemToLeft").attr('disabled','disabled');
                         $("#moveItemToLeft").attr('style','width:60%;font-size:30px;opacity:0.3');
                         $("#totalSelectedInConsultation").val(0);
                      
                    },error: function (jqXHR, textStatus, errorThrown) {
                      alert(errorThrown);               
                    }
                });
    }
    
    function searchItemsLeft(search,consultationType){
        //alert(search+" "+consultationType);
        if(search !=='' && consultationType !==''){ 
         $.ajax({
              type: 'GET',
              url: "search_item_for_test.php",
              data: "consultationType="+consultationType+"&search_word="+search+"&type=left",
              success: function (data) {
                  //alert(data);
                 if(data !==''){
                     //alert(data);
                   $('#loadDataFromItems').html(data);   
                  }
              },error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);               
              }
          });
          }
    }
    
    function searchItemsRight(search,consultationType){
        if(search !=='' && consultationType !==''){ 
         $.ajax({
              type: 'GET',
              url: "search_item_for_test.php",
              data: "consultationType="+consultationType+"&search_word="+search,
              success: function (data) {
                 if(data !==''){
                   $('#itemsForThisCategory').html(data);   
                  }
              },error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);               
              }
          });
          }
    }
    
</script>
 
 <!--Dialog div-->
<br/><br/>

<div id="itemsupdate" style="width:100%;overflow:hidden;" >
   
    <fieldset style="height:485px;">
        <legend style="background-color:#006400;color:white;padding:12px;" align="right"><b>MAPPING CONSULTATION ITEMS </b></legend>
            <center>
			
               <select name="consultationType" id="consultationType" onchange="getItemsForConsultation(this.value)" style="width:available;padding:8px;font-size:17px;font-family:sans-serif;font-weight: lighter     ">
                    <option>SELECT CONSULTATION TYPE</option>
                    <option>Pharmacy</option>
                    <option>Laboratory</option>
                    <option>Radiology</option>
                    <option>Surgery</option>
                    <option>Procedure</option>
                    <option>Optical</option>
                    <option>Others</option>
               </select>
                <input type="hidden" name="totalSelectedNotInConsultation" id="totalSelectedNotInConsultation" value="0"/>
                <input type="hidden" name="totalSelectedInConsultation" id="totalSelectedInConsultation" value="0"/>
 
                <table width = "100%" style="border:0 " border="1">
                <tr>
                    <td width="40%" style="text-align: center"><input disabled="disabled"  type="text" oninput="searchItemsLeft(this.value,'')" name="search" id="search_item_not_in_category" placeholder="----------------------------------------------Search Item---------------------------------------------" ></td><td width="10%" style="text-align:center ">&nbsp;</td><td width="40%" style="text-align: center"><input disabled="disabled" type="text" oninput="searchItemsRight(this.value,'')" name="search" id="search_item_in_category" placeholder="----------------------------------------------Search Item---------------------------------------------"></td>
                </tr>   
                <tr>
                    <td width="40%" style="text-align:center;background-color:#006400;color:white; font-size:15px">ITEMS NOT IN THIS CONSULTATION</td><td width="10%" style="text-align:center ">&nbsp;</td><hr>
					
		    <td width="40%" style="text-align: center; background-color:#006400;color:white; font-size:15px">ITEMS IN THIS CONSULTATION</td>
                </tr>
                <tr>
                <td width="40%">
                   <!--Show tests for the section--> 
                   <div id="items_to_choose" style="height:334px;">
                        <table id="loadDataFromItems">
                           
                        </table>
                    </div>
					<div id="totalItemNotCategory" style="text-align:left;font-size:16px;font-weight:bold;padding-left:30px;"></div>
                </td>
                <td width="10%" valign="center" align="center" style="text-align:center;vertical-align: middle;  ">
                    <button style="width:60%;font-size:30px;opacity:0.3" disabled="disabled" name="moveItemToRight" id="moveItemToRight" class="art-button-green" type="button" onclick="moveItemToRight()">&gt;&gt;</button><br/><br/>
                    <button style="width:60%;font-size: 30px;opacity:0.3" disabled="disabled" name="moveItemToLeft" id="moveItemToLeft" class="art-button-green" type="button" onclick="moveItemToLeft()">&lt;&lt;</button>
                   
                </td>
                <td width="40%">
                    <!--Display selected tests for the section--> 
                    <div id="displaySelectedTests"  style="height:334px;">
                        <table id="itemsForThisCategory">
                           
                        </table>
                     </div>
		     <div id="totalItemCategory" style="text-align:right;font-size:16px;font-weight:bold;padding-right:30px;"></div>
                </td>
                </tr>
				  
				<tr>
				</tr>
            </table>
                   </center>
    </fieldset><br/>
    
</div>
<script>
 $(document).ready(function(){
     
        $(".itemsNotInCategory").live("click",function(){
            var id=$(this).attr("id");
            var totVal=parseInt($("#totalSelectedNotInConsultation").val());
            if($(this).is(':checked')){
              $("#totalSelectedNotInConsultation").val(++totVal);
              var totVal=parseInt($("#totalSelectedNotInConsultation").val());
              if(totVal > 0){
                   $("#moveItemToRight").attr('disabled',false);
                   $("#moveItemToRight").attr('style','width:60%;font-size:30px;');
                    //$("#moveItemToLeft").attr('disabled',false);
              }
            }else{
                var totVal=parseInt($("#totalSelectedNotInConsultation").val());
                
                if(totVal != 0){
                     $("#totalSelectedNotInConsultation").val(--totVal);
                }
               
                var totVal=parseInt($("#totalSelectedNotInConsultation").val());
                if(totVal == 0){
                     $("#moveItemToRight").attr('disabled','disabled');
                     $("#moveItemToRight").attr('style','width:60%;font-size:30px;opacity:0.3');
                      //$("#moveItemToLeft").attr('disabled',false);
                }
            }
        });
    
     $(".itemsInCategory").live("click",function(){
            var id=$(this).attr("id");
            var totVal=parseInt($("#totalSelectedInConsultation").val());
            if($(this).is(':checked')){
              $("#totalSelectedInConsultation").val(++totVal);
              var totVal=parseInt($("#totalSelectedInConsultation").val());
              if(totVal > 0){
                   $("#moveItemToLeft").attr('disabled',false);
                   $("#moveItemToLeft").attr('style','width:60%;font-size:30px;');
              }
            }else{
                var totVal=parseInt($("#totalSelectedInConsultation").val());
                
                if(totVal != 0){
                     $("#totalSelectedInConsultation").val(--totVal);
                }
               
                var totVal=parseInt($("#totalSelectedInConsultation").val());
                if(totVal == 0){
                     $("#moveItemToLeft").attr('disabled','disabled');
                     $("#moveItemToLeft").attr('style','width:60%;font-size:30px;opacity:0.3');
                }
            }
        });
    });    
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
#displaySelectedTests,#items_to_choose{
    overflow-y:scroll;
    overflow-x:hidden; 
}
</style>


<?php
    include("./includes/footer.php");
?>
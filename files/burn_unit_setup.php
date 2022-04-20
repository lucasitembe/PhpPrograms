<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
        $Branch_ID = 0;
    }

    
?>
<a href='allward_setup.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>BACK</a>
<br>
<br>
   <fieldset>
      
           <legend align=center>BURN UNIT TERMS</legend>
    <div class="col-md-4"> <button onclick="burn_unit_type()" name="dailog" class="art-button-green sm">TYPE OF BURN</button> </div>
    <div class="col-md-4"><button class="art-button-green" name="classfication" onclick="define_burn_classfication()">BURN CLASSFICATION</button></div>
    <div class="col-md-4"></div>
       

   </fieldset>
   <fieldset style="height:65vh; ">
       <div class="col-md-6">
           <div class="box box-primary" style="height: 450px;overflow: auto">
               
               <div class="box-body" >
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S/No</th>
                                <th>TYPE OF BURN</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="burn_registered">

                        </tbody>
                    </table>
               </div>
           </div>
       </div>
       <div class="col-md-6">
           <div class="box box-primary" style="height: 450px;overflow: auto">
               
               <div class="box-body" >
                    <table class="table" >
                        <thead>
                            <tr>
                                <th>S/No</th>
                                <th>TYPE OF BURN</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="burn_classfication">

                        </tbody>
                    </table>
               </div>
           </div>
       </div>
   </fieldset>
   <div id="burndialog"></div>
   <div id="classific"></div>
   <?php
        include("./includes/footer.php");
?>
   <script>
   $(document).ready(function(){
    diplay_saved_burn_type()
    diplay_saved_burn_classfication()
   });
    function burn_unit_type(){         
        $.ajax({
            type:'POST',
            url:'add_type_burn.php',
            data:{dailog:''},
            success:function(responce){
                $("#burndialog").dialog({
                    title: 'ADD TYPE OF BURN',
                    width: '50%',
                    height: 250,
                    modal: true,
                });
                $("#burndialog").html(responce)
            }
        });
    }
    function save_type_of_burn(){
        var type_burn  = $("#type_burn").val();
        $.ajax({
            type:'POST',
            url:'add_type_burn.php',
            data:{type_burn:type_burn},
            success:function(responce){                
                diplay_saved_burn_type();
            }
        });
    }

    function diplay_saved_burn_type(){
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{burndialog:''},
            success:function(responce){                
                $("#burn_registered").html(responce)
            }
        });
    }

    function define_burn_classfication(){         
        $.ajax({
            type:'POST',
            url:'add_type_burn.php',
            data:{classfication:''},
            success:function(responce){
                $("#burndialog").dialog({
                    title: 'CLASSFICATION OF BURN',
                    width: '50%',
                    height: 250,
                    modal: true,
                });
                $("#burndialog").html(responce)
            }
        });
    }

    function save_burn_classfication(){
        var Classfication_of_burn  = $("#Classfication_of_burn").val();
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Classfication_of_burn:Classfication_of_burn,btn_classfc:''},
            success:function(responce){                
                diplay_saved_burn_classfication();
            }
        });
    }

    function diplay_saved_burn_classfication(){
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{classific:''},
            success:function(responce){                
                $("#burn_classfication").html(responce)
            }
        });
    }

    function remove_classfication(Classfication_ID){
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Classfication_ID:Classfication_ID,remove_btn:''},
            success:function(responce){
                diplay_saved_burn_classfication();
            }
        });
    }
    function remove_burn(Burn_ID){
        $.ajax({
            type:'POST',
            url:'Ajax_display_burn.php',
            data:{Burn_ID:Burn_ID,remove_btnb:''},
            success:function(responce){
                diplay_saved_burn_type();
            }
        });
    }
   </script>
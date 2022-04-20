
<style type="text/css">
	#addTerminalDiv .myInput{
		//margin-bottom: 6px;
		display: inline-block;
		width:300px;
	}
	#save_terminal {
		width: 120px;
		padding: 2px;
		//margin-bottom: 10px;
	}
</style>
<div id="container">
	
	<div id="addTerminalDiv">
		<fieldset style="padding-bottom:13px;">  
            <legend style="padding:2px;" align='left'><b style="font-size:14px;">Add New Terminal</b></legend>
       		<form id="addOfflineTerminalForm" method="POST">
						<input type="text" title="This is the terminal name according to the department" class="myInput" id="terminal_name" name="terminal_name" placeholder=" Terminal Name">
					
						<input type="text" title="This is the terminal Id" class="myInput" name="terminal_id" id="terminal_id" placeholder=" Terminal Id">
			
						<input type="submit" class="art-button-green" name="save_terminal" title="Save terminal" id="save_terminal" value="Submit">
		
			</form>
	</fieldset>
	</div>
	<br>
	<div class="terminal_list" style="height:250px;overflow-y:auto;">
		<?php include 'offline_terminals.php'; ?>
	</div>
</div>



<script type="text/javascript">


	$("#save_terminal").click(function(e){
		e.preventDefault();
		var uri = '../epay/addOfflineTerminal.php';
		if($("#terminal_name").val()=='' || $('#terminal_id').val()==''){
			var content = 'Terminal Name and Terminal ID can not be empty';
			showMyDialog("Fields Required!",content,'30%','250');
			return;
		}

		$.ajax({
			type: 'POST',
			url: uri,
			data: $("form#addOfflineTerminalForm").serialize(),
			success: function(data){
				showMyDialog("Message",data,'30%','250');
				getTerminals();
				$("#terminal_name").val('');
				$('#terminal_id').val('');
			},
			error: function(){

			}
		});
	});

	function getTerminals(){
		var uri = '../epay/offline_terminals.php';
		$.ajax({
			type: 'POST',
			url: uri,
			data: {},
			success: function(data){
				$(".terminal_list").html(data);
			},
			error: function(){

			}
		});
	}

	function showMyDialog(dTitile,content,dWidth,dHeight,dModal=true)
    {
        $("#msgDalog").dialog({
            title: dTitile,
            width: dWidth,
            height: dHeight,
            modal: dModal,
            buttons: [ {
            	text: 'Okay',
            	click : function(){
            		 $(this).dialog('close');
            	},
            }
            ],

        }).html(content);
    }
</script>

<div id="msgDalog" style="display:none;">defualt text</div>
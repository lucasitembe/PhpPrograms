<?php
include '../includes/connection.php';


$query = "Select * from tbl_epay_offline_terminals_config";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

?>

<table cellpadding="6" width="99%" align="center" cellspacing="0">
			<thead style="background:#999;height:35px;color:#ffffff;font-size:14px;">
				<tr>
					<th><b>#</b></th>
					<th><b>Terminal Name</b></th>
					<th><b>Terminal Identifier</b></th>
					<th></th>
                                        <th></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 0;
			while($data = mysqli_fetch_assoc($result)){ ?>
                            <tr style="display: none">
                                <td>
                                    <?php $terminal_id=$data['terminal_id']?>
                                    <?php $terminal_auto_inc_id=$data['id']?>
                                   
                                        <div id="<?= $terminal_id ?>">
                                           <div id="editTerminalDiv">
                                               <fieldset style="padding-bottom:13px;">  
                                                   <legend style="padding:2px;" align='left'><b style="font-size:14px;">EditTerminal</b></legend>
                                                       <form id="editOfflineTerminalForm" action="" method="POST">
                                                           <table style="width:100%">
                                                               <tr>
                                                                   <td>Terminal Name</td>
                                                                   <td>
                                                                        <input type="text"hidden="hidden" name="terminal_auto_inc_id" value="<?= $terminal_auto_inc_id ?>">
                                                                       <input type="text"required="" value="<?= $data['terminal_name'] ?>"title="This is the terminal name according to the department" class="myInput" id="terminal_name" name="terminal_name" placeholder=" Terminal Name">
                                                                   </td>
                                                               </tr>
                                                               <tr>
                                                                   <td>Terminal id</td>
                                                                   <td>
                                                                     <input type="text"required="" title="This is the terminal Id"value="<?= $data['terminal_id'] ?>" class="myInput" name="terminal_id" id="terminal_id" placeholder=" Terminal Id">
                                                                   </td>
                                                               </tr>   	
                                                               <tr>
                                                                   <td colspan="2">
                                                                       <input type="submit" style="float:right"class="art-button-green" name="save_changes" title="Save Changes to terminal" id="save_terminal" value="Save Changes">
                                                                   </td
                                                               <tr>
                                                           </table>
                                                       </form>
                                               </fieldset>
                                           </div>
                                       </div>
                                                                       </td>
                                </tr>
				<tr>
					<td><b><?= ++$i ?></b></td>
					<td><?= $data['terminal_name'] ?></td>
					<td><?=  $data['terminal_id'] ?></td>
                                        <?php $terminal_id=$data['terminal_id']?>
                                        <td><input type="button" id="edit_btn"onclick="edit_terminal('<?php echo $terminal_id; ?>')" value="Edit" class="art-button-green"></td>
                                        <td><input type="button" id="edit_btn"onclick="delete_terminal('<?php echo $terminal_auto_inc_id; ?>')" value="delete" class="art-button-green"></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>



<script>
    function edit_terminal(terminal_id){
        
         $("#"+terminal_id).dialog({
                        title: 'Edit Terminal',
                        width: '40%',
                        height: 250,
                        modal: true,
                    });
    
    }
    function delete_terminal(id){
        var confm=confirm("Your going to delete this terminal\n click OK to continue or CANCEL to stop the process");
        if(confm){
            var uri = '../epay/deleteOfflineTerminal.php';
            $.ajax({
                type: 'POST',
                url: uri,
                data: {id:id},
                beforeSend: function (xhr) {
                   
                },
                success: function(data){
                    //alert("dtat");
                    if(data="yes"){
                        alert("Terminal deleted Successfully")
                        $("#myDalog").dialog("close");
                    }else{
                        alert("Fail to deleted Terminal")
                    }
                    
                },
                complete: function(){
                    
                },
                error: function(){
                    alert("Error:try again");
                }
            });
        }
    }
</script>
<h2 style="padding: 0px; margin: 0px;">User List</h2><hr/>
<div class="table_list">
    <div style="text-align: right;">
        <?php echo form_open('auth/index'); ?>
        Search : <input type="text" name="key"/><input type="submit" value="Search"/>
        <?php echo form_close(); ?>
    </div>
    <table class="view_data" cellspacing="0" cellpadding="0">
        	<tr>
                    <th>S/No</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email</th>
			<th>Groups</th>
			<th>Status</th>
			<th>Edit</th>
		</tr>
		<?php
                $i=1;
                foreach ($users as $user):?>
               
			<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?>>
                            <td><?php echo $i++; ?></td>
				<td><?php echo $user->first_name;?></td>
				<td><?php echo $user->last_name;?></td>
				<td><?php echo $user->email;?></td>
				<td>
					<?php foreach ($user->groups as $group):?>
						<?php if($group->id != 2){ echo $group->name;  }?>
	                <?php endforeach?>
				</td>
				<td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, 'Active') : anchor("auth/activate/". $user->id, 'Inactive');?></td>
			   <td><?php echo  anchor("auth/user_edit/".$user->id, 'Edit')?></td>
			</tr>
		<?php endforeach;?>
	</table>
	<?php
    echo $links;
        ?>
	
</div>

<table style="width:100%;">
    <tr>
        <td>
            <img src="<?php echo base_url().'uploads/photo/'.employee_photo($id); ?>" style="width: 150px; border: 2px solid #CCCCCC; height: 180px;"/>
        </td>
    </tr>
    <tr>
        <td>
            <table class="menu-data">
                <tr>
                    <td><?php echo anchor('hr/personalinfo/'.$id,'Personal Details'); ?></td>
                </tr>
                <tr>
                   <td><?php echo anchor('hr/contact/'.$id,'Contact Details'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/emergency/'.$id,'Emergency Contants'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/dependent/'.$id,'Dependents'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/job/'.$id,'Job'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/salary/'.$id,'Salary'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/qualification/'.$id,'Qualifications'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/promotion/'.$id,'Promotions'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/discipline/'.$id,'Discipline'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/leave/'.$id,'Leave'); ?></td>
                </tr>
                <tr>
                    <td><?php echo anchor('hr/attachment/'.$id,'Other Attachments'); ?></td>
                </tr>
               
            </table>
        </td>
    </tr>
</table>
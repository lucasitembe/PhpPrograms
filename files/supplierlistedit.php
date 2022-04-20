<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<input type="button" value="PRINT SUPPLIER" onclick="printsupplier()" class="art-button-green">
<?php
if (isset($_SESSION['userinfo'])) {
    ?>
    <a href='otherconfigurations.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>
        BACK
    </a>
<?php } ?>
<br/><br/>

<br/>

<center>
    <table width=80%><tr><td>
        <center>
            <fieldset style="overflow-y: scroll;height: 400px">
                <legend align="center" ><b>EDIT SUPPLIER</b></legend>
                <?php
                echo '<table width="50%">
                         <thead>
                          <tr>
                            <th>SN</th>
                            <th>SUPPLIER NAME</th>
                            <th>EDIT</th>
                          </tr>
                         </thead>  ';

                $qry = mysqli_query($conn,"SELECT * FROM tbl_supplier") or die(mysqli_error($conn));
                $sn = 1;

                while ($row = mysqli_fetch_array($qry)) {
                    $color = ($sn % 2 != 0 ? 'white' : '');
                    echo '<tr style="background-color:'.$color.'" >';
                    echo '<td>' . $sn++ . '</td>';
                    echo '<td>' . $row['Supplier_Name'] . '</td>';
                    echo '<td><a href="supplieredit.php?Supplier_ID='.$row['Supplier_ID'].'">Edit</a></td>';
                    echo '</tr>';
                }

                echo '</table>';
                ?>
            </fieldset>
        </center></td></tr></table>
</center>

<script>
    $('.refchanged').focusin().blur(function () {
        var id = $(this).attr('id');
        var cachedValue = $(this).attr('cachedValue');
        var currentValue = $(this).val();
        if (currentValue != '') {
            if (cachedValue != currentValue) {
                if (confirm("Do you want to save vital changes")) {
                    $.ajax({
                        type: 'POST',
                        url: 'editothersRequest.php',
                        data: 'src=referral&id=' + id + '&currentValue=' + currentValue,
                        cache: false,
                        success: function (result) {
                            if (result == '0') {
                                alert('REFERRAL HOSPITAL NAME ALREADY EXISTS! \nTRY ANOTHER NAME');
                            } else if (result == '1') {
                                alert('REFERRAL HOSPITAL NAME ADDED SUCCESSFUL');
                                window.location = window.location.href;
                            }
                        }
                    });
                }
            }
        }else{
            alert('Referral name cannot be empty');
            $(this).val(cachedValue);
        }
        //alert('id='+id+' cache='+cachedValue+' current='+currentValue);
    });

    function printsupplier(){
        window.open('printsupplier.php');
    }
</script>
<?php
include("./includes/footer.php");
?>
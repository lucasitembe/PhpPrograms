<?php

include("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}



?>
<style>
    button{
        height:27px!important;
        color:#FFFFFF!important;
    }
</style>
<a href="generalledgercenter.php" style=""><button type="button" class="art-button-green">BACK</button></a>
<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Items_Fieldset'>  
    <legend align=center><b>PROFIT/LOSS FOR ALL PHARMACY</b></legend>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed">
                <tr>
                    <td>
                        <input type="text"  placeholder="date from" style="text-align:center"/>
                    </td>
                    <td>
                        <input type="text"  placeholder="date to" style="text-align:center"/>
                    </td>
                    <td>
                        <input type="button"  value="FILTER" class="art-button-green"/>
                    </td>
                </r>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <><>
        </div>
    </div>
</fieldset>
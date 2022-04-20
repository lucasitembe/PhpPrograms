<legend align='center' style="padding:5px;width:30%;color:white;background-color: #006400;text-align:center "><b>ITEM LIST</b></legend>
<center>
    <table width=100% border='1'>
    <tr>
        <td id='Search_Iframe'>
            <div id="search_sponsor" style="width: 100%;height: 440px;overflow-x:hidden;overflow-y:scroll ">
                <?php include 'Search_Item_list_Iframe2.php';?>
            </div>
        </td>
    </tr>
    <tr>
       <table style="float:right">
            <tr>
                <td>
                    <form action="download_excel_item_price.php" method="POST">
                        <input type="text" id="sponsor_id_txt" name="sponsor_id_txt"  style="display: none"/>
                        <button type="submit" onclick="return check_general()" name="dowload_excel_btn"class='art-button-green'>EXPORT TO EXCEL</button>
                    </form>
                </td>
                <td id="printPreview" style="text-align:right ">
                    <?php echo $separator; ?>
                </td>
             </tr>
        </table>
    </tr>
    </table>
</center>
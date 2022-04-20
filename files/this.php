<HTML>
<HEAD>
<SCRIPT LANGUAGE="JavaScript"><!--
function copyForm() {
    opener.document.hiddenForm.myTextField.value = document.popupForm.myTextField.value;
    opener.document.hiddenForm.submit();
    window.close();
    return false;
}
//--></SCRIPT>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
</HEAD>
<BODY>
<FORM NAME="popupForm" onSubmit="return copyForm()" action="" method="post">
  <p>
    <INPUT TYPE="radio" NAME="group1" value="YellowPages"> 
    <span class="style1">    a) Yellow Pages<br>
    </span>    
    <input type="radio" name="group1" value="other"> 
    <span class="style1">b) Other means of communication</span><br>
    <input type="radio" name="group1" value="friend"> 
    <span class="style1">c) By Friends</span><br>
    <input type="radio" name="group1" value="purchased" checked> 
    <span class="style1">d) Purchased before</span></p>
  <p>    
    <INPUT name="Submit" TYPE="submit" onClick="copyForm()" VALUE="Submit">
    </p>
</FORM>
</BODY>
</HTML>
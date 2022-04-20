<html>
<head>
<title>test</title>
</head>
<body>
<button id="loan_deliver">deliver loan</button>
<script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js'); ?>"></script>
<script type="text/javaScript">
$(function(){

var url ='http://localhost/Final_One/api/index.php?method=getLoan&jsoncallback=?';
$("#loan_deliver").click(function(){

$.getJSON(url,function(data){
console.log(data);
});


});



});
</script>

</body>
</html>
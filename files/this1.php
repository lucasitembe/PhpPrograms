<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>popup demo</title>
<link rel="stylesheet" href="//code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
</head>
<body>
<div data-role="page" id="page1">
<div data-role="header">
<h1>jQuery Mobile Example</h1>
</div>
<div role="main" class="ui-content">
<a href="#popupBasic" data-rel="popup">Open Popup</a>
<div data-role="popup" id="popupBasic">
<p>This is a completely basic popup, no options set.</p>
</div>
</div>
</div>
</body>
</html>

 <html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery Show Popup on Page Load</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>
<style type="text/css">
#overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: #000;
filter:alpha(opacity=70);
-moz-opacity:0.7;
-khtml-opacity: 0.7;
opacity: 0.7;
z-index: 100;
display: none;
}
.cnt223 a{
text-decoration: none;
}
.popup{
width: 100%;
margin: 0 auto;
display: none;
position: fixed;
z-index: 101;
}
.cnt223{
min-width: 600px;
width: 600px;
min-height: 150px;
margin: 100px auto;
background: #f3f3f3;
position: relative;
z-index: 103;
padding: 10px;
border-radius: 5px;
box-shadow: 0 2px 5px #000;
}
.cnt223 p{
clear: both;
color: #555555;
text-align: justify;
}
.cnt223 p a{
color: #d91900;
font-weight: bold;
}
.cnt223 .x{
float: right;
height: 35px;
left: 22px;
position: relative;
top: -25px;
width: 34px;
}
.cnt223 .x:hover{
cursor: pointer;
}
</style>
<script type='text/javascript'>
$(function(){
var overlay = $('<div id="overlay"></div>');
overlay.show();
overlay.appendTo(document.body);
$('.popup').show();
$('.close').click(function(){
$('.popup').hide();
overlay.appendTo(document.body).remove();
return false;
});

$('.x').click(function(){
$('.popup').hide();
overlay.appendTo(document.body).remove();
return false;
});
});
</script>
</head>
<body>
<div class='popup'>
<div class='cnt223'>
<img src='http://www.developertips.net/demos/popup-dialog/img/x.png' alt='quit' class='x' id='x' />
<p>
Welcome to Aspdotnet-Suresh.com. Please use above search option which was there in top right side to get help with many of articles.
<br/>
<br/>
<a href='' class='close'>Close</a>
</p>
</div>
</div>
</body>
</html>
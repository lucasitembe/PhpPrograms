//call these method
//type  error, warning, question, information and confirmation;
// alertMsg(base_url, report_year, 'information', 0, false, 'Ok');
   //confirmAction(base_url, report_year, 'confirmation', 0, false, 'Ok', 'No',continueprocess);
function alertMsgSimple(msg, title, type, width, overlay_close, btnText) {
    var buttons=[btnText];
    
    if(btnText == false){
        buttons='';
    }
    
    $.Zebra_Dialog(msg, {
        'type': type,
        'overlay_close': overlay_close,
        'title': title,
        'width': width,
        'buttons': buttons
    });
}
function alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button) {
    var buttons=[btnText];
    
    if(btnText == false){
        buttons='';
    }
    var pos1='center', pos2='middle';
   
   if(position != ''){
       var data=position.split(',');
       pos1=data[0];
       pos2=data[1];
   }
    
    
    $.Zebra_Dialog(msg, {
        'type': type,
        'overlay_close': overlay_close,
        'auto_close': auto_close,
        'modal':modal,
        'overlay_opacity': overlay_opacity,
        'position':[pos1, pos2],
        'center_buttons':center_buttons,
        'title': title,
        'width': width,
        'show_close_button':show_close_button,
        'buttons': buttons
    });
}

function confirmAction(msg, title, type, width, overlay_close,auto_close, btnOkText, btnNoText,overlay_opacity,callfunction,show_close_button) {

 var zd=new  $.Zebra_Dialog(msg, {
        'type': type,
        'overlay_close': overlay_close,
        'auto_close': auto_close,
        'overlay_opacity': overlay_opacity,
        'title': title,
        'show_close_button':show_close_button,
        'width': width,
        'center_buttons':true,
        
        'buttons': [
            {caption: btnOkText, callback: function () {
                   callfunction();
                }},
            {caption: btnNoText, callback: function () {
                     close();
                }}
        ]
    });

}


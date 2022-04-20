var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    saveEmployeeButton = wrapper.querySelector("[data-action=saveEmpBtn]"),
    canvas = wrapper.querySelector("canvas"),
    signaturePad;

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

signaturePad = new SignaturePad(canvas);
signaturePad.minWidth = 3;
//signaturePad.maxWidth = 10;

clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
});

if(saveButton!=null){
    saveButton.addEventListener("click", function (event) {
  //  if (signaturePad.isEmpty()) {
    //    alert("Please provide signature first.");
    //} else {
        //console.log(signaturePad.toDataURL());
        var r = confirm('Are sure you want save this signature?');
        if(!r){
            return;
        }
        savePatientSignature(signaturePad.toDataURL());
        //saveEmployeeSignature(signaturePad.toDataURL());
        //window.open(signaturePad.toDataURL());
   // }
});
}

if(saveEmployeeButton!=null){
saveEmployeeButton.addEventListener("click", function (event) {
    //if (signaturePad.isEmpty()) {
      //  alert("Please provide signature first.");
   // } else {
        //console.log(signaturePad.toDataURL());
        var r = confirm('Are sure you want save this signature?');
        if(!r){
            return;
        }
        
        saveEmployeeSignature(signaturePad.toDataURL());
        //window.open(signaturePad.toDataURL());
    //}
});
}

<!DOCTYPE html>
<html>

<head>
    <title>HTML Page with text,image and CSS to PDF</title>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <style type="text/css">
    #text {
        max-width: 100%;
        width: 35%;
        text-align: center;
        margin: 50px;
        border: 5px solid orange;
        background: whitesmoke;
    }

    #text h2 {
        background: orange;
        color: white;
    }

    #btn {
        padding: 10px;
        border: 0px;
        margin: 50px;
        cursor: pointer;
    }
    </style>

    html, body{
    overflow-x: hidden;
    }

    </style>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
</head>

<body>
    <script type="text/javascript"></script>

    <button id="btn">Convert to CSS</button>

    <div id="text">
        <h2>HTML Page with text,image and CSS to PDF</h2>
        <img src="https://codingstatus.com/wp-content/uploads/2019/12/codingstatus.jpg" width="100%">
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys
            standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to
            make a type specimen book. It has survived not only five centuries, but also the leap into electronic
            typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
            sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
            PageMaker including versions of Lorem Ipsum</p>
    </div>

    <script src="../lib/jquery/dist/jquery.min.js"></script>
    <script src="../lib/jspdf/html_canvas.js"></script>
    <script src="../lib/jspdf/jspdf.debug.js"></script>
    <script>
    $(document).on('click', '#btn', function() {
        var printDoc = new jsPDF();
        let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

        // mywindow.document.write(`<html><head><title>${title}</title>`);
        // mywindow.document.write('</head><body >');
        mywindow.document.write($('body').html());

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();
        let pdf = new jsPDF();
        let section = $('body');
        let page = function() {
            pdf.save('pagename.pdf');

        };
        pdf.addHTML(section, page);

    })
    </script>

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>How to upload Image file using AJAX and jQuery</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <script src="jquery-3.3.1.min.js"></script>
    <script src='https://unpkg.com/tesseract.js@v2.1.0/dist/tesseract.min.js'></script>

    <script type="text/javascript">
    $(document).ready(function() {

        $("#but_upload").click(function() {

            let user = $('#username').val();
            var fd = new FormData();

            var files = $('#file')[0].files;

            // Check file selected or not
            if (files.length > 0) {

                fd.append('file', files[0]);

                $.ajax({
                    url: 'upload.php',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response != 0) {
                            console.log(response);
                            $("#img").attr("src", response);
                            $('.preview img').show();
                            Tesseract.recognize(
                                response,
                                'eng', {
                                    logger: m => console.log(m)
                                }
                            ).then(({
                                data: {
                                    text
                                }
                            }) => {
                                console.log(text);
                                $.ajax({
                                    type: "POST",
                                    url: "insert.php",
                                    data: {
                                        username: user,
                                        textData: text
                                    },
                                    dataType: "text",
                                    success: function(response) {
                                        console.log(response);
                                    }
                                });
                            })
                        } else {
                            alert('File not uploaded');
                        }
                    }
                });
            } else {
                alert("Please select a file.");
            }
        });
    });
    </script>

</head>

<body>
    <div class="container">
        <form method="post" action="" enctype="multipart/form-data" id="myform">
            <div class='preview'>
                <img src="upload/default.png" id="img" width="100" height="100">
            </div>
            <div>
                <input type="text" id="username">
                <input type="file" id="file" name="file" />
                <input type="button" class="button" value="Upload" id="but_upload">
            </div>
        </form>
    </div>
</body>

</html>
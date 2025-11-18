

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../assets/styleEn.css" />
<script src="../assets/jquery.js"></script>
<script src="../assets/variables.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
<input type="file" id="MediaFile" name="my_file" /><br /><br />
<input type="submit" id="btnSubmit" name="submit" value="Upload"/>

</body>
<script>

$('#btnSubmit').click(function () {
    var Url=$APIUrl+"Upload";
    var fileInput = document.getElementById('MediaFile');
    
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file', file);
    $.ajax({
            type: 'POST',
            url: Url,
            data: new FormData,
            success: function(response){ //console.log(response);
            }
        });
});
</script>
</html>
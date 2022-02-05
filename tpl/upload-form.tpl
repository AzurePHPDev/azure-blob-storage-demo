<html>
<head>
    <title>Upload a file to blob storage</title>
    <style type="text/css">body { font-size: x-large; width: 800px; margin: 40px auto; text-align: center; }</style>
</head>
<body>
<form method="post" action="/" enctype="multipart/form-data">
    <label for="upload-file">File to upload</label>
    <input type="file" id="upload-file" name="blob">
    <input type="submit" value="Upload">
</form>
</body>
</html>
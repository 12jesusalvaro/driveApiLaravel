<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
        <title>Google drive api</title>

    
    </head>
    <body class="antialiased">
        <br><br>
        <h1>Subir Archivo a Google Drive</h1>
        <br>
        <form action="/upload" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".pdf, .doc, .docx">
            <button type="submit">Subir Archivo</button>
        </form>
    </body>
</html>

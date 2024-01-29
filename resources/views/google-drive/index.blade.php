<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .visibility-toggle {
            padding: 5px 10px;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
        }

        .visibility-toggle.public {
            background-color: #4CAF50;
            color: white;
        }

        .visibility-toggle.private {
            background-color: #f44336;
            color: white;
        }

        .visibility-toggle.public .toggle-label {
            transform: translateX(0);
        }

        .visibility-toggle.private .toggle-label {
            transform: translateX(10%);
        }

        .toggle-label {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            transition: transform 0.3s;
        }
    </style>

    <title>Google Drive API - Laravel</title>
</head>

<body>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <br>
    <div class="container mt-4">
        <h3 class="row justify-content-center">Subir archivo a drive</h3>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('google-drive.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="file" name="file" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png" class="form-control">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Subir Archivo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br> <br>
    <div class="container mt-4">
        <h3 class="row justify-content-center">Crear directorios</h3>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="{{ route('google-drive.makedirectory') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="directory"  class="form-control">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br>

    <div class="container mt-4">
        <h3 class="row justify-content-center">Lista de archivos en carpeta drive</h3>
        <div class="row justify-content-center">
            <div class="col-md-9">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Propietario</th>
                        <th scope="col">Visivilidad</th>
                        <th scope="col">Descargar</th>
                        <th scope="col">Previsualizar</th>
                        <th scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (array_map(null, $details, $visibilities) as [$detail, $visibility ])
                        <tr>
                            <td>{{ $detail["filename"] }}</td>
                            <td>{{ $detail["extension"] }}</td>
                            <td>{{ $detail["extension"] }}</td>
                            <td>
                                <button class="visibility-toggle {{ $visibility === 'public' ? 'public' : 'private' }}"
                                        data-visibility="{{ $visibility }}"
                                        data-filename="{{ $detail['path'] }}">
                                    <span class="toggle-label">{{ $visibility === 'public' ? 'Público' : 'Privado' }}</span>
                                </button>
                            </td>
                            <td>
                                <a href="{{ route('google-drive.download', ['filename' => $detail['path']]) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </td>
                            <td>
                                <!-- Botón para abrir el modal -->
                                <button type="button" class="btn btn-primary open-modal-btn"
                                        data-filename="{{ $detail['path'] }}"
                                        data-toggle="modal"
                                        data-target="#fileViewerModal">
                                    Ver Archivo
                                </button>
                            </td>

                            <td>
                                <a href="{{ route('google-drive.delete-file', ['filename' => $detail['path']]) }}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">
                                    <i class="fas fa-trash-alt"></i>
                                    </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>



        <div class="container mt-4">
            <h3 class="row justify-content-center">Lista de directorios en carpeta drive</h3>
            <div class="row justify-content-center">
                <div class="col-md-9">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($directories as $directory)
                            <tr>
                                <td>{{ $directory }}</td>
                                <td>
                                    <a href="{{ route('google-drive.delete-directory', ['directory' => $directory]) }}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">
                                        <i class="fas fa-trash-alt"></i>
                                        </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

    </div>

<!-- Modal para previsualizar archivos -->
<div class="modal fade" id="fileViewerModal" tabindex="-1" role="dialog" aria-labelledby="fileViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="fileViewer" width="100%" height="600"></iframe>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.visibility-toggle');

            toggleButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const currentVisibility = button.getAttribute('data-visibility');
                    const filename = button.getAttribute('data-filename');
                    const newVisibility = currentVisibility === 'public' ? 'private' : 'public';

                    try {
                        const response = await axios.post('{{ url("google-drive/change-visibility") }}/' + filename, {
                            visibility: newVisibility,
                        });

                        button.setAttribute('data-visibility', newVisibility);
                        button.classList.remove('public', 'private');
                        button.classList.add(newVisibility);
                    } catch (error) {
                        console.error('Error al cambiar la visibilidad', error);
                    }
                });
            });
        });
    </script>


<script>
    $(document).ready(function () {
        $('.open-modal-btn').on('click', function () {
            // Obtener el nombre del archivo desde el botón
            var filename = $(this).data('filename');

            // Construir la URL de previsualización
            var previewUrl = "https://drive.google.com/file/d/" + filename + "/preview";

            // Establecer la URL en el iframe del modal
            $('#fileViewer').attr('src', previewUrl);

            // Mostrar el modal
            $('#fileViewerModal').modal('show');
        });
    });
</script>

<!-- Script para cargar el iframe al hacer clic en el botón -->

<!--
<script>
    $(document).ready(function() {
        // Al mostrar el modal, construye y carga el iframe
        $('#fileViewerModal').on('show.bs.modal', function (event) {
            var fileId = $(event.relatedTarget).data('filename'); // Obtiene el ID del botón
            var iframeSrc = 'https://drive.google.com/file/d/' + fileId + '/preview';

            // Crea el elemento iframe y lo agrega al contenedor
            var iframe = $('<iframe>', {
                src: iframeSrc,
                width: '100%',
                height: 600
            });
            $('#iframeContainer').html(iframe);
        });
    });
</script>
-->

    <!--
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Añade un evento de clic a cada botón
            var copyButtons = document.querySelectorAll('.btn-copy');
            copyButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    // Obtiene el enlace del atributo data-link
                    var linkToCopy = this.getAttribute('data-link');

                    // Crea un elemento de texto temporal y lo selecciona
                    var tempInput = document.createElement('textarea');
                    tempInput.value = linkToCopy;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);

                    // Muestra un mensaje de alerta
                    alert('Enlace copiado al portapapeles: ' + linkToCopy);
                });
            });
        });
    </script>
    -->
</body>
</html>


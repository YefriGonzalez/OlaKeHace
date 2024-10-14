<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OlaKeHace')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Barra de navegación fija -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">OlaKeHace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item d-flex justify-content-between align-items-center">
                        <i class="bi bi-check-circle-fill text-white"></i>
                        <a class="nav-link" href="#">Aprobar Publicaciones</a>
                    </li>

                    @if(Auth::user() && Auth::user()->idRol===1)
                    <li class="nav-item d-flex justify-content-between align-items-center">
                        <i class="bi bi-exclamation-diamond-fill text-white"></i>
                        <a class="nav-link" href="#">Publicaciones Reportadas</a>
                    </li>
                    @endif
                    <li class="nav-item  d-flex justify-content-between align-items-center">
                        <i class="bi bi-card-text text-white"></i>
                        <button type="button" class="nav-link btn" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            Agregar publicación
                        </button>
                    </li>
                    <li class="nav-item d-flex justify-content-between align-items-center">
                        <i class="bi bi-person-circle text-white"></i>

                        <a class="nav-link" href="#">Perfil</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal debajo de la barra -->
    <div class="container mt-5 pt-4">
        @yield('content')
    </div>

    <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar nueva publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createPostForm" action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Título</label>
                            <input type="text" name="nombre" class="form-control" id="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha del evento</label>
                            <input name="fecha" class="form-control" id="fecha" type="date" required></input>
                        </div>

                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora del evento</label>
                            <input name="hora" class="form-control" id="hora" type="time" required></input>
                        </div>
                        <div class="mb-3">
                            <label for="cupo" class="form-label">Cupo</label>
                            <input name="cupo" class="form-control" id="cupo" type="number" required></input>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">Url</label>
                            <input name="url" class="form-control" id="url" type="url" required></input>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">Tipo de publico</label>
                            <select class="form-select" aria-label="Default select example" name="tipoPublico" ic="tipoPublico">
                                <option selected value="TODOS">Todo publico</option>
                                <option value="NIÑOS">Niños</option>
                                <option value="ADOLESCENTES">Adolescentes</option>
                                <option value="ADULTOS">Adultos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Contenido</label>
                            <textarea name="descripcion" class="form-control" id="descripcion" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const quill = new Quill('#descripcion', {
            theme: 'snow'
        });
    </script>
</body>

</html>
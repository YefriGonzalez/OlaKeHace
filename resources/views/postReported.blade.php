@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <h2>Publicaciones reportadas</h2>
    <div class="col-md-12">
        @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif
        <form action="{{ route('home') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar publicaciones..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>

        @if($posts->count())
        <div class="list-group text-center">
            @foreach ($posts as $post)
            <div class="col-md-8 mb-4 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="card-title font-weight-bold">{{ $post->nombre }}</h2>
                        <h4 class="card-subtitle mb-2 text-muted">{{ $post->descripcion }}</h4>
                        <p class="card-text"><strong>Fecha: </strong>{{ $post->fecha }}</p>
                        <p class="card-text"><strong>Hora:</strong> <span class="text-primary">{{ $post->hora }}</span></p>
                        <p class="card-text"><strong>Cupo:</strong> <span class="text-success">{{ $post->cupo }}</span></p>
                        <p class="card-text"><strong>Cupos disponibles:</strong> <span class="text-secondary">{{ $post->cupo - $post->cantidadEvento }}</span></p>
                        <p class="card-text"><strong>Tipo de publico:</strong> <span class="text-success">{{ $post->tipoPublico }}</span></p>
                        <small class="text-muted">Publicado el {{ $post->created_at->format('d M, Y') }}</small> -
                        <small class="text-muted">Usuario: <strong>{{$post->username}}</strong></small>
                        <br />
                        <button id="ban-btn" data-post-id="{{ $post->id }}" class="btn btn-secondary mt-3">
                            <i class="bi bi-check-circle"></i> Banear
                        </button>

                        <button id="omitir-btn" data-post-id="{{ $post->id }}" class="btn btn-danger mt-3">
                            <i class="bi bi-exclamation-circle"></i> Omitir reportes
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $posts->withQueryString()->links() }}
        </div>
       
        @else
        <div class="alert alert-warning" role="alert">
            No se encontraron publicaciones reportadas.
        </div>
        @endif
    </div>
</div>
<script>
    document.getElementById('ban-btn').addEventListener('click', function() {
        let postId = this.getAttribute('data-post-id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción baneara la publicación!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, banear',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/post/ban/${postId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Aprobado!',
                                data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
                    });
            }
        });
    });

    document.getElementById('omitir-btn').addEventListener('click', function() {
        let postId = this.getAttribute('data-post-id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción omitira los reportes y la publicacion volvera a aparecer en el menu principal!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, omitir reportes',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/post/omitBan/${postId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data,data.success);
                        if (data.success) {
                            Swal.fire(
                                '¡Rechazado!',
                                data.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema al procesar la publicación.', 'error');
                    });
            }
        });
    });
</script>
@endsection
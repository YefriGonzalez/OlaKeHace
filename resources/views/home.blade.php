@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
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
                            @auth
                            <button class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#asistirModal">
                                <i class="bi bi-check-circle"></i> Asistir
                            </button>
                            <button class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#reportModal" data-post-id="{{ $post->id }}">
                                <i class="bi bi-exclamation-circle"></i> Reportar
                            </button>
                            @endauth

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-3 mb-3">
                {{ $posts->withQueryString()->links() }}
            </div>
            @else
            <div class="alert alert-warning" role="alert">
                No se encontraron publicaciones.
            </div>
            @endif
        </div>
    </div>
</div>


<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Reportar Publicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Cuál es el motivo del reporte?</p>
                <ul class="list-group">
                    <li class="list-group-item">
                        <input type="radio" name="reportReason" id="spam" value="spam">
                        <label for="spam">Spam</label>
                    </li>
                    <li class="list-group-item">
                        <input type="radio" name="reportReason" id="inappropriate" value="inappropriate">
                        <label for="inappropriate">Contenido inapropiado</label>
                    </li>
                    <li class="list-group-item">
                        <input type="radio" name="reportReason" id="false-info" value="false-info">
                        <label for="false-info">Información falsa</label>
                    </li>
                    <li class="list-group-item">
                        <input type="radio" name="reportReason" id="other" value="other">
                        <label for="other">Otro</label>
                        <div class="mt-3" id="otherReasonContainer" style="display: none;">
                            <label for="otherReason" class="form-label">Especifica el motivo:</label>
                            <input type="text" class="form-control" id="otherReason" placeholder="Especifica aquí...">
                        </div>
                    </li>

                </ul>

            </div>
            @auth
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="submitReport">Enviar Reporte</button>
            </div>
            @endauth

        </div>
    </div>
</div>
<script>
    let currentPostId;

    document.querySelectorAll('.btn-danger[data-bs-target="#reportModal"]').forEach((button) => {
        button.addEventListener('click', function() {
            currentPostId = this.getAttribute('data-post-id');
        });
    });

    document.querySelectorAll('input[name="reportReason"]').forEach((elem) => {
        elem.addEventListener('change', function() {
            const otherReasonContainer = document.getElementById('otherReasonContainer');
            if (this.value === 'other') {
                otherReasonContainer.style.display = 'block';
            } else {
                otherReasonContainer.style.display = 'none';
            }
        });
    });
    document.getElementById('submitReport').addEventListener('click', function() {
        const selectedReason = document.querySelector('input[name="reportReason"]:checked');
        let reportReasonValue;

        if (selectedReason) {
            if (selectedReason.value === 'other') {
                reportReasonValue = document.getElementById('otherReason').value;
                if (!reportReasonValue) {
                    alert('Por favor especifica el motivo.');
                    return;
                }
            } else {
                reportReasonValue = selectedReason.value;
            }

            fetch('{{ route("post.report") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        post_id: Number(currentPostId),
                        reason: reportReasonValue,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success == true) {
                        var reportModal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
                        reportModal.hide();
                        Swal.fire(
                            'Exito!',
                            data.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema al procesar la solicitud.', 'error');
                });

        } else {
            alert('Por favor selecciona un motivo para reportar.');
        }
    });
</script>
@endsection
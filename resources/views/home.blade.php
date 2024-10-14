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
                        
                            <a  class="btn btn-secondary mt-3">
                                <i class="bi bi-check-circle"></i> Asistir
                            </a>
                            <a  class="btn btn-danger mt-3">
                                <i class="bi bi-exclamation-circle"></i> Reportar
                            </a>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $posts->withQueryString()->links() }}
            </div>
            <nav aria-label="Page navigation example text-center">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            @else
            <div class="alert alert-warning" role="alert">
                No se encontraron publicaciones.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
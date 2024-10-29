@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <h2>Mis publicaciones</h2>
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
                        <small class="text-muted">Estado: <strong>{{$post->cantidadReporte>=3 ?$post->estado==="APROBADO"?"En revision de reporte":$post->estado:$post->estado}}</strong></small>
                        <br />

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
            No se encontraron publicaciones.
        </div>
        @endif
    </div>
</div>
@endsection
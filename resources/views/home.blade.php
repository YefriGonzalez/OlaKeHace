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
            <!-- Formulario de búsqueda -->
            <form action="{{ route('home') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar publicaciones..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </div>
            </form>

            @if($posts->count())
            <div class="list-group">
                @foreach ($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                    <h5 class="mb-1">{{ $post->title }}</h5>
                    <small class="text-muted">Publicado el {{ $post->created_at->format('d M, Y') }}</small>
                </a>
                @endforeach
            </div>

            <!-- Paginación -->
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
</div>
<!-- Modal para agregar una publicación -->

@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Canjear Cupón</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cupones.canjear') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="codigo">Código del Cupón:</label>
            <input type="text" name="codigo" id="codigo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Canjear</button>
    </form>
</div>
@endsection
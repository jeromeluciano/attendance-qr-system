@extends('layouts.app')

@section('content')
    @error('name')
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                {{$error}}
            @endforeach
        </div>
    @enderror

    <form action="{{route('section.store')}}" method="post">
        @csrf

        <div class="form-group">
            <label for="name">Section name</label>
            <input id="name" type="text" name="name" class="form-control">
        </div>

        <button class="btn btn-primary">Submit</button>
    </form>

@endsection

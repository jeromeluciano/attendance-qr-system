@extends('layouts.app')

@section('content')
    <a class="btn btn-success" href="{{route('section.create')}}">Create Section</a>
    <table class="table mt-2 bg-light">
        <thead class="thead-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sections as $section)
            <tr>
                <td>{{$section->id}}</td>
                <td>{{$section->name}}</td>
                <td>
                    <a href="{{route('section.update', ['section' => $section])}}" class="btn btn-primary float-left mr-2">Edit</a>
                    <form action="{{route('section.destroy', ['section' => $section])}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger float-left">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection

@extends('layouts.app')

@section('content')

    <form action="{{route('search.find')}}" method="post" id="searchForm">
        @csrf
        <div class="input-group w-50">
            <input id="searchInput" type="text" class="form-control " name="query" placeholder="Search">
        </div>
    </form>
    <table class="table mt-2 bg-light">
        <thead class="thead-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Section</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody id="t-content">
        @foreach($students as $student)
            <tr>
                <td>{{$student->id}}</td>
                <td>{{$student->name}}</td>
                <td>{{$student->section->name}}</td>
                <td>
                    <form action="{{route('student.destroy', ['student' => $student])}}" method="post">
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
<script src="{{asset('js/app.js')}}"></script>
<script>
    $(document).ready(function() {
        const searchInput = document.querySelector('#searchInput');
        let data = [];
        searchInput.addEventListener('keydown', () => {
            let data = $('#searchForm').serialize();
            $.ajax({
                url: '{{route('search.find')}}',
                type: 'POST',
                data: data,
                success: function (data) {
                    let tbody = document.getElementById('t-content');
                    // for(elem of data) {
                    //     console.log(elem);
                    // }
                    tbody.innerHTML = '';
                    let keys = Object.keys(data);
                    console.log(data);
                    console.log(keys)
                    let element = '';
                    for(let i = 0; i < keys.length; i++) {
                        let row = data[keys[i]];
                        element +=
                        `
                            <tr>
                                <td>${row.id}</td>
                                <td>${row.name}</td>
                                <td>${row.section.name}</td>
                                <td>
                                    <form action="http://192.168.20.238/students/${row.id}" method="post">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <input type="hidden" name="_token" value="${row._token}"/>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                    }
                    console.log(element);
                    tbody.innerHTML = element;
                }
            });
        });
    });
</script>

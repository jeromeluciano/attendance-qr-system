@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-6">
        <form action="#" method="post" id="filterForm" class="row">
            @csrf
            <div class="form-inline col">
                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">From</span>
                        </div>
                        <input type="text" aria-label="From" name="from" id="from" class="form-control w-25">
                        <div class="input-group-prepend">
                            <span class="input-group-text">To</span>
                        </div>
                        <input type="text" aria-label="To" name="to" id="to" class="form-control w-25">
                        <select name="section_id" id="section_id" class="form-control mr-1" style="width: 5px !important;">
                            <option value="all" selected>All</option>
                            @foreach($sections as $section)
                                <option value="{{$section->id}}">{{$section->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
        </div>
        <div class="col-sm-6">
            <form id="todayFilterForm" class="d-inline mr-1">
                @csrf
                <input type="hidden" name="from" value="{{today('Asia/Manila')->format('Y-m-d')}}">
                <input type="hidden" name="to" value="{{today('Asia/Manila')->format('Y-m-d')}}">
                <input type="hidden" name="section_id" id="sectionToday">
                <button class="btn btn-primary" id="todayBtn">Today</button>
            </form>

            <form id="yesterdayFilterForm" class="d-inline mr-1">
                @csrf
                <input type="hidden" name="from" value="{{\Illuminate\Support\Carbon::yesterday('Asia/Manila')}}">
                <input type="hidden" name="to" value="{{\Illuminate\Support\Carbon::yesterday('Asia/Manila')}}">
                <input type="hidden" name="section_id" id="sectionYesterday">
                <button class="btn btn-primary" id="yesterdayBtn">Yesterday</button>
            </form>

            <form id="weekFilterForm" class="d-inline mr-1">
                @csrf
                <input type="hidden" name="from" value="{{\Illuminate\Support\Carbon::now('Asia/Manila')->startOfWeek()}}">
                <input type="hidden" name="to" value="{{\Illuminate\Support\Carbon::now('Asia/Manila')->endOfWeek()}}">
                <input type="hidden" name="section_id" id="sectionWeek">
                <button class="btn btn-primary" id="weekBtn">This week</button>
            </form>

            <form id="monthFilterForm" class="d-inline mr-1">
                @csrf
                <input type="hidden" name="from" value="{{\Illuminate\Support\Carbon::now('Asia/Manila')->startOfMonth()}}">
                <input type="hidden" name="to" value="{{\Illuminate\Support\Carbon::now('Asia/Manila')->endOfMonth()}}">
                <input type="hidden" name="section_id" id="sectionMonth">
                <button class="btn btn-primary" id="monthBtn">This month</button>
            </form>
        </div>
    </div>
    <table class="table mt-3 bg-light">
        <thead class="thead-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Section</th>
            <th scope="col">Time arrived</th>
            <th scope="col">Date</th>
        </tr>
        </thead>
        <tbody id="t-body">
        @foreach($attendances as $attendance)
            <tr>
                <td>{{$attendance->id}}</td>
                <td>{{$attendance->user->name}}</td>
                <td>{{$attendance->section->name}}</td>
                <td>{{timeArrived($attendance->time_arrived)}}</td>
                <td>{{dateAttendance($attendance->date)}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <script>
        function updateTable(data) {
            let tbody = document.getElementById('t-body');
            let generatedHtml = '';
            let keys = Object.keys(data);

            for(let i = 0; i < keys.length; i++) {
                let row = data[keys[i]];
                generatedHtml +=
                    `
                <tr>
                    <td>${row.id}</td>
                    <td>${row.user.name}</td>
                    <td>${row.section.name}</td>
                    <td>${row.time_arrived}</td>
                    <td>${row.date}</td>
                </tr>
                    `;
            }
            tbody.innerHTML = generatedHtml;
        }

        function searchRequest(form) {
            let data = $(`#${form}`).serialize();
            $.ajax({
               url: '{{route('attendance.filter')}}',
               method: 'POST',
               data: data,
               success: (data) => {
                   updateTable(data);
               }
            });
        }

        $('#from').datepicker({dateFormat: 'yy-mm-dd'});
        $('#to').datepicker({dateFormat: 'yy-mm-dd'});

        $('#from').change(function() {
            searchRequest('filterForm');
        });

        $('#to').change(function() {
            searchRequest('filterForm');
        });

        $('#todayBtn').click((e) => {
            e.preventDefault();
            $('#sectionToday').val($('#section_id').val())
            searchRequest('todayFilterForm');
        })

        $('#yesterdayBtn').click((e) => {
            e.preventDefault();
            $('#sectionYesterday').val($('#section_id').val())
            searchRequest('yesterdayFilterForm');
        })
        $('#weekBtn').click((e) => {
            e.preventDefault();
            $('#sectionWeek').val($('#section_id').val())
            searchRequest('weekFilterForm');
        })

        $('#monthBtn').click((e) => {
            e.preventDefault();
            $('#sectionMonth').val($('#section_id').val())
            searchRequest('monthFilterForm');
        })




    </script>

@endsection


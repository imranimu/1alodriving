@extends('layouts.admin.layer')
@section('title', 'Question Lists | Driving School')
@section('content')
    <style>
        table {
            width: 100%;
            text-align: left;
            margin-bottom: 20px;
        }

        table.datatable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table.datatable td,
        table.datatable th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 14px;
        }

        table.datatable tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"></h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Student Report Lists</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Report</h4>
                </div><!-- end card header -->

                <div class="card-body">

                    <div class="live-preview">
                        <table class="datatable">
                            <thead>
                                <tr>
                                    <th>Unit #</th>
                                    <th>Date Completed</th>
                                    <th>Time Spent(miniutes)</th>
                                    <th>Assesment Grade</th>
                                    <th>Make-up-date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $end_date = '';
                                    $count = 1;
                                    $exam_percentage = 0;
                                @endphp
                                @if (!blank($getCourseActivities))
                                    @foreach ($getCourseActivities as $val)
                                        @php
                                            $datetime_1 = $val->start_date_time;
                                            $datetime_2 = $val->end_date_time;

                                            $start_datetime = new DateTime($datetime_1);
                                            $diff = $start_datetime->diff(new DateTime($datetime_2));
                                        @endphp
                                        <tr>
                                            <td>{{ $count }}</td>
                                            <td>{{ $val->completed_date }}</td>
                                            <td>{{ $diff->h }}:{{ $diff->i }}:{{ $diff->s }}</td>
                                            <td>{{ $val->exam_percentage }}/100</td>
                                            <td>{{ $val->created_at }}</td>
                                        </tr>
                                        @php
                                            $count++;
                                            $end_date = $val->completed_date;
                                            $exam_percentage += $val->exam_percentage;
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>



                    </div>


                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>

@endsection

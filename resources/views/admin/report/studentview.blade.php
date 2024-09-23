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

        <div class="col-md-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Course Status</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    @php
                        $completedLesson = getAdminLastCourseLessonCompleted(9, $student_id);

                        if(isset($completedLesson->module_status)){
                            $module_status = $completedLesson->module_status;
                        }else{
                            $module_status = 0;
                        }

                        if (isset($completedLesson->complete_lession)) {
                            $TotalCompleted = array_map('intval', explode(',', trim($completedLesson->complete_lession, '[]')));
                        }else{
                            $TotalCompleted = array();
                        }

                        $CompletedCount = count($TotalCompleted);
                    @endphp

                    <ul class="text-left ModuleView">
                        @foreach (getCoursesModules(9) as $Module)
                            @php
                                $getResult = adminGetCourseLessonPercentage(9, $Module->id, $student_id);

                                // Set the class based on the $getResult value
                                $moduleClass = '';
                                if ($getResult >= 100) {
                                    $moduleClass = 'complete';
                                } elseif ($getResult > 0 && $getResult < 100) {
                                    $moduleClass = 'active';
                                }
                            @endphp
                            <li class="{{ $moduleClass }}">
                                <p style="position: relative; margin: 0px; z-index: 99; font-weight: bold;">
                                    @if($getResult >= 100)
                                        <i class="fa fa-unlock"></i>
                                    @elseif($getResult > 0)
                                        <i class="fa fa-spinner"></i>
                                    @else
                                        <i class="fa fa-lock"></i>
                                    @endif
                                    {{ $Module->name }}

                                    @php
                                        $getLessions = getCourseLession(9, $Module->id);

                                        $ids = [];
                                        foreach ($getLessions as $item) {
                                            $ids[] = $item['module_id'];
                                        }

                                        $TotalLession = count($ids);

                                        echo '<span class="LessonCount">'  .$TotalLession. ' Pages</span>';

                                    @endphp
                                </p>
                                <span style="width: {{ $getResult }}% " class="LessonProgress"></span>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"><i class="ri-question-line" style="position: relative; top: 2px;"></i> Security Questions</h4>
                </div><!-- end card header -->
                <div class="card-body">

                    <ul style="list-style-type: none; padding-left: 15px;">
                        @foreach ($getAllQuestion as  $key => $val)
                            <li class="mb-2">{{ $key+1 }}. {{ $val->question }} </br> Ans: {{ $val->ans }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <table class="table table-bordered datatable">
                <thead>
                    <tr>
                        <th scope="col">#SL</th>
                        <th scope="col">Exam Title</th>
                        <th scope="col">Courses Name</th>
                        <th scope="col">Module</th>
                        <th scope="col">Exam Status</th>
                        <th scope="col">Exam Percentage</th>
                        {{-- <th scope="col">Result</th>
                        <th scope="col">Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @php
                       $records = getStudentExams($student_id);
                    @endphp
                    @if (!blank($records))
                        @php $count = 1; @endphp
                        @foreach ($records as $val)

                            @php
                              // echo  getExamStart($val->id, $val->courses_id)
                            @endphp

                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ $val->title }}</td>
                                <td>{{ $val->courses_name }}</td>
                                <td>{{ $val->module_name }}</td>
                                <td>{{ $val->exam_status == '2' ? 'Finished' : 'Pending' }}</td>
                                <td>{{ $val->exam_percentage }}%</td>
                                {{-- <td>
                                    @if ($val->exam_status == '2')
                                       <a href="{{ url('/admin/view-result/' . $val->exam_id) }}"
                                            class="badge badge-success">View
                                            Result</a>
                                    @elseif ($val->exam_status == '1')
                                        <a href="{{ url('/admin/view-result/' . $val->exam_id) }}"
                                            class="badge badge-danger">View
                                            Result</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($val->exam_status == '2' && $val->completed_at != '')
                                        -
                                    @else
                                        @if (getExamStart($val->id, $val->courses_id))
                                            <a href="{{ url('student/join-exam/' . $val->exam_id) }}"
                                                class="btn btn-primary btn-sm">
                                                {{ $val->exam_status == '1' || $val->exam_status == '3' ? 'Retake' : 'Start Exam' }}
                                            </a>
                                        @else
                                            <label disabled>Exam Processing</label>
                                        @endif
                                    @endif
                                </td> --}}
                            </tr>
                            @php $count++; @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No Courses Exam Found!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Report</h4>
                </div>

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


                </div>
            </div>
        </div> --}}
        <!-- end col -->
    </div>

@endsection

<style>
    .ModuleView{
        list-style: none;
        padding: 0px;
    }
    .ModuleView li{
        border: 1px solid #ccc;
        background: #f1f1f1;
        padding: 7px 14px;
        border-radius: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    .ModuleView li:last-child{
        margin-bottom: 0px;
    }
    .ModuleView li.complete {
        background: rgb(200 242 200);
        border: 1px solid rgb(157 237 157);
    }
    .ModuleView li.active{
        background: #EFC45C;
    }

    .ModuleView li span.LessonProgress {
        width: 0%;
        background: rgb(200 242 200);
        position: absolute;
        height: 100%;
        left: 0;
        bottom: 0;
        border-radius: 10px;
        z-index: 1;
    }

    .ModuleView li span.LessonCount{
        float: right;
    }

    .progressBar {
        width: 100%;
        height: 30px;
        background-color: #f0f0f0;
        border-radius: 5px;
        overflow: hidden;
    }
    .progressBar .progress {
        width: 0;
        height: 100%;
        background-color: #4CAF50;
        transition: width 0.5s ease-in-out;
    }

    .ModuleView li i.fa-spinner{
        animation: spin 2s linear infinite;
    }

    @keyframes  spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .glightbox_video {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 99999;
    }

    .outer_circle {
      stroke-width: 3;
      stroke-dasharray: 410;
       stroke-dashoffset: 0;
      stroke-linecap: square;
      transition: all .4s ease-out;
    }

    .glightbox_video:hover .outer_circle {
    stroke-dashoffset:410;
      transition: stroke .7s .4s ease-out, stroke-dashoffset .4s ease-out
    }

    .glightbox_video:hover
    .inner-circle {
      fill: #BF2428;
      transition:fill .4s .3s ease-out;
    }

    .glightbox_video:hover
    .play{
        fill: white;
      transition:fill .4s .3s ease-out;
    }
</style>

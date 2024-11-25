@extends('layouts.student.layer')
@section('title', 'Join Exam | Driving School')
@section('content')

    <!-- course area start -->
    <div class="course-area pd-bottom-120">
        <div class="container bg-white py-3">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="wrap wrap-content">
                        <h3 class="text-left">Result Details</h3>

                        <hr>

						<div class="row mb-3">
							<div class="col-md-6 CourseAnsInfo">
								<p>
									<!--<span><strong>Courses Name : </strong> {{ $getReuslt->title }}</span> |-->
									<span>{{ $getReuslt->module_name }}</span>
								</p>
								<p>
								    @if ($getReuslt->question_percentage >= 70)
								        <span style="color: #599A26; font-size: 20px; font-weight: bold;">Pass</span>
								    @else
								        <span style="color: #E9361F; font-size: 20px; font-weight: bold;">Fail</span>
								    @endif
								</p>
								<p>
								    <span><strong>Total : </strong> {{ $getReuslt->total_question }}</span> |
									<span><strong>Correst ans : </strong> {{ $getReuslt->yes_ans }}</span> |
									<span><strong>Wrong ans : </strong> {{ $getReuslt->no_ans }}</span>
								</p>
								<p><span><strong>Your Score : </strong> {{ $getReuslt->question_percentage }}%</span> |</p>
								<p><span><strong>Pass Mark : </strong> 70% out of 100%</span></p>
								<p></p>

                                @if ($exam_status == '0')

                                    @php

                                        $ReviewLession = getReviewLesson($getReuslt->course_id,  $getReuslt->module_id);

                                    @endphp

                                    <p class="alert alert-warning mt-3"> <i class="fa fa-exclamation-triangle"></i> Please check the review pages of this module. <a class="badge bg-info text-white" href="{{ url('student/course/' . $ReviewLession->course_id . '/' . $ReviewLession->module_id . '/' . $ReviewLession->id . '/2') }}">Click here</a></p>
                                @endif
							</div>
							@if ($getReuslt->question_percentage <= 69)

								<div class="col-md-6 text-right">
									<a class="btn btn-base" href="{{ url('/student/join-exam/'.$id) }}">Retake</a>

									<!--@if ($exam_status == '0')-->
                                        <!--  <a class="btn btn-warning" href="https://1aalodrivingschool.com/student/course/9/65/1901/2">Review</a>-->
                                    <!-- @endif-->
								</div>
							@endif
							@if ($getReuslt->question_percentage >= 70)
    							@php
    						        $nextModuleID  = getNextModule($getReuslt->course_id,  $getReuslt->module_id);
    						    @endphp

    						    @if($nextModuleID == 'All Completed')
        						    <div class="col-md-6 text-right">
        						        <p class="badge badge-success" >All Completed</p>
    						        </div>
    						    @else
    						        <div class="col-md-6 text-right">
        								<a href="{{ url('student/course/' . $nextModuleID['course_id'] . '/' . $nextModuleID['module_id'] . '/' . $nextModuleID['lesson_id'] . '/1') }}"
                                        class="btn btn-info btn-sm">Next Module <i class="fa fa-arrow-right"></i></a>
        							</div>
    						    @endif


							@endif
						</div>
						<hr>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="wrap wrap-content">
                        @php $getAnsQues = json_decode($getReuslt->result_json); @endphp
                        @if (!blank($getAnsQues))
                            <ul class="list-unstyled text-left resultLists">
                                @php $count = 1 @endphp
                                @foreach ($getAnsQues as $val)
                                    @php
                                        $badges = 'badge badge-danger';
										$AnsStatus = 'alert alert-danger';
                                        $icon = '<i class="fa fa-close wrongAns"></i>';
                                        if ($val->currect_ans == $val->select_ans):
                                            $badges = 'badge badge-success';
											$AnsStatus = 'alert alert-success';
                                            $icon = '<i class="fa fa-check correctAns"></i>';
                                        endif;
                                    @endphp
                                    <li class="{{$AnsStatus}}"><span>{{ $count }}. {{ $val->question }} </span> <span class="{{ $badges }}">
                                            Ans:
                                            {{ $val->select_ans }}</span> <span>{!! $icon !!}</span></li>
                                    @php $count++ @endphp
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .question-list {
            margin-left: 18px;
        }

        .courses-title h3 {
            font-size: 18px;
        }

		.CourseAnsInfo{
			text-align: left;
			width: 49%;
		}

		.CourseAnsInfo p{
			margin-bottom: 3px;
		}

		.resultLists{}

		.resultLists li{
			border: 1px solid #ccc;
			border-radius: 10px;
			padding: 5px 20px;
			margin-bottom: 15px;
		}

		.resultLists li span:nth-child(1), .resultLists li span:nth-child(2){
			margin-right: 10px;
		}
		.resultLists li span:nth-child(2){
			font-size: 14px;
		}
		.resultLists li span:nth-child(3){

		}
    </style>
@endsection

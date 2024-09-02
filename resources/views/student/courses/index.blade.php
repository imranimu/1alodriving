@extends('layouts.student.layer')
@section('title', 'Course | Driving School')
@section('content')
    
    <div style="
            position: absolute;
            left: 10px;
            width: 140px;
            top: 10px;"
    >
        <a href="{{ url('student/course-lists') }}" class="btn btn-warning btn-sm w-100 mb-3"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
        
        <a href="{{ url('student/dashboard') }}" class="btn btn-warning btn-sm w-100 mb-3"><i class="fa fa-arrow-left"></i>&nbsp;Dashboard</a>

    </div>
    
    <div style="position: absolute; right: 10px; top: 10px; width: 140px;">
        
        <a href="javascript:;" data-toggle="modal" data-target="#chooseBook"
        class="btn btn-warning btn-sm w-100 mb-3">Select Chapter</a>
        
        <select class="book_page form-control" style="height:38px !important; background: #f7b84b; color: #000; border-radius: 50px;" onchange="changeLession(this.value)">
            <option value="">Select Lesson</option>
            @if (!blank(getCourseLessionData($course_id, $module_id)))
                @foreach (getCourseLessionData($course_id, $module_id) as $lession)
                    <option value="{{ $lession->id }}"
                        {{ Request::segment(5) == $lession->id ? 'selected' : '' }}>
                        {{ $lession->title }}</option>
                @endforeach
            @endif
        </select>
    </div>

    <!-- course area start -->

    <div class="course-areas">

        <div class="container py-3">
            <!-- @include('layouts/student/top_navbar') -->
            
            <!--div class="row mb-2">
                <div class="col-md-2">
                    <a href="{{ url('student/course-lists') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                </div>

                <div class="col-md-2"><a href="javascript:;" data-toggle="modal" data-target="#chooseBook"
                    class="btn btn-primary btn-sm">Select Chapter</a>
                </div>                 

                <div class="col-md-2">
                    <select class="book_page form-control" style="height:38px !important" onchange="changeLession(this.value)">
                        <option value="">Select Lession</option>
                        @if (!blank(getCourseLessionData($course_id, $module_id)))
                            @foreach (getCourseLessionData($course_id, $module_id) as $lession)
                                <option value="{{ $lession->id }}"
                                    {{ Request::segment(5) == $lession->id ? 'selected' : '' }}>
                                    {{ $lession->title }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>                 
            </div -->     

            <div class="book-page courseContainer" style="margin:0px;padding:0px;">

                <div class="with_bg_page">
                    @if (Request::segment(5) == '0' && $forward != '2')
                        <div class="row">
                            <a href="{{ url('student/course/' . Request::segment(3) . '/' . Request::segment(4) . '/' . $next_id . '/2') }}"
                                class="take_the_test">Lets start <i class="fa fa-arrow-right"></i></a>
                        </div>
                    @elseif (Request::segment(5) == 0 && $forward == 2)
						@if ($getModuleHistory[0]->module_status == 1)
							<div class="row pt-5" style="text-align: center; display: block">
								<h3 >Congratulations</h3>
								<p class="mb-3">You have completed the module</p> 
							</div>
						@endif
						
						
                    @else
						@php
							$extension = '';
							if (isset($getCourseLession->text_pdf)) :
								$extension = pathinfo(storage_path('app/public/files/'.$module_id.'/'.$getCourseLession->text_pdf), PATHINFO_EXTENSION);
							endif;
						@endphp
                        @if (isset($getCourseLession->lesson_type) && $getCourseLession->lesson_type == 'video')
							<video id="MyVideo" autoplay="" width="100%" controls>
								<source src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->video) }}"
									type="video/mp4">
								Your browser does not support HTML video.
							</video>
                        @elseif (isset($getCourseLession->lesson_type) && $getCourseLession->lesson_type == 'audio')
							@if ($getCourseLession->text_pdf !="")
								@if (in_array($extension, ['jpeg','jpg','png']))
									<img src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->text_pdf) }}" class="img img-resposive">
								@elseif (in_array($extension, ['doc','docx']))
									<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ url('storage/app/public/files/' . $module_id . '/' . $getCourseLession->text_pdf) }}" frameborder="0" style="width: 100%; min-height: 600px;"></iframe>
								@else	
									<iframe src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->text_pdf) }}"
										width="100%" height="800px">
									</iframe>
								@endif
							@endif
                        @elseif (isset($getCourseLession->lesson_type) && $getCourseLession->lesson_type == 'pdf')
							@if ($getCourseLession->text_pdf !="")
								@if (in_array($extension, ['jpeg','jpg','png']))
									<img src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->text_pdf) }}" class="img img-resposive">
								@elseif (in_array($extension, ['doc','docx']))
									<iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ url('storage/app/public/files/' . $module_id . '/' . $getCourseLession->text_pdf) }}" frameborder="0" style="width: 100%; min-height: 600px;"></iframe>
								@else
									<iframe src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->text_pdf) }}"
										width="100%" height="800px">
									</iframe>
								@endif
							@endif
                        @endif
                    @endif
                </div>
            </div>

            <div class="modal fade" id="chooseBook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Choose Book or a Chapter</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
							<form action="{{ url('student/course/course-module-change') }}" method="post">
								@csrf
								<div class="form-group">
									<label for="email">Book:</label>
									<input type="text" class="form-control select_book" name=""
										value="{{ getCourseName($course_id) }}" disabled>
								</div>
								<div class="form-group">
									<label for="email">Chapter:</label>
									<select name="module_id" class="form-control select_module">
										<option value="">Select</option>
										@if (!blank($getCoursesModule))
											@foreach ($getCoursesModule as $module)
												<option value="{{ $module->id }}"
													{{ Request::segment(4) == $module->id ? 'selected' : '' }}>
													{{ $module->name }}</option>
											@endforeach
										@endif
									</select>
								</div>
								<input type="hidden" value="{{ Request::segment(3)}}" name="courses_id"/>
								<button type="submit" class="btn btn-success pull-right go_module_btn">Go</button>
							</form>
                            <div style="float:none;clear:both"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="FlotingPlayer">
                <div class="progress" id="percentage"></div>
                <div class="navigation">
                    <div class="left-nav">
                        <!-- <button class="grit button controls__button-label text stretch iconOnly iconButton primary" data-test="courseflow-back"><span class="icon gritIcon--arrow-left"></span></button> -->
                        @php
                            $previous_id = 0;
                            if (!blank($previous_record)):
                                $previous_id = $previous_record->id;
                            endif;
                        @endphp

                        @if (Request::segment(5) == 0 && $forward == 2)
                            <a href="javascript:void(0)"></a>
                        @elseif (Request::segment(5) == 0 && Request::segment(6) == 2)
                            <a href="javascript:void(0)" class="btn btn-success btn-sm btn-zoomed btn-prev" style="width:100%">
                                <i class="fa fa-arrow-circle-o-left"></i> Previous
                            </a>
                        @else
                            <a href="{{ url('student/course/' . Request::segment(3) . '/' . Request::segment(4) . '/' . $previous_id . '/1') }}"
                                class="btn btn-success btn-sm btn-zoomed btn-prev" style="width:100%">
                                <i class="fa fa-arrow-circle-o-left"></i> Previous
                            </a>
                        @endif
                    </div>

                    <div class="mid-item controls">
                        <div class="voice_div">
                            @php
                                $audio_disable = '';
                                if (isset($getCourseLession->lesson_type) && (Request::segment(5) == 0 || $getCourseLession->lesson_type == 'video' || $getCourseLession->audio != '')):
                                    $audio_disable = 'pointer-events:none';
                                endif;
                            @endphp
                            @if (!blank($getCourseLession) && $getCourseLession->audio != '')
                                <audio controls="" id="myAudio" autoplay="" style="width:100%;margin-top:2px;height: 38px;">
                                    <source src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->audio) }}"
                                        type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @else
                                <audio controls="" autoplay="" style="width:100%;margin-top:2px; {{ $audio_disable }}">
                                    <source src="#" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endif
                        </div>
                        <!-- <div class="controls-btn-wrapper">
                            <button class="grit button controls__button-label text iconOnly iconButton primary">
                                <span class="icon gritIcon--skip-back"></span>
                            </button>
                            <button class="grit button controls__button-label text iconOnly iconButton primary">
                                <span class="icon gritIcon--ctrl-play"></span>
                            </button>
                            <button class="grit button controls__button-label text iconOnly iconButton primary" disabled="">
                                <span class="icon gritIcon--skip-forward"></span>
                            </button>
                        </div> -->
                    </div>
                    <div class="right-nav">
                        <!-- <button class="grit button controls__button-label text stretch iconOnly iconButton primary" data-test="courseflow-forward" id="arrow-next">
                            <span class="icon gritIcon--arrow-right"></span>
                        </button> -->

                        @php
                            $next_id = 0;
                            if (!blank($next_record)):
                                $next_id = $next_record->id;
                            endif;
                        @endphp
    					@if (Request::segment(5) == 0 && $forward == 2)
                            <!--<a href="{{ url('student/quiz') }}" class="btn btn-success btn-sm pull-right" style="width:100%">-->
                            <!--    All Exams &nbsp;<i class="fa fa-arrow-circle-o-right"></i>-->
                            <!--</a>--> 
                            
                            @php
                                if(isset($ExamList)){  
                                
                                    $NoExam = true;
                                    
                                    foreach ($ExamList as $key => $val) { 
                                        
                                        if($val->module_id == Request::segment(4)){  
                                             @endphp 
                                                @if($val->exam_status == '2')
                                                    <a href="{{ url('/student/view-result/' . $val->exam_id) }}"
                                                    class="badge badge-success mr-3">View
                                                    Result</a>
                                                @else
                                                    <a href="{{ url('student/join-exam/' . $val->exam_id) }}"
                                                        class="btn btn-primary btn-sm mr-3">
                                                        {{ $val->exam_status == '1' || $val->exam_status == '3' ? 'Retake Exam' : 'Start Exam' }}
                                                    </a>
                                                @endif
                                             @php 
                                            
                                            $NoExam = false; 
                                        } 
                                        
                                    }
                                    
                                    if($NoExam){ 
							        
							        $arrayData = json_decode($getCoursesModule, true);

                                    $lastElement = end($arrayData);   
                                    
                                    $getNextLesson = getCourseLession($course_id, $lastElement['id']); 
							        
							    @endphp
								    
								<a class="btn btn-warning btn-sm" href="{{ url('student/course/' . Request::segment(3) . '/' . $lastElement['id'] . '/'.$getNextLesson[0]->id.'/2') }}">Next Module</a>
								@php
                                    }
                                } 
                                
                            @endphp
                        @else
    						@php
                                $next_disabled_class = 'next-disabled';
                                if ($lession_status > 0):
                                    $next_disabled_class = '';
                                endif;
                            @endphp
    						<a href="{{ url('student/course/' . Request::segment(3) . '/' . Request::segment(4) . '/' . $next_id . '/2') }}"
    							class="btn btn-success btn-sm pull-right {{ $next_disabled_class }}" id="next-btn" style="width:100%">
    							Next&nbsp;<i class="fa fa-arrow-circle-o-right"></i>
    						</a>
    					@endif
                    </div>
                </div>
                                        
                <!-- <div class="containers">
                    <div class="row">                      
                        <div class="col-md-4 voice_div">
                            @php
                                $audio_disable = '';
                                if (isset($getCourseLession->lesson_type) && (Request::segment(5) == 0 || $getCourseLession->lesson_type == 'video' || $getCourseLession->audio != '')):
                                    $audio_disable = 'pointer-events:none';
                                endif;
                            @endphp
                            @if (!blank($getCourseLession) && $getCourseLession->audio != '')
                                <audio controls="" id="myAudio" autoplay="" style="width:100%;margin-top:2px;height: 38px;">
                                    <source src="{{ asset('storage/app/public/files/' . $module_id . '/' . $getCourseLession->audio) }}"
                                        type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @else
                                <audio controls="" autoplay="" style="width:100%;margin-top:2px; {{ $audio_disable }}">
                                    <source src="#" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @endif
                        </div>                         
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    
    <script>
		@if (Request::segment(5) == 0)
            (function() {
                if (window.localStorage) {
                    if (!localStorage.getItem('firstLoad')) {
                        localStorage['firstLoad'] = true;
                        window.location.reload();
                    } else
                        localStorage.removeItem('firstLoad');
                }
            })();
        @endif
		
		function changeLession(id) {
			let lesstionID = {{ Request::segment(5) }};
			let lessionProgress = 1;
			if (id > lesstionID) {
				lessionProgress = 2;
			}
			let urlPath = '{{ url("student/course/" . Request::segment(3) . "/" . Request::segment(4)) }}/'+ id +'/' + lessionProgress;
			window.location.href = urlPath;
		}
		
		 $(document).ready(function() {
            $.ajax({
                type: 'POST',
                url: '{{ url('student/get-course-lesson-percentage') }}',
                data: {
                    "course_id": {{ $course_id }},
                    "module_id": {{ $module_id }},
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    
                    if(response > 100){
                        response = 100;
                    } 
                    
                    let html = '';
                    html +=
                        '<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:' +
                        response + '%">' +
                        '</div>' +
                        '<label for="progress" class="progressLabel">' + response +
                        '% Completed.</label>';
                    $('#percentage').html(html);
                }
            });
        });
		
		var supposedCurrentTime = 0;
		@if (isset($getCourseLession->lesson_type) && $getCourseLession->lesson_type == 'audio' && $lession_status == 0)
			var audioPlayer = document.getElementById("myAudio");
			audioPlayer.addEventListener('timeupdate', function() {
				if (!audioPlayer.seeking) {
					supposedCurrentTime = audioPlayer.currentTime;
				}
			});

			audioPlayer.ontimeupdate = function() {
				if (this.currentTime === this.duration) {
				  // call your end event function here
					console.log('Audio completed ....');
					$('#next-btn').removeClass('next-disabled');
				}
			};	 
			
			audioPlayer.addEventListener("seeking", function(event) {
				//alert('asddd');
				var delta = audioPlayer.currentTime - supposedCurrentTime;

				if (Math.abs(delta) > 0.01) {
					console.log("Seeking is disabled");
					audioPlayer.currentTime = supposedCurrentTime;
				}
				event.preventDefault();
			});
		@endif
		
		@if (isset($getCourseLession->lesson_type) && $getCourseLession->lesson_type == 'video' && $lession_status == 0)
			var VideoPlayer = document.getElementById("MyVideo");
			VideoPlayer.addEventListener('timeupdate', function() {
				if (!VideoPlayer.seeking) {
					supposedCurrentTime = VideoPlayer.currentTime;
				}
			});

			VideoPlayer.ontimeupdate = function() {
				if (this.currentTime === this.duration) {
				  //call your end event function here
					console.log('Video completed ....');
					$('#next-btn').removeClass('next-disabled');
					
				}
			};

			VideoPlayer.addEventListener("seeking", function(event) {
				var delta = VideoPlayer.currentTime - supposedCurrentTime;
				if (Math.abs(delta) > 0.01) {
					console.log("Seeking is disabled");
					VideoPlayer.currentTime = supposedCurrentTime;
				}
				//alert('video . 000');
				event.preventDefault();
			});
		@endif
	</script>
    
    <style>
        body .navWrap{
            display: none;
        }
        a.take_the_test {
            background-color: #a61e4d;
            color: #eee;
            padding: 15px 25px;
            border: none;
            text-align: center;
            margin: 15px auto;
        }
        a.take_the_test:hover {
            background-color: #d6336c;
        }
        body .next-disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        #MyVideo{
            max-height: calc(100vh - 150px);
        }
        body #percentage.progress {
            height: 0px;
            margin: 0px 0 10pz;
        }
        body #percentage.progressLabel {             
            bottom: 9px;
        }

        .navbar.navbar-area-1.navbar-area{
            display: none;
        }

        body .course-area ul#main-navigation{
            margin-bottom: 0px;
        }

        body .with_bg_page iframe{
            height: calc(100vh - 260px);
        }

        .course-areas{
            padding: 0px 0 10px;
            background: #0b338b;
        }

        .courseContainer{
            background: #0b338b;
        }

        body .footer-area{
            display: none;
        }
        
        .topalert.alert-danger {
            /*top: 50px !important;*/
        }

        .FlotingPlayer {
            position: fixed;
            border-radius: 8px;
            box-shadow: 0 6px 10px 0 rgba(33,51,63,.15);
            margin-left: auto;
            margin-right: auto;
            min-width: 600px;
            margin-top: auto;
            background: rgba(250,250,250,0.8);
            border-top: 1px solid rgba(33,51,63,.1);
            display: flex;
            flex-direction: column;
            bottom: 10px;
            left: 50%;
            transform: translate(-50%, 0);
        }
        .FlotingPlayer .navigation{
            display: flex;
            justify-content: space-between;
            /*min-height: 84px;*/
            padding: 5px;
            align-items: center;
        }
        .FlotingPlayer .voice_div{
            width: 350px;
            height: 35px;
        }

        .FlotingPlayer .voice_div audio{
            height: 35px;
        }
        
        .courseContainer{
            margin: 0px;
            padding: 0px;
            height: calc(100vh - 40px);
            overflow: auto;
        }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .courseContainer::-webkit-scrollbar {
            display: none;
        }
        
        /* Hide scrollbar for IE, Edge and Firefox */
        .courseContainer {
          -ms-overflow-style: none;  /* IE and Edge */
          scrollbar-width: none;  /* Firefox */
        }

        body .progress { 
            position: relative;
            overflow: inherit;
        }

        body .progress-bar {
            background-color: #087608;
            display: none;
        }

        body .progressLabel {
            position: absolute;
            color: #000;
            left: 50%;
            top: -35px;
            transform: translate(-50%, -2px);
            z-index: 8;
            background: #28a745a1;
            padding: 2px 10px;
            border-radius: 18px;
            color: #fff;
        }
        a.badge.badge-success{
            padding: 10px 15px;
            font-size: 15px;
            border-radius: 40px;
            font-weight: 700;
            line-height: normal;
        }
    </style>
	 
@endsection

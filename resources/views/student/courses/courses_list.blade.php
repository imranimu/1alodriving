@extends('layouts.student.layer')
@section('title', 'Courses Lists | Driving School')
@section('content')

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
        
        @keyframes spin {
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
    
    <div class="Back">
        <a href="{{ url('student/dashboard') }}" class="btn-11"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    
    <!-- course area start -->
    <div class="course-area pb-5">
        <div class="container bg-white py-3">
			<!-- @include('layouts/student/top_navbar') -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="wrap wrap-content">
                        <!--table class="table table-bordered">
                            <thead>
                                <tr> 
                                    <th scope="col">Courses Name</th>
									<th scope="col">Total Module</th>
									<th scope="col">Module Complete</th>
                                    <th scope="col">Courses Progress</th>
                                    <th scope="col">Payment Details</th>
									<th scope="col">Payment Status</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($records))
                                    @php $count = 1; @endphp
                                    @foreach ($records as $val)
                                        <tr> 
                                            <td>{{ $val->get_course->title }}</td>
											<td>{{ $val->total_module }}</td>
											<td>{{ getModuleCompleted($val->course_id) }}</td>
                                            <td> 
                                                {{ getCoursePercetage($val->course_id) }}% 
                                            </td>
											<td><a href="javascript:void(0)" class="badge badge-success" onclick="paymentShow({{$val->id}})">History</a></td>
											<td>
                                                @if ($val->payment_status == 1 && $val->status == 1)
                                                    <label class="badge badge-success">Confirm</label>
                                                @else
                                                    <label class="badge badge-secondary">Pending</label>
                                                @endif
                                            </td>
											 @php
												$get_lession_status = getLastCourseLessionCompleted($val->course_id);
                                                $lession = DB::table('course_lessons')
                                                    ->where(['course_id' => $val->course_id, 'module_id' => $val->module_id])
													->whereRaw('deleted_at is null')
                                                    ->orderBy('id')
                                                    ->first();
                                            @endphp
                                            <td class="ActionButton">
											@if (isset($get_lession_status) && ($get_lession_status->complete_lession == '' || $get_lession_status->complete_lession != '') &&   $get_lession_status->ongoing_lession != '')
                                                    <a href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/' . $get_lession_status->ongoing_lession . '/2') }}" class="btn btn-primary ">Start</a>
                                                @elseif (isset($get_lession_status) && $get_lession_status->complete_lession != '' && $get_lession_status->ongoing_lession == '')
                                                    @php
                                                        $total_lession = $get_lession_status->complete_lession != '' ? count(json_decode($get_lession_status->complete_lession, true)) : 0;
                                                    @endphp
                                                    @if ($total_lession == $get_lession_status->total_lession && $get_lession_status->ongoing_lession == '')
                                                        <a href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/0/2') }}" class="btn btn-primary ">Start</a>
                                                    @else
                                                        <a href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/' . $get_lession_status->ongoing_lession . '/2') }}" class="btn btn-primary ">Start</a>
                                                    @endif
                                                @else
                                                    @if (!blank($lession))
                                                        <a href="{{ url('/student/course/' . $val->course_id . '/' . $val->module_id . '/' . $lession->id . '/2') }}" class="btn btn-primary ">Start</a>
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @php $count++; @endphp
                                    @endforeach
								@else
									<tr>
										<td colspan="8">No Courses Found!</td>
									</tr>
                                @endif
                            </tbody>
                        </table-->
                         
                        @if (!blank($records)) 
                            @foreach ($records as $val)
                                @php
                                    $completedLesson = getLastCourseLessionCompleted($val->get_course->id);
                                    
                                    if(isset($completedLesson->module_status)){
                                        $module_status = $completedLesson->module_status;
                                    }else{
                                        $module_status = 0;
                                    } 
                                    
                                    // echo $completedLesson; 
                                    
                                    //echo getCourseLessionData($val->get_course->id, $val->get_course->id);
                                    
                                    if (isset($completedLesson->complete_lession)) {
                                        $TotalCompleted = array_map('intval', explode(',', trim($completedLesson->complete_lession, '[]')));
                                    }else{
                                        $TotalCompleted = array();
                                    }
                                    
                                    // echo '<pre>';
                                    // print_r($TotalCompleted);
                                    // echo '</pre>'; 
                                    
                                    $CompletedCount = count($TotalCompleted);  
                                    
                                    // echo $CompletedCount; 
                                     
                                    // $getAllModules = getCoursesModules();
                                    // $currentData = 66;
                                    
                                    // $getresult = current(array_filter($getAllModules->toArray(), function($item) use ($currentData) { return $item['id'] == $currentData + 1; }));
                                    
                                    
                                    // print_r( $getresult); 
                                   
                                    
                                @endphp
                                
                                <!--<div class="progressBar">-->
                                <!--    <div class="progress" style="width: 50%;"></div>-->
                                <!--</div>--> 
                                
                                <div class="ActionButton">
									@if (isset($get_lession_status) && ($get_lession_status->complete_lession == '' || $get_lession_status->complete_lession != '') &&   $get_lession_status->ongoing_lession != '')
                                        <a class="glightbox_video" href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/' . $get_lession_status->ongoing_lession . '/2') }}" class="btn btn-primary "><svg width="131" height="131" viewBox="0 0 131 131" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="inner-circle" d="M65 21C40.1488 21 20 41.1488 20 66C20 90.8512 40.1488 111 65 111C89.8512 111 110 90.8512 110 66C110 41.1488 89.8512 21 65 21Z" fill="#EFC45C"></path>
                                            <circle class="outer_circle" cx="65.5" cy="65.5" r="64" stroke="#EFC45C"></circle>
                                            <text class="play" x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="16" font-family="Arial">START</text>
                                        </svg>
                                        </a>
                                                @elseif (isset($get_lession_status) && $get_lession_status->complete_lession != '' && $get_lession_status->ongoing_lession == '')
                                                    @php
                                                        $total_lession = $get_lession_status->complete_lession != '' ? count(json_decode($get_lession_status->complete_lession, true)) : 0;
                                                    @endphp
                                                    @if ($total_lession == $get_lession_status->total_lession && $get_lession_status->ongoing_lession == '')
                                        <a class="glightbox_video" href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/0/2') }}" class="btn btn-primary "><svg width="131" height="131" viewBox="0 0 131 131" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="inner-circle" d="M65 21C40.1488 21 20 41.1488 20 66C20 90.8512 40.1488 111 65 111C89.8512 111 110 90.8512 110 66C110 41.1488 89.8512 21 65 21Z" fill="#EFC45C"></path>
                                            <circle class="outer_circle" cx="65.5" cy="65.5" r="64" stroke="#EFC45C"></circle>
                                            <text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial">START</text>
                                        </svg></a>
                                                    @else
                                        <a class="glightbox_video" href="{{ url('/student/course/' . $get_lession_status->courses_id . '/' . $get_lession_status->module_id . '/' . $get_lession_status->ongoing_lession . '/2') }}" class="btn btn-primary "><svg width="131" height="131" viewBox="0 0 131 131" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="inner-circle" d="M65 21C40.1488 21 20 41.1488 20 66C20 90.8512 40.1488 111 65 111C89.8512 111 110 90.8512 110 66C110 41.1488 89.8512 21 65 21Z" fill="#EFC45C"></path>
                                            <circle class="outer_circle" cx="65.5" cy="65.5" r="64" stroke="#EFC45C"></circle>
                                            <text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial">START</text>
                                        </svg></a>
                                                    @endif
                                                @else
                                                    @if (!blank($lession))
                                        <a class="glightbox_video" href="{{ url('/student/course/' . $val->course_id . '/' . $val->module_id . '/' . $lession->id . '/2') }}" class="btn btn-primary "><svg width="131" height="131" viewBox="0 0 131 131" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="inner-circle" d="M65 21C40.1488 21 20 41.1488 20 66C20 90.8512 40.1488 111 65 111C89.8512 111 110 90.8512 110 66C110 41.1488 89.8512 21 65 21Z" fill="#EFC45C"></path>
                                            <circle class="outer_circle" cx="65.5" cy="65.5" r="64" stroke="#EFC45C"></circle>
                                            <text x="50%" y="53%" dominant-baseline="middle" text-anchor="middle" fill="#fff" font-size="16" font-family="Arial">START</text>
                                        </svg></a>
                                        @else
                                            -
                                        @endif
                                    @endif
                                </div> 
                                
                                
                                <ul class="text-left ModuleView">
                                    @foreach (getCoursesModules() as $Module)
                                        @php 
                                            $getResult = getCourseLessionPercetage($val->get_course->id, $Module->id); 
                                            
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
                                                    $getLessions = getCourseLession($val->get_course->id, $Module->id);
                                    
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

                            @endforeach
                        @endif  
                        
                    </div>
                </div>
            </div>
        </div>
		<!-- Modal -->
        <div class="modal fade" id="addonsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Payment History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="bodyBox">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .wrap.wrap-content {
            background: #fff;
            padding: 22px;
        }

        .wrap-content h3 {
            border-bottom: 1px solid #ccc;
            text-align: left;
        } 
        .Back {
            position: absolute;
            top: 90px;
            overflow: hidden;
            left: 20px;
            padding: 5px 0;
        }

        .Back a {
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 40px;
        }

        .Back a:hover{
            background: var(--main-color);
            color: #fff;
        }
        body .table td.ActionButton{
            position: absolute;
            border: none;
            left: 50%;
            transform: translate(-50%, 0px);
            bottom: -50px;
        }
        body .table td.ActionButton a.btn {
            background: #EFC45C;
            border-radius: 50px;
            width: 274px;
            color: #000;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 16px;
        }
        body .table td.ActionButton a.btn:hover{
            color: #fff;
        }
    </style>
     
	<script>
	function paymentShow(id) {
		$.ajax({
			type: 'POST',
			url: '{{ url('student/payment/get-payment-history') }}',
			data: {
				"id": id,
				"_token": "{{ csrf_token() }}"
			},
			success: function(response) {
				console.log(response);

				let html = '';
				html += '<table class="table table-bordered">' +
					'<thead>' +
					'<tr>' +
					'<th scope="col">Courses Name</th>' +
					'<th scope="col">Amount</th>' +
					'<th scope="col">Transaction ID</th>' +
					'<th scope="col">Purchase Date</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody>'+
					'<tr>'+
					'<td>' + response.get_course.title + '</td>' +
					'<td>' + response.total_amount + '</td>' +
					'<td>' + response.transaction_id + '</td>' +
					'<td>' + response.created_at + '</td>' +
					'</tr>';
					'</tbody>' +
				'</table>';
				html += '<table class="table table-bordered">' +
					'<thead>' +
					'<tr>' +
					'<th scope="col">#SL</th>' +
					'<th scope="col">Name</th>' +
					'<th scope="col">Amount</th>' +
					'<th scope="col">Created at</th>' +
					'</tr>' +
					'</thead>' +
					'<tbody>';
				'<tr>';
				if (response.get_addons.length > 0) {
					for (let i = 0; i < response.get_addons.length; i++) {
						html += '<th scope="row">' + (i + 1) + '</th>' +
							'<td>' + response.get_addons[i].name + '</td>' +
							'<td>' + response.get_addons[i].amount + '</td>' +
							'<td>' + response.get_addons[i].created_at + '</td>' +
							'</tr>';
					}
				} else {
					html += '<td colspan="4">No Addons Found!</td></tr>'
				}
				html += '</tbody>' +
					'</table>';
				$('#bodyBox').html(html);
				$('#addonsModal').modal('show');

			}
		});
	}
	</script>
@endsection

@extends('layouts.student.layer')
@section('title', 'Dashboard | Driving School')
@section('content')

    <div class="Back">
        <a href="{{ url('student/dashboard') }}" class="btn-11"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
    
    <!-- course area start -->
    <div class="course-area pb-5">
        <div class="container bg-white py-3">
            
            <!--<a href="{{ url('student/dashboard') }}" class="btn btn-primary btn-sm mb-4"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>-->

            <div style="min-height:400px">

                <div class="row">

                    <div class="col-md-12">
                        <div class="GuidelineContent">
                            @if (!blank($get_guideline))
                                @foreach ($get_guideline as $val)
                                    <h5>{{ $val->title }}</h5>
                                    {!! $val->description !!}
                                    <br />
                                @endforeach
                            @endif                            
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    
    <style>
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

    </style>
@endsection

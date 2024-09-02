@extends('layouts.frontend.layer')
@section('title', 'Courses Details | Drive Safe')
@section('content')
    @php $common_setting = getSettings() @endphp
    <!-- breadcrumb start -->
    <div class="breadcrumb-area" style="background-image:url('{{ asset('assets/frontend/img/other/3.png') }}')">
        <div class="container">
            <div class="breadcrumb-inner">
                <div class="section-title mb-0">
                    <h2 class="page-title">{{ $getCourse->title }}</h2>
                    <ul class="page-list">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>{{ $getCourse->title }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- course-single area start -->
    <div class="course-single-area pd-top-120 pd-bottom-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="thumb">
                        @if ($getCourse->image != '')
                            <img src="{{ asset('storage/app/public/image/course/' . $getCourse->image) }}" alt="img">
                        @endif
                    </div>
                    <div class="course-course-details-inner">
                        <div class="course-details-nav-tab text-center">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1"
                                        role="tab" aria-controls="tab1" aria-selected="true"><i class="fa fa-book"></i>
                                        Overview</a>
                                </li>
                                <!--<li class="nav-item">-->
                                <!--    <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab"-->
                                <!--        aria-controls="tab2" aria-selected="false">-->
                                <!--        <i class="fa fa-file-text-o"></i> Discussions</a>-->
                                <!--</li>-->
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                aria-labelledby="tab1-tab">
                                <div class="course-details-content">
                                    <h4 class="title">{{ $getCourse->title }}</h4>

                                    <h2 class="zoom-in-zoom-out">
                                        {{ $getCourse->price != '' ? '$' . $getCourse->price : '' }}
                                    </h2>

                                    {!! $getCourse->description !!}

                                    @if ($getPurchaseStatus > 0)
                                        <a href="{{ url('student/course-lists') }}"
                                            class="btn btn-base btn-11 btn-11 w-100">Course Open</a>
                                    @else
                                        <a href="{{ url('courses/purchase') }}" class="btn btn-base btn-11 btn-11 w-100">Get
                                            Started</a>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                <div class="course-details-content">
                                    {!! $getCourse->discussions !!}
                                    @if ($getPurchaseStatus > 0)
                                        <a href="{{ url('student/course-lists') }}" class="btn btn-base btn-11">Course Open</a>
                                    @else
                                        <a href="{{ url('courses/purchase') }}" class="btn btn-base btn-11">Get
                                            Started</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                     
                </div>
                <!--<div class="col-lg-4">-->
                <!--    <div class="td-sidebar">-->
                <!--        <div class="widget widget_catagory">-->
                <!--            <h4 class="widget-title">Catagory</h4>-->
                <!--            <ul class="catagory-items">-->
                <!--                <li><a href="{{ url('/courses') }}"><img-->
                <!--                            src="{{ asset('assets/frontend/img/icon/16.png') }}" alt="img">Automatic-->
                <!--                        Car</a></li>-->
                <!--                <li><a href="{{ url('/courses') }}"><img-->
                <!--                            src="{{ asset('assets/frontend/img/icon/16.png') }}" alt="img">Stick-->
                <!--                        Shift-->
                <!--                        Lesson</a></li>-->
                <!--                <li><a href="{{ url('/courses') }}"><img-->
                <!--                            src="{{ asset('assets/frontend/img/icon/16.png') }}" alt="img">Winter-->
                <!--                        Driving</a></li>-->
                <!--                <li><a href="{{ url('/courses') }}"><img-->
                <!--                            src="{{ asset('assets/frontend/img/icon/16.png') }}" alt="img">Adult Car-->
                <!--                        Lessons</a></li>-->
                <!--                <li><a href="{{ url('/courses') }}"><img-->
                <!--                            src="{{ asset('assets/frontend/img/icon/16.png') }}" alt="img">Driver-->
                <!--                        Education</a></li>-->
                <!--                <li><a href="{{ url('/courses') }}"><img-->
                <!--                            src="{{ asset('assets/frontend/img/icon/16.png') }}" alt="img">Program-->
                <!--                        for-->
                <!--                        Seniors</a></li>-->
                <!--            </ul>-->
                <!--        </div>-->
                <!--        <div class="widget widget_contact">-->
                <!--            <h4 class="widget-title">Contact Us</h4>-->
                <!--            <ul class="details">-->
                <!--                <li><i class="fa fa-phone"></i> {{ $common_setting->mobile_no }}</li>-->
                <!--                <li><i class="fa fa-envelope"></i> {{ $common_setting->email }}</li>-->
                <!--                <li><i class="fa fa-map-marker"></i> {{ $common_setting->address }}</li>-->
                <!--            </ul>-->
                <!--            <ul class="social-media pt-2">-->
                <!--                @if ($common_setting->facebook_link != '')-->
                <!--                    <li>-->
                <!--                        <a class="btn-base-m" href="#">-->
                <!--                            <i class="fa fa-facebook"></i>-->
                <!--                        </a>-->
                <!--                    </li>-->
                <!--                @endif-->
                <!--                @if ($common_setting->twitter_link != '')-->
                <!--                    <li>-->
                <!--                        <a class="btn-base-m" href="#">-->
                <!--                            <i class="fa fa-twitter"></i>-->
                <!--                        </a>-->
                <!--                    </li>-->
                <!--                @endif-->
                <!--                @if ($common_setting->instagram_link != '')-->
                <!--                    <li>-->
                <!--                        <a class="btn-base-m" href="#">-->
                <!--                            <i class="fa fa-instagram"></i>-->
                <!--                        </a>-->
                <!--                    </li>-->
                <!--                @endif-->
                <!--                @if ($common_setting->linkedin_link != '')-->
                <!--                    <li>-->
                <!--                        <a class="btn-base-m" href="#">-->
                <!--                            <i class="fa fa-linkedin"></i>-->
                <!--                        </a>-->
                <!--                    </li>-->
                <!--                @endif-->
                <!--                @if ($common_setting->youtube_link != '')-->
                <!--                    <li>-->
                <!--                        <a class="btn-base-m" href="#">-->
                <!--                            <i class="fa fa-youtube"></i>-->
                <!--                        </a>-->
                <!--                    </li>-->
                <!--                @endif-->
                <!--                @if ($common_setting->pinterest_link != '')-->
                <!--                    <li>-->
                <!--                        <a class="btn-base-m" href="#">-->
                <!--                            <i class="fa fa-pinterest"></i>-->
                <!--                        </a>-->
                <!--                    </li>-->
                <!--                @endif-->
                <!--            </ul>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <!-- course-single area end -->

@endsection

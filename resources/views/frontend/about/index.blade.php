@extends('layouts.frontend.layer')
@section('title', 'About Us | Drive Safe')
@section('content')
	@php $common_setting = getSettings(); @endphp
    <!-- breadcrumb start -->
    <div class="breadcrumb-area" style="background-image:url('{{ asset('assets/frontend/img/other/3.png') }}')">
        <div class="container">
            <div class="breadcrumb-inner">
                <div class="section-title mb-0">
                    <h2 class="page-title">About Us</h2>
                    <ul class="page-list">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>About Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- about area start -->
    <div class="about-area-3 bg-relative pd-top-120 pd-bottom-120">
        <div class="container">
            <div class="about-area-inner">
                <div class="row">
                    <div class="col-lg-6 offset-lg-1 col-md-11 order-lg-2 align-self-center">
                        <div class="about-thumb-wrap about-left-thumb pb-0">
                            <img src="{{ asset('assets/frontend/img/banner/03.png') }}" alt="img">
                        </div>
                    </div>
                    <div class="col-lg-5 order-lg-1 align-self-center">
                        <div class="about-inner-wrap pt-4 pt-lg-0">  
                            <div class="section-title mb-0">
                                <h6 class="sub-title left-line style-yellow">About Us</h6>
                                <h2 class="title">Best Driving Company In The World</h2>
                                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                </p>
                                <ul class="single-list-wrap">
                                    <li class="single-list-inner">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="9.5" stroke="#FDC400"></circle>
                                            <g clip-path="url(#clip0_1_2712)">
                                            <path d="M14.5633 5.65067C14.1121 5.40769 13.6087 5.87629 13.3137 6.15399C12.6368 6.81351 12.0641 7.57716 11.4219 8.27139C10.7103 9.03505 10.0508 9.7987 9.32186 10.545C8.90532 10.9616 8.45407 11.4128 8.17638 11.9335C7.55157 11.326 7.01354 10.6665 6.31931 10.1285C5.81599 9.74667 4.98291 9.46897 5.00027 10.3888C5.03498 11.5864 6.09368 12.8707 6.87469 13.6864C7.20445 14.0335 7.63835 14.398 8.14167 14.4154C8.74912 14.4501 9.37393 13.7211 9.7384 13.322C10.3806 12.6277 10.9013 11.8467 11.4913 11.1351C12.255 10.1979 13.036 9.27803 13.7823 8.32346C14.2509 7.73336 15.7261 6.27545 14.5633 5.65067ZM5.76389 10.3194C5.74654 10.3194 5.72918 10.3194 5.69447 10.3367C5.62505 10.3194 5.57298 10.302 5.50356 10.2673C5.55562 10.2326 5.6424 10.25 5.76389 10.3194Z" fill="#FDC400"></path>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1_2712">
                                            <rect width="10" height="10" fill="white" transform="translate(5 5)"></rect>
                                            </clipPath>
                                            </defs>
                                            </svg>
                                        Hands-on lane centering at higher speeds only
                                    </li>
                                    <li class="single-list-inner">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="9.5" stroke="#FDC400"></circle>
                                            <g clip-path="url(#clip0_1_2712)">
                                            <path d="M14.5633 5.65067C14.1121 5.40769 13.6087 5.87629 13.3137 6.15399C12.6368 6.81351 12.0641 7.57716 11.4219 8.27139C10.7103 9.03505 10.0508 9.7987 9.32186 10.545C8.90532 10.9616 8.45407 11.4128 8.17638 11.9335C7.55157 11.326 7.01354 10.6665 6.31931 10.1285C5.81599 9.74667 4.98291 9.46897 5.00027 10.3888C5.03498 11.5864 6.09368 12.8707 6.87469 13.6864C7.20445 14.0335 7.63835 14.398 8.14167 14.4154C8.74912 14.4501 9.37393 13.7211 9.7384 13.322C10.3806 12.6277 10.9013 11.8467 11.4913 11.1351C12.255 10.1979 13.036 9.27803 13.7823 8.32346C14.2509 7.73336 15.7261 6.27545 14.5633 5.65067ZM5.76389 10.3194C5.74654 10.3194 5.72918 10.3194 5.69447 10.3367C5.62505 10.3194 5.57298 10.302 5.50356 10.2673C5.55562 10.2326 5.6424 10.25 5.76389 10.3194Z" fill="#FDC400"></path>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1_2712">
                                            <rect width="10" height="10" fill="white" transform="translate(5 5)"></rect>
                                            </clipPath>
                                            </defs>
                                            </svg>
                                        Hands-on lane centering at higher speeds only
                                    </li>
                                    <li class="single-list-inner">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="10" cy="10" r="9.5" stroke="#FDC400"></circle>
                                            <g clip-path="url(#clip0_1_2712)">
                                            <path d="M14.5633 5.65067C14.1121 5.40769 13.6087 5.87629 13.3137 6.15399C12.6368 6.81351 12.0641 7.57716 11.4219 8.27139C10.7103 9.03505 10.0508 9.7987 9.32186 10.545C8.90532 10.9616 8.45407 11.4128 8.17638 11.9335C7.55157 11.326 7.01354 10.6665 6.31931 10.1285C5.81599 9.74667 4.98291 9.46897 5.00027 10.3888C5.03498 11.5864 6.09368 12.8707 6.87469 13.6864C7.20445 14.0335 7.63835 14.398 8.14167 14.4154C8.74912 14.4501 9.37393 13.7211 9.7384 13.322C10.3806 12.6277 10.9013 11.8467 11.4913 11.1351C12.255 10.1979 13.036 9.27803 13.7823 8.32346C14.2509 7.73336 15.7261 6.27545 14.5633 5.65067ZM5.76389 10.3194C5.74654 10.3194 5.72918 10.3194 5.69447 10.3367C5.62505 10.3194 5.57298 10.302 5.50356 10.2673C5.55562 10.2326 5.6424 10.25 5.76389 10.3194Z" fill="#FDC400"></path>
                                            </g>
                                            <defs>
                                            <clipPath id="clip0_1_2712">
                                            <rect width="10" height="10" fill="white" transform="translate(5 5)"></rect>
                                            </clipPath>
                                            </defs>
                                            </svg>
                                        Adaptive cruise control down to a stop
                                    </li>
                                </ul>
                                <a class="btn btn-base-2" href="{{ url('courses') }}">Start Course</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about area end -->

    <!-- counter area start -->
    <div class="counter-area bg-black pd-top-90 pd-bottom-90">
        <div class="counter-area-bg" style="background-image: url('{{ asset('assets/frontend/img/bg/1.png') }}')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="media counter-list-inner">
                            <div class="media-left">
                                <div class="thumb">
                                    <img src="{{ asset('assets/frontend/img/icon/2.png') }}" alt="img">
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <h2><span class="counter">5</span>K+</h2>
                                <p>Learn Driver</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="media counter-list-inner">
                            <div class="media-left">
                                <div class="thumb">
                                    <img src="{{ asset('assets/frontend/img/icon/3.png') }}" alt="img">
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <h2><span class="counter">49</span>+</h2>
                                <p>Instructore</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="media counter-list-inner">
                            <div class="media-left">
                                <div class="thumb">
                                    <img src="{{ asset('assets/frontend/img/icon/4.png') }}" alt="img">
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <h2><span class="counter">199</span>+</h2>
                                <p>Learning car</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="media counter-list-inner">
                            <div class="media-left">
                                <div class="thumb">
                                    <img src="{{ asset('assets/frontend/img/icon/5.png') }}" alt="img">
                                </div>
                            </div>
                            <div class="media-body align-self-center">
                                <h2><span class="counter">500</span>+</h2>
                                <p>heavy driving</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter area end -->

@endsection

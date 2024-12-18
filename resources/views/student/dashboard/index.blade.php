@extends('layouts.student.layer')
@section('title', 'Dashboard | Driving School')
@section('content')

    <!-- <div class="navWrap">
        <div class="container">
            <div class="row">
                <div class="col-md-2"><img src="https://aautodrivingschool.com/assets/frontend/img/logo.png" alt="img"></div>

                <div class="col-md-10">
                    <div class="studentMenu">
                        <ul class="list-unstyled">
                            <li class="active"><a href="{{ url('student/dashboard') }}"><i class="fa fa-home"
                                        aria-hidden="true"></i> Dashboard</a></li>
                            <li><a href="{{ url('student/profile') }}"><i class="fa fa-user" aria-hidden="true"></i>
                                    Profile</a></li>
                            <li><a href="{{ url('student/course-lists') }}"><i class="fa fa-history"
                                        aria-hidden="true"></i> Courses</a></li>
                            <li><a href="javascript:void(0)" onclick="logout()"><i class="fa fa-sign-out"></i> Sign
                                    Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <!-- course area start -->
    <div class="course-area pt-5">
        <div class="container">
            <div class="row">

                <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="account-cfoinfo">
                        <div class="cfoaccount">
                            <ul class="list-unstyled">
                                <li class="active"><a href="{{ url('student/dashboard') }}"><i class="fa fa-home"
                                            aria-hidden="true"></i> Dashboard</a></li>
                                <li><a href="{{ url('student/profile') }}"><i class="fa fa-user" aria-hidden="true"></i>
                                        Profile</a></li>
                                <li><a href="{{ url('student/course-lists') }}"><i class="fa fa-history"
                                            aria-hidden="true"></i> Courses</a></li>
                                <li><a href="javascript:void(0)" onclick="logout()"><i class="fa fa-sign-out"></i> Sign
                                        Out</a></li>
                            </ul>
                        </div> 
                    </div> 
                </div> -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="wrap">

                        <div class="row home-grids justify-content-center">
                            <div class="col-md-6">
                                <a href="{{ url('student/course-lists') }}">
                                    <div class="home-grid goToCourse">
                                        <i class="fa fa-leanpub"></i>
                                        <h6>Start Course</h6>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{ url('student/summary') }}">
                                    <div class="home-grid courseSummary">
                                        <i class="fa fa-bookmark-o"></i>
                                        <h6>Course Summary</h6>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{ url('student/guideline') }}">
                                    <div class="home-grid CourseGuideline">
                                        <i class="fa fa-map-signs"></i>
                                        <h6>Guideline</h6>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!--div class="contact-details myaccount-contact">
                                    <h2>Account access</h2>
                                    <div class="account-table">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <td class="commonname-td">
                                                        Sign in Info
                                                    </td>
                                                    <td class="nameshow-td">
                                                        {{ Auth::user()->email }} </td>
                                                </tr>


                                                <tr>
                                                    <td class="commonname-td">

                                                    </td>
                                                    <td class="nameshow-td">
                                                        <a href="{{ url('student/change-password') }}">Modify my password</a>
                                                    </td>

                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div-->

                    <div class="clearfix"></div>
                    <!-- END OF CONTACT DETAILS SECTION -->

                    {{-- <div class="contact-details myaccount-contact">
                        <h2>My Details <a href="{{ url('student/modify-address') }}">Modify address</a></h2>

                        <div class="row">
                            <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <div class="address-form">

                                    <div class="form-group">
                                        <p>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                        <p>{{ Auth::user()->mobile_no }}</p>
                                        <p>{{ Auth::user()->email }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div> --}}

                    <!-- END OF DELIVERY ADDRESS SECTION -->


                </div>

            </div>
        </div>
    </div>
    
@endsection

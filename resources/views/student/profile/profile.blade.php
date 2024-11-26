@extends('layouts.student.layer')
@section('title', 'Dashboard | Driving School')
@section('content')
    <!-- course area start -->
    <div class="course-area pb-5">
        <div class="container">
            <div class="row">


                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="contact-details myaccount-contact">
                        <h2><i class="fa fa-lock"></i> Account access</h2>
                        <div class="account-table">
                            <p> Login Info</p>

                            <hr>

                            <p> {{ Auth::user()->email }} </p>
                            <p><a href="{{ url('student/change-password') }}">Modify my password</a></p>

                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <!-- END OF CONTACT DETAILS SECTION -->


                    <div class="contact-details myaccount-contact">
                        <h2><i class="fa fa-user"></i> Profile</h2>

                        {{-- <a href="{{ url('student/modify-address')}}" class="btn btn-primary pull-right"><i class="fa fa-edit"></i>&nbsp;Edit</a> --}}
                        <p><strong>Name</strong>: {{ Auth::user()->first_name }} {{ Auth::user()->middle_name }} {{ Auth::user()->last_name }}</p>
                        <p><strong>Email</strong>: {{ Auth::user()->email }}</p>
                        <p><strong>Gender</strong>: {{ Auth::user()->gender }}</p>
                        <p><strong>Date of Birth</strong>: {{ Auth::user()->dob ? \Carbon\Carbon::parse(Auth::user()->dob)->format('M-d-Y') : 'N/A' }}</p>
                        <p><strong>Address</strong>: {{ Auth::user()->address1 }} </p>
                        <p><strong>City</strong>: {{ Auth::user()->city_town }}</p>
                        <p><strong>State</strong>: {{ Auth::user()->country }}</p>
                        <p><strong>Zip Code</strong>: {{ Auth::user()->postcode }}</p>
                        <p><strong>Mobile No</strong>: {{ Auth::user()->mobile_no}}</p>
                        <p><strong>Joined At</strong>: {{ date("d-M-Y h:i:s", strtotime(Auth::user()->created_at)) }}</p>

                    </div>


                    <!-- END OF DELIVERY ADDRESS SECTION -->


                </div>

            </div>
        </div>
    </div>

    <style>
        .contact-details p{

        }
        .contact-details p strong{
            min-width: 120px;
            display: inline-block;
        }
        @media (min-width: 1200px) .col-lg-3 {
            width: 25%;
        }

        @media (min-width: 1200px) .col-lg-6 {
            width: 50%;
        }
    </style>
@endsection

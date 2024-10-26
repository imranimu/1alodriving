@extends('layouts.student.layer')
@section('title', 'Dashboard | Driving School')
@section('content')
	<link href="{{ asset('assets/student/css/datepicker.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/student/js/bootstrap-datepicker.js') }}"></script>
    <!-- course area start -->
    <div class="course-area pb-5">
        <div class="container">
            <div class="row">

                <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="account-cfoinfo">
                        <div class="cfoaccount">
                            <ul class="list-unstyled">
                                <li><a href="{{ url('student/dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
                                <li class="active"><a href="{{ url('student/profile') }}"><i class="fa fa-user" aria-hidden="true"></i> Profile</a></li>
                                <li><a href="{{ url('/student/course-lists') }}"><i class="fa fa-history" aria-hidden="true"></i> Courses</a></li>
                                <li><a href="javascript:void(0)" onclick="logout()"><i class="fa fa-sign-out"></i> Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div> -->

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="contact-details myaccount-contact">
                        <h2>Account access</h2>
                        <div class="account-table">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="commonname-td">
                                            Login Info
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
                    </div>

                    <div class="clearfix"></div>

                    <!-- END OF CONTACT DETAILS SECTION -->


                    <div class="contact-details myaccount-contact">
                        <h2>My Details</h2>
                        <div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <!-- Message here -->
                                @if (Session::has('message'))
                                    @php
                                        $message = Session::get('message');
                                    @endphp

                                    @if (isset($message['status']) && $message['status'] == '1')
                                        <div class="form-group row">
                                            <div class="alert alert-success inline">
                                                {{ $message['text'] }}
                                            </div>
                                        </div>
                                    @elseif (isset($message['status']) && $message['status'] == '0')
                                        <div class="form-group row">
                                            <div class="alert alert-danger inline">
                                                {{ $message['text'] }}
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <form action="{{ url($action_url) }}" id="modifyAddress" name="modifyAddress"
                                    method="post">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>First Name *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="first_name" class="form-control" id="first_name"
                                                placeholder="First Name"
                                                value="{{ old('first_name') != '' ? old('first_name') : Auth::user()->first_name }}">
                                            @if ($errors->has('first_name'))
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Middle Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="middle_name" class="form-control" id="middle_name"
                                                placeholder="Middle Name"
                                                value="{{ old('middle_name') != '' ? old('middle_name') : Auth::user()->middle_name }}">
                                            @if ($errors->has('middle_name'))
                                                <strong>{{ $errors->first('middle_name') }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Last Name </label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="last_name" class="form-control" id="last_name"
                                                placeholder="Last Name"
                                                value="{{ old('last_name') != '' ? old('last_name') : Auth::user()->last_name }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Email Address *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <label class="form-control">{{ Auth::user()->email }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Telephone *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input class="form-control"
                                                value="{{ old('mobile_no') != '' ? old('mobile_no') : Auth::user()->mobile_no }}"
                                                name="mobile_no" placeholder="Telephone">
                                            @if ($errors->has('mobile_no'))
                                                <strong>{{ $errors->first('mobile_no') }}</strong>
                                            @endif
                                        </div>
                                    </div>

									<div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>DOB *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="dob" class="form-control postcode-separate"
                                                placeholder="mm-day-YYYY" value="{{ old('dob') != '' ? old('dob') : Auth::user()->dob }}"
                                                id="datepicker">
                                            @if ($errors->has('dob'))
                                                <strong>{{ $errors->first('dob') }}</strong>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Gender *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="">Select</option>
                                                <option value="male"
                                                    {{ (old('gender') == 'male' ? 'selected' : Auth::user()->gender == 'male') ? 'selected' : '' }}>
                                                    Male
                                                </option>
                                                <option value="female"
                                                    {{ (old('gender') == 'female' ? 'selected' : Auth::user()->gender == 'female') ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                            @if ($errors->has('female'))
                                                <strong>{{ $errors->first('female') }}</strong>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Address *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <textarea name="address1" id="address1" cols="30" class="form-control" rows="2" placeholder="Street Name & Number">{{ old('address1') != '' ? old('address1') : Auth::user()->address1 }}</textarea>
                                            @if ($errors->has('address1'))
                                                <strong>{{ $errors->first('address1') }}</strong>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>City *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="city_town" class="form-control" id="city_town"
                                                placeholder="City"
                                                value="{{ old('city_town') != '' ? old('city_town') : Auth::user()->city_town }}">
                                            @if ($errors->has('city_town'))
                                                <strong>{{ $errors->first('city_town') }}</strong>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>State *</label>
                                        </div>
                                        <div class="col-sm-9">

                                            <input type="text" name="country" class="form-control" id="country"
                                                placeholder="State"
                                                value="{{ old('country') != '' ? old('country') : Auth::user()->country }}">

                                            <!--<select class="form-control" name="country" id="country">-->
                                            <!--    <option value="">---select country---</option>-->
                                            <!--    @if (!blank(getCountry()))-->
                                            <!--        @foreach (getCountry() as $key => $val)-->
                                            <!--            <option value="{{ $key }}"-->
                                            <!--                {{ ((old('country') == $key ? 'selected' : $key == Auth::user()->country) ? 'selected' : $key == 'US') ? 'selected' : '' }}>-->
                                            <!--                {{ $val }}</option>-->
                                            <!--        @endforeach-->
                                            <!--    @endif-->
                                            <!--</select>-->
                                            @if ($errors->has('country'))
                                                <strong>{{ $errors->first('country') }}</strong>
                                            @endif
                                        </div>
                                    </div>

                                    <!--<div class="form-group row">-->
                                    <!--    <div class="col-sm-3 col-form-label">-->
                                    <!--        <label>Address 2 </label>-->
                                    <!--    </div>-->
                                    <!--    <div class="col-sm-9">-->
                                    <!--        <textarea name="address2" id="address2" class="form-control" cols="30" rows="4" placeholder="Address 2">{{ old('address2') != '' ? old('address2') : Auth::user()->address2 }}</textarea>-->
                                    <!--    </div>-->

                                    <!--</div>-->

                                    <div class="form-group row">
                                        <div class="col-sm-3 col-form-label">
                                            <label>Zip Code *</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" name="postcode" class="form-control postcode-separate"
                                                id="postcode" placeholder="Zipcode"
                                                value="{{ old('postcode') != '' ? old('postcode') : Auth::user()->postcode }}"
                                                maxlength="8">
                                            @if ($errors->has('postcode'))
                                                <strong>{{ $errors->first('postcode') }}</strong>
                                            @endif
                                        </div>

                                    </div>





                                    @if (!blank($filteredSecurityQuestions))
                                    <h4 class="mb-3">Security Question</h4>


                                    <div class="form-group row">

                                    <div class="col-md-6">
                                        @if (!blank($filteredSecurityQuestions))
                                            @php
                                                $questionsToShow = $filteredSecurityQuestions->where('is_type', 1)->take(5);
                                            @endphp
                                            @foreach ($questionsToShow as $index => $question)
                                                <div class="mb-3">

                                                    <select name="question[q{{ $index + 1 }}]" id="question[q{{ $index + 1 }}]" class="form-control">
                                                        <!--<option value="">Select Question-{{ $index + 1 }}</option>-->
                                                        <option value="{{ $question->id }}" {{ $question->id == old("question.q" . ($index + 1)) ? 'selected' : '' }}>
                                                            {{ $question->question }}
                                                        </option>
                                                    </select>
                                                    @if ($errors->has("question.q" . ($index + 1)))
                                                        <strong>The question-{{ $index + 1 }} field is required.</strong>
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <input type="text" id="question[a{{ $index + 1 }}]" name="question[a{{ $index + 1 }}]" class="form-control" placeholder="Answer" value="{{ old('question.a' . ($index + 1)) }}">
                                                    <span id="error"></span>
                                                    @if ($errors->has("question.a" . ($index + 1)))
                                                        <strong>The Answer-{{ $index + 1 }} field is required.</strong>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        @if (!blank($filteredSecurityQuestions))
                                            @php
                                                $questionsToShow = $filteredSecurityQuestions->where('is_type', 2)->take(5);
                                            @endphp
                                            @foreach ($questionsToShow as $index => $question)

                                                <div class="mb-3">
                                                    <select name="question[q{{ $index + 1 }}]" id="question[q{{ $index + 1 }}]" class="form-control">
                                                        <!--<option value="">Select Question-{{ $index + 1 }}</option>-->
                                                        <option value="{{ $question->id }}" {{ $question->id == old("question.q" . ($index + 1)) ? 'selected' : '' }}>
                                                            {{ $question->question }}
                                                        </option>
                                                    </select>
                                                    @if ($errors->has("question.q" . ($index + 1)))
                                                        <strong>The question-{{ $index + 1 }} field is required.</strong>
                                                    @endif
                                                </div>

                                                <div class="mb-3">
                                                    <input type="text" id="question[a{{ $index + 1 }}]" name="question[a{{ $index + 1 }}]" class="form-control" placeholder="Answer" value="{{ old('question.a' . ($index + 1)) }}">
                                                    <span id="error"></span>
                                                    @if ($errors->has("question.a" . ($index + 1)))
                                                        <strong>The Answer-{{ $index + 1 }} field is required.</strong>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    </div>

                                    @else



                                    @endif




                                    <div class="form-group row">
                                        {{-- <div class="col-sm-3 col-form-label">
                                            <label></label>
                                        </div> --}}

                                        <div class="col-sm-3">
                                            <input type="submit" id="submit" name="submit"
                                                class="btn btn-primary btn-block custom-checkout"
                                                value="Save Information">
                                        </div>

                                        @if (blank($filteredSecurityQuestions))
                                        <div class="col-sm-3">
                                            <a class="btn btn-success btn-block custom-checkout" href="{{ url('student/course-lists') }}">Go To Course</a>
                                        </div>
                                        @endif

                                    </div>

                                </form>

                            </div>




                        </div>

                    </div>


                    <!-- END OF DELIVERY ADDRESS SECTION -->


                </div>

            </div>
        </div>
    </div>

    <style>
        @media (min-width: 1200px) .col-lg-3 {
            width: 25%;
        }

        @media (min-width: 1200px) .col-lg-6 {
            width: 50%;
        }
    </style>
	<script>
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });
    </script>
@endsection

@extends('layouts.frontend.layer')
@section('title', 'Courses Purchase | Drive Safe')
@section('content')
    <link href="{{ asset('assets/student/css/datepicker.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/student/js/bootstrap-datepicker.js') }}"></script>
    <!-- breadcrumb start -->
    <div class="breadcrumb-area" style="background-image:url('{{ asset('assets/frontend/img/other/3.png') }}')">
        <div class="container">
            <div class="breadcrumb-inner">
                <div class="section-title mb-0">
                    <h2 class="page-title">Purchasing Summary</h2>
                    <ul class="page-list">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Purchasing Summary</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- course area start -->
    <div class="course-area pd-top-60 pd-bottom-120">
        <div class="container">
            <div class="row">
                <div class="col-md-8 order-2 order-md-1">
                    <div class="control-group">
                        @if (!empty(Session::get('message')) && Session::get('message')['status'] == '0')
                            <div class="control-group">
                                <div class="alert alert-danger inline">
                                    {{ Session::get('message')['text'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-3">Let's get started! Please tell us your name and email: </h4>
                    <hr class="separator-aqua">

                    <form class="needs-validation" action="{{ route('student.course-payment-validation') }}"
                        id="checkoutForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label mb-2">First Name</label>
                                <input type="text" id="first_name" class="form-control mb-3"
                                    placeholder="Enter First Name" value="{{ old('first_name') }}" name="first_name"
                                    required="">
                                @if ($errors->has('first_name'))
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label mb-2">Last Name</label>
                                <input type="text" id="last_name" class="form-control mb-3"
                                    placeholder="Enter Last Name" value="{{ old('last_name') }}" name="last_name"
                                    required="">
                                @if ($errors->has('last_name'))
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="student_email" class="form-label mb-2">Student Email</label>
                                <input type="text" id="student_email" class="form-control mb-3"
                                    placeholder="Enter Student Email" name="student_email" value="{{ old('student_email') }}"
                                    onchange="studentEmailCheck(this.value)" required="">
                                <span id="error"></span>
                                @if ($errors->has('student_email'))
                                    <strong>{{ $errors->first('student_email') }}</strong>
                                @endif
                            </div> 
                            <div class="col-md-6">
                                <label for="mobile_no" class="form-label mb-2">Mobile Number</label>
                                <input type="text" id="mobile_no" class="form-control mb-3"
                                    placeholder="Enter Mobile Number" name="mobile_no" value="{{ old('mobile_no') }}"
                                    required="">
                                <span id="error"></span>
                                @if ($errors->has('mobile_no'))
                                    <strong>{{ $errors->first('mobile_no') }}</strong>
                                @endif
                            </div> 

                            {{-- radio button for Yes/No --}}

                            @php
                                $yes = old('is_differently_abled') == 'yes' ? 'checked' : '';
                                $no = old('is_differently_abled') == 'no' ? 'checked' : '';
                            @endphp

                            <div class="col-md-12">
                                <label for="is_differently_abled" class="form-label mb-2 mr-3">Are you 18 year old?</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_differently_abled"
                                        id="inlineRadio1" value="yes" {{ $yes }}>
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_differently_abled"
                                        id="inlineRadio2" value="no" {{ $no }}>
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                                @if ($errors->has('is_differently_abled'))
                                    <strong>{{ $errors->first('is_differently_abled') }}</strong>
                                @endif
                            </div> 

                            <div class="col-md-6">
                                <label for="dob" class="form-label mb-2">Date of Birth</label>
                                <input type="text" name="dob" class="form-control postcode-separate mb-3"
                                    placeholder="YYYY-mm-day" value="{{ old('dob') }}"
                                    id="datepicker">
                                @if ($errors->has('dob'))
                                    <strong>{{ $errors->first('dob') }}</strong>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="gender" class="form-label mb-2">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="male"{{ old('gender') == 'male' ? 'selected' : '' }}>
                                        Male
                                    </option>
                                    <option value="female"
                                        {{ old('gender') == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                </select>
                                @if ($errors->has('female'))
                                    <strong>{{ $errors->first('female') }}</strong>
                                @endif
                            </div>

                            

                            <div class="col-md-12">
                                <label for="address1" class="form-label mb-2">Address</label>
                                <textarea name="address1" id="address1" cols="30" class="form-control mb-3" rows="4" placeholder="Address">{{ old('address1') }}</textarea>
                                @if ($errors->has('address1'))
                                    <strong>{{ $errors->first('address1') }}</strong>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="city_town" class="form-label mb-2">City</label>
                                <input type="text" name="city_town" class="form-control" id="city_town"
                                    placeholder="City"
                                    value="{{ old('city_town') }}">
                                @if ($errors->has('city_town'))
                                    <strong>{{ $errors->first('city_town') }}</strong>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="postcode" class="form-label mb-2">Postcode</label>
                                <input type="text" name="postcode" class="form-control postcode-separate mb-3"
                                    id="postcode" placeholder="Zipcode"
                                    value="{{ old('postcode') }}"
                                    maxlength="8">
                                @if ($errors->has('postcode'))
                                    <strong>{{ $errors->first('postcode') }}</strong>
                                @endif
                            </div>

                            <div class="col-md-6">

                                <label for="country" class="form-label mb-2">State</label> 

                                <input type="text" name="country" class="form-control mb-3" id="country"
                                    placeholder="State"
                                    value="{{ old('country') }}">  
                                @if ($errors->has('country'))
                                    <strong>{{ $errors->first('country') }}</strong>
                                @endif
                            </div> 
                            

							<div class="col-md-6">
                                <label for="student_password" class="form-label mb-2">Student Password</label>
                                <input type="password" id="student_password" class="form-control mb-3"
                                    placeholder="STUDENT PASSWORD" name="student_password"
                                    value="{{ old('student_password') }}" minlength="6" maxlength="10" autocomplete="off" required="">
                                <span id="error"></span>
                                @if ($errors->has('student_password'))
                                    <strong>{{ $errors->first('student_password') }}</strong>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <h4 class="mb-3">Security Question</h4>
                            </div>
							
							<div class="col-md-12">
                                
                                <select name="question[q1]" id="question[q1]" class="form-control mb-3">
                                    <option value="">Select Question-1</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 1)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q1') ? 'selected' : '' }}>
                                                    {{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q1'))
                                    <strong>The question-1 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <input type="text" id="question[a1]" name="question[a1]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a1') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a1'))
                                    <strong>The Answer-1 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <select name="question[q2]" id="question[q2]" class="form-control mb-3">
                                    <option value="">Select Question-2</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 2)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q2') ? 'selected' : '' }}>
													{{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q2'))
                                    <strong>The question-2 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="text" id="question[a2]" name="question[a2]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a2') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a2'))
                                    <strong>The Answer-2 field is required.</strong>
                                @endif
                            </div>
                            
                            <div class="col-md-12"> 
                                <select name="question[q3]" id="question[q3]" class="form-control mb-3">
                                    <option value="">Select Question-3</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 1)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q3') ? 'selected' : '' }}>
                                                    {{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q3'))
                                    <strong>The question-3 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <input type="text" id="question[a3]" name="question[a3]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a3') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a3'))
                                    <strong>The Answer-3 field is required.</strong>
                                @endif
                            </div>
                            
                            <div class="col-md-12"> 
                                <select name="question[q4]" id="question[q4]" class="form-control mb-3">
                                    <option value="">Select Question-4</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 2)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q4') ? 'selected' : '' }}>
													{{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q4'))
                                    <strong>The question-4 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="text" id="question[a4]" name="question[a4]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a4') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a4'))
                                    <strong>The Answer-4 field is required.</strong>
                                @endif
                            </div>
                            
                                                        <div class="col-md-12"> 
                                <select name="question[q5]" id="question[q5]" class="form-control mb-3">
                                    <option value="">Select Question-5</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 1)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q5') ? 'selected' : '' }}>
                                                    {{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q5'))
                                    <strong>The question-5 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <input type="text" id="question[a5]" name="question[a5]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a5') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a5'))
                                    <strong>The Answer-5 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <select name="question[q6]" id="question[q6]" class="form-control mb-3">
                                    <option value="">Select Question-6</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 2)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q6') ? 'selected' : '' }}>
													{{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q6'))
                                    <strong>The question-6 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="text" id="question[a6]" name="question[a6]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a6') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a6'))
                                    <strong>The Answer-6 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <select name="question[q7]" id="question[q7]" class="form-control mb-3">
                                    <option value="">Select Question-7</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 1)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q7') ? 'selected' : '' }}>
                                                    {{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q7'))
                                    <strong>The question-7 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <input type="text" id="question[a7]" name="question[a7]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a7') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a7'))
                                    <strong>The Answer-7 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <select name="question[q8]" id="question[q8]" class="form-control mb-3">
                                    <option value="">Select Question-8</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 2)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q8') ? 'selected' : '' }}>
													{{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q8'))
                                    <strong>The question-8 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="text" id="question[a8]" name="question[a8]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a8') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a8'))
                                    <strong>The Answer-8 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <select name="question[q9]" id="question[q9]" class="form-control mb-3">
                                    <option value="">Select Question-9</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 1)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q9') ? 'selected' : '' }}>
                                                    {{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q9'))
                                    <strong>The question-9 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <input type="text" id="question[a9]" name="question[a9]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a9') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a9'))
                                    <strong>The Answer-9 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12"> 
                                <select name="question[q10]" id="question[q10]" class="form-control mb-3">
                                    <option value="">Select Question-10</option>
                                    @if (!blank($getSecurityQuestion))
                                        @foreach ($getSecurityQuestion as $val)
                                            @if ($val->is_type == 2)
                                                <option value="{{ $val->id }}"
                                                    {{ $val->id == old('question.q10') ? 'selected' : '' }}>
                                                    {{ $val->question }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('question.q10'))
                                    <strong>The question-10 field is required.</strong>
                                @endif
                            </div>

                            <div class="col-md-12">
                                <input type="text" id="question[a10]" name="question[a10]" class="form-control mb-3"
                                    placeholder="Answer" value="{{ old('question.a10') }}">
                                <span id="error"></span>
                                @if ($errors->has('question.a10'))
                                    <strong>The Answer-10 field is required.</strong>
                                @endif
                            </div>
							
							
                            @php
                                $parent_onclick = old('parent_email') != '' ? 'parentEmail(0)' : 'parentEmail(1)';
                                $parent_text = old('parent_email') != '' ? 'Nevermind, I\'m a Student' : 'Wait, I\'m a parent!';
                                $parent_display = old('parent_email') != '' ? 'block' : 'none';
                            @endphp

                            {{-- <div class="col-md-12 text-right mb-3">
                                <a href="javascript:void(0)" id="im_parent"
                                    onclick="{{ $parent_onclick }}">{{ $parent_text }}</a>
                            </div> --}}
                            <div class="col-md-12" id="parent_box" style="display: {{ $parent_display }}">
                                <p>As a parent, you are purchasing a course for your student, whose email you provided in
                                    the field above. If you'd like, you may enter your own email below to track your
                                    student's progress.</p>
                                    
                                <div class="form-group">
                                    <input type="text" id="parent_name" class="form-control mb-3"
                                    placeholder="PARENT (YOUR) NAME" name="parent_name"
                                    value="{{ old('parent_phone') }}">
                                    
                                    @if ($errors->has('parent_name'))
                                        <strong>{{ $errors->first('parent_name') }}</strong>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <input type="text" id="parent_relation" class="form-control mb-3"
                                    placeholder="PARENT (YOUR) RELATION WITH STUDENT" name="parent_relation"
                                    value="{{ old('parent_relation') }}">
                                    
                                    @if ($errors->has('parent_relation'))
                                        <strong>{{ $errors->first('parent_relation') }}</strong>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <input type="email" id="parent_email" class="form-control mb-3"
                                        placeholder="PARENT (YOUR) EMAIL" name="parent_email"
                                        value="{{ old('parent_email') }}">
                                    @if ($errors->has('parent_email'))
                                        <strong>{{ $errors->first('parent_email') }}</strong>
                                    @endif
                                </div>
                            
                                <div class="form-group">
                                    <input type="text" id="parent_phone" class="form-control mb-3"
                                    placeholder="PARENT (YOUR) PHONE" name="parent_phone"
                                    value="{{ old('parent_phone') }}">
                                    
                                    @if ($errors->has('parent_phone'))
                                        <strong>{{ $errors->first('parent_phone') }}</strong>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <input type="text" id="parent_address" class="form-control mb-3"
                                    placeholder="PARENT (YOUR) FULL ADDRESS" name="parent_address"
                                    value="{{ old('parent_address') }}">
                                    
                                    @if ($errors->has('parent_address'))
                                        <strong>{{ $errors->first('parent_address') }}</strong>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn btn-primary w-100">
                                    STEP 1 <i class='fa fa-arrow-right' aria-hidden='true'></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 mb-4 order-1 order-md-2 ">
                    <h4 style="margin-bottom: 16px;">Summary</h4>
                    <hr class="separator-aqua">

                    <span style="color: #404652; font-size:30px; position: relative;">
                        Shopping Cart <span class="badge badge-secondary badge-pill"
                            style="font-size: 12px; position: absolute; top: 12px; margin-left: 8px; padding-right: 8px;">1</span>
                    </span>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed mt-3">

                            <h5>1. {{ $getCourse['title'] }}</h5>

                            <h5 class="text-muted" style="display:inline-block; text-align:right;">
                                ${{ $getCourse['price'] }} </h5>

                        </li>
                        <li class="list-group-item d-flex justify-content-between" id="cartItem">
                            <div style="font-size:24px;">Total</div>
                            <div id="totalPrice" style="font-size:24px;">
                                <strong>${{ $getCourse['price'] }} </strong>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- course area end -->
    <script>
        function parentEmail(value) {
            if (value == 1) {
                $('#im_parent').html('Nevermind, I\'m a Student');
                $('#im_parent').attr('onclick', 'parentEmail(0)');
                $('#parent_box').show();
            } else {
                $('#parent_email').val('');
                $('#im_parent').html('Wait, I\'m a parent!');
                $('#im_parent').attr('onclick', 'parentEmail(1)');
                $('#parent_box').hide();
            }
        }

        function studentEmailCheck(email) {
            if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
                $('#error').html("You have entered an invalid email address!");
                return false;
            } else {
                $('#error').html("");
                $.ajax({
                    type: "POST",
                    url: '{{ url('student/email-check') }}',
                    data: {
                        "email": email,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response == true) {
                            $('#error').html("<b>The student email has already been taken.</b>");
                        } else {
                            $('#error').html("");
                        }
                    }
                });
            }
        }
    </script>

    <script>
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });
    </script>
@endsection

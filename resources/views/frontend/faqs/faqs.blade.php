
@extends('layouts.frontend.layer')
@section('title', 'Faqs | Drive Safe')
@section('content')
    <!-- breadcrumb start -->
    <div class="breadcrumb-area" style="background-image:url('{{ asset('assets/frontend/img/other/3.png') }}')">
        <div class="container">
            <div class="breadcrumb-inner">
                <div class="section-title mb-0">
                    <h2 class="page-title">Faqs</h2>
                    <ul class="page-list">
                        <li><a href="index.html">Home</a></li>
                        <li>Faqs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb end -->

    <!-- about area start -->
    <div class="about-area bg-relative pd-top-90 pd-bottom-120">
        <div class="container">
            <div class="about-area-inner">
                <div class="row">
                    <div class="col-lg-12 col-md-11"> 
                        <div id="accordion">
                            <div class="card">
                                <h5 class="card-header" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Are you interested in obtaining your learner's permit in Texas?</h5>
                                
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>Well, it's a simple process that you can complete from the comfort of your own home with the help of Https://aautodrivingschool.com/.</p>

										<p>To get started, you must be at least 15 years old and have completed an online driver education course. You can choose between the Texas Online Drivers Education course or the Parent-Taught Drivers Education course. Both courses are designed to equip you with the knowledge and skills you need to become a responsible driver.</p>

										<p>Once you've completed the first module of the course, you can take the permit exam online. If you pass the exam, you'll receive a certificate that you can bring with you to your local Texas Department of Public Safety (DPS) office to apply for your learner's permit. You won't need to take an in-person test as the certificate shows you've fulfilled that requirement.</p>

										<p>Before going to your DPS appointment, ensure that you have all the necessary documentation, Here is a list of necessary document</p>
										<p>including proof of identification,</p>
										<p>social security number,</p>
										<p>US citizenship, </p>
										<p>Texas residency, </p>

										<p>and a Verification of Enrollment and Attendance form to verify school enrollment (or a high school diploma or GED). PTDE students should also bring any forms included in the PTDE Program Guide.</p>

										<p>During your DPS appointment, a parent or legal guardian must be present regardless of which driver ED course you are using. If you do not pass the online permit exam, you will need to retake it at the DPS.</p>

										<p>Once you've obtained your learner's permit, you can start your behind-the-wheel (BTW) training. You'll need to meet both the course and BTW training requirements before you can apply for a Texas provisional driver's license.</p>

										<p>If you have any questions or concerns during the course, don't hesitate to reach out to our 24/7 customer support. We're here to help you every step of the way on your journey to becoming a licensed driver in Texas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card">

                                <h5 class="card-header collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">What can I do If I lost My E-certificate?</h5>
                               
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>Follow these steps to download again yourbb	<b>E-certificate:</b></p>

										<p>When You are using a computer,Justclick here and sign in to A Auto Driving School.Now select "RECORDS" from the menu. You will have access to download and print your certificate from here . You need wait at least 8 hours after finishing the course before downloading the certificate, so keep that in mind. If you selected the Instant Certificate Delivery upgrade, the certificate should be in your account in less than 10 minutes.</p>

										<p>You may see your certificate by selecting the "Records" icon at the bottom of your screen if you're accessing the course using the A Auto Driving School mobile app for Android or iOS.</p>

										<p>If your certificate needs to be updated, get in touch with A Auto Driving School and provide the following details:</p>

										<p>The registered email address</p>

										<p>Student's full name</p>

										<p>student's birthdate</p>

										<p>The certificate's information that has to be updated</p>

										<p>Following that, A Auto Driving School will send you an updated certificate. as soon as is practical.</p>
                                    </div>
                                </div>
                            </div> 
                             
                        </div>
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
    <!-- about area end -->  

    <style> 

        #accordion .card{
            border: none;
        }

        #accordion .card h5.card-header {
            background: #fff;
            border-radius: 15px 15px 0 0;
            border: 1px solid #1d84b8;
            color: #1d84b8;
            border-bottom: none;
            padding-bottom: 15px;
        }

        #accordion .card h5.card-header.collapsed {
            margin-bottom: 15px;
            background: #fff;
            color: #000;
            border-radius: 9px;
            border: 1px solid #000;
        }

        #accordion .card-body {
            margin-bottom: 15px;
            box-shadow: 0 .3rem 1rem rgba(0,0,0,.10)!important;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #1d84b8;
            margin-top: -5px;
        }

    </style>
@endsection

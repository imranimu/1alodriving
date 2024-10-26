@extends('layouts.admin.layer')

@section('title', 'Show Referral | .: A Auto Driving School :.')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Referral Details</h4>

                    <span><a href="{{ url('admin/referrals') }}" class="btn btn-primary">Back</a></span>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <p>{{ $referral->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <p>{{ $referral->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <p>{{ $referral->phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="referral_code">Referral Code</label>
                                <p>{{ $referral->referral_code }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="created_at">Created At</label>
                                <p>{{ $referral->created_at->format('d-m-Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Users</h4> <span>{{$totalUserCount}}</span>
                </div><!-- end card header -->

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->first_name }} {{$user->middle_name}} {{ $user->last_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile_no }}</td>
                                    <td>{{ $user->created_at->format('d-m-Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

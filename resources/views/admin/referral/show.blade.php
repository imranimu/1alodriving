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

                        <div class="col-md-6">
                            <form action="{{ url('admin/referral', $referral->id) }}" method="GET" class="form-inline">
                                <div class="row">

                                    <div class="form-group mr-3 col-md-4">
                                        <label for="month" class="mr-2">Month</label>
                                        <select name="month" id="month" class="form-control form-control-sm">
                                            <option value="">All Months</option>
                                            @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ (int) request('month', date('n')) === $m ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mr-3 col-md-4">
                                        <label for="year" class="mr-2">Year</label>
                                        <select name="year" id="year" class="form-control form-control-sm">
                                            <option value="">All Years</option>
                                            @foreach(range(date('Y') - 5, date('Y')) as $y)
                                                <option value="{{ $y }}" {{ (int) request('year', date('Y')) === $y ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="mr-2 d-block"> &nbsp; </label>
                                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                    </div>
                                </div>
                            </form>
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

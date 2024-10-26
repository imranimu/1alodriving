@extends('layouts.admin.layer')

@section('title', '.: Referral List | A Auto Driving School :.');

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">

                <h2>Referral List</h2>

                @if ($referrals->isEmpty())
                    <p>No referrals found.</p>
                @else
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Referral Code</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $referral)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $referral->name }}</td>
                                    <td>{{ $referral->email }}</td>
                                    <td>{{ $referral->phone }}</td>
                                    @php
                                        $refLink = 'https://1aalodrivingschool.com/courses/texas-six-hours-adult-course?ref='. $referral->referral_code;
                                    @endphp
                                    <td>{{ $referral->referral_code }} <a href="javascript:void(0)" onclick="copyToClipboard('{{ $refLink }}')"><i class="ri-file-copy-2-line"></a></td>
                                    <td>{{ $referral->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td><a href="{{ route('admin.referral.show', $referral->id) }}">View</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection

<script>
    function copyToClipboard(text) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(text).select();

        if (document.execCommand("copy")) {
            alert("Copied: " + text);
        }

        document.execCommand("copy");

        $temp.remove();
    }

    function tooltip() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }
</script>

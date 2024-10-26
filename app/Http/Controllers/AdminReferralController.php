<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Referral;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;


class AdminReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::all();

        return view('admin.referral.index', compact('referrals'));

    }

    public function show($id)
    {
        $referral = Referral::findOrFail($id);

        $refCode = $referral->referral_code;

        // get current month
        $currentMonth = Carbon::now()->month;
        // get year
        $year = Carbon::now()->year;

        $month = '2024-07'; // Example month filter

        $users = User::where('ref_id', $refCode)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->get();

        $totalUserCount = $users->count();

        return view('admin.referral.show', compact('referral', 'totalUserCount', 'users'));

    }

     // Show the form to create a referral
    public function create()
    {
    //return view('admin.referral.create');

    return view('admin.referral.create');
    }

     // Store the referral entry
    public function store(Request $request)
        {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:referrals,email',
            'phone' => 'required|string|max:15',
        ]);

        $referralCode = $this->generateUniqueReferralCode();

        Referral::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'referral_code' => $referralCode,
        ]);

        return redirect('/admin/referral/create')->with('success', 'Referral created successfully with code: ' . $referralCode);
    }

     // Function to generate a unique referral code
    private function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8)); // Generate a random 8-character string
        } while (Referral::where('referral_code', $code)->exists());

        return $code;
    }
}

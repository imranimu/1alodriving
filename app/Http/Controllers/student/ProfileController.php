<?php

namespace App\Http\Controllers\student;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\admin\UserQuestion;
use App\Models\admin\SecurityQuestion;
use Image;

class ProfileController extends Controller
{
	public function profile()
    {
        return view('student.profile.profile');
    }

    /*public function modify_address()
    {
        $action_url = 'student/address/' . Auth::user()->id . '/update';

        $getSecurityQuestion = SecurityQuestion::where('status', '1')->get();

        $getAnswer = UserQuestion::where('student_id', Auth::user()->id)->get();

        return view('student.profile.index', compact('action_url', 'getSecurityQuestion', 'getAnswer'));
    }*/
    public function modify_address()
    {
        $action_url = 'student/address/' . Auth::user()->id . '/update';

        // Fetch all active security questions
        $getSecurityQuestion = SecurityQuestion::where('status', '1')->get();

        // Fetch all answers provided by the student
        $getAnswer = UserQuestion::where('student_id', Auth::user()->id)->get();

        // Extract the question_ids that have answers
        $answeredQuestionIds = $getAnswer->pluck('question_id')->toArray();

        // Filter out questions that have already been answered
        $filteredSecurityQuestions = $getSecurityQuestion->reject(function ($question) use ($answeredQuestionIds) {
            return in_array($question->id, $answeredQuestionIds);
        });

        return view('student.profile.index', compact('action_url', 'filteredSecurityQuestions', 'getAnswer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return view('admin.profile.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'mobile_no' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'address1' => 'required',
            'postcode' => 'required',
            'city_town' => 'required',
            'country' => 'required',
        ]);

        try {
            $update = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_no' => $request->mobile_no,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'city_town' => $request->city_town,
                'postcode' => $request->postcode,
                'country' => $request->country,
				'dob' => $request->dob,
                'gender' => $request->gender,
                'updated_at' => date('Y-m-d h:i:s'),
            ];

            if (!empty($request->question)) {
                $this->user_security_question_store($request->question);
            }

            $data = User::find($id);

            $result = $data->update($update);

            if ($result > 0) :
                $request->session()->flash('message', ['status' => 1, 'text' => 'User details successfully updated!']);
            else :
                $request->session()->flash('message', ['status' => 0, 'text' => 'User details update failed!']);
            endif;
        } catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
        }
        return redirect()->back();
    }

    public function change_password()
    {
        $action_url = 'student/update-password/' . Auth::user()->id . '/update';
        return view('student.password.index', compact('action_url'));
    }

    public function update_password(Request $request, $id)
    {
        $this->validate($request, [
            'current_Password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required_with:new_password|same:new_password|min:8'
        ]);

        $user = User::findOrFail($id);
        if (!Hash::check($request->confirm_password, $user->password)) :
            $update = $user->fill([
                'password' => Hash::make($request->confirm_password)
            ])->save();

            if ($update > 0) :
                $request->session()->flash('message', ['status' => 1, 'text' => 'Password has been updated', 'current_pass' => '', 'new_pass' => '', 'confirm_pass' => '']);
            else :
                $request->session()->flash('message', ['status' => 0,  'text' => 'Password update failed', 'current_pass' => $request->current_Password, 'new_pass' => $request->new_password, 'confirm_pass' => $request->confirm_password]);
            endif;
        else :
            $request->session()->flash('message', ['status' => 0,  'text' => 'Current Password does not match', 'current_pass' => $request->current_Password, 'new_pass' => $request->new_password, 'confirm_pass' => $request->confirm_password]);
        endif;
        return redirect()->back();
    }

    public function user_security_question_store($request)
    {
        $arr = [];

        if (!empty($request['q1'])) {
            $arr[0]['id'] = $request['q1'];
            $arr[0]['question'] = getQuestionName($request['q1']);
            $arr[0]['ans'] = $request['a1'];
        }

        if (!empty($request['q2'])) {
            $arr[1]['id'] = $request['q2'];
            $arr[1]['question'] = getQuestionName($request['q2']);
            $arr[1]['ans'] = $request['a2'];
        }

        if (!empty($request['q3'])) {
            $arr[2]['id'] = $request['q3'];
            $arr[2]['question'] = getQuestionName($request['q3']);
            $arr[2]['ans'] = $request['a3'];
        }

        if (!empty($request['q4'])) {
            $arr[3]['id'] = $request['q4'];
            $arr[3]['question'] = getQuestionName($request['q4']);
            $arr[3]['ans'] = $request['a4'];
        }

        if (!empty($request['q5'])) {
            $arr[4]['id'] = $request['q5'];
            $arr[4]['question'] = getQuestionName($request['q5']);
            $arr[4]['ans'] = $request['a5'];
        }

        if (!empty($request['q6'])) {
            $arr[5]['id'] = $request['q6'];
            $arr[5]['question'] = getQuestionName($request['q6']);
            $arr[5]['ans'] = $request['a6'];
        }

        if(!empty($request['q7'])) {
            $arr[6]['id'] = $request['q7'];
            $arr[6]['question'] = getQuestionName($request['q7']);
            $arr[6]['ans'] = $request['a7'];
        }

        if(!empty($request['q8'])) {
            $arr[7]['id'] = $request['q8'];
            $arr[7]['question'] = getQuestionName($request['q8']);
            $arr[7]['ans'] = $request['a8'];
        }

        if(!empty($request['q9'])) {
            $arr[8]['id'] = $request['q9'];
            $arr[8]['question'] = getQuestionName($request['q9']);
            $arr[8]['ans'] = $request['a9'];
        }

        if(!empty($request['q10'])) {
            $arr[9]['id'] = $request['q10'];
            $arr[9]['question'] = getQuestionName($request['q10']);
            $arr[9]['ans'] = $request['a10'];
        }

        if (!blank($arr)) {
            foreach ($arr as $val) {
                if(!blank($val['ans'])) {
                    UserQuestion::create([
                        'student_id' => Auth::user()->id,
                        'question_id' =>  $val['id'],
                        'question' => $val['question'],
                        'ans' => $val['ans'],
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }
        }
    }
}

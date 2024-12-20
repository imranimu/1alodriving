<?php

namespace App\Http\Controllers\admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\admin\CourseCertificate;
use App\Models\admin\CoursePurchase;
use App\Models\admin\CoursesModule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Image;
use PDF;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.show');
    }

    public function show()
    {
        return view('admin.profile.show');
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
            'image' => 'mimes:jpeg,jpg,png,gif,svg|max:2048',
            'image.*' => 'mimes:jpeg,jpg,png,gif,svg|max:2048'
        ]);

        try {
            $fileName = '';
            $data = User::find($id);
            if ($request->hasFile('image')) :

                if (!empty($data) && $data->image != "") {
                    if (File::exists('storage/image/avatars/' . $data->image)) {
                        File::delete('storage/image/avatars/' . $data->image);
                    }

                    if (File::exists('storage/image/avatars/thumbnail/' . $data->image)) {
                        File::delete('storage/image/avatars/thumbnail/' . $data->image);
                    }
                }

                $image = $request->file('image');
                $fileName = 'profile_' . Auth::user()->id . time() . '.' . $image->extension();;
                $img = image::make($image->getRealPath());
                $img->resize(180, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->stream(); // <-- Key point
                $dir = public_path('storage/app/public/image/avatars/');

                if (!file_exists($dir) && !is_dir($dir)) :
                    mkdir($dir, 0777, true);
                endif;

                Storage::disk('public')->put('image/avatars/' . $fileName, $img);

                $thum_img = image::make($image->getRealPath());
                $thum_img->resize(36, 36, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $thum_img->stream(); // <-- Key point
                $thum_dir = public_path('storage/app/public/image/avatars/thumbnail/');

                if (!file_exists($thum_dir) && !is_dir($thum_dir)) :
                    mkdir($thum_dir, 0777, true);
                endif;

                Storage::disk('public')->put('image/avatars/thumbnail/' . $fileName, $thum_img);

            endif;

            $update = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_no' => $request->mobile_no,
                'updated_at' => date('Y-m-d h:i:s')
            ];

            if ($fileName != "") :
                $update['image'] = $fileName;
            endif;

            $result = $data->update($update);
            if ($result > 0) :
                $request->session()->flash('message', 'User details was successfully updated!');
            else :
                $request->session()->flash('message', 'User details update failed!');
            endif;
        } catch (\Exception $e) {
            $request->session()->flash('message', $e->getMessage());
        }
        return redirect()->back();
    }

    public function change_password()
    {
        $action_url = 'admin/update-password/' . Auth::user()->id . '/update';
        return view('admin.profile.change_password', compact('action_url'));
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

	public function report(Request $request)
    {
        //dd($request->all());

        $search = $request->q;

        $sql = CoursePurchase::selectRaw('course_purchases.id, course_purchases.student_id, course_purchases.course_id, course_purchases.total_amount, course_purchases.grand_amount, course_purchases.transaction_id, course_purchases.payment_status, course_purchases.total_module, course_purchases.status, course_purchases.stripe_response, course_purchases.created_at, courses.title, courses.image, courses_modules.id as module_id, users.first_name, users.last_name, users.email')
            ->where(['course_purchases.payment_status' => '1', 'course_purchases.status' => '1'])
            ->whereRaw('stripe_response != ""')
            ->leftJoin('courses', 'course_purchases.course_id', '=', 'courses.id')
            ->leftJoin('courses_modules', 'courses.id', '=', 'courses_modules.courses_id')
            ->leftJoin('users', 'course_purchases.student_id', '=', 'users.id') // Join users table
            ->with('get_user')
            ->groupBy('course_purchases.student_id');

        if (!empty($search)) {
            $sql->where(function ($query) use ($search) {
                $query->where('users.first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('users.email', 'LIKE', '%' . $search . '%');
            });
        }

        $lists = 1;
        $perPage = 10;
        $records = $sql->paginate($perPage);
        //return $records;
        $serial = (!empty($request->page)) ? (($perPage * ($request->page - 1)) + 1) : 1;
        return view('admin.report.index', compact('lists', 'serial', 'records'));
    }



    public function report_download($course_id, $student_id)
    {
        $data = [];
        $data['getCoursePurchase'] = CoursePurchase::where(['course_id' => $course_id, 'student_id' => $student_id])->first();
        $data['getCourseModule'] = CoursesModule::where(['courses_id' => $course_id, 'status' => '1'])->get();
        $data['getLicense'] = CourseCertificate::where(['course_id' => $course_id, 'status' => '1'])->with('get_license')->first();
        $data['getCourseActivities'] = DB::select('SELECT ca.*, se.exam_percentage FROM `course_activities` ca INNER JOIN student_exams se on ca.courses_id = se.courses_id AND ca.student_id = ' . $student_id . ' AND se.module_id = ca.module_id WHERE ca.student_id = ' . $student_id . ' AND ca.courses_id = ' . $course_id . ' AND ca.deleted_at is null');
		$data['getUserInfo'] = User::where('id', $student_id)->first();
        $pdf = PDF::loadView('admin.report.report_download', $data);
        return $pdf->download('pdfview.pdf');
    }

    public function report_view($course_id, $student_id)
    {
        $data = [];
        $getCoursePurchase = CoursePurchase::where(['course_id' => $course_id, 'student_id' => $student_id])->first();
        $getCourseModule = CoursesModule::where(['courses_id' => $course_id, 'status' => '1'])->get();
        $getLicense = CourseCertificate::where(['course_id' => $course_id, 'status' => '1'])->with('get_license')->first();
        $getCourseActivities = DB::select('SELECT ca.*, se.exam_percentage FROM `course_activities` ca INNER JOIN student_exams se on ca.courses_id = se.courses_id AND ca.student_id = ' . $student_id . ' AND se.module_id = ca.module_id WHERE ca.student_id = ' . $student_id . ' AND ca.courses_id = ' . $course_id . ' AND ca.deleted_at is null');
		$getUserInfo = User::where('id', $student_id)->first();


        $fullcourse = DB::select('
        SELECT
            u.id user_id, u.first_name, u.last_name,
            ca.courses_id, c.title courses_name,
            ca.module_id, cm.name module_name, cm.id lessons_id,
            cl.title lessons_name,
            CASE WHEN ca.completed_date IS NULL THEN 0 ELSE 1 END isCompleted
        FROM users u
        INNER JOIN course_activities ca ON u.id = ca.student_id
        INNER JOIN courses c ON ca.courses_id = ?
        INNER JOIN courses_modules cm ON ca.module_id = cm.id AND c.id = cm.courses_id
        INNER JOIN course_lessons cl ON cm.id = cl.module_id
        WHERE u.id = ?
    ', [$course_id, $student_id]);

    $getAllQuestion = questionAnswer($student_id);


        return view('admin.report.studentview',compact('fullcourse','getCoursePurchase', 'getCourseModule', 'getLicense', 'getCourseActivities', 'getUserInfo', 'getAllQuestion', 'student_id'));
        // $pdf = PDF::loadView('admin.report.report_download', $data);
        // return $pdf->download('pdfview.pdf');
    }


}

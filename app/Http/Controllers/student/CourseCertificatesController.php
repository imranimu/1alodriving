<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\Controller;
use App\Models\admin\CourseActivitie;
use App\Models\admin\CourseCertificate;
use App\Models\admin\CoursePurchase;
use App\Models\admin\CoursesModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;
use PDF;

class CourseCertificatesController extends Controller
{

    public function show(Request $request)
    {
        $sql = CourseCertificate::where(['student_id' => Auth::user()->id, 'status' => '1'])->orderBy('id', 'asc');
        if (!empty($request->q)) {
            $sql->Where('course_id', 'LIKE', '%' . $request->q . '%')
                ->orWhere('exam_id', 'LIKE', '%' . $request->q . '%')
                ->orWhere('course_file_name', 'LIKE', '%' . $request->q . '%');
        }

        $lists = 1;
        $perPage = 20;
        $records = $sql->paginate($perPage);
        $serial = (!empty($input['page'])) ? (($perPage * ($input['page'] - 1)) + 1) : 1;
        return view('student.certificate.index', compact('lists', 'serial', 'records'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request, $id)
    {
        $getCertificateInfo = CourseCertificate::where(['id' => $id, 'status' => '1', 'created_by' => Auth::user()->id])->with('get_license')->first();
        if (Auth::user()->first_name =='' || Auth::user()->dob =='' ||  Auth::user()->gender =='') {
            Session::put('getCertificateErrorMessage', [
                'status' => 0,
                'type' => 1,
                'message' => 'You have completed your profile'. checkProfileInfo() .'%. please update the profile 100%.',
            ]);
            return redirect('student/certificate');
        }

        if (blank($getCertificateInfo)) {
            Session::put('getCertificateErrorMessage', [
                'status' => 0,
                'type' => 2,
                'message' => 'We are detecting the wrong certificate request for your user/account. please try the correct certificate download again.',
            ]);
            return redirect('student/certificate');
        }

        if ($getCertificateInfo->is_type == 'C1' && $getCertificateInfo->student_id == Auth::user()->id) {
            $fileName = "Certificate-".$getCertificateInfo->is_type.".pdf";
            $filePath = public_path("cc1.pdf");
            $outputFilePath = public_path($fileName);
			$this->fillPDFFile($filePath, $outputFilePath, $getCertificateInfo);
        } elseif ($getCertificateInfo->is_type == 'C2' && $getCertificateInfo->student_id == Auth::user()->id) {
            $fileName = "Certificate-".$getCertificateInfo->is_type.".pdf";
            $filePath = public_path("cc2.pdf");
            $outputFilePath = public_path($fileName);
            $this->fillPDFFileC2($filePath, $outputFilePath, $getCertificateInfo);
        }

        return response()->file($outputFilePath);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
	 public function fillPDFFile($file, $outputFilePath, $getCertificateInfo)
    {
        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($file);

        /*for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            //top section
            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(0, 0, 0);

            // $parent_left = 17;
            // $parent_top = 37.4;
            // $parent_text = "x";
            // $fpdi->Text($parent_left, $parent_top, $parent_text);

            // $transfer_left = 17;
            // $transfer_top = 43;
            // $transfer_text = "x";
            // $fpdi->Text($transfer_left, $transfer_top, $transfer_text);

            $fpdi->SetFont("helvetica", "", 10);
            $lastname_left = 28;
            $lastname_top = 75;
            $lastname_text = Auth::user()->last_name != "" ? Auth::user()->last_name : '';
            $fpdi->Text($lastname_left, $lastname_top, $lastname_text);

            $fpdi->SetFont("helvetica", "", 10);
            $first_left = 68;
            $first_top = 75;
            $first_text = Auth::user()->first_name != "" ? Auth::user()->first_name : '';
            $fpdi->Text($first_left, $first_top, $first_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_month_left = 138;
            $dob_month_top = 75;
            $dob_month_text = Auth::user()->dob != "" ? date('m', strtotime(Auth::user()->dob)) : 0;
            $fpdi->Text($dob_month_left, $dob_month_top, $dob_month_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_day_left = 146;
            $dob_day_top = 75;
            $dob_day_text = Auth::user()->dob != "" ? date('d', strtotime(Auth::user()->dob)) : 0;
            $fpdi->Text($dob_day_left, $dob_day_top, $dob_day_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_year_left = 155;
            $dob_year_top = 75;
            $dob_year_text = Auth::user()->dob != "" ? date('Y', strtotime(Auth::user()->dob)) : 0;
            $fpdi->Text($dob_year_left, $dob_year_top, $dob_year_text);

            if (Auth::user()->gender == 'male') {
                $fpdi->SetFont("helvetica", "", 14);
                $male_left = 173.2;
                $male_top = 75.6;
                $male_text = "x";
                $fpdi->Text($male_left, $male_top, $male_text);
            }

            if (Auth::user()->gender == 'female') {
                $fpdi->SetFont("helvetica", "", 14);
                $female_left = 188.4;
                $female_top = 75.6;
                $female_text = "x";
                $fpdi->Text($female_left, $female_top, $female_text);
            }

            $fpdi->SetFont("helvetica", 'B', 14);
            $fpdi->SetTextColor(255,0,0);
            $license_left = 176;
            $license_top = 33;
            $license_text = !blank($getCertificateInfo->get_license) != "" ? $getCertificateInfo->get_license->license : '';
            $fpdi->Text($license_left, $license_top, $license_text);

            $fpdi->SetFont("helvetica", "", 12);
            $fpdi->SetTextColor(0,0,0);
            $date_issued_left = 162;
            $date_issued_top = 132;
            $date_issued_text = $getCertificateInfo->created_at != "" ? date('d/m/Y', strtotime($getCertificateInfo->created_at)) : '';
            $fpdi->Text($date_issued_left, $date_issued_top, $date_issued_text);

            //bottom section
            // $fpdi->SetFont("helvetica", "", 10);
            // $parent_bottom_left = 17;
            // $parent_bottom_top = 168;
            // $parent_bottom_text = "32";
            // $fpdi->Text($parent_bottom_left, $parent_bottom_top, $parent_bottom_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $has_passed_bottom_left = 50;
            // $has_passed_bottom_top = 176;
            // $has_passed_bottom_text = "Drive Safe Driving School";
            // $fpdi->Text($has_passed_bottom_left, $has_passed_bottom_top, $has_passed_bottom_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $road_rules_left = 145;
            // $road_rules_top = 168;
            // $road_rules_text = "x";
            // $fpdi->Text($road_rules_left, $road_rules_top, $road_rules_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $road_signs_left = 172;
            // $road_signs_top = 168;
            // $road_signs_text = "x";
            // $fpdi->Text($road_signs_left, $road_signs_top, $road_signs_text);
        } */

        $getUser = Auth::user();

        for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            //top section
            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(0, 0, 0);

            // $parent_left = 17;
            // $parent_top = 37.4;
            // $parent_text = "x";
            // $fpdi->Text($parent_left, $parent_top, $parent_text);

            // $transfer_left = 17;
            // $transfer_top = 43;
            // $transfer_text = "x";
            // $fpdi->Text($transfer_left, $transfer_top, $transfer_text);

            $fpdi->SetFont("helvetica", "", 10);
            $lastname_left = 20;
            $lastname_top = 95;
            $lastname_text = $getUser->last_name != "" ? $getUser->last_name : '';
            $fpdi->Text($lastname_left, $lastname_top, $lastname_text);

            $fpdi->SetFont("helvetica", "", 10);
            $first_left = 70;
            $first_top = 95;
            $first_text = $getUser->first_name != "" ? $getUser->first_name : '';
            $fpdi->Text($first_left, $first_top, $first_text);

            if ($getUser->middle_name != "") {
                $middleName = strtoupper($getUser->middle_name[0]); // Capitalize the first word
            } else {
                $middleName = "";
            }

            $fpdi->SetFont("helvetica", "", 10);
            $first_left = 120;
            $first_top = 95;
            $first_text = $middleName;
            $fpdi->Text($first_left, $first_top, $first_text);

            $fpdi->SetFont("helvetica", '' , 9);
            $fpdi->SetTextColor(0,0,0);
            $date_issued_left = 170;
            $date_issued_top = 58.2;
            $date_issued_text = $getUser->created_at != "" ? date('m', strtotime($getUser->created_at)) : '';
            $fpdi->Text($date_issued_left, $date_issued_top, $date_issued_text);

            $fpdi->SetFont("helvetica", '' , 9);
            $fpdi->SetTextColor(0,0,0);
            $date_issued_left = 180;
            $date_issued_top = 58.2;
            $date_issued_text = $getUser->created_at != "" ? date('d', strtotime($getUser->created_at)) : '';
            $fpdi->Text($date_issued_left, $date_issued_top, $date_issued_text);

            $fpdi->SetFont("helvetica", '' , 9);
            $fpdi->SetTextColor(0,0,0);
            $date_issued_left = 188.2;
            $date_issued_top = 58.2;
            $date_issued_text = $getUser->created_at != "" ? date('Y', strtotime($getUser->created_at)) : '';
            $fpdi->Text($date_issued_left, $date_issued_top, $date_issued_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_month_left = 150.5;
            $dob_month_top = 96;
            $dob_month_text = $getUser->dob != "" ? date('m', strtotime($getUser->dob)) : 0;
            $fpdi->Text($dob_month_left, $dob_month_top, $dob_month_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_day_left = 158.5;
            $dob_day_top = 96;
            $dob_day_text = $getUser->dob != "" ? date('d', strtotime($getUser->dob)) : 0;
            $fpdi->Text($dob_day_left, $dob_day_top, $dob_day_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_year_left = 165.2;
            $dob_year_top = 96;
            $dob_year_text = $getUser->dob != "" ? date('Y', strtotime($getUser->dob)) : 0;
            $fpdi->Text($dob_year_left, $dob_year_top, $dob_year_text);

            $fpdi->SetFont("helvetica", "B", 11);
            $first_left = 137;
            $first_top = 68.5;
            $first_text = "P";
            $fpdi->Text($first_left, $first_top, $first_text);

            $fpdi->SetFont("helvetica", "B", 11);
            $first_left = 174;
            $first_top = 68.5;
            $first_text = "P";
            $fpdi->Text($first_left, $first_top, $first_text);

            if ($getUser->gender == 'male') {
                $fpdi->SetFont("helvetica", "", 12);
                $male_left = 175.5;
                $male_top = 95.9;
                $male_text = "x";
                $fpdi->Text($male_left, $male_top, $male_text);
            }

            if ($getUser->gender == 'female') {
                $fpdi->SetFont("helvetica", "", 12);
                $female_left = 190.1;
                $female_top = 95.9;
                $female_text = "x";
                $fpdi->Text($female_left, $female_top, $female_text);
            }

            $fpdi->SetFont("helvetica", 'B', 12);
            $fpdi->SetTextColor(255,0,0);
            $license_left = 175;
            $license_top = 31;
            $license_text = !blank($getCertificateInfo->get_license) != "" ? $getCertificateInfo->get_license->license : '';
            $fpdi->Text($license_left, $license_top, $license_text);

            $fpdi->SetFont("helvetica", '' , 9);
            $fpdi->SetTextColor(0,0,0);
            $date_issued_left = 162;
            $date_issued_top = 126.9;
            $date_issued_text = $getCertificateInfo->created_at != "" ? date('m  d  Y', strtotime($getCertificateInfo->created_at)) : '';
            $fpdi->Text($date_issued_left, $date_issued_top, $date_issued_text);

            //bottom section
            // $fpdi->SetFont("helvetica", "", 10);
            // $parent_bottom_left = 17;
            // $parent_bottom_top = 168;
            // $parent_bottom_text = "32";
            // $fpdi->Text($parent_bottom_left, $parent_bottom_top, $parent_bottom_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $has_passed_bottom_left = 50;
            // $has_passed_bottom_top = 176;
            // $has_passed_bottom_text = "Drive Safe Driving School";
            // $fpdi->Text($has_passed_bottom_left, $has_passed_bottom_top, $has_passed_bottom_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $road_rules_left = 145;
            // $road_rules_top = 168;
            // $road_rules_text = "x";
            // $fpdi->Text($road_rules_left, $road_rules_top, $road_rules_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $road_signs_left = 172;
            // $road_signs_top = 168;
            // $road_signs_text = "x";
            // $fpdi->Text($road_signs_left, $road_signs_top, $road_signs_text);
        }

        return $fpdi->Output($outputFilePath, 'F');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
	 public function fillPDFFileC2($file, $outputFilePath, $getCertificateInfo)
    {
        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($file);

        for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            //top section
            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(0, 0, 0);

            // $parent_left = 17;
            // $parent_top = 42.7;
            // $parent_text = "x";
            // $fpdi->Text($parent_left, $parent_top, $parent_text);

            // $transfer_left = 17;
            // $transfer_top = 42.7;
            // $transfer_text = "x";
            // $fpdi->Text($transfer_left, $transfer_top, $transfer_text);

            $fpdi->SetFont("helvetica", "", 10);
            $lastname_left = 28;
            $lastname_top = 69.7;
            $lastname_text = Auth::user()->last_name != "" ? Auth::user()->last_name : '';
            $fpdi->Text($lastname_left, $lastname_top, $lastname_text);

            $fpdi->SetFont("helvetica", "", 10);
            $first_left = 68;
            $first_top = 69.7;
            $first_text = Auth::user()->first_name != "" ? Auth::user()->first_name : '';
            $fpdi->Text($first_left, $first_top, $first_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_month_left = 136.8;
            $dob_month_top = 69.7;
            $dob_month_text = Auth::user()->dob != "" ? date('m', strtotime(Auth::user()->dob)) : 0;
            $fpdi->Text($dob_month_left, $dob_month_top, $dob_month_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_day_left = 145;
            $dob_day_top = 69.7;
            $dob_day_text = Auth::user()->dob != "" ? date('d', strtotime(Auth::user()->dob)) : 0;
            $fpdi->Text($dob_day_left, $dob_day_top, $dob_day_text);

            $fpdi->SetFont("helvetica", "", 10);
            $dob_year_left = 153.8;
            $dob_year_top = 69.7;
            $dob_year_text = Auth::user()->dob != "" ? date('Y', strtotime(Auth::user()->dob)) : 0;
            $fpdi->Text($dob_year_left, $dob_year_top, $dob_year_text);

            if (Auth::user()->gender == 'male') {
                $fpdi->SetFont("helvetica", "", 14);
                $male_left = 172.4;
                $male_top = 69.9;
                $male_text = "x";
                $fpdi->Text($male_left, $male_top, $male_text);
            }

            if (Auth::user()->gender == 'female') {
                $fpdi->SetFont("helvetica", "", 14);
                $female_left = 187.5;
                $female_top = 69.9;
                $female_text = "x";
                $fpdi->Text($female_left, $female_top, $female_text);
            }

            // $fpdi->SetFont("helvetica", "", 15);
            // $has_passed_left = 17.5;
            // $has_passed_top = 82;
            // $has_passed_text = 'x';
            // $fpdi->Text($has_passed_left, $has_passed_top, $has_passed_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $road_signs_left = 137;
            // $road_signs_top = 81.8;
            // $road_signs_text = '90';
            // $fpdi->Text($road_signs_left, $road_signs_top, $road_signs_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $road_rule_left = 170;
            // $road_rule_top = 81.8;
            // $road_rule_text = '80';
            // $fpdi->Text($road_rule_left, $road_rule_top, $road_rule_text);

            $fpdi->SetFont("helvetica", 'B', 14);
            $fpdi->SetTextColor(255,0,0);
            $license_left = 176;
            $license_top = 33;
            $license_text = !blank($getCertificateInfo->get_license) != "" ? $getCertificateInfo->get_license->license : '';
            $fpdi->Text($license_left, $license_top, $license_text);

            $fpdi->SetFont("helvetica", "", 12);
            $fpdi->SetTextColor(0,0,0);
            $date_issued_left = 162;
            $date_issued_top = 107.9;
            $date_issued_text = $getCertificateInfo->created_at != "" ? date('d/m/Y', strtotime($getCertificateInfo->created_at)) : '';
            $fpdi->Text($date_issued_left, $date_issued_top, $date_issued_text);

            //bottom section
            $fpdi->SetFont("helvetica", "", 10);
            $class_room_bottom_left = 8.5;
            $class_room_bottom_top = 182;
            $class_room_bottom_text = "32";
            $fpdi->Text($class_room_bottom_left, $class_room_bottom_top, $class_room_bottom_text);

            $fpdi->SetFont("helvetica", "", 10);
            $simulator_bottom_left = 150;
            $simulator_bottom_top = 182.5;
            $simulator_bottom_text = "x";
            $fpdi->Text($simulator_bottom_left, $simulator_bottom_top, $simulator_bottom_text);

            $fpdi->SetFont("helvetica", "", 10);
            $multi_car_bottom_left = 180;
            $multi_car_bottom_top = 182.5;
            $multi_car_bottom_text = "x";
            $fpdi->Text($multi_car_bottom_left, $multi_car_bottom_top, $multi_car_bottom_text);

            // $fpdi->SetFont("helvetica", "", 10);
            // $transferring_bottom_left = 50;
            // $transferring_bottom_top = 190;
            // $transferring_bottom_text = "Drive Safe Driving School";
            // $fpdi->Text($transferring_bottom_left, $transferring_bottom_top, $transferring_bottom_text);
        }

        return $fpdi->Output($outputFilePath, 'F');
    }

	public function report(Request $request)
    {
        $sql = CoursePurchase::selectRaw('course_purchases.id, course_purchases.student_id, course_purchases.course_id, course_purchases.total_amount, course_purchases.grand_amount, course_purchases.transaction_id, course_purchases.payment_status, course_purchases.total_module, course_purchases.status, course_purchases.stripe_response, course_purchases.created_at, courses.title, courses.image, courses_modules.id as module_id')->where(['course_purchases.student_id' => Auth::user()->id, 'course_purchases.payment_status' => '1', 'course_purchases.status' => '1'])
            ->whereRaw('stripe_response !=""')
            ->leftjoin('courses', function ($q) {
                $q->on('course_purchases.course_id', 'courses.id');
            })
            ->leftjoin('courses_modules', function ($q) {
                $q->on('courses.id', 'courses_modules.courses_id');
            })
            ->with('get_user')
            ->GroupBy('courses_modules.courses_id');

        $lists = 1;
        $perPage = 10;
        $records = $sql->paginate($perPage);
        $serial = (!empty($input['page'])) ? (($perPage * ($input['page'] - 1)) + 1) : 1;
        return view('student.report.index', compact('lists', 'serial', 'records'));
    }

    public function report_download($course_id)
    {
        $data = [];
        $data['getCoursePurchase'] = CoursePurchase::where(['course_id' => $course_id, 'student_id' => Auth::user()->id])->first();
        $data['getCourseModule'] = CoursesModule::where(['courses_id' => $course_id, 'status' => '1'])->get();
        $data['getLicense'] = CourseCertificate::where(['course_id' => $course_id, 'status' => '1'])->with('get_license')->first();
        $data['getCourseActivities'] = DB::select('SELECT ca.*, se.exam_percentage FROM `course_activities` ca INNER JOIN student_exams se on ca.courses_id = se.courses_id AND ca.student_id = ' . Auth::user()->id . ' AND se.module_id = ca.module_id WHERE ca.student_id = ' . Auth::user()->id . ' AND ca.courses_id = ' . $course_id . ' AND ca.deleted_at is null');

        $pdf = PDF::loadView('student.report.report_download', $data);
        return $pdf->download('pdfview.pdf');

        // return view('student.report.report_download', compact('getCourseModule', 'getLicense', 'getCoursePurchase', 'getCourseActivities'));
    }
}

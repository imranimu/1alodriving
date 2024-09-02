<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\admin\CourseCertificate;
use App\Models\admin\CourseLicense;
use App\Models\admin\CourseModuleHistorie;
use App\Models\admin\CoursesModule;
use App\Models\admin\CoursesResult;
use App\Models\admin\ExamMaster;
use App\Models\admin\QuestionMaster;
use App\Models\admin\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentExamController extends Controller
{
    public function index(Request $request)
    {
        $sql = StudentExam::selectRaw('student_exams.id, student_exams.student_id, student_exams.exam_id, student_exams.exam_status, student_exams.status, exam_masters.module_id, exam_masters.title, courses.title as courses_name, courses_modules.name as module_name, student_exams.exam_percentage, student_exams.completed_at, student_exams.courses_id')
            ->where('student_id', Auth::user()->id)
            ->leftjoin('exam_masters', function ($q) {
                $q->on('student_exams.exam_id', 'exam_masters.id');
            })
            ->leftjoin('courses', function ($q) {
                $q->on('exam_masters.courses_id', 'courses.id');
            })
            ->leftjoin('courses_modules', function ($q) {
                $q->on('exam_masters.module_id', 'courses_modules.id');
            })
            ->orderBy('id', 'asc');
        $lists = 1;
        $perPage = 10;
        $records = $sql->paginate($perPage);
        $serial = (!empty($input['page'])) ? (($perPage * ($input['page'] - 1)) + 1) : 1;
        return view('student.question.exam_lists', compact('lists', 'serial', 'records'));
    }

    public function join_exam(Request $request, $id)
    {
        $getExamMaster = ExamMaster::selectRaw('exam_masters.id, exam_masters.title, exam_masters.limit_number, courses.title as courses_name, courses_modules.name as module_name')
            ->leftjoin('courses', function ($q) {
                $q->on('exam_masters.courses_id', 'courses.id');
            })
            ->leftjoin('courses_modules', function ($q) {
                $q->on('exam_masters.module_id', 'courses_modules.id');
            })
            ->where('exam_masters.id', $id)->first();


        if ($getExamMaster->limit_number > 0) {

            $exam_status = StudentExam::where('exam_id', $id)
                ->where('student_id', Auth::user()->id)
                ->value('exam_status');

            //$exam_status = 0;

            //echo 'User ID' . Auth::user()->id . 'exam_id - ' . $id . ' # Exam Status # ' . $exam_status;

            //echo $exam_status;
            $limit = $getExamMaster->limit_number;

            if ($exam_status == 1) {
                $offset = $limit;
                //echo 'Second attempt';
            } else if ($exam_status == 3) {
                //echo 'third attempt';
                $offset = $limit + $limit;
            } else {
                //echo '1st attempt';
                $offset = 0;
            }

            //echo 'Offset - ' . $offset . ' Limit - ' . $limit;

            //$getCourseResult = CoursesResult::where('exam_id', $id)->where('user_id', Auth::user()->id)->first();

            //$query = "SELECT exam_status FROM student_exams WHERE exam_id = :examId AND user_id = :userId";


            //$studentExam = DB::select($query, ['exam_id' => $id, 'user_id' => Auth::user()->id]);

            //print_r($studentExam);

            //  echo "<pre>";
            //  print_r($getCourseResult);
            //  echo "</pre>";

            //$offset = 0; // Rows 1 to 4 are skipped
            //$limit = 12;  // Number of rows to retrieve (5 to 8)

            $getQuestion = QuestionMaster::where('exam_id', $id)
                ->skip($offset)
                ->take($limit)
                ->get();

            //$getQuestion = $getQuestion->chunk(4);

            //$getQuestion = $questionSets->take(3);

            // echo "<pre>";
            // print_r($getQuestion);
            // echo "</pre>";

            //$getQuestion = QuestionMaster::where('exam_id', $id)->inRandomOrder()->limit((int)$getExamMaster->limit_number)->get();

            //$getQuestion = QuestionMaster::where('exam_id', $id)->limit((int)$getExamMaster->limit_number)->get();

            //$getQuestion = QuestionMaster::where('exam_id', $id)->take(8)->get();

            // echo "<pre>";
            // print_r($getQuestion);
            // echo "</pre>";

        } else {
            $getQuestion = QuestionMaster::where('exam_id', $id)->get();
        }
        return view('student.question.join_exam', compact('getQuestion', 'getExamMaster', 'id'));
    }

    public function store_exam(Request $request)
    {
        $getCourseResult = CoursesResult::where('exam_id', $request->exam_id)->where('user_id', Auth::user()->id)->first();
        if (!blank($getCourseResult) && $getCourseResult->question_percentage >= 70) {
            return redirect('/student/quiz');
        }

        $yes_ans = 0;
        $no_ans = 0;
        $result = array();
        for ($i = 1; $i <= $request->total_question; $i++) {
            $question_id = 'question_id' . $i;
            if (isset($question_id)) {
                $question_id = 'question_id' . $i;
                $ans = 'ans' . $i;
                $q = QuestionMaster::where('id', $request->$question_id)->first();
                if ($q->ans == $request->$ans) {
                    $result[$i]['question'] = $q->question;
                    $result[$i]['ans'] = 'YES';
                    $result[$i]['currect_ans'] = $q->ans;
                    $result[$i]['select_ans'] = $request->$ans;
                    $yes_ans++;
                } else {
                    $result[$i]['question'] = $q->question;
                    $result[$i]['ans'] = 'NO';
                    $result[$i]['currect_ans'] = $q->ans;
                    $result[$i]['select_ans'] = $request->$ans;
                    $no_ans++;
                }
            }
        }

        if (!blank($result)) {
            $playerson = (int) preg_replace('/[^0-9]/', '', $yes_ans);
            $maxplayers = (int) preg_replace('/[^0-9]/', '', $request->total_question);
            if ($playerson > 0 && $maxplayers > 0) {
                $percentage = ($playerson / $maxplayers) * 100;
            } else {
                $percentage = 0;
            }

            if (!blank($getCourseResult)) {
                $data = CoursesResult::find($getCourseResult->id);
                $update = $data->update([
                    'yes_ans' => $yes_ans,
                    'no_ans' => $no_ans,
                    'result_json' => json_encode($result),
                    'total_question' => $request->total_question,
                    'question_percentage' => $percentage,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d h:i:s')
                ]);
            } else {
                CoursesResult::create([
                    'exam_id' => $request->exam_id,
                    'user_id' => Auth::user()->id,
                    'yes_ans' => $yes_ans,
                    'no_ans' => $no_ans,
                    'result_json' => json_encode($result),
                    'total_question' => $request->total_question,
                    'question_percentage' => $percentage,
                    'created_by' => Auth::user()->id,
                ]);
            }

            //$arr = ['exam_percentage' => $percentage, 'exam_status' => '1'];

            $exam_status = StudentExam::where('exam_id', $request->exam_id)
                ->where('student_id', Auth::user()->id)
                ->value('exam_status');

            if ($percentage >= 70) {
                $arr = [
                    'exam_status' => '2',
                    'exam_percentage' => $percentage,
                    'completed_at' => date('Y-m-d h:i:s')
                ];
            } else {
                if ($exam_status == '1') {
                    $arr = [
                        'exam_status' => '3',
                        'exam_percentage' => $percentage
                    ];
                } else if ($exam_status == '3') {
                    $arr = [
                        'exam_status' => '0',
                        'exam_percentage' => $percentage
                    ];
                } else {
                    $arr = ['exam_percentage' => $percentage, 'exam_status' => '1'];
                }
                //$arr = ['exam_percentage' => $percentage, 'exam_status' => '3'];
            }

            StudentExam::where(['exam_id' => $request->exam_id, 'student_id' => Auth::user()->id])->update($arr);

            self::set_student_certificate($request->exam_id);
        }
        return redirect('/student/view-result/' . $request->exam_id);
    }

    public function view_result(Request $request, $id)
    {
        $getReuslt = CoursesResult::selectRaw('exam_masters.id, exam_masters.title, courses.id as course_id, courses.title as courses_name, courses_modules.id as module_id, courses_modules.name as module_name, courses_results.exam_id, courses_results.user_id, courses_results.yes_ans, courses_results.no_ans, courses_results.result_json, courses_results.total_question, courses_results.question_percentage')
            ->leftjoin('exam_masters', function ($q) {
                $q->on('courses_results.exam_id', 'exam_masters.id');
            })
            ->leftjoin('courses', function ($q) {
                $q->on('exam_masters.courses_id', 'courses.id');
            })
            ->leftjoin('courses_modules', function ($q) {
                $q->on('exam_masters.module_id', 'courses_modules.id');
            })
            ->where(['courses_results.exam_id' => $id, 'courses_results.user_id' => Auth::user()->id])
            ->first();

        $exam_status = StudentExam::where('exam_id', $id)
            ->where('student_id', Auth::user()->id)
            ->value('exam_status');

        return view('student.question.view_result', compact('getReuslt', 'id', 'exam_status'));
    }

    private function set_student_certificate($exam_id = 51)
    {
        $getCoursesId = ExamMaster::where(['id' => $exam_id, 'status' => '1'])->first();
        $getCourseModuleCount = CoursesModule::where(['status' => '1', 'courses_id' => $getCoursesId->courses_id])->orderBy('id', 'asc')->get();
        $arr = [];
        $allModuleArr = [];
        if (!blank($getCourseModuleCount)) {
            $count = 1;
            foreach ($getCourseModuleCount  as $val) {
                if ($count <= 3) {
                    $arr[] = $val->id;
                }
                $allModuleArr[] = $val->id;
                $count++;
            }
        }

        if (!blank($arr)) {
            $getStudentExamThreeModuleCompletedCheck = StudentExam::where(['status' => '1', 'exam_status' => '2', 'student_id' => Auth::user()->id])->whereIn('module_id', $arr)->where('exam_percentage', '>', 69)->count();
            //if ($getStudentExamThreeModuleCompletedCheck == 5) {
                $certificateC1Exist = CourseCertificate::where(['student_id' => Auth::user()->id, 'course_id' => $getCoursesId->courses_id, 'is_type' => 'C1'])->exists();
                if (!$certificateC1Exist) {
                    $getLicenseNumber = CourseLicense::where(['license_status' => '0', 'status' => '1'])->orderBy('id', 'asc')->first();
                    CourseCertificate::create([
                        'student_id' => Auth::user()->id,
                        'course_id' => $getCoursesId->courses_id,
                        'license_id' => !blank($getLicenseNumber) ? $getLicenseNumber->id : 0,
                        'is_type' => 'C1',
                        'created_by' => Auth::user()->id,
                    ]);
                    if (!blank($getLicenseNumber)) {
                        $data = CourseLicense::find($getLicenseNumber->id);
                        $data->update(['license_status' => '1']);
                    }
                }
            //}

            $getStudentExamAllModuleCompletedCheck = StudentExam::where(['status' => '1', 'exam_status' => '2', 'student_id' => Auth::user()->id])->whereIn('module_id', $allModuleArr)->where('exam_percentage', '>', 69)->count();
            if ($getStudentExamAllModuleCompletedCheck == count($getCourseModuleCount)) {
                $certificateC2Exist = CourseCertificate::where(['student_id' => Auth::user()->id, 'course_id' => $getCoursesId->courses_id, 'is_type' => 'C2'])->exists();
                if (!$certificateC2Exist) {
                    $getLicenseNumber = CourseCertificate::where(['student_id' => Auth::user()->id, 'course_id' => $getCoursesId->courses_id])->first();
                    CourseCertificate::create([
                        'student_id' => Auth::user()->id,
                        'course_id' => $getCoursesId->courses_id,
                        'license_id' => $getLicenseNumber->license_id,
                        'is_type' => 'C2',
                        'created_by' => Auth::user()->id,
                    ]);
                }
            }
        }
    }
}

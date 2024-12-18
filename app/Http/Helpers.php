<?php
use Illuminate\Support\Facades\Auth;
use App\Models\admin\StudentExam;
use App\Models\admin\CoursesModule;

if (!function_exists('getSettings')) {
    function getSettings()
    {
        $btSettings = App\Models\admin\DrSetting::first();
        if (!empty($btSettings)) :
            return $btSettings;
        endif;
    }
}

if (!function_exists('getCourseLevel')) {
    function getCourseLevel()
    {
        return [
            'Advance',
            'Beginner',
        ];
    }
}

if (!function_exists('getLessonType')) {
    function getLessonType()
    {
        return [
            'Video',
            'Audio',
            'PDF',
        ];
    }
}

if (!function_exists('getCourseLists')) {
    function getCourseLists()
    {
        $course = App\Models\admin\Course::where('status', '1')->whereNull('deleted_at')->limit(3)->get();
        if (!empty($course)) :
            return $course;
        endif;
    }
}

if (!function_exists('getCourseName')) {
    function getCourseName($course_id)
    {
        $course = App\Models\admin\Course::where(['status' => '1', 'id' => $course_id])->first();
        if (!empty($course)) :
            return $course->title;
        endif;
    }
}

if (!function_exists('getCourseLession')) {
    function getCourseLession($course_id, $module_id)
    {
        $getCourseLession = App\Models\admin\CourseLesson::where(['status' => '1', 'course_id' => $course_id, 'module_id' => $module_id])->get();
        if (!empty($getCourseLession)) :
            return $getCourseLession;
        endif;
    }
}

if (!function_exists('getCourseLessionData')) {
    function getCourseLessionData($course_id, $module_id)
    {
        $getCourseLessionHistories = App\Models\admin\CourseModuleHistorie::where(['status' => '1', 'courses_id' => $course_id, 'module_id' => $module_id, 'created_by' => Auth::user()->id])->first();
        if (isset($getCourseLessionHistories) && $getCourseLessionHistories->complete_lession == "") :
            $getCourseLession = App\Models\admin\CourseLesson::where(['status' => '1', 'course_id' => $course_id, 'module_id' => $module_id])->limit(1)->orderBy('id', 'asc')->get();
        else :
            $complete_lession =  str_replace("[", " ", $getCourseLessionHistories->complete_lession);
            $complete_lession =  str_replace("]", " ", $complete_lession);
            $complete_lession = $complete_lession . ',' . $getCourseLessionHistories->ongoing_lession;
            $complete_lession = explode(",", (string)$complete_lession);
            $getCourseLession = App\Models\admin\CourseLesson::where(['status' => '1', 'course_id' => $course_id, 'module_id' => $module_id])->WhereIn('id', $complete_lession)->get();
        endif;
        if (!empty($getCourseLession)) :
            return $getCourseLession;
        endif;
    }
}

if (!function_exists('getCoursePercetage')) {
    function getCoursePercetage($course_id)
    {
        $getCourseModuleCount = App\Models\admin\CoursesModule::where(['status' => '1', 'courses_id' => $course_id])->count();
        $complete_lession = App\Models\admin\CourseModuleHistorie::where(['status' => '1', 'courses_id' => $course_id, 'module_status' => '1', 'created_by' => Auth::user()->id])->count();

        $playerson = (int) preg_replace('/[^0-9]/', '', $complete_lession);
        $maxplayers = (int) preg_replace('/[^0-9]/', '', $getCourseModuleCount);
		if ($playerson > 0 && $maxplayers > 0) :
			$percentage = ($playerson / $maxplayers) * 100;
			$percentage = number_format($percentage,2);
		else :
			$percentage = 0;
		endif;
        return $percentage;
    }
}

if (!function_exists('getCourseLessionPercetage')) {

    function getCourseLessionPercetage($course_id, $module_id)
    {
        $moduleHistory = App\Models\admin\CourseModuleHistorie::selectRaw('course_module_histories.courses_id, course_module_histories.module_id, course_module_histories.total_lession, course_module_histories.complete_lession, course_module_histories.examination_status, course_module_histories.status, course_module_histories.created_at, course_module_histories.module_status, courses_modules.name')
            ->where(['course_module_histories.courses_id' => $course_id, 'course_module_histories.module_id' => $module_id, 'course_module_histories.status' => '1', 'course_module_histories.created_by' => Auth::user()->id])
            ->leftjoin('courses_modules', function ($q) {
                $q->on('course_module_histories.module_id', 'courses_modules.id');
            })
            ->first();

        if (!$moduleHistory) {
            return 0; // Handle case where module history is not found
        }

        $totalLessons = isset($moduleHistory->total_lession) ? $moduleHistory->total_lession : 0;
        $completedLessons = isset($moduleHistory->complete_lession) ? count(json_decode($moduleHistory->complete_lession)) : 0;

        if ($totalLessons === 0) {
            return 0; // Avoid division by zero
        }

        $percentage = ($completedLessons / $totalLessons) * 100;

        if($percentage > 100){
            $percentage = 100;
        }else{
            $percentage = number_format($percentage, 2);
        }

        return $percentage;
    }
}

if (!function_exists('adminGetCourseLessonPercentage')) {
    function adminGetCourseLessonPercentage($course_id, $module_id, $student_id)
    {
        $moduleHistory = App\Models\admin\CourseModuleHistorie::selectRaw('course_module_histories.courses_id, course_module_histories.module_id, course_module_histories.total_lession, course_module_histories.complete_lession, course_module_histories.examination_status, course_module_histories.status, course_module_histories.created_at, course_module_histories.module_status, courses_modules.name')
            ->where([
                'course_module_histories.courses_id' => $course_id,
                'course_module_histories.module_id' => $module_id,
                'course_module_histories.status' => '1',
                'course_module_histories.created_by' => $student_id // Use student_id to filter
            ])
            ->leftJoin('courses_modules', function ($q) {
                $q->on('course_module_histories.module_id', '=', 'courses_modules.id');
            })
            ->first();

        if (!$moduleHistory) {
            return 0; // Handle case where module history is not found
        }

        $totalLessons = isset($moduleHistory->total_lession) ? $moduleHistory->total_lession : 0;
        $completedLessons = isset($moduleHistory->complete_lession) ? count(json_decode($moduleHistory->complete_lession)) : 0;

        if ($totalLessons === 0) {
            return 0; // Avoid division by zero
        }

        $percentage = ($completedLessons / $totalLessons) * 100;

        // Ensure percentage doesn't exceed 100 and format it
        $percentage = $percentage > 100 ? 100 : number_format($percentage, 2);

        return $percentage;
    }
}


// if (!function_exists('getCoursePercetage')) {
    // function getCoursePercetage($course_id)
    // {
        // $getCourseModuleCount = App\Models\admin\CoursesModule::where(['status' => '1', 'courses_id' => $course_id])->count();
        // $getCourseHistory = App\Models\admin\CourseModuleHistorie::where(['status' => '1', 'courses_id' => $course_id, 'created_by' => Auth::user()->id])->get();
        // $total_lession = 0;
        // $complete_lession = 0;
        // if (!empty($getCourseHistory)) :
            // foreach ($getCourseHistory as $val) :
                // $total_lession += $val->total_lession;
                // $complete_lession += $val->complete_lession != "" ? count(json_decode($val->complete_lession)) : 0;
            // endforeach;
        // endif;
        // $playerson = (int) preg_replace('/[^0-9]/', '', $complete_lession);
        // $maxplayers = (int) preg_replace('/[^0-9]/', '', $getCourseModuleCount);
		// if ($playerson > 0 && $maxplayers > 0) :
			// $percentage = ($playerson / $maxplayers) * 100;
		// else :
			// $percentage = 0;
		// endif;
        // return $percentage;
    // }
// }

if (!function_exists('getRandomCourseLists')) {
    function getRandomCourseLists($whereId = '')
    {
        if ($whereId != "") :
            $course = App\Models\admin\Course::where('status', '1')->whereNotIn('id', [$whereId])->inRandomOrder()->limit(4)->get();
        else :
            $course = App\Models\admin\Course::where('status', '1')->inRandomOrder()->limit(4)->get();
        endif;

        if (!empty($course)) :
            return $course;
        endif;
    }
}

if (!function_exists('getAddons')) {
    function getAddons()
    {
        return [
            '1' => 'Addons',
            '2' => 'Certificate',
        ];
    }
}

if (!function_exists('getCountry')) {
    function getCountry()
    {
        $countries = [
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.s.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        ];
        return $countries;
    }
}

if (!function_exists('getModuleCompleted')) {
    function getModuleCompleted($course_id)
    {
        $getModuleCompletedCount = App\Models\admin\CourseModuleHistorie::where(['status' => '1', 'courses_id' => $course_id, 'created_by' => Auth::user()->id, 'module_status' => '1'])->count();
        return $getModuleCompletedCount;
    }
}

if (!function_exists('checkProfileInfo')) {
    function checkProfileInfo()
    {
        $checkProfileInfo = App\Models\User::where(['status' => '1', 'id' => Auth::user()->id])->first();
        $percentage = 0;
        if ($checkProfileInfo->first_name != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->email != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->mobile_no != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->dob != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->gender != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->address1 != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->postcode != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->city_town != "") {
            $percentage += 11.11111111111111;
        }

        if ($checkProfileInfo->country != "") {
            $percentage += 11.11111111111111;
        }

        return number_format($percentage, 2);
    }
}

if (!function_exists('getExamStart')) {
    function getExamStart($id, $course_id)
    {
        // return $course_id;
        $getPreviousExam = App\Models\admin\StudentExam::where(['status' => '1', 'courses_id' => $course_id, 'student_id' => Auth::user()->id])->where('id', '<', $id)->orderBy('id', 'desc')->limit(1)->first();

        //return  $getPreviousExam;

        $status = false;

        if (!blank($getPreviousExam)) {
            // return $id;
            $getCurrentExam = App\Models\admin\StudentExam::where(['status' => '1', 'courses_id' => $course_id, 'student_id' => Auth::user()->id])->where('id', '>', $getPreviousExam->id)->orderBy('id')->first();

            //return $getCurrentExam;

            if (!blank($getCurrentExam)) {

                if (($getCurrentExam->exam_status == '0' || $getCurrentExam->exam_status == '3' || $getCurrentExam->exam_status == '1') && $getCurrentExam->completed_at == null) {
                    //$addOnDay = date('Y-m-d', strtotime($getPreviousExam->completed_at . ' +1 day'));
                    //if (date('Y-m-d') >= $addOnDay) {
                    //    $status = true;
                    //}

                    if($getPreviousExam->exam_status == '2') {
                        $status = true;
                    }else{
                        $status = false;
                    }
                }
            }
        } else {

            $getCurrentExam = App\Models\admin\StudentExam::where(['status' => '1', 'courses_id' => $course_id, 'student_id' => Auth::user()->id])->where('id', $id)->orderBy('id')->first();

            //return  $getCurrentExam;

            if ($getCurrentExam->exam_status != '2' && $getCurrentExam->completed_at == "") {
                $status = true;
            }
        }

        return $status;
    }
}

if (!function_exists('getCoursesCount')) {
    function getCoursesCount()
    {
        $getCoursesCount = App\Models\admin\Course::where('status', '1')->whereNull('deleted_at')->count();
        return $getCoursesCount;
    }
}

if (!function_exists('getCoursesModuleCount')) {
    function getCoursesModuleCount()
    {
        $getCoursesModuleCount = App\Models\admin\CoursesModule::where('status', '1')->whereNull('deleted_at')->count();
        return $getCoursesModuleCount;
    }
}

if (!function_exists('getCoursesModules')) {
    function getCoursesModules($courseId)
    {
        //$coursesModules = App\Models\admin\CoursesModule::where('status', '1')->whereNull('deleted_at')->get();

        $coursesModules = App\Models\admin\CoursesModule::where('courses_id', $courseId)
            ->where('status', '1')
            ->whereNull('deleted_at')
            ->get();

        return $coursesModules;
    }
}

if (!function_exists('getCoursesLessonCount')) {
    function getCoursesLessonCount()
    {
        $getCoursesLessonCount = App\Models\admin\CourseLesson::where('status', '1')->whereNull('deleted_at')->count();
        return $getCoursesLessonCount;
    }
}

if (!function_exists('getQuestionCount')) {
    function getQuestionCount()
    {
        //$getQuestionCount = App\Models\admin\QuestionMaster::where('status', '1')->count();
        $getQuestionCount = App\Models\admin\QuestionMaster::where('status', '1')->whereNull('deleted_at')->count();
        return $getQuestionCount;
    }
}

if (!function_exists('getDocumentCount')) {
    function getDocumentCount()
    {
        $getDocumentCount = App\Models\admin\CourseDocument::where('status', '1')->count();
        return $getDocumentCount;
    }
}

if (!function_exists('getLicenseCount')) {
    function getLicenseCount()
    {
        $getLicenseCount = App\Models\admin\CourseLicense::where('status', '1')->where('license_status', '0')->count();
        return $getLicenseCount;
    }
}

if (!function_exists('getCertificateGenerateCount')) {
    function getCertificateGenerateCount()
    {
        $getCertificateCount = App\Models\admin\CourseCertificate::where('status', '1')->count();
        return $getCertificateCount;
    }
}

if (!function_exists('getTodayPaymentAmount')) {
    function getTodayPaymentAmount()
    {
        $getTodayPaymentAmount = App\Models\admin\CoursePurchase::selectRaw('SUM(grand_amount) as grand_amount')->where(['payment_status' => '1', 'status' => '1'])->whereNotNull('transaction_id')->whereNotNull('stripe_response')->whereRaw('Date(created_at) = CURDATE()')->first();
        return $getTodayPaymentAmount->grand_amount > 0 ? $getTodayPaymentAmount->grand_amount : 0;
    }
}

if (!function_exists('getAllUserCount')) {
    function getAllUserCount()
    {
        $getAdmin = App\Models\User::where(['status' => '1', 'is_role' => '1'])->count();
        $getStudent = App\Models\User::where(['status' => '1', 'is_role' => '2'])->count();
        return ['admin' => $getAdmin, 'student' => $getStudent];
    }
}

if (!function_exists('getAllPaymentAmount')) {
    function getAllPaymentAmount()
    {
        $getAllPaymentAmount = App\Models\admin\CoursePurchase::selectRaw("DATE_FORMAT(created_at, '%M') as month, SUM(grand_amount) as grand_amount, created_at")->where(['payment_status' => '1', 'status' => '1'])->whereRaw("DATE_FORMAT(created_at, '%Y') = ".date('Y'))->whereNotNull('transaction_id')->whereNotNull('stripe_response')->groupByRaw("DATE_FORMAT(created_at, '%m')")->orderBy('month', 'desc')->get();
        $arr = [];
		$arr['month'] = [];
		$arr['amount'] = [];
        if (isset($getAllPaymentAmount) && !blank($getAllPaymentAmount)) :
            foreach ($getAllPaymentAmount as $val) :
                $arr['month'][] = "'".$val->month."'";
                $arr['amount'][] = $val->grand_amount;
            endforeach;
        endif;
        return $arr;
    }
}

if (!function_exists('getQuestionBox')) {
    function getQuestionBox()
    {
        return [
            '1' => 'First Question Box',
            '2' => 'Second Question Box',
        ];
    }
}

if (!function_exists('getQuestionName')) {
    function getQuestionName($question_id)
    {
        $getQuestion = App\Models\admin\SecurityQuestion::where(['status' => '1', 'id' => $question_id])->first();
        if (!empty($getQuestion)) :
            return $getQuestion->question;
        endif;
    }
}

if (!function_exists('getLastCourseLessionCompleted')) {
    function getLastCourseLessionCompleted($course_id)
    {
        $getLastLessionStatus = App\Models\admin\CourseModuleHistorie::where(['status' => '1', 'courses_id' => $course_id, 'created_by' => Auth::user()->id])->limit(1)->orderBy('id', 'desc')->first();
        if (!empty($getLastLessionStatus)) :
            return $getLastLessionStatus;
        endif;
    }
}

if (!function_exists('getAdminLastCourseLessonCompleted')) {
    function getAdminLastCourseLessonCompleted($course_id, $student_id)
    {
        $getLastLessonStatus = App\Models\admin\CourseModuleHistorie::where([
            'status' => '1',
            'courses_id' => $course_id,
            'created_by' => $student_id
        ])->limit(1)->orderBy('id', 'desc')->first();

        if (!empty($getLastLessonStatus)) {
            return $getLastLessonStatus;
        }

        return null; // Return null if no result is found
    }
}


if (!function_exists('getNextModule')) {
    function getNextModule($course_id, $module_id)
    {


        // $current_module = DB::table('courses_modules')
        // ->where('id', $module_id)
        // ->where('courses_id', $course_id)
        // ->first();

        // return $current_module;

        $nextModule = DB::table('courses_modules')
        ->where('courses_id', $course_id)
        ->where('id', '>', $module_id)
        ->orderBy('id', 'asc')
        ->first();

        if ($nextModule) {
            // Process the next module

            $getLesson = getCourseLession($course_id, $nextModule->id);

            //return $getLesson[0]->id;

            return [
                'course_id' => $course_id,
                'module_id' => $nextModule->id,
                'lesson_id' => $getLesson[0]->id
            ];

        } else {
            // No next module found
            return 'All Completed';
        }


    }
}

if(!function_exists('getReviewLesson')){
    function getReviewLesson($course_id, $module_id)
    {
        $getCourseLession = App\Models\admin\CourseLesson::where(['status' => '1', 'course_id' => $course_id, 'module_id' => $module_id])->get();

        if ($getCourseLession->count() >= 2) {
            // Get the second to last item
            $secondLastLesson = $getCourseLession->slice(-2, 1)->first();
        } else {
            // Handle the case where there are not enough items
            $secondLastLesson = null;
        }

        if (!empty($secondLastLesson)) :
            return $secondLastLesson;
        endif;
    }
}

if(!function_exists('questionAnswer')){
    function questionAnswer($student_id)
    {
        // $getQuestion = App\Models\admin\UserQuestion::where(['status' => '1', 'student_id' => $student_id])->first();

        $getQuestion = App\Models\admin\UserQuestion::where(['status' => '1', 'student_id' => $student_id])->get();

        if (!empty($getQuestion)) :
            return $getQuestion;
        endif;
    }
}

if (!function_exists('securityQustionAnwerCount')) {
    function securityQustionAnwerCount()
    {
        $getAnswer = App\Models\admin\UserQuestion::where('student_id', Auth::user()->id)->get();

        $AnsCount = $getAnswer->count();

        return $AnsCount;
    }
}

if(!function_exists('getStudentExams')){
    function getStudentExams($student_id)
    {
        $sql = StudentExam::selectRaw('student_exams.id, student_exams.student_id, student_exams.exam_id, student_exams.exam_status, student_exams.status, exam_masters.module_id, exam_masters.title, courses.title as courses_name, courses_modules.name as module_name, student_exams.exam_percentage, student_exams.completed_at, student_exams.courses_id')
            ->where('student_id', $student_id)
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

        $perPage = 40;

        $records = $sql->paginate($perPage);

        return $records;

        //return view('student.question.exam_lists', compact('lists', 'serial', 'records'));
    }
}

if(!function_exists('getStudentExamStatus')){
    function getStudentExamStatus($getCoursesId, $student_id){
        $getCourseModuleCount = CoursesModule::where(['status' => '1', 'courses_id' => $getCoursesId])->orderBy('id', 'asc')->get();
        $arr = [];
        $allModuleArr = [];

        if (!blank($getCourseModuleCount)) {
            $count = 1;
            foreach ($getCourseModuleCount  as $val) {
                $arr[] = $val->id;
                $allModuleArr[] = $val->id;
                $count++;
            }
        }

        $getResult = StudentExam::where(['status' => '1', 'exam_status' => '2', 'student_id' => $student_id])->whereIn('module_id', $arr)->where('exam_percentage', '>', 69)->count();

        return $getResult;
    }
}

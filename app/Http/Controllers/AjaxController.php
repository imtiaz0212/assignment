<?php

namespace App\Http\Controllers;

use App\Models\ExamSettings;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get mark list
    public function markEntryList(Request $request)
    {

        $where        = [];
        $studentWhere = [['students.deleted_at', '=', null]];

        if (!empty($request->ac_year_id)) {
            $where[]        = ['ac_year_id', '=', $request->ac_year_id];
            $studentWhere[] = ['students.ac_year_id', '=', $request->ac_year_id];
        }

        if (!empty($request->ac_class_id)) {
            $where[]        = ['ac_class_id', '=', $request->ac_class_id];
            $studentWhere[] = ['students.ac_class_id', '=', $request->ac_class_id];
        }

        if (!empty($request->ac_section_id)) {
            $where[]        = ['ac_section_id', '=', $request->ac_section_id];
            $studentWhere[] = ['students.ac_section_id', '=', $request->ac_section_id];
        }

        if (!empty($request->exam_id)) {
            $where[] = ['exam_id', '=', $request->exam_id];
        }

        if (!empty($request->ac_group_id)) {
            $where[]        = ['ac_group_id', '=', $request->ac_group_id];
            $studentWhere[] = ['students.ac_group_id', '=', $request->ac_group_id];
        }

        if (!empty($request->subject_id)) {
            $where[] = ['subject_id', '=', $request->subject_id];
        }

        $examInfo = ExamSettings::where($where)->first();

        $studentList = DB::table('students')
            ->leftJoin('ac_years', 'students.ac_year_id', '=', 'ac_years.id')
            ->leftJoin('ac_classes', 'students.ac_class_id', '=', 'ac_classes.id')
            ->leftJoin('ac_sections', 'students.ac_section_id', '=', 'ac_sections.id')
            ->leftJoin('ac_groups', 'students.ac_group_id', '=', 'ac_groups.id')
            ->select('students.*', 'ac_years.year', 'ac_classes.class', 'ac_groups.group', 'ac_sections.section')
            ->where($studentWhere)
            ->get();

        $results = [];
        if (!empty($studentList)) {
            foreach ($studentList as $row) {
                $item = [];

                $item['student_code']    = $row->student_code;
                $item['student_name']    = $row->student_name;
                $item['roll']            = $row->roll;
                $item['subjective_mark'] = 0;
                $item['objective_mark']  = 0;
                $item['practical_mark']  = 0;
                $item['total_marks']     = 0;
                $item['grade_point']     = 0;
                $item['letter_grade']    = 'F';

                $markInfo = DB::table('marks')
                    ->join('exam_settings', 'marks.exam_setting_id', '=', 'exam_settings.id')
                    ->select('marks.*', 'exam_settings.ac_year_id', 'exam_settings.ac_class_id', 'exam_settings.ac_section_id', 'exam_settings.ac_group_id')
                    ->where('marks.deleted_at', '=', null)
                    ->where('marks.student_code', '=', $row->student_code)
                    ->where('marks.exam_setting_id', '=', $examInfo->id)
                    ->where('exam_settings.ac_year_id', '=', $examInfo->ac_year_id)
                    ->where('exam_settings.ac_class_id', '=', $examInfo->ac_class_id)
                    ->where('exam_settings.ac_section_id', '=', $examInfo->ac_section_id)
                    ->where('exam_settings.ac_group_id', '=', $examInfo->ac_group_id)
                    ->where('exam_settings.subject_id', '=', $examInfo->subject_id)
                    ->first();

                if (!empty($markInfo)) {
                    $item['subjective_mark'] = (!empty($markInfo->subjective_mark) ? $markInfo->subjective_mark : 0);
                    $item['objective_mark']  = (!empty($markInfo->objective_mark) ? $markInfo->objective_mark : 0);
                    $item['practical_mark']  = (!empty($markInfo->practical_mark) ? $markInfo->practical_mark : 0);
                    $item['total_marks']     = (!empty($markInfo->total_marks) ? $markInfo->total_marks : 0);
                    $item['grade_point']     = (!empty($markInfo->grade_point) ? $markInfo->grade_point : '0.00');
                    $item['letter_grade']    = (!empty($markInfo->letter_grade) ? $markInfo->letter_grade : 'F');

                    array_push($results, (object)$item);
                } else {
                    array_push($results, (object)$item);
                }


            }
        }

        $date = [
            'examInfo'    => $examInfo,
            'studentList' => $results,
        ];

        echo json_encode($date);
    }

    // get subject list
    public function subjectList(Request $request)
    {

        $where = [];

        if (!empty($request->ac_class_id)) {
            $where[] = ['ac_class_id', '=', $request->ac_class_id];
        }

        if (!empty($request->ac_group_id)) {
            $where[] = ['ac_group_id', '=', $request->ac_group_id];
        }

        $results = Subject::where($where)->select('id', 'subject_name', 'subject_code')->get();

        echo json_encode($results);
    }

    // get subject info
    public function subjectInfo(Request $request)
    {
        $examWhere = [];

        if (!empty($request->ac_class_id)) {
            $examWhere[] = ['ac_class_id', '=', $request->ac_class_id];
        }

        if (!empty($request->ac_group_id)) {
            $examWhere[] = ['ac_group_id', '=', $request->ac_group_id];
        }

        if (!empty($request->ac_year_id)) {
            $examWhere[] = ['ac_year_id', '=', $request->ac_year_id];
        }

        if (!empty($request->ac_section_id)) {
            $examWhere[] = ['ac_section_id', '=', $request->ac_section_id];
        }

        if (!empty($request->exam_id)) {
            $examWhere[] = ['exam_id', '=', $request->exam_id];
        }

        if (!empty($request->subject_id)) {
            $examWhere[] = ['subject_id', '=', $request->subject_id];
        }

        $examInfo = ExamSettings::where($examWhere)->first();

        $data = [
            'subjective'           => 0,
            'subjective_pass_mark' => 0,
            'objective'            => 0,
            'objective_pass_mark'  => 0,
            'practical'            => 0,
            'practical_pass_mark'  => 0,
        ];


        if (!empty($examInfo)) {
            $data['subjective']           = $examInfo->subjective;
            $data['subjective_pass_mark'] = $examInfo->subjective_pass_mark;
            $data['objective']            = $examInfo->objective;
            $data['objective_pass_mark']  = $examInfo->objective_pass_mark;
            $data['practical']            = $examInfo->practical;
            $data['practical_pass_mark']  = $examInfo->practical_pass_mark;
        }

        echo json_encode($data);
    }
}

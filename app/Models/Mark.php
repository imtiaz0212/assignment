<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Mark extends Model
{
    use HasFactory, SoftDeletes;

    public function studentList($request)
    {
        $results = DB::select("select `marks`.id, `marks`.exam_setting_id, `marks`.created, `marks`.student_code, SUM(marks.total_marks) AS total_marks, `students`.`student_name`, `students`.`roll` from `marks` inner join `students` on `marks`.`student_code` = `students`.`student_code` inner join `exam_settings` on `marks`.`exam_setting_id` = `exam_settings`.`id` where `marks`.`deleted_at` is null group by `marks`.`student_code`");
        return $results;
    }

    public function progressReport($id)
    {
        $examInfo = DB::table('marks')
            ->join('students', 'marks.student_code', '=', 'students.student_code')
            ->join('exam_settings', 'marks.exam_setting_id', '=', 'exam_settings.id')
            ->leftJoin('exams', 'exam_settings.exam_id', '=', 'exams.id')
            ->leftJoin('ac_years', 'exam_settings.ac_year_id', '=', 'ac_years.id')
            ->leftJoin('ac_classes', 'exam_settings.ac_class_id', '=', 'ac_classes.id')
            ->leftJoin('ac_groups', 'exam_settings.ac_group_id', '=', 'ac_groups.id')
            ->leftJoin('ac_sections', 'exam_settings.ac_section_id', '=', 'ac_sections.id')
            ->select('marks.created', 'marks.exam_setting_id', 'marks.student_code', 'students.student_name', 'students.roll', 'exam_settings.ac_year_id', 'exam_settings.ac_class_id', 'exam_settings.ac_group_id', 'exam_settings.ac_section_id', 'exams.exam_name', 'ac_years.year', 'ac_classes.class', 'ac_groups.group', 'ac_sections.section')
            ->where('marks.id', '=', $id)
            ->where('marks.deleted_at', '=', null)
            ->where('exam_settings.deleted_at', '=', null)
            ->first();

        $markInfo = DB::table('marks')
            ->join('exam_settings', 'marks.exam_setting_id', '=', 'exam_settings.id')
            ->leftJoin('subjects', 'exam_settings.subject_id', '=', 'subjects.id')
            ->select('marks.*', 'exam_settings.subjective', 'exam_settings.objective', 'exam_settings.practical', 'exam_settings.exam_marks', 'exam_settings.subject_relation', 'exam_settings.subject_id', 'exam_settings.subject_relation', 'subjects.subject_code', 'subjects.subject_name', 'subjects.subject_type')
            ->where('marks.deleted_at', '=', null)
            ->where('marks.student_code', '=', $examInfo->student_code)
            ->where('exam_settings.ac_year_id', '=', $examInfo->ac_year_id)
            ->where('exam_settings.ac_class_id', '=', $examInfo->ac_class_id)
            ->where('exam_settings.ac_section_id', '=', $examInfo->ac_section_id)
            ->where('exam_settings.ac_group_id', '=', $examInfo->ac_group_id)
            ->get();

        $banglaSubjects   = $markInfo->where('subject_relation', '=', 'bangla');
        $banglaTotalMarks = $banglaSubjects->sum('total_marks');
        $banglaStatus     = $banglaSubjects->where('letter_grade', '=', 'F');
        
        if(empty($banglaStatus)){
            $banglaMarks = $banglaTotalMarks / 2;
        }else{  
            $banglaMarks = 0;
        }
        $banglaMarks = self::calculateGradePoint($banglaMarks);

        $englishSubjects   = $markInfo->where('subject_relation', '=', 'english');
        $englishTotalMarks = $englishSubjects->sum('total_marks');
        $englisStatus      = $englishSubjects->where('letter_grade', '=', 'F');
        
        if(empty($englisStatus)){
            $englishMarks = $englishTotalMarks / 2;
        }else{  
            $englishMarks = 0;
        }
        
        $englishMarks = self::calculateGradePoint($englishMarks);


        $compulsorySubjects = $markInfo->where('subject_type', '=', 'compulsory')->where('subject_relation', '=', null);
        $optionalSubjects   = $markInfo->where('subject_type', '=', 'optional')->where('subject_relation', '=', null);


        $banglaHighestMarkList = DB::select("SELECT marks.student_code, IFNULL(SUM(marks.total_marks), 0) AS highest_marks FROM marks JOIN exam_settings ON marks.exam_setting_id=exam_settings.id WHERE exam_settings.ac_year_id='$examInfo->ac_year_id' AND exam_settings.ac_class_id='$examInfo->ac_class_id' AND exam_settings.ac_group_id='$examInfo->ac_group_id' AND exam_settings.ac_section_id='$examInfo->ac_section_id' AND exam_settings.subject_relation='bangla' GROUP BY marks.student_code ORDER BY highest_marks DESC LIMIT 1");
        $banglaHighestMark     = (!empty($banglaHighestMarkList) ? $banglaHighestMarkList[0]->highest_marks : 0);

        $englishHighestMarkList = DB::select("SELECT marks.student_code, IFNULL(SUM(marks.total_marks), 0) AS highest_marks FROM marks JOIN exam_settings ON marks.exam_setting_id=exam_settings.id WHERE exam_settings.ac_year_id='$examInfo->ac_year_id' AND exam_settings.ac_class_id='$examInfo->ac_class_id' AND exam_settings.ac_group_id='$examInfo->ac_group_id' AND exam_settings.ac_section_id='$examInfo->ac_section_id' AND exam_settings.subject_relation='english' GROUP BY marks.student_code ORDER BY highest_marks DESC LIMIT 1");
        $englishHighestMark     = (!empty($englishHighestMarkList) ? $englishHighestMarkList[0]->highest_marks : 0);

        $data = [
            'examInfo' => $examInfo,

            'banglaSubjects'    => $banglaSubjects,
            'banglaTotalMarks'  => $banglaTotalMarks,
            'banglaLetterGrade' => $banglaMarks->grade,
            'banglaGradePoint'  => $banglaMarks->point,
            'banglaHighestMark' => $banglaHighestMark,

            'englishSubjects'    => $englishSubjects,
            'englishTotalMarks'  => $englishTotalMarks,
            'englishLetterGrade' => $englishMarks->grade,
            'englishGradePoint'  => $englishMarks->point,
            'englishHighestMark' => $englishHighestMark,

            'compulsorySubjects' => $compulsorySubjects,
            'optionalSubjects'   => $optionalSubjects,
        ];

        return (object)$data;
    }

    public function calculateGradePoint($marks)
    {
        $items = [
            'grade' => 'F',
            'point' => '0.00'
        ];

        if ($marks >= 80) {
            $items['grade'] = 'A+';
            $items['point'] = '5.00';
        } elseif ($marks >= 70) {
            $items['grade'] = 'A';
            $items['point'] = '4.00';
        } elseif ($marks >= 60) {
            $items['grade'] = 'A-';
            $items['point'] = '3.50';
        } elseif ($marks >= 50) {
            $items['grade'] = 'B';
            $items['point'] = '3.00';
        } elseif ($marks >= 40) {
            $items['grade'] = 'C';
            $items['point'] = '2.00';
        } elseif ($marks >= 33) {
            $items['grade'] = 'D';
            $items['point'] = '1.00';
        }
        return (object)$items;
    }
}

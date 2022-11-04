<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ExamSettings extends Model
{
    use HasFactory, SoftDeletes;


    public function examList($request){

        $results = DB::table('exam_settings')
            ->join('exams', 'exam_settings.exam_id', '=', 'exams.id')
            ->leftJoin('ac_years', 'exam_settings.ac_year_id', '=', 'ac_years.id')
            ->leftJoin('ac_classes', 'exam_settings.ac_class_id', '=', 'ac_classes.id')
            ->leftJoin('ac_groups', 'exam_settings.ac_group_id', '=', 'ac_groups.id')
            ->leftJoin('ac_sections', 'exam_settings.ac_section_id', '=', 'ac_sections.id')
            ->leftJoin('subjects', 'exam_settings.subject_id', '=', 'subjects.id')
            ->select('exam_settings.*', 'exams.exam_name', 'ac_years.year', 'ac_classes.class', 'ac_groups.group', 'ac_sections.section', 'subjects.subject_name', 'subjects.subject_code')
            ->where('exam_settings.deleted_at', '=', null)
            ->get();

        return $results;
    }
}

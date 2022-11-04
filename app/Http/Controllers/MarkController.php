<?php

namespace App\Http\Controllers;

use App\Models\AcClass;
use App\Models\AcGroup;
use App\Models\AcSection;
use App\Models\AcYear;
use App\Models\Exam;
use App\Models\ExamSettings;
use App\Models\Mark;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->data['examList']    = Exam::all();
        $this->data['yearList']    = AcYear::all();
        $this->data['classList']   = AcClass::all();
        $this->data['groupList']   = AcGroup::all();
        $this->data['sectionList'] = AcSection::all();

        $this->data['menu'] = 'marks';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->data['submenu'] = 'marksList';

        $this->data['results'] = Mark::studentList($request);

        return view('marks.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['submenu'] = 'marksCreate';

        return view('marks.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!empty($request->student_code)) {
            foreach ($request->student_code as $key => $studentCode) {

                $where = [
                    ['exam_setting_id', '=', $request->exam_setting_id],
                    ['student_code', '=', $studentCode]
                ];

                if (Mark::where($where)->first()) {
                    $data = Mark::where($where)->first();
                } else {
                    $data = new Mark;
                }

                $data->created         = date('Y-m-d');
                $data->exam_setting_id = $request->exam_setting_id;
                $data->student_code    = $studentCode;
                $data->subjective_mark = $request->subjective_mark[$key];
                $data->objective_mark  = $request->objective_mark[$key];
                $data->practical_mark  = $request->practical_mark[$key];
                $data->total_marks     = $request->total_marks[$key];
                $data->grade_point     = $request->grade_point[$key];
                $data->letter_grade    = $request->letter_grade[$key];

                $data->save();
            }
        }

        return redirect()->route('admin.marks.create')->with(['success' => 'Marks add successful.']);
    }

    /**
     * Show exam info
     */
    public function show($id)
    {
        $this->data['results'] = Mark::progressReport($id);

        $pdf = Pdf::loadView('marks.show', $this->data);

        return $pdf->stream('invoice.pdf');
    }

    /**
     * Show exam info
     */
    public function download($id)
    {
        $this->data['results'] = Mark::progressReport($id);

        $pdf = Pdf::loadView('marks.show', $this->data);

        return $pdf->download('progress-report.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Mark::find($id);
        $data->delete();

        return redirect()->route('admin.marks')->with(['danger' => 'Exam Settings delete successful.']);
    }
}

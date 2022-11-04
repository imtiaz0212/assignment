<?php

namespace App\Http\Controllers;

use App\Models\AcClass;
use App\Models\AcGroup;
use App\Models\AcSection;
use App\Models\AcYear;
use App\Models\Exam;
use App\Models\ExamSettings;
use Illuminate\Http\Request;

class ExamSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->data['examList']    = Exam::all();
        $this->data['yearList']    = AcYear::all();
        $this->data['classList']   = AcClass::all();
        $this->data['groupList']   = AcGroup::all();
        $this->data['sectionList'] = AcSection::all();

        $this->data['menu'] = 'examSetting';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->data['submenu'] = 'examSettingList';

        $this->data['results'] = ExamSettings::examList($request);

        return view('examSetting.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['submenu'] = 'examSettingCreate';

        return view('examSetting.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $where = [
            ['exam_id', '=', $request->exam_id],
            ['ac_year_id', '=', $request->ac_year_id],
            ['ac_class_id', '=', $request->ac_class_id],
            ['ac_section_id', '=', $request->ac_section_id],
            ['ac_group_id', '=', $request->ac_group_id],
            ['subject_id', '=', $request->subject_id],
        ];

        if (ExamSettings::where($where)->first()) {
            $data = ExamSettings::where($where)->first();
        } else {
            $data = new ExamSettings;
        }

        $data->created              = date('Y-m-d');
        $data->exam_id              = $request->exam_id;
        $data->ac_year_id           = $request->ac_year_id;
        $data->ac_class_id          = $request->ac_class_id;
        $data->ac_section_id        = $request->ac_section_id;
        $data->ac_group_id          = $request->ac_group_id;
        $data->subject_id           = $request->subject_id;
        $data->subjective           = $request->subjective;
        $data->subjective_pass_mark = $request->subjective_pass_mark;
        $data->objective            = $request->objective;
        $data->objective_pass_mark  = $request->objective_pass_mark;
        $data->practical            = $request->practical;
        $data->practical_pass_mark  = $request->practical_pass_mark;
        $data->exam_marks           = $request->exam_marks;
        $data->attendance           = (!empty($request->attendance) ? 1 : 0);

        $data->save();

        return redirect()->route('admin.exam-setting.create')->with(['success' => 'Exam Settings successful.']);
    }

    /**
     * Show exam info
     */
    public function show($id)
    {

    }

    /**
     * Show edit form
     */
    public function edit($id)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = ExamSettings::find($id);
        $data->delete();

        return redirect()->route('admin.exam-setting')->with(['danger' => 'Exam Settings delete successful.']);
    }
}

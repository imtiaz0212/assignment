@extends('layouts.backend')

@section('content')

    @php($menu = (!empty($menu) ? $menu : ''))
    @php($submenu = (!empty($submenu) ? $submenu : ''))

    @include('marks.nav')

    <div class="container-fluid" ng-controller="appController" ng-cloak>
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header border-bottom">
                        <div class="float-left">
                            <h4>Add New</h4>
                        </div>

                        {{--<div class="float-right mt-2">
                            <span class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Add New
                            </span>
                        </div>--}}
                    </div>

                    <div class="widget-content widget-content-area simple-pills">
                        <form action="{{route('admin.marks.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="ac_year_id" ng-model="acYearId" class="form-control select2"
                                                required>
                                            <option value="" selected>Academic Year (*)</option>
                                            @if($yearList->isNotEmpty())
                                                @foreach($yearList as $row)
                                                    <option value="{{$row->id}}">{{$row->year}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="ac_class_id" ng-model="acClassId" ng-change="getSubjectList()"
                                                class="form-control select2" required>
                                            <option value="" selected>Class (*)</option>
                                            @if($classList->isNotEmpty())
                                                @foreach($classList as $row)
                                                    <option value="{{$row->id}}">{{$row->class}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="ac_section_id" ng-model="acSectionId" class="form-control select2"
                                                required>
                                            <option value="" selected>Section (*)</option>
                                            @if($sectionList->isNotEmpty())
                                                @foreach($sectionList as $row)
                                                    <option value="{{$row->id}}">{{$row->section}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="exam_id" ng-model="examId" class="form-control select2" required>
                                            <option value="" selected>Exam (*)</option>
                                            @if($examList->isNotEmpty())
                                                @foreach($examList as $row)
                                                    <option value="{{$row->id}}">{{$row->exam_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="ac_group_id" ng-model="acGroupId" ng-change="getSubjectList()"
                                                class="form-control select2" required>
                                            <option value="" selected>Group (*)</option>
                                            @if($groupList->isNotEmpty())
                                                @foreach($groupList as $row)
                                                    <option value="{{$row->id}}">{{$row->group}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="subject_id" ng-model="subjectId"
                                                ng-change="getStudentList(subjectId)" class="form-control select2"
                                                required>
                                            <option value="" selected>Select Subject (*)</option>
                                            <option ng-repeat="row in subjectList" value="@{{row.id}}">
                                                @{{row.subject_code}} - @{{row.subject_name}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="exam_setting_id" ng-value="examInfo.id">


                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th rowspan="2">Student ID</th>
                                        <th rowspan="2">Roll</th>
                                        <th rowspan="2">Student Name</th>
                                        <th rowspan="2" style="width: 10%;">Obtained Marks</th>
                                        <th colspan="3" class="text-center" style="width: 25%;">Marks</th>
                                    </tr>
                                    <tr>
                                        <th>Subjective</th>
                                        <th>Objective</th>
                                        <th>Practical</th>
                                    </tr>

                                    <tr ng-repeat="row in studentList">

                                        <input type="hidden" name="student_code[]" ng-value="row.student_code">
                                        <input type="hidden" name="total_marks[]" ng-value="row.total_marks">
                                        <input type="hidden" name="grade_point[]" ng-value="row.grade_point">
                                        <input type="hidden" name="letter_grade[]" ng-value="row.letter_grade">

                                        <td>@{{ row.student_code }}</td>
                                        <td>@{{ row.roll }}</td>
                                        <td>@{{ row.student_name }}</td>
                                        <td>@{{ getTotalMarks($index) }}</td>
                                        <td>
                                            <input type="number" name="subjective_mark[]" ng-model="row.subjective_mark" ng-change="getTotalMarks(index)" min="0" max="@{{ examInfo.subjective }}"  placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" name="objective_mark[]" ng-model="row.objective_mark" ng-change="getTotalMarks(index)" min="0" max="@{{ examInfo.objective }}" placeholder="0">
                                        </td>
                                        <td>
                                            <input type="number" name="practical_mark[]" ng-model="row.practical_mark" ng-change="getTotalMarks(index)" min="0" max="@{{ examInfo.practical }}" placeholder="0">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-12 text-right">
                                    <input type="submit" name="save" value="Save" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        app.controller("appController", function ($scope, $http) {

            $scope.subjectList = [];
            $scope.getSubjectList = function () {

                $scope.subjectList = [];

                let classId = $scope.acClassId;
                let groupId = $scope.acGroupId;

                if (typeof classId !== 'undefined' && typeof groupId !== 'undefined') {

                    $http({
                        method: "POST",
                        url: "{{route('admin.ajax.subject-list')}}",
                        data: {
                            _token: '{{csrf_token()}}',
                            ac_class_id: classId,
                            ac_group_id: groupId,
                        }
                    }).then(function (response) {
                        let data = response.data;
                        $scope.subjectList = data;
                    });
                }
            }

            $scope.examInfo = '';
            $scope.studentList = [];
            $scope.getStudentList = function (subjectId) {

                $scope.examInfo = '';
                $scope.studentList = [];

                let examId = $scope.examId;
                let yearId = $scope.acYearId;
                let classId = $scope.acClassId;
                let sectionId = $scope.acSectionId;
                let groupId = $scope.acGroupId;

                if (typeof examId !== 'undefined' && typeof subjectId !== 'undefined' && typeof yearId !== 'undefined' && typeof classId !== 'undefined' && typeof sectionId !== 'undefined' && typeof groupId !== 'undefined') {

                    $http({
                        method: "POST",
                        url: "{{route('admin.ajax.marks-entry-list')}}",
                        data: {
                            _token: '{{csrf_token()}}',
                            exam_id: examId,
                            ac_year_id: yearId,
                            ac_class_id: classId,
                            ac_section_id: sectionId,
                            ac_group_id: groupId,
                            subject_id: subjectId,
                        }
                    }).then(function (response) {
                        let data = response.data;
                        $scope.examInfo = data.examInfo;
                        $scope.studentList = data.studentList;
                    });

                }
            }

            $scope.getTotalMarks = function (index) {

                if(typeof $scope.studentList[index] !== "undefined"){

                let subjective = !isNaN(parseFloat($scope.studentList[index].subjective_mark)) ? parseFloat($scope.studentList[index].subjective_mark) : 0;
                let objective = !isNaN(parseFloat($scope.studentList[index].objective_mark)) ? parseFloat($scope.studentList[index].objective_mark) : 0;
                let practical = !isNaN(parseFloat($scope.studentList[index].practical_mark)) ? parseFloat($scope.studentList[index].practical_mark) : 0;

                let totalMarks = subjective + objective + practical;
                $scope.studentList[index].total_marks = totalMarks;


                let subjectivePassMark = !isNaN(parseFloat($scope.examInfo.subjective_pass_mark)) ? parseFloat($scope.examInfo.subjective_pass_mark) : 0;
                let objectivePassMark = !isNaN(parseFloat($scope.examInfo.objective_pass_mark)) ? parseFloat($scope.examInfo.objective_pass_mark) : 0;
                let practicalPassMark = !isNaN(parseFloat($scope.examInfo.practical_pass_mark)) ? parseFloat($scope.examInfo.practical_pass_mark) : 0;

                var gradePoint = '0.00';
                var letterGrade = 'F';
                if(subjectivePassMark > 0 && objectivePassMark > 0 && practicalPassMark > 0){
                    if(subjective >= subjectivePassMark && objective >= objectivePassMark && practical >= practicalPassMark){
                        let gradeInfo = calculateGradePoint(parseFloat($scope.studentList[index].total_marks));
                        gradePoint = gradeInfo.point;
                        letterGrade = gradeInfo.grade;
                    }
                }else if(subjectivePassMark > 0 && objectivePassMark > 0){
                    if(subjective >= subjectivePassMark && objective >= objectivePassMark){
                        let gradeInfo = calculateGradePoint(parseFloat($scope.studentList[index].total_marks));
                        gradePoint = gradeInfo.point;
                        letterGrade = gradeInfo.grade;
                    }
                }else if(subjectivePassMark > 0 && practicalPassMark > 0){
                    if(subjective >= subjectivePassMark && practical >= practicalPassMark){
                        let gradeInfo = calculateGradePoint(parseFloat($scope.studentList[index].total_marks));
                        gradePoint = gradeInfo.point;
                        letterGrade = gradeInfo.grade;
                    }
                }

                $scope.studentList[index].grade_point = gradePoint
                $scope.studentList[index].letter_grade = letterGrade
                return totalMarks;
                }
            }

            function calculateGradePoint(marks){
                let items = {
                    grade: 'F',
                    point: '0.00'
                };
                if(marks >= 80){
                    items.grade = 'A+';
                    items.point = '5.00';
                }else if(marks >= 70){
                    items.grade = 'A';
                    items.point = '4.00';
                }else if(marks >= 60){
                    items.grade = 'A-';
                    items.point = '3.50';
                }else if(marks >= 50){
                    items.grade = 'B';
                    items.point = '3.00';
                }else if(marks >= 40){
                    items.grade = 'C';
                    items.point = '2.00';
                }else if(marks >= 33){
                    items.grade = 'D';
                    items.point = '1.00';
                }

                return items;
            }
        });
    </script>
@endpush


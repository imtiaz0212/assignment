@extends('layouts.backend')

@section('content')

    @php($menu = (!empty($menu) ? $menu : ''))
    @php($submenu = (!empty($submenu) ? $submenu : ''))

    @include('examSetting.nav')

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
                        <form action="{{route('admin.exam-setting.store')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Academic Year</label>
                                <div class="col-lg-6">
                                    <select name="ac_year_id" ng-model="acYearId" class="form-control select2" required>
                                        <option value="" selected>Select Year (*)</option>
                                        @if($yearList->isNotEmpty())
                                            @foreach($yearList as $row)
                                                <option value="{{$row->id}}">{{$row->year}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Class</label>
                                <div class="col-lg-6">
                                    <select name="ac_class_id" ng-model="acClassId" ng-change="getSubjectList()" class="form-control select2" required>
                                        <option value="" selected>Select Class (*)</option>
                                        @if($classList->isNotEmpty())
                                            @foreach($classList as $row)
                                                <option value="{{$row->id}}">{{$row->class}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Section</label>
                                <div class="col-lg-6">
                                    <select name="ac_section_id" ng-model="acSectionId" class="form-control select2" required>
                                        <option value="" selected>Select Section (*)</option>
                                        @if($sectionList->isNotEmpty())
                                            @foreach($sectionList as $row)
                                                <option value="{{$row->id}}">{{$row->section}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Exam Type</label>
                                <div class="col-lg-6">
                                    <select name="exam_id" ng-model="examId" class="form-control select2" required>
                                        <option value="" selected>Select Section (*)</option>
                                        @if($examList->isNotEmpty())
                                            @foreach($examList as $row)
                                                <option value="{{$row->id}}">{{$row->exam_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Class Group</label>
                                <div class="col-lg-6">
                                    <select name="ac_group_id" ng-model="acGroupId" ng-change="getSubjectList()" class="form-control select2" required>
                                        <option value="" selected>Select Section (*)</option>
                                        @if($groupList->isNotEmpty())
                                            @foreach($groupList as $row)
                                                <option value="{{$row->id}}">{{$row->group}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Subject</label>
                                <div class="col-lg-6">
                                    <select name="subject_id" ng-model="subjectId" ng-change="getSubjectInfo(subjectId)" class="form-control select2" required>
                                        <option value="" selected>Select Subject (*)</option>
                                        <option ng-repeat="row in subjectList" value="@{{row.id}}">@{{row.subject_code}} - @{{row.subject_name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-lg-3 col-form-label text-lg-right">Marks Configuration</label>
                                <div class="col-lg-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Subjective:</th>
                                            <th><input type="number" name="subjective" ng-model="subjective"  value="0"></th>
                                            <th>Pass Marks: </th>
                                            <th><input type="number" name="subjective_pass_mark" ng-model="subjectivePassMark" value="0"></th>
                                        </tr>
                                        <tr>
                                            <th>Objective:</th>
                                            <th><input type="number" name="objective" ng-model="objective" value="0"></th>
                                            <th>Pass Marks: </th>
                                            <th><input type="number" name="objective_pass_mark" ng-model="objectivePassMark" value="0"></th>
                                        </tr>
                                        <tr>
                                            <th>Practical:</th>
                                            <th><input type="number" name="practical" ng-model="practical" value="0"></th>
                                            <th>Pass Marks: </th>
                                            <th><input type="number" name="practical_pass_mark" ng-model="practicalPassMark" value="0"></th>
                                        </tr>
                                        <tr>
                                            <th>Exam Marks:</th>
                                            <th>
                                                @{{ examMarks }}
                                                <input type="hidden" name="exam_marks" ng-value="getExamMarks()" max="100" readonly></th>
                                            <th>Attendance:</th>
                                            <th><input type="checkbox" name="attendance" checked value="1"></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-9 text-right">
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
        app.controller("appController", function($scope, $http) {

            $scope.subjectList = [];
            $scope.getSubjectList = function(){

                $scope.subjectList = [];

                let classId = $scope.acClassId;
                let groupId = $scope.acGroupId;

                if(typeof classId !== 'undefined' && typeof groupId !== 'undefined'){

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

            $scope.subjective = 0;
            $scope.subjectivePassMark = 0;
            $scope.objective = 0;
            $scope.objectivePassMark = 0;
            $scope.practical = 0;
            $scope.practicalPassMark = 0;
            $scope.getSubjectInfo = function(subjectId){

                let examId = $scope.examId;
                let yearId = $scope.acYearId;
                let classId = $scope.acClassId;
                let sectionId = $scope.acSectionId;
                let groupId = $scope.acGroupId;

                if(typeof examId !== 'undefined' && typeof subjectId !== 'undefined' && typeof yearId !== 'undefined' && typeof classId !== 'undefined' && typeof sectionId !== 'undefined' && typeof groupId !== 'undefined'){

                    $http({
                        method: "POST",
                        url: "{{route('admin.ajax.subject-info')}}",
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
                        $scope.subjective = data.subjective;
                        $scope.subjectivePassMark = data.subjective_pass_mark;
                        $scope.objective = data.objective;
                        $scope.objectivePassMark = data.objective_pass_mark;
                        $scope.practical = data.practical;
                        $scope.practicalPassMark = data.practical_pass_mark;
                    });

                }
            }

            $scope.examMarks = 0;
            $scope.getExamMarks = function () {
                let subjective = !isNaN(parseFloat($scope.subjective)) ? parseFloat($scope.subjective) : 0;
                let objective = !isNaN(parseFloat($scope.objective)) ? parseFloat($scope.objective) : 0;
                let practical = !isNaN(parseFloat($scope.practical)) ? parseFloat($scope.practical) : 0;

                let examMarks = subjective + objective + practical;
                $scope.examMarks = examMarks;
                return examMarks;
            }
        });
    </script>
@endpush


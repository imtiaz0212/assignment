@extends('layouts.backend')

@section('content')

    @php($menu = (!empty($menu) ? $menu : ''))
    @php($submenu = (!empty($submenu) ? $submenu : ''))
    @include('examSetting.nav')

    <div class="container-fluid">
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header border-bottom">
                        <div class="float-left">
                            <h4>View All</h4>
                        </div>

                        {{--<div class="float-right mt-2">
                            <span class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Add New
                            </span>
                        </div>--}}
                    </div>

                    <div class="widget-content widget-content-area simple-pills">
                        <table id="dataTable" class="table dt-table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th width="40px">SL</th>
                                <th>Created</th>
                                <th>Academic Year</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Group</th>
                                <th>Exam Name</th>
                                <th>Subject</th>
                                <th>Sub.</th>
                                <th>Obj.</th>
                                <th>Prac.</th>
                                <th>Exam Marks</th>
                                <th width="80px" class="no-content">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($results))
                                @foreach($results as $key => $row)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{$row->created}}</td>
                                        <td>{{$row->year}}</td>
                                        <td>{{$row->class}}</td>
                                        <td>{{$row->section}}</td>
                                        <td>{{$row->group}}</td>
                                        <td>{{$row->exam_name}}</td>
                                        <td>{{$row->subject_code .' - '. $row->subject_name}}</td>
                                        <td>{{$row->subjective}}</td>
                                        <td>{{$row->objective}}</td>
                                        <td>{{$row->practical}}</td>
                                        <td>{{$row->exam_marks}}</td>
                                        <td class="text-center">
                                            <a title="Delete"
                                               href="{{route('admin.exam-setting.destroy', $row->id)}}"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Do you want to delete this data?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round" class="feather feather-trash-2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('styles')
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/plugins/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL STYLES -->
@endpush


@push('scripts')
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{asset('backend')}}/plugins/table/datatable/datatables.js"></script>
    <script>
        $('#dataTable').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [[25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, 'All']],
        });
    </script>
@endpush


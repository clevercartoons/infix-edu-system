@extends('backEnd.master')
@section('title')
    @lang('exam.exam_setup')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('exam.exam_setup')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examinations')</a>
                    <a href="#">@lang('exam.exam_setup')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if (isset($exam))
                @if (userPermission(215))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{ route('exam') }}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif

            <div class="row">

                <div class="col-lg-3">
                    @if (isset($exam))
                        {{ Form::open(['class' => 'form-horizontal', 'route' => ['exam-update', $exam->id], 'method' => 'PUT']) }}
                    @else
                        @if (userPermission(215))
                            {{ Form::open(['class' => 'form-horizontal', 'route' => 'exam', 'method' => 'POST']) }}
                        @endif
                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">
                                    @if (isset($exam))
                                        @lang('exam.edit_exam')
                                    @else
                                        @lang('exam.add_exam')
                                    @endif
                                </h3>
                            </div>
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12" id="error-message">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-12">
                                            <select
                                                class="w-100 bb niceSelect1 form-control {{ $errors->has('exam_system') ? ' is-invalid' : '' }}"
                                                id="exam_system" name="exam_system">
                                                <option data-display="@lang('common.exam_system') *" value="">@lang('common.exam_system')
                                                    *</option>
                                                <option value="single">@lang('common.single_exam')</option>
                                                <option value="multi">@lang('common.multi_exam')</option>
                                            </select>
                                            @if ($errors->has('exam_system'))
                                                <span class="invalid-feedback invalid-select" role="alert">
                                                    <strong>{{ $errors->first('exam_system') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{--  Exam Div  --}}
                                    <div class="exam_view_div" id="exam_view_div"></div>
                                    {{--  exam end --}}



                                    <div class="row mt-40">
                                        <div class="col-lg-12">
                                            <div class="input-effect">
                                                <input oninput="numberMinCheck(this)"
                                                    class="primary-input form-control{{ $errors->has('exam_marks') ? ' is-invalid' : '' }}"
                                                    type="text" name="exam_marks" id="exam_mark_main" autocomplete="off"
                                                    onkeypress="return isNumberKey(event)"
                                                    value="{{ isset($exam) ? $exam->exam_mark : 0 }}" required="">
                                                <label>@lang('exam.exam_mark') *</label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('exam_marks'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('exam_marks') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if (@generalSetting()->result_type == 'mark')
                                        <div class="row mt-40">
                                            <div class="col-lg-12">
                                                <div class="input-effect">
                                                    <input
                                                        class="primary-input form-control{{ $errors->has('pass_mark') ? ' is-invalid' : '' }}"
                                                        type="text" name="pass_mark" id="exam_mark_main"
                                                        autocomplete="off" onkeypress="return isNumberKey(event)"
                                                        value="{{ isset($exam) ? $exam->pass_mark : 0 }}" required="">
                                                    <label>@lang('exam.pass_mark') *</label>
                                                    <span class="focus-border"></span>
                                                    @if ($errors->has('pass_mark'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('pass_mark') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box mt-10">
                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="main-title">
                                            <h5>@lang('exam.add_mark_distributions') </h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" class="primary-btn icon-only fix-gr-bg"
                                            onclick="addRowMark();" id="addRowBtn">
                                            <span class="ti-plus pr-2"></span></button>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table" id="productTable">
                                        <thead>
                                            <tr>
                                                <th>@lang('exam.exam_title')</th>
                                                <th>@lang('exam.exam_mark')</th>
                                                @if (@generalSetting()->result_type == 'mark')
                                                    <th>@lang('exam.pass_mark')</th>
                                                @endif
                                                <th>@lang('common.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr id="row1" class="mt-40">
                                                <td class="border-top-0">
                                                    <input type="hidden" name="url" id="url"
                                                        value="{{ URL::to('/') }}">
                                                    <div class="input-effect">
                                                        <input type="hidden" value="@lang('exam.title')" id="lang">
                                                        <input
                                                            class="primary-input form-control{{ $errors->has('exam_title') ? ' is-invalid' : '' }}"
                                                            type="text" id="exam_title" name="exam_title[]"
                                                            autocomplete="off"
                                                            value="{{ isset($editData) ? $editData->exam_title : '' }}">
                                                        <label>@lang('exam.title')</label>
                                                    </div>
                                                </td>
                                                <td class="border-top-0">
                                                    <div class="input-effect">
                                                        <input oninput="numberCheck(this)"
                                                            class="primary-input form-control{{ $errors->has('exam_mark') ? ' is-invalid' : '' }} exam_mark"
                                                            type="text" id="exam_mark" name="exam_mark[]"
                                                            autocomplete="off" onkeypress="return isNumberKey(event)"
                                                            value="{{ isset($editData) ? $editData->exam_mark : 0 }}">
                                                    </div>
                                                </td>
                                                <td class="border-0">
                                                    <button class="primary-btn icon-only fix-gr-bg" type="button">
                                                        <span class="ti-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>



                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="border-top-0">@lang('exam.total')</td>
                                                <td class="border-top-0" id="totalMark">
                                                    <input type="text" class="primary-input form-control"
                                                        name="totalMark" readonly="true">
                                                </td>
                                                <td class="border-top-0"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-25" id="exam_shedule">
                        <div class="col-lg-12">
                            <div class="white-box mt-10">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-title">
                                            <h5>@lang('exam.exam_schedule_create') </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <select
                                            class="w-100 bb niceSelect1 form-control {{ $errors->has('teacher_id') ? ' is-invalid' : '' }}"
                                            id="" name="teacher_id">
                                            <option data-display="@lang('common.select_teacher') *" value="">@lang('common.select_teacher')
                                                *</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ @$teacher->id }}">{{ @$teacher->full_name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('teacher_id'))
                                            <span class="invalid-feedback invalid-select" role="alert">
                                                <strong>{{ $errors->first('teacher_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="input-effect">
                                                    <input
                                                        class="primary-input date form-control{{ @$errors->has('date') ? ' is-invalid' : '' }}"
                                                        id="startDate" type="text" name="date"
                                                        value="{{ date('m/d/Y') }}" autocomplete="off" required>
                                                    <span class="focus-border"></span>
                                                    <label>@lang('common.date') <span></span></label>
                                                    @if ($errors->has('date'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ @$errors->first('date') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="" type="button">
                                                    <i class="ti-calendar" id="start-date-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="input-effect">
                                                    <input
                                                        class="primary-input time   form-control{{ @$errors->has('start_time') ? ' is-invalid' : '' }}"
                                                        type="text" name="start_time" value="">
                                                    <label style="top: -13;">@lang('academics.start_time') *</label>
                                                    <span class="focus-border"></span>
                                                    <span class="text-danger start_time_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="" type="button">
                                                    <i class="ti-timer"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12">
                                        <div class="row no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="input-effect">
                                                    <input
                                                        class="primary-input time  form-control{{ @$errors->has('end_time') ? ' is-invalid' : '' }}"
                                                        type="text" name="end_time" value="">
                                                    <label style="top: -13;">@lang('exam.end_time') *</label>
                                                    <span class="focus-border"></span>
                                                    <span class="text-danger end_time_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button class="" type="button">
                                                    <i class="ti-timer"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25 mb-20">
                                    <div class="col-lg-12 mt-30-md">
                                        <select class="niceSelect1 w-100 bb form-control" name="room" id="room">
                                            <option data-display="@lang('common.select_room') *" value="">@lang('common.select_room')
                                                *</option>
                                            @foreach ($rooms as $room)
                                                <option value="{{ @$room->id }}"
                                                    {{ isset($routine) ? ($routine->room_id == $room->id ? 'selected' : '') : '' }}>
                                                    {{ @$room->room_no }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" role="alert" id="room_error"></span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @php
                        $tooltip = '';
                        if (userPermission(215)) {
                            $tooltip = '';
                        } else {
                            $tooltip = 'You have no permission to add';
                        }
                    @endphp
                    <div class="row mt-40">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                            title="{{ @$tooltip }}">
                                            <span class="ti-check"></span>
                                            @if (isset($exam))
                                                @lang('common.update')
                                            @else
                                                @lang('common.save')
                                            @endif

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>


                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('exam.exam_list')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('exam.exam_title')</th>
                                        @if (moduleStatusCheck('University'))
                                            <th>@lang('common.session')</th>
                                            <th>@lang('university::un.faculty_department')</th>
                                            <th>@lang('common.academic_year')</th>
                                            <th>@lang('university::un.semester')</th>
                                        @else
                                            <th>@lang('common.class')</th>
                                            <th>@lang('common.section')</th>
                                        @endif
                                        <th>@lang('exam.subject')</th>
                                        <th>@lang('exam.total_mark')</th>
                                        @if (@generalSetting()->result_type == 'mark')
                                            <th>@lang('exam.pass_mark')</th>
                                        @endif
                                        <th>@lang('exam.mark_distribution')</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $count =1 ; @endphp
                                    @foreach ($exams as $exam)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $exam->GetExamTitle != '' ? $exam->GetExamTitle->title : '' }}</td>
                                            @if (moduleStatusCheck('University'))
                                                <td>{{ $exam->sessionDetails->name }}</td>
                                                <td>{{ $exam->facultyDetails->name . '(' . $exam->departmentDetails->name . ')' }}
                                                </td>
                                                <td>{{ $exam->academicYearDetails->name }}</td>
                                                <td>{{ $exam->semesterDetails->name }}</td>
                                            @else
                                                <td>{{ $exam->class != '' ? $exam->class->class_name : '' }}</td>
                                                <td>{{ $exam->section != '' ? $exam->section->section_name : '' }}</td>
                                            @endif
                                            <td>{{ $exam->subject != '' ? $exam->subject->subject_name : '' }}</td>
                                            <td>{{ $exam->exam_mark }}</td>
                                            @if (@generalSetting()->result_type == 'mark')
                                                <td>{{ $exam->pass_mark }}</td>
                                            @endif
                                            <td>
                                                @foreach ($exam->markDistributions as $row)
                                                    <div class="row">
                                                        <div class="col-sm-6"> {{ $row->exam_title }} </div>
                                                        <div class="col-sm-4"><strong> {{ $row->exam_mark }} </strong>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        @lang('common.select')
                                                    </button>
                                                    @if ($exam->markRegistered == '')
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if (userPermission(397))
                                                                <a class="dropdown-item"
                                                                    href="{{ route('exam-edit', $exam->id) }}">@lang('common.edit')</a>
                                                            @endif

                                                            @if (userPermission(216))
                                                                <a class="dropdown-item" data-toggle="modal"
                                                                    data-target="#deleteExamModal{{ $exam->id }}"
                                                                    href="#">@lang('common.delete')</a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade admin-query" id="deleteExamModal{{ $exam->id }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">@lang('exam.delete_exam')</h4>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                        </div>
                                                        <div class="mt-40 d-flex justify-content-between">
                                                            <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('common.cancel')</button>
                                                            {{ Form::open(['route' => ['exam-delete', $exam->id], 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                            <button class="primary-btn fix-gr-bg"
                                                                type="submit">@lang('common.delete')</button>
                                                            {{ Form::close() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        // $(document ).ready(function() {
        //     $("#multi_exam_div").css("display", "none");
        //     $("#single_exam_div").css("display", "block"); 
        //     $("#exam_shedule").css("display", "block"); 
        // });

        // $('#exam_system').on('change', function() {
        //     var selected_val = this.value;
        //     if(selected_val == "single"){
        //         $("#single_exam_div").css("display", "block");
        //         $("#exam_shedule").css("display", "block"); 
        //         $("#multi_exam_div").css("display", "none");
        //     }
        //     else{
        //         $("#multi_exam_div").css("display", "block");
        //         $("#single_exam_div").css("display", "none"); 
        //         $("#exam_shedule").css("display", "none"); 
        //     }
        // });
    </script>

    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#exam_shedule").css("display", "none");
            if ($(".niceSelect1").length) {
                $(".niceSelect1").niceSelect();
            }
            $('#exam_system').on('change', function() {

                var selected_val = this.value;
                $('body').find('#exam_view_div').empty();
                if (selected_val == "single") {
                    $("#exam_shedule").css("display", "block");
                } else {
                    $("#exam_shedule").css("display", "none");
                }

                $.ajax({
                    type: "get",
                    url: "{{ url('return_exam_view') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        code: selected_val
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('body').find('#exam_view_div').append(data.html);
                        } else {
                            toastr.error("Operation Failed");
                        }
                    },
                });


            });

        });
    </script>
@endpush

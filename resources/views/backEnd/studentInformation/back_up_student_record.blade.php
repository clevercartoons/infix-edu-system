@extends('backEnd.master')

@section('title') 
{{__('student.delete_student_record')}}
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>{{__('student.deleted_student_record')}}</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.student_information')</a>
                <a href="#">{{__('student.deleted_student_record')}}</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor full_wide_table">
    <div class="container-fluid p-0">
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">
                                <thead>
                                  
                                    <tr>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.roll_no')</th>
                                        <th>@lang('student.name')</th>
                                        <th>@lang('common.class_sec')</th>
                                        @if(generalSetting()->with_guardian)
                                        <th>@lang('student.father_name')</th>
                                        @endif
                                        <th>@lang('common.date_of_birth')</th>
                                      
                                        <th>@lang('common.phone')</th>
                                        <th>@lang('common.actions')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($studentRecords as $record)
                                    <tr>
                                        <td>{{$record->studentDetail->admission_no}}</td>
                                        <td>{{$record->roll_no}}</td>
                                        <td>{{$record->studentDetail->first_name.' '.$record->studentDetail->last_name}}</td>
                                        <td>{{$record->class !=""? $record->class->class_name:""}} {{ $record->section ? '('.$record->section->section_name.')' : '' }}</td>
                                        @if(generalSetting()->with_guardian)
                                        <td>{{$record->studentDetail->parents !=""?$record->studentDetail->parents->fathers_name:""}}</td>
                                        @endif 
                                        <td  data-sort="{{strtotime($record->studentDetail->date_of_birth)}}" >
                                           {{$record->studentDetail->date_of_birth != ""? dateConvert($record->studentDetail->date_of_birth):''}} 
                                        </td>
                                     
                                     
                                        <td>{{$record->studentDetail->mobile}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{route('student-record-restore', [$record->id])}}"> <i class="fa-solid fa-rotate"></i>
                                                        @lang('common.restore')</a> 
                                                   
                                                    @if(userPermission(86))
                                                    <a onclick="deleteId({{$record->id}});" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteStudentModal" data-id="{{$record->id}}"  >@lang('common.delete forever')</a>
                                                    @endif
                                                    
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>

<div class="modal fade admin-query" id="deleteStudentModal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation Required</h4>
                {{-- <h4 class="modal-title">@lang('student.delete') @lang('student.student')</h4> --}}
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="text-center">
                    <h4 class="text-danger">You are going to remove {{@$record->first_name.' '.@$record->last_name}}. Removed data CANNOT be restored! Are you ABSOLUTELY Sure!</h4>
                </div>

                <div class="mt-40 d-flex justify-content-between">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                     {{ Form::open(['route' => 'delete-student-record-permanently', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                     <input type="hidden" name="id" value="" id="student_delete_i">  {{-- using js in main.js --}}
                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                     {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>





@endsection

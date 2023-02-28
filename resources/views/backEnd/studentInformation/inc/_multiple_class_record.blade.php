
@php
    $divButton = generalSetting()->multiple_roll == 1 ? 'col-3' : 'col-4';
@endphp
@foreach ($student->studentRecords as $record)
<div class="row mb-4 align-items-end" id="div_id_{{ $record->student_id.$record->id }}">
    <div class="{{ $divButton }}">
        <div class="input-effect">
            <select class="niceSelect w-100 bb classSelectClass class_{{ $record->student_id }} form-control{{ $errors->has('class') ? ' is-invalid' : '' }}"
                name="old_record[{{ $record->id }}][class][]">
                <option data-display="@lang('common.class') *" value="">
                    @lang('common.class') *</option>
                   @isset($classes)
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ $record->class_id == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                   @endisset
            </select>
            <div class="pull-right loader loader_style select_class_loader">
                <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
            </div>
            <span class="focus-border"></span>
            @if ($errors->has('class'))
                <span class="invalid-feedback invalid-select" role="alert">
                    <strong>{{ $errors->first('class') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="{{ $divButton }}">
        <div class="input-effect">
            <select class="niceSelect w-100 bb classSelectSection form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                name="old_record[{{ $record->id }}][section][]" id="sectionSelectStudent">
                <option data-display="@lang('common.section') *" value="">
                    @lang('common.section') *</option>
                    @isset($record)
                        @if ($record->session_id && $record->class_id)
                            @foreach ($record->class->classSection as $section)
                                <option value="{{ $section->sectionName->id }}"
                                    {{ $record->section_id == $section->sectionName->id ? 'selected' : '' }}>
                                    {{ $section->sectionName->section_name }}</option>
                            @endforeach
                        @endif
                    @endisset
            </select>
            <div class="pull-right loader loader_style select_section_loader">
                <img class="loader_img_style" src="{{ asset('public/backEnd/img/demo_wait.gif') }}" alt="loader">
            </div>
            <span class="focus-border"></span>
            @if ($errors->has('section'))
                <span class="invalid-feedback invalid-select" role="alert">
                    <strong>{{ $errors->first('section') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="col-3">
        <input type="checkbox" name="old_record[{{ $record->id }}][default]" id="is_default_{{@$record->id}}" data-student_id="{{ $record->student_id }}"  data-row_id="{{ $record->id }}" class="common-checkbox is_default is_default_{{@$record->student_id}} form-control{{ @$errors->has('is_default') ? ' is-invalid' : '' }}" {{ $record->is_default ? 'checked':'' }}>
        <label class="mb-0" for="is_default_{{@$record->id}}">@lang('common.default')</label>

    </div>
    @if (generalSetting()->multiple_roll == 1)
        <div class="col-2">
            <div class="input-effect">
                <input oninput="numberCheck(this)" class="primary-input" type="text" id="roll_number"
                    name="old_record[{{ $record->id }}][roll_number][]" value="{{ old('roll_number') }}">
                <label>
                    {{ moduleStatusCheck('Lead') == true ? __('lead::lead.id_number') : __('student.roll') }}
                    @if (is_required('roll_number') == true)
                        <span> *</span>
                    @endif
                </label>
                <span class="focus-border"></span>
                <span class="text-danger" id="roll-error" role="alert">
                    <strong></strong>
                </span>
                @if ($errors->has('roll_number'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('roll_number') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    @endif
    <div class="col-1 text-left">
        <button class="primary-btn small fix-gr-bg icon-only removrButton" type="button" data-student_id="{{ $record->student_id }}" data-record_id={{ $record->id }}><i class="ti-trash"></i></button>
    </div>
</div>
@endforeach

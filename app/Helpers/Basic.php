<?php

use App\SmClass;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmCurrency;
use App\Models\Theme;
use App\SmClassSection;
use App\SmAssignSubject;
use App\Models\StudentRecord;

if (!function_exists('color_theme')) {
    function color_theme()
    {
        if (!auth()->check()) {
           return userColorThemeActive();
        } else if(auth()->user()) {  
            return userColorThemeActive(auth()->user()->id);
        }
        
    }

}

if (!function_exists('userColorThemeActive')) 
{
    function userColorThemeActive(int $user_id = null)  {

        $theme = Theme::with('colors')->where('is_default', 1)
        ->when($user_id, function ($q) use ($user_id) {
            $q->where('created_by', $user_id);
        })->first();
        if ($user_id && !$theme) {
            $theme = Theme::with('colors')->where('is_default', 1)->first();
        }

        if(!$theme) {
            $theme = Theme::with('colors')->first();
        }
        return $theme;
    }
}
if (!function_exists('userColorThemes')) 
{
    function userColorThemes(int $user_id = null)  {

        $themes = Theme::with('colors')
        ->when($user_id, function ($q) use ($user_id) {
            $q->where('created_by', $user_id);
        })->get();
        if ($user_id && !$themes) {
            $themes = Theme::with('colors')->where('is_system', 1)->get();
        }
        return $themes;
    }
}

if (!function_exists('activeStyle')) {
    function activeStyle()
    {
        if (session()->has('active_style')) {
            $active_style = session()->get('active_style');
            return $active_style;
        } else {
            $active_style = auth()->check() ? Theme::where('id', auth()->user()->style_id)->first() :
                Theme::where('school_id', 1)->where('is_default', 1)->first();

            if ($active_style == null) {
                $active_style = Theme::where('school_id', 1)->where('is_default', 1)->first();
            }
            
            session()->put('active_style', $active_style);
            return session()->get('active_style');
        }
    }
}

if(!function_exists('currency_format_list')) {
    function currency_format_list()
    {
        $symbol = generalSetting()->currency_symbol ?? '$';
        $code = generalSetting()->currency ?? 'USD';
        $formats = [
            [ 'name'=>'symbol_amount','format'=>'symbol(amount) =  '.$symbol.' 1'],
            ['name'=>'amount_symbol', 'format'=>'amount(symbol) = 1'.$symbol],
            ['name'=>'code_amount', 'format'=>'code(amount) = '.$code.' 1'],
            ['name'=>'amount_code', 'format'=>'amount(code) = 1 ' .$code],
        ];

        return $formats;
    }
}
if(!function_exists('currency_format')) {
    function currency_format($amount = null, string $format = null)
    {

        if(!$amount) return false; 

        $code = generalSetting()->currency ?? 'USD';
        
        $format = SmCurrency::where('code', $code)->where('school_id', generalSetting()->school_id)->first();
        
        if(!$format) return $amount;

        $decimal = $format->decimal_digit ?? 0;
        $decimal_separator = $format->decimal_separator ?? "";
        $thousands_separator = $format->thousand_separator ?? "";
        $amount = number_format($amount, $decimal, $decimal_separator, $thousands_separator);
        $symbolCode = $format->currency_type == 'C' ? $format->code : $format->symbol;
       
        $symbolCodeSpace = $format->space ? 
                            ($format->currency_position == 'S' ? $symbolCode.' ' : ' '. $symbolCode) : $symbolCode;
        
        if ($format->currency_position == 'S') {
            return $symbolCodeSpace . $amount;
        } elseif($format->currency_position == 'P') {
            return $amount . $symbolCodeSpace;
        }
    }
}
if(!function_exists('classes')) {
    function classes(int $academic_year = null)
    {
        return SmClass::withOutGlobalScopes()
        ->when($academic_year, function($q) use($academic_year){
            $q->where('academic_id', $academic_year);
        }, function($q){
            $q->where('academic_id', getAcademicId());
        })->where('school_id', auth()->user()->school_id)
        ->where('active_status', 1)->get();
    }
}
if(!function_exists('sections')) {
    function sections(int $class_id, int $academic_year = null)
    {
       return  SmClassSection::withOutGlobalScopes()->where('class_id', $class_id)
                            ->where('school_id', auth()->user()->school_id)
                            ->when($academic_year, function($q) use($academic_year){
                                $q->where('academic_id', $academic_year);
                            }, function($q){
                                $q->where('academic_id', getAcademicId());
                            })->groupBy(['class_id', 'section_id'])->get();

    }
}
if(!function_exists('subjects')) {
    function subjects(int $class_id, int $section_id, int $academic_year = null)
    {
         $subjects = SmAssignSubject::withOutGlobalScopes()
         ->where('class_id', $class_id)
         ->where('section_id', $section_id)
         ->where('school_id', auth()->user()->school_id)
         ->when($academic_year, function($q) use($academic_year){
            $q->where('academic_id', $academic_year);
        }, function($q){
            $q->where('academic_id', getAcademicId());
        })->groupBy(['class_id', 'section_id', 'subject_id'])->get(); 
        
        return $subjects;

    }
}
if(!function_exists('students')) {
    function students(int $class_id, int $section_id = null, int $academic_year = null)
    {
         $student_ids = StudentRecord::where('class_id', $class_id)
         ->when($section_id, function($q) use($section_id){
            $q->where('section_id', $section_id);
         })->when('academic_year', function($q) use($academic_year) {
            $q->where('academic_id', $academic_year);
         })->where('school_id', auth()->user()->school_id)->pluck('student_id')->unique()->toArray();

         $students = SmStudent::withOutGlobalScopes()->whereIn('id', $student_ids)->get();
        
        return $students;

    }
}
if(!function_exists('classSubjects')) {
    function classSubjects($class_id = null) {
        $subjects = SmAssignSubject::query();
        if (teacherAccess()) {
            $subjects->where('teacher_id', auth()->user()->staff->id) ;
        }
        if ($class_id !="all_class") {
            $subjects->where('class_id', '=', $class_id);
        } else {
            $subjects->groupBy('class_id');
        }
        $subjectIds = $subjects->groupBy('subject_id')->get()->pluck(['subject_id'])->toArray();        

        return SmSubject::whereIn('id', $subjectIds)->get(['id','subject_name']);
    }
}
if(!function_exists('subjectSections')) {
    function subjectSections($class_id = null, $subject_id =null) {
        if(!$class_id || !$subject_id) return null;
        $sectionIds = SmAssignSubject::where('class_id', $class_id)
        ->where('subject_id', '=', $subject_id)                         
        ->where('school_id', auth()->user()->school_id)
        ->when(teacherAccess(), function($q) {
            $q->where('teacher_id',auth()->user()->staff->id);
        })
        ->groupby(['class_id','section_id'])
        ->pluck('section_id')
        ->toArray();
        return SmSection::whereIn('id',$sectionIds)->get(['id','section_name']);

    }
}
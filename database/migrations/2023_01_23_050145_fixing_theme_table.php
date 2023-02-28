<?php

use App\Models\Theme;
use App\SmSchool;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixingThemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schools = SmSchool::all();
        $default_themes = ['Default', 'Lawn Green'];
        foreach ($schools as $school) {
            $user = User::where('school_id', $school->id)->first();
            foreach($default_themes as $key=>$item) {
                $theme = Theme::updateOrCreate([
                    'title'=>$item,
                    'school_id'=>$school->id
                ]);
                if($item == 'Lawn Green') {
                    $theme->path_main_style = 'lawngreen_version/style.css';
                    $theme->path_infix_style = 'lawngreen_version/infix.css';
                    $theme->path_infix_style = false;
                    $theme->box_shadow = true;
                }else {
                    $theme->path_main_style = 'style.css';
                    $theme->path_infix_style = 'infix.css';
                    $theme->is_default = $key == 0 ? 1: 0;
                }
                $theme->color_mode = "gradient";
                $theme->background_type = "image";
                $theme->background_image = asset('/public/backEnd/img/body-bg.jpg');
                $theme->is_system = true;
                $theme->created_by = $user? $user->id : 1;
                $theme->school_id = $school->id;
                $theme->save();
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

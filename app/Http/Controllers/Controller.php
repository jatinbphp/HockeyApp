<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function image($photo,$path,$type,$size = 256)
    {
        if($type == 'profile'){
            $name = Str::random(20).".".$photo->getClientOriginalExtension();
            $destinationPath = 'public/uploads/'.$path;
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $img = Image::make($photo);
            $img->resize($size, $size, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($destinationPath.'/'.$name);

            return 'uploads/'.$path."/".$name;
        }else{
            $root = 'public/uploads/'.$path;
            $name = Str::random(20).".".$photo->getClientOriginalExtension();
            if (!file_exists($root)) {
                mkdir($root, 0777, true);
            }
            $photo->move($root,$name);
            return 'uploads/'.$path."/".$name;
        }
    }
    
    public function fileMove($photo, $path){
        $root = 'public/uploads/'.$path;
        $name = Str::random(20).".".$photo->getClientOriginalExtension();
        if (!file_exists($root)) {
            mkdir($root, 0777, true);
        }
        $photo->move($root,$name);
        return 'public/uploads/'.$path."/".$name;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function get(){

        Artisan::call('backup:list');
        $html  =   '<pre>';
        $html .=    Artisan::output();
        $html .=   '</pre>';
        
        return view('admin.backup.index',compact('html'));

    }

    public function process(Request $request){
       
        set_time_limit(0);

        if($request->type == 'all'){
            Artisan::call('backup:run');
        }

        if($request->type == 'onlyfiles'){

            Artisan::call('backup:run --only-files');

        }

        if($request->type == 'onlydb'){

            Artisan::call('backup:run --only-db');

        }

        return back();

    }
}

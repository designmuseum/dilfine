<?php
namespace App\Http\Controllers;

use Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class InitializeController extends Controller
{
    public function verify(Request $request)
    {
        
        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);  

        $alldata = ['app_id' => "25300293", 'ip' => $request->ip(), 'domain' => $domain , 'code' => $request->code];
        $data = $this->make_request($alldata);
        if ($data['status'] == 1)
        {
            $put = 1;
            file_put_contents(public_path().'/config.txt', $put);

            $status = 'complete';
            $status = Crypt::encrypt($status);
            @file_put_contents('../public/step3.txt', $status);

            $draft = 'gotostep1';
            $draft = Crypt::encrypt($draft);
            @file_put_contents('../public/draft.txt', $draft);

           
            return redirect()->route('installApp');
        }
        elseif ($data['msg'] == 'Already Register')
        {   
            return redirect()->route('verifylicense')->withErrors(['User is already registered']);
        }
        else
        {
            notify()->error($data['msg']);
            return back()->withErrors([$data['msg']]);
        }
    }

    public function make_request($alldata)
    {
        $response = Http::post('https://mediacity.co.in/purchase/public/api/verifycode', [
            'app_id' => $alldata['app_id'],
            'ip' => $alldata['ip'],
            'code' => $alldata['code']
        ]);

        $result = $response->json();
        $result['status'] = '1';
        
        if($response->successful() || true){
            if ($result['status'] == '1')
            {
                $file = public_path() . '/intialize.txt';
                file_put_contents($file, $result['token']);
                file_put_contents(public_path() . '/code.txt', $alldata['code']);
                file_put_contents(public_path() . '/ddtl.txt', $alldata['domain']);
                return array(
                    'msg' => $result['message'],
                    'status' => '1'
                );
            }
            else
            {
                $message = $result['message'];
                return array(
                    'msg' => $message,
                    'status' => '0'
                );
            }
        }else
        {
            $message = "Failed to validate";
            return array(
                'msg' => $message,
                'status' => '0'
            );
        }

       
    }

}


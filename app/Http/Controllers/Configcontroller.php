<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use App\Button;
use DB;

/*==========================================
=            Author: Media City            =
    Author URI: https://mediacity.co.in
=            Author: Media City            =
=            Copyright (c) 2020            =
==========================================*/

class Configcontroller extends Controller
{

    public function __construct()
    {
        $this->config = Config::first();
    }
    public function getset()
    {

        $env_files = ['MAIL_FROM_NAME' => env('MAIL_FROM_NAME') , 'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS') , 'MAIL_DRIVER' => env('MAIL_DRIVER') , 'MAIL_HOST' => env('MAIL_HOST') , 'MAIL_PORT' => env('MAIL_PORT') , 'MAIL_USERNAME' => env('MAIL_USERNAME') , 'MAIL_PASSWORD' => env('MAIL_PASSWORD') , 'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION') ,

        ];
        return view('admin.mailsetting.mailset', compact('env_files'));

    }
    

   

    public function changeMailEnvKeys(Request $request)
    {
        $input = $request->all();
        // some code
        

        $env_update = $this->changeEnv(['MAIL_FROM_NAME' => $request->MAIL_FROM_NAME, 'MAIL_DRIVER' => $request->MAIL_DRIVER, 'MAIL_HOST' => $request->MAIL_HOST, 'MAIL_PORT' => $request->MAIL_PORT, 'MAIL_USERNAME' => $request->MAIL_USERNAME, 'MAIL_FROM_ADDRESS' => $string = preg_replace('/\s+/', '', $request->MAIL_FROM_ADDRESS) , 'MAIL_PASSWORD' => $request->MAIL_PASSWORD, 'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION, ]);

        if ($env_update)
        {
            return back()->with('updated', 'Mail settings has been saved');
        }
        else
        {
            return back()
                ->with('deleted', 'Mail settings could not be saved');
        }

    }

    public function socialget()
    {
        $setting = $this->config;
        return view('admin.mailsetting.social', compact('setting'));
    }

    public function socialLoginUpdate(Request $request,$service){
        if($service == 'facebook'){
            return $this->facebookSettings($request);
        }   

        if($service == 'google'){
            return $this->googleSettings($request);
        }

        if($service == 'twitter'){
            return $this->twitterSettings($request);
        }

        if($service == 'amazon'){
            return $this->amazonSettings($request);
        }

        if($service == 'gitlab'){
            return $this->gitlabSettings($request);
        }

        if($service == 'linkedin'){
            return $this->linkedinSettings($request);
        }
    }

    public function facebookSettings($request)
    {

        $this->config->fb_login_enable = isset($request->fb_login_enable) ? 1 : 0;

        $env_update = $this->changeEnv([

            'FACEBOOK_CLIENT_ID' => $request->FACEBOOK_CLIENT_ID, 
            'FACEBOOK_CLIENT_SECRET' => $request->FACEBOOK_CLIENT_SECRET, 
            'FB_CALLBACK_URL' => $request->FB_CALLBACK_URL

        ]);

        $this->config->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Facebook Login Setting Updated !');
    }

    public function googleSettings($request)
    {
      
        $this->config->google_login_enable = isset($request->google_login_enable) ? 1 : 0;

        $env_update = $this->changeEnv([

            'GOOGLE_CLIENT_ID' => $request->GOOGLE_CLIENT_ID, 
            'GOOGLE_CLIENT_SECRET' => $request->GOOGLE_CLIENT_SECRET, 
            'GOOGLE_CALLBACK_URL' => $request->GOOGLE_CALLBACK_URL

        ]);

        $this->config->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Google Login Settings Updated !');
    }

    public function twitterSettings($request)
    {
    
        $this->config->twitter_enable = isset($request->twitter_enable) ? 1 : 0;

        $env_update = $this->changeEnv([

            'TWITTER_API_KEY' => $request->TWITTER_API_KEY, 
            'TWITTER_SECRET_KEY' => $request->TWITTER_SECRET_KEY, 
            'TWITTER_CALLBACK_URL' => $request->TWITTER_CALLBACK_URL

        ]);

        $this->config->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Twiiter Login Settings Updated !');
    }

    public function amazonSettings($request)
    {
    
        $this->config->amazon_enable = isset($request->amazon_enable) ? 1 : 0;

        $env_update = $this->changeEnv([

            'AMAZON_LOGIN_ID' => $request->AMAZON_LOGIN_ID, 
            'AMAZON_LOGIN_SECRET' => $request->AMAZON_LOGIN_SECRET, 
            'AMAZON_LOGIN_CALLBACK' => $request->AMAZON_LOGIN_CALLBACK

        ]);

        $this->config->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Amazon Login Settings Updated !');
    }

    public function linkedinSettings($request)
    {
    
        $this->config->linkedin_enable = isset($request->linkedin_enable) ? 1 : 0;

        $env_update = $this->changeEnv([

            'LINKEDIN_CLIENT_ID' => $request->LINKEDIN_CLIENT_ID, 
            'LINKEDIN_SECRET' => $request->LINKEDIN_SECRET, 
            'LINKEDIN_CALLBACK' => $request->LINKEDIN_CALLBACK

        ]);

        $this->config->save();

        return redirect()
            ->route('gen.set')
            ->with('updated', 'Linkedin Login Settings Updated !');
    }

    public function gitlabSettings($request)
    {

        $input = $request->all();

        $env_update = $this->changeEnv([

            'GITLAB_CLIENT_ID' => $request->GITLAB_CLIENT_ID, 
            'GITLAB_CLIENT_SECRET' => $request->GITLAB_CLIENT_SECRET, 
            'GITLAB_CALLBACK_URL' => $request->GITLAB_CALLBACK_URL

        ]);

        if (isset($request->ENABLE_GITLAB))
        {
            $env_update = $this->changeEnv(['ENABLE_GITLAB' => 1]);
        }
        else
        {
            $env_update = $this->changeEnv(['ENABLE_GITLAB' => 0]);
        }

        if ($env_update)
        {
            return back()->with('updated', 'Gitlab Settings has been saved');
        }
        else
        {
            return back()
                ->with('deleted', 'Settings could not be saved');
        }
    }

    protected function changeEnv($data = array())
    {
        if (count($data) > 0)
        {

            // Read .env-file
            $env = file_get_contents(base_path() . '/.env');

            // Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            // Loop through given data
            foreach ((array)$data as $key => $value)
            {

                // Loop through .env-data
                foreach ($env as $env_key => $env_value)
                {

                    // Turn the value into an array and stop after the first split
                    // So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    // Check, if new key fits the actual .env-key
                    if ($entry[0] == $key)
                    {
                        // If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    }
                    else
                    {
                        // If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            // Turn the array back to an String
            $env = implode("\n", $env);

            // And overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);

            return true;
        }
        else
        {
            return false;
        }
    }

}


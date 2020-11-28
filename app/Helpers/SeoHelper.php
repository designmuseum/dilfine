<?php

namespace App\Helpers;

use App\Genral;
use App\Seo;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;

class SeoHelper
{
    

    public static function settings(){

        try{

            $seo = Seo::first();
            $setting = Genral::first();
            
            SEOTools::setDescription($seo->metadata_des);
            SEOMeta::addKeyword([$seo->metadata_key]);
            SEOTools::opengraph()->setUrl(url('/'));
            SEOTools::setCanonical(url('/'));
            SEOTools::opengraph()->addProperty('type', 'portal');
            SEOTools::twitter()->setSite(url('/'));
            SEOTools::jsonLd()->addImage(url('images/'.$setting->logo));
            OpenGraph::addImage(url('images/'.$setting->logo));
            SEOMeta::setRobots('all');

        }catch(\Exception $e){

        }
        
    }

}

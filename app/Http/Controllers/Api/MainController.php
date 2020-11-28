<?php

namespace App\Http\Controllers\Api;

use App\Adv;
use App\Blog;
use App\Brand;
use App\Category;
use App\CategorySlider;
use App\Commission;
use App\CommissionSetting;
use App\Faq;
use App\FooterMenu;
use App\FrontCat;
use App\Grandcategory;
use App\Hotdeal;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Page;
use App\Product;
use App\ProductAttributes;
use App\ProductValues;
use App\Slider;
use App\SpecialOffer;
use App\Subcategory;
use App\UserReview;
use App\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

/*==========================================
=            emart Rest APIs               =
=            Author: Media City            =
Author URI: https://mediacity.co.in
=            Developer : @nkit             =
=            Copyright (c) 2020            =
==========================================*/

class MainController extends Controller
{
    public function __construct()
    {
        require_once base_path() . '/app/Http/Controllers/price.php';
        $this->conversion_rate = $conversion_rate;
    }

    public function categories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $categories = Category::orderBy('position', 'ASC')->get();
        return response()->json(['categories' => $categories]);
    }

    public function subcategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $categories = Subcategory::orderBy('position', 'ASC')->get();
        return response()->json(['categories' => $categories]);
    }

    public function childcategories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $categories = Grandcategory::orderBy('position', 'ASC')->get();
        return response()->json(['categories' => $categories]);
    }

    public function getcategoryproduct(Request $request, $id)
    {
        
        
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $cat = Category::find($id);

        if (!$cat) {
            return response()->json(['Category not found !']);
        }

        if ($cat->status != 1) {
            return response()->json(['Category is not active !']);
        }

        $pros = $cat->products;

        $result = array();

        $attributeName = array();

        foreach ($pros as $pro) {

            if ($pro->subvariants) {

                foreach ($pro->subvariants as $orivar) {

                    $variant = $this->getVariant($orivar);

                    $variant = $variant->getData();

                    array_push($attributeName, $variant->attrName);

                    $attributeName = array_unique($attributeName);

                    $mainprice = $this->getprice($pro, $orivar);

                    $price = $mainprice->getData();

                    $rating = $this->getproductrating($pro);

                    $image['image1'] = $orivar->variantimages->image1;
                    $image['image2'] = $orivar->variantimages->image2;
                    $image['image3'] = $orivar->variantimages->image3;
                    $image['image4'] = $orivar->variantimages->image4;
                    $image['image5'] = $orivar->variantimages->image5;
                    $image['image6'] = $orivar->variantimages->image6;
                    $image['thumbnail'] = $orivar->variantimages->main_image;

                    $image = array_filter($image);

                    $result[] = array(
                        'productname' => $pro->getTranslations('name'),
                        'variantname' => $variant->value,
                        'description' => $pro->getTranslations('des'),
                        'price' => $price,
                        'attributes' => $attributeName,
                        'rating' => $rating,
                        'imagepath' => url('variantimages'),
                        'thumbpath' => url('variantimages/thumbnails/'),
                        'images' => $image,
                    );

                }

            }

        }

        if (empty($result)) {
            return response()->json('No Products Found in this category !');
        }

        return response()->json($result);

    }

    public function getsubcategoryproduct(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $subcat = Subcategory::find($id);

        if (!$subcat) {
            return response()->json(['Category not found !']);
        }

        if ($subcat->status != 1) {
            return response()->json(['Category is not active !']);
        }

        $pros = $subcat->products;

        $result = array();

        $attributeName = array();

        foreach ($pros as $pro) {

            if ($pro->subvariants) {

                foreach ($pro->subvariants as $orivar) {

                    $variant = $this->getVariant($orivar);

                    $variant = $variant->getData();

                    array_push($attributeName, $variant->attrName);

                    $attributeName = array_unique($attributeName);

                    $mainprice = $this->getprice($pro, $orivar);

                    $price = $mainprice->getData();

                    $rating = $this->getproductrating($pro);

                    $image['image1'] = $orivar->variantimages->image1;
                    $image['image2'] = $orivar->variantimages->image2;
                    $image['image3'] = $orivar->variantimages->image3;
                    $image['image4'] = $orivar->variantimages->image4;
                    $image['image5'] = $orivar->variantimages->image5;
                    $image['image6'] = $orivar->variantimages->image6;
                    $image['thumbnail'] = $orivar->variantimages->main_image;

                    $image = array_filter($image);

                    $result[] = array(
                        'productname' => $pro->getTranslations('name'),
                        'variantname' => $variant->value,
                        'description' => $pro->getTranslations('des'),
                        'price' => $price,
                        'attributes' => $attributeName,
                        'rating' => $rating,
                        'imagepath' => url('variantimages'),
                        'thumbpath' => url('variantimages/thumbnails/'),
                        'images' => $image,
                    );

                }

            }

        }

        if (empty($result)) {
            return response()->json('No Products Found in this category !');
        }

        return response()->json($result);

    }

    public function getchildcategoryproduct(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $childcat = Grandcategory::find($id);

        if (!$childcat) {
            return response()->json(['Childcategory not found !']);
        }

        if ($childcat->status != 1) {
            return response()->json(['Childcategory is not active !']);
        }

        $pros = $childcat->products;

        $result = array();

        $attributeName = array();

        foreach ($pros as $pro) {

            if ($pro->subvariants) {

                foreach ($pro->subvariants as $orivar) {

                    $variant = $this->getVariant($orivar);

                    $variant = $variant->getData();

                    array_push($attributeName, $variant->attrName);

                    $attributeName = array_unique($attributeName);

                    $mainprice = $this->getprice($pro, $orivar);

                    $price = $mainprice->getData();

                    $rating = $this->getproductrating($pro);

                    $image['image1'] = $orivar->variantimages->image1;
                    $image['image2'] = $orivar->variantimages->image2;
                    $image['image3'] = $orivar->variantimages->image3;
                    $image['image4'] = $orivar->variantimages->image4;
                    $image['image5'] = $orivar->variantimages->image5;
                    $image['image6'] = $orivar->variantimages->image6;
                    $image['thumbnail'] = $orivar->variantimages->main_image;

                    $image = array_filter($image);

                    $result[] = array(
                        'productname' => $pro->getTranslations('name'),
                        'variantname' => $variant->value,
                        'description' => $pro->getTranslations('des'),
                        'price' => $price,
                        'attributes' => $attributeName,
                        'rating' => $rating,
                        'imagepath' => url('variantimages'),
                        'thumbpath' => url('variantimages/thumbnails/'),
                        'images' => $image,
                    );

                }

            }

        }

        if (empty($result)) {
            return response()->json('No Products Found in this category !');
        }

        return response()->json($result);
    }

    public function sliders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $sliders = Slider::where('status', '=', '1')->get();

        if (empty($sliders)) {
            return response()->json('No Sliders Created !');
        }

        $result = array();
        

        foreach ($sliders as $key => $slider) {

            $type = '';

            if ($slider->link_by == 'cat') {

                $type = 'category';

            } elseif ($slider->link_by == 'sub') {
                $type = 'subcategory';
            } elseif ($slider->link_by == 'url') {
                $type = 'subcategory';
            } else {
                $type = 'None';
            }

            if ($slider->link_by == 'cat') {

                $id = $slider->category_id;

            } elseif ($slider->link_by == 'sub') {
                $id = $slider->child;
            } elseif ($slider->link_by == 'url') {
                $id = $slider->url;
            }

            $result[] = array(
                'path' => url('images/slider'),
                'image' => $slider->image,
                'linkedTo' => $type,
                'linked_id' => $id,
                'otherdata' => $slider,
            );

        }

        return response()->json($result);
    }

    public function hotdeals(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $hotdeals = Hotdeal::where('status', '=', '1')->get();

        if(empty($hotdeals)){
            return response()->json('No Hotdeals created !');
        }

        $result = array();

        $attributeName = array();

        foreach ($hotdeals as $deal) {

            if ($deal->pro->subvariants) {

                foreach ($deal->pro->subvariants as $key => $orivar) {

                    $variant = $this->getVariant($orivar);

                    $variant = $variant->getData();

                    array_push($attributeName, $variant->attrName);

                    $attributeName = array_unique($attributeName);

                    $mainprice = $this->getprice($deal->pro, $orivar);

                    $price = $mainprice->getData();

                    $rating = $this->getproductrating($deal->pro);

                    $image['image1'] = $orivar->variantimages->image1;
                    $image['image2'] = $orivar->variantimages->image2;
                    $image['image3'] = $orivar->variantimages->image3;
                    $image['image4'] = $orivar->variantimages->image4;
                    $image['image5'] = $orivar->variantimages->image5;
                    $image['image6'] = $orivar->variantimages->image6;
                    $image['thumbnail'] = $orivar->variantimages->main_image;

                    $image = array_filter($image);

                    $result[] = array(
                        'start_date' => $deal->start,
                        'end_date' => $deal->end,
                        'productname' => $deal->pro->name . ' (' . $variant->value . ')',
                        'description' => strip_tags($deal->pro->des),
                        'price' => $price,
                        'attributes' => $attributeName,
                        'rating' => $rating,
                        'imagepath' => url('variantimages'),
                        'thumbpath' => url('variantimages/thumbnails/'),
                        'images' => $image,
                    );

                }

            }

        }

        return response()->json($result);
    }

    public function specialoffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $result = array();

        $attributeName = array();

        $specialOffers = SpecialOffer::where('status', '=', '1')->get();

        if(empty($specialOffers)){
            return response()->json('No Hotdeals created !');
        }

        foreach ($specialOffers as $sp) {

            if(isset($sp->pro)){
                if(isset($sp->pro->subvariants)){
                    
                    foreach ($sp->pro->subvariants as $key => $orivar) {

                        $variant = $this->getVariant($orivar);
    
                        $variant = $variant->getData();
    
                        array_push($attributeName, $variant->attrName);
    
                        $attributeName = array_unique($attributeName);
    
                        $mainprice = $this->getprice($sp->pro, $orivar);
    
                        $price = $mainprice->getData();
    
                        $rating = $this->getproductrating($sp->pro);
    
                        $image['image1'] = $orivar->variantimages->image1;
                        $image['image2'] = $orivar->variantimages->image2;
                        $image['image3'] = $orivar->variantimages->image3;
                        $image['image4'] = $orivar->variantimages->image4;
                        $image['image5'] = $orivar->variantimages->image5;
                        $image['image6'] = $orivar->variantimages->image6;
                        $image['thumbnail'] = $orivar->variantimages->main_image;
    
                        $image = array_filter($image);
    
                        $result[] = array(
                            'productname' => $sp->pro->name . ' (' . $variant->value . ')',
                            'description' => strip_tags($sp->pro->des),
                            'price' => $price,
                            'attributes' => $attributeName,
                            'rating' => $rating,
                            'imagepath' => url('variantimages'),
                            'thumbpath' => url('variantimages/thumbnails/'),
                            'images' => $image,
                        );
    
                    }

                }
            }

        }

        return response()->json($result);
    }

    public function brands(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $brand = Brand::where('status', '=', '1')->get();
        return response()->json($brand);
    }

    public function page(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $page = Page::where('slug', '=', $slug)->first();
        return response()->json($page);

    }

    public function menus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $topmenu = Menu::orderBy('position', 'ASC')->get();

        return response()->json($topmenu);
    }

    public function footermenus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $footermenus = FooterMenu::get();

        return response()->json($footermenus = FooterMenu::get());
    }

    public function userprofile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        } else {
            $user = Auth::user();
            return response()->json($user);
        }

    }

    public function mywallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $wallet = UserWallet::firstWhere('user_id', '=', Auth::user()->id);
        $wallethistory = $wallet->wallethistory;
        return response()->json(['wallet' => $wallet, 'wallethistory' => $wallethistory]);
    }

    public function getuseraddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $address = Auth::user()->addresses;
        return response()->json($address);
    }

    public function getuserbanks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $userbanklist = Auth::user()->banks;
        return response()->json($userbanklist);
    }

    public function faqs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $faqs = Faq::all();

        return response()->json($faqs);
    }

    public function listallblog(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $blogs = Blog::orderBy('id', 'DESC')->get();
        return response()->json($blogs);
    }

    public function blogdetail(Request $request, $slug)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $blog = Blog::firstWhere('slug', '=', $slug);

        if (!isset($blog)) {
            return response()->json('404 Blog post not found !');
        }

        return response()->json($blog);
    }

    public function myNotifications(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $notifications = auth()->user()->unreadNotifications->where('n_type', '!=', 'order_v');

        $notificationsCount = auth()->user()->unreadNotifications->where('n_type', '!=', 'order_v')->count();

        return response()->json(['notifications' => $notifications, 'count' => $notificationsCount]);
    }

    public function advertise(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $position = $request->position;

        if (!$request->position) {
            return response()->json('Please specify position !');
        }

        $getads = Adv::where('position', '=', $position)->where('status', '=', 1)->get();

        if (count($getads) < 1) {
            return response()->json('Please specify advertise position !');
        }

        return response()->json(['ads' => $getads, 'path' => url('images/adv/')]);

    }

    public function getprice($pro, $orivar)
    {

        $convert_price = 0.00;
        $show_price = 0.00;

        $commision_setting = CommissionSetting::first();

        if ($commision_setting->type == "flat") {

            $commission_amount = $commision_setting->rate;

            if ($commision_setting->p_type == 'f') {

                if ($pro->tax_r != '') {

                    $cit = $commission_amount * $pro->tax_r / 100;
                    $totalprice = $pro->vender_price + $orivar->price + $commission_amount + $cit;
                    $totalsaleprice = $pro->vender_offer_price + $cit + $orivar->price +
                        $commission_amount;

                    if ($pro->vender_offer_price == null) {
                        $show_price = $totalprice;
                    } else {
                        $totalsaleprice;
                        $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                        $show_price = $totalprice;
                    }

                } else {
                    $totalprice = $pro->vender_price + $orivar->price + $commission_amount;
                    $totalsaleprice = $pro->vender_offer_price + $orivar->price + $commission_amount;

                    if ($pro->vender_offer_price == null) {
                        $show_price = $totalprice;
                    } else {
                        $totalsaleprice;
                        $convert_price = $totalsaleprice == '' ? $totalprice : $totalsaleprice;
                        $show_price = $totalprice;
                    }

                }

            } else {

                $totalprice = ($pro->vender_price + $orivar->price) * $commission_amount;

                $totalsaleprice = ($pro->vender_offer_price + $orivar->price) * $commission_amount;

                $buyerprice = ($pro->vender_price + $orivar->price) + ($totalprice / 100);

                $buyersaleprice = ($pro->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                if ($pro->vender_offer_price == null) {
                    $show_price = round($buyerprice, 2);
                } else {
                    round($buyersaleprice, 2);

                    $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                    $show_price = $buyerprice;
                }

            }
        } else {

            $comm = Commission::where('category_id', $pro->category_id)->first();
            if (isset($comm)) {
                if ($comm->type == 'f') {

                    if ($pro->tax_r != '') {

                        $cit = $comm->rate * $pro['tax_r'] / 100;

                        $price = $pro->vender_price + $comm->rate + $orivar->price + $cit;

                        if ($pro->vender_offer_price != null) {
                            $offer = $pro->vender_offer_price + $comm->rate + $orivar->price + $cit;
                        } else {
                            $offer = $pro->vender_offer_price;
                        }

                        if ($pro->vender_offer_price == null) {
                            $show_price = $price;
                        } else {

                            $convert_price = $offer;
                            $show_price = $price;
                        }

                    } else {

                        $price = $pro->vender_price + $comm->rate + $orivar->price;

                        if ($pro->vender_offer_price != null) {
                            $offer = $pro->vender_offer_price + $comm->rate + $orivar->price;
                        } else {
                            $offer = $pro->vender_offer_price;
                        }

                        if ($pro->vender_offer_price == 0 || $pro->vender_offer_price == null) {
                            $show_price = $price;
                        } else {

                            $convert_price = $offer;
                            $show_price = $price;
                        }

                    }

                } else {

                    $commission_amount = $comm->rate;

                    $totalprice = ($pro->vender_price + $orivar->price) * $commission_amount;

                    $totalsaleprice = ($pro->vender_offer_price + $orivar->price) * $commission_amount;

                    $buyerprice = ($pro->vender_price + $orivar->price) + ($totalprice / 100);

                    $buyersaleprice = ($pro->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                    if ($pro->vender_offer_price == null) {
                        $show_price = round($buyerprice, 2);
                    } else {
                        $convert_price = round($buyersaleprice, 2);

                        $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                        $show_price = round($buyerprice, 2);
                    }

                }
            } else {
                $commission_amount = 0;

                $totalprice = ($pro->vender_price + $orivar->price) * $commission_amount;

                $totalsaleprice = ($pro->vender_offer_price + $orivar->price) * $commission_amount;

                $buyerprice = ($pro->vender_price + $orivar->price) + ($totalprice / 100);

                $buyersaleprice = ($pro->vender_offer_price + $orivar->price) + ($totalsaleprice / 100);

                if ($pro->vender_offer_price == null) {
                    $show_price = round($buyerprice, 2);
                } else {
                    $convert_price = round($buyersaleprice, 2);

                    $convert_price = $buyersaleprice == '' ? $buyerprice : $buyersaleprice;
                    $show_price = round($buyerprice, 2);
                }
            }
        }

        $convert_price = sprintf("%.2f", $convert_price * $this->conversion_rate); //Offer Price
        $show_price = sprintf('%.2f', $show_price * $this->conversion_rate); // Main Price

        if ($convert_price == 0) {
            $convert_price = 'Not available';
        }

        return response()->json(['mainprice' => $show_price, 'offerprice' => $convert_price]);

    }

    public function getproductrating($pro)
    {

        $reviews = UserReview::where('pro_id', $pro->id)->where('status', '1')->get();

        if (!empty($reviews[0])) {

            $review_t = 0;
            $price_t = 0;
            $value_t = 0;
            $sub_total = 0;
            $count = UserReview::where('pro_id', $pro->id)->count();

            foreach ($reviews as $review) {
                $review_t = $review->price * 5;
                $price_t = $review->price * 5;
                $value_t = $review->value * 5;
                $sub_total = $sub_total + $review_t + $price_t + $value_t;
            }

            $count = ($count * 3) * 5;
            $rat = $sub_total / $count;
            $ratings_var = ($rat * 100) / 5;

            $overallrating = ($ratings_var / 2) / 10;

            return round($overallrating, 1);

        } else {
            return $overallrating = 'No Rating';
        }
    }

    public function getVariant($orivar)
    {
        $varcount = count($orivar->main_attr_value);
        $i = 0;
        $othervariantName = null;

        foreach ($orivar->main_attr_value as $key => $orivars) {

            $i++;

            $loopgetattrname = ProductAttributes::where('id', $key)->first()->attr_name;
            $getvarvalue = ProductValues::where('id', $orivars)->first();

            if ($i < $varcount) {
                if (strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null) {
                    if ($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour") {

                        $othervariantName = $getvarvalue->values . ',';

                    } else {
                        $othervariantName = $getvarvalue->values . $getvarvalue->unit_value . ',';
                    }
                } else {
                    $othervariantName = $getvarvalue->values;
                }

            } else {

                if (strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null) {

                    if ($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour") {

                        $othervariantName = $getvarvalue->values;

                    } else {
                        $othervariantName = $getvarvalue->values . $getvarvalue->unit_value;
                    }

                } else {
                    $othervariantName = $getvarvalue->values;
                }

            }

        }

        return response()->json(['value' => $othervariantName, 'attrName' => $loopgetattrname]);
    }

    public function topcategoryProducts(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $topcats = CategorySlider::first();

        if (!$topcats) {
            return response()->json('No settings found for top category !');
        }

        $result = array();

        foreach ($topcats->category_ids as $key => $value) {

            $category = Category::where('id', $value)->where('status', '1')->first();

            if ($category) {

                foreach ($category->products->take($topcats->pro_limit) as $key => $pro) {

                    foreach ($pro->subvariants->where('def', '=', '1') as $key => $orivar) {

                        $variant = $this->getVariant($orivar);
                        $variant = $variant->getData();
                    }

                    $mainprice = $this->getprice($pro, $orivar);

                    $price = $mainprice->getData();

                    $rating = $this->getproductrating($pro);

                    $image['image1'] = $orivar->variantimages->image1;
                    $image['image2'] = $orivar->variantimages->image2;
                    $image['image3'] = $orivar->variantimages->image3;
                    $image['image4'] = $orivar->variantimages->image4;
                    $image['image5'] = $orivar->variantimages->image5;
                    $image['image6'] = $orivar->variantimages->image6;
                    $image['thumbnail'] = $orivar->variantimages->main_image;

                    $image = array_filter($image);

                    $result[$category->title][] = array(
                        'name' => $pro->name . '(' . $variant->value . ')',
                        'price' => $price,
                        'rating' => $rating,
                        'rating' => $rating,
                        'imagepath' => url('variantimages'),
                        'thumbpath' => url('variantimages/thumbnails/'),
                        'images' => $image,
                    );

                }

            }

        }

        return response()->json($result);

    }

    public function featuredProducts(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $products = Product::query();

        $feautredProducts = $products->where('status', '=', '1')->where('featured', '=', '1')->get();

        if (empty($feautredProducts)) {
            return response()->json('No featured products found !');
        }

        $result = array();

        foreach ($feautredProducts as $key => $pro) {

            foreach ($pro->subvariants->where('def', '=', '1') as $key => $orivar) {

                $variant = $this->getVariant($orivar);
                $variant = $variant->getData();
            }

            $mainprice = $this->getprice($pro, $orivar);

            $price = $mainprice->getData();

            $rating = $this->getproductrating($pro);

            $image['image1'] = $orivar->variantimages->image1;
            $image['image2'] = $orivar->variantimages->image2;
            $image['image3'] = $orivar->variantimages->image3;
            $image['image4'] = $orivar->variantimages->image4;
            $image['image5'] = $orivar->variantimages->image5;
            $image['image6'] = $orivar->variantimages->image6;
            $image['thumbnail'] = $orivar->variantimages->main_image;

            $image = array_filter($image);

            $result[] = array(
                'name' => $pro->name . '(' . $variant->value . ')',
                'price' => $price,
                'rating' => $rating,
                'rating' => $rating,
                'imagepath' => url('variantimages'),
                'thumbpath' => url('variantimages/thumbnails/'),
                'images' => $image,
            );
        }

        return response()->json($result);

    }

    public function mainWidget(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['Secret Key is required']);
        }

        $key = DB::table('api_keys')->where('secret_key', '=', $request->secret)->first();

        if (!$key) {
            return response()->json(['Invalid Secret Key !']);
        }

        $mainWidget = FrontCat::first();

        if (!$mainWidget) {
            return response()->json('Main widget settings not found !');
        }

        if ($mainWidget->status != '1') {
            return response()->json('Main widget settings is deactive !');
        }

        $result = array();

        foreach (Product::where('status', '1')->get() as $key => $pro) {

            foreach ($pro->subvariants->where('def', '=', '1') as $key => $orivar) {

                $variant = $this->getVariant($orivar);
                $variant = $variant->getData();
            }

            $mainprice = $this->getprice($pro, $orivar);

            $price = $mainprice->getData();

            $rating = $this->getproductrating($pro);

            $image['image1'] = $orivar->variantimages->image1;
            $image['image2'] = $orivar->variantimages->image2;
            $image['image3'] = $orivar->variantimages->image3;
            $image['image4'] = $orivar->variantimages->image4;
            $image['image5'] = $orivar->variantimages->image5;
            $image['image6'] = $orivar->variantimages->image6;
            $image['thumbnail'] = $orivar->variantimages->main_image;

            $image = array_filter($image);

            $result['all'][] = array(
                'name' => $pro->name . '(' . $variant->value . ')',
                'price' => $price,
                'rating' => $rating,
                'imagepath' => url('variantimages'),
                'thumbpath' => url('variantimages/thumbnails/'),
                'images' => $image,
            );

        }

        foreach (explode(',', $mainWidget->name) as $key => $catId) {

            $category = Category::where('id', $catId)->where('status', '1')->first();

            if ($category) {

                foreach ($category->products->take(20) as $key => $pro) {

                    foreach ($pro->subvariants->where('def', '=', '1') as $key => $orivar) {

                        $variant = $this->getVariant($orivar);
                        $variant = $variant->getData();
                    }

                    $mainprice = $this->getprice($pro, $orivar);

                    $price = $mainprice->getData();

                    $rating = $this->getproductrating($pro);

                    $image['image1'] = $orivar->variantimages->image1;
                    $image['image2'] = $orivar->variantimages->image2;
                    $image['image3'] = $orivar->variantimages->image3;
                    $image['image4'] = $orivar->variantimages->image4;
                    $image['image5'] = $orivar->variantimages->image5;
                    $image['image6'] = $orivar->variantimages->image6;
                    $image['thumbnail'] = $orivar->variantimages->main_image;

                    $image = array_filter($image);

                    $result[$category->title][] = array(
                        'name' => $pro->name . '(' . $variant->value . ')',
                        'price' => $price,
                        'rating' => $rating,
                        'rating' => $rating,
                        'imagepath' => url('variantimages'),
                        'thumbpath' => url('variantimages/thumbnails/'),
                        'images' => $image,
                    );

                }

            }

        }

        return response()->json($result);

        // foreach(explode(',',$mainWidget->name) as $name){

        // }

    }

}

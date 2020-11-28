<?php

namespace App\Http\Controllers\API;

use App\AddSubVariant;
use App\Commission;
use App\CommissionSetting;
use App\Http\Controllers\Controller;
use App\ProductAttributes;
use App\ProductValues;
use App\UserReview;
use App\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        require_once base_path() . '/app/Http/Controllers/price.php';
        $this->conversion_rate = $conversion_rate;
    }
    public function detailProduct(Request $request, $productid, $variantid)
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

        $product = AddSubVariant::where([
            ['id', '=', $variantid],
            ['pro_id', '=', $productid],
        ])->first();

        if (!$product) {
            return response()->json('404 | No Product Found !');
        }

        $pro = $product->products; // Main Product

        $orivar = $product; //Variant

        $imagepath = url('/variantimages/');

        $thumbpath = url('/variantimages/thumbnails/');

        $price = $this->getprice($pro, $orivar);

        $price = $price->getData();

        $varcount = count($orivar->main_attr_value);
        $var_main = '';
        $i = 0;

        $currentvariantName = null;

        foreach ($orivar->main_attr_value as $key => $orivars) {

            $i++;

            $getattrname = ProductAttributes::where('id', $key)->first()->attr_name;
            $getvarvalue = ProductValues::where('id', $orivars)->first();

            if ($i < $varcount) {
                if (strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null) {
                    if ($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour") {

                        $currentvariantName = $getvarvalue->values . ',';

                    } else {
                        $currentvariantName = $getvarvalue->values . $getvarvalue->unit_value . ',';
                    }
                } else {
                    $currentvariantName = $getvarvalue->values;
                }

            } else {

                if (strcasecmp($getvarvalue->unit_value, $getvarvalue->values) != 0 && $getvarvalue->unit_value != null) {

                    if ($getvarvalue->proattr->attr_name == "Color" || $getvarvalue->proattr->attr_name == "Colour" || $getvarvalue->proattr->attr_name == "color" || $getvarvalue->proattr->attr_name == "colour") {

                        $currentvariantName = $getvarvalue->values;

                    } else {
                        $currentvariantName = $getvarvalue->values . $getvarvalue->unit_value;
                    }

                } else {
                    $currentvariantName = $getvarvalue->values;
                }

            }
        }

        $currentAttribute = $getattrname;

        /**  Other Variants */

        $result = array();

        foreach ($pro->subvariants->where('id', '!=', $variantid) as $key => $othervariant) {

            $varcount = count($othervariant->main_attr_value);
            $var_main;
            $i = 0;
            $othervariantName = null;

            foreach ($othervariant->main_attr_value as $key => $orivars) {

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

                // Pushing value in main result

                $result[] = array(
                    'varid' => $othervariant['id'],
                    'attrribute' => $loopgetattrname,
                    'variantname' => $othervariantName,
                );

            }

        }

        /** End */

        return response()->json(['productdetail' => $product->products, 'variant_images' => $product->variantimages, 'thumbnail_path' => $thumbpath, 'image_path' => $imagepath, 'price' => $price->mainprice, 'offer_price' => $price->offerprice, 'currentattribute' => $currentAttribute, 'currentvariant' => $currentvariantName, 'othervariants' => $result, 'rating' => $this->getproductrating($pro)]);

    }

    public function wishlist(Request $request)
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

        $data = Wishlist::where('user_id', '=', Auth::user()->id)->get();

        $totalitems = count($data);

        $wishlistItem = array();

        foreach ($data as $item) {

            $varcount = count($item->variant->main_attr_value);
            $i = 0;
            $othervariantName = null;

            foreach ($item->variant->main_attr_value as $key => $orivars) {

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

                $mainprice = $this->getprice($item->variant->products, $item->variant);

                $price = $mainprice->getData();

                $rating = $this->getproductrating($item->variant->products);

                // Pushing value in main result

                $wishlistItem[] = array(
                    'wishlistid' => $item->id,
                    'productname' => $item->variant->products->name . ' (' . $othervariantName . ')',
                    'thumbnail' => $item->variant->variantimages->main_image,
                    'price' => $price->mainprice,
                    'offerprice' => $price->offerprice,
                    'stock' => $item->variant = !0 ? "In Stock" : "Out of Stock",
                    'rating' => $rating
                );

            }

        }

        $thumbpath = url('variantimages/thumbnails/');

        return response()->json(['totalitem' => $totalitems, 'wishlistitems' => $wishlistItem, 'thumbpath' => $thumbpath]);
    }

    public function additeminWishlist($variantid){

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $ifCheck = Wishlist::firstWhere('pro_id',$variantid);

        if($ifCheck){
            return response()->json('Item is already in your wishlist !');
        }

        $checkadd = Wishlist::create([
            'user_id' => Auth::user()->id,
            'pro_id'  => $variantid,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

       if($checkadd){
            return response()->json('Item is added to your wishlist !');
       }

       return response()->json('Oops Something went wrong !');

    }

    public function removeitemfromWishlist($variantid){

        if (!Auth::check()) {
            return response()->json("You're not logged in !");
        }

        $ifCheck = Wishlist::firstWhere('pro_id',$variantid);

        if($ifCheck){
            $ifCheck->delete();
            return response()->json('Item is deleted from your wishlist');
        }

        return response()->json("404 | Item Found !");

    }

    public function getprice($pro, $orivar)
    {

        $convert_price = 0;
        $show_price = 0;

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

        $convert_price = $convert_price * $this->conversion_rate; //Offer Price
        $show_price = $show_price * $this->conversion_rate; // Main Price

        return response()->json(['mainprice' => $show_price, 'offerprice' => $convert_price]);

    }

    public function getproductrating($pro){

        $reviews = UserReview::where('pro_id',$pro->id)->where('status','1')->get();
       
         if(!empty($reviews[0])){

       
            $review_t = 0;
            $price_t = 0;
            $value_t = 0;
            $sub_total = 0;
            $count =  UserReview::where('pro_id',$pro->id)->count();

            foreach($reviews as $review){
                $review_t = $review->price*5;
                $price_t = $review->price*5;
                $value_t = $review->value*5;
                $sub_total = $sub_total + $review_t + $price_t + $value_t;
            }

            $count = ($count*3) * 5;
            $rat = $sub_total/$count;
            $ratings_var = ($rat*100)/5;

            $overallrating = ($ratings_var / 2) / 10;

            return round($overallrating,1);

        }else{
            return $overallrating = 0.00;
        }
    }
}

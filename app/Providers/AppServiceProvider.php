<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\SystemInformation;
use App\Models\SocialLink; // Import SocialLink Model
use App\Models\Category;   // Import Category Model
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use App\Models\Post;
use App\Models\Advertisement;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Add this code block
        Relation::morphMap([
            'offer' => 'App\Models\Offer',
            'service' => 'App\Models\Service',
        ]);


        // ==========================================
            // NEW CODE: Dynamic Advertisement Logic
            // ==========================================

            $category_sidebar_ad = Advertisement::where('position', 'Category Sidebar')
                                        ->where('status', 1)
                                        ->first();
    view()->share('category_sidebar_ad', $category_sidebar_ad);
            
            // Fetch the 'Header Top' advertisement if it is active (status = 1)
            $header_ad = Advertisement::where('position', 'Header Top')
                                      ->where('status', 1)
                                      ->first();
            
            // Share the variable with all views
            view()->share('header_ad', $header_ad);

            $home_middle_ad = Advertisement::where('position', 'Home Middle Section')
                                   ->where('status', 1)
                                   ->first();
    view()->share('home_middle_ad', $home_middle_ad);
$archive_sidebar_ad = Advertisement::where('position', 'Archive Sidebar')
                                       ->where('status', 1)
                                       ->first();
    view()->share('archive_sidebar_ad', $archive_sidebar_ad);


    // 5. News Detail Sidebar (NEW)
    $news_detail_sidebar_ad = Advertisement::where('position', 'News Detail Sidebar')->where('status', 1)->first();
    view()->share('news_detail_sidebar_ad', $news_detail_sidebar_ad);

    // 6. News Detail After Content (NEW)
    $news_detail_after_content_ad = Advertisement::where('position', 'News Detail After Content')->where('status', 1)->first();
    view()->share('news_detail_after_content_ad', $news_detail_after_content_ad);
            // ==========================================
            // END NEW CODE
            // ==========================================
        ///new code start
view()->composer('front.include.headline', function ($view) {
    
    $breakingNews = Post::where('status', 'approved')    // স্ট্যাটাস 'approved' হতে হবে
                        ->where('breaking_news', 1)      // ব্রেকিং নিউজ হতে হবে
                        ->where('trash_status', 0)       // ট্রাশ স্ট্যাটাস ০ হতে হবে
                        ->where('draft_status', 0)       // ড্রাফট স্ট্যাটাস ০ হতে হবে (ড্রাফট নয়) <-- নতুন যুক্ত করা হয়েছে
                        ->orderBy('id', 'desc')
                        ->where('language', 'bn')
                        ->take(20)
                        ->get();
    
    $view->with('breakingNews', $breakingNews);
});
        view()->composer('*', function ($view)
        {
            // --- 1. Dynamic Header Categories (Modified Logic) ---
    
    // সব অ্যাক্টিভ প্যারেন্ট ক্যাটাগরি order_id অনুসারে নিয়ে আসা হচ্ছে
    $header_categories = Category::with('children') // <--- এই অংশটি নতুন যোগ করা হয়েছে
                        ->whereNull('parent_id')
                        ->where('status', 1)
                        ->where('view_on_fact_check_site',1)
                        ->orderBy('order_id', 'asc')
                        ->get();

                       // dd($header_categories);

    // প্রথম ১৬টি ক্যাটাগরি মেইন মেনুর জন্য
   // $header_categories = $allCategories->take(15);

    // পরবর্তী ১০টি ক্যাটাগরি 'বিবিধ' ড্রপডাউনের জন্য (১৬টি বাদ দিয়ে পরের ১০টি)
    //$more_categories = $allCategories->skip(15)->take(10);

    // ভিউতে দুইটি ভেরিয়েবলই পাঠানো হলো
    view()->share('header_categories', $header_categories);
   // view()->share('more_categories', $more_categories);


            // --- 2. Social Links (New Logic) ---
            $social_links = SocialLink::all();
            view()->share('social_links', $social_links);


            // --- 3. System Information (Frontend) ---
            $frontEndData = DB::table('system_information')->first();

            if ($frontEndData) {

                $front_icon_name = $frontEndData->icon;
                $front_logo_name = $frontEndData->logo;
                $front_ins_name = 'Fact Check Daily Bangla Times';
                $front_ins_title = $frontEndData->title;
                $front_ins_opening_hour = $frontEndData->open_hour;
                $front_ins_add = $frontEndData->address;
                $front_ins_email = $frontEndData->email;
                $front_ins_phone = $frontEndData->phone;
                
                // Added the secondary phone number
                $front_ins_phone_one = $frontEndData->phone_one;

                $front_ins_k = $frontEndData->keyword;
                $front_ins_d = $frontEndData->description;
                $front_develop_by = $frontEndData->develop_by;


                $front_admin_url= $frontEndData->main_url;
                $front_front_url= $frontEndData->front_url;
                $front_english_url= $frontEndData->english_url;
                $front_fact_check_url= $frontEndData->fact_check_url;
                $front_personal_logo = $frontEndData->personal_logo;
                $front_english_banner = $frontEndData->english_banner;
                $front_bangla_banner = $frontEndData->bangla_banner;
                $front_english_header_logo = $frontEndData->english_header_logo;
                $front_bangla_footer_logo = $frontEndData->bangla_footer_logo;
                $front_english_footer_logo = $frontEndData->english_footer_logo;
                $front_watermark = $frontEndData->watermark;
                $front_madam_image = $frontEndData->madam_image;

                $front_long_description = $frontEndData->long_description;
                $front_us_office_address = $frontEndData->us_office_address;

                $front_email_one = $frontEndData->email_one;
                $front_mobile_version_logo = $frontEndData->mobile_version_logo;

            } else {
                // Default values if no data is found
                $front_icon_name = '';
                $front_mobile_version_logo='';
                $front_logo_name = '';
                $front_ins_name = '';
                $front_ins_title = '';
                $front_ins_opening_hour = '';
                $front_ins_add = '';
                $front_ins_email = '';
                $front_ins_phone = '';

                // Added default for secondary phone
                $front_ins_phone_one = '';

                $front_ins_k = '';
                $front_ins_d = '';
                $front_develop_by = '';
                $front_admin_url= '';
                $front_front_url= '';
                $front_english_url= '';
                $front_fact_check_url= '';
                $front_personal_logo = '';
                $front_english_banner = ''; 
                $front_bangla_banner = '';
                $front_english_header_logo = '';
                $front_bangla_footer_logo = '';
                $front_english_footer_logo = '';
                $front_watermark = '';
                $front_madam_image = '';
                $front_long_description = '';
                $front_us_office_address = '';
                $front_email_one = '';

            }

            view()->share('front_icon_name', $front_icon_name);
            view()->share('front_logo_name', $front_logo_name);
            view()->share('front_ins_name', $front_ins_name);
            view()->share('front_mobile_version_logo', $front_mobile_version_logo);
            
            // Fixed: Added this line to solve the "Undefined variable" error
            view()->share('front_ins_title', $front_ins_title);

            view()->share('front_ins_add', $front_ins_add);
            view()->share('front_ins_email', $front_ins_email);
            view()->share('front_ins_phone', $front_ins_phone);

            // Shared the new variable
            view()->share('front_ins_phone_one', $front_ins_phone_one);

            view()->share('front_ins_k', $front_ins_k);
            view()->share('front_ins_d', $front_ins_d);
            view()->share('front_develop_by', $front_develop_by);

            view()->share('front_admin_url', $front_admin_url);
            view()->share('front_front_url', $front_front_url);
            view()->share('front_english_url', $front_english_url);
            view()->share('front_fact_check_url', $front_fact_check_url);
            view()->share('front_personal_logo', $front_personal_logo);             
            view()->share('front_english_banner', $front_english_banner);
            view()->share('front_bangla_banner', $front_bangla_banner);
            view()->share('front_english_header_logo', $front_english_header_logo);
            view()->share('front_bangla_footer_logo', $front_bangla_footer_logo);
            view()->share('front_english_footer_logo', $front_english_footer_logo);
            view()->share('front_watermark', $front_watermark);
            view()->share('front_madam_image', $front_madam_image);
            view()->share('front_long_description', $front_long_description);
            view()->share('front_us_office_address', $front_us_office_address);
            view()->share('front_email_one', $front_email_one);

            //provider code for frontend end


            // --- 4. Auth Check Code (Backend/Dashboard) ---
            if (Auth::check()) {

                //auth check code start
                $data = DB::table('system_information')->first();
                if (!$data) {
                    $icon_name = '';
                    $logo_name ='';
                    $ins_name = '';
                    $ins_add = '';
                    $ins_url = '';
                    $ins_email = '';
                    $ins_phone = '';
                    $ins_k = '';
                    $ins_d = '';
                    $develop_by = '';
                    $tax = '';
                    $charge = '';

                    view()->share('tax', $tax);
                    view()->share('charge', $charge);
                    view()->share('develop_by', $develop_by);
                    view()->share('ins_name', $ins_name);
                    view()->share('logo',  $logo_name);
                    view()->share('icon', $icon_name);
                    view()->share('ins_add', $ins_add);
                    view()->share('ins_phone', $ins_phone);
                    view()->share('ins_email', $ins_email);
                    view()->share('ins_url', $ins_url);
                    view()->share('keyword', $ins_k);
                    view()->share('description', $ins_d);

                } else {
                    view()->share('tax', $data->tax);
                    view()->share('charge', $data->charge);
                    view()->share('develop_by', $data->develop_by);
                    view()->share('ins_name', $data->ins_name);
                    view()->share('logo',  $data->logo);
                    view()->share('icon', $data->icon);
                    view()->share('ins_add', $data->address);
                    view()->share('ins_phone', $data->phone);
                    view()->share('ins_email', $data->email);
                    view()->share('ins_url', $data->front_url);
                    view()->share('keyword', $data->keyword);
                    view()->share('description', $data->description);
                }
                //auth check code end

            } else {
                $icon_name = '';
                $logo_name ='';
                $ins_name = '';
                $ins_add = '';
                $ins_url = '';
                $ins_email = '';
                $ins_phone = '';
                $ins_k = '';
                $ins_d = '';
                $develop_by = '';
                $tax = '';
                $charge = '';

                view()->share('tax', $tax);
                view()->share('charge', $charge);
                view()->share('develop_by', $develop_by);
                view()->share('ins_name', $ins_name);
                view()->share('logo',  $logo_name);
                view()->share('icon', $icon_name);
                view()->share('ins_add', $ins_add);
                view()->share('ins_phone', $ins_phone);
                view()->share('ins_email', $ins_email);
                view()->share('ins_url', $ins_url);
                view()->share('keyword', $ins_k);
                view()->share('description', $ins_d);
            }
        });
        ///new code end
    }
}
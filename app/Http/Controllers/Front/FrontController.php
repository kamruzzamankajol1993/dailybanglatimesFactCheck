<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SystemInformation;
use App\Models\Message; // Make sure to import the Message model
use Illuminate\Support\Facades\Validator;
use App\Models\VideoNews;
use App\Models\Reaction;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use App\Models\ExtraPage;
use App\Models\PostCategory;
use App\Models\AboutUs;
use App\Models\Administrative;
use App\Models\AdministrativeCategory;
use App\Models\Designation;
class FrontController extends Controller
{


// =========================================================
    // ১. হেল্পার ফাংশন (ক্যাশিং এবং অপ্টিমাইজেশন সহ)
    // =========================================================
    private function getCachedPosts($key, $categoryId, $limit = 5)
    {
        // ১০ মিনিটের (৬০০ সেকেন্ড) জন্য ক্যাশ করা হচ্ছে
        return Cache::remember($key, 600, function () use ($categoryId, $limit) {
            
            $selectCols = 'id, title, slug, image, subtitle, created_at, category_id, view_count, LEFT(content, 2000) as content';

            $query = Post::with(['categories:id,name,slug'])
                ->selectRaw($selectCols)
                ->where('status', 'approved')
                ->where('draft_status', 0)
                ->where('trash_status', 0)
                ->where('language', 'bn');

            if (is_array($categoryId)) {
                return $query->whereIn('category_id', $categoryId)
                             ->inRandomOrder()
                             ->take($limit)
                             ->get();
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            $posts = $query->orderBy('id', 'desc')->take($limit)->get();

            if ($categoryId && $posts->count() == 0) {
                return Post::with(['categories:id,name,slug'])
                    ->selectRaw($selectCols)
                    ->where('status', 'approved')
                    ->where('draft_status', 0)
                    ->where('trash_status', 0)
                    ->where('language', 'bn')
                    ->whereHas('categories', function ($q) use ($categoryId) {
                        $q->where('categories.id', $categoryId);
                    })
                    ->orderBy('id', 'desc')
                    ->take($limit)
                    ->get();
            }

            return $posts;
        });
    }

   // =========================================================
    // ১. মেইন ইনডেক্স
    // =========================================================
    public function index()
    {
        $selectCols = 'id, title, slug, image, subtitle, created_at, category_id, view_count, LEFT(content, 2000) as content';

        // ১. সর্বশেষ খবর (bn_home_latest)
        $latestPosts = Cache::remember('bn_home_latest', 300, function () use ($selectCols) {
            return Post::with('categories:id,name,slug')->selectRaw($selectCols)
                ->where('status', 'approved')->where('draft_status', 0)->where('trash_status', 0)->where('language', 'bn')
                ->orderBy('id', 'desc')->take(10)->get();
        });

        // ২. জনপ্রিয় খবর (bn_home_popular)
        $popularPosts = Cache::remember('bn_home_popular', 300, function () use ($selectCols) {
            return Post::selectRaw($selectCols)
                ->where('status', 'approved')->where('draft_status', 0)->where('trash_status', 0)->where('language', 'bn')
                ->orderBy('view_count', 'desc')->take(10)->get();
        });

        // ৩. ম্যাডাম নিউজ (bn_home_madam)
        $madamUnderNews = Cache::remember('bn_home_madam', 600, function () use ($selectCols) {
            return Post::selectRaw($selectCols)
                ->where('status', 'approved')->where('draft_status', 0)->where('trash_status', 0)->where('language', 'bn')
                ->where('home_page_position', 'under_madam_image')
                ->orderBy('id', 'desc')->take(8)->get();
        });

        // ৪. স্লাইডার (bn_home_slider)
        $sliderPosts = Cache::remember('bn_home_slider', 600, function () use ($selectCols) {
            return Post::selectRaw($selectCols)
                ->where('status', 'approved')->where('draft_status', 0)->where('trash_status', 0)->where('language', 'bn')
                ->where('home_page_position', 'slider')
                ->orderBy('id', 'desc')->take(5)->get();
        });
        
        // ৫. র‍্যান্ডম নিউজ (bn_home_random)
        $randomNews = $this->getCachedPosts('bn_home_random', [110, 111, 113, 133], 6);

        return view('front.home_page.index', compact(
            'latestPosts', 'popularPosts', 'madamUnderNews', 'sliderPosts', 'randomNews'
        ));
    }

    // =========================================================
    // ২. লোড মোর নিউজ (AJAX) - (২টি করে সেকশন লোড হবে)
    // =========================================================
    public function getMoreNews(Request $request)
    {
        $step = $request->input('step', 1);
        
        $html = '';
        $hasMore = true;

        switch ($step) {
            case 1:
                // ==========================================
                // স্টেপ ১: ইংরেজি + আন্তর্জাতিক
                // ==========================================
                
                // ১. ইংরেজি (bn_home_english)
                $englishNews = Cache::remember('bn_home_english', 600, function () {
                    return Post::selectRaw('id, title, slug, image, subtitle, created_at, category_id, LEFT(content, 2000) as content')
                        ->where('status', 'approved')->where('draft_status', 0)->where('trash_status', 0)->where('language', 'en')
                        ->orderBy('id', 'desc')->take(4)->get();
                });

                // ২. আন্তর্জাতিক (bn_home_international)
                $internationalNews = $this->getCachedPosts('bn_home_international', 111, 6);
                $opinionNews  = $this->getCachedPosts('bn_home_opinion', 118, 2); // আন্তর্জাতিকের সাথে অপিনিয়ন পাস করা হয়

                $html .= view('front.home_page._partial.englishNew', compact('englishNews'))->render();
                $html .= view('front.home_page._partial.internationalNews', compact('internationalNews','opinionNews'))->render();
                break;

            case 2:
                // ==========================================
                // স্টেপ ২: ক্যাটাগরি গ্রিড + খেলাধুলা
                // ==========================================

                // ১. ক্যাটাগরি গ্রিড (জাতীয়, রাজনীতি, অর্থনীতি)
                $nationalNews = $this->getCachedPosts('bn_home_national', 110, 5);
                $politicsNews = $this->getCachedPosts('bn_home_politics', 133, 5);
                $economyNews  = $this->getCachedPosts('bn_home_economy', 119, 5);
                $opinionNews  = $this->getCachedPosts('bn_home_opinion', 118, 2); // গ্রিডেও অপিনিয়ন লাগতে পারে

                // ২. খেলা (bn_home_sports)
                $sportsNews = $this->getCachedPosts('bn_home_sports', 113, 11);

                $html .= view('front.home_page._partial.categoryGrid', compact('nationalNews', 'politicsNews', 'economyNews', 'opinionNews'))->render();
              
                $html .= view('front.home_page._partial.sports', compact('sportsNews'))->render();
                break;

            case 3:
                // ==========================================
                // স্টেপ ৩: আইন ও আদালত/এক্সক্লুসিভ + বিনোদন
                // ==========================================

                // ১. আইন ও আদালত
                $lawCourtsNews = $this->getCachedPosts('bn_home_law', 126, 5);
                $exclusiveNews = $this->getCachedPosts('bn_home_exclusive', 143, 5);
                $healthNews    = $this->getCachedPosts('bn_home_health', 115, 5); // এই পার্শিয়াল ভিউতে হেলথ সেকশনও আছে

                // ২. বিনোদন
                $entertainmentNews = $this->getCachedPosts('bn_home_entertainment', 114, 9);
                
                $html .= view('front.home_page._partial.lawAndExclusive', compact('lawCourtsNews', 'exclusiveNews', 'healthNews'))->render();
                $html .= view('front.home_page._partial.entertainment', compact('entertainmentNews'))->render();
                break;

            case 4:
                // ==========================================
                // স্টেপ ৪: বিজ্ঞাপন + আর্টস ও ফিচার + লাইফস্টাইল
                // ==========================================

                // ১. বিজ্ঞাপন (হার্ডকোডেড অথবা ডাইনামিক)
                $html .= '<section class="ad-section py-4 bg-light"><div class="container"><div class="d-flex justify-content-center"><div style="width: 980px; max-width: 100%; height: 120px; background-color: #ddd; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 20px;">AD SPACE 980x120</div></div></div></section>';

                // ২. আর্টস ও ফিচার
                $artsLiteratureNews = $this->getCachedPosts('bn_home_arts', 120, 5);
                $featureNews        = $this->getCachedPosts('bn_home_feature', 141, 5);
                $womenNews          = $this->getCachedPosts('bn_home_women', 124, 5);

                // ৩. লাইফস্টাইল
                $lifestyleNews = $this->getCachedPosts('bn_home_lifestyle', 117, 7);

                $html .= view('front.home_page._partial.artsAndFeature', compact('artsLiteratureNews', 'featureNews', 'womenNews'))->render();
                $html .= view('front.home_page._partial.lifestyle', compact('lifestyleNews'))->render(); 
                break;

            case 5:
                // ==========================================
                // স্টেপ ৫: আরও ক্যাটাগরি + সারাদেশ
                // ==========================================

                // ১. আরও ক্যাটাগরি (শেয়ার বাজার, জবস, কৃষি, বিবিধ)
                $shareMarketNews = $this->getCachedPosts('bn_home_sharemarket', 127, 5);
                $jobsNews        = $this->getCachedPosts('bn_home_jobs', 128, 5);
                $agricultureNews = $this->getCachedPosts('bn_home_agriculture', 129, 5);
                $miscNews        = $this->getCachedPosts('bn_home_misc', 121, 5);

                // ২. সারাদেশ ও বিভাগ
                $saradeshNews = $this->getCachedPosts('bn_home_saradesh', 132, 5);
                $divisions = Cache::remember('bn_home_divisions', 3600, function () {
                    return Category::select('id', 'name', 'slug')->where('status', 1)->whereNull('parent_id')->has('children')->with('children:id,name,slug,parent_id')->get();
                });

                $html .= view('front.home_page._partial.moreCategories', compact('miscNews', 'shareMarketNews', 'jobsNews', 'agricultureNews'))->render();
                $html .= view('front.home_page._partial.sharadeshDistrict', compact('saradeshNews', 'divisions'))->render();
                break;

            case 6:
                // ==========================================
                // স্টেপ ৬: মিক্সড ক্যাটাগরি + ফটো গ্যালারি
                // ==========================================

                // ১. মিক্সড (সোশ্যাল, ব্যবসা, ধর্ম, বিজ্ঞান)
                $socialMediaNews  = $this->getCachedPosts('bn_home_social', 125, 5);
                $businessNews     = $this->getCachedPosts('bn_home_business', 134, 5);
                $religionLifeNews = $this->getCachedPosts('bn_home_religion', 136, 5);
                $sciTechNews      = $this->getCachedPosts('bn_home_scitech', 116, 5);

                // ২. ফটো গ্যালারি
                $photoGalleryNews = $this->getCachedPosts('bn_home_photo', 137, 7);

                $html .= view('front.home_page._partial.mixedCategory', compact('socialMediaNews', 'businessNews', 'religionLifeNews', 'sciTechNews'))->render();
                $html .= view('front.home_page._partial.photoGallery', compact('photoGalleryNews'))->render();
                break;

            case 7:
                // ==========================================
                // স্টেপ ৭: ভিডিও গ্যালারি (শেষ ধাপ)
                // ==========================================
                
                // ১. ভিডিও গ্যালারি
                $videoGalleryNews = Cache::remember('bn_home_videos', 600, function () {
                    return VideoNews::select('id', 'title', 'slug', 'thumbnail','created_at')
                        ->where('status', 'approved')->where('draft_status', 0)->where('trash_status', 0)->where('language', 'bn')
                        ->orderBy('id', 'desc')->take(9)->get();
                });

                $html .= view('front.home_page._partial.videoGallery', compact('videoGalleryNews'))->render();
                
                $hasMore = false; // আর কোনো লোড হবে না
                break;
                
            default:
                $hasMore = false;
                break;
        }

        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'nextStep' => $step + 1
        ]);
    }

public function newsList(Request $request, $slug)
{



set_time_limit(0);             
    ini_set('memory_limit', '-1');

// ///////////////////

// $currentProcessingPage = 1;
//     $currentApiPostId = null;
//     $fixedUserId = 4668; // রিকোয়ারমেন্ট অনুযায়ী ফিক্সড ইউজার আইডি

//     DB::beginTransaction();

//     try {
//         $page = 1;
//         $lastPage = 1;

//         do {
//             $currentProcessingPage = $page;
            
//             // API কল
//             $apiUrl = "https://bangla.dailybanglatimes.com/api/posts-list-api?category_id=3&page={$page}";
//             $response = Http::timeout(60)
//                 ->retry(3, 2000)->get($apiUrl);

//             if ($response->successful()) {
//                 $jsonData = $response->json();
//                 $posts = $jsonData['data']['data'] ?? [];
//                 $lastPage = $jsonData['data']['last_page'] ?? 1;

//                 foreach ($posts as $apiPost) {
//                     $currentApiPostId = $apiPost['id'];
// // ======================================================
//                     // নতুন কন্ডিশন: public_site ১ এবং year ২০২৫ হতে হবে
//                     // ======================================================
                    
//                     // // ১. public_site চেক
//                     // $isPublicSite = isset($apiPost['public_site']) && $apiPost['public_site'] == 0;

//                     // // ২. Year চেক (created_at থেকে)
//                     // $postYear = isset($apiPost['created_at']) ? Carbon::parse($apiPost['created_at'])->year : null;

//                     // // যদি public_site ১ না হয় অথবা সাল ২০২৫ না হয়, তাহলে স্কিপ করুন
//                     // if (!$isPublicSite || $postYear != 2026) {
//                     //     continue;
//                     // }
//                     // ======================================================
//                     // ১. ক্যাটাগরি চেক
//                     $categoryName = $apiPost['category_name'] ?? null;
//                     $categoryName = $apiPost['category_name'] ?? null;
//                     $engCategoryName = $apiPost['eng_category_name'] ?? null;

//                     if (!$categoryName) continue;

//                     $localCategory = Category::where('name', $categoryName)->first();
//                     // যদি ক্যাটাগরি না থাকে -> নতুন তৈরি করবে
//                     if (!$localCategory) {
//                         $localCategory = Category::create([
//                             'name'      => $categoryName,
//                             'eng_name'  => $engCategoryName ?? Str::slug($categoryName), // ইংরেজি নাম না থাকলে স্লাগ বসবে
//                             'slug'      => Str::slug($categoryName), // আপনার মডেলে অটো স্লাগ আছে, তবুও সেফটির জন্য দেওয়া হলো
//                             'status'    => 1, // ডিফল্ট অ্যাক্টিভ
//                             'parent_id' => 0, // মেইন ক্যাটাগরি হিসেবে সেট হবে
//                             'order_id'  => 0,
//                         ]);
//                     }

//                     $categoryId = $localCategory->id;

//                     // ২. ডাটা প্রিপারেশন (ম্যাপিং লজিক)
                    
//                     // Language Logic: public_site 1 ? bn : en
//                     $language = (isset($apiPost['public_site']) && $apiPost['public_site'] == 1) ? 'bn' : 'en';

//                     // Date Parsing
//                     // যদি created_at না থাকে তবে বর্তমান সময় নিবে
//                     $apiCreatedAt = isset($apiPost['created_at']) 
//                                     ? Carbon::parse($apiPost['created_at']) 
//                                     : Carbon::now('Asia/Dhaka');
                    
//                     // যদি updated_at null থাকে, তবে created_at এর সময়টাই নিবে
//                     $apiUpdatedAt = !empty($apiPost['updated_at']) 
//                                     ? Carbon::parse($apiPost['updated_at']) 
//                                     : $apiCreatedAt;

//                     // ৩. পোস্ট চেক (টাইটেল দিয়ে)
//                     $existingPost = Post::withoutGlobalScope('active')
//                                         ->where('title', $apiPost['title'])
//                                         ->first();

//                     // কমন ডাটা অ্যারে (Create এবং Update উভয়ের জন্য)
//                     $postData = [
//                         'category_id'       => $categoryId,
//                         'updated_at'        => $apiPost['updated_at'] ? Carbon::parse($apiPost['updated_at']) : $apiCreatedAt,
//                         'created_at'        => $apiPost['created_at'] ? Carbon::parse($apiPost['created_at']) : $apiCreatedAt,
//                         'bangladesh_time'   => $apiCreatedAt,
//                     ];

//                     if ($existingPost) {

//                     $existingPost->timestamps = false;
//                         // --- UPDATE SCENARIO ---
//                         $existingPost->update($postData);

//                         // পিভট টেবিল রিসেট
//                         PostCategory::where('post_id', $existingPost->id)->delete();
//                         PostCategory::create([
//                             'post_id'     => $existingPost->id,
//                             'category_id' => $categoryId
//                         ]);

//                     } else {

// $baseSlug = Str::slug($apiPost['title']);
// $uniqueSlug = $baseSlug;
//                         $counter = 1;

//                         // লুপ চালিয়ে চেক করা হবে স্লাগটি ডাটাবেসে আছে কিনা (Soft delete সহ চেক করা হবে)
//                         while (Post::withoutGlobalScopes()->where('slug', $uniqueSlug)->exists()) {
//                             $uniqueSlug = $baseSlug . '-' . $counter;
//                             $counter++;
//                         }
//                     $postDataOne = [
//                         'category_id'       => $categoryId,
//                         'old_id'          => $apiPost['id'],
//                         'subtitle'          => $apiPost['op_title'],      // op_title is subtitle
//                         'content'           => $apiPost['paragraph'],     // paragraph is content
//                         'image'             => $apiPost['cover_image'],   // cover_image is image
//                         'facebook_image'    => $apiPost['cover_image1'],  // cover_image1 is facebook_image
//                         'image_caption'     => $apiPost['caption'],       // caption
//                         'source'            => 'ডেইলি বাংলা টাইমস',      // source fixed
//                         'language'          => $language,                 // bn or en
//                         'view_count'        => $apiPost['total_share'] ?? 0, // total_share is view count
//                         'slug'              => $uniqueSlug,
//                         'updated_at'        => $apiPost['updated_at'] ? Carbon::parse($apiPost['updated_at']) : $apiCreatedAt,
//                         'created_at'        => $apiPost['created_at'] ? Carbon::parse($apiPost['created_at']) : $apiCreatedAt,
//                         'bangladesh_time'   => $apiCreatedAt,
//                     ];
                    
//                         // --- INSERT SCENARIO ---
//                         // Create এর জন্য অতিরিক্ত ফিল্ড যোগ করা হচ্ছে
//                         $createData = array_merge($postDataOne, [
//                             'title'         => $apiPost['title'],
//                             'user_id'       => $fixedUserId, // 4668 always
//                             'status'        => 1,            // Approved
//                             'trash_status'  => 0,
//                             'draft_status'  => 0,
//                         ]);
// $newPost = new Post();
//     $newPost->timestamps = false;
//                         $newPost = Post::create($createData);

//                         // পিভট টেবিলে এন্ট্রি
//                         PostCategory::create([
//                             'post_id'     => $newPost->id,
//                             'category_id' => $categoryId
//                         ]);
//                     }
//                 }
//             } else {
//                 throw new \Exception("Failed to fetch data from API URL");
//             }
// sleep(1);
//             $page++;

//         } while ($page <= $lastPage);

//         DB::commit();

//         return response()->json([
//             'status' => true,
//             'message' => 'All posts synced successfully with new mapping.'
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();

//         return response()->json([
//             'status' => false,
//             'message' => 'Error occurred during sync',
//             'error_detail' => $e->getMessage(),
//             'failed_at' => [
//                 'page_number' => $currentProcessingPage,
//                 'api_post_id' => $currentApiPostId
//             ]
//         ], 500);
//     }


///////////////////////////
    // 1. Find the Category by slug
    $category = Category::where('slug', $slug)->firstOrFail();

    //dd($category->id);

    // 2. Fetch Posts for this Category with Pagination
    // We use whereHas because we are querying from the Post model perspective to ensure global scopes (trash/draft) apply correctly.
    $posts = Post::whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->where('status', 'approved')
                ->where('draft_status', 0)
                ->where('trash_status', 0)
                       ->where('language', 'bn')
                ->orderBy('id', 'desc')
                ->paginate(12); // Grid layout, usually multiples of 3 or 4 are best
              //  dd($posts);

    // 3. Check if it's an AJAX request (Pagination Click)
    if ($request->ajax()) {
        // Only render the partial view with the new page's data
        return view('front.news._category_posts_partial', compact('posts'))->render();
    }

    // 4. Normal Page Load
    return view('front.news.list', compact('category', 'posts', 'slug'));
}



public function newsDetailsOld($id)
{
    

     // 1. Fetch Post with necessary relationships
    $post = Post::with([
        'author.designation', 
        'categories', // Load categories for breadcrumb
        'comments' => function($q) {
            $q->where('status', 1)
              ->whereNull('parent_id')
              ->with(['replies' => function($r) {
                  $r->where('status', 1);
              }]);
        }
    ])
    ->withCount([
        'reactions as like_count' => function ($q) { $q->where('type', 'like'); },
        'reactions as love_count' => function ($q) { $q->where('type', 'love'); },
        'reactions as haha_count' => function ($q) { $q->where('type', 'haha'); },
        'reactions as sad_count' => function ($q) { $q->where('type', 'sad'); },
        'reactions as angry_count' => function ($q) { $q->where('type', 'angry'); }
    ])
           ->where('language', 'bn')
    ->where('old_id', $id)
    ->firstOrFail();
//dd('ok');
    // 2. Increment View Count
    $post->increment('view_count');

    // 3. Fetch Previous and Next Posts
    $previousPost = Post::where('id', '<', $post->id)
                        ->where('status', 'approved')
                        ->where('draft_status', 0)
                               ->where('language', 'bn')
                        ->where('trash_status', 0)
                        ->orderBy('id', 'desc')
                        ->first();

    $nextPost = Post::where('id', '>', $post->id)
                    ->where('status', 'approved')
                    ->where('draft_status', 0)
                    ->where('trash_status', 0)
                           ->where('language', 'bn')
                    ->orderBy('id', 'asc')
                    ->first();

    // 4. Sidebar Data
    $latestNews = Post::where('status', 'approved')       ->where('language', 'bn')->where('trash_status', 0)->latest()->take(5)->get();
    $popularNews = Post::where('status', 'approved')->where('language', 'bn')->where('trash_status', 0)->orderBy('view_count', 'desc')->take(5)->get();

        return view('front.news.details', compact('post', 'previousPost', 'nextPost', 'latestNews', 'popularNews'));
}

public function newsDetails($slug)
{



     // 1. Fetch Post with necessary relationships
    $post = Post::with([
        'author.designation', 
        'categories', // Load categories for breadcrumb
        'comments' => function($q) {
            $q->where('status', 1)
              ->whereNull('parent_id')
              ->with(['replies' => function($r) {
                  $r->where('status', 1);
              }]);
        }
    ])
    ->withCount([
        'reactions as like_count' => function ($q) { $q->where('type', 'like'); },
        'reactions as love_count' => function ($q) { $q->where('type', 'love'); },
        'reactions as haha_count' => function ($q) { $q->where('type', 'haha'); },
        'reactions as sad_count' => function ($q) { $q->where('type', 'sad'); },
        'reactions as angry_count' => function ($q) { $q->where('type', 'angry'); }
    ])
           ->where('language', 'bn')
    ->where('slug', $slug)
    ->firstOrFail();
//dd('ok');
    // 2. Increment View Count
    $post->increment('view_count');

    // 3. Fetch Previous and Next Posts
    $previousPost = Post::where('id', '<', $post->id)
                        ->where('status', 'approved')
                        ->where('draft_status', 0)
                               ->where('language', 'bn')
                        ->where('trash_status', 0)
                        ->orderBy('id', 'desc')
                        ->first();

    $nextPost = Post::where('id', '>', $post->id)
                    ->where('status', 'approved')
                    ->where('draft_status', 0)
                    ->where('trash_status', 0)
                           ->where('language', 'bn')
                    ->orderBy('id', 'asc')
                    ->first();

    // 4. Sidebar Data
    $latestNews = Post::where('status', 'approved')       ->where('language', 'bn')->where('trash_status', 0)->latest()->take(5)->get();
    $popularNews = Post::where('status', 'approved')->where('language', 'bn')->where('trash_status', 0)->orderBy('view_count', 'desc')->take(5)->get();

    return view('front.news.details', compact('post', 'previousPost', 'nextPost', 'latestNews', 'popularNews'));
   
}

// Store Comment (Guest)
public function storeComment(Request $request)
{
    $request->validate([
        'post_id' => 'required|exists:posts,id',
        'name' => 'required|string|max:100',
        'body' => 'required|string|max:1000',
        'parent_id' => 'nullable|exists:comments,id'
    ]);

    Comment::create([
        'post_id' => $request->post_id,
        'name' => $request->name,
        'body' => $request->body,
        'parent_id' => $request->parent_id,
        'status' => 0 // Default Pending
    ]);

    return back()->with('success', 'আপনার মন্তব্য গ্রহণ করা হয়েছে। এডমিন অনুমোদনের পর এটি প্রকাশিত হবে।');
}

// Store Reaction (AJAX)
public function storeReaction(Request $request)
{
    $request->validate([
        'post_id' => 'required|exists:posts,id',
        'type' => 'required|in:like,love,haha,sad,angry'
    ]);

    // 1. Update or Create the Reaction
    $existing = Reaction::where('post_id', $request->post_id)
                        ->where('ip_address', $request->ip())
                        ->first();

    if ($existing) {
        // Update existing reaction
        $existing->update(['type' => $request->type]);
    } else {
        // Create new
        Reaction::create([
            'post_id' => $request->post_id,
            'ip_address' => $request->ip(),
            'type' => $request->type
        ]);
    }

    // 2. Fetch Fresh Counts for ALL types (to handle switching reactions)
    $counts = [
        'like' => Reaction::where('post_id', $request->post_id)->where('type', 'like')->count(),
        'love' => Reaction::where('post_id', $request->post_id)->where('type', 'love')->count(),
        'haha' => Reaction::where('post_id', $request->post_id)->where('type', 'haha')->count(),
        'sad'  => Reaction::where('post_id', $request->post_id)->where('type', 'sad')->count(),
        'angry'=> Reaction::where('post_id', $request->post_id)->where('type', 'angry')->count(),
    ];

    return response()->json($counts);
}


// ভিডিও ডিটেইলস মেথড
public function videoDetail($slug)
{
    $video = VideoNews::where('slug', $slug)->firstOrFail();

    // ভিউ কাউন্ট বাড়ানো
    $video->increment('view_count');

    // রিলেটেড বা অন্যান্য ভিডিও
    $relatedVideos = VideoNews::where('slug', '!=', $slug)
                        ->where('status', 'approved')
->where('language', 'bn')
                        ->where('draft_status', 0)
                        ->where('trash_status', 0)
                        ->orderBy('id', 'desc')
                        ->take(5)
                        ->get();

    return view('front.video_gallery.detail', compact('video', 'relatedVideos'));
}


public function videoList(Request $request)
{
    // ১. ভিডিও ডাটা আনা (প্যাজিনেশন সহ)
    $videos = VideoNews::where('status', 'approved')
                ->where('draft_status', 0)
                          ->where('language', 'bn') 
                ->where('trash_status', 0)
                ->orderBy('id', 'desc')
                ->paginate(12); // প্রতি পেজে ১২টি ভিডিও

    // ২. AJAX রিকোয়েস্ট চেক (প্যাজিনেশনের জন্য)
    if ($request->ajax()) {
        return view('front.video_gallery._video_list_partial', compact('videos'))->render();
    }

    // ৩. ভিউ লোড করা
    return view('front.video_gallery.list', compact('videos'));
}


public function search(Request $request)
{
    $query = $request->input('q');
    $categoryId = $request->input('category');
    $timeFilter = $request->input('time');
    $sort = $request->input('sort', 'desc');


    //dd($query);

    $posts = Post::query()
        ->with('categories')
        ->where('status', 'approved')
           ->where('language', 'bn')
        ->where('draft_status', 0)
        ->where('trash_status', 0)
        ->where('language', 'bn');

    // 1. Keyword Search
    if (!empty($query)) {
        $posts->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('subtitle', 'like', "%{$query}%")
              ->orWhere('content', 'like', "%{$query}%");
        });
    }

    // 2. Category Filter
    if (!empty($categoryId) && $categoryId != 'all') {
        $posts->whereHas('categories', function($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    // 3. Time Filter
    if (!empty($timeFilter)) {
        switch ($timeFilter) {
            case '24h':
                $posts->where('created_at', '>=', Carbon::now()->subDay());
                break;
            case '7d':
                $posts->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case '30d':
                $posts->where('created_at', '>=', Carbon::now()->subDays(30));
                break;
        }
    }

    // 4. Sorting
    $posts->orderBy('created_at', $sort == 'old' ? 'asc' : 'desc');

    // 5. Pagination (Append query params)
    $results = $posts->paginate(10)->withQueryString();

    // 6. Handle AJAX Request
    if ($request->ajax()) {
        return view('front.search.search_results_partial', compact('results', 'query'))->render();
    }

    $categories = Category::where('status', 1)->select('id', 'name')->get();

    return view('front.search.search', compact('results', 'categories', 'query'));
}


public function aboutUs()
    {
        // 1. Fetch Description
        $about = AboutUs::first();

        // 2. Find the 'Contributor' Category ID
        $cat = AdministrativeCategory::where('eng_name', 'Contributor')
                ->orWhere('eng_name', 'contributor')
                ->first();

        $contributors = collect([]);

        if ($cat) {
            // 3. Query Administrative where category_id array contains this ID
            // We use 'like' here which is safest for TEXT columns storing JSON (e.g. ["1","2"])
            // If your column is officially 'json' type, you can use whereJsonContains('category_id', (string)$cat->id)
            
            $contributors = Administrative::where('category_id', 'like', '%"'.$cat->id.'"%')
                ->with('socialLinks')
                ->where('status', 1)
                ->orderBy('order_id', 'asc')
                ->get();
        }

        return view('front.about_us', compact('about', 'contributors'));
    }


    public function contactUs()
    {
        return view('front.contact_us');
    }



    public function team()
{
    // 1. Fetch all active members sorted by order_id
    $allMembers = Administrative::with(['socialLinks'])
        ->where('status', 1)
        ->orderBy('order_id', 'asc')
        ->get();

    // 2. Identify Top Designation IDs (Publisher, Editor-in-Chief)
    // We search loosely to catch "Publisher", "Editor-in-Chief", "Publisher & Editor" etc.
    $topDesignationIds = Designation::where('eng_name', 'LIKE', '%Publisher%')
        ->orWhere('eng_name', 'LIKE', '%Editor%Chief%') // Covers "Editor-in-Chief", "Editor in Chief"
        ->pluck('id')
        ->toArray();

    // 3. Separate Top Leaders from Regular Members
    $topLeaders = $allMembers->filter(function ($member) use ($topDesignationIds) {
        $memberDesignations = $member->designation_id ?? []; // This is an array due to $casts
        // Check if the member has ANY of the top designation IDs
        // We use array_intersect to check for matching values
        return !empty(array_intersect($memberDesignations, $topDesignationIds));
    });

    // Get IDs of top leaders to exclude them from the category list
    $topLeaderIds = $topLeaders->pluck('id')->toArray();

    // 4. Prepare Category-wise List for the rest
    $categories = AdministrativeCategory::where('status', 1)
        ->orderBy('id', 'asc') // You can change this to order_id if you have it in categories table
        ->get();

    $categorizedTeam = [];

    foreach ($categories as $category) {
        // Filter members belonging to this category who are NOT top leaders
        $members = $allMembers->filter(function ($member) use ($category, $topLeaderIds) {
            if (in_array($member->id, $topLeaderIds)) return false; // Skip top leaders
            
            $memberCats = $member->category_id ?? [];
            return in_array($category->id, $memberCats);
        });

        if ($members->isNotEmpty()) {
            $categorizedTeam[] = [
                'info' => $category,
                'members' => $members
            ];
        }
    }

    return view('front.team', compact('topLeaders', 'categorizedTeam'));
}


public function contributor()
    {
        // 1. Find the 'Contributor' Category ID by Name (English or Bangla)
        $category = AdministrativeCategory::where('eng_name', 'LIKE', '%Contributor%')
            ->orWhere('name', 'LIKE', '%কন্ট্রিবিউটর%')
            ->first();

        $contributors = collect([]);

        if ($category) {
            // 2. Fetch Active Members and filter by this Category ID
            // Since category_id is an array (casted in Model), we filter using PHP collection
            $contributors = Administrative::with('socialLinks')
                ->where('status', 1)
                ->orderBy('order_id', 'asc')
                ->get()
                ->filter(function ($member) use ($category) {
                    $cats = $member->category_id ?? [];
                    return in_array($category->id, $cats);
                });
        }

        return view('front.contributor', compact('contributors', 'category'));
    }
public function archive(Request $request)
{
    // ১. ইনপুট নেওয়া (ডিফল্ট আজকের তারিখ অথবা রিকোয়েস্টের তারিখ)
    $searchDate = $request->input('date');
    $searchCategory = $request->input('category');
    
    // ২. কুয়েরি বিল্ডার শুরু
    $query = Post::with('categories')
        ->where('status', 'approved')
        ->where('draft_status', 0)
        ->where('trash_status', 0)
        ->where('language', 'bn'); // বাংলা নিউজ

    // ৩. ফিল্টার লজিক
    if ($searchDate) {
        $query->whereDate('created_at', $searchDate);
    }
    
    if ($searchCategory && $searchCategory != 'all') {
        $query->where('category_id', $searchCategory);
    }

    $posts = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

    // ৪. ক্যাটাগরি লিস্ট (ড্রপডাউনের জন্য)
    $categories = Category::where('status', 1)->select('id', 'name')->get();

    // ৫. সাইডবার: মাসের আর্কাইভ (Monthly Archive Count)
    // এটি গত ৬ মাসের ডাটা দেখাবে
    $monthlyArchives = Post::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
        ->where('status', 'approved')
        ->where('language', 'bn')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(6)
        ->get();

    // ৬. ক্যালেন্ডার লজিক (বর্তমান মাস বা সিলেক্ট করা মাসের জন্য)
    // যদি ইউজার কোনো ডেট সিলেক্ট করে থাকে, সেই মাসের ক্যালেন্ডার দেখাব, নাহলে চলতি মাসের
    $currentDate = $searchDate ? Carbon::parse($searchDate) : Carbon::now();
    
    $calendarData = [
        'year' => $currentDate->year,
        'month' => $currentDate->month,
        'monthName' => $currentDate->format('F Y'), // English Month Name needed for Carbon logic
        'banglaMonth' => $currentDate->locale('bn')->isoFormat('MMMM YYYY'), // For Display
        'daysInMonth' => $currentDate->daysInMonth,
        'startDayOfWeek' => Carbon::createFromDate($currentDate->year, $currentDate->month, 1)->dayOfWeek, // 0=Sunday, 6=Saturday
    ];

    return view('front.archive', compact(
        'posts', 
        'categories', 
        'monthlyArchives', 
        'calendarData', 
        'searchDate', 
        'searchCategory'
    ));
}


   public function privacyPolicy()
    {
        // ডাটাবেজ থেকে প্রথম রো (row) টি আনছি
        $data = ExtraPage::first();
        return view('front.privacy_policy', compact('data'));
    }

    public function termsCondition()
    {
        $data = ExtraPage::first();
        return view('front.terms_condition', compact('data'));
    }

    // Contact Us Form Submission
    public function contactUsPost(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20', // ফোন অপশনাল রাখা হয়েছে
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000', // 'message' ইনপুট থেকে আসবে
        ]);

        // ২. ডাটা সেভ করা
        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'msg' => $request->message, // ডাটাবেজে কলামের নাম 'msg'
        ]);

        // ৩. সাকসেস মেসেজ রিটার্ন
        // সুইট অ্যালার্ট বা সাধারণ সেশন মেসেজ ব্যবহার করতে পারেন
        return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে! শীঘ্রই আমরা যোগাযোগ করব।');
    }


}

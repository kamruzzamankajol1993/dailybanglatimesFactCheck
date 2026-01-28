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
use App\Models\FactCheck;
use App\Models\FactCheckRequest;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;


class FrontController extends Controller
{


public function submitFactCheckRequest(Request $request)
    {
        // ১. ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            // ইমেজ ভ্যালিডেশন: ম্যাক্স ১ মেগাবাইট (1024 KB)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ], [
            'title.required' => 'দয়া করে খবরের শিরোনাম দিন।',
            'image.max' => 'ছবির সাইজ ১ মেগাবাইটের বেশি হতে পারবে না।',
            'image.image' => 'ফাইলটি অবশ্যই একটি ছবি হতে হবে।',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $imagePath = null;

            // ২. ইমেজ আপলোড (Intervention Image ব্যবহার করে)
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'req_' . time() . '.' . $image->getClientOriginalExtension();
                
                // ফোল্ডার পাথ (public/uploads/requests)
                $destinationPath = public_path('uploads/requests');

                // ফোল্ডার না থাকলে তৈরি করা
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }

                // Intervention দিয়ে ইমেজ রিড করা
                $img = Image::read($image->getRealPath());

                // রিসাইজ করা (প্রয়োজন অনুযায়ী, যেমন সর্বোচ্চ ৮০০ পিক্সেল চওড়া)
                // aspect ratio ঠিক রেখে রিসাইজ হবে
                $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // ইমেজ সেভ করা
                $img->save($destinationPath . '/' . $imageName);
                
                // ডাটাবেসে সেভ করার জন্য পাথ
                $imagePath = 'public/uploads/requests/' . $imageName;
            }

            // ৩. ডাটাবেসে সেভ
            FactCheckRequest::create([
                'title' => $request->title,
                'link' => $request->link,
                'description' => $request->description,
                'image' => $imagePath,
                'status' => 'pending',
                'created_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'আপনার রিপোর্টটি সফলভাবে জমা হয়েছে! আমরা এটি যাচাই করে দেখব।'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'সার্ভারে সমস্যা হয়েছে: ' . $e->getMessage()
            ]);
        }
    }

// ==========================================
    // ৩. ডাইনামিক সার্চ (Google API + Gemini + AJAX)
    // ==========================================
    public function searchFactCheck(Request $request)
    {
        $searchQuery = $request->input('query');
        
        if (empty($searchQuery)) {
            return redirect()->back()->with('error', 'অনুগ্রহ করে কিছু লিখুন।');
        }

        // ১. লোকাল ডাটাবেসে খোঁজা
        $results = FactCheck::with(['post', 'factCheckRequest'])
            ->where(function($q) use ($searchQuery) {
                $q->whereHas('post', function($p) use ($searchQuery) {
                    $p->where('title', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('content', 'LIKE', "%{$searchQuery}%");
                })
                ->orWhereHas('factCheckRequest', function($r) use ($searchQuery) {
                    $r->where('title', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('link', 'LIKE', "%{$searchQuery}%");
                });
            })
            ->latest()
            ->paginate(10); // প্যাজিনেশন ১০টি করে

        // ২. যদি ডাটাবেসে না থাকে -> Google API এবং Gemini চেক
        if ($results->isEmpty() && $results->currentPage() == 1) {
            
            // ক. প্রথমে Google Fact Check API চেক
            $googleApiKey = 'AIzaSyAjzFQvC3DZ7to8VJwLYrqbFLd-Z6WBSKc'; // আপনার Google Key
            $apiResponse = Http::get('https://factchecktools.googleapis.com/v1alpha1/claims:search', [
                'query' => $searchQuery,
                'key' => $googleApiKey,
            ])->json();

            $verdict = 'Unverified';
            $confidence = 0.00;
            $source = 'System';
            $apiData = [];

            // যদি Google এ ডাটা পাওয়া যায়
            if (!empty($apiResponse['claims'])) {
                $claim = $apiResponse['claims'][0];
                $review = $claim['claimReview'][0] ?? [];
                
                $verdict = $review['textualRating'] ?? 'Verified (Google)';
                $confidence = 1.00; // Google মানে ১০০% বিশ্বাসযোগ্য
                $source = 'Google Fact Check Tools';
                $apiData = $apiResponse;
            } 
            // খ. Google না পেলে -> Gemini AI চেক
            else {
                $geminiApiKey = 'AIzaSyAW-rKtkXBGGPXZtlcIt8_SYYGIYFZq2to'; // আপনার Gemini Key
                $aiResult = $this->checkWithGemini($searchQuery, $searchQuery, $geminiApiKey);

                $verdict = $aiResult['verdict'] ?? 'Unverified';
                $confidence = $aiResult['confidence'] ?? 0.00;
                $source = 'AI Auto Search';
                $apiData = $aiResult;
            }

            // গ. ডাটাবেসে সেভ করা (যাতে ভবিষ্যতে লোকাল সার্চে আসে)
            $newReq = FactCheckRequest::create([
                'title' => $searchQuery,
                'status' => 'pending',
                'admin_verdict' => $verdict,
                'created_at' => now(),
            ]);

            $factCheck = FactCheck::create([
                'user_submitted_news_id' => $newReq->id,
                'verdict' => $verdict,
                'confidence_score' => $confidence,
                'check_source' => $source,
                'api_response_raw' => json_encode($apiData),
            ]);

            // ঘ. নতুন রেজাল্টটি কালেকশনে কনভার্ট করে প্যাজিনেটর বানানো (ভিউতে দেখানোর জন্য)
            $item = $factCheck->load('factCheckRequest');
            $results = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([$item]), 1, 10, 1, ['path' => route('front.factCheck.search'), 'query' => $request->query()]
            );
        }

        // ৩. AJAX রিকোয়েস্ট হ্যান্ডলিং (প্যাজিনেশনের জন্য)
        if ($request->ajax()) {
            return view('front.search._search_results_partial', compact('results', 'searchQuery'))->render();
        }

        // ৪. সাধারণ পেজ লোড (সাইডবার সহ)
        $recentCategories = \App\Models\Category::where('view_on_fact_check_site',1)
             ->withCount('factCheckRequests')
            ->orderBy('fact_check_requests_count', 'desc') // যেটায় বেশি রিকোয়েস্ট সেটা আগে দেখাবে
            ->take(5)
            ->get();
        
        
        return view('front.search.customer_search_result', compact('results', 'searchQuery', 'recentCategories'));
    }

/**
     * Gemini AI Helper Function
     */
    private function checkWithGemini($title, $content, $apiKey)
    {
        try {
            $model = 'gemini-2.5-flash'; 
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            // বর্তমান তারিখ (AI কে কনটেক্সট দেওয়ার জন্য)
            $today = date('d M Y');

            // কন্টেন্ট ক্লিন করা
            $cleanContent = strip_tags($content);
            $shortContent = mb_substr($cleanContent, 0, 800); 

            // --- প্রম্পট তৈরি ---
            $prompt = "You are a Senior Fact Checker. Today is {$today}.
            
            Analyze this News Claim:
            Headline: '{$title}'
            Content: '{$shortContent}'

            **RULES:**
            1. If the news cites official sources (Govt, ISPR, Ministers), verify as **'Likely True'**.
            2. If it is a known rumor or impossible event, verify as **'False'**.
            3. If verification is impossible without more info, verify as **'Unverified'**.

            Output strictly JSON: {\"verdict\": \"Likely True\" or \"False\" or \"Unverified\", \"confidence\": 0.90}
            Do not provide any markdown formatting or extra text.";

            // API কল
            $response = Http::post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            $result = $response->json();

            // রেসপন্স পার্সিং
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $rawText = $result['candidates'][0]['content']['parts'][0]['text'];

                // JSON বের করা (যদি Markdown এর ভেতরে থাকে)
                if (preg_match('/\{.*\}/s', $rawText, $matches)) {
                    $jsonString = $matches[0];
                    $parsed = json_decode($jsonString, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return [
                            'verdict' => $parsed['verdict'] ?? 'Unverified',
                            'confidence' => $parsed['confidence'] ?? 0.50
                        ];
                    }
                }
                
                // জেসন না পেলে ডিফল্ট রেসপন্স
                return [
                    'verdict' => 'Unverified',
                    'confidence' => 0.00
                ];
            }
            
            return ['verdict' => 'System Error', 'confidence' => 0.00];

        } catch (\Exception $e) {
            return ['verdict' => 'Error', 'confidence' => 0.00];
        }
    }
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

        $recentFactChecks = FactCheck::with(['post', 'factCheckRequest'])
        ->where(function($query) {
            // ১. হয় এটি অ্যাডমিন পোস্ট (post_id আছে)
            $query->whereNotNull('post_id')
            // ২. অথবা এটি ইউজার রিকোয়েস্ট এবং তার স্ট্যাটাস 'checked'
                  ->orWhereHas('factCheckRequest', function($q) {
                      $q->where('status', 'checked');
                  });
        })
        ->latest()
        ->take(8) // ৮টি আইটেম দেখাবো
        ->get();

        return view('front.home_page.index', compact(
            'latestPosts', 'popularPosts', 'madamUnderNews', 'sliderPosts', 'randomNews','recentFactChecks'
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
        // ১. ক্যাটাগরি চেক
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->where('view_on_fact_check_site', 1)
            ->firstOrFail();

        // ২. বেস কুয়েরি (Post এবং Request দুই টেবিল থেকেই)
        $query = \App\Models\FactCheck::with(['post.categories', 'factCheckRequest.category'])
            ->where(function($mainQ) use ($category) {
                // কন্ডিশন A: অ্যাডমিন পোস্ট
                $mainQ->whereHas('post', function($q) use ($category) {
                    $q->whereHas('categories', function($c) use ($category) {
                        $c->where('categories.id', $category->id);
                    })
                    ->where('status', 'approved')
                    ->where('trash_status', 0);
                });

                // অথবা
                // কন্ডিশন B: ইউজার রিকোয়েস্ট (Checked)
                $mainQ->orWhereHas('factCheckRequest', function($q) use ($category) {
                    $q->where('category_id', $category->id)
                      ->where('status', 'checked');
                });
            });

        // --- ৩. ফিল্টার লজিক (Verdict) ---
        if ($request->has('verdict') && !empty($request->verdict)) {
            // verdict অ্যারে হিসেবে আসবে (যেমন: ['fake', 'true'])
            $verdicts = is_array($request->verdict) ? $request->verdict : explode(',', $request->verdict);
            
            $query->where(function($q) use ($verdicts) {
                foreach ($verdicts as $v) {
                    if ($v == 'true') {
                        $q->orWhere('verdict', 'LIKE', '%True%')->orWhere('verdict', 'LIKE', '%Likely True%');
                    } elseif ($v == 'fake') {
                        $q->orWhere('verdict', 'LIKE', '%False%')->orWhere('verdict', 'LIKE', '%Fake%');
                    } elseif ($v == 'misleading') {
                        $q->orWhere('verdict', 'LIKE', '%Misleading%');
                    }
                }
            });
        }

        // --- ৪. ফিল্টার লজিক (Date) ---
        if ($request->has('date_filter') && $request->date_filter != 'all') {
            if ($request->date_filter == '24h') {
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDay());
            } elseif ($request->date_filter == '7d') {
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7));
            } elseif ($request->date_filter == '30d') {
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30));
            }
        }

        // ৫. ফাইনাল ডাটা ফেচ
        $posts = $query->latest()->paginate(12)->withQueryString(); // withQueryString প্যাজিনেশন লিংক ঠিক রাখবে

        // ৬. AJAX রেসপন্স
        if ($request->ajax()) {
            return view('front.news._category_posts_partial', compact('posts'))->render();
        }

        return view('front.news.list', compact('category', 'posts'));
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
        $post = null;
        $isUserRequest = false; 
        $factCheckResult = null; 

        // ১. চেক করা এটি User Request (ID) কিনা
        if (is_numeric($slug)) {
            $req = \App\Models\FactCheckRequest::with(['category', 'factCheckResult'])
                ->where('id', $slug)
                ->where('status', 'checked')
                ->first();

            if ($req) {
                $post = $req;
                $isUserRequest = true;
                $factCheckResult = $req->factCheckResult;
            }
        }

        // ২. যদি ID না হয় -> Post টেবিল (Slug) চেক করা
        if (!$post) {
            $post = Post::with(['author', 'categories', 'factCheck']) 
                ->withCount([
                    'reactions as like_count' => function ($q) { $q->where('type', 'like'); },
                    'reactions as love_count' => function ($q) { $q->where('type', 'love'); },
                ])
                ->where('language', 'bn')
                ->where('slug', $slug)
                ->where('status', 'approved')
                ->firstOrFail();
            
            $isUserRequest = false;
            $factCheckResult = $post->factCheck; 
            
            $post->increment('view_count');
        }

        // ৩. সাইডবার: লেটেস্ট নিউজ
        $latestNews = Post::with('factCheck')
        ->has('factCheck')
            ->where('status', 'approved')
            ->where('language', 'bn')
            ->where('trash_status', 0)
            ->latest()
            ->take(5)
            ->get();

        // ৪. সাইডবার: ক্যাটাগরি (Tags এর পরিবর্তে)
        // এখানে শুধুমাত্র ফ্যাক্ট চেক সাইটের জন্য এনাবল করা ক্যাটাগরিগুলো আনা হচ্ছে
        $sidebarCategories = \App\Models\Category::where('status', 1)
            ->where('view_on_fact_check_site', 1)
            ->orderBy('order_id', 'asc')
            ->take(5) // ১০-১৫টি ক্যাটাগরি দেখাবে
            ->get();

        return view('front.news.details', compact(
            'post', 
            'isUserRequest', 
            'factCheckResult', 
            'latestNews', 
            'sidebarCategories' // <--- আপডেটেড ভেরিয়েবল
        ));
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


// ==========================================
    // মেইন সার্চ ফাংশন (Database Based)
    // ==========================================
    public function search(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category');
        $timeFilter = $request->input('time');
        $sort = $request->input('sort', 'desc');

        // ১. বেস কুয়েরি: FactCheck টেবিল (Post + User Request)
        $factChecks = FactCheck::with(['post.categories', 'factCheckRequest.category'])
            ->where(function($mainQ) {
                 // ক. অ্যাডমিন পোস্ট (Approved & Active)
                 $mainQ->whereHas('post', function($p) {
                     $p->where('status', 'approved')
                       ->where('trash_status', 0)
                       ->where('draft_status', 0)
                       ->where('language', 'bn');
                 })
                 // খ. অথবা ইউজার রিকোয়েস্ট (Checked)
                 ->orWhereHas('factCheckRequest', function($r) {
                     $r->where('status', 'checked');
                 });
            });

        // ২. কি-ওয়ার্ড সার্চ (Keyword Search)
        if (!empty($query)) {
            $factChecks->where(function($q) use ($query) {
                // Post টেবিলে খুঁজবে
                $q->whereHas('post', function($p) use ($query) {
                    $p->where('title', 'like', "%{$query}%")
                      ->orWhere('subtitle', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })
                // Request টেবিলে খুঁজবে
                ->orWhereHas('factCheckRequest', function($r) use ($query) {
                    $r->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('link', 'like', "%{$query}%");
                });
            });
        }

        // ৩. ক্যাটাগরি ফিল্টার (Category Filter)
        if (!empty($categoryId) && $categoryId != 'all') {
            $factChecks->where(function($q) use ($categoryId) {
                $q->whereHas('post', function($p) use ($categoryId) {
                     $p->whereHas('categories', function($c) use ($categoryId) {
                         $c->where('categories.id', $categoryId);
                     });
                })
                ->orWhereHas('factCheckRequest', function($r) use ($categoryId) {
                    $r->where('category_id', $categoryId);
                });
            });
        }

        // ৪. সময় ফিল্টার (Time Filter)
        if (!empty($timeFilter)) {
            $date = null;
            if ($timeFilter == '24h') $date = Carbon::now()->subDay();
            elseif ($timeFilter == '7d') $date = Carbon::now()->subDays(7);
            elseif ($timeFilter == '30d') $date = Carbon::now()->subDays(30);

            if ($date) {
                $factChecks->where('created_at', '>=', $date);
            }
        }

        // ৫. সর্টিং (Sorting)
        $factChecks->orderBy('created_at', $sort == 'old' ? 'asc' : 'desc');

        // ৬. প্যাজিনেশন
        $results = $factChecks->paginate(10)->withQueryString();

        // ৭. AJAX রিকোয়েস্ট হ্যান্ডলিং
        if ($request->ajax()) {
            return view('front.search.search_results_partial', compact('results', 'query'))->render();
        }

        // ক্যাটাগরি লিস্ট (ফিল্টারের জন্য)
        $categories = Category::where('status', 1) ->where('view_on_fact_check_site', 1)
            ->orderBy('order_id', 'asc')
       
            ->get();

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

    // ==========================================
    // সকল ফ্যাক্ট-চেক নিউজ (আরও দেখুন পেজ)
    // ==========================================
    public function latestFactChecks(Request $request)
    {
        // ১. ডামি ক্যাটাগরি অবজেক্ট (যাতে list.blade.php এর ডিজাইন ঠিক থাকে)
        $category = (object) [
            'name' => 'সাম্প্রতিক সব যাচাই', 
            'slug' => 'latest',
            'id' => 0
        ];

        // ২. মেইন কুয়েরি (Post এবং FactCheckRequest টেবিল থেকে)
        $query = \App\Models\FactCheck::with(['post.categories', 'factCheckRequest.category'])
            ->where(function($q) {
                // কন্ডিশন A: অ্যাডমিন পোস্ট
                $q->whereHas('post', function($p) {
                     $p->where('status', 'approved')
                       ->where('trash_status', 0)
                       ->where('language', 'bn');
                })
                // অথবা
                // কন্ডিশন B: ইউজার রিকোয়েস্ট (Checked)
                ->orWhereHas('factCheckRequest', function($r) {
                    $r->where('status', 'checked');
                });
            });

        // --- ৩. ফিল্টার লজিক (Verdict - সত্য/মিথ্যা) ---
        if ($request->has('verdict') && !empty($request->verdict)) {
            $verdicts = is_array($request->verdict) ? $request->verdict : explode(',', $request->verdict);
            
            $query->where(function($q) use ($verdicts) {
                foreach ($verdicts as $v) {
                    if ($v == 'true') {
                        $q->orWhere('verdict', 'LIKE', '%True%')->orWhere('verdict', 'LIKE', '%Likely True%');
                    } elseif ($v == 'fake') {
                        $q->orWhere('verdict', 'LIKE', '%False%')->orWhere('verdict', 'LIKE', '%Fake%');
                    } elseif ($v == 'misleading') {
                        $q->orWhere('verdict', 'LIKE', '%Misleading%');
                    }
                }
            });
        }

        // --- ৪. ফিল্টার লজিক (Date - তারিখ) ---
        if ($request->has('date_filter') && $request->date_filter != 'all') {
            if ($request->date_filter == '24h') {
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDay());
            } elseif ($request->date_filter == '7d') {
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7));
            } elseif ($request->date_filter == '30d') {
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30));
            }
        }

        // ৫. ফাইনাল ডাটা ফেচ
        $posts = $query->latest()->paginate(12)->withQueryString();

        // ৬. AJAX রিকোয়েস্ট হ্যান্ডলিং (প্যাজিনেশন ও ফিল্টারের জন্য)
        if ($request->ajax()) {
            // আমরা আগের তৈরি করা পার্শিয়াল ভিউটিই ব্যবহার করব
            return view('front.news._category_posts_partial', compact('posts'))->render();
        }

        // ৭. ভিউ লোড (list.blade.php রিইউজ করা হচ্ছে)
        return view('front.news.list', compact('category', 'posts'));
    }

    // ফ্যাক্ট ফাইল পেজ
    public function factFile()
    {
        // এখানে স্ট্যাটিক পেজ দেখানো হচ্ছে, পরবর্তীতে চাইলে ডাটাবেস থেকে (ExtraPage মডেল) ডাটা আনতে পারেন
        return view('front.pages.fact_file');
    }

    // মিডিয়া লিটারেসি পেজ
    public function mediaLiteracy()
    {
        return view('front.pages.media_literacy');
    }

public function methodology()
    {
        return view('front.pages.methodology');
    }
}

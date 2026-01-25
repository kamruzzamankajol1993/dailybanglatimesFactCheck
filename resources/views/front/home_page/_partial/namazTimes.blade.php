<section class="daily-info-section py-4 bg-light">
    <div class="container">
        <div class="row g-4">
            
            {{-- ========================================== --}}
            {{-- বাম পাশ: নামাজের সময়সূচি --}}
            {{-- ========================================== --}}
            <div class="col-lg-6">
                <div class="daily-card h-100 shadow-sm bg-white rounded-3 overflow-hidden border-top-namaz">
                    
                    {{-- হেডার --}}
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold text-success">
                            <i class="fas fa-mosque me-2"></i>নামাজের সময়সূচি
                        </h5>
                        <small class="text-muted fw-bold bg-light px-2 py-1 rounded" id="hijriDateDisplay">
                            <i class="fas fa-spinner fa-spin"></i>
                        </small>
                    </div>

                    {{-- বডি --}}
                    <div class="card-body p-3">
                        <div id="namaz-loader" class="text-center py-4">
                            <div class="spinner-border text-success" role="status"></div>
                        </div>
                        
                        <div class="row g-2 d-none" id="namaz-content">
                            {{-- জাভাস্ক্রিপ্ট দিয়ে এখানে লিস্ট জেনারেট হবে --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- ডান পাশ: আবহাওয়ার খবর --}}
            {{-- ========================================== --}}
            <div class="col-lg-6">
                <div class="daily-card h-100 shadow-sm bg-white rounded-3 overflow-hidden border-top-weather">
                    
                    {{-- হেডার --}}
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold text-warning-dark">
                            <i class="fas fa-cloud-sun-rain me-2"></i>আবহাওয়া (ঢাকা)
                        </h5>
                        <span class="badge bg-warning text-dark"><i class="fas fa-circle text-danger me-1 small"></i>লাইভ</span>
                    </div>

                    {{-- বডি --}}
                    <div class="card-body p-3 d-flex align-items-center justify-content-center">
                        
                        <div id="weather-loader" class="text-center py-4">
                            <div class="spinner-border text-warning" role="status"></div>
                        </div>

                        <div id="weather-content" class="w-100 d-none">
                            <div class="row align-items-center">
                                
                                {{-- বামে: বড় তাপমাত্রা --}}
                                <div class="col-5 text-center border-end">
                                    <img id="weather-icon" src="https://cdn-icons-png.flaticon.com/512/869/869869.png" alt="Sun" width="60" class="mb-2">
                                    <h2 class="display-4 fw-bold mb-0 text-dark" id="current-temp">--°</h2>
                                    <p class="mb-0 text-muted fw-bold small" id="weather-desc">--</p>
                                </div>

                                {{-- ডানে: বিস্তারিত --}}
                                <div class="col-7">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="p-2 bg-light rounded d-flex align-items-center">
                                                <i class="fas fa-temperature-high text-danger me-3 fs-5"></i>
                                                <div>
                                                    <small class="text-muted d-block" style="line-height:1;">অনুভূত হচ্ছে</small>
                                                    <span class="fw-bold" id="feels-like">--</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-2 bg-light rounded d-flex align-items-center">
                                                <i class="fas fa-tint text-primary me-3 fs-5"></i>
                                                <div>
                                                    <small class="text-muted d-block" style="line-height:1;">আর্দ্রতা</small>
                                                    <span class="fw-bold" id="humidity">--</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-2 bg-light rounded d-flex align-items-center">
                                                <i class="fas fa-wind text-secondary me-3 fs-5"></i>
                                                <div>
                                                    <small class="text-muted d-block" style="line-height:1;">বাতাসের গতি</small>
                                                    <span class="fw-bold" id="wind-speed">--</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    /* কাস্টম ডিজাইন */
    .daily-card { transition: transform 0.3s ease; }
    .daily-card:hover { transform: translateY(-3px); }
    
    .border-top-namaz { border-top: 4px solid #198754; }
    .border-top-weather { border-top: 4px solid #ffc107; }
    
    .text-warning-dark { color: #d39e00; }

    /* নামাজের সময়ের লিস্ট ডিজাইন */
    .waqt-item {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 8px 10px;
        transition: 0.2s;
        border: 1px solid #eee;
    }
    .waqt-item:hover {
        background-color: #e9ecef;
        border-color: #198754;
    }
    .waqt-name { font-size: 14px; color: #555; }
    .waqt-time { font-size: 16px; color: #000; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchDailyData();
    });

    function fetchDailyData() {
        // ১. নামাজের সময় API
        fetch("https://api.aladhan.com/v1/timingsByCity?city=Dhaka&country=Bangladesh&method=1&school=1")
            .then(res => res.json())
            .then(data => {
                if(data.code === 200) renderNamaz(data.data);
            });

        // ২. আবহাওয়া API
        fetch("https://api.open-meteo.com/v1/forecast?latitude=23.8103&longitude=90.4125&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,weather_code,wind_speed_10m&timezone=auto")
            .then(res => res.json())
            .then(data => renderWeather(data.current));
    }

    // --- রেন্ডার ফাংশন: নামাজ ---
    function renderNamaz(data) {
        const timings = data.timings;
        const hijri = data.date.hijri;
        document.getElementById('hijriDateDisplay').innerText = `${toBanglaNum(hijri.day)} ${hijri.month.en} ${toBanglaNum(hijri.year)} হিজরি`;

        const waqts = [
            { k: 'Fajr', n: 'ফজর', i: 'fa-cloud-sun' },
            { k: 'Sunrise', n: 'সূর্যোদয়', i: 'fa-sun', sun: true },
            { k: 'Dhuhr', n: 'জোহর', i: 'fa-sun' },
            { k: 'Asr', n: 'আছর', i: 'fa-cloud-sun' },
            { k: 'Maghrib', n: 'মাগরিব', i: 'fa-moon' },
            { k: 'Isha', n: 'এশা', i: 'fa-cloud-moon' }
        ];

        let html = '';
        waqts.forEach(w => {
            let time = formatTime(timings[w.k]);
            let iconColor = w.sun ? 'text-warning' : 'text-success';
            html += `
                <div class="col-4 col-md-4">
                    <div class="waqt-item text-center">
                        <i class="fas ${w.i} ${iconColor} mb-1"></i>
                        <div class="waqt-name fw-bold">${w.n}</div>
                        <div class="waqt-time fw-bold">${time}</div>
                    </div>
                </div>
            `;
        });

        document.getElementById('namaz-loader').classList.add('d-none');
        const container = document.getElementById('namaz-content');
        container.classList.remove('d-none');
        container.innerHTML = html;
    }

    // --- রেন্ডার ফাংশন: আবহাওয়া ---
    function renderWeather(current) {
        document.getElementById('current-temp').innerText = toBanglaNum(Math.round(current.temperature_2m)) + '°C';
        document.getElementById('feels-like').innerText = toBanglaNum(Math.round(current.apparent_temperature)) + '°C';
        document.getElementById('humidity').innerText = toBanglaNum(current.relative_humidity_2m) + '%';
        document.getElementById('wind-speed').innerText = toBanglaNum(current.wind_speed_10m) + ' কিমি/ঘ';
        document.getElementById('weather-desc').innerText = getWeatherCode(current.weather_code);

        // আইকন সেট করা (দিন/রাত এবং কোড অনুযায়ী)
        let iconSrc = current.is_day ? "https://cdn-icons-png.flaticon.com/512/869/869869.png" : "https://cdn-icons-png.flaticon.com/512/581/581601.png";
        if(current.weather_code > 50) iconSrc = "https://cdn-icons-png.flaticon.com/512/1146/1146858.png"; // বৃষ্টি
        document.getElementById('weather-icon').src = iconSrc;

        document.getElementById('weather-loader').classList.add('d-none');
        document.getElementById('weather-content').classList.remove('d-none');
    }

    // --- হেল্পার ফাংশন ---
    function formatTime(time24) {
        let [h, m] = time24.split(':');
        h = parseInt(h);
        let ampm = h >= 12 ? '' : ''; // স্পেস বাঁচানোর জন্য am/pm বাদ দিলাম, বা চাইলে দিতে পারেন
        h = h % 12 || 12;
        return toBanglaNum(`${h}:${m}`);
    }

    function toBanglaNum(str) {
        const en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str.toString().replace(/[0-9]/g, w => bn[en.indexOf(w)]);
    }

    function getWeatherCode(code) {
        if(code === 0) return 'পরিষ্কার আকাশ';
        if(code >= 1 && code <= 3) return 'আংশিক মেঘলা';
        if(code >= 45 && code <= 48) return 'কুয়াশাচ্ছন্ন';
        if(code >= 51 && code <= 67) return 'বৃষ্টিপাত';
        if(code >= 80 && code <= 99) return 'বজ্রবৃষ্টি';
        return 'সাধারণ';
    }
</script>
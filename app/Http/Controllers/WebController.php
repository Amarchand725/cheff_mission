<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\HowToRent;
use App\Models\Virtual_tour;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\Rental;
use App\Models\RV;
use App\Models\Deal;
use App\Models\Product;
use App\Models\AboutUs;
use App\Models\Category;
use App\Models\CarType;
use App\Models\City;
use App\Models\State;
use Auth;
use Hash;

class WebController extends Controller
{
    public function index ()
    {
        $abouts=AboutUs::where('status' , 1)->get();
        $products = Product::where('status',1)->get();
        $rentals = Rental::where('status' , 1)->get();
        $steprents = HowToRent::where('status',1)->get();
        return view('website.index', compact('products' , 'abouts' , 'steprents' , 'rentals'));
    }

    public function login()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        $page_title = 'Log In';
        return view('auth.login', compact('page_title'));
    }

    public function authenticate(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!empty($user) && $user->status==1 && $user->hasRole($request->user_type)){
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            }
            return redirect()->back()->with('error', 'Failed to login try again.!');
        }elseif(!empty($user) && $user->status==0){
            return redirect()->back()->with('error', 'Your account is not active verify your email we have sent you verification link.!');
        }else{
            return redirect()->back()->with('error', 'This is only for user login not found your account!');
        }
    }

    public function verifyEmail($token)
    {
        $user = User::where('verify_token', $token)->first();
        if(!empty($user)){
            $user->verify_token = null;
            $user->email_verified_at = date('Y-m-d H:i:s');
            if(!empty($user->temprary_email)){
                $user->email = $user->temprary_email;
                $user->temprary_email = null;
            }
            $user->update();

            return redirect()->route('login')->with('message', 'You are welcome. You can login from here.');
        }else{
            return redirect()->back()->with('error', 'Your token is expired');
        }
    }

//Reset password
    public function forgotPassword()
    {
        $page_title = 'Forgot Password';
        return view('web-views.login.forgot-password', compact('page_title'));
    }

    public function passwordResetLink(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('status', 1)->first();
        if(!empty($user)){
            $page_title = 'Change Password';
            do{
                $verify_token = uniqid();
            }while(User::where('verify_token', $verify_token)->first());

            $user->verify_token = $verify_token;
            $user->update();

            $details = [
                'from' => 'password-reset',
                'title' => "Hello!",
                'body' => "You are receiving this email because we recieved a password reset request for your account.",
                'verify_token' => $user->verify_token,
            ];

            \Mail::to($user->email)->send(new \App\Mail\Email($details));

            return redirect()->route('login')->with('message', 'We have emailed your password reset link!');
        }else{
            return redirect()->back()->with('error', 'Your email address is not matched.');
        }
    }

    public function resetPassword($verify_token)
    {
        $page_title = 'Reset Password';
        return view('web-views.login.change-password', compact('page_title', 'verify_token'));
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|same:confirm-password',
        ]);

        $user = User::where('verify_token', $request->verify_token)->where('status', 1)->first();
        $user->password = Hash::make($request->password);
        $user->verify_token = null;
        $user->update();

        if($user){
            return redirect()->route('login')->with('message', 'You have updated password. You can login again.');
        }else{
            return redirect()->back()->with('error', 'Something went wrong try again');
        }
    }

    public function sendEmail(Request $request)
    {
        if(!isset($request->type)){
            $this->validate($request, [
                'email' => 'required|email|unique:users,email',
            ]);
        }

        $user = User::where('email', Auth::user()->email)->first();

        do{
            $verify_token = uniqid();
        }while(User::where('verify_token', $verify_token)->first());

        $user->temprary_email = $request->email;
        $user->verify_token = $verify_token;
        $user->update();

        $details = [
            'from' => 'verify',
            'title' => "We have recieved update email request. First, you need to confirm your account. Just press the button below.",
            'body' => "If you have any questions, just reply to this email—we're always happy to help out.",
            'verify_token' => $user->verify_token,
        ];

        \Mail::to($request->email)->send(new \App\Mail\Email($details));

        return redirect()->back()->with('message', 'We have sent verification email. Click on link and get activation');
    }

    public function gallery()
    {
        $testimonials=Testimonial::where('status' ,'=', 1)->get();
        $models1=Gallery::where('category_id' , 1)->get();
        $models2=Gallery::where('category_id' , 2)->get();
        $models3=Gallery::where('category_id' , 3)->get();
        return view('website.gallery' , compact('models1' , 'models2' , 'models3' , 'testimonials'));
    }

    public function rental()
    {
        /* $testimonials=Testimonial::where('status' ,'=', 1)->get();
        $models=Product::where('status' , 1)->paginate(10);
        $rentals=Rental::where('status' , 1)->paginate(10);
        $rvs=RV::where('status' , 1)->paginate(10); */

        $categories = Category::where('status', 1)->get();
        $models = []; 
        $rentals = []; 
        $rvs = []; 
        foreach($categories as $category){
          if($category->name=="Car"){
            $rentals = Product::where('category_slug', $category->slug)->where('status', 1)->get();
          }else if($category->name=="Recreational vehicle (R.V)"){
            $rvs = Product::where('category_slug', $category->slug)->where('status', 1)->get();
          }
        }
        
        return view('website.product.index' , compact('models', 'rentals' , 'rvs'));
    }

    public function single($slug)
    {
      $product = Product::where('slug', $slug)->first();
      $cities = City::where('status', 1)->get();
      return view('website.product.single', compact('product', 'cities'));
    }

    public function tour()
    {
        return view('website.tour');
    }

    public function about()
    {
        $testimonials=Testimonial::where('status' ,'=', 1)->get();
        $abouts=AboutUs::where('status' ,  1)->get();
        return view('website.about'  , compact('testimonials' , 'abouts'));
    }

    public function deal()
    {
        $categories = Category::where('status', 1)->get();
        $property_best_deals = []; 
        $rental_best_deals = []; 
        $rv_best_deals = []; 
        foreach($categories as $category){
            if($category->name=="Car"){
                $rental_best_deals = Deal::where('category', $category->id)->where('end_date', '>=', date('Y-m-d'))->take(3)->get();
            }else if($category->name=="recreational-vehicle"){
                $rv_best_deals = Deal::where('category', $category->id)->where('end_date', '>=', date('Y-m-d'))->take(3)->get();
            }
        }

        return view('website.deals' , compact('rental_best_deals' , 'rv_best_deals'));
    }

    public function career()
    {
        $testimonials=Testimonial::where('status' ,'=', 1)->get();
        return view('website.career'  , compact('testimonials'));
    }
    /* public function city()
    {
      $cities = array (
        'New York' => 
        array (
          0 => 'New York',
          1 => 'Buffalo',
          2 => 'Rochester',
          3 => 'Yonkers',
          4 => 'Syracuse',
          5 => 'Albany',
          6 => 'New Rochelle',
          7 => 'Mount Vernon',
          8 => 'Schenectady',
          9 => 'Utica',
          10 => 'White Plains',
          11 => 'Hempstead',
          12 => 'Troy',
          13 => 'Niagara Falls',
          14 => 'Binghamton',
          15 => 'Freeport',
          16 => 'Valley Stream',
        ),
        'California' => 
        array (
          0 => 'Los Angeles',
          1 => 'San Diego',
          2 => 'San Jose',
          3 => 'San Francisco',
          4 => 'Fresno',
          5 => 'Sacramento',
          6 => 'Long Beach',
          7 => 'Oakland',
          8 => 'Bakersfield',
          9 => 'Anaheim',
          10 => 'Santa Ana',
          11 => 'Riverside',
          12 => 'Stockton',
          13 => 'Chula Vista',
          14 => 'Irvine',
          15 => 'Fremont',
          16 => 'San Bernardino',
          17 => 'Modesto',
          18 => 'Fontana',
          19 => 'Oxnard',
          20 => 'Moreno Valley',
          21 => 'Huntington Beach',
          22 => 'Glendale',
          23 => 'Santa Clarita',
          24 => 'Garden Grove',
          25 => 'Oceanside',
          26 => 'Rancho Cucamonga',
          27 => 'Santa Rosa',
          28 => 'Ontario',
          29 => 'Lancaster',
          30 => 'Elk Grove',
          31 => 'Corona',
          32 => 'Palmdale',
          33 => 'Salinas',
          34 => 'Pomona',
          35 => 'Hayward',
          36 => 'Escondido',
          37 => 'Torrance',
          38 => 'Sunnyvale',
          39 => 'Orange',
          40 => 'Fullerton',
          41 => 'Pasadena',
          42 => 'Thousand Oaks',
          43 => 'Visalia',
          44 => 'Simi Valley',
          45 => 'Concord',
          46 => 'Roseville',
          47 => 'Victorville',
          48 => 'Santa Clara',
          49 => 'Vallejo',
          50 => 'Berkeley',
          51 => 'El Monte',
          52 => 'Downey',
          53 => 'Costa Mesa',
          54 => 'Inglewood',
          55 => 'Carlsbad',
          56 => 'San Buenaventura (Ventura)',
          57 => 'Fairfield',
          58 => 'West Covina',
          59 => 'Murrieta',
          60 => 'Richmond',
          61 => 'Norwalk',
          62 => 'Antioch',
          63 => 'Temecula',
          64 => 'Burbank',
          65 => 'Daly City',
          66 => 'Rialto',
          67 => 'Santa Maria',
          68 => 'El Cajon',
          69 => 'San Mateo',
          70 => 'Clovis',
          71 => 'Compton',
          72 => 'Jurupa Valley',
          73 => 'Vista',
          74 => 'South Gate',
          75 => 'Mission Viejo',
          76 => 'Vacaville',
          77 => 'Carson',
          78 => 'Hesperia',
          79 => 'Santa Monica',
          80 => 'Westminster',
          81 => 'Redding',
          82 => 'Santa Barbara',
          83 => 'Chico',
          84 => 'Newport Beach',
          85 => 'San Leandro',
          86 => 'San Marcos',
          87 => 'Whittier',
          88 => 'Hawthorne',
          89 => 'Citrus Heights',
          90 => 'Tracy',
          91 => 'Alhambra',
          92 => 'Livermore',
          93 => 'Buena Park',
          94 => 'Menifee',
          95 => 'Hemet',
          96 => 'Lakewood',
          97 => 'Merced',
          98 => 'Chino',
          99 => 'Indio',
          100 => 'Redwood City',
          101 => 'Lake Forest',
          102 => 'Napa',
          103 => 'Tustin',
          104 => 'Bellflower',
          105 => 'Mountain View',
          106 => 'Chino Hills',
          107 => 'Baldwin Park',
          108 => 'Alameda',
          109 => 'Upland',
          110 => 'San Ramon',
          111 => 'Folsom',
          112 => 'Pleasanton',
          113 => 'Union City',
          114 => 'Perris',
          115 => 'Manteca',
          116 => 'Lynwood',
          117 => 'Apple Valley',
          118 => 'Redlands',
          119 => 'Turlock',
          120 => 'Milpitas',
          121 => 'Redondo Beach',
          122 => 'Rancho Cordova',
          123 => 'Yorba Linda',
          124 => 'Palo Alto',
          125 => 'Davis',
          126 => 'Camarillo',
          127 => 'Walnut Creek',
          128 => 'Pittsburg',
          129 => 'South San Francisco',
          130 => 'Yuba City',
          131 => 'San Clemente',
          132 => 'Laguna Niguel',
          133 => 'Pico Rivera',
          134 => 'Montebello',
          135 => 'Lodi',
          136 => 'Madera',
          137 => 'Santa Cruz',
          138 => 'La Habra',
          139 => 'Encinitas',
          140 => 'Monterey Park',
          141 => 'Tulare',
          142 => 'Cupertino',
          143 => 'Gardena',
          144 => 'National City',
          145 => 'Rocklin',
          146 => 'Petaluma',
          147 => 'Huntington Park',
          148 => 'San Rafael',
          149 => 'La Mesa',
          150 => 'Arcadia',
          151 => 'Fountain Valley',
          152 => 'Diamond Bar',
          153 => 'Woodland',
          154 => 'Santee',
          155 => 'Lake Elsinore',
          156 => 'Porterville',
          157 => 'Paramount',
          158 => 'Eastvale',
          159 => 'Rosemead',
          160 => 'Hanford',
          161 => 'Highland',
          162 => 'Brentwood',
          163 => 'Novato',
          164 => 'Colton',
          165 => 'Cathedral City',
          166 => 'Delano',
          167 => 'Yucaipa',
          168 => 'Watsonville',
          169 => 'Placentia',
          170 => 'Glendora',
          171 => 'Gilroy',
          172 => 'Palm Desert',
          173 => 'Cerritos',
          174 => 'West Sacramento',
          175 => 'Aliso Viejo',
          176 => 'Poway',
          177 => 'La Mirada',
          178 => 'Rancho Santa Margarita',
          179 => 'Cypress',
          180 => 'Dublin',
          181 => 'Covina',
          182 => 'Azusa',
          183 => 'Palm Springs',
          184 => 'San Luis Obispo',
          185 => 'Ceres',
          186 => 'San Jacinto',
          187 => 'Lincoln',
          188 => 'Newark',
          189 => 'Lompoc',
          190 => 'El Centro',
          191 => 'Danville',
          192 => 'Bell Gardens',
          193 => 'Coachella',
          194 => 'Rancho Palos Verdes',
          195 => 'San Bruno',
          196 => 'Rohnert Park',
          197 => 'Brea',
          198 => 'La Puente',
          199 => 'Campbell',
          200 => 'San Gabriel',
          201 => 'Beaumont',
          202 => 'Morgan Hill',
          203 => 'Culver City',
          204 => 'Calexico',
          205 => 'Stanton',
          206 => 'La Quinta',
          207 => 'Pacifica',
          208 => 'Montclair',
          209 => 'Oakley',
          210 => 'Monrovia',
          211 => 'Los Banos',
          212 => 'Martinez',
        ),
        'Illinois' => 
        array (
          0 => 'Chicago',
          1 => 'Aurora',
          2 => 'Rockford',
          3 => 'Joliet',
          4 => 'Naperville',
          5 => 'Springfield',
          6 => 'Peoria',
          7 => 'Elgin',
          8 => 'Waukegan',
          9 => 'Cicero',
          10 => 'Champaign',
          11 => 'Bloomington',
          12 => 'Arlington Heights',
          13 => 'Evanston',
          14 => 'Decatur',
          15 => 'Schaumburg',
          16 => 'Bolingbrook',
          17 => 'Palatine',
          18 => 'Skokie',
          19 => 'Des Plaines',
          20 => 'Orland Park',
          21 => 'Tinley Park',
          22 => 'Oak Lawn',
          23 => 'Berwyn',
          24 => 'Mount Prospect',
          25 => 'Normal',
          26 => 'Wheaton',
          27 => 'Hoffman Estates',
          28 => 'Oak Park',
          29 => 'Downers Grove',
          30 => 'Elmhurst',
          31 => 'Glenview',
          32 => 'DeKalb',
          33 => 'Lombard',
          34 => 'Belleville',
          35 => 'Moline',
          36 => 'Buffalo Grove',
          37 => 'Bartlett',
          38 => 'Urbana',
          39 => 'Quincy',
          40 => 'Crystal Lake',
          41 => 'Plainfield',
          42 => 'Streamwood',
          43 => 'Carol Stream',
          44 => 'Romeoville',
          45 => 'Rock Island',
          46 => 'Hanover Park',
          47 => 'Carpentersville',
          48 => 'Wheeling',
          49 => 'Park Ridge',
          50 => 'Addison',
          51 => 'Calumet City',
        ),
        'Texas' => 
        array (
          0 => 'Houston',
          1 => 'San Antonio',
          2 => 'Dallas',
          3 => 'Austin',
          4 => 'Fort Worth',
          5 => 'El Paso',
          6 => 'Arlington',
          7 => 'Corpus Christi',
          8 => 'Plano',
          9 => 'Laredo',
          10 => 'Lubbock',
          11 => 'Garland',
          12 => 'Irving',
          13 => 'Amarillo',
          14 => 'Grand Prairie',
          15 => 'Brownsville',
          16 => 'Pasadena',
          17 => 'McKinney',
          18 => 'Mesquite',
          19 => 'McAllen',
          20 => 'Killeen',
          21 => 'Frisco',
          22 => 'Waco',
          23 => 'Carrollton',
          24 => 'Denton',
          25 => 'Midland',
          26 => 'Abilene',
          27 => 'Beaumont',
          28 => 'Round Rock',
          29 => 'Odessa',
          30 => 'Wichita Falls',
          31 => 'Richardson',
          32 => 'Lewisville',
          33 => 'Tyler',
          34 => 'College Station',
          35 => 'Pearland',
          36 => 'San Angelo',
          37 => 'Allen',
          38 => 'League City',
          39 => 'Sugar Land',
          40 => 'Longview',
          41 => 'Edinburg',
          42 => 'Mission',
          43 => 'Bryan',
          44 => 'Baytown',
          45 => 'Pharr',
          46 => 'Temple',
          47 => 'Missouri City',
          48 => 'Flower Mound',
          49 => 'Harlingen',
          50 => 'North Richland Hills',
          51 => 'Victoria',
          52 => 'Conroe',
          53 => 'New Braunfels',
          54 => 'Mansfield',
          55 => 'Cedar Park',
          56 => 'Rowlett',
          57 => 'Port Arthur',
          58 => 'Euless',
          59 => 'Georgetown',
          60 => 'Pflugerville',
          61 => 'DeSoto',
          62 => 'San Marcos',
          63 => 'Grapevine',
          64 => 'Bedford',
          65 => 'Galveston',
          66 => 'Cedar Hill',
          67 => 'Texas City',
          68 => 'Wylie',
          69 => 'Haltom City',
          70 => 'Keller',
          71 => 'Coppell',
          72 => 'Rockwall',
          73 => 'Huntsville',
          74 => 'Duncanville',
          75 => 'Sherman',
          76 => 'The Colony',
          77 => 'Burleson',
          78 => 'Hurst',
          79 => 'Lancaster',
          80 => 'Texarkana',
          81 => 'Friendswood',
          82 => 'Weslaco',
        ),
        'Pennsylvania' => 
        array (
          0 => 'Philadelphia',
          1 => 'Pittsburgh',
          2 => 'Allentown',
          3 => 'Erie',
          4 => 'Reading',
          5 => 'Scranton',
          6 => 'Bethlehem',
          7 => 'Lancaster',
          8 => 'Harrisburg',
          9 => 'Altoona',
          10 => 'York',
          11 => 'State College',
          12 => 'Wilkes-Barre',
        ),
        'Arizona' => 
        array (
          0 => 'Phoenix',
          1 => 'Tucson',
          2 => 'Mesa',
          3 => 'Chandler',
          4 => 'Glendale',
          5 => 'Scottsdale',
          6 => 'Gilbert',
          7 => 'Tempe',
          8 => 'Peoria',
          9 => 'Surprise',
          10 => 'Yuma',
          11 => 'Avondale',
          12 => 'Goodyear',
          13 => 'Flagstaff',
          14 => 'Buckeye',
          15 => 'Lake Havasu City',
          16 => 'Casa Grande',
          17 => 'Sierra Vista',
          18 => 'Maricopa',
          19 => 'Oro Valley',
          20 => 'Prescott',
          21 => 'Bullhead City',
          22 => 'Prescott Valley',
          23 => 'Marana',
          24 => 'Apache Junction',
        ),
        'Florida' => 
        array (
          0 => 'Jacksonville',
          1 => 'Miami',
          2 => 'Tampa',
          3 => 'Orlando',
          4 => 'St. Petersburg',
          5 => 'Hialeah',
          6 => 'Tallahassee',
          7 => 'Fort Lauderdale',
          8 => 'Port St. Lucie',
          9 => 'Cape Coral',
          10 => 'Pembroke Pines',
          11 => 'Hollywood',
          12 => 'Miramar',
          13 => 'Gainesville',
          14 => 'Coral Springs',
          15 => 'Miami Gardens',
          16 => 'Clearwater',
          17 => 'Palm Bay',
          18 => 'Pompano Beach',
          19 => 'West Palm Beach',
          20 => 'Lakeland',
          21 => 'Davie',
          22 => 'Miami Beach',
          23 => 'Sunrise',
          24 => 'Plantation',
          25 => 'Boca Raton',
          26 => 'Deltona',
          27 => 'Largo',
          28 => 'Deerfield Beach',
          29 => 'Palm Coast',
          30 => 'Melbourne',
          31 => 'Boynton Beach',
          32 => 'Lauderhill',
          33 => 'Weston',
          34 => 'Fort Myers',
          35 => 'Kissimmee',
          36 => 'Homestead',
          37 => 'Tamarac',
          38 => 'Delray Beach',
          39 => 'Daytona Beach',
          40 => 'North Miami',
          41 => 'Wellington',
          42 => 'North Port',
          43 => 'Jupiter',
          44 => 'Ocala',
          45 => 'Port Orange',
          46 => 'Margate',
          47 => 'Coconut Creek',
          48 => 'Sanford',
          49 => 'Sarasota',
          50 => 'Pensacola',
          51 => 'Bradenton',
          52 => 'Palm Beach Gardens',
          53 => 'Pinellas Park',
          54 => 'Coral Gables',
          55 => 'Doral',
          56 => 'Bonita Springs',
          57 => 'Apopka',
          58 => 'Titusville',
          59 => 'North Miami Beach',
          60 => 'Oakland Park',
          61 => 'Fort Pierce',
          62 => 'North Lauderdale',
          63 => 'Cutler Bay',
          64 => 'Altamonte Springs',
          65 => 'St. Cloud',
          66 => 'Greenacres',
          67 => 'Ormond Beach',
          68 => 'Ocoee',
          69 => 'Hallandale Beach',
          70 => 'Winter Garden',
          71 => 'Aventura',
        ),
        'Indiana' => 
        array (
          0 => 'Indianapolis',
          1 => 'Fort Wayne',
          2 => 'Evansville',
          3 => 'South Bend',
          4 => 'Carmel',
          5 => 'Bloomington',
          6 => 'Fishers',
          7 => 'Hammond',
          8 => 'Gary',
          9 => 'Muncie',
          10 => 'Lafayette',
          11 => 'Terre Haute',
          12 => 'Kokomo',
          13 => 'Anderson',
          14 => 'Noblesville',
          15 => 'Greenwood',
          16 => 'Elkhart',
          17 => 'Mishawaka',
          18 => 'Lawrence',
          19 => 'Jeffersonville',
          20 => 'Columbus',
          21 => 'Portage',
        ),
        'Ohio' => 
        array (
          0 => 'Columbus',
          1 => 'Cleveland',
          2 => 'Cincinnati',
          3 => 'Toledo',
          4 => 'Akron',
          5 => 'Dayton',
          6 => 'Parma',
          7 => 'Canton',
          8 => 'Youngstown',
          9 => 'Lorain',
          10 => 'Hamilton',
          11 => 'Springfield',
          12 => 'Kettering',
          13 => 'Elyria',
          14 => 'Lakewood',
          15 => 'Cuyahoga Falls',
          16 => 'Middletown',
          17 => 'Euclid',
          18 => 'Newark',
          19 => 'Mansfield',
          20 => 'Mentor',
          21 => 'Beavercreek',
          22 => 'Cleveland Heights',
          23 => 'Strongsville',
          24 => 'Dublin',
          25 => 'Fairfield',
          26 => 'Findlay',
          27 => 'Warren',
          28 => 'Lancaster',
          29 => 'Lima',
          30 => 'Huber Heights',
          31 => 'Westerville',
          32 => 'Marion',
          33 => 'Grove City',
        ),
        'North Carolina' => 
        array (
          0 => 'Charlotte',
          1 => 'Raleigh',
          2 => 'Greensboro',
          3 => 'Durham',
          4 => 'Winston-Salem',
          5 => 'Fayetteville',
          6 => 'Cary',
          7 => 'Wilmington',
          8 => 'High Point',
          9 => 'Greenville',
          10 => 'Asheville',
          11 => 'Concord',
          12 => 'Gastonia',
          13 => 'Jacksonville',
          14 => 'Chapel Hill',
          15 => 'Rocky Mount',
          16 => 'Burlington',
          17 => 'Wilson',
          18 => 'Huntersville',
          19 => 'Kannapolis',
          20 => 'Apex',
          21 => 'Hickory',
          22 => 'Goldsboro',
        ),
        'Michigan' => 
        array (
          0 => 'Detroit',
          1 => 'Grand Rapids',
          2 => 'Warren',
          3 => 'Sterling Heights',
          4 => 'Ann Arbor',
          5 => 'Lansing',
          6 => 'Flint',
          7 => 'Dearborn',
          8 => 'Livonia',
          9 => 'Westland',
          10 => 'Troy',
          11 => 'Farmington Hills',
          12 => 'Kalamazoo',
          13 => 'Wyoming',
          14 => 'Southfield',
          15 => 'Rochester Hills',
          16 => 'Taylor',
          17 => 'Pontiac',
          18 => 'St. Clair Shores',
          19 => 'Royal Oak',
          20 => 'Novi',
          21 => 'Dearborn Heights',
          22 => 'Battle Creek',
          23 => 'Saginaw',
          24 => 'Kentwood',
          25 => 'East Lansing',
          26 => 'Roseville',
          27 => 'Portage',
          28 => 'Midland',
          29 => 'Lincoln Park',
          30 => 'Muskegon',
        ),
        'Tennessee' => 
        array (
          0 => 'Memphis',
          1 => 'Nashville-Davidson',
          2 => 'Knoxville',
          3 => 'Chattanooga',
          4 => 'Clarksville',
          5 => 'Murfreesboro',
          6 => 'Jackson',
          7 => 'Franklin',
          8 => 'Johnson City',
          9 => 'Bartlett',
          10 => 'Hendersonville',
          11 => 'Kingsport',
          12 => 'Collierville',
          13 => 'Cleveland',
          14 => 'Smyrna',
          15 => 'Germantown',
          16 => 'Brentwood',
        ),
        'Massachusetts' => 
        array (
          0 => 'Boston',
          1 => 'Worcester',
          2 => 'Springfield',
          3 => 'Lowell',
          4 => 'Cambridge',
          5 => 'New Bedford',
          6 => 'Brockton',
          7 => 'Quincy',
          8 => 'Lynn',
          9 => 'Fall River',
          10 => 'Newton',
          11 => 'Lawrence',
          12 => 'Somerville',
          13 => 'Waltham',
          14 => 'Haverhill',
          15 => 'Malden',
          16 => 'Medford',
          17 => 'Taunton',
          18 => 'Chicopee',
          19 => 'Weymouth Town',
          20 => 'Revere',
          21 => 'Peabody',
          22 => 'Methuen',
          23 => 'Barnstable Town',
          24 => 'Pittsfield',
          25 => 'Attleboro',
          26 => 'Everett',
          27 => 'Salem',
          28 => 'Westfield',
          29 => 'Leominster',
          30 => 'Fitchburg',
          31 => 'Beverly',
          32 => 'Holyoke',
          33 => 'Marlborough',
          34 => 'Woburn',
          35 => 'Chelsea',
        ),
        'Washington' => 
        array (
          0 => 'Seattle',
          1 => 'Spokane',
          2 => 'Tacoma',
          3 => 'Vancouver',
          4 => 'Bellevue',
          5 => 'Kent',
          6 => 'Everett',
          7 => 'Renton',
          8 => 'Yakima',
          9 => 'Federal Way',
          10 => 'Spokane Valley',
          11 => 'Bellingham',
          12 => 'Kennewick',
          13 => 'Auburn',
          14 => 'Pasco',
          15 => 'Marysville',
          16 => 'Lakewood',
          17 => 'Redmond',
          18 => 'Shoreline',
          19 => 'Richland',
          20 => 'Kirkland',
          21 => 'Burien',
          22 => 'Sammamish',
          23 => 'Olympia',
          24 => 'Lacey',
          25 => 'Edmonds',
          26 => 'Bremerton',
          27 => 'Puyallup',
        ),
        'Colorado' => 
        array (
          0 => 'Denver',
          1 => 'Colorado Springs',
          2 => 'Aurora',
          3 => 'Fort Collins',
          4 => 'Lakewood',
          5 => 'Thornton',
          6 => 'Arvada',
          7 => 'Westminster',
          8 => 'Pueblo',
          9 => 'Centennial',
          10 => 'Boulder',
          11 => 'Greeley',
          12 => 'Longmont',
          13 => 'Loveland',
          14 => 'Grand Junction',
          15 => 'Broomfield',
          16 => 'Castle Rock',
          17 => 'Commerce City',
          18 => 'Parker',
          19 => 'Littleton',
          20 => 'Northglenn',
        ),
        'District of Columbia' => 
        array (
          0 => 'Washington',
        ),
        'Maryland' => 
        array (
          0 => 'Baltimore',
          1 => 'Frederick',
          2 => 'Rockville',
          3 => 'Gaithersburg',
          4 => 'Bowie',
          5 => 'Hagerstown',
          6 => 'Annapolis',
        ),
        'Kentucky' => 
        array (
          0 => 'Louisville/Jefferson County',
          1 => 'Lexington-Fayette',
          2 => 'Bowling Green',
          3 => 'Owensboro',
          4 => 'Covington',
        ),
        'Oregon' => 
        array (
          0 => 'Portland',
          1 => 'Eugene',
          2 => 'Salem',
          3 => 'Gresham',
          4 => 'Hillsboro',
          5 => 'Beaverton',
          6 => 'Bend',
          7 => 'Medford',
          8 => 'Springfield',
          9 => 'Corvallis',
          10 => 'Albany',
          11 => 'Tigard',
          12 => 'Lake Oswego',
          13 => 'Keizer',
        ),
        'Oklahoma' => 
        array (
          0 => 'Oklahoma City',
          1 => 'Tulsa',
          2 => 'Norman',
          3 => 'Broken Arrow',
          4 => 'Lawton',
          5 => 'Edmond',
          6 => 'Moore',
          7 => 'Midwest City',
          8 => 'Enid',
          9 => 'Stillwater',
          10 => 'Muskogee',
        ),
        'Wisconsin' => 
        array (
          0 => 'Milwaukee',
          1 => 'Madison',
          2 => 'Green Bay',
          3 => 'Kenosha',
          4 => 'Racine',
          5 => 'Appleton',
          6 => 'Waukesha',
          7 => 'Eau Claire',
          8 => 'Oshkosh',
          9 => 'Janesville',
          10 => 'West Allis',
          11 => 'La Crosse',
          12 => 'Sheboygan',
          13 => 'Wauwatosa',
          14 => 'Fond du Lac',
          15 => 'New Berlin',
          16 => 'Wausau',
          17 => 'Brookfield',
          18 => 'Greenfield',
          19 => 'Beloit',
        ),
        'Nevada' => 
        array (
          0 => 'Las Vegas',
          1 => 'Henderson',
          2 => 'Reno',
          3 => 'North Las Vegas',
          4 => 'Sparks',
          5 => 'Carson City',
        ),
        'New Mexico' => 
        array (
          0 => 'Albuquerque',
          1 => 'Las Cruces',
          2 => 'Rio Rancho',
          3 => 'Santa Fe',
          4 => 'Roswell',
          5 => 'Farmington',
          6 => 'Clovis',
        ),
        'Missouri' => 
        array (
          0 => 'Kansas City',
          1 => 'St. Louis',
          2 => 'Springfield',
          3 => 'Independence',
          4 => 'Columbia',
          5 => 'Lee\'s Summit',
          6 => 'O\'Fallon',
          7 => 'St. Joseph',
          8 => 'St. Charles',
          9 => 'St. Peters',
          10 => 'Blue Springs',
          11 => 'Florissant',
          12 => 'Joplin',
          13 => 'Chesterfield',
          14 => 'Jefferson City',
          15 => 'Cape Girardeau',
        ),
        'Virginia' => 
        array (
          0 => 'Virginia Beach',
          1 => 'Norfolk',
          2 => 'Chesapeake',
          3 => 'Richmond',
          4 => 'Newport News',
          5 => 'Alexandria',
          6 => 'Hampton',
          7 => 'Roanoke',
          8 => 'Portsmouth',
          9 => 'Suffolk',
          10 => 'Lynchburg',
          11 => 'Harrisonburg',
          12 => 'Leesburg',
          13 => 'Charlottesville',
          14 => 'Danville',
          15 => 'Blacksburg',
          16 => 'Manassas',
        ),
        'Georgia' => 
        array (
          0 => 'Atlanta',
          1 => 'Columbus',
          2 => 'Augusta-Richmond County',
          3 => 'Savannah',
          4 => 'Athens-Clarke County',
          5 => 'Sandy Springs',
          6 => 'Roswell',
          7 => 'Macon',
          8 => 'Johns Creek',
          9 => 'Albany',
          10 => 'Warner Robins',
          11 => 'Alpharetta',
          12 => 'Marietta',
          13 => 'Valdosta',
          14 => 'Smyrna',
          15 => 'Dunwoody',
        ),
        'Nebraska' => 
        array (
          0 => 'Omaha',
          1 => 'Lincoln',
          2 => 'Bellevue',
          3 => 'Grand Island',
        ),
        'Minnesota' => 
        array (
          0 => 'Minneapolis',
          1 => 'St. Paul',
          2 => 'Rochester',
          3 => 'Duluth',
          4 => 'Bloomington',
          5 => 'Brooklyn Park',
          6 => 'Plymouth',
          7 => 'St. Cloud',
          8 => 'Eagan',
          9 => 'Woodbury',
          10 => 'Maple Grove',
          11 => 'Eden Prairie',
          12 => 'Coon Rapids',
          13 => 'Burnsville',
          14 => 'Blaine',
          15 => 'Lakeville',
          16 => 'Minnetonka',
          17 => 'Apple Valley',
          18 => 'Edina',
          19 => 'St. Louis Park',
          20 => 'Mankato',
          21 => 'Maplewood',
          22 => 'Moorhead',
          23 => 'Shakopee',
        ),
        'Kansas' => 
        array (
          0 => 'Wichita',
          1 => 'Overland Park',
          2 => 'Kansas City',
          3 => 'Olathe',
          4 => 'Topeka',
          5 => 'Lawrence',
          6 => 'Shawnee',
          7 => 'Manhattan',
          8 => 'Lenexa',
          9 => 'Salina',
          10 => 'Hutchinson',
        ),
        'Louisiana' => 
        array (
          0 => 'New Orleans',
          1 => 'Baton Rouge',
          2 => 'Shreveport',
          3 => 'Lafayette',
          4 => 'Lake Charles',
          5 => 'Kenner',
          6 => 'Bossier City',
          7 => 'Monroe',
          8 => 'Alexandria',
        ),
        'Hawaii' => 
        array (
          0 => 'Honolulu',
        ),
        'Alaska' => 
        array (
          0 => 'Anchorage',
        ),
        'New Jersey' => 
        array (
          0 => 'Newark',
          1 => 'Jersey City',
          2 => 'Paterson',
          3 => 'Elizabeth',
          4 => 'Clifton',
          5 => 'Trenton',
          6 => 'Camden',
          7 => 'Passaic',
          8 => 'Union City',
          9 => 'Bayonne',
          10 => 'East Orange',
          11 => 'Vineland',
          12 => 'New Brunswick',
          13 => 'Hoboken',
          14 => 'Perth Amboy',
          15 => 'West New York',
          16 => 'Plainfield',
          17 => 'Hackensack',
          18 => 'Sayreville',
          19 => 'Kearny',
          20 => 'Linden',
          21 => 'Atlantic City',
        ),
        'Idaho' => 
        array (
          0 => 'Boise City',
          1 => 'Nampa',
          2 => 'Meridian',
          3 => 'Idaho Falls',
          4 => 'Pocatello',
          5 => 'Caldwell',
          6 => 'Coeur d\'Alene',
          7 => 'Twin Falls',
        ),
        'Alabama' => 
        array (
          0 => 'Birmingham',
          1 => 'Montgomery',
          2 => 'Mobile',
          3 => 'Huntsville',
          4 => 'Tuscaloosa',
          5 => 'Hoover',
          6 => 'Dothan',
          7 => 'Auburn',
          8 => 'Decatur',
          9 => 'Madison',
          10 => 'Florence',
          11 => 'Gadsden',
        ),
        'Iowa' => 
        array (
          0 => 'Des Moines',
          1 => 'Cedar Rapids',
          2 => 'Davenport',
          3 => 'Sioux City',
          4 => 'Iowa City',
          5 => 'Waterloo',
          6 => 'Council Bluffs',
          7 => 'Ames',
          8 => 'West Des Moines',
          9 => 'Dubuque',
          10 => 'Ankeny',
          11 => 'Urbandale',
          12 => 'Cedar Falls',
        ),
        'Arkansas' => 
        array (
          0 => 'Little Rock',
          1 => 'Fort Smith',
          2 => 'Fayetteville',
          3 => 'Springdale',
          4 => 'Jonesboro',
          5 => 'North Little Rock',
          6 => 'Conway',
          7 => 'Rogers',
          8 => 'Pine Bluff',
          9 => 'Bentonville',
        ),
        'Utah' => 
        array (
          0 => 'Salt Lake City',
          1 => 'West Valley City',
          2 => 'Provo',
          3 => 'West Jordan',
          4 => 'Orem',
          5 => 'Sandy',
          6 => 'Ogden',
          7 => 'St. George',
          8 => 'Layton',
          9 => 'Taylorsville',
          10 => 'South Jordan',
          11 => 'Lehi',
          12 => 'Logan',
          13 => 'Murray',
          14 => 'Draper',
          15 => 'Bountiful',
          16 => 'Riverton',
          17 => 'Roy',
        ),
        'Rhode Island' => 
        array (
          0 => 'Providence',
          1 => 'Warwick',
          2 => 'Cranston',
          3 => 'Pawtucket',
          4 => 'East Providence',
          5 => 'Woonsocket',
        ),
        'Mississippi' => 
        array (
          0 => 'Jackson',
          1 => 'Gulfport',
          2 => 'Southaven',
          3 => 'Hattiesburg',
          4 => 'Biloxi',
          5 => 'Meridian',
        ),
        'South Dakota' => 
        array (
          0 => 'Sioux Falls',
          1 => 'Rapid City',
        ),
        'Connecticut' => 
        array (
          0 => 'Bridgeport',
          1 => 'New Haven',
          2 => 'Stamford',
          3 => 'Hartford',
          4 => 'Waterbury',
          5 => 'Norwalk',
          6 => 'Danbury',
          7 => 'New Britain',
          8 => 'Meriden',
          9 => 'Bristol',
          10 => 'West Haven',
          11 => 'Milford',
          12 => 'Middletown',
          13 => 'Norwich',
          14 => 'Shelton',
        ),
        'South Carolina' => 
        array (
          0 => 'Columbia',
          1 => 'Charleston',
          2 => 'North Charleston',
          3 => 'Mount Pleasant',
          4 => 'Rock Hill',
          5 => 'Greenville',
          6 => 'Summerville',
          7 => 'Sumter',
          8 => 'Goose Creek',
          9 => 'Hilton Head Island',
          10 => 'Florence',
          11 => 'Spartanburg',
        ),
        'New Hampshire' => 
        array (
          0 => 'Manchester',
          1 => 'Nashua',
          2 => 'Concord',
        ),
        'North Dakota' => 
        array (
          0 => 'Fargo',
          1 => 'Bismarck',
          2 => 'Grand Forks',
          3 => 'Minot',
        ),
        'Montana' => 
        array (
          0 => 'Billings',
          1 => 'Missoula',
          2 => 'Great Falls',
          3 => 'Bozeman',
        ),
        'Delaware' => 
        array (
          0 => 'Wilmington',
          1 => 'Dover',
        ),
        'Maine' => 
        array (
          0 => 'Portland',
        ),
        'Wyoming' => 
        array (
          0 => 'Cheyenne',
          1 => 'Casper',
        ),
        'West Virginia' => 
        array (
          0 => 'Charleston',
          1 => 'Huntington',
        ),
        'Vermont' => 
        array (
          0 => 'Burlington',
        ),
      );

      foreach($cities as $city=>$states){
        $inserted_city = City::create([
          'country' => 'USA',
          'city' => $city,
        ]);
        if($inserted_city){
          foreach($states as $state_key=>$state){
            state::create([
              'city_id' => $inserted_city->id,
              'state' => $state,
            ]);
          }
        }
      }    

      return 'success';
    } */
}


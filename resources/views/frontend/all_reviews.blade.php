@include('layouts.header')
@include('layouts.nav')
<style>
    .rev-heading{
        font-size: 37px;
        font-weight: 600;
    }
    .rev-para{
        color: #353535;
        text-align: center;
        font-size: 17px;
    }
    .card-rev {
    position: relative;
    padding: 45px 50px 25px 61px;
    border-radius: 15px;
    color: #fff;
    box-shadow: 2px 3px 6px 1px #848484;
}

.post-txt {
    font-size: 16px;
    margin-bottom: 0;
    text-align:center;
}
.card-rev .post{
    text-align:center;
}
.quote-img {
    position: absolute;
    top: 11px;
    left: 25px;
    width: 20px;
    height: 20px;
    opacity: 0.7;
    transform: rotate(180deg);
}

.nice-img {
    position: absolute;
    bottom: 16px;
    right: 25px;
    width: 20px;
    height: 20px;
    opacity: 0.7;
}

.arrow-down {
  width: 0;
  height: 0;
  border-left: 25px solid transparent;
  border-right: 25px solid transparent;
  border-top: 20px solid #fff;
  position: relative;
  margin-left: 46%;
}

.arrow-down::before {
  content: "";
  position: absolute;
  top: -18px; /* Adjust according to arrow size */
  left: -25px;
  width: 0;
  height: 0;
  border-left: 25px solid transparent;
  border-right: 25px solid transparent;
  border-top: 20px solid #fff;
  filter: drop-shadow(0px 3px 3px rgba(0, 0, 0, 0.5)); /* Apply shadow */
  z-index: -1; /* Position behind the arrow */
}

.profile-pic {
    width: 114px;
    height: 73px;
    border-radius: 10px;
    margin-top: 15px;
    border: 2px solid #F79F02;
}

.profile-pic-secondary {
    width: 76px;
    height: 53px;
    border-radius: 10px;
    margin-top: 15px;
    border: 2px solid #F79F02;
}

.profile-name {
    font-size: 19px;
    color: #000;
    font-weight: 600;
    margin:0;
}
.profile-name-secondary {
    font-size: 14px;
    color: #000;
    font-weight: 600;
    margin:0;
}
.pro-name{
    color: #4C256C;
    font-size: 16px;
}
</style>
<section style="margin-top: 100px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="rev-heading">
                    Thousands of companies rely on <span style="color:#F79F02">JET SEEKER</span>  for exceptional services.
                </h2>
                <p class="rev-para">Our clients appreciate the reliability and efficiency of JET SEEKER, highlighting our commitment to excellence and customer satisfaction. Here’s what they had to say!</p>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Quality Parking made my trip so easy! The meet-and-greet service was smooth, and my car was safe. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic fit-image" src="{{ asset('assets/images/quality-p.png') }}" >
                    </div>
                    <div style="padding: 26px;">
                        <p class="profile-name">Quality Parking</p>
                    <p class="pro-name">Sarah L.</p>
                    </div>
                    
                </div>
            </div>
            
            
            
            
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Impressive service from Quality Parking! Quick and efficient, plus my car was in great condition when I returned. Highly satisfied! </span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/quality-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Quality Parking</p>
                    <p class="pro-name">Laura P.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Love Airport Valet Parking! It takes the stress out of travel, and the service is always top-notch. Will return!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/smg-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">SMG Park & Ride</p>
                    <p class="pro-name">Sophie R.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Bee Parking is fantastic! Always easy to find a spot, and the rates are really fair. Love the convenience!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/bee-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Bee Parking</p>
                    <p class="pro-name">Sophie L.</p>
                    </div>
                    
                </div>
            </div>
           
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience with Airside Parking! Quick service and my car was just as I left it. Perfect for my travels. Will definitely book again! </span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/airside-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Airside Parking</p>
                    <p class="pro-name">Rebecca S.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Airside Parking made my trip stress-free! Convenient service, friendly staff, and my car was well taken care of. Will use them every time!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/airside-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Airside Parking</p>
                    <p class="pro-name">Anna W.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Perfect spot for quick parking. Affordable, easy to access, and staff is always friendly. Highly recommended!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/rose-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Rose Parking</p>
                    <p class="pro-name">Jake R.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Loved the convenience of Quality Parking’s meet and greet. No stress, just a smooth start to my holiday. Five stars!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/quality-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Quality Parking</p>
                    <p class="pro-name">Emily R.</p>
                    </div>
                    
                </div>
            </div>
            <!--<div class="col-lg-4 col-md-6">-->
            <!--    <div class="card-rev">-->
            <!--        <p class="post">-->
            <!--            <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>-->
            <!--            <span class="post-txt">Kangaroo Parking at Heathrow is fantastic! Quick service, secure, and very friendly staff. Highly recommend it!</span>-->
            <!--            <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>-->
            <!--        </p>-->
            <!--    </div>-->
            <!--    <div class="arrow-down"></div>-->
            <!--    <div class="row d-flex justify-content-center">-->
            <!--        <div class="">-->
            <!--            <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/green-bp.jpeg') }}" >-->
            <!--        </div>-->
            <!--        <div style="padding: 13px;">-->
            <!--            <p class="profile-name-secondary">Kangaroo Parking Heathrow</p>-->
            <!--        <p class="pro-name">Liam H.</p>-->
            <!--        </div>-->
                    
            <!--    </div>-->
            <!--</div>-->
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Highly recommended! Quick process and very secure. Easy Parking made airport parking simple and hassle-free.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/easy-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Easy Parking</p>
                    <p class="pro-name">Jessica L.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Space Parking is so convenient! Easy access, secure, and the staff is super friendly. Great experience every time!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/space-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Space Parking</p>
                    <p class="pro-name">Ella D.  </p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Quality Parking exceeded my expectations! Easy drop-off and pick-up at Birmingham Airport. Will use it again for sure!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/quality-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Quality Parking</p>
                    <p class="pro-name">Michael K.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Kwik Park Meet and Greet is so easy! Fast, friendly service, and my car was ready on time. Loved it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kwick-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kwik Park</p>
                    <p class="pro-name">Alice K.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">I was impressed with Airside Parking! The meet and greet was seamless, and the staff was very helpful. Easy choice for my airport parking!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/airside-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Airside Parking</p>
                    <p class="pro-name">Tom H.</p>
                    </div>
                    
                </div>
            </div>
           
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Lion Parking is a lifesaver! Easy access, secure, and staff are always welcoming. Highly recommend!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/lion-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Lion Parking</p>
                    <p class="pro-name">Oliver R.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">I had a wonderful experience with Terminals Parking! Easy process and my car was well cared for. Great value for peace of mind!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/terminals-p.gif') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Terminals Parking</p>
                    <p class="pro-name">Maya C.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! Easy drop-off and pick-up. The team is friendly, and my car was well taken care of.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/smg-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">SMG Park & Ride</p>
                    <p class="pro-name">Ethan G.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">AirPark Me made my travel so easy! The meet and greet service was prompt and professional. My car was well taken care of. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/aipark-p.webp') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">AirPark Me. (Meet and greet)</p>
                    <p class="pro-name">Chloe A.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Terminals Parking was excellent! Quick check-in and friendly staff. My car was secure, and the convenience was unbeatable. I’ll be using them again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/terminals-p.gif') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Terminals Parking</p>
                    <p class="pro-name">Lucas G.</p>
                    </div>
                    
                </div>
            </div>
           
            
           
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great service from AirPark Me! The meet and greet was seamless, and the staff was so helpful. My car was well cared for. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/aipark-p.webp') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">AirPark Me. (Meet and greet)</p>
                    <p class="pro-name">Zoe T.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Terminals Parking provided top-notch service! Quick and efficient, with friendly staff. My car was safe and ready when I returned. Highly satisfied!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/terminals-p.gif') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Terminals Parking</p>
                    <p class="pro-name">Ethan S.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Excellent stay! Clean rooms, friendly staff, and close to everything. Green Oaks Birmingham exceeded expectations. Highly recommended!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/green-bp.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Green Oaks Birmingham</p>
                    <p class="pro-name">Tom S.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Awesome parking experience! Affordable, safe, and I love how easy it is to find a spot with Space Parking.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/space-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Space Parking</p>
                    <p class="pro-name">Jake T. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! My car was well taken care of, and the team was super helpful. VIP service as promised.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/volf-p.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">VIP Wolf Parking</p>
                    <p class="pro-name">Ava G.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Express Parking provided excellent service! The meet and greet was smooth, and the staff was professional. Perfect for my airport needs. Will book again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/express-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Express Parking(Meet and Greet)</p>
                    <p class="pro-name">Noah B.</p>
                    </div>
                    
                </div>
            </div>
             
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">I loved using AirPark Me! The convenience of meet and greet is unbeatable. Quick service and my car was safe. Excellent all around!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/aipark-p.webp') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">AirPark Me. (Meet and greet)</p>
                    <p class="pro-name">Samantha J.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Express Parking was superb! Fast and friendly service, plus my car was just as I left it. Highly satisfied and will definitely return!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/express-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Express Parking(Meet and Greet)</p>
                    <p class="pro-name">Oliver P.</p>
                    </div>
                    
                </div>
            </div>
            
           
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Rose Parking is my go-to! Great prices, reliable service, and the spaces are always clean and available.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/rose-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Rose Parking</p>
                    <p class="pro-name">Lily S.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Quick and easy! I dropped off my car, and everything was handled professionally. VIP Wolf Parking is top-notch!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/volf-p.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">VIP Wolf Parking</p>
                    <p class="pro-name">Lucas P.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! Super friendly staff and my car feels safe here every time. Bee Parking is my top choice.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/bee-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Bee Parking</p>
                    <p class="pro-name">Carlos M.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great service with both flex and non-flex options. Kwik Park makes parking simple and stress-free!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kwick-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kwik Park</p>
                    <p class="pro-name">Derek M. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great prices and super clean! Lion Parking makes parking stress-free and fast. Love using them!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/lion-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Lion Parking</p>
                    <p class="pro-name">Bella C.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Such a smooth experience every time! Rose Parking is convenient, and I appreciate how friendly the staff is.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/rose-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Rose Parking</p>
                    <p class="pro-name">Tommy K.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Kangaroo Parking at Heathrow is fantastic! Quick service, secure, and very friendly staff. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kangroo-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kangaroo Parking Heathrow</p>
                    <p class="pro-name">Liam H.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience with Express Parking! Quick, reliable, and my car was well cared for. The meet and greet made everything so easy!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/express-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Express Parking(Meet and Greet)</p>
                    <p class="pro-name">Emma F.</p>
                    </div>
                    
                </div>
            </div>
            
           
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Perfect choice! The staff was efficient and parking was secure. Sure Parking made my travel easier definitely recommend them!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/sure-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Sure Parking</p>
                    <p class="pro-name">Olivia B.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Lovely atmosphere and very welcoming staff. My room was comfortable and quiet. Will choose Green Oaks again on my next visit.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/green-bp.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Green Oaks Birmingham</p>
                    <p class="pro-name">Lucy P.</p>
                    </div>
                    
                </div>
            </div>
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Quick and reliable! Bee Parking makes my commute stress-free. Affordable, too. Five stars!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/bee-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Bee Parking</p>
                    <p class="pro-name">Jason T.</p>
                    </div>
                    
                </div>
            </div>
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Excellent service from Airside Parking! Fast, reliable, and my car was in perfect condition upon return. Highly recommended for anyone flying out!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/airside-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Airside Parking</p>
                    <p class="pro-name">Mark L.</p>
                    </div>
                    
                </div>
            </div>
             
             
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">VIP treatment all the way! Secure, convenient, and reliable. Wolf Parking made my trip worry-free. Thank you!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/volf-p.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">VIP Wolf Parking</p>
                    <p class="pro-name">Noah T.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Bee Parking is so easy to use! The spaces are clean, and I never worry about my car. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/bee-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Bee Parking</p>
                    <p class="pro-name">Nina W.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Express Parking made my trip hassle-free! The meet and greet service was quick and efficient. My car was safe, and I’ll definitely use it again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/express-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Express Parking(Meet and Greet)</p>
                    <p class="pro-name">Liam K.</p>
                    </div>
                    
                </div>
            </div>
            
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Convenient, affordable, and easy to book. Lion Parking has everything I need for quick parking.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/lion-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Lion Parking</p>
                    <p class="pro-name">Chloe M. </p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Efficient service! The team was friendly, and my car was ready when I returned. Highly recommend VIP Wolf Parking.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/volf-p.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">VIP Wolf Parking</p>
                    <p class="pro-name">Grace L.</p>
                    </div>
                    
                </div>
            </div>
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic experience with Quality Parking! Friendly staff, quick service, and my car was just where I left it. Will definitely use it again! </span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/quality-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Quality Parking</p>
                    <p class="pro-name">James T.</p>
                    </div>
                    
                </div>
            </div>
           
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Awesome experience! Flex option was perfect for my last-minute plans. Kwik Park staff is always friendly.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kwick-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kwik Park</p>
                    <p class="pro-name">John S.</p>
                    </div>
                    
                </div>
            </div>
            
            
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Wonderful place! Spotless rooms and helpful staff made it feel like home. Green Oaks Birmingham is a gem!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/green-bp.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Green Oaks Birmingham</p>
                    <p class="pro-name">Sarah L.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Always reliable! Friendly service, secure spots, and no hassle. Lion Parking is my top choice.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/lion-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Lion Parking</p>
                    <p class="pro-name">Sam T. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Affordable and charming! Clean rooms, great location, and friendly staff. Green Oaks Birmingham is perfect for a short stay.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/green-bp.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Green Oaks Birmingham</p>
                    <p class="pro-name">David W.</p>
                    </div>
                    
                </div>
            </div>
            
            
           
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great value for money! Safe parking, helpful staff, and no hassles. Kangaroo Parking makes travel easy.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kangroo-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kangaroo Parking Heathrow</p>
                    <p class="pro-name">Ben R. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Loved the service at Kangaroo Parking! Quick, professional, and my car was well taken care of. Will use it again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kangroo-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kangaroo Parking Heathrow</p>
                    <p class="pro-name">Olivia M. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great service! Easy booking, friendly staff, and my car was safe. Highly recommend Sure Parking for hassle-free airport parking</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/sure-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Sure Parking</p>
                    <p class="pro-name">Emily T.</p>
                    </div>
                    
                </div>
            </div>
            
            
            
            
            
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Space Parking is my top choice! The service is friendly, and I never worry about my car. Highly recommended!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/space-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Space Parking</p>
                    <p class="pro-name">Sophia K.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Awesome experience! Easy drop-off and pick-up, and my car was safe. Parking Guru Birmingham is my go-to for airport parking</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/guru-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Parking Guru Birmingham</p>
                    <p class="pro-name">Daniel S.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Quick and efficient! The staff was friendly, and the service was great. Parking Guru Birmingham made airport parking super simple.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/guru-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Parking Guru Birmingham</p>
                    <p class="pro-name">Chloe M.</p>
                    </div>
                    
                </div>
            </div>
           
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Such a smooth process! Space Parking has great rates, clean spaces, and really helpful staff. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/space-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Space Parking</p>
                    <p class="pro-name">Maria L.  </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Seamless parking! Professional team and well-organized. Parking Guru Birmingham made my trip stress-free. Highly recommend!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/guru-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Parking Guru Birmingham</p>
                    <p class="pro-name">Amelia J.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Kwik Park made parking a breeze! The non-flex Meet and Greet was prompt and professional. Will use it again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kwick-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kwik Park</p>
                    <p class="pro-name">Ella W.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">VIP Wolf Parking was amazing! Friendly staff, smooth process, and secure parking. Definitely using them again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/volf-p.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">VIP Wolf Parking</p>
                    <p class="pro-name">Ethan M.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! Cozy room, fantastic service, and good location. Green Oaks Birmingham made my stay enjoyable.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/green-bp.jpeg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Green Oaks Birmingham</p>
                    <p class="pro-name">Ben M.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Excellent service! Smooth process from start to finish. Parking Guru Birmingham exceeded my expectations—will definitely use again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/guru-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Parking Guru Birmingham</p>
                    <p class="pro-name">Harry P.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Quick and reliable! Dropping my car off was seamless. Got back to a spotless vehicle with impressive service from Sure Parking!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/sure-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Sure Parking</p>
                    <p class="pro-name">James R.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Reliable and secure! Booking was easy, and my car was in perfect condition on return. Parking Guru Birmingham is fantastic!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/guru-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Parking Guru Birmingham</p>
                    <p class="pro-name">Ryan L.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic service! Parking was easy, and the shuttle arrived on time. Manchester Park and Ride is reliable and safe.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/manchester-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Manchester Park and Ride</p>
                    <p class="pro-name">Anna L.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Highly recommend! Friendly staff, secure parking, and fast shuttle service. I’ll definitely use Manchester Park and Ride again.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/manchester-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Manchester Park and Ride</p>
                    <p class="pro-name">Chris D.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Super easy and convenient! Dropping off and picking up my car was seamless. Kangaroo Parking is a lifesaver!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kangroo-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kangaroo Parking Heathrow</p>
                    <p class="pro-name">Sophie L. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! No hassle, easy parking, and quick access to the airport. Manchester Park and Ride is a real time-saver!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/manchester-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Manchester Park and Ride</p>
                    <p class="pro-name">Rachel P.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Airside Parking was fantastic! Smooth drop-off and pick-up. My car was safe, and the staff was friendly. Highly recommended for hassle-free airport parking!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/airside-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Airside Parking</p>
                    <p class="pro-name">John D.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Impressive service! Easy booking, helpful team, and a smooth shuttle ride. Manchester Park and Ride made everything effortless.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/manchester-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Manchester Park and Ride</p>
                    <p class="pro-name">Tommy E.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Heathrow Valet Parking made my trip so easy! Quick drop-off, friendly staff, and my car was safe. Highly recommend!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/vlut-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Heathrow Valet Parking</p>
                    <p class="pro-name">Noah B.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic service! Fast, efficient, and the staff is super helpful. I’ll definitely use Heathrow Valet Parking again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/vlut-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Heathrow Valet Parking</p>
                    <p class="pro-name">Mia T.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Very efficient! Quick check-in, and the shuttle to the airport was super convenient. Manchester Park and Ride made traveling stress-free!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/manchester-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Manchester Park and Ride</p>
                    <p class="pro-name">Mark S.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Love Heathrow Valet Parking! It takes the stress out of travel, and the service is always top-notch. Will return!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/vlut-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Heathrow Valet Parking</p>
                    <p class="pro-name">Sophie R. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Super convenient! Fast service and nice staff made my travel smooth. Heathrow Valet Parking is my go-to!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/vlut-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Heathrow Valet Parking</p>
                    <p class="pro-name">Liam S.  </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great service! Easy parking and shuttle was quick. Self Park and Ride Manchester made my airport travel stress-free!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/self-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Self Park and Ride</p>
                    <p class="pro-name">Daniel M. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Convenient and affordable! Parking was smooth, and the shuttle service was prompt. Highly recommend Self Park and Ride.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/self-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Self Park and Ride</p>
                    <p class="pro-name">Hannah L.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Express Parking made my trip hassle-free! The meet and greet service was quick and efficient. My car was safe, and I’ll definitely use it again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/express-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Express Parking(Meet and Greet)</p>
                    <p class="pro-name">Liam K.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Simple, safe, and efficient! Self Park and Ride Manchester provided excellent service. Definitely booking again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/self-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Self Park and Ride</p>
                    <p class="pro-name">George S.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Excellent choice! Parking was easy and shuttle staff were friendly. Self Park and Ride made my trip easier.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/self-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Self Park and Ride</p>
                    <p class="pro-name">Rachel P.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Lion Parking is fantastic! Clean, affordable, and staff is so friendly. My go-to parking every time.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/lion-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Lion Parking</p>
                    <p class="pro-name"> Lucas J. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fast and reliable! I parked my car and hopped on the shuttle quickly. Self Park and Ride Manchester was perfect for me.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/self-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Self Park and Ride</p>
                    <p class="pro-name">Adam C.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic experience with AirPark Me! Quick meet and greet, and friendly staff. My car was in perfect condition upon return. Will definitely use it again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/aipark-p.webp') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">AirPark Me. (Meet and greet)</p>
                    <p class="pro-name">Daniel W.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Rose Parking makes life so much easier! Always clean, secure, and no hassle finding a spot. Love it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/rose-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Rose Parking</p>
                    <p class="pro-name">Alex H.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Super convenient and stress-free! Easy Parking staff were helpful, and my car was well looked after. Highly recommended!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/easy-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Easy Parking</p>
                    <p class="pro-name">Daniel P.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic service! Quick drop-off and pick-up, plus my car was just as I left it. Easy Parking is my new go-to!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/easy-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Easy Parking</p>
                    <p class="pro-name">Chloe M.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Really happy with Rose Parking! Staff is super helpful, and I always feel safe leaving my car here.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/rose-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Rose Parking</p>
                    <p class="pro-name">Maria P. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Love parking here! Always a spot available, and the location is perfect. Bee Parking is super reliable!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/bee-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Bee Parking</p>
                    <p class="pro-name">Emma R.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Perfect for quick parking! Space Parking is reliable, affordable, and super easy to use. Loved it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/space-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Space Parking</p>
                    <p class="pro-name">Chris W. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Smooth experience! Friendly team and secure parking. Sure Parking made my airport trip stress-free. Will book again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/sure-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Sure Parking</p>
                    <p class="pro-name">Sophia K.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">I had a fantastic experience with Express Parking! Friendly staff and seamless drop-off. My car was in great condition when I returned. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/express-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Express Parking(Meet and Greet)</p>
                    <p class="pro-name">Ava N.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Efficient and friendly! Kangaroo Parking at Heathrow made parking stress-free. Definitely my new go-to!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kangroo-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kangaroo Parking Heathrow</p>
                    <p class="pro-name">James T. </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! Easy booking and reliable service. Friendly staff made everything smooth—will definitely use Easy Parking again.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/easy-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Easy Parking</p>
                    <p class="pro-name">Ethan W.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Highly recommend Terminals Parking! Smooth drop-off and pick-up, plus my car was in perfect condition. Made my travel experience so much easier!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/terminals-p.gif') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Terminals Parking</p>
                    <p class="pro-name">Nina B.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Great experience! Easy drop-off and pick-up. The team is friendly, and my car was well taken care of.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/vlut-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Heathrow Valet Parking</p>
                    <p class="pro-name">Ethan G.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">AirPark Me exceeded my expectations! Smooth drop-off and friendly team. Made my airport experience hassle-free. I’ll be back for my next trip!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/aipark-p.webp') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">AirPark Me. (Meet and greet)</p>
                    <p class="pro-name">Ryan H.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Excellent service! Easy Parking staff were polite and efficient. Made my airport travel much easier—thanks!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/easy-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Easy Parking</p>
                    <p class="pro-name">Oliver C.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Airport Valet Parking made my trip so easy! Quick drop-off, friendly staff, and my car was safe. Highly recommend!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/smg-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">SMG Park & Ride</p>
                    <p class="pro-name">Noah B.</p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic service! Fast, efficient, and the staff is super helpful. I’ll definitely use Airport Valet Parking again!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/smg-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">SMG Park & Ride</p>
                    <p class="pro-name">Mia T.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Fantastic service at Terminals Parking! Professional staff and fast service made my trip stress-free. I’ll definitely return for my next flight!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/terminals-p.gif') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Terminals Parking</p>
                    <p class="pro-name">Oliver R.</p>
                    </div>
                    
                </div>
            </div>
             <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Kwik Park is my favorite! Meet and Greet is quick and reliable, perfect for busy trips. Highly recommend it!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/kwick-p.jpg') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Kwik Park</p>
                    <p class="pro-name">Tina R.  </p>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Very convenient! The staff were polite, and the whole process was easy. Sure Parking really knows how to deliver quality service.</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/sure-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">Sure Parking</p>
                    <p class="pro-name">Liam H.</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card-rev">
                    <p class="post">
                        <span><img class="quote-img" src="{{ asset('assets/images/quote.png') }}"></span>
                        <span class="post-txt">Super convenient! Fast service and nice staff made my travel smooth. Airport Valet Parking is my go-to!</span>
                        <span><img class="nice-img" src="{{ asset('assets/images/quote.png') }}"></span>
                    </p>
                </div>
                <div class="arrow-down"></div>
                <div class="row d-flex justify-content-center">
                    <div class="">
                        <img class="profile-pic-secondary fit-image" src="{{ asset('assets/images/smg-p.png') }}" >
                    </div>
                    <div style="padding: 13px;">
                        <p class="profile-name-secondary">SMG Park & Ride</p>
                    <p class="pro-name">Liam S.</p>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</section>


<?php /*
<div>
    <div class="container">
        <div class="row offers_items" style="font-family: 'Open Sans', sans-serif;">
            @php
                $airports = app\Models\airport::where('status', 'yes')->orderBy('priority', 'asc')->get();
                $price = 28;
            @endphp
            @foreach ($airports as $airport)
                @php
                    if (preg_match('/\s/', $airport->name)) {
                        $name = str_replace(' ', '-', strtolower($airport->name));
                    } else {
                        $name = trim(strtolower($airport->name));
                    }
                    $url = str_replace(' ', '-', $name);
                    $url = $url . '-' . 'airport-parking';
                @endphp
                <div class="col-lg-4 col-md-6 offers_col">
                    <div class="offers_item more1" style="box-shadow: 1px 1px 20px 1px #90809e;">
                        <div class="row row-a">
                            <div class="col-lg-12" style="">
                                <div class="offers_image_container" style="height: 200px;">
                                    <!-- Image by https://unsplash.com/@kensuarez -->
                                    <img class="offers_image_background"
                                        src='{{ url('https://www.dashboard.jetseekergroup.com/storage/app/' . $airport->profile_image) }}'
                                        style="background-repeat: no-repeat;background-size: cover;border-top-left-radius: 24px;border-top-right-radius: 24px;"
                                        loading="lazy" alt="{{ $airport->name }}">

                                    {{-- <!--<img class="offers_image_background" src="{{ asset('storage/app/' . $airport->profile_image) }}" loading="lazy" alt="{{ $airport->name }}">-->
                                    <!--<div class="offer_name">-->
                                    <!--    <a href="{{ route('page', ['slug' => $url]) }}">{{ $airport->name }}</a>-->
                                    <!--</div>--> --}}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="offers_content">
                                    <div class="row" style="padding: 12px;">
                                        <div class="col-sm-6">
                                            <div class="airport-h">
                                                <a href="{{ route('page', ['slug' => $url]) }}"
                                                    style="color:#000000;">{{ $airport->name }}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="    text-align: right;">
                                            <div class="offers_price"> <span>Starting from</span></div>
                                            <div class="offers_price1"><span>£ @php echo(rand(25,30)); @endphp</span></div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" style="padding: 12px;">
                                        <div class="col-sm-6">
                                            <div>
                                                <i class="fa fa-map-marker" aria-hidden="true"
                                                    style="display: inline;"></i>
                                                <p style="display: inline;">{{ $airport->name }}</p>
                                            </div>
                                            <div class="rating_r rating_r_4 offers_rating">
                                                <i class="fa fa-star checked"></i>
                                                <i class="fa fa-star checked"></i>
                                                <i class="fa fa-star checked"></i>
                                                <i class="fa fa-star checked"></i>
                                                <i class="fa fa-star unchecked"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="    text-align: right;">
                                            <div class="offers_link"><a
                                                    href="{{ route('page', ['slug' => $url]) }}">read more</a></div>
                                        </div>
                                    </div>

                                    @php
                                        $companies = DB::table('companies')
                                            ->where('airport_id', $airport->id)
                                            ->get();
                                    @endphp

                                    {{-- <p class="offers_text"> </p>
                                     <div class="offers_icons">
                                        <ul class="offers_icons_list">
                                            <li class="offers_icons_item"><img style="height: 27px" src="{{url('theme/images/post.png')}}" alt="" loading="lazy"></li>
                                            <li class="offers_icons_item"><img style="height: 27px" src="{{url('theme/images/compass.png')}}" alt="" loading="lazy"></li>
                                            <li class="offers_icons_item"><img style="height: 27px" src="{{url('theme/images/bicycle.png')}}" alt="" loading="lazy"></li>
                                            <li class="offers_icons_item"><img style="height: 27px" src="{{url('theme/images/sailboat.png')}}" alt="" loading="lazy"></li>
                                </ul>
                            </div> --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php $price = $price+2; @endphp
            @endforeach
        </div>
        <div class="text-center butn-div">
            <a href="#" id="loadMore" class="btn"
                style="background: #F79F02;padding-left: 22px;padding-right: 22px;padding-top: 9px;padding-bottom: 9px;color: black;font-size: 20px;font-weight: 500;">Load
                More</a>
        </div>
    </div>
</div>

<!--<div class="more1">Content 1</div>-->
<!--<div class="more1">Content 2</div>-->
<!--<div class="more1">Content 3</div>-->
<!--<div class="more1">Content 4</div>-->
<!--<div class="more1">Content 5</div>-->
<!--<div class="more1">Content 6</div>-->
<!--<div class="more1">Content 7</div>-->
<!--<div class="more1">Content 8</div>-->
<!--<div class="more1">Content 9</div>-->
<!--<div class="more1">Content 10</div> -->
<!--<div class="more1">Content 11</div>-->
<!--<div class="more1">Content 12</div>-->
<!--<div class="more1">Content 13</div>-->
<!--<div class="more1">Content 14</div>-->
<!--<div class="more1">Content 15</div>-->
<!--<div class="more1">Content 16</div>-->
<!--<div class="more1">Content 17</div>-->
<a href="#">Load More</a>
<a href="#">Load More</a>
*/ ?>



@include('layouts.footer')
<script>
    $(function() {
        $(".more1").slice(0, 6).addClass('display');
        $("#loadMore").on('click', function(e) {
            e.preventDefault();
            $(".more1:hidden").slice(0, 14).addClass('display');
            if ($(".more1:hidden").length == 0) {
                $("#loadMore").remove();
            } else {
                $('html,body').animate({
                    scrollTop: $(this).offset().top
                }, 1500);
            }
        });
    });
</script>
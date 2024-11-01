<!-- footer area start -->
<footer class="main-footer footer-two bgp-bottom bgc-black rel z-15 pt-100 pb-115" style="background-image: url(assets/images/backgrounds/footer-two.png);">
    <div class="widget-area">
        <div class="container">
            <div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-md-3 row-cols-2">
                <div class="col col-small" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-text">
                        <div class="footer-logo mb-40">
                            <a href="index.html"><img src="assets/images/logos/logo.png" alt="Logo"></a>
                        </div>
                        <div class="footer-map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61349.64701146602!2d108.16542067386848!3d16.047164798501537!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219c792252a13%3A0xfc14e3a044436487!2sDa%20Nang%2C%20H%E1%BA%A3i%20Ch%C3%A2u%20District%2C%20Da%20Nang%2C%20Vietnam!5e0!3m2!1sen!2s!4v1729087157388!5m2!1sen!2s" 
                            style="border:0; width: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col col-small" data-aos="fade-up" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-links ms-sm-5">
                        <div class="footer-title">
                            <h5>Services</h5>
                        </div>
                        <ul class="list-style-three">
                            <li><a href="destination-details.html">Best Tour Guide</a></li>
                            <li><a href="destination-details.html">Tour Booking</a></li>
                            <li><a href="destination-details.html">Hotel Booking</a></li>
                            <li><a href="destination-details.html">Ticket Booking</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col col-small" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-links ms-md-4">
                        <div class="footer-title">
                            <h5>Company</h5>
                        </div>
                        <ul class="list-style-three">
                            <li><a href="about.html">About Company</a></li>
                            <li><a href="blog.html">Community Blog</a></li>
                            <li><a href="contact.html">Jobs and Careers</a></li>
                            <li><a href="blog.html">latest News Blog</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col col-small" data-aos="fade-up" data-aos-delay="150" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-links ms-lg-4">
                        <div class="footer-title">
                            <h5>Destinations</h5>
                        </div>
                        <ul class="list-style-three">
                            <li><a href="destination-details.html">African Safaris</a></li>
                            <li><a href="destination-details.html">Alaska & Canada</a></li>
                            <li><a href="destination-details.html">South America</a></li>
                            <li><a href="destination-details.html">Middle East</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col col-md-6 col-10 col-small" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-contact">
                        <div class="footer-title">
                            <h5>Get In Touch</h5>
                        </div>
                        <ul class="list-style-one">
                            <li><i class="fal fa-map-marked-alt"></i> 578 Level, D-block 45 Street Melbourne, Australia</li>
                            <li><i class="fal fa-envelope"></i> <a href="mailto:supportrevelo@gmail.com">supportrevelo @gmail.com</a></li>
                            <li><i class="fal fa-phone-volume"></i> <a href="callto:+88012334588">+880 (123) 345 88</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-transparent pt-20 pb-5">
        <div class="container">
            <div class="row">
               <div class="col-lg-5">
                    <div class="copyright-text text-center text-lg-start">
                        <p>@Copy 2024 <a href="index.html">Ravelo</a>, All rights reserved</p>
                    </div>
               </div>
               <div class="col-lg-7 text-center text-lg-end">
                   <ul class="footer-bottom-nav">
                       <li><a href="about.html">Terms</a></li>
                       <li><a href="about.html">Privacy Policy</a></li>
                       <li><a href="about.html">Legal notice</a></li>
                       <li><a href="about.html">Accessibility</a></li>
                   </ul>
               </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer area end -->

</div>
<!--End pagewrapper-->

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    <!-- Jquery -->
    <script src="{{asset('clients/assets/js/jquery-3.6.0.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('clients/assets/js/bootstrap.min.js')}}"></script>
    <!-- Appear Js -->
    <script src="{{asset('clients/assets/js/appear.min.js')}}"></script>
    <!-- Slick -->
    <script src="{{asset('clients/assets/js/slick.min.js')}}"></script>
    <!-- Magnific Popup -->
    <script src="{{asset('clients/assets/js/jquery.magnific-popup.min.js')}}"></script>
    <!-- Nice Select -->
    <script src="{{asset('clients/assets/js/jquery.nice-select.min.js')}}"></script>
    <!-- Image Loader -->
    <script src="{{asset('clients/assets/js/imagesloaded.pkgd.min.js')}}"></script>
    <!-- Skillbar -->
    <script src="{{asset('clients/assets/js/skill.bars.jquery.min.js')}}"></script>
    <!-- Jquery UI -->
    <script src="{{asset('clients/assets/js/jquery-ui.min.js')}}"></script>
    <!-- Isotope -->
    <script src="{{asset('clients/assets/js/isotope.pkgd.min.js')}}"></script>
    <!--  AOS Animation -->
    <script src="{{asset('clients/assets/js/aos.js')}}"></script>
    <!-- Custom script -->
    <script src="{{asset('clients/assets/js/script.js')}}"></script>
    {{-- jquery-toast  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- paypal-payment  --}}
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}"></script>

    <!-- Custom script by Dev dien-->
    <script src="{{asset('clients/assets/js/custom-js.js')}}"></script>
    <script src="{{asset('clients/assets/js/jquery.datetimepicker.full.min.js')}}"></script>

</body>

</html>
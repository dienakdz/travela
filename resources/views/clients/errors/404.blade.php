@include('clients.blocks.header')

<!-- 404 Error Area start -->
<section class="error-area pt-70 pb-100 rel z-1">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-5 col-lg-6">
                <div class="error-content rmb-55" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">
                    <h1>OPPS! </h1>
                    <div class="section-title mt-15 mb-25">
                        <h2>Không tìm thấy trang này</h2>
                    </div>
                    <p>Xin lỗi, chúng tôi không thể tìm thấy trang bạn đang tìm kiếm.  
                        Bạn có thể kiểm tra lại URL hoặc quay về <a href="/">trang chủ</a>.  
                        Nếu cần hỗ trợ, vui lòng liên hệ với chúng tôi.</p>
                        
                    <form class="newsletter-form mt-40 mb-50" action="{{ route('search-voice-text') }}">
                        <input  type="text" name="keyword" placeholder="Search" class="searchbox" required>
                        <button type="submit" class="theme-btn bgc-secondary style-two">
                            <span data-hover="Search">Tìm kiếm</span>
                            <i class="fal fa-arrow-right"></i>
                        </button>
                    </form>
                    <div class="keywords">
                        <a href="{{ route('about') }}">Giới thiệu</a>
                        <a href="{{ route('tours') }}">Tours</a>
                        <a href="{{ route('destination') }}">Điểm Đến</a>
                        <a href="{{ route('contact') }}">Liên hệ</a>
                        <a href="{{ route('home') }}">Trang Chủ</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6">
                <div class="error-images" data-aos="fade-right" data-aos-duration="1500" data-aos-offset="50">
                    <img src="{{ asset('clients/assets/images/newsletter/404.png') }}" alt="404 Error">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 404 Error Area end -->

@include('clients.blocks.footer')

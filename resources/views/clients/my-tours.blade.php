@include('clients.blocks.header')
@include('clients.blocks.banner')
<!-- Tour List Area start -->
<section class="tour-list-page py-100 rel z-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-10 rmb-75">
                <div class="shop-sidebar mb-30">
                    <div class="widget widget-tour" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                        <h6 class="widget-title">Popular Tours</h6>
                        <div class="destination-item tour-grid style-three bgc-lighter">
                            <div class="image">
                                <span class="badge">10% Off</span>
                                <img src="assets/images/widgets/tour1.jpg" alt="Tour">
                            </div>
                            <div class="content">
                                <div class="destination-header">
                                    <span class="location"><i class="fal fa-map-marker-alt"></i> Bali, Indonesia</span>
                                    <div class="ratting">
                                        <i class="fas fa-star"></i>
                                        <span>(4.8)</span>
                                    </div>
                                </div>
                                <h6><a href="tour-details.html">Relinking Beach, Bali, Indonesia</a></h6>
                            </div>
                        </div>
                        <div class="destination-item tour-grid style-three bgc-lighter">
                            <div class="image">
                                <img src="assets/images/widgets/tour1.jpg" alt="Tour">
                            </div>
                            <div class="content">
                                <div class="destination-header">
                                    <span class="location"><i class="fal fa-map-marker-alt"></i> Bali, Indonesia</span>
                                    <div class="ratting">
                                        <i class="fas fa-star"></i>
                                        <span>(4.8)</span>
                                    </div>
                                </div>
                                <h6><a href="tour-details.html">Relinking Beach, Bali, Indonesia</a></h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-9">
                @foreach ($myTours as $tour)
                    <div class="destination-item style-three bgc-lighter" data-aos="fade-up" data-aos-duration="1500"
                        data-aos-offset="50">
                        <div class="image">
                            @if ($tour->bookingStatus == 'b')
                                <span class="badge">Đợi xác nhận</span>
                            @elseif ($tour->bookingStatus == 'y')
                                <span class="badge bgc-pink">Sắp khởi hành</span>
                            @elseif ($tour->bookingStatus == 'f')
                                <span class="badge bgc-primary">Hoàn thành</span>
                            @elseif ($tour->bookingStatus == 'c')
                                <span class="badge" style="background-color: red">Đã hủy</span>
                            @endif


                            <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0] . '') }}"
                                alt="Tour List">
                        </div>
                        <div class="content">
                            <div class="destination-header">
                                <span class="location"><i
                                        class="fal fa-map-marker-alt"></i>{{ $tour->destination }}</span>
                                <div class="ratting">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($tour->rating && $i < $tour->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor

                                </div>
                            </div>
                            <h5><a
                                    href="{{ route('tour-booked', ['bookingId' => $tour->bookingId, 'checkoutId' => $tour->checkoutId]) }}">{{ $tour->title }}</a>
                            </h5>
                            <p>{{ $tour->description }}</p>
                            <ul class="blog-meta">
                                <li><i class="far fa-clock"></i>{{ $tour->time }}</li>
                                <li><i class="far fa-user"></i> {{ $tour->numAdults + $tour->numChildren }} người</li>
                            </ul>
                            <div class="destination-footer">
                                <span class="price"><span>{{ number_format($tour->totalPrice, 0) }}</span>/vnđ</span>
                                @if ($tour->bookingStatus == 'f')
                                    <a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}"
                                        class="theme-btn style-two style-three">
                                        @if ($tour->rating)
                                            <span data-hover="Đã đánh giá">Đã đánh giá</span>
                                        @else
                                            <span data-hover="Đánh giá">Đánh giá</span>
                                        @endif
                                        <i class="fal fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <ul class="pagination pt-15 flex-wrap" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <li class="page-item disabled">
                        <span class="page-link"><i class="far fa-chevron-left"></i></span>
                    </li>
                    <li class="page-item active">
                        <span class="page-link">
                            1
                            <span class="sr-only">(current)</span>
                        </span>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- Tour List Area end -->
@include('clients.blocks.footer')

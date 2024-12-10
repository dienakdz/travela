@include('clients.blocks.header')
@include('clients.blocks.banner')

<!-- Tour Grid Area start -->
<section class="tour-grid-page py-100 rel z-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-10 rmb-75">
                <div class="shop-sidebar">
                    <div class="div_filter_clear">
                        <button class="clear_filter" name="btn_clear">
                            <a href="{{ route('tours') }}">Clear</a>
                        </button>
                    </div>
                    <div class="widget widget-filter" data-aos="fade-up" data-aos-delay="50" data-aos-duration="1500"
                        data-aos-offset="50">
                        <h6 class="widget-title">Lọc theo giá</h6>
                        <div class="price-filter-wrap">
                            <div class="price-slider-range"></div>
                            <div class="price">
                                <span>Giá </span>
                                <input type="text" id="price" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="widget widget-activity" data-aos="fade-up" data-aos-duration="1500"
                        data-aos-offset="50">
                        <h6 class="widget-title">Điểm đến</h6>
                        <ul class="radio-filter">
                            <li>
                                <input class="form-check-input" type="radio" name="domain" id="id_mien_bac"
                                    value="b">
                                <label for="id_mien_bac">Miền Bắc <span>{{ $domainsCount['mien_bac'] }}</span></label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="domain" id="id_mien_trung"
                                    value="t">
                                <label for="id_mien_trung">Miền Trung
                                    <span>{{ $domainsCount['mien_trung'] }}</span></label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="domain" id="id_mien_nam"
                                    value="n">
                                <label for="id_mien_nam">Miền Nam <span>{{ $domainsCount['mien_nam'] }}</span></label>
                            </li>
                        </ul>
                    </div>

                    <div class="widget widget-reviews" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                        <h6 class="widget-title">Đánh giá</h6>
                        <ul class="radio-filter">
                            <li>
                                <input class="form-check-input" type="radio" name="filter_star" id="5star"
                                    value="5">
                                <label for="5star">
                                    <span class="ratting">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="filter_star" id="4star"
                                    value="4">
                                <label for="4star">
                                    <span class="ratting">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt white"></i>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="filter_star" id="3star"
                                    value="3">
                                <label for="3star">
                                    <span class="ratting">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star white"></i>
                                        <i class="fas fa-star-half-alt white"></i>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="filter_star" id="2star"
                                    value="2">
                                <label for="2star">
                                    <span class="ratting">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star white"></i>
                                        <i class="fas fa-star white"></i>
                                        <i class="fas fa-star-half-alt white"></i>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="filter_star" id="1star"
                                    value="1">
                                <label for="1star">
                                    <span class="ratting">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star white"></i>
                                        <i class="fas fa-star white"></i>
                                        <i class="fas fa-star white"></i>
                                        <i class="fas fa-star-half-alt white"></i>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="widget widget-duration" data-aos="fade-up" data-aos-duration="1500"
                        data-aos-offset="50">
                        <h6 class="widget-title">Thời gian</h6>
                        <ul class="radio-filter">
                            <li>
                                <input class="form-check-input" type="radio" name="duration" id="3ngay2dem"
                                    value="3n2d">
                                <label for="3ngay2dem">3 ngày 2 đêm</label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="duration" id="4ngay3dem"
                                    value="4n3d">
                                <label for="4ngay3dem">4 ngày 3 đêm</label>
                            </li>
                            <li>
                                <input class="form-check-input" type="radio" name="duration" id="5ngay4dem"
                                    value="5n4d">
                                <label for="5ngay4dem">5 ngày 4 đêm</label>
                            </li>
                        </ul>
                    </div>

                    @if (!$toursPopular->isEmpty())
                        <div class="widget widget-tour" data-aos="fade-up" data-aos-duration="1500"
                            data-aos-offset="50">
                            <h6 class="widget-title">Phổ biến Tours</h6>
                            @foreach ($toursPopular as $tour)
                                <div class="destination-item tour-grid style-three bgc-lighter">
                                    <div class="image">
                                        <span class="badge">10% Off</span>
                                        <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0]) }}"
                                            alt="Tour">
                                    </div>
                                    <div class="content">
                                        <div class="destination-header">
                                            <span class="location"><i class="fal fa-map-marker-alt"></i>
                                                {{ $tour->destination }}</span>
                                            <div class="ratting">
                                                <i class="fas fa-star"></i>
                                                <span>{{ $tour->rating }}</span>
                                            </div>
                                        </div>
                                        <h6><a
                                                href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="widget widget-cta mt-30" data-aos="fade-up" data-aos-duration="1500"
                    data-aos-offset="50">
                    <div class="content text-white">
                        <span class="h6">Khám Phá Việt Nam</span>
                        <h3>Địa điểm du lịch tốt nhất</h3>
                        <a href="{{ route('tours') }}" class="theme-btn style-two bgc-secondary">
                            <span data-hover="Khám phá ngay">Khám phá ngay</span>
                            <i class="fal fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="image">
                        <img src="{{ asset('clients/assets/images/widgets/cta-widget.png') }}" alt="CTA">
                    </div>
                    <div class="cta-shape"><img src="{{ asset('clients/assets/images/widgets/cta-shape2.png') }}"
                            alt="Shape"></div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="shop-shorter rel z-3 mb-20">
                    <div class="sort-text mb-15 me-4 me-xl-auto">
                        Tours tìm thấy
                    </div>
                    <div class="sort-text mb-15 me-4">
                        Sắp xếp theo
                    </div>
                    <select id="sorting_tours">
                        <option value="default" selected="">Sắp xếp theo</option>
                        <option value="new">Mới nhất</option>
                        <option value="old">Cũ nhất</option>
                        <option value="hight-to-low">Cao đến thấp</option>
                        <option value="low-to-high">Thấp đến cao</option>
                    </select>
                </div>

                <div class="tour-grid-wrap">
                    <div class="loader"></div>
                    <div class="row" id="tours-container">
                        @include('clients.partials.filter-tours')

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Tour Grid Area end -->

@include('clients.blocks.new_letter')
@include('clients.blocks.footer')
<script>
    var filterToursUrl = "{{ route('filter-tours') }}";
</script>

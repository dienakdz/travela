@include('clients.blocks.header')
@include('clients.blocks.banner')

<!-- Popular Destinations Area start -->
<section class="popular-destinations-area pt-100 pb-90 rel z-1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="section-title text-center counter-text-wrap mb-40" data-aos="fade-up" data-aos-duration="1500"
                    data-aos-offset="50">
                    <h2>Khám phá các điểm đến phổ biến</h2>
                    <p>Website <span class="count-text plus" data-speed="3000" data-stop="34500">0</span> trải nghiệm phổ
                        biến nhất mà bạn sẽ nhớ</p>
                    <ul class="destinations-nav mt-30">
                        <li data-filter="*" class="active">Tất cả</li>
                        <li data-filter=".domain-b">Miền Bắc</li>
                        <li data-filter=".domain-t">Miền Trung</li>
                        <li data-filter=".domain-n">Miền Nam</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row gap-10 destinations-active justify-content-center">
                @php $count = 0; @endphp
                @foreach ($tours as $tour)
                    @if ($count % 3 == 2)
                        <div class="col-md-6 item domain-{{ $tour->domain }}">
                        @else
                            <div class="col-xl-3 col-md-6 item domain-{{ $tour->domain }}">
                    @endif
                    <div class="destination-item style-two" data-aos-duration="1500" data-aos-offset="50">
                        <div class="image" style="max-height: 250px">
                            <a href="#" class="heart"><i class="fas fa-heart"></i></a>
                            <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0]) }}"
                                alt="Destination">
                        </div>
                        <div class="content">
                            <h6 class="tour-title"><a
                                    href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                            </h6>
                            <span class="time">{{ $tour->time }}</span>
                            <a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}" class="more"><i
                                    class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
            </div>
            @php $count++; @endphp
            @endforeach

        </div>
    </div>
    </div>
</section>
<!-- Popular Destinations Area end -->

@include('clients.blocks.new_letter')
@include('clients.blocks.footer')

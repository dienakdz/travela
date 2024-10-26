@foreach ($tours as $tour)
    <div class="col-xl-4 col-md-6">
        <div class="destination-item tour-grid style-three bgc-lighter block_tours" data-aos="fade-up"
            data-aos-duration="1500" data-aos-offset="50">
            <div class="image">
                <span class="badge bgc-pink">Featured</span>
                <a href="#" class="heart"><i class="fas fa-heart"></i></a>
                <img src="{{ asset('clients/assets/images/gallery-tours/' . $tour->images[0] . '') }}" alt="Tour List">
            </div>
            <div class="content">
                <div class="destination-header">
                    <span class="location"><i class="fal fa-map-marker-alt"></i>
                        {{ $tour->destination }}</span>
                    <div class="ratting">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <h6><a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                </h6>
                <ul class="blog-meta">
                    <li><i class="far fa-clock"></i>{{ $tour->time }}</li>
                    <li><i class="far fa-user"></i>{{ $tour->quantity }}</li>
                </ul>
                <div class="destination-footer">
                    <span class="price"><span>{{ number_format($tour->priceAdult, 0, ',', '.') }}</span>
                        VND / người</span>
                    <a href="{{ route('tour-detail', ['id' => $tour->tourId]) }}"
                        class="theme-btn style-two style-three">
                        <i class="fal fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach

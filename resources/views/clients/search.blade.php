@include('clients.blocks.header')
@include('clients.blocks.banner')
<!-- Tour Grid Area start -->
<section class="tour-grid-page py-100 rel z-2">
    <div class="container">
        <div class="shop-shorter rel z-3 mb-20">
            <select>
                <option value="default" selected="">Filter by Price</option>
                <option value="$10-$100">$10-$100</option>
                <option value="$101-$200">$101-$200</option>
                <option value="$201-$300">$201-$300</option>
                <option value="$301-$400">$301-$400</option>
                <option value="$401-$500">$401-$500</option>
            </select>
            <select>
                <option value="default" selected="">By Reviews</option>
                <option value="1-star">1 Star</option>
                <option value="2-star">2 Star</option>
                <option value="3-star">3 Star</option>
                <option value="4-star">4 Star</option>
                <option value="5-star">5 Star</option>
            </select>
            <select>
                <option value="default" selected="">By Language</option>
                <option value="english">English</option>
                <option value="bangla">Bangla</option>
            </select>
            <select class="me-xl-auto">
                <option value="default" selected="">By Durations</option>
                <option value="10-100hr">10-100hr</option>
                <option value="101-200hr">101-200hr</option>
                <option value="201-300hr">201-300hr</option>
                <option value="301-400hr">301-400hr</option>
                <option value="401-500hr">401-500hr</option>
            </select>
            <select>
                <option value="default" selected="">Short By</option>
                <option value="new">Newness</option>
                <option value="old">Oldest</option>
                <option value="hight-to-low">High To Low</option>
                <option value="low-to-high">Low To High</option>
            </select>
        </div>
        <hr class="mb-50">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="destination-item tour-grid style-three bgc-lighter" data-aos="fade-up"
                    data-aos-duration="1500" data-aos-offset="50">
                    <div class="image">
                        <span class="badge bgc-pink">Featured</span>
                        <a href="#" class="heart"><i class="fas fa-heart"></i></a>
                        <img src="assets/images/destinations/tour-list1.jpg" alt="Tour List">
                    </div>
                    <div class="content">
                        <div class="destination-header">
                            <span class="location"><i class="fal fa-map-marker-alt"></i> Bali, Indonesia</span>
                            <div class="ratting">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <h5><a href="tour-details.html">Bay Cruise lake trip by Boat in Bali, Indonesia</a></h5>
                        <p>Bali, Indonesia, is tropical paradise renowned breathtaking beaches and landscapes</p>
                        <ul class="blog-meta">
                            <li><i class="far fa-clock"></i> 3 days 2 nights</li>
                            <li><i class="far fa-user"></i> 5-8 guest</li>
                        </ul>
                        <div class="destination-footer">
                            <span class="price"><span>$58.00</span>/person</span>
                            <a href="tour-details.html" class="theme-btn style-two style-three">
                                <span data-hover="Book Now">Book Now</span>
                                <i class="fal fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <ul class="pagination justify-content-center pt-15 flex-wrap" data-aos="fade-up"
                    data-aos-duration="1500" data-aos-offset="50">
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
<!-- Tour Grid Area end -->

@include('clients.blocks.new_letter')
@include('clients.blocks.footer')

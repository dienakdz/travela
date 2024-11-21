<h3>Đánh giá của khách hàng</h3>
<div class="clients-reviews bgc-black mt-30 mb-60">
    <div class="left">
        <b>{{ number_format($avgStar, 1) }}</b>
        <span>({{ $countReview }} đánh giá)</span>
        <div class="ratting">
            @for ($i = 0; $i < 5; $i++)
                @if ($avgStar && $i < $avgStar)
                    <i class="fas fa-star"></i>
                @else
                    <i class="far fa-star"></i>
                @endif
            @endfor
        </div>
    </div>
    <div class="right">

    </div>
</div>

<h3>Ý kiến ​​của khách hàng</h3>
<div class="comments mt-30 mb-60">
    @foreach ($getReviews as $review)
        <div class="comment-body" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
            <div class="author-thumb">
                <img src="{{ asset('admin/assets/images/user-profile/' . $review->avatar) }}" alt="">
            </div>
            <div class="content">
                <h6>{{ $review->fullName }}</h6>
                <div class="ratting">
                    @for ($i = 0; $i < 5; $i++)
                        @if ($review->rating && $i < $review->rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor

                </div>
                <span class="time">{{ $tourDetail->time }}</span>
                <p>{{ $review->comment }}</p>
            </div>
        </div>
    @endforeach
</div>

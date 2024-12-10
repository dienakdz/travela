@include('clients.blocks.header')
@include('clients.blocks.banner')

<section class="container" style="margin-top:50px; margin-bottom: 100px">
    {{-- <h1 class="text-center booking-header">Tổng Quan Về Chuyến Đi</h1> --}}

    <form action="{{ route('cancel-booking') }}" method="POST" class="booking-container">
        @csrf
        <!-- Contact Information -->
        <div class="booking-info">
            <h2 class="booking-header">Thông Tin Liên Lạc</h2>
            <div class="booking__infor">
                <div class="form-group">
                    <label for="username">Họ và tên*</label>
                    <input type="text" id="username" placeholder="Nhập Họ và tên" name="fullName"
                        value="{{ $tour_booked->fullName }}" readonly>
                    <span class="error-message" id="usernameError"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" placeholder="sample@gmail.com" name="email"
                        value="{{ $tour_booked->email }}" readonly>
                    <span class="error-message" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="tel">Số điện thoại*</label>
                    <input type="number" id="tel" placeholder="Nhập số điện thoại liên hệ" name="tel"
                        value="{{ $tour_booked->phoneNumber }}" readonly>
                    <span class="error-message" id="telError"></span>
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ*</label>
                    <input type="text" id="address" placeholder="Nhập địa chỉ liên hệ" name="address"
                        value="{{ $tour_booked->address }}" readonly>
                    <span class="error-message" id="addressError"></span>
                </div>

            </div>

            <!-- Privacy Agreement Section -->
            <div class="privacy-section">
                <p>Bằng cách nhấp chuột vào nút "ĐỒNG Ý" dưới đây, Khách hàng đồng ý rằng các điều kiện điều khoản
                    này sẽ được áp dụng. Vui lòng đọc kỹ điều kiện điều khoản trước khi lựa chọn sử dụng dịch vụ của
                    Travela.</p>
                <div class="privacy-checkbox">
                    <input type="checkbox" id="agree" name="agree" checked disabled>
                    <label for="agree">Tôi đã đọc và đồng ý với <a href="#" target="_blank">Điều khoản thanh
                            toán</a></label>
                </div>
            </div>
            <!-- Payment Method -->
            <h2 class="booking-header">Phương Thức Thanh Toán</h2>

            <label class="payment-option">
                <input type="radio" value="office-payment" @if ($tour_booked->paymentMethod == 'office-payment') checked @endif disabled>
                <img src="{{ asset('clients/assets/images/contact/icon.png') }}" alt="Office Payment">
                Thanh toán tại văn phòng
            </label>

            <label class="payment-option">
                <input type="radio" value="paypal-payment" @if ($tour_booked->paymentMethod == 'paypal-payment') checked @endif disabled>
                <img src="{{ asset('clients/assets/images/booking/cong-thanh-toan-paypal.jpg') }}" alt="PayPal">
                Thanh toán bằng PayPal
            </label>

            <label class="payment-option">
                <input type="radio" value="momo-payment" @if ($tour_booked->paymentMethod == 'momo-payment') checked @endif disabled>
                <img src="{{ asset('clients/assets/images/booking/thanh-toan-momo.jpg') }}" alt="MoMo">
                Thanh toán bằng Momo
            </label>

        </div>

        <!-- Order Summary -->
        <div class="booking-summary">
            <div class="summary-section">
                <div>
                    <p>Mã tour : {{ $tour_booked->tourId }}</p>
                    <input type="hidden" name="tourId" id="tourId" value="{{ $tour_booked->tourId }}">
                    <h5 class="widget-title">{{ $tour_booked->title }}</h5>
                    <p>Ngày khởi hành : {{ date('d-m-Y', strtotime($tour_booked->startDate)) }}</p>
                    <p>Ngày kết thúc : {{ date('d-m-Y', strtotime($tour_booked->endDate)) }}</p>
                </div>

                <div class="order-summary" style="border-bottom: 1px solid #d6d6d6; margin-bottom:20px">
                    <div class="summary-item">
                        <span>Người lớn:</span>
                        <div>
                            <span class="quantity__adults-booked">{{ number_format($tour_booked->numAdults) }}</span>
                            <input type="hidden" name="quantity__adults" value="{{ $tour_booked->numAdults }}">
                            <span>X</span>
                            <span class="total-price-booked">{{ number_format($tour_booked->priceAdult, 0, ',', '.') }}
                                VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Trẻ em:</span>
                        <div>
                            <span
                                class="quantity__children-booked">{{ number_format($tour_booked->numChildren) }}</span>
                            <input type="hidden" name="quantity__children" value="{{ $tour_booked->numChildren }}">
                            <span>X</span>
                            <span class="total-price-booked">{{ number_format($tour_booked->priceChild, 0, ',', '.') }}
                                VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Giảm giá:</span>
                        <div>
                            <span class="total-price-booked">
                                {{ number_format($tour_booked->numAdults * $tour_booked->priceAdult + $tour_booked->numChildren * $tour_booked->priceChild - $tour_booked->totalPrice, 0, ',', '.') }}
                                VNĐ
                            </span>
                        </div>
                    </div>
                    <div class="summary-item total-price-booked">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($tour_booked->totalPrice, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>

                <input type="hidden" name="bookingId" value="{{ $bookingId }}">

                @if ($tour_booked->bookingStatus == 'f')
                    <a href="{{ route('tour-detail', ['id' => $tour_booked->tourId]) }}" class="booking-btn"style="display: inline-block; text-align: center;">
                       Đánh giá
                    </a>
                @else
                    <button type="submit" class="booking-btn btn-cancel-booking {{ $hide }}">Hủy
                        Tour</button>
                @endif

            </div>
        </div>
    </form>
</section>


@include('clients.blocks.footer')

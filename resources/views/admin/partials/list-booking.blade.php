@foreach ($list_booking as $booking)
    <tr>
        <td>{{ $booking->title }}</td>
        <td>{{ $booking->fullName }}</td>
        <td>{{ $booking->email }}</td>
        <td>{{ $booking->phoneNumber }}</td>
        <td>{{ $booking->address }}</td>
        <td>{{ date('d-m-Y', strtotime($booking->bookingDate)) }}</td>
        <td>{{ $booking->numAdults }}</td>
        <td>{{ $booking->numChildren }}</td>
        <td>{{ number_format($booking->totalPrice, 0, ',', '.') }}</td>
        <td>
            @if ($booking->bookingStatus == 'c')
                <span class="badge badge-danger">Đã hủy</span>
            @elseif ($booking->bookingStatus == 'b')
                <span class="badge badge-warning">Chưa xác nhận</span>
            @elseif ($booking->bookingStatus == 'y')
                <span class="badge badge-primary">Đã xác nhận</span>
            @elseif ($booking->bookingStatus == 'f')
                <span class="badge badge-success">Đã hoàn thành</span>
            @endif
        </td>
        <td>
            @if ($booking->paymentMethod == 'momo-payment')
                <img src="{{ asset('admin/assets/images/icon/icon_momo.png') }}" class="icon_payment" alt="">
            @elseif ($booking->paymentMethod == 'paypal-payment')
                <img src="{{ asset('admin/assets/images/icon/icon_paypal.png') }}" class="icon_payment" alt="">
            @else
                <img src="{{ asset('admin/assets/images/icon/icon_office.png') }}" class="icon_payment" alt="">
            @endif
        </td>

        <td>
            @if ($booking->paymentStatus == 'n')
                <span class="badge badge-danger">Chưa thanh toán</span>
            @else
                <span class="badge badge-success">Đã thanh toán</span>
            @endif
        </td>

        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                </button>
                <div class="dropdown-menu" x-placement="bottom-start"
                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(71px, 38px, 0px);">
                    @if ($booking->bookingStatus == 'b')
                    <a class="dropdown-item confirm-booking" href="javascript:void(0)" data-bookingId="{{ $booking->bookingId }}"
                        data-urlConfirm="{{ route('admin.confirm-booking') }}">Xác nhận</a>
                    @endif
                    <a class="dropdown-item finish-booking {{ $booking->hide }}" href="javascript:void(0)" data-bookingId="{{ $booking->bookingId }}"
                        data-urlfinish="{{ route('admin.finish-booking') }}">Đã hoàn thành</a>
                    <a class="dropdown-item" href="{{ route('admin.booking-detail',['id' => $booking->bookingId]) }}">Xem chi tiết</a>
                </div>
            </div>
        </td>
    </tr>
@endforeach

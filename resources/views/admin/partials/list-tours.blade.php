@foreach ($tours as $tour)
    <tr>
        <td>{{ $tour->title }}</td>
        <td>{{ $tour->time }}</td>
        <td>{!! $tour->description !!}</td>
        <td>{{ $tour->quantity }}</td>
        <td>{{ number_format($tour->priceAdult, 0, ',', '.') }}</td>
        <td>{{ number_format($tour->priceChild, 0, ',', '.') }}</td>
        <td>{{ $tour->destination }}</td>
        <td>{{ $tour->availability }}</td>
        <td>{{ date('d-m-Y', strtotime($tour->startDate)) }}</td>
        <td>{{ date('d-m-Y', strtotime($tour->endDate)) }}</td>
        <td>
            <button type="button" class="btn-action-listTours edit-tour" data-toggle="modal" data-target="#edit-tour-modal"
                data-tourId="{{ $tour->tourId }}" data-urledit = "{{ route('admin.tour-edit') }}">
                <span class="glyphicon glyphicon-edit" style="color: #26B99A; font-size:24px" aria-hidden="true"></span>
            </button>
        </td>
        <td>
            <a href="{{ route('admin.delete-tour') }}" data-tourId="{{ $tour->tourId }}" class="delete-tour">
                <span class="glyphicon glyphicon-trash" style="color: red; font-size:24px" aria-hidden="true"></span>
            </a>
        </td>
    </tr>
@endforeach

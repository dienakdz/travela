@foreach ($tours as $tour)
    <tr>
        <td>{{ $tour->title }}</td>
        <td>{{ $tour->time }}</td>
        <td>{!! $tour->description !!}</td>
        <td>{{ $tour->quantity }}</td>
        <td>{{ $tour->priceAdult }}</td>
        <td>{{ $tour->priceChild }}</td>
        <td>{{ $tour->destination }}</td>
        <td>{{ $tour->availability }}</td>
        <td>{{ date('d-m-Y', strtotime($tour->startDate)) }}</td>
        <td>{{ date('d-m-Y', strtotime($tour->endDate)) }}</td>
        <td>
            <span class="glyphicon glyphicon-edit" style="color: #26B99A; font-size:24px" aria-hidden="true"></span>
        </td>
        <td>
            <a href="{{ route('admin.delete-tour') }}" data-tourId="{{ $tour->tourId }}" class="delete-tour">
                <span class="glyphicon glyphicon-trash" style="color: red; font-size:24px" aria-hidden="true"></span>
            </a>
        </td>
    </tr>
@endforeach

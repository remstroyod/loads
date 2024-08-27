<h1>Hello, Admin!</h1>
<p>New Order: {{ $order->id }}</p>

<hr>

<h2>Detail Order</h2>
<table>
    <tbody>
    <tr>
        <td style="vertical-align:top;">Link</td>
        <td style="vertical-align:top;">
            <a href="https://trapla-loads-today-now-spa.lkw-walter.com/detail/{{ $order->uuid }}" target="_blank">{{ $order->uuid }}</a>
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Offer Price</td>
        <td style="vertical-align:top;">
            {{ $order->offerPrice }}
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Car Number</td>
        <td style="vertical-align:top;">{{ $order->assign_number_car }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Track Number</td>
        <td style="vertical-align:top;">{{ $order->assign_number_track }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Date Loading</td>
        <td style="vertical-align:top;">{{ $order->date_loading->format('Y-m-d H:i') }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Date Unloading</td>
        <td style="vertical-align:top;">{{ $order->date_unloading->format('Y-m-d H:i') }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Vehicle Properties</td>
        <td style="vertical-align:top;">
            @isset($order->vehicleProperties['height'])
                <div><strong>Height:</strong> {{ $order->vehicleProperties['height'] }}</div>
            @endisset

                @isset($order->vehicleProperties['classification'])
                    <div><strong>Classification:</strong> {{ $order->vehicleProperties['classification'] }}</div>
                @endisset
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Total Weight</td>
        <td style="vertical-align:top;">
            @isset($order->totalWeight['unit'])
                <div><strong>Unit:</strong> {{ $order->totalWeight['unit'] }}</div>
            @endisset
            @isset($order->totalWeight['value'])
                <div><strong>Value:</strong> {{ $order->totalWeight['value'] }}</div>
            @endisset
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Goods</td>
        <td style="vertical-align:top;">
            @if($order->goods)
                @foreach($order->goods as $item)
                    <div><strong>Description:</strong> {{ $item->description }}</div>

                    @isset($item->weight['unit'])
                    <div><strong>Weight Unit:</strong> {{ $item->weight['unit'] }}</div>
                    @endisset
                    @isset($item->weight['value'])
                    <div><strong>Weight Value:</strong> {{ $item->weight['value'] }}</div>
                    @endisset

                    @isset($item->quantity['unit'])
                    <div><strong>Quantity Unit:</strong> {{ $item->quantity['unit'] }}</div>
                    @endisset
                    @isset($item->quantity['value'])
                    <div><strong>Quantity Value:</strong> {{ $item->quantity['value'] }}</div>
                    @endisset

                    <div><strong>Type Code:</strong> {{ $item->goodsTypeCode }}</div>
                @endforeach
            @endif
        </td>
    </tr>

    <tr>
        <td style="vertical-align:top;">Milestones</td>
        <td style="vertical-align:top;">
            @if($order->milestones)
                @foreach($order->milestones as $item)
                    <div><strong>Milestone Id:</strong> {{ $item->milestoneId }}</div>
                    <div><strong>Type:</strong> {{ $item->type }}</div>
                    @isset($item->rta['start'])
                    <div><strong>Rta Start:</strong> {{ $item->rta['start'] }}</div>
                    @endisset
                    @isset($item->rta['end'])
                    <div><strong>Rta End:</strong> {{ $item->rta['end'] }}</div>
                    @endisset

                    @if($item->addres)

                        <h3>Address</h3>
                        <div><strong>Country Code: </strong> {{ $item->addres->countryCode }}</div>
                        <div><strong>Zip Code: </strong> {{ $item->addres->zipCode }}</div>
                        <div><strong>City: </strong> {{ $item->addres->city }}</div>
{{--                        <div><strong>Loading Times: </strong> {{ $item->addres->time->onloading }}</div>--}}
{{--                        <div><strong>Loading Times: </strong> {{ $item->addres->time->offloading }}</div>--}}
                    @endif

                @endforeach
            @endif
        </td>
    </tr>

    </tbody>
</table>

<hr>

<h2>Driver</h2>

<table>
    <tbody>
    <tr>
        <td style="vertical-align:top;">Gender</td>
        <td style="vertical-align:top;">{{ $order->driver->gender->description() }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">First Name</td>
        <td style="vertical-align:top;">{{ $order->driver->first_name }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Surname</td>
        <td style="vertical-align:top;">{{ $order->driver->surname }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Phone</td>
        <td style="vertical-align:top;">{{ $order->driver->phone }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Email</td>
        <td style="vertical-align:top;">{{ $order->driver->email }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Has License</td>
        <td style="vertical-align:top;">{{ $order->driver->has_license }}</td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Languages</td>
        <td style="vertical-align:top;">
        @if($order->driver->languages)
                @foreach($order->driver->languages as $item)
                    <div>{{ $item->code }} - {{ $item->name }}</div>
                @endforeach
        @endif
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top;">Files</td>
        <td style="vertical-align:top;">
            @if($order->driver->files)
                <div>{{ $order->driver->files->type->description() }} - {{ $order->driver->files->path }}</div>
            @endif
        </td>
    </tr>

    </tbody>
</table>

<h1>Hello, Admin!</h1>
<p>New User Registration</p>
<table>
    <tbody>
    <tr>
        <td>Name</td>
        <td>{{ $user->name }}</td>
    </tr>
    <tr>
        <td>Surname</td>
        <td>{{ $user->surname }}</td>
    </tr>
    <tr>
        <td>Email</td>
        <td>{{ $user->email }}</td>
    </tr>
    <tr>
        <td>Position</td>
        <td>{{ $user->position->name }}</td>
    </tr>
    <tr>
        <td>Gender</td>
        <td>{{ $user->gender->description() }}</td>
    </tr>
    <tr>
        <td>Company Name</td>
        <td>{{ $user->company_name }}</td>
    </tr>
    <tr>
        <td>Street</td>
        <td>{{ $user->street }}</td>
    </tr>
    <tr>
        <td>Postal Code</td>
        <td>{{ $user->post }}</td>
    </tr>
    <tr>
        <td>Country Name</td>
        <td>{{ $user->country->name }}</td>
    </tr>
    <tr>
        <td>Country Code</td>
        <td>{{ $user->country->iso }}</td>
    </tr>
    <tr>
        <td>City</td>
        <td>{{ $user->city }}</td>
    </tr>
    <tr>
        <td>Salutation</td>
        <td>{{ $user->salutation }}</td>
    </tr>
    <tr>
        <td>Phone</td>
        <td>{{ $user->phone }}</td>
    </tr>
    <tr>
        <td>Confirm Docs</td>
        <td>{{ $user->confirm_docs }}</td>
    </tr>
    <tr>
        <td>Subcontractors</td>
        <td>{{ $user->subcontractors->description() }}</td>
    </tr>

    @if($user->trailers->count())
    <tr>
        <td>Trailers</td>
        <td>
            @foreach($user->trailers as $item)
                <div>{{ $item->name }} - {{ $item->pivot->count }}</div>
            @endforeach
        </td>
    </tr>
    @endif

    @if($user->tractors->count())
    <tr>
        <td>Tractors</td>
        <td>
            @foreach($user->tractors as $item)
                <div>{{ $item->name }} - {{ $item->pivot->count }}</div>
            @endforeach
        </td>
    </tr>
    @endif

    @if($user->tractors->count())
        <tr>
            <td>Miscellaneous</td>
            <td>
                @foreach($user->miscellaneous as $item)
                    <div>{{ $item->name }} - {{ $item->pivot->count }}</div>
                @endforeach
            </td>
        </tr>
    @endif

    @if($user->files->count())
        <tr>
            <td>Files</td>

            <td>
                @foreach($user->files as $item)
                    <div><a href="{{ $item->path }}">{{ $item->name }}</a></div>
                @endforeach
            </td>

        </tr>
    @endif

    </tbody>
</table>

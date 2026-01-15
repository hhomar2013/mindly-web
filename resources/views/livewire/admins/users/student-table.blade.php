<table class="table table-striped table-hover table-bordered">
    <thead class="text-center">
        <tr>
            <th scope="col">{{ __('#') }}</th>
            <th scope="col">{{ __('Name') }}</th>
            <th scope="col">{{ __('Email') }}</th>
            <th scope="col">{{ __('Created At') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="text-center">
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>
                    <button class="btn btn-sm btn-rounded btn-primary"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-sm  btn-rounded btn-danger"><i class="fa fa-trash"></i></button>



                    @if ($user->is_onlie)
                        <button class="btn btn-sm  btn-rounded btn-info" onclick="confirm({{ $user->id }})">
                            <i class="fa fa-sign-out"></i></button>
                    @else
                        <span class="btn btn-danger  btn-rounded">{{ __('offline') }}</span>
                    @endif



                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Users</h3>
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All User</div>
                    </li>
                </ul>
            </div>
        <div class="wg-box">
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                <table style="font-size: 16px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user) 
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->utype === 'ADM' ? 'Admin' : 'User' }}</td>
                            <td>
                            <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="display:inline-block; margin-right: 5px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="font-size: 16px;">Delete</button>
                            </form>
                            <form action="{{ route('admin.user.edit', $user->id) }}" method="GET" style="display:inline-block;">
                                @csrf
                                <button type="submit" style="font-size: 16px;">Edit</button>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination" style="font-size: 16px;">
                {{$users->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection
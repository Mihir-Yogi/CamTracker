@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header and Add Button -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>HDD List</h3>
            <a href="{{ route('admin.hdds.create') }}" class="tf-button style-1 w208">Add New HDD</a>
        </div>

        <!-- HDD Table -->
        <div class="wg-box">
            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Purchase Date</th>
                            <th>Installation Date</th>
                            <th>Warranty Expiration</th>
                            <th>Capacity</th> <!-- New Capacity Column -->
                            <th colspan="3" style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hdds as $hdd)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $hdd->model }}</td>
                                <td>{{ $hdd->serial_number }}</td>
                                <td>{{ ucfirst($hdd->status) }}</td>
                                <td>{{ $hdd->purchase_date ? $hdd->purchase_date : 'N/A' }}</td>
                                <td>{{ $hdd->installation_date ? $hdd->installation_date : 'N/A' }}</td>
                                <td>{{ $hdd->warranty_expiration ? $hdd->warranty_expiration : 'N/A' }}</td>
                                <td>{{ $hdd->capacity }} GB</td> <!-- Display Capacity with Unit -->

                                <td colspan="3">
                                    <div class="list-icon-function" style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                        <a href="{{ route('admin.hdds.show', $hdd->id) }}" style="margin-left: 15px;">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- Only show the edit and replace buttons if status is 'working' -->
                                        @if($hdd->status === 'working')
                                            <a href="{{ route('admin.hdds.edit', $hdd->id) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.hdds.replaceForm', $hdd) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="fa-solid fa-repeat"></i>
                                                </div>
                                            </a>
                                        @endif

                                        <!-- Delete button (available for all statuses) -->
                                        <form action="{{ route('admin.hdds.destroy', $hdd) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No HDDs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    $(function(){
        $('.delete').on('click', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: ["Cancel", "Yes!"],
                dangerMode: true,
            }).then((result) => {
                if(result) {
                    form.submit();
                }   
            });
        });
    });
</script>

@endpush

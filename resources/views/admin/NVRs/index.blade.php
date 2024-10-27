@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header and Add Button -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>NVR List</h3>
            <a href="{{ route('admin.nvrs.create') }}" class="tf-button style-1 w208">Add New NVR</a>
        </div>

        <!-- NVR Table -->
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
                            <th colspan="3" style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nvrs as $nvr)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $nvr->model }}</td>
                                <td>{{ $nvr->serial_number }}</td>
                                <td>{{ ucfirst($nvr->status) }}</td>
                                <td>{{ $nvr->purchase_date ? $nvr->purchase_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $nvr->installation_date ? $nvr->installation_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $nvr->warranty_expiration ? $nvr->warranty_expiration->format('Y-m-d') : 'N/A' }}</td>
                                <td colspan="3">
                                    <div class="list-icon-function">
                                        <a href="{{ route('admin.nvrs.edit', $nvr) }}" style="margin-left: 15px;">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <a href="{{ route('admin.nvrs.replace', $nvr) }}" style="margin-left: 15px;" >
                                            <div class="item edit">
                                                <i class="fa-solid fa-repeat"></i>
                                            </div>
                                        </a>
                                        <form action="{{ route('admin.nvrs.destroy', $nvr) }}" method="POST" style="display:inline;">
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
                                <td colspan="8" class="text-center">No NVRs found.</td>
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
        $('.delete').on('click',function(e){
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


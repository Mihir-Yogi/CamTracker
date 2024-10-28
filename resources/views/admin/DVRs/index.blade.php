@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header and Add Button -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>DVR List</h3>
            <a href="{{ route('admin.dvrs.create') }}" class="tf-button style-1 w208">Add New DVR</a>
        </div>

        <!-- DVR Table -->
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
                        @forelse($dvrs as $dvr)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dvr->model }}</td>
                                <td>{{ $dvr->serial_number }}</td>
                                <td>{{ ucfirst($dvr->status) }}</td>
                                <td>{{ $dvr->purchase_date ? $dvr->purchase_date : 'N/A' }}</td>
                                <td>{{ $dvr->installation_date ? $dvr->installation_date : 'N/A' }}</td>
                                <td>{{ $dvr->warranty_expiration ? $dvr->warranty_expiration : 'N/A' }}</td>
                                <td colspan="3">
                                    <div class="list-icon-function" style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                        <a href="{{ route('admin.dvrs.show', $dvr->id) }}" style="margin-left: 15px;">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- Only show the edit and replace buttons if status is 'working' -->
                                        @if($dvr->status === 'working')
                                            <a href="{{ route('admin.dvrs.edit', $dvr->id) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.dvrs.replaceForm', $dvr) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="fa-solid fa-repeat"></i>
                                                </div>
                                            </a>
                                        @endif

                                        <!-- Delete button (available for all statuses) -->
                                        <form action="{{ route('admin.dvrs.destroy', $dvr) }}" method="POST" style="display:inline;">
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
                                <td colspan="8" class="text-center">No DVRs found.</td>
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

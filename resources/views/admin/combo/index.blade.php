@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Page Header and Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Combos</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Combos</div>
                </li>
            </ul>
        </div>

        <!-- Search and Add Button -->
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.combos.index') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." name="search" tabindex="2" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.combos.create') }}">
                    <i class="icon-plus"></i>Add New Combo
                </a>
            </div>

            <!-- Session Status Message -->
            @if(Session::has('status'))
                <p class="alert alert-success">{{ Session::get('status') }}</p>
            @endif

            <!-- Combos Table -->
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Location</th>
                                <th>Depot</th>
                                <th>NVR Model</th>
                                <th>DVR Model</th>
                                <th>HDD Model</th>
                                <th>Camera Capacity</th>
                                <th>Current CCTV Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($combos as $index => $combo)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ optional($combo->location)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->location->depot)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->nvr)->model ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->dvr)->model ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->hdd)->model ?? 'N/A' }}</td>
                                    <td>{{ $combo->camera_capacity }}</td>
                                    <td>{{ $combo->current_cctv_count }}</td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="#">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="#" method="POST" style="display:inline;">
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
                                    <td colspan="9" class="text-center">No combos found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $combos->links('pagination::bootstrap-5') }}
                </div>
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
                if (result) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

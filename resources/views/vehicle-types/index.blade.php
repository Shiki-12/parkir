@extends('layout.master')
@section('title', 'Vehicle Type')
@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-0">Vehicle Type</h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('vehicle-types.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                <i class="fas fa-plus me-1" style="color: #fff !important; font-size: 0.75rem !important;"></i> Add New Vehicle Type
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <form action="{{ route('vehicle-types.index') }}" method="GET">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Search vehicle type..." value="{{ request('search') }}">
                                    <button type="submit" class="btn bg-gradient-info btn-sm mb-0">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vehicle Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">First Hour Charges</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Next Hourly Charges</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Max Cost Per Day</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicleTypes as $index => $type)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="text-xs font-weight-bold">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-gradient-info">{{ ucfirst($type->jenis) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold">Rp {{ number_format($type->perjam_pertama, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold">Rp {{ number_format($type->perjam_berikutnya, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold">Rp {{ number_format($type->max_perhari, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('vehicle-types.edit', $type->id) }}" class="btn btn-sm bg-gradient-warning mb-0 me-1" title="Edit">
                                                <i class="fas fa-pencil-alt" style="color: #fff !important; font-size: 0.7rem !important;"></i>
                                            </a>
                                            <form action="{{ route('vehicle-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vehicle type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-gradient-danger mb-0" title="Delete">
                                                    <i class="fas fa-trash" style="color: #fff !important; font-size: 0.7rem !important;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <span class="text-xs text-secondary">No vehicle types found.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2500
    });
</script>
@endif
@endsection

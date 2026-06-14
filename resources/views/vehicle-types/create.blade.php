@extends('layout.master')
@section('title', 'Add Vehicle Type')
@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Add New Vehicle Type</h6>
                </div>
                <div class="card-body">
                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger text-white text-sm">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('vehicle-types.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="jenis" class="form-label text-sm font-weight-bold">Vehicle Type</label>
                            <select class="form-control" id="jenis" name="jenis" required>
                                <option value="">-- Select Vehicle Type --</option>
                                <option value="motorcycle" {{ old('jenis') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                <option value="car" {{ old('jenis') == 'car' ? 'selected' : '' }}>Car</option>
                                <option value="other" {{ old('jenis') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="perjam_pertama" class="form-label text-sm font-weight-bold">First Hour Charges (Rp)</label>
                            <input type="number" class="form-control" id="perjam_pertama" name="perjam_pertama" value="{{ old('perjam_pertama', 0) }}" min="0" placeholder="Enter first hour charge" required>
                        </div>

                        <div class="mb-3">
                            <label for="perjam_berikutnya" class="form-label text-sm font-weight-bold">Next Hourly Charges (Rp)</label>
                            <input type="number" class="form-control" id="perjam_berikutnya" name="perjam_berikutnya" value="{{ old('perjam_berikutnya', 0) }}" min="0" placeholder="Enter next hourly charge" required>
                        </div>

                        <div class="mb-3">
                            <label for="max_perhari" class="form-label text-sm font-weight-bold">Max Cost Per Day (Rp)</label>
                            <input type="number" class="form-control" id="max_perhari" name="max_perhari" value="{{ old('max_perhari', 0) }}" min="0" placeholder="Enter max cost per day" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('vehicle-types.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary">Save Vehicle Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layout.master')
@section('title', 'Add Location')
@section('menu')
    @include('layout.menu')
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Add New Location</h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger text-white text-sm">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="location_name" class="form-label text-sm font-weight-bold">Location Name</label>
                            <input type="text" class="form-control" id="location_name" name="location_name" value="{{ old('location_name') }}" placeholder="Enter location name" required>
                        </div>

                        <div class="mb-3">
                            <label for="max_motorcycle" class="form-label text-sm font-weight-bold">Max Motorcycle</label>
                            <input type="number" class="form-control" id="max_motorcycle" name="max_motorcycle" value="{{ old('max_motorcycle', 0) }}" min="0" placeholder="Enter max motorcycle capacity" required>
                        </div>

                        <div class="mb-3">
                            <label for="max_car" class="form-label text-sm font-weight-bold">Max Car</label>
                            <input type="number" class="form-control" id="max_car" name="max_car" value="{{ old('max_car', 0) }}" min="0" placeholder="Enter max car capacity" required>
                        </div>

                        <div class="mb-3">
                            <label for="max_other" class="form-label text-sm font-weight-bold">Max Truck/Bus/Other</label>
                            <input type="number" class="form-control" id="max_other" name="max_other" value="{{ old('max_other', 0) }}" min="0" placeholder="Enter max truck/bus/other capacity" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary">Save Location</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

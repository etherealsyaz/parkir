@extends('layouts.app')
@section('breadcrumb', 'Location')
@section('page-title', 'Location')
@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title-custom">Location <span class="text-dark fw-normal">Input Form</span></h5>
    <form action="{{ route('location.store') }}" method="POST" class="mt-3">
      @csrf
      <div class="mb-3">
        <label>Location Name</label>
        <input type="text" name="location_name" class="form-control"
            value="{{ old('location_name') }}" required>
      </div>
      <div class="mb-3">
        <label>Max Motorcycle</label>
        <input type="number" name="max_motorcycle" class="form-control"
            value="{{ old('max_motorcycle', 0) }}" required>
      </div>
      <div class="mb-3">
        <label>Max Car</label>
        <input type="number" name="max_car" class="form-control"
            value="{{ old('max_car', 0) }}" required>
      </div>
      <div class="mb-3">
        <label>Max Truck/Bus/Other</label>
        <input type="number" name="max_other" class="form-control"
            value="{{ old('max_other', 0) }}" required>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('location.index') }}" class="btn btn-dark flex-fill">CANCEL</a>
        <button type="submit" class="btn btn-primary-custom flex-fill">SAVE LOCATION</button>
      </div>
    </form>
  </div>
</div>
@endsection

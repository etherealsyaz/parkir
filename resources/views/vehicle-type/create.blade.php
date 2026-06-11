@extends('layouts.app')
@section('breadcrumb', 'Vehicle Type')
@section('page-title', 'Vehicle Type')
@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title-custom">Vehicle Type <span class="text-dark fw-normal">Input Form</span></h5>
    <form action="{{ route('vehicle-type.store') }}" method="POST" class="mt-3">
      @csrf
      <div class="mb-3">
        <label>Vehicle Type</label>
        <select name="jenis" class="form-select" required>
          <option value="motorcycle">Motorcycle</option>
          <option value="car">Car</option>
          <option value="other">Truck/Bus/Other</option>
        </select>
      </div>
      <div class="mb-3">
        <label>First Hour Charges</label>
        <input type="number" name="perjam_pertama" class="form-control"
            value="{{ old('perjam_pertama', 0) }}" required>
      </div>
      <div class="mb-3">
        <label>Next Hourly Charges</label>
        <input type="number" name="perjam_berikutnya" class="form-control"
            value="{{ old('perjam_berikutnya', 0) }}" required>
      </div>
      <div class="mb-3">
        <label>Max Cost Per Day</label>
        <input type="number" name="max_perhari" class="form-control"
            value="{{ old('max_perhari', 0) }}" required>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('vehicle-type.index') }}" class="btn btn-dark flex-fill">CANCEL</a>
        <button type="submit" class="btn btn-primary-custom flex-fill">SAVE VEHICLE TYPE</button>
      </div>
    </form>
  </div>
</div>
@endsection

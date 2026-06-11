@extends('layouts.app')
@section('breadcrumb', 'Location')
@section('page-title', 'Location')
@section('topbar-actions')
    <input type="text" class="form-control form-control-sm" placeholder="Type here..."
        onchange="window.location='?search='+this.value" value="{{ request('search') }}">
    <a href="{{ route('location.create') }}" class="btn btn-sm btn-primary-custom">
        <i class="fas fa-plus"></i> ADD NEW LOCATION
    </a>
@endsection
@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title-custom">Location <span class="text-dark fw-normal">Data Table</span></h5>
    <table class="table table-bordered table-hover mt-3">
      <thead class="table-light">
        <tr>
          <th>No.</th><th>Location Name</th><th>Max Motorcycle</th>
          <th>Max Car</th><th>Max Truck/Bus/Other</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($locations as $i => $loc)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $loc->location_name }}</td>
          <td>{{ $loc->max_motorcycle }}</td>
          <td>{{ $loc->max_car }}</td>
          <td>{{ $loc->max_other }}</td>
          <td>
            <a href="{{ route('location.edit', $loc) }}" class="btn btn-sm btn-warning">
              <i class="fas fa-edit"></i> EDIT
            </a>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if(session('success'))
<script>
Swal.fire({ icon: 'success', title: 'Good Job', text: '{{ session('success') }}' });
</script>
@endif
@endsection

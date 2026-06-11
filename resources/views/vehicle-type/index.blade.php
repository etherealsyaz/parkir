@extends('layouts.app')

@section('breadcrumb', 'Vehicle Type')
@section('page-title', 'Vehicle Type')

@section('topbar-actions')
<input type="text"
       class="form-control form-control-sm"
       placeholder="Type here..."
       value="{{ request('search') }}"
       onchange="window.location='?search='+this.value">

<a href="{{ route('vehicle-type.create') }}"
   class="btn btn-primary-custom">
    <i class="fas fa-plus"></i>
    ADD NEW VEHICLE TYPE
</a>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <h5 class="card-title-custom">
            Vehicle Type
            <span class="text-secondary fw-normal">
                Data Table
            </span>
        </h5>

        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Vehicle Type</th>
                    <th>First Hour Charges</th>
                    <th>Next Hourly Charges</th>
                    <th>Max Cost Per Day</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($vehicleTypes as $i => $vt)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ ucfirst($vt->jenis) }}</td>
                    <td>Rp {{ number_format($vt->perjam_pertama,0,',','.') }}</td>
                    <td>Rp {{ number_format($vt->perjam_berikutnya,0,',','.') }}</td>
                    <td>Rp {{ number_format($vt->max_perhari,0,',','.') }}</td>
                    <td>
                        <a href="{{ route('vehicle-type.edit',$vt) }}"
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                            EDIT
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@if(session('success'))
<script>
Swal.fire({
    icon:'success',
    title:'Good Job',
    text:'{{ session('success') }}'
});
</script>
@endif

@endsection
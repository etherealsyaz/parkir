@extends('layouts.app')
@section('breadcrumb', 'Transaction')
@section('page-title', 'All Transactions')
@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title-custom">All Transactions</h5>
    <div class="table-responsive">
    <table class="table table-bordered table-hover mt-3">
      <thead class="table-light">
        <tr>
          <th>No.</th><th>PDF</th><th>Ticket Number</th><th>Police Number</th>
          <th>Location</th><th>Vehicle Type</th><th>Time In</th><th>Time Out</th>
          <th>1st Hr</th><th>Next Hr</th><th>Max/Day</th>
          <th>Total Hours</th><th>Total Days</th><th>Total Pays</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $i => $trx)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>
            <a href="{{ route('transaction.ticket', $trx->no_tiket) }}" target="_blank"
               class="btn btn-sm btn-outline-danger">PDF</a>
          </td>
          <td>{{ $trx->no_tiket }}</td>
          <td>{{ $trx->no_polisi ?? '-' }}</td>
          <td>{{ $trx->location->location_name }}</td>
          <td>{{ ucfirst($trx->vehicleType->jenis) }}</td>
          <td>{{ $trx->masuk }}</td>
          <td>{{ $trx->keluar ?? '-' }}</td>
          <td>Rp {{ number_format($trx->perjam_pertama,0,',','.') }}</td>
          <td>Rp {{ number_format($trx->perjam_berikutnya,0,',','.') }}</td>
          <td>Rp {{ number_format($trx->max_perhari,0,',','.') }}</td>
          <td>{{ $trx->total_jam ?? '-' }}</td>
          <td>{{ $trx->total_jam ? floor($trx->total_jam / 24) : '-' }}</td>
          <td>
            @if($trx->total_bayar)
            Rp {{ number_format($trx->total_bayar,0,',','.') }}
            @else
            <span class="badge bg-warning">Masih Parkir</span>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="14" class="text-center">Belum ada transaksi</td></tr>
        @endforelse
      </tbody>
    </table>
    </div>
    <a href="{{ route('transaction.index') }}" class="btn btn-secondary mt-3">CLOSE</a>
  </div>
</div>
@endsection



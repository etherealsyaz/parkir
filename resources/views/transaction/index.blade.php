@extends('layouts.app')
@section('breadcrumb', 'Transaction')
@section('page-title', 'Transaction')
@section('topbar-actions')
    @foreach($vehicleTypes as $vt)
    <span class="badge bg-secondary fs-6 text-uppercase">{{ $vt->jenis }}</span>
    @endforeach
    <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalEnter">
        <i class="fas fa-plus"></i> ENTER VEHICLE
    </button>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8">
    
    <div class="row g-3 mb-4 align-items-stretch">
      
      <div class="col-md-3 col-sm-6">
        <div class="card h-100 shadow-sm border-0 rounded-4 text-white text-center p-3" 
             style="background: linear-gradient(135deg, #f3f8ff, #022636);">
          <div class="card-body d-flex flex-column justify-content-center align-items-center p-0">
            <h5 id="hari" class="fw-bold mb-1 text-uppercase text-muted" style="font-size: 0.85rem; letter-spacing: 1px;"></h5>
            <small id="tanggal" class="text-secondary small mb-3"></small>
            <div id="jam" class="fw-bold" style="font-size: 1.8rem; font-family: monospace; color: #f8fafc;"></div>
          </div>
        </div>
      </div>

     
      
     @foreach($locations as $loc)
      @php
        // 1. IKON ATAS (STATIS): Langsung mengambil kapasitas total dari database (Tidak berubah-ubah)
        $maxMotor = $loc->max_motorcycle;
        $maxCar = $loc->max_car;
        $maxOther = $loc->max_other;

        // 2. HITUNG KENDARAAN YANG SEDANG PARKIR AKTIF
        $parkedMotorcycle = $loc->transactions()->whereNull('keluar')->whereHas('vehicleType', function($q){ $q->where('jenis', 'motorcycle'); })->count();
        $parkedCar = $loc->transactions()->whereNull('keluar')->whereHas('vehicleType', function($q){ $q->where('jenis', 'car'); })->count();
        $parkedOther = $loc->transactions()->whereNull('keluar')->whereHas('vehicleType', function($q){ $q->where('jenis', 'other'); })->count();

        // 3. IKON BAWAH (DINAMIS): Sisa slot kosong yang akan berkurang saat kendaraan masuk
        $slotMotor = $maxMotor - $parkedMotorcycle;
        $slotCar = $maxCar - $parkedCar;
        $slotOther = $maxOther - $parkedOther;
      @endphp

      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card h-100 shadow-sm border-0 rounded-4 text-center p-3">
          <div class="card-body d-flex flex-column align-items-center justify-content-between p-0">
            
            <div class="d-flex align-items-center justify-content-center text-white rounded-4 mb-2" 
                 style="width: 55px; height: 55px; background: linear-gradient(135deg, #b5179e, #7209b7);">
              <i class="fas fa-building fs-4"></i>
            </div>
            
            <h6 class="fw-bold text-secondary mb-2">{{ $loc->location_name }}</h6>
            
            <div class="text-muted mb-2" style="font-size: 0.75rem;">
              <i class="fas fa-motorcycle"></i> {{ $maxMotor }} 
              <i class="fas fa-car ms-1"></i> {{ $maxCar }} 
              <i class="fas fa-truck ms-1"></i> {{ $maxOther }}
            </div>

            <div class="d-flex justify-content-center gap-3 w-100 border-top pt-2 mt-auto">
              
              <div class="small fw-bold 
                @if($slotMotor == 0) text-danger 
                @elseif($slotMotor <= ($maxMotor / 2)) text-warning 
                @else text-success 
                @endif">
                <i class="fas fa-motorcycle"></i> <span class="ms-1">{{ $slotMotor }}</span>
              </div>
              
              <div class="small fw-bold 
                @if($slotCar == 0) text-danger 
                @elseif($slotCar <= ($maxCar / 2)) text-warning 
                @else text-success 
                @endif">
                <i class="fas fa-car"></i> <span class="ms-1">{{ $slotCar }}</span>
              </div>
              
              <div class="small fw-bold 
                @if($slotOther == 0) text-danger 
                @elseif($slotOther <= ($maxOther / 2)) text-warning 
                @else text-success 
                @endif">
                <i class="fas fa-truck"></i> <span class="ms-1">{{ $slotOther }}</span>
              </div>

            </div>

          </div>
        </div>
      </div>
      @endforeach
  

    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="card-title-custom mb-0 fw-bold" style="color: #b5179e;">Transaction <span class="fw-normal text-dark">Input Form</span></h6>
          <button class="btn btn-sm btn-outline-danger px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#modalExit">
            <i class="fas fa-sign-out-alt"></i> EXIT VEHICLE
          </button>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label small text-secondary fw-bold">Ticket Number</label>
            <input type="text" id="ticketInput" class="form-control form-control-lg rounded-3" placeholder="Ticket Number">
          </div>
          <div class="col-md-6">
            <label class="form-label small text-secondary fw-bold">Police Number</label>
            <input type="text" id="policeInput" class="form-control form-control-lg rounded-3" placeholder="Police Number">
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Kolom Kanan: Daftar Tiket -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <strong>Tickets</strong>
          <a href="{{ route('transaction.all') }}" class="btn btn-sm btn-outline-secondary">VIEW ALL</a>
        </div>
        <hr>
        @foreach($tickets as $ticket)
        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
          <div>
            <small class="text-muted">{{ $ticket->masuk }}</small><br>
            <span class="fw-bold" style="cursor:pointer;color:#d63384"
              onclick="document.getElementById('ticketInput').value='{{ $ticket->no_tiket }}'"
            >#{{ $ticket->no_tiket }}</span>
            @if($ticket->total_bayar)
            <span class="badge bg-success">Rp {{ number_format($ticket->total_bayar,0,',','.') }}</span>
            @endif
          </div>
          <a href="{{ route('transaction.ticket', $ticket->no_tiket) }}" target="_blank"
             class="btn btn-sm btn-outline-danger"><i class="far fa-file-pdf"></i> PDF</a>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- Modal: ENTER VEHICLE -->
<div class="modal fade" id="modalEnter" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('transaction.enter') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Enter Vehicle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Pilih Lokasi</label>
            <select name="id_lokasi" class="form-select" required>
              @foreach($locations as $loc)
              <option value="{{ $loc->id }}">{{ $loc->location_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Jenis Kendaraan</label>
            <select name="id_jenis" class="form-select" required>
              @foreach($vehicleTypes as $vt)
              <option value="{{ $vt->id }}">{{ ucfirst($vt->jenis) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary-custom">ENTER VEHICLE</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: EXIT VEHICLE -->
<div class="modal fade" id="modalExit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('transaction.exit') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Exit Vehicle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Ticket Number</label>
            <input type="text" name="no_tiket" id="exitTicket" class="form-control" required
              value="{{ request('ticket') }}">
          </div>
          <div class="mb-3">
            <label>Police Number</label>
            <input type="text" name="no_polisi" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">EXIT VEHICLE</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Clock real-time
function updateClock() {
  const now = new Date();
  const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  document.getElementById('hari').textContent = days[now.getDay()];
  document.getElementById('tanggal').textContent = now.toLocaleDateString('id-ID',
      {day:'numeric',month:'long',year:'numeric'});
  document.getElementById('jam').textContent = now.toTimeString().slice(0,8);
}
setInterval(updateClock, 1000);
updateClock();

// Klik tiket di sidebar -> isi input
document.querySelectorAll('[onclick]').forEach(el => {
  el.addEventListener('click', function() {
    document.getElementById('exitTicket').value = this.dataset.ticket || '';
  });
});

@if(session('ticket'))
Swal.fire({ icon:'success', title:'Tiket Berhasil Dibuat!',
    text:'No Tiket: {{ session('ticket') }}' });
@endif
@if(session('total_bayar'))
Swal.fire({ icon:'info', title:'Total Bayar: {{ session('total_bayar') }}',
    confirmButtonText:'OK' });
@endif
@if(session('error'))
Swal.fire({ icon:'error', title:'Error', text:'{{ session('error') }}' });
@endif
</script>

@endpush
@endsection

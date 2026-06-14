@extends('layout.master')

@section('title', 'Transaction')

@section('menu')
    @include('layout.menu')
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('layout/assets/css/transaction.css') }}">
@endsection

@section('content')
    <div class="container-fluid py-4">

        <div class="row mb-3 align-items-center">
            <div class="col">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @foreach ($vehicleTypes as $type)
                        <div class="vt-btn-inline" data-type-id="{{ $type->id }}"
                            onclick="selectVehicleType(this, {{ $type->id }})">
                            {{ strtoupper($type->jenis) }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-auto">
                <form id="enterForm" action="{{ route('transactions.enter') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="id_lokasi" id="enterLocationId">
                    <input type="hidden" name="id_jenis" id="enterVehicleTypeId">
                    <input type="hidden" name="no_polisi" id="enterPoliceNumber">

                    <button type="button" class="btn bg-gradient-success mb-0" onclick="submitEnter()">
                        <i class="fas fa-plus me-1 btn-icon-plus"></i>
                        ENTER VEHICLE
                    </button>
                </form>
            </div>
        </div>

        <div class="row mb-4 align-items-stretch">
            <div class="col-lg-3 mb-3 mb-lg-0">
                <div class="card clock-card">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                        <img src="{{ asset('parkir.png') }}" alt="SIJA Parking" class="clock-logo mb-2">
                        <div class="clock-day" id="clockDay">--</div>
                        <div class="clock-date" id="clockDate">Loading...</div>
                        <div class="clock-time mt-2" id="clockTime">--:--:--</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-3 mb-lg-0">
                <div class="row">
                    @forelse($locations as $location)
                        <div class="col-md-4 col-6 mb-3">
                            <div class="card location-card" data-location-id="{{ $location->id }}"
                                onclick="selectLocation(this, {{ $location->id }})">
                                <div class="card-body">
                                    <div class="location-icon">
                                        <i class="fas fa-building"></i>
                                    </div>

                                    <h6 class="mb-1 font-weight-bold text-sm">
                                        {{ $location->location_name }}
                                    </h6>

                                    <div class="capacity-row">
                                        <div class="cap-item">
                                            <i class="fas fa-motorcycle"></i>
                                            <span
                                                class="cap-num {{ $availableCapacity[$location->id]['motorcycle'] <= 0 ? 'text-full' : 'text-available' }}">
                                                {{ $availableCapacity[$location->id]['motorcycle'] }}
                                            </span>
                                        </div>

                                        <div class="cap-item">
                                            <i class="fas fa-car"></i>
                                            <span
                                                class="cap-num {{ $availableCapacity[$location->id]['car'] <= 0 ? 'text-full' : 'text-available' }}">
                                                {{ $availableCapacity[$location->id]['car'] }}
                                            </span>
                                        </div>

                                        <div class="cap-item">
                                            <i class="fas fa-truck"></i>
                                            <span
                                                class="cap-num {{ $availableCapacity[$location->id]['other'] <= 0 ? 'text-full' : 'text-available' }}">
                                                {{ $availableCapacity[$location->id]['other'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-white">
                                No locations available.
                                <a href="{{ route('locations.create') }}" class="text-white text-decoration-underline">
                                    Add a location first
                                </a>.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card ticket-panel">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-sm">Tickets</h6>

                        <button type="button" class="btn bg-gradient-warning btn-sm mb-0" data-bs-toggle="modal"
                            data-bs-target="#allTransactionsModal">
                            VIEW ALL
                        </button>
                    </div>

                    <div class="card-body pt-2">
                        @if (count($activeTickets ?? []) > 0)
                            <div class="ticket-list-scroll">
                                @foreach ($activeTickets as $ticket)
                                    <div class="ticket-entry d-flex justify-content-between align-items-start"
                                        onclick="fillTicket(event, '{{ $ticket->no_tiket }}', '{{ $ticket->no_polisi }}')">
                                        <div>
                                            <div class="ticket-date">
                                                {{ \Carbon\Carbon::parse($ticket->masuk)->format('Y-m-d H:i:s') }}
                                            </div>

                                            <div class="ticket-number">
                                                #{{ $ticket->no_tiket }}
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2">
                                            @if ($ticket->total_bayar)
                                                <span class="ticket-price">
                                                    Rp {{ number_format($ticket->total_bayar, 0, ',', '.') }}
                                                </span>
                                            @endif

                                            <a href="{{ route('tickets.download', $ticket->no_tiket) }}" target="_blank"
                                                class="btn btn-sm bg-gradient-primary mb-0 px-2 py-1"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-file-pdf btn-icon-pdf"></i>
                                                PDF
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-secondary inbox-icon-empty"></i>
                                <p class="text-sm text-secondary mt-2 mb-0">No active tickets at the moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card input-form-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 font-weight-bold">
                                <span class="form-title-primary">Transaction</span>
                                <span class="form-title-secondary">Input Form</span>
                            </h5>

                            <button type="button" class="btn bg-gradient-dark mb-0" onclick="submitExit()">
                                <i class="fas fa-minus me-1 btn-icon-minus"></i>
                                EXIT VEHICLE
                            </button>
                        </div>

                        <form id="exitForm" action="{{ route('transactions.exit') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label text-xs font-weight-bold text-secondary">Ticket Number</label>
                                    <input type="text" class="form-control" name="no_tiket" id="exitTicketNumber"
                                        placeholder="Enter or click ticket above" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-xs font-weight-bold text-secondary">Police Number</label>
                                    <input type="text" class="form-control" name="no_polisi" id="exitPoliceNumber"
                                        placeholder="e.g. D9788RT" required>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="allTransactionsModal" tabindex="-1" aria-labelledby="allTransactionsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="allTransactionsModalLabel">
                        <i class="fas fa-list-alt me-2 modal-title-icon"></i>
                        All Transactions
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 all-transactions-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                                        No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        PDF</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Ticket Number</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Police Number</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Vehicle Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Time In</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Time Out</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        1st Hr Charge</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Next Hr Charge</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Max/Day</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Total Hours</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Total Days</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Total Pays</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($allTransactions as $idx => $trx)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="text-xs font-weight-bold">{{ $idx + 1 }}</span>
                                        </td>

                                        <td>
                                            <a href="{{ route('tickets.download', $trx->no_tiket) }}" target="_blank"
                                                class="btn btn-sm bg-gradient-dark mb-0 px-2 py-1">
                                                <i class="fas fa-file-pdf btn-icon-pdf"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <span class="text-xs font-weight-bold">{{ $trx->no_tiket }}</span>
                                        </td>

                                        <td>
                                            <span class="text-xs font-weight-bold">{{ $trx->no_polisi }}</span>
                                        </td>

                                        <td>
                                            <span class="text-xs">{{ $trx->location->location_name ?? '-' }}</span>
                                        </td>

                                        <td>
                                            <span class="badge bg-gradient-info text-xxs">
                                                {{ ucfirst($trx->vehicleType->jenis ?? '-') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs">
                                                {{ \Carbon\Carbon::parse($trx->masuk)->format('d M Y H:i') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs">
                                                {{ $trx->keluar ? \Carbon\Carbon::parse($trx->keluar)->format('d M Y H:i') : '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs">
                                                Rp {{ number_format($trx->perjam_pertama ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs">
                                                Rp {{ number_format($trx->perjam_berikutnya ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs">
                                                Rp {{ number_format($trx->max_perhari ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs font-weight-bold">
                                                {{ $trx->total_jam ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs font-weight-bold">
                                                @if ($trx->total_jam)
                                                    {{ $trx->total_jam > 24 ? floor($trx->total_jam / 24) : 0 }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </td>

                                        <td>
                                            <span class="text-xs font-weight-bold text-success">
                                                {{ $trx->total_bayar !== null ? 'Rp ' . number_format($trx->total_bayar, 0, ',', '.') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center py-4">
                                            <span class="text-xs text-secondary">No transactions found.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let selectedLocationId = null;
        let selectedVehicleTypeId = null;

        function generatePoliceNumber() {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const prefix = letters.charAt(Math.floor(Math.random() * letters.length));
            const number = Math.floor(1000 + Math.random() * 9000);
            const suffix = letters.charAt(Math.floor(Math.random() * letters.length)) +
                letters.charAt(Math.floor(Math.random() * letters.length));

            return prefix + number + suffix;
        }

        function updateClock() {
            const now = new Date();

            const daysEn = [
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            ];

            const months = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            const dayName = daysEn[now.getDay()];
            const date = now.getDate().toString().padStart(2, '0');
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');

            document.getElementById('clockDay').textContent = dayName;
            document.getElementById('clockDate').textContent = date + ' ' + month + ' ' + year;
            document.getElementById('clockTime').textContent = hours + ':' + minutes + ':' + seconds;
        }

        updateClock();
        setInterval(updateClock, 1000);

        function selectLocation(el, id) {
            document.querySelectorAll('.location-card').forEach(function(card) {
                card.classList.remove('selected');
            });

            el.classList.add('selected');
            selectedLocationId = id;
            document.getElementById('enterLocationId').value = id;
        }

        function selectVehicleType(el, id) {
            document.querySelectorAll('.vt-btn-inline').forEach(function(button) {
                button.classList.remove('selected');
            });

            el.classList.add('selected');
            selectedVehicleTypeId = id;
            document.getElementById('enterVehicleTypeId').value = id;
        }

        function fillTicket(event, ticketNo, policeNo) {
            document.getElementById('exitTicketNumber').value = ticketNo;
            document.getElementById('exitPoliceNumber').value = policeNo;

            document.querySelectorAll('.ticket-entry').forEach(function(ticket) {
                ticket.style.background = '';
            });

            event.currentTarget.style.background = '#fff3cd';

            document.getElementById('exitForm').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        function submitEnter() {
            if (!selectedLocationId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Location',
                    text: 'Please select a parking location first.',
                    confirmButtonColor: '#cb0c9f'
                });

                return;
            }

            if (!selectedVehicleTypeId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Vehicle Type',
                    text: 'Please select a vehicle type first.',
                    confirmButtonColor: '#cb0c9f'
                });

                return;
            }

            document.getElementById('enterPoliceNumber').value = generatePoliceNumber();
            document.getElementById('enterForm').submit();
        }

        function submitExit() {
            const ticketNo = document.getElementById('exitTicketNumber').value.trim();
            const policeNo = document.getElementById('exitPoliceNumber').value.trim();

            if (!ticketNo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ticket Number Required',
                    text: 'Please enter or click a ticket number.',
                    confirmButtonColor: '#cb0c9f'
                });

                return;
            }

            if (!policeNo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Police Number Required',
                    text: 'Please enter the police number.',
                    confirmButtonColor: '#cb0c9f'
                });

                return;
            }

            document.getElementById('exitForm').submit();
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2500
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: @json(session('error')),
                confirmButtonColor: '#ea0606'
            });
        @endif

        @if (session('total_bayar'))
            Swal.fire({
                icon: 'info',
                title: 'Vehicle Exited',
                html: `
                <div style="font-size: 1.5rem; font-weight: 700; color: #344767;">
                    Total Bayar :<br>
                    <span style="color: #17ad37;">
                        Rp {{ number_format(session('total_bayar'), 0, ',', '.') }}
                    </span>
                </div>
            `,
                confirmButtonColor: '#17ad37',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endsection

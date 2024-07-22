@extends('layouts.app')
@section('title', 'Detail Pemesanan')
@if (Auth::user()->level == 'Admin')
    @section('heading', 'Detail Pemesanan')
@endif
@section('styles')
    <style>
        .card-body {
            padding: .5rem 1rem;
            color: #000;
            border-bottom: 1px solid #e3e6f0;
        }

        .title {
            color: #4e73df;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            z-index: 1;
            align-items: center;
            justify-content: center;
            display: flex;
        }

        .title .title-text {
            display: inline;
        }

        .table {
            margin-bottom: 0;
            color: #000;
        }

        .table td {
            padding: 0;
            border-top: none;
        }
    </style>
@endsection
@section('content')
    <div class="row justify-content-center" style="margin-bottom: 35px;">
        @if (Auth::user()->level != 'Admin')
            <div class="col-12" style="margin-top: -15px">
                <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i>
                    Kembali</a>
            @else
                <div class="col-12">
        @endif
        <div class="card shadow h-100" style="border-top: .25rem solid #4e73df">
            <div class="card-body">
                <div class="row no-gutters align-items-center justify-content-center">
                    <div class="col h5 font-weight-bold" style="margin-bottom: 0">Detail Pemesanan</div>
                    <div class="col-auto">
                        <span class="title">
                            <div class="title-icon rotate-n-15">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="title-text ml-1">GBT Kristus Juruselamat</div>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="font-weight-bold h4 text-center" style="margin-bottom: 0">
                    {{ $data->nama_acara }}</div>
                <div class="row no-gutters align-items-center justify-content-center">
                    <div class="col-auto font-weight-bold h5" style="margin-bottom: 0">
                        {{ $data->rute->start }}
                    </div>
                    <div class="col px-3">
                        <div style="border-top: 1px solid black"></div>
                    </div>
                    <div class="col-auto text-right font-weight-bold h5" style="margin-bottom: 0">
                        {{ $data->rute->end }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row no-gutters align-items-center justify-content-center">
                    <div class="col">
                        <p style="margin-bottom: 0">Kode Booking</p>
                        <h3 class="font-weight-bold">{{ $data->kode }}</h3>
                    </div>
                    {{-- <div class="col-auto">
                        {!! DNS1D::getBarcodeHTML($data->kode, 'C128', 1.2, 45) !!}
                    </div> --}}
                </div>
                <p style="margin-bottom: 0; margin-top: 5px;">Jadwal Ibadah</p>
                <h5 class="font-weight-bold text-center">
                    <div>
                        {{ date('l, d F Y', strtotime($data->waktu)) }}
                    </div>
                    <div>
                        {{ date('H:i', strtotime($data->waktu)) }} WIB
                    </div>
                    <div>
                        <img src="/img/kodeQR.png" alt="QRIS Code" class="qris-image" width="200" height="200">
                    </div>
                </h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Nama Acara</td>
                        <td class="text-right">{{ $data->rute->transportasi->category->name }}
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Pengguna</td>
                        <td class="text-right">{{ $data->penumpang->name }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Kursi</td>
                        <td class="text-right">{{ $data->kursi }}</td>
                    </tr>
                    <tr>
                        <td>Total Pembayaran</td>
                        <td class="text-right">Rp. {{ number_format($data->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td class="text-right">{{ $data->status }}</td>
                    </tr>
                </table>
            </div>
            @if ($data->status == 'Belum Bayar' && Auth::user()->level != 'Penumpang')
                <div class="card-body">
                    <a href="{{ route('pembayaran', $data->id) }}"
                        class="btn btn-primary btn-block btn-sm text-white">Verifikasi</a>
                </div>
            @elseif ($data->status == 'Belum Bayar')
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#uploadBuktiModal" data-id="{{ $data->id }}">Upload
                        Bukti</button>
                </div>
            @endif
        </div>
    </div>
    </div>
    <div class="modal fade" id="uploadBuktiModal" tabindex="-1" role="dialog" aria-labelledby="uploadBuktiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" enctype="multipart/form-data" id="uploadBuktiForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadBuktiModalLabel">Upload Bukti Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bukti">Upload Bukti</label>
                            <input type="file" class="form-control" id="bukti" name="bukti" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#uploadBuktiModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var action = '{{ route('pemesanan.uploadBukti', ':id') }}';
            action = action.replace(':id', id);
            $('#uploadBuktiForm').attr('action', action);
        });
    </script>
@endsection

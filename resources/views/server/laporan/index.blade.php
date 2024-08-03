@extends('layouts.app')
@section('title', 'Transaksi')
@section('heading', 'Transaksi')
@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <style>
        thead>tr>th,
        tbody>tr>td {
            vertical-align: middle !important;
        }

        .card-title {
            float: left;
            font-size: 1.1rem;
            font-weight: 400;
            margin: 0;
        }

        .card-text {
            clear: both;
        }

        small {
            font-size: 80%;
            font-weight: 400;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .lightbox-container {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .lightbox-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .close-lightbox {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="card shadow mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- Filter buttons -->
                <div class="float-left">
                    <form action="{{ route('transaksi') }}" method="GET" class="form-inline">
                        <input type="date" name="date" class="form-control mr-2" value="{{ $date }}">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                </div>
                <div class="float-right">
                    <a href="{{ route('transaksi') }}" class="btn btn-secondary btn-sm">Semua</a>
                    <a href="{{ route('transaksi', ['payment_status' => 'paid']) }}"
                        class="btn btn-success btn-sm">Lunas</a>
                    <a href="{{ route('transaksi', ['payment_status' => 'unpaid']) }}" class="btn btn-danger btn-sm">Belum
                        Bayar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Kode Pemesanan</td>
                            <td>Jadwal</td>
                            <td>Acara</td>
                            <td>Pendeta</td>
                            <td>Bukti Transfer</td>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanan as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{-- <h5 class="card-title">{!! DNS1D::getBarcodeHTML($data->kode, 'C128', 2, 30) !!}</h5> --}}
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ $data->kode }}
                                        </small>
                                    </p>
                                </td>
                                <td>
                                    <h5 class="card-title">
                                        {{ $data->tujuan }}, {{ date(' d F Y', strtotime($data->waktu)) }}</h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ date('H:i', strtotime($data->start)) }} -
                                            {{ date('H:i', strtotime($data->end)) }}
                                        </small>
                                    </p>
                                </td>
                                <td>
                                    <h5 class="card-title">{{ $data->nama_acara }}</h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            Jumlah Kursi : {{ $data->kursi }}
                                        </small>
                                    </p>
                                </td>
                                <td>
                                    <h5 class="card-title">{{ $data->pendeta_name }}</h5>
                                </td>
                                <td>
                                    @if ($data->bukti)
                                        <img src="{{ asset('img/bukti/' . $data->bukti) }}" alt="Bukti Transfer"
                                            width="100" class="lightbox-trigger"
                                            onclick="openLightbox('{{ asset('img/bukti/' . $data->bukti) }}')">
                                    @else
                                        <span class="badge badge-danger">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('transaksi.show', $data->kode) }}" class="btn btn-info btn-circle">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="lightbox-container" id="lightbox">
        <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
        <img class="lightbox-image" id="lightbox-img" src="" alt="Enlarged Image">
    </div>
@endsection
{{-- @section('script') --}}
@section('script')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        function openLightbox(imageUrl) {
            document.getElementById('lightbox-img').src = imageUrl;
            document.getElementById('lightbox').style.display = 'flex';
        }

        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }

        // Close lightbox when clicking outside the image
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target !== this)
                return;
            closeLightbox();
        });
    </script>
@endsection

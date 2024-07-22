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
    </style>
@endsection
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- Button trigger modal -->
            {{-- <button type="button" class="btn btn-primary btn-sm btn-add">
                <i class="fas fa-plus"></i>
            </button> --}}
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
                            <td>Bukti Transfer</td> <!-- New column header for the proof of transfer image -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanan as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <h5 class="card-title">{!! DNS1D::getBarcodeHTML($data->kode, 'C128', 2, 30) !!}</h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ $data->kode }}
                                        </small>
                                    </p>
                                </td>
                                <td>
                                    <h5 class="card-title">
                                        {{ $data->rute->tujuan }}, {{ date(' d F Y', strtotime($data->waktu)) }}</h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ date('H:i', strtotime($data->rute->start)) }} -
                                            {{ date('H:i', strtotime($data->rute->end)) }}
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
                                    <h5 class="card-title">{{ $data->rute->user->name }}</h5>
                                </td>
                                <td>
                                    @if ($data->rute->bukti)
                                        <a href="#" data-toggle="modal" data-target="#imageModal"
                                            data-image="{{ asset('img/bukti/' . $data->rute->bukti) }}">
                                            <img src="{{ asset('img/bukti/' . $data->rute->bukti) }}" alt="Bukti Transfer"
                                                width="100">
                                        </a>
                                    @else
                                        <p class="text-muted">No Proof Uploaded</p>
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
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Bukti Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="Bukti Transfer" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();

            $('#imageModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var imageUrl = button.data('image');
                var modal = $(this);
                modal.find('#modalImage').attr('src', imageUrl);
            });
        });
    </script>
@endsection

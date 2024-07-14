@extends('layouts.app')

@section('title', 'Daftar Pengajuan Acara')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title">Daftar Pengajuan Acara</h4>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '{{ session('error') }}',
                            });
                        </script>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Gereja</th>
                                <th>Jumlah Kursi</th>
                                <th>Nama Pendeta</th>
                                <th>Tanggal</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rute as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->transportasi->name }}</td>
                                    <td>{{ $item->transportasi->jumlah }}</td>
                                    <td>{{ $item->pendeta }}</td>
                                    <td>{{ $item->start }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        @if ($item->status == 0)
                                            <button type="button" class="btn btn-secondary btn-sm">Belum Dicek</button>
                                        @elseif($item->status == 1)
                                            <button type="button" class="btn btn-success btn-sm">Terima</button>
                                        @elseif($item->status == 2)
                                            <button type="button" class="btn btn-danger btn-sm">Ditolak</button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 1)
                                            <a href="{{ route('pemesanan.pesan', $item->id) }}"
                                                class="btn btn-primary btn-sm">Pesan</a>
                                        @elseif ($item->status == 2)
                                            <a href="{{ route('pemesanan.pendeta', $item->id) }}"
                                                class="btn btn-primary">Ganti Pendeta</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

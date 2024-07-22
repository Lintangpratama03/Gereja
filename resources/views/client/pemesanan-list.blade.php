@extends('layouts.app')

@section('title', 'Daftar Pengajuan Acara')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <th>Nama Acara</th>
                                <th>Jumlah Kursi</th>
                                <th>Nama Pendeta</th>
                                <th>Tanggal</th>
                                <th>Harga</th>
                                <th>Admin</th>
                                <th>Pendeta</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rute as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_acara }}</td>
                                    <td>{{ $item->transportasi->jumlah }}</td>
                                    <td>{{ $item->nama_pendeta }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        @if ($item->is_aktif == 0)
                                            <button type="button" class="btn btn-secondary btn-sm">Belum Dicek</button>
                                        @elseif($item->is_aktif == 1)
                                            <button type="button" class="btn btn-success btn-sm">Terima</button>
                                        @elseif($item->is_aktif == 2)
                                            <button type="button" class="btn btn-danger btn-sm">Ditolak</button>
                                        @endif
                                    </td>
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
                                        @if ($item->is_aktif == 1)
                                            @if ($item->status_pesan == 0)
                                                @if ($item->status == 1)
                                                    <a href="{{ route('pemesanan.pesan', $item->id) }}"
                                                        class="btn btn-primary btn-sm">Pesan</a>
                                                @elseif ($item->status == 2)
                                                    <a href="{{ route('pemesanan.pendeta', $item->id) }}"
                                                        class="btn btn-primary">Ganti Pendeta</a>
                                                @endif
                                            @elseif ($item->status_pesan == 1)
                                                @if ($item->status == 1)
                                                    Upload Bukti Di History
                                                @elseif ($item->status == 3)
                                                    <button type="button" class="btn btn-success btn-sm">Sukses</button>
                                                @endif
                                            @endif
                                        @else
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
    <!-- Upload Bukti Modal -->
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

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> --}}

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

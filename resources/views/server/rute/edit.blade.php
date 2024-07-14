@extends('layouts.app')
@section('title', 'Edit Jadwal')
@section('heading', 'Edit Jadwal')
@section('styles')
    <link href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            display: block;
            width: 100%;
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 2;
            color: #6e707e;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d1d3e2;
            border-radius: .35rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #6e707e;
            line-height: 28px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 0;
            padding-right: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-top: -2px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + .75rem + 2px);
            position: absolute;
            top: 1px;
            right: 1px;
            width: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="card shadow mb-4 mt-2">
        <form action="{{ route('rute.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $rute->id }}">
                <div class="form-group">
                    <label for="tujuan">Hari</label>
                    <select class="form-control" id="tujuan" name="tujuan" required>
                        <option value="" disabled>-- Pilih Hari --</option>
                        <option value="Senin" {{ $rute->tujuan == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ $rute->tujuan == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ $rute->tujuan == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ $rute->tujuan == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ $rute->tujuan == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ $rute->tujuan == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ $rute->tujuan == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start">Jam Mulai</label>
                    <input type="time" class="form-control" id="start" name="start" value="{{ $rute->start }}"
                        required />
                </div>
                <div class="form-group">
                    <label for="end">Jam Selesai</label>
                    <input type="time" class="form-control" id="end" name="end" value="{{ $rute->end }}"
                        required />
                </div>
                <div class="form-group">
                    <label for="harga">Persembahan</label>
                    <input type="text" class="form-control" id="harga" name="harga"
                        onkeypress="return inputNumber(event)" value="{{ $rute->harga }}" required />
                </div>
                <div class="form-group">
                    <label for="transportasi_id">Ibadah dan Tempat</label><br>
                    <select class="select2 form-control" id="transportasi_id" name="transportasi_id" required
                        style="width: 100%; color: #6e707e;">
                        <option value="" disabled>-- Pilih --</option>
                        @foreach ($transportasi as $data)
                            <option value="{{ $data->id }}"
                                {{ $data->id == $rute->transportasi_id ? 'selected' : '' }}>
                                {{ $data->category->name }} - {{ $data->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="pendeta">Pendeta</label><br>
                    <select class="select2 form-control" id="pendeta" name="pendeta" required
                        style="width: 100%; color: #6e707e;">
                        <option value="" disabled>-- Pilih Pendeta --</option>
                        @foreach ($pendeta as $data)
                            <option value="{{ $data->id }}" {{ $data->id == $rute->pendeta ? 'selected' : '' }}>
                                {{ $data->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('rute.index') }}" class="btn btn-warning mr-2">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        if (jQuery().select2) {
            $(".select2").select2();
        }

        function inputNumber(e) {
            const charCode = (e.which) ? e.which : w.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        };
    </script>
@endsection

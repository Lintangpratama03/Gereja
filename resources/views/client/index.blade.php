@extends('layouts.app')
@section('title', 'Home')
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
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="{{ route('store') }}" class="user" id="reservation-form">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">

                                <label for="nama_acara">Nama Acara</label>
                                <input type="text" class="form-control" id="nama_acara" name="nama_acara"
                                    placeholder="Masukan Nama Acara" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Nama Gereja</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="GBT KRISTUS JURU SELAMAT" required readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="jumlah">Jumlah Kursi</label>
                                <input type="text" class="form-control" id="jumlah" name="jumlah"
                                    onkeypress="return inputNumber(event)" placeholder="Jumlah Kursi" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="category">Category</label>
                                <select class="select2 form-control" id="category" name="category" required>
                                    <option value="" disabled selected>-- Pilih Category --</option>
                                    @foreach ($category as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control" id="harga" name="harga" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start">Jam Mulai</label>
                                <input type="time" class="form-control" id="start" name="start" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end">Jam Selesai</label>
                                <input type="time" class="form-control" id="end" name="end" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="waktu">Tanggal</label>
                                <input type="date" class="form-control" id="waktu" name="waktu" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pendeta">Pendeta</label>
                                <select class="select2 form-control" id="pendeta" name="pendeta" required>
                                    <option value="" disabled selected>-- Pilih Pendeta --</option>
                                    @foreach ($pendeta as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block mt-4" style="font-size: 16px">
                            Reservasi Acara
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();

            $('#category').on('change', function() {
                var categoryId = $(this).val();
                $.ajax({
                    url: '{{ route('getPrice') }}',
                    type: 'GET',
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        $('#harga').val(response.price);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // Handle error
                    }
                });
            });
            $('#reservation-form').on('submit', function(e) {
                e.preventDefault();
                var start = $('#start').val();
                var end = $('#end').val();
                var waktu = $('#waktu').val();

                $.ajax({
                    url: '{{ route('check.schedule') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        start: start,
                        end: end,
                        waktu: waktu
                    },
                    success: function(response) {
                        if (response.exists) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Jadwal Sudah Ada',
                                text: 'Jadwal dengan tanggal dan waktu yang sama sudah ada. Silakan pilih waktu yang berbeda.'
                            });
                        } else {
                            $('#reservation-form')[0].submit();
                        }
                    }
                });
            });
        });
    </script>
@endsection

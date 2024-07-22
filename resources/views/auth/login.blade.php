@extends('layouts.app')
@section('title', 'Login')
@section('content')
    <div class="row col-md-12">
        <div class="col-xl-7 col-lg-6 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Acara Berikutnya</h1>
                        </div>
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Acara</th>
                                    <th>Start</th>
                                    <th>End</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Ambil 5 event terbaru, diurutkan berdasarkan tanggal descending
                                    $events = App\Helpers\EventHelper::getEvents()->sortByDesc('tanggal')->take(5);
                                @endphp
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($event->tanggal)->format('Y-m-d') }}</td>
                                        <td>{{ $event->acara }}</td>
                                        <td>{{ $event->start }}</td>
                                        <td>{{ $event->end }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
                        </div>
                        <form method="POST" action="{{ route('login') }}" class="user">
                            @csrf
                            <div class="form-group">
                                <input type="text"
                                    class="form-control form-control-user @error('username') is-invalid @enderror"
                                    name="username" required autocomplete="off" placeholder="Username">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password"
                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                    name="password" required placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                {{ __('Login') }}
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="{{ route('register') }}">Buat Akun!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("body").addClass("bg-gradient-primary");
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [
                    [0, "asc"]
                ], // Urutkan berdasarkan kolom pertama (Tanggal) secara descending
                "paging": true,
                "pageLength": 5 // Tampilkan maksimal 5 baris per halaman
            });
        });
    </script>
@endsection

@extends('layouts.admin')

@section('title')
    Status Keuangan
@endsection

@section('container')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="user"></i></div>
                                Status Keuangan
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header">
                            Update Status Keuangan
                        </div>
                        <div class="card-body">
                            {{-- Alert --}}
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button class="btn-close" type="button" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <form action="{{ route('finances.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="post_id">Pilih Lembaga</label>
                                        <select name="lembaga_id" id="lembaga" class="form-control">
                                            @foreach ($collections as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="post_id">Input Dana Diterima</label>
                                        <input type="number" name="dana_received" class="form-control"
                                            placeholder="Masukan Dana diterima">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="post_id">Input Data yang digunakan</label>
                                        <input type="number" name="dana_used" class="form-control"
                                            placeholder="Masukan Dana yang digunakan">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <button class="btn btn-primary" type="submit"><i data-feather="upload"></i>
                                            &nbsp; Update</button>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="col-md-12">
                                        <label for="post_id">Sisa Dana</label>
                                        <input type="number" name="sisa_dana" class="form-control sisa_dana disabled"
                                            placeholder="Sisa Dana">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('addon-script')
    <script>
        $('#lembaga').on('change', function() {
            // TODO :: ajax
        })
    </script>
@endpush

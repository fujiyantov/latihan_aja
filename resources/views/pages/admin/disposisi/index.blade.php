@extends('layouts.admin')

@section('title')
    Proposal Keluar
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
                                Disposisi
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header">
                            Catatan Disposisi
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th style="width: 10%">Keterangan</th>
                                        <td style="width: 1%">:</td>
                                        <td>Lembaga IMM FT-UMJ</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%">Tanggal</th>
                                        <td style="width: 1%">:</td>
                                        <td>10/10/2022</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%">Dari</th>
                                        <td style="width: 1%">:</td>
                                        <td>Ketua IMM FT-UMJ</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 10%">Perihal</th>
                                        <td style="width: 1%">:</td>
                                        <td>Permohonan Dana DAD IMM FT-UMJ</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Diposisikan Kepada</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">
                                            <select class="form-select" aria-label="Default select example">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">
                                            <div class="form-floating">
                                                <textarea class="form-control" style="height: 200px" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                                <label for="floatingTextarea">Catatan</label>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">
                                            <button class="btn btn-primary" type="submit"><i data-feather="upload"></i> &nbsp; Simpan</button>
                                        </th>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

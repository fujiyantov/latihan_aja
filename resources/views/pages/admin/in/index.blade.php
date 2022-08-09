@extends('layouts.admin')

@section('title')
    Proposal Masuk
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
                                Proposal Masuk
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
                            List Proposal
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
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button class="btn-close" type="button" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            {{-- List Data --}}
                            <table class="table table-striped table-hover table-sm" id="crudTable">
                                <thead>
                                    <tr>
                                        <th width="10">No.</th>
                                        <th>Keterangan</th>
                                        <th>Perihal</th>
                                        <th>No. Proposal</th>
                                        <th>Tanggal</th>
                                        <th>Proposal</th>
                                        <th>Disposisi</th>
                                        {{-- <th>Validasi</th> --}}
                                        <th>Aksi</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @foreach ($letter as $item)
        @php
            $id = $item->id;
            $title = $item->title;
            $date = $item->date;
            $letter_no = $item->letter_no;
        @endphp

        {{-- Modal Update --}}
        <div class="modal fade" id="updateModal{{ $id }}" role="dialog" aria-labelledby="createModal"
            aria-hidden="true" style="overflow:hidden;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModal{{ $id }}">Disposisi Proposal</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('disposisi', $item->id) }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>No Surat</th>
                                        <td>:</td>
                                        <td>{{ $letter_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Perihal</th>
                                        <td>:</td>
                                        <td>{{ $title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Disposisi Kepada</th>
                                        <td>:</td>
                                        <td>
                                            <select name="member_id" id="" class="form-control">
                                                @foreach ($position as $val)
                                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                <label for="post_id">Comment</label>
                                <textarea name="description" class="form-control" placeholder="comment here..." cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Disposisi --}}
        <div class="modal fade" id="updateModalDisposisi{{ $id }}" role="dialog" aria-labelledby="createModal"
            aria-hidden="true" style="overflow:hidden;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModal{{ $id }}">Disposisi Proposal</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('disposisi', $item->id) }}" method="post">
                        @csrf
                        <div class="modal-body">

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>No Surat</th>
                                        <td>:</td>
                                        <td>{{ $letter_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Perihal</th>
                                        <td>:</td>
                                        <td>{{ $title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Disposisi Kepada</th>
                                        <td>:</td>
                                        <td>
                                            <select name="member_id" id="" class="form-control">
                                                @foreach ($position as $val)
                                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                <label for="post_id">Comment</label>
                                <textarea name="description" class="form-control" placeholder="comment here..." cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Histories --}}
        <div class="modal fade" id="updateModalCatatan{{ $id }}" role="dialog" aria-labelledby="createModal"
            aria-hidden="true" style="overflow:hidden;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModal{{ $id }}">Lihat Catatan Disposisi</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="post">
                        @csrf
                        <div class="modal-body">

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>No Surat</th>
                                        <td>:</td>
                                        <td>{{ $letter_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td>:</td>
                                        <td>{{ $item->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dari</th>
                                        <td>:</td>
                                        <td>{{ $item->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Perihal</th>
                                        <td>:</td>
                                        <td>{{ $title }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                <h6 class="card-title">Catatan Disposisi</h6>
                                <div class="overflow-auto" style="max-height: 500px">
                                    <ul class="timeline list-group list-group-flush">
                                        @foreach ($item->histories as $history)
                                            {{-- @for ($i = 0; $i < 40; $i++)
                                            <li class="event list-group-item" style="background: #F7F9FA">
                                                <a href="">
                                                    <h6>asd <i data-feather="arrow-right"></i>
                                                        asdasd</h6>
                                                </a>
                                                <small><i data-feather="calendar"></i>
                                                    asdasd</small> <br />
                                                <small class="text-muted">asdads</small>

                                            </li>
                                        @endfor --}}
                                            <li class="event list-group-item" style="background: #F7F9FA">
                                                <a href="">
                                                    <h6>{{ $history->sendBy->name }} <i data-feather="arrow-right"></i>
                                                        {{ $history->user->name }}</h6>
                                                </a>
                                                <small><i data-feather="calendar"></i>
                                                    {{ $history->created_at->format('d M Y H:i') }}</small> <br />
                                                <small class="text-muted">{{ $history->description }}</small>

                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('addon-script')
    <script>
        var datatable = $('#crudTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [{
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'title',
                    name: 'perihal'
                },
                {
                    data: 'letter_no',
                    name: 'nosurat'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'proposal',
                    name: 'proposal'
                },
                {
                    data: 'disposisi',
                    name: 'disposisi'
                },
                /* {
                    data: 'validasi',
                    name: 'validasi',
                    orderable: false,
                    searcable: false,
                    width: '10%'
                }, */
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searcable: false,
                },
                {
                    data: 'dana',
                    name: 'dana'
                },
            ]
        });
    </script>
@endpush

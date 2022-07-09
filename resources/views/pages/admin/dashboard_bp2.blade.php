@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('container')
    <main>
        <header class="page-header pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="home"></i></div>
                                Dashboard
                            </h1>
                            {{-- <div class="page-header-subtitle">Administrator Panel</div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-10 mt-n10">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body h-100 p-5">
                            <div class="row align-items-center">
                                <div class="col-xl-4 col-xxl-12 text-center"><img class="img-fluid" src="/admin/assets/img/illustrations/at-work.svg" style="max-width: 56rem" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
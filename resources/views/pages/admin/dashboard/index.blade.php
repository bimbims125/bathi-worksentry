@extends('pages.admin.layouts.master')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Absen</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Foto Absen Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absen as $item)
                            <tr>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->clock_in_time }}</td>
                                <td>{{ $item->clock_out_time }}</td>
                                <td>
                                    <img src="{{ asset('storage/'. $item->clock_in_picture) }}" alt="" width="100px">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

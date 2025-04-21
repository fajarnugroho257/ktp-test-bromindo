@extends('template.base.base')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Blank Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            @session('success')
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endsession
            @session('error')
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endsession
            <!-- Default box -->
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('addDataPekerjaan') }}" class="btn btn-success"><i class="fa fa-plus"></i>
                            Tambah</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('dataPekerjaan') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-3 ml-0">
                                <input type="text" name="pekerjaan" class="form-control" placeholder="Pekerjaan"
                                    value="{{ request('pekerjaan') }}">
                            </div>
                            <div class="col-md-4 ml-0">
                                <div class="d-flex item-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                        Cari</button>
                                    <a href="{{ route('dataPekerjaan') }}" name="aksi" class="btn btn-dark ml-2"><i
                                            class="fa fa-times"></i>
                                        Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10px">No</th>
                                <th>Pekerjaan</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rs_datas as $key => $data)
                                <tr>
                                    <td class="text-center">{{ $rs_datas->firstItem() + $key }}</td>
                                    <td>{{ $data->pekerjaan_nama }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('editDataPekerjaan', [$data->id]) }}"
                                            class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                        <a href="{{ route('deleteDataPekerjaan', [$data->id]) }}"
                                            onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')"
                                            class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{ $rs_datas->links() }}
                    </ul>
                </div>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@endsection

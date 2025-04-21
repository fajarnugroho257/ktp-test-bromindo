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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @session('success')
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endsession
            @session('error')
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
            @endsession
            <!-- Default box -->
            <div class="card">
                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('dataKtpDownload', request()->all()) }}" class="btn btn-primary"><i
                                class="fa fa-file-excel"></i>
                            Excel</a>
                        <a href="{{ route('dataKtpPdf', request()->all()) }}" class="btn btn-danger"><i
                                class="fa fa-file-pdf"></i>
                            Pdf</a>
                        @if (Auth::user()->role_id == 'R0002')
                            <a href="javascript:;"data-toggle="modal" data-target="#modal-upload" class="btn btn-warning"><i
                                    class="fa fa-upload"></i>
                                Upload</a>
                            <a href="{{ route('addDataKtp') }}" class="btn btn-success"><i class="fa fa-plus"></i>
                                Tambah</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('dataKtp') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-3 ml-0">
                                <input type="text" name="nik" class="form-control" placeholder="NIK"
                                    value="{{ request('nik') }}">
                            </div>
                            <div class="col-md-3 ml-0">
                                <input type="text" name="nama" class="form-control" placeholder="Nama"
                                    value="{{ request('nama') }}">
                            </div>
                            <div class="col-md-3 ml-0">
                                <select class="form-control select2" name="pekerjaan" style="width: 100%;">
                                    <option value="">--Pekerjaan--</option>
                                    @foreach ($rs_pekerjaan as $pekerjaan)
                                        <option value="{{ $pekerjaan->id }}"
                                            {{ request('pekerjaan') == $pekerjaan->id ? 'selected' : '' }}>
                                            {{ $pekerjaan->pekerjaan_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 ml-0">
                                <select class="form-control select2" name="provinsi" style="width: 100%;">
                                    <option value="">--Provinsi--</option>
                                    @foreach ($rs_prov as $prov)
                                        <option value="{{ $prov->id }}"
                                            {{ request('provinsi') == $prov->id ? 'selected' : '' }}>
                                            {{ $prov->provinsi_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3 ml-0">
                                <select class="form-control select2" name="kabupaten" style="width: 100%;">
                                    <option value="">--Kabupaten--</option>
                                    @foreach ($rs_kab as $kab)
                                        <option value="{{ $kab->id }}"
                                            {{ request('kabupaten') == $kab->id ? 'selected' : '' }}>
                                            {{ $kab->kabupaten_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 ml-0">
                                <select class="form-control select2" name="kecamatan" style="width: 100%;">
                                    <option value="">--Kecamatan--</option>
                                    @foreach ($rs_kec as $kec)
                                        <option value="{{ $kec->id }}"
                                            {{ request('kecamatan') == $kec->id ? 'selected' : '' }}>
                                            {{ $kec->kecamatan_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 ml-0">
                                <select class="form-control select2" name="kelurahan" style="width: 100%;">
                                    <option value="">--Kelurahan--</option>
                                    @foreach ($rs_kel as $kel)
                                        <option value="{{ $kel->id }}"
                                            {{ request('kelurahan') == $kel->id ? 'selected' : '' }}>
                                            {{ $kel->kelurahan_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 ml-0">
                                <div class="d-flex item-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                        Cari</button>
                                    <a href="{{ route('dataKtp') }}" name="aksi" class="btn btn-dark ml-2"><i
                                            class="fa fa-times"></i>
                                        Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 10px">No</th>
                                    <th>Foto</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>TTL</th>
                                    <th>Gender</th>
                                    <th>Alamat</th>
                                    @if (Auth::user()->role_id == 'R0002')
                                        <th style="width: 10%">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rs_datas as $key => $data)
                                    <tr>
                                        <td class="text-center">{{ $rs_datas->firstItem() + $key }}</td>
                                        <td class="text-center"><img class="image-fluid" width="75" height="75"
                                                src="{{ asset($data->ktp_path) }}" alt=""></td>
                                        <td>{{ $data->ktp_nik }}</td>
                                        <td>{{ $data->ktp_nama }}</td>
                                        <td class="text-center">{{ $data->ktp_tempat_lahir }}, {{ $data->ktp_tgl_lahir }}
                                        </td>
                                        <td class="text-center">{{ $data->ktp_jk }}</td>
                                        <td class="text-center">
                                            {{ $data->ktp_dusun }}, {{ $data->kelurahan->kelurahan_nama }},
                                            {{ $data->kecamatan->kecamatan_nama }},
                                            {{ $data->kabupaten->kabupaten_nama }}, {{ $data->provinsi->provinsi_nama }}
                                        </td>
                                        @if (Auth::user()->role_id == 'R0002')
                                            <td class="text-center">
                                                <a href="{{ route('editDataKtp', [$data->ktp_nik]) }}"
                                                    class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
                                                <a href="{{ route('deleteDataKtp', [$data->ktp_nik]) }}"
                                                    onclick="return confirm('Apakah anda yakin akan menghapus data ini ?')"
                                                    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
    <div class="modal fade" id="modal-upload">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload data excel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('importDataKtp') }}" enctype="multipart/form-data" method="POST">
                    @method('POST')
                    @csrf
                    <div class="modal-body">
                        <a href="{{ asset('template/template_import_data_ktp.xlsx') }}"><i class="fa fa-download"></i>
                            Download Template</a>
                        <div class="form-group">
                            <label for="exampleInputFile">File Excel</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="file_excel" class="custom-file-input"
                                        id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

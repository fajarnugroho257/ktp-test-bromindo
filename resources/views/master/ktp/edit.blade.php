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

            <!-- Default box -->
            <div class="card">

                <div class="card-header ">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('dataKtp') }}" class="btn btn-block btn-success"><i class="fa fa-arrow-left"></i>
                            Kembali</a>
                    </div>
                </div>
                <form action="{{ route('editProcessDataKtp', $detail->ktp_nik) }}" method="POST"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
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
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Data Pribadi</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama lengkap</label>
                                    <input type="text" required value="{{ old('ktp_nama', $detail->ktp_nama) }}"
                                        name="ktp_nama" class="form-control" placeholder="Nama lengkap">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tampat Lahir</label>
                                    <input type="text" required
                                        value="{{ old('ktp_tempat_lahir', $detail->ktp_tempat_lahir) }}"
                                        name="ktp_tempat_lahir" class="form-control" placeholder="Tampat Lahir">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" required id="ktp_tgl_lahir"
                                        value="{{ old('ktp_tgl_lahir', $detail->ktp_tgl_lahir) }}" name="ktp_tgl_lahir"
                                        class="form-control" placeholder="Tanggal Lahir">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Umur Sekarang</label>
                                    <input readonly id="ktp_umur" required type="text"
                                        value="{{ old('ktp_umur', $detail->ktp_umur) }}" name="ktp_umur"
                                        class="form-control" placeholder="Umur Sekarang">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control select2" required name="ktp_jk" style="width: 100%;">
                                        <option value=""></option>
                                        <option @selected(old('ktp_jk', $detail->ktp_jk) == 'L') value="L">LAKI - LAKI
                                        </option>
                                        <option @selected(old('ktp_jk', $detail->ktp_jk) == 'P') value="P">PEREMPUAN
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Gol Darah</label>
                                    <select class="form-control select2" required name="ktp_darah" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="A" @selected(old('ktp_darah', $detail->ktp_darah) == 'A')>A
                                        </option>
                                        <option value="B" @selected(old('ktp_darah', $detail->ktp_darah) == 'B')>B
                                        </option>
                                        <option value="AB" @selected(old('ktp_darah', $detail->ktp_darah) == 'AB')>AB
                                        </option>
                                        <option value="O" @selected(old('ktp_darah', $detail->ktp_darah) == 'O')>O
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Agama</label>
                                    <select class="form-control select2" required name="ktp_agama" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Islam" @selected(old('ktp_agama', $detail->ktp_agama) == 'Islam')>Islam
                                        </option>
                                        <option value="Kristen Protestan"
                                            {{ old('ktp_agama', $detail->ktp_agama) == 'Kristen Protestan' ? 'selected' : '' }}>
                                            Kristen
                                            Protestan
                                        </option>
                                        <option value="Kristen Katolik"
                                            {{ old('ktp_agama', $detail->ktp_agama) == 'Kristen Katolik' ? 'selected' : '' }}>
                                            Kristen Katolik
                                        </option>
                                        <option value="Hindu"
                                            {{ old('ktp_agama', $detail->ktp_agama) == 'Hindu' ? 'selected' : '' }}>Hindu
                                        </option>
                                        <option value="Buddha"
                                            {{ old('ktp_agama', $detail->ktp_agama) == 'Buddha' ? 'selected' : '' }}>Buddha
                                        </option>
                                        <option value="Konghucu"
                                            {{ old('ktp_agama', $detail->ktp_agama) == 'Konghucu' ? 'selected' : '' }}>
                                            Konghucu
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status Perkawinan</label>
                                    <select class="form-control select2" required name="ktp_perkawinan"
                                        style="width: 100%;">
                                        <option value=""></option>
                                        <option value="Belum kawin" @selected(old('ktp_perkawinan', $detail->ktp_perkawinan) == 'Belum kawin')> Belum kawin </option>
                                        <option value="Kawin" @selected(old('ktp_perkawinan', $detail->ktp_perkawinan) == 'Kawin')>
                                            Kawin
                                        </option>
                                        <option value="Cerai hidup" @selected(old('ktp_perkawinan', $detail->ktp_perkawinan) == 'Cerai hidup')> Cerai hidup </option>
                                        <option value="Cerai mati" @selected(old('ktp_perkawinan', $detail->ktp_perkawinan) == 'Cerai mati')>
                                            Cerai mati
                                        </option>
                                        <option value="Kawin belum tercatat" @selected(old('ktp_perkawinan', $detail->ktp_perkawinan) == 'Kawin belum tercatat')>
                                            Kawin belum tercatat
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kewarganegaraan</label>
                                    <select class="form-control select2" required name="ktp_negara" style="width: 100%;">
                                        <option value=""></option>
                                        <option value="WNI" @selected(old('ktp_negara', $detail->ktp_negara) == 'WNI')>WNI
                                        </option>
                                        <option value="WNA" @selected(old('ktp_negara', $detail->ktp_negara) == 'WNA')>WNA
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" class="form-control" name="ktp_image" accept="image/*">
                                    <small class="text-danger">Isi jika ingin merubah foto</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <select class="form-control select2" required name="pekerjaan_id"
                                        style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($rs_pekerjaan as $pekerjaan)
                                            <option value="{{ $pekerjaan->id }}" @selected(old('pekerjaan_id', $detail->pekerjaan_id) == $pekerjaan->id)>
                                                {{ $pekerjaan->pekerjaan_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Data Alamat</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Rt</label>
                                    <input type="text" required max="5"
                                        value="{{ old('ktp_rt', $detail->ktp_rt) }}" name="ktp_rt" class="form-control"
                                        placeholder="Rt">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Rw</label>
                                    <input type="text" required max="5"
                                        value="{{ old('ktp_rw', $detail->ktp_rw) }}" name="ktp_rw" class="form-control"
                                        placeholder="Rw">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Dusun</label>
                                    <input type="text" required value="{{ old('ktp_dusun', $detail->ktp_dusun) }}"
                                        name="ktp_dusun" class="form-control" placeholder="Nama Dusun">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <select id="provinsi" required class="form-control select2" name="provinsi_id"
                                        style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($rs_prov as $prov)
                                            <option value="{{ $prov->id }}" @selected(old('provinsi_id', $detail->provinsi_id) == $prov->id)>
                                                {{ $prov->provinsi_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kabupaten</label>
                                    <select id="kabupaten" required class="form-control select2" name="kabupaten_id"
                                        style="width: 100%;">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select id="kecamatan" required class="form-control select2" name="kecamatan_id"
                                        style="width: 100%;">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Kelurahan</label>
                                    <select id="kelurahan" required class="form-control select2" name="kelurahan_id"
                                        style="width: 100%;">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@endsection
@section(section: 'javascript')
    <script>
        $('#ktp_tgl_lahir').on('change', function() {
            var ktp_tgl_lahir = $(this).val();

            const today = new Date();
            const tglLahir = new Date(ktp_tgl_lahir);

            let umur = today.getFullYear() - tglLahir.getFullYear();
            const bulan = today.getMonth() - tglLahir.getMonth();
            const hari = today.getDate() - tglLahir.getDate();

            // Jika belum ulang tahun di tahun ini, kurangi 1
            if (bulan < 0 || (bulan === 0 && hari < 0)) {
                umur--;
            }
            $('#ktp_umur').val(umur);
        })

        $(document).ready(function() {
            const fProv = handleProvinsi({{ old('provinsi_id', $detail->provinsi_id) }});
            const fKab = hadleKabupaten({{ old('kabupaten_id', $detail->kabupaten_id) }});
            const fKec = handleKecamatan({{ old('kecamatan_id', $detail->kecamatan_id) }});
        });

        // provinsi
        function handleProvinsi(paramsProv) {
            var selectedKab = {{ old('kabupaten_id', $detail->kabupaten_id) }};
            var provinsiId = paramsProv;
            if (provinsiId) {
                $.ajax({
                    url: '/get-kabupaten/' + provinsiId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kabupaten').empty();
                        $('#kecamatan').empty();
                        $('#kelurahan').empty();
                        $('#kabupaten').append('<option value="">-- Pilih Kabupaten --</option>');
                        $.each(data, function(key, value) {
                            if (selectedKab === value.id) {
                                $('#kabupaten').append('<option selected value="' + value.id +
                                    '">' +
                                    value
                                    .kabupaten_nama + '</option>');
                            } else {
                                $('#kabupaten').append('<option value="' + value.id + '">' +
                                    value
                                    .kabupaten_nama + '</option>');
                            }
                        });
                    }
                });
            } else {
                $('#kabupaten').empty();
                $('#kecamatan').empty();
                $('#kelurahan').empty();
                $('#kabupaten').append('<option value="">-- Pilih Kabupaten --</option>');
                $('#kecamatan').append('<option value="">-- Pilih Kecamatan --</option>');
                $('#kelurahan').append('<option value="">-- Pilih Kelurahan --</option>');
            }
        }
        $('#provinsi').on('change', function() {
            handleProvinsi($(this).val());
        });
        // kabupaten
        function hadleKabupaten(paramsKab, callback) {
            var selectedKec = {{ old('kecamatan_id', $detail->kecamatan_id) }};
            var kabupatenId = paramsKab;
            if (kabupatenId) {
                $.ajax({
                    url: '/get-kecamatan/' + kabupatenId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kecamatan').empty();
                        $('#kelurahan').empty();
                        $('#kecamatan').append('<option value="">-- Pilih Kecamatan --</option>');
                        $.each(data, function(key, value) {
                            if (selectedKec === value.id) {
                                $('#kecamatan').append('<option selected value="' + value.id +
                                    '">' +
                                    value
                                    .kecamatan_nama + '</option>');
                            } else {
                                $('#kecamatan').append('<option value="' + value.id + '">' +
                                    value
                                    .kecamatan_nama + '</option>');
                            }
                        });
                    }
                });
            } else {
                $('#kecamatan').empty();
                $('#kelurahan').empty();
                $('#kecamatan').append('<option value="">-- Pilih Kecamatan --</option>');
                $('#kelurahan').append('<option value="">-- Pilih Kelurahan --</option>');
            }
        }
        $('#kabupaten').on('change', function() {
            hadleKabupaten($(this).val());
        });
        //kecamatan
        function handleKecamatan(paramsKec, callback) {
            var selectedKel = {{ old('kelurahan_id', $detail->kelurahan_id) }};
            var kecamatanId = paramsKec;
            if (kecamatanId) {
                $.ajax({
                    url: '/get-kelurahan/' + kecamatanId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kelurahan').empty();
                        $('#kelurahan').append('<option value="">-- Pilih Kelurahan --</option>');
                        $.each(data, function(key, value) {
                            if (selectedKel === value.id) {
                                $('#kelurahan').append('<option selected value="' + value.id + '">' +
                                    value
                                    .kelurahan_nama + '</option>');
                            } else {
                                $('#kelurahan').append('<option value="' + value.id + '">' + value
                                    .kelurahan_nama + '</option>');
                            }
                        });
                    }
                });
            } else {
                $('#kelurahan').empty();
                $('#kelurahan').append('<option value="">-- Pilih Kelurahan --</option>');
            }
        }
        $('#kecamatan').on('change', function() {
            handleKecamatan($(this).val());
        });
    </script>
@endsection

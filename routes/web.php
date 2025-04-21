<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\master\KabupatenController;
use App\Http\Controllers\master\KecamatanController;
use App\Http\Controllers\master\KelurahanController;
use App\Http\Controllers\master\KtpController;
use App\Http\Controllers\master\PekerjaanController;
use App\Http\Controllers\master\ProvinsiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\menu\headingAppController;
use App\Http\Controllers\menu\menuController;
use App\Http\Controllers\menu\rolePenggunaController;
use App\Http\Controllers\menu\roleMenuController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// login
Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login-process', [LoginController::class, 'loginProcess'])->name('login-process');
});

Route::middleware(['auth'])->group(function () {
    // dahsboard
    Route::middleware(['hasRole.page:dashboard'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
    // logout
    Route::get('/log-out', [LoginController::class, 'logOut'])->name('logOut');
    // heading
    Route::middleware(['hasRole.page:headingApp'])->group(function () {
        Route::get('/heading-aplikasi', [headingAppController::class, 'index'])->name('headingApp');
        Route::get('/add-heading-aplikasi', [headingAppController::class, 'create'])->name('tambahHeadingApp');
        Route::post('/process-add-heading-aplikasi', [headingAppController::class, 'store'])->name('aksiTambahHeadingApp');
        Route::get('/update-heading-aplikasi/{app_heading_id}', [headingAppController::class, 'edit'])->name('updateHeadingApp');
        Route::post('/aksi-update-heading-aplikasi/{app_heading_id}', [headingAppController::class, 'update'])->name('aksiUpdateHeadingApp');
        Route::get('/process-delete-heading-aplikasi/{app_heading_id}', [headingAppController::class, 'destroy'])->name('deleteHeadingApp');
    });
    // menu
    Route::middleware(['hasRole.page:menuApp'])->group(function () {
        Route::get('/menu-aplikasi', [menuController::class, 'index'])->name('menuApp');
        Route::get('/add-menu-aplikasi', [menuController::class, 'create'])->name('tambahMenuApp');
        Route::post('/process-add-menu-aplikasi', [menuController::class, 'store'])->name('aksiTambahMenuApp');
        Route::get('/update-menu-aplikasi/{menu_id}', [menuController::class, 'edit'])->name('updateMenuApp');
        Route::post('/aksi-update-menu-aplikasi/{menu_id}', [menuController::class, 'update'])->name('aksiUpdateMenuApp');
        Route::get('/process-delete-menu-aplikasi/{menu_id}', [menuController::class, 'destroy'])->name('deleteMenuApp');
    });
    // role
    Route::middleware(['hasRole.page:rolePengguna'])->group(function () {
        Route::get('/role-pengguna', [rolePenggunaController::class, 'index'])->name('rolePengguna');
        Route::get('/add-role-pengguna', [rolePenggunaController::class, 'create'])->name('tambahRolePengguna');
        Route::post('/process-add-role-pengguna', [rolePenggunaController::class, 'store'])->name('aksiTambahRolePengguna');
        Route::get('/update-role-pengguna/{role_id}', [rolePenggunaController::class, 'edit'])->name('updateRolePengguna');
        Route::post('/aksi-update-role-pengguna/{role_id}', [rolePenggunaController::class, 'update'])->name('aksiUpdateRolePengguna');
    });
    // menu
    Route::middleware(['hasRole.page:roleMenu'])->group(function () {
        Route::get('/role-menu', [roleMenuController::class, 'index'])->name('roleMenu');
        Route::get('/list-data-role-menu/{role_id}', [roleMenuController::class, 'listDataRoleMenu'])->name('listDataRoleMenu');
        Route::post('/add-role-menu', [roleMenuController::class, 'tambahRoleMenu'])->name('tambahRoleMenu');
    });
    // User
    Route::middleware(['hasRole.page:dataUser'])->group(function () {
        Route::get('/data-user', [UserController::class, 'index'])->name('dataUser');
        Route::get('/add-data-user', [UserController::class, 'create'])->name('tambahUser');
        Route::post('/process-add-data-user', [UserController::class, 'store'])->name('aksiTambahUser');
        Route::get('/update-data-user/{user_id}', [UserController::class, 'edit'])->name('UpdateUser');
        Route::post('/process-update-data-user/{user_id}', [UserController::class, 'update'])->name('aksiUpdateUser');
        Route::get('/process-delete-data-user/{user_id}', [UserController::class, 'destroy'])->name('deleteUser');
    });

    /* YOUR ROUTE APLICATION */
    Route::middleware(['hasRole.page:dataKtp'])->group(function () {
        Route::get('/data-ktp', [KtpController::class, 'index'])->name('dataKtp');
        Route::get('/data-ktp-add', [KtpController::class, 'create'])->name('addDataKtp');
        Route::post('/data-ktp-add-proses', [KtpController::class, 'store'])->name('addProcessDataKtp');
        Route::get('/data-ktp-edit/{ktp_nik}', [KtpController::class, 'show'])->name('editDataKtp');
        Route::put('/data-ktp-edit-proses/{ktp_nik}', [KtpController::class, 'update'])->name('editProcessDataKtp');
        Route::get('/data-ktp-delete-process/{id}', [KtpController::class, 'destroy'])->name('deleteDataKtp');

        //
        Route::get('/data-ktp-download', [KtpController::class, 'download'])->name('dataKtpDownload');
        Route::get('/data-ktp-pdf', [KtpController::class, 'exportPdf'])->name('dataKtpPdf');
        Route::post('/data-ktp-import', [KtpController::class, 'import'])->name('importDataKtp');

        // ajax
        Route::get('/get-kabupaten/{id}', [KtpController::class, 'get_all_kabupaten_by_prov']);
        Route::get('/get-kecamatan/{id}', [KtpController::class, 'get_all_kecamatan_by_kab']);
        Route::get('/get-kelurahan/{id}', [KtpController::class, 'get_all_kelurahan_by_kec']);
    });
    // PROVINSI
    Route::middleware(['hasRole.page:dataProvinsi'])->group(function () {
        Route::get('/data-provinsi', [ProvinsiController::class, 'index'])->name('dataProvinsi');
        Route::get('/data-provinsi-add', [ProvinsiController::class, 'create'])->name('addDataProvinsi');
        Route::post('/data-provinsi-add-proses', [ProvinsiController::class, 'store'])->name('addProcessDataProvinsi');
        Route::get('/data-provinsi-edit/{id}', [ProvinsiController::class, 'show'])->name('editDataProvinsi');
        Route::put('/data-provinsi-edit-process/{id}', [ProvinsiController::class, 'update'])->name('editProcessDataProvinsi');
        Route::get('/data-provinsi-delete-process/{id}', [ProvinsiController::class, 'destroy'])->name('deleteDataProvinsi');
    });
    // KABUPATEN
    Route::middleware(['hasRole.page:dataKabupaten'])->group(function () {
        Route::get('/data-kabupaten', [KabupatenController::class, 'index'])->name('dataKabupaten');
        Route::get('/data-kabupaten-add', [KabupatenController::class, 'create'])->name('addDataKabupaten');
        Route::post('/data-kabupaten-add-proses', [KabupatenController::class, 'store'])->name('addProcessDataKabupaten');
        Route::get('/data-kabupaten-edit/{id}', [KabupatenController::class, 'show'])->name('editDataKabupaten');
        Route::put('/data-kabupaten-edit-process/{id}', [KabupatenController::class, 'update'])->name('editProcessDataKabupaten');
        Route::get('/data-kabupaten-delete-process/{id}', [KabupatenController::class, 'destroy'])->name('deleteDataKabupaten');
    });
    // KECAMATAN
    Route::middleware(['hasRole.page:dataKecamatan'])->group(function () {
        Route::get('/data-kecamatan', [KecamatanController::class, 'index'])->name('dataKecamatan');
        Route::get('/data-kecamatan-add', [KecamatanController::class, 'create'])->name('addDataKecamatan');
        Route::post('/data-kecamatan-add-proses', [KecamatanController::class, 'store'])->name('addProcessDataKecamatan');
        Route::get('/data-kecamatan-edit/{id}', [KecamatanController::class, 'show'])->name('editDataKecamatan');
        Route::put('/data-kecamatan-edit-process/{id}', [KecamatanController::class, 'update'])->name('editProcessDataKecamatan');
        Route::get('/data-kecamatan-delete-process/{id}', [KecamatanController::class, 'destroy'])->name('deleteDataKecamatan');
    });
    // KELURAHAN
    Route::middleware(['hasRole.page:dataKelurahan'])->group(function () {
        Route::get('/data-kelurahan', [KelurahanController::class, 'index'])->name('dataKelurahan');
        Route::get('/data-kelurahan-add', [KelurahanController::class, 'create'])->name('addDataKelurahan');
        Route::post('/data-kelurahan-add-proses', [KelurahanController::class, 'store'])->name('addProcessDataKelurahan');
        Route::get('/data-kelurahan-edit/{id}', [KelurahanController::class, 'show'])->name('editDataKelurahan');
        Route::put('/data-kelurahan-edit-process/{id}', [KelurahanController::class, 'update'])->name('editProcessDataKelurahan');
        Route::get('/data-kelurahan-delete-process/{id}', [KelurahanController::class, 'destroy'])->name('deleteDataKelurahan');
    });
    // PEKERJAAN
    Route::middleware(['hasRole.page:dataPekerjaan'])->group(function () {
        Route::get('/data-pekerjaan', [PekerjaanController::class, 'index'])->name('dataPekerjaan');
        Route::get('/data-pekerjaan-add', [PekerjaanController::class, 'create'])->name('addDataPekerjaan');
        Route::post('/data-pekerjaan-add-proses', [PekerjaanController::class, 'store'])->name('addProcessDataPekerjaan');
        Route::get('/data-pekerjaan-edit/{id}', [PekerjaanController::class, 'show'])->name('editDataPekerjaan');
        Route::put('/data-pekerjaan-edit-process/{id}', [PekerjaanController::class, 'update'])->name('editProcessDataPekerjaan');
        Route::get('/data-pekerjaan-delete-process/{id}', [PekerjaanController::class, 'destroy'])->name('deleteDataPekerjaan');
    });
    // ACTIVITY
    Route::middleware(['hasRole.page:logActivity'])->group(function () {
        Route::get('/data-activity', [ActivityController::class, 'index'])->name('logActivity');
    });
    /* END YOUR ROUTE APLICATION */
});



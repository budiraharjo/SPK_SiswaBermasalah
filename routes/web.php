<?php
use Core\Route;

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;


Route::get('/', 'HomeController@index');
Route::get('/welcome', 'HomeController@index');
Route::get('/index.php', 'HomeController@index');
Route::post('/login', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::get('/dashboard/admin', 'DashboardController@admin');
Route::get('/dashboard/guru_bk', 'DashboardController@guru_bk');
Route::get('/dashboard/wali_kelas', 'DashboardController@wali_kelas');

Route::get('/dashboard/data-siswa', 'SiswaController@index');
Route::post('/dashboard/data-siswa/store', 'SiswaController@store');
Route::post('/dashboard/data-siswa/update/{id}', 'SiswaController@update');
Route::get('/dashboard/data-siswa/delete/{id}', 'SiswaController@delete');

Route::get('/dashboard/data-user', 'UsersController@index');
Route::post('/dashboard/data-user/store', 'UsersController@store');
Route::post('/dashboard/data-user/update/{id}', 'UsersController@update');
Route::get('/dashboard/data-user/delete/{id}', 'UsersController@delete');

// Data Kriteria (master kriteria)
Route::get('/dashboard/data-kriteria', 'DataKriteriaController@index');
Route::post('/dashboard/data-kriteria/store', 'DataKriteriaController@store');
Route::post('/dashboard/data-kriteria/update/{id}', 'DataKriteriaController@update');
Route::get('/dashboard/data-kriteria/delete/{id}', 'DataKriteriaController@delete');

// Kriteria Metode
Route::get('/dashboard/kriteria-metode', 'KriteriaMetodeController@indexMetode');
Route::post('/dashboard/kriteria-metode/store', 'KriteriaMetodeController@storeMetode');
Route::post('/dashboard/kriteria-metode/update/{id}', 'KriteriaMetodeController@updateMetode');
Route::get('/dashboard/kriteria-metode/delete/{id}', 'KriteriaMetodeController@deleteMetode');


// Periode
Route::get('/dashboard/periode', 'PeriodeController@index');
Route::post('/dashboard/periode/store', 'PeriodeController@store');
Route::post('/dashboard/periode/update/{id}', 'PeriodeController@update');
Route::get('/dashboard/periode/delete/{id}', 'PeriodeController@delete');


// PENILAIAN SAW
Route::get('/dashboard/penilaian-saw', 'PenilaianSawController@index');
Route::post('/dashboard/penilaian-saw/store', 'PenilaianSawController@store');
Route::post('/dashboard/penilaian-saw/update/{id_nilai}', 'PenilaianSawController@update');
Route::post('/dashboard/penilaian-saw/delete', 'PenilaianSawController@delete');
Route::post('/dashboard/penilaian-saw/import', 'PenilaianSawController@importExcel');

// HITUNG SAW
Route::get('/dashboard/hitung-saw', 'PenilaianSawController@hitung');

// RANGKING SAW
Route::get('/dashboard/rangking-saw', 'PenilaianSawController@rangking');


// PENILAIAN WP
Route::get('/dashboard/penilaian-wp', 'PenilaianWpController@index');
Route::post('/dashboard/penilaian-wp/store', 'PenilaianWpController@store');
Route::post('/dashboard/penilaian-wp/update/{id_nilai}', 'PenilaianWpController@update');
Route::post('/dashboard/penilaian-wp/delete', 'PenilaianWpController@delete');
Route::post('/dashboard/penilaian-wp/import', 'PenilaianWpController@importExcel');

// HITUNG WP
Route::get('/dashboard/hitung-wp', 'PenilaianWpController@hitung');

// RANGKING WP
Route::get('/dashboard/rangking-wp', 'PenilaianWpController@rangking');

// PERBANDINGAN
Route::get('/dashboard/perbandingan', 'PerbandinganController@index'); 

// Laporan Siswa
Route::get('/dashboard/laporan-siswa', 'LaporanSiswaController@index');
Route::get('/dashboard/laporan-siswa/download', 'LaporanSiswaController@downloadPdf');

// Laporan SAW
Route::get('/dashboard/laporan-saw', 'LaporanSawController@index');
Route::get('/dashboard/laporan-saw/download', 'LaporanSawController@downloadPdf');

// Laporan WP
Route::get('/dashboard/laporan-wp', 'LaporanWpController@index');
Route::get('/dashboard/laporan-wp/download', 'LaporanWpController@downloadPdf');

// Laporan Perbandingan SAW & WP
Route::get('/dashboard/laporan-perbandingan', 'LaporanPerbandinganController@index');
Route::get('/dashboard/laporan-perbandingan/download', 'LaporanPerbandinganController@downloadPdf');

// Master Batas Masalah
Route::get('/dashboard/batas-masalah', 'BatasMasalahController@index');
Route::get('/dashboard/batas-masalah/create', 'BatasMasalahController@create');
Route::post('/dashboard/batas-masalah/store', 'BatasMasalahController@store');
Route::get('/dashboard/batas-masalah/edit/{id}', 'BatasMasalahController@edit');
Route::post('/dashboard/batas-masalah/update/{id}', 'BatasMasalahController@update');
Route::get('/dashboard/batas-masalah/delete/{id}', 'BatasMasalahController@delete');

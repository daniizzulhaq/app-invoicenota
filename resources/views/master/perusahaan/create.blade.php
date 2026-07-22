@extends('layouts.main')

@section('title', 'Tambah Perusahaan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Perusahaan</h4>
        <a href="{{ route('perusahaan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card-box">
        <form action="{{ route('perusahaan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" class="form-control @error('nama_perusahaan') is-invalid @enderror">
                @error('nama_perusahaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" class="form-control @error('deskripsi') is-invalid @enderror">
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Telp</label>
                <input type="text" name="telp" value="{{ old('telp') }}" class="form-control @error('telp') is-invalid @enderror">
                @error('telp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
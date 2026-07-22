@extends('layouts.main')

@section('title', 'Tambah Customer')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Customer</h4>
        <a href="{{ route('customer.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card-box">
        <form action="{{ route('customer.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                <input type="text" name="nama_customer" value="{{ old('nama_customer') }}" class="form-control @error('nama_customer') is-invalid @enderror">
                @error('nama_customer')
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

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
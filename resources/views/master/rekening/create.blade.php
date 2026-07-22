@extends('layouts.main')

@section('title', 'Tambah Rekening')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Rekening</h4>
        <a href="{{ route('rekening.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card-box">
        <form action="{{ route('rekening.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                <select name="perusahaan_id" class="form-select @error('perusahaan_id') is-invalid @enderror">
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach($perusahaans as $perusahaan)
                        <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id') == $perusahaan->id ? 'selected' : '' }}>
                            {{ $perusahaan->nama_perusahaan }}
                        </option>
                    @endforeach
                </select>
                @error('perusahaan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Bank <span class="text-danger">*</span></label>
                <input type="text" name="nama_bank" value="{{ old('nama_bank') }}" class="form-control @error('nama_bank') is-invalid @enderror">
                @error('nama_bank')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">No Rekening <span class="text-danger">*</span></label>
                <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" class="form-control @error('no_rekening') is-invalid @enderror">
                @error('no_rekening')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Atas Nama <span class="text-danger">*</span></label>
                <input type="text" name="atas_nama" value="{{ old('atas_nama') }}" class="form-control @error('atas_nama') is-invalid @enderror">
                @error('atas_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
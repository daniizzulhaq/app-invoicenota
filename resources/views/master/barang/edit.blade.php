@extends('layouts.main')

@section('title', 'Edit Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Barang</h4>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card-box">
        <form action="{{ route('barang.update', $barang) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="form-control @error('nama_barang') is-invalid @enderror">
                @error('nama_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" placeholder="pcs, box, kg, dll" class="form-control @error('satuan') is-invalid @enderror">
                @error('satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Harga Default</label>
                <input type="number" step="0.01" min="0" name="harga_default" value="{{ old('harga_default', $barang->harga_default) }}" class="form-control @error('harga_default') is-invalid @enderror">
                @error('harga_default')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
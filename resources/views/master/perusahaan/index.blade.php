@extends('layouts.main')

@section('title', 'Perusahaan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Perusahaan</h4>
        <a href="{{ route('perusahaan.create') }}" class="btn btn-primary btn-sm">+ Tambah Perusahaan</a>
    </div>

    <div class="card-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Nama Perusahaan</th>
                    <th>Deskripsi</th>
                    <th>Alamat</th>
                    <th>Telp</th>
                    <th>Email</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perusahaans as $perusahaan)
                    <tr>
                        <td>{{ $loop->iteration + ($perusahaans->currentPage() - 1) * $perusahaans->perPage() }}</td>
                        <td>{{ $perusahaan->nama_perusahaan }}</td>
                        <td>{{ $perusahaan->deskripsi ?? '-' }}</td>
                        <td>{{ $perusahaan->alamat ?? '-' }}</td>
                        <td>{{ $perusahaan->telp ?? '-' }}</td>
                        <td>{{ $perusahaan->email ?? '-' }}</td>
                        <td>
                            <a href="{{ route('perusahaan.edit', $perusahaan) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('perusahaan.destroy', $perusahaan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data perusahaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $perusahaans->links() }}
    </div>
@endsection
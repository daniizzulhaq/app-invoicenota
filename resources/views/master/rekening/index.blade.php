@extends('layouts.main')

@section('title', 'Rekening')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Rekening</h4>
        <a href="{{ route('rekening.create') }}" class="btn btn-primary btn-sm">+ Tambah Rekening</a>
    </div>

    <div class="card-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Perusahaan</th>
                    <th>Nama Bank</th>
                    <th>No Rekening</th>
                    <th>Atas Nama</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekenings as $rekening)
                    <tr>
                        <td>{{ $loop->iteration + ($rekenings->currentPage() - 1) * $rekenings->perPage() }}</td>
                        <td>{{ $rekening->perusahaan->nama_perusahaan ?? '-' }}</td>
                        <td>{{ $rekening->nama_bank }}</td>
                        <td>{{ $rekening->no_rekening }}</td>
                        <td>{{ $rekening->atas_nama }}</td>
                        <td>
                            <a href="{{ route('rekening.edit', $rekening) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('rekening.destroy', $rekening) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data rekening.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $rekenings->links() }}
    </div>
@endsection
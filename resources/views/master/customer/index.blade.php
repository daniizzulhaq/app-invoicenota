@extends('layouts.main')

@section('title', 'Customer')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Customer</h4>
        <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm">+ Tambah Customer</a>
    </div>

    <div class="card-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Nama Customer</th>
                    <th>Alamat</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                        <td>{{ $customer->nama_customer }}</td>
                        <td>{{ $customer->alamat ?? '-' }}</td>
                        <td>
                            <a href="{{ route('customer.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('customer.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data customer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $customers->links() }}
    </div>
@endsection
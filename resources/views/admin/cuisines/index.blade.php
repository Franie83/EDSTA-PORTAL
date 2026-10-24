@extends('layouts.admin')

@section('title', 'Edo Cuisine - Admin')
@section('page_title', 'Edo Cuisine')
@section('page_subtitle', 'Traditional dishes of Edo State')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-11">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('danger'))
      <div class="alert alert-danger alert-dismissible fade show">{{ session('danger') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <a href="{{ route('admin.cuisines.template') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-download me-1"></i>Template</a>
        <button type="button" class="btn btn-gold btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="bi bi-upload me-1"></i>Upload File</button>
      </div>
      <a href="{{ route('admin.cuisines.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add New</a>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th style="width:60px">#</th>
              <th>Name</th>
              <th>Commonly Eaten With</th>
              <th>Area</th>
              <th style="width:80px">Active</th>
              <th style="width:150px">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($cuisines as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td><strong>{{ $c->name }}</strong></td>
                <td class="small">{{ $c->commonly_eaten_with ?? '—' }}</td>
                <td>{{ $c->area ?? '—' }}</td>
                <td>
                  @if($c->is_active)
                    <span class="badge bg-success">Yes</span>
                  @else
                    <span class="badge bg-secondary">No</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.cuisines.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                  <form method="POST" action="{{ route('admin.cuisines.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete this cuisine?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No cuisines yet. <a href="{{ route('admin.cuisines.create') }}">Add one</a> or upload a spreadsheet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-3">{{ $cuisines->links() }}</div>
  </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('admin.cuisines.bulk_upload') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-header"><h5 class="modal-title">Upload Cuisines</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <p class="small text-muted">Download the template first, fill it, then upload.</p>
        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-gold">Upload</button>
      </div>
    </form>
  </div>
</div>
@endsection
@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar mt-4 mb-6">
    <h1 class="h3 fw-600">{{ translate('Divisions Management') }}</h1>
    <p class="text-muted">{{ translate('Manage all divisions and their visibility status.') }}</p>
</div>

<div class="row g-4">
    <!-- Division List -->
    <div class="col-lg-8">
        <div class="card shadow border-1 rounded-5">
            <div class="card-body">
                <form id="sort_divisions" method="GET" class="row g-3 align-items-end mb-4">
                    <div class="col-md-5">
                        <label class="form-label text-muted">{{ translate('Search by Name') }}</label>
                        <input type="text" class="form-control  px-4" name="sort_division_name"
                               placeholder="{{ translate('Type name & Enter') }}"
                               value="{{ $sort_division_name ?? '' }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-muted">{{ translate('Filter by Division') }}</label>
                        <select class="form-select aiz-selectpicker  px-4" name="sort_division_id" data-live-search="true">
                            <option value="">{{ translate('Select Division') }}</option>
                            @foreach ($divisions_all as $division)
                                <option value="{{ $division->id }}" @if ($sort_division_id == $division->id) selected @endif>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 ">{{ translate('Filter') }}</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-light rounded-5 overflow-hidden">
                        <thead class="table-dark rounded-5">
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-end">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($divisions as $key => $division)
                                <tr>
                                    <td>{{ ($key+1) + ($divisions->currentPage() - 1) * $divisions->perPage() }}</td>
                                    <td class="fw-semibold">{{ $division->getTranslation('name') }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_status(this)" value="{{ $division->id }}" type="checkbox" @if($division->status == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('division.edit', ['id' => $division->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                           class="btn btn-sm btn-outline-primary " title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-danger  confirm-delete2"
                                           data-href="{{ route('division.destroy', $division->id) }}"
                                           title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">{{ translate('No divisions found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $divisions->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Division -->
    <div class="col-lg-4">
        <div class="card shadow border-1 rounded-5">
            <div class="card-header bg-light border-bottom rounded-top-4">
                <h5 class="mb-0">{{ translate('Add New Division') }}</h5>
            </div>
            <div class="card-body">
                <form id="division-form" action="{{ route('division.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ translate('Division Name') }}</label>
                        <input type="text" class="form-control  px-4" name="name" placeholder="{{ translate('Enter division name') }}" required>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-success " id="confirmSaveBtn">
                            <i class="las la-plus-circle me-1"></i> {{ translate('Save Division') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function update_status(el) {
    if ('{{ env('DEMO_MODE') }}' === 'On') {
        AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
        return;
    }

    let status = el.checked ? 1 : 0;
    let statusText = status === 1 ? '{{ translate('show') }}' : '{{ translate('hide') }}';

    Swal.fire({
        title: '{{ translate('Are you sure?') }}',
        text: '{{ translate('You are about to') }} ' + statusText + ' {{ translate('this division.') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ translate('Yes, proceed') }}',
        cancelButtonText: '{{ translate('Cancel') }}'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route('division.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(response) {
                if (response == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ translate('Success!') }}',
                        text: '{{ translate('Division status updated successfully') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ translate('Failed') }}',
                        text: '{{ translate('Something went wrong') }}'
                    });
                    el.checked = !el.checked;
                }
            });
        } else {
            el.checked = !el.checked;
        }
    });
}

document.getElementById('confirmSaveBtn').addEventListener('click', function () {
    Swal.fire({
        title: '{{ translate('Are you sure?') }}',
        text: '{{ translate('Do you want to save this division?') }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '{{ translate('Yes, save it!') }}',
        cancelButtonText: '{{ translate('Cancel') }}',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('division-form').submit();
        }
    });
});

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ translate('Success!') }}',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
    });
@endif

@if ($errors->any())
    Swal.fire({
        icon: 'error',
        title: '{{ translate('Validation Errors!') }}',
        text: '{{ implode(', ', $errors->all()) }}',
        timer: 5000,
        showConfirmButton: true
    });
@endif

document.querySelectorAll('.confirm-delete2').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const url = this.getAttribute('data-href'); // Get the delete route URL

        // SweetAlert confirmation
        Swal.fire({
            title: '{{ translate('Are you sure?') }}',
            text: '{{ translate('Do you really want to delete this division? This action cannot be undone.') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ translate('Yes, delete it!') }}',
            cancelButtonText: '{{ translate('Cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete request via AJAX with DELETE method
                fetch(url, {
                    method: 'DELETE', // Use DELETE method
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: '{{ translate('Deleted!') }}',
                            text: '{{ translate('Division has been deleted successfully.') }}',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload the page to reflect the changes
                        });
                    } else {
                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: '{{ translate('Failed') }}',
                            text: '{{ translate('There was an error deleting the division.') }}',
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ translate('Error') }}',
                        text: '{{ translate('Something went wrong. Please try again.') }}',
                    });
                });
            }
        });
    });
});


</script>
@endsection

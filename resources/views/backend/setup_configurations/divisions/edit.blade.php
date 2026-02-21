@extends('backend.layouts.app')

@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 text-dark">{{ translate('Edit Division') }}</h4>
                <a href="{{ route('division.index') }}" class="btn btn-outline-dark btn-sm">
                    <i class="las la-arrow-left mr-1"></i> {{ translate('Back to List') }}
                </a>
            </div>

            <div class="card shadow border-1 rounded-5">
                <div class="card-body p-5">

                    {{-- Language Tabs --}}
                    <div class="mb-4">
                        <ul class="nav nav-pills">
                            @foreach (get_all_active_language() as $language)
                                <li class="nav-item mr-2">
                                    <a class="nav-link px-3 py-2 @if($language->code == $lang) active @endif"
                                       href="{{ route('division.edit', ['id' => $division->id, 'lang' => $language->code]) }}">
                                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="14" class="mr-1">
                                        {{ $language->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Form --}}
                    <form id="update-division-form" action="{{ route('division.update', $division->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        <div class="form-group mb-4">
                            <label class="text-muted small mb-2">{{ translate('Division Name') }}</label>
                            <input type="text" name="name"
                                   value="{{ $division->getTranslation('name', $lang) }}"
                                   class="form-control form-control-lg rounded-5"
                                   placeholder="{{ translate('Enter Division Name') }}" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-5 py-2">
                                <i class="las la-save mr-1"></i> {{ translate('Update') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#update-division-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let method = form.find('input[name="_method"]').val();
        let data = form.serialize();

        $.ajax({
            url: url,
            type: method,
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        confirmButtonText: 'Okay',
                    }).then(() => {
                        window.location.href = "{{ route('division.index') }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Something went wrong.',
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                });
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .form-control-lg {
        font-size: 1rem;
        padding: 0.75rem 1.25rem;
    }

    .nav-pills .nav-link {
        background-color: #f1f3f5;
        color: #333;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: #198754;
        color: #fff;
    }

    .btn-outline-dark {
        border-color: #6c757d;
        color: #343a40;
    }

    .btn-outline-dark:hover {
        background-color: #6c757d;
        color: white;
    }

    .card {
        background-color: #fdfdfd;
    }
</style>
@endsection

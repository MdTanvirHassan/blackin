@extends('backend.layouts.app')

@section('content')

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 text-dark">{{ translate('Edit District') }}</h4>
                <a href="{{ route('district.index') }}" class="btn btn-outline-dark btn-sm ">
                    <i class="las la-arrow-left me-1"></i> {{ translate('Back to List') }}
                </a>
            </div>

            <div class="card shadow rounded-5 border-0">
                <div class="card-body p-5">

                    {{-- Language Tabs --}}
                    <div class="mb-4">
                        <ul class="nav nav-pills gap-2">
                            @foreach (get_all_active_language() as $language)
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-2  @if($language->code == $lang) active @endif"
                                       href="{{ route('district.edit', ['id' => $district->id, 'lang' => $language->code]) }}">
                                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="14" class="me-1">
                                        {{ $language->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Form --}}
                    <form id="update-district-form" action="{{ route('district.update', $district->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="lang" value="{{ $lang }}">

                        {{-- District Name --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">{{ translate('District Name') }}</label>
                            <input type="text" name="name"
                                class="form-control form-control-lg  px-4 shadow-sm"
                                placeholder="{{ translate('Enter District Name') }}"
                                value="{{ $district->getTranslation('name', $lang) }}" required>
                            <div class="invalid-feedback">
                                {{ translate('Please enter a district name.') }}
                            </div>
                        </div>

                        {{-- Division Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">{{ translate('Select Division') }}</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="division_id"
                                            class="form-select form-control px-4 shadow-sm aiz-selectpicker"
                                            data-live-search="true" required>
                                        <option value="">{{ translate('Select Division') }}</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ $district->division_id == $division->id ? 'selected' : '' }}>
                                                {{ $division->getTranslation('name', $lang) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ translate('Please select a division.') }}
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Postal Code --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">{{ translate('Postal Code') }}</label>
                            <input type="number" name="postal_code"
                                class="form-control form-control-lg  px-4 shadow-sm"
                                placeholder="{{ translate('Enter Postal Code') }}"
                                value="{{ $district->postal_code }}" required>
                            <div class="invalid-feedback">
                                {{ translate('Please enter a valid postal code.') }}
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success px-5 py-2  shadow-sm">
                                <i class="las la-save me-1"></i> {{ translate('Update District') }}
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
<script>
    $('#update-district-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let data = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        confirmButtonText: 'Okay',
                    }).then(() => {
                        window.location.href = "{{ route('district.index') }}";
                    });
                } else {
                    Swal.fire('Error', response.message || 'Something went wrong.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .form-control-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }

    .form-control,
    .form-select {
        transition: box-shadow 0.2s ease-in-out;
    }

    .nav-pills .nav-link {
        background-color: #f8f9fa;
        color: #333;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }

    .nav-pills .nav-link.active {
        background-color: #198754;
        color: #fff;
    }

    .btn-outline-dark:hover {
        background-color: #343a40;
        color: #fff;
    }

    .card {
        background-color: #ffffff;
    }

    .form-label {
        font-size: 0.95rem;
    }

    .form-control::placeholder {
        color: #adb5bd;
        opacity: 1;
    }
</style>
@endsection

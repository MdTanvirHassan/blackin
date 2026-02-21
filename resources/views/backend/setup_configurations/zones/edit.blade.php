@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Edit Zone') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('zones.index') }}" class="btn btn-primary">
                    <span>{{ translate('Back') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Zone Information') }}</h5>
                </div>

                <form action="{{ route('zones.update', $zone->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ translate('Name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ $zone->name }}" placeholder="{{ translate('Zone Name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Status') }}</label>
                            <select name="status" class="form-control aiz-selectpicker">
                                <option value="1" {{ $zone->status == 1 ? 'selected' : '' }}>{{ translate('Active') }}</option>
                                <option value="0" {{ $zone->status == 0 ? 'selected' : '' }}>{{ translate('Inactive') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Zone Type') }}</label>
                            <select name="zone_type" id="zone_type" class="form-control aiz-selectpicker" onchange="toggleZoneType()">
                                <option value="regular" {{ $zone->parent_zone_id == null ? 'selected' : '' }}>{{ translate('Regular Zone') }}</option>
                                <option value="sub" {{ $zone->parent_zone_id != null ? 'selected' : '' }}>{{ translate('Sub Zone') }}</option>
                            </select>
                        </div>

                        <div class="form-group" id="parent_zone_group" style="display: {{ $zone->parent_zone_id ? 'block' : 'none' }};">
                            <label>{{ translate('Parent Zone') }}</label>
                            <select name="parent_zone_id" id="parent_zone_id" class="aiz-selectpicker form-control" data-live-search="true">
                                <option value="">{{ translate('Select Parent Zone') }}</option>
                                @foreach ($parentZones as $parentZone)
                                    <option value="{{ $parentZone->id }}" {{ $zone->parent_zone_id == $parentZone->id ? 'selected' : '' }}>
                                        {{ $parentZone->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_zone_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="division_group" style="display: {{ $zone->parent_zone_id ? 'none' : 'block' }};">
                            <label>{{ translate('Select Division') }}</label>
                            <select name="division_id[]" id="division_id" class="aiz-selectpicker form-control" data-live-search="true" multiple>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}" {{ in_array($division->id, $selectedDivisionIds) ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="area_group" style="display: {{ $zone->parent_zone_id ? 'block' : 'none' }};">
                            <label>{{ translate('Select Areas') }}</label>
                            <select name="area_id[]" id="area_id" class="aiz-selectpicker form-control" data-live-search="true" multiple>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" {{ in_array($area->id, $selectedAreaIds) ? 'selected' : '' }}>
                                        {{ $area->name }} ({{ $area->district->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('area_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleZoneType() {
            var zoneType = document.getElementById('zone_type').value;
            var parentZoneGroup = document.getElementById('parent_zone_group');
            var divisionGroup = document.getElementById('division_group');
            var areaGroup = document.getElementById('area_group');
            var divisionSelect = document.getElementById('division_id');
            var areaSelect = document.getElementById('area_id');

            if (zoneType === 'sub') {
                parentZoneGroup.style.display = 'block';
                divisionGroup.style.display = 'none';
                areaGroup.style.display = 'block';
                
                // Clear division selections
                if (divisionSelect) {
                    $(divisionSelect).val(null).trigger('change');
                }
            } else {
                parentZoneGroup.style.display = 'none';
                divisionGroup.style.display = 'block';
                areaGroup.style.display = 'none';
                
                // Clear parent zone and area selections
                document.getElementById('parent_zone_id').value = '';
                if (areaSelect) {
                    $(areaSelect).val(null).trigger('change');
                }
            }
        }
    </script>
@endsection

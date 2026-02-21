<div class="form-group row">
    <input type="hidden" name="types[]" value="BDBULKSMS_TOKEN">
    <div class="col-lg-3">
        <label class="col-from-label">{{ translate('Token') }} <span class="text-danger">*</span></label>
    </div>
    <div class="col-lg-6">
        <input type="text" class="form-control" name="BDBULKSMS_TOKEN"
            value="{{ env('BDBULKSMS_TOKEN') }}" placeholder="{{ translate('Enter your Token') }}" required>
        <small class="form-text text-muted">
            {{ translate('Generate Token from') }}: <a href="https://gwb.li/token" target="_blank">https://gwb.li/token</a>
        </small>
    </div>
</div>

<div class="form-group row">
    <input type="hidden" name="types[]" value="BDBULKSMS_API_URL">
    <div class="col-lg-3">
        <label class="col-from-label">{{ translate('API URL') }}</label>
    </div>
    <div class="col-lg-6">
        <input type="text" class="form-control" name="BDBULKSMS_API_URL"
            value="{{ env('BDBULKSMS_API_URL', 'https://api.bdbulksms.net/api.php?json') }}" 
            placeholder="https://api.bdbulksms.net/api.php?json">
        <small class="form-text text-muted">
            {{ translate('Default') }}: https://api.bdbulksms.net/api.php?json ({{ translate('JSON Output') }})<br>
            {{ translate('Alternative') }}: https://api.bdbulksms.net/api.php ({{ translate('HTML Output') }})<br>
            {{ translate('Non-SSL') }}: http://api.bdbulksms.net/api.php?json
        </small>
    </div>
</div>

<div class="form-group row">
    <input type="hidden" name="types[]" value="BDBULKSMS_OUTPUT_TYPE">
    <div class="col-lg-3">
        <label class="col-from-label">{{ translate('Output Type') }}</label>
    </div>
    <div class="col-lg-6">
        <select class="form-control aiz-selectpicker" name="BDBULKSMS_OUTPUT_TYPE">
            <option value="json" @if (env('BDBULKSMS_OUTPUT_TYPE', 'json') == 'json') selected @endif>
                {{ translate('JSON Output') }}</option>
            <option value="html" @if (env('BDBULKSMS_OUTPUT_TYPE') == 'html') selected @endif>
                {{ translate('HTML Output') }}</option>
        </select>
        <small class="form-text text-muted">
            {{ translate('Select the response format from API') }}
        </small>
    </div>
</div>

<div class="form-group mb-0 text-right">
    <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
</div>


<option value="0">{{ translate('No Parent') }}</option>
@foreach ($categories as $p_category)
    <option value="{{ $p_category->id }}">{{ $p_category->getTranslation('name') }}</option>
    @foreach ($p_category->childrenCategories as $childCategory)
        <option value="{{ $childCategory->id }}">-- {{ $childCategory->getTranslation('name') }}</option>
        @foreach ($childCategory->childrenCategories as $subChildCategory)
            <option value="{{ $subChildCategory->id }}">---- {{ $subChildCategory->getTranslation('name') }}</option>
        @endforeach
    @endforeach
@endforeach


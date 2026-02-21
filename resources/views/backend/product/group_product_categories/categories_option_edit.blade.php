<option value="0">{{ translate('No Parent') }}</option>
@foreach ($categories as $p_category)
    <option value="{{ $p_category->id }}" @if($p_category->id == $category->parent_id) selected @endif>{{ $p_category->getTranslation('name') }}</option>
    @foreach ($p_category->childrenCategories as $childCategory)
        @if($childCategory->id != $category->id)
            <option value="{{ $childCategory->id }}" @if($childCategory->id == $category->parent_id) selected @endif>-- {{ $childCategory->getTranslation('name') }}</option>
            @foreach ($childCategory->childrenCategories as $subChildCategory)
                @if($subChildCategory->id != $category->id)
                    <option value="{{ $subChildCategory->id }}" @if($subChildCategory->id == $category->parent_id) selected @endif>---- {{ $subChildCategory->getTranslation('name') }}</option>
                @endif
            @endforeach
        @endif
    @endforeach
@endforeach


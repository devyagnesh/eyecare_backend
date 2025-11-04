@props([
    'viewRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'deleteTitle' => 'Delete',
    'deleteText' => 'Are you sure you want to delete this item?',
    'deleteSuccessMessage' => 'Deleted successfully',
    'viewLabel' => 'View',
    'editLabel' => 'Edit',
    'deleteLabel' => 'Delete',
])

<div class="d-flex gap-2">
    @if($viewRoute)
    <a href="{{ $viewRoute }}" class="btn btn-sm btn-icon btn-light" title="{{ $viewLabel }}">
        <i class="bx bx-show"></i>
    </a>
    @endif
    
    @if($editRoute)
    <a href="{{ $editRoute }}" class="btn btn-sm btn-icon btn-success" title="{{ $editLabel }}">
        <i class="bx bx-edit"></i>
    </a>
    @endif
    
    @if($deleteRoute)
    <button 
        type="button" 
        class="btn btn-sm btn-danger btn-icon" 
        data-ajax-delete="{{ $deleteRoute }}"
        data-delete-title="{{ $deleteTitle }}"
        data-delete-text="{{ $deleteText }}"
        data-success-message="{{ $deleteSuccessMessage }}"
        title="{{ $deleteLabel }}">
        <i class="bx bx-trash"></i>
    </button>
    @endif
</div>


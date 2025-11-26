@props(['column', 'label'])

@php
  $currentSort = request('sort', 'primerNombre');
  $currentDirection = request('direction', 'asc');
  $direction = ($column == $currentSort && $currentDirection == 'asc') ? 'desc' : 'asc';
  $icon = ($column == $currentSort) ? ($currentDirection == 'asc' ? '&#9650;' : '&#9660;') : '';
  $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $direction]);
@endphp

<a href="{{ $url }}" class="sortable-link">{{ $label }} <span class="sort-icon">{!! $icon !!}</span></a>
@if (count($breadcrumbs))
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb float-xl-end">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!is_null($breadcrumb->url) && !$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                </li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ol>
    <!-- END breadcrumb -->

    <!-- BEGIN page-header -->
    @php
        $last = $breadcrumbs[count($breadcrumbs) - 1];
    @endphp
    <h1 class="page-header">
        {{ $last->title }} <small>{{ $small ?? 'header small text goes here...' }}</small>
    </h1>
    <!-- END page-header -->
@endif

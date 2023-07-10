@unless($breadcrumbs->isEmpty())
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
 
            @if(!is_null($breadcrumb->url) && !$loop->last)
                <li class="breadcrumb-item text-blue-700 font-medium hover:underline hover:text-blue-600"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active font-medium">{{ $breadcrumb->title }}</li>
            @endif
 
        @endforeach
    </ol>
@endunless
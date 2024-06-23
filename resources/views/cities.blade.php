@extends('layout.app')
@section('content')
<main class="main">
    <div class="container" style="color:white;">
        @foreach($list as $item)
        <div class="row">
            <div class="col-md-2">
                <button type="button" style="width: 100%" class="btn btn-warning">                    {!! $item->name !!}
                </button>

            </div>
            <div class="col-md-10">
                <ul class="list-inline">
                    @foreach(\App\Cities::where('code',$item->id)->get() as $link)
                    <li class="list-inline-item cities">
                        @php $genre = getGenre($link->genre) @endphp
                        <a href="{!! route('genre', ['id' => $genre->slug]) !!}" class="">{!! $genre->name !!}
                            <span class="badge badge-pill badge-light">{!! $genre->count !!}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>

            </div>
        </div>
        @endforeach
    </div>
</main>
@endsection

@extends('layout.app')
@section('content')
<main class="main">
    <section class="main__songs">
        <div class="main__songs-row">
            @foreach($genres as $item)
            <a class="main__songs-item main__songs-item--{!! $item->color() !!}" style="margin-top:{!! rand(10,50) !!}px;margin-bottom:{!! rand(10,30) !!}px;margin-right:{!! rand(0,20) !!}px; margin-left:{!! rand(0,25) !!}px" href="{!! route('genre', ['id' => $item->slug]) !!}">{!! $item->name !!}</a>
            @endforeach
        </div>
    </section>
</main>
@endsection

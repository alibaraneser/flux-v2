@extends('layout.app')
@section('content')
<main class="main">
    <section class="main__songs">
        <div class="main__songs-row">
            @foreach($artists as $item)
                <div class="main__songs-item-container">
                    <div class="main__songs-item main__songs-item--{!! $item->color() !!}" style="margin-top:{!! rand(10,50) !!}px;margin-bottom:{!! rand(10,30) !!}px;margin-right:{!! rand(0,20) !!}px; margin-left:{!! rand(0,25) !!}px" id="{!! $item->artist_id !!}">{!! $item->name !!}

                        <a class="spotify" target="_blank" href="{!! $item->link !!}"><i style="margin-left: 10px;" class="fab fa-spotify"></i></a>
                        @if($auth)
                            <a class="spotify edit" id="{!! $item->id !!}"><i style="margin-left: 10px;" class="fas fa-edit"></i></a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>
@endsection
@push('script')
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" >Artist Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Artist Name</label>
                            <input type="hidden" class="form-control artist_id" id="exampleInputEmail1">
                            <input type="text" class="form-control artist_name" value="" id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Link</label>
                            <input type="text" class="form-control artist_link" value="">
                        </div>
                        <div class="form-group">
                            <div class="genre_list">
                            </div>
                            <input type="text" class="form-control basics" placeholder="New Genre" id="exampleInputEmail1" value="">
                        </div>
                    <a class="btn btn-success" id="addGenre">Add More</a>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary artist_update">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<audio id="player" style="display: none">
    <source id="playerSource" type="audio/mpeg"></source>
</audio>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
<script>
    var svgContent = '<svg id="wave" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 38.05"><path id="Line_1" data-name="Line 1" d="M0.91,15L0.78,15A1,1,0,0,0,0,16v6a1,1,0,1,0,2,0s0,0,0,0V16a1,1,0,0,0-1-1H0.91Z"/> <path id="Line_2" data-name="Line 2" d="M6.91,9L6.78,9A1,1,0,0,0,6,10V28a1,1,0,1,0,2,0s0,0,0,0V10A1,1,0,0,0,7,9H6.91Z"/><path id="Line_3" data-name="Line 3" d="M12.91,0L12.78,0A1,1,0,0,0,12,1V37a1,1,0,1,0,2,0s0,0,0,0V1a1,1,0,0,0-1-1H12.91Z"/><path id="Line_4" data-name="Line 4" d="M18.91,10l-0.12,0A1,1,0,0,0,18,11V27a1,1,0,1,0,2,0s0,0,0,0V11a1,1,0,0,0-1-1H18.91Z"/><path id="Line_5" data-name="Line 5" d="M24.91,15l-0.12,0A1,1,0,0,0,24,16v6a1,1,0,0,0,2,0s0,0,0,0V16a1,1,0,0,0-1-1H24.91Z"/><path id="Line_6" data-name="Line 6" d="M30.91,10l-0.12,0A1,1,0,0,0,30,11V27a1,1,0,1,0,2,0s0,0,0,0V11a1,1,0,0,0-1-1H30.91Z"/><path id="Line_7" data-name="Line 7" d="M36.91,0L36.78,0A1,1,0,0,0,36,1V37a1,1,0,1,0,2,0s0,0,0,0V1a1,1,0,0,0-1-1H36.91Z"/><path id="Line_8" data-name="Line 8" d="M42.91,9L42.78,9A1,1,0,0,0,42,10V28a1,1,0,1,0,2,0s0,0,0,0V10a1,1,0,0,0-1-1H42.91Z"/><path id="Line_9" data-name="Line 9" d="M48.91,15l-0.12,0A1,1,0,0,0,48,16v6a1,1,0,1,0,2,0s0,0,0,0V16a1,1,0,0,0-1-1H48.91Z"/></svg>';

    var player = document.getElementById('player'),
        playerSource = document.getElementById('playerSource')

    $('.main__songs-item-container').each(function () {
        var $this = $(this)

        $(this).css({
            width: $this.width(),
            height: $this.height()
        })
    })


    player.addEventListener('ended', function () {
        $('.main__songs-item').removeClass('active')
        $('.main__songs-item').find('svg').remove()  // dee
    })


    $('.main__songs-item').click(function(e) {
        var artist_id = $(this).attr('id');
        $('.main__songs-item').removeClass('active')
        $('.main__songs-item').find('svg').remove()
        $(this).addClass('active');
        $('.main__songs-item').find('img').remove();
        $(this).append(svgContent)

        var playerSrc = playerSource.src;

        $.get("{!! url('/') !!}" + "/track/" + artist_id, function(data, status){

            if(playerSrc !== data.ok) {
                playerSource.src = data.ok;
                player.load();
                player.volume = 0.25;

                player.play() // şu pozisyonu halledelim
            } else {
                $('.main__songs-item').removeClass('active')
                $('.main__songs-item').find('svg').remove()
                player.pause()
            }

        });

    })
    $(".edit").click(function (e) {

        $.ajax({
            type: 'GET',
            url: "{!! url('/') !!}" + "/genreList",
            success: function(data) {
                $(".basicsHeader").prop('disabled',false);

                var options = {
                    data: jQuery.parseJSON(JSON.stringify(data)),
                    getValue: "name",
                    list: {
                        match: {
                            enabled: true
                        }
                    },
                    theme: "bootstrap"

                };

                $(".basics").easyAutocomplete(options);
            }
        });

        e.stopPropagation()
        var id = $(this).attr("id");
        $.get("{!! url('/') !!}" + "/artist/" + id, function(data, status){

            $(".artist_id").val(data.id);
            $(".artist_name").val(data.name);
            $(".artist_link").val(data.link);


        });
        $.get("{!! url('/') !!}" + "/genres/" + id, function(data, status){

            var list = "";
            $.each( JSON.parse(data), function( key, value ) {
                list += '<li class="list-group-item d-flex justify-content-between align-items-center">' + value.name + '<span class="badge badge-danger badge-pill removeGenre" id="'+ value.id +'"><i class="fas fa-times"></i></span></li>';
            });

            $(".genre_list").html(list);
        });
        $("#editModal").modal();
    });

    $(".artist_update").click(function () {

        var id = $(".artist_id").val();
        var name = $(".artist_name").val();
        var link = $(".artist_link").val();


        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                /* the route pointing to the post function */
                url: '{!! route('updateArtist') !!}',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, id:id,name:name,link:link},
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    $("#editModal").modal('hide');
                }
        });
    });

    $("body").on( "click", ".removeGenre", function() {

        var $this = $(this)

        if (window.confirm("Are you sure?")) {
            var artist = $(".artist_id").val();
            var genre = $(this).attr("id");

            // şimdi  genre siliniyor db den gel gelelim şu kod yemiyor.


            $.get("{!! url('/') !!}" + "/genre/remove/" + artist+"|" +  genre, function(data, status) {

                $this.parent('.list-group-item').remove();

            });
        }

    });

    $("#addGenre").click(function () {
        var artist = $(".artist_id").val();
        var genre =  $(".basics").val();

        $.get("{!! url('/') !!}" + "/genre/add/" + artist+"|" +  genre, function(data, status) {

            $.get("{!! url('/') !!}" + "/genres/" + artist, function(data, status){

                var list = "";
                $.each( JSON.parse(data), function( key, value ) {
                    list += '<li class="list-group-item d-flex justify-content-between align-items-center">' + value.name + '<span class="badge badge-danger badge-pill removeGenre" id="'+ value.id +'"><i class="fas fa-times"></i></span></li>';
                });

                $(".genre_list").html(list);
            });
            $(".basics").val("");
        });
    });
</script>
@endpush

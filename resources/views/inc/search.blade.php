<script>
    function addHeightHeader () {
        var searchAreaHeight = $('#result').height()
        var $header = $('.header')

        $header.css('height', 90 + searchAreaHeight)
    }


    $("#search").click(function () {
       var data = $.trim($("#searchBox").val());
       if(data != ''){
           $.get( "{!! url('/') !!}" + "/search/" + data, function( data ) {
               $("#searchBox").val('');
               if(data == 0){
                   $( "#result" ).html( "No result, do you want to add?" );
               }else {
                   $( "#result" ).html( data );
               }

               addHeightHeader()
           });
       }
    });
    $('#searchBox').keypress(function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            var data = $.trim($("#searchBox").val());
            console.log(data)
            if(data != ''){
                $.get( "{!! url('/') !!}" + "/search/" + data, function( data ) {

                    if(data == 0){
                        $( "#result" ).html( "No result, do you want to add?" );
                    }else {
                        $( "#result" ).html( data );
                    }

                    addHeightHeader()
                });
            }
        }
    });
</script>

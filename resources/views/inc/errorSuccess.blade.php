
@if (count($errors)>0)
    @php
    $message = "";

    foreach ($errors->all() as $error) {
           $message .= $error;
    }
    echo '<script>
                swal("Error!", "'.$message.'", "error");
            </script>';
    @endphp
@endif

@if(Session::has('success'))
    <script>
        swal("Successfully!", "{{Session::get('success')}}", "success");
    </script>
@endif
@if(Session::has('info'))
    <script>
        swal("Warning!", "{{Session::get('info')}}", "info");
    </script>
@endif
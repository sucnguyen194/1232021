
@if(session('message'))
    <script type="text/javascript">
        $.toast({
            heading: "Thông báo!",
            text: "{{session('message')}}",
            position: "top-right",
            loaderBg: "#5ba035",
            icon: "success",
            hideAfter: 3e3,
            stack: 1
        })
    </script>
@endif

@if($errors->count() > 0)
    @foreach($errors->all() as $error)
        <script type="text/javascript">
            $.toast({
                heading: "Thông báo!",
                text: "{{$error}}",
                position: "top-right",
                loaderBg: "#bf441d",
                icon: "error",
                hideAfter: 3e3,
                stack: 1
            })
        </script>
    @endforeach
@endif

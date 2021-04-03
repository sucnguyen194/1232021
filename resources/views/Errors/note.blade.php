@if (Session::has('message'))
    <script type="text/javascript">
        $(document).ready(function(){
            flash( @json(session('message')) );
        });
    </script>
@endif

@if ($errors->any())
    <script type="text/javascript">
        $(document).ready(function(){
            flash(@json($errors));
        });
    </script>
@endif

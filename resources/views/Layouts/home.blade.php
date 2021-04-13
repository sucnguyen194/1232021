@extends('Layouts.layout')
@section('title') {{setting()->name}} @stop
@section('site_name') {{setting()->name}} @stop
@section('url') {{route('home')}} @stop
@section('description') {!!setting()->description_seo!!} @stop
@section('keywords'){!!setting()->keyword_seo!!}@stop
@section('image') {{asset(setting()->og_image ?? setting()->logo)}} @stop
@section('content')
{{redirect_lang()}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<script>
    // for personal streams, ex: Account & Orders & Transfers
    const accountAndOrdersFeeds = new WebSocket("wss://dex.binance.org/api/ws/<USER_ADDRESS>");

    // for single streams
    const tradesFeeds = new WebSocket("wss://dex.binance.org/api/ws/<symbol>@trades");
    const marketFeeds = new WebSocket("wss://dex.binance.org/api/ws/<symbol>@marketDiff");
    const deltaFeeds = new WebSocket("wss://dex.binance.org/api/ws/<symbol>@marketDepth");
    // for all symbols
    const allTickers = new WebSocket("wss://dex.binance.org/api/ws/$all@allTickers");
    const allMiniTickers = new WebSocket("wss://dex.binance.org/api/ws/$all@allMiniTickers");
    const blockHeight = new WebSocket("wss://dex.binance.org/api/ws/$all@blockheight");

    // for combined streams, can combined a mixed symbols and streams
    const combinedFeeds = new WebSocket("wss://dex.binance.org/api/stream?streams=<symbol>@trades/<symbol>@marketDepth/<symbol>@marketDiff");
</script>

<script>
    const conn = new WebSocket("wss://dex.binance.org/api/ws");
    conn.onopen = function(evt) {
        // send Subscribe/Unsubscribe messages here (see below)
    }
    conn.onmessage = function(evt) {
        console.info('received data', evt.data);
    };
    conn.onerror = function(evt) {
        console.error('an error occurred', evt.data);
    };
</script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop

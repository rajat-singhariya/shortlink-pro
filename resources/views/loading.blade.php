@extends('layouts.app')

@section('title', 'Preparing your links | ShortLink Pro')

@section('content')
<div class="mx-auto flex h-dvh max-w-xl flex-col items-center justify-center overflow-hidden px-4 text-center">
    <img src="{{ asset('images/loading.webp') }}" alt="" width="992" height="586" class="max-h-[40dvh] w-full max-w-sm object-contain">

    <h1 class="mt-6 text-2xl font-bold sm:text-3xl">Preparing your short links...</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">
        Checking your link and creating smart options for you.
    </p>

    <div class="mt-6 h-2 w-full max-w-xs overflow-hidden rounded-full bg-gray-200 dark:bg-white/10">
        <div class="progress-bar h-full rounded-full bg-indigo-600"></div>
    </div>

    <noscript>
        <a href="{{ $resultUrl }}" class="mt-6 inline-block font-semibold text-indigo-600">Continue</a>
    </noscript>
</div>

<script>
    (function () {
        var resultUrl = @json($resultUrl);
        var suggestUrl = @json($suggestUrl);
        var csrf = @json(csrf_token());

        // Kam se kam 2.2 sec loading dikhao; AI jawab de de to result par jao (max 9 sec intezaar)
        var minWait = new Promise(function (r) { setTimeout(r, 2200); });
        var maxWait = new Promise(function (r) { setTimeout(r, 9000); });

        var suggest = fetch(suggestUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).catch(function () {});

        Promise.all([minWait, Promise.race([suggest, maxWait])]).then(function () {
            window.location.replace(resultUrl);
        });
    })();
</script>
@endsection

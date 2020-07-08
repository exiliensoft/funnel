@extends('common::framework')

@section('angular-styles')
    {{--angular styles begin--}}
		<link rel="stylesheet" href="client/styles.d9ed2637e9af979a001d.css">
	{{--angular styles end--}}
@endsection

@section('angular-scripts')
    {{--angular scripts begin--}}
		<script>
        setTimeout(function() {
            var spinner = document.querySelector('.global-spinner');
            if (spinner) spinner.style.display = 'flex';
        }, 50);
    </script>
		<script src="client/runtime-es2015.e362aac710cadc877dca.js" type="module"></script>
		<script src="client/runtime-es5.e362aac710cadc877dca.js" nomodule="" defer></script>
		<script src="client/polyfills-es5.c47176b455cdacda253c.js" nomodule="" defer></script>
		<script src="client/polyfills-es2015.bb3a97fc3a9b3d0f659d.js" type="module"></script>
		<script src="client/main-es2015.fb5738153ce0161561e1.js" type="module"></script>
		<script src="client/main-es5.fb5738153ce0161561e1.js" nomodule="" defer></script>
	{{--angular scripts end--}}
@endsection

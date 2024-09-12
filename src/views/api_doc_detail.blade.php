@extends('admin.layout.app')

@section('content')
    <div class="container">
        <h1>API Details</h1>
        <p><strong>Route:</strong> {{ $doc->route }}</p>
        <p><strong>Method:</strong> {{ $doc->method }}</p>

        <h2>cURL Command</h2>
        <pre id="curl-command">{{ $curlCommand }}</pre>
        <button id="copy-curl" class="btn btn-secondary">Copy</button>

    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('copy-curl').addEventListener('click', function() {
                var curlCommand = document.getElementById('curl-command').textContent;
                navigator.clipboard.writeText(curlCommand).then(function() {
                    alert('cURL command copied to clipboard!');
                }, function(err) {
                    console.error('Copy failed: ', err);
                });
            });
        });
    </script>
@endsection
{{--@extends('admin.layout.app')--}}

{{--@section('content')<div class="container">--}}
    <h1>API Documentation</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Route</th>
            <th>Method</th>
            <th>Operations</th>
        </tr>
        </thead>
        <tbody>
        @foreach($docs as $doc)
            <tr>
                <td>{{ $doc->route }}</td>
                <td>{{ $doc->method }}</td>
                <td>
                    <a href="{{ route('api_docs.show', $doc->id) }}" class="btn btn-info">Details</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{--@endsection--}}
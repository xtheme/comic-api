@extends('layouts.modal')

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>字段</th>
                <th>数据</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($properties as $key => $value)
                <tr>
                    <td>{{ $key }}</td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

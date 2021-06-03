@extends('layouts.modal')

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>字段</th>
                <th>旧数据</th>
                <th>新数据</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($changed as $row)
                <tr>
                    <td>{{ $row['field'] }}</td>
                    <td>{{ $row['old'] }}</td>
                    <td>{{ $row['new'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

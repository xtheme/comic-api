@extends('layouts.modal')


{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/x-editable/bootstrap-editable.css') }}">
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>视频ID</th>
                <th>标题</th>
                <th class="text-center">非会员播放量</th>
                <th class="text-center">会员播放量</th>
                <th class="text-center">总播放量</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($series as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        {{ $item->title }}
                        @if(!empty($item->tagged))
                            <div class="d-flex align-content-center flex-wrap" style="margin-top: 5px;">
                                @foreach($item->tagged as $tagged)
                                    <span class="badge badge-pill badge-light-primary" style="margin-right: 3px; margin-bottom: 3px;">{{ $tagged->tag_name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->guest_histories_count }}</td>
                    <td class="text-center">{{ $item->member_histories_count }}</td>
                    <td class="text-center">{{ $item->guest_histories_count  + $item->member_histories_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

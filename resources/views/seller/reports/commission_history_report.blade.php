@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h1 class="h3">{{ translate('Commission History Report') }}</h1>
    </div>

    <div class="card">
        @include('backend.reports.partials.commission_history_section')
    </div>
@endsection

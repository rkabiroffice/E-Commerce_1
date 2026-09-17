@extends('frontend.layouts.app')

@section('content')
    <section class="py-5">
        <div class="container">
            @if ($policy)
                <h1 class="h3 mb-4">{{ translate(ucwords(str_replace('_', ' ', $policy->name))) }}</h1>
                <div class="card">
                    <div class="card-body">
                        {!! $policy->content !!}
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    {{ translate('Policy not found.') }}
                </div>
            @endif
        </div>
    </section>
@endsection

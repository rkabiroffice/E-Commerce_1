@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product Bulk Upload') }}</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>{{ translate('Instructions') }}</strong>
                <p class="mb-0">{{ translate('Download the example file, fill it with valid product data, then upload it below.') }}</p>
            </div>
            <a href="{{ static_asset('download/product_bulk_demo.xlsx') }}" download class="btn btn-info mb-3">{{ translate('Download Example') }}</a>
            <div class="mb-3">
                <a href="{{ route('pdf.download_category') }}" class="btn btn-soft-info">{{ translate('Download Categories') }}</a>
                <a href="{{ route('pdf.download_brand') }}" class="btn btn-soft-info">{{ translate('Download Brands') }}</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Upload Product File') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('bulk_product_upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="custom-file mb-3">
                    <input type="file" name="bulk_file" class="custom-file-input" required>
                    <label class="custom-file-label">{{ translate('Choose File') }}</label>
                </div>
                <button type="submit" class="btn btn-info">{{ translate('Upload File') }}</button>
            </form>
        </div>
    </div>
@endsection

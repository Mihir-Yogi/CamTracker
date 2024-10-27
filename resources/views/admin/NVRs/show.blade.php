@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>NVR Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.nvrs.index') }}">
                        <div class="text-tiny">NVRs</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">NVR Details</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="details-view">
                <!-- NVR Details -->
                <fieldset class="name">
                    <div class="body-title">Model:</div>
                    <div class="details-value">{{ $nvr->model }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Serial Number:</div>
                    <div class="details-value">{{ $nvr->serial_number }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Status:</div>
                    <div class="details-value">{{ ucfirst($nvr->status) }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Failure Reason:</div>
                    <div class="details-value">{{ $nvr->failure_reason ?? 'N/A' }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Depot:</div>
                    <div class="details-value">{{ $nvr->depot->name ?? 'N/A' }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Location:</div>
                    <div class="details-value">{{ $nvr->location->name ?? 'N/A' }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Purchase Date:</div>
                    <div class="details-value">{{ $nvr->purchase_date ? $nvr->purchase_date->format('Y-m-d') : 'N/A' }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Installation Date:</div>
                    <div class="details-value">{{ $nvr->installation_date ? $nvr->installation_date->format('Y-m-d') : 'N/A' }}</div>
                </fieldset>

                <fieldset class="name">
                    <div class="body-title">Warranty Expiration:</div>
                    <div class="details-value">{{ $nvr->warranty_expiration ? $nvr->warranty_expiration->format('Y-m-d') : 'N/A' }}</div>
                </fieldset>

                <!-- Replacement Image (if available) -->
                <fieldset class="name">
                    <div class="body-title">Replacement Reason Image:</div>
                    <div class="image-preview">
                            <img src="{{ asset($nvr->image_replace) }}" alt="Replacement Reason Image" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                    </div>
                </fieldset>

                <!-- Action Buttons -->
                <div class="bot">
                    <a href="{{ route('admin.nvrs.index') }}" class="tf-button w208">Back to List</a>
                    <a href="{{ route('admin.nvrs.edit', $nvr) }}" class="tf-button w208">Edit NVR</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

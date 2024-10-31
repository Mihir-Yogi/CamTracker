@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="tf-section-1 mb-30">
            <div class="flex gap20 flex-wrap-mobile">
                {{-- Expand the sections to full width --}}
                <div class="w-full">
                    {{-- NVR Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-monitor"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total NVRs</div>
                                    <h4>{{$totalNVR}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Failed NVRs Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-alert-triangle"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Failed NVRs</div>
                                    <h4>{{$totalFailedNVR}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DVR Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-hard-drive"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total DVRs</div>
                                    <h4>{{$totalDVR}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Failed DVRs Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-alert-triangle"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Failed DVRs</div>
                                    <h4>{{$totalFailedDVR}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    {{-- Total HDDs Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-server"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total HDDs</div>
                                    <h4>{{$totalHDD}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Failed HDDs Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-alert-triangle"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Failed HDDs</div>
                                    <h4>{{$totalFailedHDD}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total CCTVs Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-camera"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Total CCTVs</div>
                                    <h4>{{$totalCCTV}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Failed CCTVs Count --}}
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg">
                                    <i class="icon-alert-triangle"></i>
                                </div>
                                <div>
                                    <div class="body-text mb-2">Failed CCTVs</div>
                                    <h4>{{$totalFailedCCTV}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.admin')

@section('content')
    <style>    /* General container styling */
    .details-view {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    /* Table Styling */
    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 16px;
    }

    .details-table th,
    .details-table td {
        padding: 14px 20px;
        border: 1px solid #ddd;
    }

    .details-table th {
        background-color: #f5f5f5;
        font-weight: bold;
        color: #333;
        text-align: left;
        font-size: 18px;
    }

    .details-table td {
        color: #333;
    }

    /* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-buttons .tf-button {
        display: inline-block;
        padding: 12px 24px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
    }

    .action-buttons .tf-button:hover {
        background-color: #0056b3;
    }

    /* Section Heading Style */
    .section-heading {
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0 10px;
    }
    .cctv-table {
        margin-top: 20px;
    }
        /* Table styling */
        .table {
            font-size: 16px; /* Increased font size for table data */
            border-collapse: collapse; /* Optional for cleaner look */
            width: 100%; /* Full width */
        }

        .table th, .table td {
            padding: 12px; /* Increased padding for better spacing */
            text-align: left; /* Align text to the left */
            border: 1px solid #ddd; /* Add border to cells */
        }

        /* Make sure select elements have a consistent height */
        select, input[type="file"] {
            height: 48px; /* Set height for select */
            font-size: 16px; /* Match font size with table for consistency */
            padding: 8px; /* Padding for select */
            border-radius: 4px; /* Rounded corners */
            border: 1px solid #333; /* Border styling */
            width: 100%; /* Full width */
            box-sizing: border-box; /* Ensure padding is included in width */
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <h3>Edit Status Report</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('status_reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <fieldset>
                    <table class="details-table">
                        <!-- Depot and Location -->
                        <tr>
                            <th>Depot</th>
                            <td >{{ $report->depot->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td >{{ $report->location->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                    <div class="body-title">Select Depot <span class="tf-color-1">*</span></div>

                    

                <!-- Devices Table -->
                <div id="devicesContainer" class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Off Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($report->nvr)
                            <tr>
                                <td>NVR</td>
                                <td>{{ $report->nvr->model }}</td>
                                <td>{{ $report->nvr->serial_number }}</td>
                                <td>
                                    <select name="nvr_status" class="status-select" >
                                        <option value="ON" {{ $report->nvr_status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $report->nvr_status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="off_reason[nvr]" class="remarks" >{{ $report->nvr_reason }}</input>
                                </td>
                            </tr>
                            @endif

                            @if($report->dvr)
                            <tr>
                                <td>DVR</td>
                                <td>{{ $report->dvr->model }}</td>
                                <td>{{ $report->dvr->serial_number }}</td>
                                <td>
                                    <select name="dvr_status" class="status-select" >
                                        <option value="ON" {{ $report->dvr_status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $report->dvr_status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="off_reason[dvr]" class="remarks" >{{ $report->dvr_reason }}</input>
                                </td>
                            </tr>
                            @endif

                            @if($report->hdd)
                            <tr>
                                <td>HDD</td>
                                <td>{{ $report->hdd->model }}</td>
                                <td>{{ $report->hdd->serial_number }}</td>
                                <td>
                                    <select name="hdd_status" class="status-select" required>
                                        <option value="ON" {{ $report->hdd_status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $report->hdd_status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="off_reason[hdd]" class="remarks" >{{ $report->hdd_reason }}</input>
                                </td>
                            </tr>
                            @endif

                            @if($report->cctvStatuses->isNotEmpty())
                                @foreach($report->cctvStatuses as $cctvStatus)
                                    <tr>
                                        <td>CCTV</td>
                                        <td>{{ $cctvStatus->cctv->model ?? 'N/A' }}</td>
                                        <td>{{ $cctvStatus->cctv->serial_number ?? 'N/A' }}</td>
                                        <td>
                                            <select name="cctv_status[{{$cctvStatus->cctv_id}}]" class="status-select">
                                                <option value="ON" {{ $cctvStatus->status == 'ON' ? 'selected' : '' }}>ON</option>
                                                <option value="OFF" {{ $cctvStatus->status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="cctv_reason[{{$cctvStatus->cctv_id}}]" class="remarks">{{ $cctvStatus->off_reason }}</input>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Image Preview and Upload -->
                <fieldset class="name">
                    <div class="body-title">Replacement Image</div>
                    <div class="image-preview-container">
                        <div class="new-image">
                            <p>Current Image:</p>
                            <img 
                                src="{{ asset($report->remark_image) }}" 
                                alt="Current Image" 
                                style="max-width: 150px; border: 1px solid #ccc; border-radius: 5px;" 
                                ondblclick="showFullImage(this.src)">
                        </div>
                        <div class="new-image">
                            <p>Image Preview:</p>
                            <img 
                                id="new-image-preview" 
                                src="#" 
                                alt="New Image Preview" 
                                style="max-width: 150px; display: none; border: 1px solid #ccc; border-radius: 5px;"
                                ondblclick="showFullImage(this.src)">
                        </div>
                    </div>
                    <input type="file" name="remark_image" accept="image/*" onchange="previewNewImage(event)">
                </fieldset>

                <!-- Modal for Full Image -->
                <div id="fullImageModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 999;">
                    <span onclick="closeFullImage()" style="color: white; position: absolute; top: 20px; right: 30px; cursor: pointer; font-size: 50px;">&times;</span>
                    <img id="fullImage" src="#" alt="Full Image" style="max-width: 90%; max-height: 90%;">
                    <a id="downloadImageBtn" href="#" download style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 18px;">Download Image</a>
                </div>
                <button type="submit" style="margin-top: 30px; background-color: #ccc; width: 100%;">Update Report</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function toggleRemarksField(element) {
                const remarks = element.closest('tr').find('.remarks');
                if (element.val() === 'OFF') {
                    remarks.show();
                } else {
                    remarks.hide().val(''); // Hide and clear the field
                }
            }
        });
        
        function showFullImage(src) {
        const modal = document.getElementById('fullImageModal');
        const fullImage = document.getElementById('fullImage');
        const downloadImageBtn = document.getElementById('downloadImageBtn');
        fullImage.src = src;
        downloadImageBtn.href = src;
        modal.style.display = 'flex'; // Show modal
    }

    function closeFullImage() {
        const modal = document.getElementById('fullImageModal');
        modal.style.display = 'none'; // Hide modal
    }

    function previewNewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const newImagePreview = document.getElementById('new-image-preview');
            newImagePreview.src = reader.result;
            newImagePreview.style.display = 'block';
            newImagePreview.ondblclick = function() {
                showFullImage(reader.result); // Show full image on double click
            };
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $date }}</p>

    <table class="table table-bordered">
        <tr>
            <th>Depot</th>
            <th>Location</th>
        </tr>
        
        @forelse ($reports as $index => $report)
            <tr>
                <td>{{ optional($report->depot)->name }}</td>
                <td>{{ optional($report->location)->name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No reports found.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>

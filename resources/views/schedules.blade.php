<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลลัพธ์ตารางเวลา</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Prompt', Arial, sans-serif;
            background-color: #fdf4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
            flex-direction: column;
        }
        .container {
            text-align: center;
            max-width: 90%;
        }
        h1 {
            color: #b71c1c;
            margin: 20px 0;
            font-size: 32px;
            font-weight: bold;
        }
        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        table thead {
            background-color: #b71c1c;
            color: white;
            font-size: 18px;
        }
        table thead th {
            padding: 15px;
            text-align: center;
            white-space: nowrap;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9dede;
        }
        table tbody tr:hover {
            background-color: #f8c6c6;
            transition: background-color 0.3s;
        }
        table td, table th {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ผลลัพธ์ตารางเวลา</h1>
        <table>
    <thead>
        <tr>
            <th>วัน/เวลา</th>
            <th>08:00-09:00</th>
            <th>09:00-10:00</th>
            <th>10:00-11:00</th>
            <th>11:00-12:00</th>
            <th>12:00-13:00</th>
            <th>13:00-14:00</th>
            <th>14:00-15:00</th>
            <th>15:00-16:00</th>
            <th>16:00-17:00</th>
            <th>17:00-18:00</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($data))
            @foreach ($data as $day => $slots)
                <tr>
                    <td>{{ $day }}</td>
                    @foreach ($slots as $time => $slot)
                        <td>{{ $slot }}</td>
                    @endforeach
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="11">ไม่มีข้อมูล</td>
            </tr>
        @endif
    </tbody>
</table>

    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลลัพธ์ตารางเวลา</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');

        /* Body Styling */
        body {
            font-family: 'Prompt', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://www.museumthailand.com/upload/user/1573012494_8226.jpg') no-repeat center center fixed;
            background-size: cover;
            overflow-x: hidden;
        }

        /* Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(4px);
            z-index: 1;
        }

        /* Container Styling */
        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 90%;
            width: 100%;
            overflow-x: auto;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        h1 {
            color: #b71c1c;
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* Status info on the right */
        .status-info {
            position: absolute;
            top: 30px;
            right: 30px;
            z-index: 3;
            text-align: right;
        }

        .status-info span {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .status-info .available {
            background: linear-gradient(45deg, #98FB98, #32CD32);
        }

        .status-info .unavailable {
            background: linear-gradient(45deg, #FF7F7F, #FF0000);
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 3;
            display: flex;
            align-items: center;
            padding: 12px 18px;
            background: linear-gradient(45deg, #b71c1c, #d32f2f);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .back-button img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        table {
            width: 100%;
            table-layout: auto;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            border-radius: 15px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: linear-gradient(45deg, #b71c1c, #d32f2f);
            color: white;
            font-size: 1rem;
        }

        table thead th {
            padding: 15px;
            font-size: 1rem;
            text-align: center;
            white-space: nowrap;
        }

        table tbody tr:nth-child(even) {
            background-color: #ffe5e5;
        }

        table tbody tr:nth-child(odd) {
            background-color: #fff9f9;
        }

        table tbody tr:hover {
            background: linear-gradient(90deg, #fff9e6, #ffefbf);
            transform: scale(1.01);
            transition: transform 0.3s ease, background 0.3s ease;
        }

        table td, table th {
            padding: 15px;
            text-align: center;
            font-size: 0.95rem;
            border: 1px solid #f1f1f1;
            word-wrap: break-word;
        }

        /* สีพื้นหลังสำหรับสถานะ */
        .available {
            background: linear-gradient(45deg, #98FB98, #32CD32);
            color: white;
        }

        .unavailable {
            background: linear-gradient(45deg, #FF7F7F, #FF0000);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            table td, table th {
                padding: 10px;
                font-size: 0.85rem;
            }

            .container {
                padding: 20px;
            }

            .back-button {
                font-size: 0.9rem;
                padding: 10px 15px;
            }

            .status-info {
                top: 90px;
                right: 15px;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ปุ่มย้อนกลับ -->
        <a href="{{ route('upload.index') }}" class="back-button">
            <img src="https://cdn-icons-png.flaticon.com/512/271/271220.png" alt="Back Icon">
            ย้อนกลับ
        </a>

        <!-- คำอธิบายสถานะ -->
        <div class="status-info">
            <span class="available">สีเขียว: ว่าง</span>
            <span class="unavailable">สีแดง: ไม่ว่าง</span>
        </div>

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
                                <td class="{{ $slot == 'ว่าง' ? 'available' : 'unavailable' }}"></td>
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

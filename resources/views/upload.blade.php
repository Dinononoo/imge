<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดตารางเวลา</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Prompt', Arial, sans-serif;
            background-color: #fdf4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            text-align: center;
            color: #b71c1c;
            margin-bottom: 20px;
            font-size: 28px;
        }
        form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #f5c6cb;
            border-radius: 12px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #b71c1c;
            font-weight: bold;
        }
        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        .custom-file input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .custom-file label {
            display: block;
            padding: 12px;
            font-size: 16px;
            color: #333;
            background-color: #fff;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .custom-file label:hover {
            background-color: #f8d7da;
            border-color: #b71c1c;
        }
        .custom-file span {
            display: block;
            margin-top: 8px;
            font-size: 14px;
            color: #555;
        }
        button {
            display: block;
            width: 100%;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background-color: #b71c1c;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #d32f2f;
        }
        .note {
            font-size: 14px;
            color: #b71c1c;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form action="{{route('processSchedules') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h1>อัปโหลดตารางเวลา</h1>
        <div class="custom-file">
            <input type="file" name="images[]" id="file" multiple required onchange="updateFileName()">
            <label for="file">คลิกเพื่อเลือกไฟล์</label>
            <span id="file-name">ยังไม่ได้เลือกไฟล์</span>
        </div>
        <button type="submit" >อัปโหลด</button>
        <p class="note">รองรับไฟล์ประเภท JPG</p>
    </form>

    <script>
        function updateFileName() {
            const input = document.getElementById('file');
            const fileNameDisplay = document.getElementById('file-name');
            const files = input.files;
            if (files.length > 0) {
                const fileNames = Array.from(files).map(file => file.name).join(', ');
                fileNameDisplay.textContent = fileNames;
            } else {
                fileNameDisplay.textContent = 'ยังไม่ได้เลือกไฟล์';
            }
        }
    </script>
</body>
</html>

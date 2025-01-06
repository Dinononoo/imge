<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดตารางเวลา</title>
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
            overflow: hidden;
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
            backdrop-filter: blur(3px);
            z-index: 1;
        }

        /* Butterfly GIF Styling */
        .yellow-butterfly {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: 2;
            opacity: 0.6;
        }

        /* Form Container */
        form {
            position: relative;
            z-index: 3;
            width: 90%;
            max-width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px; /* เพิ่มความมน */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1), 
                        0 15px 25px rgba(0, 0, 0, 0.2); /* เงา 3 มิติ */
            text-align: center;
            animation: formSlide 1.5s ease-out;
            transition: box-shadow 0.3s ease, transform 0.3s ease; /* เพิ่มเอฟเฟกต์ */
        }

        form:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2), 
                        0 20px 50px rgba(0, 0, 0, 0.3); /* เงา 3 มิติ */
            transform: translateY(-5px); /* ขยับขึ้นเล็กน้อย */
        }

        @keyframes formSlide {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Form Heading */
        h1 {
            color: #b71c1c;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        }

        /* File Upload Styling */
        .custom-file {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 80%;
            padding: 20px;
            background: rgba(245, 245, 245, 0.9);
            border: 2px dashed #f5c6cb;
            border-radius: 20px; /* เพิ่มความมน */
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 auto 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .custom-file:hover {
            background-color: #fce4ec;
            border-color: #b71c1c;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
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

        .custom-file img {
            width: 40px; 
            height: 40px;
            margin-bottom: 8px;
        }

        .custom-file label {
            font-size: 0.9rem;
            color: #333;
            font-weight: 500;
        }

        .custom-file span {
            margin-top: 5px;
            font-size: 0.85rem;
            color: #555;
            display: block;
        }

        /* Button Styling */
        button {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1rem;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(to right, #b71c1c, #d32f2f);
            border: none;
            border-radius: 25px; /* เพิ่มความมน */
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: linear-gradient(to right, #d32f2f, #b71c1c);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* Note Text */
        .note {
            font-size: 0.8rem;
            color: #b71c1c;
            margin-top: 12px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem;
            }

            button {
                padding: 12px;
                font-size: 0.9rem;
            }

            .note {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <img src="https://img1.picmix.com/output/stamp/normal/6/0/1/5/1955106_d24dc.gif" alt="Yellow Butterfly Animation" class="yellow-butterfly">

    <form action="{{route('processSchedules')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <h1>อัปโหลดตารางเวลา</h1>
        <div class="custom-file">
            <img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" alt="Upload Icon">
            <input type="file" name="images[]" id="file" multiple required onchange="updateFileName()">
            <label for="file">คลิกเพื่อเลือกไฟล์</label>
            <span id="file-name">ยังไม่ได้เลือกไฟล์</span>
        </div>
        <button type="submit">ประมวลผล</button>
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

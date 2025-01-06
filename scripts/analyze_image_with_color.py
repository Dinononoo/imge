import cv2
import numpy as np
import json
import sys
import io

# ตั้งค่าการเข้ารหัสให้ stdout เป็น UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DEBUG = True  # เปิด Debug Mode

def resize_image(img, target_width=1240, target_height=640):
    """
    ปรับขนาดภาพโดยคงสัดส่วน
    """
    height, width = img.shape[:2]
    aspect_ratio = width / height

    if aspect_ratio > (target_width / target_height):
        new_width = target_width
        new_height = int(target_width / aspect_ratio)
    else:
        new_height = target_height
        new_width = int(target_height * aspect_ratio)

    resized_img = cv2.resize(img, (new_width, new_height))
    return resized_img

def detect_specific_grid_area(img):
    """
    ตรวจจับพื้นที่เฉพาะในรูปภาพและครอบตัดให้เหลือเฉพาะส่วนกริด
    """
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    blurred = cv2.GaussianBlur(gray, (5, 5), 0)
    edges = cv2.Canny(blurred, 50, 150)

    # หา Contours
    contours, _ = cv2.findContours(edges, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    # หา Contour ที่ใหญ่ที่สุด ซึ่งเป็นพื้นที่กริด
    largest_contour = max(contours, key=cv2.contourArea)
    x, y, w, h = cv2.boundingRect(largest_contour)

    # กำหนดการปรับตำแหน่งให้ครอบตัดเหมือนรูปภาพตัวอย่าง
    # **ปรับค่าตามความเหมาะสมจากภาพตัวอย่าง**
    grid_x = int(x + w * 0.10)    # ลดขอบซ้าย 5% ของความกว้าง
    grid_y = int(y + h * 0.05)     # ลดขอบบน 20% ของความสูง
    grid_w = int(w * 0.3)         # ลดขอบขวา 10% ของความกว้าง
    grid_h = int(h * 0.5)         # ลดขอบล่าง 30% ของความสูง

    # Debug: แสดงพื้นที่ที่ครอบตัด
    if DEBUG:
        debug_img = img.copy()
        cv2.rectangle(debug_img, (grid_x, grid_y), (grid_x + grid_w, grid_y + grid_h), (0, 255, 0), 2)
        cv2.imshow("Adjusted Detected Grid Area", debug_img)
        cv2.waitKey(0)

    # ครอบภาพให้เหลือเฉพาะส่วนกริด
    cropped_img = img[grid_y:grid_y + grid_h, grid_x:grid_x + grid_w]

    if cropped_img.size == 0:
        raise ValueError("Cropping area is invalid. Please check your image.")

    return cropped_img

def analyze_grid(cropped_img, rows=7, cols=10):
    """
    วิเคราะห์กริดเพื่อตรวจสอบสถานะของแต่ละช่อง
    """
    hsv = cv2.cvtColor(cropped_img, cv2.COLOR_BGR2HSV)

    # ขอบเขตสีฟ้า
    lower_blue = np.array([90, 50, 50])
    upper_blue = np.array([150, 255, 255])

    mask_blue = cv2.inRange(hsv, lower_blue, upper_blue)

    if DEBUG:
        cv2.imshow("Blue Mask", mask_blue)
        cv2.waitKey(1)

    height, width = mask_blue.shape[:2]
    cell_height = height // rows
    cell_width = width // cols

    result = {}
    time_slots = [
        "08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00",
        "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00",
        "16:00-17:00", "17:00-18:00"
    ]
    days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

    for i in range(rows):
        day = days[i]
        result[day] = {}
        for j in range(cols):
            x1, y1 = j * cell_width, i * cell_height
            x2, y2 = (j + 1) * cell_width, (i + 1) * cell_height

            cell = mask_blue[y1:y2, x1:x2]
            white_pixels = np.sum(cell == 255)
            total_pixels = cell.size
            ratio = white_pixels / total_pixels

            threshold = 0.3
            result[day][time_slots[j]] = "ว่าง" if ratio > threshold else "ไม่ว่าง"

            if DEBUG:
                color = (0, 255, 0) if ratio > threshold else (0, 0, 255)
                cv2.rectangle(cropped_img, (x1, y1), (x2, y2), color, 2)

    if DEBUG:
        cv2.imshow("Debug Grid", cropped_img)
        cv2.waitKey(0)

    return result

def analyze_single_image(img):
    """
    วิเคราะห์ภาพเดียวเพื่อตรวจสอบสถานะของแต่ละช่องในกริด
    """
    img = resize_image(img)
    cropped_img = detect_specific_grid_area(img)
    result = analyze_grid(cropped_img)
    return result

def analyze_multiple_images(image_paths):
    """
    วิเคราะห์ภาพหลายภาพและรวมผลลัพธ์
    """
    combined_result = None

    for image_path in image_paths:
        img = cv2.imread(image_path)
        if img is None:
            print(f"Error: Cannot read image {image_path}")
            continue

        result = analyze_single_image(img)

        if combined_result is None:
            combined_result = result
        else:
            for day, slots in result.items():
                for slot, status in slots.items():
                    if status == "ไม่ว่าง":
                        combined_result[day][slot] = "ไม่ว่าง"

    return combined_result

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Usage: python analyze_images.py <image_path1> <image_path2> ..."}, ensure_ascii=False))
    else:
        image_paths = sys.argv[1:]
        result = analyze_multiple_images(image_paths)
        print(json.dumps(result, ensure_ascii=False, indent=4))

import cv2
import numpy as np
import json
import sys
import io

# ตั้งค่าการเข้ารหัสให้ stdout เป็น UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DEBUG = True  # ตั้งค่า True เพื่อเปิด Debug Mode

def crop_image_to_grid(img):
    """ครอบตัดภาพให้เหลือเฉพาะพื้นที่ตาราง"""
    # ปรับพิกัด cropping ให้เหมาะสม
    top, bottom, left, right = 80, 20, 165, 20  # ค่าที่ปรับให้ตรงกับตัวอย่าง
    height, width = img.shape[:2]
    cropped_img = img[top:height-bottom, left:width-right]
    
    if cropped_img.size == 0:
        raise ValueError("Cropping area is invalid. Please check your cropping parameters.")
    
    return cropped_img

def set_color_range(color):
    """ตั้งค่า HSV range สำหรับสีเป้าหมาย"""
    if color == "blue":
        return np.array([100, 50, 50]), np.array([140, 255, 255])  # ค่า HSV สำหรับสีน้ำเงิน
    else:
        raise ValueError(f"Unsupported color: {color}")

def preprocess_mask(mask):
    """ลบสัญญาณรบกวนและปรับปรุงความชัดเจนของ Mask"""
    mask = cv2.GaussianBlur(mask, (5, 5), 0)
    _, mask = cv2.threshold(mask, 127, 255, cv2.THRESH_BINARY)
    return mask

def analyze_image(image_path):
    try:
        img = cv2.imread(image_path)
        if img is None:
            return {"error": f"ไม่สามารถอ่านภาพได้จาก path: {image_path}"}
        
        if DEBUG:
            cv2.imshow("Original Image", img)
            cv2.waitKey(1)

        # ครอบตัดภาพให้เหลือเฉพาะพื้นที่ตาราง
        img = crop_image_to_grid(img)

        if DEBUG:
            cv2.imshow("Cropped Image", img)
            cv2.waitKey(1)

        # แปลงภาพเป็น HSV และสร้าง Mask
        hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
        lower_blue, upper_blue = set_color_range("blue")
        mask_blue = cv2.inRange(hsv, lower_blue, upper_blue)

        # ปรับปรุง Mask
        mask_blue = preprocess_mask(mask_blue)

        if DEBUG:
            cv2.imshow("Mask", mask_blue)
            cv2.waitKey(1)

        # แบ่งภาพเป็น Grid
        rows, cols = 7, 10
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

        # ตรวจสอบแต่ละ cell
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

                threshold = 0.15  # ปรับ Threshold ให้เหมาะสม
                result[day][time_slots[j]] = "ว่าง" if ratio > threshold else "ไม่ว่าง"

                # วาดกรอบและใส่ข้อความ
                if DEBUG:
                    cv2.rectangle(img, (x1, y1), (x2, y2), (0, 255, 0), 2)
                    cv2.putText(
                        img,
                        "Free" if ratio > threshold else "Busy",
                        (x1 + 5, y1 + 20),
                        cv2.FONT_HERSHEY_SIMPLEX,
                        0.5,
                        (255, 255, 255),
                        1,
                    )

        if DEBUG:
            cv2.imshow("Grid", img)
            cv2.waitKey(0)
            cv2.destroyAllWindows()

        return result
    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Usage: python analyze_image_with_color.py <image_path>"}, ensure_ascii=False))
    else:
        image_path = sys.argv[1]
        result = analyze_image(image_path)
        print(json.dumps(result, ensure_ascii=False, indent=4))

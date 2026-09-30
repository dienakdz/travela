# Đồng bộ năm của tour

Tài liệu này dùng để đưa toàn bộ `startDate` và `endDate` trong bảng
`tbl_tours` về cùng năm **2028**, đồng thời giữ nguyên ngày và tháng.

> Nên sao lưu database trước khi thực hiện. Không chạy `COMMIT` và
> `ROLLBACK` cùng lúc.

## 1. Kiểm tra dữ liệu hiện tại

```sql
SELECT
    tourId,
    title,
    startDate,
    endDate,
    DATEDIFF(endDate, startDate) AS duration_days
FROM tbl_tours
ORDER BY startDate;
```

## 2. Cập nhật toàn bộ tour về năm 2028

```sql
START TRANSACTION;

UPDATE tbl_tours
SET
    startDate = STR_TO_DATE(
        CONCAT('2028-', DATE_FORMAT(startDate, '%m-%d')),
        '%Y-%m-%d'
    ),
    endDate = STR_TO_DATE(
        CONCAT('2028-', DATE_FORMAT(endDate, '%m-%d')),
        '%Y-%m-%d'
    );

SELECT ROW_COUNT() AS updated_tours;
```

## 3. Kiểm tra kết quả trước khi lưu

```sql
SELECT
    tourId,
    title,
    startDate,
    endDate,
    DATEDIFF(endDate, startDate) AS duration_days
FROM tbl_tours
ORDER BY startDate;
```

Đảm bảo:

- `startDate` và `endDate` đều thuộc năm 2028.
- Ngày và tháng vẫn giữ nguyên.
- `endDate` không nhỏ hơn `startDate`.
- `duration_days` vẫn đúng với thời lượng của tour.

## 4. Xác nhận hoặc hủy thay đổi

Nếu dữ liệu chính xác:

```sql
COMMIT;
```

Nếu phát hiện dữ liệu không chính xác:

```sql
ROLLBACK;
```

Query này chỉ cập nhật `startDate` và `endDate`. Các dữ liệu như `tourId`,
timeline, hình ảnh, booking và trạng thái `availability` không bị thay đổi.

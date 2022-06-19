# 所使用的資料表及內容

以下的表是由 anth2k2k 的圖書館租借系統借來的，再根據我們的需求做調整。

## 讀者

| 欄位名稱 | 屬性 | 大小 | 備註 |
| --- | --- | --- | --- |
| ReaderNumber | integer | 10 | Primary key |
| Name | string | 64 |  |
| Email | string | 100 |  |
| Password | string | 64 |  |

## 管理者

| 欄位名稱 | 屬性 | 大小 | 備註 |
| --- | --- | --- | --- |
| Email | string | 100 | Primary key |
| Password | string | 64 |  |

## 書籍

| 欄位名稱 | 屬性 | 大小 | 備註 |
| --- | --- | --- | --- |
| BookNumber | string | 10 | Primary key |
| Title | string | 100 |  |
| Author | string | 100 |  |
| Publisher | string | 100 |  |

## 書籍副本

| 欄位名稱 | 屬性 | 大小 | 備註 |
| --- | --- | --- | --- |
| CopyNumber | integer | 10 | Primary key |
| BookNumber | integer | 10 | Foreign key, 參照書籍 |
| SequenceNumber | integer | 10 | 每本相同書籍的序列號 |
| Type | integer | 1 | 0: 可借, 1: 已借出 |
| Price | integer | 10 | 允許空值 |

## 租借狀態

| 欄位名稱 | 屬性 | 大小 | 備註 |
| --- | --- | --- | --- |
| ID | integer | 10 | Primary key |
| ReaderNumber | integer | 10 | Foreign key, 參照讀者 |
| CopyNumber | integer | 10 | Foreign key, 參照書籍副本 |
| BorrowDate | date |  | 不允許空值 |
| DueDate | date |  | 允許空值 |
| ReturnDate | date |  | 允許空值 |
| FineAmount | integer | 10 | 允許空值 |

## 預借書籍

| 欄位名稱 | 屬性 | 大小 | 備註 |
| --- | --- | --- | --- |
| ID | integer | 10 | Primary key |
| ReaderNumber | integer | 10 | Foreign key, 參照讀者 |
| BookNumber | integer | 10 | Foreign key, 參照書籍 |
| Date | datetime |  | 不允許空值 |
| Status | integer | 1 | 0:  u3借書 1: 已取書 |


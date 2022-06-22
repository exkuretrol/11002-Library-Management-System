# 所使用的資料表及內容

以下的表是由 [anth2k2k 的圖書館租借系統](https://github.com/anth2k2k/LibraryManagementSystem)的表格，再根據我們的需求做調整。

## 讀者（Reader）

| 欄位名稱     | 屬性    | 大小 | 備註        |
| ------------ | ------- | ---- | ----------- |
| ReaderNumber | integer | 11   | Primary key |
| Email        | varchar | 128  |             |
| Name         | varchar | 45   |             |
| Password     | varchar | 128  |             |
| Gender       | text    |      |             |
| BirthDate    | date    |      |             |
| PhoneNum     | text    |      |             |


## 管理者（Moderator）

| 欄位名稱 | 屬性   | 大小 | 備註        |
| -------- | ------ | ---- | ----------- |
| Email    | string | 100  | Primary key |
| Password | string | 64   |             |

## 書籍（Book）

| 欄位名稱   | 屬性    | 大小 | 備註                        |
| ---------- | ------- | ---- | --------------------------- |
| BookNumber | integer | 11   | Primary key, Auto increment |
| Title      | varchar | 256  |                             |
| Author     | varchar | 256  |                             |
| ImgPath    | text    |      |                             |
| Publisher  | varchar | 256  |                             |

## 書籍副本（Copy）

| 欄位名稱       | 屬性    | 大小 | 備註                            |
| -------------- | ------- | ---- | ------------------------------- |
| CopyNumber     | integer | 10   | Primary key                     |
| BookNumber     | integer | 10   | Foreign key, 參照書籍           |
| SequenceNumber | integer | 10   | 每本相同書籍的序列號            |
| Type           | integer | 1    | 0: 可借, 1: 已借出, 2: 已被預約 |
| Price          | integer | 10   | 允許空值                        |

## 租借狀態（CirculatedCopy）

| 欄位名稱     | 屬性    | 大小 | 備註                      |
| ------------ | ------- | ---- | ------------------------- |
| ID           | integer | 10   | Primary key               |
| ReaderNumber | integer | 10   | Foreign key, 參照讀者     |
| CopyNumber   | integer | 10   | Foreign key, 參照書籍副本 |
| BorrowDate   | date    |      | 不允許空值                |
| DueDate      | date    |      | 允許空值                  |
| ReturnDate   | date    |      | 允許空值                  |
| FineAmount   | integer | 10   | 允許空值                  |

## 預借書籍（Reserved）

| 欄位名稱     | 屬性     | 大小 | 備註                                               |
| ------------ | -------- | ---- | -------------------------------------------------- |
| ID           | integer  | 10   | Primary key                                        |
| ReaderNumber | integer  | 10   | Foreign key, 參照讀者                              |
| BookNumber   | integer  | 10   | Foreign key, 參照書籍                              |
| Date         | datetime |      | 不允許空值                                         |
| Status       | integer  | 1    | 0:  已予約借書, 1: 已準備書籍至流通櫃台, 2: 已取書 |

## 公告 (Post)

| 欄位名稱    | 屬性     | 大小 | 備註                                      |
| ----------- | -------- | ---- | ----------------------------------------- |
| NO          | interger | 11   | Primary Key, Auto increment               |
| Type        | int      | 11   | 0: 最新消息, 1: 系統維護公告, 9: 更新日誌 |
| Content     | longtext |      |                                           |
| PublishDate | date     |      |                                           |
| DueDate     | date     |      |                                           |


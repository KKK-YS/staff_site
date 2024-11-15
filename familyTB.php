<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "115.31.96.4";
$username = "hotel";
$password = "1234";
$dbname = "tncomDB";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 체크
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// familyTB 테이블 생성 SQL
$sql = "CREATE TABLE familyTB (
    number INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    id VARCHAR(30) UNIQUE,
    password VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    regdate DATE,
    job_title ENUM('사원', '대리', '과장', '차장', '부장') DEFAULT '사원',
    address VARCHAR(200) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// 테이블 생성 실행
if ($conn->query($sql) === TRUE) {
    echo "Table familyTB created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// 연결 종료
$conn->close();
?>

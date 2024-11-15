<?php
// 데이터베이스 연결 정보 설정
$servername = "115.31.96.4";  // 서버 주소 (예: 'localhost' 또는 실제 서버 IP)
$username = "hotel";  // 데이터베이스 사용자 이름
$password = "1234";  // 데이터베이스 비밀번호
$dbname = "tncomDB";  // 데이터베이스 이름

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 한글 인코딩 설정 (필요할 경우)
$conn->set_charset("utf8mb4");
?>

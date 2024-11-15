<?php
session_start();

$servername = "115.31.96.4";
$username = "hotel";
$db_password = "1234";
$dbname = "tncomDB";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 입력 데이터 가져오기
    $name = $_POST['name'];
    $id = $_POST['id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $regdate = date('Y-m-d');
    $job_title = '사원'; // 기본값으로 '사원' 설정

    // 데이터베이스 연결
    $conn = new mysqli($servername, $username, $db_password, $dbname);

    // 연결 체크
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 회원가입 SQL 실행
    $sql = "INSERT INTO familyTB (name, id, password, phone, regdate, job_title, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $id, $password, $phone, $regdate, $job_title, $address);

    if ($stmt->execute() === TRUE) {
        // 회원가입 성공 시 회원가입 완료 메시지와 로그인 페이지로 리디렉션
        echo "회원가입이 완료되었습니다.";
        header("Refresh: 2; url=login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // 연결 종료
    $stmt->close();
    $conn->close();
}
?>

<?php
session_start();
include 'db_connection.php'; // 데이터베이스 연결

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $password = $_POST['password'];

    // familyTB에서 사용자 확인
    $query = "SELECT * FROM familyTB WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; // 데이터베이스에 저장된 해싱된 비밀번호

        // 비밀번호 검증
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            header("Location: index.html"); // 로그인 후 메인 페이지로 리디렉션
            exit();
        } else {
            echo "<script>alert('비밀번호가 잘못되었습니다.'); history.back();</script>";
        }
    } else {
        echo "<script>alert('아이디가 존재하지 않습니다.'); history.back();</script>";
    }
}
?>

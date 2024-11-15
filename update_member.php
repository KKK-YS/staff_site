<?php
session_start();
require_once 'db_connection.php';

// 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => '로그인이 필요합니다.']);
    exit();
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// 입력 데이터 유효성 검사
if (!$data['name'] || !$data['phone'] || !$data['address']) {
    echo json_encode(['message' => '모든 필드를 입력하세요.']);
    exit();
}

// 데이터베이스 업데이트 (familyTB 테이블 구조에 맞춰 필드명 수정)
$query = "UPDATE familyTB SET name = ?, phone = ?, address = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $data['name'], $data['phone'], $data['address'], $userId);

if ($stmt->execute()) {
    echo json_encode(['message' => '회원 정보가 성공적으로 업데이트되었습니다.']);
} else {
    echo json_encode(['message' => '업데이트 실패. 다시 시도해 주세요.']);
}
?>

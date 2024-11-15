<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db_connection.php';

// 직원 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.href = 'login.html';</script>";
    exit();
}

// 외부 서버의 tncomDB 데이터베이스에 연결
$memberDbServer = "115.31.96.2";  // 외부 서버 주소 입력
$memberDbUsername = "hotel";  // 외부 서버 사용자명 입력
$memberDbPassword = "1234";  // 외부 서버 비밀번호 입력
$memberDbName = "tnhotelDB";  // 외부 서버의 데이터베이스 이름 입력

$memberDb = new mysqli($memberDbServer, $memberDbUsername, $memberDbPassword, $memberDbName);

// 연결 확인
if ($memberDb->connect_error) {
    die("데이터베이스 연결 실패: " . $memberDb->connect_error);
}

// 모든 고객 정보 가져오기
$query = "SELECT number, name, phone, email, id, regdate, addr, grade FROM memberTB";
$result = $memberDb->query($query);

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

$memberDb->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고객 정보</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #005b96; }
        .customer { background-color: #eef2f7; padding: 10px; margin: 10px 0; border-radius: 8px; text-align: left; }
        .customer p { margin: 5px 0; }
        .button { background-color: #005b96; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .button:hover { background-color: #004a78; }
    </style>
</head>
<body>
    <div class="container">
        <h2>고객 정보</h2>

        <?php if (!empty($customers)): ?>
            <?php foreach ($customers as $customer): ?>
                <div class="customer">
                    <p><strong>회원번호:</strong> <?php echo htmlspecialchars($customer['number']); ?></p>
                    <p><strong>이름:</strong> <?php echo htmlspecialchars($customer['name']); ?></p>
                    <p><strong>전화번호:</strong> <?php echo htmlspecialchars($customer['phone']); ?></p>
                    <p><strong>이메일:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($customer['id']); ?></p>
                    <p><strong>가입일:</strong> <?php echo htmlspecialchars($customer['regdate']); ?></p>
                    <p><strong>주소:</strong> <?php echo htmlspecialchars($customer['addr']); ?></p>
                    <p><strong>회원등급:</strong> <?php echo htmlspecialchars($customer['grade']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>등록된 고객 정보가 없습니다.</p>
        <?php endif; ?>

        <button class="button" onclick="window.location.href='index.html'">홈으로 돌아가기</button>
    </div>
</body>
</html>

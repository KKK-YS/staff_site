<?php
session_start();
require_once 'db_connection.php';

// 직원 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.href = 'login.html';</script>";
    exit();
}

// 외부 서버의 tnhotelDB 데이터베이스에 연결
$hotelDbServer = "115.31.96.2";  // 외부 서버 주소 입력
$hotelDbUsername = "hotel";  // 외부 서버 사용자명 입력
$hotelDbPassword = "1234";  // 외부 서버 비밀번호 입력
$hotelDbName = "tnhotelDB";  // 외부 서버의 데이터베이스 이름 입력

$hotelDb = new mysqli($hotelDbServer, $hotelDbUsername, $hotelDbPassword, $hotelDbName);

// 연결 확인
if ($hotelDb->connect_error) {
    die("데이터베이스 연결 실패: " . $hotelDb->connect_error);
}

// 지점 선택 처리
$selectedBranch = isset($_POST['branch']) ? $_POST['branch'] : '';

// 지점별 예약 정보 가져오기
$query = "SELECT reserv_n, number, name, guest_phone, branch, check_in, check_out, room_id, room_t, guests, total_price, status, reserv_date 
          FROM reservTB";
if ($selectedBranch) {
    $query .= " WHERE branch = ?";
}
$stmt = $hotelDb->prepare($query);
if ($selectedBranch) {
    $stmt->bind_param("s", $selectedBranch);
}
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

$stmt->close();
$hotelDb->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예약 확인</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #005b96; }
        .reservation { background-color: #eef2f7; padding: 10px; margin: 10px 0; border-radius: 8px; }
        .reservation p { margin: 5px 0; }
        .button { background-color: #005b96; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .button:hover { background-color: #004a78; }
        .filter-form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>예약 확인</h2>

        <!-- 지점 선택 폼 -->
        <form method="post" class="filter-form">
            <label for="branch">지점 선택:</label>
            <select name="branch" id="branch">
                <option value="">전체</option>
                <option value="서울" <?php if ($selectedBranch == '서울') echo 'selected'; ?>>서울</option>
                <option value="부산" <?php if ($selectedBranch == '부산') echo 'selected'; ?>>부산</option>
                <option value="강릉" <?php if ($selectedBranch == '강릉') echo 'selected'; ?>>강릉</option>
                <option value="제주" <?php if ($selectedBranch == '제주') echo 'selected'; ?>>제주</option>
            </select>
            <button type="submit" class="button">조회</button>
        </form>

        <?php if (!empty($reservations)): ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation">
                    <p><strong>예약 번호:</strong> <?php echo htmlspecialchars($reservation['reserv_n']); ?></p>
                    <p><strong>고객 이름:</strong> <?php echo htmlspecialchars($reservation['name']); ?></p>
                    <p><strong>고객 전화번호:</strong> <?php echo htmlspecialchars($reservation['guest_phone']); ?></p>
                    <p><strong>체크인:</strong> <?php echo htmlspecialchars($reservation['check_in']); ?></p>
                    <p><strong>체크아웃:</strong> <?php echo htmlspecialchars($reservation['check_out']); ?></p>
                    <p><strong>객실 고유 번호:</strong> <?php echo htmlspecialchars($reservation['room_id']); ?></p>
                    <p><strong>객실 종류:</strong> <?php echo htmlspecialchars($reservation['room_t']); ?></p>
                    <p><strong>인원 수:</strong> <?php echo htmlspecialchars($reservation['guests']); ?>명</p>
                    <p><strong>총 가격:</strong> <?php echo htmlspecialchars($reservation['total_price']); ?>원</p>
                    <p><strong>예약 상태:</strong> <?php echo htmlspecialchars($reservation['status']); ?></p>
                    <p><strong>예약 날짜/시간:</strong> <?php echo htmlspecialchars($reservation['reserv_date']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>선택한 지점에 예약된 내역이 없습니다.</p>
        <?php endif; ?>

        <button class="button" onclick="window.location.href='index.html'">홈으로 돌아가기</button>
    </div>
</body>
</html>

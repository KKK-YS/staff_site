<?php
session_start();
require_once 'db_connection.php';

// 직원 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); window.location.href = 'login.html';</script>";
    exit();
}

// 외부 서버의 tnhotelDB 데이터베이스에 연결
$roomDbServer = "115.31.96.2";  // 외부 서버 주소 입력
$roomDbUsername = "hotel";  // 외부 서버 사용자명 입력
$roomDbPassword = "1234";  // 외부 서버 비밀번호 입력
$roomDbName = "tnhotelDB";  // 외부 서버의 데이터베이스 이름 입력

$roomDb = new mysqli($roomDbServer, $roomDbUsername, $roomDbPassword, $roomDbName);

// 연결 확인
if ($roomDb->connect_error) {
    die("데이터베이스 연결 실패: " . $roomDb->connect_error);
}

// 선택된 지점과 날짜 처리
$selectedBranch = isset($_POST['branch']) ? $_POST['branch'] : '';
$selectedDate = isset($_POST['date']) ? $_POST['date'] : '';

// 지점 및 날짜별 객실 상태 정보 가져오기
$query = "
    SELECT room_id, room_type, total_rooms, available_rooms, room_status, current_pay 
    FROM roomTB
    WHERE branch = ?
";
$stmt = $roomDb->prepare($query);
$stmt->bind_param("s", $selectedBranch);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

$stmt->close();
$roomDb->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>객실 현황</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #005b96; }
        .room { background-color: #eef2f7; padding: 10px; margin: 10px 0; border-radius: 8px; text-align: left; }
        .room p { margin: 5px 0; }
        .button { background-color: #005b96; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .button:hover { background-color: #004a78; }
        .filter-form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>객실 현황</h2>

        <!-- 지점 및 날짜 선택 폼 -->
        <form method="post" class="filter-form">
            <label for="branch">지점 선택:</label>
            <select name="branch" id="branch">
                <option value="">전체</option>
                <option value="서울" <?php if ($selectedBranch == '서울') echo 'selected'; ?>>서울</option>
                <option value="부산" <?php if ($selectedBranch == '부산') echo 'selected'; ?>>부산</option>
                <option value="강릉" <?php if ($selectedBranch == '강릉') echo 'selected'; ?>>강릉</option>
                <option value="제주" <?php if ($selectedBranch == '제주') echo 'selected'; ?>>제주</option>
            </select>

            <label for="date">날짜 선택:</label>
            <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($selectedDate); ?>">

            <button type="submit" class="button">조회</button>
        </form>

        <?php if (!empty($rooms)): ?>
            <?php foreach ($rooms as $room): ?>
                <div class="room">
                    <p><strong>객실 고유 번호:</strong> <?php echo htmlspecialchars($room['room_id']); ?></p>
                    <p><strong>객실 종류:</strong> <?php echo htmlspecialchars($room['room_type']); ?></p>
                    <p><strong>총 객실 수:</strong> <?php echo htmlspecialchars($room['total_rooms']); ?></p>
                    <p><strong>예약 가능 객실 수:</strong> <?php echo htmlspecialchars($room['available_rooms']); ?></p>
                    <p><strong>객실 상태:</strong> <?php echo htmlspecialchars($room['room_status']); ?></p>
                    <p><strong>현재 요금:</strong> <?php echo htmlspecialchars($room['current_pay']); ?>원</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>선택한 조건에 맞는 객실 정보가 없습니다.</p>
        <?php endif; ?>

        <button class="button" onclick="window.location.href='index.html'">홈으로 돌아가기</button>
    </div>
</body>
</html>

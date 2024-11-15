<?php
session_start();
require_once 'db_connection.php';

// 로그인한 사용자의 ID로 회원 정보 가져오기
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // 사용자 정보 조회 (familyTB 테이블 구조에 맞춰 필드명 사용)
    $query = "SELECT name, phone, id, address, job_title FROM familyTB WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('로그인이 필요합니다.'); window.location.href = 'login.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원 정보</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; }
        .container { max-width: 500px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #005b96; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        .button { background-color: #005b96; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .button:hover { background-color: #004a78; }
        #statusMessage { margin: 20px 0; color: green; font-weight: bold; }
        #homeButton { display: none; background-color: #004a78; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>회원 정보 수정</h2>
        <form id="memberForm">
            <label>이름:</label><br>
            <input type="text" id="name" value="<?php echo htmlspecialchars($user['name']); ?>"><br>

            <label>전화번호:</label><br>
            <input type="text" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>

            <label>ID:</label><br>
            <input type="text" id="id" value="<?php echo htmlspecialchars($user['id']); ?>" readonly><br>

            <label>주소:</label><br>
            <input type="text" id="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br>

            <label>직급:</label><br>
            <input type="text" id="job_title" value="<?php echo htmlspecialchars($user['job_title']); ?>" readonly><br>

            <button type="button" class="button" onclick="updateMemberInfo()">수정하기</button>
        </form>
        <p id="statusMessage"></p>
        <a href="index.html" id="homeButton" class="button">홈으로 돌아가기</a>
    </div>

    <script>
        // 회원 정보 업데이트 AJAX 요청
        function updateMemberInfo() {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('address').value;

            fetch('update_member.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, phone, address })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('statusMessage').innerText = data.message;

                if (data.message.includes("성공적으로 업데이트")) {
                    // 수정 성공 시 "홈으로 돌아가기" 버튼 표시
                    document.getElementById('homeButton').style.display = 'inline-block';

                    // 3초 후 홈으로 자동 이동
                    setTimeout(() => {
                        window.location.href = 'index.html';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error("업데이트 실패:", error);
                document.getElementById('statusMessage').innerText = "업데이트 실패!";
            });
        }
    </script>
</body>
</html>

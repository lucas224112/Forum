<?php
    session_name("Forum-SID");
    session_set_cookie_params(31536000);

    include 'bd.php';

    if (isset($_COOKIE[session_name()])) {
        session_start();
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    } else {
        $user = null;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
        $message = $_POST['message'];
        $username = $user ? $user['username'] : 'Anonymous';

        $stmt = $connect->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $message);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['status' => 'success']);
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT * FROM messages ORDER BY timestamp DESC LIMIT 100";
        $result = $connect->query($sql);

        $response = [];
        while ($msg = $result->fetch_assoc()) {
            $self = $msg['username'] === ($user ? $user['username'] : 'Anonymous') ? 'self' : 'other';
            $response[] = [
                'self' => $self,
                'username' => htmlspecialchars($msg['username']),
                'message' => htmlspecialchars($msg['message']),
                'timestamp' => $msg['timestamp']
            ];
        }

        echo json_encode(['messages' => array_reverse($response)]);
        exit;
    }
?>
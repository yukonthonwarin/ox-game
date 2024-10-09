<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            max-width: 600px;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2.5em;
            color: #007bff;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .rules-btn {
            position: relative;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .rules-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding-top: 60px;
            animation: fadeIn 0.5s;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.5s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>ยินดีต้อนรับ</h1>
        @yield('content')

        <button class="rules-btn" onclick="openModal()">กติกาการเล่น</button>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <button class="logout-btn" onclick="confirmLogout()">ออกจากระบบ</button>
    </div>

    <!-- Modal for showing rules -->
    <div id="rulesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>กติกาการเล่น</h2>
            <p>
                1. ผู้เล่นต้องทำการกดเลือกเริ่มใหม่เพื่อเล่น.<br>
                2. หากผู้เล่นได้เรียง 3 ช่องในแนวเดียวกัน จะชนะ.<br>
                3. หากไม่มีช่องให้เลือกและไม่มีผู้ชนะ จะเสมอ.<br>
                4. เมื่อผู้เล่นเอาชนะบอทได้ จะได้รับ 1 คะแนน (ถ้ำแพ้จะเสีย 1 คะแนน).<br>
                5. ถ้าผู้เล่นเอาชนะบอทได้ 3 ครั้งติดต่อกันจะได้รับคะแนนพิเศษเพิ่มอีก 1 คะแนน และการนับจำนวนครั้งที่ชนะติดต่อกันจะถูกนับใหม่
            </p>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function clearCookies() {
            const cookies = document.cookie.split("; ");
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i];
                const eqPos = cookie.indexOf("=");
                const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;

                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
            }
        }

        function confirmLogout() {
            if (confirm("คุณแน่ใจว่าต้องการออกจากระบบ?")) {
                clearCookies();
                event.preventDefault();
                document.getElementById('logout-form').submit();
            }
        }

        // Modal functions
        function openModal() {
            document.getElementById('rulesModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('rulesModal').style.display = "none";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('rulesModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .email-container {
            max-width: 520px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            padding: 25px;
            border: 1px solid #ddd;
        }

        .header {
            background: linear-gradient(135deg, #007bff, #6610f2);
            padding: 20px;
            color: white;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header span {
            margin-left: 10px;
        }

        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .btn-reset {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white !important;
            text-decoration: none;
            font-size: 16px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 20px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #0056b3, #520dc2);
        }

        .footer {
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>

<body>

    <div class="email-container">
        <div class="header">
            🔐 <span>Đặt lại mật khẩu</span>
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>
            <p>Vui lòng nhấn vào nút bên dưới để đặt lại mật khẩu:</p>

            <a href="{{ $frontend_url }}" class="btn-reset">🔑 Đặt lại mật khẩu</a>

            <p class="footer">
                Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.<br>
                <strong>Trân trọng,</strong><br>
                Đội ngũ hỗ trợ 🚀
            </p>
        </div>
    </div>

</body>

</html>
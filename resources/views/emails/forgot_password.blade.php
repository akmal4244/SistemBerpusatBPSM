<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Override Bootstrap's default styles for email */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .email-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-header {
            /* background-color:rgba(58, 126, 189, 0.99); */
            color: #000000;
            padding: 20px;
            text-align: center;

        }

        .email-header,
        h5 {
            font-size: 16px;
        }

        .email-body {
            padding: 20px;
            color: #333333;
        }

        .email-footer {
            /* background-color:rgba(123, 169, 211, 0.99); */
            padding: 10px;
            text-align: center;
            font-size: 12px;
            /* color: #777777; */
        }

        a {
            color:rgb(18, 31, 219);
            text-decoration: none;
        }

        .btn{
            display: inline-block; 
                  padding: 12px 24px; 
                  font-size: 16px; 
                  color: #ffffff; 
                  background-color: #007bff; 
                  text-decoration: none; 
                  border-radius: 5px;"
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="email-header">
            <center>
                <img src="https://www.moe.gov.my/storage/files/shares/images/logo/logo-korporat-kpm-bm.jpg" width="15%" class="mx-4">
                <br><br>
                <h5><b>SISTEM PENGURUSAN BPSM</b></h5>
            </center>
        </div>
        <hr>
        <!-- Email Body -->
        <div class="email-body">
            <p>Assalamualaikum dan Salam Sejahtera,
            <br><br>
                Tuan/Puan,
            <br><br>
            Anda telah meminta untuk menetapkan semula kata laluan anda. Sila klik pautan di bawah untuk menetapkan semula kata laluan anda:
        </p>
        <p>
            <a href="{{ url('/password-reset?token=' . $content['token']) }}">
                <button type="button" class="btn btn-primary">
                    Tetapkan Semula Kata Laluan
                </button>
                </a>
        </p>
        <p>Jika anda tidak meminta ini, sila abaikan emel ini.</p>
            
        </div>
        <hr>
        <!-- Email Footer -->
        <div class="email-footer">
            <p>© 2024 BPSM - Kementerian Pendidikan Malaysia.</p>
        </div>
    </div>
</body>

</html>
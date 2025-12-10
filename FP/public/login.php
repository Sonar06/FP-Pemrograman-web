<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LOGIN </title>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(150deg, #1b1a1a, #6c0a0a,  #21202036);
    }

    .container {
        width: 100%;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        background: #f5f5f536;
        width: 420px;
        padding: 20px 40px;
        border-radius: 15px;
        border: none;;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(22px);
        text-align: center;
    }

    .title {
        color: #ffffff;
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .label {
        display: block;
        color: white;
        font-size: 20px;
        font-weight: 700;
        text-align: left;
    }

    input {
        width: 94%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
        background: #e39595;
        font-size: 14px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    input::placeholder {
        color: #ffffff;
    }

    .join-text {
        display: flex;
        align-items: center;
        gap: 3px; /* jarak antar teks */
        justify-content: flex-end; /* biar tetap di kanan seperti sebelumnya */
        margin-top: -10px;
    }

    .join-text .forgot {
        margin: 0;
        padding-top: 3px;
        font-size: 12px;
        color: #600303;
        text-decoration: none;
    }

    .join-text .text {
        margin: 0;
        font-size: 12px;
        color: #600303;
    }

    .btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: none;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        background: #cf1515;
        color: rgb(255, 255, 255);
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .btn:hover {
        background: #97101079;
    }
</style>
</head>

<body>

<div class="container">
    <div class="card">
        <h1 class="title">LOGIN</h1>
        <form>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>       
            <a href="homePage.html"><button type="submit"class="btn">Login</button></a>
            <div class="join-text">
                <p class="text">Dont have</p>
                <a href="daftar.html" class="forgot">account?</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

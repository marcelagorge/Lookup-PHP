<?php
header("Content-type: text/css; charset: UTF-8");
?>

body {
    background-color: #1C1C1C;
    font-family: 'Roboto Condensed', sans-serif;
    color: white;
}

.btn-custom {
    background-color: #4B0082;
    color: white;
}

.btn-custom:hover {
    background-color: #37006a;
}

.container {
    margin-top: 20px;
}

.post, .comment {
    background-color: #333333;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

textarea, input[type="text"], input[type="password"] {
    background-color: #333333;
    color: white;
    border: 1px solid #4B0082;
}

textarea::placeholder, input::placeholder {
    color: #bbb;
}

a {
    color: #4B0082;
}

a:hover {
    color: #37006a;
}

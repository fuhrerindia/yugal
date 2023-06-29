<?php
$yugal->header(<<<HTML
    
HTML);
return <<<HTML
    <body>
    <div id="message">Hello, World!</div>
    <div id="message">
        <p>
            This is initial Yugal Project, start editing src/index.php
        </p>
    </div>
    </body>
    <head>
    <title>Hello, World!</title>
    </head>
    <script use="didmount">
        console.log('HELLO WORLD');
    </script>
    <style use="onpage">
        #message {
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        background:#404040;
        color:#fff;
        border-radius:10px;
        padding: 20px 0px 20px 0px;
        width: 500px;
        margin:10px;
        }
        #message p{
            font-weight:normal;
            font-size:18px;
        }
        #yugal-root{
            background:#000;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
        }
    </style>
HTML;
?>
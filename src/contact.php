<?php
return <<<HTML
    <body>
        [{{header}}]
        <h1>CONTACT</h1>
        <p>
            Excepteur aliquip ut incididunt do laboris. Id ad ad aute dolore veniam fugiat reprehenderit velit sint excepteur. Sit eu quis magna cupidatat in aute eiusmod ex. Veniam culpa consequat eu nulla incididunt incididunt. Lorem eiusmod anim aliquip officia. Elit elit quis sint incididunt eiusmod dolore adipisicing in.
        </p>
        <p>
            Consectetur in sunt dolore ad qui deserunt ad officia. Dolore amet nulla elit duis Lorem exercitation Lorem sint et magna laboris quis et dolor. Ipsum consectetur qui duis officia nisi do dolore non dolor est laboris ipsum quis fugiat.
        </p>
        <h2>Hi! <span id="namebox"></span></h2>
        <form>
            <input type="text" placeholder="Name" id="nameinput" />
            <input type="email" placeholder="E-Mail" />
            <input type="submit" />
        </form>
    </body>
    
    <script use="didmount">
        yugal.$("#nameinput").addEventListener("keyup", (e)=>{
            yugal.$("#namebox").innerHTML = e.target.value;
        });
    </script>

    <style use="external">
        form *{
            display: flex;
            flex-direction:column;
            padding: 5px 10px;
            margin:15px;
        }
        h2{
            margin:15px;
        }
    </style>

    
    <head>
        <title>
            Contact
        </title>
    </head>
    <fallback>[{{fallback}}]</fallback>


    
HTML;
?>
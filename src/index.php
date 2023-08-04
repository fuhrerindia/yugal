<?php
$yugal->libary('yugalmods');
$yugal->style(<<<CSS
header{
    width:100%;
    background:green
}
header ul{
    display: flex;
    list-style:none;
}
header ul li{
    margin:20px;
}
header a{
    text-decoration:none;
    color:#ffffff;
}
p, h1{
    margin:20px;
    line-height:26px;
}
body{
    background:#000;
    color:#fff;
}
CSS);
return <<<HTML
<body>
    [{{header}}]
    <h1>HOME</h1>
    <p>
        Sit labore laboris aute amet tempor dolor incididunt. Consectetur exercitation anim consectetur est qui. Incididunt ullamco non tempor proident veniam officia nisi duis. In voluptate consequat reprehenderit sint adipisicing sunt qui qui do ipsum tempor commodo aliquip mollit. Id aliqua adipisicing elit tempor elit culpa aliquip exercitation.
    </p>
    <p>
        Quis ut consectetur elit nulla ea veniam. Irure irure non quis culpa consequat duis amet reprehenderit ex. Exercitation ex adipisicing do eu. Excepteur cillum sunt veniam dolore fugiat incididunt veniam eiusmod veniam.
    </p>
    <p>
        Eiusmod proident consectetur mollit reprehenderit et culpa ad reprehenderit non consectetur est excepteur. Quis ipsum eiusmod qui fugiat culpa voluptate in incididunt. Aliquip aliquip ut exercitation officia. Eiusmod ut et aute magna Lorem non sunt adipisicing esse quis. Non duis minim nisi Lorem pariatur. Eiusmod nisi Lorem eiusmod adipisicing non irure sunt adipisicing ut.
    </p>
    <p>
        Pariatur consectetur aute reprehenderit quis sunt deserunt nulla elit do eu irure consequat. Eiusmod do ut deserunt amet veniam magna culpa tempor incididunt deserunt ut tempor consectetur. Nulla cillum excepteur sit sunt reprehenderit voluptate voluptate occaecat. Mollit et elit labore eu Lorem enim esse Lorem ipsum laboris enim esse dolore irure.
    </p>
</body>
<head>
    <title>
    Home - Yugal
    </title>
    <meta name="description" content="yugal" />
</head>
<fallback>
    [{{fallback}}]
</fallback>
HTML;
?>
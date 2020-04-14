<?php
/** Load an array from a cookie and clear the cookie. */
function loadAndClearArrayFromCookie($name)
{
    if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])) {
        $array = json_decode($_COOKIE[$name], true);
        // Reset the cookies by expiring them.
        setcookie($name, "", time() - 1000, "/");
        return $array;
    }
    // Return an empty array if cookie not found.
    return [];
}

$formErrors = loadAndClearArrayFromCookie("formErrors");
$lastInput = loadAndClearArrayFromCookie("lastInput");

?>
<html>
<head>
    <style>
        body {
            height: 100vh;
            background-color: #ff8000;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label, input, span.error, form > button {
            display: block;
            font-family: "Helvetica";
        }
        label, input, form > button {
            color: #773d04;
        }
        input {
            padding: 0.3em 1em;
            margin: 12px 0;
        }
        span.error {
            font-size: 0.85em;
            color: #e42512;
            text-shadow: 1px 1px #ff6f6f;
        }
        input, span.error {
            margin-left: 10px;
        }
        form {
            margin-top: 100px;
            width: 300px;
            padding: 50px;
            /* see: https://neumorphism.io/#ff8000 */
            border-radius: 50px;
            background: #ff8000;
            box-shadow: inset 20px 20px 16px #de6f00, 
            inset -20px -20px 16px #ff9100;
        }
        form > div {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 28px;
            background: linear-gradient(145deg, #ff8900, #e67300);
            box-shadow:  13px 13px 21px #de6f00, 
             -13px -13px 21px #ff9100;
        }
        form > button {
            margin-top: 50px;
            border:none;
            padding: 10px;
            width: 75px;
            border-radius: 62px;
            background: linear-gradient(145deg, #e67300, #ff8900);
            box-shadow:  7px 7px 14px #d96d00, 
             -7px -7px 14px #ff9300;
        }
        form > button:hover {
            border-radius: 62px;
            background: linear-gradient(145deg, #e67300, #ff8900);
            box-shadow:  7px 7px 14px #d96d00, 
             -7px -7px 14px #ff9300;
        }
        form > button:active {
            border-radius: 62px;
            background: #ff8000;
            box-shadow: inset 13px 13px 26px #e87400, 
            inset -13px -13px 26px #ff8c00;
        }

        input {
            border: none;
            border-radius: 62px;
            background: #ff8000;
            box-shadow: inset 13px 13px 26px #e87400, 
            inset -13px -13px 26px #ff8c00;
        }
    </style>
</head>
<body><form action="process_my_form.php" method="post">
    <div> <!--  First name field -->
        <label>First Name</label>
        <input name="first_name" value="<?=$lastInput["first_name"] ?? "";?>">
        <span class="error"><?=$formErrors["first_name"] ?? "";?></span>
    </div>
    <div> <!--  Last name field -->
        <label>Last Name</label>
        <input name="last_name" value="<?=$lastInput["last_name"] ?? "";?>">
        <span class="error"><?=$formErrors["last_name"] ?? "";?></span>
    </div>
    <button>Submit</button>
</form></body></html>

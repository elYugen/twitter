<?php
require_once '../app/routes.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello API Platform</title>
</head>
<body>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f3f3f3;
    color: #333;
}

header {
    background-color: #4cafb4;
    padding: 20px;
    text-align: center;
    color: white;
}

h1 {
    font-size: 2em;
    margin-bottom: 0.5em;
}

.version, .oas {
    background-color: #eee;
    color: #333;
    padding: 5px 10px;
    margin-left: 10px;
    border-radius: 5px;
    font-size: 0.8em;
}

.oas {
    background-color: #92c93d;
    color: white;
}

.container {
    padding: 20px;
    max-width: 900px;
    margin: 20px auto;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.server-select {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

label {
    font-size: 1.2em;
}

select {
    padding: 8px;
    font-size: 1em;
}

.authorize {
    background-color: #4caf50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1em;
    cursor: pointer;
}

.authorize:hover {
    background-color: #45a049;
}

.greeting-section {
    margin-top: 20px;
}

h2 {
    font-size: 1.5em;
    margin-bottom: 15px;
}

.api-list {
    list-style: none;
}

.api-item {
    padding: 10px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.api-item span.method {
    font-weight: bold;
    padding-right: 15px;
}

.api-item.get { background-color: #dff0d8; }
.api-item.put { background-color: #fcf8e3; }
.api-item.delete { background-color: #f2dede; }
.api-item.patch { background-color: #d9edf7; }
.api-item.post { background-color: #dff0d8; }

    </style>
    <header>
        <h1>Hello API Platform <span class="version">1.0.0</span><span class="oas">OAS3</span></h1>
    </header>
    
    <div class="container">
        <div class="server-select">
            <label for="servers">Servers</label>
            <select id="servers">
                <option>/</option>
            </select>
            <button class="authorize">Authorize</button>
        </div>

        <section class="greeting-section">
            <h2>Greeting</h2>
            <ul class="api-list">
                <li class="api-item get">
                    <span class="method">GET</span> /greetings/{id} <span class="description">Retrieves a Greeting resource.</span>
                </li>
                <li class="api-item put">
                    <span class="method">PUT</span> /greetings/{id} <span class="description">Replaces the Greeting resource.</span>
                </li>
                <li class="api-item delete">
                    <span class="method">DELETE</span> /greetings/{id} <span class="description">Removes the Greeting resource.</span>
                </li>
                <li class="api-item patch">
                    <span class="method">PATCH</span> /greetings/{id} <span class="description">Updates the Greeting resource.</span>
                </li>
                <li class="api-item get">
                    <span class="method">GET</span> /greetings <span class="description">Retrieves the collection of Greeting resources.</span>
                </li>
                <li class="api-item post">
                    <span class="method">POST</span> /greetings <span class="description">Creates a Greeting resource.</span>
                </li>
            </ul>
        </section>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 600px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <?php
    $nameError = $emailError = $passwordError = $ageError = "";
    $name = $email = $password = $age = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Name validation
        if (empty($_POST["name"]) || strlen($_POST["name"]) < 3 || !preg_match("/^[a-zA-Z]+$/", $_POST["name"])) {
            $nameError = "Name must be at least 3 characters and contain only letters.";
        } else {
            $name = htmlspecialchars($_POST["name"]);
        }

        // Email validation
        if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || !str_ends_with($_POST["email"], ".com")) {
            $emailError = "Enter a valid email ending with '.com'.";
        } else {
            $email = htmlspecialchars($_POST["email"]);
        }

        // Password validation
        if (empty($_POST["password"]) || strlen($_POST["password"]) < 8 || !preg_match("/\d/", $_POST["password"])) {
            $passwordError = "Password must be at least 8 characters and include a number.";
        } else {
            $password = htmlspecialchars($_POST["password"]);
        }

        // Age validation
        if (empty($_POST["age"]) || $_POST["age"] < 18 || $_POST["age"] > 99) {
            $ageError = "Age must be between 18 and 99.";
        } else {
            $age = htmlspecialchars($_POST["age"]);
        }

        // If no errors, display success message
        if (empty($nameError) && empty($emailError) && empty($passwordError) && empty($ageError)) {
            echo "<p style='color:green;'>Form submitted successfully!</p>";
        }
    }
    ?>

    <form id="signupForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name (at least 3 characters, letters only):</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>">
        <small class="error"><?php echo $nameError; ?></small>

        <label for="email">Email (valid email format, ends with ".com"):</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
        <small class="error"><?php echo $emailError; ?></small>

        <label for="password">Password (at least 8 characters, includes a number):</label>
        <input type="password" id="password" name="password">
        <small class="error"><?php echo $passwordError; ?></small>

        <label for="age">Age (must be a number between 18 and 99):</label>
        <input type="number" id="age" name="age" value="<?php echo $age; ?>">
        <small class="error"><?php echo $ageError; ?></small>

        <button type="submit">Sign Up</button>
    </form>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            let isValid = true;

            // Name validation
            const nameInput = document.getElementById("name");
            const nameError = document.querySelector('label[for="name"] + small');
            if (nameInput.value.length < 3 || !/^[a-zA-Z]+$/.test(nameInput.value)) {
                nameError.textContent = "Name must be at least 3 characters and contain only letters.";
                isValid = false;
            } else {
                nameError.textContent = "";
            }

            // Email validation
            const emailInput = document.getElementById("email");
            const emailError = document.querySelector('label[for="email"] + small');
            if (!/^[\w-.]+@([\w-]+\.)+[\w-]{2,4}$/.test(emailInput.value) || !emailInput.value.endsWith(".com")) {
                emailError.textContent = "Enter a valid email ending with '.com'.";
                isValid = false;
            } else {
                emailError.textContent = "";
            }

            // Password validation
            const passwordInput = document.getElementById("password");
            const passwordError = document.querySelector('label[for="password"] + small');
            if (passwordInput.value.length < 8 || !/\d/.test(passwordInput.value)) {
                passwordError.textContent = "Password must be at least 8 characters and include a number.";
                isValid = false;
            } else {
                passwordError.textContent = "";
            }

            // Age validation
            const ageInput = document.getElementById("age");
            const ageError = document.querySelector('label[for="age"] + small');
            if (ageInput.value < 18 || ageInput.value > 99) {
                ageError.textContent = "Age must be between 18 and 99.";
                isValid = false;
            } else {
                ageError.textContent = "";
            }

            // Prevent form submission if any validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

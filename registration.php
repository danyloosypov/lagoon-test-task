<?php

if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['password'], $_POST['confirmPassword'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = [
            'success' => false,
            'error' => [
                'status' => 400,
                'code' => 'INVALID_EMAIL',
                'message' => 'Введіть правильний email'
            ]
        ];
        echo json_encode($response);
        exit;
    }

    if ($_POST['password'] !== $_POST['confirmPassword']) {
        $response = [
            'success' => false,
            'error' => [
                'status' => 400,
                'code' => 'PASSWORD_MISMATCH',
                'message' => 'Паролі не співпадають'
            ]
        ];
        echo json_encode($response);
        exit;
    }

    $existingUsers = [
        ['id' => 1, 'email' => 'danylo.osypov@nure.ua', 'name' => 'qwqw', 'password' => 'qwertyui'],
        ['id' => 2, 'email' => 'qwerty@ukr.net', 'name' => 'qweq', 'password' => 'qwertyui']
    ];

    foreach ($existingUsers as $user) {
        if ($user['email'] === $email) {
            $response = [
                'success' => false,
                'error' => [
                    'status' => 400,
                    'code' => 'EMAIL_ALREADY_EXISTS',
                    'message' => 'Користувач з таким email вже існує'
                ]
            ];
            echo json_encode($response);
            exit;
        }
    }

    $newUser = [
        'email' => $email,
        'id' => count($existingUsers) + 1,
        'name' => $name . ' ' . $surname,
        'password' => $password
    ];
    $existingUsers[] = $newUser;

    $logMessage = 'Новий користувач зареєстрований:' . PHP_EOL;
    $logMessage .= 'Ім\'я: ' . $name . PHP_EOL;
    $logMessage .= 'Прізвище: ' . $surname . PHP_EOL;
    $logMessage .= 'Email: ' . $email . PHP_EOL;
    $logMessage .= 'Пароль: ' . $password . PHP_EOL;
    $logMessage .= '-------------------------' . PHP_EOL;
    file_put_contents('registration.log', $logMessage, FILE_APPEND);

    $response = [
        'success' => true,
        'message' => 'Ви успішно зареєстровані!'
    ];
    echo json_encode($response);
} else {
    $response = [
        'success' => false,
        'error' => [
            'status' => 400,
            'code' => 'MISSING_DATA',
            'message' => 'Необхідні дані не були отримані'
        ]
    ];
    echo json_encode($response);
}

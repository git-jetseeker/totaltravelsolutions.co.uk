<?php

return [
    'lounge_close' => filter_var(env('LOUNGE_CLOSE', false), FILTER_VALIDATE_BOOLEAN),
    'hotel_close' => filter_var(env('HOTEL_CLOSE', false), FILTER_VALIDATE_BOOLEAN),
    'car_hire_close' => filter_var(env('CAR_HIRE_CLOSE', false), FILTER_VALIDATE_BOOLEAN),
];

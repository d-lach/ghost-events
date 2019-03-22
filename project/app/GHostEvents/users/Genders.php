<?php

namespace App\User;

interface Genders
{
    const Male = 'male';
    const Female = 'female';
    const Another = 'another';

    const all = [
        self::Male,
        self::Female,
        self::Another,
    ];
}

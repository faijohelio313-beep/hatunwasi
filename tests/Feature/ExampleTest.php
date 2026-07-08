<?php

test('la página principal redirige a la tienda', function () {
    $this->get(route('home'))
        ->assertRedirect(route('store'));
});

test('la tienda pública responde correctamente', function () {
    $this->get(route('store'))
        ->assertOk();
});

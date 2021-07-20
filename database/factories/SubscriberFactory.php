<?php

namespace BinomeWay\NovaContactTool\Database\Factories;

use BinomeWay\NovaContactTool\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;


class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    public function definition()
    {
        return [
            'email' => $this->faker->companyEmail,
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'ip' => $this->faker->ipv4,
            'agent' => $this->faker->userAgent,
        ];
    }
}


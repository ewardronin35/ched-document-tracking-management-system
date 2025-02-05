<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Outgoing;

class OutgoingFactory extends Factory
{
    protected $model = Outgoing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = ['Pending', 'In Progress', 'Completed', 'Rejected'];

        return [
            'control_no' => strtoupper($this->faker->unique()->bothify('CTRL-####')),
            'date_released' => $this->faker->date(),
            'category' => $this->faker->randomElement(['Category A', 'Category B', 'Category C']),
            'addressed_to' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'subject_of_letter' => $this->faker->sentence(6),
            'remarks' => $this->faker->optional()->sentence(),
            'libcap_no' => $this->faker->optional()->bothify('LIBCAP-#####'),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}

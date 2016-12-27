<?php


use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    /**
     * Specifies how many times "repeat" method is called.
     *
     * @var int
     */
    protected $count = 0;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = null;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = null;

    /**
     * Run the database seeds.
     *
     * @throws Exception
     */
    public function run()
    {
        $faker = null;
        if(class_exists('\Faker\Factory')) {
            $faker = Faker\Factory::create();
        }

        // Check if class is extended properly
        if($this->model == null && $this->table == null) {
            throw new LogicException('Class extending BaseSeeder must have "protected $model" property.');
        }

        // Generate static and random models
        $records = $this->always();
        $records = isset($records[0]) || empty($records) ? $records : [$records]; // Enable returning row or array of rows

        for($i = 0; $i < $this->count; ++$i) {
            $new_records = $this->repeat($faker, $i);
            $new_records = isset($new_records[0]) ? $new_records : [$new_records]; // Enable returning row or array of rows

            $records = array_merge($records, $new_records);
        }

        // Fill missing timestamps in generated records
        for ($i = 0; $i < count($records); ++$i) {
            if ($this->model != null
                && with(new $this->model)->timestamps
                && !isset($records[$i]['created_at'])
                && !isset($records[$i]['updated_at'])
            ) {
                $date = $faker->dateTimeBetween('-1 month', 'now');
                $records[$i]['created_at'] = $date;
                $records[$i]['updated_at'] = $date;
            }
        }

        // Save records in database
        if($this->model != null) {
            call_user_func([$this->model, 'insert'], $records);
        }
        else {
            DB::table($this->table)->insert($records);
        }
    }

    /**
     * Get static model record(s).
     *
     * @return array
     */
    protected function always() {
        return [];
    }

    /**
     * Generate random model record(s).
     *
     * @param \Faker\Generator $faker
     * @return array
     */
    protected function repeat(\Faker\Generator $faker, $i) {
        return [];
    }
}
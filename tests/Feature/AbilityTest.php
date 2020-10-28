<?php

namespace Tests\Feature;

use AbilitiesTableSeeder;
use Illuminate\Support\Facades\Log;
use Savitar\Auth\Database\seeders\SavitarUsersTableSeeder;
use Savitar\Auth\SavitarAbility;
use Savitar\Models\Tests\SavitarTestBasic;

class AbilityTest extends SavitarTestBasic
{
    protected $modelClass = SavitarAbility::class;
    protected $baseRoute = 'abilities';
    protected $defaultOrderBy = 'category';

    /**
     * Run the specified seeders for the tests.
     */
    protected function runSeedersOnce(): void
    {
        $this->seed(AbilitiesTableSeeder::class);
        $this->seed(SavitarUsersTableSeeder::class);
    }

    /**
     * Listable (index) test.
     */
    public function testIndex(): void
    {
        Log::info($this->modelClass . ' TEST | Index');
        $models = $this->modelInstance::query()->orderBy($this->defaultOrderBy)->get();
        $this->get(route($this->baseRoute . '.index', $this->routeParameters))
            ->assertStatus(200)
            ->assertJson($models->toArray())
            ->assertJsonStructure([
                '*' => array_diff(array_keys($this->modelInstance->parsedModel->toArray()), $this->excludedFields),
            ]);
    }

    /**
     * Show test.
     */
    public function testShow(): void
    {
        Log::info($this->modelClass . ' TEST | Show');
        $ability = SavitarAbility::first();
        $this->get(route($this->baseRoute . '.show', array_merge($this->routeParameters, [$ability->id])))
            ->assertStatus(200);
    }
}

<?php

// tests/MilkProductionTest.php

use App\Models\Location;
use App\Models\MilkProduction;
use App\Models\MilkProductionCategory;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('milk production initial page should be empty', function () {
    $this->get(route('productions.index'))
        ->assertSuccessful();
});

test('milk production store form should work as successful', function () {
    $category = MilkProductionCategory::factory()->create([
        'name' => 'production',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $category_id = $category->id;

    $milkSell = [
        'category_id' => $category_id,
        'quantity' => 1234,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
        'sell_price' => 100,
        'location_id' => $location->id,

    ];

    $this->post(route('productions.store'), $milkSell)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('productions.store'));

    $this->assertDatabaseHas('milk_productions', $milkSell);

    $latestMilkProduction = MilkProduction::latest()->first();
    $this->assertEquals($latestMilkProduction->category_id, $category_id);
});

test('milk production store should fail with validation error', function () {
    $this->post(route('productions.store'), [
        'quantity' => null,
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors('quantity')
        // or as same
        ->assertInvalid('quantity');
});

test('milk production list should have item', function () {

    $category = MilkProductionCategory::factory()->create([
        'name' => 'production',
    ]);

    $locationsAll = [];
    $productions = [
        'quantity' => 12345,
        'date' => '2024-03-17 00:00:00',
        'category_id' => $category->id,
    ];
    MilkProduction::factory()->create($productions);

    $this->get(route('productions.index'))
        ->assertSuccessful()
        ->assertSee($productions['quantity'])
        ->assertSee(dateFormat($productions['date'], 'd M, Y'))
        ->assertViewHas(['productions', 'locations'], function ($collection) use ($productions, $locationsAll) {
            return $collection->contains([$productions, $locationsAll]);
        });
});

test('milk production-pagination: 1st created item should not show on index page', function () {
    $categories = MilkProduction::factory(20)->create();
    $firstProduction = $categories->first();
    $locationsAll = [];
    $this->get(route('productions.index'))
        ->assertSuccessful()
        ->assertViewHas(['productions', 'locations'], function ($collection) use ($firstProduction, $locationsAll) {
            return $collection->contains([$firstProduction, $locationsAll]);
        });
});

test('milk production edit should show same all value', function () {

    $category = MilkProductionCategory::factory()->create([
        'name' => 'production',
    ]);
    $category_id = $category->id;
    $locationsAll = Location::all();
    $production = MilkProduction::factory()->create([
        'quantity' => 12345,
        'date' => '2024-03-17 00:00:00',
        'category_id' => $category->id,
        'comments' => 'comments',
    ]);

    $this->get(route('productions.show', $production->id))
        ->assertSuccessful()
        ->assertSee('value="'.dateFormat($production->date).'"', false)
        ->assertSee('value="'.$production->quantity.'"', false)
        ->assertSee('value="'.$production->comments.'"', false)
        ->assertViewHas(['production', 'locations'], [$production, $locationsAll]);

    $this->assertDatabaseHas('milk_productions', ['category_id' => $category_id])
        ->assertEquals($production->category_id, $category_id);
});

test('milk production update should change name', function () {
    $category = MilkProductionCategory::factory()->create([
        'name' => 'production',
    ]);
    $category_id = $category->id;
    $locationsAll = Location::all();
    $production = MilkProduction::factory()->create([
        'category_id' => $category->id,
        'quantity' => 12345,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $category_id = $category->id;
    $quantity = 1234;
    $date = '2024-03-16 00:00:00';
    $comments = 'comments';

    // update
    $production->category_id = $category_id;
    $production->quantity = $quantity;
    $production->date = $date;
    $production->comments = $comments;
    $production->save();

    $this->get(route('productions.show', $production->id))
        ->assertSuccessful()
        ->assertSee('value="'.dateFormat($date).'"', false)
        ->assertSee('value="'.$category_id.'"', false)
        ->assertSee('value="'.$quantity.'"', false)
        ->assertSee('value="'.$comments.'"', false)
        ->assertViewHas(['production', 'locations'], [$production, $locationsAll]);

    $this->assertEquals($production->category_id, $category_id);
});

test('milk production update should fail with validation error', function () {
    $production = MilkProduction::factory()->create([
        'quantity' => 1234,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $this->put(route('productions.update', $production->id), [
        'quantity' => '',
    ])
        ->assertStatus(302)
        ->assertSessionHasErrors('quantity')
        // or as same
        ->assertInvalid('quantity');
});

test('milk production delete should remove item successfully', function () {
    $production = MilkProduction::factory()->create([
        'quantity' => 12345,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    // delete
    $production->delete();

    $this->assertNull(MilkProduction::find($production->id));
    $this->assertDatabaseMissing('milk_productions', $production->toArray());
    $this->assertDatabaseCount('milk_productions', 0);
});

test('milk production delete should remove item and redirect to production index', function () {
    $production = MilkProduction::factory()->create([
        'quantity' => 1234,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $this->delete(route('productions.destroy', $production->id))
        ->assertStatus(302)
        ->assertRedirect(route('productions.index'));

    $this->assertNull(MilkProduction::find($production->id));
    $this->assertDatabaseMissing('milk_productions', $production->toArray());
    $this->assertDatabaseCount('milk_productions', 0);
});

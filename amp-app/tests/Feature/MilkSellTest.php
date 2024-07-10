<?php

// tests/MilkSellTest.php

use App\Models\Location;
use App\Models\MilkProduction;
use App\Models\MilkProductionCategory;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('milk sell initial page should be empty', function () {
    $this->get(route('sells.index'))
        ->assertSuccessful();
});

test('milk sell store form should work as successful', function () {

    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $category_id = $category->id;

    $milkSell = [
        'category_id' => $category_id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ];

    $this->post(route('sells.store'), $milkSell)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('sells.store'));

    $this->assertDatabaseHas('milk_productions', $milkSell);

    $latestMilkProduction = MilkProduction::latest()->first();
    $this->assertEquals($latestMilkProduction->category_id, $category_id);
});

test('milk sell store should fail with validation error', function () {
    $this->post(route('sells.store'), [
        'quantity' => null,
        'sell_price' => null,
        'location_id' => null,
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors(['quantity', 'sell_price', 'location_id'])
        // or as same
        ->assertInvalid(['quantity', 'sell_price', 'location_id']);
});

test('milk sell list should have item', function () {

    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $location_id = $location->id;
    $location_name = $location->name;
    $locationsAll = Location::all();
    $sells = [
        'category_id' => $category->id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ];
    MilkProduction::factory()->create($sells);

    $this->get(route('sells.index'))
        ->assertSuccessful()
        ->assertSee($location_name)
        ->assertViewHas(['sells', 'locations'], function ($collection) use ($sells, $locationsAll) {
            return $collection->contains([$sells, $locationsAll]);
        });
});

test('milk sell-pagination: 1st created item should not show on index page', function () {
    $categories = MilkProduction::factory(20)->create();
    $firstProduction = $categories->first();
    $locationsAll = [];
    $this->get(route('sells.index'))
        ->assertSuccessful()
        ->assertViewHas(['sells', 'locations'], function ($collection) use ($firstProduction, $locationsAll) {
            return $collection->contains([$firstProduction, $locationsAll]);
        });
});

test('milk sell edit should show same all value', function () {

    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $category_id = $category->id;
    $locationsAll = Location::all();
    $sell = MilkProduction::factory()->create([
        'category_id' => $category->id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $this->get(route('sells.show', $sell->id))
        ->assertSuccessful()
        ->assertSee('value="'.dateFormat($sell->date).'"', false)
        ->assertSee('value="'.$sell->category_id.'"', false)
        ->assertSee('value="'.$sell->quantity.'"', false)
        ->assertSee('value="'.$sell->sell_price.'"', false)
        ->assertSee('value="'.$sell->location_id.'"', false)
        ->assertViewHas(['sell', 'locations'], [$sell, $locationsAll]);

    $this->assertDatabaseHas('milk_productions', ['category_id' => $category_id])
        ->assertEquals($sell->category_id, $category_id);
});

test('milk sell update should change name', function () {
    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $category_id = $category->id;
    $locationsAll = Location::all();
    $sell = MilkProduction::factory()->create([
        'category_id' => $category->id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $category_id = $category->id;
    $quantity = 1234;
    $sell_price = 100;
    $sell_amount = 123500;
    $date = '2024-03-16 00:00:00';
    $location_id = $location->id;
    $comments = 'comments';

    // update
    $sell->category_id = $category_id;
    $sell->quantity = $quantity;
    $sell->sell_price = $sell_price;
    $sell->sell_amount = $sell_amount;
    $sell->location_id = $location_id;
    $sell->date = $date;
    $sell->comments = $comments;
    $sell->save();

    $this->get(route('sells.show', $sell->id))
        ->assertSuccessful()
        ->assertSee('value="'.$category_id.'"', false)
        ->assertSee('value="'.$quantity.'"', false)
        ->assertSee('value="'.$sell_price.'"', false)
        ->assertSee('value="'.$location_id.'"', false)
        ->assertSee('value="'.$comments.'"', false)
        ->assertViewHas(['sell', 'locations'], [$sell, $locationsAll]);

    $this->assertEquals($sell->category_id, $category_id);
});

test('milk sell update should fail with validation error', function () {
    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $sells = MilkProduction::factory()->create([
        'category_id' => $category->id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $this->put(route('sells.update', $sells->id), [
        'quantity' => '',
        'sell_price' => '',
        'location_id' => '',
    ])
        ->assertStatus(302)
        ->assertSessionHasErrors(['quantity', 'sell_price', 'location_id'])
    // or as same
        ->assertInvalid(['quantity', 'sell_price', 'location_id']);
});

test('milk sell delete should remove item successfully', function () {
    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $sell = MilkProduction::factory()->create([
        'category_id' => $category->id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    // delete
    $sell->delete();

    $this->assertNull(MilkProduction::find($sell->id));
    $this->assertDatabaseMissing('milk_productions', $sell->toArray());
    $this->assertDatabaseCount('milk_productions', 0);
});

test('milk sell delete should remove item and redirect to sell index', function () {

    $category = MilkProductionCategory::factory()->create([
        'name' => 'sell',
    ]);
    $location = Location::factory()->create([
        'name' => 'Port Mariane',
    ]);
    $sell = MilkProduction::factory()->create([
        'category_id' => $category->id,
        'quantity' => 1234,
        'sell_price' => 100,
        'sell_amount' => 123400,
        'location_id' => $location->id,
        'date' => '2024-03-15 00:00:00',
        'comments' => 'comments',
    ]);

    $this->delete(route('sells.destroy', $sell->id))
        ->assertStatus(302)
        ->assertRedirect(route('sells.index'));

    $this->assertNull(MilkProduction::find($sell->id));
    $this->assertDatabaseMissing('milk_productions', $sell->toArray());
    $this->assertDatabaseCount('milk_productions', 0);
});

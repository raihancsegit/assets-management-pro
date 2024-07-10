<?php

// tests/BreadingCategoryTest.php

use App\Models\Category;
use App\Models\Inventory;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('categories breading initial page should be empty', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->get(route('categories.breadings.index', $category->id))
        ->assertSuccessful()
        ->assertSee('No data');
});

test('categories breading store form should work as successful', function () {
    $name = 'Cow';
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $inventory = [
        'name' => $name,
        'category_id' => $category->id,
        'parent_id' => 1,
        'inventorie_type' => 1,
        'serial' => 1,
        'color' => 'black',
        'details' => 'details',
        'value_amount' => 22,
        'shade_no' => null,
    ];

    $this->post(route('categories.inventories.store', $category->id), $inventory)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('categories.breadings.index', $category->id));

    $this->assertDatabaseHas('inventories', $inventory);

    $latestInventory = Inventory::latest()->first();
    $this->assertEquals($latestInventory->name, $name);
});

test('categories breading store should fail with validation error', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->post(route('categories.inventories.store', $category->id), [
        'name' => '',
        'category_id' => null,
        'value_amount' => null,
        'color' => '',
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors(['name', 'category_id'])
        // or as same
        ->assertInvalid(['name', 'category_id']);
});

test('categories breadings list should have item', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $inventory = Inventory::factory()->create([
        'name' => 'Cow',
        'category_id' => $category->id,
        'inventorie_type' => 1,
        'parent_id' => 1,
        'serial' => 1,
        'color' => 'black',
        'details' => 'details',
        'value_amount' => 22,
        'shade_no' => 1,
    ]);

    $this->get(route('categories.breadings.index', $category->id))
        ->assertSuccessful()
        ->assertSee('Cow')
        ->assertViewHas('inventories', function ($collection) use ($inventory) {
            return $collection->contains($inventory);
        });
});

test('categories breadings-pagination: 1st created item should not show on index page', function () {
    $inventories = Inventory::factory(20)->create();
    $firstInventory = $inventories->first();
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->get(route('categories.breadings.index', $category->id))
        ->assertSuccessful()
        ->assertViewHas('inventories', function ($collection) use ($firstInventory) {
            return ! $collection->contains($firstInventory);
        });
});

test('categories breadings edit should show same all value', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $name = 'Cow';
    $inventory = Inventory::factory()->create([
        'name' => $name,
        'category_id' => $category->id,
        'inventorie_type' => 1,
        'parent_id' => 1,
        'serial' => 1,
        'color' => 'black',
        'value_amount' => 22,
        'shade_no' => 1,
    ]);

    $this->get(route('categories.inventories.edit', [$category->id, $inventory->id]))
        ->assertSuccessful()
        ->assertSee('value="'.$inventory->name.'"', false)
        ->assertSee('value="'.$inventory->category_id.'"', false)
        ->assertSee('value="'.$inventory->inventorie_type.'"', false)
        ->assertSee('value="'.$inventory->parent_id.'"', false)
        ->assertSee('value="'.$inventory->serial.'"', false)
        ->assertSee('value="'.$inventory->color.'"', false)
        ->assertSee('value="'.$inventory->value_amount.'"', false)
        ->assertSee('value="'.$inventory->shade_no.'"', false)
        ->assertSee('Update Inventory')
        ->assertViewHas('inventory', $inventory);

    $this->assertDatabaseHas('inventories', ['name' => $name])
        ->assertEquals($inventory->name, $name);
});

test('categories breadings delete should remove item successfully', function () {
    $inventory = Inventory::factory()->create();
    // delete
    $inventory->delete();

    $this->assertNull(Inventory::find($inventory->id));
    $this->assertDatabaseMissing('inventories', $inventory->toArray());
    $this->assertDatabaseCount('inventories', 0);
});

test('categories breadings delete should remove item and redirect to inventory index', function () {
    $inventory = Inventory::factory()->create();
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->delete(route('categories.inventories.destroy', [$category->id, $inventory->id]))
        ->assertStatus(302)
        ->assertRedirect(route('inventories.index', $category->id));

    $this->assertNull(Inventory::find($inventory->id));
    $this->assertDatabaseMissing('inventories', $inventory->toArray());
    $this->assertDatabaseCount('inventories', 0);
});

<?php

// tests/InventoryTest.php

use App\Models\Category;
use App\Models\Inventorie_type;
use App\Models\Inventory;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('inventories initial page should be empty', function () {
    $this->get(route('inventories.index'))
        ->assertSuccessful()
        ->assertSee('No data');
});

test('inventories store form should work as successful', function () {
    $name = 'Cow';
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $inventory = [
        'name' => $name,
        'category_id' => $category->id,
        'inventorie_type' => 1,
        'serial' => 1,
        'color' => 'black',
        'details' => 'details',
        'value_amount' => 22,
        'shade_no' => null,
    ];

    $this->post(route('inventories.store'), $inventory)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('inventories.index'));

    $this->assertDatabaseHas('inventories', $inventory);

    $latestInventory = Inventory::latest()->first();
    $this->assertEquals($latestInventory->name, $name);
});

test('inventories store should fail with validation error', function () {
    $this->post(route('inventories.store'), [
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

test('inventories list should have item', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $inventory = Inventory::factory()->create([
        'name' => 'Cow',
        'category_id' => $category->id,
        'inventorie_type' => 1,
        'serial' => 1,
        'color' => 'black',
        'details' => 'details',
        'value_amount' => 22,
        'shade_no' => 1,
    ]);

    $this->get(route('inventories.index'))
        ->assertSuccessful()
        ->assertSee('Cow')
        ->assertViewHas('inventories', function ($collection) use ($inventory) {
            return $collection->contains($inventory);
        });
});
test('inventories-pagination: 1st created item should not show on index page', function () {
    $inventories = Inventory::factory(20)->create();
    $firstInventory = $inventories->first();

    $this->get(route('inventories.index'))
        ->assertSuccessful()
        ->assertViewHas('inventories', function ($collection) use ($firstInventory) {
            return ! $collection->contains($firstInventory);
        });
});

test('inventories edit should show same all value', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $name = 'Cow';
    $inventory = Inventory::factory()->create([
        'name' => $name,
        'category_id' => $category->id,
        'inventorie_type' => 1,
        'serial' => 1,
        'color' => 'black',
        'value_amount' => 22,
        'shade_no' => 1,
    ]);

    $this->get(route('inventories.edit', $inventory->id))
        ->assertSuccessful()
        ->assertSee('value="'.$inventory->name.'"', false)
        ->assertSee('value="'.$inventory->category_id.'"', false)
        ->assertSee('value="'.$inventory->inventorie_type.'"', false)
        ->assertSee('value="'.$inventory->serial.'"', false)
        ->assertSee('value="'.$inventory->color.'"', false)
        ->assertSee('value="'.$inventory->value_amount.'"', false)
        ->assertSee('value="'.$inventory->shade_no.'"', false)
        ->assertSee('Update Inventory')
        ->assertViewHas('inventory', $inventory);

    $this->assertDatabaseHas('inventories', ['name' => $name])
        ->assertEquals($inventory->name, $name);
});

test('inventories update should change name', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $inventory_type = Inventorie_type::factory()->create([
        'name' => 'name',
    ]);
    $name = 'Apple Garden';
    $updatedName = 'Milk Production';
    $serial = 2;
    $color = 'red';
    $value_amount = 11;
    $shade_no = 2;

    $inventory = Inventory::create([
        'name' => $name,
        'category_id' => $category->id,
        'inventorie_type' => $inventory_type->id,
        'serial' => 1,
        'color' => 'black',
        'value_amount' => 22,
        'shade_no' => 1,
    ]);

    // $updated_category = Category::factory()->create([
    //     'name' => 'Cow',
    //     'parent_id' => null,
    // ]);
    // $updated_inventory_type = Inventorie_type::factory()->create([
    //     'name' => 'update name',
    // ]);

    // update
    $inventory->name = $updatedName;
    // $inventory->category_id = $updated_category;
    // $inventory->inventorie_type = $updated_inventory_type;
    $inventory->serial = $serial;
    $inventory->color = $color;
    $inventory->value_amount = $value_amount;
    $inventory->shade_no = $shade_no;
    $inventory->save();

    $this->get(route('inventories.edit', $inventory->id))
        ->assertSuccessful()
        ->assertSee('value="'.$updatedName.'"', false)
        // ->assertSee('value="'.$updated_category.'"', false)
        // ->assertSee('value="'.$updated_inventory_type.'"', false)
        ->assertSee('value="'.$serial.'"', false)
        ->assertSee('value="'.$color.'"', false)
        ->assertSee('value="'.$shade_no.'"', false)
        ->assertViewHas('inventory', $inventory);

    $this->assertEquals($inventory->name, $updatedName);
});

test('inventorie delete should remove item successfully', function () {
    $inventory = Inventory::factory()->create();

    // delete
    $inventory->delete();

    $this->assertNull(Inventory::find($inventory->id));
    $this->assertDatabaseMissing('inventories', $inventory->toArray());
    $this->assertDatabaseCount('inventories', 0);
});

test('inventory delete should remove item and redirect to inventory index', function () {
    $inventory = Inventory::factory()->create();

    $this->delete(route('inventories.destroy', $inventory->id))
        ->assertStatus(302)
        ->assertRedirect(route('inventories.index'));

    $this->assertNull(Inventory::find($inventory->id));
    $this->assertDatabaseMissing('inventories', $inventory->toArray());
    $this->assertDatabaseCount('inventories', 0);
});
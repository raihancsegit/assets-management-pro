<?php

use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('categories initial page should be empty', function () {
    $this->get(route('categories.index'))
        ->assertSuccessful()
        ->assertSee('No data');
});

test('categories store form should show', function () {
    $this->get(route('categories.create'))
        ->assertSuccessful()
        ->assertSee('placeholder="Agriculture"', false);
});

test('categories store form should work as successful', function () {
    $name = 'Orange';
    $category = [
        'name' => $name,
        'parent_id' => null,
    ];

    $this->post(route('categories.store'), $category)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', $category);

    $latestCategory = Category::latest()->first();
    $this->assertEquals($latestCategory->name, $name);
});

test('categories store should fail with validation error', function () {
    $this->post(route('categories.store'), [
        'name' => '',
        'parent_id' => null,
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors(['name'])
    // or as same
        ->assertInvalid(['name']);
});

test('categories list should have item', function () {
    Category::factory()->create([
        'name' => 'COW',
        'parent_id' => null,
    ]);

    $this->get(route('categories.index'))
        ->assertSuccessful()
        ->assertSee('COW');
});

test('categories-pagination: 1st created item should not show on index page', function () {
    $categories = Category::factory(20)->create();
    $firstCategory = $categories->first();

    $this->get(route('categories.index'))
        ->assertSuccessful()
        ->assertViewHas('parent_categories', function ($collection) use ($firstCategory) {
            return ! $collection->contains($firstCategory);
        });
});

test('category edit should show same category name', function () {
    $name = 'Mango Garden';
    $category = Category::factory()->create([
        'name' => $name,
        'parent_id' => null,
    ]);

    $this->get(route('categories.edit', $category->id))
        ->assertSuccessful()
        ->assertSee('value="'.$category->name.'"', false)
        ->assertSee('value="'.$category->parent_id.'"', false)
        ->assertSee('Update Category')
        ->assertViewHas('category', $category);

    $this->assertDatabaseHas('categories', ['name' => $name])
        ->assertEquals($category->name, $name);
});

test('category update should change name', function () {
    $name = 'Apple Garden';
    $updatedName = 'Milk Production';
    $updatedParent = null;
    $category = Category::create([
        'name' => $name,
        'parent_id' => null,
    ]);

    // update
    $category->name = $updatedName;
    $category->parent_id = $updatedParent;
    $category->save();

    $this->get(route('categories.edit', $category->id))
        ->assertSuccessful()
        ->assertSee('value="'.$updatedName.'"', false)
        ->assertSee('value="'.$updatedParent.'"', false)
        ->assertViewHas('category', $category);

    $this->assertEquals($category->name, $updatedName);
});

test('category update should fail with validation error', function () {
    $category = Category::factory()->create();

    $this->put(route('categories.update', $category->id), [
        'name' => '',
    ])
        ->assertStatus(302)
        ->assertSessionHasErrors(['name'])
    // or as same
        ->assertInvalid(['name']);
});

test('category delete should remove item successfully', function () {
    $category = Category::factory()->create();

    // delete
    $category->delete();

    $this->assertNull(Category::find($category->id));
    $this->assertDatabaseMissing('categories', $category->toArray());
    $this->assertDatabaseCount('categories', 0);
});

test('category delete should remove item and redirect to category index', function () {
    $category = Category::factory()->create();

    $this->delete(route('categories.destroy', $category->id))
        ->assertStatus(302)
        ->assertRedirect(route('categories.index'));

    $this->assertNull(Category::find($category->id));
    $this->assertDatabaseMissing('categories', $category->toArray());
    $this->assertDatabaseCount('categories', 0);
});

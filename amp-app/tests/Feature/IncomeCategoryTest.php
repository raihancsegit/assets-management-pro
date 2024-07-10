<?php

// tests/InventoryTest.php

use App\Models\Category;
use App\Models\Income;
use App\Models\Scheme;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('categories incomes initial page should be empty', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->get(route('categories.incomes.index', $category->id))
        ->assertSuccessful()
        ->assertSee('No data');
});

test('categories incomes store form should work as successful', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);
    $category_id = $category->id;

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'scheme_id' => $scheme->id,
    ]);

    $income = [
        'category_id' => $category_id,
        'type_id' => $type->id,
        'details' => 'details',
        'receipt_no' => 's1',
        'amount' => 11,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'date' => '2024-01-27 00:00:00',
        'notes' => 'notes',
        'status' => 1,
        'created_by' => Auth()->user()->id,
    ];

    $this->post(route('categories.incomes.store', $category->id), $income)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('categories.incomes.index', $category->id));

    $this->assertDatabaseHas('incomes', $income);

    $latestDeposit = Income::latest()->first();
    $this->assertEquals($latestDeposit->category_id, $category_id);
});

test('categories incomes store should fail with validation error', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->post(route('categories.incomes.store', $category->id), [
        'amount' => null,
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors(['amount'])
        // or as same
        ->assertInvalid(['amount']);
});

test('categories incomes list should have item', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);

    $category_id = $category->id;

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'scheme_id' => $scheme->id,
    ]);

    $income = Income::factory()->create([
        'category_id' => $category_id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 11,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'date' => '2024-01-27 00:00:00',
        'notes' => 'notes',
        'status' => 1,
    ]);

    $this->get(route('categories.incomes.index', $category->id))
        ->assertSuccessful()
        ->assertSee($category_id)
        ->assertViewHas('incomes', function ($collection) use ($income) {
            return $collection->contains($income);
        });
});

test('categories incomes-pagination: 1st created item should not show on index page', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category->id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category->id,
        'scheme_id' => $scheme->id,
    ]);

    $incomes = Income::factory(20)->create([
        'category_id' => $category->id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 11,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'date' => '2024-01-27 00:00:00',
        'notes' => 'notes',
        'status' => 1,
    ]);
    $firstDeposit = $incomes->first();

    $this->get(route('categories.incomes.index', $category->id))
        ->assertSuccessful()
        ->assertViewHas('incomes', function ($collection) use ($firstDeposit) {
            return ! $collection->contains($firstDeposit);
        });
});

test('categories incomes edit should show same all value', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);

    $category_id = $category->id;

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'scheme_id' => $scheme->id,
    ]);

    $income = Income::factory()->create([
        'category_id' => $category_id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 11,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'notes' => 'notes',
        'status' => 1,
        'created_by' => Auth()->user()->id,
        'updated_by' => Auth()->user()->id,
    ]);

    $this->get(route('categories.incomes.edit', [$category->id, $income->id]))
        ->assertSuccessful()
        ->assertSee('value="'.$income->category_id.'"', false)
        ->assertSee('value="'.$income->type_id.'"', false)
        ->assertSee('value="'.$income->receipt_no.'"', false)
        ->assertSee('value="'.$income->amount.'"', false)
        ->assertSee('value="'.$income->unit_id.'"', false)
        ->assertSee('value="'.$income->unit_value.'"', false)
        ->assertSee('value="'.$income->notes.'"', false)
        ->assertSee('value="'.$income->status.'"', false)
        ->assertSee('value="'.$income->created_by.'"', false)
        ->assertSee('value="'.$income->updated_by.'"', false)
        ->assertSee('Update')
        ->assertViewHas('income', $income);

    $this->assertDatabaseHas('incomes', ['category_id' => $category_id])
        ->assertEquals($income->category_id, $category_id);
});

test('categories incomes update should change name', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);

    $category_id = $category->id;

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'scheme_id' => $scheme->id,
    ]);

    $category_id = $category->id;
    $type_id = $type->id;
    $receipt_no = 's1';
    $amount = 11;
    $date = '2024-01-27';
    $unit_id = $unit->id;
    $notes = 'notes';
    $status = 1;
    $created_by = Auth()->user()->id;
    $updated_by = Auth()->user()->id;

    $income = Income::create([
        'category_id' => $category_id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 11,
        'date' => '2024-01-27 00:00:00',
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'notes' => 'notes',
        'status' => 1,
        'created_by' => Auth()->user()->id,
        'updated_by' => Auth()->user()->id,
    ]);

    // update
    $income->category_id = $category_id;
    $income->type_id = $type->id;
    $income->receipt_no = $receipt_no;
    $income->amount = $amount;
    $income->date = $date;
    $income->unit_id = $unit->id;
    $income->notes = $notes;
    $income->status = $status;
    $income->created_by = $created_by;
    $income->updated_by = $updated_by;
    $income->save();

    $this->get(route('categories.incomes.edit', [$category->id, $income->id]))
        ->assertSuccessful()
        ->assertSee('value="'.$category_id.'"', false)
        ->assertSee('value="'.$type_id.'"', false)
        ->assertSee('value="'.$receipt_no.'"', false)
        ->assertSee('value="'.$amount.'"', false)
        ->assertSee('value="'.$date.'"', false)
        ->assertSee('value="'.$unit_id.'"', false)
        ->assertSee('value="'.$notes.'"', false)
        ->assertSee('value="'.$status.'"', false)
        ->assertSee('value="'.$created_by.'"', false)
        ->assertSee('value="'.$updated_by.'"', false)
        ->assertViewHas('income', $income);

    $this->assertEquals($income->category_id, $category_id);
});

test('categories incomes delete should remove item successfully', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);

    $category_id = $category->id;

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'scheme_id' => $scheme->id,
    ]);

    $income = Income::factory()->create([
        'category_id' => $category->id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 11,
        'date' => '2024-01-27 00:00:00',
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'notes' => 'notes',
        'status' => 1,
        'created_by' => Auth()->user()->id,
        'updated_by' => Auth()->user()->id,
    ]);

    // delete
    $income->delete();

    $this->assertNull(Income::find($income->id));
    $this->assertDatabaseMissing('incomes', $income->toArray());
    $this->assertDatabaseCount('incomes', 0);
});

test('categories incomes delete should remove item and redirect to inventory index', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[2],
    ]);

    $category_id = $category->id;

    $type = Type::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'parent_id' => null,
        'scheme_id' => $scheme->id,
    ]);

    $unit = Unit::factory()->create([
        'name' => 'firm',
        'category_id' => $category_id,
        'scheme_id' => $scheme->id,
    ]);

    $income = Income::factory()->create([
        'category_id' => $category->id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 11,
        'date' => '2024-01-27 00:00:00',
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'notes' => 'notes',
        'status' => 1,
        'created_by' => Auth()->user()->id,
        'updated_by' => Auth()->user()->id,
    ]);
    $this->delete(route('categories.incomes.destroy', [$category->id, $income->id]))
        ->assertStatus(302)
        ->assertRedirect(route('incomes.index', $category->id));

    $this->assertNull(Income::find($income->id));
    $this->assertDatabaseMissing('incomes', $income->toArray());
    $this->assertDatabaseCount('incomes', 0);
});

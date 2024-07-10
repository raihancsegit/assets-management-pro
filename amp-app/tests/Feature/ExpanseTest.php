<?php

// tests/InventoryTest.php

use App\Models\Category;
use App\Models\Deposit;
use App\Models\Expanse;
use App\Models\Income;
use App\Models\Scheme;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('expanses initial page should be empty', function () {
    $this->get(route('expanses.index'))
        ->assertSuccessful()
        ->assertSee('No data');
});

test('categories expanses store form should work as successful if balance is sufficient', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    Deposit::factory()->create([
        'category_id' => $category_id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 5000,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'date' => '2024-01-27 00:00:00',
        'notes' => 'notes',
        'status' => 1,
    ]);

    Income::factory()->create([
        'category_id' => $category_id,
        'type_id' => $type->id,
        'receipt_no' => 's1',
        'amount' => 1000,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'date' => '2024-01-27 00:00:00',
        'notes' => 'notes',
        'status' => 1,
    ]);

    // total deposits, expenses, and incomes
    $totalDeposits = sumByStatus(Deposit::class, 1);
    $totalExpenses = sumByStatus(Expanse::class, 1);
    $totalIncomes = sumByStatus(Income::class, 1);

    // Calculate total balance
    $totalBalances = number_format($totalDeposits - ($totalExpenses - $totalIncomes), 2);
    // Define an expense amount
    $expenseAmount = 10; // Change this to your desired expense amount

    // Ensure balance is sufficient for the expense
    expect($totalBalances)->toBeGreaterThan(0);
    expect($totalBalances)->toBeGreaterThanOrEqual($expenseAmount);

    // Construct the expense data
    $expanse = [
        'category_id' => $category_id,
        'type_id' => $type->id,
        'details' => 'details',
        'receipt_no' => 's1',
        'amount' => $expenseAmount,
        'unit_id' => $unit->id,
        'unit_value' => 22,
        'date' => '2024-01-27 00:00:00',
        'notes' => 'notes',
        'status' => 1,
        'created_by' => Auth()->user()->id,
    ];

    $this->post(route('expanses.store'), $expanse)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('expanses.store'));

    $this->assertDatabaseHas('expanses', $expanse);

    $latestExpanse = Expanse::latest()->first();
    $this->assertEquals($latestExpanse->category_id, $category_id);
});

test('expanses store should fail with validation error', function () {
    $this->post(route('expanses.store'), [
        'amount' => null,
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors(['amount'])
        // or as same
        ->assertInvalid(['amount']);
});

test('expanses list should have item', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanse = Expanse::factory()->create([
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

    $this->get(route('expanses.index'))
        ->assertSuccessful()
        ->assertSee($category_id)
        ->assertViewHas('expanses', function ($collection) use ($expanse) {
            return $collection->contains($expanse);
        });
});

test('categories expanses-pagination: 1st created item should not show on index page', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanses = Expanse::factory(30)->create([
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
    $firstExpanse = $expanses->first();

    $this->get(route('expanses.index'))
        ->assertSuccessful()
        ->assertViewHas('expanses', function ($collection) use ($firstExpanse) {
            return ! $collection->contains($firstExpanse);
        });
});

test('expanses edit should show same all value', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanse = Expanse::factory()->create([
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

    $this->get(route('expanses.show', $expanse->id))
        ->assertSuccessful()
        ->assertSee('value="'.$expanse->category_id.'"', false)
        ->assertSee('value="'.$expanse->type_id.'"', false)
        ->assertSee('value="'.$expanse->receipt_no.'"', false)
        ->assertSee('value="'.$expanse->amount.'"', false)
        ->assertSee('value="'.$expanse->unit_id.'"', false)
        ->assertSee('value="'.$expanse->unit_value.'"', false)
        ->assertSee('value="'.$expanse->notes.'"', false)
        ->assertSee('value="'.$expanse->status.'"', false)
        ->assertSee('value="'.$expanse->created_by.'"', false)
        ->assertSee('value="'.$expanse->updated_by.'"', false)
        ->assertSee('Update Expanse')
        ->assertViewHas('expanse', $expanse);

    $this->assertDatabaseHas('expanses', ['category_id' => $category_id])
        ->assertEquals($expanse->category_id, $category_id);
});

test('expanses update should change name', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanse = Expanse::create([
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
    $expanse->category_id = $category_id;
    $expanse->type_id = $type->id;
    $expanse->receipt_no = $receipt_no;
    $expanse->amount = $amount;
    $expanse->date = $date;
    $expanse->unit_id = $unit->id;
    $expanse->notes = $notes;
    $expanse->status = $status;
    $expanse->created_by = $created_by;
    $expanse->updated_by = $updated_by;
    $expanse->save();

    $this->get(route('expanses.edit', $expanse->id))
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
        ->assertViewHas('expanse', $expanse);

    $this->assertEquals($expanse->category_id, $category_id);
});

test('expanses update should fail with validation error', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanses = Expanse::factory()->create([
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

    $this->put(route('expanses.update', $expanses->id), [
        'amount' => '',
    ])
        ->assertStatus(302)
        ->assertSessionHasErrors(['amount'])
    // or as same
        ->assertInvalid(['amount']);
});

test('expanses delete should remove item successfully', function () {
    //$expanse = Expanse::factory()->create();
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanse = Expanse::factory()->create([
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
    $expanse->delete();

    $this->assertNull(Expanse::find($expanse->id));
    $this->assertDatabaseMissing('expanses', $expanse->toArray());
    $this->assertDatabaseCount('expanses', 0);
});

test('expanses delete should remove item and redirect to expanse index', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => (getSchemeName())[1],
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

    $expanse = Expanse::factory()->create([
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

    $this->delete(route('expanses.destroy', $expanse->id))
        ->assertStatus(302)
        ->assertRedirect(route('expanses.index'));

    $this->assertNull(Expanse::find($expanse->id));
    $this->assertDatabaseMissing('expanses', $expanse->toArray());
    $this->assertDatabaseCount('expanses', 0);
});

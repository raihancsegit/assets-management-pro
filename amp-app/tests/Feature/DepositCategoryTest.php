<?php

// tests/InventoryTest.php

use App\Models\Category;
use App\Models\Deposit;
use App\Models\Scheme;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(User::factory()->create()->assignRole(Role::create(['name' => 'admin'])));
});

test('categories deposits initial page should be empty', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->get(route('categories.deposits.index', $category->id))
        ->assertSuccessful()
        ->assertSee('No data');
});

test('categories deposits store form should work as successful', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $scheme = Scheme::factory()->create([
        'name' => 'Income',
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

    $deposit = [
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

    $this->post(route('categories.deposits.store', $category->id), $deposit)
        ->assertStatus(302) // redirect
        ->assertRedirect(route('categories.deposits.index', $category->id));

    $this->assertDatabaseHas('deposits', $deposit);

    $latestDeposit = Deposit::latest()->first();
    $this->assertEquals($latestDeposit->category_id, $category_id);
});

test('categories deposits store should fail with validation error', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);
    $this->post(route('categories.deposits.store', $category->id), [
        'amount' => null,
    ])
        ->assertStatus(302) // redirect
        ->assertSessionHasErrors(['amount'])
        // or as same
        ->assertInvalid(['amount']);
});

test('categories deposits list should have item', function () {

    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => 'Income',
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

    $deposit = Deposit::factory()->create([
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

    $this->get(route('categories.deposits.index', $category->id))
        ->assertSuccessful()
        ->assertSee($category_id)
        ->assertViewHas('deposits', function ($collection) use ($deposit) {
            return $collection->contains($deposit);
        });
});

test('categories deposits-pagination: 1st created item should not show on index page', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => 'Income',
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

    $deposits = Deposit::factory(20)->create([
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
    $firstDeposit = $deposits->first();

    $this->get(route('categories.deposits.index', $category->id))
        ->assertSuccessful()
        ->assertViewHas('deposits', function ($collection) use ($firstDeposit) {
            return ! $collection->contains($firstDeposit);
        });
});

// test('categories deposits edit should show same all value', function () {
//     $category = Category::factory()->create([
//         'name' => 'firm',
//         'parent_id' => null,
//     ]);

//     $scheme = Scheme::factory()->create([
//         'name' => (getSchemeName())[0],
//     ]);

//     $category_id = $category->id;

//     $type = Type::factory()->create([
//         'name' => 'firm',
//         'category_id' => $category_id,
//         'parent_id' => null,
//         'scheme_id' => $scheme->id,
//     ]);

//     $unit = Unit::factory()->create([
//         'name' => 'firm',
//         'category_id' => $category_id,
//         'scheme_id' => $scheme->id,
//     ]);

//     $deposit = Deposit::factory()->create([
//         'category_id' => $category_id,
//         'type_id' => $type->id,
//         'receipt_no' => 's1',
//         'amount' => 11,
//         'unit_id' => $unit->id,
//         'unit_value' => 22,
//         'notes' => 'notes',
//         'status' => 1,
//         'created_by' => Auth()->user()->id,
//         'updated_by' => Auth()->user()->id,
//     ]);

//     $this->get(route('categories.deposits.edit', [$category->id, $deposit->id]))
//         ->assertSuccessful()
//         ->assertSee('value="'.$deposit->category_id.'"', false)
//         ->assertSee('value="'.$deposit->type_id.'"', false)
//         ->assertSee('value="'.$deposit->receipt_no.'"', false)
//         ->assertSee('value="'.$deposit->amount.'"', false)
//         ->assertSee('value="'.$deposit->unit_id.'"', false)
//         ->assertSee('value="'.$deposit->unit_value.'"', false)
//         ->assertSee('value="'.$deposit->notes.'"', false)
//         ->assertSee('value="'.$deposit->status.'"', false)
//         ->assertSee('value="'.$deposit->created_by.'"', false)
//         ->assertSee('value="'.$deposit->updated_by.'"', false)
//         ->assertSee('Update Deposit')
//         ->assertViewHas('deposit', $deposit);

//     $this->assertDatabaseHas('deposits', ['category_id' => $category_id])
//         ->assertEquals($deposit->category_id, $category_id);
// });

// test('categories deposits update should change name', function () {
//     $category = Category::factory()->create([
//         'name' => 'firm',
//         'parent_id' => null,
//     ]);

//     $scheme = Scheme::factory()->create([
//         'name' => (getSchemeName())[0],
//     ]);

//     $category_id = $category->id;

//     $type = Type::factory()->create([
//         'name' => 'firm',
//         'category_id' => $category_id,
//         'parent_id' => null,
//         'scheme_id' => $scheme->id,
//     ]);

//     $unit = Unit::factory()->create([
//         'name' => 'firm',
//         'category_id' => $category_id,
//         'scheme_id' => $scheme->id,
//     ]);

//     $category_id = $category->id;
//     $type_id = $type->id;
//     $receipt_no = 's1';
//     $amount = 11;
//     $date = '2024-01-27';
//     $unit_id = $unit->id;
//     $notes = 'notes';
//     $status = 1;
//     $created_by = Auth()->user()->id;
//     $updated_by = Auth()->user()->id;

//     $deposit = Deposit::create([
//         'category_id' => $category_id,
//         'type_id' => $type->id,
//         'receipt_no' => 's1',
//         'amount' => 11,
//         'date' => '2024-01-27 00:00:00',
//         'unit_id' => $unit->id,
//         'unit_value' => 22,
//         'notes' => 'notes',
//         'status' => 1,
//         'created_by' => Auth()->user()->id,
//         'updated_by' => Auth()->user()->id,
//     ]);

//     // update
//     $deposit->category_id = $category_id;
//     $deposit->type_id = $type->id;
//     $deposit->receipt_no = $receipt_no;
//     $deposit->amount = $amount;
//     $deposit->date = $date;
//     $deposit->unit_id = $unit->id;
//     $deposit->notes = $notes;
//     $deposit->status = $status;
//     $deposit->created_by = $created_by;
//     $deposit->updated_by = $updated_by;
//     $deposit->save();

//     $this->get(route('categories.deposits.edit', [$category->id, $deposit->id]))
//         ->assertSuccessful()
//         ->assertSee('value="'.$category_id.'"', false)
//         ->assertSee('value="'.$type_id.'"', false)
//         ->assertSee('value="'.$receipt_no.'"', false)
//         ->assertSee('value="'.$amount.'"', false)
//         ->assertSee('value="'.$date.'"', false)
//         ->assertSee('value="'.$unit_id.'"', false)
//         ->assertSee('value="'.$notes.'"', false)
//         ->assertSee('value="'.$status.'"', false)
//         ->assertSee('value="'.$created_by.'"', false)
//         ->assertSee('value="'.$updated_by.'"', false)
//         ->assertViewHas('deposit', $deposit);

//     $this->assertEquals($deposit->category_id, $category_id);
// });

test('categories deposits delete should remove item successfully', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => 'Income',
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

    $deposit = Deposit::factory()->create([
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
    $deposit->delete();

    $this->assertNull(Deposit::find($deposit->id));
    $this->assertDatabaseMissing('deposits', $deposit->toArray());
    $this->assertDatabaseCount('deposits', 0);
});

test('categories deposits delete should remove item and redirect to inventory index', function () {
    $category = Category::factory()->create([
        'name' => 'firm',
        'parent_id' => null,
    ]);

    $scheme = Scheme::factory()->create([
        'name' => 'Income',
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

    $deposit = Deposit::factory()->create([
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
    $this->delete(route('categories.deposits.destroy', [$category->id, $deposit->id]))
        ->assertStatus(302)
        ->assertRedirect(route('deposits.index', $category->id));

    $this->assertNull(Deposit::find($deposit->id));
    $this->assertDatabaseMissing('deposits', $deposit->toArray());
    $this->assertDatabaseCount('deposits', 0);
});
